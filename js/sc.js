
var profile = {
	"sc_accessible": true,
	"connected": false,
	"pseudo": null,
	"avatar": null,
	"lien": null
};

function sc_init() {
	$('#sc-log-form').on('submit', function (e) {
		e.preventDefault();
		console.debug($(this));
		connexion($(this));
	});

	$(document).click(function (e) {
		var cible = $(".sc-log-form");
		var container = $(".sc-log.not-log");
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			smooth_hide(cible);
		} else {
			smooth_show(cible);
		}
	});

	checkConnexion();
	checkSCLoop();
}

function checkConnexion() {
	$('.sc-log-content').removeAttr('href');
	$(".sc-log").addClass('not-log');
	$('.deco-btn').hide();
	$('.sc-log-content').addClass('load');
	myPost("index.php", {m: "sc", f: "connexion_info"}, function (data) {
		$('.sc-log-content').removeClass('load');
		if (!data) {
			setConnected(false);
			return;
		}
		setConnected(data['is-connecte'], data['pseudo'], data['avatar'], data['lien']);
	}, "json"/*, function(jqXHR, textStatus, error) {
		isTimeoutError(jqXHR.responseText);
	}*/);
}

function setSCAccessible(sc_accessible) {
	if (profile['sc_accessible'] === sc_accessible) {
		return;
	}
	profile['sc_accessible'] = sc_accessible;
	console.log(sc_accessible);
	if (!sc_accessible) {
		$('.sc_not_accessible').show();
	}
}

function checkSCLoop() {
	if (cfg['sc_check_interval'] <= 0)
		return;
	myPost("index.php", {m: "sc", f: "accessible"}, function (data) {
		setSCAccessible(data);
		setTimeout(function () {
			checkSCLoop();
		}, cfg['sc_check_interval']);
	}, "json");
}

function connexion(form) {
	var packet = $(form).serialize();

	$('#sc-log-form button[type=submit]').addClass("load");
	$('#sc-log-form button[type=submit]').prop("disabled", true);
	$('.sc-log-form-error').html('');

	myPost("index.php", {m: "sc", f: "connexion", p: packet}, function (data) {
		if (!data) {
			$('.sc-log-form-error').html('Le serveur senscritique ne répond pas. Connexion échouée.');
		} else if (data['success']) {
			var items = data['items'];
			setConnected(true, items['pseudo'], items['avatar'], items['lien']);
		} else {
			$('.sc-log-form-error').html(data['message']);
		}
		$('#sc-log-form button[type=submit]').removeClass("load");
		$('#sc-log-form button[type=submit]').prop("disabled", false);
	}, "json");
}

function setConnected(is_connecte, pseudo, avatar, lien) {
	profile['connected'] = is_connecte;
	profile['pseudo'] = is_connecte ? pseudo : null;
	profile['avatar'] = is_connecte ? avatar : null;
	profile['lien'] = is_connecte ? lien : null;

	if (is_connecte) {
		smooth_hide(".sc-log-form");
		$('.sc-log-content').html('<img class="avatar" src="' + profile['avatar'] + '"/>\n\
<span class="pseudo">' + profile['pseudo'] + '</span>');
		$('.sc-log-content').attr('href', profile['lien']);
		$(".sc-log").removeClass('not-log');
		$('.deco-btn').show();

		for (var i = 0; i < collection.length; i++) {
			(function (o) {
				if (o == null) {
					return;
				}
				if (o.manote != null) {
					o.manote = null;
				}
				$('#oeuvre-' + o.id + ' .o-manote').addClass("load");
				myPost("index.php", {m: "items", f: "front", id: o.id}, function (data) {
					o.loadJSON(data);
					var txt = o.maNoteToTxt();
					$('#oeuvre-' + o['id'] + ' .o-manote').html(txt);
					$('#oeuvre-' + o['id'] + ' .o-manote').removeClass("load");
					if (fiche_id === o['id']) {
						$('#fiche .o-manote').html(txt);
					}
				}, "json");
			})(collection[i]);
		}

	} else {
		$('.sc-log-content').html("Se connecter");
		$('.sc-log-content').removeAttr('href');
		$(".sc-log").addClass('not-log');
		$('.deco-btn').hide();
		smooth_show(".sc-log-form");

		var o;
		for (var i = 0; i < collection.length; i++) {
			o = collection[i];
			if (o == null) {
				continue;
			}
			o.manote = {'is_connecte': false};
			var txt = o.maNoteToTxt();
			$('#oeuvre-' + o.id + ' .o-manote').html(txt);
			if (fiche_id === o.id) {
				$('#fiche .o-manote').html(txt);
			}
		}
	}
}

function deconnexion() {
	var exit_icn = $('.sc-log .deco-btn .gog-btn').html();
//	$('.sc-log .deco-btn .gog-btn').addClass('load');
	myPost("index.php", {m: "sc", f: "deconnexion"}, function (data) {
		if (data['success']) {
			setConnected(false);
		} else {
			errorAlert("La déconnexion semble avoir échouée. Actualisez pour vérifier.");
		}
//		$('.sc-log .deco-btn .gog-btn').removeClass('load');
	}, "json");
}