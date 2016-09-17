
	<div class="gog-modal smooth hide2" id="scanner-modal">
		<div class="log-form scan_pane module gog-form">
			<button class="gog-modal-close" data-action="close"><i class="fui-cross"></i></button>
			<h2 class="gog-form-title">
				Scanner de fichiers vidéo
				<br/>
<!--				<span class="small">
				<?php echo CFG::$b_path; ?>
				</span>-->
			</h2>
			<button onclick="scan();" id="scan_btn" class="gog-btn-big loadable">
				Lancer le scanner
			</button>
			<div id="scanfini" class='gog-form-description'>
				Scan terminé. <span id="nonew">Aucune nouvelle entrée.</span>
				<div class="error">
					<span class="scan-error-other"></span>
					<span class="scan-error-files error">
						<span class="scan-error-files-nbr"></span> fichiers n'ont pas pu être reconnu. <span class='a' onclick='showHideScan();'></span>.
						<br/>Le scanner ne reconnait pas les fichiers possédant certains types de caractères (spéciaux, asiatiques ou arabes, ...).
					</span>
				</div>
			</div>
			<div id="table_scan">
				<table class="gog-table">
					<thead>
						<tr>
							<th></th><th>Nom</th><th>Extension</th><th></th>
						</tr>
					</thead>
					<tbody id="table_body">

					</tbody>
				</table>
			</div>
		</div>
		<form action="#" id="form_scan" class='gog-form module'>
			<h2 class="gog-form-title">Ajout</h2><br/><span class='small'></span>
			<ol class="gog-fieldset">
				<li class="gog-field">
					<input type="text" name="langues" id="iplangues" class="gog-input" placeholder="Langues" data-toggle="popover" data-trigger="focus" title="Langues" data-content="fr, en, jap, ...." data-placement="left"/>
				</li>
				<li class="gog-field">
					<input type="text" name="sub" id="ipsub" class="gog-input" placeholder="Sous-titres" data-toggle="popover" data-trigger="focus" title="Sous-titres" data-content="fr, en, jap, ...." data-placement="left"/>
				</li>
				<li class="gog-field" id="seriepart">
					<input type="text" name="saison" id="ipsaison" class="gog-input" placeholder='Nom ou n° de saison' data-toggle="popover" data-trigger="focus" title="Nom ou n° de saison" data-content="Attention, l'ensemble du dossier sera compté dans la saison." data-placement="left"/>
				</li>
				<li class="gog-field">
					<input type="url" name="sc" id="ipsc" onchange="checkLienSC(this)" class="gog-input" placeholder="Lien Senscritique" required data-toggle="popover" data-trigger="focus" title="Lien Senscritique" data-content="http://www.senscritique.com/serie/Narcos/8457821" data-placement="left"/>
				</li>
				<li class="gog-field">
					<button type="submit" onchange="" name="submit" class="gog-btn-big gog-active">Ajouter</button>
				</li>
			</ol>
			<input type="hidden" name="path"/>
		</form>
	</div>