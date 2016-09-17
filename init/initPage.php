
<div class="login">
	<div class="log-screen">
		<div class="log-icon">
			<h4 class="pre-titre">Bienvenue dans</h4>
			<img src="img/logo.jpg" alt="">
			<h4><small>La vidéothèque de Mr. Marmotte</small></h4>
		</div>
		<hr class="sep-sm">
		<div class="text-center">
			<form action="index.php" method="post">
				<input type="hidden" name="m" value="vitrine"/>
				<button type="submit" class="btn btn-embossed btn-danger btn-lg" id="entrer-btn" onclick="entrer();">Entrer<span class="fui-arrow-right"></span></button>
			</form>
		</div>
		<hr class="sep-sm">
		<div class="log-form cfg_pane smooth">
			<div class="text-center">
				<div class="lead">
					<?php if (CFG::$loaded) { ?>
						Contenu du fichier cfg
					<?php } else { ?>
						Fichier cfg non chargé
						<?php
						exit();
					}
					?>
				</div>
				<div class="small">
					<?php echo CFG_PATH; ?>
				</div>
			</div>
			<table class="table table-condensed">
				<tbody>
					<?php
					foreach (CFG::$cfg as $cle => $valeur) {
						echo '<tr><td>' . $cle . '</td><td>' . (!is_bool($valeur) ? $valeur : ($valeur ? "true" : "false")) . '</td></tr>';
					}
					?>
				</tbody>
			</table>
		</div>
		<div class="log-form scan_pane dark_pane">
			<div class="text-center">
				<div class="lead">
					Scanner de fichiers vidéo
				</div>
				<div class="small">
					<?php echo CFG::$b_path; ?>
				</div>
			</div>
			<button onclick="scan();" id="scan_btn" class="btn btn-danger btn-lg btn-block">
				Lancer le scanner
			</button>
			<div id="scanfini" class="text-center">
				Scan terminé. <span id="nonew">Aucune nouvelle entrée.</span>
			</div>
			<div id="table_scan">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th></th><th>Nom</th><th>Extension</th><th>Path</th>
						</tr>
					</thead>
					<tbody id="table_body">

					</tbody>
				</table>
			</div>
		</div>
		<form action="#" id="form_scan">
			<div id="control_scan">
				<label class="radio">
					<input type="radio" name="type" value="film" onclick="film();" data-toggle="radio" class="custom-radio">
					Film
				</label>
				<label class="radio">
					<input type="radio" name="type" value="serie" onclick="serie();" data-toggle="radio" class="custom-radio">
					Série (par saison)
				</label>
			</div>
			<div id="inputpart">
				<div class="form-group">
					<label for="ipnom">Nom</label>
					<input type="text" name="nom" id="ipnom" class="form-control input-sm" />
				</div>
				<div class="form-group">
					<label for="ipsaga">Saga</label>
					<input type="text" name="saga" id="ipsaga" class="form-control input-sm" placeholder="optionnel"/>
				</div>
				<div class="form-group">
					<label for="iplangues">Langues</label>
					<input type="text" name="langues" id="iplangues" class="form-control input-sm" placeholder="fr, en"/>
				</div>
				<div class="form-group">
					<label for="ipsub">Sous-titres</label>
					<input type="text" name="sub" id="ipsub" class="form-control input-sm" placeholder="fr, en"/>
				</div>
				<div class="form-group">
					<label for="ipsc">Lien Senscritique</label>
					<input type="text" name="sc" id="ipsc" class="form-control input-sm" placeholder="http://www.senscritique.com/serie/Narcos/8457821" required/>
				</div>
				<div class="form-group" id="seriepart">
					<label for="ipsaison">Saison</label><span class="fui fui-question-circle" data-toggle="tooltip" title="Le contenu du dossier sera inclus dans la saison"></span>
					<input type="number" name="saison" id="ipsaison" class="form-control input-sm"/>
				</div>
				<input type="hidden" name="path"/>
				<input type="submit" class="btn btn-sm btn-primary"/>
			</div>
		</form>
	</div></div>