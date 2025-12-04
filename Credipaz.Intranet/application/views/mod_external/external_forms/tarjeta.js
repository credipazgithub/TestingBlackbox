var _idEmpresa = "0";
var _idVendedor = 0;
var _auth = null;
var _new = false;

_FUNCTIONS.onTraerLookUp2("EstadoCivil")
	.then(function (data) { _TOOLS.loadCombo(data, { "target": "#sLKEstadoCivil", "selected": "-1", "id": "codigo", "description": "descripcion", "default": "[Seleccione]" }); })
_FUNCTIONS.onTraerLookUp2("Nacionalidad")
	.then(function (data) { _TOOLS.loadCombo(data, { "target": "#sLKNacionalidad", "selected": "-1", "id": "codigo", "description": "descripcion", "default": "[Seleccione]" }); })
_FUNCTIONS.onTraerLookUp2("Ocupacion")
	.then(function (data) { _TOOLS.loadCombo(data, { "target": "#sLKOcupacion", "selected": "-1", "id": "codigo", "description": "descripcion", "default": "[Seleccione]" }); })
_FUNCTIONS.onTraerLookUp2("Telediscado")
	.then(function (data) {
		_TOOLS.loadCombo(data, { "target": "#sDomiTETelediscado", "selected": "-1", "id": "codigo", "description": "descripcion", "default": "[Seleccione]" });
		$("#sDomiTETelediscado1").html($("#sDomiTETelediscado").html());
		$("#sDomiTETelediscado1").val("-1");
	})
_FUNCTIONS.onTraerLookUp2("Provincia")
	.then(function (data) {
		_TOOLS.loadCombo(data, { "target": "#sDomiPcia", "selected": "-1", "id": "codigo", "description": "descripcion", "default": "[Seleccione]" });
		$("#sDomiPcia1").html($("#sDomiPcia").html());
		$("#sDomiPcia1").val("-1");
	})
_FUNCTIONS.onTraerLookUp2("Localidad")
	.then(function (data) {
		_TOOLS.loadCombo(data, { "target": "#sLKDomiLocalidad", "selected": "-1", "id": "codigo", "description": "descripcion", "default": "[Seleccione]" });
		$("#sLKDomiLocalidad1").html($("#sLKDomiLocalidad").html());
		$("#sLKDomiLocalidad1").val("-1");
	})

_FUNCTIONS.onTraerLookUp2("RubroLaboral")
	.then(function (data) {
		_TOOLS.loadCombo(data, { "target": "#sLKRubroLaboral", "selected": "-1", "id": "codigo", "description": "descripcion", "default": "[Seleccione]" });
	})
_FUNCTIONS.onTraerLookUp2("TipoVivienda")
	.then(function (data) {
		_TOOLS.loadCombo(data, { "target": "#sLKTipoVivienda", "selected": "-1", "id": "codigo", "description": "descripcion", "default": "[Seleccione]" });
	})


