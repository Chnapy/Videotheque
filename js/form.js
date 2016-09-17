
var cfg;

function form_init() {
	$('#param-form, #vlc-form, #path-form, #config-form').each(function (e) {
		$(this).on('submit', function (e) {
			e.preventDefault();
			param_generic($(this));
		});
	});
}

function param_generic(form) {
	var packet = $(form).serialize();
	var btn_submit = $(form).find('button[type=submit]');
	$(btn_submit).addClass("load");
	$(btn_submit).prop("disabled", true);
	$(form).find('.param-form-error').html('');

	myPost("index.php", {m: "init", f: "params", p: packet}, function (data) {
		setParams(data);
		$(btn_submit).removeClass("load");
		$(btn_submit).prop("disabled", false);
		var btn_html = $(btn_submit).html();
		$(btn_submit).html("Modifications effectuées <span class='fui-check'></span>");
		setTimeout(function () {
			$(btn_submit).html(btn_html);
		}, 3000);
	}, "json");
}

function setParams(_cfg) {
	cfg = _cfg;
	console.debug(cfg);

	setAffichage(cfg['affichage']);
	setTri(cfg['tri']);

	if (!cfg['curseur_load']) {
		$('body').removeClass('wait');
	} else if ($.active > 0) {
		$('body').addClass('wait');
	}
}