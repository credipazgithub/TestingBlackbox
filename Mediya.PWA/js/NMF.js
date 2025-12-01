var _NMF = {
    showLinkAutorizaciones: true,
    _joined: false,
    _showPopup:true,
    _cdn_server: "https://cdn.gruponeodata.com/",
    urlPago: "",
    _reconectaVideo:false,
    _dataSwiss: null,
    _dataGerdanna: null, 
    _credentialsReady:false,
    _pagination_actual_value: 1,
    _map_icon: "",
    _lastjson_getCupons: null,
    deferredPrompt: null,
    _last_image: "",
    _default_page: "",
    _bInstalling: false,
    id_category: 0,
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
    _timer_telemedicina: 0,
    _id_charge_code: 0,
    _id_credipaz: 0,
    _nombre: "",
    _TIMER_LAZY: 0,
    _TMR_IMAGES: 0,
    _TMR_STATUS:0,
    _video_active: false,
    _itemsPagos: [],
    _credencial: "",
    _credencialSwiss: "",
    _credencialMediYa: "",
    mode_categoria: "",
    type_categoria: "",
    _TEST_DNI: 20734796,

    /* Funciones generales */
    readConfigServers: function (_key) {
        return new Promise(
            function (resolve, reject) {
                fetch("./Recursos/configServers.json?" + _TOOLS.UUID())
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
        /*DEfault access visible point*/
        $(".page-sections").load(_NMF._default_page, function () {
            $(".app-header").hide();
            $(".app-beneficios").hide();
            $(".app-footer").hide();
            setTimeout(function () {
                $(".app-header").removeClass("d-none").fadeIn("slow");
                $(".app-beneficios").removeClass("d-none").fadeIn("slow");
                $(".app-footer").removeClass("d-none").fadeIn("slow");
            }, 10);
        });
    },
    onToggleVisibilityByStatus: function () {
        if (_NMF._logged) {
            _NMF._showPopup = false;
            $(".loged").removeClass("d-none");
            $(".notloged").addClass("d-none");
        } else {
            setTimeout(function () {
                if (_NMF._showPopup) {
                    var _html = "<a href='#' class='btn-data-page initAsociate btnAsociate' data-direction='normal' data-href='./includes/MediYa/asociarme.html'>";
                    _html += "<img src='img/banner.jpg' style='width:100%;'/>"
                    _html += "</a>";
                    _NMF.onShowHtmlModal(_html, function () {
                        _NMF._showPopup = false;
                        setTimeout(function () {
                            _NMF.onDestroyModal("#modal-html");
                        }, 5000);
                        
                    });
                }
            },1000)
            $(".loged").addClass("d-none");
            $(".notloged").removeClass("d-none");
        }
   },
    onOpenTelemedicina: function () {
        var d = new Date();
        var _hour = d.getHours();
        var _day = d.getDay();
        if (_NMF._session_data.TelemedicinaDiasAtencion.indexOf(_day.toString()) === -1) { return false; }
        if (_hour < _NMF._session_data.TelemedicinaHoraDesde || _hour >= _NMF._session_data.TelemedicinaHoraHasta) { return false; }
        return true;
    },
    onResizeInner: function () {
        $(".page-footer").removeClass("d-none");
        var _h = (screen.height - $(".page-header").height() - $(".page-footer").height()) + 20;
        $(".inner-page").css("height", (_h + "px"));
    },
    onSeeMap: function (_this) {
        $(".see-listado").removeClass("d-none");
        $(".see-map").addClass("d-none");
        $(".maparea").removeClass("d-none");
        $(".results").addClass("d-none");
        _NMF.onDrawMap(".maparea");
    },
    onSeeListado: function (_this) {
        clearInterval(_NMF._TMR_IMAGES);
        $(".see-map").removeClass("d-none");
        $(".see-listado").addClass("d-none");
        $(".results").removeClass("d-none");
        $(".map").addClass("d-none");
    },
    onPageHeader: function (_target, _title, _img, _back, _map) {
        $(_target).hide();
        var _rest = 11;
        var _html = "";
        var _htmlMap = "";
        _html += "<div class='col-1 pt-2'>";
        _html += "   <a href='#' class='btn-data-page' data-direction='normal' data-href='" + _back + "'>";
        _html += "      <i class='material-icons chevron' style='color:grey;font-size:30px;'>chevron_left</i>";
        _html += "   </a>";
        _html += "</div>";
        if (_img != "") {
            _html += "<div class='col-1 imgTitle'><img src='" + _img + "' style='width:44px;' /></div>";
            _rest -= 1;
        }
        if (_map) {
            _htmlMap = "<div class='col-5 text-right pr-1'>";
            _htmlMap += "<a href='#' class='btn-toggle-near btn-near-map'>Cerca mío</a>";
            _htmlMap += "<a href='#' class='see-map'><img src='./img/Comun/ubicacion.png' style='width:28px;'/></a>";
            _htmlMap += "<a href='#' class='btn-toggle-map see-listado d-none'>Listado</a>";
            _htmlMap += "</div>";
            _rest -= 5;
        }
        _html += "<div class='col-" + _rest + " text-center p-0'><b class='dyn-title'>" + _TOOLS.titleCase(_title) + "</b></div>";
        _html += _htmlMap;

        $(_target).html(_html).fadeIn("fast");
    },
    onCategoriaBeneficios: function (_scope, _target, _id, _title, _img, _back) {
        var _html = "<div class='col-6 p-2 text-center'>";
        _html += "   <a href='#' class='btn-data-page' data-id='" + _id + "' data-img='' data-title='" + _title + "' data-parent='0' data-direction='normal' data-href='./includes/Comun/dynamic.html'>";
        _html += "      <img src='" + _img + "' style='width:100%;' alt='" + _title + "' />";
        _html += "   </a>";
        _html += "</div>";
        $(_target).append(_html);
    },
    onCategoriaMedicina: function (_scope, _target, _id, _title, _img, _back) {
        var _html = "<div class='col-12 p-1 text-center'>";
        _html += "   <button class='px-4 btn-data-page btn-menu-intermedio' data-id='" + _id + "' data-img='' data-title='" + _title + "' data-parent='0' data-direction='normal' data-href='./includes/Comun/dynamic.html' style='width:90%;'>";
        if (_img != "") { _html += "<img src='" + _img + "' style='width:100%;' alt='" + _title + "' />"; }
        _html += _title;
        _html += "   </button>";
        _html += "</div>";
        $(_target).append(_html);
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
                _NMF._credentialsReady = false;
                _NMF.onSetInterface(null);
            }
        });
    },
    onGetUserInformation: function () {
        _API.UiGetUserInformation(_NMF._auth_user_data).then(
            function (data) {
                console.log("UiGetUserInformation XXX1");
                console.log(data);
                var _message = "";
                switch (data.scope) {
                    case "CP":
                        _message = data.message.GetUserInformationResult;
                        break;
                    default:
                        _message = data.message.message.GetUserInformationResult;
                        break;
                }
                if (_message == "") { _message = '{"IdCliente":0}'; }
                _NMF._session_data = JSON.parse(_message);
                _NMF._id_credipaz = parseInt(_NMF._session_data.IdCliente);
                _NMF._nombre = _NMF._session_data.ApellidoNombre;
                if (_NMF._session_data.CodigoArea == undefined) { _NMF._session_data.CodigoArea = ''; }
                if (_NMF._session_data.Telefono == undefined) { _NMF._session_data.Telefono = ''; }
                if (_NMF._session_data.Periodo == undefined) { _NMF._session_data.Periodo = '201903'; }
                if (_NMF._session_data.Archivo == undefined) { _NMF._session_data.Archivo = '0101000231'; }
                if (_NMF._session_data == null) { _NMF._session_data = { "IdCliente": 0 }; }
                if (_NMF._session_data.MontoTelemedicina == undefined) { _NMF._session_data.MontoTelemedicina = 1000; }
                if (_NMF._session_data.TeleMedConsultasResto == undefined) { _NMF._session_data.TeleMedConsultasResto = 0; }
                if (_NMF._session_data.TeleMedicina == undefined) { _NMF._session_data.TeleMedicina = "N"; }
                if (_NMF._session_data.PagoEnLinea == undefined) { _NMF._session_data.PagoEnLinea = 0; }
                if (_NMF._session_data.EspecialidadesMedicas == undefined) { _NMF._session_data.EspecialidadesMedicas = 1; }
                if (_NMF._session_data.CREDENCIALClub == undefined) { _NMF._session_data.CREDENCIALClub = 0; }
                if (_NMF._session_data.CREDENCIALClub == "*") { _NMF._session_data.CREDENCIALClub = 0; }
                if (_NMF._session_data.LogoClub == undefined) { _NMF._session_data.LogoClub = 0; }
                if (_NMF._session_data.SorteoLibre == undefined) { _NMF._session_data.SorteoLibre = 0; }
                if (_NMF._session_data.TelemedicinaHoraDesde == undefined) { _NMF._session_data.TelemedicinaHoraDesde = 8; }
                if (_NMF._session_data.TelemedicinaHoraHasta == undefined) { _NMF._session_data.TelemedicinaHoraHasta = 20; }
                if (_NMF._session_data.TelemedicinaDiasAtencion == undefined) { _NMF._session_data.TelemedicinaDiasAtencion = "1234560"; }
                if (_NMF._session_data.TeleMedicinaRegistrado == undefined) { _NMF._session_data.TeleMedicinaRegistrado = 1; }

                _NMF._session_data.TelemedicinaMensajeDias = "Disponible de lunes a sábados de"; 
                _NMF._session_data.TelemedicinaMensajeCerrado = _NMF._session_data.TelemedicinaMensajeDias + " " + _NMF._session_data.TelemedicinaHoraDesde + " a " + _NMF._session_data.TelemedicinaHoraHasta + "hs."; 
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


                /* default de valores de botones en la interface */
                if (_NMF._session_data.botonTelemedicina == undefined) { _NMF._session_data.botonTelemedicina = 0; }
                if (_NMF._session_data.botonFarmacias == undefined) { _NMF._session_data.botonFarmacias = 0; }
                if (_NMF._session_data.botonUrgencias == undefined) { _NMF._session_data.botonUrgencias = 0; }
                if (_NMF._session_data.botonEspecialidades == undefined) { _NMF._session_data.botonEspecialidades = 0; }
                if (_NMF._session_data.botonLaboratorios == undefined) { _NMF._session_data.botonLaboratorios = 0; }

                _NMF._session_data.botonTelemedicina = parseInt(_NMF._session_data.botonTelemedicina);
                _NMF._session_data.botonFarmacias = parseInt(_NMF._session_data.botonFarmacias);
                _NMF._session_data.botonUrgencias = parseInt(_NMF._session_data.botonUrgencias);
                _NMF._session_data.botonEspecialidades = parseInt(_NMF._session_data.botonEspecialidades);
                _NMF._session_data.botonLaboratorios = parseInt(_NMF._session_data.botonLaboratorios);

                if (parseInt(_NMF._session_data.IdSocio) != 0) {
                    $(".botonTelemedicina").hide();
                    $(".botonFarmacias").hide();
                    $(".botonUrgencias").hide();
                    $(".botonEspecialidades").hide();
                    $(".botonLaboratorios").hide();
                    if (_NMF._session_data.botonTelemedicina == 1) { $(".botonTelemedicina").fadeIn("slow"); }
                    if (_NMF._session_data.botonFarmacias == 1) { $(".botonFarmacias").fadeIn("slow"); }
                    if (_NMF._session_data.botonUrgencias == 1) { $(".botonUrgencias").fadeIn("slow"); }
                    if (_NMF._session_data.botonEspecialidades == 1) { $(".botonEspecialidades").fadeIn("slow"); }
                    if (_NMF._session_data.botonLaboratorios == 1) { $(".botonLaboratorios").fadeIn("slow"); }
                } else {
                    $(".botonTelemedicina").show();
                    $(".botonFarmacias").show();
                    $(".botonUrgencias").show();
                    $(".botonEspecialidades").show();
                    $(".botonLaboratorios").show();
                }
                //Forzar monto cero para dni de prueba!
                if (parseInt(_NMF._auth_user_data.dni) == parseInt(_NMF._TEST_DNI)) {
                    _NMF._session_data.TeleMedConsultasResto = 10;
                    _NMF._session_data.MontoTelemedicina = 0;
                }
                console.log(_NMF._session_data);

            })
            .catch(function (data) {
                console.log(data);
            });
    },

    /* Navegación */
    onChangePage: function (_this) {
        _NMF.onDestroyModal('#modal-html');

        _NMF._pagination_actual_value = 1;
        if (_NMF._actualPage.toUpperCase() == _this.attr("data-href").toUpperCase()) { return false; }
        _NMF._actualPage = _this.attr("data-href");
        clearInterval(_NMF._TMR_IMAGES);
        _NMF.onDestroyModal('#modal_canje');

        /*-------------------------------------------*/
        /* Reset de variables al entar a cada página */
        /*-------------------------------------------*/
        _NMF._video_active = false;
        /*-------------------------------------------*/

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
        if (_this.attr("data-id") != undefined) { _NMF.id_category = _this.attr("data-id"); }
        $(".searcher").attr("data-category", _NMF.id_category);

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
                    _NMF.urlPago = ("https://intranet.credipaz.com/linkDirect/pagos-fiserv-pwacr/" + _TOOLS.utf8_to_b64(_NMF._auth_user_data.dni));
                    $(".linkPago").attr("href", _NMF.urlPago);
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
                console.log("err");
                console.log(err);
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
    onMisCupones: function () {
        return new Promise(
            function (resolve, reject) {
                try {
                    _API.UiGetCupons({ "dni": _NMF._auth_user_data.dni, "mode_categoria": "miscupones" }).then(function (data) {
                        var _html = "";
                        if (data.status == "OK") {
                            $.each(data.message.data, function (i, obj) { try { _html += _NMF.onBuildBeneficioItem(obj, true, true); } catch (err) { } });
                            if (_html == "") { _html = "<span class='badge badge-info text-center' style='font-size:1rem;width:100%;'>Sin canjes activos</span>"; }
                            $(".ls-miscupones").html(_html);
                        }
                        resolve(data);
                    }).catch(function (err) {
                        $(".ls-miscupones").html("");
                        _NMF.onModalAlert("Error", JSON.stringify(err), "danger");
                        reject(err);
                    });
                } catch (err) {
                    reject(err);
                }
            });
    },
    onVerCanje: function (_this) {
        try {
            var obj = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-record")));
            //if (obj.message_canje != null && obj.message_canje != "") { _html += "<p>" + obj.message_canje + "</p>"; }
            //if (obj.des_image != null && obj.des_image != "") { _html += "<img src='" + obj.des_image + "' style='width:100%;' alt='Imagen'/>"; }
            //if (obj.des_sinopsys != null && obj.des_sinopsys != "") { _html += "<p>" + obj.des_sinopsys + "</p>"; }
            //if (obj.des_legales != null && obj.des_legales != "") { _html += "<p>" + obj.des_legales + "</p>"; }

            var _html = "<div class='row no-gutters p-1 mt-2 mb-2 align-items-center'>";
            _html += "      <div class='col-1'>";
            _html += "         <button class='close closeModal m-0 p-0' data-modal='#modal_cupon' style='opacity:1;'>";
            _html += "            <i class='material-icons' style='color:grey;font-size:40px;'>chevron_left</i>";
            _html += "         </button>";
            _html += "      </div>";
            _html += "      <div class='col-11'>";
            _html += "         <div class='row no-gutters p-1' style='border:solid 1px silver;border-radius:10px;'>"
            _html += $(".item-cupon-" + obj.id).html();
            _html += "         </div>";
            _html += "     </div>";
            _html += "</div>";

            _html += "<div style='font-size:0.8em;'>Ya tenés listo tu cupón: Cód.: " + obj.verification + "</div>";
            if (obj.qr_code != null && obj.qr_code != "") { _html += "<div class='text-center'><img src='" + obj.qr_code + "' style='width:70%;' alt='QRCode'/></div>"; }
            var _modal = "<div id='modal_cupon' class='modal fade' style='padding:0px;margin:0px;padding-left:0px;z-index:9999999;width:100vw;height:100vh;'>";
            _modal += "   <div class='modal-dialog modal-lg' style='padding:0px;margin:0px;padding-left:0px;z-index:9999999;width:100vw;height:100vh;'>";
            _modal += "      <div class='modal-content' style='z-index:9999999;width:100vw;height:100vh;'>";
            _modal += "         <div class='modal-body detalle-canje'>" + _html + "</div>";
            _modal += "         <div class='modal-body result-canje d-none'></div>";
            _modal += "      </div>";
            _modal += "   </div>";
            _modal += "</div>";
            $("body").append(_modal);
            $("#modal_cupon").modal({ "show": true, "keyboard": false, "backdrop": "static" });
            $("#modal_cupon").on('d-none.bs.modal', function () { });
        } catch (err) {
            console.log("ERR", err);
        }
        //_NMF.onShowHtmlModal(_html, function () {
        //    var _json = { "id": obj.id_canje, "id_beneficio": obj.id_beneficio, "data_function": "confirmar-canje", "verification": "" };
        //    _API.UiApplicationMobileFunction(_json).then(function (data) { }).catch(function (err) { });
        //});
    },
    onMisRecetas: function (_this) {
        _API.UiRecetasTelemedicina({ "id_charge_code": _NMF._id_charge_code, "request_mode": "actual" })
            .then(function (data) {
                if (data.status == "OK") {
                    var _line = "";
                    var _viewed = "";
                    var _html = "";
                    var _target_recetas = ".actual-telemedicina";
                    var _rec = _TOOLS.utf8_to_b64(JSON.stringify(data.data));
                    $(_target_recetas).attr("data-rec", _rec);
                    _html = "<ul class='list-group'>";
                    $.each(data.data, function (i, obj) {
                        var _str = _TOOLS.utf8_to_b64(JSON.stringify(obj));
                        if (parseInt(obj.viewed) == 0) {
                            _viewed = "<td style='width:30px;'><span class='material-icons' style='color:orange;'>hourglass_empty</span></td>";
                        } else {
                            _viewed = "<td style='width:30px;'><span class='material-icons' style='color:cyan;'>check</span></td>";
                        }
                        switch (parseInt(obj.id_type_item)) {
                            case 2: //receta
                                _line = _viewed + "<td style='width:30px;'><span class='material-icons' style='color:darkred;'>receipt</span></td>";
                                _line += "<td style='width:100%;'>" + obj.description + "</td>";
                                var _classClick = "btn-see-message";
                                if (obj.type_media == "pdf") { _classClick = "btn-see-message-pdf"; }
                                _line += "<td align='right'><a href='#' class='btn btn-sm btn-raised btn-warning " + _classClick + "' data-item='" + _str + "'>Ver</a></td>";
                                break;
                        }
                        _html += "<li>";
                        _html += "   <table style='width:100%;'>";
                        _html += "      <tr>" + _line + "</tr>";
                        _html += "      <tr><td colspan='3' style='font-size:9px;'><i>" + obj.created + "</i></td></tr>";
                        _html += "   </table>";
                        _html += "</li>";
                    });
                    _html += "</ul>";
                    $(".mis-recetas").html(_html);
                }
            });
    },
    onViewDirectTelemedicina: function (_this) {
        try {
            _NMF.onDestroyModal('#telemedicinaModalView');
            var _data = _this.attr("data-item");
            if (_data.indexOf("<div") > -1) {
                _data = _data.split(",")[0];
                _data = _data.split("<")[0];
            }
            var _json = JSON.parse(_TOOLS.b64_to_utf8(_data));
            var _raw_data = JSON.parse(_json.raw_data);
            /*Si es enviado por el usuario, se marca como verificado, leido!*/
            _API.UiViewMessagesTelemedicina({ "id": _json.id }).then(function (data) { });

            var _htmlAdd = "<div class='pt-4'>";
            _htmlAdd += "       <table width='100%' style='margin-top:5px;'>";
            _htmlAdd += "          <tr>";
            _htmlAdd += "            <td width='40%' align='right'>";
            _htmlAdd += "               <button class='noshare btn btn-raised btn-xs btn-info btnGetPDF' data-title='Compartir receta' data-text='Compartir receta' data-source='areapdfreceta' data-orientation='portrait' data-size='A4' data-type='base64' data-file='Receta.pdf'><i class='material-icons'>save</i> Guardar</button>";
            _htmlAdd += "            </td>";
            _htmlAdd += "            <td width='40%' align='left'>";
            _htmlAdd += "               <button class='noshare btn btn-raised btn-xs btn-success btnGetPDF' data-title='Compartir receta' data-text='Compartir receta' data-source='areapdfreceta' data-orientation='portrait' data-size='A4' data-type='share' data-file='Receta.pdf'><i class='material-icons'>share</i> Compartir</button>";
            _htmlAdd += "            </td>";
            _htmlAdd += "          </tr>";
            _htmlAdd += "       </table>";
            _htmlAdd += "</div>";

            var _html = "<div class='modal fade' id='telemedicinaModalView' role='dialog' style='padding:0px;margin:0px;'>";
            _html += " <div class='modal-dialog modal-lg' role='document' style='padding:0px;margin:0px;'>";
            _html += "  <div class='modal-content'>";
            _html += "      <button  class='noshare close btn-close-modal' data-target='#telemedicinaModalView' data-dismiss='modal' style='color:darkred;z-index:999999;position:absolute;right:5px;top:-5px;font-size:3rem;'>&times;</button>";
            _html += _htmlAdd;
            var _body = _json.message;
            _body += "<a href='#' class='btn btn-raised btn-block btn-md btn-info noshare btn-resolve-inicio' data-id_type_vademecum='" + _json.id_type_vademecum + "' data-type='farmacia'>Buscar farmacias</a>";
            if (parseInt(_json.carbon_copy) == 1) { _body += "<hr style='page-break-before:always;background-color:#fff;border-top:2px dashed #8c8b8b;'/>" + _json.message.replace("ORIGINAL", "DUPLICADO"); }
            _html += "    <div id='areapdfreceta' class='modal-body area-pdf-receta' style='margin:0px;padding:10px;border:solid 1px silver;'>" + _body + _htmlAdd + "</div>";
            _html += "    <div class='modal-footer' style='margin:0px;padding:5px;padding-left:10px;padding-right:10px;'></div>";
            _html += "  </div>";
            _html += " </div>";
            _html += "</div>";
            _html = _html.replace('<h4 class="">', "<h4>");
            _html = _html.replace("<h4>Indicaciones</h4>", "<h4>Indicaciones</h4><pre>" + _raw_data.indicacion + "</pre>");
            _html = _html.replace("<h4 class>Indicaciones</h4>", "<h4>Indicaciones</h4><pre>" + _raw_data.indicacion + "</pre>");
            _html = _html.replace('<h4 class="noshare">Indicaciones</h4>', '<h4>Indicaciones</h4><pre>' + _raw_data.indicacion + '</pre>');
            _html = _html.replace('</textarea>', '</textarea><pre style="text-align:left;">' + _raw_data.indicacion + '</pre>');
            _html = _html.replace(/sin-descuento/g, "sin-descuento d-none");

            $("body").append(_html);

            var _css = { "width": "100%", "text-align": "right", "color": "black", "border": "solid 0px transparent", "font-weight": "bold", "font-size": "14px", "margin": "0px", "padding": "0px", "background-color": "white" };
            $(".obra_social").attr("value", _raw_data.obra_social).val(_raw_data.obra_social).prop('disabled', true).css(_css);
            $(".obra_social_plan").attr("value", _raw_data.obra_social_plan).val(_raw_data.obra_social_plan).prop('disabled', true).css(_css);
            $(".nro_obra_social").attr("value", _raw_data.nro_obra_social).val(_raw_data.nro_obra_social).prop('disabled', true).css(_css);
            _css["text-align"] = "left";
            $(".medicamento_1").attr("value", _raw_data.medicamento1).val(_raw_data.medicamento1).prop('disabled', true).css(_css);
            $(".medicamento_2").attr("value", _raw_data.medicamento2).val(_raw_data.medicamento2).prop('disabled', true).css(_css);
            $(".medicamento_1_msg").addClass("d-none");
            $(".medicamento_2_msg").addClass("d-none");

            _css["height"] = "30px";
            _css["overflow-y"] = "d-none";

            $(".indicacion").hide();
            $("#telemedicinaModalView").modal({ backdrop: false, keyboard: true, show: true });
            $("#telemedicinaModalView").css({ "padding": "0px", "margin": "0px" });
            $("input").removeClass("form-control").css(_css);
            return true;
        } catch (rex) {
            _NMF.onModalAlert("Error", rex.message, "danger");
            return false;
        }
    },
    onViewDirectTelemedicinaPDF: function (_this) {
        try {
            _NMF.onDestroyModal('#telemedicinaModalViewPDF');
            var _data = _this.attr("data-item");
            if (_data.indexOf("<div") > -1) {
                _data = _data.split(",")[0];
                _data = _data.split("<")[0];
            }
            var _json = JSON.parse(_TOOLS.b64_to_utf8(_data));
            /*Si es enviado por el usuario, se marca como verificado, leido!*/
            _API.UiViewMessagesTelemedicina({ "id": _json.id }).then(function (data) { });

            var _htmlAdd = "<div class='pt-4'>";
            _htmlAdd += "       <table width='100%' style='margin-top:5px;'>";
            _htmlAdd += "          <tr>";
            _htmlAdd += "            <td width='40%' align='right'>";
            _htmlAdd += "               <button class='noshare btn btn-raised btn-xs btn-info btnGetPDF' data-title='Compartir receta' data-text='Compartir receta' data-source='areapdfreceta' data-orientation='portrait' data-size='A4' data-type='base64' data-file='Receta.pdf'><i class='material-icons'>save</i> Guardar</button>";
            _htmlAdd += "            </td>";
            _htmlAdd += "            <td width='40%' align='left'>";
            _htmlAdd += "               <button class='noshare btn btn-raised btn-xs btn-success btnGetPDF' data-title='Compartir receta' data-text='Compartir receta' data-source='areapdfreceta' data-orientation='portrait' data-size='A4' data-type='share' data-file='Receta.pdf'><i class='material-icons'>share</i> Compartir</button>";
            _htmlAdd += "            </td>";
            _htmlAdd += "          </tr>";
            _htmlAdd += "       </table>";
            _htmlAdd += "</div>";

            var _html = "<div class='modal fade' id='telemedicinaModalViewPDF' role='dialog' style='padding:0px;margin:0px;'>";
            _html += "  <div class='modal-dialog modal-lg' role='document' style='padding: 0px; margin: 0px; height: 100 %;'>";
            _html += "  <div class='modal-content p-0 m-0' style='overflow:auto;'>";
            _html += "    <div id='areapdfreceta' class='modal-body p-0 m-0 area-pdf-receta' style='padding:0px;margin:0px;word-spacing: 5px;letter-spacing:0.05em;'>";
            _html += "       <button  class='noshare close btn-close-modal' data-dismiss='modal' style='color:darkred;font-size:42px;position:absolute;top:5px;right:7px;'>&times;</button>";
            //_html += _htmlAdd.replace(/ /g, "&nbsp;");
            _html += _htmlAdd;
            _html += "       <canvas id='canvas' class='canvaImg'></canvas>";
            _html += "       <img id='exported1' class='exported1' style='width:100%;display:none;'></img>";
            _html += "       <img id='exported2' class='exported2' style='width:100%;display:none;'></img>";
            _html += "    </div>";
            _html += "  </div>";
            _html += " </div>";
            _html += "</div>";
            $("body").append(_html);
            _json.message = (_json.message);
            $(".iframePDF").attr("data", ("<div style='letter-spacing:0.05em;'>" + _json.message + "</div>"));
            //base64PdfData = atob(_json.message.split(",")[1].replace(/ /g, "&nbsp;"));
            base64PdfData = atob(_json.message.split(",")[1]);
            var loadingTask = pdfjsLib.getDocument({ data: base64PdfData });
            var scale = 1;

            loadingTask.promise.then(function (pdf) {
                pdf.getPage(1).then(function (page) {
                    var viewport = page.getViewport({ scale: scale });
                    var canvas = document.getElementById('canvas');
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    var renderContext = { canvasContext: context, viewport: viewport };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function () {
                        $(".exported1").attr("src", canvas.toDataURL()).show();
                        try {
                            pdf.getPage(2).then(function (page) {
                                var viewport = page.getViewport({ scale: scale });
                                var canvas = document.getElementById('canvas');
                                var context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                var renderContext = { canvasContext: context, viewport: viewport };
                                var renderTask = page.render(renderContext);
                                renderTask.promise.then(function () {
                                    $(".exported2").attr("src", canvas.toDataURL()).show();
                                });
                            });
                        } catch (err) { } finally {
                            $(".canvaImg").hide();
                        }
                    });
                });
            });

            $("#telemedicinaModalViewPDF").modal({ backdrop: false, keyboard: true, show: true });
            $("#telemedicinaModalViewPDF").css({ "padding": "0px", "margin": "0px" });
            return true;
        } catch (rex) {
            _NMF.onModalAlert("Error", rex.message, "danger");
            return false;
        }
    },
    onMisComprobantes: function (_this) {
        _API.UiComprobantesTelemedicina({})
            .then(function (data) {
                if (data.status == "OK") {
                    $(".mis-recibos").html(_NMF.onDrawRecibos(data.recibos, "recibos", "receipt_long"));
                    $(".mis-facturas").html(_NMF.onDrawFacturas(data.facturas, "facturas", "receipt"));
                }
            });
    },
    onBtnComprobantes: function (_this) {
        var _mode = _this.attr("data-mode");
        $(".mis-comprobantes").addClass("d-none");
        $(".mis-" + _mode).removeClass("d-none");
        $(".btn-comprobantes").css("border", "solid 0px cyan");
        _this.css("border", "solid 2px cyan");
    },
    onDrawRecibos: function (data, _type, _icon) {
        var _line = "";
        var _viewed = "";
        var _html = "";
        _html = "<table width='100%'>";
        $.each(data.data, function (i, obj) {
            var _str = _TOOLS.utf8_to_b64(JSON.stringify(obj));
            if (obj.id_payment == null) { obj.id_payment = 999999; }
            _html += "<tr>";
            _html += "   <td width='80%'>";
            _html += "      <a href='#' class='btn btn-sm btn-raised btn-block btn-primary btn-see-comprobante' data-mode='" + _type + "' data-item='" + _str + "'>" + _TOOLS.toDDMMYY(obj.created, "-") + "</a>";
            _html += "   </td>";
            _html += "</tr>";
        });
        _html += "</table>";
        return _html;
    },
    onDrawFacturas: function (data, _type, _icon) {
        var _line = "";
        var _viewed = "";
        var _html = "";
        _html = "<table width='100%'>";
        $.each(data.message, function (i, obj) {
            obj.logoAFIP = data.message.logoAFIP;
            var _str = _TOOLS.utf8_to_b64(JSON.stringify(obj));
            if (obj.id_payment == null) { obj.id_payment = 999999; }
            if (obj.NroComprobante != undefined) {
                _html += "<tr>";
                _html += "   <td width='80%'>";
                _html += "      <a href='#' class='btn btn-sm btn-raised btn-block btn-primary btn-see-comprobante' data-mode='" + _type + "' data-item='" + _str + "'>Nº " + obj.NroComprobante + "</a>";
                _html += "   </td>";
                _html += "   <td align='right'> <b>$ " + obj.Importe + "</b></td>";
                _html += "</tr>";
            }
        });
        _html += "</table>";
        return _html;
    },
    onSeeComprobante: function (_this) {
        var _type = _this.attr("data-mode");
        try {
            _NMF.onDestroyModal('#telemedicinaComprobante');
            var _data = _this.attr("data-item");
            if (_data.indexOf("<div") > -1) {
                _data = _data.split(",")[0];
                _data = _data.split("<")[0];
            }
            var _raw_data = JSON.parse(_TOOLS.b64_to_utf8(_data));
            var _title = "";
            var _numero = "";
            var _body = "";
            switch (_type) {
                case "recibos":
                    _title = "RECIBO";
                    _body = _TOOLS.b64_to_utf8(_raw_data.base64_2);
                    break;
                case "facturas":
                    _title = "FACTURA";
                    var _ptoVta = _TOOLS.LPAD(_raw_data.Prefijo, '0', 5);
                    var _nroCmp = _TOOLS.LPAD(_raw_data.NroComprobante, '0', 8);
                    _raw_data.Fecha = _raw_data.Fecha.replace(" ", "T");
                    _body += "<div style='width:100%;border:solid 1px black;'>";
                    _body += "   <table width='100%'>";
                    _body += "      <tr>";
                    _body += "         <td align='center' style='border-bottom:solid 1px black;'><b>ORIGINAL</b></td>";
                    _body += "      </tr>";
                    _body += "   </table>";
                    _body += "   <table width='100%'>";
                    _body += "      <tr>";
                    _body += "         <td align='left' style='width:40%;padding-left:10px;' valign='top'><b>MEDIYA S.A.</b></td>";
                    _body += "         <td align='center' style='width:20%;border:solid 1px black;font-size:16px;' valign='top'><b>B</b></br><span style='font-size:7px;'>COD.006</span></td>";
                    _body += "         <td align='left' style='width:40%;padding-left:10px;' valign='top'><b>FACTURA</b></td>";
                    _body += "      </tr>";
                    _body += "   </table>";
                    _body += "   <table width='100%'>";
                    _body += "      <tr>";
                    _body += "         <td align='left' style='width:50%;padding-left:5px;font-size:9px;border-bottom:solid 1px black;border-right:solid 1px black;'>";
                    _body += "            <table width='100%'>";
                    _body += "               <tr><td valign='top'><b>Razón Social:</b></td><td valign='top'>MEDIYA S.A</td></tr>";
                    _body += "               <tr><td valign='top'><b>Domicilio Comercial:</b></td><td valign='top'>Sarmiento 552 Piso: 17 - CABA</td></tr>";
                    _body += "               <tr><td valign='top'><b>Condición frente al IVA:</b></td><td valign='top'><b>IVA Responsable Inscripto</b></td></tr>";
                    _body += "            </table>";
                    _body += "         </td>";
                    _body += "         <td align='left' style='width:50%;padding-left:5px;font-size:9px;border-bottom:solid 1px black;'>";
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
                    _body += "   <table width='100%' style='font-size:9px;'>";
                    _body += "      <tr>";
                    _body += "         <td align='left' style='padding:2px;'>";
                    _body += "            <table cellpadding='3'>";
                    _body += "               <tr>";
                    _body += "                  <td valign='bottom'><b>Facturado Desde:</b></td>";
                    _body += "                  <td valign='bottom'>" + _TOOLS.toDDMMYY(_raw_data.Fecha, "-") + "</td>";
                    _body += "                  <td valign='bottom'><b>Hasta:</b></td>";
                    _body += "                  <td valign='bottom'>" + _TOOLS.toDDMMYY(_raw_data.Fecha, "-") + "</td>";
                    _body += "                  <td valign='bottom'><b>Fecha de Vto.para el pago:</b></td>";
                    _body += "                  <td valign='bottom'></td>";
                    _body += "               </tr>";
                    _body += "            </table>";
                    _body += "         </td>";
                    _body += "      </tr>";
                    _body += "   </table>";
                    _body += "</div>";

                    _body += "<div style='width:100%;margin-top:2px;border:solid 1px black;'>";
                    _body += "   <table width='100%' style='font-size:9px;'>";
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

                    _body += "<div style='width:100%;margin-top:2px;'>";
                    _body += "   <table width='100%' style='font-size:8px;'>";
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

                    _body += "<div style='width:100%;border:solid 1px black;margin-top:5px;'>";
                    _body += "   <table style='font-size:10px;font-weight:bold;width:100%;'>";
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

                    _body += "<div style='width:100%;margin-top:5px;'>";
                    _body += "   <table width='100%' style='font-size:10px;'>";
                    _body += "      <tr>";
                    _body += "         <td><b>CAE Nº: </b></td>";
                    _body += "         <td>" + _raw_data.CAE + "</td>";
                    _body += "         <td><b>Fecha de Vto. de CAE: </b></td>";
                    _body += "         <td>" + _raw_data.VtoCAE + "</td>";
                    _body += "      </tr>";
                    _body += "   </table>";
                    _body += "   <table width='100%' style='font-size:9px;'>";
                    _body += "      <tr>";
                    _body += "         <td width='40%'><div id='qr1'/></td>";
                    _body += "         <td width='40%'><img src='" + _raw_data.logoAFIP + "' style='width:100%;' /></td>";
                    _body += "      </tr>";
                    _body += "      <tr>";
                    _body += "         <td colspan='2'><i>Esta Administración Federal no se responsabiliza por los datos ingresados en el detalle de la operación</i></td>";
                    _body += "      </tr>";
                    _body += "   </table>";
                    _body += "</div>";

                    break;
            }
            var _htmlAdd = "<hr class='noshare' style='background-color:#fff;border-top:2px dashed #8c8b8b;'/>";
            var _htmlAdd = "<div class='pt-4'>";
            _htmlAdd += "       <table width='100%' style='margin-top:5px;'>";
            _htmlAdd += "          <tr>";
            _htmlAdd += "            <td width='40%' align='right'>";
            _htmlAdd += "               <button class='noshare btn btn-raised btn-xs btn-info btnGetPDF' data-title='Compartir factura' data-text='Compartir factura' data-source='areapdfreceta' data-orientation='portrait' data-size='A4' data-type='base64' data-file='Factura.pdf'><i class='material-icons'>save</i> Guardar</button>";
            _htmlAdd += "            </td>";
            _htmlAdd += "            <td width='40%' align='left'>";
            _htmlAdd += "               <button class='noshare btn btn-raised btn-xs btn-success btnGetPDF' data-title='Compartir factura' data-text='Compartir factura' data-source='areapdfreceta' data-orientation='portrait' data-size='A4' data-type='share' data-file='Factura.pdf'><i class='material-icons'>share</i> Compartir</button>";
            _htmlAdd += "            </td>";
            _htmlAdd += "          </tr>";
            _htmlAdd += "       </table>";
            _htmlAdd += "</div>";

            var _html = "<div class='modal fade' id='telemedicinaComprobante' role='dialog' style='padding:0px;margin:0px;z-index:999998;'>";
            _html += " <div class='modal-dialog modal-lg' role='document' style='padding:0px;margin:0px;height:100%;z-index:999999;'>";
            _html += "  <div class='modal-content'>";
            _html += "    <button  class='noshare close btn-close-modal' data-dismiss='modal' style='color:darkred;font-size:42px;position:absolute;top:20px;right:0px;'>&times;</button>";
            _html += _htmlAdd;
            _html += "    <div id='areapdfreceta' class='modal-body area-pdf-factura' style='margin:0px;padding:10px;border:solid 1px silver;'>" + _body + "</div>";
            _html += "    <div class='modal-footer' style='margin:0px;padding:5px;padding-left:10px;padding-right:10px;'></div>";
            _html += "  </div>";
            _html += " </div>";
            _html += "</div>";

            $("body").append(_html);

            switch (_type) {
                case "facturas":
                    var _param = _TOOLS.utf8_to_b64(JSON.stringify(_raw_data.QR));
                    $("#qr1").ClassyQR({ create: true, type: 'url', size: 110, url: 'https://www.afip.gob.ar/fe/qr?p=' + _param });
                    break;
            }
            $("#telemedicinaComprobante").modal({ backdrop: false, keyboard: true, show: true });
            $("#telemedicinaComprobante").css({ "padding": "0px", "margin": "0px" });
            return true;
        } catch (rex) {
            _NMF.onModalAlert("Error", rex.message, "danger");
            return false;
        }
    },
    onGetPDF: function (_this) {
        var _docArea = _this.attr("data-source");
        var _documentSize = _this.attr("data-size");
        var _type = _this.attr("data-type");
        var _landscape = _this.attr("data-orientation");
        var _fileName = _this.attr("data-file");
        var _title = _this.attr("data-title");
        var _text = _this.attr("data-text");

        $(".noshare").hide();
        $(".indicacion").prop('disabled', false);
        $(_this.attr("data-source")).css({ "width": "200mm", "height": "251mm" });
        var _footer = '<div style="width:100%;position:absolute;left:0px;bottom:0px;" class="added-footer"><img src="' + $(".img-footer").attr("src") + '" style="width:100%;"/></div>';
        $("." + _docArea).append(_footer);

        $(".indicacion").prop('disabled', true);
        _TOOLS.HtmlToPdfFile(_type, ("#" + _docArea), _fileName, _title, _text, function (data) {
            _NMF.onDestroyModal('#telemedicinaModalView');
            _NMF.onDestroyModal('#telemedicinaModalViewPDF');
            _NMF.onDestroyModal('#telemedicinaComprobante');
            if (!data) { _NMF.onModalAlert("Alerta", "Su dispositivo no permite compartir archivos.", "info"); }
        });
    },
    onVerCupon: function (_this, _canjea) {
        var _html = "";
        _NMF._last_image = $("." + _this.attr("data-img-ref")).attr("src");

        $(".area-cupon").html("").hide();
        var obj = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-record")));

        var _address = $(".item-only_address-" + obj.id).html();
        var _ciudad = $(".item-ciudad-" + obj.id).html();
        var _localidad = $(".item-localidad-" + obj.id).html();
        var _provincia = $(".item-provincia-" + obj.id).html();
        var _phone = $(".item-phone-" + obj.id).html();

        var _address_add = (_address + " " + _ciudad);

        if (_NMF._last_image != "" && _NMF._last_image != undefined) {
            _html += "<table align='center' style='width:80%;color:grey;'>";
            _html += "   <tr>";
            _html += "      <td valign='middle' align='center'>";
            _html += "         <img class='img-beneficio' src='" + _NMF._last_image + "' style='width:80%;'/>";
            _html += "      </td>";
            _html += "   </tr>";
            _html += "</table>";
        } else {
            _html += "<br/>";
            _html += "<br/>";
        }
        _html += "<div class='card p-2 mt-2 shadow-sm'>";
        _html += "<table align='center' style='width:100%;color:grey;font-size:1rem;'>";
        _html += "   <tr>";
        _html += "      <td align='left' colspan='2'><b>" + obj.description + "</b></td>";
        _html += "   </tr>";
        if (_address != "" && _address != undefined) {
            _html += "<tr>";
            _html += "   <td><img src='./img/Comun/me.png' style='width:24px;'/></td><td align='left'>" + _address_add + "</td>";
            _html += "<tr>";
            _html += "</tr>";
            _html += "   <td></td><td align='left'>" + _localidad + " " + _provincia + "</td>";
            _html += "</tr>";
        }
        if (_phone != "" && _phone != undefined) {
            _html += "<tr>";
            _phone = _phone.replace("Tel.", "");
            _phonelink = _phone.split(" // ")[0];
            _phonelink = _phonelink.replace("-", "").trim();
            _phonelink = _phonelink.split(" ")[0];
            _html += "   <td><img src='./img/Mediya/0800_azul.png' style='width:24px;'/></td><td align='left'><a href='tel:" + _phonelink + "'>" + _phone + "</td>";
            _html += "</tr>";
        }

        if (obj.sinopsys == "null" || obj.sinopsys == null || obj.sinopsys == "") { obj.sinopsys = obj.sinopsys_local; }
        if (obj.sinopsys != "null" && obj.sinopsys != null && obj.sinopsys != "") { _html += "<tr><td align='center' colspan='2'><br/>" + obj.sinopsys + "</td></tr>"; }
        if (obj.legales != "null" && obj.legales != null && obj.legales != "") { _html += "   <tr><td align='center' colspan='2'>" + obj.legales + "</td></tr>"; }
        _html += "</table>";
        _html += "</div>";
        _html += "<input type='hidden' id='id_canje' name='id_canje' value='" + obj.id_canje + "'/>";
        _html += "<input type='hidden' id='id_beneficio' name='id_beneficio' value='" + obj.id + "'/>";
        _html += "<input type='hidden' id='type_beneficio' name='id_type_beneficio' value='" + obj.type_beneficio + "'/>";
        _html += "<input type='hidden' id='id_type_status_canje' name='id_type_beneficio' value='" + obj.id_type_status_canje + "'/>";
        _html += "<input type='hidden' id='id_type_beneficio' name='id_type_beneficio' value='" + obj.id_type_beneficio + "'/>";
        _html += "<input type='hidden' id='type_execution' name='id_type_execution' value='" + obj.type_execution + "'/>";
        _html += "<input type='hidden' id='id_type_execution' name='id_type_execution' value='" + obj.id_type_execution + "'/>";
        _html += "<input type='hidden' id='verification' name='verification' value='" + obj.verification + "'/>";
        _html += "<button class='close closeModal p-1 px-3' data-modal='#modal_canje' style='position:absolute;left:0px;top:0px;opacity:1;z-index:999999;'>";
        _html += "   <i class='material-icons' style='color:grey;font-size:40px;'>chevron_left</i>";
        _html += "</button><br/>";
        if (_NMF.showLinkAutorizaciones) { _html += "<center><a href='mailto:autorizaciones@gerdanna.com' class='btn btn-success btn-sm' target='_blank'>Autorización de estudios</a></venter>"; }
        var _modal = "<div id='modal_canje' class='modal fade' style='padding:0px;margin:0px;padding-left:0px;z-index:9999999;width:100vw;height:100vh;'>";
        _modal += "   <div class='modal-dialog modal-lg' style='padding:0px;margin:0px;padding-left:0px;z-index:9999999;width:100vw;height:100vh;'>";
        _modal += "      <div class='modal-content' style='z-index:9999999;width:100vw;height:100vh;'>";
        _modal += "         <div class='modal-body detalle-canje p-2'>" + _html + "</div>";
        _modal += "         <div class='modal-body result-canje p-2 d-none'></div>";
        _modal += "      </div>";
        _modal += "   </div>";
        _modal += "</div>";
        $("body").append(_modal);
        $("#modal_canje").modal({ "show": true, "keyboard": false, "backdrop": "static" });
        $("#modal_canje").on('d-none.bs.modal', function () { });
    },
    onIsNotSocio: function (_msg) {
        if (!_NMF._logged) {
            _NMF.onModalAlert("Alerta", "Por favor, inicie sesión para solicitar servicios", "warning");
            setTimeout(function () { $(".btnLogin").click(); }, 250);
            return false;
        }
        _NMF.onModalAlert("Alerta", _msg, "info");
        window.open("https://mediya.com.ar/asociate/", "_blank");
    },
    onSearchFromBar: function (_this) {
        _NMF._pagination_actual_value = 1;
        _NMF.id_category = _this.attr("data-category");
        _NMF.onGoForBeneficios("buscar", _NMF.id_category, _this.val(), 0);
    },
    onGoForBeneficios: function (_mode_categoria, _id_type_category, _coords, _near) {
        var _line = "<div class='row py-1'><div class='col-3'><div class='skeleton line-loader'></div></div><div class='col-9'><div class='skeleton line-loader'></div></div></div>";
        var _html = "";
        setTimeout(function () {
            for (var i = 0; i < 10; i++) { _html += _line; }
            $(".results").html(_html).show();
        }, 10);
        _NMF.mode_categoria = _mode_categoria;
        _NMF.type_categoria = _id_type_category;
        if (_GMAP._TRACK_POSITION == null) { _GMAP.onDetect(); }
        setTimeout(function () {
            var _lat = 0;
            var _lng = 0;
            try {
                _lat = _GMAP._TRACK_POSITION.lat;
                if (_lat == null) { _lat = 0; }
                _lng = _GMAP._TRACK_POSITION.lng;
                if (_lng == null) { _lng = 0; }
            } catch (e) { }
            _NMF._lastjson_getCupons = {
                "mode_categoria": _NMF.mode_categoria,
                "type_categoria": _NMF.type_categoria,
                "search": "",
                "coords": _coords,
                "near": _near,
                "lat": _lat,
                "lng": _lng,
                "id_type_vademecum": -1,
                "page": _NMF._pagination_actual_value
            };
            _API.UiGetCupons(_NMF._lastjson_getCupons).then(function (data) {
                _NMF.onBuildBeneficiosResults(data);
            }).catch(function (err) {
                _NMF.onModalAlert("Error", JSON.stringify(err), "danger");
            });
        }, 250);
    },
    onNextResultPage: function (_this) {
        _NMF._pagination_actual_value += 1;
        _NMF._lastjson_getCupons.page = _NMF._pagination_actual_value;
        $(".infinite-loader").fadeOut("slow");
        _API.UiGetCupons(_NMF._lastjson_getCupons).then(function (data) {
            $(".infinite-loader").remove();
            var _h = $(".inner-page").scrollTop();
            _NMF.onBuildBeneficiosResults(data);
            $(".inner-page").animate({ scrollTop: (_h + 300) }, 500);
        }).catch(function (err) {
            _NMF.onModalAlert("Error", JSON.stringify(err), "danger");
        });
    },
    onBuildBeneficiosResults(data) {
        if (data.status == "OK") {
            var _html = "";
            //marcar cuando NO se hace canje de cupon
            var _canjea = true;

            switch (parseInt(_NMF.type_categoria)) {
                case -125://Farmacias
                case 323://Oftalmología
                case 324://Otorrino
                case 325://Urología
                case 326://Neurología
                case 327://Neumonología
                case 328://Alergia
                case 329://Anatomía patológica
                case 330://Endoc.y nutrición
                case 331://Rehabilitación médica
                case 332://Odontología
                case 333://Pediatría
                case 334://Cardiología
                case 335://Traumatología
                case 336://Ginecología
                case 337://Clínica médica
                case 338://Análisis clínicos
                case 339://Radiografías
                case 340://Imágenes
                    _canjea = true;
                    break;
                default:
                    _canjea = true;
                    break;
            }
            $.each(data.message.data, function (i, obj) { try { _html += _NMF.onBuildBeneficioItem(obj, _canjea, false); } catch (err) { } });
            if (data.message.totalpages == 0) {
                _html += "<div class='no-gutters text-center footer-text'>";
                _html += "   <span class='badge badge-secondary p-2' style='font-size:0.75rem;'>Sin datos para esta consulta</span>";
                _html += "</div>";
            } else {
                if (data.message.page != data.message.totalpages) {
                    _html += "<div class='infinite-loader no-gutters pt-3 text-center footer-text align-items-center'>";
                    _html += "   <a href='#' class='btnInfiniteLoader'><i class='material-icons'>downloading</i> Ver más</a>";
                    _html += "</div>";
                }
            }
            clearInterval(_NMF._TMR_IMAGES);
            _NMF._TMR_IMAGES = setInterval(function () { _NMF.onLoadImagesInScreen(".img-loader"); }, 500);
            if (_NMF._pagination_actual_value == 1) {
                $(".results").html(_html).fadeIn("fast");
            } else {
                $(".results").append(_html).fadeIn("fast");
            }
            $(".res-map").removeClass("d-none");
        }
    },
    onBuildBeneficioItem: function (obj, _canjea, _verCupon) {
        try {
            console.log("obj");
            console.log(obj);


            /*parsing and defaults*/
            if (obj.description == "null" || obj.description == null || obj.description == "S/D") { obj.description = ""; }
            if (obj.amount == "null" || obj.amount == null) { obj.amount = ""; }
            if (obj.address == "null" || obj.address == null || obj.address == "S/D" || obj.address == "") { obj.address = ""; }
            if (obj.localidad == "null" || obj.localidad == null || obj.localidad == "S/D" || obj.localidad == "") { obj.localidad = ""; }
            if (obj.provincia == "null" || obj.provincia == null || obj.provincia == "S/D" || obj.provincia == "") { obj.provincia = ""; }
            if (obj.phone == "null" || obj.phone == null || obj.phone == "S/D" || obj.phone == "") { obj.phone = ""; }
            if (obj.cellphone == "null" || obj.cellphone == null || obj.cellphone == "S/D" || obj.cellphone == "") { obj.cellphone = ""; }

            var _html = "";
            var _image = obj.image;
            var _phone = "";
            var _distance = "";
            var _amount = _TOOLS.stripHtml(obj.amount);
            var _kms = Math.round((obj.kms + Number.EPSILON) * 100) / 100;
            var _description = obj.description;
            var _address = obj.address;
            var _only_address = obj.address;
            var _ciudad = obj.ciudad; 
            var _provincia = obj.provincia; 
            var _localidad = obj.localidad; 
            var _canjeado = "";
            var _classForCanje = "btnVerCupon";
            if (!_canjea) { _classForCanje = "btnVerCuponNoCanjea"; }
            if (_verCupon) {
                _image = obj.des_image;
                _amount = _TOOLS.stripHtml(obj.des_amount);
                _canjeado = ("Canjeado el " + obj.date_canje);
                _classForCanje = "btn-vercanje";
            }
            if (!_canjea) { _image = "./img/Comun/item_" + _NMF.type_categoria + ".png"; }
            if (_address != "") {
                if (obj.ciudad != "") { _address += ", " + _ciudad; }
                if (obj.provincia != "") { _address += ", " + _provincia; }
                if (obj.localidad != "") { _address += ", " + _localidad; }
                if (!isNaN(_kms)) { if (_kms < 0) { _distance = "¡A " + (_kms * 1000) + " mts de vos!"; } else { _distance = "¡A " + _kms + " kms de vos!"; } }
            }
            if (obj.phone != "") { _phone = "Tel. " + obj.phone; }
            if (obj.cellphone != "") { if (_phone != "") { _phone += ", "; }; _phone += ("Cel. " + obj.cellphone); }

            _html = "<div class='row no-gutters py-1 " + _classForCanje + " item-cupon item-cupon-" + obj.id + "' data-img-ref='img-" + obj.id + "' data-record='" + _TOOLS.utf8_to_b64(JSON.stringify(obj)) + "'>";
            _html += "   <div class='img-beneficio col-2 no-gutters text-center'>";
            if (!_canjea || _verCupon) {
                _html += "  <img src='" + _image + "' class='item-cupon-image img-" + obj.id + "' style='width:100%;'/>";
            } else {
                _html += "  <img src='./img/Comun/placeholder.png' class='item-cupon-image img-loader img-" + obj.id + "' data-load='" + _image + "' style='width:100%;'/>";
            }
            _html += "   </div>";

            _html += "   <div class='txt-beneficio col-10 text-left nd-beneficio-" + obj.id + "'>";
            if (_description != "") {
                _html += "  <div class='p-0 m-0 nd-beneficio item-description'><b>" + _description.toUpperCase() + "</b></div>";
                var _vAddress = "";
                var _vAmount = "d-none";
                if (_canjea) { _vAddress = ""; _vAmount = ""; }
                if (_amount != "" && _amount != "0") { _html += "<div class='" + _vAmount + " p-0 m-0 nd-small item-amount item-amount-" + obj.id + "'><b>" + _amount + "</b></div>"; }
                if (_canjeado != "") { _html += "  <div class='" + _vAmount + " p-0 m-0 nd-tiny item-canjeado item-canjeado-" + obj.id + "'>" + _canjeado + "</div>"; }
                if (_address != "") { _html += "<div class='" + _vAddress + " p-0 m-0 nd-small item-address item-address-" + obj.id + "'>" + _address + "</div>"; }
                _html += "<div class='d-none p-0 m-0 nd-small item-only_address item-only_address-" + obj.id + "'>" + _only_address + "</div>"; 
                _html += "<div class='d-none p-0 m-0 nd-small item-ciudad item-ciudad-" + obj.id + "'>" + _ciudad + "</div>"; 
                _html += "<div class='d-none p-0 m-0 nd-small item-provincia item-provincia-" + obj.id + "'>" + _provincia + "</div>"; 
                _html += "<div class='d-none p-0 m-0 nd-small item-localidad item-localidad-" + obj.id + "'>" + _localidad + "</div>"; 
                if (_phone != "") { _html += "<div class='" + _vAddress + " p-0 m-0 nd-small item-phone item-phone-" + obj.id + "'>" + _phone + "</div>"; }
                if (_distance != "") { _html += "  <div class='" + _vAddress + " p-0 m-0 nd-tiny item-distance item-distance-" + obj.id + "'>" + _distance + "</div>"; }
            }
            _html += "   </div>";

            _html += "</div>";
        } catch (err) {
            console.log(err);
        }
        return _html;
    },
    onLoadImagesInScreen: function (_selector) {
        $(_selector).each(function () {
            var obj = $(this);
            if (obj.attr("src") == "./img/Comun/placeholder.png") {
                if (_TOOLS.isElementVisible(obj[0])) {
                    var _data = obj.attr("data-load").split(":");
                    var _json = { "id": _data[1], "type": _data[0] };
                    _API.UiGetImage(_json).then(function (data) {
                        if (data.status == "OK") {
                            $(".sinopsys-" + _data[1]).hide();
                            obj.attr("src", data.data[0].image).fadeIn("fast");
                            $(".sinopsys-" + _data[1]).html(data.data[0].sinopsys).fadeIn("fast");
                        }
                    }).catch(function (err) { });
                }
            }
        });
    },
    onDrawMap: function (_target) {
        clearInterval(_NMF._TMR_IMAGES);
        _GMAP.map = null;
        $(".map").remove();
        $(".dControls").remove();
        $(_target).html("<div id='map' class='map'></div>");
        const searchPane = document.createElement("div");
        searchPane.setAttribute("id", "dControls");
        searchPane.style.width = '80%';
        var _searcher = "<div class='row no-gutters bg-white py-2 shadow style='width:100%;'>";
        _searcher += "      <div class='col-9 no-gutters pt-2'>";
        _searcher += "         <input class='py-2 mapsearch' type='search' id='mapsearch' name='mapsearch' placeholder='Buscar por Provincia, Ciudad...'>";
        _searcher += "      </div>";
        _searcher += "      <div class='col-3 no-gutters pt-2 text-left'>";
        _searcher += "         <a href='#' class='btn btn-sm btn-info btn-raised btn-search-map'>Buscar</a>";
        _searcher += "      </div>";
        _searcher += "   </div>";
        _searcher += "   <div class='items-results col-12 p-0 m-0 bg-white shadow d-none' style='position:absolute;left:0px;top:90px;height:450px;overflow:auto;'></div>";

        searchPane.innerHTML = _searcher;
        _TOOLS.resize();
        _GMAP.onCreateMap();
        _GMAP.map.controls[google.maps.ControlPosition.TOP_LEFT].push(searchPane);
        //_GMAP.onDrawMarkers(null);
    },
    onSearchMap: function (_categoria) {
        if (_GMAP._TRACK_POSITION == null) { _GMAP.onDetect(); }
        setTimeout(function () {
            var _search = $(".mapsearch").val();
            if (_search == "") {
                _NMF.onModalAlert("Alerta", "¡Tenés que buscar por provincia y/o ciudad!", "warning");
                return false;
            }
            var _json = {
                "mode_categoria": _NMF.mode_categoria,
                "type_categoria": _NMF.type_categoria,
                "search": _search,
                "coords": _search,
                "near": 0,
                "lat": _GMAP._TRACK_POSITION.lat,
                "lng": _GMAP._TRACK_POSITION.lng,
                "id_type_vademecum": -1
            };
            _API.UiGetCupons(_json).then(function (data) {
                _NMF.onBuildMapResults(data);
            }).catch(function (err) {
                _NMF.onModalAlert("Error", JSON.stringify(err), "danger");
            });
        }, 250);
    },
    onNearMap: function (_categoria) {
        if (_GMAP._TRACK_POSITION == null) { _GMAP.onDetect(); }
        setTimeout(function () {
            var _json = {
                "mode_categoria": _NMF.mode_categoria,
                "type_categoria": _NMF.type_categoria,
                "search": "",
                "coords": "",
                "near": 1,
                "lat": _GMAP._TRACK_POSITION.lat,
                "lng": _GMAP._TRACK_POSITION.lng,
                "id_type_vademecum": -1
            };
            _API.UiGetCupons(_json).then(function (data) {
                _NMF.onBuildMapResults(data);
            }).catch(function (err) {
                _NMF.onModalAlert("Error", JSON.stringify(err), "danger");
            });
        }, 250);
    },
    onBuildMapResults(data) {
        var _dirty = false;
        if (data.status == "OK") {
            var _html = "";
            _html = "<div class='row no-gutters align-items-center' style='border:solid 0px white;border-bottom:solid 1px silver;'>";
            $.each(data.message.data, function (i, obj) {
                try {
                    _dirty = true;
                    _html += _NMF.onBuildItemMap(obj);
                } catch (err) { }
            });
            _html += "</div>";
            if (_dirty) {
                $(".items-results").html(_html).removeClass("d-none").fadeIn("fast");
            } else {
                var _msg = "No hay nada para mostrar en la zona que buscaste";
                if (parseInt(_NMF.type_categoria) >= 323) { _msg = "Llama al <a href='tel:08103336488'>0810 333 6488</a>"; }
                _NMF.onModalAlert("Alerta", _msg, "warning");
            }
            $.each(_GMAP.markers, function (i, obj) { obj.setMap(null); });
        }
    },
    onBuildItemMap: function (obj) {
        var _html = "";
        var _htmlCupon = "";
        try {
            var _distance = "";
            var _kms = (Math.round((obj.kms + Number.EPSILON) * 100) / 100);
            var _amount = _TOOLS.stripHtml(obj.amount);
            if (obj.amount == "null" || obj.amount == null) { obj.amount = ""; }
            if (obj.description == "null" || obj.description == null || obj.description == "S/D") { obj.description = ""; }
            if (obj.domicilio == "null" || obj.domicilio == null || obj.domicilio == "S/D" || obj.domicilio == "") { obj.domicilio = ""; }
            if (obj.localidad == "null" || obj.localidad == null || obj.localidad == "S/D" || obj.localidad == "") { obj.localidad = ""; }
            if (obj.ciudad == "null" || obj.ciudad == null || obj.ciudad == "S/D" || obj.ciudad == "") { obj.ciudad = ""; }
            if (obj.provincia == "null" || obj.provincia == null || obj.provincia == "S/D" || obj.provincia == "") { obj.provincia = ""; }
            if (!isNaN(_kms)) {
                if (_kms < 0) {
                    var _meters = (_kms * 1000);
                    _distance = ("¡A " + _meters + " mts, de vos!");
                } else {
                    _distance = ("¡A " + _kms + " kms, de vos!");
                }
            } else {
                _distance = "";
            }
            _html = "<div style='cursor:pointer;' class='col-12 no-gutters text-left item-mapa' data-record='" + _TOOLS.utf8_to_b64(JSON.stringify(obj)) + "'>";
            if (obj.description != "null" || obj.description != null || obj.description != "") {
                _html += "<div class='pl-2 pb-1 mt-2 mb-1 nd-beneficio'><b>" + obj.description + "</b></div>";
            }
            _html += "   <div class='p-0 pl-2 my-1 nd-distance'>";
            if (obj.domicilio != "") {
                obj.domicilio = obj.domicilio.replace(obj.localidad, "");
                obj.domicilio = obj.domicilio.replace(obj.ciudad, "");
                obj.domicilio = obj.domicilio.replace(obj.provincia, "");
                obj.provincia = obj.provincia.replace(obj.ciudad, "");
                _html += obj.domicilio + " " + obj.localidad + " " + obj.provincia + " " + obj.ciudad + "<br/>";
                _html += "      <i>" + _distance + "</i>";
            }
            _html += "   </div>";
            _html += "</div>";
        } catch (err) {
            console.log(err);
        }
        return _html;
    },
    onSelectItemMapa: function (_this) {
        var obj = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-record")));
        if (obj.lat != "0" && obj.lng != "0") {
            var _size = { width: 30, height: 36 };
            var latLng = new google.maps.LatLng(obj.lat, obj.lng);
            var _icon = { url: _NMF._map_icon, scaledSize: new google.maps.Size(_size.width, _size.height) };
            var _class = "";//"no-marker-label";
            var _content = "";
            if (obj.description != "null" || obj.description != null || obj.description != "") { _content = obj.description; }
            var marker = new MarkerWithLabel(
                {
                    position: latLng,
                    animation: google.maps.Animation.DROP,
                    draggable: false,
                    map: _GMAP.map,
                    icon: _icon,
                    title: _content,
                    labelContent: _content,
                    labelAnchor: new google.maps.Point(-5, 5),
                    labelClass: _class,
                    labelInBackground: true
                });
            _GMAP.markers.push(marker);
            _GMAP.map.setCenter({ lat: (obj.lat - 0.08), lng: obj.lng });
            marker.addListener('click', function (event) {
                $(".items-results").fadeOut("slow");
                const infowindow = new google.maps.InfoWindow({
                    content: "<div style='width:220px;'>" + _NMF.onBuildItemMap(obj) + "</div>"
                });
                infowindow.addListener('closeclick', function () { $(".items-results").fadeIn("fast"); });
                infowindow.open({ anchor: marker, map });
            });
            $(".items-results").fadeOut("slow");
        }

    },
    onToggleCobertura: function (_this) {
        $(".toggle-salud").attr("src", $(".toggle-salud").attr("data-a"));
        $(".toggle-vida").attr("src", $(".toggle-vida").attr("data-a"));

        if (_this.attr("data-status") == "off") {
            $(".toggle-cobertura").attr("data-status", "off");
            _this.attr("data-status", "on");
            _this.attr("src", _this.attr("data-v"));
            switch (_this.attr("data-cobertura")) {
                case "salud":
                    _API.UiGetWebPosts({ "id": 101 })
                        .then(function (data) {
                            $(".mensaje-cobertura").html(data.data[0].body_post).removeClass("d-none");
                        })
                        .catch(function (err) { console.log(err); });
                    break;
                case "vida":
                    _API.UiGetWebPosts({ "id": 103 })
                        .then(function (data) {
                            $(".mensaje-cobertura").html(data.data[0].body_post).removeClass("d-none");
                            $(".pdf-cobertura").removeClass("d-none");
                        })
                        .catch(function (err) { console.log(err); });
                    break;
            }
        }
    },
    drawCredentialSwiss: function (datajson) {
        $(".area-swiss").html("").removeClass("card-loader").removeClass("skeleton").addClass("d-none");
        $.each(datajson.data, function (i, item) {
            _NMF.drawCredential("canvasSwiss", "./img/Credenciales/swiss.jpg", "swiss", item).then(function (data) {
                _NMF._credencialSwiss = data;
                if (item.NroCredencial != "") { _NMF.drawItemCredential(_NMF._credencialSwiss, item, "swiss"); }
            });
            $(".area-swiss").removeClass("d-none");
            $(".title-credencial").show();
            _NMF._credentialsReady = true;
        });

    },
    drawCredentialGerdanna: function (datajson) {
        $(".area-gerdanna").html("").removeClass("card-loader").removeClass("skeleton").addClass("d-none");
        $.each(datajson.data, function (i, item) {
            _NMF.drawCredential("canvasGerdanna", "./img/Credenciales/gerdanna.jpg", "gerdanna", item).then(function (data) {
                _NMF._credencialGerdanna = data;
                if (item.NroCredencial != "") { _NMF.drawItemCredential(_NMF._credencialGerdanna, item, "gerdanna"); }
            });
            $(".area-gerdanna").removeClass("d-none");
            $(".title-credencial").show();
            _NMF._credentialsReady = true;
        });
    },
    drawCredential: function (_canva, _file, _mode, data) {
        return new Promise(
            function (resolve, reject) {
                try {
                    var canvas = document.createElement('canvas');
                    canvas.width = 640;
                    canvas.height = 406;
                    const ctx = canvas.getContext('2d');
                    let img = new Image();
                    img.addEventListener("load", () => {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.reset();
                        ctx.drawImage(img, 0, 0);
                        var _NroCredencial = data.NroCredencial;
                        var _Nombre = data.Nombre;
                        var _FechaIngreso = data.FechaIngreso;
                        var _FechaNacimiento = data.FechaNacimiento;
                        var _token = data.Token;
                        switch (_mode) {
                            case "clubredondo":
                                break;
                            case "swiss":
                                ctx.fillStyle = "rgb(76, 76, 76)";
                                ctx.font = '25px Lato-black';
                                ctx.fillText(_NroCredencial, 54, 150);
                                ctx.fillText("TOKEN: " + _token, 420, 150);
                                ctx.fillText(_Nombre, 54, 185);
                                ctx.font = '20px Lato-black';
                                ctx.fillText(_FechaIngreso, 110, 222);
                                ctx.fillText(_FechaNacimiento, 320, 222);
                                break;
                            case "gerdanna":
                                ctx.fillStyle = "rgb(255, 255, 255)";
                                ctx.font = '24px Roboto-light';
                                ctx.fillText(_Nombre, 40, 285);
                                ctx.font = '24px Roboto-black';
                                ctx.fillText(_NroCredencial, 40, 320);
                                //ctx.fillText("TOKEN: " + _token, 40, 375);
                                break;
                        }
                        var _b64 = canvas.toDataURL("image/png");
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.reset();
                        resolve(_b64);
                    });
                    img.src = _file;
                } catch (err) {
                    reject(null);
                }
            });
    },
    drawItemCredential: function (data, _record, _tipo) {
        $(".area-" + _tipo).removeClass("skeleton");
        $(".area-" + _tipo).removeClass("card-loader");
        var _html = "";
        _html += "<a href='#' class='btn-data-page' data-direction='normal' data-href='./includes/Comun/credencial" + _tipo + ".html'>";
        _html += "<img class='img-tarjeta p-1' src='" + data + "' alt='Credencial' />";
        _html += "</a>";
        if (parseInt(_record.IDParentesco) == 1) {
            $(".area-" + _tipo).prepend(_html);
        } else {
            $(".area-" + _tipo).append(_html);
        }
    },

    /* Acciones reactivas */
    onEvalCallStatus: function (_json) {
        var _params = _json;
        var _bConnect = false;
        if (_json == null) {
            _bConnect = true;
            _params = {};
        }
        _API.UiStatusTelemedicina(_params)
            .then(function (data) {
                console.log(data);
                console.log("_NMF._joined " + _NMF._joined);
                console.log("_bConnect " + _bConnect);

                if (!_NMF._joined) {
                    $(".inactive-paycode").removeClass("d-none");
                    $(".active-paycode").addClass("d-none");
                }
                if (data.status == "OK" && data.paycode != "0" && data.paycode != "") {
                    if (!_NMF._joined) {
                        $(".inactive-paycode").addClass("d-none");
                        $(".active-paycode").removeClass("d-none");
                    }
                    _NMF._id_charge_code = parseInt(data.paycode);
                    $(".paycode").html(data.paycode);
                    var _msg = "<p><b>Nuestros médicos están atendiendo en este momento.</b></p>";
                    _msg += "<p>El tiempo estimado de espera es de <b>aproximadamente 5 minutos.</b></p>";
                    _msg += "<p>¡Gracias por su paciencia!</p>";
                    $(".paycode-message").html(_msg);
                    $('[name ^= "jitsiConferenceFrame"]').css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
                    if (!_NMF._joined) {
                        $(_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.target).hide();
                        if (_bConnect) {
                            _NEOVIDEO.onJoinOpenSession(data.token_meet).then(function (conn) {
                                _NMF._joined = true;
                                $(".btnShowVideo").click();
                            }).catch(function (err) {
                                _NMF._joined = false;
                                //$(".inactive-paycode").removeClass("d-none");
                                //$(".active-paycode").addClass("d-none");
                            });
                        } else {
                            _NMF._joined = false;
                        }
                    }
                } else {
                    _NMF._joined = false;
                }
            }).catch(function (err) {
                _NMF.onModalAlert("Error", JSON.stringify(err), "danger");
            });
    },
    onConnectVideoChat: function () {
        return new Promise(
            function (resolve, reject) {
                try {
                    setTimeout(function () {
                        navigator.mediaDevices.getUserMedia({ audio: true, video: true }).catch(error => {
                            _NMF.onModalAlert("Error", "Ud. no tiene habilitados los permisos de cámara y/o micrófono para nuestro sitio. \n Por favor habilítelos cuando su teléfono lo solicite.<br/>Si no lo hace, deberá acceder a su navegador y remover el bloqueo para nuestro sitio.", "danger");
                        });
                    }, 2000);

                    var _config = { "id_external": 0, "live": 0, "tech": "normal" };
                    _NEOVIDEO.onCreateNewVideoRoom(null, _config).then(function (data) {
                        resolve(data);
                    });
                } catch (err) {
                    reject(err);
                }
            });
    },
};
if (window.location.hostname != "localhost") { _NMF._server = "./"; }
