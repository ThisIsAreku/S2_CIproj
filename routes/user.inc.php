<?php

$err = -1;


$loginErrors = array(
	1 => "Mauvais mot de passe",
	2 => "L'utilisateur n'existe pas"
	);

$createErrors = array(
	10 => "L'utilisateur existe déja",
	11 => "Les mots de passes ne sont pas identiques",
	12 => "l'adresse email est incorrecte"
	);


if(isset($_POST['action']))
	if($_POST['action'] == 'login')
	{
		if($Dataman->hasUser($_POST['user']))
		{
			if(!$Dataman->doLogin($_POST['user'], $_POST['pass'])){
				$err = 1;
			}else{
				if(isset($_GET['b']))
				{
					header('Location: '.$_GET['b']);
					die('Vous êtes maintenant connecté, redirection en cours...');
				}
			}
		}else{
			$err = 2;
		}
	}elseif($_POST['action'] == 'create')
	{
		if(!$Dataman->hasUser($_POST['user'])){
			if($_POST['pass1'] == $_POST['pass2']){
				if(preg_match('/^[a-z0-9\-_]+(\.[a-z0-9\-_]+)*@[a-z0-9\-]+(\.[a-z0-9\-]+)*(\.[a-z]{2,3})/i', $_POST['email'])){
					$Dataman->addUser($_POST['user'], $_POST['pass1'], $_POST['email']);
					$Dataman->doLogin($_POST['user'], $_POST['pass1']);
					if(isset($_GET['b']))
					{
						header('Location: '.$_GET['b']);
						die('Vous êtes maintenant connecté, redirection en cours...');
					}
				}else{
					$err = 12;
				}
			}else{
				$err = 11;
			}
		}else{
			$err = 10;
		}
	}


if(isset($_GET['get_out_of_here']))
{
	$Dataman->doLogout();
	header('Location: '.$_GET['b']);
	die('Vous avez été déconnecté, redirection en cours...');
}

if(isset($_POST['user_style']))
{
	$Dataman->getCurrentUser()->setStyle($_POST['user_style']);
	$Dataman->save('users');
}




