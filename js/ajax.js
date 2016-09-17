
var error_message = "Une erreur s'est produite. En êtes-vous le fautif ? Probablement :)";

function ajax_init() {
	$(document).ajaxStart(function () {
		ajax_start();
	}).ajaxStop(function () {
		$('body').removeClass('wait');
	});

	if ($.active > 0) {
		ajax_start();
	}

	setInterval(function () {
		if ($.active === 0) {
			$('body').removeClass('wait');
		}
	}, 5000);
}

function ajax_start() {
//	console.log("start");
	if (cfg['curseur_load']) {
		$('body').addClass('wait');
	}
}

function myPost(url, items, func, type, fail) {

	if (arguments.length === 3) {
		$.post(url, items, function (data) {
			var sc_accessible = data !== false;
//			setSCAccessible(sc_accessible);
			func(data, sc_accessible);
		}).fail(function () {
			errorAlert(error_message);
			return;
		});
	} else if (arguments.length === 4) {
		myPost(url, items, func, type, function () {
			errorAlert(error_message);
			return;
		});
	} else {
		$.post(url, items, function (data) {
			var sc_accessible = data !== false;
//			setSCAccessible(sc_accessible);
			func(data, sc_accessible);
		}, type).fail(function () {
			fail();
		});
	}
}

function htmlentities(string, quote_style, charset, double_encode) {

	var hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style),
			symbol = '';
	string = string == null ? '' : string + '';

	if (!hash_map) {
		return false;
	}

	if (quote_style && quote_style === 'ENT_QUOTES') {
		hash_map["'"] = '&#039;';
	}

	if (!!double_encode || double_encode == null) {
		for (symbol in hash_map) {
			if (hash_map.hasOwnProperty(symbol)) {
				string = string.split(symbol)
						.join(hash_map[symbol]);
			}
		}
	} else {
		string = string.replace(/([\s\S]*?)(&(?:#\d+|#x[\da-f]+|[a-zA-Z][\da-z]*);|$)/g, function (ignore, text, entity) {
			for (symbol in hash_map) {
				if (hash_map.hasOwnProperty(symbol)) {
					text = text.split(symbol)
							.join(hash_map[symbol]);
				}
			}

			return text + entity;
		});
	}

	return string;
}