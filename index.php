<?php
require 'bootstrap.php';

include $route;

if($accept_xhtml && ENABLE_XHTML){
	header('Content-type: application/xhtml+xml; charset=utf-8');
	echo '<?xml version="1.0" encoding="UTF-8" ?>';
}else{
	header('Content-type: text/html; charset=utf-8');
}
?>
<!DOCTYPE html>
<html class="nojs" xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
<head>
	<meta charset="utf-8" />
	
	<title><?php echo pageTitle('Kolorshop'); ?></title>

	<link rel="shortcut icon" type="image/png" href="<?php echo URL_BASE; ?>/img/favicon1.png" />

	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,400italic|Playfair+Display:900' rel='stylesheet' type='text/css' />

	<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE; ?>/style/<?php echo (isset($_SESSION['loggedin']) ? $Dataman->getCurrentUser()->getStyle() : 'main'); ?>.css" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE; ?>/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE; ?>/css/font-awesome-ie7.min.css" />

	<script type="text/javascript">
		var url_base = '<?php echo URL_BASE; ?>';
	</script>
	<script type="text/javascript" src="<?php echo URL_BASE; ?>/js/functions.js"></script>
	<script type="text/javascript" src="<?php echo URL_BASE; ?>/js/scripts.js"></script>
</head>
<body>
	<div class="clearfooter_wrap">
		<div id="wrapper">
			<div id="reqjs">
				<div class="big">Votre navigateur est obsolète, ou bien javascript n'est pas activé</div>
				Ce site requiert javascript pour fonctionner. Mettez a jour votre navigateur pour une navigation optimale
			</div>
			<div id="cart">
				<?php if(!isset($_SESSION['loggedin'])): ?>
					<a href="<?php echo URL_BASE; ?>/user?login">Se connecter</a>
				<?php else: ?>
					Bienvenue <?php echo $Dataman->getCurrentUser()->getUsername(); ?> ! <a href="<?php echo URL_BASE; ?>/user?get_out_of_here&amp;b=<?php echo $_SERVER['REQUEST_URI']; ?>">Se déconnecter</a>
				<?php endif; ?>
				<a class="cart_item" href="<?php echo URL_BASE; ?>/cart"><i class="icon-shopping-cart"></i> Total: <span id="cart-wrap"><span id="cart-sum"><?php echo number_format ($_SESSION['cart_sum'], 2, ',', ' '); ?></span>&#8364;</span></a>
			</div>
			<div id="header">
				<h1 class="sitename"><span class="logo">KS</span>Kolor<span class="bold">Shop.</span></h1>
				<!-- c'est pas au point...
				<div id="search-field">
					<form method="get" action="search">
						<input type="text" name="q" placeholder="Rechercher" value="<?php if(!empty($_GET['q'])) echo $_GET['q']; ?>" />
						<button type="submit">r</button>
					</form>
				</div>-->
				<ul class="header-menu">
					<li><a href="<?= URL_BASE; ?>/">Accueil</a></li>
					<li><a href="<?= URL_BASE; ?>/articles">Articles</a></li>
					<li><a href="<?= URL_BASE; ?>/search">Rechercher</a></li>
					<li><a href="<?= URL_BASE; ?>/user">Mon compte</a></li>
					<li><a href="<?= URL_BASE; ?>/about">À propos</a></li>
				</ul>
			</div>
			<div id="content-wrapper">
				<!-- content start here -->
				<!-- route: <?= $route; ?> -->
				<?= pageContent(); ?>
				<!-- content end here -->
				<br class="clear" />
			</div>

		</div>
		<div class="clearfooter"></div>
	</div>
	<div id="footer">
		<div class="footer-wrapper">
			Page executé en <?= returnRunTime(); ?> seconde
		</div>
	</div>
</body>
</html>