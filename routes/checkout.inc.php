<?php
function pageTitle($append)
{
	return 'Valider ma commande | '.$append;
}
function pageContent()
{
	global $Dataman;
?>
	<div id="content">
		<h2>Valider ma commande</h2>
<?php if(count($_SESSION['cart_items']) == 0): ?>
		<p>Votre panier est vide !</p>
<?php else: ?>
	<?php if(!isset($_SESSION['loggedin'])): ?>
		<p>Pour passez commande, vous devez d'abord vous connecter</p>
		<p><a href="<?php echo URL_BASE; ?>/user?login&b=<?php echo URL_BASE; ?>/checkout" class="btn"><i class="icon-user icon-large"></i> Se connecter</a></p>
	<?php else: ?>
		<div>
			<p>En ce moment, tout est offert ! cliquez juste sur le bouton ci-dessous pour valider votre commande</p>
			<p><button type="button" id="validate-command" class="btn"><i class="icon-check icon-large"></i> Je valide ma commande</button></p>
		</div>
	<?php endif; ?>
<?php endif; ?>
	</div>
<?php
}
?>