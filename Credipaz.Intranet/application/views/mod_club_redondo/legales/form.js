var verifyCallback = function (response) { $(".btnAccept").removeAttr("disabled").css({ "color": "black" }).removeClass("d-none"); };
function onloadCallback() { grecaptcha.render('widget', { 'sitekey': '6LenFa0UAAAAAEd_psRxiIPw6OLdqEafljEMJjeB', 'callback': verifyCallback }); };

$("body").off("click", ".btnAccept").on("click", ".btnAccept", function () {
	var _id_type_request = $(".id_type_request").val();
	var _dni = $(".DNI").val();
	var _telefono = $(".Telefono").val();
	var _motivo = $(".Motivo").val();
	if (_id_type_request == "") { alert("Debe ingresar Motivo de la consulta"); return false; }
	if (_dni == "") { alert("Debe ingresar DNI"); return false; }
	if (_telefono == "") { alert("Debe ingresar Teléfono de contacto"); return false; }
	if (_motivo == "") { alert("Debe ingresar Detalles"); return false; }

	$(".btnAction").hide();
	var _json = {
		"id_type_request": _id_type_request,
		"DNI": _dni,
		"telefono_contacto":_telefono,
		"motivo_consulta": _motivo,
		"importe_total": 0,
		"code_payment": "Sin cargo"
	}
	_json["function"] = "generatePaycode";
	_json["module"] = "mod_legal";
	_json["table"] = "charges_codes";
	_json["model"] = "charges_codes";
	_json["method"] = "api.backend/neocommand"; //method
	_AJAX.ExecuteDirect(_json, null).then(function (data) {
		setTimeout(function () { window.location.reload(); }, 5000);
		_FUNCTIONS.onShowInfo("La consulta legal ha sido procesada", "CONSULTA LEGAL");
	}).catch(function (err) {
		setTimeout(function () { window.location.reload(); }, 5000);
		_FUNCTIONS.onShowAlert(err.message, "CONSULTA LEGAL");
	});
});
