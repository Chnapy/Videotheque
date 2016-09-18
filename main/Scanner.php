<?php

switch ($_POST['f']) {
	case "scan":
		Scanner::scan();
		break;
	case "ajout":
		Scanner::ajout();
		break;
}

class Scanner {

	private static $list;
	private static $failed;
	private static $ignored;

	static function scan() {
		$src = CFG::$b_path;

		self::$list = [];
		self::$failed = [];
		self::$ignored = BIBLIO::getAllPath();

		$success = self::parcourDirectory($src);
		$ret = array(
			"success" => $success,
			"item" => self::$list,
			"failed" => self::$failed
		);

		echo json_encode($ret);
	}

	private static function parcourDirectory($dir) {
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
				if (self::isIgnored($path)) {
					continue;
				}

				if (is_dir($path)) {
					if (!self::parcourDirectory($path)) {
						return false;
					}
				} else {
					self::checkFile($path);
				}
			}
		}
		return true;
	}

	private static function isIgnored($path) {
		foreach (self::$ignored as $i) {
			if ($path == $i) {
				return true;
			}
		}
		return false;
	}

	private static function checkFile($file) {

		if (!realpath($file)) {
			self::$failed[] = $file;
			return false;
		}

		$mime = mime_content_type($file);
		if (!strstr($mime, "video/")) {
			return false;
		}

		array_push(self::$list, $file);
	}

	static function ajout() {
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

}
