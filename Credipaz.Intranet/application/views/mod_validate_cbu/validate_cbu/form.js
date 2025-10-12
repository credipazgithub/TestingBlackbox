//Variables 

//Hooks 
//Attach of events related to objects in the interface
//The interface, has not any event into the html view page

$("body").off("click", ".btn-consultar-cbu").on("click", ".btn-consultar-cbu", function () {
	calculate($(this));
});
$("body").off("click", ".btn-consultar-alias").on("click", ".btn-consultar-alias", function () {
	calculate($(this));
});


//Functions 
function calculate(_this) {
	$(".resultado").fadeOut("fast");
	var _type = _this.attr("data-type");
	if (_TOOLS.validate("." + _type)) {
		var _json = { "tipoConsulta": _this.attr("data-type"), "valorConsulta": $("."+_type).val() };
		_AJAX.UiValidateCBU(_json).then(function (datajson) {
			console.log(datajson);
			var _html = "";
			_html += "<table class='table-condensed' style='color:grey;' align='center'>";
			if (datajson.status == "OK") {
				if (datajson.message.mensaje == undefined) {
					var _activa = "<i class='material-icons' style='color:red;'>close</i>";
					if (datajson.message.cuentaActiva) { _activa = "<i class='material-icons' style='color:green;'>check</i>"; }
					_html += "   <tr><td colspan='2'><hr/><b>DETALLES</b></td></tr>";
					_html += "   <tr><td>Activa</td><td>" + _activa + "</td></tr>";
					_html += "   <tr><td>Código<td style='color:blue;'>" + datajson.message.respuestaCodigo + "</td></tr>";
					_html += "   <tr><td>Respuesta</td><td>" + datajson.message.respuestaDescripcion + "</td></tr>";
					_html += "   <tr><td>Nombre</td><td>" + datajson.message.nombre + "</td></tr>";
					_html += "   <tr><td>CUIT</td><td>" + datajson.message.cuit + "</td></tr>";
					_html += "   <tr><td>Persona</td><td>" + datajson.message.tipoPersona + "</td></tr>";
					_html += "   <tr><td>CBU</td><td>" + datajson.message.numeroCBU + "</td></tr>";
					_html += "   <tr><td>Banco</td><td>" + datajson.message.numeroBanco + "</td></tr>";
					_html += "   <tr><td>Tipo</td><td>" + datajson.message.tipoCuenta + " " + datajson.message.descripcion + "</td></tr>";
					_html += "   <tr><td>Moneda</td><td>" + datajson.message.monedaCuenta + "</td></tr>";
					_html += "   <tr><td colspan='2'><hr/><b>TITULARES</b></td></tr>";
					$.each(datajson.message.titulares.Titulares.SdtbBTAliasTitulares_Titular, function (a, item) {
						_html += "       <tr><td>Documento</td><td>" + item.Documento + "</td></tr>";
						_html += "       <tr><td>Nombre</td><td>" + item.Nombre + "</td></tr>";
						_html += "       <tr><td>Tipo</td><td>" + item.TipoPersona + "</td></tr>";
					});
					_html += "       <tr><td colspan='2'><hr/><b>IDENTIFICADORES</b></td></tr>";
					_html += "       <tr><td>Alias Id</td><td>" + datajson.message.aliasId + "</td></tr>";
					_html += "       <tr><td>Alias Original</td><td>" + datajson.message.aliasOriginal + "</td></tr>";
					_html += "       <tr><td>Alias Valor</td><td>" + datajson.message.aliasValor + "</td></tr>";
					_html += "       <tr><td>Transacción</td><td style='color:blue;'>" + datajson.message.transaccion + "</td></tr>";
				} else {
					_html += "       <tr><td colspan='2'><hr/><b>RESPUESTA</b></td></tr>";
					_html += "       <tr><td colspan='2' style='color:red;'>" + datajson.message.mensaje + "</td></tr>";
					_html += "       <tr><td colspan='2'><hr/><b>IDENTIFICADORES</b></td></tr>";
					_html += "       <tr><td>Estado <span style='color:blue;'>" + datajson.message.idEstado + "</span></td></tr>";
					_html += "       <tr><td colspan='2'>" + datajson.message.time + "</td></tr>";
				}
			} else {
				_FUNCTIONS.onShowAlert(datajson.message, "Alerta");
				_html += "       <tr><td colspan='2'><hr/><b>RESPUESTA</b></td></tr>";
				_html += "       <tr><td colspan='2' style='color:red;'>" + datajson.message + "</td></tr>";
			}
			_html += "</table>";
			$(".resultado").html(_html);
			$(".resultado").fadeIn("fast");
		});
	}
}
