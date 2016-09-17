<?php

namespace thcolin\SensCritiqueAPI\Models;

use thcolin\SensCritiqueAPI\Exceptions\DocumentParsingException;

class TVShow extends Oeuvre {

	protected $saisons;

	function __construct($json) {
		parent::__construct($json);

		$this->saisons = array();

		foreach ($json['saisons'] as $k => $v) {
			$this->saisons[$k] = array(
				"nom-saison" => $k,
				"path" => $v['path'],
				"langues" => $v['langues'],
				"sub" => $v['sub']
			);
			foreach ($v['langues'] as $l) {
				$l = trim($l);
				if (!in_array($l, $this->langues)) {
					$this->langues[] = $l;
				}
			}
			foreach ($v['sub'] as $s) {
				$s = trim($s);
				if (!in_array($s, $this->sub)) {
					$this->sub[] = $s;
				}
			}
		}
	}

	public function getNbrSaisons() {
		return count($this->saisons);
	}

	public function isSerie() {
		return true;
	}

	public function isPathOk() {
		foreach ($this->saisons as $s) {
			if (!is_dir($s['path'])) {
				return false;
			}
		}
		return true;
	}

	public function getVideos() {
		$ret = array();

		foreach ($this->saisons as $s) {
			$isset = $this->isPathOk();
			$ret[] = array(
				"nom-saison" => $s['nom-saison'],
				"langues" => $s['langues'],
				"sub" => $s['sub'],
				"paths" => $isset ? self::getAllPaths($s['path']) : $s['path'],
				"isset" => $isset
			);
		}

		return $ret;
	}

}
