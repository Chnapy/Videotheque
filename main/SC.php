<?php

/**
 * SC.php
 *
 * 
 */
use thcolin\SensCritiqueAPI\Client;

class SC {

	public static $client;
	public static $loaded = false;

	static function init() {
		self::$client = new Client();
		self::$client->init();

		self::$loaded = true;
	}

	static function requete() {
		switch ($_POST['f']) {
			case 'connexion':
				self::connexion();
				break;
			case 'deconnexion':
				self::deconnexion();
				break;
			case 'accessible':
				echo json_encode(self::$client->isSCAccessible());
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
