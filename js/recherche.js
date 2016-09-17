

var search_options = {
	reals: [],
	acteurs: [],
	langues: [],
	sub: [],
	tags: [],
	type: [],
	pays: [],
	annee: [],
	duree: [],
	notemoy: [],
	manote: [],
	saga: []
};
var search_params = {
	titre: '',
	reals: [],
	acteurs: [],
	langues: [],
	sub: [],
	tags: [],
	type: [],
	pays: [],
	annee: [],
	duree: [],
	notemoy: [],
	manote: [],
	saga: []
};

var timeoutInput;

function recherche_init() {
	$('#search-input').keydown(function () {
		clearTimeout(timeoutInput);
		$('#filtre .filtre-search-box').removeClass('load');
	});
	$('#search-input').keyup(function () {
		$('#filtre .filtre-search-box').addClass('load');
		search_params.titre = $('#search-input').val();
		timeoutInput = setTimeout(function () {
			rechercher();
			$('#filtre .filtre-search-box').removeClass('load');
		}, 500);
		if ($('#search-input').val().length > 0) {
			$('#filtre .filtre-search-box').addClass('can-clear');
		} else {
			$('#filtre .filtre-search-box').removeClass('can-clear');
		}
	});
	$('#filtre .filtre-search-box .fui-cross-circle').click(function () {
		$('#search-input').val('');
		$('#filtre .filtre-search-box').removeClass('can-clear');
		$('#search-input').focus();
		search_params.titre = '';
		rechercher();
	});

	$('#content').scroll(function () {
		filtre_relocate();
	});
	filtre_relocate();
}

