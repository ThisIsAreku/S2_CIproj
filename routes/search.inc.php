<?php
function pageTitle($append)
{
	return 'Rechercher | '.$append;
}
function pageContent()
{
	global $Dataman;
	$r = array();
	$searched = false;
	if(isset($_GET['q']))
	{

		/*if($_GET['type'] == 'color')
		{
			$rgb = hexToRgb($_GET['c']);
			$r = $Dataman->searchItemsByColor($rgb['r'], $rgb['g'], $rgb['b'], 75);
			$searched = true;
		}
		elseif($_GET['type'] == 'name')
		{*/
			if(isset($_GET['global']))
			{
			$r = $Dataman->searchItemsByNameEx($_GET['q']);
			}else{
			$r = $Dataman->searchItemsByName($_GET['q']);
			}
			$searched = true;
		//}
	}

	$c = count($r);
?>
<div id="content">
	<h3>Rechercher un article</h3>
	<form id="search-form" method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<div class="search-by-name">
			<div class="control-group">
				<label class="control-label">Nom</label>
				<div class="controls"><input type="text" name="q" placeholder="Rechercher" value="<?php if(!empty($_GET['q'])) echo $_GET['q']; ?>" /></div>
			</div>
			<div class="control-group">
				<div class="controls"><input type="checkbox" id="s_global" name="global" value="yes" <?php if(isset($_GET['global'])) echo 'checked="checked"'; ?> /><label for="s_global"> Rechercher dans la description</label></div>
			</div>
		</div>
		<!--<div class="search-by-date">
			<div class="control-group">
				<label class="control-label">Date d'ajout</label>
				<div class="controls"><input type="date" name="d" placeholder="Date" value="<?php if(!empty($_GET['d'])) echo $_GET['d']; ?>" /></div>
			</div>
		</div>-->
		<br />
		<div class="control-group">
			<div class="controls"><button type="submit" class="btn"><i class="icon-search icon-large"></i> Rechercher</button></div>
		</div>
	</form>
	<br class="clear" />
<?php if($searched): ?>
	<p><strong><?php echo $c; ?></strong> élément<?php if($c>1) echo 's'; ?> correspond<?php if($c>1) echo 'ent'; ?> à votre recherche</p>
	<div class="items-grid">
<?php foreach($r as $itm): ?>
	<div class="item">
		<div class="color-block mask<?php echo rand(1,3); ?>" style="background-color: #<?php echo $itm->getHex(); ?>;">
			<a href="article/<?php echo $itm->getHex(); ?>" class="over"><i class="icon-zoom-in icon-2x"></i></a>
		</div>
		<div class="color-desc">
			<h4><?php echo $itm->getName(); ?></h4>
			<div class="color-code"><?php echo $itm->getPriceFormatted(); ?>€</div>
			<div class="action-buttons">		
				<?php if(isset($_SESSION['cart_items'][$itm->getHex()])): ?>
				<button type="button" class="btn btn-success"><strong>Ajouté !</strong></button>
				<?php else: ?>
				<button type="button" class="btn add-to-cart" value="<?php echo $itm->getHex(); ?>"><i class="icon-plus-sign icon-large"></i> Ajouter</button>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endforeach; ?>
	<br class="clear" />
	</div>
<?php endif; ?>
</div>
<?php
}
?>
