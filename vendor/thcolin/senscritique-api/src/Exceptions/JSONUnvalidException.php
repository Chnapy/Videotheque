<?php

namespace thcolin\SensCritiqueAPI\Exceptions;

use Exception;

class JSONUnvalidException extends Exception {
	
	private $json;
	
	function __construct($json) {
		$this->json = $json;
	}

	function getJSON() {
		return $this->json;
	}
	
}

?>
