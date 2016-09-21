
var collection = [];
var sagas;
var fiche_id = -1;
var is_loadAll = false;

var sc_error_HTML = '<span class="fui-cross"></span>';

function Oeuvre(json) {

	this.loadJSON = function (json) {
		for (var key in json) {
			if (json.hasOwnProperty(key)) {
				this[key] = json[key];
			}
		}
	};

	this.loadJSON(json);

	this.isFilm = function () {
		return this.type1 === 'film';
	};

	this.afficheToTxt = function () {
		return this.affiche == null ? 'img/affiche_default.png' : this.affiche;
	};

	this.anneeToTxt = function () {
		if (this.annee == null)
			return sc_error_HTML;
		return this.annee;
	};

	this.titreToTxt = function () {
		if (this.titre == null)
			return this.lien_sc.split('/')[4];
		return this.titre;
	};

	this.typesToTxt = function () {
		var ret = '';
		if (this.type1 != null)
			ret += 'o-' + this.type1;
		if (this.type2 != null && this.type2.length > 0)
			ret += ' o-' + this.type2;
		return ret;
	};

	this.type2ToTxt = function () {
		if (this.type2 == null || this.type2.length === 0)
			return "";
		return '<div class="o-type2 label">\n\
						' + this.type2 + '\n\
					</div>';
	};

	this.moyenneToTxt = function () {
		if (this.notemoy == null) {
			return sc_error_HTML;
		}
		if (this.notemoy.trim().length === 0)
			return "-";
		return this.notemoy;
	};

	this.maNoteToTxt = function () {
		if (this.manote == null) {
			return sc_error_HTML;
		}
		if (!this.manote['is_connecte']) {
			return '<span class="sc_icon sc_icon_none"></span>';
		}
		var main, sec = '';
		if (this.manote['vu']) {
			if (this.manote['note']) {
				main = this.manote['note'] + '';
				if (this.manote['coeur']) {
					sec += '<span class="sc_icon sc_icon_coeur"></span>';
				}
			} else if (this.manote['coeur']) {
				main = '<span class="sc_icon sc_icon_coeur"></span>';
			} else if (this.manote['critique']) {
				main = '<span class="sc_icon sc_icon_critique"></span>';
			} else {
				main = '<span class="sc_icon sc_icon_vu"></span>';
			}
			if (this.manote['envie']) {
				sec += '<span class="sc_icon sc_icon_envie"></span>';
			}
			if (this.manote['encours']) {
				sec += '<span class="sc_icon sc_icon_encours"></span>';
			}
			if (this.manote['critique']) {
				sec += '<span class="sc_icon sc_icon_critique"></span>';
			}
		} else {
			if (this.manote['encours']) {
				main = '<span class="sc_icon sc_icon_encours"></span>';
				if (this.manote['envie']) {
					sec += '<span class="sc_icon sc_icon_envie"></span>';
				}
			} else if (this.manote['envie']) {
				main = '<span class="sc_icon sc_icon_envie"></span>';
			} else {
				main = '-';
			}
		}
		return main + '<div class="o-note-sec">' + sec + '</div>';
	};

	this.realToTxt = function () {

		var txt_real = "Par ";
		if (this.reals == null)
			return txt_real + sc_error_HTML;
		for (var i = 0; i < this.reals.length; i++) {
			if (this.reals[i].trim() === "") {
				continue;
			}
			if (i === this.reals.length - 1 && i > 0) {
				txt_real += " et ";
			} else if (i > 0) {
				txt_real += ", ";
			}
			txt_real += '<span class="a" onclick="search_reals([[\'' + this.reals[i] + '\', true]]);">' + this.reals[i] + '</span>';
		}

		return txt_real;
	};

	this.acteursToTxt = function () {
		if (this.acteurs == null) {
			return ' avec ' + sc_error_HTML;
		}

		var txt_act = "";
		if (this.acteurs.length > 0) {
			txt_act += ' avec ';
			for (var i = 0; i < this.acteurs.length; i++) {
				if (i === this.acteurs.length - 1 && i > 0) {
					txt_act += " et ";
				} else if (i > 0) {
					txt_act += ", ";
				}
				txt_act += '<span class="a" onclick="search_acteurs([[\'' + this.acteurs[i] + '\', true]]);">' + this.acteurs[i] + '</span>';
			}
		}
		return txt_act;
	};

	this.tagsToTxt = function () {
		if (this.tags == null) {
			return sc_error_HTML;
		}

		var txt_tags = "";
		for (var i = 0; i < this.tags.length; i++) {
			txt_tags += '<span class="tag gog-btn" onclick="search_tags([[\'' + this.tags[i] + '\', true]]);">' + this.tags[i] + '</span>\n';
		}
		return txt_tags;
	};

	this.nbrSaisonsToTxt = function () {
		return this.type1 === "serie"
				? '<div class="o-saisons"><strong>' + this.nbr_saisons + '</strong> saison' + (this.nbr_saisons > 1 ? "s" : "") + '</div>'
				: '';
	};

	this.languesToTxt = function (langues) {
		if (arguments.length === 0) {
			var langues = this.langues;
		}
		var txt_langues = "";
		for (var i = 0; i < langues.length; i++) {
			txt_langues += '<span class="label label-default">' + langues[i] + '</span>';
		}
		return txt_langues;
	};

	this.subToTxt = function (sub) {
		if (arguments.length === 0) {
			var sub = this.sub;
		}
		var txt_sub = "";
		for (var i = 0; i < sub.length; i++) {
			txt_sub += '<span class="label label-default">' + sub[i] + '</span>';
		}
		return txt_sub;
	};

	this.hassagaToTxt = function () {
		if (this.has_saga == null) {
			return '<div class="o-saga gog-btn gog-active">' + sc_error_HTML + '</div>';
		}
		return this.has_saga ? '<div class="o-saga gog-btn gog-active">Saga</div>' : '';
	};

	this.paysToTxt = function () {

		var txt_pays = "Pays d'origine: ";
		if (this.pays == null || this.pays === '')
			return txt_pays + sc_error_HTML;
		for (var i = 0; i < this.pays.length; i++) {
			if (this.pays[i].trim() === "") {
				continue;
			}
			if (i > 0) {
				txt_pays += ", ";
			}
			txt_pays += '<a href="#">' + this.pays[i] + '</a>';
		}

		return txt_pays;
	};

	this.dureeToTxt = function () {
		var txt;
		if (this.duree == null) {
			txt = sc_error_HTML;
		} else {
			txt = (this.duree.h === 0 ? '' : this.duree.h + 'h ') + this.duree.m + 'm';
		}
		return '<span class="glyphicon glyphicon-time"></span> ' + txt;
	};

	this.trailerToTxt = function () {
		if (this.trailer == null) {
			return sc_error_HTML;
		}
		if (this.trailer === '')
			return '';
		return '<iframe src="' + this.trailer + '" allowfullscreen=""></iframe>';
	};

	this.videosToTxt = function () {
		var videos_txt = '';
		for (var i = 0; i < this.videos.length; i++) {
			s = this.videos[i];

			var saison_txt = "";
			if (s['isset']) {
				for (var j = 0; j < s['paths'].length; j++) {
					saison_txt += '<div class="video gog-btn gog-active" onclick="vlc(\'' + s['paths'][j] + '\')">\n\
								<span class="fui-play"></span>\n\
								<div class="video-path">\n\
								' + s['paths'][j].split('/').reverse()[0] + '\n\
								</div>\n\
							</div>\n';
				}
			} else {
				saison_txt = '<div class="video no-isset"><span class="glyphicon glyphicon-floppy-remove o-no-file"></span>' + s['paths'][0] + '</div>';
			}

			videos_txt += '\n\
					<div class="saison module" ' + (this.videos.length === 1 ? "style='display:bloc;width:100%'" : "") + '>\n\
						<span class="fui-folder a gog-btn explorer" data-path="' + s['paths'][0] + '"></span>\n\
						' + (this.type1 === 'film' ? "" : '<div class="saison-titre">Saison ' + s["nom-saison"] + '</div>') + '\n\
						<div class="o-langues">\n\
							<span class="glyphicon glyphicon-volume-up"></span>\n\
							' + this.languesToTxt(s['langues']) + '\n\
						</div>\n\
						<div class="o-sub">\n\
							<span class="glyphicon glyphicon-subtitles"></span>\n\
							' + this.subToTxt(s['sub']) + '\n\
						</div>\n\
						<div class="list-video">\n\
							' + saison_txt + '\n\
						</div>\n\
					</div>';
		}
		return videos_txt;
	};
}

