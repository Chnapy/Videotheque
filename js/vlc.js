

function vlc(path) {
	myPost("index.php", {m: "vlc", f: "start", path: path}, function (data) {
		if(!data) {
//			errorAlert("Ouverture de VLC impossible. Vérifiez vos paramètres de chemins.");
		}
	}, "json");
}