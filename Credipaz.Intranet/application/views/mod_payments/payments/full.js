var _dni = "";
var _form = "";
var _gateway = "";
var _timer = 0;
var _TMR_PAY_BOTONPAGO = 0;
var _idTransaction = 0;

$("body").off("click", ".btn-deuda-fiserv").on("click", ".btn-deuda-fiserv", function (e) {
	$(".data-payment2").addClass("d-none");
	_FUNCTIONS.onLoadPaymentData(1, _form, _gateway);
});
$("body").off("click", ".btn-copySimple").on("click", ".btn-copySimple", function () {
	_TOOLS.copySimple($(this).attr("data-source"));
});

$("body").off("click", ".btn-pagar-fiserv").on("click", ".btn-pagar-fiserv", function (e) {
	$(".btn-pagar-fiserv").hide();
	$(".paymentMethod").val($(this).attr("data-tc"));
	setTimeout(function () {
		var _dni = parseInt($(".dni_tarjeta").val());
		var _raw_request = JSON.stringify(_TOOLS.getFormValues(".dataPost", $(this)));
		var _json = {
			"id_type_channel": 1,
			"identificacion": _FUNCTIONS._itemsPagos[0]["Identificacion"],
			"currency_request": $("#currency").val(),
			"dni_request": _dni,
			"amount_request": $("#chargetotal").val(),
			"raw_request": _raw_request,
			"channel": "FSRV"
		};
		_AJAX.UiInitTransactionFiservNet(_json).then(function (data) {
			_idTransaction = data.message.id;
			_FUNCTIONS._itemsPagos[0]["idTransfer"] = _idTransaction;
			$("#referencedMerchantTransactionID").val(_idTransaction);
			$("#comments").val(JSON.stringify(_FUNCTIONS._itemsPagos));
			$(".data-payment1").addClass("d-none");
			$(".data-payment2").removeClass("d-none");
			$(".datos-informados").addClass("d-none");
			checkoutform.submit();
			//$(".btn-deuda-fiserv").click();
			clearInterval(_FUNCTIONS._TMR_PAY_BOTONPAGO);
			_FUNCTIONS._TMR_PAY_BOTONPAGO = setInterval(function () {
				_FUNCTIONS.onCheckStatusPaymentBotonPago(_idTransaction, _dni);
			}, 1000);
		});
	}, 500);
});