$("body").off("click", ".btn-login").on("click", ".btn-login", function () {
	try {
		if (_TOOLS.validate(".validate", true)) {
			var _hide = $(this).attr("data-hide");
			var _show = $(this).attr("data-show");
			var _json = _TOOLS.getFormValues(".dbase", $(this));
			_AJAX._waiter = true;
			_AJAX.UiLoginComercializador(_json)
				.then(function (_auth) {
					$(".dataAuth").html("<span class='badge badge-warning' style='font-size:16px;'>Bienvenido/a <b>" + _auth.message[0].Nombre + "</b></span>");
					if (_auth.status == "OK") {
						if (_auth.message != null && _auth.message[0] != null && _auth.message[0].Id != null) {
							$(".nIDVendedor").val(_auth.message[0].Id);
							$(".nIDComercializadora").val(_auth.message[0].IdEmpresa);
							$(_hide).addClass("d-none");
							$(_show).removeClass("d-none");
						} else {
							_FUNCTIONS.onShowInfo("No se han autenticado las credenciales provistas", "Alerta");
							return false;
						}
					} else {
						throw _auth;
					}
				})
				.catch(function (error) {
					alert(error.message);
					throw error;
				});
		}
	}
	catch (rex) {
		_FUNCTIONS.onShowInfo("No se han autenticado las credenciales provistas", "Alerta");
	}
});
$("body").off("click", ".btnVerifyDni").on("click", ".btnVerifyDni", function () {
	try {
		var _ok = true;
		var _redirect = true;
		$(".info-verify").html("");
		if (!_TOOLS.checkLen("nCUIL", "CUIL")) { return false; }
		if (!_TOOLS.checkLen("sDomiTE", "Teléfono", 6)) { return false; }

		var _message = "Complete los datos requeridos para verificar";
		if (_TOOLS.validate(".validateInit", true)) {
			_AJAX._waiter = true;
			_AJAX.UiGetVerifySolicitudTarjeta(_TOOLS.getFormValues(".dbaseInit", $(this)))
				.then(function (_data) {
					if (_data.message[0].logica) {
						_message = "La solicitud ha sido pre aprobada.  Complete todos los datos solicitados.";
						$(".info-verify").removeClass("badge-warning").addClass("badge-success");
					} else {
						_ok = false;
						$(".info-verify").removeClass("badge-success").addClass("badge-warning");
						_message = "Aviso.  " + _data.message[0].mensaje;
						_FUNCTIONS.onShowInfo(_message, "Alerta");
					}
					if (_ok) {
						$(".off-init").removeClass("d-none");
						$(".laboral").addClass("d-none");
						$(".col-verify").remove();
						$(".nDoc").attr("disabled", true);
						$(".sSexo").attr("disabled", true);
						$(".sNombre").attr("disabled", true);
						$(".sEmail").attr("disabled", true);
						$(".nCUIL").attr("disabled", true);
						$(".sDomiTE").attr("disabled", true);
						$(".sDomiTETelediscado").attr("disabled", true);
					}
					$(".info-verify").html(_message);
				})
				.catch(function (error) {
					$(".info-verify").removeClass("badge-success").addClass("badge-warning");
					$(".info-verify").html(error.message).addClass("badge-danger");;
					throw error;
				});
		} else {
			$(".info-verify").html(_message).addClass("badge-info");
		}
	}
	catch (rex) {
		_FUNCTIONS.onShowInfo(rex.message, "Alerta");
	}
});
$("body").off("click", ".btnCancel").on("click", ".btnCancel", function () {
	//window.location.reload();
	history.back();
});
$("body").off("click", ".btnAccept").on("click", ".btnAccept", function () {
	if (_auth == "") { _auth = 0; }
	if (!_TOOLS.validate(".validateAdherir", true)) { return false; }
	var _json = _TOOLS.getFormValues(".dbaseAdherir", null);

	$(".btnAction").hide();
	_AJAX.UiSetEmitirTarjeta(_json).then(function (_data) {
		if (_new) {
			//Armado de parametros para entrar en modo edicion!
			redirectEdit(_data.Mensaje);
		} else {
			//Refrescar la página luego de grabar!
			window.location.reload();
		}
	}).catch(function (err) {
		alert(err.message);
	});
});
$("body").off("change", ".sLKOcupacion").on("change", ".sLKOcupacion", function () {
	$(".form-control").removeClass("is-invalid");
	switch (parseInt($(this).val())) {
		case 1: // Empleado
			$(".valLab").addClass("validateAdherir");
			$(".laboral").removeClass("d-none")
			break;
		default:
			$(".valLab").removeClass("validateAdherir");
			$(".laboral").addClass("d-none");
			break;
	}
});
$("body").off("click", ".btnDeleteAdicional").on("click", ".btnDeleteAdicional", function () {
	var _id = parseInt($(this).attr("data-id"));
	try {
		if (!confirm("Se marcará como baja el adicional seleccionado.  ¿Confirma la operación?")) { return false; }
		// Armar llamada para persistir la baja
		// definir el procedimiento y armar el stack hasta la base de datos
		$(".tr-" + _id).fadeOut("slow", function () { $(".tr-" + _id).remove(); })
	}
	catch (rex) {
		_FUNCTIONS.onShowInfo("No se ha podido procesar la baja", "Alerta");
	}
});
$("body").off("click", ".btnEditAdicional").on("click", ".btnEditAdicional", function () {
	var _id = parseInt($(this).attr("data-id"));
	var _codigo = parseInt($(this).attr("data-codigo"));
	var _username = $(this).attr("data-username");
	var _record = $(this).attr("data-record");
	try {
		_FUNCTIONS.onSetAdicionalTarjeta(_username, _id, _codigo, _record);
	}
	catch (rex) {
		_modo = "el alta";
		if (_idFamiliar != 0) { _modo = "la modificación"; }
		_FUNCTIONS.onShowInfo("No se ha podido procesar " + _modo, "Alerta");
	}
});
$("body").off("click", ".btnVerHistorialDePagos").on("click", ".btnVerHistorialDePagos", function () {
	_FUNCTIONS.onVerHistorialDePagos($(this));
});

