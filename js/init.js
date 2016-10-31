
/* global cfg */

function onLoad() {
	init();
	ajax_init();
	sc_init();
	recherche_init();
	form_init();
	initVitrine();

	if (cfg['first_use']) {
		firstInit();
	}
	if (cfg['load_auto']) {
		loadall();
	}
}

function init() {
	$(':radio').radiocheck();
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();

	$('#ipsc').on('input', function () {
		checkLienSC($(this).val());
	});

	$(document).click(function (e) {
		$(".gog-modal").each(function () {
			if ($(this).hasClass('hide2')) {
				return;
			}
			var cible = $(this);
			var container;
			if ($(this).is('.module')) {
				container = $(this);
			} else {
				container = $(this).find(".module");
			}
			var close = $(container).find('.gog-modal-close');
			if ((!container.is(e.target) && container.has(e.target).length === 0 && !$(this).is('#first-modal')) || (close.is(e.target) || close.has(e.target).length > 0)) {
				smooth_hide(cible);
			}
		});
	});

	$(document).click(function (e) {
		var cible, dropdown;
		$('.dropdown-back').each(function () {
			cible = $(this).find('.dropdown-back-value, .dropdown-back-content');
			if ((cible.is(e.target) || $(cible).find($(e.target)).length) && !$(this).hasClass('expanded')) {
				$(this).addClass('expanded');
			} else if ($(this).hasClass('expanded')) {
				$(this).removeClass('expanded');
				dropdown = $(this);
				$(this).find('.dropdown-back-item').each(function () {
					if (($(this).is(e.target) || $(this).find($(e.target)).length) || $(this).find('*').is(e.target)) {
						$(dropdown).find('.dropdown-back-value').html($(this).html());
						try {
							window[$(dropdown).data('function')]($(this).data('value'));
						} catch (e) {
							console.error("Lancement de fonction dropdown impossible: '" + $(dropdown).data('function') + "'");
						}
						return;
					}
				});
			}
		});
		$('.filtre-dropdown').each(function () {
			cible = $(this);
			close = $(this).find('.fui-cross');
			if ((cible.is(e.target) || $(cible).find($(e.target)).length)
					&& $(this).hasClass('is-contracted') && $(this).find('.filtre-item').length > 0
					&& !close.is(e.target) && !$(close).find($(e.target)).length) {
				$(this).removeClass('is-contracted');
			} else if (!$(this).hasClass('is-contracted')) {
				if (!cible.is(e.target) && !$(cible).find($(e.target)).length) {
					$(this).addClass('is-contracted');
				}
			}
		});
	});

	$('.gog-checkbox').click(function () {
		if ($(this).hasClass('is-selected')) {
			$(this).removeClass('is-selected');
		} else {
			$(this).addClass('is-selected');
		}
		if ($(this).find('input[type="checkbox"]').length > 0) {
			$(this).find('input[type="checkbox"]').prop('checked', $(this).hasClass('is-selected'));
		}
	});

	$('.gog-radio-group').each(function () {
		var group = this;
		$(this).find('.gog-radio').click(function () {
			if (!$(this).hasClass('is-selected')) {
				$(group).find('.gog-radio input[type="radio"]').prop('checked', false);
				$(group).find('.gog-radio').removeClass('is-selected');
				$(this).addClass('is-selected');
				if ($(this).find('input[type="radio"]').length > 0) {
					$(this).find('input[type="radio"]').prop('checked', true);
				}
			}
		});
	});

	$('#form_scan').on('submit', function (e) {
		e.preventDefault();
		if ($('#form_scan input[name=sc]').val().length <= 0
				|| $('#form_scan input[name=path]').val().length <= 0) {
			return;
		}

		send($(this));
	});

	initFiltre();
	initExplorer();
}

