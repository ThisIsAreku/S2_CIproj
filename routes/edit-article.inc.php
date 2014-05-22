<?php
$result_msg = '';
if(isset($_POST['action']))
{
	$itm = $Dataman->getByHex($_POST['hex']);
	if($itm != null)
	{
		$edited = false;
		if(!empty ($_POST['name']))
			if($itm->setName(htmlentities($_POST['name'])))
				$edited = true;

		if(!empty ($_POST['text']))
			if($itm->setText(htmlentities($_POST['text'])))
				$edited = true;

		if(is_numeric ($_POST['price']))
			if($itm->setPrice($_POST['price']))
				$edited = true;

		if($edited){
			$result_msg = 'Le produit a été modifié ! <a href="'.URL_BASE.'/user#edt-prod">modifier un autre produit</a>';
			$Dataman->save();
		}else{
			$result_msg = 'Aucune modification';
		}
	}else{
		if(!empty ($_POST['hex']) && !empty ($_POST['name']) && is_numeric ($_POST['price']) && !empty ($_POST['text']))
		{
			$Dataman->addItem(new Item($_POST));
			$result_msg = 'Le produit a été ajouté ! <a href="'.URL_BASE.'/edit-article">ajouter un autre produit</a>';
		}
	}
}


function pageTitle($append)
{
	return 'Gestion des produits | '.$append;
}
function pageContent()
{
	global $Dataman, $result_msg;

	$itm = null;
	if(isset($_GET['id']))
	{
		$itm = $Dataman->getByHex($_GET['id']);
	}
?>
<div id="content">
	<h2><?php if($itm == null){ echo 'Ajouter'; }else{ echo 'Modifier'; }?> un produit</h2>
	<?php if(!empty($result_msg)): ?><p><?php echo $result_msg; ?></p><?php endif; ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<input type="hidden" name="action" value="<?php if($itm == null){ echo 'add'; }else{ echo 'edit'; }?>" />


		<div class="control-group">
			<label class="control-label">Nom</label>
			<div class="controls"><input type="text" name="name" placeholder="Nom" value="<?php echo ($itm != null ? $itm->getName() : ''); ?>" /></div>
		</div>

		<div class="color-selector">
			<input class="color-hidden" type="hidden" name="hex" value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>" />

			<?php if(!isset($_GET['id'])): ?>
			<div id="add-prod-inline">
				<div class="control-group">
					<label class="control-label">Rouge</label>
					<div class="controls"><input type="number" class="r" maxlength="3" min="0" max="255" step="1" value="0" placeholder="Composante rouge" /></div>
				</div>
				<div class="control-group">
					<label class="control-label">Vert</label>
					<div class="controls"><input type="number" class="v" maxlength="3" min="0" max="255" step="1" value="0" placeholder="Composante verte" /></div>
				</div>
				<div class="control-group">
					<label class="control-label">Bleu</label>
					<div class="controls"><input type="number" class="b" maxlength="3" min="0" max="255" step="1" value="0" placeholder="Composante bleu" /></div>
				</div>
			</div>
			<?php endif; ?>
			<div class="control-group">
				<label class="control-label">Code</label>
				<div class="controls">
					<input class="color-hex" type="text" maxlength="6" readonly="readonly" placeholder="Code couleur" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '000000'; ?>" />
					<div class="color-preview" style="background-color: #<?php echo isset($_GET['id']) ? $_GET['id'] : '000000'; ?>">&nbsp;</div>
				</div>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Description</label>
			<div class="controls"><textarea name="text" placeholder="Description" warp="soft" rows="3"><?php echo ($itm != null ? $itm->getText() : ''); ?></textarea></div>
		</div>
		<div class="control-group">
			<label class="control-label">Prix</label>
			<div class="controls"><input type="number" min="0" step="any" name="price" placeholder="Prix" value="<?php echo ($itm != null ? $itm->getPrice() : ''); ?>" /></div>
		</div>
		<br />
		<div class="control-group">
			<div class="controls"><button type="submit" class="btn"><i class="icon-<?php if($itm == null){ echo 'plus-sign'; }else{ echo 'edit'; }?> icon-large"></i> <?php if($itm == null){ echo 'Ajouter'; }else{ echo 'Modifier'; }?></button></div>
		</div>
	</form>
	<br class="clear" />
</div>
<?php
}
?>
