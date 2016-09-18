<?php

/**
 * SC.php
 *
 * 
 */
use thcolin\SensCritiqueAPI\Client;

class SC {

	private static $client;
	public static $loaded = false;

	static function init() {
		Client::init();

		self::$loaded = true;
	}

	public static function getClient() {
		if (!isset(self::$client)) {
			self::$client = new Client();
		}
		return self::$client;
	}

	static function requete() {
		self::getClient();
		switch ($_POST['f']) {
			case 'connexion':
				self::connexion();
				break;
			case 'deconnexion':
				self::deconnexion();
				break;
			case 'accessible':
				echo json_encode(self::$client->isSCAccessible());
				break;
			case 'connexion_info':
				self::getConnexionInfos();
				break;
		}
	}

	private static function getConnexionInfos() {
		if (SC::$client->isConnecte()) {
			$items = self::$client->getUserItems();
			echo json_encode(array(
				"is-connecte" => true,
				"pseudo" => $items['pseudo'],
				"avatar" => $items['avatar'],
				"lien" => $items['lien']
			));
		} else {
			echo json_encode(array(
				"is-connecte" => false
			));
		}
	}

	static function connexion() {
		$packet = $_POST['p'];

		$var = array();
		parse_str($packet, $var);

		$json = self::$client->connection($var['email'], $var['pass']);
		echo json_encode($json['json']);
	}

	static function deconnexion() {
		$json = self::$client->deconnection();
		echo json_encode($json);
	}

}