function totalizePayment(_this) {
	var _pagoTarjeta = 0;
	var _reset = _this.attr("data-reset");
	var _sort = parseInt(_this.attr("data-sort"));
	var _total = 0;
	var _color = "";
	var _rec = "";

	_FUNCTIONS._itemsPagos = [];
	$(".divForm").fadeOut("fast").html("");
	if (_reset != "") {
		$(_reset).prop("checked", false);
	}
	else {
		$(".chkCre").each(function () {
			var _dataSort = parseInt($(this).attr("data-sort"));
			if (_dataSort != 9999) {
				if (_dataSort <= _sort) {
					$(this).prop("checked", true);
				} else {
					$(this).prop("checked", false);
				}
			}
		});
	}
	if (_reset == ".chkTarMin" || _reset == ".chkTarTot") { $(".otro_monto").val(""); }

	if ($(".samImporte").val() != undefined) {
		$(".samImporte").css("background-color", "white");
		$(".samImporte").each(function () {
			_rec = "";
			var _samMin = parseInt($(this).attr("min"));
			var _samMax = parseInt($(this).attr("max"));
			var _samImporte = parseInt($(this).val());
			_color = "lightgreen";
			if (_samImporte != "" && (_samImporte < _samMin || _samImporte > _samMax)) {
				alert("Solo puede pagar un mínimo de $ " + _samMin + " o un máximo de $ " + _samMax);
				$(this).val(0);
				_color = "pink";
			} else {
				_rec = JSON.parse(_TOOLS.b64_to_utf8($(this).attr("data-record")));
				_rec.Importe = _samImporte.toFixed(2).toString();
				$(this).attr("data-record", _TOOLS.utf8_to_b64(JSON.stringify(_rec)));
				_total += _samImporte;
				if (_samImporte != 0) { _FUNCTIONS._itemsPagos.push(_rec); }
			}
			$(this).css("background-color", _color);
		});
	}
	/*
	if ($(".moraImporte").val() != undefined) {
		$(".moraImporte").css("background-color", "white");
		$(".moraImporte").each(function () {
			_rec = "";
			var _moraMin = parseInt($(this).attr("min"));
			var _moraMax = parseInt($(this).attr("max"));
			var _moraImporte = parseInt($(this).val());
			_color = "lightgreen";
			if (_moraImporte != "" && (_moraImporte < _moraMin || _moraImporte > _moraMax)) {
				alert("Solo puede pagar un mínimo de $ " + _moraMin + " o un máximo de $ " + _moraMax);
				$(this).val(0);
				_color = "pink";
			} else {
				_rec = JSON.parse(_TOOLS.b64_to_utf8($(this).attr("data-record")));
				_rec.Importe = _moraImporte.toFixed(2).toString();
				$(this).attr("data-record", _TOOLS.utf8_to_b64(JSON.stringify(_rec)));
				_total += _moraImporte;
				if (_moraImporte != 0) { _FUNCTIONS._itemsPagos.push(_rec); }
			}
			$(this).css("background-color", _color);
		});
	}
	*/

	if ($(".otro_monto").val() != undefined) {
		$(".otro_monto").css("background-color", "white");
		_rec = "";
		var _otro_monto = parseFloat($(".otro_monto").val().replaceAll(".", "").replaceAll(",", "."));
		if (isNaN(_otro_monto)) {
			$(".otro_monto").val("");
			_otro_monto = 0;
		} else {
			var _tar_min = parseFloat($(".chkTarMin").val());
			var _tar_tot = parseFloat($(".chkTarTot").val());
			_rec = JSON.parse(_TOOLS.b64_to_utf8($(".otro_monto").attr("data-record")));
			if (_otro_monto != 0) {
				if (isNaN(_tar_min)) { _tar_min = 0; }
				if (isNaN(_tar_tot)) { _tar_tot = 0; }
				_color = "lightgreen";
				if (_otro_monto < _tar_min || _otro_monto > _tar_tot) { _color = "pink"; }
				$(".otro_monto").css("background-color", _color);
				$(".chkTar").prop("checked", false);
				_total += _otro_monto;
			}
			if (_otro_monto == null || _otro_monto == "") { _otro_monto = 0; }
			_rec.Importe = _otro_monto.toFixed(2).toString();
			if (_tar_tot != 0) { _pagoTarjeta = 1; };
		}
		if (_otro_monto != 0) { _FUNCTIONS._itemsPagos.push(_rec); }
	}
	$(".chkPay").each(function () {
		var _rec = JSON.parse(_TOOLS.b64_to_utf8($(this).attr("data-record")));
		if ($(this).prop("checked")) {
			if (_rec.Importe == null || _rec.Importe == "" ) { _rec.Importe = 0;}
			if (parseFloat(_rec.Importe) != 0) {
				_rec.Importe = _rec.Importe.toFixed(2).toString();
				_FUNCTIONS._itemsPagos.push(_rec);
				_total += parseFloat(this.value);
			}
		}
	});
	/*consolidar total contra los items registrados! */
	var _total_consolidado = 0;
	for (let item of _FUNCTIONS._itemsPagos) {_total_consolidado += (item["Importe"]*1);}
	_total = _total_consolidado;
	$(".coinTotal").html(_TOOLS.formatMoney(_total, 2));
	if (_total.toString().indexOf(".")==-1) { _total += ".00"; }
	$(".importe").val(_total);
	if (_total_consolidado > 0) {
		var _dni = parseInt($(".dni_tarjeta").val());
		var _targetFrame = "iframe_fiserv";
		var _location = window.location.href;
		var _json = {
			"pagoTarjeta": _pagoTarjeta,
			"paymentMethod": "",
			"currency": "032",
			"total": _total,
			"dni": _dni,
			"itemsPagos": JSON.stringify(_FUNCTIONS._itemsPagos),
			"targetFrame": _targetFrame,
			"sandbox":0,
			"visible": 1,
			"parentUri": _location
		};
		_AJAX.UiBuildFormFiserv(_json).then(function (data) {
			$(".divForm").html(data.data).fadeIn("fast");
			$("#comments").val(JSON.stringify(_FUNCTIONS._itemsPagos));
			$(".data-payment1").removeClass("d-none");
			$(".data-payment2").addClass("d-none");
			_FUNCTIONS.onReBuildLinkPago();
		});
	} else {
		$(".data-payment1").addClass("d-none");
		$(".data-payment2").addClass("d-none");
	}
}
