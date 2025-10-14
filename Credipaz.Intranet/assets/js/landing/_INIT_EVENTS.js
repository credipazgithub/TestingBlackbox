(function () {
	var today = new Date();
	$.getScript("/assets/js/AJAX.js?" + today.toDateString()).done(function (script, textStatus) {
		_AJAX._pre = ".";
		$.getScript("/assets/js/TOOLS.js?" + today.toDateString()).done(function (script, textStatus) {
			$.getScript("/assets/js/FUNCTIONS.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
				moment().tz("America/Montevideo").format();
				$("body").off("click", ".btn-login").on("click", ".btn-login", function () {
					_FUNCTIONS.onLogin($(this), "site").then(function (data) {
						_AJAX.UiLogged({});
					});
				});
				$("body").off("click", ".btn-logout").on("click", ".btn-logout", function () {
					_FUNCTIONS.onLogout($(this), "site");
				});
			});
		});
	});
})();

