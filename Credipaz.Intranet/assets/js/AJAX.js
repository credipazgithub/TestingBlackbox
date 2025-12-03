_AJAX = {
	/**
	 * /
	 * GENERAL
	 */
	docUiExecute: function (_endpoint, _json) {
		return new Promise(
			function (resolve, reject) {
				var _call = { type: "POST", dataType: "json", url: _endpoint, data: _json };
				var ajaxRq = $.ajax({
					type: "POST",
					dataType: "json",
					url: _endpoint,
					data: _json,
					error: function (xhr, ajaxOptions, thrownError) { reject(thrownError); },
					success: function (datajson) { resolve(datajson); }
				});
			});
	},
	_pre: "",
	_waiter: false,
	server: (window.location.protocol + "//" + window.location.host + "/"),
	_here: (window.location.protocol + "//" + window.location.host + "/"),
	_ready: false,
	_user_firebase: null,
	_uid: null,
	_id_app: null,
	_id_channel: null,
	_channels: {},
	_id_user_active: null,
	_id_type_user_active: null,
	_id_sucursal: 100,
	_sucursal: "Casa Central",
	_username_active: null,
	_master_account: null,
	_image_active: null,
	_master_image_active: null,
	_language: "es-ar",
	_token_authentication: "",
	_token_authentication_created: "",
	_token_authentication_expire: "",
	_token_transaction: "",
	_token_transaction_created: "",
	_token_transaction_expire: "",
	_token_push: null,
	_model: null,
	_function: null,
	_module: null,
	_start_time: 0,
	forcePost: function (_path, _target, _parameters) {
		$("#forcedPost").remove();
		var _html = ("<form id='forcedPost' method='post' action='" + _AJAX.server + _path + "' target='" + _target + "'>");
		$.each(_parameters, function (key, value) {
			if (key == "where") { value = _TOOLS.utf8_to_b64(value); }
			_html += ("<input type='hidden' id='" + key + "' name='" + key + "' value='" + value + "'></input>");
		});
		_html += "</form>";
		$("body").append(_html);
		setTimeout(function () { $("#forcedPost").submit(); }, 1000);
	},
	formatFixedParameters: function (_json) {
		try {
			_AJAX._user_firebase.getIdToken().then(function (data) {
				_AJAX._token_push = data;
			}).catch(function (data) {
				_AJAX._token_push = "";
			});
		} catch (rex) {
			_AJAX._token_push = null;
		} finally {
			_json["token_push"] = _AJAX._token_push;
			_json["language"] = _AJAX._language;
			_json["token_authentication"] = _AJAX._token_authentication;
			if (_AJAX._id_user_active == "" || _AJAX._id_user_active == null) { _AJAX._id_user_active = 0;}
			_json["id_user_active"] = _AJAX._id_user_active;
			_json["username_active"] = _AJAX._username_active;
			if (_json["id_app"] == undefined) { _json["id_app"] = _AJAX._id_app; }
			if (_json["id_type_user_active"] == undefined) { _json["id_type_user_active"] = _AJAX._id_type_user_active; }
			if (_json["id_channel"] == undefined) { _json["id_channel"] = _AJAX._id_channel; }
			if (_json["model"] == undefined) { _json["module"] = _AJAX.model; }
			if (_json["module"] == undefined) { _json["module"] = _AJAX._module; }
			if (_json["function"] == undefined) { _json["function"] = _AJAX._function; }
			if (_json["table"] == undefined) { _json["table"] = ""; }
			if (_json["method"] == undefined) { _json["method"] = "api.backend/neocommand"; }
			return _json;
		}
	},
	initialize: function (_user_firebase) {
		if (_AJAX._user_firebase == null) { _AJAX._user_firebase = _user_firebase; }
		_AJAX._ready = true;
	},
	ExecuteDirect: function (_json, _method) {
		return new Promise(
			function (resolve, reject) {
				try {
					_AJAX.Execute(_AJAX.formatFixedParameters(_json)).then(function (datajson) {
						if (datajson.status != undefined) {
							if (datajson.status == "OK") {
								$(".raw-username_active").html(_AJAX._username_active);
								$(".raw-master_account").html(_AJAX._master_account);
								resolve(datajson);
							} else {
								reject(datajson);
							}
						} else {
							resolve(datajson);
						}
					});
				} catch (rex) {
					reject(rex);
				}
			});
	},
	Execute: function (_json) {
		_AJAX._start_time = new Date().getTime();
		return new Promise(
			function (resolve, reject) {
				try {
					if (!_AJAX._ready) { _AJAX.initialize(null); }
					$(".raw-raw-request").html(_TOOLS.prettyPrint(_json));
					var ajaxRq = $.ajax({
						type: "POST",
						dataType: "json",
						url: (_AJAX.server + _json.method),
						data: _json,
						beforeSend: function () {_AJAX.onBeforeSendExecute(); },
						complete: function () { _AJAX.onCompleteExecute(); },
						error: function (xhr, ajaxOptions, thrownError) {reject(thrownError);},
						success: function (datajson) {
							_AJAX.onSuccessExecute(datajson, _json)
								.then(function (datajson) { resolve(datajson); })
								.catch(function (err) { reject(err); });
						}
					});
				} catch (rex) {
					reject(rex);
				}
			}
		)
	},
	Load: function (_file) {
		return new Promise(
			function (resolve, reject) {
				var ajaxRq = $.ajax({
					type: "GET",
					timeout: 10000,
					dataType: "html",
					async: false,
					cache: false,
					url: _file,
					success: function (data) { resolve(data); },
					error: function (xhr, msg) { reject(msg); }
				});
			});
	},
	onBeforeSendExecute: function () {
		$(".waiter").removeClass("d-none");
		$(".wait-menu-ajax").html("<img src='https://intranet.credipaz.com/assets/img/menu.gif' style='height:24px'/>");
		$(".wait-search-ajax").html("<img src='https://intranet.credipaz.com/assets/img/search.gif' style='height:25px;width:50px;'/>");
		$(".wait-accept-ajax").html("<img src='https://intranet.credipaz.com/assets/img/accept.gif' style='height:25px;width:65px;'/>");
		if (_AJAX._waiter) {
			$(".wait-ajax").html("<img src='https://intranet.credipaz.com/assets/img/wait.gif' style='height:36px;'/>");
			$.blockUI({ message: '<img src="https://intranet.credipaz.com/assets/img/wait.gif" />', css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
		}
	},
	onCompleteExecute: function () {
		var request_time = ((new Date().getTime() - _AJAX._start_time) / 1000);
		if ($(".img-master").attr("src") != _AJAX._master_image_active) { $(".img-master").attr("src", _AJAX._master_image_active); }
		if ($(".img-user").attr("src") != _AJAX._image_active) { $(".img-user").attr("src", _AJAX._image_active); }
		$(".elapsed-time").html("Respuesta en " + request_time + " s");
		$(".waiter").html("");
		$(".status-ajax-calls").removeClass("d-none");
		$.unblockUI();
		_AJAX._waiter = false;
	},
	onSuccessExecute: function (datajson, _json_original) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (datajson["message"] == "Records") { datajson["message"] = "";}
					$(".raw-raw-response").html(_TOOLS.prettyPrint(datajson));
					$(".raw-message").html(datajson["code"] + ": " + datajson["message"]);
					if (datajson["status"] == "OK") {
						$(".status-last-call").removeClass("badge-danger").addClass("badge-success");
						$(".status-message").removeClass("d-sm-inline");
						//if (parseInt(_AJAX._doc_editor) == 1) { $(".editor-mode").removeClass("d-none"); } else { $(".editor-mode").addClass("d-none"); }
						//if (parseInt(_AJAX._doc_reviser) == 1) { $(".reviser-mode").removeClass("d-none"); } else { $(".reviser-mode").addClass("d-none"); }
						//if (_AJAX._doc_publisher == 1) { $(".publisher-mode").removeClass("d-none"); } else { $(".publisher-mode").addClass("d-none"); }
					} else {
						$(".status-last-call").removeClass("badge-success").addClass("badge-danger");
						$(".status-message").html(datajson["code"] + ": " + datajson["message"]).addClass("d-sm-inline");
					}
					$(".status-last-call").html(datajson["status"]);
					if (datajson == null) {
						datajson = { "results": null };
						resolve(datajson);
					} else {
						if (datajson.compressed == null) { datajson.compressed = false; }
						if (datajson.compressed == undefined) { datajson.compressed = false; }
						if (datajson != null && datajson.compressed) {
							var zip = new JSZip();
							JSZip.loadAsync(atob(datajson.message)).then(function (zip) {
								zip.file("compressed.tmp").async("string").then(
									function success(content) {
										datajson.message = content;
										resolve(datajson);
									},
									function error(err) { reject(err); });
							});
						} else {
							if (datajson.message != "") { _FUNCTIONS.onAlert({ "message": datajson.message, "class": "alert-danger" }); }
							switch (parseInt(datajson.code)) {
								case 5400:
								case 5200:
								case 5401:
									$(".barTelemedicina").remove();
									var _title = (datajson.code + ": " + datajson.message);
									var _body = "<p class='text-monospace'>Ha cambiado su token de autenticación.</p>";
									_body += "<p class='text-monospace'>Esto puede haberse debido a: ";
									_body += "<li>Sus credenciales fueron usadas en otro dispositivo estando la actual sesión activa</li>";
									_body += "<li>Desde administración, se ha modificado su perfil de seguridad</li>";
									_body += "</p > ";
									_body += "<p class='text-monospace'>Por favor autentíquese nuevamente, para seguir en este dispositivo.</p>";
									_FUNCTIONS.onInfoModal({ "close": true, "title": _title, "body": _body }, null, function () { window.location = "/"; });
									_FUNCTIONS.onReloadInit();
									break;
								default:
									resolve(datajson);
									break;
							}
						}
					}
				} catch (rex) {
					reject(rex);
				}
			}
		)
	},

	/**
	 * /
	 * MOD_BACKEND
	 */
	UiGet: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "get";
				_AJAX._waiter = false;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetTransparent: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "get";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX._waiter = false;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSave: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "save"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSaveSpecial: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "saveSpecial"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLock: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "lock"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiUnlock: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "unlock"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiOffline: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "offline"; //function
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiOnline: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "online"; //function
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiDelete: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "delete"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiProcess: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "process"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiForm: function (_json) {
		return new Promise(
			function (resolve, reject) {
				//_json["function"] = "form";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) {
					resolve(data);
				}).catch(function (err) {
					reject(err);
				});
			});
	},
	UiBrow: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "brow";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiEdit: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "edit";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiExcel: function (_json) {
		_json["mode"] = "view";
		_json["exit"] = "download";
		_json["function"] = "excel";
		_AJAX.forcePost('api.backend/neocommand', '_blank', _AJAX.formatFixedParameters(_json));
	},
	UiPdf: function (_json) {
		_json["mode"] = "view";
		_json["exit"] = "download";
		_json["function"] = "pdf";
		_AJAX.forcePost('api.backend/neocommand', '_blank', _AJAX.formatFixedParameters(_json));
	},
	UiAuthenticate: function (_json) {
		return new Promise(
			function (resolve, reject) {
				//_json["try"] = "LOCAL";
				_json["try"] = "LDAP";
				_json["method"] = "api.backend/authenticate"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLogged: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/logged"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLoggedIntegracion: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/loggedIntegracion"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLoggedCesiones: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/loggedCesiones"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLoggedTiendaMil: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/loggedTiendaMil"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLoggedMediYa: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/loggedMediYa"; //method

				console.log(_json);

				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLogout: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/logout"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiMessageRead: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "messageRead";
				_json["module"] = "mod_backend";
				_json["table"] = "messages_attached";
				_json["model"] = "messages_attached";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiMessagesNotification: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "notifications";
				_json["module"] = "mod_backend";
				_json["table"] = "messages_attached";
				_json["model"] = "messages_attached";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSendExternal: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_backend";
				_json["table"] = "external";
				_json["model"] = "external";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLogGeneral: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/logGeneral";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) {
					resolve(data);
				}).catch(function (err) {
					reject(err);
				});
			});
	},
	UiMenuTree: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_AJAX._waiter = false;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiMenuLevelOne: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/MenuLevelOne"; 
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiChangePassword: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "changePassword";
				_json["module"] = "mod_backend";
				_json["table"] = "users";
				_json["model"] = "users";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiInformUserArea: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/informUserArea"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetDataCliente: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetDataCliente";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	/**
	 * /
	 * MOD_FOLDERS
	 */
	UiFolderChangeStatus: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "changeStatus";
				//_json["module"] = "mod_folders";
				_json["table"] = "folders";
				_json["model"] = "folders";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFoldersNotification: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "notifications";
				_json["module"] = "mod_folders";
				_json["table"] = "folder_items";
				_json["model"] = "folder_items";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFoldersNotViewedNotification: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "notViewedNotifications";
				_json["module"] = "mod_folders";
				_json["table"] = "folder_items";
				_json["model"] = "folder_items";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFolderFileLoader: function (_json) {
		_json["mode"] = "view";
		_json["exit"] = "download";
		_json["function"] = "fileLoader";
		_json["module"] = "mod_folders";
		_json["table"] = "folder_items";
		_json["model"] = "folder_items";
		_AJAX.forcePost('api.backend/neocommand', '_blank', _AJAX.formatFixedParameters(_json));
	},
	UiStatusFolderItem: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "statusFolderItem";
				_json["module"] = "mod_folders";
				_json["table"] = "folder_items";
				_json["model"] = "folder_items";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFolderDetails: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "folderDetails";
				_json["module"] = "mod_folders";
				_json["table"] = "folders";
				_json["model"] = "folders";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiPriorityFolderItem: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "priority";
				_json["module"] = "mod_folders";
				_json["table"] = "folder_items";
				_json["model"] = "folder_items";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiMarkUserRead: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "markUserRead";
				_json["module"] = "mod_folders";
				_json["table"] = "folder_items_log";
				_json["model"] = "folder_items_log";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
		
	/**
	 * /
	 * MOD_CHANNELS
	 */
	UiAssignBuffer: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "assign";
				_json["module"] = "mod_channels";
				_json["table"] = "buffer_in";
				_json["model"] = "buffer_in";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiDirectEmail: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "directEmail";
				_json["module"] = "mod_email";
				_json["table"] = "email";
				_json["model"] = "email";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiDirectEmailTransparent: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "directEmail";
				_json["module"] = "mod_email";
				_json["table"] = "email";
				_json["model"] = "email";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	/**
	 * /
	 * MOD_CRM
	 */
	UiCRM: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_crm";
				_json["table"] = "operators_tasks";
				_json["model"] = "operators_tasks";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiAssignOperator: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "assign";
				_json["module"] = "mod_crm";
				_json["table"] = "operators_tasks";
				_json["model"] = "operators_tasks";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	
	/**
	 * /
	 * MOD_VALIDATE_CBU
	 */
	UiValidateCBU: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "ValidateCBU";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	/**

 	 * /
	 * MOD_PROVIDERS
	 */
	UiGetSectorsByProvider: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getSectorsByProvider";
				_json["module"] = "mod_providers";
				_json["table"] = "providers";
				_json["model"] = "providers";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	/**
	 * 
	 * /
	 * MOD_TELEMEDICINA
	 */
	UiCheckPaycode: function(_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "checkPaycode";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "charges_codes";
				_json["model"] = "charges_codes";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGeneratePaycode: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "generatePaycode";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "charges_codes";
				_json["model"] = "charges_codes";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiDirectTelemedicina: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "directTelemedicina";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "messages";
				_json["model"] = "messages";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLoadMessagesTelemedicina: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "get";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "messages";
				_json["model"] = "messages";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiViewMessagesTelemedicina: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "verifyMessage";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "messages";
				_json["model"] = "messages";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFreeTelemedicina: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "freeTelemedicina";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "operators_tasks";
				_json["model"] = "operators_tasks";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiPreviousTelemedicina: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_telemedicina";
				_json["table"] = "operators_tasks";
				_json["model"] = "operators_tasks";
				_json["function"] = "previousTelemedicina";
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiRecetasTelemedicina: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_telemedicina";
				_json["table"] = "messages";
				_json["model"] = "messages";
				_json["function"] = "recetasTelemedicina";
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiEmergency: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "emergency";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "operators_tasks";
				_json["model"] = "operators_tasks";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiEvalTelemedicinaQueue: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "evalTelemedicinaQueue";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "operators_tasks";
				_json["model"] = "operators_tasks";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiVideoDoctorStatus: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "videoDoctorStatus";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "charges_codes";
				_json["model"] = "charges_codes";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiTelemedicinaSendAuditAV: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "sendAuditAV";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "charges_codes";
				_json["model"] = "charges_codes";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiPostClose: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "postClose";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "operators_tasks";
				_json["model"] = "operators_tasks";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiCancelTelemedicina: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "cancelTelemedicina";
				_json["module"] = "mod_telemedicina";
				_json["table"] = "charges_codes";
				_json["model"] = "charges_codes";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFarmaLinkRecetas: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_external";
				_json["table"] = "Farmalink";
				_json["model"] = "Farmalink";
				_json["function"] = "Generate";
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiClonarRecetas: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_telemedicina";
				_json["table"] = "messages";
				_json["model"] = "messages";
				_json["function"] = "clonarReceta";
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	/**
	 * /
	 * MOD_PAYMENTS
	 */
	UiPaymentsSimple: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "processPayment";
				_json["module"] = "mod_payments";
				_json["table"] = "payments";
				_json["model"] = "payments_simple";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiPaymentsDocument: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "processPayment";
				_json["module"] = "mod_payments";
				_json["table"] = "payments";
				_json["model"] = "payments_document";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiPaymentsSimple: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "processPayment";
				_json["module"] = "mod_payments";
				_json["table"] = "payments";
				_json["model"] = "payments_simple";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiBuildFormFiserv: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "buildFormFiserv";
				_json["module"] = "mod_payments";
				_json["table"] = "payments_fiserv";
				_json["model"] = "payments_fiserv";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	UiInitTransactionFiservNet: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "PagosIniciarTransaccion";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiPaymentsInfo: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "infoPayments";
				_json["module"] = "mod_payments";
				_json["table"] = "Transactions";
				_json["model"] = "Transactions";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	/**
	 * /
	 * MOD_MARKETING
	 */
	UiIndicadoresCredipazInfo: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "infoIndicadores";
				_json["module"] = "mod_marketing";
				_json["table"] = "Transactions_credipaz";
				_json["model"] = "Transactions_credipaz";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiIndicadoresMediyaInfo: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "infoIndicadores";
				_json["module"] = "mod_marketing";
				_json["table"] = "Transactions_mediya";
				_json["model"] = "Transactions_mediya";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	/**
	 * /
	 * MOD_EXTERNAL
	 */
	UiClubRedondoWSTransparent: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_external";
				_json["table"] = "ClubRedondoWS";
				_json["model"] = "ClubRedondoWS";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiClubRedondoWS: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_external";
				_json["table"] = "ClubRedondoWS";
				_json["model"] = "ClubRedondoWS";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiRegistrarCobranza: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "registrarCobranza";
				_json["module"] = "mod_external";
				_json["table"] = "ClubRedondoWS";
				_json["model"] = "ClubRedondoWS";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLookUp: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "lookup";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetVerifySolicitudTarjeta: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "VerifySolicitudTarjeta";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSetEmitirTarjeta: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "SetEmitirTarjeta";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetTarjeta: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetTarjeta";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	/**
	 * /
	 * MOD_FOLLOW
	 */
	UiFollowAssignDoctor: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "assignDoctor";
				_json["module"] = "mod_follow";
				_json["table"] = "sinisters";
				_json["model"] = "sinisters";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFollowAddOccurs: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "addOccurs";
				_json["module"] = "mod_follow";
				_json["table"] = "sinisters";
				_json["model"] = "sinisters";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFollowChangeMedicalNote: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "changeMedicalNotes";
				_json["module"] = "mod_follow";
				_json["table"] = "sinisters";
				_json["model"] = "sinisters";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFollowChangePriority: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "changePriority";
				_json["module"] = "mod_follow";
				_json["table"] = "sinisters";
				_json["model"] = "sinisters";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFollowChangeAudit: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "changeAudit";
				_json["module"] = "mod_follow";
				_json["table"] = "sinisters";
				_json["model"] = "sinisters";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFollowChangeFullVacuna: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "changeFullVacuna";
				_json["module"] = "mod_follow";
				_json["table"] = "sinisters";
				_json["model"] = "sinisters";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFollowStatics: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "rpt_seguimientoCOVID";
				_json["module"] = "mod_dbcentral";
				_json["table"] = "consulta";
				_json["model"] = "consulta";
				_json["system"] = "storedprocedure";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFollowRegisterSends: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "registerSends";
				_json["module"] = "mod_follow";
				_json["table"] = "sinisters_sends";
				_json["model"] = "sinisters_sends";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiFollowStatusSends: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "statusSends";
				_json["module"] = "mod_follow";
				_json["table"] = "sinisters_sends";
				_json["model"] = "sinisters_sends";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	/**
	 * /
	 * MOD_DIRECT_SALE
	 */
	UiVideoVendorStatus: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "videoVendorStatus";
				_json["module"] = "mod_direct_sale";
				_json["table"] = "devices";
				_json["model"] = "devices";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiVideoBuyerStatus: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "videoBuyerStatus";
				_json["module"] = "mod_direct_sale";
				_json["table"] = "devices";
				_json["model"] = "devices";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSendDeviceCapture: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "sendDeviceCapture";
				_json["module"] = "mod_direct_sale";
				_json["table"] = "devices";
				_json["model"] = "devices";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiStopDeviceCapture: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "stopDeviceCapture";
				_json["module"] = "mod_direct_sale";
				_json["table"] = "devices";
				_json["model"] = "devices";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiCheckDevices: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "checkDevices";
				_json["module"] = "mod_direct_sale";
				_json["table"] = "devices";
				_json["model"] = "devices";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiActivateDevice: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "activateDevice";
				_json["module"] = "mod_direct_sale";
				_json["table"] = "devices";
				_json["model"] = "devices";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiDeActivateDevice: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "deActivateDevice";
				_json["module"] = "mod_direct_sale";
				_json["table"] = "devices";
				_json["model"] = "devices";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiRequestAttention: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "requestAttention";
				_json["module"] = "mod_direct_sale";
				_json["table"] = "devices";
				_json["model"] = "devices";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiVideoVendorStatusMIL: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "videoVendorStatus";
				_json["module"] = "mod_direct_sale";
				_json["table"] = "operators_tasks";
				_json["model"] = "operators_tasks";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiVideoBuyerStatusMIL: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "videoBuyerStatus";
				_json["module"] = "mod_direct_sale";
				_json["table"] = "operators_tasks";
				_json["model"] = "operators_tasks";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiCatalogoMIL: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/catalogoMIL"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	/**
	 * /
	 * MOD_CLUB_REDONDO
	 */
	UiGetCupons: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_club_redondo";
				_json["table"] = "beneficios";
				_json["model"] = "beneficios";
				_json["function"] = "getCupons";
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiUsuarioMediya: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "usuarioMediya";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetTitularMediya: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetTitularMediya";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetAdicionalesMediya: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetAdicionalesMediya";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiDelAdicionalMediya: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "DelAdicionalMediya";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSetAdicionalMediya: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "SetAdicionalMediya";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSetTitularMediya: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "SetTitularMediya";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLoginVendedor: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "LogInVendedor";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLoginComercializador: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "LoginComercializador";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetCredenciales: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.pwa/GetCredenciales"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetHistorialDePagos: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.pwa/GetHistorialDePagos"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetAdicionalesTarjeta: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetAdicionalesTarjeta";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSetAdicionalTarjeta: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "SetAdicionalTarjeta";
				_json["module"] = "mod_external";
				_json["table"] = "NetCoreCPFinancial";
				_json["model"] = "NetCoreCPFinancial";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	/**
	 * /
	 * MOD_ONBOARDING
	 */
	UiGetFieldById: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getFieldById";
				_AJAX._waiter = false;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiIndicadoresOnboarding: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "infoIndicadores";
				_json["module"] = "mod_onboarding";
				_json["table"] = "Informes";
				_json["model"] = "Informes";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	/**
	 * /
	 * MOD_LEGAL
	 */
	UiDirectLegales: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "save";
				_json["module"] = "mod_legal";
				_json["table"] = "Operators_tasks_items";
				_json["model"] = "Operators_tasks_items";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	/**
	 * /
	 * MOD_DBCENTRAL
	 */
	UiMediYaSubdiario: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "reportSubdiario";
				_json["module"] = "mod_dbcentral";
				_json["table"] = "mediya";
				_json["model"] = "mediya";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiIngresosConsulta: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "reportConsulta";
				_json["module"] = "mod_dbcentral";
				_json["table"] = "ingresos";
				_json["model"] = "ingresos";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiIngresosUpdate: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "reportUpdate";
				_json["module"] = "mod_dbcentral";
				_json["table"] = "ingresos";
				_json["model"] = "ingresos";
				_json["method"] = "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},	
	UiAlertTelegramTiendaMil: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "AlertTelegramTiendaMil";
				_json["module"] = "mod_push";
				_json["table"] = "Telegram";
				_json["model"] = "Telegram";
				_json["method"] = "api.backend/neocommandTransparent"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
};
