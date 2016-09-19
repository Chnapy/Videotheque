<?php

use thcolin\SensCritiqueAPI\Models\Oeuvre;
use thcolin\SensCritiqueAPI\Exceptions\RedirectException;
use jyggen\Curl\Exception\CurlErrorException;
use thcolin\SensCritiqueAPI\Models\Artwork;

function getOfflineData($item) {
	return getAllParams($item, array(
		'id',
		'type1',
		'langues',
		'sub',
		'lien_sc',
		'nbr_saisons',
		'path',
		'videos',
		'file_isset',
//		'is_connecte'	//it's not offline !
	));
}

function getDetailsData($item) {
	return getAllParams($item, array(
		'titre',
		'titre_sec',
		'annee',
		'duree',
		'tags',
		'reals',
		'acteurs',
		'pays',
		'synopsis'
	));
}

function getFrontData($item) {
	return getAllParams($item, array(
		'type2',
		'trailer',
		'has_saga',
		'saga',
		'affiche',
		'back_affiche',
		'notemoy',
		'manote'
	));
}

if (!BIBLIO::$loaded) {
	echo json_encode(false);
	exit();
}

switch ($_POST['f']) {
	case "load":
		load();
		break;
	case "offline":
		$id = intval($_POST['id']);
		$item = Oeuvre::constructOeuvre(BIBLIO::getById($id));
		echo json_encode(getOfflineData($item));
		break;
	case "details":
	case "front":
		getDataFromString($_POST['f'], intval($_POST['id']));
		break;
	case "init":
		echo json_encode(BIBLIO::getAllID());
		break;
}

function getDataFromString($f, $id) {
	$json = BIBLIO::getById($id);
	$item = Oeuvre::constructOeuvre($json);
	switch ($f) {
		case "front":
			$data = getFrontData($item);
			break;
		case "details":
			$data = getDetailsData($item);
			break;
		case "offline":
			$data = getOfflineData($item);
			break;
		default:
			return;
	}
	if (CFG::$cfg['sc_cache']['active']) {
		$json['sc_cache'] = array_merge($json['sc_cache'], $data);
		$json['sc_cache']['last_load'] = time();
		BIBLIO::writeById($id, $json);
	}
	echo json_encode($data);
}

function details() {
	$id = intval($_POST['id']);
	$params = $_POST['params'];
	echo json_encode(getDetails($id, $params));
}

function load() {
	$id = intval($_POST['id']);
	$json = BIBLIO::getById($id);
	$item = Oeuvre::constructOeuvre($json);

	if (CFG::$cfg['sc_cache']['active']) {
		if (!isset($json['sc_cache']['titre'])) {
			$json['sc_cache'] = getDetailsData($item) + getFrontData($item);
			$json['sc_cache']['last_load'] = time();
			BIBLIO::writeById($id, $json);
		}
		$arr = $json['sc_cache'] + getOfflineData($item);
	} else {
		$arr = getOfflineData($item) + getDetailsData($item) + getFrontData($item);
	}
//	var_dump($arr);
	echo json_encode($arr);
}

function getDetails($id, $params) {
	$item = Oeuvre::constructOeuvre(BIBLIO::getById($id));
	return getAllParams($item, $params);
}

function getNext($id) {
	$i = 0;
	while (isset(BIBLIO::$biblio[$i]) && $id > BIBLIO::$biblio[$i]['id']) {
		$i++;
	}
	if (!isset(BIBLIO::$biblio[$i])) {
		return false;
	}

	$item = Oeuvre::constructOeuvre(BIBLIO::$biblio[$i]);
	return getOfflineData($item) + getDetailsData($item) + getFrontData($item);
}

function getAllParams($item, $params) {
	$paramValues = array();
	for ($i = 0; $i < count($params); $i++) {
		try {
			$paramValues[$params[$i]] = getParamValue($item, $params[$i]);
		} catch (CurlErrorException $ex) {
			
		} catch (RedirectException $ex) {
			
		}
	}
	return $paramValues;
}

function getParamValue($item, $param) {
	switch ($param) {
		case "id":
			return $item->getJsonId();
		case "affiche":
			return $item->getAffiche();
		case "back_affiche":
			return $item->getBackAffiche();
		case "titre":
			return $item->getTitle();
		case "titre_sec":
			return $item->getTitle(Artwork::TITLE_ORIGINAL);
		case "annee":
			return $item->getYear();
		case "type1":
			return $item->getType();
		case "pays":
			return $item->getCountries();
		case "synopsis":
			return $item->getStoryline();
		case "reals":
			return $item->getDirectors();
		case "acteurs":
			return $item->getActors();
		case "nbr_saisons":
			return $item->getNbrSaisons();
		case "langues":
			return $item->getLangues();
		case "sub":
			return $item->getSub();
		case "tags":
			return $item->getGenres();
		case "has_saga":
			return $item->hasSaga();
		case "saga":
			return $item->getSaga();
		case "duree":
			return $item->getDuration();
		case "notemoy":
			return $item->getNoteMoyenne();
		case "manote":
			return $item->getMaNote();
		case "videos":
			return $item->getVideos();
		case "lien_sc":
			return dirname($item->getUrl());
		case "type2":
			return $item->getType2();
		case "trailer":
			return $item->getTrailer();
		case "is_connecte":
			return SC::getClient()->isConnecte();
		case "file_isset":
			return $item->isPathOk();
		case "path":
			return $item->getPath();
	}
}
