var _FISERV = {
	server: "https://intranet.credipaz.com/",
	method: "api.backend/neocommandTransparent",
	_itemsPagos: null,
	onPayFiserv: function (_json) {
		return new Promise(
			function (resolve, reject) {
				try {
					_FISERV._itemsPagos = null;
					var _formContainer = ("#" + _json["formContainer"]);
					var _iframeContainer = ("#" + _json["iframeContainer"]);
					if (_FISERV._itemsPagos == null) {
						_FISERV._itemsPagos = [{
							"Tipo": _json.type,
							"Identificacion": (_json.dni + " " + _json.description),
							"Importe": _json.total,
							"idTransfer": 0,
						}];
						_FISERV._itemsPagos = JSON.stringify(_FISERV._itemsPagos);
					}
					_json["installments"] = _json.installments;
					_json["function"] = "buildFormFiserv";
					_json["module"] = "mod_payments";
					_json["table"] = "payments_fiserv";
					_json["model"] = "payments_fiserv";
					_json["itemsPagos"] = _FISERV._itemsPagos;
					var ajaxRq = $.ajax({
						type: "POST",
						dataType: "json",
						url: (_FISERV.server + _FISERV.method),
						data: _json,
						error: function (xhr, ajaxOptions, thrownError) {reject(thrownError);},
						success: function (data) {
							$(_formContainer).html(data.data).removeClass("d-none");
							$(_iframeContainer).html("<iframe id='iframe_fiserv' name='iframe_fiserv' class='iframe_fiserv' src='' frameborder='0' style='height:100vh;width:100%;display:none;' />");
							$("#comments").val(JSON.stringify(_FISERV._itemsPagos));
							$("body").off("click", ".btn-pagar-fiserv").on("click", ".btn-pagar-fiserv", function (e) {
								checkoutform.submit();
							});
							resolve(data);
						}
					});
				} catch (rex) {
					alert(rex);
				}
			});
	},
	validate: function (_selector) {
		if (_seeAlert == undefined) { _seeAlert = false; }
		var _ret = true;
		$(_selector).each(function () { _ret = _FISERV.formatValidation($(this)) && _ret; });
		return _ret;
	},
	formatValidation: function (_obj) {
		var _ret = true;
		var property = _obj.attr('name');
		switch (_obj.prop("tagName")) {
			case "TEXTAREA":
			case "INPUT":
				switch (_obj.attr("type")) {
					case "radio":
						_ret = ($("input[name='" + property + "']:checked").val() != undefined);
						if (!_ret) {
							_obj.parent().css("border", "solid 1px red");
						} else {
							_obj.parent().css("border", "solid 0px transparent");
						}
						break;
					case "checkbox":
						var _checked = _obj.is(":checked");
						if (!_checked) { _ret = false; }
						break;
					default:
						if (_obj.hasClass("data-list")) {
							if (_obj.attr("data-selected-id") == "" || _obj.attr("data-selected-id") == undefined) { _ret = false; }
						} else {
							if (_obj.val() == "") { _ret = false; }
						}
						break;
				}
				break;
			case "SELECT":
				if (_obj.val() == "-1" || _obj.val() == undefined || _obj.val() == null || _obj.val() == "") { _ret = false; }
				break;
		}
		if (_ret) {
			_obj.removeClass("is-invalid").addClass("is-valid");
			$(".invalid-" + _obj.prop("name")).html("").addClass("d-none");
		} else {
			_obj.removeClass("is-valid").addClass("is-invalid");
			var _msg = _obj.attr("placeholder");
			if (_msg == undefined) { _msg = "el valor de selección"; }
			$(".invalid-" + _obj.prop("name")).html("Debe completar " + _msg).removeClass("d-none");
		}
		//if (!_ret) { alert(property);}
		return _ret;
	},
	getFormValues: function (_selector, _this) {
		try {
			var _jsonSave = {};
			$(_selector).each(function () {
				var property = $(this).attr('name');
				var value = "";
				switch ($(this).attr("data-type")) {
					case "select":
						if ($(this).length == 0) { value = ""; } else { value = $(this).val(); }
						if (value == null || value == "-1" || value == "0") { value = ""; }
						break;
					case "radio":
						value = $("input[name='" + property + "']:checked").val();
						if (value == undefined) { value = ""; }
						break;
					case "checkbox":
						if ($(this).prop("checked")) {
							value = $(this).val();
							if (parseInt(value) == 0 || value == '') { value = 1; }
						} else {
							value = 0;
						}
						break;
					default:
						value = $(this).val();
						break;
				}
				_jsonSave[property] = value;
			});
		} catch (rex) { };
		return _jsonSave;
	},
}

