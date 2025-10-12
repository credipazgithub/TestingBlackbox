var verifyCallback = function (response) { $(".btnAccept").removeAttr("disabled").css({ "color": "black" }).removeClass("d-none"); };
function onloadCallback() { grecaptcha.render('widget', { 'sitekey': '6LenFa0UAAAAAEd_psRxiIPw6OLdqEafljEMJjeB', 'callback': verifyCallback }); };

$("body").off("click", ".btnAccept").on("click", ".btnAccept", function () {
	var _nombre = $(".Nombre").val();
	var _apellido = $(".Apellido").val();
	var _dni = $(".DNI").val();
	var _sexo = $("#sexo:checked").val();
	if (_sexo == null) { _sexo = ""; }
	var _telefono = $(".Telefono").val();
	var _email = $(".Email").val();

	if (_nombre == "") { alert("Debe ingresar Nombre"); return false; }
	if (_apellido == "") { alert("Debe ingresar Apellido"); return false; }
	if (_dni == "") { alert("Debe ingresar DNI"); return false; }
	if (_sexo == "") { alert("Debe seleccionar Sexo"); return false; }
	if (_telefono == "") { alert("Debe ingresar su Nº de teléfono"); return false; }
	if (_email == "") { alert("Debe ingresar Email"); return false; }

	$(".btnAction").hide();
	var _json = {
		"data_function": "arrepentimiento-club-redondo",
		"function": "applicationMobileFunction",
		"Nombre": _nombre,
		"Apellido": _apellido,
		"DNI": _dni,
		"Sexo": _sexo,
		"Telefono": _telefono,
		"Email": _email
	}
	_AJAX.UiSendExternal(_json).then(function (data) {
		if (data.status == "OK") {
			setTimeout(function () { window.location.reload(); }, 5000);
			_FUNCTIONS.onShowInfo("La solicitud de arrepentimiento ha sido procesada", "SOLICITUD DE ARREPENTIMIENTO");
		} else {
			throw data;
		}
	}).catch(function (err) {
		alert(err.message);
	});
});
