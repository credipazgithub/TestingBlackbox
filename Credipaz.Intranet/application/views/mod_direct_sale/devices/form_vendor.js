var _video_interval = 2000;
_NEOVIDEO._username = "credipaz";
_NEOVIDEO._password = "08.!Rcp#@80";

$("body").off("change", ".browser_id_request").on("change", ".browser_id_request", function () {
	try {
		var _json = {};
		_json["id"] = $(this).val();
		_json["module"] = "mod_onboarding";
		_json["model"] = "Requests_core";
		_json["table"] = "Requests_core";
		_json["page"] = "-1";
		_AJAX.UiEdit(_json).then(function (data) {
			if (data.status == "OK") {
				$(".area-form").html(data.message).removeClass("d-none").fadeIn("slow");
				$(".btn-abm-cancel").remove();
			} else {
				throw data;
			}
		}).catch(function (rex) {
			_FUNCTIONS.onShowAlert(rex.message, "No se puede editar el registro");
		});
	} catch (rex) {
		_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
	}
});

$("body").off("click", ".btn-connect-monitor").on("click", ".btn-connect-monitor", function () {
	$(".imgMonitor").attr("src", "https://intranet.credipaz.com/assets/img/wait.gif");
	$(".divMonitors").removeClass("d-none");
	$(".btn-connect-monitor").addClass("d-none");
	$(".btn-disconnect-monitor").removeClass("d-none");
	clearInterval(_FUNCTIONS._TIMER_DEVICE_UPDATE);
	_FUNCTIONS._TIMER_DEVICE_UPDATE = setInterval(function () { checkDevices(); }, _video_interval);
});

$("body").off("click", ".btn-disconnect-monitor").on("click", ".btn-disconnect-monitor", function () {
	$(".divMonitors").addClass("d-none");
	$(".btn-connect-monitor").removeClass("d-none");
	$(".btn-disconnect-monitor").addClass("d-none");
	clearInterval(_FUNCTIONS._TIMER_DEVICE_UPDATE);
});

$("body").off("click", ".btn-open-session").on("click", ".btn-open-session", function () {
	var _id_transaction = $(this).attr("data-id_transaction");
	$.blockUI({ message: '<img src="https://intranet.credipaz.com/assets/img/wait.gif" />', css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });

	$(".btn-disconnect-monitor").click();
	$(".modeMonitoreo").addClass("d-none");
	$(".modeVideochat").removeClass("d-none");
	clearInterval(_FUNCTIONS._TIMER_DEVICE_UPDATE);
	var _id_device = $(this).attr("data-id-device");
	_NEOVIDEO._INTERFACE_CONFIG_OVERWRITE.TOOLBAR_BUTTONS = ['hangup', 'desktop'];
	_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height = "600px";
	_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width = "100%";
	_NEOVIDEO.onDisconnect = function () {
		clearInterval(_VIDEOCHAT._tmrVideoActive);
		_AJAX.UiVideoVendorStatus({ "id_device": _id_device, "videoStatus": 0 })
			.then(
				function (data) {
					$(".modeVideochat").addClass("d-none");
					$(".modeMonitoreo").removeClass("d-none");
					$(".btn-connect-monitor").click();
				});
	};

	_NEOVIDEO.onJoinOpenSession(_id_transaction).then(function (data) {
		_VIDEOCHAT._tmrVideoActive = setInterval(function () {
			_paramStatus = { "id_device": _id_device, "videoStatus": 1, "token_meet": _id_transaction };
			_AJAX.UiVideoVendorStatus(_paramStatus).then(function (status) { });
		}, 5000);
		$("#meet").fadeIn("slow");
		$('[id^="jitsiConferenceFrame"]').css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
		$.unblockUI();
	}).catch(function (err) {
		$.unblockUI();
		_FUNCTIONS.onShowAlert(err.message, "Alerta");
	});

	_AJAX.UiRequestAttention({ "id": _id_device, "requested_attention": 0, "code": null }).then(function (datajson) { });
});

function checkDevices() {
	_AJAX.UiCheckDevices({}).then(function (datajson) {
		//console.log("devices");
		//console.log(datajson);
		if (datajson.status == "OK") {
			$.each(datajson.data, function (ndx, item) {
				var _msg = "Desconectado";
				var _img = "https://intranet.credipaz.com/assets/img/disconnected.gif";
				$(".alert-requested-" + item.id).addClass("d-none");
				$(".connected-" + item.id).addClass("d-none");
				if (parseInt(item.requested_attention) == 1) {
					$(".alert-requested-" + item.id).removeClass("d-none");
					$(".connected-" + item.id).removeClass("d-none");
					$(".connected-" + item.id).attr("data-id_transaction", item.code);
				}
				if (item.id_user_in_use != null && item.connected > 10) {
					_msg = "Libre";
					_img = ("./attached/device-" + item.id + ".png?" + _TOOLS.UUID());
					$(".disconnected-" + item.id).addClass("d-none");
				} else {
					if (item.connected <= 10) {
						$(".alert-requested-" + item.id).addClass("d-none");
						_img = "https://intranet.credipaz.com/assets/img/disconnected.png";
						_msg = "En sesión de venta";
					}
					$(".connected-" + item.id).addClass("d-none");
					$(".disconnected-" + item.id).removeClass("d-none")
				}
				$(".imgMonitor-" + item.id).attr("src", _img);
				$(".message-" + item.id).html(_msg);
			});
		} else {
			_body = "<h4><span class='badge badge-danger'>Error al consultar el estado de los dispositivos</span></h4>";
			_FUNCTIONS.onInfoModal({ "title": "Estado de dispositivos", "body": _body, "close": true, "size": "modal-lg", "center": false });

		}
	}).catch(function (error) {
		alert(error.message);
	});
}

_FUNCTIONS.onClearTimers();
