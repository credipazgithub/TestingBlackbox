$("body").off("click", ".btn-execute").on("click", ".btn-execute", function () {
	try {
		if (_TOOLS.validate(".validate", true)) {
			var _json = { "fecha_desde": $(".fecha_desde").val(), "fecha_hasta": $(".fecha_hasta").val() };
			_AJAX.UiIndicadoresMediyaInfo(_json).then(function (data) {
				console.log(data);
				$(".datos-informados").html(data.report).removeClass("d-none");
			});
		}
	}
	catch (rex) {
		alert("Error de ejecución, por favor contacte a soporte!");
	}
});