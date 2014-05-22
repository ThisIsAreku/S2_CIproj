<?php
function pageTitle($append)
{
	return 'À propos | '.$append;
}
function pageContent()
{
	global $Dataman;
?>
<div id="content">
	<h2>À propos de ce projet</h2>
	<p>Ce site web a été réalisé pour le second semestre de L1</p>
	<p>Il utilise Font Awesome par Dave Gandy - <a href="http://fortawesome.github.com/Font-Awesome" target="_blank">http://fortawesome.github.com/Font-Awesome</a>, LESS (<a href="http://lesscss.org/" target="_blank">http://lesscss.org/</a>), des images de Subtle Pattern (<a href="http://subtlepatterns.com">http://subtlepatterns.com</a> ) et un peu de magie noire.</p>
</div>
<?php
}
?>