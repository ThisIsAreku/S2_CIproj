<?php
function pageTitle($append)
{
	return 'Accueil | '.$append;
}
function pageContent()
{
	global $Dataman;
?>
	<div id="slider" data-num="<?= count($Dataman->getShowcase()); ?>"><div class="wrapper" style="width: <?= count($Dataman->getShowcase())*981; ?>px">
		<?php $first = true; foreach($Dataman->getShowcase() as $sc): ?>
		<div class="slide<?php if($first){ $first = false; echo ' active'; } ?>" style="background-color: #<?= $sc->getHex(); ?>">
			<div class="name"><?= $sc->getName(); ?></div>
			<div class="text"><?= $sc->getPriceFormatted(); ?>&#8364; - <a href="<?= URL_BASE; ?>/article/<?= $sc->getHex(); ?>">Acheter maintenant</a></div>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="progress"><div class="inner"></div></div>
	</div>
	<div id="content">
		<h2>Bienvenue sur Kolorshop</h2>
		<p>Faite votre séléction parmis notre large choix de couleurs. N'hésitez pas a tester le thème alternatif du site en vous connectant ou bien en créant un compte</p>
	</div>
<?php
}
?>