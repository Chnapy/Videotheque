<?php

namespace thcolin\SensCritiqueAPI\Models;

use thcolin\SensCritiqueAPI\Core\API;
use thcolin\SensCritiqueAPI\Models\Artwork;
use SC;

class Oeuvre {

	protected $json_id;
	protected $uri;
	protected $type;
	protected $langues;
	protected $sub;
	protected $path;
	protected $details;
	protected $front;

	function __construct($json) {
		$this->json_id = $json['id'];
		$this->type = $json['type'];
		$this->langues = array();
		$this->sub = array();
		$uri = $json['sc'];
		$explode = explode('/', $uri);

		if (!in_array('details', $explode)) {
			$explode[] = 'details';
		}

		$this->uri = implode('/', $explode);
	}

	static function constructOeuvre($json) {
		$class = self::getClassByURI($json['sc']);

		if (!$class) {
			throw new URIException();
		}

		return new $class($json);
	}

	static function getClassByURI($uri) {
		$explode = explode('/', $uri);

		switch ($explode[3]) {
			case 'film':
				return 'thcolin\SensCritiqueAPI\Models\Movie';
				break;
			case 'serie':
				return 'thcolin\SensCritiqueAPI\Models\TVShow';
				break;
			default:
				return null;
				break;
		}
	}

	function checkConnection() {
		$this->checkDetails();
		SC::getClient()->setConnecte(boolval($this->details->find('html body .lahe-header .lahe-topBar-userMenu .profileAction')));
	}

	static function getTrueUrl($url) {

		$str = "http://" . API::DOMAIN . "/";
		$strlen = strlen($str);

		if (substr($url, 0, $strlen) === $str) {
			$url = substr($url, $strlen);
		}

		return $url;
	}

	public function isSerie() {
		return false;
	}

	public function getTitle($type = Artwork::TITLE_DEFAULT) {
		$this->checkDetails();
		return $this->details->getTitle($type);
	}

	public function getYear() {
		$this->checkDetails();
		return $this->details->getYear();
	}

	public function getDuration() {
		$this->checkDetails();
		$d = explode(' ', $this->details->getDuration());
		$a = array();
		foreach ($d as $v) {
			$v = intval($v);
			if ($v !== 0) {
				$a[] = $v;
			}
		}
		if (count($a) === 1) {
			return array('h' => 0, 'm' => $a[0]);
		} else {
			return array('h' => $a[0], 'm' => $a[1]);
		}
	}

	public function getTrailer() {
		$this->checkFront();
		return $this->front->getTrailer();
	}

	public function getType2() {
		$this->checkFront();
		return $this->front->getType2();
	}

	public function hasSaga() {
		$this->checkFront();
		return $this->front->hasSaga();
	}

	public function getSaga() {
		$this->checkFront();
		return $this->front->getSaga($this->json_id);
	}

	public function getGenres($array = false) {
		$this->checkDetails();
		$tags = explode(', ', $this->details->getGenres($array));
		for ($i = 0; $i < count($tags); $i++) {
			if (trim($tags[$i]) === "") {
				unset($tags[$i]);
			}
		}
		return $tags;
	}

	public function getDirectors($array = false) {
		$this->checkDetails();
		$real = explode(', ', $this->details->getDirectors($array));
		for ($i = 0; $i < count($real); $i++) {
			if (trim($real[$i]) === "") {
				unset($real[$i]);
			}
		}
		return $real;
	}

	public function getActors($array = false) {
		$this->checkDetails();
		$acteurs = explode(', ', $this->details->getActors($array));
		for ($i = 0; $i < count($acteurs); $i++) {
			if (trim($acteurs[$i]) === "") {
				unset($acteurs[$i]);
			}
		}
		return $acteurs;
	}

	public function getCountries($array = false) {
		$this->checkDetails();
		$pays = explode(', ', $this->details->getCountries($array));
		for ($i = 0; $i < count($pays); $i++) {
			if (trim($pays[$i]) === "") {
				unset($pays[$i]);
			}
		}
		return $pays;
	}

	public function getAffiche() {
		$this->checkFront();
		return $this->front->getMainBigAffiche();
	}

	public function getBackAffiche() {
		$this->checkFront();
		return $this->front->getBackAffiche();
	}

	public function getNoteMoyenne() {
		$this->checkFront();
		return $this->front->getMoyenne();
	}

	public function getMaNote() {
		$this->checkFront();
		return $this->front->getMaNote();
	}

	public function getStoryline() {
		$this->checkDetails();
		return $this->details->getStoryline();
	}

	public function getNbrSaisons() {
		return 0;
	}

	public function isPathOk() {
		
	}

	public function getVideos() {
		//abstract
	}

	static function getAllPaths($dir) {

		if (!is_dir($dir)) {
			return false;
		}
		$dossierOuvert = opendir($dir);
		if ($dossierOuvert === FALSE) {
			return false;
		}

		$ret = array();

		while ($file = readdir($dossierOuvert)) {
			if ($file != ".." && $file != "." && !is_dir("$dir/$file") && strstr(mime_content_type("$dir/$file"), "video/")) {
				$ret[] = "$dir/$file";
			}
		}

		return $ret;
	}

	public function checkDetails() {
		if (!isset($this->details)) {
			$url_cut = Oeuvre::getTrueUrl($this->uri);
			$this->details = new Artwork($url_cut);
		}
	}

	public function checkFront() {
		if (!isset($this->front)) {
			$this->front = SC::getClient()->getOeuvreFront(dirname($this->uri));
		}
	}

	public function getJsonId() {
		return $this->json_id;
	}

	public function getType() {
		return $this->type;
	}

	public function getLangues() {
		return $this->langues;
	}

	public function getSub() {
		return $this->sub;
	}

	public function getPath() {
		return $this->path;
	}

	public function getUrl() {
		return $this->uri;
	}

}
