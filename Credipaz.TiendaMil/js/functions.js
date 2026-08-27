var _F = {
    _lastResponse: null,
    _cameraId: null,
    _actual_id: 0,
    _max_filesize_upload: 2,
    _last_get: null,
    _auth_user_data: null,
    _log: true,
    _objStack: [],
    _keyStack: [],
    _itemsShoppingCart: [],
    _iPage: null,
    _TIMER_STATUS: 0,
    _TIMER_STREAM: 0,
    _TMR_PAY_BOTONPAGO:0,
    _live: 0,
    _itemsPagos: null,
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
    onInitApplication: function (_optionsWrtc) {
        /*Todo va wrapped dentro del llamado a la configuracion de server Blacbox */
        _F.readConfigServers("Intranet").then(function (data) {
            _AJAX_deprecated._isPWA = (_T.onAllUrlParams().mode == "standalone");
            if (!_AJAX_deprecated._ready) { _AJAX_deprecated.initialize(); }
            _AJAX_deprecated._init_page = "app-home";
            _AJAX_deprecated._switch_site = "";
            _AJAX_deprecated._SERVER = data.url;
            _videoServer = "https://api.gruponeodata.com/neovideo.v1/";
            _authenticationServer = "https://api.gruponeodata.com/neoauthentication.v1/";
            $.getScript("./js/Neodata/NEOAUTHENTICATION.js?" + _T.UUID()).done(function (script, textStatus) {
                $.getScript("./js/Neodata/NEOVIDEO-jvideo1.js?" + _T.UUID()).done(function (script, textStatus) {
                    $.getScript("https://jvideo1.gruponeodata.com/external_api.js?" + _T.UUID()).done(function (script, textStatus) {
                        _NEOAUTHENTICATION._SERVER = _authenticationServer;
                        _NEOVIDEO._SERVER = _videoServer;
                        _NEOVIDEO._id_application = 6;
                        _NEOVIDEO._username = "mil";
                        _NEOVIDEO._password = "08.!Rcp#@80";
                        _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.target = "#meet";
                        _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.roomname = "NEOVIDEO";
                        _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.fullname = " ";
                        _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.alias = " ";
                        _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width = "100vw";
                        _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height = "100vh";
                        _NEOVIDEO._CONFIG_OVERWRITE.hideConferenceSubject = true;
                        /*Defines behavior when close video*/
                        _NEOVIDEO.onDisconnect = function () { window.location = "thanks.html"; };
                        _F.onTryPage(null, "app-intro");
                    });
                });
            });
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
                        $.getScript("js/pages/" + oLocal.ID + ".js?" + _T.UUID(), function () {
                            if (!oLocal.LOADED) {
                                _AJAX_deprecated.Load("./pages/" + oLocal.ID + ".html?" + _T.UUID()).then(function (data) {
                                    oLocal.LOADED = true;
                                    $(".app").append(data);
                                    $("." + oLocal.ID).fadeIn("fast");
                                });
                            } else {
                                $("." + oLocal.ID).fadeIn("fast");
                            }
                            oLocal.FUNCTIONS = _fnc.getReference();
                            if ($.isFunction(oLocal.FUNCTIONS.onShow)) { oLocal.FUNCTIONS.onShow(); }
                        });
                    };
                    o.onHide = function () {
                        $("." + this.ID).hide();
                        if ($.isFunction(this.FUNCTIONS.onHide)) { this.FUNCTIONS.onHide(); }
                    };
                    resolve(o);
                } catch (rex) {
                    reject(rex);
                }
            })
    },
    onTryPage: function (_this, _id) {
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
            });
        } else {
            _F._iPage.onShow();
        }
        try { if (String(_iLast.ID) != String(_id)) { _F._keyStack.push(_id); } } catch (err) { };
        setTimeout(_F.onConfigureApplication, 250);
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
    onLog: function (_message) { if (_F._log) { console.log(_message); } },
    onDestroyModal: function (_target) {
        $(_target).remove();
        $(".modal-backdrop").remove();
        $("body").removeClass("modal-open");
    },
    onModalAlert: function (_title, _body, _class, _footer) {
        if (_class == undefined) { _class = "info"; }
        _F.onDestroyModal("#alterModal");
        var _html = "<div class='modal fade' id='alterModal' role='dialog'>";
        _html += " <div class='modal-dialog modal-dialog-centered' role='document'>";
        _html += "  <div class='modal-content'>";
        _html += "    <div class='modal-header text-" + _class + "' style='border:solid 0px white;'>";
        _html += "      <h2 class='modal-title'>" + _title + "</h2>";
        _html += "    </div>";
        _html += "    <div class='modal-body'>";
        _html += _body;
        _html += "    </div>";
        _html += "    <div class='modal-footer font-weight-light' style='border:solid 0px white;'>";
        if (_footer == undefined || _footer=="") {
            _html += "<button type='button' class='btn-raised btn btn-cancel-alert btn-" + _class + " btn-sm'><i class='material-icons'>done</i></span>Aceptar</button>";
        } else {
            _html += _footer;
        }
        _html += "    </div>";
        _html += "  </div>";
        _html += " </div>";
        _html += "</div>";
        $("body").append(_html);
        $("#alterModal").modal({ backdrop: true, keyboard: true, show: true });
        return true;
    },
    onErrHandler: function (msg) {
        var _text = "";
        if (typeof msg === 'object') {
            if (msg.message != undefined) {
                _text = msg.message;
            } else {
                _text = JSON.stringify(msg);
            }
        } else {
            _text = msg;
        }
        _F.onModalAlert("System alert", _text, "warning");
    },
    onDestroyAllWindows: function () {
        _F.onDestroyModal("#initWaiting");
        _F.onDestroyModal("#initModal");
        _F.onDestroyModal("#initReceived");
        _F.onDestroyModal("#initShoppingCart");
        _F.onDestroyModal("#initCatalog");
        _F.onDestroyModal("#initItemCatalog");
        _F.onDestroyModal("#initPaymentPlatform");
        _F.onDestroyModal("#initPayment");
    },

    /**
     * /
     * specific CALLS
     */

    onModalInitial: function (_mode) {
        _API_deprecated.UiGetUserAreas({ "last_area": "tienda mil" }).then(function (data) {
            var _sin_operador = (parseInt(data.seconds) > 15);
            /*Evalua si esta disponible para atención o no */
            var _form = false;
            var _open = true;
            var d = new Date();
            var day = d.getDay();
            var hour = d.getHours();
            var min = d.getMinutes();
            var _lv_from = 9
            var _lv_from2 = 30
            var _lv_to = 19
            var _lv_to2 = 30
            var _s_from = 9
            var _s_from2 = 0
            var _s_to = 13
            var _s_to2 = 0
            var _msg_close = "<p>Nuestro horario de atención es:</p>";
            _msg_close += "<p>Lunes a Viernes de <b>" + _lv_from + ":30hs. a " + _lv_to + ":30hs.</b></p>";
            _msg_close += "<p>Sábados de <b>" + _s_from + "hs. a " + _s_to + "hs.</b></p>";
            switch (day) {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                    _open = (hour >= _lv_from) && (hour <= _lv_to);
                    if (_open && hour == _lv_from) { _open = (min >= _lv_from2); }
                    if (_open && hour == _lv_to) { _open = (min <= _lv_to2); }
                    break;
                case 6:
                    _open = (hour >= _s_from) && (hour <= _s_to);
                    if (_open && hour == _s_from) { _open = (min >= _s_from2); }
                    if (_open && hour == _s_to) { _open = (min <= _s_to2); }
                    break;
                case 7:
                    _open = false;
                    break;
            }
            var _info1 = "";
            var _info2 = "";
            var _color = "";
            clearInterval(_F._TIMER_STATUS);
            clearInterval(_F._TIMER_STREAM);
            _F._TIMER_STREAM = setInterval(function () { _F.onLiveStreamStatus(); }, 2500);

            _F.onDestroyAllWindows();
            $(".area-received").addClass("d-none");
            $(".btn-shoppingcart").addClass("d-none");
            $(".badge-shoppingcart").addClass("d-none");
            //_open = true;
            //_sin_operador = true;
            if (_open && !_sin_operador) {
                switch (_mode) {
                    case "normal":
                        _info1 = "Vas a iniciar la llamada con tu cámara y tu micrófono desactivados";
                        if (_F._live == 0) {
                            _info2 = "Podrás activarlos cuando desees";
                        } else {
                            _info2 = "Hay un evento en vivo que estamos transmitiendo.<br/>¡Podés unirte y ver nuestros productos en vivo!";
                            _color = "color:navy;";
                        }
                        break;
                    case "wrtc":
                        _info1 = "Parece que el navegador de tu dispositivo ha dado algún problema";
                        _info2 = "¡Por favor intentá de nuevo!<br/>Si el problema continúa, tratá de usar otro teléfono o una computadora para conectarte. ";
                        break;
                }
            } else {
                _form = true;
                _msg_close = "";
                if (_sin_operador) { _msg_close = "<p>Todos nuestros operadores se encuentran ocupados</p>"; }
                _info1 = _msg_close;
                _info2 = "Por favor dejanos tus datos para que podamos contactarte<br/>";
                /* Forzar botón de Whatsapp */
                _form = false;
                _info1 = _msg_close + "Por favor contactanos por este medio<br/>";
                _info2 = "<a class='btn btn-success btn-lg btn-raised' href='https://wa.me/541136056484' target='_blank'>Whatsapp</a>";
            }
            var _html = "<div class='modal fade' id='initModal' role='dialog' style='z-index: 999999;'>";
            _html += " <div class='modal-init-frame modal-dialog modal-dialog-centered'>";
            _html += "  <div class='modal-content m-0 my-1' style='width:100%;height:90vh;'>";
            _html += "    <div class='modal-body justify-content-center align-items-center text-center p-0 m-0 pt-3'>";
            _html += "      <img src='./img/logo.png' />"
            _html += "      <p class='pt-3 pb-1 px-2' style='font-size:0.85rem;'><b>" + _info1 + "</b></p>";
            if (_F._live == 0) {
                _html += "<p class='px-2' style='font-size:0.80rem;'>" + _info2 + "</p>";
            } else {
                _html += "<p class='px-2' style='font-size:0.80rem;" + _color + "'>" + _info2 + "</p>";
            }
            if (_form) {
                _html += "   <div class='container'>";
                _html += "      <div class='row mb-1'>";
                _html += "         <div class='col-12'><input type='text' class='tows form-control validate name' name='name' placeholder='Nombre' /></div>";
                _html += "      </div>";
                _html += "      <div class='row mb-1'>";
                _html += "         <div class='col-12'><input type='text' class='tows form-control validate surname' name='surname' placeholder='Apellido' /></div>";
                _html += "      </div>";
                _html += "      <div class='row mb-1'>";
                _html += "         <div class='col-8'><input type='text' class='tows form-control validate dni onlyNumbers' inputmode='numeric' maxlength='12' name='dni' placeholder='DNI' /></div>";
                _html += "         <div class='col-4'>";
                _html += "            <select class='tows form-control validate sex' name='sex'>";
                _html += "               <option selected value='-1'>[Sexo]</option>";
                _html += "               <option value='M'>Masculino</option>";
                _html += "               <option value='F'>Femenino</option>";
                _html += "            </select>";
                _html += "         </div>";
                _html += "      </div>";
                _html += "      <div class='row mb-1'>";
                _html += "         <div class='col-4'><input type='text' class='tows form-control validate area onlyNumbers' list='prefijos' inputmode='numeric' maxlength='4' name='area' placeholder='Area' /></div>";
                _html += "         <div class='col-8'><input type='tel' class='tows form-control validate phone onlyNumbers' inputmode='numeric' maxlength='8' name='phone' placeholder='Celular' /></div>";
                _html += "      </div>";
                _html += "      <div class='row mb-1'>";
                _html += "         <div class='col-12'><input type='text' class='tows form-control validate email' name='email' placeholder='Email' /></div>";
                _html += "      </div>";
                _html += "      <div class='row mb-1'>";
                _html += "         <div class='col-12 areaSucursal'><input type='hidden' class='nIDSucursal tows' name='nIDSucursal' placeholder='nIDSucursal' value='100' /></div>";
                _html += "      </div>";
                _html += "      <div class='row mb-1'><div id='widget' class='col-12' style='margin-top: 5px;'></div></div>";
                _html += "   </div>";
            }

            _html += "    <div class='py-0 m-0 px-auto mt-2' style='border:solid 0px white;'>";
            if (_F._live == 0) {
                if (_open && !_sin_operador) {
                    _html += "   <button data-id='0' data-mode='" + _mode + "' type='button' class='loaded-catalog btn-raised  btn btn-accept-alert btn-dark btn-md'>Ingresar</button>";
                } else {
                    _html += "   <button data-id='0' data-mode='tiendamil' type='button' class='loaded-catalog btn-raised btn btn-accept-landing btn-success btn-md d-none'>Enviar datos</button>";
                }
            } else {
                if (_mode == "normal") {
                    _html += "<button type='button' class='btn-raised btn-block btn btn-join-live btn-success btn-md'>¡Tienda en vivo!</button>";
                }
            }
            _html += "    </div>";

            _html += "    </div>";

            _html += "  </div>";
            _html += " </div>";
            _html += "</div>";
            $("body").append(_html);

            $("#initModal").modal({ backdrop: "static", keyboard: false, show: true });
            $(".modal-init-frame").fadeIn("slow");
        });
    },
    onModalWaiting: function () {
        _F.onDestroyModal("#initWaiting");
        _API_deprecated.UiGetUserAreas({ "last_area": "tienda mil" }).then(function (data) {
            var _html = "<div class='modal fade' id='initWaiting' role='dialog' style='z-index: 999999;'>";
            _html += "      <div class='modal-wait-frame modal-dialog modal-dialog-centered'>";
            _html += "         <div class='modal-content m-0 my-2' style='width:100%;height:100%;'>";
            _html += "            <div class='modal-header pr-auto mr-auto' style='border:solid 0px white;'>";
            _html += "               <img src='./img/powered.png' style='width:90%;'/>";
            _html += "            </div>";
            _html += "            <div class='modal-body justify-content-center align-items-center text-center'>";
            _html += "               <img src='./img/logo.png'/>"
            if (parseInt(data.seconds) <= 15) {
                _html += "               <p class='pt-5 pb-1 px-5' style='font-size:0.85rem;'><b>¡Estamos por atenderte!</b></p>";
                _html += "               <p class='px-5 pb-2' style='font-size:0.80rem;'>Por favor aguardá unos instantes</p>";
                _html += "               <img src='./img/search.gif' style='width:64px;'/>"
                _html += "               <p class='px-5 pb-2' style='font-size:0.80rem;'>¡Trataremos de atenderte en menos de dos minutos!</p>";
            } else {
                _html += "               <p class='pt-5 pb-1 px-5' style='font-size:0.85rem;'><b>No podemos atenderte en ese momento</b></p>";
                _html += "               <p class='px-1 pb-2' style='font-size:0.80rem;'>Por favor comunicate con nosotros</p>";
                _html += "               <a class='btn btn-success btn-lg btn-raised' href='https://wa.me/541136056484' target='_blank'>Whatsapp</a>";
            }
            _html += "            </div>";
            _html += "         </div>";
            _html += "      </div>";
            _html += "   </div>";
            $("body").append(_html);
            $("#initWaiting").modal({ backdrop: "static", keyboard: false, show: true });
            $(".modal-wait-frame").fadeIn("slow");
        });
        return true;
    },
    onModalItemReceived: function (_this) {
        _F.onDestroyModal("#initReceived");
        var _obj = JSON.parse(_T.b64_to_utf8(_this.attr("data-raw")));
        var _html = "<div class='modal fade' id='initReceived' role='dialog' style='z-index: 999999;'>";
        _html += "      <div class='modal-wait-frame modal-dialog modal-dialog-centered'>";
        _html += "         <div class='modal-content m-0 my-1' style='width:100%;'>";
        _html += "            <div class='modal-header' style='border:solid 0px white;'>";
        _html += "               <button type='button' class='close btn-close-received' data-dismiss='modal' aria-hidden='true' style='font-size:2.5rem;'>&times;</button>";
        _html += "            </div>";
        _html += "            <div class='modal-body justify-content-center align-items-center text-center'>";
        _html += "               <img src='" + _obj.image + "' style='width:100%;'/>";
        _html += "               <p style='font-size:1rem;text-align:center;'>" + _obj.code + " - " + _obj.description + "</p>";
        _html += "               <p style='font-size:1rem;text-align:center;'>$ " + parseFloat(_obj.precio).toLocaleString('de') + "</p>";
        _html += "            </div>";
        /*
        _html += "            <div class='modal-footer justify-content-center align-items-center text-center'>";
        _html += "               <a href='#' style='width:125px;' class='btn btn-sm btn-danger btn-removeItem' data-raw='" + _this.attr("data-raw") + "'>";
        _html += "                  <i class='material-icons p-2'>cancel</i>";
        _html += "               </a>";
        _html += "               <a href='#' style='width:125px;' class='btn btn-sm btn-success btn-addToCart' data-raw='" + _this.attr("data-raw") + "'>";
        _html += "                  <i class='material-icons p-2'>shopping_cart</i>";
        _html += "               </a>";
        _html += "            </div>";
        */
        _html += "         </div>";
        _html += "      </div>";
        _html += "   </div>";
        $("body").append(_html);
        $("#initReceived").modal({ backdrop: "static", keyboard: false, show: true });
        return true;
    },
    onModalShoppingCart: function (_this) {
        _F.onDestroyModal("#initShoppingCart");
        var _empty = "<tr><td colspan='4'><span class='badge badge-danger'>Su carrito de compras está vacío</span></td></tr>";
        var _total = 0;
        var _html = "<div class='modal fade' id='initShoppingCart' role='dialog' style='z-index: 999999;'>";
        _html += "      <div class='modal-wait-frame modal-dialog modal-dialog-centered modal-dialog-scrollable'>";
        _html += "         <div class='modal-content m-0 my-1' style='width:100%;'>";
        _html += "            <div class='modal-header' style='border:solid 0px white;'>";
        _html += "               <h4>Carrito de compras</h4>";
        _html += "               <button type='button' class='close btn-close-cart' data-dismiss='modal' aria-hidden='true' style='font-size:2.5rem;'>&times;</button>";
        _html += "            </div>";
        _html += "            <div class='modal-body justify-content-center align-items-center text-center'>";
        _html += "               <table class='w-100'>";
        $.each(_F._itemsShoppingCart, function (i, item) {
            item = JSON.parse(_T.b64_to_utf8(item));
            _empty = "";
            _total += parseFloat(item.precio);
            _html += "                  <tr class='shadow-sm trItemCart trItemCart-" + i + "' style='border-radius:5px;'><input id='itemPrecio' name='itemPrecio' class='itemPrecio' data-item='" + item.description + "' type='hidden' value='" + item.precio + "'/>";
            _html += "                     <td class='p-1' style='width:55px;' valign='middle'><img src='" + item.image + "' style='width:100%;'/></td>";
            _html += "                     <td class='p-1' valign='left' style='font-size:0.5em;color:black;'>" + item.description + "</td>";
            _html += "                     <td class='p-1' align='right' style='width:100px;font-size:0.6em;color:black;' valign='middle'>$ " + parseFloat(item.precio).toLocaleString('de') + "</td>";
            _html += "                     <td class='p-1' align='right' style='width:40px;' valign='middle'>";
            _html += "                        <a href='#' class='btn-delete-item-cart' data-index='" + i + "'>";
            _html += "                           <i class='material-icons' style='color:red;font-size:1.75rem !important;'>delete</i></span>";
            _html += "                        </a>";
            _html += "                     </td>";
            _html += "                  </tr>";
            _html += "                  <tr style='line-height:5px;'><td colspan='4'><br/></td></tr>";
        });
        if (_total != 0) {
            _html += "                  <tr style='line-height:5px;border-top:double 3px black;'><td colspan='4'><br/></td></tr>";
            _html += "                  <tr style='border-radius:15px;'>";
            _html += "                     <td colspan='2' class='px-3' align='right'>Total</td>";
            _html += "                     <td class='p-1 total-cart' align='right' valign='middle' style='color:#e6007e;font-weight:bold;'></td>";
            _html += "                     <td></td>";
            _html += "                  </tr>";
        }
        _html += _empty;
        _html += "               </table>";
        _html += "            </div>";
        _html += "            <div class='modal-footer justify-content-center align-items-center text-center'>";
        _html += "               <a href='#' style='width:200px;background-color:#e6007e !important;' class='btn btn-sm btn-success btn-payCart'>";
        _html += "                  <i class='material-icons p-2'>shopping_cart</i><i class='material-icons p-2'>credit_card</i><i class='material-icons p-2'>attach_money</i> Terminar y pagar";
        _html += "               </a>";
        _html += "            </div>";
        _html += "         </div>";
        _html += "      </div>";
        _html += "   </div>";
        $("body").append(_html);
        _F.onTotalizeCart();
        $("#initShoppingCart").modal({ backdrop: "static", keyboard: false, show: true });
        return true;
    },

    onCreateNewVideoRoom: function (_this) {
        _this.html("Llamando...").attr("disabled", true);
        var _id_item = _this.attr("data-id");
        var _mode = _this.attr("data-mode");
        _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.caller = "Cliente";
        _NEOVIDEO._INTERFACE_CONFIG_OVERWRITE.TOOLBAR_BUTTONS = ['microphone', 'camera', 'hangup', 'chat'];
        _NEOVIDEO._CONFIG_OVERWRITE.startWithAudioMuted = false;
        _NEOVIDEO._CONFIG_OVERWRITE.startWithVideoMuted = true;

        _NEOVIDEO.onDisconnect = function (data) {
            $(".active-video").addClass("d-none");
            _F.onModalInitial("normal");
        };
        _NEOVIDEO.onParticipantJoined = function (data) {
            $(".active-video").removeClass("d-none");
            _F.onEvalShoppingCartVisibility();
            _F.onDestroyModal("#initWaiting");
        };
        _NEOVIDEO.onParticipantLeft = function (data) {
            $(".active-video").addClass("d-none");
            _NEOVIDEO.onTurnOffVideo();
            _F.onDestroyModal("#initWaiting");
            /*
            setTimeout(function () {
                $(".loading-catalog").addClass("d-none");
                $(".loaded-catalog").removeClass("d-none");
            }, 2000);
            */
        };

        /*Additional events*/
        //_NEOVIDEO.onLog = function (data) { console.log("onLog->"); console.log(data); };
        //_NEOVIDEO.onVideoConferenceJoined = function (data) {console.log("onVideoConferenceJoined->");console.log(data);};
        //_NEOVIDEO.onVideoConferenceLeft = function (data) {console.log("onVideoConferenceLeft->");console.log(data);};
        //_NEOVIDEO.onBrowserSupport = function (data) { console.log("onBrowserSupport->"); console.log(data); };
        //_NEOVIDEO.onBreakoutRoomsUpdated = function (data) { console.log("onBreakoutRoomsUpdated->"); console.log(data); };
        //_NEOVIDEO.onErrorOccurred = function (data) {console.log("onErrorOccurred->");console.log(data);};

        var _config = { "id_external": _id_item, "live": 0, "tech": _mode };
        _NEOVIDEO.onCreateNewVideoRoom(_this, _config).then(function (data) {
            $('[name ^= "jitsiConferenceFrame"]').css({ "height": "90vh", "width": "100vw" });
            $(_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.target).fadeIn("slow");

            _F._TIMER_STATUS = setInterval(function () { _F.onCommunicationStatus(); }, 5000);
            _F.onDestroyAllWindows();
            _F.onModalWaiting();
            var _params = {
                "id_group": 1088,
                "subject": "Se ha solicitado atención en Tienda MIL",
                "body": "Un cliente ha iniciado una solicitud de atención y está en espera."
            };
        });
    },
    onJoinOpenSession: function (_this) {
        if (_F._live == 0) { alert("¡Ha finalizado el vivo!"); return; }

        _NEOVIDEO.onDisconnect = function (data) {
            $(".active-video").addClass("d-none");
            if (_F._live != 0) { setTimeout(function () { _F.onModalInitial("normal"); }, 3000); }
        };

        /*Additional events*/
        //_NEOVIDEO.onLog = function (data) { console.log("onLog->"); console.log(data); };
        //_NEOVIDEO.onVideoConferenceJoined = function (data) { console.log("onVideoConferenceJoined->"); console.log(data); };
        //_NEOVIDEO.onVideoConferenceLeft = function (data) { console.log("onVideoConferenceLeft->"); console.log(data); };
        //_NEOVIDEO.onBrowserSupport = function (data) { console.log("onBrowserSupport->"); console.log(data); };
        //_NEOVIDEO.onBreakoutRoomsUpdated = function (data) { console.log("onBreakoutRoomsUpdated->"); console.log(data); };
        //_NEOVIDEO.onErrorOccurred = function (data) { console.log("onErrorOccurred->"); console.log(data); };

        _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.caller = "Cliente";
        _NEOVIDEO._INTERFACE_CONFIG_OVERWRITE.TOOLBAR_BUTTONS = ['hangup', 'chat'];
        _NEOVIDEO._CONFIG_OVERWRITE.startWithAudioMuted = true;
        _NEOVIDEO._CONFIG_OVERWRITE.startWithVideoMuted = true;
        _NEOVIDEO.onJoinOpenSession(_F._live).then(function (data) {
            $('[name ^= "jitsiConferenceFrame"]').css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
            $(".active-video").removeClass("d-none");
            $(_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.target).fadeIn("slow");
            _F.onDestroyAllWindows();
        });
    },

    onTotalizeCart: function () {
        var _total = 0;
        $(".itemPrecio").each(function () {
            _total += parseFloat($(this).val());
        });
        $(".total-cart").html("$ " + _total.toLocaleString('de'));
    },
    onAddToCart: function (_this) {
        var _obj = JSON.parse(_T.b64_to_utf8(_this.attr("data-raw")));
        $(".item-received-" + _obj.id_item + "-" + _obj.id).remove();
        _F._itemsShoppingCart.push(_T.utf8_to_b64(JSON.stringify(_obj)));
        _F.onEvalShoppingCartVisibility();
        //_T.setCookie("shoppingcarttiendamil", JSON.stringify(_F._itemsShoppingCart), 1);
        _F.onDestroyModal("#initReceived");
    },
    onResetShoppingCart: function() {
        $(".trItemCart").remove();
        _F._itemsShoppingCart = [];
        $(".btn-shoppingcart").addClass("d-none");
        $(".badge-shoppingcart").addClass("d-none");
    },
    onCommunicationStatus: function () {
        _F.onEvalShoppingCartVisibility();
        _NEOVIDEO.onGetRelatedData({ "id": _NEOVIDEO._id_transaction }).then(function (status) {
            if (status.status == "OK") {
                $.each(status.records, function (i, item) {
                    var _obj = JSON.parse(_T.b64_to_utf8(item.raw_data));
                    var _params = { "id": item.id, "remove": 0 };
                    _NEOVIDEO.onReceiveRelatedData(_params);
                    var _target = ("item-received-" + _obj.id_item + "-" + _obj.id);
                    if (item.verified == null || item.verified=="" || item.verified=="null") {
                        $(("." + _target)).remove();
                        var _html = "<li data-raw='" + item.raw_data + "' style='background-color:white;list-style-type: none;min-width:100px;width:100px;height:165px;border-radius:10px;' class='p-1 m-1 shadow item-received " + _target + "' data-id='" + _obj.id_item + "'>";
                        _html += "<img src='" + _obj.image + "' style='position:relative;width:100%;'/>";
                        _html += "<p style='font-size:0.50rem;color:black;' class='pt-1'>" + _obj.code + " - " + _obj.description + "</p>";
                        _html += "</li>";
                        $(".list-catalogitems").append(_html).removeClass("d-none");
                        $(".area-received").removeClass("d-none");
                    } else {
                        if (item.offline == null || item.offline == "" || item.offline == "null") {
                            var i = 0;
                        } else {
                            $(("." + _target)).remove();
                            var _resend = { "id": item.id, "remove": item.id };
                            _NEOVIDEO.onReceiveRelatedData(_resend);
                        }
                    }
                });
            }
        });
    },
    onLiveStreamStatus: function () {
        _NEOVIDEO.onListAvailableLiveStreaming().then(function (data) {
            var _prior = _F._live;
            _F._live = 0;
            $.each(data.records, function (i, item) {
                if (i == 0) { _F._live = item.id; }
            });
            if (_prior != _F._live) { _F.onModalInitial("normal"); }
        });
    },

    onRemoveItem: function (_this) {
        var _obj = JSON.parse(_T.b64_to_utf8(_this.attr("data-raw")));
        $(".item-received-" + _obj.id_item + "-" + _obj.id).remove();
        //_T.setCookie("shoppingcarttiendamil", JSON.stringify(_F._itemsShoppingCart), 1);
        _F.onDestroyModal("#initReceived");
    },
    onDeleteItemCart: function (_this) {
        if (!confirm("¿Confirma quitar el producto de su carrito de compras?")) { return false; }
        var _index = _this.attr("data-index");
        $(".trItemCart-" + _index).fadeOut("fast", function () {
            $(".trItemCart-" + _index).remove();
            _F.onTotalizeCart();
            _F._itemsShoppingCart.splice(_index, 1);
            _F.onEvalShoppingCartVisibility();
            //_T.setCookie("shoppingcarttiendamil", JSON.stringify(_F._itemsShoppingCart), 1);
            if (_F._itemsShoppingCart == 0) { $(".btn-close-cart").click(); }
        });
    },
    onEvalShoppingCartVisibility: function () {
        $(".badge-shoppingcart").html(_F._itemsShoppingCart.length);
        if (_F._itemsShoppingCart.length != 0) {
            $(".btn-shoppingcart").removeClass("d-none");
            $(".badge-shoppingcart").removeClass("d-none");
        } else {
            $(".btn-shoppingcart").addClass("d-none");
            $(".badge-shoppingcart").addClass("d-none");
        }
    },
    onItemCatalogo: function (_obj) {
        var _record = _T.utf8_to_b64(JSON.stringify(_obj));
        var _html = "";
        _html += "<div class='item-catalogo col-5 shadow p-1 mb-2 mx-2 btn-see-details' data-raw='" + _record + "'>";
        _html += "	<img src='" + _obj.image + "' style='border-radius:15px;width:100%;' alt='" + _obj.description + "'/>";
        _html += "  <p class='m-0 px-1'>" + _obj.description + "</p>";
        _html += "</div>";
        return _html;
    },
}
