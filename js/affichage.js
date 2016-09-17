
var affichage;
var tri;

function setAffichage(classe) {
	if (affichage != null && affichage === classe) {
		return;
	}
	$('#content').removeClass(affichage);
	affichage = classe;
	$('#content').addClass(affichage);
	switch (affichage) {
		case 'list1':
			$('#dropdown-affichage .dropdown-back-value').html('<span class="fui-list-columned"></span>');
			break;
		case 'list2':
			$('#dropdown-affichage .dropdown-back-value').html('<span class="fui-list-small-thumbnails"></span>');
			break;
		case 'list3':
			$('#dropdown-affichage .dropdown-back-value').html('<span class="fui-list-large-thumbnails"></span>');
			break;
		default:
			return;
	}
	myPost("index.php", {m: "init", f: "affichage", v: affichage}, function (data) {
	}, "json");
}

function setTri(_tri) {
	if (tri != null && tri === _tri) {
		return;
	}
	tri = _tri;
	switch (tri) {
		case 'id':
			$('#dropdown-tri .dropdown-back-value').html('Par ID');
			break;
		case 'titre':
			$('#dropdown-tri .dropdown-back-value').html('Par titre');
			break;
		case 'notemoy':
			$('#dropdown-tri .dropdown-back-value').html('Par note moyenne');
			break;
		case 'manote':
			$('#dropdown-tri .dropdown-back-value').html('Par ma note');
			break;
		case 'annee':
			$('#dropdown-tri .dropdown-back-value').html('Par année');
			break;
		case 'duree':
			$('#dropdown-tri .dropdown-back-value').html('Par durée');
			break;
		default:
			return;
	}
	myPost("index.php", {m: "init", f: "tri", v: tri}, function (data) {
	}, "json");
	
	var clone = collection.slice();
	clone.sort(getCompareFct());
//	console.debug(clone);

	$("#content-body").html("");
	for (var i = 0; i < clone.length; i++) {
		addOeuvre(clone[i]);
	}
}

function getCompareFct() {
	switch (tri) {
		case 'id':
			return compareById;
		case 'titre':
			return compareByTitre;
		case 'notemoy':
			return compareByNoteMoy;
		case 'manote':
			return compareByMaNote;
		case 'annee':
			return compareByAnnee;
		case 'duree':
			return compareByDuree;
	}
}

function compareById(a, b) {
	if (a.id < b.id)
		return -1;
	if (a.id > b.id)
		return 1;
	return 0;
}

function compareByTitre(a, b) {
	if (a.titre < b.titre)
		return -1;
	if (a.titre > b.titre)
		return 1;
	return 0;
}

function compareByNoteMoy(a, b) {
	a = parseFloat(a.notemoy), b = parseFloat(b.notemoy);
	if (a < b)
		return 1;
	if (a > b)
		return -1;
	return 0;
}

function compareByMaNote(a, b) {
	a = a.manote.note + (a.manote.coeur ? 0.05 : 0) + (a.manote.critique ? 0.01 : 0) + (a.manote.encours ? 0.01 : 0) + (a.manote.vu ? 0.01 : 0) + (a.manote.envie ? 0.01 : 0);
	b = b.manote.note + (b.manote.coeur ? 0.05 : 0) + (b.manote.critique ? 0.01 : 0) + (b.manote.encours ? 0.01 : 0) + (b.manote.vu ? 0.01 : 0) + (b.manote.envie ? 0.01 : 0);
	if ((a < b))
		return 1;
	if (a > b)
		return -1;
	return 0;
}

function compareByAnnee(a, b) {
	if (a.annee < b.annee)
		return 1;
	if (a.annee > b.annee)
		return -1;
	return 0;
}

function compareByDuree(a, b) {
	a = a.duree.h * 60 + a.duree.m;
	b = b.duree.h * 60 + b.duree.m;
	if (a < b)
		return 1;
	if (a > b)
		return -1;
	return 0;
}