function redirectEdit(_codigo) {
	var _path = window.location.pathname.split("/");
	var _param = { "user": $(".username").val(), "codigo": _codigo, "id_sucursal": parseInt($(".IDSucursal").val()) };
	window.location.href = (window.location.protocol + "//" + window.location.host + "/" + _path[1] + "/" + _path[2] + "/" + _TOOLS.utf8_to_b64(JSON.stringify(_param)));
}
function setData(_data) {
	if (_data[0].dFechaNac != "") { $(".dFechaNac").val(new Date(_data[0].dFechaNac).toISOString().split('T')[0]); }
	if (_data[0].dFechaIngreso1 != "") { $(".dFechaIngreso1").val(new Date(_data[0].dFechaIngreso1).toISOString().split('T')[0]); }

	_TOOLS.itemToControl(_data[0], "nDoc", "");
	_TOOLS.itemToControl(_data[0], "sSexo", "-1");
	_TOOLS.itemToControl(_data[0], "sNombre", "");
	_TOOLS.itemToControl(_data[0], "sEmail", "");
	_TOOLS.itemToControl(_data[0], "nCUIL", "");
	_TOOLS.itemToControl(_data[0], "sDomiTETelediscado", "-1");
	_TOOLS.itemToControl(_data[0], "sDomiTE", "");
	_TOOLS.itemToControl(_data[0], "sLKEstadoCivil", "-1");
	_TOOLS.itemToControl(_data[0], "sLKNacionalidad", "-1");
	_TOOLS.itemToControl(_data[0], "sLKOcupacion", "-1");
	_TOOLS.itemToControl(_data[0], "sCBU", "");
	_TOOLS.itemToControl(_data[0], "sLKTipoVivienda", "-1");
	_TOOLS.itemToControl(_data[0], "nImporteAlquiler", "");
	_TOOLS.itemToControl(_data[0], "sDomiCalle", "");
	_TOOLS.itemToControl(_data[0], "sDomiNro", "");
	_TOOLS.itemToControl(_data[0], "sDomiPisoDpto", "");
	_TOOLS.itemToControl(_data[0], "sDomiCP", "");
	_TOOLS.itemToControl(_data[0], "sDomiEntre", "");
	_TOOLS.itemToControl(_data[0], "sDomiBarrio", "");
	_TOOLS.itemToControl(_data[0], "sDomiPcia", "-1");
	_TOOLS.itemToControl(_data[0], "sLKDomiLocalidad", "-1");
	_TOOLS.itemToControl(_data[0], "sRazonSocial", "");
	_TOOLS.itemToControl(_data[0], "nCUIT", "");
	_TOOLS.itemToControl(_data[0], "sDomiCalle1", "");
	_TOOLS.itemToControl(_data[0], "sDomiNro1", "");
	_TOOLS.itemToControl(_data[0], "sDomiPisoDpto1", "");
	_TOOLS.itemToControl(_data[0], "sDomiCP1", "");
	_TOOLS.itemToControl(_data[0], "sDomiEntre1", "");
	_TOOLS.itemToControl(_data[0], "sDomiPcia1", "-1");
	_TOOLS.itemToControl(_data[0], "sLKDomiLocalidad1", "-1");
	_TOOLS.itemToControl(_data[0], "sDomiTETelediscado1", "-1");
	_TOOLS.itemToControl(_data[0], "sDomiTE1", "");
	_TOOLS.itemToControl(_data[0], "sDomiTEInt", "");
	_TOOLS.itemToControl(_data[0], "sCargo", "");
	_TOOLS.itemToControl(_data[0], "sLegajo", "");
	_TOOLS.itemToControl(_data[0], "sSeccion", "");
	_TOOLS.itemToControl(_data[0], "nIngresoMensual", "");
	_TOOLS.itemToControl(_data[0], "nOtrosIngresos", "");
	_TOOLS.itemToControl(_data[0], "sAntiguedad", "");

	$(".btnVerMapa").attr("data-lat", $(".Latitud").val());
	$(".btnVerMapa").attr("data-lng", $(".Longitud").val());

	$(".sLKOcupacion").change();
	$(".nDoc").attr("disabled", true);
	$(".sSexo").attr("disabled", true);
	var _vigencia = new Date(_data[0].dVigDesde).toISOString().split('T')[0] + " al " + new Date(_data[0].dVigHasta).toISOString().split('T')[0];
	var _color = "badge-danger";

	if (new Date(_data[0].dVigHasta) > new Date()) { _color = "badge-success"; }

	var _html = "<table>";
	_html += "		<tr>";
	_html += "		   <td><span class='p-2 badge badge-dark'>Código: <b>" + _data[0].sCodigo + "</b></td>";
	_html += "		   <td><span class='p-2 badge badge-dark'>PAN: <b>" + _data[0].masked_pan + "</b></td>";
	_html += "		   <td><span class='p-2 badge " + _color + "'>Vigencia: <b>" + _vigencia + "</b></td>";
	_html += "		</tr>";
	_html += "	</table>";
	$(".toolBar").html(_html);
}

_FUNCTIONS.onGetTarjeta($(".sCodigo").val(), $(".username").val());

