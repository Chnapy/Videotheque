


<div id="content" class="smooth">
	<div id="content-header" class="">
		<div id="content-header-top" class="cf">
			<button id='load_btn' class='gog-btn loadable gog-active' onclick="loadall();">Charger les oeuvres</button>
			<h1 class="content-header-top-title"><span class="loadable load-oeuvre l-text"></span><span class="nbr_oeuvres"></span><span class="nbr_total_oeuvres"></span> FILMS & SERIES</h1>

			<div class="content-header-top-right">
				<div class="content-header-top-item dropdown-back" data-function="setAffichage" id="dropdown-affichage">
					AFFICHAGE :
					<span class="dropdown-back-value"></span><!--
					--><span class="dropdown-back-content">
						<span class="dropdown-back-wrapper">
							<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
							<i class="dropdown-back-pointer-up"></i>
						</span>
						<span class="dropdown-back-items">
							<span class="dropdown-back-item" data-value="list1"><span class="fui-list-columned"></span></span>
							<span class="dropdown-back-item" data-value="list2"><span class="fui-list-small-thumbnails"></span></span>
						</span>
					</span>
				</div>
				<div class="content-header-top-item dropdown-back" data-function="setTri" id="dropdown-tri">
					TRIER :
					<span class="dropdown-back-value"></span><!--
					--><span class="dropdown-back-content">
						<span class="dropdown-back-wrapper">
							<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
							<i class="dropdown-back-pointer-up"></i>
						</span>
						<span class="dropdown-back-items">
							<span class="dropdown-back-item" data-value="id">Par ID</span>
							<span class="dropdown-back-item" data-value="titre">Par titre</span>
							<span class="dropdown-back-item" data-value="notemoy">Par note moyenne</span>
							<span class="dropdown-back-item" data-value="manote">Par ma note</span>
							<span class="dropdown-back-item" data-value="annee">Par année</span>
							<span class="dropdown-back-item" data-value="duree">Par durée</span>
						</span>
					</span>
				</div>
			</div>
		</div>

		<div id="filtre-anchor"></div>
		<div id="filtre" class="cf">
			<div class="module module-filtre">
				<div class="filtre-search-container">
					<div class="filtre-container filtre-search">
						<div class="filtre-search-box">
							<input type="search" class="search-input" id="search-input" placeholder="Rechercher..."/>
							<i class="fui-search"></i>
							<i class="spinner"></i>
							<i class="fui-cross-circle"></i>
						</div>
					</div>
				</div>
				<div class="filtre-show-container">
					<div id="filtre-show" class="filtre filtre-dropdown is-contracted" onclick="filtre_show(true);">
						<i class="glyphicon glyphicon-chevron-left"></i>
						Autres filtres
						<i class="glyphicon glyphicon-chevron-right"></i>
					</div>
				</div>
				<div class="filtre-dropdowns-container cf">
					<div id="filtre-reals" class="filtre filtre-dropdown is-contracted" data-function="search_reals">
						Réalisateurs
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group">
							</div>
						</div>
					</div>
					<div id="filtre-acteurs" class="filtre filtre-dropdown is-contracted" data-function="search_acteurs">
						Acteurs
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group">
							</div>
						</div>
					</div>
					<div id="filtre-langues" class="filtre filtre-dropdown is-contracted" data-function="search_langues">
						Langues
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group">
							</div>
						</div>
					</div>
					<div id="filtre-sub" class="filtre filtre-dropdown is-contracted" data-function="search_sub">
						Sous-titres
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group">
							</div>
						</div>
					</div>
					<div id="filtre-tags" class="filtre filtre-dropdown is-contracted" data-function="search_tags">
						Tags
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group">
							</div>
						</div>
					</div>
					<div id="filtre-annee" class="filtre filtre-dropdown is-contracted" data-function="search_annee">
						Annee
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group">
							</div>
						</div>
					</div>
					<div id="filtre-manote" class="filtre filtre-dropdown is-contracted" data-function="search_manote">
						Ma note
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group filtre-group-mid">
								<div class="filtre-item" data-value=">">
									<i class="gog-checkbox"></i><span>></span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">1">
									<i class="gog-radio"></i><span>1</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">2">
									<i class="gog-radio"></i><span>2</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">3">
									<i class="gog-radio"></i><span>3</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">4">
									<i class="gog-radio"></i><span>4</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">5">
									<i class="gog-radio"></i><span>5</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">6">
									<i class="gog-radio"></i><span>6</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">7">
									<i class="gog-radio"></i><span>7</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">8">
									<i class="gog-radio"></i><span>8</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">9">
									<i class="gog-radio"></i><span>9</span>
								</div>
							</div>
							<div class="filtre-group filtre-group-mid">
								<div class="filtre-item" data-value="<">
									<i class="gog-checkbox"></i><span>&lt;</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<2">
									<i class="gog-radio"></i><span>2</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<3">
									<i class="gog-radio"></i><span>3</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<4">
									<i class="gog-radio"></i><span>4</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<5">
									<i class="gog-radio"></i><span>5</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<6">
									<i class="gog-radio"></i><span>6</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<7">
									<i class="gog-radio"></i><span>7</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<8">
									<i class="gog-radio"></i><span>8</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<9">
									<i class="gog-radio"></i><span>9</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<10">
									<i class="gog-radio"></i><span>10</span>
								</div>
							</div>
						</div>
					</div>
					<div id="filtre-notemoy" class="filtre filtre-dropdown is-contracted" data-function="search_notemoy">
						Mote moyenne
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group filtre-group-mid">
								<div class="filtre-item" data-value=">">
									<i class="gog-checkbox"></i><span>></span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">1">
									<i class="gog-radio"></i><span>1</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">2">
									<i class="gog-radio"></i><span>2</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">3">
									<i class="gog-radio"></i><span>3</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">4">
									<i class="gog-radio"></i><span>4</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">5">
									<i class="gog-radio"></i><span>5</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">6">
									<i class="gog-radio"></i><span>6</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">7">
									<i class="gog-radio"></i><span>7</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">8">
									<i class="gog-radio"></i><span>8</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value=">9">
									<i class="gog-radio"></i><span>9</span>
								</div>
							</div>
							<div class="filtre-group filtre-group-mid">
								<div class="filtre-item" data-value="<">
									<i class="gog-checkbox"></i><span>&lt;</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<2">
									<i class="gog-radio"></i><span>2</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<3">
									<i class="gog-radio"></i><span>3</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<4">
									<i class="gog-radio"></i><span>4</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<5">
									<i class="gog-radio"></i><span>5</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<6">
									<i class="gog-radio"></i><span>6</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<7">
									<i class="gog-radio"></i><span>7</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<8">
									<i class="gog-radio"></i><span>8</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<9">
									<i class="gog-radio"></i><span>9</span>
								</div>
								<div class="filtre-item filtre-item-sub" data-value="<10">
									<i class="gog-radio"></i><span>10</span>
								</div>
							</div>
						</div>
					</div>
					<div id="filtre-type" class="filtre filtre-dropdown is-contracted" data-function="search_type">
						Type
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group">
								<div class="filtre-item" data-value="film">
									<i class="gog-checkbox"></i><span>Film</span>
								</div>
								<div class="filtre-item" data-value="serie">
									<i class="gog-checkbox"></i><span>Série</span>
								</div>
								<div class="filtre-item" data-value="anime">
									<i class="gog-checkbox"></i><span>Anime</span>
								</div>
							</div>
						</div>
					</div>
					<div id="filtre-pays" class="filtre filtre-dropdown is-contracted" data-function="search_pays">
						Pays
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group">
							</div>
						</div>
					</div>
					<div id="filtre-duree" class="filtre filtre-dropdown is-contracted" data-function="search_duree">
						Durée
						<i class="dropdown-back-icon glyphicon glyphicon-chevron-down"></i>
						<i class="fui-cross"></i>
						<i class="pointer-up"></i>
						<div class="filtre-items">
							<div class="filtre-group">
								<div class="filtre-item" data-value="0">
									<i class="gog-checkbox"></i><span>&lt; 20m</span>
								</div>
								<div class="filtre-item" data-value="1">
									<i class="gog-checkbox"></i><span>20m - 40m</span>
								</div>
								<div class="filtre-item" data-value="2">
									<i class="gog-checkbox"></i><span>40m - 1h</span>
								</div>
								<div class="filtre-item" data-value="3">
									<i class="gog-checkbox"></i><span>1h - 1h 30m</span>
								</div>
								<div class="filtre-item" data-value="4">
									<i class="gog-checkbox"></i><span>1h 30m - 2h</span>
								</div>
								<div class="filtre-item" data-value="5">
									<i class="gog-checkbox"></i><span>2h - 2h 30m</span>
								</div>
								<div class="filtre-item" data-value="6">
									<i class="gog-checkbox"></i><span>2h 30m - 3h</span>
								</div>
								<div class="filtre-item" data-value="7">
									<i class="gog-checkbox"></i><span>> 3h</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php require_once 'content/first.php'; ?>
	<div id="content-body" class="smooth">
	</div>
</div>