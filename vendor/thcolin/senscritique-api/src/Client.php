<?php

namespace thcolin\SensCritiqueAPI;

use thcolin\SensCritiqueAPI\Document;
use thcolin\SensCritiqueAPI\Core\Connection;
use thcolin\SensCritiqueAPI\Core\API;
use thcolin\SensCritiqueAPI\User\User;
use thcolin\SensCritiqueAPI\User\Listing;
use thcolin\SensCritiqueAPI\Models\Artwork;
use thcolin\SensCritiqueAPI\Models\Oeuvre;
use thcolin\SensCritiqueAPI\Exceptions\JSONUnvalidException;
use thcolin\SensCritiqueAPI\Exceptions\RedirectException;
use jyggen\Curl\Exception\CurlErrorException;
use CFG;

class Client {

	use Connection;

	static $OPTIONS_AJAX;
	static $OPTIONS_NORMAL;
	private $connecte;
	private $sc_accessible;
	private $doc;

	public static function init() {
		$cookie_path = CFG::$cfg['cookie_path'];

		if (!file_exists(realpath($cookie_path))) {
			touch($cookie_path);
		}

		self::$OPTIONS_AJAX = array(CURLOPT_COOKIEJAR => realpath($cookie_path),
			CURLOPT_COOKIEFILE => realpath($cookie_path),
			CURLOPT_COOKIESESSION => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array(
				'X-Requested-With:XMLHttpRequest'
			),
			CURLOPT_CONNECTTIMEOUT_MS => CFG::$cfg['curl_timeout']
		);

		self::$OPTIONS_NORMAL = array(CURLOPT_COOKIEJAR => realpath($cookie_path),
			CURLOPT_COOKIEFILE => realpath($cookie_path),
			CURLOPT_COOKIESESSION => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT_MS => CFG::$cfg['curl_timeout']
		);
	}
	
	public static function setTimeout($timeout_ms) {
		self::$OPTIONS_AJAX[CURLOPT_CONNECTTIMEOUT_MS] = $timeout_ms;
		self::$OPTIONS_NORMAL[CURLOPT_CONNECTTIMEOUT_MS] = $timeout_ms;
	}

	public function getUser($username) {
		$uri = $username;
		return new User($uri);
	}

	public function getList($id) {
		$uri = substr($this->api->getLocation('liste/Unkown/' . $id), 1);
		return new Listing($uri);
	}

	public function getOeuvre($id, $type) {
		if (!$this->connecte) {
			return false;
		}
		$uri = substr($this->api->getLocation($type . '/Unkown/' . $id), 1);
		return Artwork::constructObjectByURI($uri);
	}

	public function getFilm($id) {
		return $this->getOeuvre($id, 'film');
	}

	public function connection($email, $pass) {
		$post = array(
			"email" => $email,
			"pass" => $pass
		);

		try {
			$json = $this->api->getJSONByURIWithPOST('auth/login', $post, self::$OPTIONS_AJAX);
		} catch (JSONUnvalidException $e) {
			$json = $e->getJSON();
			if (is_null($json)) {
				throw $e;
			}
		}
		$this->connecte = $json["json"]["success"];

		if ($this->connecte) {
			$this->checkDoc();
//pseudo + avatar + lien_profile
			$json["json"]['items'] = $this->getUserItems();
		}

		return $json;
	}

	public function deconnection() {
		try {
			$this->api->getDocumentByURI('logout', self::$OPTIONS_NORMAL);
		} catch (RedirectException $e) {
			
		}
		unlink(CFG::$cfg['cookie_path']);
		return array("success" => !$this->isConnecte());
	}

	public function getUserItems() {
		$this->checkDoc();
		$profile = $this->doc->find('html body .lahe-header .lahe-topBar-userMenu .profileAction')[0];

		$pseudo = $profile->find('.lahe-userMenu-username')[0]->text();
		$avatar = $profile->find('.lahe-userMenu-avatar')[0]->getAttribute('src');
		$lien = 'http://' . API::DOMAIN . $profile->getAttribute('href');

		return array(
			'pseudo' => $pseudo,
			'avatar' => $avatar,
			'lien' => $lien
		);
	}

	public function isSCAccessible() {
		if (isset($this->sc_accessible)) {
			return $this->sc_accessible;
		}
		try {
			$this->checkDoc();
			$this->sc_accessible = true;
		} catch (CurlErrorException $ex) {
			$this->sc_accessible = false;
		} catch (RedirectException $ex) {
			$this->sc_accessible = false;
		}
		return $this->sc_accessible;
	}

	public function isConnecte() {
		if (isset($this->connecte)) {
			return $this->connecte;
		}
		if (!$this->isSCAccessible()) {
			$this->connecte = false;
		} else {
			$this->checkDoc();
			$this->connecte = boolval($this->doc->find('html body .lahe-header .lahe-topBar-userMenu .profileAction'));
		}
		return $this->connecte;
	}

	private function checkDoc() {
		if (!isset($this->doc)) {
			$this->doc = $this->api->getDocumentByURI('apropos', self::$OPTIONS_NORMAL);
		}
	}

	public function setConnecte($bool) {
		$this->connecte = $bool;
	}

	public function getAPI() {
		return $this->api;
	}

	public function getOeuvreFront($url) {

		$d = new \OeuvreFace(Oeuvre::getTrueUrl($url));
//		echo $d->getDocument();exit();
		return $d;
	}

}

?>
