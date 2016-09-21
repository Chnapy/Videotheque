

<div id="fiche" class="container-fluid hide smooth">
	<div id="fiche-background">
		<img id="fiche-back" class="fadein" />
		<div id="fiche-over"></div>
	</div>
	<div id="fiche-content">
		<div class="sup-header">
			<button class="gog-btn bout-retour" onclick="toVitrine();"><span class="fui-arrow-left"></span></button>
			<div class="modif-fiche">
				<span class="gog-btn fiche-actu loadable" onclick="actualiserFiche();"><span class="glyphicon glyphicon-refresh"></span></span>
				<span class="gog-btn fiche-params loadable" onclick="smooth_show($('#fiche-param-modal'));"><span class="fui-gear"></span></span>
				<span class="gog-btn fiche-supp loadable" onclick="smooth_show($('#fiche-supp-modal'));"><span class="fui-cross"></span></span>
			</div>
		</div>
		<div class="row header">
			<div class="col-md-10">
				<div class="title-head bloc">
					<h1 class="title-main">
						<span class="title-main-text">
						</span>
						<span class="small annee">
						</span>
						<a href="" target="_blank" class="fui-export redirect-sc"></a>
					</h1>

				</div>
				<div class="small title-sec bloc loadable l-text"></div>
			</div>
			<div class="col-md-2">
				<div class="o-types">
					<div class="o-type2 label">
					</div>
					<div class="o-type label">
					</div>
				</div>
			</div>
		</div>
		<div class="row body">
			<div class="col-md-3 gauche">
				<img class="affiche"/>

			</div>
			<div class="col-md-6 centre">
				<div class='section'>
					<div class="mini-titre">Infos</div>
					<div class="contenu">
						<span class="reals">
						</span>
						<span class="acteurs">
						</span>
						<br/>
						<span class="pays loadable l-text">
						</span>
						<br/>
						<span class="nbr_saisons">
						</span>
						<span class="duree">
						</span>
					</div>
				</div>
				<div class='section synopsis loadable'>
					<div class="mini-titre">Synopsis</div>
					<div class="contenu">
					</div>
				</div>
				<div class="trailer-box loadable">
					<div class="mini-titre">Trailer</div>
					<div class="trailer">
					</div>
				</div>
				<div class="visionnage-box loadable">
					<div class="mini-titre">Visionner</div>
					<div class="visionnage">
					</div>
				</div>
			</div>
			<div class="col-md-3 droite">
				<div class="mini-titre">Notes</div>
				<div class="o-notes">
					<div class="o-note-glob">
					</div>
					<div class="o-manote">
					</div>
				</div>
				<div class='saga loadable'>
					<div class="mini-titre">Saga</div>
					<div class="saga-list module">
						<div class='saga-head'>
							<span class="saga-head-txt"></span><a href="" target="_blank" class="fui-export redirect-sc"></a>
						</div>
						<div class='saga-body'>
						</div>
					</div>
				</div>
				<div class="mini-titre">Tags</div>
				<div class="o-tags">
				</div>
			</div>
		</div>
		<button class="fiche-toleft smooth gog-btn-big"><span class="fui-arrow-left"></span><span class="fiche-toleft-titre"></span></button>
		<button class="fiche-toright smooth gog-btn-big"><span class="fiche-toright-titre"></span><span class="fui-arrow-right"></span></button>
	</div>
</div>

<div class="gog-modal smooth hide2" id="fiche-supp-modal">

	<div class="gog-form module smooth" id='fiche-supp-form'>
		<button class="gog-modal-close" data-action="close"><i class="fui-cross"></i></button>
		<h2 class="gog-form-title">
			Supprimer l'oeuvre
		</h2>
		<div class='gog-form-description'>
			Action irrémédiable.
			<br/>Les fichiers ne seront pas impactés.
		</div>
		<div class="gog-field">
			<button class="gog-btn-big gog-active" onclick='supprimerFiche();'>Supprimer l'oeuvre</button>
		</div>
	</div>
</div>

<div class="gog-modal smooth hide2" id="fiche-param-modal">
	<div class="module gog-form">
		<button class="gog-modal-close" data-action="close"><i class="fui-cross"></i></button>
		<form id='fiche-param-form'>
			<h2 class="gog-form-title">
				Paramètres de l'oeuvre
			</h2>
			<table class="gog-table">
				<tbody>
					<tr><td class="gog-td-label">Lien Senscritique</td>
						<td><input type="url" name="lien_sc" class="gog-input" required id='fiche-param-liensc'></td>
					</tr>
				</tbody>
			</table>
			<div id='fiche-param-content'>
			</div>
			<div class="gog-field">
				<button type='submit' class="gog-btn-big gog-active">Effectuer les modifications</button>
			</div>
		</form>
	</div>
</div>