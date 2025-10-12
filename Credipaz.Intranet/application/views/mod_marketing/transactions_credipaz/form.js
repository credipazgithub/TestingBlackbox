$("body").off("click", ".btn-execute").on("click", ".btn-execute", function () {
	try {
		if (_TOOLS.validate(".validate", true)) {
			$(".datos-informados").fadeOut("slow", function () { $(".datos-informados").removeClass("d-none"); } );
			$.blockUI({ message: '<img src="https://intranet.credipaz.com/assets/img/wait.gif" />', css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
			var _json = { "fecha_desde": $(".fecha_desde").val(), "fecha_hasta": $(".fecha_hasta").val() };
			_AJAX.UiIndicadoresCredipazInfo(_json).then(function (data) {
				$(".datos-informados").html(data.report).removeClass("d-none").fadeIn("fast");
				$.unblockUI();
			});
		}
	}
	catch (rex) {
		$.unblockUI();
		alert("Error de ejecución, por favor contacte a soporte!");
	}
});