function initVitrine() {
	$('#fiche-param-form').on('submit', function (e) {
		e.preventDefault();
		modifierFiche($(this));
	});
}

function loadall() {
	collection = [];
	sagas = [];
	is_loadAll = true;
	$("#load_btn").hide();
	$('.content-header-top-title').show();
	$("#content-body").html("");
	$('.load-oeuvre').addClass('load');

	myPost("index.php", {m: "items", f: "init"}, function (data) {
		if (data === false) {
			errorAlert("Le fichier de données de la bibliothèque semble inaccessible. Vérifiez vos paramètres de chemins.");
		}
		$('.nbr_oeuvres').html('0/');
		$('.nbr_total_oeuvres').html(data.length);
		load(0, data);
	}, "json");
}

function load(i, tab) {
	if (tab[i] == null) {
		$('.nbr_oeuvres').html('');
		$('.load-oeuvre').removeClass('load');
		if (cfg['sc_cache']['sauf_notes']) {
			loadNotesForAll(profile.connected);
		}
		return;
	}
	loadOeuvre(tab[i], function () {
		$('.nbr_oeuvres').html((i + 1) + '/');
		load(i + 1, tab);
	});
}

function loadOeuvre(id, func) {
	myPost("index.php", {m: "items", f: "load", id: id}, function (data) {
		loadItem(data);
		if (func) {
			func();
		}
	}, "json");
}

