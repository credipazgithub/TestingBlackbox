var _NMF = {
    _cdn_server: "https://cdn.gruponeodata.com/",
    urlPago: "",
    urlResumen:"",
    _pagination_actual_value: 1,
    deferredPrompt: null,
    _default_page: "",
    _bInstalling: false,
    _actualPage: "",
    _logged: false,
    _server: "./",
    _user_active: null,
    _session_data: null,
    _auth_user_data: {
        "id": 0,
        "field": "username",
        "value": "",
        "email": "",
        "password": "",
        "dni": "",
        "sex": "",
        "area": "",
        "phone": "",
        "uid": "",
        "viable": 0,
        "name": "",
        "IdSolicitud": 0,
        "id_club_redondo": 0
    },
    _id_credipaz: 0,
    _nombre: "",

    /* Funciones generales */
    readConfigServers: function (_key) {
        return new Promise(
            function (resolve, reject) {
                fetch("./Recursos/configServers.json")
                    .then(response => {
                        if (!response.ok) { throw new Error(`HTTP error! status: ${response.status}`); }
                        return response.text();
                    })
                    .then(_ret => {
                        var data = JSON.parse(_ret);
                        var _item = data.find(item => item.key === _key);
                        resolve(_item);
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        reject(error);
                    });
            });
    },
    onDestroyModal: function (_target, _callback) {
        $(_target).remove();
        $(".modal-backdrop").remove();
        $("body").removeClass("modal-open");
        if ($.isFunction(_callback)) { _callback(); }
    },
    onModalFullScreen: function (_title, _body, _class) {
        if (_class == undefined) { _class = "info"; }
        _NMF.onDestroyModal("#alterFullScreen");
        var _html = "<div class='modal fade' id='alterFullScreen' role='dialog' style='padding:0px;margin:0px;z-index:9999999;width:100vw;height:100vh;'>";
        _html += " <div class='modal-dialog' role='document' style='padding:0px;margin:0px;z-index:9999999;width:100vw;height:100vh;'>";
        _html += "  <div class='modal-content' style='position:absolute;left:0px;top:0px;width:100vw;height:100vh;'>";
        _html += "    <div class='modal-header text-" + _class + "'>";
        if (_title != "") { _html += "<h4>" + _title + "</h4>"; }
        _html += "    </div>";
        _html += "    <div class='modal-body'>";
        _html += _body;
        _html += "    </div>";
        _html += "  </div>";
        _html += " </div>";
        _html += "</div>";
        $("body").append(_html);
        $("body").off("click", ".btn-cancel-alert").on("click", ".btn-cancel-alert", function () {
            _NMF.onDestroyModal("#alterFullScreen");
        });
        $("#alterFullScreen").modal({ backdrop: false, keyboard: false, show: true });
        return true;
    },
    onNoInstallApp: function () {
        _NMF.onDestroyModal("#alterFullScreen", function () {
            setTimeout(function () {
                var _html = "";
                _html += ("<div>¿Desea siempre usar en modo Web?</div>");
                _html += "<hr/>";
                _html += "<table>";
                _html += "   <tr>";
                _html += "      <td align='right'><button id='fixinstall' class='btn bt-md btn-success btn-raised fixinstall'>Siempre web</button></td>";
                _html += "      <td align='left'><button id='fixnoinstall' class='btn bt-md btn-primary btn-raised fixnoinstall'>Preguntarme más tarde</button></td>";
                _html += "   </tr>";
                _html += "</table>";
                _NMF.onModalAlert("Confirmar acción", _html, "dark", false, function () { });
            }, 500);
        });
    },
    onModalAlert: function (_title, _body, _class) {
        if (_class == undefined) { _class = "info"; }
        _NMF.onDestroyModal("#alterModal");
        var _html = "<div class='modal fade' id='alterModal' role='dialog' style='z-index:999998;'>";
        _html += " <div class='modal-dialog modal-dialog-centered modal-lg m-0 p-0' role='document' style='z-index:999999;'>";
        _html += "  <div class='modal-content mt-2' style='position:absolute;left:0px;top:0px;width:100vw;'>";
        _html += "    <div class='modal-header text-" + _class + "'>";
        if (_title != "") {
            _html += "<h4>" + _title + "<button  class='close pull-right' data-dismiss='modal' style='position:absolute;right:10px;top:10px;font-size:2rem;'>&times;</button></h4>";
        } else {
            _html += "<button  class='close' data-dismiss='modal' style='position:absolute;right:10px;top:10px;font-size:2rem;'>&times;</button>";
        }
        _html += "    </div>";
        _html += "    <div class='modal-body'>";
        _html += _body;
        _html += "    </div>";
        //_html += "    <div class='modal-footer font-weight-light'>";
        //_html += "       <button  class='btn-raised btn btn-cancel-alert btn-" + _class + " btn-sm'><i class='material-icons'>done</i></span>Aceptar</button>";
        //_html += "    </div>";
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
    onShowHtmlModal: function (_message, _callback) {
        _NMF.onDestroyModal("#modal-html");
        var _html = "";
        _html += "<div id='modal-html' class='modal fade' style='z-index:999999;'>";
        _html += "  <div class='modal-dialog modal-dialog-centered modal-lg m-0 p-0' role='document' style='z-index:999999;'>";
        _html += "    <div class='modal-content mt-2' style='position:absolute;left:0px;top:0px;width:100vw;'>";
        _html += "      <button  class='close btn-close-modal' data-target='#modal-html' data-dismiss='modal' style='z-index:999999;position:absolute;right:10px;top:-5px;font-size:3rem;'>&times;</button>";
        _html += "      <div class='modal-body danger alert-default'>" + _message + "</div>";
        _html += "    </div>";
        _html += "  </div>";
        _html += "</div>";
        $("body").append(_html);
        $("#modal-html").on('hide.bs.modal', function () { });
        $("#modal-html").modal({ backdrop: true, keyboard: true });
        if ($.isFunction(_callback)) { _callback(); }
    },
    onModalByIdPost: function (_this) {
        var _id = _this.attr("data-id");
        var _accept = _this.attr("data-accept");
        var _reject = _this.attr("data-reject");
        var _check = _this.attr("data-check");
        _API.UiGetWebPosts({ "id": _id })
            .then(function (data) {
                var _html = data.data[0].body_post;
                _html += "<hr/>";
                _html += "<div class='row mt-3'>";
                if (_reject != "") {
                    _html += "   <div class='col-6 text-center'>";
                    _html += "      <a href='#' data-target='#modal-html' class='btn-close-modal btn btn-danger btn-raised pull-left'>" + _reject + "</a>";
                    _html += "   </div>";
                }
                if (_accept != "") {
                    _html += "   <div class='col-6 text-center'>";
                    _html += "      <a href='#' class='btn-action-accept btn btn-success btn-raised pull-right' data-target='" + _check + "'>" + _accept + "</a>";
                    _html += "   </div>";
                }
                _html += "</div>";
                _NMF.onShowHtmlModal(_html, function () {

                });
            })
            .catch(function (err) {
                console.log(err);
            });
    },

    /* Funciones de interface */
    onSelectMyName: function (_this) {
        _NMF._auth_user_data.viable = _this.attr("data-viable");
        _NMF._auth_user_data.name = _this.html();
        $(".newUser").removeClass("d-none");
        $(".lastStep").removeClass("d-none");
        $(".secondStep").addClass("d-none");
    },
    onAcceptCheckBox: function (_this) {
        $(_this.attr("data-target")).prop("checked", true);
        _NMF.onDestroyModal("#modal-html");
    },
    onToggleSecret: function (_this, _target) {
        switch (_this.html().includes("visibility_off")) {
            case true:
                _this.html("<i class='material-icons'>visibility</i>");
                break;
            default:
                _this.html("<i class='material-icons'>visibility_off</i>");
                break;
        }
        var input = $(_target);
        if (input.attr("type") == "password") { input.attr("type", "text"); } else { input.attr("type", "password"); }
    },
    onCloseSidebar: function (_this) {
        $(".page-sidebar").animate({ left: "-2500px", opacity: 0 }, 500, function () { });
    },
    onToggleSidebar: function (_this) {
        if ($(".page-sidebar").css("left") == "-2500px") {
            $(".add-header").addClass("d-none");
            $(".page-sidebar").animate({ left: "0px", opacity: 1 }, 500, function () { });
        } else {
            $(".add-header").removeClass("d-none");
            $(".page-sidebar").animate({ left: "-2500px", opacity: 0 }, 500, function () { });
        }
    },
    onSetInterface: function (user) {
        _NMF.onGetUserInformation();
        $(".page-sections").html("");
        $(".toggle-sidebar").animate({ opacity: 1 }, 250, function () { });
        $(".displayUserdata").animate({ opacity: 1 }, 250, function () { });
        _NMF._user_active = user;
        if (_NMF._logged) {
            var _displayName = _NMF._auth_user_data.name;
            try { _displayName = _NMF._auth_user_data.name.split(" ")[0]; } catch (err) {}
            var _pre = "Hola, ";
            if (_displayName == null) { _displayName = user.email.split("@")[0]; }
            $(".displayUserdata").html(_pre + _displayName);
        }
        _NMF.onToggleVisibilityByStatus();
        /*Default access visible point*/
        $(".page-sections").load(_NMF._default_page, function () {
            $(".app-header").hide();
            $(".app-beneficios").hide();
            $(".app-footer").hide();
            setTimeout(function () {
                $(".app-header").removeClass("d-none").fadeIn("slow");
                $(".app-beneficios").removeClass("d-none").fadeIn("slow");
                $(".app-footer").removeClass("d-none").fadeIn("slow");
            }, 1);
        });
    },
    onToggleVisibilityByStatus: function () {
        if (_NMF._logged) {
            $(".loged").removeClass("d-none");
            $(".notloged").addClass("d-none");
        } else {
            $(".loged").addClass("d-none");
            $(".notloged").removeClass("d-none");
        }
    },
    onResizeInner: function () {
        $(".page-footer").removeClass("d-none");
        var _h = (screen.height - $(".page-header").height() - $(".page-footer").height()) + 20;
        $(".inner-page").css("height", (_h + "px"));
    },
    onPageHeader: function (_target, _title, _img, _back, _map) {
        $(_target).hide();
        var _rest = 11;
        var _html = "";
        var _htmlMap = "";
        _html += "<div class='col-1 pt-2'>";
        _html += "   <a href='#' class='btn-data-page' data-direction='normal' data-href='" + _back + "'>";
        _html += "      <i class='material-icons' style='color:grey;font-size:30px;'>chevron_left</i>";
        _html += "   </a>";
        _html += "</div>";
        if (_img != "") {
            _html += "<div class='col-1'><img src='" + _img + "' style='width:44px;' /></div>";
            _rest -= 1;
        }
        _html += "<div class='col-" + _rest + " text-center p-0'><b class='dyn-title'>" + _TOOLS.titleCase(_title) + "</b></div>";
        _html += _htmlMap;

        $(_target).html(_html).fadeIn("fast");
    },

    /* Evaluaciones de contexto */
    onEvalLoginStatus: function () {
        var _get = _DB.Get("logged");
        if (_get != null) { _NMF._auth_user_data = _get; }
        firebase.auth().onAuthStateChanged(function (user) {
            if (user) {
                try {
                    _API.UiTestUserValuePWA({ "type": "document", "documentNumber": _NMF._auth_user_data.dni })
                        .then(function (data) {
                            _NMF.onAuthenticateMobile(user);
                        }).catch(function (err) {
                            _NMF.onModalAlert("Error", err.message, "danger");
                        });
                } catch (rex) { }
            } else {
                _DB.Remove("logged");
                _NMF._logged = false;
                _NMF.onSetInterface(null);
            }
        });
    },
    onGetUserInformation: function () {
        _API.UiGetUserInformation(_NMF._auth_user_data).then(
            function (data) {
                var _message = "";
                _message = data.message.GetUserInformationResult;
                if (_message == "") { _message = '{"IdCliente":0}'; }
                _NMF._session_data = JSON.parse(_message);
                _NMF._id_credipaz = parseInt(_NMF._session_data.IdCliente);
                _NMF._nombre = _NMF._session_data.ApellidoNombre;
                if (_NMF._session_data.Periodo == undefined) { _NMF._session_data.Periodo = '201903'; }
                if (_NMF._session_data.Archivo == undefined) { _NMF._session_data.Archivo = '0101000231'; }
                _NMF.urlResumen = (_HTTPREQUEST.server + "downloadResumen/" + _NMF._session_data.Periodo + "/" + _NMF._session_data.Archivo + ".pdf");
                if (_NMF._session_data == null) { _NMF._session_data = { "IdCliente": 0 }; }
                if (_NMF._session_data.MontoTelemedicina == undefined) { _NMF._session_data.MontoTelemedicina = 1000; }
                if (_NMF._session_data.TeleMedConsultasResto == undefined) { _NMF._session_data.TeleMedConsultasResto = 0; }
                if (_NMF._session_data.TeleMedicina == undefined) { _NMF._session_data.TeleMedicina = 1; }
                if (_NMF._session_data.PagoEnLinea == undefined) { _NMF._session_data.PagoEnLinea = 0; }
                if (_NMF._session_data.EspecialidadesMedicas == undefined) { _NMF._session_data.EspecialidadesMedicas = 1; }
                if (_NMF._session_data.CREDENCIALClub == undefined) { _NMF._session_data.CREDENCIALClub = 0; }
                if (_NMF._session_data.CREDENCIALClub == "*") { _NMF._session_data.CREDENCIALClub = 0; }
                if (_NMF._session_data.LogoClub == undefined) { _NMF._session_data.LogoClub = 0; }
                if (_NMF._session_data.SorteoLibre == undefined) { _NMF._session_data.SorteoLibre = 0; }
                if (_NMF._session_data.TelemedicinaMensajeDias == undefined) { _NMF._session_data.TelemedicinaMensajeDias = "Nuestro horario de atención es de lunes a sábado de"; }
                if (_NMF._session_data.TelemedicinaHoraDesde == undefined) { _NMF._session_data.TelemedicinaHoraDesde = 8; }
                if (_NMF._session_data.TelemedicinaHoraHasta == undefined) { _NMF._session_data.TelemedicinaHoraHasta = 20; }
                if (_NMF._session_data.TelemedicinaDiasAtencion == undefined) { _NMF._session_data.TelemedicinaDiasAtencion = "1234560"; }
                if (_NMF._session_data.TelemedicinaMensajeCerrado == undefined) { _NMF._session_data.TelemedicinaMensajeCerrado = _NMF._session_data.TelemedicinaMensajeDias + " " + _NMF._session_data.TelemedicinaHoraDesde + " a " + _NMF._session_data.TelemedicinaHoraHasta + "hs."; }
                if (_NMF._session_data.LegalesConsultasResto == undefined) { _NMF._session_data.LegalesConsultasResto = 1; }
                if (_NMF._session_data.LegalesMensajeAtencion == undefined) { _NMF._session_data.LegalesMensajeAtencion = ""; }
                if (_NMF._session_data.MontoLegales == undefined) { _NMF._session_data.MontoLegales = 0; }
                if (_NMF._session_data.ColorAsociate == undefined) { _NMF._session_data.ColorAsociate = "#ff24a0"; }
                if (_NMF._session_data.NombreCredencial == undefined) { _NMF._session_data.NombreCredencial = ""; }
                if (_NMF._session_data.PANClub == undefined) { _NMF._session_data.PANClub = 0; }
                if (_NMF._session_data.Sube == undefined) { _NMF._session_data.Sube = 1; }
                if (_NMF._session_data.resumen == undefined) { _NMF._session_data.resumen = 1; }
                if (_NMF._session_data.ClubRedondo == undefined) { _NMF._session_data.ClubRedondo = 0; }
                if (_NMF._session_data.MontoCreditoOfrecido == undefined) { _NMF._session_data.MontoCreditoOfrecido = 0; }
                if (_NMF._session_data.MontoAdelantoTarjeta == undefined) { _NMF._session_data.MontoAdelantoTarjeta = 0; }
                if (_NMF._session_data.OfrecerAdelantoTarjeta == undefined) { _NMF._session_data.OfrecerAdelantoTarjeta = 0; }
                if (_NMF._session_data.OfrecerTarjeta == undefined) { _NMF._session_data.OfrecerTarjeta = 1; }
                if (_NMF._session_data.OfrecerCredito == undefined) { _NMF._session_data.OfrecerCredito = 1; }
                if (_NMF._session_data.text != undefined) { _NMF.onModalFullScreen("", _NMF._session_data.text, "warning"); }
                if (_NMF._session_data.NroTelCredito == undefined) { _NMF._session_data.NroTelCredito = "08103339009"; }
                if (_NMF._session_data.NroTelTarjeta == undefined) { _NMF._session_data.NroTelTarjeta = "08103339009"; }
                if (_NMF._session_data.NroTelEnfermedadesCriticas == undefined) { _NMF._session_data.NroTelEnfermedadesCriticas = "08106662582"; }
                if (_NMF._session_data.NroTeltrasplantes == undefined) { _NMF._session_data.NroTeltrasplantes = "08106662583"; }
                if (_NMF._session_data.NroTelCoberturaVida == undefined) { _NMF._session_data.NroTelCoberturaVida = "08106662584"; }
                if (_NMF._session_data.NroTelAsisHogar == undefined) { _NMF._session_data.NroTelAsisHogar = "08003333127"; }
                if (_NMF._session_data.ModoCredencialSWISS == undefined) { _NMF._session_data.ModoCredencialSWISS = 0; }
            })
            .catch(function (data) {
                console.log(data);
            });
    },

    /* Navegación */
    onChangePage: function (_this) {
        _NMF._pagination_actual_value = 1;
        if (_NMF._actualPage.toUpperCase() == _this.attr("data-href").toUpperCase()) { return false; }
        _NMF._actualPage = _this.attr("data-href");
        _NMF.onDestroyModal('#modal_canje');
        var _direction = _this.attr("data-direction");
        var _iDirection = 1;
        switch (_direction) {
            case "reverse":
                _iDirection = -1;
                break;
        }
        if ($(".page-sidebar").css("left") == "0px") {
            $(".page-sidebar").css({ "opacity": 0 });
            _NMF.onToggleSidebar(null);
        }
        $(".effect").html($(".page-header").html() + $(".page-sections").html());
        $(".effect").css({ "left": "0px", "opacity": 1 }).show();

        var ms = Date.now();
        $(".page-sections").load((_NMF._server + _NMF._actualPage + "?" + ms),
            function () {
                _NMF.onToggleVisibilityByStatus();
                if (_this.attr("data-id") != undefined) {
                    $(".dyn-title").html(_this.attr("data-title"));
                }
                $(".effect").animate({
                    left: ($(".effect").width() * _iDirection),
                    opacity: 0.75
                }, 500, function () {
                    $(".effect").html("").css({ "left": "0px" }).hide();
                });
            }
        );
    },

    /* Acciones concretas */
    onAuthenticateMobile: function (user) {
        _API.UiAuthenticateMobile({
            "id": _NMF._auth_user_data.id,
            "field": "username",
            "value": user.email,
            "email": user.email,
            "dni": _NMF._auth_user_data.dni,
            "sex": _NMF._auth_user_data.sex,
            "area": _NMF._auth_user_data.area,
            "phone": _NMF._auth_user_data.phone,
            "viable": _NMF._auth_user_data.viable,
            "name": _NMF._auth_user_data.name,
            "password": _NMF._auth_user_data.password,
            "IdSolicitud": 0
        }).then(function (data) {
            try {
                if (data.userdata != null) {
                    _NMF._auth_user_data.id_club_redondo = 0;
                    if (data.clubredondo != undefined) { _NMF._auth_user_data.id_club_redondo = data.clubredondo.ClubRedondo; }
                    _NMF._auth_user_data.id = data.id;
                    _NMF._auth_user_data.dni = data.userdata.documentNumber;
                    if (_NMF._auth_user_data.dni.includes("@")) { _NMF._auth_user_data.dni = _NMF._auth_user_data.dni.split("@")[0]; }
                    _NMF.urlPago = (_HTTPREQUEST.server + "linkDirect/pagos-fiserv-mcp/" + _TOOLS.utf8_to_b64(_NMF._auth_user_data.dni));
                    _NMF._auth_user_data.sex = data.userdata.documentSex;
                    _NMF._auth_user_data.area = data.userdata.documentArea;
                    _NMF._auth_user_data.phone = data.userdata.documentPhone;
                    _NMF._auth_user_data.viable = data.userdata.viable;
                    _NMF._auth_user_data.name = data.userdata.documentName;

                    _HTTPREQUEST._token_authentication = data.token_authentication;
                    _HTTPREQUEST._id_user_active = _NMF._auth_user_data.id;
                    _HTTPREQUEST._id_club_redondo = _NMF._auth_user_data.id_club_redondo;
                    _DB.Set("logged", _NMF._auth_user_data);
                    _NMF._logged = true;
                    _NMF.onSetInterface(user);
                } else {
                    throw new Error();
                }
            } catch (err) {
                _NMF.onModalAlert("Alerta", "Imposible acceder con el DNI y contraseña provistos.  Reintente o recupere su contraseña desde el menú lateral", "info");
                _NMF.onActionLogout(null);
            }
        }).catch(function (data) {
            _NMF.onActionLogout(null);
            _NMF.onModalAlert("Alerta", "Imposible acceder con el DNI y contraseña provistos.  Reintente o recupere su contraseña desde el menú lateral", "info");
        });

    },
    onActionLogin: function (_this) {
        if (!$("#chkTerminos").prop("checked")) {
            _NMF.onModalAlert("Alerta", "Debe aceptar los términos y condiciones de uso.", "info");
            return false;
        }
        var _dni = $(".dni").val();
        var _password = $(".password").val();
        var _email = (_dni + _HTTPREQUEST._sufixEmail);
        switch (_this.attr("data-step")) {
            case "first":
                _API.UiTestUserValuePWA({ "type": "document", "documentNumber": _dni })
                    .then(function (data) {
                        $(".firstStep").addClass("d-none");
                        if (!data.exists) {
                            $(".secondStep").removeClass("d-none");
                            var _html = "<h4 class='lblSubTitle'>¿Sos alguna de estas personas?</h4>";
                            $.each(data.names, function (i, obj) {
                                _html += "<a href='#' data-viable='" + obj.viable + "' class='my-3 btn btn-block btn-raised btn-secondary btn-i-am'>" + obj.name.replace(",", "") + "</a>";
                            });
                            _html += "<a href='#' class='mt-2 btn-data-page btn btn-danger btn-raised' style='border-width:0px;' data-direction='normal' data-href='/includes/Comun/login.html'>No soy ninguna de estas personas</a>";
                            $(".selectName").html(_html);
                        } else {
                            $(".lastStep").removeClass("d-none");
                        }
                    });
                break;
            case "last":
                _NMF._auth_user_data.id = 0;
                _NMF._auth_user_data.email = _email;
                _NMF._auth_user_data.sex = $(".sex").val();
                _NMF._auth_user_data.area = $(".area").val();
                _NMF._auth_user_data.phone = $(".phone").val();
                _NMF._auth_user_data.password = $(".password").val();
                firebase.auth().signInWithEmailAndPassword(_email, "123456")
                    .then(function (data) {
                        _DB.Set("logged", _NMF._auth_user_data);
                        _NMF.onAuthenticateMobile(_NMF._auth_user_data);
                    })
                    .catch(function (error) {
                        firebase.auth().createUserWithEmailAndPassword(_email, "123456")
                            .then(function (data) {
                                firebase.auth().signInWithEmailAndPassword(_email, "123456").then(function (data) {
                                    _DB.Set("logged", _NMF._auth_user_data);
                                    _NMF.onAuthenticateMobile(_NMF._auth_user_data);
                                });
                            })
                            .catch(function (error) {
                                _NMF.onModalAlert("Alerta", "Usuario y/o contraseña erróneas.  Reintente.", "danger");
                                $(".app-login").fadeIn("fast");
                            });
                    });
                break;
        }
    },
    onActionLogout: function (_this) {
        $("#bodyMain").fadeOut(2000);
        firebase.auth().signOut().then(function () { }, function (error) {
            _NMF.onModalAlert("Error", JSON.stringify(error), "danger");
        });
        _DB.Remove("logged");
        window.location = "/";
    },
    onActionExit: function (_this) {
        if (!$("#chkRecordarme").prop("checked")) { _NMF.onActionLogout(null); }
        window.close();
    },
    onActionForget: function (_this) {
        if (!_TOOLS.validate(".validateForget")) {
            _NMF.onModalAlert("Datos faltantes", "Complete los datos requeridos", "warning");
            return false;
        }
        var _email = ($("#dni").val() + _HTTPREQUEST._sufixEmail);
        var _json = { "id": 0, "email": _email, "module": "mod_backend", "table": "users", "model": "users" };
        _API.UiDelete(_json).then(function (datajson) {
            if (datajson.status == "OK") {
                firebase.auth().signInWithEmailAndPassword(_email, "123456")
                    .then(function (data) {
                        firebase.auth().currentUser.delete();
                    }).catch(function (error) { });
                _NMF.onModalAlert("Información", "Debe realizar el proceso de registro nuevamente.", "info");
                _NMF.onActionLogout(null);
            }
        });
    },
    onIsNotSocio: function (_msg) {
        if (!_NMF._logged) {
            _NMF.onModalAlert("Alerta", "Por favor, inicie sesión para solicitar servicios", "warning");
            setTimeout(function () { $(".btnLogin").click(); }, 250);
            return false;
        }
        _NMF.onModalAlert("Alerta", _msg, "info");
        window.open("https://clubredondo.com.ar/Landings_ClubRedondo/leads.html#header3-z", "_blank");
    },
    onVerPanCabal: function (_this, _show) {
        var str = _NMF._session_data.PANCABAL;
        if (!_show) { str = _NMF._session_data.lastPANCABAL; }
        var parts = str.match(/.{1,4}/g);
        var _formatted = parts.join("  ");
        $(".PANCABAL").html(_formatted);
    },
};
if (window.location.hostname != "localhost") { _NMF._server = "./"; }
