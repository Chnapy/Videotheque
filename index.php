
<?php

use thcolin\SensCritiqueAPI\Exceptions\RedirectException;
use thcolin\SensCritiqueAPI\Exceptions\JSONUnvalidException;
use jyggen\Curl\Exception\CurlErrorException;

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'main/include.php';

CFG::init();
BIBLIO::init();
SC::init();

if (!isset($_POST['m']) || empty($_POST['m'])) {
	$m = "vitrine";
} else {
	$m = $_POST['m'];
}

try {
	switch ($m) {
		case "init":
			require_once 'init/init.php';
			exit();
		case "vitrine":
			beginHTML();
			require_once 'vitrine/vitrine.php';
			endHTML();
			exit();
		case "items":
			require_once 'vitrine/items.php';
			exit();
		case "sc":
			SC::requete();
			exit();
		case "vlc":
			require_once 'main/vlc.php';
			exit();
		default:
			exit();
	}
} catch (CurlErrorException $ex) {
	echo json_encode(false);
} catch (RedirectException $ex) {
	echo json_encode(false);
} catch (JSONUnvalidException $ex) {
	echo json_encode(false);
}

function beginHTML() {
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Ma vidéothèque</title>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			
			<link rel="icon" type="image/x-icon" href="img/favicon.ico">

			<link rel="stylesheet" href="css/bootstrap.min.css" />

			<link href="css/flat-ui.min.css" rel="stylesheet" type="text/css" />

			<link href="css/style.css" rel="stylesheet" type="text/css" />
			<link href="css/oeuvre.css" rel="stylesheet" type="text/css" />
			<link href="css/oeuvre_list.css" rel="stylesheet" type="text/css" />
			<link href="css/fiche.css" rel="stylesheet" type="text/css" />
		</head>
		<body>
			<?php
		}

		function endHTML() {
			?>
			<div id="alerts">
			</div>
			<script src="js/jquery-2.2.1.min.js"></script>
			<script src="js/flat-ui.min.js"></script>

			<script src="js/ajax.js"></script>
			<script src="js/alert.js"></script>
			<script src="init/init.js"></script>
			<script src="vitrine/vitrine.js"></script>
			<script src="js/first.js"></script>
			<script src="js/sc.js"></script>
			<script src="js/recherche.js"></script>
			<script src="js/affichage.js"></script>
			<script src="js/form.js"></script>
			<script src="js/vlc.js"></script>
		</body>
	</html>
	<?php
}