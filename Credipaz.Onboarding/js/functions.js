var _F = {
    /*
     * Data capture structure
     * */
    _titleInitial:"",
    _intranet: false,
    _TIMER_LAZY:0,
    _CreditData: null,
    _ClientData: {
        "dirty": false,
        "msg_status": "",
        "comprobanteIngresos": "",
        "img_firma": "",
        "codigo_sms_a_verificar": "",
        "codigo_sms": "",
        "link_extract": "",
        "link_certificate": "",
        "_verifyData": {},
        "_solicitudData": {},
    },
    /*
     * Controlinterface properties
     * */
    _lastMessage:"",
    _objStack: [],
    _keyStack: [],
    _iPage: null,
    _activeImage: 0,
    _dataNode: "",
    /*
     * General interface functions 
     * */
    onGetLocation: function () {
        return new Promise(
            function (resolve, reject) {
                try {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function (position) { resolve({ "latitude": position.coords.latitude, "longitude": position.coords.longitude }); },
                            function (err) { resolve({ "latitude": 0, "longitude": 0 }); }
                        );
                    } else{
                        resolve({ "latitude": 0, "longitude": 0 });
                    }
                    
                } catch (rex) {
                    reject({ "latitude": 0, "longitude": 0 });
                }
            });
    },
    onTryPageAbstract: function (_id) {
        return new Promise(
            function (resolve, reject) {
                try {
                    var o = new Object();
                    o.activeLog = true;
                    o.ID = _id;
                    o.FUNCTIONS = null;
                    o.LOADED = false;
                    o.onShow = function () {
                        var oLocal = this;
                        $.getScript("js/pages/" + oLocal.ID + ".js", function () {
                            if (!oLocal.LOADED) {
                                _AJAX_deprecated.Load("./pages/" + oLocal.ID + ".html").then(function (data) {
                                    oLocal.LOADED = true;
                                    $(".app").append(data);
                                    $("." + oLocal.ID).removeClass("d-none").addClass("d-flex");
                                    oLocal.FUNCTIONS = _fnc.getReference();
                                    if ($.isFunction(oLocal.FUNCTIONS.onShow)) { oLocal.FUNCTIONS.onShow(); }
                                });
                            } else {
                                $("." + oLocal.ID).removeClass("d-none").addClass("d-flex");
                                oLocal.FUNCTIONS = _fnc.getReference();
                                if ($.isFunction(oLocal.FUNCTIONS.onShow)) { oLocal.FUNCTIONS.onShow(); }
                            }
                        });
                    };
                    o.onHide = function () {
                        $("." + this.ID).addClass("d-none");
                        if ($.isFunction(this.FUNCTIONS.onHide)) { this.FUNCTIONS.onHide(); }
                    };
                    resolve(o);
                } catch (rex) {
                    reject(rex);
                }
            })
    },
    onTryPage: function (_this, _id) {
        return new Promise(
            function (resolve, reject) {
                try {
                    if (_id == undefined) { return false; }
                    $(".nav-link").removeClass("active").removeClass("text-primary");
                    if (_this != null && _this.hasClass("nav-link")) { _this.addClass("active").addClass("text-primary"); }

                    var _iLast = _F._iPage;
                    if (_F._iPage != null) { _F._iPage.onHide(); }
                    _F._iPage = _F.onTryStack(_id);
                    if (_F._iPage == null) {
                        _F.onTryPageAbstract(_id).then(function (o) {
                            _F._iPage = o;
                            _F._objStack.push(_F._iPage);
                            _F._iPage.onShow();
                            resolve(null);
                        });
                    } else {
                        _F._iPage.onShow();
                        resolve(null);
                    }
                    try { if (String(_iLast.ID) != String(_id)) { _F._keyStack.push(_id); } } catch (err) { };
                    setTimeout(_F.onConfigureApplication, 250);
                } catch (rex) {
                    reject(rex);
                }
            })
    },
    onConfigureApplication: function () {
        history.replaceState('', 'Credipaz', '/'); 
    },
    onTryStack: function (_id) {
        var _ret = null;
        for (var i = 0; i < _F._objStack.length; i++) {
            if (_F._objStack[i].ID == _id) {
                _ret = _F._objStack[i];
                break;
            }
        }
        return _ret;
    },
    onDestroyModal: function (_target) {
        $(_target).remove();
        $(".modal-backdrop").remove();
        $("body").removeClass("modal-open");
    },
    onModalInfo: function (_title, _body, _class) {
        if (_class == undefined) { _class = "info"; }
        _F.onDestroyModal("#alterModalInfo");
        var _html = "<div class='modal fade' id='alterModalInfo' role='dialog'>";
        _html += " <div class='modal-dialog modal-dialog-centered' role='document'>";
        _html += "  <div class='modal-content'>";
        _html += "    <div class='modal-header text-" + _class + "'>";
        _html += "      <h2 class='modal-title'>" + _title + "</h2>";
        _html += "    </div>";
        _html += "    <div class='modal-body'>";
        _html += _body;
        _html += "    </div>";
        _html += "  </div>";
        _html += " </div>";
        _html += "</div>";
        $("body").append(_html);
        $("#alterModalInfo").modal({ backdrop: true, keyboard: true, show: true });
        return true;
    },
    onModalAlert: function (_title, _body, _class) {
        if (_class == undefined) { _class = "info"; }
        _F.onDestroyModal("#alterModal");
        var _html = "<div class='modal fade' id='alterModal' role='dialog'>";
        _html += " <div class='modal-dialog' role='document'>";
        _html += "  <div class='modal-content'>";
        _html += "    <div class='modal-header text-" + _class + "'>";
        _html += "      <h2 class='modal-title'>" + _title + "</h2>";
        _html += "    </div>";
        _html += "    <div class='modal-body'>";
        _html += _body;
        _html += "    </div>";
        _html += "    <div class='modal-footer font-weight-light'>";
        _html += "       <button type='button' class='btn-raised btn btn-cancel-alert btn-" + _class + " btn-sm'><i class='material-icons'>done</i></span>Aceptar</button>";
        _html += "    </div>";
        _html += "  </div>";
        _html += " </div>";
        _html += "</div>";
        $("body").append(_html);
        $("body").off("click", ".btn-cancel-alert").on("click", ".btn-cancel-alert", function () {
            _F.onDestroyModal("#alterModal");
        });
        $("#alterModal").modal({ backdrop: true, keyboard: true, show: true });
        return true;
    },
    onSeeRequest: function (_this) {
        _F.onSolicitudBuilder().then(function () {
            _F.onDestroyModal("#requestModal");
            var _html = "<div class='modal fade' id='requestModal' role='dialog'>";
            _html += " <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>";
            _html += "  <div class='modal-content'>";
            _html += ("    <div class='modal-body'>" + _T.b64_to_utf8(_F._ClientData._solicitudData.pdf_solicitud) + "</div>");
            _html += "    <div class='modal-footer font-weight-light'>";
            _html += "       <button type='button' class='btn-raised btn btn-cancel-request btn-secondary btn-sm'><i class='material-icons'>done</i></span>Cerrar</button>";
            _html += "    </div>";
            _html += "  </div>";
            _html += " </div>";
            _html += "</div>";
            $("body").append(_html);
            $("body").off("click", ".btn-cancel-request").on("click", ".btn-cancel-request", function () {
                _F.onDestroyModal("#requestModal");
            });
            $("#requestModal").modal({ backdrop: true, keyboard: true, show: true });
        });
        return true;
    },
    onSeeRequestPagare: function (_this) {
        _F.onPagareBuilder().then(function (_data) {
            _F.onDestroyModal("#requestModal");
            var _html = "<div class='modal fade' id='requestModal' role='dialog'>";
            _html += " <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>";
            _html += "  <div class='modal-content'>";
            _html += ("    <div class='modal-body'>" + _T.b64_to_utf8(_data) + "</div>");
            _html += "    <div class='modal-footer font-weight-light'>";
            _html += "       <button type='button' class='btn-raised btn btn-cancel-request btn-secondary btn-sm'><i class='material-icons'>done</i></span>Cerrar</button>";
            _html += "    </div>";
            _html += "  </div>";
            _html += " </div>";
            _html += "</div>";
            $("body").append(_html);
            $("body").off("click", ".btn-cancel-request").on("click", ".btn-cancel-request", function () {
                _F.onDestroyModal("#requestModal");
            });
            $("#requestModal").modal({ backdrop: true, keyboard: true, show: true });
        });
        return true;
    },
    onSeeRequestAmutra: function (_this) {
        _F.onAmutraBuilder().then(function (_data) {
            _F.onDestroyModal("#requestModal");
            var _html = "<div class='modal fade' id='requestModal' role='dialog'>";
            _html += " <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>";
            _html += "  <div class='modal-content'>";
            _html += ("    <div class='modal-body'>" + _T.b64_to_utf8(_data) + "</div>");
            _html += "    <div class='modal-footer font-weight-light'>";
            _html += "       <button type='button' class='btn-raised btn btn-cancel-request btn-secondary btn-sm'><i class='material-icons'>done</i></span>Cerrar</button>";
            _html += "    </div>";
            _html += "  </div>";
            _html += " </div>";
            _html += "</div>";
            $("body").append(_html);
            $("body").off("click", ".btn-cancel-request").on("click", ".btn-cancel-request", function () {
                _F.onDestroyModal("#requestModal");
            });
            $("#requestModal").modal({ backdrop: true, keyboard: true, show: true });
        });
        return true;
    },

    /*
     * Interface step by step validation
     */
    onValidateStep: function (_this) {
        var _stage = _this.attr("data-scope");
        $("input").attr("disabled", false);
        try {
            _AJAX_deprecated._responseTitle = "Gracias por brindarnos la información solicitada";
            _AJAX_deprecated._responseMessage = "Vamos a contactarte a la brevedad para terminar el proceso de emisión";
            _F.onTryPage(null, "msg-ok");
        } catch (err) {
            _F.onModalAlert("Alerta", err, "warning");
        }
    },

    /*
     * Foto interface builder
     */
    onCameraOn: function (_this) {
        var _id = _this.attr("data-id");
        _F._activeImage = _this.attr("data-id");
        _F._dataNode = _this.attr("data-node");
        $("#camera").click();
    },
    onGetBase64: function (file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
        });
    },
    onClearPictures: function () {
        _F._ClientData._solicitudData.img_foto_cara = "";
        _F._ClientData._solicitudData.img_comprobante_servicio = "";
        _F._ClientData._solicitudData.img_comprobante_ingreso = "";
        _F._ClientData._solicitudData.img_dni_frente = "";
        _F._ClientData._solicitudData.img_dni_dorso = "";
    },
    onCameraChange: function (_this, e) {
        _F.onGetBase64(e.target.files[0]).then(function (data) {
            _F.onClearPictures();
            $.blockUI({ message: "<img src='./img/wait.gif' style='width:100px;' />", css: { width: '100px', border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
            _F._ClientData._solicitudData[_F._dataNode] = data;
            data = null;
            $(".btn-footer").removeClass("d-none");
            /*Save iface data to requests*/
            _F._ClientData._solicitudData.controlPoint = _F._dataNode;
            _API_deprecated.UiOnboardingSaveRequest(_F._ClientData._solicitudData).then(function (_response) {
                $.unblockUI();
                _F.onClearPictures();
                $(".btnValidationStep").click();
            }).catch(function (err) {
                alert("¡No se pudo enviar la foto al servidor!");
            });
        });
    },
    onPagePhoto: function (_id, _description, _custom, _scope, _node) {
        var _html = "";
        _html += "<div class='hideable-" + _id + " card-deck area-documents text-center mt-5 p-0'>";
        _html += "  <div data-id='" + _id + "' class='p-0 m-0 card text-center card-image card-image-" + _id + "'>";
        _html += "    <div class='card-body p-0 m-0'>";
        _html += "      <div class='row p-0 m-0'>";
        _html += "         <div class='col-12 text-center mt-2'><h4>" + _description + "</h4></div>";
        _html += "         <div class='col-12 text-center mt-2'><h5>" + _custom + "</h5></div>";
        _html += "         <div class='col-12 text-center mt-2'>";
        _html += "            <a href='#' data-node='" + _node + "' data-id='" + _id + "' class='btn btn-primary btnCamera btn-sm'>";
        _html += "               <span style='font-size:2rem;' class='material-icons'>photo_camera</span>";
        _html += "            </a>";
        _html += "         </div>";
        _html += "         <div class='col-12 text-center'><img class='d-none card-img img-" + _id + " card-img-top' src='./img/placeholder.png' style='width:100%;'></div>";
        _html += "         <div class='col-12 text-left mt-2'>";
        _html += "            <ul>";
        _html += "               <li>Desactivá el flash</li>"
        _html += "               <li>Verificá que la imagen se vea nítida</li>"
        _html += "               <li>Alineá usando las líneas de guía</li>"
        _html += "            </ul>";
        _html += "         </div>";
        _html += "      </div>";
        _html += "    </div>";
        _html += "  </div>";
        _html += "</div>";
        $(".foto-" + _id).html(_html);
        if (_AJAX_deprecated._monopage) { $(".btnBack").remove(); }
    },
    onDrawQRUrl: function (_page, _forced) {
        var _msg = "";
        $("#qrcode").html("").addClass("d-none");
        if (!_T.isMobileDevice() || _forced) {
            var _params = ("verificated=" + _F._ClientData._solicitudData.id + "&monopage=" + _page);
            var _url = (window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "?" + _params);
            new QRCode(document.getElementById("qrcode"), _url);
            var _msg = "<span class='mt-4 badge badge-secondary'>Retomar desde este punto</span>";
            _msg = "<span class='mt-4 badge badge-dark'>Continuar desde el móvil</span>";
            $("#qrcode").append(_msg).removeClass("d-none");
        }
    },
    onSetSolicitudData: function (data) {
        try {
            /*
             * Full WS Financial original response!
             * */
            if (data.data.raw_verify == null || data.data.raw_verify=="") {
                _F._ClientData._solicitudData.raw_verify = {};
            } else {
                _F._ClientData._solicitudData.raw_verify = JSON.parse(data.data.raw_verify);
                _F._ClientData._solicitudData.raw_verify = _F._ClientData._solicitudData.raw_verify["0"];
            }
            /*
             * Datos del credito, de la solicitud y entorno
             * */
            _F._ClientData._solicitudData.id = data.data.id;
            _F._ClientData._solicitudData.sqIdemia = data.data.SQidemia;
            if (data.data.id_type_modo_pago == null) { data.data.id_type_modo_pago = 0; }
            _F._ClientData._solicitudData.id_type_modo_pago = data.data.id_type_modo_pago;
            _F._ClientData._solicitudData.NroSolicitud = data.data.idSolicitudCredito;
            _F._ClientData._solicitudData.controlPoint = data.data.controlPoint;
            _F._ClientData._solicitudData.permiteNuevo = parseInt(data.data.permiteNuevo);
            _F._ClientData._solicitudData.permiteRenovacion = parseInt(data.data.permiteRenovacion);
            _F._ClientData._solicitudData.min = data.data.min;
            _F._ClientData._solicitudData.max = data.data.max;
            _F._ClientData._solicitudData.default = data.data.default;
            _F._ClientData._solicitudData.Capital = data.data.Capital;
            _F._ClientData._solicitudData.importe = data.data.importe;
            _F._ClientData._solicitudData.importeLetras = data.data.importeLetras;
            _F._ClientData._solicitudData.fechaEmisionCompleta = data.data.fechaEmisionCompleta;
            _F._ClientData._solicitudData.cuotas = data.data.cuotas;
            _F._ClientData._solicitudData.tasa = data.data.tasa;
            _F._ClientData._solicitudData.idplan = data.data.idplan;
            _F._ClientData._solicitudData.idcomercio = data.data.idcomercio;
            _F._ClientData._solicitudData.TNA = data.data.TNA;
            _F._ClientData._solicitudData.TEA = data.data.TEA;
            _F._ClientData._solicitudData.CFTNA = data.data.CFTNA;
            _F._ClientData._solicitudData.CFTEA = data.data.CFTEA;
            _F._ClientData._solicitudData.fechavto1 = data.data.fechavto1;
            _F._ClientData._solicitudData.monto = data.data.monto;
            /*
             * Datos generales del solicitante
             **/
            _F._ClientData._solicitudData.IdCliente = data.data.idCliente;
            _F._ClientData._solicitudData.EstadoCivil = data.data.EstadoCivil;
            _F._ClientData._solicitudData.Nacionalidad = data.data.Nacionalidad;
            _F._ClientData._solicitudData.Sucursal = data.data.Sucursal;

            _F._ClientData._solicitudData.Apellido = data.data.Apellido;
            _F._ClientData._solicitudData.Nombre = data.data.Nombre;
            _F._ClientData._solicitudData.Tipo = data.data.Tipo;
            _F._ClientData._solicitudData.Documento = data.data.Documento;
            _F._ClientData._solicitudData.Sexo = data.data.Sexo;
            _F._ClientData._solicitudData.Email = data.data.Email;
            _F._ClientData._solicitudData.prefijoTelefono = data.data.prefijoTelefono;
            _F._ClientData._solicitudData.Telefono = data.data.Telefono;
            _F._ClientData._solicitudData.prefijoTelefonoAlt = data.data.prefijoTelefonoAlt;
            _F._ClientData._solicitudData.TelefonoAlt = data.data.TelefonoAlt;
            _F._ClientData._solicitudData.Nacionalidad = data.data.Nacionalidad;
            if (data.data.FechaNacimiento != null) { _F._ClientData._solicitudData.FechaNacimiento = data.data.FechaNacimiento.split(" ")[0]; }
            _F._ClientData._solicitudData.EstadoCivil = data.data.EstadoCivil;
            _F._ClientData._solicitudData.Calle = data.data.Calle;
            _F._ClientData._solicitudData.Numero = data.data.Numero;
            _F._ClientData._solicitudData.Piso = data.data.Piso;
            _F._ClientData._solicitudData.Departamento = data.data.Departamento;
            _F._ClientData._solicitudData.CodigoPostal = data.data.CodigoPostal;
            _F._ClientData._solicitudData.EntreCalles = data.data.EntreCalles;
            _F._ClientData._solicitudData.Barrio = data.data.Barrio;
            _F._ClientData._solicitudData.Localidad = data.data.Localidad;
            _F._ClientData._solicitudData.Provincia = data.data.Provincia;
            _F._ClientData._solicitudData.ProvinciaDesc = data.data.ProvinciaDesc;
            _F._ClientData._solicitudData.Vivienda = data.data.Vivienda;
            _F._ClientData._solicitudData.iva = data.data.iva;
            _F._ClientData._solicitudData.cuil = data.data.cuil;
            _F._ClientData._solicitudData.Ocupacion = data.data.Ocupacion;
            _F._ClientData._solicitudData.RazonSocial = data.data.RazonSocial;
            _F._ClientData._solicitudData.cuit = data.data.cuit;
            _F._ClientData._solicitudData.Seccion = data.data.Seccion;
            _F._ClientData._solicitudData.Legajo = data.data.Legajo;
            _F._ClientData._solicitudData.Cargo = data.data.Cargo;
            _F._ClientData._solicitudData.Rubro = data.data.Rubro;
            _F._ClientData._solicitudData.IngresoMensual = data.data.IngresoMensual;
            if (data.data.FechaIngreso != null) { _F._ClientData._solicitudData.FechaIngreso = data.data.FechaIngreso.split(" ")[0]; }
            _F._ClientData._solicitudData.Antiguedad = data.data.Antiguedad;
            _F._ClientData._solicitudData.CalleEmpresa = data.data.CalleEmpresa;
            _F._ClientData._solicitudData.NumeroEmpresa = data.data.NumeroEmpresa;
            _F._ClientData._solicitudData.PisoEmpresa = data.data.PisoEmpresa;
            _F._ClientData._solicitudData.DepartamentoEmpresa = data.data.DepartamentoEmpresa;
            _F._ClientData._solicitudData.CodigoPostalEmpresa = data.data.CodigoPostalEmpresa;
            _F._ClientData._solicitudData.EntreCallesEmpresa = data.data.EntreCallesEmpresa;
            _F._ClientData._solicitudData.LocalidadEmpresa = data.data.LocalidadEmpresa;
            _F._ClientData._solicitudData.ProvinciaEmpresa = data.data.ProvinciaEmpresa;
            _F._ClientData._solicitudData.ProvinciaEmpresaDesc = data.data.ProvinciaEmpresaDesc;
            _F._ClientData._solicitudData.prefijoTelefonoEmpresa = data.data.prefijoTelefonoEmpresa;
            _F._ClientData._solicitudData.TelefonoEmpresa = data.data.TelefonoEmpresa;
            _F._ClientData._solicitudData.prefijoTelefonoAltEmpresa = data.data.prefijoTelefonoAltEmpresa;
            _F._ClientData._solicitudData.TelefonoAltEmpresa = data.data.TelefonoAltEmpresa;
            _F._ClientData._solicitudData.pdf_solicitud = data.data.pdf_solicitud;
            _F._ClientData._solicitudData.img_additional = data.data.img_additional;
            _F._ClientData._solicitudData.CBU = data.data.CBU;
            _F._ClientData._solicitudData.id_type_request = data.data.id_type_request;

            _F._ClientData._solicitudData.Capital = data.data.Capital;
            _F._ClientData._solicitudData.Cuenta = "N/A";
            _F._ClientData._solicitudData.Pago_Total_Cierre = data.data.Pago_Total_Cierre;
            _F._ClientData._solicitudData.Deuda_Futura_Cierre = data.data.Deuda_Futura_Cierre;
            _F._ClientData._solicitudData.Deuda_Total_Cierre = data.data.Deuda_Total_Cierre;
            _F._ClientData._solicitudData.Pagos_Periodo = data.data.Pagos_Periodo;
            _F._ClientData._solicitudData.Consumos_Periodo = data.data.Consumos_Periodo;
            _F._ClientData._solicitudData.created = data.data.created;
        } catch (ex) {
            console.log(ex);
            alert("Error registrando datos de solicitud");
        }
    },
    onSetVal: function (target) {
        $("." + target).val(_F._ClientData._solicitudData[target]);
    },
    onSolicitudBuilder: function () {
        return new Promise(
            function (resolve, reject) {
                _AJAX_deprecated._BPAM["Formulario"] = "SOLICITUDPRODUCTO";
                _AJAX_deprecated._BPAM["ValueForRetrieve"] = _F._ClientData._solicitudData.id_type_request;
                _API_deprecated.UiGetFormulario(_AJAX_deprecated._BPAM).then(function (_ret) {
                    try {
                        var data = _T.b64_to_utf8(_ret.message.mensaje);
                        /*
                         * Reemplazar valores en solicitud
                         * */
                        data = _T.tagReplace(data, /\[APELLIDO\]/g, _F._ClientData._solicitudData.Apellido);
                        data = _T.tagReplace(data, /\[NOMBRE\]/g, _F._ClientData._solicitudData.Nombre);
                        data = _T.tagReplace(data, /\[DNI\]/g, _F._ClientData._solicitudData.Documento);
                        data = _T.tagReplace(data, /\[CALLE\]/g, _F._ClientData._solicitudData.Calle);
                        data = _T.tagReplace(data, /\[NRO\]/g, _F._ClientData._solicitudData.Numero);
                        data = _T.tagReplace(data, /\[PISO\]/g, _F._ClientData._solicitudData.Piso);
                        data = _T.tagReplace(data, /\[DEPTO\]/g, _F._ClientData._solicitudData.Departamento);
                        data = _T.tagReplace(data, /\[LOCALIDAD\]/g, _F._ClientData._solicitudData.Localidad);
                        data = _T.tagReplace(data, /\[PROVINCIA\]/g, _F._ClientData._solicitudData.ProvinciaDesc);
                        data = _T.tagReplace(data, /\[CODIGOPOSTAL\]/g, _F._ClientData._solicitudData.CodigoPostal);
                        data = _T.tagReplace(data, /\[CELULAR\]/g, (_F._ClientData._solicitudData.prefijoTelefono + " " + _F._ClientData._solicitudData.Telefono));
                        if (_F._ClientData._solicitudData.Email != null) { data = _T.tagReplace(data, /\[EMAIL\]/g, _F._ClientData._solicitudData.Email); }
                        data = _T.tagReplace(data, /\[CAPITAL\]/g, _T.toCurr(_F._ClientData._solicitudData.Capital));
                        data = _T.tagReplace(data, /\[IMPORTE\]/g, _T.toCurr(_F._ClientData._solicitudData.importe));
                        data = _T.tagReplace(data, /\[CUOTAS\]/g, _F._ClientData._solicitudData.cuotas);

                        data = _T.tagReplace(data, /\[TNA\]/g, _F._ClientData._solicitudData.TNA + "%");
                        data = _T.tagReplace(data, /\[TEA\]/g, _F._ClientData._solicitudData.TEA + "%");
                        data = _T.tagReplace(data, /\[CFTNA\]/g, _F._ClientData._solicitudData.CFTNA + "%");
                        data = _T.tagReplace(data, /\[CFTEA\]/g, _F._ClientData._solicitudData.CFTEA + "%");

                        if (_F._ClientData._solicitudData.IdCliente != "") {
                            data = _T.tagReplace(data, /\[SUCURSAL\]/g, _F._ClientData._solicitudData.Sucursal);
                            if (_F._ClientData._solicitudData.IdCliente != null) { data = _T.tagReplace(data, /\[LEGAJO\]/g, _F._ClientData._solicitudData.IdCliente); }
                            if (_F._ClientData._solicitudData.Nacionalidad != null) { data = _T.tagReplace(data, /\[NACIONALIDAD\]/g, _F._ClientData._solicitudData.Nacionalidad); }
                            if (_F._ClientData._solicitudData.EstadoCivil != null) { data = _T.tagReplace(data, /\[ESTADOCIVIL\]/g, _F._ClientData._solicitudData.EstadoCivil); }
                        }

                        switch (parseInt(_F._ClientData._solicitudData.Tipo)) {
                            case 566://REfinanciacion
                                data = _T.tagReplace(data, /\[MONTO\]/g, _T.toFormat(_F._ClientData._solicitudData.Capital));
                                data = _T.tagReplace(data, /\[CUENTA\]/g, _F._ClientData._solicitudData.Cuenta);
                                data = _T.tagReplace(data, /\[PAGO_TOTAL_ULTIMO_RESUMEN\]/g, _T.toFormat(_F._ClientData._solicitudData.Pago_Total_Cierre));
                                data = _T.tagReplace(data, /\[DEUDA_A_VENCER_ULTIMO_RESUMEN\]/g, _T.toFormat(_F._ClientData._solicitudData.Deuda_Futura_Cierre));
                                data = _T.tagReplace(data, /\[DEUDA_TOTAL_EXIGIDA\]/g, _T.toFormat(_F._ClientData._solicitudData.Deuda_Total_Cierre));
                                data = _T.tagReplace(data, /\[PAGOS_EFECTUADOS_MES\]/g, _T.toFormat(_F._ClientData._solicitudData.Pagos_Periodo));
                                data = _T.tagReplace(data, /\[CONSUMOS_EFECTUADOS_PERIODO\]/g, _T.toFormat(_F._ClientData._solicitudData.Consumos_Periodo));
                                data = _T.tagReplace(data, /\[FECHAVTO1\]/g, _F._ClientData._solicitudData.created);

                                break;
                            default:
                                data = _T.tagReplace(data, /\[MONTO\]/g, _T.toCurr(_F._ClientData._solicitudData.monto));
                                break;
                        }

                        _F._ClientData._solicitudData.pdf_solicitud = _T.utf8_to_b64(data);
                        resolve(_F._ClientData._solicitudData.pdf_solicitud);
                    } catch (err) {
                        reject(err);
                    }
                });
            });
    },
    onPagareBuilder: function () {
        return new Promise(
            function (resolve, reject) {
                _AJAX_deprecated._BPAM["Formulario"] = "PAGARECREDITO";
                _AJAX_deprecated._BPAM["ValueForRetrieve"] = _F._ClientData._solicitudData.id_type_request;
                _API_deprecated.UiGetFormulario(_AJAX_deprecated._BPAM).then(function (_ret) {
                    try {
                        var data = _T.b64_to_utf8(_ret.message.mensaje);
                        /*
                         * Reemplazar valores en solicitud
                         * */
                        data = _T.tagReplace(data, /\[APELLIDO\]/g, _F._ClientData._solicitudData.Apellido);
                        data = _T.tagReplace(data, /\[NOMBRE\]/g, _F._ClientData._solicitudData.Nombre);
                        data = _T.tagReplace(data, /\[DNI\]/g, _F._ClientData._solicitudData.Documento);
                        data = _T.tagReplace(data, /\[CALLE\]/g, _F._ClientData._solicitudData.Calle);
                        data = _T.tagReplace(data, /\[NRO\]/g, _F._ClientData._solicitudData.Numero);
                        data = _T.tagReplace(data, /\[PISO\]/g, _F._ClientData._solicitudData.Piso);
                        data = _T.tagReplace(data, /\[DEPTO\]/g, _F._ClientData._solicitudData.Departamento);
                        data = _T.tagReplace(data, /\[LOCALIDAD\]/g, _F._ClientData._solicitudData.Localidad);
                        data = _T.tagReplace(data, /\[PROVINCIA\]/g, _F._ClientData._solicitudData.ProvinciaDesc);
                        data = _T.tagReplace(data, /\[CODIGOPOSTAL\]/g, _F._ClientData._solicitudData.CodigoPostal);
                        data = _T.tagReplace(data, /\[IMPORTE\]/g, _T.toCurr(_F._ClientData._solicitudData.importe));
                        data = _T.tagReplace(data, /\[IMPORTELETRAS\]/g, _T.toCurr(_F._ClientData._solicitudData.importeLetras));
                        data = _T.tagReplace(data, /\[CELULAR\]/g, (_F._ClientData._solicitudData.prefijoTelefono + " " + _F._ClientData._solicitudData.Telefono));
                        data = _T.tagReplace(data, /\[FECHAEMISION\]/g, _F._ClientData._solicitudData.fechaEmisionCompleta);
                        resolve(_T.utf8_to_b64(data));
                    } catch (err) {
                        reject(err);
                    }
                });
            });
    },
    onAmutraBuilder: function () {
        return new Promise(
            function (resolve, reject) {
                _AJAX_deprecated._BPAM["Formulario"] = "SOLICITUDAMUTRA";
                _AJAX_deprecated._BPAM["ValueForRetrieve"] = _F._ClientData._solicitudData.id_type_request;
                _API_deprecated.UiGetFormulario(_AJAX_deprecated._BPAM).then(function (_ret) {
                    try {
                        var data = _T.b64_to_utf8(_ret.message.mensaje);
                        /*
                         * Reemplazar valores en solicitud
                         * */
                        data = _T.tagReplace(data, /\[APELLIDO\]/g, _F._ClientData._solicitudData.Apellido);
                        data = _T.tagReplace(data, /\[NOMBRE\]/g, _F._ClientData._solicitudData.Nombre);
                        data = _T.tagReplace(data, /\[DNI\]/g, _F._ClientData._solicitudData.Documento);
                        data = _T.tagReplace(data, /\[CALLE\]/g, _F._ClientData._solicitudData.Calle);
                        data = _T.tagReplace(data, /\[NRO\]/g, _F._ClientData._solicitudData.Numero);
                        data = _T.tagReplace(data, /\[PISO\]/g, _F._ClientData._solicitudData.Piso);
                        data = _T.tagReplace(data, /\[DEPTO\]/g, _F._ClientData._solicitudData.Departamento);
                        data = _T.tagReplace(data, /\[LOCALIDAD\]/g, _F._ClientData._solicitudData.Localidad);
                        data = _T.tagReplace(data, /\[PROVINCIA\]/g, _F._ClientData._solicitudData.ProvinciaDesc);
                        data = _T.tagReplace(data, /\[CODIGOPOSTAL\]/g, _F._ClientData._solicitudData.CodigoPostal);
                        data = _T.tagReplace(data, /\[CELULAR\]/g, (_F._ClientData._solicitudData.prefijoTelefono + " " + _F._ClientData._solicitudData.Telefono));
                        data = _T.tagReplace(data, /\[FECHAEMISION\]/g, _F._ClientData._solicitudData.fechaEmisionCompleta);
                        resolve(_T.utf8_to_b64(data));
                    } catch (err) {
                        reject(err);
                    }
                });
            });
    },
    onEmitirProducto: function (_img_additional) {
        /*Final call for close process and create request for credit*/
        _F.onModalInfo("Emisión de producto", "Por favor, aguarde que el proceso finalice.  Puede demorar unos minutos, no salga de esta página.", "info"); 
        _F.onSolicitudBuilder().then(function () {
            _API_deprecated.UiOnboardingFinalRequest({ "pdf_solicitud": _F._ClientData._solicitudData.pdf_solicitud, "img_additional": _img_additional, "id": _AJAX_deprecated._KEY, "lat": _AJAX_deprecated._BPAM["latitude"], "lng": _AJAX_deprecated._BPAM["longitude"] }).then(function (data) {
                _F._CreditData = data.data.message;
                $(".btnCancelProcess").addClass("d-none");
                _F._ClientData.link_extract = data.link_extract;
                _F._ClientData.link_certificate = data.link_certificate;
                _F.onDestroyModal("#alterModalInfo");
                _AJAX_deprecated._responseTitle = "";
                _AJAX_deprecated._responseMessage = "Se ha registrado la firma correctamente";
                _F.onTryPage(null, "msg-firmado");
            }).catch(function (err) {
                _F.onDestroyModal("#alterModalInfo");
                _AJAX_deprecated._responseMessage = err.message;
                _F.onTryPage(null, "msg-error");
            });
        });
    },

    /*
     * Firma de documentos adHoc
     */
    onBuildFirmaDocumento: function (data, segmento_carpeta_digital, sufijo, pageToAlter, x, y) {
        _AJAX_deprecated._BPAM["documento"] = _T.b64_to_utf8(data.message.mensaje);
        _AJAX_deprecated._BPAM["segmento_carpeta_digital"] = segmento_carpeta_digital;
        /*Override de posiciones de firma */
        switch (_AJAX_deprecated._BPAM["Formulario"]) {
            case "adhesionamutramediya":
                y = 350;
                break;
            case "adhesiongrupofamiliarmediya":
                y = 420;
                break;
            case "adhesionmediya":
                x = 150;
                y = 500;
                break;
        }
        _AJAX_deprecated._BPAM["pageToAlter"] = pageToAlter;
        _AJAX_deprecated._BPAM["x"] = x;
        _AJAX_deprecated._BPAM["y"] = y;
        $(".seeDocument").html(_AJAX_deprecated._BPAM["documento"]);
        $(".hfirma").removeClass("d-none");
        $(".topMenu").addClass("d-none");
        const canvas = document.querySelector('#sketchpad');
        const sketchpad = new Atrament(canvas, { width: 250, height: 200, color: 'black' });
        sketchpad.clear();
        sketchpad.weight = 1;
        sketchpad.mode = 'draw';
        sketchpad.smoothing = 0.75;
        sketchpad.adaptiveStroke = true;
        sketchpad.recordStrokes = false;
        $("body").off("click", ".btn-clear-sign").on("click", ".btn-clear-sign", function () {
            sketchpad.clear();
            $(".afirmar").removeClass("d-none");
            $(".firmado").addClass("d-none");
        });
        $("body").off("click", ".btn-ok-sign").on("click", ".btn-ok-sign", function () {
            if (!_F._ClientData.dirty) {
                _F.onModalAlert("Control de firma", "Debe firmar el formulario", "warning");
                return false;
            }
            _AJAX_deprecated._BPAM["img_additional"] = sketchpad.toImage();
            $(".btn-clear-sign").addClass("d-none");
            $(".afirmar").addClass("d-none");
            $(".firmado").removeClass("d-none");
            /*Enviar info para firma!*/
            _API_deprecated.UiFirmarFormulario(_AJAX_deprecated._BPAM).then(function (ret) {
                _F._ClientData.link_extract = ret.message.records[0].link_extract;
                _F._ClientData.link_certificate = ret.message.records[0].link_certificate;
                $(".hFirmado").addClass("d-none");
                _AJAX_deprecated._responseTitle = "";
                _AJAX_deprecated._responseMessage = "Se ha registrado la firma correctamente";
                _F.onTryPage(null, "msg-firmado");
            }).catch(function (err) {
                _AJAX_deprecated._responseMessage = err.message;
                _F.onTryPage(null, "msg-error");
            });
        });
        sketchpad.addEventListener('dirty', () => _F._ClientData.dirty = true);
        sketchpad.addEventListener('clean', () => _F._ClientData.dirty = false);
        sketchpad.addEventListener('strokestart', function () { });
        sketchpad.addEventListener('strokeend', function () { });
        $(".sketchpad").css("border", "solid 1px red");
    },
};
