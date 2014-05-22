<?php
function pageTitle($append)
{
	return 'Articles | '.$append;
}
function pageContent()
{
	global $Dataman;
	$page = 1;
	$count = 15;
	$countvar = isset($_GET['count']) ? $_GET['count'] : null;
	if(isset($_GET['page']) && is_numeric($_GET['page']))
		$page = intval($_GET['page']);
	if(isset($countvar) && is_numeric($countvar))
		$count = intval($countvar);
	if(isset($countvar) && $countvar == 'all')
		$count = $Dataman->getNumItems();

	$shift = ($page-1) * $count;
	$maxpage = ceil($Dataman->getNumItems() / $count);
?>
<div id="content">
				<h2>Articles</h2>
				<form method="get" action="<?= URL_BASE; ?>/articles">
					<div class="control-group">
						<label class="control-label">Nombre d'éléments par page</label>
						<div class="controls">
							<select name="count">
								<option value="15" <?php if($count == 15) echo 'selected="selected"'; ?>>15</option>
								<option value="30" <?php if($count == 30) echo 'selected="selected"'; ?>>30</option>
								<option value="60" <?php if($count == 60) echo 'selected="selected"'; ?>>60</option>
								<option value="120" <?php if($count == 120) echo 'selected="selected"'; ?>>120</option>
								<option value="all" <?php if($countvar == 'all') echo 'selected="selected"'; ?>>Tous</option>
							</select>
							<button type="submit" class="btn">Afficher</button>
						</div>
					</div>
				</form>
				<br class="clear" />
				<p>Actuellement <?= $Dataman->getNumItems(); ?> articles dans la boutique !</p>
				<div class="items-grid">
				<?php for($i=0; $i<$count && $i<$Dataman->getNumItems()-$shift; $i++): $itm = $Dataman->getItem($i+$shift); ?>
					<div class="item">
						<div class="color-block mask<?php echo rand(1,3); ?>" style="background-color: #<?php echo $itm->getHex(); ?>;">
							<a href="<?= URL_BASE; ?>/article/<?php echo $itm->getHex(); ?>" class="over"><i class="icon-zoom-in icon-2x"></i></a>
						</div>
						<div class="color-desc">
							<h4><?php echo $itm->getName(); ?></h4>
							<div class="color-code"><?php echo $itm->getPriceFormatted(); ?>&euro;</div>
							<div class="action-buttons">		
								<?php if(isset($_SESSION['cart_items'][$itm->getHex()])): ?>
								<button type="button" class="btn btn-success"><strong>Ajouté !</strong></button>
								<?php else: ?>
								<button type="button" class="btn add-to-cart" value="<?php echo $itm->getHex(); ?>"><i class="icon-plus-sign icon-large"></i> Ajouter</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endfor; ?>
				<br class="clear" />
				</div>
				<div class="links">
					<?php if($page > 1): ?><a href="<?= URL_BASE; ?>/articles?page=<?php echo $page-1; if($count != 15) echo '&amp;count=',$count; ?>" class="prev-page"><i class="icon-angle-left"></i> Page précédente</a><?php endif; ?>
					<?php if($page < $maxpage): ?><a href="<?= URL_BASE; ?>/articles?page=<?php echo $page+1; if($count != 15) echo '&amp;count=',$count; ?>" class="next-page">Page suivante <i class="icon-angle-right"></i></a><?php endif; ?>
					
					<div>Page <?= $page ?> sur <?= $maxpage ?></div>
				</div>
				<br class="clear" />
			</div>
<?php
}
?>