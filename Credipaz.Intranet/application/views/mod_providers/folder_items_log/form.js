//Variables 

//Hooks 
//Attach of events related to objects in the interface
//The interface, has not any event into the html view page
$(function () {
	defineClicks();
});

//Functions 
function calculate(_this) {
	var _type = _this.attr("data-type");
	var _mark_username = $("#mark_username").val();
	if (_TOOLS.validate("." + _type)) {
		var _json = { "model": "calificacion", "type": _type };
		switch (_type) {
			case "validate":
				_json["mark_username"] = _mark_username;
				break;
		}
		_AJAX.UiMarkUserRead(_json).then(function (datajson) {
			if (datajson.status == "OK") {
				alert("Se han marcado como leídos, todos los documentos relacionados al usuario " + _mark_username);
				$("#mark_username").val("");
			} else {
				_FUNCTIONS.onShowAlert(datajson.message, "Alerta");
			}
		});
	}
}
function defineClicks() {
	$("body").off("click", ".btn-consultar").on("click", ".btn-consultar", function () {
		calculate($(this));
	});
}
