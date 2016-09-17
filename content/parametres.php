

	<div class="gog-modal smooth hide2" id="param-modal">
		<div class="log-form scan_pane module gog-form">
			<button class="gog-modal-close" data-action="close"><i class="fui-cross"></i></button>

			<form action="#" method="POST" class="gog-form smooth" id='param-form'>
				<h2 class="gog-form-title">
					Paramètres
				</h2>
				<table class="gog-table">
					<tbody>
						<tr><td class="gog-td-label"><label for="ip_load_auto">Charger les oeuvres au démarrage</label></td>
							<td>
								<i class="gog-checkbox <?php echo CFG::$cfg['load_auto'] ? 'is-selected' : ''; ?>">
									<input type="checkbox" name="load_auto" id="ip_load_auto" <?php echo CFG::$cfg['load_auto'] ? 'checked' : ''; ?>>
								</i>
							</td>
						</tr>
						<tr><td class="gog-td-label">Interval test de réponse Senscritique, en ms (ou 0 pour ne pas effectuer de test)</td>
							<td><input type="number" name="sc_interval" class="gog-input" required value=<?php echo CFG::$cfg['sc_check_interval']; ?>></td>
						</tr>
						<tr><td class="gog-td-label"><label for="ip_curseur">Afficher les requêtes Ajax via le curseur</label></td>
							<td>
								<i class="gog-checkbox <?php echo CFG::$cfg['curseur_load'] ? 'is-selected' : ''; ?>">
									<input type="checkbox" name="curseur_load" id="ip_curseur" <?php echo CFG::$cfg['curseur_load'] ? 'checked' : ''; ?>>
								</i>
							</td>
						</tr>
						<tr>
							<td class="gog-td-label">Lors du scroll afficher la barre des filtres</td>
							<td>
								<div class="gog-radio-group">
									<label>
										<i class="gog-radio <?php echo CFG::$cfg['filtre_pos'] === 0 ? 'is-selected' : ''; ?>">
											<input type="radio" name="filtre_pos" value="0" <?php echo CFG::$cfg['filtre_pos'] === 0 ? 'checked' : ''; ?>>
										</i>
										En haut
									</label>
									<br/>
									<label>
										<i class="gog-radio <?php echo CFG::$cfg['filtre_pos'] === 1 ? 'is-selected' : ''; ?>">
											<input type="radio" name="filtre_pos" value="1" <?php echo CFG::$cfg['filtre_pos'] === 1 ? 'checked' : ''; ?>>
										</i>
										A gauche
									</label>
									<br/>
									<label>
										<i class="gog-radio <?php echo CFG::$cfg['filtre_pos'] === 2 ? 'is-selected' : ''; ?>">
											<input type="radio" name="filtre_pos" value="2" <?php echo CFG::$cfg['filtre_pos'] === 2 ? 'checked' : ''; ?>>
										</i>
										Ne pas la déplacer
									</label>
								</div>
							</td>
						</tr>
						<tr><td class="gog-td-label"><label for="ip_first">Afficher la page d'aide au démarrage</label></td>
							<td>
								<i class="gog-checkbox <?php echo CFG::$cfg['first_use'] ? 'is-selected' : ''; ?>">
									<input type="checkbox" name="first_use" id="ip_first" <?php echo CFG::$cfg['first_use'] ? 'checked' : ''; ?>>
								</i>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" name="param" value="param"/>
				<div class="gog-field">
					<button type="submit" class="gog-btn-big gog-active">Envoyer les modifications du dessus</button>
				</div>
				<p class="gog-form-description param-form-error error"></p>
			</form>
			<form action="#" method="POST" class="gog-form smooth" id='vlc-form'>
				<h2 class="gog-form-title">
					VLC
				</h2>
				<table class="gog-table">
					<tbody>
						<tr><td class="gog-td-label"><label for="ip_fullscreen">Fullscreen au lancement</label></td>
							<td>
								<i class="gog-checkbox <?php echo CFG::$cfg['vlc_params']['fullscreen'] ? 'is-selected' : ''; ?>">
									<input type="checkbox" name="fullscreen" id="ip_fullscreen" <?php echo CFG::$cfg['vlc_params']['fullscreen'] ? 'checked' : ''; ?>>
								</i>
							</td>
						</tr>
						<tr><td class="gog-td-label"><label for="ip_play">Démarrer la vidéo au lancement</label></td>
							<td>
								<i class="gog-checkbox <?php echo CFG::$cfg['vlc_params']['play_auto'] ? 'is-selected' : ''; ?>">
									<input type="checkbox" name="play_auto" id="ip_play" <?php echo CFG::$cfg['vlc_params']['play_auto'] ? 'checked' : ''; ?>>
								</i>
							</td>
						</tr>
						<tr><td class="gog-td-label">Volume au lancement</td>
							<td><input type="number" name="volume" class="gog-input" required value=<?php echo CFG::$cfg['vlc_params']['volume']; ?>></td>
						</tr>
						<tr><td class="gog-td-label">Rognage</td>
							<td>
								<div class="gog-field">
									Gauche
									<input type="number" name="crop0" class="gog-input" required value=<?php echo CFG::$cfg['vlc_params']['crop'][0]; ?>>
								</div>
								<div class="gog-field">
									Haut
									<input type="number" name="crop1" class="gog-input" required value=<?php echo CFG::$cfg['vlc_params']['crop'][1]; ?>>
								</div>
								<div class="gog-field">
									Droite
									<input type="number" name="crop2" class="gog-input" required value=<?php echo CFG::$cfg['vlc_params']['crop'][2]; ?>>
								</div>
								<div class="gog-field">
									Bas
									<input type="number" name="crop3" class="gog-input" required value=<?php echo CFG::$cfg['vlc_params']['crop'][3]; ?>>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" name="param" value="vlc"/>
				<div class="gog-field">
					<button type="submit" class="gog-btn-big gog-active">Envoyer les modifications du dessus</button>
				</div>
				<p class="gog-form-description param-form-error error"></p>
			</form>
			<form action="#" method="POST" class="gog-form smooth" id='path-form'>
				<h2 class="gog-form-title">
					Chemins et répertoires
				</h2>
				<p class="gog-form-description">
					Privilégiez les chemins absolus.
					<br/>Les modifications de ces propriétés exigerons certainement le rechargement de la page.
					<br/>L'utilisation de backslash n'est d'aucune utilité, mais n'est pas gênant pour autant.
				</p>
				<table class="gog-table">
					<tbody>
						<tr><td class="gog-td-label">Fichier de configuration principal</td>
							<td><input type="text" disabled class="gog-input" required value="<?php echo CFG_PATH; ?>"></td>
						</tr>
						<tr><td class="gog-td-label">Répertoire cible du scanner</td>
							<td><input type="text" name="biblio_path" class="gog-input" required value="<?php echo CFG::$cfg['biblio_path']; ?>"></td>
						</tr>
						<tr><td class="gog-td-label">Fichier de données de la vidéothèque</td>
							<td><input type="text" name="biblio_cfg_path" class="gog-input" required value="<?php echo CFG::$cfg['biblio_cfg_path']; ?>"></td>
						</tr>
						<tr><td class="gog-td-label">Exécutable de VLC</td>
							<td><input type="text" name="vlc_path" class="gog-input" required value="<?php echo CFG::$cfg['vlc_path']; ?>"></td>
						</tr>
						<tr><td class="gog-td-label">Exécutable du gestionnaire de fichier</td>
							<td><input type="text" name="explorer_path" class="gog-input" required value="<?php echo CFG::$cfg['explorer_path']; ?>"></td>
						</tr>
						<tr><td class="gog-td-label">Fichier de stockage du cookie Senscritique</td>
							<td><input type="text" name="cookie_path" class="gog-input" required value="<?php echo CFG::$cfg['cookie_path']; ?>"></td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" name="param" value="path"/>
				<div class="gog-field">
					<button type="submit" class="gog-btn-big gog-active">Envoyer les modifications du dessus</button>
				</div>
				<p class="gog-form-description param-form-error error"></p>
			</form>
			<form action="#" method="POST" class="gog-form smooth" id='config-form'>
				<h2 class="gog-form-title">
					Fichier de configuration principal
				</h2>
				<p class="gog-form-description error">
					<strong>ATTENTION</strong>
					<br/>Les informations entrées définiront le nouveau contenu du fichier de configuration et ce <strong>sans aucune vérification préalable !</strong>
					<br/>Privilégiez la modification du fichier par les formulaires du dessus.
				</p>
				<textarea class="gog-input area-config" name="content" required><?php echo CFG::toHTML(); ?></textarea>
				<input type="hidden" name="param" value="config"/>
				<div class="gog-field">
					<button type="submit" class="gog-btn-big gog-active">Envoyer les modifications du dessus</button>
				</div>
				<p class="gog-form-description param-form-error error"></p>
			</form>
		</div>
	</div>