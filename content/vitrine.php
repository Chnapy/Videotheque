<script>
	window.onload = function () {
		setParams(<?php
echo json_encode(CFG::$cfg);
?>);
//		setConnected(<?php
//if (SC::$client->isConnecte()) {
//	$items = SC::$client->getUserItems();
//	echo 'true, "' . $items['pseudo'] . '", "' . $items['avatar'] . '", "' . $items['lien'] . '"';
//} else {
//	echo 'false';
//}
?>//);
		onLoad();
	};
</script>



<nav>
	<div class="nav_item nav_left a" onclick="smooth_show($('#apropos-modal'));">
		<img class="logo" src="img/apropos/marmotte_fume.jpg" alt="">
	</div>
	<div class="nav_middle row">
		<div class="nav_item a" onclick="smooth_show($('#scanner-modal'));">
			Scanner
		</div>
		<div class="nav_item a" onclick="smooth_show($('#param-modal'));">
			Paramètres
		</div>
	</div>
	<div class="nav_item nav_right">
		<div class="head-sc nav_item a">
			<div class="sc-log not-log">
				<div class="sc_green">
					<img src="img/icones/sc/sc_logo_1.png" class="sc_green_1"/>
					<img src="img/icones/sc/sc_logo_2.png" class="sc_green_2"/>
					<span class="fui-cross sc_green_not_access sc_not_accessible error"></span>
				</div>
				<div class='deco-btn'>
					<span class="gog-btn" onclick="deconnexion();"><span class="fui-exit"></span></span>
				</div>
				<a class='sc-log-content loadable' target='_blank'>
				</a>
				<form action="#" method="POST" class="sc-log-form gog-form smooth" id='sc-log-form'>
					<div class="sc-log-form-tip"></div>
					<h2 class="gog-form-title"><i class="sc-logo"></i>Connexion</h2>
					<p class="gog-form-description error sc_not_accessible">Senscritique semble être inaccessible. La connexion risque d'échouer.</p>
					<ol class="gog-fieldset">
						<li class="gog-field">
							<input type="email" name="email" required="required" placeholder="Email" class="gog-input"/>
						</li>
						<li class="gog-field">
							<input type="password" name="pass" required="required" placeholder="Mot de passe" class="gog-input" maxlength="4096"/>
						</li>
						<li class="gog-field">
							<button type="submit" name="submit" class="gog-btn-big gog-active ">Se connecter maintenant</button>
						</li>
					</ol>
					<p class="gog-form-description sc-log-form-error error"></p>
					<p class="gog-form-description">
						Aucune donnée envoyée n'est stockée par le serveur. <strong>Un cookie sera cependant créé et stocké</strong>, une déconnexion le détruisant.
					</p>
					<div class="gog-form-footer">
						<div class="gog-form-section">
							<a class="gog-btn-big" href="http://www.senscritique.com/auth/forgetPassword" target="_blank">Réinitialiser le mot de passe <span class='fui-export redirect-sc'></span></a>
						</div>
						<div class="gog-form-section">
							<a class="gog-btn-big" href="http://www.senscritique.com/register" target="_blank">Je n'ai pas de compte SC <span class='fui-export redirect-sc'></span></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</nav>
<span id='alpha'>v0.2a</span>
<!--<div class="nav-spacer"></div>-->
<div id="all">
	<?php require_once 'content/parametres.php'; ?>

	<?php require_once 'content/scanner.php'; ?>

	<?php require_once 'content/content.php'; ?>

	<?php require_once 'content/fiche.php'; ?>

	<?php require_once 'content/apropos.php'; ?>

	<?php // require_once 'content/first.php'; ?>
</div>
