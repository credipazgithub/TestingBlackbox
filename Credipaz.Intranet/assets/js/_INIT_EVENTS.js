(function () {
	var today = new Date();
	$.getScript("./assets/js/AJAX.js?" + today.toDateString()).done(function (script, textStatus) {
		$.getScript("./assets/js/TOOLS.js?" + today.toDateString()).done(function (script, textStatus) {
			$.getScript("https://jvideo1.gruponeodata.com/external_api.js?" + today.toDateString()).done(function (script, textStatus) {
				$.getScript("./assets/js/FUNCTIONS.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
					_FUNCTIONS._sessionUID = _TOOLS.UUID();
					$.getScript("./assets/js/VIDEOCHAT.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
						$.getScript("./assets/js/PAYMENT.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
							$.getScript("./assets/js/MEDIA.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
								//$.getScript("./assets/js/WEBSOCKET.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
								/*-----------------------------------------------*/
								/*Nueva configuracion NeoEcosystem*/
								/*-----------------------------------------------*/
								var _authenticationServer = "https://localhost:44315/neoauthentication.v1/";
								var _videoServer = "https://localhost:44315/neovideo.v1/";
								//if (window.location.hostname != "localhost") {
								_videoServer = "https://api.gruponeodata.com/neovideo.v1/";
								_authenticationServer = "https://api.gruponeodata.com/neoauthentication.v1/";
								//}
								$.getScript(_FUNCTIONS._cdn_server + "authentication/NEOAUTHENTICATION.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
									$.getScript("./assets/js/NEOVIDEO-jvideo1.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
										_NEOAUTHENTICATION._SERVER = _authenticationServer;
										_NEOVIDEO._SERVER = _videoServer;
										_NEOVIDEO._id_application = 6;
									});
								});
								/*-----------------------------------------------*/
								moment().tz("America/Argentina/Buenos_Aires").format();
								_MEDIA.checkDeviceSupport(function () { });
								window.addEventListener("dragover", function (e) { e = e || event; e.preventDefault(); }, false);
								window.addEventListener("drop", function (e) { e = e || event; e.preventDefault(); }, false);
								$("body").off("click", ".btn-test-push").on("click", ".btn-test-push", function () {
									_FUNCTIONS.onTestPush($(this));
								});
								$("body").off("keyup", ".module-sinister-validate").on("keyup", ".module-sinister-validate", function (e) {
									_FUNCTIONS.onValidateModuleSinister($(this));
								});
								$("body").off("click", ".btn-follow-assign-doctor").on("click", ".btn-follow-assign-doctor", function () {
									_FUNCTIONS.onMedicalAssignDoctor($(this));
								});
								$("body").off("click", ".btn-add-occurs").on("click", ".btn-add-occurs", function () {
									_FUNCTIONS.onMedicalAddOccurs($(this));
								});
								$("body").off("click", ".btn-medical-notes").on("click", ".btn-medical-notes", function () {
									_FUNCTIONS.onMedicalNotes($(this));
								});
								$("body").off("click", ".btn-envios-details").on("click", ".btn-envios-details", function () {
									_FUNCTIONS.onEnviosDetails($(this));
								});
								$("body").off("click", ".btn-config").on("click", ".btn-config", function () {
									_FUNCTIONS.onConfigUserPreferences($(this));
								});
								$("body").off("click", ".btn-copyClip").on("click", ".btn-copyClip", function () {
									_TOOLS.copyToClipboard($(this));
								});
								$("body").off("keyup", ".textarea").on("keyup", ".textarea", function (e) {
									var textarea = $(this), top = textarea.scrollTop(), height = textarea.height();
									textarea.attr('rows', 2).css("heigth", "40");
									if (top > 0) { textarea.css("height", top + height); }
								});
								$("body").off("keyup", ".search-trigger").on("keyup", ".search-trigger", function (e) {
									var keyCode = (e.keyCode || e.which);
									if (keyCode === 13) { _FUNCTIONS.onBrowserSearch($(".btn-browser-search")); }
								});
								$("body").off("change", ".search-trigger").on("change", ".search-trigger", function () {
									if ($(this).is("select") === true) { _FUNCTIONS.onBrowserSearch($(".btn-browser-search")); }
								});
								$("body").off("change", ".pass_to_review").on("change", ".pass_to_review", function () {
									_FUNCTIONS.onPassToReview($(this));
								});
								$("body").off("change", ".audit_control").on("change", ".audit_control", function () {
									_FUNCTIONS.onFollowChangeAudit($(this));
								});
								$("body").off("change", ".full_vacuna").on("change", ".full_vacuna", function () {
									_FUNCTIONS.onFollowChangeFullVacuna($(this));
								});
								$("body").off("click", ".btn-silence").on("click", ".btn-silence", function () {
									_FUNCTIONS.onToggleSilence();
								});
								$("body").off("click", ".btn-login").on("click", ".btn-login", function () {
									_FUNCTIONS.onLogin($(this)).then(function (data) { _AJAX.UiLogged({}); });
								});
								$("body").off("click", ".btn-login-backend").on("click", ".btn-login-backend", function () {
									_FUNCTIONS.onLogin($(this), "backend").then(function (data) { _AJAX.UiLogged({}); });
								});
								$("body").off("click", ".btn-login-cesiones").on("click", ".btn-login-cesiones", function () {
									_FUNCTIONS.onLogin($(this), "cesiones").then(function (data) { _AJAX.UiLoggedCesiones({}); });
								});
								$("body").off("click", ".btn-login-tiendamil").on("click", ".btn-login-tiendamil", function () {
									_FUNCTIONS.onLogin($(this), "tiendamil").then(function (data) { _AJAX.UiLoggedTiendaMil({}); });
								});
								$("body").off("click", ".btn-login-mediya").on("click", ".btn-login-mediya", function () {
									_FUNCTIONS.onLogin($(this), "mediya").then(function (data) { _AJAX.UiLoggedMediYa({}); });
								});
								$("body").off("click", ".btn-login-integracion").on("click", ".btn-login-integracion", function () {
									_FUNCTIONS.onLogin($(this), "integracion").then(function (data) { _AJAX.UiLoggedIntegracion({}); });
								});
								$("body").off("click", ".btn-logout").on("click", ".btn-logout", function () {
									_FUNCTIONS.onLogout($(this));
								});
								$("body").off("click", ".btn-menu-open").on("click", ".btn-menu-open", function (e) {
									_FUNCTIONS.onMenuOpen($(this), e);
								});
								$("body").off("click", ".btn-menu-close").on("click", ".btn-menu-close", function (e) {
									_FUNCTIONS.onMenuClose($(this), e);
								});
								$("body").off("click", ".btn-menu-click").on("click", ".btn-menu-click", function (e) {
									_FUNCTIONS.onMenuClick($(this));
								});
								$("body").off("click", ".btn-record-edit").on("click", ".btn-record-edit", function (e) {
									_FUNCTIONS.onRecordEdit($(this));
								});
								$("body").off("click", ".btn-check-paycode").on("click", ".btn-check-paycode", function (e) {
									_FUNCTIONS.onCheckPaycode($(this));
								});
								$("body").off("click", ".btn-next-attention").on("click", ".btn-next-attention", function (e) {
									_FUNCTIONS.onNextMedicalRequest($(this));
								});
								$("body").off("click", ".btn-record-remove").on("click", ".btn-record-remove", function (e) {
									_FUNCTIONS.onRecordRemove($(this));
								});
								$("body").off("click", ".btn-record-offline").on("click", ".btn-record-offline", function (e) {
									_FUNCTIONS.onRecordOffline($(this));
								});
								$("body").off("click", ".btn-record-online").on("click", ".btn-record-online", function (e) {
									_FUNCTIONS.onRecordOnline($(this));
								});
								$("body").off("click", ".btn-record-process").on("click", ".btn-record-process", function (e) {
									_FUNCTIONS.onRecordProcess($(this));
								});
								$("body").off("click", ".btn-abm-accept").on("click", ".btn-abm-accept", function (e) {
									_FUNCTIONS.onAbmAccept($(this));
								});
								$("body").off("click", ".btn-abm-accept-special").on("click", ".btn-abm-accept-special", function (e) {
									_FUNCTIONS.onAbmAcceptSpecial($(this));
								});
								$("body").off("click", ".btn-abm-cancel").on("click", ".btn-abm-cancel", function (e) {
									_FUNCTIONS.onAbmCancel($(this));
								});
								$("body").off("click", ".btn-browser-search").on("click", ".btn-browser-search", function (e) {
									_FUNCTIONS.onBrowserSearch($(this));
								});
								$("body").off("click", ".btn-excel-search").on("click", ".btn-excel-search", function (e) {
									_FUNCTIONS.onBrowserSearch($(this));
								});
								$("body").off("click", ".btn-pdf-search").on("click", ".btn-pdf-search", function (e) {
									_FUNCTIONS.onBrowserSearch($(this));
								});
								$("body").off("click", ".btn-brief").on("click", ".btn-brief", function (e) {
									_FUNCTIONS.onBriefModal($(this));
								});
								$("body").off("click", ".btn-verify-signs").on("click", ".btn-verify-signs", function (e) {
									_FUNCTIONS.onVerifySigns($(this));
								});
								$("body").off("click", ".btn-close-modal").on("click", ".btn-close-modal", function (e) {
									$($(this).attr("data-click")).click();
									_FUNCTIONS.onDestroyModal(".modal");
								});
								$("body").off("click", ".btn-upload").on("click", ".btn-upload", function (e) {
									$($(this).attr("data-click")).click();
								});
								$("body").off("click", ".btn-upload-reset").on("click", ".btn-upload-reset", function (e) {
									_FUNCTIONS.onResetSelectedFile($(this));
								});
								$("body").off("click", ".btn-upload-delete").on("click", ".btn-upload-delete", function (e) {
									_FUNCTIONS.onDeleteSelectedFile($(this));
								});
								$("body").off("click", ".btn-link-delete").on("click", ".btn-link-delete", function (e) {
									_FUNCTIONS.onDeleteSelectedLink($(this));
								});
								$("body").off("click", ".btn-folders-delete").on("click", ".btn-folders-delete", function (e) {
									_FUNCTIONS.onDeleteSelectedFileFolders($(this));
								});
								$("body").off("change", ".btn-pick-files-img_comprobante_servicio").on("change", ".btn-pick-files-img_comprobante_servicio", function (e) {
									_FUNCTIONS.onProcessSelectedFilesSimple($(this));
								});
								$("body").off("change", ".btn-pick-files-img_comprobante_ingreso").on("change", ".btn-pick-files-img_comprobante_ingreso", function (e) {
									_FUNCTIONS.onProcessSelectedFilesSimple($(this));
								});
								$("body").off("change", ".btn-pick-files-img_dni_frente").on("change", ".btn-pick-files-img_dni_frente", function (e) {
									_FUNCTIONS.onProcessSelectedFilesSimple($(this));
								});
								$("body").off("change", ".btn-pick-files-img_dni_dorso").on("change", ".btn-pick-files-img_dni_dorso", function (e) {
									_FUNCTIONS.onProcessSelectedFilesSimple($(this));
								});
								$("body").off("change", ".btn-pick-files-image-simple").on("change", ".btn-pick-files-image-simple", function (e) {
									_FUNCTIONS.onProcessSelectedFilesSimple($(this));
								});
								$("body").off("change", ".btn-pick-files-image").on("change", ".btn-pick-files-image", function (e) {
									_FUNCTIONS.onProcessSelectedFiles($(this));
								});
								$("body").off("change", ".btn-pick-files-image_apaisada").on("change", ".btn-pick-files-image_apaisada", function (e) {
									_FUNCTIONS.onProcessSelectedFiles($(this));
								});
								$("body").off("change", ".btn-folders-files-folders").on("change", ".btn-folders-files-folders", function (e) {
									_FUNCTIONS.onProcessSelectedFilesFolders($(this));
								});
								$("body").off("change", ".id_type_command").on("change", ".id_type_command", function (e) {
									_FUNCTIONS.onTypeCommandChange($(this));
								});
								$("body").off("click", ".btn-folder-change-status").on("click", ".btn-folder-change-status", function (e) {
									_FUNCTIONS.onFoldersChangeStatus($(this));
								});
								$("body").off("click", ".btn-follow-change-priority").on("click", ".btn-follow-change-priority", function (e) {
									_FUNCTIONS.onFollowChangePriority($(this));
								});
								$("body").off("click", ".btn-message-external").on("click", ".btn-message-external", function (e) {
									_FUNCTIONS.onFolderMessagesModal($(this));
								});
								$("body").off("click", ".btn-message-read").on("click", ".btn-message-read", function (e) {
									_FUNCTIONS.onMessageRead($(this));
								});
								$("body").off("click", ".btn-record-check").on("click", ".btn-record-check", function (e) {
									_FUNCTIONS.onCheckRecord($(this));
								});
								$("body").off("click", ".external_operator").on("click", ".external_operator", function (e) {
									_FUNCTIONS.onCheckExternalOperator($(this));
								});
								$("body").off("click", ".btn-buffer-assign").on("click", ".btn-buffer-assign", function (e) {
									_FUNCTIONS.onAssignBuffer($(this));
								});
								$("body").off("click", ".btn-crm-assign").on("click", ".btn-crm-assign", function (e) {
									_FUNCTIONS.onAssignOperator($(this));
								});
								$("body").off("click", ".btn-reply-email").on("click", ".btn-reply-email", function () {
									_FUNCTIONS.onProcessDirectEmail($(this));
								});
								$("body").off("change", ".id_type_task_close").on("change", ".id_type_task_close", function () {
									_FUNCTIONS.onTypeTaskClose($(this));
								});
								$("body").off("change", ".id_provider").on("change", ".id_provider", function () {
									_FUNCTIONS.onIdProvider($(this));
								});
								$("body").off("keyup", ".folder-item-priority").on("keyup", ".folder-item-priority", function () {
									_FUNCTIONS.onFolderItemPriority($(this));
								});
								$("body").off("change", ".folder-item-priority-update").on("change", ".folder-item-priority-update", function () {
									_FUNCTIONS.onFolderItemPriorityUpdate($(this));
								});
								$("body").off("change", ".id_type_task_close").on("change", ".id_type_task_close", function () {
									_FUNCTIONS.onIdTypeTaskClose($(this));
								});
								$("body").off("click", ".btn-status-folder-item").on("click", ".btn-status-folder-item", function () {
									_FUNCTIONS.onStatusFolderItem($(this));
								});
								$("body").off("click", ".btn-folder-audit").on("click", ".btn-folder-audit", function () {
									_FUNCTIONS.onFolderAudit($(this));
								});
								$("body").off("click", ".btn-external-link").on("click", ".btn-external-link", function () {
									_FUNCTIONS.onAddLinkExternal($(this));
								});
								$("body").off("click", ".btn-telemedicina-msg").on("click", ".btn-telemedicina-msg", function () {
									_FUNCTIONS.onProcessDirectTelemedicina($(this));
								});
								$("body").off("click", ".btn-telemedicina-msg-pdf").on("click", ".btn-telemedicina-msg-pdf", function () {
									_FUNCTIONS.onProcessDirectTelemedicinaPDF($(this));
								});
								$("body").off("click", ".msg-telemedicina").on("click", ".msg-telemedicina", function () {
									_FUNCTIONS.onViewDirectTelemedicina($(this));
								});
								$("body").off("click", ".msg-telemedicina-pdf").on("click", ".msg-telemedicina-pdf", function () {
									_FUNCTIONS.onViewDirectTelemedicinaPDF($(this));
								});
								$("body").off("click", ".previous-telemedicina").on("click", ".previous-telemedicina", function () {
									_FUNCTIONS.onViewPreviousTelemedicina($(this));
								});
								$("body").off("click", ".btn-free-telemedicina").on("click", ".btn-free-telemedicina", function () {
									_FUNCTIONS.onFreeTelemedicina($(this));
								});
								$("body").off("click", ".btn-request-pictures").on("click", ".btn-request-pictures", function () {
									_FUNCTIONS.onRequestPictures($(this));
								});
								$("body").off("click", ".btn-add-closed").on("click", ".btn-add-closed", function () {
									_FUNCTIONS.onAddPostNotesTelemedicina($(this));
								});
								$("body").off("click", ".btn-cancel-telemedicina").on("click", ".btn-cancel-telemedicina", function () {
									_FUNCTIONS.onCancelTelemedicina($(this));
								});
								$("body").off("click", ".btn-doctor-atencion").on("click", ".btn-doctor-atencion", function () {
									_FUNCTIONS.onToggleTelemedicina($(this));
								});
								$("body").off("click", ".btnLoadMessagesTelemedicina").on("click", ".btnLoadMessagesTelemedicina", function () {
									_FUNCTIONS.onLoadMessagesTelemedicina($(this).attr("data-id"), ".div-imagenes", ".div-comunicaciones");
								});
								$("body").off("click", ".btn-follow-accept").on("click", ".btn-follow-accept", function () {
									_FUNCTIONS.onFollowAccept($(this));
								});
								$("body").off("click", ".btn-discharge-accept").on("click", ".btn-discharge-accept", function () {
									_FUNCTIONS.onDischargeAccept($(this));
								});
								$("body").off("click", ".btn-integrity").on("click", ".btn-integrity", function () {
									_FUNCTIONS.onIntegrity($(this));
								});
								$("body").off("click", ".btn-reverify").on("click", ".btn-reverify", function () {
									_FUNCTIONS.onReverify($(this));
								});
								$("body").off("click", ".btn-ResetPassword").on("click", ".btn-ResetPassword", function () {
									_FUNCTIONS.onResetPassword($(this));
								});
								$("body").off("click", ".btn-see-object").on("click", ".btn-see-object", function () {
									_FUNCTIONS.onSeeObject($(this));
								});
								$("body").off("click", ".btn-shorcut-ws").on("click", ".btn-shorcut-ws", function () {
									_FUNCTIONS.onSendShortcutWS($(this));
								});
								$("body").off("click", ".btn-operator-task-item").on("click", ".btn-operator-task-item", function () {
									_FUNCTIONS.onLegalesModal($(this));
								});
								$("body").off("click", ".btn-operator-task-item-auto").on("click", ".btn-operator-task-item-auto", function () {
									_FUNCTIONS.onLegalesAutoRecord($(this));
								});
								$("body").off("click", ".btn-clon-receta").on("click", ".btn-clon-receta", function () {
									_FUNCTIONS.onClonarReceta($(this));
								});
								$("body").off("click", ".btn-see-comprobante").on("click", ".btn-see-comprobante", function () {
									_FUNCTIONS.onSeeComprobante($(this));
								});
								$("body").off("click", ".btnGetPDF").on("click", ".btnGetPDF", function () {
									_FUNCTIONS.onGetPDF($(this));
								});
								$("body").off("click", ".btn-xSearchDNI").on("click", ".btn-xSearchDNI", function () {
									_FUNCTIONS.onxSearchDNI($(this));
								});
								$("body").off("click", ".xCaptureHtml").on("click", ".xCaptureHtml", function (e) {
									_FUNCTIONS.onCaptureHtml($(this));
								});
								$("body").off("click", ".btnResetCaptura").on("click", ".btnResetCaptura", function (e) {
									_FUNCTIONS.onResetCaptura($(this));
								});
								$("body").off("keyup", ".xSearchDNI").on("keyup", ".xSearchDNI", function (e) {
									var keyCode = (e.keyCode || e.which);
									if (keyCode === 13) { $(".btn-xSearchDNI").click(); }
								});
								//});
							});
						});
					});
				});
			});
		});
	});
})();
