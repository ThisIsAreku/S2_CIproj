<?php
require 'bootstrap.php';


function echoSuccess($data)
{
	echo json_encode(array('success' => true, 'data' => $data, 'exec' => returnRunTime()));
}
function echoError($data, $die = false)
{
	echo json_encode(array('success' => false, 'data' => $data, 'exec' => returnRunTime()));
	if($die)
		die();
}
function compileCartDatas()
{
	return array(
		'total' => number_format ($_SESSION['cart_sum'], 2, ',', ' '),
		'items' => $_SESSION['cart_items'],
		'num' => count($_SESSION['cart_items'])
		);
}
function clearCart()
{
	$_SESSION['cart_items'] = array();
	$_SESSION['cart_sum'] = 0;	
}


if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
{
	header('Content-type: text/html; charset=utf-8', true);
	echo "Javascript n'est pas activé sur votre navigateur, ce site ne peut pas fonctionner correctement<br />";
	if(isset($_GET['b'])){
		echo '<a href="', $_GET['b'], '">retour</a>';
	}else{
		echo '<a href="', URL_BASE, '">accueil</a>';
	}
	die();

}
header('Content-type: application/json; charset=utf-8', true);


switch($_GET['r'])
{
	case 'addtocart':
		$itm = $Dataman->getByHex($_POST['id']);
		if($itm !== null)
		{
			if(!isset($_SESSION['cart_items'][$itm->getHex()]))
			{
				$_SESSION['cart_items'][$itm->getHex()] = $itm;
				$_SESSION['cart_sum'] += $itm->getPrice();
				echoSuccess(array('cart' => compileCartDatas()));
			}else{
				echoError(array('msg' => "L'article est déja dans votre panier !", 'cart' => compileCartDatas()));
			}
		}else{
			echoError(array('msg' => "L'article n'existe pas !", 'cart' => compileCartDatas()));
		}
	break;
	case 'rmfromcart':
		if(isset($_POST['id']))
		{
			$itmHex = $_POST['id'];
			if(isset($_SESSION['cart_items'][$itmHex]))
			{
				$price = $_SESSION['cart_items'][$itmHex]->getPrice();
				unset($_SESSION['cart_items'][$itmHex]);
				if(count($_SESSION['cart_items']) > 0){
					//$_SESSION['cart_items'] = array_values($_SESSION['cart_items']);
					$_SESSION['cart_sum'] -= $price;
				}else{
					clearCart();
				}
				echoSuccess(array('cart' => compileCartDatas()));
			}else{
				echoError(array('msg' => "L'article n'est pas dans votre panier !", 'cart' => compileCartDatas()));
			}
		}else{
			echoError(array('msg' => "L'article n'existe pas !", 'cart' => compileCartDatas()));
		}
	break;
	case 'clearcart':
		clearCart();
		echoSuccess(array('cart' => compileCartDatas()));
	break;
	case 'getcartinfo':
		echoSuccess(array('cart' => compileCartDatas()));
	break;
	case 'validatecommand':
		if(count($_SESSION['cart_items']) > 0)
		{
			if($Dataman->doCommand($_SESSION['cart_items'], $_SESSION['cart_sum']))
			{
				clearCart();
				echoSuccess(array('msg' => "Merci pour votre commande ! Pourquoi ne pas <a href=\"".URL_BASE."/\">passer d'autres commandes ?</a>", 'cart' => compileCartDatas()));
			}else{
				echoError(array('msg' => "Vous n'êtes pas connecté !"));
			}
		}else{
			echoError(array('msg' => "Votre panier est vide !"));
		}
	break;
	case 'rmfromdb':
		if(!$Dataman->isLoggedIn())
			echoError(array('msg' => "Vous n'êtes pas connecté"), true);

		if(!$Dataman->getCurrentUser()->isAdmin())
			echoError(array('msg' => "Vous n'êtes pas administrateur"), true);

		if(isset($_POST['id']))
		{
			$itmHex = $_POST['id'];
			if($Dataman->deleteItem($itmHex))
			{
				echoSuccess('');
			}else{
				echoError(array('msg' => "L'article n'existe pas !"));
			}
		}else{
			echoError(array('msg' => "Erreur de requête"));
		}
	break;
	case 'switchshowcase':
		$Dataman->load('products');
		$itm = $Dataman->getByHex($_POST['id']);
		if($itm !== null)
		{
			$newstate = !$itm->isShowcase();
			$itm->setShowcase($newstate);
			$Dataman->save('products');
			echoSuccess(array('showcase' => $newstate));
		}else{
			echoError(array('msg' => "L'article n'existe pas !"));
		}
	break;
	default:
		echoError(array('msg' => 'unknown command'));
}