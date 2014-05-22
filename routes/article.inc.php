<?php 
$_GET['id'] = !empty($_GET['id']) ? $_GET['id'] : null;
$itm = $Dataman->getByHex($_GET['id']);
function pageTitle($append)
{
	global $itm;
	if($itm == null) {
		return 'Erreur | '.$append;
	}else{
		return $itm->getName().' | '.$append;
	}
}
function pageContent()
{
	global $Dataman, $itm;
?>
<div id="content" class="item-display">
			<?php if($itm != null): ?>
				<h2><?php echo $itm->getName(); ?></h2>
				<div class="color-band" style="background-color: #<?php echo $itm->getHex(); ?>"><div class="overlay"></div></div>
				<div class="item-price">
					<div class="price-tag"><i class="icon-tag"></i> <?php echo $itm->getPriceFormatted(); ?>&euro;</div>
					<?php if(isset($_SESSION['cart_items'][$itm->getHex()])): ?>
					<button type="button" class="btn btn-success"><strong>Ajouté !</strong></button>
					<?php else: ?>
					<button type="button" class="btn add-to-cart" value="<?php echo $itm->getHex(); ?>"><i class="icon-plus-sign icon-large"></i> Ajouter</button>
					<?php endif; ?>
				</div>
				<div class="item-desc">
					<?= $itm->getTextFormatted(); ?>
				</div>
			<?php else: ?>
				<h3>L'article n'existe pas</h3>
				<p>Oups.. cet article n'existe pas.. Il vaudrait mieux <a href="<?php echo URL_BASE; ?>/articles">retourner à la liste des artices !</a></p>
			<?php endif; ?>
			</div>
			<br class="clear" />
<?php } ?>