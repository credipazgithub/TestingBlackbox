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
	var _dni = $(".DNI").val();
	var _sexo = $("#sexo:checked").val();
	if (_sexo == null) { _sexo = ""; }
	if (_DocumentoSocio == "") { alert("Debe ingresar su DNI"); return false; }
	if (_dni == "") { alert("Debe ingresar DNI del socio a presentar"); return false; }
	if (_sexo == "") { alert("Debe seleccionar Sexo del socio a presentar"); return false; }

	$(".btnAction").hide();
	var _json = {
		"DNI": _dni,
		"DocumentoSocio": _DocumentoSocio,
		"Sexo": _sexo,
		"IdCartera": 1,
		"IdSucursal": 100,
		"IdCanal": 2,
		"UsuarioAlta": _mode,
		"data_function": "presentar-club-redondo",
		"function": "applicationMobileFunction",
        "referente": _referente,
		"track": _track,
	};
	_AJAX.UiSendExternal(_json).then(function (data) {
		var _pixel = ("<img src='https://adrspain.go2cloud.org/SL4SZ?adv_sub=" + _track + "' width='1' height='1' />");
		if (_track != "") {
			$("body").append(_pixel);
			var _json = { "id": "0", "code": "CLUBREDONDO", "description": "PIXEL", "id_user": "2", "action": "ADHESION", "trace": _pixel, "id_rel": "0", "table_rel": "" };
			_AJAX.UiLogGeneral(_json).then(function (data) {
				if (data.status == "OK") {
					resolve(data);
				} else {
					throw data;
				}
			});
		}
		alert(data.message);
		$(".btnCancel").click();
	}).catch(function (err) {
		alert(err.message);
		$(".btnCancel").click();
	});
});