function loadItem(item) {
	var oeuvre = new Oeuvre(item);
	var isset = collection[oeuvre.id] != null;
	collection[oeuvre.id] = oeuvre;
	addOeuvre(oeuvre);
	if (!isset) {
		add_options(oeuvre);
	}
}

function addOeuvre(o) {
	if (!oeuvreRespectSearch(o)) {
		if (o != null)
			console.debug(o);
		return false;
	}

	var txt_file_isset = o.file_isset ? 'hide' : '';

	var content = '<div id="oeuvre-' + o.id + '" class="oeuvre smooth ' + o.typesToTxt() + ' fadein">\n\
			<div class="o-real">\n\
				' + o.realToTxt() + '' + o.acteursToTxt() + '\n\
			</div>\n\
			<div class="o-tags">' + o.tagsToTxt() + '</div>\n\
			' + o.hassagaToTxt() + '\n\
			<span class="glyphicon glyphicon-floppy-remove o-no-file ' + txt_file_isset + '"></span>\n\
			<div class="o-notes">\n\
				<div class="o-note-glob loadable">' + o.moyenneToTxt() + '</div>\n\
				<div class="o-manote loadable l-white">' + o.maNoteToTxt() + '</div>\n\
			</div>\n\
			<div class="o-clickable clickable" onclick="toFiche(' + o.id + ')">\n\
				<div class="o-affiche">\n\
					<img src="' + o.afficheToTxt() + '"/>\n\
				</div>\n\
				<div class="o-nom">' + o.titreToTxt() + '</div>\n\
				<div class="o-annee small">' + o.anneeToTxt() + '</div>\n\
				<br/>\n\
				' + o.nbrSaisonsToTxt() + '\n\
				<div class="o-duree">' + o.dureeToTxt() + '</div>\n\
				<div class="o-langues">\n\
					<span class="glyphicon glyphicon-volume-up"></span>\n\
					' + o.languesToTxt() + '\n\
				</div>\n\
				<div class="o-sub">\n\
					<span class="glyphicon glyphicon-subtitles"></span>\n\
					' + o.subToTxt() + '\n\
				</div>\n\
				<div class="o-types">\n\
					' + o.type2ToTxt() + '\n\
					<div class="o-type label">\n\
						' + o.type1 + '\n\
					</div>\n\
				</div>\n\
				<div class="o-border">\n\
				</div>\n\
			</div>\n\
		</div>';

	if ($('#oeuvre-' + o.id).length > 0) {
		$('#oeuvre-' + o.id).replaceWith(content);
	} else {
		$("#content-body").append(content);
	}
	return true;
}

function toVitrine() {
	smooth_hide($('#fiche'));
	smooth_show($('#content'));
}

