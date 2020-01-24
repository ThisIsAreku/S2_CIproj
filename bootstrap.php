<?php
defined('TIME_START') or define('TIME_START', microtime(true));
require 'config.inc.php';

require 'cron.php';

require 'php/item.class.php';
require 'php/user.class.php';
require 'php/command.class.php';

require 'php/functions.inc.php';
require 'php/dataman.module.php';

session_start();
//debug
if(isset($_GET['clearsess']))
	$_SESSION = array();
if(isset($_GET['printsess']))
{
	var_dump($_SESSION);
	die();
}

if(!isset($_SESSION['cart_items']))
	$_SESSION['cart_items'] = array();
if(!isset($_SESSION['cart_sum']))
	$_SESSION['cart_sum'] = 0;

header('X-debug-self: '.$_SERVER["PHP_SELF"]);
if(basename($_SERVER["PHP_SELF"]) == 'index.php')
{
	$route_base = 'routes/';
	$allowed_routes = array(
		'index' => 'index.inc.php',
		'article' => 'article.inc.php',
		'articles' => 'articles.inc.php',
		'cart' => 'cart.inc.php',
		'checkout' => 'checkout.inc.php',
		'user' => 'user.inc.php',
		'about' => 'about.inc.php',
		'search' => 'search.inc.php',
		'edit-article' => 'edit-article.inc.php'
		);
	$error_routes = array(
		'404' => 'errors/404.inc.php',
		'500' => 'errors/500.inc.php'
		);

	$_GET['r'] = !empty($_GET['r']) ? $_GET['r'] : 'index';
	$route = $route_base.$allowed_routes['index'];
	header('X-Redirect-Arg: '.$_GET['r']);
	if(isset($allowed_routes[$_GET['r']])){
		$route = $route_base.$allowed_routes[$_GET['r']];
		if(!file_exists($route))
		{
			header('X-Redirect-Info1: phpNotFound');
			sendErrorHeader(500);
		}
	}else{
		header('X-Redirect-Info1: phpNotFound');
		sendErrorHeader(404);
	}

	if(isset($_GET['e']) && isset($error_routes[$_GET['e']])){
		header('X-Redirect-Info2: urlError');
		$route = $route_base.$error_routes[$_GET['e']];
		sendErrorHeader($_GET['e']);
	}
	/*var_dump($_GET);
	var_dump($_SERVER);
	die($route);*/
}

$Dataman = new DataManager(realpath(dirname(__FILE__)).'/data/');


//fix pour le validator w3c
header('X-UA-Compatible: IE=edge,chrome=1', true);
$accept_xhtml = preg_match("/application\/xhtml\+xml/i",$_SERVER['HTTP_ACCEPT']);

