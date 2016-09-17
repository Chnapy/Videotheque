<?php

/**
 * BIBLIO.php
 *
 * 
 */
class BIBLIO {

	public static $biblio;
	public static $loaded = false;
	public static $last_id = 0;

	static function init() {
		if (!is_file(CFG::$cfg['biblio_cfg_path'])) {
			self::$biblio = array();
			self::submit();
		} else {
			$file = @file_get_contents(CFG::$cfg['biblio_cfg_path']);
			if (!$file) {
				self::$biblio = array();
				self::submit();
			} else {
				self::$biblio = json_decode($file, true);
				usort(self::$biblio, "self::cmp");

				if (!is_array(self::$biblio)) {
					return;
				}
				foreach (self::$biblio as $item) {
					if (is_numeric($item['id']) > 0 && $item['id'] >= self::$last_id) {
						self::$last_id = $item['id'] + 1;
					}
				}
			}
		}

		self::$loaded = true;
	}

	static function cmp($a, $b) {
		return ($a['id'] < $b['id']) ? -1 : 1;
	}

	static function getAllID() {
		$ret = array();
		for ($i = 0; $i < count(self::$biblio); $i++) {
			$ret[] = self::$biblio[$i]['id'];
		}
		return $ret;
	}

	static function getAllPath() {
		$ret = array();
		for ($i = 0; $i < count(self::$biblio); $i++) {
			switch (self::$biblio[$i]['type']) {
				case 'film':
					$ret[] = self::$biblio[$i]['path'];
					break;
				case 'serie':
					foreach (self::$biblio[$i]['saisons'] as $s) {
						$ret[] = $s['path'];
					}
					break;
			}
		}
		return $ret;
	}

	static function addFilm($path, $langues, $sub, $sc) {
		self::$biblio[] = array(
			'id' => self::$last_id,
			'type' => 'film',
			'path' => $path,
			'langues' => $langues,
			'sub' => $sub,
			'sc' => $sc);

		if (!self::submit()) {
			return false;
		}
		$id = self::$last_id;

		return array(
			"success" => true,
			"id" => $id
		);
	}

	static function addSerie($path, $langues, $sub, $sc, $saison) {
		if (is_file($path)) {
			$path = dirname($path);
		}
		if (realpath($path) === realpath(CFG::$cfg['biblio_cfg_path'])) {
			return false;
		}
		for ($i = 0; $i < count(self::$biblio); $i++) {
			if (self::$biblio[$i]['sc'] == $sc) {
				if (array_key_exists($saison, self::$biblio[$i]['saisons'])) {
					return false;
				}

				self::$biblio[$i]['saisons'][$saison] = array(
					'path' => $path,
					'langues' => $langues,
					'sub' => $sub
				);

				if (!self::submit()) {
					return false;
				}
				$id = self::$biblio[$i]['id'];
				return array(
					"success" => true,
					"id" => $id
				);
			}
		}

		self::$biblio[] = array(
			'id' => self::$last_id,
			'type' => 'serie',
			'sc' => $sc,
			'saisons' => array(
				$saison => array(
					'path' => $path,
					'langues' => $langues,
					'sub' => $sub
				)
		));

		if (!self::submit()) {
			return false;
		}
		$id = self::$last_id;

		return array(
			"success" => true,
			"id" => $id
		);
	}

	private static function submit() {
		$fp = fopen(CFG::$cfg['biblio_cfg_path'], 'w');
		if ($fp === FALSE) {
			return false;
		}
		if (fwrite($fp, json_encode(self::$biblio)) === FALSE) {
			return false;
		}
		fclose($fp);

		return true;
	}

	static function getById($id) {
		foreach (self::$biblio as $item) {
			if (intval($item['id']) === $id) {
				return $item;
			}
		}
		return false;
	}

}