function toFiche(id) {
	smooth_hide($('#content'));
	smooth_show($('#fiche'));

	var prev = $('#oeuvre-' + id).prev();
	var next = $('#oeuvre-' + id).next();

	if (prev.length === 0) {
		$('.fiche-toleft').hide();
	} else {
		$('.fiche-toleft').show();
		var prev_id = $(prev).attr('id').split('-')[1];
		$('.fiche-toleft').attr('onclick', 'toFiche(' + prev_id + ');')
		$('.fiche-toleft-titre').text($(prev).find('.o-nom').html());
	}

	if (next.length === 0) {
		$('.fiche-toright').hide();
	} else {
		$('.fiche-toright').show();
		var next_id = $(next).attr('id').split('-')[1];
		$('.fiche-toright').attr('onclick', 'toFiche(' + next_id + ');')
		$('.fiche-toright-titre').text($(next).find('.o-nom').html());
	}

	if (id === fiche_id) {
		return;
	}
	fiche_id = id;
	var o = collection[id];

	console.debug(o);

	$('#fiche-param-liensc').val(o.lien_sc);
	$('#fiche-param-content').html('');

	var params = [], head;
	if (o.isFilm()) {
		params.push(['', o.path, o.langues, o.sub]);
	} else {
		for (var i = 0; i < o.videos.length; i++) {
			var nom_saison, path, langues, sub;
			nom_saison = o.videos[i]['nom-saison'];
			path = o.path[i];
			langues = o.videos[i].langues;
			sub = o.videos[i].sub;
			params.push([nom_saison, path, langues, sub]);
		}
	}
	for (var i = 0; i < params.length; i++) {
		$('#fiche-param-content').append('<table class="gog-table">\n\
				<tbody>\n\
					' + (o.isFilm() ? '' : ('<tr><th>Saison ' + params[i][0] + '</th><th></th></tr>')) + '\n\
					<tr><td class="gog-td-label">Langues</td>\n\
						<td><input type="text" name="langues[]" class="gog-input" required value="' + params[i][2] + '"></td>\n\
					</tr>\n\
					<tr><td class="gog-td-label">Sous-titres</td>\n\
						<td><input type="text" name="sub[]" class="gog-input" value="' + params[i][3] + '"></td>\n\
					</tr>\n\
					<tr><td class="gog-td-label">Chemin</td>\n\
						<td><input type="text" name="path[]" class="gog-input" required value="' + params[i][1] + '"></td>\n\
					</tr>\n\
				</tbody>\n\
			</table>');
	}

	$('#fiche-back').hide();
	$('#fiche').removeClass("o-serie");
	$('#fiche').removeClass("o-film");
	$('#fiche').removeClass("o-anime");
	$('#fiche .o-type2').html("");
	$('#fiche .saga-body').html("");

	$('#fiche').addClass(o.typesToTxt());
	$("#fiche .title-main-text").html(o.titreToTxt());
	$("#fiche .title-main .annee").html(o.anneeToTxt());
	$("#fiche .o-type").html(o.type1);
	$("#fiche .o-type2").html(o.type2);
	$("#fiche .affiche").attr("src", o.afficheToTxt());
	$("#fiche .reals").html(o.realToTxt());
	$("#fiche .acteurs").html(o.acteursToTxt());
	$("#fiche .duree").html(o.dureeToTxt());
	$("#fiche .nbr_saisons").html(o.nbrSaisonsToTxt());
	$("#fiche .o-note-glob").html(o.moyenneToTxt());
	$("#fiche .o-manote").html(o.maNoteToTxt());
	$("#fiche .o-tags").html(o.tagsToTxt());

	$("#fiche .title-main .redirect-sc").hide();
	$("#fiche .saga-head .redirect-sc").hide();
	$('#fiche .synopsis').show();
	if (o.has_saga != null && o.has_saga) {
		$("#fiche .saga").show();
	} else {
		$("#fiche .saga").hide();
	}

	loadDetails(o);
}

function loadDetails(o) {
	var isFilm = o.type1 === 'film';

	$('#fiche .title-sec').html(o.titre_sec);
//	$('#fiche .title-sec').removeClass('load');

	$('#fiche .pays').html(o.paysToTxt());
//	$('#fiche .pays').removeClass('load');

	if (o.synopsis === '') {
		$('#fiche .synopsis').hide();
	} else {
		$('#fiche .synopsis .contenu').html(o.synopsis);
	}
//	$('#fiche .synopsis').removeClass('load');

	$('#fiche .title-main .redirect-sc').attr("href", o.lien_sc);
	$('#fiche .title-main .redirect-sc').show();
	if (o.has_saga) {
		setFicheSaga(o.saga);
//		var post = true;
//		for (var i = 0; i < sagas.length; i++) {
//			for (var j = 0; (sagas[i] != null) && (j < sagas[i]['content'].length); j++) {
//				if (sagas[i]['content'][j]['b_id'] === o.id) {
//					setFicheSaga(o.sagas);
//					j = sagas[i]['content'].length;
//					i = sagas.length;
//					post = false;
//				}
//			}
//		}
//		if (post) {
//			myPost("index.php", {m: "items", f: "details", id: o.id, params: ["saga"]}, function (data) {
//				sagas.push(data["saga"]);
//				setFicheSaga(data["saga"]);
//			}, "json");
//		}
	}
	$("#fiche .visionnage").html(o.videosToTxt());
	initExplorer();
	$('#fiche .visionnage-box').removeClass('load');
	if (o.back_affiche.length > 0) {
		$('#fiche-back').attr("src", o.back_affiche);
		$('#fiche-back').show();
	}
	if (o.trailer === '') {
		$('#fiche .trailer-box').hide();
	} else {
		$('#fiche .trailer-box .trailer').html(o.trailerToTxt());
		$('#fiche .trailer-box').removeClass('load');
		$('#fiche .trailer-box').show();
	}
}

