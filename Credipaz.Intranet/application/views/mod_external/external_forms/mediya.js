var _idEmpresa = "0";
var _idVendedor = 0;
var _auth = null;
var _new = false;
var _empleadoCredipaz = false;
var _recaudadora = "N";

_FUNCTIONS.onTraerLookUp2("ModoPago")
	.then(function (data) {
		_TOOLS.loadCombo(data, { "target": "#IdModoPago", "selected": -1, "id": "id", "description": "descripcion", "default": "[Elija forma de pago]" });
	})
	.catch(function (err) { });
_FUNCTIONS.onTraerLookUp2("EstadoCivilDbClub")
	.then(function (data) { _TOOLS.loadCombo(data, { "target": "#IdEstadoCivil", "selected": -1, "id": "id", "description": "descripcion", "default": "[Seleccione]" }); })
_FUNCTIONS.onTraerLookUp2("NacionalidadDbClub")
	.then(function (data) { _TOOLS.loadCombo(data, { "target": "#IdNacionalidad", "selected": -1, "id": "id", "description": "descripcion", "default": "[Seleccione]" }); })
_FUNCTIONS.onTraerLookUp2("OcupacionDBClub")
	.then(function (data) { _TOOLS.loadCombo(data, { "target": "#IdOcupacion", "selected": -1, "id": "id", "description": "descripcion", "default": "[Seleccione]" }); })
_FUNCTIONS.onTraerLookUp2("Telediscado")
	.then(function (data) {
		_TOOLS.loadCombo(data, { "target": "#AreaTelefonoSocio", "selected": -1, "id": "id", "description": "descripcion", "default": "[Seleccione]" });
	})