function pageTitle($append)
{
	global $Dataman;
	$r = 'Se connecter';
	if($Dataman->isLoggedIn())
		$r = 'Mon compte';
	return $r.' | '.$append;
}
function pageContent()
{
	global $Dataman, $err, $loginErrors, $createErrors;
?>
<div id="content">
<?php if(!$Dataman->isLoggedIn()): ?>
	<div class="col2">
		<h3>Se connecter</h3>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<input type="hidden" name="action" value="login" /><div class="control-group">
				<label class="control-label">Nom d'utilisateur</label>
				<div class="controls"><input type="text" name="user" placeholder="Nom d'utilisateur" /></div>
			</div>
			<div class="control-group">
				<label class="control-label">Mot de passe</label>
				<div class="controls"><input type="password" name="pass" placeholder="Mot de passe" /></div>
			</div>
			<br />
			<div class="control-group">
				<div class="controls"><button type="submit" class="btn"><i class="icon-user icon-large"></i> Connexion</button></div>
			</div>
		</form>
		<div class="resultmsg"><?php if(isset($loginErrors[$err])) echo $loginErrors[$err]; ?></div>
	</div>
	<div class="col2">
		<h3>Nouvel utilisateur</h3>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<input type="hidden" name="action" value="create" />
			<div class="control-group">
				<label class="control-label">Nom d'utilisateur</label>
				<div class="controls"><input type="text" name="user" placeholder="Nom d'utilisateur" /></div>
			</div>
			<div class="control-group">
				<label class="control-label">Adresse email</label>
				<div class="controls"><input type="email" name="email" placeholder="Adresse email" /></div>
			</div>
			<div class="control-group">
				<label class="control-label">Mot de passe</label>
				<div class="controls">
					<input type="password" name="pass1" placeholder="Mot de passe" />
					<input type="password" name="pass2" placeholder="Confirmez" />
				</div>
			</div>
			<br />
			<div class="control-group">
				<div class="controls"><button type="submit" class="btn"><i class="icon-pencil icon-large"></i> Créer un compte</button></div>
			</div>
		</form>
		<div class="resultmsg"><?php if(isset($createErrors[$err])) echo $createErrors[$err]; ?></div>
	</div>
<?php else: ?>
	<h3>Mes infos</h3>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<div class="control-group">
			<label class="control-label">Nom d'utilisateur</label>
			<div class="controls">
				<input type="text" readonly="readonly" value="<?php echo $Dataman->getCurrentUser()->getUsername(); ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Adresse email</label>
			<div class="controls">
				<input type="text" readonly="readonly" value="<?php echo $Dataman->getCurrentUser()->getEmail(); ?>" />
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label">Thème du site</label>
			<div class="controls">
				<select name="user_style">
					<option value="main" <?php echo ($Dataman->getCurrentUser()->getStyle() == 'main' ? 'selected="selected"' : ''); ?>>Défaut</option>
					<option value="alternate" <?php echo ($Dataman->getCurrentUser()->getStyle() == 'alternate' ? 'selected="selected"' : ''); ?>>Alternatif</option>
				</select>
				<button type="submit" class="btn">Appliquer</button>
			</div>
		</div>		
	</form>
	<h2>Historique de vos commandes</h2>
	<?php $commands = $Dataman->getCommandsByUser($Dataman->getCurrentUser()->getUsername());
	if(count($commands) > 0): ?>
		<ul>
			<?php foreach($commands as $c): ?>
			<li><?php echo date('d/m/Y à H:i:s', $c->getDate()); ?> (<?php echo $c->getTotalFormatted(); ?>&#8364;) <a href="#" class="show_command_detail">détails</a>
				<div class="command_detail">
					<table class="table">
						<thead>
							<tr>
								<th></th>
								<th>Nom</th>
								<th>Prix</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($c->getItems() as $hex => $itm): ?>
							<tr>
								<td class="cube" style="background-color: #<?php echo $itm->getHex(); ?>;"></td>
								<td class="text"><?php echo $itm->getName(); ?></td>
								<td class="price"><?php echo $itm->getPriceFormatted(); ?>&#8364;</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>Vous n'avez pas passé de commandes</p>
	<?php endif; ?>
<?php if($Dataman->getCurrentUser()->isAdmin()): ?>	
	<h2>Administration</h2>
	<h3>Commandes</h3>
	<?php 
	$byusers = array();
	foreach($Dataman->getCommands() as $c)
	{
		if(!isset($byusers[$c->getUser()]))
			$byusers[$c->getUser()] = array();
		$byusers[$c->getUser()][] = $c;		
	} ?>
	<div>
		<?php foreach( array_keys($byusers) as $u): ?>
		<span class="spaced"><a href="#commands-<?= $u ?>"><?= $u ?></a></span>
		<?php endforeach; ?>
	</div>
	<?php foreach($byusers as $k => $v): ?>
	<div>
		<h4 id="commands-<?= $k ?>"><?= $k ?></h4>
		<ul>
			<?php foreach($v as $c): ?>
			<li><?= date('d/m/Y à H:i:s', $c->getDate()); ?> (<?= $c->getTotalFormatted(); ?>&#8364;) <a href="#" class="show_command_detail">détails</a>
				<div class="command_detail">
					<table class="table">
						<thead>
							<tr>
								<th></th>
								<th>Nom</th>
								<th>Prix</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($c->getItems() as $hex => $itm): ?>
							<tr>
								<td class="cube" style="background-color: #<?= $itm->getHex(); ?>;"></td>
								<td class="text"><?= $itm->getName(); ?></td>
								<td class="price"><?= $itm->getPriceFormatted(); ?>&#8364;</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endforeach; ?>
	<h3>Gestion des produits</h3>
	<h4>Ajouter un produit</h4>
	<form id="add-prod" method="get" action="<?= URL_BASE; ?>/edit-article">
		<div id="add-prod-inline" class="color-selector">
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
			<div class="control-group">
				<label class="control-label">Code</label>
				<div class="controls">
					<input name="id" class="color-hex" type="text" maxlength="6" pattern="^[a-fA-F0-9]{6}$" placeholder="Code couleur" value="000000" />
					<div class="color-preview" style="background-color: #000000">&nbsp;</div>
				</div>
			</div>
		</div>
		<br />
		<div class="control-group">
			<div class="controls"><button type="submit" class="btn"><i class="icon-plus-sign icon-large"></i> Ajouter</button></div>
		</div>
	</form>
	<h4 id="edt-prod">Modifier un produit</h4>
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th>Nom</th>
				<th>Prix</th>
				<th>-</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($Dataman->getItems() as $hex => $itm): ?>
			<tr>
				<td class="cube" style="background-color: #<?php echo $itm->getHex(); ?>;"></td>
				<td class="text"><?= $itm->getName(); ?></td>
				<td class="price"><?= $itm->getPriceFormatted(); ?>&#8364;</td>
				<td class="last2">
					<a href="#<?php echo $itm->getHex(); ?>" class="normalize switch-showcase" title="Mettre '<?= $itm->getName(); ?>' en page d'accueil"><i class="icon-star<?php if(!$itm->isShowcase()) echo '-empty'; ?> icon-large"></i></a>
					<a href="<?php echo URL_BASE; ?>/edit-article?id=<?php echo $itm->getHex(); ?>"class="btn"><i class="icon-edit icon-large"></i> Modifier</a>
					<button type="button" value="<?php echo $itm->getHex(); ?>" class="btn btn-alert rm-from-db"><i class="icon-trash icon-large"></i> Supprimer</button>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
<?php endif; ?>
	<br class="clear" />
</div>
<?php
}
?>