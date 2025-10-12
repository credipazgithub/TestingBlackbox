$("body").off("click", ".btn-execute").on("click", ".btn-execute", function () {
	try {
		if (_TOOLS.validate(".validate", true)) {
			var _json = { "dni": $(".dni").val(), "sexo": $(".sexo").val() };
			_AJAX.UiIngresosConsulta(_json).then(function (data) {
				$(".resultados").html(data.message).fadeIn("slow");
				$(".btn-download").attr("href", (_AJAX.server + data.csv));
			})
		}
	}
	catch (rex) {
		alert("Error de ejecución, por favor contacte a soporte!");
	}
});

$("body").off("click", ".btn-exclude").on("click", ".btn-exclude", function () {
	var _total = 0;
	$($(this).attr("data-remove")).remove();
	$('.amount').each(function () { _total += parseFloat($(this).attr('data-importe')); });
	$(".total").html("$ " + _total.toLocaleString('de'));
	$(".total").attr("data-total", _total);
});

$("body").off("click", ".btn-update").on("click", ".btn-update", function () {
	try {
		var _id_cliente = $(".cliente").attr("data-cliente");
		var _mensaje = $(".mensaje").attr("data-mensaje");
		if (_mensaje != "") {
			_FUNCTIONS.onShowAlert("Es imposible procesar la solicitud.<br/><b>" + _mensaje + "</b> ", "Alerta");
			return false;
		}
		var _json = { "IdCliente": _id_cliente, "Ingresos": $(".total").attr("data-total") };
		_AJAX.UiIngresosUpdate(_json).then(function (data) {
			_FUNCTIONS.onShowInfo("<b style='color:green;'>Se han enviado los datos para actualización en forma correcta</b>","Información");
		})
	}
	catch (rex) {
		alert("Error de ejecución, por favor contacte a soporte!");
	}
});
