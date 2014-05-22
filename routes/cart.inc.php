<?php
function pageTitle($append)
{
	return 'Mon panier | '.$append;
}
function pageContent()
{
	global $Dataman;
?>
	<div id="content">
		<h3>Mon panier</h3>
		<div class="items-list" id="cart-items">
	<?php if(count($_SESSION['cart_items']) > 0): ?>
		<table class="table">
			<thead>
				<tr><th></th><th>Nom</th><th>Prix</th><th></th></tr>
			</thead>
			<tbody>
		<?php foreach($_SESSION['cart_items'] as $hex => $itm): ?>
				<tr>
					<td class="cube" style="background-color: #<?php echo $itm->getHex(); ?>;"></td>
					<td class="text"><?php echo $itm->getName(); ?></td>
					<td class="price"><?php echo $itm->getPriceFormatted(); ?>€</td>
					<td class="last"><button type="button" class="btn rm-from-cart" value="<?php echo $itm->getHex(); ?>"><i class="icon-minus-sign icon-large"></i> Enlever</button></td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Votre panier est vide !</p>
	<?php endif; ?>
		</div>
		<br />
		<div class="action-buttons">
		<?php if(count($_SESSION['cart_items']) > 0): ?>
			<button type="button" class="btn btn-alert clear-cart"><i class="icon-remove-sign icon-large"></i> Vider le panier</button>
			<a href="<?php echo URL_BASE; ?>/checkout" class="btn" style="float: right"><i class="icon-money icon-large"></i> Passer à la caisse</a>
		<?php endif; ?>
		</div>
	</div>
<?php
}
?>