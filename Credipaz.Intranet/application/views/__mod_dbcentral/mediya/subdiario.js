$("body").off("click", ".btn-execute").on("click", ".btn-execute", function () {
	try {
		if (_TOOLS.validate(".validate", true)) {
			var _json = { "FechaDesde": $(".FechaDesde").val(), "FechaHasta": $(".FechaHasta").val() };
			_AJAX.UiMediYaSubdiario(_json).then(function (data) {
				$(".resultados").html(data.message);
				$(".btn-download").attr("href", (_AJAX.server + data.csv));
			})
		}
	}
	catch (rex) {
		alert("Error de ejecución, por favor contacte a soporte!");
	}
});

