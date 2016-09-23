<?php

use thcolin\SensCritiqueAPI\Client;

switch ($_POST['f']) {
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
			CFG::$cfg['sc_cache']['active'] = (isset($form['sc_cache_active']) && $form['sc_cache_active'] === 'on');
			CFG::$cfg['sc_cache']['sauf_notes'] = (isset($form['sc_cache_sauf_notes']) && $form['sc_cache_sauf_notes'] === 'on');
			CFG::$cfg['curseur_load'] = (isset($form['curseur_load']) && $form['curseur_load'] === 'on');
			if (isset($form['filtre_pos'])) {
				CFG::$cfg['filtre_pos'] = intval($form['filtre_pos']);
			}
			CFG::$cfg['bg_uni'] = (isset($form['bg_uni']) && $form['bg_uni'] === 'on');
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