function initFiltre() {
	$('.filtre-dropdown').each(function () {
		var dd = $(this);
		var fct = $(this).data('function');
		var all_items = $(this).find('.filtre-item');
		$(this).find('.filtre-group').each(function () {
			var items = $(this).find('.filtre-item');
			var group = $(this);
			$(items).each(function () {
				$(this).off('click');
				$(this).click(function () {
//					console.debug($(this));
					if ($(this).hasClass('is-selected')) {
						if ($(this).find('.gog-checkbox').length > 0) {
							$(this).removeClass('is-selected');
						}
					} else {
						if ($(this).find('.gog-radio').length > 0) {
							var items_unselect;
							if ($(this).hasClass('filtre-item-sub')) {
								items_unselect = $(group).find('.filtre-item-sub');
							} else {
								items_unselect = $(dd).find('.filtre-item:not(.filtre-item-sub)');
							}
							$(items_unselect).each(function () {
								if ($(this).find('.gog-radio').length > 0) {
									$(this).removeClass('is-selected');
								}
							});
						}
						$(this).addClass('is-selected');
					}
					var ret = [], bool, nbr_selec = 0;
					$(all_items).each(function () {
						bool = $(this).hasClass('is-selected');
						if (bool)
							nbr_selec++;
						ret.push([$(this).data('value'), bool]);
					});
					if (nbr_selec === 0)
						$(dd).removeClass('is-selected');
					else if (!$(dd).hasClass('is-selected'))
						$(dd).addClass('is-selected');
					window[fct](ret);
				});
			});
		});
		$(this).find('.fui-cross').off('click');
		$(this).find('.fui-cross').click(function (e) {
			$(all_items).removeClass('is-selected');
			$(dd).removeClass('is-selected');
			var ret = [];
			$(all_items).each(function () {
				ret.push([$(this).data('value'), $(this).hasClass('is-selected')]);
			});
			window[fct](ret);
		});
	});
}

function initExplorer() {
	$('.explorer').each(function () {
		$(this).off('click');
		$(this).click(function () {
			myPost("index.php", {m: "init", f: "open_dir", path: $(this).data('path')}, function (data) {
				if (data.trim() == 'false') {
//					errorAlert("Ouverture du gestionnaire de fichier impossible. Vérifiez vos paramètres de chemins.");
				}
			});
		});
	});
}

var scanResult = [];
var selectId = -1;
var loadHTML = '<span class="spinner"></span>';

