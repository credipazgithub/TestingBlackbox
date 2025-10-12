var _id_active = 0;
var _activeCatalog = "";
var _new = false;
var _iLazy = 0;
var _LW = [];
_NEOVIDEO._username = "mil";
_NEOVIDEO._password = "08.!Rcp#@80";
_NEOVIDEO._CONFIG_OVERWRITE.hideConferenceSubject = true;

function calculateWidthFromHeight(height, aspectRatioWidth = 18, aspectRatioHeight = 9) {
	return (height / aspectRatioHeight) * aspectRatioWidth;
}

// Example usage:
function fillWaiting() {
	_AJAX.UiInformUserArea({ "last_area": "tienda mil" }).then(function (datajson) { }).catch(function (error) { alert(error.message); });
	_NEOVIDEO.onListAvailableVideoRooms().then(function (data) {
		var _bAlert = false;
		var _html = "";
		$.each(data.records, function (i, item) {
			if (item.id_type_status == 1) {
				_AJAX.UiAlertTelegramTiendaMil({ "id": item.id }).then(function (data) { });
				var _msg = "Aguardando atención";
				var _style = " style='padding:10px;background-color:transparent;color:black;cursor:pointer;border:solid 1px silver;border-radius:10px;' ";
				var _rec = _TOOLS.utf8_to_b64(JSON.stringify(item));
				var _color = "success";
				var _color2 = "secondary";
				if (item.id == _id_active) {
					_style = " style='padding:10px;background-color:green;color:white;cursor:pointer;border-radius:10px;' ";
					_msg = "¡En atención!";
					_color2 = "light";
				}
				_html += "<li class='chat-" + item.id + " list-group-item btn-join-chat' data-record='" + _rec + "' " + _style + ">";
				if (item.seconds > 15 && item.seconds < 30) { _color = "info"; }
				if (item.seconds > 30 && item.seconds < 60) { _color = "warning"; }
				if (item.seconds > 60) {
					_color = "danger";
					_msg = "Por finalizar espera";
					_color2 = "info";
				}
				_html += "   <i class='material-icons'>videocam</i><span class='badge badge-" + _color + "'>" + item.waiting + "</span> <span class='badge badge-" + _color2 + "'>" + _msg + "</span> <span class='badge badge-dark'>#" + item.id + "</span>";
				_html += "   <div class='origin-" + item.id + "'></div>";
				_html += "</li>";
			}
		});
		$(".divWaiting").html(_html);
		$.each(data.records, function (i, item) {
			if (item.id_type_status == 1) {
				if (item.transaction.id_external != 0) {
					//$(".origin-" + item.id).html("");
					_AJAX.UiCatalogoMIL({ "where": ("id=" + item.transaction.id_external) }).then(function (_datajson) {
						$.each(_datajson.data, function (i, origin) {
							var _html = "<table style='width:100%;'>";
							_html += "      <tr>";
							_html += "         <td style='width:100px;'><img src='" + origin.image + "'style='width:100%;'/><td>";
							_html += "         <td><span class='badge badge-dark'>$ " + parseFloat(origin.valorized).toLocaleString('de') + "</span><td>";
							_html += "         <td>" + origin.description + "<td>";
							_html += "      </tr>";
							_html += "<table>";
							$(".origin-" + item.id).html(_html);
						});

					});
				}
			}
		});

		if (_bAlert) { $("#ringerTiendaMil").attr("src", "assets/audio/vintage.mp3"); }
	});
}
function initCamera(url) {
	var newWindow = window.open(url, '_blank'); 
	if (newWindow) { 
		_LW.push(newWindow);
	} else {
		alert("¡Por favor deshabilite el bloqueador de ventanas emergentes!");
	}
}
function toggleCamera(url, _target) {
	$(".imgBig").remove();
	$.blockUI({ message: '<img src="https://intranet.credipaz.com/assets/img/wait.gif" />', css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
	var _html = "";
	_html += "<div class='imgBig' style='position:absolute;left:0px;top:0px;width:calc(100% - 113px);height:90vh;z-index:9999998;'>"
	_html += "<img src='" + url + "' style='width:100%;height:100%;'/>";
	_html += "</div>";
	$(".dyn-area").append(_html);
	$(".imgBig").width(calculateWidthFromHeight($(".imgBig").height()));
	setTimeout(function () {
		$.unblockUI();
		$(".areaClose").removeClass("d-none");
	}, 1500);
}
function buildInterface() {
	var _html = "";
	// for testing
	//_html += "<button type='button' class='btn bg-info btn-show-catalogo' style='color:white;'>Catálogo</button>";
	_html += "<div class='areaVideo d-none' style='position:absolute;left:0px;top:0px;width:100vw;height:100vh;overflow:hidden;'>";
	_html += "   <div id='meet' class='meet p-0 m-0 d-none' style='width:640px;display:none;overflow:hidden;'></div>";
	_html += "   <div class='catalogo dropdown m-2' style='position:absolute;left:0px;top:0px;'>";
	_html += "      <button type='button' class='btn bg-info btn-show-catalogo' style='color:white;'>Catálogo</button>";
	_html += "      <button type='button' class='btn btn-sm bg-secondary btn-toggle-camera ml-4' style='color:white;'><i class='material-icons icon-silence'>flip_camera_android</i></button>";
	_html += "   </div>";
	_html += "</div>";
	$(".areaVideo").remove();
	LoadCatalogo().then(function () {
		$(".loading-catalog").fadeOut("fast", function () {
			$(".loaded-catalog").removeClass("d-none");
			$("body").append(_html);
		});
	});
}
function LoadCatalogo() {

	return new Promise(
		function (resolve, reject) {
			try {
				if (_activeCatalog == "") {
					_AJAX.UiCatalogoMIL({}).then(function (_datajson) {
						_activeCatalog = buildCatalog(_datajson);
						resolve(null);
					}).catch(function (err) {
						reject(err);
					});
				} else {
					resolve(null);
				}
			} catch (rex) {
				reject(rex);
			}
		});
};
function ShowCatalogo() {
	_FUNCTIONS.onDestroyModal("#initCatalogo");
	LoadCatalogo().then(function () {
		$("body").append(_activeCatalog);
		updateCatalog();
		$(".divClose").show();
		$("#initCatalogo").modal({ backdrop: false, keyboard: false, show: true });
	});
	$("body").off("click", ".btn-close-modal").on("click", ".btn-close-modal", function () {
		$(".rx-hidden").fadeIn("fast");
		$("#initCatalogo").remove();
		$(".modal-backdrop").remove();
		$("body").css({ "overflow": "auto" });
		$(".divClose").hide();
	});
}
function updateCatalog() {
	if (_new) {
		_new = false;
		$(".btn-sendData").show();
		$(".btn-deleteDataInClient").hide();
	}
	_activeCatalog = "<div class='modal fade' id='initCatalogo' role='dialog' style='width:100vw;'>";
	_activeCatalog += $("#initCatalogo").html();
	_activeCatalog += "</div>";
}
function buildCatalog(_datajson) {
	var _html = "<div class='modal fade' id='initCatalogo' role='dialog' style='width:100vw;'>";
	_html += "      <div class='modal-wait-frame modal-dialog p-0 m-0'>";
	_html += "         <div class='modal-content'>";
	_html += "            <div class='modal-header m-0 p-1'>";
//	_html += "               <div class='divClose' style='position:absolute;top:0;right:0;z-index:9999999;'>";
//	_html += "                   <a href='#' class='btn btn-raised btn-primary btn-close-modal btn-close-catalog m-2'>Cerrar catálogo</button>";
//	_html += "               </div>";
	_html += "               <h4>Catálogo </h4>";
	_html += "               <button type='button' class='close btn-close-modal btn-close-catalog' data-dismiss='modal' style='font-size:42px;'>&times;</button>";
	_html += "            </div>";
	_html += "            <div class='modal-header m-0 p-1 mt-1'>";
	_html += "               <input id='searchInput' name='searchInput' type='text' class='mx-1 form-control searchInput' placeholder='Buscar...' />";
	_html += "               <i class='material-icons' style='font-size:22px;vertical-align:middle;'>search</i>"
	_html += "            </div>";
	_html += "            <div class='modal-body m-1 p-1'>";
	_html += "			     <table id='allItems' style='width:100%;'>";
	/* Carga de items del catalogo! */
	$.each(_datajson.data, function (i, item) {
		var _obj = { "id_item": item.id, "id_transaction": _id_active, "image": item.image, "description": item.description, "precio": item.valorized, "code": item.code };
		_html += itemCatalogo(_id_active, _obj);
	});
	_html += "			     </table>";
	_html += "            </div>";
	_html += "         </div>";
	_html += "      </div>";
	_html += "   </div>";
	return _html;
}
function itemCatalogo(_id_transaction, _obj) {
	var _record = _TOOLS.utf8_to_b64(JSON.stringify(_obj));
	var _html = "";
	_html += "<tr style='font-size:0.70rem;' class='m-1 shadow trItem trItem-" + _obj.id_item + "' >";
	_html += "   <td style='width:30px;' valign='middle' align='center' class='btn-sendData send-" + _obj.id_item + "' data-id_item='" + _obj.id_item + "' data-id_transaction='" + _id_transaction + "' data-record='" + _record + "'>";
	_html += "      <i class='material-icons'>shopping_cart_checkout</i>";
	_html += "   </td>";
	_html += "   <td style='width:30px;color:red;display:none;' valign='middle' align='center' class='btn-deleteDataInClient client-" + _obj.id_item + "' data-id_item='" + _obj.id_item + "' data-id='0'>";
	_html += "      <i class='material-icons'>history</i>";
	_html += "   </td>";
	_html += "   <td valign='middle' class='p-1' style='width:75px;'>";
	_html += "	    <img src='" + _obj.image + "' style='width:100%;'/>";
	_html += "	 </td>";
	_html += "   <td valign='top'><p class='m-0 px-1'>" + _obj.description + "</p></td>";
	_html += "   <td style='width:75px;' valign='top' align='right'><p class='m-0 px-1'>$ " + parseFloat(_obj.precio).toLocaleString('de') + "</p></td>";
	_html += "</tr>";
	return _html;
}
function startLiveStreaming() {
  	 $.blockUI({ message: '<img src="https://intranet.credipaz.com/assets/img/wait.gif" />', css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });

	_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.caller = "Tienda MIL";
	_NEOVIDEO._INTERFACE_CONFIG_OVERWRITE.TOOLBAR_BUTTONS = ['microphone', 'camera', 'hangup', 'desktop', 'chat','desktop'];
	_NEOVIDEO._CONFIG_OVERWRITE.startWithAudioMuted = false;
	_NEOVIDEO._CONFIG_OVERWRITE.startWithVideoMuted = false;
	_NEOVIDEO._CONFIG_OVERWRITE.disableSelfView = false;
	_NEOVIDEO._CONFIG_OVERWRITE.disableSelfViewSettings = false;

	_NEOVIDEO.onDisconnect = function () {
		/*Kick out all joined remaining viewers*/
		$(".btn-live-stop").click();
	};
	_NEOVIDEO.onParticipantJoined = function (data) {
		//console.log("joined");
		//console.log(data);
	};
	_NEOVIDEO.onParticipantLeft = function (data) {
		//console.log("left");
		//console.log(data);
	};
	var _config = { "id_external": 0, "live": 1 };
	_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height = "100vh";
	_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width = "100vw";
	_NEOVIDEO.onCreateNewVideoRoom($(this), _config).then(function (data) {
		$("#meet").css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
		$("#meet").css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
		$('[name ^= "jitsiConferenceFrame"]').css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
		$(_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.target).fadeIn("slow");
		$(".areaVideo").removeClass("d-none");
		$("#meet").removeClass("d-none").fadeIn("slow");
		$(".catalogo").addClass("d-none");
		$.unblockUI();
	});
}
function endVideoCall() {
	_FUNCTIONS.onDestroyModal("#initCatalogo");
	$(".areaVideo").addClass("d-none");
	$("#meet").addClass("d-none").hide();
	$(".catalogo").addClass("d-none");
	$(".btn-close-catalog").click();
	$(".chat-" + _id_active).remove();
	_id_active = 0;
	setTimeout(function () {
		//alert("Videollamada finalizada");
	}, 100);
}

$("body").off("click", ".btn-toggle-camera").on("click", ".btn-toggle-camera", function () {
	_NEOVIDEO._API.executeCommand('toggleCamera');
});
$("body").off("click", ".btn-sendData").on("click", ".btn-sendData", function () {
	var _id_item = $(this).attr("data-id_item");
	var _id_transaction = _id_active;
	var _raw_data = $(this).attr("data-record");
	var _obj = { "id_item": _id_item, "id": _id_transaction, "raw_data": _raw_data, "description": "" };
	_NEOVIDEO.onSendRelatedData(_obj).then(function (data) {
		var _id = data.numeric;
		$(".send-" + _id_item).fadeOut("fast", function () {
			$(".client-" + _id_item).fadeIn("fast").attr("data-id", _id);
			updateCatalog();
		})
	});
});
$("body").off("click", ".btn-deleteDataInClient").on("click", ".btn-deleteDataInClient", function () {
	var _id = $(this).attr("data-id");
	var _id_item = $(this).attr("data-id_item");
	var _obj = { "id": _id };
	_NEOVIDEO.onDeleteDataInClient(_obj).then(function (data) {
		$(".client-" + _id_item).attr("data-id","0").fadeOut("fast", function () {
			$(".send-" + _id_item).fadeIn("fast");
			updateCatalog();
		})
	});
});
$("body").off("click", ".btn-show-catalogo").on("click", ".btn-show-catalogo", function () {
	ShowCatalogo();
});
$("body").off("click", ".btn-join-chat").on("click", ".btn-join-chat", function () {
	var _item = JSON.parse(_TOOLS.b64_to_utf8($(this).attr("data-record")))
	if (_id_active != 0) {
		if (_id_active != _item.id) { alert("¡Al estar en una atención, no puede iniciar otra!"); }
		return false;
	}
	$.blockUI({ message: '<img src="https://intranet.credipaz.com/assets/img/wait.gif" />', css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });

	_NEOVIDEO._INTERFACE_CONFIG_OVERWRITE.TOOLBAR_BUTTONS = ['microphone', 'camera', 'hangup', 'chat', 'tileview','desktop'];
	_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height = "100vh";
	_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width = "100vw";
	_NEOVIDEO._CONFIG_OVERWRITE.disableSelfView = false;
	_NEOVIDEO._CONFIG_OVERWRITE.disableSelfViewSettings = false;

	_NEOVIDEO.onDisconnect = function () { endVideoCall(); };
	_NEOVIDEO.onParticipantLeft = function (data) {_NEOVIDEO._API.executeCommand('hangup');};

	/*Additional events*/
	//_NEOVIDEO.onLog = function (data) { console.log("onLog->"); console.log(data); };
	//_NEOVIDEO.onVideoConferenceJoined = function (data) { console.log("onVideoConferenceJoined->"); console.log(data); };
	//_NEOVIDEO.onVideoConferenceLeft = function (data) { console.log("onVideoConferenceLeft->"); console.log(data); };
	//_NEOVIDEO.onBrowserSupport = function (data) { console.log("onBrowserSupport->"); console.log(data); };
	//_NEOVIDEO.onBreakoutRoomsUpdated = function (data) { console.log("onBreakoutRoomsUpdated->"); console.log(data); };
	//_NEOVIDEO.onErrorOccurred = function (data) { console.log("onErrorOccurred->"); console.log(data); };

	_NEOVIDEO.onJoinOpenSession(_item.id).then(function (data) {
		_new = true;
		_id_active = _item.id;
		fillWaiting();
		$(".areaVideo").removeClass("d-none");
		$("#meet").removeClass("d-none").fadeIn("slow");
		$(".catalogo").removeClass("d-none");
		$('[id^="jitsiConferenceFrame"]').css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
		$("#meet").css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
		$.unblockUI();
	}).catch(function (err) {
		$(".chat-" + _id_active).remove();
		alert("¡El cliente ya no está en espera!");
	});
});

$("body").off("click", ".btn-live-start").on("click", ".btn-live-start", function () {
	if (!confirm("¿Confirma el inicio de la transmisión en vivo?")) { return false; }
	startLiveStreaming();
	$(this).addClass("d-none");
	$(".btn-live-stop").removeClass("d-none");
});
$("body").off("click", ".btn-live-stop").on("click", ".btn-live-stop", function () {
	$(".areaVideo").addClass("d-none");
	$("#meet").addClass("d-none").hide();
	$(this).addClass("d-none");
	$(".btn-live-start").removeClass("d-none");
});

fillWaiting();
clearInterval(_FUNCTIONS._TIMER_DEVICE);
_FUNCTIONS._TIMER_DEVICE = setInterval(function () { fillWaiting(); }, 1000);

$("body").off("keyup", ".searchInput").on("keyup", ".searchInput", function () {
	var _val = $(this).val();
	_iLazy = setTimeout(function () {
		clearTimeout(_iLazy);
		_TOOLS.filterTable("searchInput", "allItems", 3);
	}, 750);
});