//function loadAndApply(item, param, func) {
//	if (item[param] == null) {
//		myPost("index.php", {m: "items", f: "details", id: item['id'], params: [param]}, function (data) {
//			item[param] = (data[param] == null ? '' : data[param]);
//			if (item['id'] === fiche_id) {
//				loadAndApply(item, param, func);
//			}
//		}, "json");
//	} else {
//		func(item[param]);
//	}
//}

//function loadAndApplyNotFiche(item, param, func) {
//	if (item[param] == null) {
//		myPost("index.php", {m: "items", f: "details", id: item['id'], params: [param]}, function (data) {
//			item[param] = (data[param] == null ? '' : data[param]);
//			loadAndApplyNotFiche(item, param, func);
//		}, "json");
//	} else {
//		func(item[param]);
//	}
//}

function setFicheSaga(saga) {
	$('#fiche .saga-head .saga-head-txt').html(saga["titre"]['text']);
	$('#fiche .saga-head .redirect-sc').attr("href", saga["titre"]['lien']);
	$('#fiche .saga-head .redirect-sc').show();

	var o, affiche, isset, type, type2, clickable, titre, annee, real, border, redirect;
	for (var i = 0; i < saga['content'].length; i++) {
		o = collection[saga['content'][i]['b_id']];
		isset = o != null;
		affiche = saga['content'][i]['affiche'];
		type = isset ? 'o-' + o.type1 : '';
		type2 = (isset && o.type2.length) ? 'o-' + o.type2 : '';
		clickable = isset ? 'class="clickable" onclick="toFiche(' + o.id + ')"' : '';
		titre = isset ? o.titre : saga['content'][i]['titre'];
		annee = isset ? o.annee : '';
		real = isset ? o.realToTxt(o.reals) : '';
		border = isset ? '<div class="o-border"></div>' : '';
		redirect = isset ? '' : ' <a href="' + saga['content'][i]['url'] + '" target="_blank" class="fui-export redirect-sc"></a>';
		$('#fiche .saga-body').append('<div class="saga-item ' + type + ' ' + type2 + ' ' + (isset ? '' : 'no-isset') + '">\n\
															<div ' + clickable + '>\n\
																<img class="saga-affiche" src="' + affiche + '"/>\n\
																<div class="saga-text">\n\
																	<div class="saga-text-titre">\n\
																		' + titre + redirect + '\n\
																	</div>\n\
																	<div class="saga-text-annee">\n\
																		' + annee + '\n\
																	</div>\n\
																</div>\n\
																' + border + '\n\
															</div>\n\
															<div class="saga-text-real">\n\
																' + real + '\n\
															</div>\n\
														</div>');

	}
	$('#fiche .saga').removeClass('load');
}

function actualiserFiche() {
	var id = fiche_id;
	$('#fiche .fiche-actu').addClass('load');
	myPost("index.php", {m: "items", f: "actu", id: id}, function (data) {
		loadItem(data);
		fiche_id = -1;
		toFiche(id);
		$('#fiche .fiche-actu').removeClass('load');
	}, "json");
}

function supprimerFiche() {
	var id = fiche_id;
	$('#fiche-supp-form .gog-btn-big').addClass('load');
	myPost("index.php", {m: "items", f: "supp", id: id}, function (data) {
		smooth_hide($('#fiche-supp-modal'));
		$('#oeuvre-' + id).remove();
		collection.splice(id, 1);
		fiche_id = -1;
		toVitrine();
		$('#fiche-supp-form .gog-btn-big').removeClass('load');
	}, "json");
}

function modifierFiche(form) {
	var packet = $(form).serialize();

	var id = fiche_id;
	$('#fiche-param-form .gog-btn-big').addClass('load');
	myPost("index.php", {m: "items", f: "modif", id: id, p: packet}, function (data) {
		smooth_hide($('#fiche-param-modal'));
		loadItem(data);
		fiche_id = -1;
		toFiche(id);
		$('#fiche-param-form .gog-btn-big').removeClass('load');
	}, "json");
}