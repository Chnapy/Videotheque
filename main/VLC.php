<?php

switch ($_POST['f']) {
	case "start":
		VLC::start();
		break;
}

class VLC {

	static function start() {
		$path = $_POST['path'];
		self::launch($path);
	}

	static function launch($video_path) {

		$vlc_path = CFG::parsePath(CFG::$cfg['vlc_path'], false);
		$video_path = CFG::parsePath($video_path, false);

		$cfg_params = CFG::$cfg['vlc_params'];
		$params = [];

		if ($cfg_params['fullscreen']) {
			$params[] = '-f';
		}

		$e = '"' . $vlc_path . '" "' . $video_path . '"';
		foreach ($params as $p) {
			$e .= ' ' . $p;
		}

//	echo $e . '\n';
		exec($e, $o, $r);
//	echo 'o: ';
//	var_dump($o);
//	echo 'r: ';
//	var_dump($r);
		echo json_encode($r === 0);
	}

}
