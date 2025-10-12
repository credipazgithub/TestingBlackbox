var _vPublish = [];
var _actual_video = 0;
var video = null;
var source = null;
var videoLocal = null;
var _video_interval = 3000;
var _status_interval = 3500;
var _width_capture = 160;
var _height_capture = 120;
var _modeActive = "totem";
var _id_device_active = 0;
var _last_id = 0;
var _mode = "";
_NEOVIDEO._username = "credipaz";
_NEOVIDEO._password = "08.!Rcp#@80";

$("body").off("click", ".btn-request-video").on("click", ".btn-request-video", function () {
	deActivateCredito();
});
$("body").off("click", ".btn-request-new").on("click", ".btn-request-new", function () {
	if (confirm("¿Confirma el inicio de una nueva solicitud?")) {
		$(".onboarding").attr("src", "https://totem.credipaz.com?id_user_active=" + _AJAX._id_user_active);
	}
	setTimeout(function () { _TOOLS.toFullscreen("creditWrapper"); }, 50);
});
$("body").off("click", ".btn-request-operator").on("click", ".btn-request-operator", function () {
	$(".btn-request-operator").fadeOut("slow");
	$(".alert-wait").removeClass("d-none");
	_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height = "100vh";
	_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width = "100vw";
	_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.caller = "Cliente";
	_NEOVIDEO._INTERFACE_CONFIG_OVERWRITE.TOOLBAR_BUTTONS = ['camera','hangup'];
	_NEOVIDEO._CONFIG_OVERWRITE.startWithAudioMuted = false;
	_NEOVIDEO._CONFIG_OVERWRITE.startWithVideoMuted = false;

	_NEOVIDEO.onDisconnect = function () {
		clearInterval(_VIDEOCHAT._tmrVideoActive);
		$(".modeVideochat").addClass("d-none");
		$(".modeTotem").removeClass("d-none");
		$(".btn-request-operator").fadeIn("slow");
		$(".btn-request-credito").fadeIn("slow");
		$(".alert-wait").addClass("d-none");
		activateDevice($(".btn-connect-device-" + _id_device_active));
	};
	_NEOVIDEO.onParticipantJoined = function () {
		$(".modeTotem").addClass("d-none");
		$(".modeVideochat").removeClass("d-none");
		$(".meet-wait").addClass("d-none");
		$("#meet").removeClass("d-none").fadeIn("slow");
		$('[id^="jitsiConferenceFrame"]').css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
		videoLocal.pause();
	};
	_NEOVIDEO.onParticipantLeft = function () {
		_NEOVIDEO.onTurnOffVideo();
		$(".modeVideochat").addClass("d-none");
		$(".modeTotem").removeClass("d-none");
		$(".btn-request-operator").fadeIn("slow");
		$(".btn-request-credito").fadeIn("slow");
		$(".alert-wait").addClass("d-none");
		activateDevice($(".btn-connect-device-" + _id_device_active));
	};

	stop($(".btn-connect-device-" + _id_device_active));
	_NEOVIDEO.onCreateNewVideoRoom(null, { "id_external": 0, "live": 0 }).then(function (data) {
		$('[name ^= "jitsiConferenceFrame"]').css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
		$(_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.target).fadeIn("slow");
		setTimeout(function () {
			_AJAX.UiRequestAttention({ "id": _id_device_active, "requested_attention": 0, "code": null }).then(function (datajson) {
				$(".btn-request-operator").fadeIn("slow");
				$(".alert-wait").addClass("d-none");
			});
		}, 70000);
		_AJAX.UiRequestAttention({ "id": _id_device_active, "requested_attention": 1, "code": data.id_transaction }).then(function (datajson) { }).catch(function (error) { alert(error.message); });
	});
});
$("body").off("click", ".btn-request-credito").on("click", ".btn-request-credito", function () {
	activateCredito();
});
$("body").off("click", ".btn-connect-device").on("click", ".btn-connect-device", function () {
	activateDevice($(this));
});
$("body").off("click", ".btn-disconnect-device").on("click", ".btn-disconnect-device", function () {
	deActivateDevice($(this));
});
$("body").off("click", ".btn-fullscreen").on("click", ".btn-fullscreen", function () {
	_TOOLS.toFullscreen(_mode);
});
$('#videoLocal').show().trigger("play").bind('ended', function () {
	document.getElementById('videoLocal').controls = false
	_actual_video += 1;
	if (_actual_video > (_vPublish.length - 1)) {_actual_video = 0;}
	videoLocal.removeChild(source);
	//console.log(_vPublish[_actual_video]);
	source.setAttribute('src', (_vPublish[_actual_video] + '#t=0'));
	videoLocal.appendChild(source);
	videoLocal.play();
	_TOOLS.toFullscreen("videoWrapper"); 
});

