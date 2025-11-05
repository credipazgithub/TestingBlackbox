var _PAY = {
    _autoIn: false,
    _TMR_PAY_TELEMEDICINA: 0,
    _idTransfer_telemedicina: 0,
    _TMR_PAY_LEGALES: 0,
    _idTransfer_legales: 0,
    _TEST_DNI: 20734796,

    /**
     * /
     * ACTIVACION Y PAGOS TELEMEDICINA
     */
    onAddEspecialidades: function (_this) {
        var _json = {
            "module": "mod_backend",
            "table": "groups",
            "model": "groups",
            "page": 1,
            "pagesize": -1,
            "where": "code like 'ESPECIALIDAD_%'",
            "order": "description ASC",
        };
        _API.UiGet(_json).then(function (datajson) {
            _TOOLS.loadCombo(datajson, { "target": "#cboEspecialidad", "selected": "ESPECIALIDAD_CLINICA_MEDICA", "id": "code", "description": "description" });
        }).catch(function (error) {
            _NMF.onAlert({ "class": "alert-danger", "message": error.message });
        });
    },
    onBuildInterfacePermisosTelemedicina: function (data) {
        _PAY.onGeneratePayCodeTelemedicina($(this));
        return "";
        /*
        var _html = "<div class='modal fade' id='telemedicinaPermiso' role='dialog' style='z-index:9999999;'>";
        _html += " <div class='modal-dialog modal-dialog-centered modal-lg' role='document' style='z-index:9999999;'>";
        _html += "  <div class='modal-content'>";
        _html += "    <div class='modal-header'>";
        _html += "       <h5 class='modal-title'>Solicitud de conformidad</h5>";
        _html += "    </div>";
        _html += "    <div class='modal-body body-permiso'>";
        _html += data.data[0].body_post;
        _html += "    </div>";
        _html += "    <div class='body-footer modal-footer font-weight-light' style='margin-bottom:50px;'>";
        _html += "       <button  class='btn-raised btn-accept-permiso btn btn-success btn-sm'></span>Doy mi consentimiento</button>";
        _html += "       <button  class='btn-raised btn-cancel-permiso btn btn-danger btn-sm'></span>Cancelar</button>";
        _html += "    </div>";
        _html += "  </div>";
        _html += " </div>";
        _html += "</div>";
        return _html;
        */
    },
    onEventsPermisosTelemedicina: function () {
        $("body").off("click", ".btn-accept-permiso").on("click", ".btn-accept-permiso", function () {
            _PAY.onGeneratePayCodeTelemedicina($(this));
        });
        $("body").off("click", ".btn-cancel-permiso").on("click", ".btn-cancel-permiso", function () {
            _NMF.onDestroyModal('#telemedicinaPermiso');
        });
    },
    onGeneratePermisoTelemedicina: function (_this) {
        if (_NMF._session_data.ClubRedondo == 0) {
            _NMF.onIsNotSocio("Debe ser socio para solicitar atención de Telemedicina.");
        } else {
            _NMF.onDestroyModal('#telemedicinaPermiso');
            _API.UiGetWebPosts({ "id": 94 }).then(function (data) {
                $("body").append(_PAY.onBuildInterfacePermisosTelemedicina(data));
                _PAY.onEventsPermisosTelemedicina();
                $("#telemedicinaPermiso").modal({ backdrop: false, keyboard: true, show: true });
            });
        }
    },
    onBuildInterfaceTelemedicina: function () {
        var _html = "<div class='modal telemedicinaPrevia fade' id='telemedicinaPrevia' role='dialog' style='z-index:9999999;'>";
        _html += "       <input type='hidden' class='dbaseprevia id_payment' id='id_payment' name='id_payment' value='0'/>";
        _html += "       <input type='hidden' class='dbaseprevia code_payment' id='code_payment' name='code_payment' value='0'/>";
        _html += "       <input type='hidden' class='dbaseprevia importe_total' id='importe_total' name='importe_total' value='0'/>";
        _html += "       <input type='hidden' id='concepto' name='concepto' value='' class='dbaseprevia concepto'/>";
        _html += "       <input type='hidden' id='referencia' name='referencia' value='' class='dbaseprevia referencia'/>";
        _html += "       <input type='hidden' id='importe' name='importe' value='0' class='dbaseprevia importe'/>";
        _html += " <div class='modal-dialog modal-dialog-centered modal-lg' role='document' style='margin:0px;padding:0px;'> ";
        _html += "  <div class='imgDoctor modal-content p-2 m-0 text-center' style='border-radius:0px;border:none;margin:0px;padding:0px;width:100vw;height:100vh;'>";
        _html += "     <div class='row no-gutters align-items-center inner-title'>";
        _html += "        <div class='col-1 pt-2'>";
        _html += "           <a href='#' class='btn-cancel-previa'>";
        _html += "              <i class='material-icons chevron' style='color:white;font-size:30px;'>chevron_left</i>";
        _html += "           </a>";
        _html += "        </div>";
        _html += "        <div class='col-11 text-center p-0'><b class='dyn-title' style='color:white;'>Telemedicina</b></div>";
        _html += "     </div>";

        _html += "       <div class='alert alert-info openclosemessage' style='font-weight:bold;font-size:26px;color:white;'><b>" + _NMF._session_data.TelemedicinaMensajeCerrado + "</b></div>";
        if (_NMF.onOpenTelemedicina()) {
            _html += "       <p class='modal-title open d-none'></p>";
            if (_NMF._session_data.TeleMedConsultasResto == 0) {
                _html += "<p class='body-pago body-pago-title d-none'  style='color:white;'>Por favor, efectúe el pago de la consulta $ " + _NMF._session_data.MontoTelemedicina + "</p>";
                _PAY._autoIn = false;
            } else {
                _html += "<p class='body-pago body-pago-title d-none' style='color:white;'>¡Aún puede utilizar consultas sin costo!</p>";
                _PAY._autoIn = true;
            }
            _html += "       <p class='body-pago d-none' style='color:white;'><span class='badge badge-primary especialidad'></span></p>";
            _html += "       <div class='body-wait d-none' style='text-align:center;width:100%;height:100%;padding:10px;color:white;'>";
            _html += "           <img src='img/Comun/wait.gif' style='width:75%;' />";
            _html += "           <div style='color:white;'>Aguarde por favor...</div>";
            _html += "       </div>";
            _html += "    <div class='body-encuesta open d-none mt-3 px-2 text-center'>";
            _html += "       <table style='width:100%;color:white;' class='d-none'>";
            _html += "          <tr>";
            _html += "             <td><select id='cboEspecialidad' name='cboEspecialidad' class='form-control dbaseprevia cboEspecialidad validatePrevia'></select></td>";
            _html += "          </tr>";
            _html += "       </table>";
            _html += "       <p class='mt-3 text-center' style='color:white;'><b>Si consulta por Recetas de patología crónica, tenga a disposición documentación respaldatoria, receta previa y / o estudios realizados.</b></p>";
            if (_NMF._session_data.Telefono != "") {
                _html += "<p class='mt-2 text-center' style='color:white;'>Verifique el número de teléfono desde el cual está realizando la llamada, para que el médico pueda contactarlo en caso de que se interrumpa la comunicación.</p>";
            } else {
                _html += "<p class='mt-2 text-center' style='color:white;'>Ingrese el número de teléfono desde el cual está realizando la llamada, para que el médico pueda contactarlo en caso de que se interrumpa la comunicación.</p>";
            }
            _html += "       <div class='row no-gutters mx-2 mt-1'>";
            _html += "           <div class='col-4 text-left'>";
            _html += "               <input value='" + _NMF._session_data.CodigoArea +"' list='prefijos' inputmode='numeric' maxlength='4' type='number' id='area' name='area' class='form-control area onlyNumbers validatePrevia' placeholder='Área...' />";
            _html += "           </div>";
            _html += "           <div class='col-8 text-left'>";
            _html += "               <input value='" + _NMF._session_data.Telefono +"' inputmode='numeric' maxlength='8' type='number' id='phone' name='phone' class='form-control phone onlyNumbers validatePrevia' placeholder='Teléfono...' />";
            _html += "           </div>";
            _html += "       </div>";

            _html += "    </div>";
            _html += "    <div class='body-pago body-pago-form d-none'></div>";
            _html += "    <div class='text-center mt-3'>";
            _html += "       <table style='width:100%;'>";
            _html += "          <tr>";
            _html += "             <td align='center'>";
            var _lbl1 = "SOLICITAR VIDEOCONSULTA";
            var _lbl2 = "Aceptar";
            if (_NMF._session_data.TeleMedConsultasResto == 0) { _lbl1 = "Ir a pago"; _lbl2 = "Pagar"; }
            _html += "       <button class='btn-raised btn-move-open btn btn-info btn-sm openclosemessage mb-2' style='display:block;'><i class='material-icons'>chevron_right</i></span>Continuar</button>";
            _html += "       <button class='btn-accept-previa open d-none mb-2 btn-general'  style='display:block;'>" + _lbl1 + "</button>";
            if (_NMF._session_data.TeleMedConsultasResto != 0) { _html += "<button class='btn-accept-sincargo d-none mb-2 btn-general' style='display:block;'>SOLICITAR VIDEOCONSULTA</button>"; }
            _html += "             </td>";
            _html += "          </tr>";
            _html += "       </table>";
        } else {
            _html += "       <button  class='btn-cancel-previa btn btn-warning btn-sm mb-2' style='display:block;'><i class='material-icons'>chevron_right</i></span>Continuar</button>";
        }
        _html += "    </div>";
        _html += "  </div>";
        _html += "</div>";
        return _html;
    },
    onEventsTelemedicina: function () {
        $("body").off("change", ".cboEspecialidad").on("change", ".cboEspecialidad", function () {
            $(".especialidad").html($("#cboEspecialidad option:selected").text());
        });
        $("body").off("click", ".btn-move-open").on("click", ".btn-move-open", function () {
            $(".openclosemessage").addClass("d-none");
            $(".open").removeClass("d-none");
        });
        $("body").off("click", ".btn-cancel-previa").on("click", ".btn-cancel-previa", function () {
            _PAY._idTransfer_telemedicina = 0;
            clearInterval(_PAY._TMR_PAY_TELEMEDICINA);
            _NMF.onDestroyModal("#telemedicinaPrevia");
            $(".btn-generate-paycode").removeClass("d-none");
        });
        $("body").off("click", ".btn-accept-previa").on("click", ".btn-accept-previa", function () {
            if (!_TOOLS.validate(".validatePrevia")) {
                alert("¡Complete los datos solicitados!");
                $('.validatePrevia').each(function () {
                    this.style.setProperty('border', 'solid 1px red', 'important');
                });
                return false;
            };
            $(".body-pago-form").html("");
            if (_NMF._session_data.TeleMedConsultasResto == 0) {
                var _total = $(".importe").val();
                if (_total > 0) {
                    _NMF._itemsPagos = [{ "Tipo": "TELEMED", "Identificacion": _NMF._session_data.ClubRedondo, "Importe": _total, "idTransfer": 0 }];
                    var _sandbox = 0;
                    var _visible = 0;
                    var _targetFrame = "_blank";
                    var _location = window.location.href;
                    var _json = {
                        "currency": "032",
                        "total": _total,
                        "dni": _NMF._auth_user_data.dni,
                        "itemsPagos": JSON.stringify(_NMF._itemsPagos),
                        "sandbox": _sandbox,
                        "visible": _visible,
                        "parentUri": _location
                    };
                    _API.UiBuildFormFiserv(_json).then(function (data) {
                        data.data += "<iframe id='iframe_fiserv' name='iframe_fiserv' class='iframe_fiserv d-none' src='' frameborder='0' style='height:100vh;width:100%;' />";
                        $(".body-pago-form").html(data.data).removeClass("d-none");
                        $(".hrFiserv").addClass("d-none");
                        $("#comments").val(JSON.stringify(_NMF._itemsPagos));
                        if (_visible == 0) {
                            $(".btn-pagar-fiserv").addClass("d-none");
                            $(".tbl-fiserv").addClass("d-none");
                            $(".btn-pagar-fiserv").click();
                        }
                    });
                }
            } else {
                $(".btn-accept-sincargo").removeClass("d-none");
                /* click automatico para solicitar la atención! */
                $(".btn-accept-sincargo").click();
            }
            $(".btn-accept-previa").addClass("d-none");
            $(".body-encuesta").addClass("d-none");
            $(".btn-accept-final").removeClass("d-none");
            $(".body-pago").removeClass("d-none");
        });
        $("body").off("click", ".btn-accept-sincargo").on("click", ".btn-accept-sincargo", function () {
            if (!_TOOLS.validate(".validatePrevia")) {
                alert("¡Complete los datos del formulario!");
                return false;
            };
            _PAY.onPaymentRegistrationTelemedicina({ "apiReference": "Sin cargo" });
        });
        $("body").off("click", ".btn-pagar-fiserv").on("click", ".btn-pagar-fiserv", function (e) {
            var _raw_request = JSON.stringify(_TOOLS.getFormValues(".dataPost", $(this)));
            var _json = {
                "id_type_channel": 1,
                "identificacion": _NMF._itemsPagos[0]["Identificacion"],
                "status": "INICIADO",
                "currency_request": $("#currency").val(),
                "dni_request": $(".dni_tarjeta").val(),
                "amount_request": $("#chargetotal").val(),
                "card_request": "",
                "raw_request": _raw_request
            };
            _API.UiInitTransactionFiserv(_json).then(function (data) {
                //console.log(data.data.id);
                _PAY._idTransfer_telemedicina = data.data.id;
                _NMF._itemsPagos[0]["idTransfer"] = _PAY._idTransfer_telemedicina;
                $("#comments").val(JSON.stringify(_NMF._itemsPagos));
                $(".body-pago-title").addClass("d-none");
                $(".btn-pagar-fiserv").addClass("d-none");
                $(".iframe_fiserv").removeClass("d-none");
                clearInterval(_PAY._TMR_PAY_TELEMEDICINA);
                _PAY._TMR_PAY_TELEMEDICINA = setInterval(function () {
                    _PAY.onCheckStatusPaymentTelemedicina();
                }, 2000);
                checkoutform.submit();
            });
        });
    },
    onGeneratePayCodeTelemedicina: function (_this) {
        try {
            $(".btn-generate-paycode").addClass("d-none");
            _NMF.onDestroyModal('#telemedicinaPermiso');
            _NMF.onDestroyModal('#telemedicinaPrevia');

            var _raw_data = JSON.stringify(_NMF._auth_user_data);
            _API.UiRegisterConsent({ "type_class": "telemedicina", "raw_data": _raw_data });

            $("body").append(_PAY.onBuildInterfaceTelemedicina());
            $(".imgDoctor").css({ "background-image": "url(./img/MediYa/telemedicina-close.png)", "background-size": "cover" });

            _PAY.onAddEspecialidades(null);

            $(".importe").val(_NMF._session_data.MontoTelemedicina);
            $(".concepto").val("Teleconsulta médica");
            $(".referencia").val(_TOOLS.UUID());
            /*----------------------------------*/
            _PAY.onEventsTelemedicina();
            $(".btn-move-open").click();
            $("#telemedicinaPrevia").modal({ backdrop: false, keyboard: true, show: true });
            return true;
        } catch (rex) {
            _NMF.onAlert({ "class": "alert-danger", "message": rex.message });
            $(".body-wait").addClass("d-none");
            $(".body-pago").removeClass("d-none");
            $(".body-footer").removeClass("d-none");
            return false;
        }
    },
    onPaymentRegistrationTelemedicina: function (response) {
        _NMF.onConnectVideoChat().then(function (data) {
            var _jsonChargesCodes = _TOOLS.getFormValues(".dbaseprevia");
            var _area = $(".area").val();
            var _telefono = $(".phone").val();
            if (_area == undefined) { _area = ""; }
            if (_telefono == undefined) { _telefono = ""; }
            _jsonChargesCodes["code"] = data.id_transaction;
            _jsonChargesCodes["area"] = _area;
            _jsonChargesCodes["telefono"] = _telefono;
            _jsonChargesCodes["telefono_contacto"] = (_area + _telefono);
            _API.UiGeneratePaycode(_jsonChargesCodes).then(function (data) {
                if (data.status == "OK") {
                    if (_NMF._session_data.TeleMedConsultasResto != 0) { _NMF._session_data.TeleMedConsultasResto -= 1; }
                    _html = ("<b>¡Se ha procesado el pago en forma exitosa! Nº de transacción " + response.apiReference + "</b>");
                    _NMF.onEvalCallStatus({ "id_transaction": _jsonChargesCodes["code"] });
                } else {
                    _NMF.onAlert({ "class": "alert-info", "message": data.message });
                }
                $(".btn-cancel-previa").click();
            }).catch(function (error) {
                _NMF.onAlert({ "class": "alert-danger", "message": error });
            });
        });
        $(".body-wait").addClass("d-none");
        $(".body-pago").removeClass("d-none");
    },
    onCheckStatusPaymentTelemedicina: function () {
        /* Consultar status _PAY._idTransfer_telemedicina */
        var _json = {
            "module": "mod_payments",
            "table": "transactions",
            "model": "transactions",
            "page": 1,
            "pagesize": 1,
            "where": ("id=" + _PAY._idTransfer_telemedicina),
            "order": "description ASC",
        };
        _API.UiGet(_json).then(function (datajson) {
            if (datajson.data[0].status != "INICIADO") {
                clearInterval(_PAY._TMR_PAY_TELEMEDICINA);
                if (datajson.data[0].status == "APROBADO") {
                    $(".id_payment").val(0);
                    $(".code_payment").val(_PAY._idTransfer_telemedicina); //id en mod_payments_transactions
                    _PAY.onPaymentRegistrationTelemedicina({ "apiReference": _PAY._idTransfer_telemedicina });
                    _NMF.onAlert({ "class": "alert-success", "message": "Su pago se ha registrado.  Aguarde unos instantes, que será atendido." });
                    setTimeout(function () { $(".push-alert").remove(); }, 10000);
                } else {
                    _NMF.onAlert({ "class": "alert-danger", "message": "Su pago no ha podido ser procesado.  Reintente con otro medio de pago." });
                }

                _NMF.onDestroyModal("#telemedicinaPrevia");
                //console.log(datajson);
            }
        }).catch(function (error) {
            _NMF.onAlert({ "class": "alert-danger", "message": error.message });
        });

    },

    /**
     * /
     * ACTIVACION Y PAGOS LEGALES
     */
    onAddMotivos: function () {
        var _json = {
            "module": "mod_legal",
            "table": "type_request",
            "model": "type_request",
            "page": 1,
            "pagesize": -1,
            "where": "",
            "order": "description ASC",
        };
        _API.UiGet(_json).then(function (datajson) {
            _TOOLS.loadCombo(datajson, { "target": "#id_type_request", "selected": -1, "id": "id", "description": "description", "default": "[Elija motivo de la consulta]" });
        }).catch(function (error) {
            _NMF.onModalAlert("Alerta", error.message, "danger");
        });
    },
    onBuildInterfaceLegales: function () {
        var _html = "<div class='modal fade' id='legalesPrevia' role='dialog'>";
        _html += "       <input type='hidden' class='dbaseprevia id_payment' id='id_payment' name='id_payment' value='0'/>";
        _html += "       <input type='hidden' class='dbaseprevia code_payment' id='code_payment' name='code_payment' value='0'/>";
        _html += "       <input type='hidden' class='dbaseprevia importe_total' id='importe_total' name='importe_total' value='0'/>";
        _html += "       <input type='hidden' id='concepto' name='concepto' value='' class='dbaseprevia concepto'/>";
        _html += "       <input type='hidden' id='referencia' name='referencia' value='' class='dbaseprevia referencia'/>";
        _html += "       <input type='hidden' id='importe' name='importe' value='0' class='dbaseprevia importe'/>";
        _html += " <div class='modal-dialog modal-lg' role='document'>";
        _html += "  <div class='modal-content' style='color:white;background-color:#0047BA;'>";
        _html += "    <div class='modal-header'>";
        _html += "      <div class='inner-title'><b>Consulta legal</b></div>";
        _html += "    </div>";
        _html += "    <div class='modal-body body-encuesta open d-none'>";
        _html += "       <table style='width:100%;'>";
        _html += "          <tr>";
        _html += "             <td><label>Motivo de la consulta</label><br/><select class='form-control id_type_request dbaseprevia validatePrevia' id='id_type_request' name='id_type_request'></select></td>";
        _html += "          </tr>";
        _html += "          <tr>";
        _html += "             <td><label>Teléfono para contacto</label><br/><input type='text' class='form-control text dbaseprevia telefono_contacto validatePrevia' id='telefono_contacto' name='telefono_contacto'/></td>";
        _html += "          </tr>";
        _html += "          <tr>";
        _html += "             <td><label>Denos algunos detalles</label><br/><textarea class='text dbaseprevia motivo_consulta validatePrevia' id='motivo_consulta' name='motivo_consulta' rows='8' style='width:100%;'></textarea></td>";
        _html += "          </tr>";
        _html += "       </table>";
        _html += "    </div>";
        _html += "    <div class='modal-body body-pago body-pago-form d-none'>";
        _html += "    </div>";
        _html += "    <div class='body-footer modal-footer font-weight-light' style='margin-bottom:50px;'>";
        var _lbl1 = "Solicitar";
        var _lbl2 = "Aceptar";
        if (_NMF._session_data.LegalesConsultasResto == 0) { _lbl1 = "Ir a pago"; _lbl2 = "Pagar"; }
        _html += "       <button  class='btn-raised btn-move-open btn btn-info btn-sm openclosemessage'><i class='material-icons'>chevron_right</i></span>Continuar</button>";
        _html += "       <button  class='btn-raised btn-cancel-previa btn btn-danger btn-sm open d-none'><i class='material-icons'>not_interested</i></span>Cancelar</button>";
        _html += "       <button  class='btn-raised btn-accept-previa btn btn-success btn-sm open d-none'><i class='material-icons'>done</i></span>" + _lbl1 + "</button>";
        _html += "       <button  class='btn-raised btn-accept-final btn btn-success btn-sm d-none'><i class='material-icons'>done_all</i></span>" + _lbl2 + "</button>";
        _html += "    </div>";
        _html += "  </div>";
        _html += " </div>";
        _html += "</div>";
        return _html;
    },
    onEventsLegales: function () {
        $("body").off("click", ".btn-move-open").on("click", ".btn-move-open", function () {
            $(".openclosemessage").addClass("d-none");
            $(".open").removeClass("d-none");
        });
        $("body").off("click", ".btn-cancel-previa").on("click", ".btn-cancel-previa", function () {
            _NMF.onDestroyModal("#legalesPrevia");
        });
        $("body").off("click", ".btn-accept-previa").on("click", ".btn-accept-previa", function () {
            if (!_TOOLS.validate(".validatePrevia")) {return false;};
            $(".body-pago-form").html("");
            if (_NMF._session_data.LegalesConsultasResto == 0) {
                /*Aca va la implementacion de pago!*/
                _NMF.onModalAlert("Alerta", "¡Próximamente se podrá efectuar el pago!", "info");
            }
            $(".btn-accept-previa").addClass("d-none");
            $(".body-encuesta").addClass("d-none");
            $(".btn-accept-final").removeClass("d-none");
            $(".body-pago").removeClass("d-none");
            /*No last step - */
            $(".btn-accept-final").click();
        });
        $("body").off("click", ".btn-accept-final").on("click", ".btn-accept-final", function () {
            $(".msg-legales-status").removeClass("d-none");
            if (_NMF._session_data.LegalesConsultasResto != 0) {
                $(".id_payment").val(0);
                $(".code_payment").val("Sin cargo");
                $(".importe_total").val(0);
                $(".body-pago").addClass("d-none");
                $(".body-footer").addClass("d-none");
                $(".body-wait").removeClass("d-none");
                _NMF.onModalAlert("Info", "<b>¡Se ha ingresado su solicitud en forma exitosa!</b>", "info");
                setTimeout(function () {
                    _PAY.onPaymentRegistrationLegal({ "apiReference": "Sin cargo" }).then(function (data) {
                        if (data.status == "OK") {
                            //if (_NMF._session_data.LegalesConsultasResto != 0) { _NMF._session_data.LegalesConsultasResto -= 1; }
                        } else {
                            _NMF.onModalAlert("Alerta", data.message, "info");
                        }
                    }).catch(function (err) {
                        alert("err");
                        console.log(err);
                    });
                }, 2000);
            }
        });
    },
    onGeneratePaycodeLegal: function (_this) {
        if (_NMF._session_data.ClubRedondo == 0) {
            _NMF.onIsNotSocio("Debe ser socio para solicitar asesoría legal.");
            return false;
        }
        try {
            _NMF.onDestroyModal('#legalesPrevia');
            $("body").append(_PAY.onBuildInterfaceLegales());
            _PAY.onAddMotivos();

            /*Set payment values and description*/
            $(".importe").val(_NMF._session_data.MontoLegales);
            $(".concepto").val("Consulta legal");
            $(".referencia").val(_TOOLS.UUID());
            /*----------------------------------*/
            _PAY.onEventsLegales();
            $(".btn-move-open").click();
            $("#legalesPrevia").modal({ backdrop: false, keyboard: true, show: true });
            return true;
        } catch (rex) {
            _NMF.onModalAlert("Alerta", rex.message, "danger");
            $(".body-wait").addClass("d-none");
            $(".body-pago").removeClass("d-none");
            $(".body-footer").removeClass("d-none");
            return false;
        }
    },
    onPaymentRegistrationLegal: function (response) {
        /* En _jsonChargesCodes se almacena los valores de la transacción a enviar al server propio.*/
        var _jsonChargesCodes = _TOOLS.getFormValues(".dbaseprevia");
        _API.UiGeneratePaycodeLegal(_jsonChargesCodes).then(function (data) {
            if (data.status == "OK") {
                //if (_NMF._session_data.LegalesConsultasResto != 0) { _NMF._session_data.LegalesConsultasResto -= 1; }
                _NMF.onModalAlert("Info", "<b>¡Se ha procesado el pago en forma exitosa!</b>", "info");
            } else {
                _NMF.onModalAlert("Alerta", data.message, "info");
            }
            $(".btn-cancel-previa").click();
        }).catch(function (error) {
            _NMF.onModalAlert("Alerta", error, "danger");
        });
        $(".body-wait").addClass("d-none");
        $(".body-pago").removeClass("d-none");
    },
};



