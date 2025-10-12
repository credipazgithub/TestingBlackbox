var _mode = "";
var _callback = "";
var _referente = "";
var _track = "";

var verifyCallback = function (response) {
	$(".btnAccept").addClass("d-none"); 
	if (response != undefined) { $(".btnAccept").removeClass("d-none"); }
};
function onloadCallback() { grecaptcha.render('widget', { 'sitekey': '6LenFa0UAAAAAEd_psRxiIPw6OLdqEafljEMJjeB', 'callback': verifyCallback }); };

$("body").off("click", ".btnCancel").on("click", ".btnCancel", function () {
	if (_callback == "") { window.location.reload(); } else { window.location.href = _callback; }
});

$("body").off("click", ".btnAccept").on("click", ".btnAccept", function () {
	var _DocumentoSocio = $(".DocumentoSocio").val();

	$(".btnAction").hide();
	var _json = {
		"DocumentoSocio": _DocumentoSocio,
		"UsuarioAlta": _mode,
		"data_function": "upload",
		"function": "applicationMobileFunction",
        "referente": _referente,
		"track": _track,
	};
	_AJAX.UiSendExternal(_json).then(function (data) {
		alert(data.message);
		$(".btnCancel").click();
	}).catch(function (err) {
		alert(err.message);
		$(".btnCancel").click();
	});
});


$("body").off("drop", ".drop_zone").on("drop", ".drop_zone", function (ev) {
	var _ATTACH_LIMIT = 3;
	$(this).removeClass("drop_zone_over");
	ev.preventDefault();
	if (ev.originalEvent.dataTransfer.items) {
		for (var i = 0; i < ev.originalEvent.dataTransfer.items.length; i++) {
			if (ev.originalEvent.dataTransfer.items[i].kind === 'file') {
				var file = ev.originalEvent.dataTransfer.items[i].getAsFile();
				if (file.size > (_ATTACH_LIMIT * 1024000)) {
					$(".ls-images").append("<li class='list-group-item' style='padding:10px;'>¡No se adjuntará <span class='label label-danger'>" + file.name + "</span> porque excede los " + _ATTACH_LIMIT + "mb!</li>");
				} else {
					var reader = new FileReader();
					reader.onloadend = (function (data) { return function (evt) { $(".ls-images").append(_TOOLS.createFileItem(data.name, evt.target.result)); } })(ev.originalEvent.dataTransfer.items[i].getAsFile());
					reader.readAsDataURL(file);
				}
			}
		}
	} else {
		for (var i = 0; i < ev.originalEvent.dataTransfer.files.length; i++) {
			var file = ev.originalEvent.dataTransfer.files[i].getAsFile();
			if (file.size > (_ATTACH_LIMIT * 1024000)) {
				$(".ls-images").append("<li class='list-group-item' style='padding:10px;'>¡No se adjuntará <span class='label label-danger'>" + file.name + "</span> porque excede los " + _ATTACH_LIMIT + "mb!</li>");
			} else {
				var reader = new FileReader();
				reader.onloadend = (function (data) { return function (evt) { $(".ls-images").append(createFileItem(data.name, evt.target.result)); } })(ev.originalEvent.dataTransfer.files[i].getAsFile());
				reader.readAsDataURL(file);
			}
		}
	}
	if (ev.originalEvent.dataTransfer.items) {
		ev.originalEvent.dataTransfer.items.clear();
	} else {
		ev.originalEvent.dataTransfer.clearData();
	}
});
$("body").off("dragover", ".drop_zone").on("dragover", ".drop_zone", function (ev) {
	$(this).addClass("drop_zone_over");
	ev.preventDefault();
});
$("body").off("dragleave", ".drop_zone").on("dragleave", ".drop_zone", function (ev) {
	$(this).removeClass("drop_zone_over");
	ev.preventDefault();
});
$("body").off("click", ".btn-deattach").on("click", ".btn-deattach", function () {
	if (!confirm("¿Confirma la operación?")) { return false; }
	$("." + $(this).attr("data-id")).remove();
});


$("body").off("click", ".btn-send-reply").on("click", ".btn-send-reply", function () {
	$(".btn-send-reply").fadeOut("slow");
	var _body = $("#reply_body").val();
	var _names = "";
	var _attachs = "";
	$(".attach").each(function () {
		_names += $(this).attr("data-name") + "[NAME]";
		_attachs += $(this).attr("data-url") + "[FILE]";
	});
	var _json = {
		"id_operator_task": $("#id").val(),
		"email": $("#email").val(),
		"body": _body,
		"subject": "Contacto CREDIPAZ",
		"from": "info@credipaz.com",
		"names": _names,
		"attachs": _attachs
	};
	_AJAX.UiDirectEmail(_json).then(function (datajson) {
		if (datajson.status == "OK") {
			alert("¡La respuesta ha sido enviada!");
			$(".btn-send-reply").fadeIn("fast");
			$("#emailModal").modal("toggle");
		} else {
			alert(datajson.message);
			$(".btn-send-reply").fadeIn("fast");
		}
	}).catch(function (error) { alert(error.message); });
});
