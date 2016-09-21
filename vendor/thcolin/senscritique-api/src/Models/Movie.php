<?php

namespace thcolin\SensCritiqueAPI\Models;

use thcolin\SensCritiqueAPI\Exceptions\DocumentParsingException;

class Movie extends Oeuvre {

	function __construct($json) {
		parent::__construct($json);
		$this->langues = $json['langues'];
		$this->sub = $json['sub'];
		$this->path = trim($json['path']);
	}

	public function isPathOk($path = null) {
		return is_file($this->path);
	}

	public function getVideos() {
		$isset = $this->isPathOk();
		return array(array(
				"nom-saison" => "",
				"langues" => $this->langues,
				"sub" => $this->sub,
				"paths" => array(
					$this->path
				),
				"isset" => $isset
		));
	}

}
