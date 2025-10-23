var _NMF = {
    /*
     * Data capture structure
     * */
    _titleInitial:"",
    _intranet: false,
    _TIMER_LAZY:0,
    _CreditData: null,
    _ClientData: {
        "dirty": false,
        "bienvenida": "",
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
    _preValidationSelected: "",
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
                                _AJAX.Load("./pages/" + oLocal.ID + ".html").then(function (data) {
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

                    var _iLast = _NMF._iPage;
                    if (_NMF._iPage != null) { _NMF._iPage.onHide(); }
                    _NMF._iPage = _NMF.onTryStack(_id);
                    if (_NMF._iPage == null) {
                        _NMF.onTryPageAbstract(_id).then(function (o) {
                            _NMF._iPage = o;
                            _NMF._objStack.push(_NMF._iPage);
                            _NMF._iPage.onShow();
                            resolve(null);
                        });
                    } else {
                        _NMF._iPage.onShow();
                        resolve(null);
                    }
                    try { if (String(_iLast.ID) != String(_id)) { _NMF._keyStack.push(_id); } } catch (err) { };
                    setTimeout(_NMF.onConfigureApplication, 250);
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
        for (var i = 0; i < _NMF._objStack.length; i++) {
            if (_NMF._objStack[i].ID == _id) {
                _ret = _NMF._objStack[i];
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
        _NMF.onDestroyModal("#alterModalInfo");
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
        _NMF.onDestroyModal("#alterModal");
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
            _NMF.onDestroyModal("#alterModal");
        });
        $("#alterModal").modal({ backdrop: true, keyboard: true, show: true });
        return true;
    },
    onSeeRequest: function (_this) {
        _NMF.onSolicitudBuilder().then(function () {
            _NMF.onDestroyModal("#requestModal");
            var _html = "<div class='modal fade' id='requestModal' role='dialog'>";
            _html += " <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>";
            _html += "  <div class='modal-content'>";
            _html += ("    <div class='modal-body'>" + _TOOLS.b64_to_utf8(_NMF._ClientData._solicitudData.pdf_solicitud) + "</div>");
            _html += "    <div class='modal-footer font-weight-light'>";
            _html += "       <button type='button' class='btn-raised btn btn-cancel-request btn-secondary btn-sm'><i class='material-icons'>done</i></span>Cerrar</button>";
            _html += "    </div>";
            _html += "  </div>";
            _html += " </div>";
            _html += "</div>";
            $("body").append(_html);
            $("body").off("click", ".btn-cancel-request").on("click", ".btn-cancel-request", function () {
                _NMF.onDestroyModal("#requestModal");
            });
            $("#requestModal").modal({ backdrop: true, keyboard: true, show: true });
        });
        return true;
    },
    onSeeRequestPagare: function (_this) {
        _NMF.onPagareBuilder().then(function (_data) {
            _NMF.onDestroyModal("#requestModal");
            var _html = "<div class='modal fade' id='requestModal' role='dialog'>";
            _html += " <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>";
            _html += "  <div class='modal-content'>";
            _html += ("    <div class='modal-body'>" + _TOOLS.b64_to_utf8(_data) + "</div>");
            _html += "    <div class='modal-footer font-weight-light'>";
            _html += "       <button type='button' class='btn-raised btn btn-cancel-request btn-secondary btn-sm'><i class='material-icons'>done</i></span>Cerrar</button>";
            _html += "    </div>";
            _html += "  </div>";
            _html += " </div>";
            _html += "</div>";
            $("body").append(_html);
            $("body").off("click", ".btn-cancel-request").on("click", ".btn-cancel-request", function () {
                _NMF.onDestroyModal("#requestModal");
            });
            $("#requestModal").modal({ backdrop: true, keyboard: true, show: true });
        });
        return true;
    },
    onSeeRequestAmutra: function (_this) {
        _NMF.onAmutraBuilder().then(function (_data) {
            _NMF.onDestroyModal("#requestModal");
            var _html = "<div class='modal fade' id='requestModal' role='dialog'>";
            _html += " <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>";
            _html += "  <div class='modal-content'>";
            _html += ("    <div class='modal-body'>" + _TOOLS.b64_to_utf8(_data) + "</div>");
            _html += "    <div class='modal-footer font-weight-light'>";
            _html += "       <button type='button' class='btn-raised btn btn-cancel-request btn-secondary btn-sm'><i class='material-icons'>done</i></span>Cerrar</button>";
            _html += "    </div>";
            _html += "  </div>";
            _html += " </div>";
            _html += "</div>";
            $("body").append(_html);
            $("body").off("click", ".btn-cancel-request").on("click", ".btn-cancel-request", function () {
                _NMF.onDestroyModal("#requestModal");
            });
            $("#requestModal").modal({ backdrop: true, keyboard: true, show: true });
        });
        return true;
    },
    /*
     * General functions interactive data exchange!
     * */
    onLookup: function (_params) {
        _API.UiGetLookup2({ "Tipo": _params.tableType }).then(function (data) {

            _NMF.loadCombo(data.data, _params);
        });
    },
    loadCombo: function (datajson, params) {
        return new Promise(
            function (resolve, reject) {
                try {
                    if (params.placeholder == undefined) { params.placeholder = "[Seleccione]"; }
                    $(params.target).empty();
                    if (parseInt(params.selected) == -1) {
                        $(params.target).append('<option value="-1">' + params.placeholder + '</option>');
                    }
                    $.each(datajson, function (i, item) {
                        var _sel = "";
                        if (params.selected == item[params.id]) { _sel = "selected"; }
                        $(params.target).append('<option ' + _sel + ' value="' + item[params.id] + '">' + item[params.description] + '</option>');
                    });
                    resolve(true);
                } catch (rex) {
                    reject(rex);
                }
            });
    },

    /*
     * Interface step by step validation
     */
    onValidateStep: function (_this) {
        var _stage = _this.attr("data-scope");
        $("input").attr("disabled", false);
        try {
            _AJAX._responseTitle = "Gracias por brindarnos la información solicitada";
            _AJAX._responseMessage = "Vamos a contactarte a la brevedad para terminar el proceso de emisión";
            _NMF.onTryPage(null, "msg-ok");
        } catch (err) {
            _NMF.onModalAlert("Alerta", err, "warning");
        }
    },

    /*
     * Foto interface builder
     */
    onCameraOn: function (_this) {
        var _id = _this.attr("data-id");
        _NMF._activeImage = _this.attr("data-id");
        _NMF._dataNode = _this.attr("data-node");
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
        _NMF._ClientData._solicitudData.img_foto_cara = "";
        _NMF._ClientData._solicitudData.img_comprobante_servicio = "";
        _NMF._ClientData._solicitudData.img_comprobante_ingreso = "";
        _NMF._ClientData._solicitudData.img_dni_frente = "";
        _NMF._ClientData._solicitudData.img_dni_dorso = "";
    },
    onCameraChange: function (_this, e) {
        _NMF.onGetBase64(e.target.files[0]).then(function (data) {
            _NMF.onClearPictures();
            $.blockUI({ message: "<img src='./img/wait.gif' style='width:100px;' />", css: { width: '100px', border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
            _NMF._ClientData._solicitudData[_NMF._dataNode] = data;
            data = null;
            $(".btn-footer").removeClass("d-none");
            /*Save iface data to requests*/
            _NMF._ClientData._solicitudData.controlPoint = _NMF._dataNode;
            _API.UiOnboardingSaveRequest(_NMF._ClientData._solicitudData).then(function (_response) {
                $.unblockUI();
                _NMF.onClearPictures();
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
        if (_AJAX._monopage) { $(".btnBack").remove(); }
    },
    onDrawQRUrl: function (_page, _forced) {
        var _msg = "";
        $("#qrcode").html("").addClass("d-none");
        if (!_TOOLS.isMobileDevice() || _forced) {
            var _params = ("verificated=" + _NMF._ClientData._solicitudData.id + "&monopage=" + _page);
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
            if (data.data.raw_verify == null) {
                _NMF._ClientData._solicitudData.raw_verify = {};
            } else {
                _NMF._ClientData._solicitudData.raw_verify = JSON.parse(data.data.raw_verify);
                _NMF._ClientData._solicitudData.raw_verify = _NMF._ClientData._solicitudData.raw_verify["0"];
            }
            /*
             * Datos del credito, de la solicitud y entorno
             * */
            _NMF._ClientData._solicitudData.id = data.data.id;
            _NMF._ClientData._solicitudData.sqIdemia = data.data.SQidemia;
            if (data.data.id_type_modo_pago == null) { data.data.id_type_modo_pago = 0; }
            _NMF._ClientData._solicitudData.id_type_modo_pago = data.data.id_type_modo_pago;
            _NMF._ClientData._solicitudData.NroSolicitud = data.data.idSolicitudCredito;
            _NMF._ClientData._solicitudData.controlPoint = data.data.controlPoint;
            _NMF._ClientData._solicitudData.permiteNuevo = parseInt(data.data.permiteNuevo);
            _NMF._ClientData._solicitudData.permiteRenovacion = parseInt(data.data.permiteRenovacion);
            _NMF._ClientData._solicitudData.min = data.data.min;
            _NMF._ClientData._solicitudData.max = data.data.max;
            _NMF._ClientData._solicitudData.default = data.data.default;
            _NMF._ClientData._solicitudData.Capital = data.data.Capital;
            _NMF._ClientData._solicitudData.importe = data.data.importe;
            _NMF._ClientData._solicitudData.importeLetras = data.data.importeLetras;
            _NMF._ClientData._solicitudData.fechaEmisionCompleta = data.data.fechaEmisionCompleta;
            _NMF._ClientData._solicitudData.cuotas = data.data.cuotas;
            _NMF._ClientData._solicitudData.tasa = data.data.tasa;
            _NMF._ClientData._solicitudData.idplan = data.data.idplan;
            _NMF._ClientData._solicitudData.idcomercio = data.data.idcomercio;
            _NMF._ClientData._solicitudData.TNA = data.data.TNA;
            _NMF._ClientData._solicitudData.TEA = data.data.TEA;
            _NMF._ClientData._solicitudData.CFTNA = data.data.CFTNA;
            _NMF._ClientData._solicitudData.CFTEA = data.data.CFTEA;
            _NMF._ClientData._solicitudData.fechavto1 = data.data.fechavto1;
            _NMF._ClientData._solicitudData.monto = data.data.monto;
            /*
             * Datos generales del solicitante
             **/
            _NMF._ClientData._solicitudData.IdCliente = data.data.idCliente;
            _NMF._ClientData._solicitudData.EstadoCivil = data.data.EstadoCivil;
            _NMF._ClientData._solicitudData.Nacionalidad = data.data.Nacionalidad;
            _NMF._ClientData._solicitudData.Sucursal = data.data.Sucursal;

            _NMF._ClientData._solicitudData.Apellido = data.data.Apellido;
            _NMF._ClientData._solicitudData.Nombre = data.data.Nombre;
            _NMF._ClientData._solicitudData.Tipo = data.data.Tipo;
            _NMF._ClientData._solicitudData.Documento = data.data.Documento;
            _NMF._ClientData._solicitudData.Sexo = data.data.Sexo;
            _NMF._ClientData._solicitudData.Email = data.data.Email;
            _NMF._ClientData._solicitudData.prefijoTelefono = data.data.prefijoTelefono;
            _NMF._ClientData._solicitudData.Telefono = data.data.Telefono;
            _NMF._ClientData._solicitudData.prefijoTelefonoAlt = data.data.prefijoTelefonoAlt;
            _NMF._ClientData._solicitudData.TelefonoAlt = data.data.TelefonoAlt;
            _NMF._ClientData._solicitudData.Nacionalidad = data.data.Nacionalidad;
            if (data.data.FechaNacimiento != null) { _NMF._ClientData._solicitudData.FechaNacimiento = data.data.FechaNacimiento.split(" ")[0]; }
            _NMF._ClientData._solicitudData.EstadoCivil = data.data.EstadoCivil;
            _NMF._ClientData._solicitudData.Calle = data.data.Calle;
            _NMF._ClientData._solicitudData.Numero = data.data.Numero;
            _NMF._ClientData._solicitudData.Piso = data.data.Piso;
            _NMF._ClientData._solicitudData.Departamento = data.data.Departamento;
            _NMF._ClientData._solicitudData.CodigoPostal = data.data.CodigoPostal;
            _NMF._ClientData._solicitudData.EntreCalles = data.data.EntreCalles;
            _NMF._ClientData._solicitudData.Barrio = data.data.Barrio;
            _NMF._ClientData._solicitudData.Localidad = data.data.Localidad;
            _NMF._ClientData._solicitudData.Provincia = data.data.Provincia;
            _NMF._ClientData._solicitudData.ProvinciaDesc = data.data.ProvinciaDesc;
            _NMF._ClientData._solicitudData.Vivienda = data.data.Vivienda;
            _NMF._ClientData._solicitudData.iva = data.data.iva;
            _NMF._ClientData._solicitudData.cuil = data.data.cuil;
            _NMF._ClientData._solicitudData.Ocupacion = data.data.Ocupacion;
            _NMF._ClientData._solicitudData.RazonSocial = data.data.RazonSocial;
            _NMF._ClientData._solicitudData.cuit = data.data.cuit;
            _NMF._ClientData._solicitudData.Seccion = data.data.Seccion;
            _NMF._ClientData._solicitudData.Legajo = data.data.Legajo;
            _NMF._ClientData._solicitudData.Cargo = data.data.Cargo;
            _NMF._ClientData._solicitudData.Rubro = data.data.Rubro;
            _NMF._ClientData._solicitudData.IngresoMensual = data.data.IngresoMensual;
            if (data.data.FechaIngreso != null) { _NMF._ClientData._solicitudData.FechaIngreso = data.data.FechaIngreso.split(" ")[0]; }
            _NMF._ClientData._solicitudData.Antiguedad = data.data.Antiguedad;
            _NMF._ClientData._solicitudData.CalleEmpresa = data.data.CalleEmpresa;
            _NMF._ClientData._solicitudData.NumeroEmpresa = data.data.NumeroEmpresa;
            _NMF._ClientData._solicitudData.PisoEmpresa = data.data.PisoEmpresa;
            _NMF._ClientData._solicitudData.DepartamentoEmpresa = data.data.DepartamentoEmpresa;
            _NMF._ClientData._solicitudData.CodigoPostalEmpresa = data.data.CodigoPostalEmpresa;
            _NMF._ClientData._solicitudData.EntreCallesEmpresa = data.data.EntreCallesEmpresa;
            _NMF._ClientData._solicitudData.LocalidadEmpresa = data.data.LocalidadEmpresa;
            _NMF._ClientData._solicitudData.ProvinciaEmpresa = data.data.ProvinciaEmpresa;
            _NMF._ClientData._solicitudData.ProvinciaEmpresaDesc = data.data.ProvinciaEmpresaDesc;
            _NMF._ClientData._solicitudData.prefijoTelefonoEmpresa = data.data.prefijoTelefonoEmpresa;
            _NMF._ClientData._solicitudData.TelefonoEmpresa = data.data.TelefonoEmpresa;
            _NMF._ClientData._solicitudData.prefijoTelefonoAltEmpresa = data.data.prefijoTelefonoAltEmpresa;
            _NMF._ClientData._solicitudData.TelefonoAltEmpresa = data.data.TelefonoAltEmpresa;
            _NMF._ClientData._solicitudData.pdf_solicitud = data.data.pdf_solicitud;
            _NMF._ClientData._solicitudData.img_additional = data.data.img_additional;
            _NMF._ClientData._solicitudData.CBU = data.data.CBU;
            _NMF._ClientData._solicitudData.id_type_request = data.data.id_type_request;
            _NMF._ClientData.bienvenida = ("Hola " + _NMF._ClientData._solicitudData.Nombre + ", bienvenido/a a Credipaz");
        } catch (ex) {
            console.log(ex);
            alert("Error registrando datos de solicitud");
        }
    },
    onSetVal: function (target) {
        $("." + target).val(_NMF._ClientData._solicitudData[target]);
    },
    onSolicitudBuilder: function () {
        return new Promise(
            function (resolve, reject) {
                _AJAX._BPAM["Formulario"] = "SOLICITUDPRODUCTO";
                _AJAX._BPAM["ValueForRetrieve"] = _NMF._ClientData._solicitudData.id_type_request;
                _API.UiGetFormulario(_AJAX._BPAM).then(function (_ret) {
                    try {
                        var data = _TOOLS.b64_to_utf8(_ret.message.mensaje);
                        /*
                         * Reemplazar valores en solicitud
                         * */
                        //data = _TOOLS.tagReplace(data, /\[SOLICITUD\]/g, "a asignar");
                        //data = _TOOLS.tagReplace(data, /\[LEGAJO\]/g, "a asignar");
                        //data = _TOOLS.tagReplace(data, /\[SUCURSAL\]/g, "a asignar");
                        //data = _TOOLS.tagReplace(data, /\[FECHAVTO1\]/g, "a asignar");
                        //data = _TOOLS.tagReplace(data, /\[NACIONALIDAD\]/g, "a asignar");
                        //data = _TOOLS.tagReplace(data, /\[ESTADOCIVIL\]/g, "a asignar");
                        data = _TOOLS.tagReplace(data, /\[APELLIDO\]/g, _NMF._ClientData._solicitudData.Apellido);
                        data = _TOOLS.tagReplace(data, /\[NOMBRE\]/g, _NMF._ClientData._solicitudData.Nombre);
                        data = _TOOLS.tagReplace(data, /\[DNI\]/g, _NMF._ClientData._solicitudData.Documento);
                        data = _TOOLS.tagReplace(data, /\[CALLE\]/g, _NMF._ClientData._solicitudData.Calle);
                        data = _TOOLS.tagReplace(data, /\[NRO\]/g, _NMF._ClientData._solicitudData.Numero);
                        data = _TOOLS.tagReplace(data, /\[PISO\]/g, _NMF._ClientData._solicitudData.Piso);
                        data = _TOOLS.tagReplace(data, /\[DEPTO\]/g, _NMF._ClientData._solicitudData.Departamento);
                        data = _TOOLS.tagReplace(data, /\[LOCALIDAD\]/g, _NMF._ClientData._solicitudData.Localidad);
                        data = _TOOLS.tagReplace(data, /\[PROVINCIA\]/g, _NMF._ClientData._solicitudData.ProvinciaDesc);
                        data = _TOOLS.tagReplace(data, /\[CODIGOPOSTAL\]/g, _NMF._ClientData._solicitudData.CodigoPostal);
                        data = _TOOLS.tagReplace(data, /\[CELULAR\]/g, (_NMF._ClientData._solicitudData.prefijoTelefono + " " + _NMF._ClientData._solicitudData.Telefono));
                        data = _TOOLS.tagReplace(data, /\[EMAIL\]/g, _NMF._ClientData._solicitudData.Email);
                        data = _TOOLS.tagReplace(data, /\[CAPITAL\]/g, _TOOLS.toCurr(_NMF._ClientData._solicitudData.Capital));
                        data = _TOOLS.tagReplace(data, /\[IMPORTE\]/g, _TOOLS.toCurr(_NMF._ClientData._solicitudData.importe));
                        data = _TOOLS.tagReplace(data, /\[CUOTAS\]/g, _NMF._ClientData._solicitudData.cuotas);

                        /* Agregado 8/1/2024 */
                        data = _TOOLS.tagReplace(data, /\[MONTO\]/g, _TOOLS.toCurr(_NMF._ClientData._solicitudData.monto));
                        data = _TOOLS.tagReplace(data, /\[TNA\]/g, _NMF._ClientData._solicitudData.TNA + "%");
                        data = _TOOLS.tagReplace(data, /\[TEA\]/g, _NMF._ClientData._solicitudData.TEA + "%");
                        data = _TOOLS.tagReplace(data, /\[CFTNA\]/g, _NMF._ClientData._solicitudData.CFTNA + "%");
                        data = _TOOLS.tagReplace(data, /\[CFTEA\]/g, _NMF._ClientData._solicitudData.CFTEA + "%");

                        /* Agregado 29/8/2025 */
                        if (_NMF._ClientData._solicitudData.IdCliente != "") {
                            data = _TOOLS.tagReplace(data, /\[LEGAJO\]/g, _NMF._ClientData._solicitudData.IdCliente);
                            data = _TOOLS.tagReplace(data, /\[SUCURSAL\]/g, _NMF._ClientData._solicitudData.Sucursal);
                            data = _TOOLS.tagReplace(data, /\[NACIONALIDAD\]/g, _NMF._ClientData._solicitudData.Nacionalidad);
                            data = _TOOLS.tagReplace(data, /\[ESTADOCIVIL\]/g, _NMF._ClientData._solicitudData.EstadoCivil);
                        }

                        _NMF._ClientData._solicitudData.pdf_solicitud = _TOOLS.utf8_to_b64(data);
                        resolve(_NMF._ClientData._solicitudData.pdf_solicitud);
                    } catch (err) {
                        reject(err);
                    }
                });
            });
    },
    onPagareBuilder: function () {
        return new Promise(
            function (resolve, reject) {
                _AJAX._BPAM["Formulario"] = "PAGARECREDITO";
                _AJAX._BPAM["ValueForRetrieve"] = _NMF._ClientData._solicitudData.id_type_request;
                _API.UiGetFormulario(_AJAX._BPAM).then(function (_ret) {
                    try {
                        var data = _TOOLS.b64_to_utf8(_ret.message.mensaje);
                        /*
                         * Reemplazar valores en solicitud
                         * */
                        data = _TOOLS.tagReplace(data, /\[APELLIDO\]/g, _NMF._ClientData._solicitudData.Apellido);
                        data = _TOOLS.tagReplace(data, /\[NOMBRE\]/g, _NMF._ClientData._solicitudData.Nombre);
                        data = _TOOLS.tagReplace(data, /\[DNI\]/g, _NMF._ClientData._solicitudData.Documento);
                        data = _TOOLS.tagReplace(data, /\[CALLE\]/g, _NMF._ClientData._solicitudData.Calle);
                        data = _TOOLS.tagReplace(data, /\[NRO\]/g, _NMF._ClientData._solicitudData.Numero);
                        data = _TOOLS.tagReplace(data, /\[PISO\]/g, _NMF._ClientData._solicitudData.Piso);
                        data = _TOOLS.tagReplace(data, /\[DEPTO\]/g, _NMF._ClientData._solicitudData.Departamento);
                        data = _TOOLS.tagReplace(data, /\[LOCALIDAD\]/g, _NMF._ClientData._solicitudData.Localidad);
                        data = _TOOLS.tagReplace(data, /\[PROVINCIA\]/g, _NMF._ClientData._solicitudData.ProvinciaDesc);
                        data = _TOOLS.tagReplace(data, /\[CODIGOPOSTAL\]/g, _NMF._ClientData._solicitudData.CodigoPostal);
                        data = _TOOLS.tagReplace(data, /\[IMPORTE\]/g, _TOOLS.toCurr(_NMF._ClientData._solicitudData.importe));
                        data = _TOOLS.tagReplace(data, /\[IMPORTELETRAS\]/g, _TOOLS.toCurr(_NMF._ClientData._solicitudData.importeLetras));
                        data = _TOOLS.tagReplace(data, /\[CELULAR\]/g, (_NMF._ClientData._solicitudData.prefijoTelefono + " " + _NMF._ClientData._solicitudData.Telefono));
                        data = _TOOLS.tagReplace(data, /\[FECHAEMISION\]/g, _NMF._ClientData._solicitudData.fechaEmisionCompleta);
                        resolve(_TOOLS.utf8_to_b64(data));
                    } catch (err) {
                        reject(err);
                    }
                });
            });
    },
    onAmutraBuilder: function () {
        return new Promise(
            function (resolve, reject) {
                _AJAX._BPAM["Formulario"] = "SOLICITUDAMUTRA";
                _AJAX._BPAM["ValueForRetrieve"] = _NMF._ClientData._solicitudData.id_type_request;
                _API.UiGetFormulario(_AJAX._BPAM).then(function (_ret) {
                    try {
                        var data = _TOOLS.b64_to_utf8(_ret.message.mensaje);
                        /*
                         * Reemplazar valores en solicitud
                         * */
                        data = _TOOLS.tagReplace(data, /\[APELLIDO\]/g, _NMF._ClientData._solicitudData.Apellido);
                        data = _TOOLS.tagReplace(data, /\[NOMBRE\]/g, _NMF._ClientData._solicitudData.Nombre);
                        data = _TOOLS.tagReplace(data, /\[DNI\]/g, _NMF._ClientData._solicitudData.Documento);
                        data = _TOOLS.tagReplace(data, /\[CALLE\]/g, _NMF._ClientData._solicitudData.Calle);
                        data = _TOOLS.tagReplace(data, /\[NRO\]/g, _NMF._ClientData._solicitudData.Numero);
                        data = _TOOLS.tagReplace(data, /\[PISO\]/g, _NMF._ClientData._solicitudData.Piso);
                        data = _TOOLS.tagReplace(data, /\[DEPTO\]/g, _NMF._ClientData._solicitudData.Departamento);
                        data = _TOOLS.tagReplace(data, /\[LOCALIDAD\]/g, _NMF._ClientData._solicitudData.Localidad);
                        data = _TOOLS.tagReplace(data, /\[PROVINCIA\]/g, _NMF._ClientData._solicitudData.ProvinciaDesc);
                        data = _TOOLS.tagReplace(data, /\[CODIGOPOSTAL\]/g, _NMF._ClientData._solicitudData.CodigoPostal);
                        data = _TOOLS.tagReplace(data, /\[CELULAR\]/g, (_NMF._ClientData._solicitudData.prefijoTelefono + " " + _NMF._ClientData._solicitudData.Telefono));
                        data = _TOOLS.tagReplace(data, /\[FECHAEMISION\]/g, _NMF._ClientData._solicitudData.fechaEmisionCompleta);
                        resolve(_TOOLS.utf8_to_b64(data));
                    } catch (err) {
                        reject(err);
                    }
                });
            });
    },

    onEmitirProducto: function (_img_additional) {
        /*Final call for close process and create request for credit*/
        _NMF.onModalInfo("Emisión de producto", "Por favor, aguarde que el proceso finalice.  Puede demorar unos minutos, no salga de esta página.", "info"); 
        _NMF.onSolicitudBuilder().then(function () {
            _API.UiOnboardingFinalRequest({ "pdf_solicitud": _NMF._ClientData._solicitudData.pdf_solicitud, "img_additional": _img_additional, "id": _AJAX._KEY, "lat": _AJAX._BPAM["latitude"], "lng": _AJAX._BPAM["longitude"] }).then(function (data) {
                _NMF._CreditData = data.data.message;
                $(".btnCancelProcess").addClass("d-none");
                _NMF._ClientData.link_extract = data.link_extract;
                _NMF._ClientData.link_certificate = data.link_certificate;
                _NMF.onDestroyModal("#alterModalInfo");
                _AJAX._responseTitle = "";
                _AJAX._responseMessage = "Se ha registrado la firma correctamente";
                _NMF.onTryPage(null, "msg-firmado");
            }).catch(function (err) {
                _NMF.onDestroyModal("#alterModalInfo");
                _AJAX._responseMessage = err.message;
                _NMF.onTryPage(null, "msg-error");
            });
        });
    },

    /*
     * Firma de documentos adHoc
     */
    onBuildFirmaDocumento: function (data, segmento_carpeta_digital, sufijo, pageToAlter, x, y) {
        _AJAX._BPAM["documento"] = _TOOLS.b64_to_utf8(data.message.mensaje);
        _AJAX._BPAM["segmento_carpeta_digital"] = segmento_carpeta_digital;
        _AJAX._BPAM["pageToAlter"] = pageToAlter;
        _AJAX._BPAM["x"] = x;
        _AJAX._BPAM["y"] = y;
        $(".seeDocument").html(_AJAX._BPAM["documento"]);
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
            if (!_NMF._ClientData.dirty) {
                _NMF.onModalAlert("Control de firma", "Debe firmar el formulario", "warning");
                return false;
            }
            _AJAX._BPAM["img_additional"] = sketchpad.toImage();
            $(".btn-clear-sign").addClass("d-none");
            $(".afirmar").addClass("d-none");
            $(".firmado").removeClass("d-none");
            /*Enviar info para firma!*/
            _API.UiFirmarFormulario(_AJAX._BPAM).then(function (ret) {
                _AJAX._BPAM["link_extract"] = ret.message.records[0].link_extract;
                _AJAX._BPAM["link_certificate"] = ret.message.records[0].link_certificate;
                $(".hFirmado").addClass("d-none");
                _AJAX._responseTitle = "";
                _AJAX._responseMessage = "Se ha registrado la firma correctamente";
                _NMF.onTryPage(null, "msg-firmado");
            }).catch(function (err) {
                _AJAX._responseMessage = err.message;
                _NMF.onTryPage(null, "msg-error");
            });
        });
        sketchpad.addEventListener('dirty', () => _NMF._ClientData.dirty = true);
        sketchpad.addEventListener('clean', () => _NMF._ClientData.dirty = false);
        sketchpad.addEventListener('strokestart', function () { });
        sketchpad.addEventListener('strokeend', function () { });
        $(".sketchpad").css("border", "solid 1px red");
    },
};
