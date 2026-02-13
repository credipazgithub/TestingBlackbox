_FUNCTIONS = {
	GLOOGLE_API_KEY: "AIzaSyAm2l3M0cVh_FZ-fa7R5K81iirb2lWZne4",
	_cdn_server: "https://cdn.gruponeodata.com/",
	_forcedAlias: "",
	_vLog: ["lcuello", "gsaltamirano"],
	_oLast_record: null,
	_sessionUID: null,
	_silence: false,
	_API_VIDEO: null,
	_logo_receta_left: "",
	_logo_receta_right: "",
	_logo_receta_footer: "",
	_scrollY: 0,
	_cache: {},
	_croppie: null,
	_ATTACH_LIMIT: 1.5,
	_timerPushAlert: 0,
	_id_channel: 2,
	_defaultAttachDir: "./attached/threads/",
	_defaultBrowserSearch: "browser_search",
	_defaultBrowserSearchOperator: "like",
	_defaultBrowserSearchFields: ["code", "description"],
	_defaultProviderFooter: "<img src='https://intranet.credipaz.com/assets/img/small.png' style='width:32px;' /><a href='https://intranet.credipaz.com' target='_blank'>Credipaz</a>",
	_max_filesize_upload: 50,
	_TIMEOUT_ALERT: 3000,
	_TIMER_MODAL: 0,
	_TIMER_INTRANET: 0,
	_TIMER_FORM: 0,
	_TIMER_CAPTURE: 0,
	_TIMER_TIENDAMIL: 0,
	_TIMER_LAZY: 0,
	_TIMER_DEVICE: 0,
	_TIMER_DEVICE_UPDATE: 0,
	_TMR_PAY_BOTONPAGO: 0,
	_last_form: "",
	_first_vademecum: "",
	_itemsPagos: [],
	onDoCaptureDIV: function (_this) {
		var _id = _this.attr("data-target");
		window.scrollTo(0, 0);
		html2canvas(document.getElementById(_id)).then(function (canvas) {$(".test-image").attr("src", canvas.toDataURL("image/jpeg", 1));});
	},
	onClearTimers: function () {
		try { clearInterval(_FUNCTIONS._TMR_PAY_BOTONPAGO); } catch (e) { }
		try { clearInterval(_FUNCTIONS._TIMER_FORM); } catch (e) { }
		try { clearInterval(_FUNCTIONS._TIMER_CAPTURE); } catch (e) { }
		try { clearInterval(_FUNCTIONS._TIMER_DEVICE); } catch (e) { }
		try { clearInterval(_FUNCTIONS._TIMER_DEVICE_UPDATE); } catch (e) { }
		try { clearInterval(_VIDEOCHAT._tmrVideoActive); } catch (e) { }
		try { clearInterval(_VIDEOCHAT._tmrCountdown); } catch (e) { }
		try { clearInterval(_TOOLS._tmrClock); } catch (e) { }

		try {
			recorder.stop();
		} catch (e) { }
	},
	onReloadInit: function () {
		$(".sidebar-wrapper").fadeOut("slow");
		$(".dyn-area").fadeOut("slow", function () { setTimeout(function () { window.location = "/"; }, 10000); });
	},

	onAlert: function (_json) {
		try {
			clearTimeout(_FUNCTIONS._timerPushAlert);
			$(".push-alert").remove();

			if (typeof _json["message"] === 'object') {
				_text = "";
			} else {
				_text = _json["message"];
			}
			if (_text == undefined) { _text = ""; }
			if (_text == "") { return false; }
			var _html = "<div class='push-alert alert " + _json["class"] + " alert-dismissible fade show' role='alert'>";
			_html += "<button type='button' class='close' style='position:absolute;right:20px;' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
			_html += _text;
			_html += "</div>";
			$(".alert-frame").append(_html);
			_FUNCTIONS._timerPushAlert = setTimeout(function () { $(".push-alert").alert('close') }, 7500);
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onShowAlert: function (_message, _title) {
		_FUNCTIONS.onDestroyModal("#modal-alert");
		if (_title == undefined) { _title = ""; }
		var _html = "";
		_html += "<div id='modal-alert' class='modal fade' style='z-index:9999;'>";
		_html += "   <div class='modal-dialog'>";
		_html += "      <div class='modal-content'>";
		_html += "         <div class='modal-header'>";
		if (_title != "") {
			_html += "            <h4>" + _title + "<button type='button' style='position:absolute;right:20px;' class='close pull-right' data-dismiss='modal' aria-hidden='true'>&times;</button></h4>";
		} else {
			_html += "            <button type='button' class='close' style='position:absolute;right:20px;' data-dismiss='modal' aria-hidden='true'>&times;</button>";
		}
		_html += "         </div>";
		_html += "         <div class='modal-body danger alert-danger'>";
		_html += "            <p style='color:darkred;'>" + _message + "</p>";
		_html += "         </div>";
		_html += "      </div>";
		_html += "   </div>";
		_html += "</div>";
		$("body").append(_html);
		$("#modal-alert").on('hide.bs.modal', function () { clearInterval(_FUNCTIONS._TIMER_MODAL); });
		$("#modal-alert").modal({ backdrop: true, keyboard: true });
		_FUNCTIONS._TIMER_MODAL = setTimeout(function () { _FUNCTIONS.onDestroyModal("#modal-alert"); }, _FUNCTIONS._TIMEOUT_ALERT);
	},
	onShowInfoPDF: function (_message, _title) {
		_FUNCTIONS.onDestroyModal("#modal-infoPDF");
		if (_title == undefined) { _title = ""; }
		var _html = "";
		_html += "<div id='modal-infoPDF' class='modal fade' style='z-index:9999;overflow-y:auto;'>";
		_html += "   <div class='modal-dialog'>";
		_html += "      <div class='modal-content'>";
		_html += "         <div class='modal-header px-4 m-0'>";
		if (_title != "") {
			_html += _title + "<button type='button' class='close pull-right' style='position:absolute;right:20px;' data-dismiss='modal' aria-hidden='true'>&times;</button>";
		} else {
			_html += "            <button type='button' class='close' style='position:absolute;right:20px;' data-dismiss='modal' aria-hidden='true'>&times;</button>";
		}
		_html += "         </div>";
		_html += "         <div class='pt-1 mt-1 modal-body danger alert-default'>";
		_html += "            <p style='color:darkred;'>" + _message + "</p>";
		_html += "         </div>";
		_html += "      </div>";
		_html += "   </div>";
		_html += "</div>";
		$("body").append(_html);
		$("#modal-infoPDF").on('hide.bs.modal', function () { });
		$("#modal-infoPDF").modal({ backdrop: true, keyboard: true });
	},
	onShowInfo: function (_message, _title) {
		_FUNCTIONS.onDestroyModal("#modal-info");
		if (_title == undefined) { _title = ""; }
		var _html = "";
		_html += "<div id='modal-info' class='modal fade' style='z-index:9999;'>";
		_html += "   <div class='modal-dialog'>";
		_html += "      <div class='modal-content'>";
		_html += "         <div class='modal-header'>";
		if (_title != "") {
			_html += "            <h4>" + _title + "<button type='button' style='position:absolute;right:20px;' class='close pull-right' data-dismiss='modal' aria-hidden='true' style='position:absolute;right:15px;'>&times;</button></h4>";
		} else {
			_html += "            <button type='button' class='close pull-right' style='position:absolute;right:20px;' data-dismiss='modal' aria-hidden='true' style='position:absolute;right:15px;'>&times;</button>";
		}
		_html += "         </div>";
		_html += "         <div class='modal-body danger alert-default'>";
		_html += "            <p style='color:darkred;'>" + _message + "</p>";
		_html += "         </div>";
		_html += "      </div>";
		_html += "   </div>";
		_html += "</div>";
		$("body").append(_html);
		$("#modal-info").on('hide.bs.modal', function () { });
		$("#modal-info").modal({ backdrop: false, keyboard: true });
	},
	onShowHtmlModal: function (_title, _message, _callback) {
		_FUNCTIONS._scrollY = window.scrollY;
		_FUNCTIONS.onDestroyModal("#modal-html");
		if (_title == undefined) { _title = ""; }
		var _html = "";
		_html += "<div id='modal-html' class='modal fade' style='z-index:9999;'>";
		_html += "   <div class='modal-dialog'>";
		_html += "      <div class='modal-content'>";
		_html += "         <div class='modal-header'>";
		if (_title != "") {
			_html += "            <h4>" + _title + "<button type='button' style='position:absolute;right:20px;' class='close pull-right' data-dismiss='modal' aria-hidden='true'>&times;</button></h4>";
		} else {
			_html += "            <button type='button' style='position:absolute;right:20px;' class='close pull-right' data-dismiss='modal' aria-hidden='true'>&times;</button>";
		}
		_html += "         </div>";
		_html += "         <div class='modal-body danger alert-default'>" + _message + "</div>";
		_html += "      </div>";
		_html += "   </div>";
		_html += "</div>";
		$("body").append(_html);
		$("#modal-html").on('hide.bs.modal', function () {
			setTimeout(function () { window.scrollTo(0, _FUNCTIONS._scrollY); }, 250);
		});
		$("#modal-html").modal({ backdrop: false, keyboard: false });
		if ($.isFunction(_callback)) { _callback(); }
	},

	onDestroyModal: function (_target) {
		$(".rx-hidden").fadeIn("fast");
		$(_target).remove();
		$(".modal-backdrop").remove();
		$("body").css({ "overflow": "auto" });
		//$("body").removeClass("modal-open");
	},
	onInfoModal: function (_json, _callBack, _callBack2) {
		try {
			_FUNCTIONS.onDestroyModal("#infoModal");
			if (_json["close"] == undefined) { _json["close"] = false; }
			if (_json["size"] == undefined) { _json["size"] = "modal-md"; }
			if (_json["center"] == undefined) { _json["center"] = ""; }
			if (_json["center"] === true) { _json["center"] = "modal-dialog-centered"; } else { _json["center"] = ""; }
			var _html = "<div class='modal fade' id='infoModal' role='dialog'>";
			_html += " <div class='modal-dialog modal-dialog-scrollable " + _json["center"] + " " + _json["size"] + "' role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>" + _json["title"] + "</h4>";
			if (_json["close"]) { _html += "<button type='button' style='position:absolute;right:20px;' class='close btn-close-modal'>&times;</button>"; }
			_html += "    </div>";
			_html += "    <div class='modal-body'>";
			_html += _json["body"];
			if (!_json["close"]) {
				_html += "       <div class='progress' style='height:5px;'>";
				_html += "          <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width:100%;'></div>";
				_html += "       </div>";
			}
			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += _FUNCTIONS._defaultProviderFooter;
			_html += "</div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);

			$("body").off("click", ".btn-close-modal").on("click", ".btn-close-modal", function () {
				_FUNCTIONS.onDestroyModal("#infoModal");
				if ($.isFunction(_callBack2)) { _callBack2(); }
			});

			$('.trumbo').trumbowyg({ lang: 'es_ar' });
			if ($.isFunction(_callBack)) { _callBack(); }

			$("#infoModal").modal({ backdrop: false, keyboard: true, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onBriefModal: function (_this) {
		var _title = _this.attr("data-title");
		var _body = _this.attr("data-body");
		if (_body == "" || _body == null || _body == undefined) { _body = ""; } else { _body = _TOOLS.b64_to_utf8(_body); }
		_FUNCTIONS.onInfoModal({ "title": _title, "body": _body, "close": true, "size": "modal-xl", "center": false });
	},

	onEmailModal: function (_json) {
		try {
			_FUNCTIONS.onDestroyModal("#emailModal");
			if (_json["close"] == undefined) { _json["close"] = false; }
			if (_json["size"] == undefined) { _json["size"] = "modal-lg"; }
			if (_json["center"] == undefined) { _json["center"] = ""; }
			var _html = "<div class='modal fade' id='emailModal' role='dialog'>";
			_html += " <div class='modal-dialog modal-dialog-centered " + _json["size"] + "' role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>" + _json["title"] + "</h4>";
			if (_json["close"]) { _html += "<button type='button' class='close btn-close-modal' data-dismiss='modal'>&times;</button>"; }
			_html += "    </div>";
			_html += "    <div class='modal-body'>";
			_html += _json["body"];
			if (!_json["close"]) {
				_html += "       <div class='progress' style='height:5px;'>";
				_html += "          <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width:100%;'></div>";
				_html += "       </div>";
			}
			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += _FUNCTIONS._defaultProviderFooter;
			_html += "</div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);
			$('.trumbo').trumbowyg({ lang: 'es_ar' });
			$("body").off("drop", ".drop_zone").on("drop", ".drop_zone", function (ev) {
				$(this).removeClass("drop_zone_over");
				ev.preventDefault();
				if (ev.originalEvent.dataTransfer.items) {
					for (var i = 0; i < ev.originalEvent.dataTransfer.items.length; i++) {
						if (ev.originalEvent.dataTransfer.items[i].kind === 'file') {
							var file = ev.originalEvent.dataTransfer.items[i].getAsFile();
							if (file.size > (_FUNCTIONS._ATTACH_LIMIT * 1024000)) {
								$(".ls-images").append("<li class='list-group-item' style='padding:10px;'>¡No se adjuntará <span class='label label-danger'>" + file.name + "</span> porque excede los " + _FUNCTIONS._ATTACH_LIMIT + "mb!</li>");
							} else {
								var reader = new FileReader();
								reader.onloadend = (function (data) { return function (evt) { $(".ls-images").append(_TOOLS.createFileItem(data.name, evt.target.result)); } })(ev.originalEvent.dataTransfer.items[i].getAsFile());
								reader.readAsDataURL(file);
							}
						}
					}
				} else {
					for (var i = 0; i < ev.originalEvent.dataTransfer.files.length; i++) {
						var file = ev.originalEvent.dataTransfer.files[i].getAsFile();
						if (file.size > (_FUNCTIONS._ATTACH_LIMIT * 1024000)) {
							$(".ls-images").append("<li class='list-group-item' style='padding:10px;'>¡No se adjuntará <span class='label label-danger'>" + file.name + "</span> porque excede los " + _FUNCTIONS, _ATTACH_LIMIT + "mb!</li>");
						} else {
							var reader = new FileReader();
							reader.onloadend = (function (data) { return function (evt) { $(".ls-images").append(createFileItem(data.name, evt.target.result)); } })(ev.originalEvent.dataTransfer.files[i].getAsFile());
							reader.readAsDataURL(file);
						}
					}
				}
				if (ev.originalEvent.dataTransfer.items) {
					ev.originalEvent.dataTransfer.items.clear();
				} else {
					ev.originalEvent.dataTransfer.clearData();
				}
			});
			$("body").off("dragover", ".drop_zone").on("dragover", ".drop_zone", function (ev) {
				$(this).addClass("drop_zone_over");
				ev.preventDefault();
			});
			$("body").off("dragleave", ".drop_zone").on("dragleave", ".drop_zone", function (ev) {
				$(this).removeClass("drop_zone_over");
				ev.preventDefault();
			});
			$("body").off("click", ".btn-deattach").on("click", ".btn-deattach", function () {
				if (!confirm("¿Confirma la operación?")) { return false; }
				$("." + $(this).attr("data-id")).remove();
			});
			$("body").off("click", ".btn-send-reply").on("click", ".btn-send-reply", function () {
				$(".btn-send-reply").fadeOut("slow");
				var _body = $("#reply_body").val();
				var _names = "";
				var _attachs = "";
				$(".attach").each(function () {
					_names += $(this).attr("data-name") + "[NAME]";
					_attachs += $(this).attr("data-url") + "[FILE]";
				});
				var _json = {
					"id_operator_task": $("#id").val(),
					"email": $("#email").val(),
					"body": _body,
					"subject": "Contacto CREDIPAZ",
					"from": "info@credipaz.com",
					"names": _names,
					"attachs": _attachs
				};
				_AJAX.UiDirectEmail(_json).then(function (datajson) {
					if (datajson.status == "OK") {
						alert("¡La respuesta ha sido enviada!");
						$(".btn-send-reply").fadeIn("fast");
						$("#emailModal").modal("toggle");
					} else {
						alert(datajson.message);
						$(".btn-send-reply").fadeIn("fast");
					}
				}).catch(function (error) { alert(error.message); });
			});

			$("#emailModal").modal({ backdrop: false, keyboard: true, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onResetPassword: function (_json) {
		try {
			_FUNCTIONS.onDestroyModal("#resetModal");
			var _html = "<div class='modal fade' id='resetModal' role='dialog'>";
			_html += " <div class='modal-dialog modal-dialog-centered role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>Blanqueo de clave</h4>";
			_html += "      <button type='button' class='close btn-close-modal' data-dismiss='modal'>&times;</button>";
			_html += "    </div>";
			_html += "    <div class='modal-body'>";
			_html += "       <label for='emailReset'>Ingrese su nombre de usuario<br/> (debe ser un email)</label>";
			_html += "       <input type='email' id='emailReset' name='emailReset form-control' class='emailReset' value=''/>";
			_html += "       <hr/>";
			_html += "       <a href='#' class='btn btn-raised btn-success btn-send-reset'>Enviar</a>";
			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += _FUNCTIONS._defaultProviderFooter;
			_html += "</div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);
			$("body").off("click", ".btn-send-reset").on("click", ".btn-send-reset", function () {
				$(".btn-send-reset").fadeOut("slow");
				var _email = $("#emailReset").val();
				var _body = "<h2>Blanqueo de claves - Credipaz</h2>";
				_body += "<h4>Haga click <a href='" + _AJAX.server + "linkDirect/resetPassword/" + btoa(_email) + "' class='btn btn-primary'>aquí</a> para proceder al blanqueo de su clave</h4>";
				var _json = { "email": _email, "body": _body, "subject": "Contacto CREDIPAZ", "from": "info@credipaz.com" };
				_AJAX.UiDirectEmailTransparent(_json).then(function (datajson) {
					if (datajson.status == "OK") {
						alert("¡Se ha enviado correo con instrucciones para el blanqueo de la clave!");
						$(".btn-send-reset").fadeIn("fast");
						$("#resetModal").modal("toggle");
					} else {
						alert(datajson.message);
						$(".btn-send-reset").fadeIn("fast");
					}
				}).catch(function (error) { alert(error.message); });
			});

			$("#resetModal").modal({ backdrop: false, keyboard: true, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onLegalesAutoRecord: function (_this) {
		if (!confirm("Se registrará una llamada sin contacto.  Al llegar a 3 registros de este tipo se cerrará el caso como sin contacto.  ¿Confirma?")) { return false; }
		var _id_operator_task = _this.attr("data-id_operator_task");
		var _json = {
			"id": 0,
			"description": "Llamada sin contacto",
			"id_operator_task": _id_operator_task,
			"data": "Intento de contacto fallido",
			"auto": "nocontact"
		};
		_AJAX.UiDirectLegales(_json).then(function (datajson) {
			if (datajson.status == "OK") {
				$(".btn-abm-cancel").click();
			} else {
				alert(datajson.message);
			}
		}).catch(function (error) { alert(error.message); });
	},
	onLegalesModal: function (_this) {
		try {
			var _id = _this.attr("data-id");
			var _title = _this.attr("data-title");
			var _id_operator_task = _this.attr("data-id_operator_task");

			_FUNCTIONS.onDestroyModal("#legalesModal");
			var _html = "<div class='modal fade' id='legalesModal'>";
			_html += " <div class='modal-dialog modal-lg pt-3'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>" + _title + "</h4>";
			_html += "      <button type='button' class='close btn-close-modal' data-dismiss='modal' style='font-size:42px;'>&times;</button>";
			_html += "    </div>";
			_html += "    <div class='modal-body'>";
			_html += "       <input id='id_item' name='id_item' class='id_item' type='hidden' value='" + _id + "'></input> ";
			_html += "       <input id='id_operator_task' name='id_operator_task' class='id_operator_task' type='hidden' value='" + _id_operator_task + "'></input> ";
			_html += "       <label>Título</label><br/>";
			_html += "       <input id='title' name='title' class='form-control text title validateLegal' style='width:100%;'></input><br/> ";
			_html += "       <label>Notas</label><br/>";
			_html += "       <textarea id='data' name='data' class='form-control data validateLegal' rows='10' style='width:100%;'></textarea> ";
			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += "       <hr/>";
			_html += "       <a href='#' class='btn btn-raised btn-success btn-send-legales'>Aceptar</a>";
			_html += "    </div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);

			$("body").off("click", ".btn-send-legales").on("click", ".btn-send-legales", function () {
				$(".btn-send-legales").attr("disabled", true).hide();
				if (_TOOLS.validate(".validateLegal")) {
					var _json = {
						"id": $(".id_item").val(),
						"description": $(".title").val(),
						"id_operator_task": $(".id_operator_task").val(),
						"data": $(".data").val(),
					};
					_AJAX.UiDirectLegales(_json).then(function (datajson) {
						if (datajson.status == "OK") {
							$(".btn-send-legales").attr("disabled", false).fadeIn("fast");
							_FUNCTIONS.onDestroyModal("#legalesModal");
							_FUNCTIONS._oLast_record.click();
						} else {
							alert(datajson.message);
							$(".btn-send-legales").attr("disabled", false).fadeIn("fast");
						}
					}).catch(function (error) { alert(error.message); });
				} else {
					_FUNCTIONS.onShowAlert("Complete los datos requeridos", "Datos faltantes");
				}
			});
			$("#legalesModal").modal({ backdrop: false, keyboard: true, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},

	onTelemedicinaModal: function (_json) {
		try {
			_FUNCTIONS._first_vademecum = "";
			_FUNCTIONS.onDestroyModal("#telemedicinaModal");
			if (_json["iface"] == undefined) { _json["iface"] = "receta"; }
			if (_json["close"] == undefined) { _json["close"] = false; }
			var _html = "<div class='modal fade' id='telemedicinaModal' style='overflow-y:auto;'>";
			_html += " <div class='modal-dialog modal-lg modal-dialog-scrollable p-0 m-0'>";
			_html += "  <div class='modal-content' style='position:absolute;width:70%;left:0px;top:0px;'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>" + _json["title"] + "</h4>";
			if (_json["close"]) { _html += "<button type='button' class='close btn-close-modal' data-dismiss='modal' style='font-size:42px;'>&times;</button>"; }
			_html += "    </div>";
			_html += "    <div class='modal-body'>";
			_html += _json["body"];
			if (!_json["close"]) {
				_html += "       <div class='progress' style='height:5px;'>";
				_html += "          <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width:100%;'></div>";
				_html += "       </div>";
			}
			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += _FUNCTIONS._defaultProviderFooter;
			_html += "</div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);
			//JsBarcode(".barcode").init();

			$("body").off("keypress", ".indicacion").on("keypress", ".indicacion", function (e) {
				var maxlength = parseInt($(".indicacion").attr("rows") - 1);
				var area = document.getElementById("indicacion");
				var text = area.value.replace(/\s+$/g, "")
				var split = text.split("\n")
				if (split.length > maxlength) {
					alert("No puede escribir más de " + maxlength + " líneas en esta área de texto");
					e.preventDefault();
					return false;
				}
			});
			$("body").off("drop", ".drop_zone").on("drop", ".drop_zone", function (ev) {
				$(this).removeClass("drop_zone_over");
				ev.preventDefault();
				if (ev.originalEvent.dataTransfer.items) {
					for (var i = 0; i < ev.originalEvent.dataTransfer.items.length; i++) {
						if (ev.originalEvent.dataTransfer.items[i].kind === 'file') {
							var file = ev.originalEvent.dataTransfer.items[i].getAsFile();
							var fileExt = file.name.split('.').pop();
							switch (fileExt) {
								case "jpg":
								case "jpeg":
								case "png":
								case "pdf":
									if (file.size > (_FUNCTIONS._ATTACH_LIMIT * 1024000)) {
										$(".ls-images").html("<li class='list-group-item' style='padding:10px;'>¡No se adjuntará <span class='badge badge-danger'>" + file.name + "</span> porque excede los " + _FUNCTIONS._ATTACH_LIMIT + "mb!</li>");
									} else {
										var reader = new FileReader();
										reader.onloadend = (function (data) { return function (evt) { $(".ls-images").html(_TOOLS.createFileItem(data.name, evt.target.result)); } })(ev.originalEvent.dataTransfer.items[i].getAsFile());
										reader.readAsDataURL(file);
									}
									break;
								default:
									alert("No pueden enviarse archivos del tipo " + fileExt);
							}
						}
					}
				} else {
					for (var i = 0; i < ev.originalEvent.dataTransfer.files.length; i++) {
						var file = ev.originalEvent.dataTransfer.files[i].getAsFile();
						var fileExt = file.name.split('.').pop();
						switch (fileExt) {
							case "jpg":
							case "jpeg":
							case "png":
							case "pdf":
								if (file.size > (_FUNCTIONS._ATTACH_LIMIT * 1024000)) {
									$(".ls-images").html("<li class='list-group-item' style='padding:10px;'>¡No se adjuntará <span class='badge badge-danger'>" + file.name + "</span> porque excede los " + _FUNCTIONS, _ATTACH_LIMIT + "mb!</li>");
								} else {
									var reader = new FileReader();
									reader.onloadend = (function (data) { return function (evt) { $(".ls-images").html(_TOOLS.createFileItem(data.name, evt.target.result)); } })(ev.originalEvent.dataTransfer.files[i].getAsFile());
									reader.readAsDataURL(file);
								}
								break;
							default:
								alert("No pueden enviarse archivos del tipo " + fileExt);
						}
					}
				}
				if (ev.originalEvent.dataTransfer.items) {
					ev.originalEvent.dataTransfer.items.clear();
				} else {
					ev.originalEvent.dataTransfer.clearData();
				}
			});
			$("body").off("dragover", ".drop_zone").on("dragover", ".drop_zone", function (ev) {
				$(this).addClass("drop_zone_over");
				ev.preventDefault();
			});
			$("body").off("dragleave", ".drop_zone").on("dragleave", ".drop_zone", function (ev) {
				$(this).removeClass("drop_zone_over");
				ev.preventDefault();
			});
			$("body").off("click", ".btn-deattach").on("click", ".btn-deattach", function () {
				if (!confirm("¿Confirma la operación?")) { return false; }
				$("." + $(this).attr("data-id")).remove();
			});
			$("body").off("keypress", ".listable").on("keypress", ".listable", function (e) {
				var _alert = ("." + $(':focus').attr("id") + "_msg");
				$(_alert).html("<span class='alert alert-warning noshare sin-descuento' style='padding:1px;'>Sin descuento</span>");
			});
			$("body").off("change", ".listable").on("change", ".listable", function () {
				var _alert = ("." + $(':focus').attr("id") + "_msg");
				var options = $('datalist')[0].options;
				for (var i = 0; i < options.length; i++) {
					var _color = "info";
					if (options[i].value == $(this).val()) {
						var _id_type_vademecum = options[i].attributes["data-id_type_vademecum"].value;
						if (_id_type_vademecum != undefined && _id_type_vademecum != "") {
							if ($("#medicamento_1").val() == "" || $("#medicamento_2").val() == "") {
								$(".medicamento_1_msg").html("");
								$(".medicamento_2_msg").html("");
								_FUNCTIONS._first_vademecum = "";
							}
							if (_FUNCTIONS._first_vademecum == "") {
								_FUNCTIONS._first_vademecum = _id_type_vademecum;
								_TOOLS.toDataURL(_AJAX.server + 'assets/uploads/recetas/footer_' + _FUNCTIONS._first_vademecum + '.png', function (dataUrl) {
									_FUNCTIONS._logo_receta_footer = dataUrl;
									$(".img-footer").attr("src", _FUNCTIONS._logo_receta_footer);
								});
							} else {
								if (_FUNCTIONS._first_vademecum != _id_type_vademecum) {
									_FUNCTIONS.onShowAlert("Hay medicamentos de dos vademecums distintos seleccionados.<br/>Debe confeccionar una receta por cada grupo de medicamentos que pertenezcan a un vademecum en particular.", "Alerta de Vademecum");
									_color = "danger";
								}
							}
						}
						$(_alert).html("<span class='alert alert-success' style='padding:1px;'>Con descuento</span>");
						$(_alert).append(" <span class='alert alert-" + _color + "' style='padding:1px;'> Vademecum " + options[i].attributes["data-vademecum"].value + "</span>");

						//$(".cNCR").html($(".cNCR").attr("data-cr"));
						//$(".cNCRLabel").html("Nº credencial " + options[i].attributes["data-vademecum"].value);
						//$(".obra_social").val($(".obra_social").attr("data-default"));
						//$(".obra_social_plan").val($(".obra_social_plan").attr("data-default"));
						//$(".nro_obra_social").val($(".nro_obra_social").attr("data-default"));
						//switch (parseInt(_FUNCTIONS._first_vademecum)) {
						//	case 3://Si es vademecum Swiss
						//		$(".obra_social").val(options[i].attributes["data-vademecum"].value);
						//		$(".obra_social_plan").val("LIFE");
						//		$(".nro_obra_social").val($(".cNCR").attr("data-sw"));
						//		$(".cNCR").html($(".cNCR").attr("data-sw"));
						//		break;
						//}
						break;
					}
				}
			});

			$("body").off("click", ".btnDataNumber").on("click", ".btnDataNumber", function () {
				$(".obra_social").val($(this).attr("data-os"));
				$(".obra_social_plan").val($(this).attr("data-plan"));
				$(".nro_obra_social").val($(this).attr("data-number"));
			});

			$("body").off("click", ".btn-send-telemedicina").on("click", ".btn-send-telemedicina", function () {
				var _medicamento_1 = $("#medicamento_1").val();
				var _medicamento_2 = $("#medicamento_2").val();
				var _indicacion = $("#indicacion").val();
				var _iface = $(this).attr("data-iface");
				switch (_iface) {
					case "receta":
						if (_medicamento_1 == "" && _medicamento_2 == "") {
							alert("No ha agregado medicamentos.  Por favor complete los datos.");
							return false;
						}
						break;
					case "nota":
						if (_indicacion == "") {
							alert("No ha escrito orden o indicación.  Por favor complete los datos.");
							return false;
						}
						break;
				}
				$(".rx-hidden").fadeIn("fast");
				$(".btn-send-telemedicina").fadeOut("slow");
				$(".editable").attr("contenteditable", false);
				var _carbon_copy = $("#carbon_copy:checked").val();
				if (_carbon_copy == null) { _carbon_copy = "0"; }

				var _obra_social = $("#obra_social").val();
				var _obra_social_plan = $("#obra_social_plan").val();
				var _nro_obra_social = $("#nro_obra_social").val();

				$(".pMedicamento1").html(_medicamento_1);
				$(".pMedicamento2").html(_medicamento_2);
				$(".pIndicacion").html(_indicacion);
				$("#indicacion").remove();
				$("#medicamento_1").remove();
				$("#medicamento_2").remove();

				$(".medicamento_1_msg").css({ "display": "none" });
				$(".medicamento_2_msg").css({ "display": "none" });

				var _raw_data = {
					"obra_social": _obra_social,
					"obra_social_plan": _obra_social_plan,
					"nro_obra_social": _nro_obra_social,
					"medicamento1": _medicamento_1,
					"medicamento2": _medicamento_2,
					"indicacion": _indicacion
				};
				$(".attach").each(function () { _raw_data = $(this).attr("data-url"); });
				$(".autofill").remove();
				var _message = $("#message").html();
				var _json = {
					"carbon_copy": _carbon_copy,
					"message": _message,
					"raw_data": JSON.stringify(_raw_data),
					"id_charge_code": $(this).attr("data-id-charge-code"),
					"id_type_item": $(this).attr("data-id-type-item"),
					"id_type_direction": $(this).attr("data-id-type-direction"),
					"id_type_vademecum": _FUNCTIONS._first_vademecum,
				};

				_AJAX.UiDirectTelemedicina(_json).then(function (datajson) {
					if (datajson.estado == "OK") {
						alert("¡El mensaje ha sido enviado!");
						$(".btn-send-telemedicina").fadeIn("fast");
						$("#telemedicinaModal").modal("toggle");
					} else {
						alert(datajson.message);
						$(".btn-send-telemedicina").fadeIn("fast");
					}
				}).catch(function (error) { alert(error.message); });
			});
			$(".rx-hidden").fadeOut("fast");
			$("#telemedicinaModal").modal({ backdrop: false, keyboard: true, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onTelemedicinaModalPDF: function (_json) {
		try {
			_FUNCTIONS.onDestroyModal("#telemedicinaModalPDF");
			var _html = "<div class='modal fade' id='telemedicinaModalPDF' style='overflow-y:auto;z-index:999999;'>";
			_html += " <div class='modal-dialog modal-lg modal-dialog-scrollable p-0 m-0'>";
			_html += "  <div class='modal-content' style='position:absolute;width:95vw;left:0px;top:0px;'>";
			_html += "    <div style='position:absolute;left:100px;top:12px;z-index:999999;'>";
			_html += "       <span class='badge badge-success'>Adjunte el PDF generado:</span>";
			_html += "       <input data-id-charge-code='" + _json["id_charge_code"] + "' class='btn-upload-receta btn btn-dark' type='file' id='pdfFile' name='pdfFile' accept='application/pdf'>";
			_html += "    </div>";
			_html += "    <button type='button' class='close btn-close-modal' data-dismiss='modal' style='color:red;font-size:42px;position:absolute;right:15px;top:2px;z-index:999999;'>&times;</button>";
			_html += "    <div class='modal-body p-0 m-0'>";
			_html += _json["body"];
			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += _FUNCTIONS._defaultProviderFooter;
			_html += "</div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);

			$("body").off("click", ".btn-upload-receta").on("click", ".btn-upload-receta", function (event) {
				$(this).val(null);
			});
			$("body").off("change", ".btn-upload-receta").on("change", ".btn-upload-receta", function (event) {
				var _id_charge_code = $(this).attr("data-id-charge-code");
				var base64 = "";
				var reader = new FileReader();
				reader.readAsDataURL($(".btn-upload-receta").prop('files')[0]);
				reader.onload = function () {
					base64 = reader.result;
					var _json = {
						"carbon_copy": 0,
						"message": base64,
						"raw_data": "",
						"id_charge_code": _id_charge_code,
						"id_type_item": 2,
						"id_type_direction": 2,
						"id_type_vademecum": -1,
						"type_media": "pdf"
					};
					_AJAX.UiDirectTelemedicina(_json).then(function (datajson) {
						alert("¡Se ha adjuntado la receta correctamente!");
						_FUNCTIONS.onDestroyModal("#telemedicinaModalPDF");
					}).catch(function (error) { alert(error.message); });
				};
			});
			$("#telemedicinaModalPDF").modal({ backdrop: false, keyboard: true, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onRefreshBrowser: function () {
		if ($(".pagination").html() != undefined) {
			if ($(".pagination").html().trim() != "") {
				$(".page-item.active a").click();
			} else {
				$(".btn-browser-search").click();
			}
		} else {
			_FUNCTIONS.onShowAlert("Se han grabado los datos exitosamente", "Acción finalizada");
		}
	},
	onImageModal: function (_json) {
		try {
			_FUNCTIONS._croppie = null;
			$('.modal-body').croppie("destroy");
			var _html = "<div class='modal fade' id='imageModal'>";
			_html += " <div class='modal-dialog modal-dialog-centered' role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>" + _json["title"] + "</h4>";
			_html += "      <button type='button' class='close' data-dismiss='modal'>&times;</button>";
			_html += "    </div>";
			_html += "    <div class='modal-body'></div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += "       <button type='button' class='btn btn-primary btn-crop'>Recortar</button>";
			_html += "    </div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);
			_FUNCTIONS._croppie = $('.modal-body').croppie(
				{
					viewport: { height: 250, width: 250, type: "square" },
					boundary: { height: 400, width: 400, }
				}
			);
			_FUNCTIONS._croppie.croppie('bind', { url: _json["image"], points: [77, 469, 280, 739] });
			$("body").off("click", ".btn-crop").on("click", ".btn-crop", function () {
				var _args = { type: _json["type"], format: _json["format"], quality: _json["quality"] };
				_FUNCTIONS._croppie.croppie('result', _args).then(function (_image) {
					if (!_json["multi"]) {
						$(_json["input"]).val(_image);
						$(_json["target"]).attr("src", _image);
					} else {
						var _id = _TOOLS.UUID();
						var _line = "<li class='list-group-item li-" + _id + "'>";
						_line += "<img data-id='" + _id + "' src='" + _image + "' style='width:40px;' class='new-file img-" + _id + "' data-filename='" + _json["filename"] + "' /> ";
						_line += "<div class='badge badge-primary text-truncate' style='display:inline-block;max-width:100%;' title='" + _json["filename"] + "'>" + _json["filename"] + "</div> ";
						_line += "<a href='#' data-id='" + _id + "' class='btn btn-sm btn-danger float-right btn-upload-delete'><i class='material-icons'>delete</i></a>";
						_line += "</li>";
						$(_json["target"]).append(_line);
					}
					_FUNCTIONS.onDestroyModal("#imageModal");
				});
			});
			$("#imageModal").modal({ backdrop: false, keyboard: false, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onFolderItemPriority: function (_this) {
		$(".img-" + _this.attr("data-id")).attr("data-priority", _this.val());
	},
	onFolderItemPriorityUpdate: function (_this) {
		var _json = { "id": _this.attr("data-id"), "priority": _this.val() };
		_AJAX.UiPriorityFolderItem(_json).then(function (data) { });
	},
	onFoldersModal: function (_json) {
		try {
			if (_json["module"] == undefined) { _json["module"] = "mod_folders"; }
			_FUNCTIONS.onDestroyModal("#foldersModal");
			var _html = "<div class='modal fade' id='foldersModal'>";
			_html += " <div class='modal-dialog modal-dialog-centered' role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>" + _json["title"] + "</h4>";
			_html += "      <button type='button' class='close' data-dismiss='modal'>&times;</button>";
			_html += "    </div>";
			_html += "    <div class='modal-body'>";
			_html += "		<label>Tipo</label>";
			_html += "		<select class='validate-folders form-control' id='id_type_folder_item' name='id_type_folder_item'></select>";
			_html += "		<label>Descripción</label>";
			_html += "		<input value='' class='validate-folders form-control' type='text' name='description_folder_item' id='description_folder_item' data-clear-btn='false' placeholder='Descripción' />";
			_html += "		<label>Palabras clave (separadas por ,)</label>";
			_html += "		<input value='' class='validate-folders form-control' type='text' name='keywords_folder_item' id='keywords_folder_item' data-clear-btn='false' placeholder='Keywords' />";
			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += "       <button type='button' class='btn btn-primary btn-ok-file'>Aceptar</button>";
			_html += "    </div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);
			if (_FUNCTIONS._cache != null && _FUNCTIONS._cache.type_folder_items != undefined) {
				_TOOLS.loadCombo(_FUNCTIONS._cache.type_folder_items, { "target": "#id_type_folder_item", "selected": -1, "id": "id", "description": "description" });
			} else {
				var _data = { "module": _json["module"], "table": "type_folder_items", "model": "type_folder_items", "order": "description ASC", "page": -1, "pagesize": -1 };
				_AJAX.UiGet(_data).then(function (datajson) {
					_FUNCTIONS._cache.type_folder_items = datajson;
					_TOOLS.loadCombo(datajson, { "target": "#id_type_folder_item", "selected": -1, "id": "id", "description": "description" });
				});
			}
			$("body").off("click", ".btn-ok-file").on("click", ".btn-ok-file", function () {
				if (_TOOLS.validate(".validate-folders", false)) {
					var _description = $("#description_folder_item").val();
					var _keywords = $("#keywords_folder_item").val();
					var _id_type = $("#id_type_folder_item").val();
					var _type = $("#id_type_folder_item option:selected").text();
					var arrayLength = _json["result"].length;
					for (var i = 0; i < arrayLength; i++) {
						var _id = _TOOLS.UUID();
						var _line = "<li class='list-group-item li-" + _id + "'>";
						_line += "<input data-id='" + _id + "' id='priority' name='priority' type='number' step='10' min='0' value='0' style='width:50px;' class='folder-item-priority'/>";
						_line += "<img data-priority='0' data-id='" + _id + "' data-result='" + _json["result"][i] + "' src='" + _json["image"] + "' style='width:40px;' class='new-folder-item img-" + _id + "' data-filename='" + _json["filename"] + "' data-description='" + _description + "' data-keywords='" + _keywords + "' data-type='" + _id_type + "'/> ";
						_line += "<div class='badge badge-primary text-truncate' style='max-width:100%;' title='" + _type + "'>" + _type + "</div> ";
						_line += "<div class='badge badge-secondary text-truncate' style='max-width:100%;' title='" + _description + "'>" + _description + "</div> ";
						_line += "<div class='badge badge-info text-truncate' style='max-width:100%;' title='" + _keywords + "'>" + _keywords + "</div> ";
						_line += "<a href='#' data-id='" + _id + "' class='btn btn-sm btn-danger float-right btn-folders-delete'><i class='material-icons'>delete_forever</i></a>";
						_line += "</li>";
						$(_json["target"]).append(_line);
					}
					_FUNCTIONS.onDestroyModal("#foldersModal");
				}
			});

			$("#foldersModal").modal({ backdrop: false, keyboard: false, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onFolderMessagesModal: function (_this) {
		var _title = _this.attr("data-title");
		var _body = _this.attr("data-body");
		if (_body == "" || _body == null || _body == undefined) { _body = ""; } else { _body = _TOOLS.b64_to_utf8(_body); }
		_FUNCTIONS.onInfoModal({ "title": _title, "body": _body, "close": true, "size": "modal-lg", "center": false }, function () {
			$("body").off("click", ".btn-success-message").on("click", ".btn-success-message", function () {
				if (!_TOOLS.validate(".validate-message", false)) {
					_FUNCTIONS.onShowAlert("Complete los datos requeridos", "Datos faltantes");
					return false;
				}
				var _id = _TOOLS.UUID();
				var _html = "<li style='width:100%;' class='list-group-item " + _id + "'>";
				_html += "<table class='table-condensed table-striped' style='width:100%;'>";
				_html += " <tr><td class='new-message'>" + $("#message").val() + "</td></tr>";
				_html += " <tr><td align='right' style='font-size:9px;'>" + _TOOLS.getNow() + ": <i>" + $(".raw-username_active").html() + "</i></td></tr>";
				_html += "</table>";
				_html += "</li>";
				$(".ls-message").append(_html);
				$("#infoModal").modal("hide").data("bs.modal", null);
			});
		});
	},
	onAlertSecurity: function (_name) {
		var _ret = true;
		switch (_name) {
			case "xls":
			case "xlst":
			case "csv":
			case "doc":
			case "docx":
			case "pdf":
			case "jpg":
			case "jepg":
			case "png":
				break;
			default:
				_ret = false;
				break;
		}
		$.getJSON('https://api.ipify.org?format=json', function (data) {
			var _param = { "id": "0", "code": "ALERTA", "description": "ALERTA", "id_user": _AJAX._id_user_active, "action": "ALERTA", "trace": "ID origen: " + data.ip, "id_rel": "0", "table_rel": "" };
			_AJAX.UiLogGeneral(_param).then(function (data) { });
		});
		alert("¡Tipo de archivo no habilitado! Se ha notificado a seguridad informática");
		return _ret;
	},
	onProcessSelectedFilesFolders: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var x = document.querySelector(_this.attr("data-click"));
					for (var i = 0; i < x.files.length; i++) {
						var f = x.files[i];
						var _file_type = f.type;
						var _filename = f.name;
						var _ret = _FUNCTIONS.onAlertSecurity(f.name.split('.').pop());
						if (!_ret) { return _ret; }

						if (f.size > (_FUNCTIONS._max_filesize_upload * 1024000)) { throw ("Se aceptan archivos de hasta " + _FUNCTIONS._max_filesize_upload + "mb"); }
						var fr = new FileReader();
						var _result = [];
						fr.onload = function (e) {
							_result.push(this.result);
							var _image = _TOOLS.iconByMime(_file_type, fr.result);
							_FUNCTIONS.onFoldersModal(
								{
									"target": _this.attr("data-target"),
									"input": _this.attr("data-input"),
									"title": "Agregar archivo",
									"filename": _filename,
									"image": _image,
									"result": _result,
									"module": _this.attr("data-module"),
								});
							resolve(_image);
						}
						fr.readAsDataURL(f);
					}
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onProcessSelectedFilesSimple: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var x = document.querySelector(_this.attr("data-click"));
					for (var i = 0; i < x.files.length; i++) {
						var f = x.files[i];
						var _file_type = f.type;
						var _filename = f.name;
						var _ret = _FUNCTIONS.onAlertSecurity(f.name.split('.').pop());
						if (!_ret) { return _ret; }

						if (f.size > (_FUNCTIONS._max_filesize_upload * 1024000)) { throw ("Se aceptan archivos de hasta " + _FUNCTIONS._max_filesize_upload + "mb"); }
						var fr = new FileReader();
						fr.onload = function (event) {
							//var _image = _TOOLS.iconByMime(_file_type, fr.result);
							var _image = fr.result;
							if (!_this.attr("data-multi")) {
								$(_this.attr("data-input")).val(_image);
								$(_this.attr("data-target")).attr("src", _image);
							} else {
								var _id = _TOOLS.UUID();
								var _line = "<li class='list-group-item li-" + _id + "'>";
								_line += "<img data-id='" + _id + "' src='" + _image + "' style='width:40px;' class='new-file img-" + _id + "' data-filename='" + _filename + "' /> ";
								_line += "<div class='badge badge-primary text-truncate' style='display:inline-block;max-width:100%;' title='" + _filename + "'>" + _filename + "</div> ";
								_line += "<a href='#' data-id='" + _id + "' class='btn btn-sm btn-danger float-right btn-upload-delete'><i class='material-icons'>delete</i></a>";
								_line += "</li>";
								$(_this.attr("data-target")).append(_line);
							}
							resolve(_image);
						}
						fr.readAsDataURL(f);
					}
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onProcessSelectedFiles: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var x = document.querySelector(_this.attr("data-click"));
					for (var i = 0; i < x.files.length; i++) {
						var f = x.files[i];
						var _file_type = f.type;
						var _filename = f.name;
						var _ret = _FUNCTIONS.onAlertSecurity(f.name.split('.').pop());
						if (!_ret) { return _ret; }

						if (f.size > (_FUNCTIONS._max_filesize_upload * 1024000)) { throw ("Se aceptan archivos de hasta " + _FUNCTIONS._max_filesize_upload + "mb"); }
						var fr = new FileReader();
						fr.onload = function (event) {
							//var _image = _TOOLS.iconByMime(_file_type, fr.result);
							var _image = fr.result;
							_FUNCTIONS.onImageModal(
								{
									"target": _this.attr("data-target"),
									"input": _this.attr("data-input"),
									"type": _this.attr("data-type"),
									"format": _this.attr("data-format"),
									"quality": _this.attr("data-quality"),
									"crop": _this.attr("data-crop"),
									"multi": _this.attr("data-multi"),
									"title": "Ajustar la imagen",
									"filename": _filename,
									"image": _image
								});
							resolve(_image);
						}
						fr.readAsDataURL(f);
					}
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onResetSelectedFile: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (confirm("¿Confirma el borrado del archivo?")) {
						$(_this.attr("data-target")).attr("src", _this.attr("data-default"));
						$(_this.attr("data-input")).val("");
					}
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onDeleteSelectedFile: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (confirm("¿Confirma el borrado del archivo?")) {
						var _id = _this.attr("data-id");
						if ($(".img-" + _id).hasClass("new-file")) {
							$(".li-" + _id).fadeOut("fast").remove();
						} else {
							$(".li-" + _id).addClass("d-none")
							$(".img-" + _id).addClass("del-file");
						}
					}
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onDeleteSelectedLink: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (confirm("¿Confirma el borrado del link externo?")) {
						var _id = _this.attr("data-id");
						if ($(".img-" + _id).hasClass("new-link")) {
							$(".li-" + _id).fadeOut("fast").remove();
						} else {
							$(".li-" + _id).addClass("d-none")
							$(".img-" + _id).addClass("del-link");
						}
					}
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onDeleteSelectedFileFolders: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (confirm("¿Confirma el borrado del archivo?")) {
						var _id = _this.attr("data-id");
						if ($(".img-" + _id).hasClass("new-folder-item")) {
							$(".li-" + _id).fadeOut("fast").remove();
						} else {
							$(".li-" + _id).addClass("d-none")
							$(".img-" + _id).addClass("del-file");
						}
					}
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onDeleteSelectedLinkFolders: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (confirm("¿Confirma el borrado del link externo?")) {
						var _id = _this.attr("data-id");
						if ($(".img-" + _id).hasClass("new-link")) {
							$(".li-" + _id).fadeOut("fast").remove();
						} else {
							$(".li-" + _id).addClass("d-none")
							$(".img-" + _id).addClass("del-link");
						}
					}
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onProcessDirectEmail: function (_this) {
		var _body = "";
		_body += "<label>Email</label><input type='text' id='email' name='email' class='form-control' value='" + _this.attr("data-email") + "'/>"
		_body += "<label>Mensaje</label><textarea id='reply_body' name='reply_body' class='shadow trumbo' style='width:100%;'></textarea> ";
		_body += "<hr/>";
		_body += "<div id='drop_zone' class='drop_zone'>";
		_body += "<p>Arrastre y suelte archivos a adjuntar en esta zona</p>";
		_body += "</div>";
		_body += "<hr/>";
		_body += "<ul class='ls-images' style='padding:0px;'></ul>";
		_body += "<hr/>";
		_body += "<a href='#' class='btn btn-raised btn-lg btn-success btn-send-reply'>Enviar!</a>";
		_FUNCTIONS.onEmailModal({ "close": true, "title": "Responder", "body": _body });
	},
	onProcessDirectTelemedicinaPDF: function (_this) {
		
		var _iface = _this.attr("data-iface");
		var _id_type_item = parseInt(_this.attr("data-id-type-item"));
		var _id_charge_code = parseInt(_this.attr("data-id-charge-code"));
		var _fnac = _this.attr("data-fechanacimiento");
		var _arr = _fnac.split("/");
		_fnac = (_arr[2] + "-" + _arr[1] + "-" + _arr[0]);
		var _params = {
			"dni": _this.attr("data-dni"),
			"nombre": _this.attr("data-nombre"),
			"apellido": _this.attr("data-apellido"),
			"sexo": _this.attr("data-sexo"),
			"fechanacimiento": _fnac,
			"panswiss": _this.attr("data-panswiss"),
			"id_charge_code": _id_charge_code
		}
		_AJAX._waiter = true;
		_AJAX.UiFarmaLinkRecetas(_params).then(function (datajson) {
			var _body = "<iframe src='" + datajson.url + "' style='width:100%;height:1000px;border:solid 0px red;'></iframe>";
			_FUNCTIONS.onTelemedicinaModalPDF({ "body": _body, "id_charge_code": _id_charge_code });
		});
	},
	onProcessDirectTelemedicina: function (_this) {
		var _iface = _this.attr("data-iface");
		var _id_type_item = parseInt(_this.attr("data-id-type-item"));
		var _id_charge_code = parseInt(_this.attr("data-id-charge-code"));
		var _nombre_paciente = _this.attr("data-nombre_paciente");
		var _nro_documento = _this.attr("data-nro_documento");
		var _nro_club_redondo = _this.attr("data-nro_club_redondo");
		var _nro_swiss = _this.attr("data-nro_swiss");
		var _matricula = _this.attr("data-matricula");
		var _medico = _this.attr("data-medico");
		var _firma = _this.attr("data-firma");
		var _obra_social = _this.attr("data-obra_social");
		var _obra_social_plan = _this.attr("data-obra_social_plan");
		var _nro_obra_social = _this.attr("data-nro_obra_social");
		var _body = "";
		switch (_id_type_item) {
			case 1:
				_body += "<label>Mensaje</label><textarea id='message' rows='5' name='message' class='shadow trumbo message' style='width:100%;'></textarea> ";
				_body += "<hr/>";
				_body += "<div id='drop_zone' class='drop_zone' style='height:125px;'>";
				_body += "<p>Arrastre y suelte un solo archivo a adjuntar en esta zona.  Solo se pueden adjuntar jpg, png o pdf.</p>";
				_body += "<p>Si arrastra varios archivos, solo uno será adjuntado.</p>";
				_body += "</div>";
				_body += "<hr/>";
				_body += "<ul class='ls-images' style='padding:0px;'></ul>";
				break;
			case 2:
				_body += "<div id='message' name='message' class='shadow message' style='width:100%;'>";
				_body += '<table width="100%">';
				_body += '   <tr>';
				_body += '      <td align="left"><img src="' + _FUNCTIONS._logo_receta_left + '" width="60"/></td>';
				_body += '      <td align="center" valign="middle" style="font-size:1.75em;color:rgb(43, 135, 201);">Servicio de Telemedicina</td>';
				_body += '      <td align="right"></td>';
				//_body += '      <td align="right"><img src="' + _FUNCTIONS._logo_receta_right + '" width="60"/></td>';
				_body += '   </tr>';
				_body += '   <tr><td colspan="3" style="border-top:solid 2px rgb(43, 135, 201);"></td></tr>';
				_body += '</table > ';
				_body += "<br/>";
				_body += "<table width='100%'>";
				_body += "   <tr><td>Fecha de emisión</td><td align='right'>" + _TOOLS.getNow() + "</td></tr>";
				_body += "   <tr><td>RX</td><td align='right'><b>ORIGINAL</b></td></tr>";
				_body += "   <tr><td>Paciente</td><td align='right' class='nombrepaciente editable' contenteditable style='font-weight:bold;border-bottom:dotted 1px silver;'><b>" + _nombre_paciente + "</b></td></tr>";
				_body += "   <tr><td>Nº de documento</td><td align='right' class='documentopaciente editable' contenteditable style='font-weight:bold;border-bottom:dotted 1px silver;'><b>" + _nro_documento + "</b></td></tr>";
				//_body += "   <tr><td class='cNCRLabel'>Nº de Club Redondo</td><td align='right'><b class='cNCR' data-cr='" + _nro_club_redondo + "' data-sw='" + _nro_swiss + "'>" + _nro_club_redondo + "</b></td></tr>";
				_body += "</table>";
				_body += "<table style='border:solid 1px grey;'>";
				_body += "   <tr>";
				_body += "      <td>Obra social: <input style='border:solid 0px white;border-right:solid 1px grey;border-bottom:solid 1px grey;width:100%;' type='text' class='shadow text obra_social' id='obra_social' name='obra_social' data-default='" + _obra_social + "' value='" + _obra_social + "'/></td>";;
				_body += "      <td>Plan: <input style='border:solid 0px white;border-right:solid 1px grey;border-bottom:solid 1px grey;width:100%;' type='text' class='shadow text obra_social_plan' id='obra_social_plan' name='obra_social_plan' data-default='" + _obra_social_plan + "' value='" + _obra_social_plan + "'/></td>";
				_body += "      <td>Nº de afiliado: <input style='border:solid 0px white;border-right:solid 1px grey;border-bottom:solid 1px grey;width:100%;' type='text' class='shadow text nro_obra_social' id='nro_obra_social' name='nro_obra_social' data-default='" + _nro_obra_social + "' value='" + _nro_obra_social + "'/>";
				_body += "   </tr>";
				_body += "</table>";
				_body += "<table style='width:100%' class='noshare autofill'>";
				_body += "   <tr>";
				_body += "      <td align='center'><a href='#' class='btn btn-raised btn-sm btn-primary btnDataNumber' data-number='" + _nro_swiss + "' data-plan='LIFE' data-os='SWISS MEDICAL'>Datos Swiss</a></td>";
				_body += "      <td align='center'><a href='#' class='btn btn-raised btn-sm btn-danger btnDataNumber' data-number='" + _nro_club_redondo + "' data-plan='' data-os='CLUB REDONDO'>Datos Mediya</a></td>";
				_body += "      <td align='center'><a href='#' class='btn btn-raised btn-sm btn-secondary btnDataNumber' data-number='' data-plan='' data-os=''>Limpiar</a></td>";
				_body += "   </tr>";
				_body += "</table>";

				switch (_iface) {
					case "receta":
						_body += "<h4 class='noshare'></h4>"
						_body += "<table width='100%'>";
						_body += "   <tr><td align='left'><p class='pMedicamento1'></p><input type='text' autocomplete='off1' class='form-control text medicamento_1 listable' id='medicamento_1' name='medicamento_1' placeholder='1.' value='' list='medicamentos'/></td></tr>";
						_body += "   <tr class='noshare'><td align='left' class='medicamento_1_msg'></td></tr>";
						_body += "   <tr><td align='left'><p class='pMedicamento2'></p><input type='text' autocomplete='off2' class='form-control text medicamento_2 listable' id='medicamento_2' name='medicamento_2' placeholder='2.' value='' list='medicamentos'/></td></tr>";
						_body += "   <tr class='noshare'><td align='left' class='medicamento_2_msg'></td></tr>";
						//_body += "   <tr><td align='right'><input type='text' class='form-control text medicamento_3' id='medicamento_3' name='medicamento_3' placeholder='3.' value='' list='medicamentos'/></td></tr>";
						_body += "</table>";
						_body += "<p class='pIndicacion'></p>";
						_body += "<table width='100%'>";
						_body += "   <tr><td align='left'><textarea class='form-control indicacion' rows='11' id='indicacion' name='indicacion' placeholder='Escriba aquí notas relacionadas con la receta'></textarea></td></tr>";
						_body += "</table>";
						break;
					case "nota":
						_body += "<h4 class='noshare'></h4>"
						_body += "<p class='pIndicacion'></p>";
						_body += "<table width='100%'>";
						_body += "   <tr><td align='left'><textarea class='form-control indicacion' rows='16' id='indicacion' name='indicacion' placeholder='Escriba aquí indicaciones u órdenes al paciente'></textarea></td></tr>";
						_body += "</table>";
						_body += '<div style="display:none;"><input id="medicamento_1" name="medicamento_1" class="medicamento_1" /><input id="medicamento_2" name="medicamento_2" class="medicamento_2" /></div>';
						break;
				}

				_body += "<hr/>";
				_body += "<table width='100%'>";
				_body += "   <tr>";
				_body += "      <td align='center'>";
				if (_firma != "") { _body += "<img src='" + _firma + "' height='120'/>"; }
				_body += "      </td>";
				_body += "      <td align='left'>";
				_body += "         <table valign='middle'>";
				_body += "            <tr><td align='left'>Dr./Dra. <b>" + _medico + "</b></td></tr>";
				_body += "            <tr><td align='left'>Matrícula <b>" + _matricula + "</b></td></tr>";
				_body += "         </table>";
				_body += "      </td>";
				_body += "   </tr>";
				_body += "   <tr>";
				_body += "      <td align='center' colspan='2' >";
				_body += "         <b>Indicación / Orden</b>";
				_body += "      </td>";
				_body += "   </tr>";
				//_body += "   <tr>";
				//_body += "      <td align='center' colspan='2' >";
				//_body += "         <svg class='barcode' jsbarcode-margin='0' jsbarcode-height='50' jsbarcode-format='CODE128' jsbarcode-value='" + _id_charge_code + "' jsbarcode-textmargin='0' jsbarcode-fontoptions='bold'></svg>";
				//_body += "      </td>";
				//_body += "   </tr>";
				_body += "</table>";

				_body += '<table width="100%" class="noshare">';
				_body += '   <tr>';
				_body += '      <td align="center"><img class="img-footer" src="' + _FUNCTIONS._logo_receta_footer + '" style="width:100%;"/></td>';
				_body += '   </tr>';
				_body += '</table > ';

				_body += "</div> ";
				_body += "<hr/>";
				break;
		}
		_body += "<hr/>";
		_body += "<table>";
		_body += "   <tr>";
		_body += "      <td align='left'>";
		_body += "         <input style='height:20px;' id='carbon_copy' name='carbon_copy' type='checkbox' class='form-control check carbon_copy' value='1'/>¿Generar copia?";
		_body += "      </td>";
		_body += "      <td align='left' style='padding-left:10px;'>";
		_body += "         <button class='btn btn-raised btn-md btn-success btn-send-telemedicina' data-iface='" + _iface + "' data-id-charge-code='" + _id_charge_code + "' data-id-type-item='" + _id_type_item + "' data-id-type-direction='2'><span class='material-icons'>send</span > Enviar!</button>";
		_body += "      </td>";
		_body += "   </tr>";
		_body += "</table>";
		_FUNCTIONS.onTelemedicinaModal({ "iface": _iface, "close": true, "title": "Mensaje al paciente", "body": _body });
	},
	onViewDirectTelemedicina: function (_this) {
		try {
			var _bFill = false;
			var _json = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-item")));
			var _data = { "module": "MOD_TELEMEDICINA", "where": ("id=" + _json.id), "function": "get", "table": "messages", "model": "messages", "order": "created DESC", "page": -1, "pagesize": -1 };
			_AJAX.UiGet(_data).then(function (datajson) {
				_json = datajson.data[0];
				var _raw_data = JSON.parse(datajson.data[0].raw_data);
				_FUNCTIONS.onDestroyModal("#telemedicinaModalView");
				var _html = "<div class='modal fade' id='telemedicinaModalView' role='dialog'>";
				_html += " <input type='hidden' id='code' name='code' value='" + _TOOLS.UUID() + "' class='code dbaseComprobante'/>";
				_html += " <input type='hidden' id='description' name='description' value='Receta-Indicacion-Telemedicina' class='description dbaseComprobante'/>";
				_html += " <input type='hidden' id='base64' name='base64' value='' class='base64 dbaseComprobante'/>";
				_html += " <input type='hidden' id='filename' name='filename' value='Receta-Indicacion-" + _TOOLS.UUID() + ".pdf' class='filename dbaseComprobante'/>";
				_html += " <input type='hidden' id='extension' name='extension' value='pdf' class='extension dbaseComprobante'/>";
				_html += " <div class='modal-dialog modal-dialog-centered modal-lg' role='document'>";
				_html += "  <div class='modal-content'>";
				_html += "    <div class='modal-header'>";
				_html += "      <button type='button' class='btn btnraised btn-primary btn-download-pdf'>Descargar PDF</button>";
				_html += "      <button type='button' class='close btn-close-modal' data-dismiss='modal'>&times;</button>";
				_html += "    </div>";
				_html += "    <div class='modal-body data-pdf'>";
				switch (parseInt(_json.id_type_item)) {
					case 1:
						var _sep = ",";
						if (_raw_data.mime.slice(-1) == ",") { _sep = ""; }
						var _imgUrl = (_raw_data.mime + _sep + _raw_data.base64);
						_html += "<div class='shadow'><span class='badge badge-primary'>" + _json.message + "</span><br/><img src='" + _imgUrl + "' style='width:100%;'/></div>";
						break;
					case 2:
						_bFill = true;
						_html += "<div class='shadow'>" + _json.message + "</div>";
						break;
				}
				_html += "    </div>";
				_html += "    <div class='modal-footer font-weight-light'>";
				_html += _FUNCTIONS._defaultProviderFooter;
				_html += "</div>";
				_html += "  </div>";
				_html += " </div>";
				_html += "</div>";
				$("body").append(_html);
				if (_bFill) {
					$("#obra_social").val(_raw_data.obra_social).prop('disabled', true).css({ "background-color": "white", "height": "25px" });
					$("#nro_obra_social").val(_raw_data.nro_obra_social).prop('disabled', true).css({ "background-color": "white", "height": "25px" });

					$("#obra_social").parent().css({ "width": "33%", "padding": "10px" }).html("Obra social: <br/>" + _raw_data.obra_social);
					$("#obra_social_plan").parent().css({ "width": "33%", "padding": "10px" }).html("Plan: <br/>" + _raw_data.obra_social_plan);
					$("#nro_obra_social").parent().css({ "width": "33%", "padding": "10px" }).html("Nº afiliado: <br/>" + _raw_data.nro_obra_social);


					$("#medicamento_1").val(_raw_data.medicamento1).prop('disabled', true).css("background-color", "white");
					$("#medicamento_2").val(_raw_data.medicamento2).prop('disabled', true).css("background-color", "white");
					$("#medicamento_3").val(_raw_data.medicamento3).prop('disabled', true).css("background-color", "white");
					try {
						var _height = (document.getElementById("indicacion").scrollHeight + 75);
						$("#indicacion").val(_raw_data.indicacion).prop('disabled', true).css({ "background-color": "white", "width": "85%", "height": + _height + "px", "overflow": "hidden" });
					} catch (rex) { }
				}
				/*Si es enviado por el usuario, se marca como verificado, leido!*/
				if (parseInt(_json.id_type_direction) == 1 && parseInt(_json.viewed) == 0) { _AJAX.UiViewMessagesTelemedicina({ "id": _json.id }).then(function (data) { }); }
				$("#telemedicinaModalView").modal({ backdrop: false, keyboard: true, show: true });
				$("#telemedicinaModalView").css({ "padding": "0px", "margin": "0px" });

				$(".medicamento_1").attr("value", _raw_data.medicamento1).val(_raw_data.medicamento1).prop('disabled', true);
				$(".medicamento_2").attr("value", _raw_data.medicamento2).val(_raw_data.medicamento2).prop('disabled', true);

				if (parseInt(_json.carbon_copy) == 1) {
					var _original = $(".data-pdf").html();
					var _html = "<hr style='page-break-before:always;background-color:transparent;border-top:0px dashed #8c8b8b;'/>";
					_html += _original.replace("ORIGINAL", "DUPLICADO");
					$(".data-pdf").append(_html);
				}

				$("body").off("click", ".btn-download-pdf").on("click", ".btn-download-pdf", function () {
					var _original = $(".data-pdf").html();
					$(".noshare").remove();
					$(".base64").val(_TOOLS.utf8_to_b64($(".data-pdf").html()));
					var _url = (_AJAX.server + "downloadBase64File/" + $(".code").val() + "/" + $(".description").val());
					$(".btnGetBase64").attr("href", _url);
					var _json = _TOOLS.getFormValues(".dbaseComprobante", null);
					_json["module"] = "mod_backend";
					_json["table"] = "Files_base64";
					_json["model"] = "Files_base64";
					_AJAX.UiSave(_json).then(function (data) {

						var divContents = $("#dvContainer").html();
						var printWindow = window.open('', '', 'height=400,width=800');
						printWindow.document.write('<html><head><title>Receta - Indicación</title>');
						printWindow.document.write('</head><body >');
						printWindow.document.write($(".data-pdf").html());
						printWindow.document.write('</body></html>');
						printWindow.document.close();
						printWindow.print();

						//window.open(_url, '_blank');
						$(".data-pdf").html(_original);
						//$(".btnGetBase64").removeClass("d-none");
					});
				});
				return true;
			});
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onViewDirectTelemedicinaPDF: function (_this) {
		try {
			var _bFill = false;
			var _json = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-item")));
			var _data = { "module": "MOD_TELEMEDICINA", "where": ("id=" + _json.id), "function": "get", "table": "messages", "model": "messages", "order": "created DESC", "page": -1, "pagesize": -1 };
			_AJAX.UiGet(_data).then(function (datajson) {
				console.log(datajson);
				_json = datajson.data[0];
				//var _raw_data = JSON.parse(datajson.data[0].raw_data);
				//console.log(_raw_data);
				_FUNCTIONS.onDestroyModal("#telemedicinaModalViewPDF");
				var _html = "<div class='modal fade' id='telemedicinaModalViewPDF' role='dialog'>";
				_html += " <div class='modal-dialog modal-dialog-centered modal-lg' role='document'>";
				_html += "  <div class='modal-content'>";
				_html += "    <div class='modal-body p-0 m-0'>";
				_html += "       <button type='button' class='close btn-close-modal' data-dismiss='modal' style='color:red;font-size:42px;position:relative;right:2px;'>&times;</button>";
				_html += "       <iframe src='" + _json.message + "' style='border:solid 0px red;height:640px;width:100%;'></iframe>";
				_html += "    </div>";
				_html += "    <div class='modal-footer font-weight-light'>" + _FUNCTIONS._defaultProviderFooter + "</div>";
				_html += "  </div>";
				_html += " </div>";
				_html += "</div>";
				$("body").append(_html);
				$("#telemedicinaModalViewPDF").modal({ backdrop: true, keyboard: true, show: true });
				$("#telemedicinaModalViewPDF").css({ "padding": "0px", "margin": "0px" });

				return true;
			});
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onResolveItemStatusMedico: function (_field, _val) {
		switch (String(_val)) {
			case "0":
				return "<li style='display:inline;padding:3px;border:solid 1px pink;'>" + _field + ": <b>NO</b></li>";
			case "1":
				return "<li style='display:inline;padding:3px;border:solid 1px lightgreen;'>" + _field + ": <b>Si</b></li>";
			case "":
			case "null":
			case "-1":
				return "";
			default:
				return "<li style='display:inline;padding:3px;border:solid 1px silver;'>" + _field + ": <b>" + _val + "</b></li>";
		}

	},
	onViewPreviousTelemedicina: function (_this) {
		try {
			var _json = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-item")));
			_FUNCTIONS.onDestroyModal("#previousModalView");
			var _evaluaciones_medicas = "";
			_evaluaciones_medicas += "       <ul style='padding:0px;margin:0px;'>";
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Temperatura", _json.temperatura);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Tos", _json.tos);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Expectoración", _json.expectoracion);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Odinofagia", _json.odinofagia);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Disfagia", _json.disfagia);
			_evaluaciones_medicas += "       </ul>";
			_evaluaciones_medicas += "       <ul style='padding:0px;margin:0px;'>";
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Disnea", _json.disnea);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Náuseas", _json.nauseas);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Vómitos", _json.vomitos);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Dolor abdominal", _json.dolor_abdominal);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Diarrea", _json.diarrea);
			_evaluaciones_medicas += "       </ul>";
			_evaluaciones_medicas += "       <ul style='padding:0px;margin:0px;'>";
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Proctorragia", _json.proctorragia);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Disuria", _json.disuria);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Polaquiuria", _json.polaquiuria);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Edemas", _json.edemas);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Parestesias", _json.parestesias);
			_evaluaciones_medicas += "       </ul>";
			_evaluaciones_medicas += "       <ul style='padding:0px;margin:0px;'>";
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Calambres", _json.calambres);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Insensibilidad miembro", _json.insensibilidad_miembro);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Cefaleas", _json.cefaleas);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Migraña antecedente", _json.migrana_antecedente);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Migraña medicada", _json.migrana_medicada);
			_evaluaciones_medicas += "       </ul>";
			_evaluaciones_medicas += "       <ul style='padding:0px;margin:0px;'>";
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("TA constatada", _json.ta_constatada);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Derivado consulta", _json.derivado_consulta);
			_evaluaciones_medicas += _FUNCTIONS.onResolveItemStatusMedico("Derivado especialista", _json.derivado_especialista);
			_evaluaciones_medicas += "       </ul>";

			var _html = "<div class='modal fade' id='previousModalView' role='dialog'>";
			_html += " <div class='modal-dialog modal-dialog-centered modal-lg' role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <button type='button' class='close btn-close-modal' data-dismiss='modal'>&times;</button>";
			_html += "    </div>";
			_html += "    <div class='modal-body shadow'>";
			_html += "       <h4>" + _json.description + "<span class='badge badge-primary'>" + _json.created + "</span></h4>";
			_html += "       <table style='width:100%;'>";
			_html += "         <tr>";
			_html += "          <td style='width:60%;' valign='top'>";
			if (String(_json.refiere) == "null") { _json.refiere = ""; }
			_html += "		       <h5><b>El paciente refiere:</b></h5><p>" + _json.refiere + "</p>";
			if (String(_json.motivo) == "null") { _json.motivo = ""; }
			_html += "		       <h5><b>Motivo:</b></h5><p>" + _json.motivo + "</p>";
			if (String(_json.evolucion) == "null") { _json.evolucion = ""; }
			_html += "		       <h5><b>Evolución:</b></h5><p> " + _json.evolucion + "</p>";
			if (String(_json.diagnostico) == "null") { _json.diagnostico = ""; }
			_html += "		       <h5><b>Diagnóstico:</b></h5><p> " + _json.diagnostico + "</p>";
			if (String(_json.indicaciones) == "null") { _json.indicaciones = ""; }
			_html += "		       <h5><b>Indicaciones:</b></h5><p> " + _json.indicaciones + "</p>";
			_html += "		       <h5><b>Evaluación médica</b></h5>";
			_html += _evaluaciones_medicas;
			_html += "          </td>";
			_html += "          <td valign='top'>";
			if (String(_json.post_close) == "null") { _json.post_close = ""; }
			_html += "				<h5><b>Notas post cierre:</b></h5><p style='padding:5px;border:dotted 2px silver;'> " + _json.post_close + "</p>";
			_html += "			    <b>Agregar mas notas post cierre</b><br/>";
			_html += " 				<textarea id='post_close' name='post_close' class='text form-control post_close' style='width:100%;' rows='5'></textarea>"
			_html += "              <a href='#' class='btn btn-raised btn-success btn-add-closed' data-id='" + _json.id + "' data-target='.post_close' >Guardar nota</a>";
			_html += "         </td>";
			_html += "        </tr>";
			_html += "       </table>";

			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += _FUNCTIONS._defaultProviderFooter;
			_html += "</div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);
			$("#previousModalView").modal({ backdrop: false, keyboard: true, show: true });
			$("#previousModalView").css({ "padding": "0px", "margin": "0px" });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onAddPostNotesTelemedicina: function (_this) {
		var _id = _this.attr("data-id");
		var _post_close = $(_this.attr("data-target")).val();
		if (_post_close == "") { alert("No puede guardar una nota vacía"); }
		_AJAX.UiPostClose({ "id": _id, "post_close": _post_close }).then(function (data) {
			_FUNCTIONS.onRefreshBrowser();
			_FUNCTIONS.onDestroyModal("#previousModalView");
		});
	},
	onLoadPreviousTelemedicina: function (_id_charge_code, _target) {
		var _json = { "request_mode": "byuser", "id_charge_code": _id_charge_code };
		_AJAX.UiPreviousTelemedicina(_json).then(function (datajson) {
			$(_target).html("<ul class='ls-previous list-group'></ul>");
			if (datajson.status == "OK") {
				$.each(datajson.data, function (i, item) {
					var _rec = _TOOLS.utf8_to_b64(JSON.stringify(item));
					var _html = "<li data-mode='edit' class='previous-telemedicina shadow list-group-item list-group-item-light' style='width:100%;cursor:pointer;text-align:left;' data-item='" + _rec + "'>";
					_html += "     <table style='width:100%;'>";
					_html += "        <tr>";
					var _d = item.created.split("T");
					_html += "           <td style='width:100%;font-weight:bold;'>Atención del día " + _d[0] + " " + _d[1] + "</td> <td style='width:30px;'><span class='material-icons' style='color:blue;'>info</span></td>";
					_html += "        </tr>";
					if (item.indicaciones == null) { item.indicaciones = "Ninguna"; }
					_html += "        <tr>";
					_html += "           <td style='width:100%;'>Indicación: "+item.indicaciones+"</td>";
					_html += "        </tr>";
					_html += "     </table>";
					_html += "   </li>";
					$(".ls-previous").append(_html);
				});
			}
		}).catch(function (error) { alert(error.message); });
	},
	onLoadMessagesTelemedicina: function (_id_charge_code, _target1, _target2) {
		var _json = { "request_mode": "medic", "request_types": "both", "id_charge_code": _id_charge_code };
		_AJAX._waiter = false;
		_AJAX.UiRecetasTelemedicina(_json).then(function (datajson) {
			$(_target1).html("<ul class='ls-items1 list-group'></ul>");
			$(_target2).html("<ul class='ls-items2 list-group'></ul>");
			if (datajson.status == "OK") {
				$.each(datajson.data, function (i, item) {
					var _isPDF = (item.type_media == "pdf");
					var _line = "";
					var _align = "left";
					var _color = "list-group-item-dark";
					var _viewed = "";
					var _actual = "";
					var _target_final = "";
					var _rec = _TOOLS.utf8_to_b64(JSON.stringify(item));
					if (parseInt(item.viewed) == 0) {
						_viewed = "<td style='width:30px;' class='td-" + item.id + "'><span class='material-icons' style='color:orange;'>hourglass_empty</span></td>";
					} else {
						_viewed = "<td style='width:30px;' class='td-" + item.id + "'><span class='material-icons' style='color:cyan;'>check</span></td>";
					}
					if (_id_charge_code == item.id_charge_code) {
						_actual = "<td style='width:30px;' class='td-" + item.id + "'><span class='material-icons' style='color:red;'>favorite</span></td>";
					} else {
						_actual = "<td style='width:30px;' class='td-" + item.id + "'><span class='material-icons' style='color:gold;'>history</span></td>";
					}
					switch (parseInt(item.id_type_direction)) {
						case 1:
							_line = _actual + _viewed + "<td style='width:30px;'><span class='material-icons' style='color:navy;'>account_box</span></td><td style='width:100%;'>Mensaje al médico</td>";
							switch (parseInt(item.id_type_item)) {
								case 1: //imagen
									_target_final = ".ls-items1";
									_line = "<td style='width:100%;'>Imagen " + item.created + "</td><td style='width:30px;'><span class='material-icons' style='color:blue;'>photo_camera</span></td>" + _viewed + _actual;
									break;
							}
							break;
						case 2:
							_align = "right";
							_color = "list-group-item-light";
							_line = "<td style='width:100%;'>Mensaje " + item.created + "</td><td style='width:30px;'><span class='material-icons' style='color:green;'>chat</span></td>" + _viewed + _actual;
							switch (parseInt(item.id_type_item)) {
								case 2: //receta
									_target_final = ".ls-items2";
									_line = "<td style='width:100%;'>Receta " + item.created + "</td><td style='width:30px;'><span class='material-icons' style='color:darkred;'>receipt</span></td>" + _viewed + _actual;
									break;
							}
							break;
					}
					var _html = "<li class='shadow list-group-item " + _color + "' style='width:100%;cursor:pointer;text-align:" + _align + ";'>";
					_html += "     <table>";
					_html += "        <tr>";
					_html += "            <td style='width:55px;'>";
					if (item.id_operator == _AJAX._id_user_active && !_isPDF) {
						_html += "<a style='width:55px;' class='m-0 btn btn-raised btn-primary btn-sm btn-clon-receta' data-id_charge_code='" + _id_charge_code + "' data-id='" + item.id + "'>Clonar</a>";
					}
					_html += "            </td>";
					var _classClick = "msg-telemedicina";
					if (_isPDF) { _classClick = "msg-telemedicina-pdf"; }
					_html += "            <td><table><tr data-mode='edit' class='" + _classClick + "' data-item='" + _rec + "'>" + _line + "</tr></table></td>";
					_html += "        </tr>"
					_html += "     </table>";
					_html += "   </li>";
					if (_target_final != "") { $(_target_final).append(_html); }
				});
			}
		}).catch(function (error) { alert(error.message); });
	},
	onCancelTelemedicina: function (_this) {
		_FUNCTIONS.onDestroyModal("#cancelTelemedicinaModal");
		var _html = "<div class='modal fade' id='cancelTelemedicinaModal' role='dialog'>";
		_html += " <div class='modal-dialog modal-dialog-centered' role='document'>";
		_html += "  <div class='modal-content'>";
		_html += "    <div class='modal-header'>";
		_html += "      <h4 class='modal-title'>Cancelar atención pendiente</h4>";
		_html += "    </div>";
		_html += "    <div class='modal-body'>";

		_html += "    </div>";
		_html += "    <div class='modal-footer font-weight-light'>";
		_html += "       <table style='width:100%;'>";
		_html += "          <tr>";
		_html += "             <td width='50%' align='left'><button type='button' class='btn btn-raised btn-danger btn-ok-check' data-mode='next'>Cancelar atención</button></td>";
		_html += "             <td width='50%' align='right'><button type='button' class='btn btn-raised btn-info btn-cancel-check' data-id='" + _this.attr("data-id") + "' data-table='" + _this.attr("data-table") + "'>Salir sin cancelar</button></td>";
		_html += "          </tr>";
		_html += "       </table>";
		_html += "    </div>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";
		$("body").append(_html);

		$("body").off("click", ".btn-cancel-check").on("click", ".btn-cancel-check", function () {
			_FUNCTIONS.onDestroyModal("#cancelTelemedicinaModal");
		});

		$("body").off("click", ".btn-ok-check").on("click", ".btn-ok-check", function () {
			var _json = { "id": _this.attr("data-id"), "data-table": _this.attr("data-table") };
			_AJAX.UiCancelTelemedicina(_json).then(function (data) {
				if (data.status == "OK") {
					$(".btn-m_medical_monitoring").click();
					clearInterval(_FUNCTIONS._TIMER_FORM);
					_FUNCTIONS.onDestroyModal("#cancelTelemedicinaModal");
				} else {
					throw data;
				}
			}).catch(function (error) {
				_FUNCTIONS.onDestroyModal("#cancelTelemedicinaModal");
				_FUNCTIONS.onAlert({ "message": error.message, "class": "alert-danger" });
			});
		});
		$("#cancelTelemedicinaModal").modal({ backdrop: false, keyboard: true, show: true });
	},
	onClonarReceta: function (_this) {
		if (!confirm("Se emitirá una nueva receta exactamente igual a la seleccionada. ¿Confirma?")) { return false; }
		_AJAX.UiClonarRecetas({ "id_message": _this.attr("data-id"), "id_charge_code": _this.attr("data-id_charge_code") }).then(function (data) { });
		setTimeout(function () {
			alert("Se ha clonado la receta");
			$("#recetas").removeClass("show");
			$(".btnSoloRecetas").click();
		}, 1500);
	},

	onLogin: function (_this, _scoope) {
		return new Promise(
			function (resolve, reject) {
				var _mode = _this.attr("data-mode");
				try {
					/*Icon manager for rx!*/
					if (_scoope == undefined) { _scoope = "backend"; }
					if (_TOOLS.validate(".validate", true)) {
						var _json = _TOOLS.getFormValues(".dbase", _this);
						_json["scoope"] = _scoope;
						_AJAX._waiter = true;
						_AJAX.UiAuthenticate(_json)
							.then(function (_auth) {
								if (_auth.status == "OK") {
									_AJAX._waiter = true;
									_FUNCTIONS.onStatusAuthentication(_auth).then(function (datajson) {
										_AJAX._waiter = true;
										switch (_scoope) {
											case "mediya":
												_AJAX.UiLoggedMediYa({}).then(function (data) {
													if (data.status == "OK") {
														$(".main").fadeOut("fast", function () {
															$(".main").removeClass("container").addClass("container-flex").html(data.message).fadeIn("slow");
															resolve(data);
														});
													}
													else {
														throw data;
													}
												}).catch(function (error) { throw error; });
												break;
											case "integracion":
												_AJAX.UiLoggedIntegracion({}).then(function (data) {
													if (data.status == "OK") {
														$(".main").fadeOut("fast", function () {
															$(".main").removeClass("container").addClass("container-flex").html(data.message).fadeIn("slow");
															resolve(data);
														});
													}
													else {
														throw data;
													}
												}).catch(function (error) { throw error; });
												break;
											case "cesiones":
												_AJAX.UiLoggedCesiones({}).then(function (data) {
													if (data.status == "OK") {
														$(".main").fadeOut("fast", function () {
															$(".main").removeClass("container").addClass("container-flex").html(data.message).fadeIn("slow");
															resolve(data);
														});
													}
													else {
														throw data;
													}
												}).catch(function (error) { throw error; });
												break;
											case "tiendamil":
												_AJAX.UiLoggedTiendaMil({}).then(function (data) {
													if (data.status == "OK") {
														$(".main").fadeOut("fast", function () {
															$(".main").removeClass("container").addClass("container-flex").html(data.message).fadeIn("slow");
															resolve(data);
														});
													}
													else {
														throw data;
													}
												}).catch(function (error) { throw error; });
												break;
											case "backend":
												_AJAX.UiLogged({}).then(function (data) {
													if (data.status == "OK") {
														var _html = '<ul class="list-group">';
														var _sucursales = "";
														var _empty = true;
														$.each(_auth.data.details, function (i, item) {
															_empty = false;
															if (parseInt(item.nIDSucursal) !=0) {
																_sucursales += '<a class="list-group-item list-group-item-action btnSelectSucursal" href="#" data-name="' + item.sSucursal + '" data-id="' + item.nIDSucursal + '">' + item.sSucursal + '</a>';
															} else {
																_sucursales += '<a class="list-group-item list-group-item-action btnSelectSucursal" href="#" data-name="Casa Central" data-id="100">Casa Central</a>';
															}
														});
														if (_empty) {_sucursales = '<a class="list-group-item list-group-item-action btnSelectSucursal" href="#" data-name="Casa Central" data-id="100">Casa Central</a>';}
														_html += _sucursales;
														_html += '</ul>';
														_FUNCTIONS.onShowHtmlModal("", _html, function(){
															$(".modal-header").html("<h4>Seleccione sucursal donde se encuentra</h4>");
															$("body").off("click", ".btnSelectSucursal").on("click", ".btnSelectSucursal", function () {
																_AJAX._id_sucursal = $(this).attr("data-id");
																_AJAX._sucursal = $(this).attr("data-name");
																_FUNCTIONS.onDestroyModal(".modal");
																$(".main").fadeOut("fast", function () {
																	/*-----------------------------------------------*/
																	/*Configuracion de Comunicacion WebSocket*/
																	/*-----------------------------------------------*/
																	//_WEBSOCKET._targetReturn = ".log";
																	//_WEBSOCKET._host = "wss://websocket.credipaz.com";
																	//_WEBSOCKET._userIntranet = _AJAX._username_active;
																	//_WEBSOCKET._buildAlert = true;

																	$(".main").removeClass("container").addClass("container-flex").html(data.message).fadeIn("slow");
																	if (_auth.data.telemedicina_rol == "doctor") {
																		_FUNCTIONS.onEvalTelemedicinaQueue({});
																		$(".barTelemedicina").removeClass("d-none");
																		clearInterval(_FUNCTIONS._TIMER_TELEMEDICINA);
																		_FUNCTIONS._TIMER_TELEMEDICINA = setInterval(function () { _FUNCTIONS.onEvalTelemedicinaQueue({}); }, 120000);
																		
																	} else {
																		$(".barTelemedicina").remove();
																	}
																	if (_auth.data.tiendamil_rol=="1") {
																		_FUNCTIONS.onEvalTiendaMilQueue({});
																		clearInterval(_FUNCTIONS._TIMER_TIENDAMIL);
																		_FUNCTIONS._TIMER_TIENDAMIL = setInterval(function () { _FUNCTIONS.onEvalTiendaMilQueue({}); }, 15000);
																	} else {
																		$(".barTelemedicina").remove();
																	}
																	resolve(data);
																});
															});
														});
													}
													else {
														throw data;
													}
												}).catch(function (error) { throw error; });
												break;
											default:
												window.location = "/site/logged";
												resolve(data);
												break;
										}
									}).catch(function (error) { throw error; });
								} else {
									throw data;
								}
							}).catch(function (error) {
								alert(error.message);
								throw error;
							});
					}
				} catch (rex) {
					alert(rex.message);
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},

	onTraerLookUp2: function (_table, _key = null) {
		return new Promise(
			function (resolve, reject) {
				var _json = { "Tipo": _table, "key": _key };
				_AJAX.UiLookUp(_json).then(function (_data) {
					resolve(_data);
				}).catch(function (error) {
					reject(error);
				});
			});
	},
	onTraerLookUp: function (_table, _key = null) {
		return new Promise(
			function (resolve, reject) {
				var _json = { "function": "traerLookUp", "tabla": _table, "key": _key };
				_AJAX.UiClubRedondoWSTransparent(_json).then(function (_data) {
					resolve(_data);
				}).catch(function (error) {
					reject(error);
				});
			});
	},

	onLogout: function (_this, _mode) {
		if (_mode == undefined) { _mode = "/"; }
		_AJAX.UiLogout({}).then(function (data) {
			window.location = _mode;
		});
	},
	onToggleTelemedicina: function (_this) {
		_FUNCTIONS.onEvalTelemedicinaQueue({ "toggle": _this.attr("data-action") });
	},
	onEvalTelemedicinaQueue: function (_json) {
		_AJAX.UiEvalTelemedicinaQueue(_json).then(function (data) {
			$(".barTelemedicina").html(data.data);
			$(".myStatusTelemedicina").val(data.active);
			if (parseInt(data.active) == 1) {
				$(".doctor-on").removeClass("d-none");
				$(".doctor-off").addClass("d-none");
				if (parseInt($(".pacientesTelemedicina").val()) != parseInt(data.pacientes)) {
					$(".pacientesTelemedicina").val(data.pacientes);
					if (parseInt(data.pacientes) != 0 && $("#diagnostico").val() == undefined) {
						_FUNCTIONS.onShowAlert(data.data, "Nuevos pacientes en espera");
						if (!_FUNCTIONS._silence) { $("#ringerAlertas").attr("src", "assets/audio/vintage.mp3"); }
					}
				}
			} else {
				$(".doctor-off").removeClass("d-none");
				$(".doctor-on").addClass("d-none");
			}
		});
		try { _AJAX.UiAlertDelayTelemedicina({}).then(function () { }); } catch (err) { };
	},
	onEvalTiendaMilQueue: function (_json) {
		var _restoreU = _NEOVIDEO._username;
		var _restoreP = _NEOVIDEO._password;
		_NEOVIDEO._username = "mil";
		_NEOVIDEO._password = "08.!Rcp#@80";
		_NEOVIDEO.onListAvailableVideoRooms().then(function (data) {
			var _bAlert = false;
			var _html = "";
			$.each(data.records, function (i, item) { if (item.id_type_status == 1) { _bAlert = true; } });
			if (_bAlert) {
				_FUNCTIONS.onShowInfo("Nuevo cliente aguarda atención", "Alerta Tienda Mil");
				if (!_FUNCTIONS._silence) { $("#ringerAlertas").attr("src", "assets/audio/vintage.mp3"); }
			}
		});
		_NEOVIDEO._username = _restoreU;
		_NEOVIDEO._password = _restoreP;
	},
	onStatusAuthentication: function (datajson) {
		return new Promise(
			function (resolve, reject) {
				try {
					_AJAX.channels = datajson.data.channels;
					_AJAX._token_authentication = datajson.data.token_authentication;
					_AJAX._token_authentication_created = datajson.data.token_authentication_created;
					_AJAX._token_authentication_expire = datajson.data.token_authentication_expire;
					_AJAX._id_app = 7;
					_AJAX._id_user_active = datajson.data.id;
					_AJAX._id_type_user_active = datajson.data.id_type_user;
					_AJAX._username_active = datajson.data.username;
					_AJAX._master_account = datajson.data.master_account;
					_AJAX._image_active = datajson.data.image;
					_AJAX._master_image_active = datajson.data.master_image;
					$(".raw-id_user_active").html(_AJAX._id_user_active);
					$(".raw-id_type_user_active").html(_AJAX._id_type_user_active);
					$(".raw-master_account").html(_AJAX._master_account);
					$(".raw-username_active").html(_AJAX._username_active);
					$(".raw-a-token_created_datetime").html(_AJAX._token_authentication_created);
					$(".raw-a-token_ttl_datetime").html(_AJAX._token_authentication_expire);
					$(".raw-a-token_key").html(_AJAX._token_authentication);
					resolve(datajson);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onStatusClick: function (_this) {
		if (_this.hasClass("btn-menu-click")) { _this.find(".label-menu").append("<span class='mx-0 px-1 waiter wait-menu-ajax'></span>"); }
		if (_this.hasClass("btn-browser-search")) { _this.html("<span class='mx-0 px-1 waiter wait-search-ajax'></span>"); }
		if (_this.hasClass("btn-abm-accept")) { _this.html("<span class='mx-0 px-1 waiter wait-accept-ajax'></span>"); }
	},

	onMenuOpen: function (_this, e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
		$(".info-heading").addClass("d-none").fadeOut("slow");
	},
	onMenuClose: function (_this, e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
		$(".info-heading").removeClass("d-none").fadeIn("slow");
	},
	onMenuClick: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					_FUNCTIONS.onDestroyModal("#modal-info");
					//_WEBSOCKET.destroy(null);
					//_WEBSOCKET.connect(null);
					_AJAX._waiter = true;
					_AJAX.UiInformUserArea({ "last_area": _this.attr("data-area") }).then(function (data) { });
					$("." + _FUNCTIONS._defaultBrowserSearch).val("");
					switch (_this.attr("data-action")) {
						case "brow":
							_FUNCTIONS.onBrowserSearch(_this);
							break;
						case "form":
							_FUNCTIONS.onFormSearch(_this);
							break;
						default:
							_FUNCTIONS.onFormSearch(_this);
							break;
					}
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onToggleSilence: function () {
		_FUNCTIONS._silence = !_FUNCTIONS._silence;
		if (_FUNCTIONS._silence) {
			$(".icon-silence").html("volume_off");
		} else {
			$(".icon-silence").html("volume_up");
		}
	},
	onNextMedicalRequest: function (_this) {
		if (parseInt($(".myStatusTelemedicina").val()) == 0) { alert("Ud. se encuentra en EN DESCANSO.  Por favor cambie si estado a ATENDIENDO"); return false; }
		var _code = _this.attr("data-id");
		var _json = { "code": _code };
		/*control por atención espontánea*/
		if (_code == -999) {
			var _dni = $(".dniAdd").val();
			var _socio = $(".socioAdd").val();
			if (_dni == "" && _socio == "") { alert("Debe ingresar DNI o Nº de socio CR para generar una atención espontánea"); return false; }
			_json["dni"] = _dni;
			_json["id_club_redondo"] = _socio;
		}
		_AJAX._waiter = true;
		_AJAX.UiCheckPaycode(_json).then(function (data) {
			if (data.status == "OK") {
				//$(".page-item.active a").click();
				_this.attr("data-id", data.id_ot);
				_FUNCTIONS.onRecordEdit(_this);
			} else {
				throw data;
			}
		}).catch(function (error) {
			_FUNCTIONS.onAlert({ "message": error.message, "class": "alert-danger" });
		});
	},

	onRecordEdit: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					_this.hide();
					_FUNCTIONS._oLast_record = _this;
					_FUNCTIONS.onClearTimers();
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiEdit(_json).then(function (data) {
						if (data.status == "OK") {
							$(".dyn-area").addClass("d-none").hide();
							$(".abm").html(data.message).removeClass("d-none").fadeIn("slow");
							window.scrollTo(0, 0);
							_this.show();
							resolve(data);
						} else {
							_this.show();
							throw data;
						}
					}).catch(function (rex) {
						_FUNCTIONS.onShowAlert(rex.message, "No se puede editar el registro");
						_this.fadeOut();
						reject(rex);
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onRecordRemove: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (!confirm("¿Confirma el borrado del registro?")) {
						resolve(null);
						return false;
					}
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiDelete(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onBrowserSearch(_this).then(function () {
								_FUNCTIONS.onAlert({ "message": data.message, "class": "alert-info" });
								resolve(data);
							}).catch(function (error) { throw error; });
							resolve(data);
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onRecordOffline: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (!confirm("¿Confirma sacar de línea el registro?")) {
						resolve(null);
						return false;
					}
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiOffline(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onRefreshBrowser();
							resolve(data);
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onRecordOnline: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (!confirm("¿Confirma poner en línea el registro?")) {
						resolve(null);
						return false;
					}
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiOnline(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onRefreshBrowser();
							resolve(data);
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onRecordProcess: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (!confirm("¿Confirma la ejecución del proceso?")) {
						resolve(null);
						return false;
					}
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiProcess(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onBrowserSearch(_this).then(function () {
								_FUNCTIONS.onAlert({ "message": data.message, "class": "alert-info" });
								resolve(data);
							}).catch(function (error) { throw error; });
						} else {
							throw data;
						}
					}).error(function (err) {
						throw err;
					});
				} catch (rex) {
					_FUNCTIONS.onBrowserSearch(_this).then(function () {
						_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
						reject(rex);
					}).catch(function (error) { throw error; });
				}
			});
	},

	onAbmAccept: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (_TOOLS.validate(".validate", true)) {
						_AJAX._waiter = true;
						_AJAX.onBeforeSendExecute();
						setTimeout(function () {
							var _json = _TOOLS.getFormValues(".dbase", _this);
							console.log(_json);
							_AJAX.UiSave(_json).then(function (data) {
								if (data.status == "OK") {
									$(".abm").addClass("d-none").hide();
									$(".browser").removeClass("d-none").show();
									_FUNCTIONS.onAlert({ "message": "Se ha grabado el registro", "class": "alert-success" });
									_FUNCTIONS.onRefreshBrowser();
									resolve(data);
								} else {
									throw data;
								}
							});
						}, 250);
					}
				} catch (rex) {
					setTimeout(function () { _AJAX.onCompleteExecute(); }, 50);
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onAbmCancel: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _json = _TOOLS.getFormValues(".dbase", _this);
					_AJAX.UiUnlock(_json).then(function (data) { });
					window.scrollTo(0, 0);
					$(".abm").addClass("d-none").hide();
					$(".browser").removeClass("d-none").fadeIn("slow");
					_FUNCTIONS.onAlert({ "message": "No se han efectuado cambios al registro", "class": "alert-info" });
					_FUNCTIONS.onRefreshBrowser();
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},

	onBrowserSearch: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					_FUNCTIONS.onClearTimers();
					_AJAX._last_form = "";
					$(".abm").html("").addClass("d-none").hide();
					var _html = _this.html();
					_FUNCTIONS.onStatusClick(_this);
					var _json = _TOOLS.getFormValues(null, _this);
					var _data_mode = _this.attr("data-mode");
					if (_data_mode == undefined) { _data_mode = "brow"; }
					var _data_filters = _this.attr("data-filters");
					var _forced_field = _this.attr("data-forced-field");
					var _forced_value = _this.attr("data-forced-value");
					if (_forced_field == undefined) { _forced_field = ""; }
					if (_forced_value == undefined) { _forced_value == ""; }
					if (_data_filters == undefined || _data_filters == "[]") {
						_data_filters = [{ "name": _FUNCTIONS._defaultBrowserSearch, "operator": _FUNCTIONS._defaultBrowserSearchOperator, "fields": _FUNCTIONS._defaultBrowserSearchFields }];
					} else {
						_data_filters = JSON.parse(_data_filters);
					};
					if (_forced_field != "") { _json["forced_field"] = _forced_field; }
					if (_forced_value != "") { _json["forced_value"] = _forced_value; }
					var _where = "";
					var _arrS = [];
					var _arrW = [];
					$.each(_data_filters, function (i, item) {
						if ($("#" + item.name).val() != undefined && $("#" + item.name).val() != "") {
							var _value = $("#" + item.name).val();
							var _temp = "";
							_arrS.push({ "name": item.name, "value": _value });
							$.each(item.fields, function (ix, field) {
								if (_temp != "") { _temp += " OR "; }
								switch (item.operator.toLowerCase()) {
									case "like":
										_temp += (field + " " + item.operator + " '%" + _value + "%'");
										break;
									default:
										var _search = _value;
										if (item.type != undefined) {
											switch (item.type) {
												case "date":
													if (_search.indexOf(":") == -1) {
														if (item.operator == ">=") { _search += " 00:00:00"; }
														if (item.operator == "<=") { _search += " 23:59:59"; }
													}
													break;
											}
										}
										_temp += (field + " " + item.operator + " '" + _search + "'");
										break;
								}
							});
							if (_temp != "") { _arrW.push("(" + _temp + ")"); }
						}
					});
					for (var i = 0; i < _arrW.length; i++) { if (_where != "") { _where += " AND "; } _where += ("(" + _arrW[i] + ")"); }
					if (_forced_field != "" && _forced_value != "") {
						_arrS.push({ "name": "browser_" + _forced_field, "value": _forced_value });
						_where = (_forced_field + "=" + _forced_value);
					}
					_json["where"] = _where;
					switch (_data_mode) {
						case "brow":
							_AJAX.UiBrow(_json).then(function (data) {
								if (data.status == "OK") {
									$(".browser").html(data.message).removeClass("d-none").fadeIn("slow");
									//$(".search-trigger").css("border", "solid 1px silver");
									for (var i = 0; i < _arrS.length; i++) {
										var _id = ("#" + _arrS[i]["name"]);
										$(_id).val(_arrS[i]["value"]);
									}

									$(".search-trigger").each(function () {
										switch ($(this).prop("tagName")) {
											case "TEXTAREA":
											case "INPUT":
												if ($(this).val() != "") { $(this).css("border", "solid 1px rgb(235, 0, 139)"); }
												break;
											case "SELECT":
												var _id = ("#" + $(this).attr("id"));
												if ($(_id + " option:selected").text() != "[Seleccione]" && $(_id + " option:selected").text() != "[Todo]") {
													$(this).css("border", "solid 1px rgb(235, 0, 139)");
												}
												break;
										}
									})
									for (var i = 0; i < _arrS.length; i++) {
										var _id = ("#" + _arrS[i]["name"]);
									}
									resolve(data);
								} else {
									throw data;
								}
							}).catch(function (error) {
								throw error;
							});
							break;
						case "excel":
							_AJAX.UiExcel(_json);
							break;
						case "pdf":
							_AJAX.UiPdf(_json);
							break;
					}
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				} finally {
					setTimeout(function () { _this.html(_html) }, 250);
				}
			});
	},
	onFormSearch: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					_FUNCTIONS.onClearTimers();
					var _html = _this.html();
					var _target = _this.attr("data-forced");
					if (_target == undefined) {
						$(".abm").html("").addClass("d-none").hide();
						_target = ".browser";
					} else {
						$(".hideable").removeClass("active").removeClass("in");
						$(".nav-link").removeClass("active").removeClass("show");
					}
					_FUNCTIONS.onStatusClick(_this);
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX._last_form = _json.model;
					_json["function"] = _this.attr("data-action");
					_AJAX.UiForm(_json).then(function (data) {
						if (data.status == "OK") {
							$(_target).html(data.message).removeClass("d-none").fadeIn("slow");
							resolve(data);
						} else {
							throw data;
						}
					}).catch(function (error) {
						throw error;
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				} finally {
					setTimeout(function () { _this.html(_html) }, 250);
				}
			});
	},

	onVerifySigns: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _body = "";
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiVerifySigns(_json).then(function (data) {
						_body = "<h4><span class='badge badge-success'>Integridad verificada</span></h4>";
						_FUNCTIONS.onInfoModal({ "title": "Verificación de firmas", "body": _body, "close": true, "size": "modal-sm", "center": false });
						_FUNCTIONS.onRefreshBrowser();
						resolve(data);
					}).catch(function (error) {
						_body = "<h4><span class='badge badge-danger'>El registro tiene su integridad comprometida</span></h4>";
						_FUNCTIONS.onInfoModal({ "title": "Verificación de firmas", "body": _body, "close": true, "size": "modal-sm", "center": false });
						_FUNCTIONS.onRefreshBrowser();
						resolve(error);
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onFollowChangePriority: function (_this, _module) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _id = _this.attr("data-id");
					var _new = _this.html();
					var _json = { "id": _id, "id_type_priority": _this.attr("data-status"), "module": _this.attr("data-module") };
					_AJAX.UiFollowChangePriority(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onAlert({ "message": "Se ha cambiado la prioridad correctamente", "class": "alert-info" });
							$(".btn-title-change-priority-" + _id).html(_new);
							$(".page-item.active a").click();
							resolve(true);
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onFollowChangeAudit: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _id = _this.attr("data-id");
					var _audit_control = 0;
					var _msg = "Se ha quitado la 'Auditoría en terreno'";
					if (_this.prop("checked")) { _audit_control = 1; _msg = "Se ha asignado la 'Auditoría en terreno'"; };
					var _json = { "id": _id, "audit_control": _audit_control };
					_AJAX.UiFollowChangeAudit(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onAlert({ "message": _msg, "class": "alert-info" });
							resolve(true);
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onFollowChangeFullVacuna: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _id = _this.attr("data-id");
					var _full_vacuna = 0;
					var _msg = "Se ha quitado la marca de 'Vacunación completa'";
					if (_this.prop("checked")) { _full_vacuna = 1; _msg = "Se ha informado 'Vacunación completa'"; };
					var _json = { "id": _id, "full_vacuna": _full_vacuna };
					_AJAX.UiFollowChangeFullVacuna(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onAlert({ "message": _msg, "class": "alert-info" });
							resolve(true);
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onFoldersChangeStatus: function (_this, _module) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _id = _this.attr("data-id");
					var _new = _this.html();
					var _json = { "id": _id, "id_type_control_point": _this.attr("data-status"), "module": _this.attr("data-module") };
					_AJAX.UiFolderChangeStatus(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onAlert({ "message": "Se ha cambiado el estado correctamente", "class": "alert-info" });
							$(".btn-title-change-status-" + _id).html(_new);
							$(".page-item.active a").click();
							resolve(true);
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onFoldersNotViewedNotification: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					$(".TYPE").html("");
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiFoldersNotViewedNotification(_json).then(function (data) {
						$(".divHome").addClass("d-none").hide();
						var _iNotReady = 0;
						$.each(data.data, function (i, val) {
							if (_AJAX._last_form == "home") {
								_iNotReady += val.total;
								var _html = "<div class='py-1' style='color:cadetblue;'>Documentos sin leer <span class='float-right badge badge-primary' style='background-color:transparent;color:rgb(235, 0, 139);font-size:16px;'>" + val.total + "</span></div>";
								$(".TYPE-" + val.id_type_folder).html(_html);
							}
						});
						$.each(data.data_revisar, function (i, val) {
							if (_AJAX._last_form == "home") {
								var _html = "<div class='py-1'>Documentos para revisar <span class='float-right badge badge-primary magenta' style='background-color:rgb(235, 0, 139);font-size:16px;'>" + val.total + "</span></div>";
								$(".TYPE-" + val.id_type_folder).append(_html);
							}
						});
						$.each(data.data_publicar, function (i, val) {
							if (_AJAX._last_form == "home") {
								var _html = "<div class='py-1'><a href='#' class='btn-menu-click btn-m_folder_items' data-module='mod_folders' data-model='folders' data-table='folders' data-action='brow' data-page='1' data-forced-field='id_type_folder' data-forced-value='" + val.id_type_folder + "'>Documentos para publicar <span class='float-right badge badge-primary magenta' style='background-color:rgb(235, 0, 139);font-size:16px;'>" + val.total + "</span></a></div>";
								$(".TYPE-" + val.id_type_folder).append(_html);
							}
						});
						//$(".col").removeClass("d-none").fadeIn("fast");
						$(".raw-folder_alert").removeClass("d-none").addClass("d-sm-inline").html("<i class='material-icons' style='color:black;'>notifications</i> " + _iNotReady);
						//Only visible if some documents not offline related to user!
						$.each(data.totals, function (i, val) {
							if (parseInt(val.total) != 0) { $(".divHome-" + val.code_type_folder).removeClass("d-none").fadeIn("fast"); }
						});
						resolve(data);
					}).catch(function (error) {
						resolve(error);
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onViewFile: function (_this) {
		_AJAX.UiFolderFileLoader({ "data": _this.attr("data-direct") });
	},
	onMessageRead: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					_AJAX.UiMessageRead({ "id": _this.attr("data-id") }).then(function (data) {
						_this.fadeOut("fast", function () { _this.remove(); })
						resolve(data);
					}).catch(function (error) {
						resolve(error);
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onMessagesNotification: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiMessagesNotification(_json).then(function (data) {
						if (data.data.length != 0) {
							$(".raw-messages_alert").removeClass("d-none").addClass("d-sm-inline").html("<i class='material-icons'>email</i> " + data.data.length);
						} else {
							$(".raw-messages_alert").removeClass("d-sm-inline").addClass("d-none").html("");
						}
						resolve(data);
					}).catch(function (error) {
						resolve(error);
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onTypeCommandChange: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _data = { "module": "mod_push", "table": "type_actions", "model": "type_actions", "where": "id_type_command=" + $("#id_type_command").val(), "order": "description ASC", "page": -1, "pagesize": -1 };
					_AJAX.UiGet(_data).then(function (datajson) {
						_FUNCTIONS._cache.type_actions = datajson;
						_TOOLS.loadCombo(datajson, { "target": "#id_type_action", "selected": -1, "id": "id", "description": "description" });
						resolve(_FUNCTIONS._cache.type_actions);
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onCheckRecord: function (_this) {
		if (_this.val() == 0) { $(".btn-record-check").prop("checked", _this.prop("checked")); }
	},
	onCheckExternalOperator: function (_this) {
		$(".btn-ResetPassword").addClass("d-none");
		if ($(".external_operator").prop("checked")) { $(".btn-ResetPassword").removeClass("d-none"); }
	},
	onAssignBuffer: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _mode = _this.attr("data-id");
					var _ids = [];
					var _id_operator = _this.attr("data-status");
					if (_mode == 0) {
						$(".btn-record-check").each(function () { if ($(this).prop("checked")) { _ids.push($(this).val()); } });
					} else {
						_ids.push(_mode);
					}
					_ids.forEach(function (_id) { $(".record-" + _id).fadeOut("slow"); });
					_AJAX.UiAssignBuffer({ "ids": _ids, "id_operator": _id_operator }).then(function (datajson) {
						_ids.forEach(function (_id) { $(".record-" + _id).remove(); });
						resolve(datajson);
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onAssignOperator: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _mode = _this.attr("data-id");
					var _ids = [];
					var _id_operator = _this.attr("data-status");
					if (_mode == 0) {
						$(".btn-record-check").each(function () { if ($(this).prop("checked")) { _ids.push($(this).val()); } });
					} else {
						_ids.push(_mode);
					}
					_ids.forEach(function (_id) { $(".record-" + _id).fadeOut("slow"); });
					_AJAX.UiAssignOperator({ "ids": _ids, "id_operator": _id_operator }).then(function (datajson) {
						_ids.forEach(function (_id) { $(".record-" + _id).remove(); });
						resolve(datajson);
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onTypeTaskClose: function (_this) {
		var _is_cash = (_this.find(':selected').attr('data-is_cash'));
		var _is_reschedule = (_this.find(':selected').attr('data-is_rescheduled'));
		$(".valorized").removeClass("validate").val(0);
		$(".re_scheduled").removeClass("validate").val("");
		$(".is_cash").addClass("d-none");
		$(".is_rescheduled").addClass("d-none");
		if (_is_cash == 1) {
			$(".valorized").addClass("validate");
			$(".is_cash").removeClass("d-none");
		}
		if (_is_reschedule == 1) {
			$(".re_scheduled").addClass("validate");
			$(".is_rescheduled").removeClass("d-none");
		}
	},
	onIdProvider: function (_this) {
		_AJAX.UiGetSectorsByProvider({ "id_provider": _this.val() }).then(function (data) {
			if (data.status == "OK") {
				$('.id_type_sector').selectpicker('destroy');
				$(".id_type_sector").empty();
				$.each(data.data, function (i, item) { $('.id_type_sector').append(new Option(item.description, item.id)); });
				$('.id_type_sector').selectpicker('refresh');
			}
		});
	},
	onStatusFolderItem: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _id = _this.attr("data-id");
					var _status = _this.attr("data-status");
					_AJAX.UiStatusFolderItem({ "id": _id, "status": _status }).then(function (data) {
						var _iMsg = parseInt($(".raw-folder_alert").html().replace('<i class="material-icons">notifications</i> ', ""));
						switch (_status) {
							case "ready":
								$(".badge-readed").html("Leído");
								$(".status-" + _id).removeClass("magenta").addClass("black");
								$(".ready-" + _id).attr("data-status", "notready").html("Marcar como pendiente");
								_iMsg -= 1;
								break;
							case "notready":
								$(".badge-readed").html("No leído");
								$(".status-" + _id).removeClass("black").addClass("magenta");
								$(".ready-" + _id).attr("data-status", "ready").html("Marcar como leído");
								_iMsg += 1;
								break;
						}
						$(".btn-menu-" + _id).remove();
						if (isNaN(_iMsg)) { _iMsg = 0; }
						$(".raw-folder_alert").html("<i class='material-icons'>notifications</i> " + _iMsg);
						resolve(data);
					}).catch(function (error) {
						resolve(error);
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onFolderAudit: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _id = _this.attr("data-id");
					_AJAX.UiFolderDetails({ "id": _id }).then(function (datajson) {
						if (datajson.status == "OK") {
							var _title = "Detalles de acceso a la carpeta";
							var _body = "<table class='class'>";
							_body += "   <tbody>";
							$.each(datajson.data, function (i, file) {
								_body += "   <tr><td colspan='4'><span class='badge badge-dark'>" + file.description + "</span></td></tr>";
								$.each(file.users, function (x, user) {
									var _viewed = user.viewed;
									var _lbl = "<span class='badge badge-danger'>No visto</span>";
									if (_viewed != null) {
										_lbl = "<span class='badge badge-success'>" + _viewed + "</span>";
									}
									_body += "   <tr><td><i class='material-icons'>person</i></td><td><span class='badge badge-light'>" + user.username + "</span></td><td>" + _lbl + "</td></tr>";
								});
							});
							_body += "   </tbody>";
							_body += "</table>";

							_FUNCTIONS.onInfoModal({ "title": _title, "body": _body, "close": true, "size": "modal-xl", "center": false });
							resolve(datajson);
						} else {
							throw datajson;
						}
					}).catch(function (error) { throw error; });
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onIdTypeTaskClose: function (_this) {
		switch (parseInt(_this.val())) {
			case 2:
			case 4:
				$(".div_note_close").removeClass("d-none");
				$("#note_close").addClass("validate");
				break;
			default:
				$(".div_note_close").addClass("d-none");
				$("#note_close").val("").removeClass("validate");
				break;
		}
	},
	onReportsCRM: function (_this) {
		if (_TOOLS.validate(".validate")) {
			var _json = {
				"function": "Reports",
				"from": $("#report_from").val(),
				"to": $("#report_to").val(),
				"report": _this.attr("data-report"),
				"username": $("#username").val(),
				"id_type_task_close": $("#id_type_task_close").val(),
				"id_type_contact_channel": $("#id_type_contact_channel").val(),
				"id_tarjeta": $("#id_tarjeta").val(),
				"id_otro": $("#id_otro").val(),
				"id_myd": $("#id_myd").val(),
				"id_mil": $("#id_mil").val(),
				"id_credito": $("#id_credito").val(),
				"id_club_redondo": $("#id_club_redondo").val(),
			};
			_AJAX._waiter = true;
			_AJAX.UiCRM(_json).then(function (data) {
				var _html = ("<h3>" + _this.attr("data-title") + "</h3>");
				_html += "<table class='table table-condensed'>";
				_html += " <tr>";
				_html += "   <th>Operador</th>";
				_html += "   <th>Tarea</th>";
				_html += "   <th>Tipo de canal</th>";
				_html += "   <th>Tipo de cierre</th>";
				_html += " </tr>";
				$.each(data.data, function (i, val) {
					_html += "<tr>";
					_html += "   <td>" + val.operator + "</td>";
					_html += "   <td>" + val.description + "</td>";
					_html += "   <td>" + val.type_contact_channel + "</td>";
					_html += "   <td>" + val.type_task_close + "</td>";
					_html += "</tr>";
				});
				_html += "</table>";
				$(".resultado").html(_html);
			});
		} else {
			_FUNCTIONS.onShowAlert("Complete los datos requeridos", "Datos faltantes");
		}
	},
	onFreeTelemedicina: function (_this) {
		if (confirm("El paciente volverá a la sala de espera, sin médico asignado.  ¿Confirma?")) {
			var _id = _this.attr("data-id");
			var _json = { "id": _id };
			_AJAX.UiFreeTelemedicina(_json).then(function (data) {
				if (data.status == "OK") {
					$(".record-" + _id).fadeOut("fast").remove();
				} else {
					throw data;
				}
			}).catch(function (error) {
				_FUNCTIONS.onAlert({ "message": error.message, "class": "alert-danger" });
			});
		}
	},
	onRequestPictures: function (_this) {
		if (confirm("El paciente volverá a la sala de espera, seguirá asignado a Ud.\nSe indicará en su listado de atención si ya tiene o no, las imágenes solicitadas para que pueda retomar su atención\n¿Confirma?")) {
			var _request_pictures = (parseInt($("#request_pictures").val()) + 1);
			$("#request_pictures").val(_request_pictures);
			$("#id_type_task_close").val("");
			$(".btn-abm-accept").click();
		}
	},
	onAddLinkExternal: function (_this) {
		var _target = _this.attr("data-target");
		var _html = "";
		_html += "<label>Descripción</label>";
		_html += "<input value='' class='validate-link form-control' type='text' name='title-link' id='title-link' data-clear-btn='false' placeholder='Descripción' />";
		_html += "<label>HTML para insertar link</label>";
		_html += "<textarea rows='20' id='body-link' name='body-link' class='validate-link shadow' style='width:100%;'></textarea>";
		_html += "<div class='panel-footer'>";
		_html += " <div class='row'>";
		_html += "  <div class='col-6'>";
		_html += "   <a class='btn-cancel-link btn btn-danger btn-raised btn-md'>Cancelar</a>";
		_html += "  </div>";
		_html += "  <div class='col-6'>";
		_html += "   <a class='btn-success-link btn btn-success btn-raised btn-md'>Aceptar</a>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";
		_FUNCTIONS.onShowHtmlModal("Ingresar link externo ", _html, function () {
			$("body").off("click", ".btn-success-link").on("click", ".btn-success-link", function () {
				if (!_TOOLS.validate(".validate-link", false)) {
					_FUNCTIONS.onShowAlert("Complete los datos requeridos", "Datos faltantes");
					return false;
				}
				var _name = $("#title-link").val();
				var _body = $("#body-link").val();
				var _id = _TOOLS.UUID();
				var _html = "<li class='list-group-item li-" + _id + "'>";
				_html += "<span data-id='" + _id + "' data-link='" + _body + "' class='new-link img-" + _id + "' data-filename='" + _name + "'><i class='material-icons'>link</i></span> ";
				_html += "<div class='badge badge-primary text-truncate' style='display:inline-block;max-width:100%;' title='" + _name + "'>" + _name + "</div> ";
				_html += "<a href='#' data-id='" + _id + "' class='btn btn-sm btn-danger float-right btn-link-delete'><i class='material-icons'>delete</i></a>";
				_html += "</li>";
				$(_target).append(_html);
				$("#modal-html").modal("hide").data("bs.modal", null);
				_FUNCTIONS.onDestroyModal("#modal-html");
			});
			$("body").off("click", ".btn-cancel-link").on("click", ".btn-cancel-link", function () {
				$("#modal-html").modal("hide").data("bs.modal", null);
				_FUNCTIONS.onDestroyModal("#modal-html");
			});
		});
	},

	onBlockTelemedicina: function () {
		_FUNCTIONS.onDestroyModal("#blockModal");
		var _html = "<div class='modal fade' id='blockModal' role='dialog'>";
		_html += " <div class='modal-dialog modal-dialog-centered' role='document'>";
		_html += "  <div class='modal-content'>";
		_html += "    <div class='modal-header text-danger'>";
		_html += "      <h2 class='modal-title'>No se ha activado la grabación de auditoria.</h2>";
		_html += "    </div>";
		_html += "    <div class='modal-body'>";
		_html += "      <h3>Por favor reintente y asegúrese de activar la grabación.</h3>";
		_html += "    </div>";
		_html += "    <div class='modal-footer font-weight-light'>";
		_html += "       <button type='button' class='btn-raised btn btn-reload-alert btn-danger btn-sm'><i class='material-icons'>done</i></span>Volver a intentar</button>";
		_html += "    </div>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";
		$("body").append(_html);
		$("body").off("click", ".btn-cancel-alert").on("click", ".btn-reload-alert", function () {
			_FUNCTIONS.onDestroyModal("#blockModal");
			$(".btn-record-start").click();
		});
		$("#blockModal").modal({ backdrop: false, keyboard: false, show: true });
	},
	onLoadPaymentData: function (_interval, _form, _gateway, _importe_forzado = 0) {
		if (_importe_forzado == "") { _importe_forzado = 0; }
		_timer = setTimeout(function () {
			clearTimeout(_timer);
			$(".div-msg-intranet").html("").addClass("d-none")
			$(".div-msg-web").html("").hide();
			var _dni = $("#dni").val();
			var _json = { "form": _form, "gateway": _gateway, "dni": _dni, "function": "dataForPaymentsByType" };
			_AJAX.UiClubRedondoWSTransparent(_json).then(function (data) {
				var _inhabilitadaT = false;
				var _inhabilitadaC = false;
				if (data.status == "OK") {
					$(".div-datos").removeClass("d-none");
					$(".datos-informados").html(_FUNCTIONS.onBuildPaymentsByForm(data, _importe_forzado)).removeClass("d-none");

					const element = document.getElementById('otro_monto');
					if (element != null) {
						const maskOptions = {
							mask: Number,
							scale: 2,
							thousandsSeparator: '.',
							padFractionalZeros: true,
							normalizeZeros: true,
							radix: ',',
							mapToRadix: ['.'],
							min: 0,
							max: 999999999,
							autofix: true,
						};
						const mask = IMask(element, maskOptions);
					}

					if ($(".samImporte").val() != undefined) { totalizePayment($(".samImporte")); }
					if ($(".moraImporte").val() != undefined) { totalizePayment($(".moraImporte")); }
					if (parseFloat(_importe_forzado) != 0) {
						$(".chkPay").prop("checked", true).prop("disabled", true);
						totalizePayment($(".chkPay"));
					}
					$("body").off("change", ".cbo-cards").on("change", ".cbo-cards", function () {
						_COIN.getQuotes($(this));
					});
					$("body").off("change", ".cbo-quotes").on("change", ".cbo-quotes", function () {
						var _val = $('.cbo-quotes option:selected').data('amount');
						var _importe = _TOOLS.formatMoney(_val, 2);
						$(".coinTotal").html(_importe);
						_COIN.setQuote($(this));
					});
					$("body").off("change", ".chkPay").on("change", ".chkPay", function () {
						totalizePayment($(this));
					});
					$("body").off("keydown", ".otro_monto").on("keydown", ".otro_monto", function (e) {
						$(".data-payment1").addClass("d-none");
					});
					$("body").off("keyup", ".otro_monto").on("keyup", ".otro_monto", function (e) {
						$(".data-payment1").addClass("d-none");
						clearTimeout(_FUNCTIONS._TIMER_LAZY);
						_FUNCTIONS._TIMER_LAZY = setTimeout(function () { totalizePayment($(this)); }, 500);
					});
					$("body").off("keydown", ".samImporte").on("keydown", ".samImporte", function (e) {
						$(".data-payment1").addClass("d-none");
					});
					$("body").off("keyup", ".samImporte").on("keyup", ".samImporte", function (e) {
						$(".data-payment1").addClass("d-none");
						clearTimeout(_FUNCTIONS._TIMER_LAZY);
						_FUNCTIONS._TIMER_LAZY = setTimeout(function () { totalizePayment($(this)); }, 500);
					});
					$("body").off("keydown", ".moraImporte").on("keydown", ".moraImporte", function (e) {
						$(".data-payment1").addClass("d-none");
					});
					$("body").off("keyup", ".moraImporte").on("keyup", ".moraImporte", function (e) {
						$(".data-payment1").addClass("d-none");
						clearTimeout(_FUNCTIONS._TIMER_LAZY);
						_FUNCTIONS._TIMER_LAZY = setTimeout(function () { totalizePayment($(this)); }, 500);
					});
				} else {
					throw data;
				}
			}).catch(function (err) {
				$(".div-msg-intranet").removeClass("d-none").html("<h1 class='mt-3 text-center' style='border-raius:10px;border:solid 1px red;color:red;'>" + err.message + "</h1>");
				$(".datos-informados").html("");
			});
		}, _interval);
	},
	onBuildPaymentsByForm(data, _importe_forzado = 0) {
		var _bDirty = false;
		var _html = "";
		_html += _FUNCTIONS.onBuildItemsTarjeta(data, _importe_forzado);
		_html += _FUNCTIONS.onBuildItemsCredito(data, _importe_forzado);
		_html += _FUNCTIONS.onBuildItemsSAM(data, _importe_forzado);
		_html += _FUNCTIONS.onBuildItemsMORA(data, _importe_forzado);
		_html += _FUNCTIONS.onBuildItemsCuotaInicialCR(data, _importe_forzado);
		_html += _FUNCTIONS.onBuildItemsCuotaCRDO(data, _importe_forzado);
		_bDirty = (_html != "");
		if (!_bDirty) {
			_html = "<h3>Sin datos para este documento</h3>";
		} else {
			_FUNCTIONS.onReBuildLinkPago();
			_html += "<table style='width:100%;font-size:18px;margin-top:15px;border:dowuble 3px silver;'>";
			_html += "<tr>";
			_html += "   <td colspan='2'><b>TOTAL A PAGAR</b></td>";
			_html += "   <td align='right' class='coinTotal' style='font-size:18px;font-weight:bold;padding:10px;'></td>";
			_html += "   <td></td>";
			_html += "</tr>";
			_html += "</table>";
		}
		return _html;
	},

	onReBuildLinkPago: function () {
		var _link = $(".linkDni").val();
		var _dni = $(".dni_tarjeta").val();
		var _importe = $(".importe").val();
		if (_importe == "") { _importe = 0; }
		var _additional = (_dni + "|" + _importe);
		if (_link != "") {
			var _url = (window.location.origin + "/" + _link + "/" + _TOOLS.utf8_to_b64(_additional));
			if (_FUNCTIONS._forcedAlias != "") {
				_url = (_FUNCTIONS._forcedAlias + "/" + _link + "/" + _TOOLS.utf8_to_b64(_additional));
			}

			$(".div-msg-web").html("<table><tr><td><a href='#' class='btn bt-raised btn-sm btn-primary btn-raised btn-copySimple' data-source='myInput'><i class='material-icons'>share</i> Copiar Link de pago</a></td><td style='display:none;'><pre class='nd-button'>" + _url + "</pre></td></tr></table><input type='text' value='" + _url + "' id='myInput' class='d-none'>").fadeIn("fast");
			$(".div-msg-web").show();
		} else {
			$(".div-msg-web").html("");
		}
	},
	onBuildItemsTarjeta: function (data, _importe_forzado = 0) {
		var _html = "";
		var _bDirty = false;
		var _total = false;
		if (data.data.tarjeta != null && data.data.tarjeta.length > 0) {
			_html += "<h5>Tarjeta - " + data.data.tarjeta[0].Nombre + "</h5>";
			_html += "<table style='width:100%;font-size:14px;'>";
			$.each(data.data.tarjeta, function (i, item) {
				if (item.Identificacion != "") {
					_bDirty = true;
					if (item.Total != null) { _total = (parseFloat(item.Total) > 0); }
					var _data = { "Tipo": item.Tipo, "Identificacion": item.Identificacion, "Importe": item.Minimo };
					var _rec = _TOOLS.utf8_to_b64(JSON.stringify(_data));
					_html += "<tr>";
					_html += "   <td style='width:75%;'>Pago mínimo</td>";
					_html += "   <td style='width:2%;' align='left'></td>";
					_html += "   <td style='width:100px;' align='right'>" + _TOOLS.formatMoney(item.Minimo, 2) + "</td>";
					_html += "   <td align='center' style='width:15%;'><input data-sort='-999' data-reset='.chkTarTot' type='checkbox' class='chkPay chkTarMin chkTar' id='chkPay' name='chkPay' value='" + item.Minimo + "' data-record='" + _rec + "'/></td>";
					_html += "</tr>";
					_data = { "Tipo": item.Tipo, "Identificacion": item.Identificacion, "Importe": item.Total };
					_rec = _TOOLS.utf8_to_b64(JSON.stringify(_data));
					_html += "<tr>";
					_html += "   <td style='width:75%;'>Pago total</td>";
					_html += "   <td style='width:2%;' align='left'></td>";
					_html += "   <td style='width:100px;' align='right'>" + _TOOLS.formatMoney(item.Total, 2) + "</td>";
					_html += "   <td align='center' style='width:15%;'><input data-sort='-999' data-reset='.chkTarMin' type='checkbox' class='chkPay chkTarTot chkTar' id='chkPay' name='chkPay' value='" + item.Total + "' data-record='" + _rec + "'/></td>";
					_html += "</tr>";
					_data = { "Tipo": item.Tipo, "Identificacion": item.Identificacion, "Importe": 0 };
					_rec = _TOOLS.utf8_to_b64(JSON.stringify(_data));
					_html += "<tr>";
					_html += "   <td style='width:75%;'>Otro monto</td>";
					_html += "   <td style='width:2%;' align='left'>$</td>";
					_html += "   <td style='width:100px;' align='right'><input onclick='this.select();' data-reset='.chkTar' style='width:100px;text-align:right;-webkit-appearance:none;margin:0;' type='text' id='otro_monto' name='otro_monto' class='form-control otro_monto' value='' data-record='" + _rec + "'/></td>";
					_html += "   <td align='left' style='width:15%;'></td>";
					_html += "</tr>";
				}
			});
			_html += "</table>";
		}
		if (!_bDirty) { _html = ""; }
		if (!_total) { _html = ""; }
		return _html;
	},
	onBuildItemsCredito: function (data, _importe_forzado = 0) {
		var _html = "";
		var _bDirty = false;
		var _total = false;
		if (data.data.credito != null && data.data.credito.length > 0) {
			_html += "<h5>Crédito - " + data.data.credito[0].Nombre + "</h5>";
			_html += "<table style='width:100%;font-size:14px;'>";
			_html += "<tr style='font-weight:bold;'>";
			_html += "   <td align='center'>Vto.</td>";
			_html += "   <td align='center'>Nº</td>";
			_html += "   <td align='right'>Cuotas</td>";
			_html += "   <td align='right'>Punit.</td>";
			_html += "   <td align='right'>Total</td>";
			_html += "   <td></td>";
			_html += "</tr>";
			$.each(data.data.credito, function (i, item) {
				if (item.Identificacion != "") {
					_bDirty = true;
					if (item.Total != null) { _total = (parseFloat(item.Total) > 0); }
					var _data = { "Tipo": item.Tipo, "Identificacion": item.Identificacion, "Importe": item.Total };
					var _rec = _TOOLS.utf8_to_b64(JSON.stringify(_data));
					_html += "<tr>";
					_html += "   <td align='center'>" + item.Vto + "</td>";
					_html += "   <td align='center'>" + item.Cuota + "</td>";
					_html += "   <td align='right'>" + _TOOLS.formatMoney(item.ImporteCuota, 2) + "</td>";
					_html += "   <td align='right'>" + _TOOLS.formatMoney(item.Punitorios, 2) + "</td>";
					_html += "   <td align='right'>" + _TOOLS.formatMoney(item.Total, 2) + "</td>";
					_html += "   <td align='center' style='width:15%;'><input data-sort='" + i + "' data-reset='' type='checkbox' class='chkPay chkCre' id='chkPay' name='chkPay' value='" + item.Total + "' data-record='" + _rec + "'/></td>";
					_html += "</tr>";
				}
			});
			_html += "</table>";
		}
		if (!_bDirty) { _html = ""; }
		if (!_total) { _html = ""; }
		return _html;
	},
	onBuildItemsSAM: function (data, _importe_forzado = 0) {
		var _html = "";
		var _bDirty = false;
		var _total = false;
		var _rec = null;
		if (data.data.sam != null && data.data.sam.length > 0) {
			_html += "<h5>SAM - " + data.data.sam[0].Nombre + "</h5>";
			_html += "<table style='font-size:14px;'>";
			$.each(data.data.sam, function (i, item) {
				if (item.Identificacion != "") {
					_bDirty = true;
					if (item.Total != null) { _total = (parseFloat(item.Total) > 0); }
					var _data = { "Tipo": item.Tipo, "Identificacion": item.Identificacion, "Importe": item.Minimo };
					_rec = _TOOLS.utf8_to_b64(JSON.stringify(_data));
					_html += "<tr>";
					_html += "   <td>" + item["Descripcion"] + " #" + item.Identificacion + "</td>";
					_html += "   <td align='right' style='padding-left:15px;'>";
					_html += "      $ <input style='display:inline;width:100px;text-align:right;-webkit-appearance:none;margin:0;' min='" + parseInt(item.Minimo) + "' max='" + parseInt(item.Total) + "' type='number' id='samImporte' name='samImporte' class='form-control samImporte' value='" + parseInt(item.Minimo) +"' data-record='" + _rec + "' />"
					_html += "   (Mínimo " + _TOOLS.formatMoney(item.Minimo, 2) + " Máximo " + _TOOLS.formatMoney(item.Total, 2) + ")";
					_html += "   </td>";
					_html += "</tr>";
				}
			});
			_html += "</table>";
		}
		if (!_bDirty) { _html = ""; }
		if (!_total) { _html = ""; }
		return _html;
	},
	onBuildItemsMORA: function (data, _importe_forzado = 0) {
		var _html = "";
		var _bDirty = false;
		var _total = false;
		var _rec = null;
		if (data.data.mora != null && data.data.mora.length > 0) {
			_html += "<h5>Mora - " + data.data.mora[0].Nombre + "</h5>";
			_html += "<table style='font-size:14px;'>";
			$.each(data.data.mora, function (i, item) {
				if (item.Identificacion != "") {
					_bDirty = true;
					if (item.Total != null) { _total = (parseFloat(item.Total) > 0); }
					var _data = { "Tipo": item.Tipo, "Identificacion": item.Identificacion, "Importe": item.Total };
					_rec = _TOOLS.utf8_to_b64(JSON.stringify(_data));
					_html += "<tr>";
					_html += "   <td style='width:75%;'>" + item["Descripcion"] + " #" + item.Identificacion + "</td>";
					_html += "   <td style='width:2%;' align='left'></td>";
					_html += "   <td style='width:100px;' align='right'>" + _TOOLS.formatMoney(item.Total, 2) + "</td>";
					_html += "   <td align='center' style='width:15%;'><input data-sort='-999' data-reset='.chkMoraTot' type='checkbox' class='chkPay chkMoraMin chkMora' id='chkPay' name='chkPay' value='" + item.Total + "' data-record='" + _rec + "'/></td>";
					_html += "</tr>";

//					_html += "<tr>";
//					_html += "   <td>" + item["Descripcion"] + " #" + item.Identificacion + "</td>";
//					_html += "   <td align='right' style='padding-left:15px;'>";
//					_html += "      $ <input style='display:inline;width:100px;text-align:right;-webkit-appearance:none;margin:0;' min='" + parseInt(item.Minimo) + "' max='" + parseInt(item.Total) + "' type='number' id='moraImporte' name='moraImporte' class='form-control moraImporte' value='" + parseInt(item.Total) + "' data-record='" + _rec + "' />"
//					_html += "   </td>";
//					_html += "</tr>";
				}
			});
			_html += "</table>";
		}
		if (!_bDirty) { _html = ""; }
		if (!_total) { _html = ""; }
		return _html;
	},


	onBuildItemsCuotaInicialCR: function (data, _importe_forzado = 0) {
		if (_importe_forzado == "") { _importe_forzado = 0; }
		var _html = "";
		var _bDirty = false;
		var _total = false;
		if (data.data.cuota_inicial_cr != null && data.data.cuota_inicial_cr.length > 0) {
			_html += "<h5>Cuota inicial Mediya - " + data.data.cuota_inicial_cr[0].Nombre + "</h5>";
			_html += "<table style='width:100%;font-size:14px;'>";
			_html += "<tr style='font-weight:bold;'>";
			_html += "   <td align='center'>Concepto</td>";
			_html += "   <td align='right'>Total</td>";
			_html += "   <td></td>";
			_html += "</tr>";
			$.each(data.data.cuota_inicial_cr, function (i, item) {
				if (item.Identificacion != "") {
					if (parseFloat(_importe_forzado) != 0) { item.Total = parseFloat(_importe_forzado); }
					if (parseFloat(_importe_forzado) != 0 && i != 0) { return true; }
					_bDirty = true;
					if (item.Total != null) { _total = (parseFloat(item.Total) > 0); }
					var _data = { "Tipo": item.Tipo, "Identificacion": item.Identificacion, "Importe": item.Total };
					var _rec = _TOOLS.utf8_to_b64(JSON.stringify(_data));
					_html += "<tr>";
					_html += "   <td align='center'>" + item.Descripcion + "</td>";
					_html += "   <td align='right'>" + _TOOLS.formatMoney(item.Total, 2) + "</td>";
					//_html += "   <td align='center' style='width:15%;'><input data-sort='" + i + "' data-reset='' type='checkbox' class='chkPay chkCre' id='chkPay' name='chkPay' value='" + item.Total + "' data-record='" + _rec + "'/></td>";
					_html += "   <td align='center' style='width:15%;'><input data-sort='9999' data-reset='' type='checkbox' class='chkPay chkCre' id='chkPay' name='chkPay' value='" + item.Total + "' data-record='" + _rec + "'/></td>";
					_html += "</tr>";
				}
			});
			_html += "</table>";
		}
		if (!_bDirty) { _html = ""; }
		if (!_total) { _html = ""; }
		return _html;
	},
	onBuildItemsCuotaCRDO: function (data, _importe_forzado = 0) {
		var _html = "";
		var _bDirty = false;
		var _total = false;
		if (data.data.cuota_inicial_crdo != null && data.data.cuota_inicial_crdo.length > 0) {
			_html += "<h5>Mediya - " + data.data.cuota_inicial_crdo[0].Nombre + "</h5>";
			_html += "<table style='width:100%;font-size:14px;'>";
			_html += "<tr style='font-weight:bold;'>";
			_html += "   <td align='center'>Concepto</td>";
			_html += "   <td align='right'>Total</td>";
			_html += "   <td></td>";
			_html += "</tr>";
			$.each(data.data.cuota_inicial_crdo, function (i, item) {
				if (item.Identificacion != "") {
					_bDirty = true;
					if (item.Total != null) { _total = (parseFloat(item.Total) > 0); }
					var _data = { "Tipo": item.Tipo, "Identificacion": item.Identificacion, "Importe": item.Total };
					var _rec = _TOOLS.utf8_to_b64(JSON.stringify(_data));
					_html += "<tr>";
					_html += "   <td align='center'>" + item.Descripcion + "</td>";
					_html += "   <td align='right'>" + _TOOLS.formatMoney(item.Total, 2) + "</td>";
					//_html += "   <td align='center' style='width:15%;'><input data-sort='" + i + "' data-reset='' type='checkbox' class='chkPay chkCre' id='chkPay' name='chkPay' value='" + item.Total + "' data-record='" + _rec + "'/></td>";
					_html += "   <td align='center' style='width:15%;'><input data-sort='9999' data-reset='' type='checkbox' class='chkPay chkCre' id='chkPay' name='chkPay' value='" + item.Total + "' data-record='" + _rec + "'/></td>";
					_html += "</tr>";
				}
			});
			_html += "</table>";
		}
		if (!_bDirty) { _html = ""; }
		if (!_total) { _html = ""; }
		return _html;
	},
	onWindowComprobante: function (response, _fulldata, _raw_request) {
		var _identificaciones = "";
		var _html = "<div style='max-width:540px;width:100%;font-family:arial;border:solid 2px black;padding:5px;' class='data-pdf'>";
		_html += "<input type='hidden' id='code' name='code' value='" + _fulldata.dni + "' class='code dbaseComprobante'/>";
		_html += "<input type='hidden' id='description' name='description' value='comprobanteCOIN' class='description dbaseComprobante'/>";
		_html += "<input type='hidden' id='base64' name='base64' value='' class='base64 dbaseComprobante'/>";
		_html += "<input type='hidden' id='filename' name='filename' value='Comprobante de pago " + _TOOLS.UUID() + ".pdf' class='filename dbaseComprobante'/>";
		_html += "<input type='hidden' id='extension' name='extension' value='pdf' class='extension dbaseComprobante'/>";
		_html += "      <table style='width:100%;font-family:calibri;padding:5px;'>";
		_html += "         <tr>";
		switch (_raw_request[0].Tipo) {
			case "CRDO":
			case "CICR":
				_html += "<td align='center' valign='middle'>";
				_html += "   <img src='https://intranet.credipaz.com/assets/credipaz/img/mediya.png' style='width:75px;'/>";
				_html += "</td>";
				break;
			default:
				_html += "<td align='center' valign='middle' style='border:solid 1px black;background-color:rgb(230,0,150);'>";
				_html += "   <span style='font-weight:bold;font-size:40px;color:yellow;'>CREDIPAZ</span>";
				_html += "</td>";
				break;
		}
		_html += "         </tr>";
		_html += "         <tr>";
		_html += "            <td align='center' valign='middle' style='border-bottom:solid 1px silver;'>";
		_html += "               <span style='font-weight:bold;font-size:24px;'>Comprobante de pago</span>";
		_html += "            </td>";
		_html += "         </tr>";
		for (_item of _raw_request) {
			switch (_item.Tipo) {
				case "TAR":
					_html += "         <tr>";
					_html += "            <td align='center' valign='middle' style='font-size:24px;'>TARJETA CABAL CREDIPAZ</td>";
					_html += "         </tr>";
					break;
				case "CRE":
					_html += "         <tr>";
					_html += "            <td align='center' valign='middle' style='font-size:24px;'>CRÉDITO</td>";
					_html += "         </tr>";
					break;
				case "CICR":
					_html += "         <tr>";
					_html += "            <td align='center' valign='middle' style='font-size:24px;'>MEDIYA Cuota Anticipada</td>";
					_html += "         </tr>";
					break;
				case "CRDO":
					_html += "         <tr>";
					_html += "            <td align='center' valign='middle' style='font-size:24px;'>MEDIYA Cuota</td>";
					_html += "         </tr>";
					break;
				case "ACU":
					_html += "         <tr>";
					_html += "            <td align='center' valign='middle' style='font-size:24px;'>ACUERDO DE PAGO</td>";
					_html += "         </tr>";
					break;
			}
			_html += "         <tr>";
			_html += "            <td align='center' valign='middle' style='font-weight:bold;font-size:24px;'>$ " + _item.Importe + "</td>";
			_html += "            <td align='center' valign='middle' style='font-weight:bold;font-size:12px;'>(Importe sujeto a confirmación de cobro)</td>";
			_html += "         </tr>";
			if (_identificaciones != "") { _identificaciones += ", " }
			_identificaciones += _item.Identificacion;
		}

		_html += "         <tr>";
		_html += "            <td align='center' valign='middle'>";
		_html += "               <table align='center' style='width:80%;padding:5px;' cellspacing='0'>";
		_html += "                  <tr>";
		_html += "                     <td align='left' valign='top' style='border-top:solid 1px black;'>Identificación</td>";
		_html += "                     <td align='right' valign='top' style='border-top:solid 1px black;'>" + _identificaciones + "</td>";
		_html += "                  </tr>";
		_html += "                  <tr>";
		_html += "                     <td align='left' valign='top' style='border-top:solid 1px black;'>Medio de pago</td>";
		_html += "                     <td align='right' valign='top' style='border-top:solid 1px black;'>" + _fulldata.MedioPago + "</td>";
		_html += "                  </tr>";
		_html += "                  <tr>";
		_html += "                     <td align='left' valign='top' style='border-top:solid 1px black;'>Fecha de pago</td>";
		_html += "                     <td align='right' valign='top' style='border-top:solid 1px black;'>" + response.now + "</td>";
		_html += "                  </tr>";
		_html += "                  <tr>";
		_html += "                     <td align='left' valign='top' style='border-top:solid 1px black;border-bottom:solid 1px black;'>Número de pago</td>";
		_html += "                     <td align='right' valign='top' style='border-top:solid 1px black;border-bottom:solid 1px black;'>" + response.apiReference + "</td>";
		_html += "                  </tr>";
		_html += "               </table>";
		_html += "            </td>";
		_html += "         </tr>";
		_html += "      </table>";

		//_html += "      <table align='center' style='width:80%;font-family:calibri;padding:5px;'>";
		//_html += "         <tr><td align='center' valign='middle'><b>CREDIPAZ S.A.</b></td></tr>";
		//_html += "         <tr><td align='center' valign='middle' style='border-top:solid 1px black;border-bottom:solid 1px black;'>Av.Pte.Perón 10175, Villa Gbor.Udaondo</br>Ituzaingó, Buenos Aires</td></tr>";
		//_html += "         <tr><td align='center' valign='middle' style='border-top:solid 1px black;border-bottom:solid 1px black;'>CUIT 30-54457180-6<br/>IVA Resp.inscripto a consumidor final</td></tr>";
		//_html += "         <tr><td align='center' valign='middle'>Orientación al consumidor Prov. de Bs.As.<br/>0800-222-9042</td></tr>";
		//_html += "      </table>";
		_html += "   </div>";
		_html += "   <table align='center' style='width:100%;'>";
		_html += "      <tr>";
		_html += "         <td align='center' style='border-bottom:solid 1px grey;padding:5px;'>";
		_html += "            <a href='#' class='d-none btn btn-md btn-raised btn-success btnGetBase64'>Descargar PDF</a>";
		_html += "         </td>";
		_html += "      </tr>";
		_html += "   </table>";
		_FUNCTIONS.onShowInfoPDF(_html, "<b style='color:darkgreen;'>Pago procesado en forma exitosa</b>");
		$(".base64").val(_TOOLS.utf8_to_b64($(".data-pdf").html()));
		var _url = (_AJAX.server + "downloadBase64File/" + $(".code").val() + "/" + $(".description").val());
		$(".btnGetBase64").attr("href", _url);
		var _json = _TOOLS.getFormValues(".dbaseComprobante", null);
		_json["module"] = "mod_backend";
		_json["table"] = "Files_base64";
		_json["model"] = "Files_base64";
		_AJAX.UiSave(_json).then(function (data) {
			$(".btnGetBase64").removeClass("d-none");
		});
	},
	onMedicalAssignDoctor: function (_this) {
		_FUNCTIONS._scrollY = window.scrollY;
		var _id = _this.attr("data-id");
		var _id_user_asigned = _this.attr("data-id-user");
		var _json = { "id": _id, "id_user_asigned": _id_user_asigned };
		_AJAX.UiFollowAssignDoctor(_json).then(function (data) {
			if (data.status == "OK") {
				$(".btn-cancel-note").click();
				_FUNCTIONS.onRefreshBrowser();
				setTimeout(function () { window.scrollTo(0, _FUNCTIONS._scrollY); }, 250);
			}
		});
	},
	onMedicalAddOccurs: function (_this) {
		_FUNCTIONS._scrollY = window.scrollY;
		var _id = _this.attr("data-id");
		var _json = { "id": _id };
		_AJAX.UiFollowAddOccurs(_json).then(function (data) {
			if (data.status == "OK") {
				$(".btn-cancel-note").click();
				_FUNCTIONS.onRefreshBrowser();
				setTimeout(function () { window.scrollTo(0, _FUNCTIONS._scrollY); }, 250);
			}
		});
	},
	onMedicalNotes: function (_this) {
		var _id = _this.attr("data-id");
		var _html = "";
		_html += "<table class='table table-sm' style='width:100%;'>";
		_html += "   <tr>";
		_html += "      <td><label><b>Notas</b></label><textarea rows='5' class='form-control medical_notes validate-note' id='medical_notes' name='medical_notes'></textarea></td>";
		_html += "   </tr>";
		_html += "</table>";
		_html += "<div class='panel-footer'>";
		_html += " <div class='row mt-3'>";
		_html += "  <div class='col-6'>";
		_html += "   <a class='btn-cancel-note btn btn-danger btn-raised btn-md'>Cancelar</a>";
		_html += "  </div>";
		_html += "  <div class='col-6'>";
		_html += "   <a class='btn-success-note btn btn-success btn-raised btn-md'>Aceptar</a>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";
		_FUNCTIONS.onShowHtmlModal("Anotaciones médicas", _html, function () {
			var _record = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-record")));
			$('#medical_notes').val(_record.medical_notes);
			$("body").off("click", ".btn-success-note").on("click", ".btn-success-note", function () {
				if (!_TOOLS.validate(".validate-note", false)) {
					_FUNCTIONS.onShowAlert("Complete los datos requeridos", "Datos faltantes");
					return false;
				}
				var _json = { "id": _id, "medical_notes": $("#medical_notes").val() };
				_AJAX.UiFollowChangeMedicalNote(_json).then(function (data) {
					if (data.status == "OK") {
						$(".btn-cancel-note").click();
						_FUNCTIONS.onRefreshBrowser();
					}
				});
			});
			$("body").off("click", ".btn-cancel-note").on("click", ".btn-cancel-note", function () {
				$("#modal-html").modal("hide").data("bs.modal", null);
				_FUNCTIONS.onDestroyModal("#modal-html");
			});
		});
	},
	onEnviosDetails: function (_this) {
		var _id = _this.attr("data-id");
		var _html = "";
		_html += "<table class='table table-sm' style='width:100%;'>";
		_html += "   <tr style='background-color:silver;font-weight:bold;'>";
		_html += "      <td>Formulario</td>";
		_html += "      <td>Al paciente</td>";
		_html += "      <td>A la ART</td>";
		_html += "   </tr>";

		_html += "   <tr>";
		_html += "      <td>PMI</td>";
		_html += "      <td class='td_PMI_paciente'><input type='checkbox' id='PMI_paciente' name='PMI_paciente' class='PMI_paciente checkbox dbaseSends' value='1'/></td>";
		_html += "      <td class='td_PMI_ART'><input type='checkbox' id='PMI_ART' name='PMI_ART' class='PMI_ART checkbox dbaseSends' value='1'/></td>";
		_html += "   </tr>";
		_html += "   <tr>";
		_html += "      <td>FI</td>";
		_html += "      <td class='td_FI_paciente'><input type='checkbox' id='FI_paciente' name='FI_paciente' class='FI_paciente checkbox dbaseSends' value='1'/></td>";
		_html += "      <td class='td_FI_ART'><input type='checkbox' id='FI_ART' name='FI_ART' class='FI_ART checkbox dbaseSends' value='1'/></td>";
		_html += "   </tr>";
		_html += "   <tr>";
		_html += "      <td>PME</td>";
		_html += "      <td class='td_PME_paciente'><input type='checkbox' id='PME_paciente' name='PME_paciente' class='PME_paciente checkbox dbaseSends' value='1'/></td>";
		_html += "      <td class='td_PME_ART'><input type='checkbox' id='PME_ART' name='PME_ART' class='PME_ART checkbox dbaseSends' value='1'/></td>";
		_html += "   </tr>";
		_html += "   <tr>";
		_html += "      <td>FA</td>";
		_html += "      <td class='td_FA_paciente'><input type='checkbox' id='FA_paciente' name='FA_paciente' class='FA_paciente checkbox dbaseSends' value='1'/></td>";
		_html += "      <td class='td_FA_ART'><input type='checkbox' id='FA_ART' name='FA_ART' class='FA_ART checkbox dbaseSends' value='1'/></td>";
		_html += "   </tr>";
		_html += "</table>";

		_html += "<div class='panel-footer'>";
		_html += " <div class='row mt-3'>";
		_html += "  <div class='col-6'>";
		_html += "   <a class='btn-cancel-envios btn btn-danger btn-raised btn-md'>Cancelar</a>";
		_html += "  </div>";
		_html += "  <div class='col-6'>";
		_html += "   <a data-id='" + _id + "' class='btn-success-envios btn btn-success btn-raised btn-md'>Aceptar</a>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";
		_FUNCTIONS.onShowHtmlModal("Detalles de los envíos", _html, function () {
			_AJAX.UiFollowStatusSends({ "id": _id }).then(function (data) {
				if (data.status == "OK") {
					$.each(data.data, function (i, item) {
						$(".td_" + item.code).html("<span style='font-size:10px;'>Enviado<br/>" + item.created + "</span>");
					});
					if (parseInt(data.id_type_status) != 4) {
						var _nono = "No generado";
						$(".td_PME_paciente").html(_nono);
						$(".td_PME_ART").html(_nono);
						$(".td_FA_paciente").html(_nono);
						$(".td_FA_ART").html(_nono);
					}
				}
			});
			$("body").off("click", ".btn-success-envios").on("click", ".btn-success-envios", function () {
				var _json = {};
				_json["id"] = $(this).attr("data-id");
				if ($(".PMI_paciente").val() != undefined && $(".PMI_paciente").prop("checked")) { _json["PMI_paciente"] = 1 } else { _json["PMI_paciente"] = 0 };
				if ($(".FI_paciente").val() != undefined && $(".FI_paciente").prop("checked")) { _json["FI_paciente"] = 1 } else { _json["FI_paciente"] = 0 };
				if ($(".PME_paciente").val() != undefined && $(".PME_paciente").prop("checked")) { _json["PME_paciente"] = 1 } else { _json["PME_paciente"] = 0 };
				if ($(".FA_paciente").val() != undefined && $(".FA_paciente").prop("checked")) { _json["FA_paciente"] = 1 } else { _json["FA_paciente"] = 0 };
				if ($(".PMI_ART").val() != undefined && $(".PMI_ART").prop("checked")) { _json["PMI_ART"] = 1 } else { _json["PMI_ART"] = 0 };
				if ($(".FI_ART").val() != undefined && $(".FI_ART").prop("checked")) { _json["FI_ART"] = 1 } else { _json["FI_ART"] = 0 };
				if ($(".PME_ART").val() != undefined && $(".PME_ART").prop("checked")) { _json["PME_ART"] = 1 } else { _json["PME_ART"] = 0 };
				if ($(".FA_ART").val() != undefined && $(".FA_ART").prop("checked")) { _json["FA_ART"] = 1 } else { _json["FA_ART"] = 0 };
				_AJAX.UiFollowRegisterSends(_json).then(function (data) {
					if (data.status == "OK") {
						$(".btn-cancel-envios").click();
						_FUNCTIONS.onRefreshBrowser();
					}
				});
			});
			$("body").off("click", ".btn-cancel-envios").on("click", ".btn-cancel-envios", function () {
				$("#modal-html").modal("hide").data("bs.modal", null);
				_FUNCTIONS.onDestroyModal("#modal-html");
			});
		});

	},
	onConfigUserPreferences: function (_this) {
		var _html = "";
		_html += "<label>Valores especificados por el usuario</label>";
		_html += "<table cellpadding='2'>";
		_html += "   <tr>";
		_html += "      <td>Filas por página</td>";
		_html += "      <td><input value='25' class='preference-1 validate-values gridrows form-control' type='number' name='gridrows' id='gridrows' data-clear-btn='false' placeholder='Ingrese cantidad de filas' /></td>";
		_html += "   </tr>";
		_html += "   <tr>";
		_html += "      <td>Firma médica</td>";
		_html += "      <td>";
		_html += "      <select id='doctorsign' name='doctorsign' class='preference-2 doctorsign form-control'>";
		_html += "         <option value=''>Agregar firma médica a impresiones</option>";
		_html += "         <option value='1'>No agregar firma médica a impresiones</option>";
		_html += "      </select>";
		_html += "      </td>";
		_html += "   </tr>";
		_html += "</table>";
		_html += "<label>Detalle de audio, video y permisos</label>";
		_html += "<table cellpadding='2' width='100%'>";
		_html += "   <tr>";
		_html += "      <td>Cámara</td>";
		if (_MEDIA.hasWebcam) { _html += "<td><b style='color:darkgreen;'>Existe</b></td>"; } else { _html += "<td><b style='color:darkred;'>Sin cámara</b></td>"; }
		if (_MEDIA.isWebcamAlreadyCaptured) { _html += "<td><b style='color:darkgreen;'>Habilitada</b></td>"; } else { _html += "<td><b style='color:darkred;'>No habilitada</b></td>"; }
		_html += "      <td><b style='color:blue;'>" + _MEDIA.permissionWebcam + "</b></td>";
		_html += "   </tr>";
		_html += "   <tr>";
		_html += "      <td>Micrófono</td>";
		if (_MEDIA.hasMicrophone) { _html += "<td><b style='color:darkgreen;'>Existe</b></td>"; } else { _html += "<td><b style='color:darkred;'>Sin micrófono</b></td>"; }
		if (_MEDIA.isMicrophoneAlreadyCaptured) { _html += "<td><b style='color:darkgreen;'>Habilitado</b></td>"; } else { _html += "<td><b style='color:darkred;'>No habilitado</b></td>"; }
		_html += "      <td><b style='color:blue;'>" + _MEDIA.permissionMicrophone + "</b></td>";
		_html += "   </tr>";
		_html += "</table>";
		_html += "<div class='panel-footer'>";
		_html += " <div class='row mt-3'>";
		_html += "  <div class='col-6'>";
		_html += "   <a class='btn-cancel-value btn btn-danger btn-raised btn-md'>Cancelar</a>";
		_html += "  </div>";
		_html += "  <div class='col-6'>";
		_html += "   <a class='btn-success-value btn btn-success btn-raised btn-md'>Aceptar</a>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";
		_FUNCTIONS.onShowHtmlModal("Preferencias del usuario", _html, function () {
			var _data = { "module": "mod_backend", "table": "Rel_users_preferences", "model": "Rel_users_preferences", "page": -1, "pagesize": -1, "where": ("id_user=" + _AJAX._id_user_active) };
			_AJAX.UiGet(_data).then(function (datajson) {
				$.each(datajson.data, function (i, item) {
					$('.' + 'preference-' + item.id_preference).val(item.value);
				});
			});
			$("body").off("click", ".btn-success-value").on("click", ".btn-success-value", function () {
				if (!_TOOLS.validate(".validate-values", false)) {
					_FUNCTIONS.onShowAlert("Complete los datos requeridos", "Datos faltantes");
					return false;
				}
				var _json = {
					"gridrows": $("#gridrows").val(),
					"doctorsign": $("#doctorsign").val(),
				};
				_json["module"] = "mod_backend";
				_json["table"] = "Rel_users_preferences";
				_json["model"] = "Rel_users_preferences";
				_AJAX.UiSave(_json).then(function (data) {
					if (data.status == "OK") {
						$(".btn-cancel-value").click();
						_FUNCTIONS.onRefreshBrowser();
					}
				});
			});
			$("body").off("click", ".btn-cancel-value").on("click", ".btn-cancel-value", function () {
				$("#modal-html").modal("hide").data("bs.modal", null);
				_FUNCTIONS.onDestroyModal("#modal-html");
			});
		});
	},
	onFollowAccept: function (_this) {
		_FUNCTIONS._scrollY = window.scrollY;
		if (_TOOLS.validate(".validateQuestion", true)) {
			var _json = _TOOLS.getFormValues(".dbquestion", null);
			_json["module"] = "mod_follow";
			_json["table"] = "Rel_sinisters_questions";
			_json["model"] = "Rel_sinisters_questions";
			_AJAX.UiSave(_json).then(function (data) {
				if (data.status == "OK") {
					$(".btn-abm-cancel").click();
					setTimeout(function () { window.scrollTo(0, _FUNCTIONS._scrollY); }, 250);
				}
			});
		}
	},
	onDischargeAccept: function (_this) {
		if (_TOOLS.validate(".validateDischarge", true)) {
			var _json = _TOOLS.getFormValues(".dbaseDischarge", null);
			_json["id_sinister"] = _this.attr("data-id-sinister");
			_json["module"] = "mod_follow";
			_json["table"] = "Discharges";
			_json["model"] = "Discharges";
			_AJAX.UiSave(_json).then(function (data) {
				if (data.status == "OK") {
					window.scrollTo(0, 0);
					$(".btn-abm-cancel").click();
				}
			});
		}
	},
	onValidateModuleSinister: function (_this) {
		clearTimeout(_FUNCTIONS._TIMER_LAZY);
		var _id_type_art = $("#id_type_art").val();
		var _module_sinister = $("#module_sinister").val();
		var _version = $("#version").val();
		if (_version == '') { _version = 0; }
		if (_id_type_art != -1 && _module_sinister != "") {
			_FUNCTIONS._TIMER_LAZY = setTimeout(function () {
				var _data = { "module": "mod_follow", "table": "Sinisters", "model": "Sinisters", "page": -1, "pagesize": -1, "where": ("id_type_art=" + _id_type_art + " AND version='" + _version + "' AND module_sinister='" + _module_sinister + "'") };
				_AJAX.UiGet(_data).then(function (datajson) {
					$.each(datajson.data, function (i, item) {
						_FUNCTIONS.onShowAlert("Ya existe un registro con este número de siniestro para la ART seleccionada.", "Control de numeración");
						//$("#module_sinister").val("");
					});
				});
			}, 500);
		}
	},
	onPassToReview: function (_this) {
		if (_this.prop("checked")) {
			$(".id_type_priority").val(5);
			$(".area-priority").removeClass("d-none");
		} else {
			$(".id_type_priority").val(6);
			$(".area-priority").addClass("d-none");
		}
	},
	onTestPush: function (_this) {
		if ($(".dni").val() == "") {
			_FUNCTIONS.onShowAlert("Debe poner un DNI de usuario válido para la prueba de envío", "Datos faltantes");
			$(".testing").val("0");
			return false;
		}
		$(".testing").val("1");
		$(".btn-abm-accept").click();
		setTimeout(function () { $(".testing").val("0"); }, 2000);
	},
	onSearchBeneficios: function (_this) {
		var _search = _this.val();
		var _target = _this.attr("data-target");
		clearTimeout(_FUNCTIONS._TIMER_LAZY);
		_FUNCTIONS._TIMER_LAZY = setTimeout(function () {
			var _json = {
				"title_categoria": "...búsqueda personalizada",
				"img_categoria": "img/lupa-inverse.png",
				"code_categoria": "",
				"type_categoria": "",
				"mode_categoria": "buscar",
				"description_categoria": "",
				"class_categoria": "back-buscar",
				"search": _search,
				"coords": "",
				"near": "0",
				"parent": "0",
				"is_sub": false,
			};
			_AJAX.UiGetCupons(_json).then(function (data) {
				var _last = "";
				var _html = "<table class='table table-sm table-hover'>";
				$.each(data.message.data, function (i, item) {
					if (_last != item.description) {
						_last = item.description;
						var _rec = _TOOLS.utf8_to_b64(JSON.stringify(item));
						_html += ("<tr><td class='pick-beneficio' data-rec='" + _rec + "'>" + item.description + "</td></tr>");
					}
				});
				_html += "</ul>";
				$(_target).html(_html);
			});
		}, 500);

	},
	onIntegrity: function (_this) {
		var _id = _this.attr("data-id");
		_AJAX.UiIntegrity({ "id_transfer": _id }).then(function (data) {
			if (data.status == "OK") {
				if (data.integrity == 1) {
					_FUNCTIONS.onShowInfo(data.message, "Información");
				} else {
					_FUNCTIONS.onShowInfo(data.message, "Alerta");
				}
				_FUNCTIONS.onRefreshBrowser();
			}
		});
	},
	onReverify: function (_this) {
		var _id = _this.attr("data-id");
		var _html = "";
		_html += "<label>Base64 del archivo a verificar</label>";
		_html += "<textarea rows='15' id='raw_data' name='raw_data' class='validate-data form-control raw_data shadow' style='width:100%;'></textarea>";
		_html += "<label>Firma digital del archivo a verificar</label>";
		_html += "<textarea rows='5' id='sign' name='sign' class='validate-data sign form-control shadow' style='width:100%;'></textarea>";
		_html += "<hr/>";
		_html += "<div class='panel-footer'>";
		_html += " <div class='row'>";
		_html += "  <div class='col-6'>";
		_html += "   <a class='btn-cancel-data btn btn-danger btn-raised btn-md'>Cancelar</a>";
		_html += "  </div>";
		_html += "  <div class='col-6'>";
		_html += "   <a class='btn-success-data btn btn-success btn-raised btn-md float-right'>Aceptar</a>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";
		_FUNCTIONS.onShowHtmlModal("Ingresar datos del archivo a verificar", _html, function () {
			$("body").off("click", ".btn-success-data").on("click", ".btn-success-data", function () {
				if (!_TOOLS.validate(".validate-data", false)) {
					_FUNCTIONS.onShowAlert("Complete los datos requeridos", "Datos faltantes");
					return false;
				}
				_AJAX.UiReverify({ "id_transfer": _id, "raw_data": $(".raw_data").val(), "sign": $(".sign").val() }).then(function (data) {
					if (data.status == "OK") {
						$("#modal-html").modal("hide").data("bs.modal", null);
						_FUNCTIONS.onDestroyModal("#modal-html");
						_FUNCTIONS.onShowInfo(data.message, "Verificación");
					}
				});

			});
			$("body").off("click", ".btn-cancel-data").on("click", ".btn-cancel-data", function () {
				$("#modal-html").modal("hide").data("bs.modal", null);
				_FUNCTIONS.onDestroyModal("#modal-html");
			});
		});


	},
	onSeeObject: function (_this) {
		var _body = "<img src='" + $(_this.attr("data-obj")).attr("src") + "' style='width:100%;'/>";
		_FUNCTIONS.onInfoModal({ "title": "", "body": _body, "close": true, "size": "modal-lg", "center": true });
	},
	onAbmAcceptSpecial: function (_this) {
		if (_TOOLS.validate(".validate", true)) {
			_AJAX._waiter = true;
			_AJAX.onBeforeSendExecute();
			var _json = _TOOLS.getFormValues(".dbase", null);
			_json["module"] = _this.attr("data-module");
			_json["table"] = _this.attr("data-table");
			_json["model"] = _this.attr("data-model");
			_AJAX.UiSaveSpecial(_json).then(function (data) {
				if (data.status == "OK") {
					$(".abm").addClass("d-none").hide();
					$(".browser").removeClass("d-none").show();
					_FUNCTIONS.onAlert({ "message": "Se ha grabado el registro", "class": "alert-success" });
					_FUNCTIONS.onRefreshBrowser();
				} else {
					throw data;
				}
			});
		}
	},
	onLoadImageOnboarding: function (_field, _id) {
		$(".img-" + _field).attr("src", "https://intranet.credipaz.com/assets/img/wait.gif");
		var _json = { "module": "mod_onboarding", "table": "requests_core", "model": "requests_core", "id": _id, "image": _field };
		_AJAX.UiGetFieldById(_json).then(function (data) {
			var _img = data.data[0][_field];
			if (_img == "") { _img = "./assets/img/image-upload.png"; }
			$(".img-" + _field).attr("src", _img);
		});
	},
	onSendShortcutWS: function (_this) {
		var _id = _this.attr("data-id");
		var _id_shortcut = parseInt(_this.attr("data-shortcut"));
		var _monopage = _TOOLS.b64_to_utf8(_this.attr("data-monopage"));
		var _text = _TOOLS.b64_to_utf8(_this.attr("data-text"));
		var _record = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-record")));
		var _forced_amount = 0;
		var _msg = "No puede utilizar esta función en un registro con más de 4 días de antigüedad.";
		var _json = { "id": _id, "idPlan": _record.idplan };
		_AJAX.UiCheckPlan(_json).then(function (datajson) {
			switch (_id_shortcut) {
				case 3: //firma electrónica
					if (!confirm("Esta acción marcará como verificada la solicitud. ¿Confirma?")) {
						return false;
					} else {
						if (parseInt(_record.days) > 4) {
							_FUNCTIONS.onShowInfo(_msg, "Información");
							return false;
						}
					}
					break;
				case 4: //contraoferta
					_forced_amount = window.prompt("Ingrese monto a proponer al cliente:");
					if (_forced_amount == null) { return false; }
					if (isNaN(_forced_amount)) {
						_FUNCTIONS.onShowInfo("No has ingresado un valor numérico", "Información");
						return false;
					}
					break;
				default: // the remaining shortcuts must control caducity days
					if (parseInt(_record.days) > 4) {
						_FUNCTIONS.onShowInfo(_msg, "Información");
						return false;
					}
					break;
			}
			_text = _text.replace('[NAME]', _record.NomApe);
			var _link = ("https://onboarding.credipaz.com?verificated=" + _id + "&monopage=" + _monopage + "&forced_amount=" + _forced_amount);
			if (_record.id_user != null && _record.id_user != 0 && _record.id_user != 237967) {
				_link = ("https://totem.credipaz.com?verificated=" + _id + "&monopage=" + _monopage + "&forced_amount=" + _forced_amount);
			}
			_text = _text.replace('[LINK]', _link);
			_record.alt_description = ("+549" + _record.alt_description);
			var _link = ("https://wa.me/" + _record.alt_description + "?text=" + encodeURIComponent(_text));
			window.open(_link, '_blank');
		}).catch(function (error) {
			alert(error.message);
		});
	},
	onCreateVideoHost: function (_creator, _close_url) {
		return new Promise(
			function (resolve, reject) {
				try {
					_NEOVIDEO._username = "credipaz";
					_NEOVIDEO._password = "08.!Rcp#@80";
					_NEOVIDEO.onCreateNewVideoRoom(null).then(function (data) {
						var _params = {
							"server": _NEOVIDEO._SERVER,
							"id_application": _NEOVIDEO._id_application,
							"username": _NEOVIDEO._username,
							"password": _NEOVIDEO._password,
							"id": data.id_transaction,
							"name": "Usuario",
							"jwt": data.token,
							"creator": _creator,
							"close_url": _close_url
						}
						resolve(_params);
					}).catch(function (err) {
						_FUNCTIONS.onShowAlert(err.message, "Alerta");
						reject(rex);
					});

				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onSeeComprobante: function (_this) {
		try {
			_FUNCTIONS.onDestroyModal('#telemedicinaComprobante');
			var _raw_data = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-raw")));
			var _numero = "";
			var _body = "";
			var _ptoVta = _TOOLS.LPAD(_raw_data.Prefijo, '0', 5);
			var _nroCmp = _TOOLS.LPAD(_raw_data.NroComprobante, '0', 8);
			_body += "<div class='div-pdf' style='width:95%;border:solid 1px black;'>";
			_body += "   <table width='100%'>";
			_body += "      <tr>";
			_body += "         <td align='center' style='border-bottom:solid 1px black;'><b>ORIGINAL</b></td>";
			_body += "      </tr>";
			_body += "   </table>";
			_body += "   <table width='100%'>";
			_body += "      <tr>";
			_body += "         <td align='center' style='width:40%;padding-left:10px;' valign='top'><b>MEDIYA S.A.</b></td>";
			_body += "         <td align='center' style='width:20%;border:solid 1px black;font-size:16px;' valign='top'><b>B</b></br><span style='font-size:7px;'>COD.006</span></td>";
			_body += "         <td align='center' style='width:40%;padding-left:10px;' valign='top'><b>FACTURA</b></td>";
			_body += "      </tr>";
			_body += "   </table>";
			_body += "   <table width='100%'>";
			_body += "      <tr>";
			_body += "         <td align='left' style='width:50%;padding-left:5px;font-size:12px;border-bottom:solid 1px black;border-right:solid 1px black;'>";
			_body += "            <table width='100%'>";
			_body += "               <tr><td valign='top'><b>Razón Social:</b></td><td valign='top'>MEDIYA S.A</td></tr>";
			_body += "               <tr><td valign='top'><b>Domicilio Comercial:</b></td><td valign='top'>Sarmiento 552 Piso: 17 - CABA</td></tr>";
			_body += "               <tr><td valign='top'><b>Condición frente al IVA:</b></td><td valign='top'><b>IVA Responsable Inscripto</b></td></tr>";
			_body += "            </table>";
			_body += "         </td>";
			_body += "         <td align='left' style='width:50%;padding-left:5px;font-size:12px;border-bottom:solid 1px black;'>";
			_body += "            <table width='100%'>";
			_body += "               <tr>";
			_body += "                  <td valign='top'><b>Punto de Venta:</br>" + _ptoVta + "</b></td>";
			_body += "                  <td valign='top'><b>Comp.Nro.:</br>" + _nroCmp + "</b></td>";
			_body += "               </tr>";
			_body += "            </table>";
			_body += "            <table width='100%'>";
			_body += "               <tr><td valign='bottom' width='50%'><b>CUIT:</b></td><td valign='bottom'>30707768629</td></tr>";
			_body += "               <tr><td valign='bottom' width='50%'><b>Ingresos Brutos:</b></td><td valign='bottom'>901-055405-7</td></tr>";
			_body += "               <tr><td valign='bottom' width='50%'><b>Fecha de Inicio de Actividades:</b></td><td valign='bottom'>01/10/2001</td></tr>";
			_body += "            </table>";
			_body += "         </td>";
			_body += "      </tr>";
			_body += "   </table>";
			_body += "   <table width='100%' style='font-size:12px;'>";
			_body += "      <tr>";
			_body += "         <td align='left' style='padding:2px;'>";
			_body += "            <table cellpadding='3'>";
			_body += "               <tr>";
			_body += "                  <td valign='bottom'><b>Facturado Desde:</b></td>";
			_body += "                  <td valign='bottom'>" + _raw_data.Fecha + "</td>";
			_body += "                  <td valign='bottom'><b>Hasta:</b></td>";
			_body += "                  <td valign='bottom'>" + _raw_data.Fecha + "</td>";
			_body += "                  <td valign='bottom'><b>Fecha de Vto.para el pago:</b></td>";
			_body += "                  <td valign='bottom'></td>";
			_body += "               </tr>";
			_body += "            </table>";
			_body += "         </td>";
			_body += "      </tr>";
			_body += "   </table>";
			_body += "</div>";

			_body += "<div style='width:95%;margin-top:2px;border:solid 1px black;'>";
			_body += "   <table width='100%' style='font-size:12px;'>";
			_body += "      <tr>";
			_body += "         <td align='left' style='padding:2px;border-bottom:solid 1px black;'>";
			_body += "            <table cellpadding='1' width='100%'>";
			_body += "               <tr>";
			_body += "                  <td valign='bottom'><b>CUIT:</b></td>";
			_body += "                  <td valign='bottom'>" + _raw_data.NroDocumento + "</td>";
			_body += "                  <td valign='bottom'><b>Apellido y Nombre:</b></td>";
			_body += "                  <td valign='bottom'>" + _raw_data.Nombre + "</td>";
			_body += "               </tr>";
			_body += "               <tr>";
			_body += "                  <td valign='bottom'><b>Condición frente al IVA:</b></td>";
			_body += "                  <td valign='bottom'>Consumidor final</td>";
			_body += "                  <td valign='bottom'><b>Domicilio:</b></td>";
			_body += "                  <td valign='bottom'></td>";
			_body += "               </tr>";
			_body += "               <tr>";
			_body += "                  <td valign='bottom'><b>Condición de venta:</b></td>";
			_body += "                  <td valign='bottom'>Contado</td>";
			_body += "                  <td valign='top'></b></td>";
			_body += "                  <td valign='top'></td>";
			_body += "               </tr>";
			_body += "            </table>";
			_body += "         </td>";
			_body += "      </tr>";
			_body += "   </table>";
			_body += "</div>";

			_body += "<div style='width:95%;margin-top:2px;'>";
			_body += "   <table width='100%' style='font-size:11px;'>";
			_body += "      <tr style='background-color:silver;color:black;'>";
			_body += "         <td style='border:solid 1px black;'><b>Cód.</b></td>";
			_body += "         <td style='border:solid 1px black;'><b>Prd./Srv.</b></td>";
			_body += "         <td style='border:solid 1px black;'><b>Cant.</b></td>";
			_body += "         <td style='border:solid 1px black;'><b>U.med.</b></td>";
			_body += "         <td style='border:solid 1px black;'><b>P.Unit.</b></td>";
			_body += "         <td style='border:solid 1px black;'><b>% Bon.</b></td>";
			_body += "         <td style='border:solid 1px black;'><b>Imp.Bon.</b></td>";
			_body += "         <td style='border:solid 1px black;'><b>Subtotal</b></td>";
			_body += "      </tr>";
			_body += "      <tr>";
			_body += "         <td>" + _raw_data.Identificacion + "</td>";
			_body += "         <td>" + _raw_data.Concepto + "</td>";
			_body += "         <td>1</td>";
			_body += "         <td></td>";
			_body += "         <td>" + _raw_data.Importe + "</td>";
			_body += "         <td>0</td>";
			_body += "         <td>0</td>";
			_body += "         <td>" + _raw_data.Importe + "</td>";
			_body += "      </tr>";
			_body += "   </table>";
			_body += "</div>";

			_body += "<div style='width:95%;border:solid 1px black;margin-top:5px;'>";
			_body += "   <table style='font-size:13px;font-weight:bold;width:100%;'>";
			_body += "      <tr>";
			_body += "         <td align='right' style='width:80%;'>Subtotal: $</td>";
			_body += "         <td align='right'>" + _raw_data.Importe + "</td>";
			_body += "      </tr>";
			_body += "      <tr>";
			_body += "         <td align='right' style='width:80%;'>Importe Otros Tributos: $</td>";
			_body += "         <td align='right'>0</td>";
			_body += "      </tr>";
			_body += "      <tr>";
			_body += "         <td align='right' style='width:80%;'>Importe Total: $</td>";
			_body += "         <td align='right'>" + _raw_data.Importe + "</td>";
			_body += "      </tr>";
			_body += "   </table>";
			_body += "</div>";

			_body += "<div style='width:95%;margin-top:5px;'>";
			_body += "   <table width='100%' style='font-size:13px;'>";
			_body += "      <tr>";
			_body += "         <td><b>CAE Nº: </b></td>";
			_body += "         <td>" + _raw_data.CAE + "</td>";
			_body += "         <td><b>Fecha de Vto. de CAE: </b></td>";
			_body += "         <td>" + _raw_data.VtoCAE + "</td>";
			_body += "      </tr>";
			_body += "   </table>";
			_body += "   <table width='100%' style='font-size:12px;'>";
			_body += "      <tr>";
			_body += "         <td width='40%'>";
			_body += "            <img style='100%' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAYAAAAehFoBAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF8WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDI0LTA0LTI5VDE4OjAwOjM2LTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyNC0wNS0wNFQxMTo0MDowNi0wMzowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyNC0wNS0wNFQxMTo0MDowNi0wMzowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo3M2EyOWM1NS1mM2ExLTgzNDMtYTVkOC0zZTc1N2RjMzFjMTgiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDo5NmRlMGEzNC05YjU2LTg3NGEtODE5NS1hMGQzNWU2ZTYxNDYiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDozY2IzYTdkNS05MmRmLTI5NGQtOWFjNy0yYTdhZTg0OGQyNjciPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjNjYjNhN2Q1LTkyZGYtMjk0ZC05YWM3LTJhN2FlODQ4ZDI2NyIgc3RFdnQ6d2hlbj0iMjAyNC0wNC0yOVQxODowMDozNi0wMzowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIxLjAgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo3M2EyOWM1NS1mM2ExLTgzNDMtYTVkOC0zZTc1N2RjMzFjMTgiIHN0RXZ0OndoZW49IjIwMjQtMDUtMDRUMTE6NDA6MDYtMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7+nf9pAAAExElEQVRYw9WZfUxVZRzHT5GzUGPherPM2dvWasOtt5VROdYw0mm2JpvUqq0tt9YfVmZbbr0NV7PxB2LDWiEvXu5FJPJiIC8ShGTTeFmaSikIgQa+IISG0bfvc3/PuefcG6hc4HLus313fs/vPC+f8zy/8zznxZixDcYYNINaSL1LZVM11HHqrNaf1A9UHvUetYi6bix9hloxgcqgeiiMUmeoTVRSOIATqT0hQI6kJmrJRADfTHnGETRYXmrueAE/T/VNIKypQeqVsQKvCQNosFJDBf50EmBNZY4WePUkwppad7nASxwAa+qlSwFHOwjW1A0XA/Y6ELh+JOB4B8KaWjoc8AEHA3cGA893MKypJDtwZgQAF5rA06n+CABWutHQz7OIECUr4LV2Z8w3wBUewMgVRReKb1qh5Ysq4NFl5X1yU/m2fB4wpUDqTt2qfVts9Vg+pphHsy+X9OWvny91g4DXG/pNwe+MZiczWXB+JfBQBXBVgYZlZw8y/1gVcNO3wF07pMyjlXK8zQvM8Vq+uJ36ArKBWLb3CH0PlEubqsyt29l2rrSt2lywS+AfVn1USluKJfgx1NCvMH6nkQWkHYY/PakaSgNS9li+t5uAX3oRkMq6gN09gb465mPzBUilqhOAu13srKNsdwNQeULyqxqBhGqrrreT53Nk1G18DYZ+7/I5rtZTUmfr+MP9wPUFVv7cEPDBfrH7LwDpvLiMX4FUanBI/A2nrfJ5BHynWez0FmBehdiHzgIfHxS7ukcGqu0vq17XOQnNKz0BwG0KeMB0qAJTCd0xYFVsp336Hyv/40kCtlj5mm5g5U/AE7ssEDVyj+tRPfW31FHpowM8lwkc6bfqD/xL3xfAGn1RszyWPdsrsW3fQAKAVZyq2FSpl6O395TVcPEf1lTltVkjWdYBrKgH3mwUX+bvbGc9sLpJ8rW8oPLjYr/+s4TXi7bwupf9GZvFPs8ZeoqxX9IpeXUvqLAIBu72A7Pic3VSuPkMcE+p2K/tA5bvFnsrAUu7xK5g/K1tAJbVANs6xHe4D6jvtoAU3FE9ouqGMzKA5HrJtzIEjHRgUyuGTW/xoo2vA4CPGfppSIC/Ajb+JoWLOKLGl/oqN8vIqrSB4dDSF9jwsQEJBXs6yHz8dwwNHSq9gww5t7Spbjjz/pjmseqs5MAkbJfB8g1Ou8T2dAu40dAfOfxL2jxOyaJa4E5O1TVumZIo3unx7PhpjuQsLmnxVWInfi/H+0plqUpivYU1soRFeQRubgnwTK20O8UjfdxfLj4FMrNYZtW3GuVIPN/BOot5Pq7sf0vbDgX8vn3TMNdOc+G+1vRv0X63bAo+25QryJcrd7faGHybTLacjynSbbnEp84paN/5HFmTfZuJS2ZVHYM2jzQFvDiCtuYXFHAMdT5CgG8xHy+zIgC2xP48vCACgJ8NfkU64mDYk8O90yU6GDhlpNf8agfCNl/su0SsA4HnXOpTVYqDYN+43I+BqQ6A/Xy0n1sn89U/P9QP2usmAXbjWH8ZvEoNhQl21Xj9lLmb2jmBoHVU3ET89kqmDo0jaCv1cjh+LC6jcqkLIYIW6IuPCtefUFOzqeXUJ1QRtVc/k3RqKXsfVUx9Rq2gbh9Ln/8B9UOyFlYrX6kAAAAASUVORK5CYII='/>";
			_body += "         </td>";
			_body += "         <td width='40%'><img src='" + _raw_data.logoAFIP + "' style='width:200px;' /></td>";
			_body += "      </tr>";
			_body += "      <tr>";
			_body += "         <td colspan='2'><i>Esta Administración Federal no se responsabiliza por los datos ingresados en el detalle de la operación</i></td>";
			_body += "      </tr>";
			_body += "   </table>";
			_body += "</div>";

			var _html = "<div class='modal fade' id='telemedicinaComprobante' role='dialog'>";
			_html += " <div class='modal-dialog modal-dialog-centered' role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "       <table width='100%'>";
			_html += "          <tr>";
			_html += "             <td class='noshare' valign='middle' align='right' width='100%'>";
			_html += "                <a href='#' data-dismiss='modal'>";
			_html += "                   <i class='material-icons' style='color:grey;font-size:30px;'>close</i>";
			_html += "                </a>";
			_html += "            </td>";
			_html += "          </tr>";
			_html += "       </table>";
			_html += "    </div>";

			var _htmlAdd = "<hr class='noshare' style='background-color:#fff;border-top:2px dashed #8c8b8b;'/>";
			_htmlAdd += "       <table class='noshare' width='100%' style='margin-top:5px;'>";
			_htmlAdd += "          <tr>";
			_htmlAdd += "            <td width='40%' align='center'>";
			_htmlAdd += "               <button class='btn btn-raised btn-xs btn-info btnGetPDF' data-source='.area-pdf-factura' data-orientation='portrait' data-size='A4' data-type='share' data-file='Recibo.pdf'><i class='material-icons'>save</i> Guardar</button>";
			_htmlAdd += "            </td>";
			_htmlAdd += "          </tr>";
			_htmlAdd += "       </table>";

			_html += "    <div class='modal-body area-pdf-factura' style='margin:0px;padding:10px;border:solid 1px silver;'>" + _body + _htmlAdd + "</div>";
			_html += "    <div class='modal-footer' style='margin:0px;padding:5px;padding-left:10px;padding-right:10px;'></div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";

			$("body").append(_html);

			$("#telemedicinaComprobante").modal({ backdrop: false, keyboard: true, show: true });
			$("#telemedicinaComprobante").css({ "padding": "0px", "margin": "0px" });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onGetPDF: function (_this) {
		$(".noshare").hide();
		setTimeout(function () {
			var _html = $(_this.attr("data-source")).html();
			var opt = {
				margin: 1,
				filename: 'documento.pdf',
				image: { type: 'jpeg', quality: 1 },
				html2canvas: { scale: 1 },
				jsPDF: { format: 'a4' }
			};
			html2pdf(_html, opt);
			_FUNCTIONS.onDestroyModal('#telemedicinaComprobante');
		}, 1000);
	},
	onCheckStatusPaymentBotonPago: function (_idTransfer_botonpago, _dni) {
		var _json = {
			"module": "mod_payments",
			"table": "transactions",
			"model": "transactions",
			"page": 1,
			"pagesize": 1,
			"where": ("id=" + _idTransfer_botonpago),
			"order": "description ASC",
		};
		_AJAX.UiGetTransparent(_json).then(function (datajson) {
			if (datajson.data[0].status != "INICIADO") {
				clearInterval(_FUNCTIONS._TMR_PAY_BOTONPAGO);
				if (datajson.data[0].status == "APROBADO") {
					$(".data-payment1").addClass("d-none");
					$(".btn-deuda-fiserv").click();
					$(".id_payment").val(0);
					$(".code_payment").val(_idTransfer_botonpago); //id en mod_payments_transactions
					var response = { "now": _TOOLS.getNow(), "apiReference": _idTransfer_botonpago };
					var _fulldata = { "dni": _dni, "MedioPago": datajson.data[0].partial_card_number };
					var _raw_request = JSON.parse(datajson.data[0].raw_request);
					_raw_request = JSON.parse(_raw_request["comments"]);
					_FUNCTIONS.onWindowComprobante(response, _fulldata, _raw_request);
				} else {
					_FUNCTIONS.onAlert({ "class": "alert-danger", "message": "Su pago no ha podido ser procesado.  Reintente con otro medio de pago." });
					setTimeout(function () { window.location.reload(); }, 3000);
				}
			}
		}).catch(function (error) {
			alert(error.message);
		});
	},
	onVerCredencialesMediya: function (_this) {
		var _dni = _this.attr("data-dni");
		var _sexo = _this.attr("data-sexo");
		var _nombre = _this.attr("data-nombre");
		var _titular = _this.attr("data-titular");
		var _title = "Credenciales";
		var _html = "<div class='container'>";
		_html += "		<div class='row m-0 p-0 mb-5 loged rCredencial px-2 area-credenciales pb-5'>";
		_html += "		   <div class='col-6 p-0 m-0 text-center area-swiss skeleton card-loader'></div>";
		_html += "		   <div class='col-6 p-0 m-0 text-center area-gerdanna skeleton card-loader'></div>";
		_html += "		</div>";
		_html += "</div>";
		_html += "<div class='panel-footer mt-2'>";
		_html += " <hr/>";
		_html += " <div class='row text-center'>";
		_html += "  <div class='col-12'>";
		_html += "   <a class='btn-cancel-credenciales btn btn-danger btn-raised btn-md'>Cerrar</a>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";
		_FUNCTIONS.onDestroyModal("#modal-credenciales");
		var _window = "";
		_window += "<div id='modal-credenciales' class='modal fade' style='z-index:9999;'>";
		_window += "   <div class='modal-dialog modal-lg'>";
		_window += "      <div class='modal-content'>";
		_window += "         <div class='modal-header'>";
		_window += "            <h4>" + _title + "</h4>";
		_window += "         </div>";
		_window += "         <div class='modal-body'>" + _html + "</div>";
		_window += "      </div>";
		_window += "   </div>";
		_window += "</div>";
		$("body").append(_window);
		_AJAX.UiGetCredenciales({ "Tipo": "SWISS", "NroDocumento": _dni, "Sexo": _sexo }).then(function (datajson) {
			$.each(datajson.data, function (i, item) {
				if (_titular == "S") {
					if (parseInt(item.IDParentesco) == 1) { _TOOLS.drawCredentialSwiss(item); }
				} else {
					if (parseInt(item.IDParentesco) != 1 && parseInt(item.NroDocumento) == _dni && item.Nombre == _nombre) { _TOOLS.drawCredentialSwiss(item); }
				}
			});
		}).catch(function (error) { });
		_AJAX.UiGetCredenciales({ "Tipo": "GERDANNA", "NroDocumento": _dni, "Sexo": _sexo }).then(function (datajson) {
			$.each(datajson.data, function (i, item) {
				if (_titular == "S") {
					if (parseInt(item.IDParentesco) == 1) { _TOOLS.drawCredentialGerdanna(item); }
				} else {
					if (parseInt(item.IDParentesco) != 1 && parseInt(item.NroDocumento) == _dni && item.Nombre == _nombre) { _TOOLS.drawCredentialGerdanna(item); }
				}
			});
		}).catch(function (error) { });

		$("#modal-credenciales").on('hide.bs.modal', function () { });
		$("#modal-credenciales").modal({ backdrop: false, keyboard: true });
		$("body").off("click", ".btn-cancel-credenciales").on("click", ".btn-cancel-credenciales", function () {
			$("#modal-credenciales").modal("hide").data("bs.modal", null);
			_FUNCTIONS.onDestroyModal("#modal-credenciales");
		});
	},

	onGetAdicionalesTarjeta: function (_codigo, _username) {
		_AJAX.UiGetAdicionalesTarjeta({ "Codigo": _codigo })
			.then(function (_data) {
				$(".adicionales").html("<span class='badge badge-info'>Sin adicionales informados</span>");
				var _html = "<table class='table table-hover'>";
				_html += "      <thead>";
				_html += "         <tr>";
				_html += "            <th><a href='#' data-record='' data-id='0' data-codigo='" + _codigo + "' data-username='" + _username + "' class='btnEditAdicional'><i class='material-icons' style='color:grey;'>add_circle_outline</i></a></th>";
				_html += "            <th>Nombre</th>";
				_html += "            <th>Documento</th>";
				_html += "            <th>Estado</th>";
				_html += "            <th>Parentesco</th>";
				_html += "            <th>Nacimiento</th>";
				_html += "            <th>Edad</th>";
				_html += "            <th></th>";
				_html += "      </thead>";
				_html += "      <tbody>";
				$.each(_data.message.records, function (i, item) {
					var _record = _TOOLS.utf8_to_b64(JSON.stringify(item));
					var _color = "green";
					var _id = item["nId"];
					var _nombre = item["sNombre"];
					var _dni = item["nDoc"];
					var _estado = item["sLKEstado"];
					if (_estado != "HAB") { _color = "red"; }
					_html += "     <tr class='tr-" + _id + "'>";
					_html += "        <td><a href='#' data-record='" + _record + "' data-id='" + _id + "' data-codigo='" + _codigo + "' data-username='" + _username + "' class='btnEditAdicional'><i class='material-icons' style='color:black;'>mode_edit_outline</i></a></td>";
					_html += "        <td>" + _nombre + "</td>";
					_html += "        <td>" + _dni + "</td>";
					_html += "        <td style='color:white;background-color:" + _color + "'>" + _estado + "</td>";
					_html += "        <td>" + item["Parentesco"] + "</td>";
					_html += "        <td>" + new Date(item["FechaNacimiento"]).toISOString().split('T')[0] + "</td>";
					_html += "        <td>" + _TOOLS.getAge(item["FechaNacimiento"]) + "</td>";
					_html += "        <td><a href='#' data-record='' data-id='" + _id + "' class='btnDeleteAdicional'><i class='material-icons' style='color:red;'>delete_forever</i></a></td>";
					_html += "     </tr>";
				});
				_html += "      </tbody>";
				_html += "   </table>";
				$(".adicionales").html(_html);
			})
			.catch(function (error) {
				alert(error.message);
			});
	},
	onSetAdicionalTarjeta: function (_username, _id, _codigo, _record) {
		var _item = null;
		if (_record != "") {
			_item = JSON.parse(_TOOLS.b64_to_utf8(_record));
			_id_socio = 0;
		}
		var _title = "Nuevo adicional";
		if (parseInt(_id) != 0) { _title = "Editar adicional"; }
		var _html = "<div class='container'>";
		_html += "		<div class='row'>";
		_html += "			<div class='col-12 barReg'></div>";
		_html += "		</div>";
		_html += "		<div class='row'>";
		_html += "			<div class='col-4'>";
		_html += "				<label for='a_nDoc'>DNI</label>";
		_html += "				<input type='number' maxlength='9' class='form-control validateAdicional number dbaseAdicional a_nDoc' id='a_nDoc' name='a_nDoc' placeholder='DNI' />";
		_html += "				<input type='hidden' class='dbaseAdicional a_codigo' id='a_codigo' name='a_codigo' value='" + _codigo + "'/>";
		_html += "				<input type='hidden' class='dbaseAdicional a_nId' id='a_nId' name='a_nId' value='" + _id + "'/>";
		_html += "				<input type='hidden' class='dbaseAdicional a_username' id='a_username' name='a_username' value='" + _username + "'/>";
		_html += "			</div>";
		_html += "			<div class='col-8'>";
		_html += "				<label for='a_sNombre'>Nombre</label>";
		_html += "				<input type='text' class='form-control validateAdicional dbaseAdicional a_sNombre' id='a_sNombre' name='a_sNombre' placeholder='Nombre y apellido' />";
		_html += "			</div>";
		_html += "		</div>";
		_html += "		<div class='row'>";
		_html += "			<div class='col-6'>";
		_html += "				<label for='a_sLKParentesco'>Parentesco</label>";
		_html += "				<select id='a_sLKParentesco' name='a_sLKParentesco' class='form-control validateAdicional dbaseAdicional a_sLKParentesco'></select>";
		_html += "			</div>";
		_html += "			<div class='col-6'>";
		_html += "				<label for='a_dFechaNacimiento'>Nacimiento</label>";
		_html += "				<input type='date' class='form-control validateAdicional dbaseAdicional a_dFechaNacimiento' id='a_dFechaNacimiento' name='a_dFechaNacimiento' placeholder='Nacimiento' />";
		_html += "			</div>";
		_html += "		</div>";
		_html += "</div>";

		_html += "<div class='panel-footer mt-2'>";
		_html += " <hr/>";
		_html += " <div class='row text-center'>";
		_html += "  <div class='col-6'>";
		_html += "   <a data-codigo='" + _codigo + "' class='btn-success-adicional btn btn-success btn-raised btn-md' style='background-color:#2648b6;'>Aceptar</a>";
		_html += "  </div>";
		_html += "  <div class='col-6'>";
		_html += "   <a class='btn-cancel-adicional btn btn-danger btn-raised btn-md'>Cancelar</a>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";

		_FUNCTIONS.onDestroyModal("#modal-adicional");
		var _window = "";
		_window += "<div id='modal-adicional' class='modal fade' style='z-index:9999;'>";
		_window += "   <div class='modal-dialog modal-lg'>";
		_window += "      <div class='modal-content'>";
		_window += "         <div class='modal-header'>";
		_window += "            <h4>" + _title + "</h4>";
		_window += "         </div>";
		_window += "         <div class='modal-body'>" + _html + "</div>";
		_window += "      </div>";
		_window += "   </div>";
		_window += "</div>";
		$("body").append(_window);

		_FUNCTIONS.onTraerLookUp2("Parentesco")
			.then(function (data) {
				_TOOLS.loadCombo(data, { "target": "#a_sLKParentesco", "selected": -1, "id": "codigo", "description": "descripcion", "default": "[Seleccione]" });
				if (_item != null) {
					$(".a_nDoc").val(_item.nDoc);
					$(".a_sNombre").val(_item.sNombre);
					$(".a_sLKParentesco").val(_item.sLKParentesco);
					$(".a_dFechaNacimiento").val(new Date(_item.dFechaNacimiento).toISOString().split('T')[0]);
					var _color = "badge-success";
					if (_item.sLKEstado != "HAB") { _color = "badge-danger"; }
					var _html = "<table>";
					_html += "		<tr>";
					_html += "		   <td><span class='p-2 badge badge-dark'>Cuenta: <b>" + _item.sCodigoTarjeta + "</b></td>";
					_html += "		   <td><span class='p-2 badge badge-dark'>PAN: <b>" + _item.masked_pan + "</b></td>";
					_html += "		   <td><span class='p-2 badge " + _color + "'>Estado: <b>" + _estado + "</b></td>";
					_html += "		</tr>";
					_html += "	</table>";
					$(".barReg").html(_html);
				}
			});

		$("#modal-adicional").on('hide.bs.modal', function () { });
		$("#modal-adicional").modal({ backdrop: false, keyboard: true });
		$("body").off("click", ".btn-success-adicional").on("click", ".btn-success-adicional", function () {
			if (!_TOOLS.validate(".validateAdicional", true)) { return false; }
			var _json = _TOOLS.getFormValues(".dbaseAdicional", null);
			_json["nIDSucursal"] = $(".nIDSucursal").val();
			_AJAX.UiSetAdicionalTarjeta(_json).then(function (_data) {
				$(".adicionales").fadeOut("fast", function () {
					_FUNCTIONS.onGetAdicionalesTarjeta(_codigo, _username);
					$(".adicionales").fadeIn("fast");
					$(".btn-cancel-adicional").click();
				});
			}).catch(function (err) {
				alert(err.message);
			});
		});
		$("body").off("click", ".btn-cancel-adicional").on("click", ".btn-cancel-adicional", function () {
			$("#modal-adicional").modal("hide").data("bs.modal", null);
			_FUNCTIONS.onDestroyModal("#modal-adicional");
		});
	},
	onDelAdicionalTarjeta: function (_username, _id) {
		_AJAX.UiDelAdicionalTarjeta({ "id": _id, "username": _username }).then(function (_data) {
			$(".tr-" + _id).fadeOut("slow", function () { $(".tr-" + _id).remove(); })

		}).catch(function (err) {
			alert(err.message);
		});
		$("body").off("click", ".btn-cancel-adicional").on("click", ".btn-cancel-adicional", function () {
			$("#modal-adicional").modal("hide").data("bs.modal", null);
			_FUNCTIONS.onDestroyModal("#modal-adicional");
		});
	},
	onVerMapaMediya: function (_this) {
		var _geo = true;
		var _lat = _this.attr("data-lat");
		var _lng = _this.attr("data-lng");
		var _url = ("https://maps.googleapis.com/maps/api/staticmap?center=" + _lat + "," + _lng + "&maptype=hybrid&zoom=16&size=400x400&key=" + _FUNCTIONS.GLOOGLE_API_KEY + "&markers=" + _lat + "," + _lng + "&format=png&style=feature:poi|element:labels|visibility:off");
		var _title = "Domicilio geolocalizado";
		var _html = "<div class='container'>";
		_geo = (parseFloat(_lat) != 0 && parseFloat(_lng) != 0);
		if (!_geo) {
			_html += "<h2 style='color:red;'>No es posible la geolocalización</h2>";
			_html += "Los valores registrados para el domicilio son<br/>";
			_html += "<li><b>Latitud:</b> " + _lat + "</li>";
			_html += "<li><b>Longitud:</b> " + _lng + "</li>";
		} else {
			_html += "	    <div class='row px-1 py-4 m-0'>";
			_html += "	       <img class='imgMap' src='" + _url + "' style='width:100%;'/>";
			_html += "      </div>";
		}
		_html += "</div>";
		_html += "<div class='panel-footer mt-2'>";
		_html += " <hr/>";
		_html += " <div class='row text-center'>";
		_html += "  <div class='col-12'>";
		_html += "   <a class='btn-cancel-mapa btn btn-danger btn-raised btn-md'>Cerrar</a>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";

		_FUNCTIONS.onDestroyModal("#modal-mapa");
		var _window = "";
		_window += "<div id='modal-mapa' class='modal fade' style='z-index:9999;'>";
		_window += "   <div class='modal-dialog modal-lg'>";
		_window += "      <div class='modal-content'>";
		_window += "         <div class='modal-header'>";
		_window += "            <h4>" + _title + "</h4>";
		_window += "         </div>";
		_window += "         <div class='modal-body'>" + _html + "</div>";
		_window += "      </div>";
		_window += "   </div>";
		_window += "</div>";
		$("body").append(_window);

		$("#modal-mapa").on('hide.bs.modal', function () { });
		$("#modal-mapa").modal({ backdrop: false, keyboard: true });
		if (_geo) { $(".imgMap").attr("src", _url); }
		$("body").off("click", ".btn-cancel-mapa").on("click", ".btn-cancel-mapa", function () {
			$("#modal-mapa").modal("hide").data("bs.modal", null);
			_FUNCTIONS.onDestroyModal("#modal-mapa");
		});
	},
	onVerHistorialDePagos: function (_this) {
		var _idSocio = _this.attr("data-id_socio");

		var _title = "Historial de pagos";
		var _html = "<div class='container'>";
		_html += "	    <div class='row px-1 py-4 m-0'>";
		_html += "	       <div class='col-12 areaPagos'></div>";
		_html += "      </div>";
		_html += "</div>";

		_html += "<div class='panel-footer mt-2'>";
		_html += " <hr/>";
		_html += " <div class='row text-center'>";
		_html += "  <div class='col-12'>";
		_html += "   <a class='btn-cancel-pagos btn btn-danger btn-raised btn-md'>Cerrar</a>";
		_html += "  </div>";
		_html += " </div>";
		_html += "</div>";

		_FUNCTIONS.onDestroyModal("#modal-pagos");
		var _window = "";
		_window += "<div id='modal-pagos' class='modal fade' style='z-index:9999;'>";
		_window += "   <div class='modal-dialog modal-lg'>";
		_window += "      <div class='modal-content'>";
		_window += "         <div class='modal-header'>";
		_window += "            <h4>" + _title + "</h4>";
		_window += "         </div>";
		_window += "         <div class='modal-body'>" + _html + "</div>";
		_window += "      </div>";
		_window += "   </div>";
		_window += "</div>";
		$("body").append(_window);

		_AJAX.UiGetHistorialDePagos({ "IdSocio": _idSocio }).then(function (datajson) {
			var _html = "<table style='width:100%'>";
			_html += "<tr style='font-weight:bold;background-color:silver;'>";
			_html += "   <td align='center'>Fecha</td>";
			_html += "   <td>Origen</td>";
			_html += "   <td align='right'>Importe</td>";
			_html += "</tr>";
			$.each(datajson.message.records, function (i, item) {
				_html += "<tr>";
				_html += "   <td align='center'>" + item.FechaAlta + "</td>";
				_html += "   <td>" + item.Origen + "</td>";
				_html += "   <td align='right'>" + item.Importe + "</td>";
				_html += "</tr>";
			});
			_html += "</table>";
			$(".areaPagos").html(_html);
		}).catch(function (error) { });


		$("#modal-pagos").on('hide.bs.modal', function () { });
		$("#modal-pagos").modal({ backdrop: false, keyboard: true });
		$("body").off("click", ".btn-cancel-pagos").on("click", ".btn-cancel-pagos", function () {
			$("#modal-pagos").modal("hide").data("bs.modal", null);
			_FUNCTIONS.onDestroyModal("#modal-pagos");
		});
	},
	onGetTarjeta: function (_codigo, _username) {
		if (_codigo == undefined) { _codigo = ""; };
		if (_codigo == "0") { _codigo = ""; };
		if (_codigo != "") {
			_AJAX.UiGetTarjeta({ "Codigo": _codigo, "Usuario":_username })
				.then(function (_data) {
					if (_data.message.records == null || _data.message.records.length == 0) {
						alert("No se ha podido identificar la tarjeta solicitada.  Verifique la información provista.");
					} else {
						$(".info-verify").html("Tarjeta localizada.  Puede administrar sus datos.").removeClass("badge-warning").addClass("badge-success");
						setData(_data.message.records);
						_FUNCTIONS.onGetAdicionalesTarjeta(_codigo, _username);
					}
				})
				.catch(function (error) {
					alert(error.message);
				});
		}
	},
	onxSearchDNI: function (_this) {
		if (!_TOOLS.validate(".xSearchDNI", true)) {return false;}
		var _json = { "NroDocumento": $(".xSearchDNI").val() };
		_AJAX.UiGetDataCliente(_json).then(function (data) {
			if (data.status == "OK" && data.message.records.length != 0) {
				_FUNCTIONS.onShowInfo(data.message.html, "Datos del cliente");
				$(".modal-dialog").addClass("modal-lg");
				$(".modal-body").addClass("py-0");
			} else {
				alert("Sin resultados para esta consulta");
			}
		});
	},
	onCaptureHtml: function (_this) {
		var _target = ("." + _this.attr("data-target"));
		var _html = "<img src='https://intranet.credipaz.com/assets/img/wait.gif' id='imgWaiter' class='p-1 shadow imgWaiter shadow-md' style='width:100%;'>";
		$(_target).html(_html);
		html2canvas(document.getElementById(_this.attr("data-source"))).then(function (canvas) {
			_html = "<img src='" + canvas.toDataURL("image/jpeg", 1) + "' id='imgCaptura' class='p-1 shadow imgCaptura shadow-md' style='width:100%;'>";
			_html += "<a href='#' class='btn btn-light btn-sm btn-raised btnResetCaptura' data-target='" + _target + "' style='position:absolute;bottom:15px;left:15px;color:red;'><i class='material-icons'>delete</i> borrar</a>";
			$(_target).html(_html);
		});
	},
	onResetCaptura: function (_this) {
		$(_this.attr("data-target")).html("");
	},
}

