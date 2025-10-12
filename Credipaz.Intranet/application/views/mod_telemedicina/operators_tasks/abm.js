//Variables 
var _id_charge_code = "";
var fileName = "";
let recorder;
let recordingData = [];
let recorderStream;

//Hooks
//Attach of events related to objects in the interface
//The interface, has not any event into the html view page

function initAbm(_id, _ro, _id_type_task_close) {
	_id_charge_code = _id;
	fileName = (_id_charge_code + ".webm");
	if (_ro) {
		$("select").attr("disabled", "disabled");
		if (_id_type_task_close != "") {
			$(".view-audit").removeClass("d-none");
			$(".btn-videoconferencia").addClass("d-none");
			$(".btn-record-play").click();
		} else {
			$(".btn-videoconferencia").removeClass("d-none");
			$(".area-controls").addClass("d-none");
		}
	} else {
		$(".view-medic").removeClass("d-none");
		$(".btn-videoconferencia").removeClass("d-none");
		$(".btn-record-start").click();
	}
	clearInterval(_FUNCTIONS._TIMER_FORM);
	_FUNCTIONS.onLoadPreviousIndicaciones(_id, ".div-indicaciones");

	_FUNCTIONS.onLoadPreviousTelemedicina(_id, ".div-atenciones");
	_FUNCTIONS.onLoadMessagesTelemedicina(_id, ".div-imagenes", ".div-comunicaciones");
	_FUNCTIONS._TIMER_FORM = setInterval(
		function () {
			_FUNCTIONS.onLoadMessagesTelemedicina(_id, ".div-imagenes", ".div-comunicaciones");
		}, 30000);
	$("body").scrollTop();
}
function mixer(stream1, stream2) {
	const ctx = new AudioContext();
	const dest = ctx.createMediaStreamDestination();
	if (stream1.getAudioTracks().length > 0) { ctx.createMediaStreamSource(stream1).connect(dest); }
	if (stream2.getAudioTracks().length > 0) { ctx.createMediaStreamSource(stream2).connect(dest); }
	let tracks = dest.stream.getTracks();
	tracks = tracks.concat(stream1.getVideoTracks()).concat(stream2.getVideoTracks());
	return new MediaStream(tracks)
}
$("body").off("click", ".btn-record-start").on("click", ".btn-record-start", async () => {
	let gumStream, gdmStream;
	recordingData = [];
	try {
		gumStream = await navigator.mediaDevices.getUserMedia({ video: false, audio: true });
		gdmStream = await navigator.mediaDevices.getDisplayMedia({ video: { frameRate: 1, width: 320, height: 200, displaySurface: "browser" }, audio: true });
	} catch (e) {
		console.error("capture failure", e);
		return
	}
	recorderStream = gumStream ? mixer(gumStream, gdmStream) : gdmStream;
	recorder = new MediaRecorder(recorderStream, { mimeType: 'video/webm;codecs=vp8,opus' });
	recorder.ondataavailable = e => {
		if (e.data && e.data.size > 0) {
			let reader = new FileReader()
			reader.onloadend = () => {
				_AJAX._waiter = false;
				_AJAX.UiTelemedicinaSendAuditAV({ "id": _id_charge_code, "filename": fileName, "base64": reader.result });
			}
			reader.readAsDataURL(e.data);
			recordingData.push(e.data);
		}
	};
	recorder.onStop = () => {
		recorderStream.getTracks().forEach(track => track.stop());
		gumStream.getTracks().forEach(track => track.stop());
		gdmStream.getTracks().forEach(track => track.stop());
	};
	recorder.start();
	//clearInterval(_FUNCTIONS._TIMER_CAPTURE);
	//_FUNCTIONS._TIMER_CAPTURE = setInterval(function () {
	//	if (recorder.state === 'recording') { recorder.requestData(); }
	//}, 15000);
});
$("body").off("click", ".btn-record-stop").on("click", ".btn-record-stop", () => {
	clearInterval(_FUNCTIONS._TIMER_CAPTURE);
	try { recorder.stop(); } catch (e) { }
});
$("body").off("click", ".btn-record-save").on("click", ".btn-record-save", () => {
	const blob = new Blob(recordingData, { type: 'video/webm' });
	const url = window.URL.createObjectURL(blob);
	const a = document.createElement('a');
	a.style.display = 'none';
	a.href = url;
	a.download = fileName;
	document.body.appendChild(a);
	a.click();
	setTimeout(() => {
		document.body.removeChild(a);
		window.URL.revokeObjectURL(url);
	}, 100);
});
$("body").off("click", ".btn-record-play").on("click", ".btn-record-play", () => {
	var playback = document.getElementById("recordPlayback");
	playback.src = (_AJAX.server + "api.v1.files?file=/datos/telemedicina/" + _id_charge_code + ".webm");
	playback.play();
});


$("body").off("click", ".btn-emergency").on("click", ".btn-emergency", function () {
	var _param = { "module": "mod_web_posts", "table": "web_posts", "model": "web_posts", "order": "id ASC", "page": -1, "pagesize": -1, "where": "id=12" };
	_AJAX.UiGet(_param).then(function (_speech) {
		var _data = { "module": "mod_telemedicina", "table": "type_emergency", "model": "type_emergency", "order": "id ASC", "page": -1, "pagesize": -1 };
		_AJAX.UiGet(_data).then(function (datajson) {
			var _html = "";
			_html += _speech["data"][0]["body_post"];
			_html += "<br/>";
			_html += "<b>CLASIFICACIÓN TRIAGE</b>";
			_html += "<select id='id_type_emergency' name='id_type_emergency' class='id_type_emergency form-control dbase validate'></select>";
			_html += "<br/>";
			_html += "<b>Detalles adicionales</b>";
			_html += "<textarea class='form-control emergency_details dbase' id='emergency_details' name='emergency_details' style='width:100%;' rows='10'></textarea>";
			_html += "<br/>";
			_html += "<a href='#' class='btn btn-success btn-lg btn-confirm-emergency btn-raised btn-block'>La ambulancia está en camino</a>";
			_FUNCTIONS.onShowInfo(_html, "EMERGENCIA - Envío de ambulancia");
			_FUNCTIONS._cache.type_emergency = datajson;
			_TOOLS.loadCombo(datajson, { "target": "#id_type_emergency", "selected": -1, "id": "id", "description": "description" });

			$("body").off("click", ".btn-confirm-emergency").on("click", ".btn-confirm-emergency", () => {
				if (_TOOLS.validate(".validate", true)) {
					var _json = _TOOLS.getFormValues(".dbase");
					_json["id"] = $(".btn-emergency").attr("data-id");
					_AJAX.UiEmergency(_json).then(function (data) {
						$(".ambulance").addClass("card").html(data.data);
						_FUNCTIONS.onDestroyModal("#modal-info");
					}).catch(function (err) {
						alert(err.message);
					});
				}
			});
		});
	});
});
