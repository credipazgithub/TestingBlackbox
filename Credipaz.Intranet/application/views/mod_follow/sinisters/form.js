$("body").off("click", ".btn-execute").on("click", ".btn-execute", function () {
	if (_TOOLS.validate(".validate", true)) {
		_AJAX._waiter = true;
		$(".btn-execute").hide();
		var _params = _TOOLS.getFormValues(".dbaseStatics", $(this));
		_params["browser_id_doctor"] = $(".browser_id_doctor").val();
		_AJAX.UiFollowStatics(_params)
			.then(function (data) {
				$(".resultados").html(data.message);
				$(".btn-download").attr("href", (_AJAX.server + data.csv));
				$(".btn-execute").show();
			})
			.catch(function (err) {
				$(".btn-execute").show();
			});
	}
});

