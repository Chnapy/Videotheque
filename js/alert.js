


function myAlert(type, text, title) {
	if (arguments.length === 3) {
		myAlert(type, "<strong>" + title + "</strong> " + text);
		return;
	}
	switch (type) {
		case "success":
		case "warning":
		case "danger":
		case "info":
			break;
		default:
			myAlert("info", text);
			return;
	}
	$("#alerts").append('<div class="alert alert-' + type + ' alert-dismissible" role="alert" data-dismiss="alert">' +
			text +
			'</div>');
}

function errorAlert(text, code) {
	if (arguments.length === 2) {
		errorAlert(text + "\t(code " + code + ")");
		return;
	}
	myAlert("danger", text, "Erreur");
}

function inputAlert(type, title, fct, limit) {
//	if (modalAj || modalPref) {
//		return;
//	}

	$("#inputPopup .modal-title").text(title);
	$("#inputPopup .modal-footer input").first().attr("disabled", "true");
	$("#inputPopup .modal-body input").first().attr("type", type);
	$("#inputPopup .modal-body input").first().val("");
	if (limit === undefined) {
		$("#inputPopup .modal-body input").first().removeAttr("maxlength");
	} else {
		$("#inputPopup .modal-body input").first().attr("maxlength", limit);
	}
	$("#inputPopup .modal-body input").first().keyup(function () {
		if ($(this).val().length === 0) {
			$("#inputPopup .modal-footer input").first().attr("disabled", "true");
		} else {
			$("#inputPopup .modal-footer input").first().removeAttr("disabled");
		}
	});

	$('#form_inpopup').on('submit', function (e) {
		e.preventDefault();
		$("#inputPopup").modal("hide");
		fct($("#inputPopup .modal-body input").first().val());
		$("#inputPopup .modal-title").text("");
		$("#inputPopup .modal-body input").first().attr("type", "");
		$("#inputPopup .modal-footer input").first().attr("disabled", "true");
		$(this).off();
	});

	$("#inputPopup").modal("show");
	$("#inputPopup .modal-body input").first().focus();
}