function activateCredito() {
	_mode = "creditWrapper";
	videoLocal.pause();
	$(".videoLocal").addClass("d-none");
	$(".creditWrapper").removeClass("d-none");
	$(".creditLocal").removeClass("d-none");
	setTimeout(function () { _TOOLS.toFullscreen("creditWrapper"); }, 50);
}
function deActivateCredito() {
	_mode = "videoWrapper";
	activateDevice($(".btn-connect-device-" + _last_id));
}

function activateDevice(_this) {
	var _id_revision = 208632;
	var _id = _this.attr("data-id");
	_last_id = _id;
	_id_device_active = _id;

	_AJAX.UiActivateDevice({ "id": _id }).then(function (datajson) {
		//console.log(datajson);
		//if (_AJAX._id_user_active == _id_revision) { alert('1 - Respuesta llamada remota: ' + JSON.stringify(datajson)); }
		if (datajson.status == "OK" && datajson.data.length == 1) {
			loadLocalVideo(_this);
			play(_this);
		} else {
			_body = "<h4><span class='badge badge-danger'>El dispositivo está en uso por otro usuario</span></h4>";
			_FUNCTIONS.onInfoModal({ "title": "Estado del dispositivo", "body": _body, "close": true, "size": "modal-lg", "center": false });
		}
	}).catch(function (error) {
		alert(error.message);
	});
}
function deActivateDevice(_this) {
	_FUNCTIONS.onClearTimers();
	videoLocal.pause();
	stop(_this);
	var _id = _this.attr("data-id");
	_AJAX.UiDeActivateDevice({ "id": _id }).then(function (datajson) {
		checkDevices();
		_FUNCTIONS._TIMER_DEVICE_UPDATE = setInterval(function () { checkDevices(); }, _status_interval);
	});

	$(".videoStop").addClass("d-none");
	$(".videoLocal").addClass("d-none");
	$(".creditWrapper").addClass("d-none");
	$(".videoWrapper").addClass("d-none");
	$(".divDevices").removeClass("d-none");
}
function loadLocalVideo(_this) {
	var _id = _this.attr("data-id");
	$(".btn-disconnect-device").attr("data-id", _id);
	$(".divDevices").addClass("d-none");
	$(".videoStop").removeClass("d-none");
	$(".videoLocal").removeClass("d-none");
	$(".creditWrapper").addClass("d-none");
	$(".creditLocal").addClass("d-none");
	videoLocal = document.querySelector("#videoLocal");
	source = document.createElement('source');
	source.setAttribute('src', (_vPublish[_actual_video] + '#t=0'));
	videoLocal.appendChild(source);
	videoLocal.play();
	_TOOLS.toFullscreen("videoWrapper"); 
}
function play(_this) {
	var _id = _this.attr("data-id");
	stop(_this);
	video = document.querySelector("#video");
	if (navigator.mediaDevices.getUserMedia) {
		const constraints = {
			audio: false,
			video: {
				width: _width_capture,
				height: _height_capture
			}
		};
		navigator.mediaDevices.getUserMedia(constraints)
			.then(function (stream) {
				video.srcObject = stream;
				_FUNCTIONS._TIMER_DEVICE = setInterval(function () { updatePicture(_id); }, _video_interval);
			})
			.catch(function (err) {
				//console.log("Error en play() " + err);
			});
	}
}
function stop(_this) {
	try {
		var _id = _this.attr("data-id");
		clearInterval(_FUNCTIONS._TIMER_DEVICE);
		var stream = video.srcObject;
		var tracks = stream.getTracks();
		for (var i = 0; i < tracks.length; i++) { var track = tracks[i]; track.stop(); }
		video.srcObject = null;
		//_AJAX.UiStopDeviceCapture({ "id": _id });
	} catch (e) { }
}  
function updatePicture(_id) {
	var canvas = document.getElementById('canvas');
	var context = canvas.getContext('2d');
	canvas.width = _width_capture;
	canvas.height = _height_capture;
	context.drawImage(video, 0, 0);
	var data = canvas.toDataURL('image/png');
	var _json = { "id": _id, "base64": data }
	_AJAX.UiSendDeviceCapture(_json);
}

function checkDevices() {
	_AJAX.UiCheckDevices({}).then(function (datajson) {
		if (datajson.status == "OK") {
			$.each(datajson.data, function (ndx, item) {
				if (item.id_user_in_use == null || item.id_user_in_use == _AJAX._id_user_active || item.seconds == null) {
					$(".btn-connect-device-" + item.id).attr("disabled", false).html("Conectar").removeClass("btn-secondary").addClass("btn-success").removeClass("d-none");
					$(".img-" + item.id).attr("src", "./assets/img/kiosko.png");
					$("#badge" + item.id).hide();
				} else {
					$(".btn-connect-device-" + item.id).attr("disabled", true).html("Conectado").removeClass("btn-success").addClass("btn-secondary").addClass("d-none");
					$(".img-" + item.id).attr("src", "./assets/img/disconnected.png");
					$("#badge" + item.id).show();
				}
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
checkDevices();
_FUNCTIONS._TIMER_DEVICE_UPDATE = setInterval(function () { checkDevices(); }, _status_interval);