$("body").off("click", ".btn-login").on("click", ".btn-login", function () {
	try {
		if (_TOOLS.validate(".validate", true)) {
			var _hide = $(this).attr("data-hide");
			var _show = $(this).attr("data-show");
			var _json = _TOOLS.getFormValues(".dbase", $(this));
			_AJAX._waiter = true;
			_AJAX.UiLoginVendedor(_json)
				.then(function (_auth) {
					_recaudadora = _auth.additional[0]["Recaudadora"];
					$(".dataAuth").html("<span class='badge badge-warning' style='font-size:16px;'>Bienvenido/a <b>" + _auth.message[0].Nombre + "</b></span>");
					_FUNCTIONS.onTraerLookUp2("ModoPago")
						.then(function (data) {
							_TOOLS.loadCombo(data, { "target": "#IdModoPago", "selected": -1, "id": "id", "description": "descripcion", "default": "[Elija forma de pago]" });
							if (_recaudadora == "S") {
								$(".IdModoPago").val(4);
								//$(".IdModoPago").attr("disabled", true);
								//$(".noValidateEdit").removeClass("validateAdherir");
							}
						})
						.catch(function (err) { });
					if (_auth.status == "OK") {
						if (_auth.message != null && _auth.message[0] != null && _auth.message[0].Id != null) {
							$(".IDVendedor").val(_auth.message[0].Id);
							$(".IDEmpresa").val(_auth.message[0].IdEmpresa);
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
		var _message = "Complete los datos requeridos para verificar";
		if (_TOOLS.validate(".validateInit", true)) {
			_AJAX.UiGetTitularMediya(_TOOLS.getFormValues(".validateInit", $(this)))
				.then(function (_data) {
					if (_data.message.records==null || _data.message.records.length == 0) {
						_message = "Todo está en orden.  El DNI no es de un cliente ni de un socio.  Complete todos los datos solicitados.";
						$(".info-verify").removeClass("badge-warning").addClass("badge-success");
					} else {
						$(".info-verify").removeClass("badge-success").addClass("badge-warning");
						_message = "Aviso.  El DNI es de un cliente, revise y complete todos los datos propuestos para solicitar el alta.";
						if (_data.message.records[0].IdSocio == "") {
							setData(_data.message.records);
						} else {
							_message = ("Alerta. El DNI es de un socio Mediya en estado <b>" + _data.message.records[0].EstadoSocio + "</b>, no puede solicitar el alta ni editar.");
							_ok = false;
							var _idEmpresa = parseInt($(".IDEmpresa").val());
							if (_idEmpresa != 0) {
								_redirect = (_idEmpresa == parseInt(_data.message.records[0].Empresa));
								_message = ("Alerta. El DNI es de un socio Mediya en estado <b>" + _data.message.records[0].EstadoSocio + "</b> y no pertenece a su comercializadora, no puede solicitar el alta ni editar.");
							}
							if (_redirect) { redirectEdit(_data.message.records[0].IdSocio); }
						}
					}
					if (_ok) {
						if (_data.message.mensaje != "") {
							$(".CUIL").attr("disabled", true).val(_data.message.mensaje);
						}
						$(".off-init").removeClass("d-none");
						$(".col-verify").remove();
						$(".NroDocumento").attr("disabled", true);
						$(".Sexo").attr("disabled", true);
					}
					$(".info-verify").html(_message);
				})
				.catch(function (error) {
					console.log(error);
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
$("body").off("click", ".btn-Reservar").on("click", ".btn-Reservar", function () {
	try {
		_AJAX.UiReservarMediya({ "idSocio": $("#Id").val(), "username": $("#username").val() })
			.then(function (_data) {
				$(".btn-Reservar").remove();
			});
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
	if (!_TOOLS.checkLen("CUIL", "CUIL")) { return false; }
	if (!_TOOLS.checkLen("TelefonoSocio", "Teléfono", 8)) { return false; }

	switch (parseInt($(".IdModoPago").val())) {
		case 1: // tarjeta de credito
		case 5: // tarjeta de débito
			if (!_TOOLS.checkLen("PAN", "Nº de tarjeta")) { return false; }
			break;
		case 2: // CBU
			if (!_TOOLS.checkLen("CBU", "CBU")) { return false; }
			break;
	}
	if (_auth == "") { _auth = 0; }

	var _d = new Date($(".FechaNacimiento").val());
	if (_d.getYear() < 1) {
		$(".FechaNacimiento").addClass("is-invalid").removeClass("is-valid");
		return false;
	}
	if (!_TOOLS.validate(".validateAdherir", true)) { return false; }
	var _json = _TOOLS.getFormValues(".dbaseAdherir", null);
	var _new = (parseInt(_json["Id"]) == 0);

	$(".btnAction").hide();
	_AJAX.UiSetTitularMediya(_json).then(function (_data) {
		if (_data.message.logica) {
			if (_new) {
				//Armado de parametros para entrar en modo edicion!
				redirectEdit(_data.message.id);
			} else {
				//Refrescar la página luego de grabar!
				window.location.reload();
			}
		} else {
			$(".btnAction").show();
			_FUNCTIONS.onShowInfo(_data.message.mensaje, "Alerta");
		}
	}).catch(function (err) {
		alert(err.message);
	});
});
$("body").off("change", ".IdModoPago").on("change", ".IdModoPago", function () {
	_FUNCTIONS.onTraerLookUp("OpcionModoPago", $(this).val())
		.then(function (data) {
			_TOOLS.loadCombo(data, { "target": "#Marca", "selected": -1, "id": "Descripcion", "description": "Descripcion", "default": "[Elija tarjeta]" });
		})
		.catch(function (err) { });
	$(".adds").addClass("d-none");
	$(".relative").removeClass("validateAdherir");
	$(".CBU").val("");
	switch (parseInt($(this).val())) {
		case 1: // tarjeta de credito
		case 5: // tarjeta de débito
			$(".DAT").removeClass("d-none");
			$(".Marca").addClass("validateAdherir");
			$(".PAN").addClass("validateAdherir");
			$(".NombreTarjeta").addClass("validateAdherir");
			$(".MesVTO").addClass("validateAdherir");
			$(".AnioVTO").addClass("validateAdherir");
			break;
		case 2: // CBU
			$(".DAC").removeClass("d-none");
			$(".CBU").addClass("validateAdherir");
			break;
		case 3: // Tarjeta CP
			if (_new && parseInt($(".TarjetaCPHabilitada").val()) != 1) {
				_FUNCTIONS.onShowInfo("No puede utilizar este medio de pago para esta operación", "Alerta");
				$(".IdModoPago").val(-1);
			} else {
				$(".CBU").val($(".TarjetaCP").val());
			}
			break;
	}
});
$("body").off("click", ".btnDeleteAdicional").on("click", ".btnDeleteAdicional", function () {
	var _idFamiliar = parseInt($(this).attr("data-id"));
	var _username = $(this).attr("data-username");
	try {
		if (!confirm("Se marcará como baja el adicional seleccionado.  ¿Confirma la operación?")) { return false; }
		_FUNCTIONS.onDelAdicionalMediya(_username, _idFamiliar);
	}
	catch (rex) {
		_FUNCTIONS.onShowInfo("No se ha podido procesar la baja", "Alerta");
	}
});
$("body").off("click", ".btnEditAdicional").on("click", ".btnEditAdicional", function () {
	var _idFamiliar = parseInt($(this).attr("data-id"));
	var _idSocio = parseInt($(this).attr("data-id_socio"));
	var _username = $(this).attr("data-username");
	var _record = $(this).attr("data-record");
	try {
		_FUNCTIONS.onSetAdicionalMediya(_username, _idFamiliar, _idSocio, _record);
	}
	catch (rex) {
		_modo = "el alta";
		if (_idFamiliar != 0) { _modo = "la modificación"; }
		_FUNCTIONS.onShowInfo("No se ha podido procesar " + _modo, "Alerta");
	}
});
$("body").off("click", ".btnVerCredenciales").on("click", ".btnVerCredenciales", function () {
	_FUNCTIONS.onVerCredencialesMediya($(this));
});
$("body").off("click", ".btnVerMapa").on("click", ".btnVerMapa", function () {
	_FUNCTIONS.onVerMapaMediya($(this));
});
$("body").off("click", ".btnVerHistorialDePagos").on("click", ".btnVerHistorialDePagos", function () {
	_FUNCTIONS.onVerHistorialDePagos($(this));
});
$("body").off("click", ".btnPayEdit").on("click", ".btnPayEdit", function () {
	redirectNew();
});

function redirectEdit(_id) {
	var _path = window.location.pathname.split("/");
	var _param = { "user": $(".username").val(), "id": _id, "id_sucursal": parseInt($(".IDSucursal").val()) };
	window.location.href = (window.location.protocol + "//" + window.location.host + "/" + _path[1] + "/" + _path[2] + "?p=" + _TOOLS.utf8_to_b64(JSON.stringify(_param)));
}
function redirectNew() {
	var _path = window.location.pathname.split("/");
	window.location.href = (window.location.protocol + "//" + window.location.host + "/" + _path[1] + "/" + _path[2]);
}

function setData(_data) {
	console.log(_data);
	_empleadoCredipaz = (parseInt(_data[0].Empresa) == 999);
	if (_data[0].AnioVTO != "") {_data[0].AnioVTO = (parseInt(_data[0].AnioVTO) - 2000);}
	_data[0].Email = _data[0].Email.toString().toLowerCase(); 
	$(".FechaNacimiento").val(new Date(_data[0].FechaNacimiento).toISOString().split('T')[0]);
	_TOOLS.itemToControl(_data[0], "NroDocumento", "");
	_TOOLS.itemToControl(_data[0], "Sexo", "-1");
	_TOOLS.itemToControl(_data[0], "Nombre", "");
	_TOOLS.itemToControl(_data[0], "Apellido", "");
	_TOOLS.itemToControl(_data[0], "IdEstadoCivil", "-1");
	_TOOLS.itemToControl(_data[0], "IdNacionalidad", "-1");
	_TOOLS.itemToControl(_data[0], "IdOcupacion", "-1");
	_TOOLS.itemToControl(_data[0], "CUIL", "");
	_TOOLS.itemToControl(_data[0], "AreaTelefonoSocio", "-1");
	_TOOLS.itemToControl(_data[0], "TelefonoSocio", "");
	_TOOLS.itemToControl(_data[0], "Email", "");
	_TOOLS.itemToControl(_data[0], "Calle", "");
	_TOOLS.itemToControl(_data[0], "Numeracion", "");
	_TOOLS.itemToControl(_data[0], "Piso", "");
	_TOOLS.itemToControl(_data[0], "DptoOficLoc", "");
	_TOOLS.itemToControl(_data[0], "Torre", "");
	_TOOLS.itemToControl(_data[0], "CodigoPostal", "");
	_TOOLS.itemToControl(_data[0], "Provincia", "");
	_TOOLS.itemToControl(_data[0], "Localidad", "");
	_TOOLS.itemToControl(_data[0], "IdModoPago", "-1");
	$(".IdModoPago").change();
	setTimeout(function () {
		if (parseInt($(".IdModoPago").val()) == 4) {
			$(".dataEmpresa").html("<span class='p-2 badge badge-dark'>Empresa: <b>" + _data[0].RazonSocial + "</b></span>")
		}
		_TOOLS.itemToControl(_data[0], "Marca", "");
		_TOOLS.itemToControl(_data[0], "AnioVTO", "");
		_TOOLS.itemToControl(_data[0], "MesVTO", "");
		_TOOLS.itemToControl(_data[0], "CBU", "");
		_TOOLS.itemToControl(_data[0], "NombreTarjeta", "");
		_TOOLS.itemToControl(_data[0], "PAN", "");
		_TOOLS.itemToControl(_data[0], "Latitud", "0");
		_TOOLS.itemToControl(_data[0], "Longitud", "0");
	}, 500);

	$(".btnVerMapa").attr("data-lat", $(".Latitud").val());
	$(".btnVerMapa").attr("data-lng", $(".Longitud").val());
	$(".btnVerCredenciales").attr("data-dni", $(".NroDocumento").val());
	$(".btnVerCredenciales").attr("data-sexo", $(".Sexo").val());

	$(".NroDocumento").attr("disabled", true);
	$(".Sexo").attr("disabled", true);

	var _new = (parseInt($(".Id").val()) == 0);

	var _color = "badge-danger";
	var _stateAdditional = "";
	if (_data[0].EstadoSocio == "VIG" || _data[0].EstadoSocio == "PPI") { _color = "badge-success"; }
	/* Si menos que cero o mayor a 90 activar boton de pago al pie de cuota inicial */
	if (parseInt(_data[0].dayLastPay) < 0 || parseInt(_data[0].dayLastPay) > 90) {
		_color = "badge-warning"; 
		if (parseInt(_data[0].dayLastPay) < 0) { _stateAdditional = "<span class='p-2 badge badge-info'><b>Sin pagos de servicios aún</b></span>"; }
		if (parseInt(_data[0].dayLastPay) > 90) { _stateAdditional = "<span class='p-2 badge badge-warning'><b>Más de 90 días sin pagos de servicios</b></span>"; }
		if (!_new) {
			var _url = "https://api.mediya.com.ar/pagos-cicr/" + _TOOLS.utf8_to_b64($(".NroDocumento").val());
			
			$(".btnPayEdit").attr("href", _url);
			$(".areaPay").removeClass("d-none");
		}
	}
	var _html = "<table>";
	_html += "		<tr>";
	if (_data[0].disponible == "S") {_html += "<td><a href='#' class='p-2 btn btn-raised btn-warning btn-Reservar'>Reservar</></td>";}
	if (_data[0].AltaSocio != "") { _html += "<td><span class='p-2 badge badge-light'>Alta: <b>" + moment((new Date(_data[0].AltaSocio).toISOString().split('T')[0])).format('DD/MM/YYYY') + "</b></span></td>"; }
	if (_data[0].IdSocio != "") {
		_html += "<td><span class='p-2 badge badge-dark'>ID socio: <b>" + _data[0].IdSocio + "</b></span></td>";
		_html += "<td><span class='p-2 badge " + _color + "'>Estado: <b>" + _data[0].EstadoSocio + "</b></span></td>";
	}
	if (_data[0].FUP != "") { _html += "<td><span class='p-2 badge badge-success'>Último pago: <b>" + _data[0].FUP + "-" + _data[0].MUP + "</b></span></td>"; }
	if (_stateAdditional != "") { _html += "<td>" + _stateAdditional + "</td>"; }
	if (_empleadoCredipaz) {
		$(".IDEmpresa").val(999);
		_html += "<td><span class='p-2 badge badge-primary'><b>Empleado en Credipaz</b></span></td>";
	}
	_color = "badge-success"; 
	$(".TarjetaCP").val(_data[0].sCuenta);
	$(".TarjetaCPHabilitada").val(0);
	$(".CBU").val("");
	if (_data[0].sCuenta != "" && _data[0].sEstado == "Vigente") { $(".TarjetaCPHabilitada").val(1); }
	/*
	if (_data[0].sCuenta != "") {
		if (_data[0].sEstado != "Vigente") { _color = "badge-danger"; }
		_html += "<td><span class='p-2 badge " + _color + "'>Cuenta Tarjeta: <b>" + _data[0].sCuenta + " - " + _data[0].sEstado + "</b></span></td>";
	}
	*/
	_html += "		</tr>";
	_html += "	</table>";
	$(".toolBar").html(_html);
}

_FUNCTIONS.onGetTitularMediya($(".Id").val(), $(".username").val());