function scan() {
	$('#scan_btn').prop('disabled', true);
	$('#scan_btn').addClass('load');
	$('#table_scan').hide();
	$('#scanfini').hide();
	$('#scanfini .scan-error-files').hide();
	$('#table_body').html("");
	$('#scanfini .scan-error-other').html("");
	if ($('#scanner-modal').hasClass('show-error')) {
		showHideScan();
	}
	myPost("index.php", {m: "scanner", f: "scan"}, function (data) {
		$('#scan_btn').prop('disabled', false);
		$('#scan_btn').removeClass('load');
		$('#scanfini').show();
		if (!data.success) {
			$('#scanfini .scan-error-other').text('Scan de la bibliothèque impossible. Le chemin est-il correct ? A vérifier <a onclick="smooth_show($(\'#param-modal\'));">dans vos paramètres</a>.');
			return;
		}

		if (data.item.length > 0) {
			$('#table_scan').show();
		} else {
			$('#nonew').show();
		}

		if (data['failed'].length) {
			$('#scanfini .scan-error-files-nbr').html(data['failed'].length);
			$('#scanfini .scan-error-files').show();
		}

		var i, d, dir, lastIndex;
		for (i = 0; i < data['failed'].length; i++) {
			d = data['failed'][i];
			if(d == null)
				continue;
			var item = [];
			item["path"] = d;
			try {
				item["name"] = d.substr((d.lastIndexOf('/') + 1));
				item["ext"] = d.substr((d.lastIndexOf('.') + 1));
			} catch (e) {
				item["name"] = '?';
				item["ext"] = '?';
			}
			lastIndex = item['path'].lastIndexOf("/");
			dir = item['path'].substring(0, lastIndex);
			$('#table_body').append(" \n\
				<tr class='scan-item-error'>\n\
				<td><label class='checkbox'><input disabled type='radio' name='cb_item' data-toggle='radio' class='custom-radio'></label></td>\n\
				<td class='error'><span class='gog-table-title'>" + item['name'] + "</span><span class='gog-table-details'>" + item['path'] + "</span></td>\n\
				<td class='error'><span class='gog-table-title'>" + item['ext'].toUpperCase() + "</span></td>\n\
				<td><span class='fui-folder a gog-btn explorer' data-path='" + dir + "'></span></td>\n\
</tr> \n\
");
		}
		for (i = 0; i < data['item'].length; i++) {
			d = data['item'][i];
			if(d == null)
				continue;
			var item = [];
			item["id"] = i;
			item["path"] = d;
			item["name"] = d.substr((d.lastIndexOf('/') + 1));
			item["ext"] = d.substr((d.lastIndexOf('.') + 1));
			scanResult.push(item);
			lastIndex = item['path'].lastIndexOf("/");
			dir = item['path'].substring(0, lastIndex);

			$('#table_body').append(" \n\
				<tr id='item" + item["id"] + "'>\n\
				<td><label class='checkbox' for='r" + item["id"] + "'><input id='r" + item["id"] + "' type='radio' name='cb_item' onchange='checke(\"" + item["path"].replace(/'/g, '&apos;').replace(/"/g, '&quot;') + "\");' data-toggle='radio' class='custom-radio'></label></td>\n\
				<td><label for='r" + item["id"] + "'><span class='gog-table-title'>" + item['name'] + "</span><span class='gog-table-details'>" + item['path'] + "</span></label></td>\n\
				<td><label for='r" + item["id"] + "'><span class='gog-table-title'>" + item['ext'].toUpperCase() + "</span></label></td>\n\
				<td><span class='fui-folder a gog-btn explorer' data-path='" + dir + "'></span></td>\n\
</tr> \n\
");
		}

		$(':radio').radiocheck();
		initExplorer();

//		$('#table_body tr').click(function () {
//			var radio = $(this).find('td input:radio');
//			console.debug($(radio).attr('disabled'));
//			if ($(radio).attr('disabled') == null)
//				$(radio).radiocheck('check');
//		});

	}, "json");
}

function showHideScan() {
	if ($('#scanner-modal').hasClass('show-error')) {
		$('#scanner-modal').removeClass('show-error');
	} else {
		$('#scanner-modal').addClass('show-error');
	}
}

function send(form) {
	var packet = $(form).serialize();

	$('#form_scan input[type=submit]').prop("disabled", true);
	myPost("index.php", {m: "scanner", f: "ajout", p: packet}, function (data) {

		if (data.success) {
			$('#form_scan input[name=path]').val("");
			$('#form_scan .small').val("");
			$('#form_scan').hide();
			$('#control_scan').hide();
			scan();
			if (is_loadAll) {
				loadOeuvre(data.id);
			}
		} else {
			errorAlert("Ajout échoué");
		}
		$('#form_scan input[type=submit]').prop("disabled", false);
	}, "json");
}

function getItemById(id) {
	for (var i = 0; i < scanResult.length; i++) {
		if (scanResult[i]["id"] === id)
			return scanResult[i];
	}
	return false;
}

function checke(path) {
	$('#form_scan input[name=path]').val(path);
	$('#form_scan .small').html(path.replace(/^.*[\\\/]/, ''));
	$('#form_scan').show();
	$('#control_scan').show();
}

function smooth_hide(item) {
	$(item).addClass("hide2");
	$(item).removeClass("show2");
	setTimeout(function () {
		$(item).hide();
	}, 400);
}

function smooth_show(item) {
	$(item).removeClass('hide');
	$(item).show();
	setTimeout(function () {
		$(item).removeClass("hide2");
	}, 0);
}

function checkLienSC(val) {
	if (val == null)
		return;

	if (val.indexOf("/serie/") >= 0) {
		$('#seriepart').show();
	} else {
		$('#seriepart').hide();
	}

}