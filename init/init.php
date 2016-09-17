<?php

use thcolin\SensCritiqueAPI\Client;

switch ($_POST['f']) {
	case "scan":
		scan();
		break;
	case "ajout":
		ajout();
		break;
	case "open_dir":
		open();
		break;
	case "affichage":
		affichage();
		break;
	case "tri":
		tri();
		break;
	case "params":
		params();
		break;
}
exit();

function affichage() {
	$val = $_POST['v'];
	echo CFG::write('affichage', $val);
}

function tri() {
	$val = $_POST['v'];
	echo CFG::write('tri', $val);
}

function scan() {
	$src = CFG::$b_path;

	$ret = array(
		"success" => true,
		"item" => array(),
		"failed" => array()
	);

	global $list;
	$list = $ret['item'];

	global $failed;
	$failed = $ret['failed'];

	global $ignored;
	$ignored = BIBLIO::getAllPath();

	$ret['success'] = parcourDirectory($src);
//	if(!$ret['success']) {
//		$failed[] = 'Scan de la bibliothèque impossible. Le chemin est-il correct ? A vérifier dans les paramètres.';
//	}
	$ret['item'] = $list;
	$ret['failed'] = $failed;

	echo json_encode($ret);
}

function parcourDirectory($dir) {
	if (!is_dir($dir)) {
		return false;
	}
	$dossierOuvert = opendir($dir);
	if ($dossierOuvert === FALSE) {
		return false;
	}
	$path = null;
	while ($file = readdir($dossierOuvert)) {

		if ($file != ".." && $file != ".") {
			$path = CFG::parsePath("$dir/$file");
			if (isIgnored($path)) {
				continue;
			}

			if (is_dir($path)) {
				if (!parcourDirectory($path)) {
					return false;
				}
			} else {
				checkFile($path);
			}
		}
	}
	return true;
}

function isIgnored($path) {
	global $ignored;

	foreach ($ignored as $i) {
		if ($path == $i) {
			return true;
		}
	}
	return false;
}

function checkFile($file) {
	global $list;
	global $failed;

	if (!realpath($file)) {
		$failed[] = $file;
		return false;
	}

	$mime = mime_content_type($file);
	if (!strstr($mime, "video/")) {
		return false;
	}

	array_push($list, $file);
}

function ajout() {
	$packet = $_POST['p']; //path, langues, sub, sc, saison
	parse_str($packet);

	$path = CFG::parsePath($path);
	$langues = @split(",", str_replace(" ", "", $langues));
	$sub = @split(",", str_replace(" ", "", $sub));
	$sc = trim($sc);
	$type = explode('/', $sc)[3];

	switch ($type) {
		case "film":
			$ret = BIBLIO::addFilm($path, $langues, $sub, $sc);
			break;
		case "serie":
//			if (!is_numeric($saison)) {
//				$success = false;
//			} else {
			$ret = BIBLIO::addSerie($path, $langues, $sub, $sc, $saison);
//			}
			break;
		default:
			$ret = false;
			break;
	}

	if (!$ret) {
		echo json_encode(array(
			"success" => false
		));
	} else {
		echo json_encode($ret);
	}
}

function open() {
	$path = CFG::parsePath($_POST['path'], false);
	if (!is_dir($path)) {
		$path = dirname($path);
		if (!is_dir($path)) {
			echo json_encode(false);
			return;
		}
	}

	$out = [];
	$cmd = CFG::$cfg['explorer_path'] . ' ' . $path;
	exec($cmd, $out, $error);

	echo json_encode($error === 0);
}

function params() {

	parse_str($_POST['p'], $form);
//	var_dump($form);

	switch ($form['param']) {
		case 'param':
			CFG::$cfg['load_auto'] = (isset($form['load_auto']) && $form['load_auto'] === 'on');
			CFG::$cfg['sc_check_interval'] = intval($form['sc_interval']);
			CFG::$cfg['curl_timeout'] = intval($form['curl_timeout']);
			Client::setTimeout(CFG::$cfg['curl_timeout']);
			CFG::$cfg['curseur_load'] = (isset($form['curseur_load']) && $form['curseur_load'] === 'on');
			if (isset($form['filtre_pos'])) {
				CFG::$cfg['filtre_pos'] = intval($form['filtre_pos']);
			}
			CFG::$cfg['first_use'] = (isset($form['first_use']) && $form['first_use'] === 'on');
			break;
		case 'vlc':
			CFG::$cfg['vlc_params']['fullscreen'] = (isset($form['fullscreen']) && $form['fullscreen'] === 'on');
			CFG::$cfg['vlc_params']['play_auto'] = (isset($form['play_auto']) && $form['play_auto'] === 'on');
			CFG::$cfg['vlc_params']['volume'] = intval($form['volume']);
			CFG::$cfg['vlc_params']['crop'][0] = intval($form['crop0']);
			CFG::$cfg['vlc_params']['crop'][1] = intval($form['crop1']);
			CFG::$cfg['vlc_params']['crop'][2] = intval($form['crop2']);
			CFG::$cfg['vlc_params']['crop'][3] = intval($form['crop3']);
			break;
		case 'path':
			CFG::$cfg['biblio_path'] = CFG::parsePath($form['biblio_path']);
			CFG::$cfg['biblio_cfg_path'] = CFG::parsePath($form['biblio_cfg_path']);
			CFG::$cfg['vlc_path'] = CFG::parsePath($form['vlc_path']);
			CFG::$cfg['explorer_path'] = CFG::parsePath($form['explorer_path']);
			CFG::$cfg['cookie_path'] = CFG::parsePath($form['cookie_path']);
			break;
		case 'config':
			$content = $form['content'];
			if ($content != null && strlen(trim($content)) > 0) {
				CFG::setAllContent($content, 'APPROUVE');
			}
			break;
		default:
			return;
	}

	CFG::submit();

	echo json_encode(CFG::$cfg);
}