function isScrolledIntoView(elem) {
	var docViewTop = $(window).scrollTop();
	var docViewBottom = docViewTop + $(window).height();

	var elemTop = $(elem).offset().top;
	var elemBottom = elemTop + $(elem).height();

	return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

function filtre_show(hide) {
	var arr = [];
	$('#filtre .filtre-dropdowns-container .filtre').each(function () {
		arr.push([this, $(this).is(':visible') && isScrolledIntoView(this)]);
	});
	for (var i = 0; i < arr.length; i++) {
		if (arr[i][1]) {
			if (hide) {
				$(arr[i][0]).addClass('left-hide');
			}
		} else {
			if (hide) {
				$(arr[i][0]).removeClass('left-hide');
			} else {
				$(arr[i][0]).addClass('left-hide');
			}
		}
	}

	if (hide) {
		if ($('#filtre-show').hasClass('is-right')) {
			$('#filtre-show').removeClass('is-right');
		} else {
			$('#filtre-show').addClass('is-right');
		}
	}
}

var filtre_height;

function filtre_relocate() {
	var classe;
	switch (cfg['filtre_pos']) {
		case 0:
			classe = 'stay-top';
			$('#content').removeClass('stay-left');
			break;
		case 1:
			classe = 'stay-left';
			$('#content').removeClass('stay-top');
			break;
		case 2:
			$('#content').removeClass('stay-left');
			$('#content').removeClass('stay-top');
			return;
		default:
			return;
	}
	var window_top = $(window).scrollTop();
	var div_top = $('#filtre-anchor').offset().top;
//	console.debug(window_top);
//	console.debug(div_top);
	if (window_top > div_top) {
		if (filtre_height == null) {
			filtre_height = $('#filtre').outerHeight();
		}
		$('#content').addClass(classe);
		$('#filtre-anchor').height(filtre_height);
		filtre_show(false);
	} else {
		$('#content').removeClass(classe);
		$('#filtre-anchor').height(0);
	}
}

//Recherche inclusive
function rechercher() {
	var resultat = [];
//	console.debug(collection);
	for (var i = 0; i < collection.length; i++) {
		o = collection[i];
		resultat.push(o);
	}

	$("#content-body").html("");
	for (var i = 0; i < resultat.length; i++) {
		if (addOeuvre(resultat[i])) {
			console.log('OK' + i);
		}
	}
	$('.nbr_total_oeuvres').html($('#content-body .oeuvre').length);
}

function oeuvreRespectSearch(o) {
	if (o != null
			&& arrayContainsValues(o.reals, search_params.reals)
			&& arrayContainsValues(o.acteurs, search_params.acteurs)
			&& arrayContainsValues(o.langues, search_params.langues)
			&& arrayContainsValues(o.sub, search_params.sub)
			&& (arrayContainsValues(o.type1, search_params.type) || arrayContainsValues(o.type2, search_params.type))
			&& arrayContainsValues(o.pays, search_params.pays)
			&& arrayContainsValues(o.tags, search_params.tags)
			&& checkAnnee(o.annee)
			&& checkManote(o.manote)
			&& checkNotemoy(o.notemoy)
			&& checkDuree(o.duree)
			&& searchTitre(o.titre)) {
		return true;
	}
	return false;
}

function arrayContainsValues(arraySrc, arraySearch) {
	if (arraySearch.length === 0)
		return true;
	if (arraySrc.constructor !== Array)
		arraySrc = [arraySrc];
	for (var i = 0; i < arraySearch.length; i++) {
		if (arraySrc.indexOf(arraySearch[i]) !== -1)
			return true;
	}
	return false;
}

function add_options(o) {
	generic_add_option('reals', o);
	generic_add_option('acteurs', o);
	generic_add_option('langues', o);
	generic_add_option('sub', o);
	generic_add_option('tags', o);
	generic_add_option('pays', o);
	add_option_annee(o.annee);
	initFiltre();
}

function add_option_annee(annee) {
	var id_str = getAnneeIDandString(annee);
	if (search_options.annee.indexOf(id_str.id) === -1) {
		search_options.annee.push(id_str.id);
		$('#filtre-annee .filtre-group').append('<div class="filtre-item" data-value="' + id_str.id + '">\n\
										<i class="gog-checkbox"></i><span>' + id_str.string + '</span>\n\
									</div>');
	}
}

function search_reals(values) {
	generic_search('reals', values);
}

function search_acteurs(values) {
	generic_search('acteurs', values);
}

function search_langues(values) {
	generic_search('langues', values);
}

function search_sub(values) {
	generic_search('sub', values);
}

function search_tags(values) {
	generic_search('tags', values);
}

function search_annee(values) {
	generic_search('annee', values);
}

function search_manote(values) {
	generic_search('manote', values);
}

function search_notemoy(values) {
	generic_search('notemoy', values);
}

function search_type(values) {
	generic_search('type', values);
}

function search_pays(values) {
	generic_search('pays', values);
}

function search_duree(values) {
	generic_search('duree', values);
}

function generic_search(option, values) {
	search_params[option] = [];
	$(values).each(function () {
		if ($(this)[1]) {
			search_params[option].push($(this)[0]);
			$('#filtre-' + option).find('.filtre-item[data-value="' + $(this)[0] + '"]').addClass('is-selected');
		}
	});
	if (search_params[option].length > 0) {
		$('#filtre-' + option).addClass('is-selected');
	}
	toVitrine();
	rechercher();
}

function generic_add_option(option, o) {
	for (var i = 0; i < o[option].length; i++) {
		if (search_options[option].indexOf(o[option][i]) === -1) {
			search_options[option].push(o[option][i]);
			$('#filtre-' + option + ' .filtre-group').append('<div class="filtre-item" data-value="' + o[option][i] + '">\n\
										<i class="gog-checkbox"></i><span>' + o[option][i] + '</span>\n\
									</div>');
		}
	}
}

function getAnneeIDandString(annee) {
	var id, toString;
	if (annee > 2009) {
		id = 0;
		toString = 'Après 2009';
	} else if (annee > 1999) {
		id = 1;
		toString = '2000 - 2009';
	} else if (annee > 1989) {
		id = 2;
		toString = '1990 - 1999';
	} else if (annee > 1979) {
		id = 3;
		toString = '1980 - 1989';
	} else if (annee > 1969) {
		id = 4;
		toString = '1970 - 1979';
	} else {
		id = 5;
		toString = 'Avant 1970';
	}
	return {id: id, string: toString};
}

function checkAnnee(annee) {
	if (search_params.annee.length === 0)
		return true;
	var ret = false;
	for (var i = 0; i < search_params.annee.length; i++) {
		switch (search_params.annee[i]) {
			case 0:
				ret = ret || (annee > 2009);
				break;
			case 1:
				ret = ret || (annee <= 2009 && annee > 1999);
				break;
			case 2:
				ret = ret || (annee <= 1999 && annee > 1989);
				break;
			case 3:
				ret = ret || (annee <= 1989 && annee > 1979);
				break;
			case 4:
				ret = ret || (annee <= 1979 && annee > 1969);
				break;
			case 5:
				ret = ret || (annee <= 1969);
				break;
		}
	}
	return ret;
}

function checkManote(manote) {
	if ((search_params.manote.length <= 2) && (search_params.manote[0] == null || search_params.manote[0] === '>' || search_params.manote[0] === '<')
			&& (search_params.manote[1] == null || search_params.manote[1] === '>' || search_params.manote[1] === '<')) {
		return true;
	}
	var ret = true;
	for (var i = 0; i < search_params.manote.length; i++) {
		if (search_params.manote[i] === '>') {
			for (var j = 0; j < search_params.manote.length; j++) {
				if (i === j || search_params.manote[j].charAt(0) !== '>')
					continue;
				if (!manote.is_connecte || manote.note === 0)
					return false;
				ret = ret && (manote.note > parseInt(search_params.manote[j].substring(1)));
			}
		} else if (search_params.manote[i] === '<') {
			for (var j = 0; j < search_params.manote.length; j++) {
				if (i === j || search_params.manote[j].charAt(0) !== '<')
					continue;
				if (!manote.is_connecte || manote.note === 0)
					return false;
				ret = ret && (manote.note < parseInt(search_params.manote[j].substring(1)));
			}
		}
	}
	return ret;
}

function checkNotemoy(notemoy) {
	if ((search_params.manote.length <= 2) && (search_params.notemoy[0] == null || search_params.notemoy[0] === '>' || search_params.notemoy[0] === '<')
			&& (search_params.notemoy[1] == null || search_params.notemoy[1] === '>' || search_params.notemoy[1] === '<')) {
		return true;
	}
	var ret = true;
	for (var i = 0; i < search_params.notemoy.length; i++) {
		if (search_params.notemoy[i] === '>') {
			for (var j = 0; j < search_params.notemoy.length; j++) {
				if (i === j || search_params.notemoy[j].charAt(0) !== '>')
					continue;
				ret = ret && (notemoy > parseInt(search_params.notemoy[j].substring(1)));
			}
		} else if (search_params.notemoy[i] === '<') {
			for (var j = 0; j < search_params.notemoy.length; j++) {
				if (i === j || search_params.notemoy[j].charAt(0) !== '<')
					continue;
				ret = ret && (notemoy < parseInt(search_params.notemoy[j].substring(1)));
			}
		}
	}
	return ret;
}

function checkDuree(duree) {
	if (search_params.duree.length === 0)
		return true;
	var ret = false, tm = duree.h * 60 + duree.m;
	for (var i = 0; i < search_params.duree.length; i++) {
		switch (search_params.duree[i]) {
			case 0:
				ret = ret || (tm < 20);
				break;
			case 1:
				ret = ret || (tm >= 20 && tm < 40);
				break;
			case 2:
				ret = ret || (tm >= 40 && tm < 60);
				break;
			case 3:
				ret = ret || (tm >= 60 && tm < 90);
				break;
			case 4:
				ret = ret || (tm >= 90 && tm < 120);
				break;
			case 5:
				ret = ret || (tm >= 120 && tm < 150);
				break;
			case 6:
				ret = ret || (tm >= 150 && tm < 180);
				break;
			case 7:
				ret = ret || (tm >= 180);
				break;
		}
	}
	return ret;
}

function searchTitre(titre) {
	if (search_params.titre.trim().length === 0)
		return true;

	return titre.toLowerCase().trim().search(search_params.titre.toLowerCase().trim()) !== -1;
}