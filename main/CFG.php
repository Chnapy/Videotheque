<?php

/**
 * CFG.php
 *
 * 
 */
class CFG {

	public static $cfg;
	public static $loaded = false;
	public static $b_path;

	static function init() {
		$file = @file_get_contents(CFG_PATH);
		if (!$file) {
			$file = file_get_contents(CFG_DEFAULT_PATH);
			self::$cfg = json_decode($file, true);
			self::submit();
		} else {
			self::$cfg = json_decode($file, true);
			if (!self::$cfg || self::$cfg === NULL) {
				return;
			}
		}
		self::$b_path = self::$cfg['biblio_path'];
		self::$loaded = true;
	}

	static function write($key, $value) {
		CFG::$cfg[$key] = $value;
		return self::submit();
	}

	static function submit() {
		$fp = fopen(CFG_PATH, 'w');
		if ($fp === FALSE) {
			return false;
		}
		if (fwrite($fp, json_encode(self::$cfg)) === FALSE) {
			return false;
		}
		fclose($fp);

		return true;
	}

	static function parsePath($path, $remplace_backslash = true) {
		if ($remplace_backslash || self::isLinux()) {
			return str_replace('\\', '/', $path);
		}
		if (self::isWindows()) {
			return str_replace('/', '\\', $path);
		}
		return $path;
	}

	static function isWindows() {
		return PHP_OS === 'WINNT';
	}

	static function isLinux() {
		return PHP_OS === 'Linux';
	}

	static function toHTML() {
		$txt = file_get_contents(CFG_PATH);
		$txt = str_replace('{"', '{&#10;"', $txt);
		$txt = str_replace('&#10;}', '}', $txt);
		$txt = str_replace('}', '&#10;}', $txt);
		$txt = str_replace('[&#10;', '[', $txt);
		$txt = str_replace('[', '[&#10;', $txt);
		$txt = str_replace('&#10;]', ']', $txt);
		$txt = str_replace(']', '&#10;]', $txt);
		$txt = str_replace(',&#10;', ',', $txt);
		$txt = str_replace(',', ',&#10;', $txt);
		$txt = str_replace('":', '" : ', $txt);

		return $txt;
	}

	static function setAllContent($content, $cle) {
		if ($cle !== 'APPROUVE') {
			return false;
		}

		$fp = fopen(CFG_PATH, 'w');
		if ($fp === FALSE) {
			return false;
		}
		if (fwrite($fp, $content) === FALSE) {
			return false;
		}
		fclose($fp);

		self::$loaded = false;
		return self::init();
	}

}
