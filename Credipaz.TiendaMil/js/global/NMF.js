var _NMF = {
    _lastResponse: null,
    _activeCatalog: null,
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
    onInitApplication: function (_optionsWrtc) {
        /*Todo va wrapped dentro del llamado a la configuracion de server Blacbox */
        _AJAX.readConfigServers("Intranet").then(function (data) {
            _AJAX._isPWA = (_TOOLS.onAllUrlParams().mode == "standalone");
            if (!_AJAX._ready) { _AJAX.initialize(); }
            _AJAX._init_page = "app-home";
            _AJAX._switch_site = "";
            _AJAX._SERVER = data.url;
            _ecosystemServer = "https://api.gruponeodata.com/";
            _videoServer = "https://api.gruponeodata.com/neovideo.v1/";
            _authenticationServer = "https://api.gruponeodata.com/neoauthentication.v1/";
            $.getScript(_ecosystemServer + "assets/js/NEOAUTHENTICATION.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
                $.getScript("./js/global/NEOVIDEO-jvideo1.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
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
                    _NMF.onTryPage(null, "app-intro");
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
                        $.getScript("js/pages/" + oLocal.ID + ".js?" + _TOOLS.UUID(), function () {
                            if (!oLocal.LOADED) {
                                _AJAX.Load("./pages/" + oLocal.ID + ".html?" + _TOOLS.UUID()).then(function (data) {
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

        var _iLast = _NMF._iPage;
        if (_NMF._iPage != null) { _NMF._iPage.onHide(); }
        _NMF._iPage = _NMF.onTryStack(_id);
        if (_NMF._iPage == null) {
            _NMF.onTryPageAbstract(_id).then(function (o) {
                _NMF._iPage = o;
                _NMF._objStack.push(_NMF._iPage);
                _NMF._iPage.onShow();
            });
        } else {
            _NMF._iPage.onShow();
        }
        try { if (String(_iLast.ID) != String(_id)) { _NMF._keyStack.push(_id); } } catch (err) { };
        setTimeout(_NMF.onConfigureApplication, 250);
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
    onLog: function (_message) { if (_NMF._log) { console.log(_message); } },
    onDestroyModal: function (_target) {
        $(_target).remove();
        $(".modal-backdrop").remove();
        $("body").removeClass("modal-open");
    },
    onModalAlert: function (_title, _body, _class, _footer) {
        if (_class == undefined) { _class = "info"; }
        _NMF.onDestroyModal("#alterModal");
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
        $("body").off("click", ".btn-cancel-alert").on("click", ".btn-cancel-alert", function () {
            _NMF.onDestroyModal("#alterModal");
        });
        $("#alterModal").modal({ backdrop: true, keyboard: true, show: true });
        return true;
    },
    onModalFromFile: function (_page, _name, _header, _footer, _fullscreen, _id) {
        return new Promise(
            function (resolve, reject) {
                try {
                    _NMF.onDestroyModal("#" + _name);
                    _AJAX.Load(_page).then(function (_body) {
                        var _html = "<div class='modal fade' id='" + _name + "' role='dialog' style='overflow-y:auto;'>";
                        _html += " <div class='modal-dialog modal-dialog-centered modal-dialog-" + _name + "' role='document' style='width:100%;overflow:auto;max-width:100%;padding:0px;margin:0px;'>";
                        var _style = "";
                        if (_fullscreen) { _style = "width:100%;max-width:100%;"; }
                        _html += "  <div class='modal-content modal-content-" + _name + "' style='" + _style + "'>";
                        if (_header != "") { _html += ("<div class='modal-header modal-header-" + _name + "'>" + _header + "</div>"); }
                        _html += ("    <div class='modal-body draw-body modal-body-" + _name + "'>" + _body + "</div>");
                        if (_footer != "") { _html += ("<div class='modal-footer modal-footer-" + _name + "'>" + _footer + "</div>"); }
                        _html += "     <i class='material-icons shadow btnCloseModalFromFile' style='cursor:pointer;border-radius:25px;position:absolute;top:10px;left:5px;color:black;font-size:40px;text-align:right;padding:5px;background-color:rgba(255,255,255,0.75);'>close</i>";
                        _html += "  </div>";
                        _html += " </div>";
                        _html += "</div>";
                        _html = _html.replace(/\[ID\]/g, _id);
                        $("body").append(_html);
                        resolve(_name);
                    });
                } catch (rex) {
                    reject(rex);
                }
            }
        )
    },
    onFooterAlert: function (_message, _color) {
        $(".alert-part").hide();
        $(".alert-part-contain").removeClass(["text-danger", "text-warning", "text-info", "text-secondary"]);
        $(".alert-part-contain").html(_message).addClass(_color);
        $(".alert-part").removeClass("d-none").fadeIn("slow");
    },
    onFooterAlertNoData: function () {
        _NMF.onFooterAlert("Sin datos para la consulta efectuada", "text-danger");
    },
    onFooterAlertResult: function (data) {
        _NMF.onFooterAlert("Se están listando <b>" + data.recordsAffected + "</b> registros en la página actual, de un total de <b>" + data.recordsTotal + "</b> para los filtros seleccionados", "text-primary");
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
        _NMF.onModalAlert("System alert", _text, "warning");
    },

    onDestroyAllWindows: function () {
        _NMF.onDestroyModal("#initWaiting");
        _NMF.onDestroyModal("#initModal");
        _NMF.onDestroyModal("#initReceived");
        _NMF.onDestroyModal("#initShoppingCart");
        _NMF.onDestroyModal("#initCatalog");
        _NMF.onDestroyModal("#initItemCatalog");
        _NMF.onDestroyModal("#initPaymentPlatform");
        _NMF.onDestroyModal("#initPayment");
    },

    /**
     * /
     * specific CALLS
     */

    onModalInitial: function (_mode) {
        _API.UiGetUserAreas({ "last_area": "tienda mil" }).then(function (data) {
            var _sin_operador = (parseInt(data.seconds) > 15);
            /*Evalua si esta disponible para atención o no */
            var _form = false;
            var _open = true;
            var d = new Date();
            var day = d.getDay();
            var hour = d.getHours();
            var _lv_from = 9
            var _lv_to = 19
            var _s_from = 9
            var _s_to = 14
            var _msg_close = "<p>Nuestro horario de atención es:</p>";
            _msg_close += "<p>Lunes a Viernes de <b>" + _lv_from + "hs. a " + _lv_to + "hs.</b></p>";
            _msg_close += "<p>Sábados de <b>" + _s_from + "hs. a " + _s_to + "hs.</b></p>";
            switch (day) {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                    _open = (hour > _lv_from) && (hour < _lv_to);
                    break;
                case 6:
                    _open = (hour > _s_from) && (hour < _s_to);
                    break;
                case 7:
                    _open = false;
                    break;
            }
            var _info1 = "";
            var _info2 = "";
            var _color = "";
            clearInterval(_NMF._TIMER_STATUS);
            clearInterval(_NMF._TIMER_STREAM);
            _NMF._TIMER_STREAM = setInterval(function () { _NMF.onLiveStreamStatus(); }, 2500);

            _NMF.onDestroyAllWindows();
            $(".area-received").addClass("d-none");
            $(".btn-shoppingcart").addClass("d-none");
            $(".badge-shoppingcart").addClass("d-none");
            //_open = true;
            //_sin_operador = true;
            if (_open && !_sin_operador) {
                switch (_mode) {
                    case "normal":
                        _info1 = "Vas a iniciar la llamada con tu cámara y tu micrófono desactivados";
                        if (_NMF._live == 0) {
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
            if (_NMF._live == 0) {
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
            if (_NMF._live == 0) {
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
            _html += "<script>" + drawCaptcha() + "</script>";
            $("body").append(_html);

            /*General and Jitsi events*/
            $("body").off("input", ".onlyNumbers").on("input", ".onlyNumbers", function () {
                _TOOLS.onlyNumbers($(this));
            });
            $("body").off("click", ".btn-accept-landing").on("click", ".btn-accept-landing", function () {
                sendToServer($(this));
            });

            $("body").off("click", ".item-received").on("click", ".item-received", function () {
                _NMF.onModalItemReceived($(this));
            });
            $("body").off("click", ".btn-shoppingcart").on("click", ".btn-shoppingcart", function () {
                _NMF.onModalShoppingCart($(this));
            });
            $("body").off("click", ".btn-accept-alert").on("click", ".btn-accept-alert", function () {
                _NMF.onCreateNewVideoRoom($(this));
            });
            $("body").off("click", ".btn-catalog").on("click", ".btn-catalog", function () {
                _NMF.onDestroyModal("#initModal");
                _NMF.onModalCatalog();
            });
            $("body").off("click", ".btn-join-live").on("click", ".btn-join-live", function () {
                _NMF.onJoinOpenSession();
            });

            /*--------------ESTO DEBE ACTIVARSE Y SER MODIFICADO AL IMPLEMENTAR LA ALTERNATIVA WEBRTC! */
            /*WRTC events*/
            /*
            $("body").off("click", ".btnSendMessage").on("click", ".btnSendMessage", function () {
                _WRTC.onSendMessage($(this));
            });
            $("body").off("click", ".btnRemoteOfferSet").on("click", ".btnRemoteOfferSet", function () {
                _WRTC.onCreateNewVideoRoom().then(function (_id) {
                    
                });
            });
            $("body").off("click", ".btnRemoteOfferGot").on("click", ".btnRemoteOfferGot", function () {
                _WRTC.onJoinOpenSession();
            });
            */
            /*------------------------------------------------------------------------------------------*/

            $("body").off("click", ".btn-test-payment").on("click", ".btn-test-payment", function () {
                var _items = [
                    { "description": "Artículo 1", "amount": 1 },
                    { "description": "Artículo 2", "amount": 1 },
                    { "description": "Artículo 3", "amount": 1 },
                    { "description": "Artículo 4", "amount": 1 }
                ];
                _NMF.onSelectPaymentPlatform(_items);
            });

            $("#initModal").modal({ backdrop: "static", keyboard: false, show: true });
            $(".modal-init-frame").fadeIn("slow");
        });
    },
    onModalWaiting: function () {
        _NMF.onDestroyModal("#initWaiting");
        _API.UiGetUserAreas({ "last_area": "tienda mil" }).then(function (data) {
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
        _NMF.onDestroyModal("#initReceived");
        var _obj = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-raw")));
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
        $("body").off("click", ".btn-close-received").on("click", ".btn-close-received", function () {
            _NMF.onDestroyModal("#initReceived");
        });
        $("body").off("click", ".btn-addToCart").on("click", ".btn-addToCart", function () {
            _NMF.onAddToCart($(this));
        });
        $("body").off("click", ".btn-removeItem").on("click", ".btn-removeItem", function () {
            _NMF.onRemoveItem($(this));
        });
        $("#initReceived").modal({ backdrop: "static", keyboard: false, show: true });
        return true;
    },
    onModalShoppingCart: function (_this) {
        _NMF.onDestroyModal("#initShoppingCart");
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
        $.each(_NMF._itemsShoppingCart, function (i, item) {
            item = JSON.parse(_TOOLS.b64_to_utf8(item));
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
        _NMF.onTotalizeCart();
        $("body").off("click", ".btn-close-cart").on("click", ".btn-close-cart", function () {
            _NMF.onDestroyModal("#initShoppingCart");
        });
        $("body").off("click", ".btn-delete-item-cart").on("click", ".btn-delete-item-cart", function () {
            _NMF.onDeleteItemCart($(this));
        });
        $("body").off("click", ".btn-payCart").on("click", ".btn-payCart", function () {
            var _items = [];
            $(".itemPrecio").each(function () {
                _items.push({ "description": $(this).attr("data-item"), "amount": parseFloat($(this).val()) });
            });
            _NMF.onSelectPaymentPlatform(_items);
        });
        $("#initShoppingCart").modal({ backdrop: "static", keyboard: false, show: true });
        return true;
    },
    onModalCatalog: function (_this) {
        _NMF.onDestroyModal("#initCatalog");
        var _html = "<div class='modal fade' id='initCatalog' role='dialog' style='z-index: 999999;'>";
        _html += "      <div class='modal-wait-frame modal-dialog modal-dialog-centered modal-dialog-scrollable'>";
        _html += "         <div class='modal-content m-0 my-1' style='width:100%;'>";
        _html += "            <div class='modal-header' style='border:solid 0px white;'>";
        _html += "               <h4>Catálogo de productos</h4>";
        _html += "               <button type='button' class='close btn-close-catalog' data-dismiss='modal' aria-hidden='true' style='font-size:2.5rem;'>&times;</button>";
        _html += "            </div>";
        _html += "            <div class='modal-header m-0 p-0 mx-2' style='border:solid 0px white;'>";
        _html += "               <input id='searchInput' name='searchInput' type='text' class='mx-1 form-control searchInput' placeholder='Buscar...' />";
        _html += "               <i class='material-icons' style='font-size:22px;vertical-align:middle;'>search</i>"
        _html += "            </div>";
        _html += "            <div class='modal-body justify-content-center align-items-center text-center'>";
        _html += "               <div id='allItems' class='row justify-content-center align-items-center text-center'>";
        /*
        $.each(_NMF._activeCatalog.data, function (i, item) { _html += _NMF.onItemCatalogo(item); });
        */
        _html += "               </div>";
        _html += "            </div>";
        _html += "         </div>";
        _html += "      </div>";
        _html += "   </div>";
        $("body").append(_html);
        $("body").off("click", ".btn-close-catalog").on("click", ".btn-close-catalog", function () {
            _NMF.onDestroyModal("#initCatalog");
            _NMF.onModalInitial("normal");
            /*
            setTimeout(function () {
                $(".loading-catalog").addClass("d-none");
                $(".loaded-catalog").removeClass("d-none");
            }, 1000);
            */
        });
        $("body").off("click", ".btn-see-details").on("click", ".btn-see-details", function () {
            $("#initCatalog").fadeOut("fast");
            _NMF.onModalItemCatalog($(this));
        });

        $("body").off("keyup", ".searchInput").on("keyup", ".searchInput", function () {
            $.expr[":"].contains = $.expr.createPseudo(function (arg) {
                return function (elem) {
                    return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
                };
            });

            var _val = $(this).val();
            _iLazy = setTimeout(function () {
                clearTimeout(_iLazy);
                if (_val != "") {
                    $(".item-catalogo").hide();
                    $("div.item-catalogo:contains('" + _val + "')").show();
                } else {
                    $(".item-catalogo").show();
                }
            }, 750);
        });


        $("#initCatalog").modal({ backdrop: "static", keyboard: false, show: true });
        return true;
    },
    onModalItemCatalog: function (_this) {
        _NMF.onDestroyModal("#initItemCatalog");
        var _obj = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-raw")));
        var _html = "<div class='modal fade' id='initItemCatalog' role='dialog' style='z-index: 999999;'>";
        _html += "      <div class='modal-wait-frame modal-dialog modal-dialog-centered modal-dialog-scrollable'>";
        _html += "         <div class='modal-content m-0 my-1' style='width:100%;'>";
        _html += "            <div class='modal-header' style='border:solid 0px white;'>";
        _html += "               <h4>Catálogo de productos</h4>";
        _html += "               <button type='button' class='close btn-close-itemcatalog' data-dismiss='modal' aria-hidden='true' style='font-size:2.5rem;'>&times;</button>";
        _html += "            </div>";
        _html += "            <div class='modal-body justify-content-center align-items-center text-center'>";
        _html += "	            <img src='" + _obj.image + "' style='width:100%;' alt='" + _obj.description + "'/>";
        _html += "              <p class='m-0 px-1'>" + _obj.description + "</p>";
        _html += "              <p class='m-0 px-1'>$ " + parseFloat(_obj.valorized).toLocaleString('de') + "</p>";
        _html += "            </div>";
        _html += "            <div class='modal-footer justify-content-center align-items-center text-center'>";
        _html += "              <button data-id='" + _obj.id + "' type='button' class='btn-raised btn-block btn btn-accept-alert btn-dark btn-sm mt-2'>Hablar con un vendedor</button>";
        _html += "            </div>";
        _html += "         </div>";
        _html += "      </div>";
        _html += "   </div>";
        $("body").append(_html);
        $("body").off("click", ".btn-close-itemcatalog").on("click", ".btn-close-itemcatalog", function () {
            $("#initCatalog").fadeIn("fast");
            _NMF.onDestroyModal("#initItemCatalog");
        });
        $("#initItemCatalog").modal({ backdrop: "static", keyboard: false, show: true });
        return true;
    },

    /*------------------------------------------*/
    /*Payments----------------------------------*/
    /*------------------------------------------*/
    onSelectPaymentPlatform: function (_items) {
        _NMF.onDestroyModal("#initPaymentPlatform");
        var _html = "<div class='modal fade' id='initPaymentPlatform' role='dialog' style='z-index: 999999;'>";
        _html += "      <div class='modal-wait-frame modal-dialog modal-dialog-centered modal-dialog-scrollable'>";
        _html += "         <div class='modal-content m-0 my-1' style='width:100%;'>";
        _html += "            <div class='modal-header' style='border:solid 0px white;'>";
        _html += "               <h4>Plataformas de pago</h4>";
        _html += "               <button type='button' class='close btn-close-catalog' data-dismiss='modal' aria-hidden='true' style='font-size:2.5rem;'>&times;</button>";
        _html += "            </div>";
        _html += "            <div class='modal-body justify-content-center align-items-center text-center'>";
        _html += "               <div class='row justify-content-center align-items-center text-center'>";
        _html += "                  <table style='100%'>";
        _html += "                     <tr>";
        _html += "                        <td class='pl-2' align='left' width='50%'><a href='#' class='btn btn-md btn-raised btn-info btn-block btn-platform' data-platform='mp'>Mercado Pago</a></td>";
        _html += "                        <td class='pr-2' align='right' width='50%'><a href='#' class='btn btn-md btn-raised btn-success btn-block btn-platform' data-platform='fiserv'>Fiserv</a></td>";
        _html += "                     </tr>";
        _html += "                  </table>";
        _html += "               </div>";
        _html += "            </div>";
        _html += "         </div>";
        _html += "      </div>";
        _html += "   </div>";
        $("body").append(_html);
        $("body").off("click", ".btn-close-catalog").on("click", ".btn-close-catalog", function () {
            _NMF.onDestroyModal("#initPaymentPlatform");
        });
        $("body").off("click", ".btn-platform").on("click", ".btn-platform", function () {
            _NMF.onPayment($(this), _items);
        });
        $("#initPaymentPlatform").modal({ backdrop: "static", keyboard: false, show: true });
        return true;
    },
    onPayment: function (_this, _items) {
        _NMF.onDestroyAllWindows();
        $.blockUI({ message: "<img src='./img/wait.gif' style='width:100%;'/>", css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
        var _total = 0;
        var _details = "<div class='shadow-sm m-0 mb-1 py-2 px-3 bg-white' style='border-radius:15px;'>"
        _details += "<b style='color:black;font-size:1.1rem;'>Detalles de la compra</b>";
        _details += "<table class='mt-2' style='width:100%;color:black;font-size:0.85rem;''>";
        $.each(_items, function (i, item) {
            _total += parseFloat(item.amount);
            _details += "      <tr>";
            _details += "         <td align='left' style='padding-left:5px;'>" + item.description + "</td><td align='right' style='padding-right:5px;'>$ " + item.amount.toLocaleString('de') + "</td>";
            _details += "      </tr>";
        });
        _details += "      <tr><td colspan='2'><hr/></td></tr>";

        _details += "      <tr class='trCuotas d-none'>";
        _details += "         <td colspan='2'>";
        _details += "            <select class='form-control cboCuotas' id='cboCuotas' name='cboCuotas'>";
        _details += "               <option value='1' data-interes='0' selected>1 cuota</option>";
        _details += "               <option value='3' data-interes='10'>3 cuotas</option>";
        _details += "               <option value='6' data-interes='20'>6 cuotas</option>";
        _details += "            </select>";
        _details += "         </td>";
        _details += "      </tr>";
        _details += "      <tr class='trCuotas d-none'><td colspan='2'><hr/></td></tr>";

        _details += "      <tr style='background-color:whitesmoke;'>";
        _details += "         <td align='left' style='padding-left:5px;'><b>Total</b></td>";
        _details += "         <td class='tdTotal' data-original='" + _total + "' data-total='" + _total + "' align='right' style='padding-right:5px;font-weight:bold;'>$ " + _total.toLocaleString('de') + "</td>";
        _details += "      </tr>";
        _details += "</table>";
        _details += "</div>";
        _details += "<button type='button' class='close btn-close-payment' aria-hidden='true' style='position:absolute;right:10px;top:0px;font-size:3rem;'>&times;</button>";

        $("body").off("click", ".btn-close-payment").on("click", ".btn-close-payment", function () {
            $("#paymentBrick_container").html("").addClass("d-none");
            $(".area-received").removeClass("d-none");
            $(".btn-shoppingcart").show();
            $(".app-home").removeClass("d-none");
        });
        $("body").off("change", ".cboCuotas").on("change", ".cboCuotas", function () {
            var _original = parseFloat($(".tdTotal").attr("data-original"));
            var _interes = parseFloat($(this).find(':selected').data('interes'));
            var _total = (_original + (_original * (_interes / 100)));
            $(".tdTotal").attr("data-total", _total);
            $(".tdTotal").html("$ " + _total.toLocaleString('de'));
            $("#dataPayment").remove();
            $("#dataIframe").remove();
            _NMF.onProcessFiserv($("#paymentBrick_container").html(), $(this).val());
        });

        switch (_this.attr("data-platform")) {
            case "mp": // Mercado pago
                _NMF.onProcessMercadoPago(_total, _details);
                break;
            case "fiserv": // fiserv
                _NMF.onProcessFiserv(_details, 1);
                break;
        }
        return true;
    },
    onProcessFiserv: function (_details, _cuotas) {
        var _html = _details;
        _html += "<div id='dataPayment'></div>";
        _html += "<div id='dataIframe'></div>";

        $("#paymentBrick_container").html(_html);
        var _total = $(".tdTotal").attr("data-total");
        $(".cboCuotas").val(_cuotas);
        $(".trCuotas").removeClass("d-none");

        var _json = {
            "formContainer": "dataPayment",
            "iframeContainer": "dataIframe",
            "type": "MIL", // Valor fijo
            "visible": 0, // Si se envia 0 no muestra el form intermedio
            "styleButton": "padding:10px;color:white;border:solid 1px green !important;background-color:green !important;",
            "dni": "",
            "currency": "032",
            "total": _total,
            "installments": _cuotas,
            "description": "Compra Tienda MIL",
            "sandbox":0
        };
        _FISERV.onPayFiserv(_json).then(function (data) {
            $("body").off("click", ".btn-pagar-fiserv").on("click", ".btn-pagar-fiserv", function (e) {
                $(this).addClass("d-none");
                $(".trCuotas").addClass("d-none");
                var _raw_request = JSON.stringify(_TOOLS.getFormValues(".dataPost", $(this)));
                var _json = {
                    "id_type_channel": 1,
                    "identificacion": _FISERV._itemsPagos[0]["Identificacion"],
                    "status": "INICIADO",
                    "currency_request": $("#currency").val(),
                    "amount_request": $("#chargetotal").val(),
                    "card_request": "",
                    "raw_request": _raw_request
                };
                _API.UiInitTransactionFiserv(_json).then(function (data) {
                    var _id = data.data.id;
                    _FISERV._itemsPagos[0]["idTransfer"] = _id;
                    $("#comments").val(JSON.stringify(_FISERV._itemsPagos));
                    clearInterval(_NMF._TMR_PAY_BOTONPAGO);
                    _NMF._TMR_PAY_BOTONPAGO = setInterval(function () {
                        _NMF.onCheckStatusPayment(_id);
                    }, 2000);
                });
            });
            $(".app-home").addClass("d-none");
            $("#paymentBrick_container").removeClass("d-none");
            $(".area-received").addClass("d-none");
            $(".btn-shoppingcart").hide();
            $.unblockUI();
            /*Si se van a seleccionar cuotas, se debe agregar la interface y logica de seleccion 
             previo al apretar el boton de pagar... de hech el boton de pagar, debe ser presionado por el usuario 
             */
            //$(".btn-pagar-fiserv").click();
        });
    },
    onProcessMercadoPago: function (_total, _details) {
        /*SANDBOX*/
        const mp = new MercadoPago('TEST-8eefe187-fdbd-41e3-986e-6a3972035ac4', {
            locale: 'es-AR'
        });
        const bricksBuilder = mp.bricks();
        const renderPaymentBrick = async (bricksBuilder) => {
            const settings = {
                initialization: {
                    amount: _total, // monto a ser pagado
                },
                customization: {
                    paymentMethods: {
                        creditCard: 'all',
                        debitCard: 'all',
                    },
                },
                callbacks: {
                    onReady: () => {
                        // callback llamado cuando Brick esté listo
                        /*Custom Neodata!*/
                        $(".app-home").addClass("d-none");
                        $("#paymentBrick_container").prepend(_details).removeClass("d-none");
                        $(".trCuotas").addClass("d-none");
                        $(".area-received").addClass("d-none");
                        $(".btn-shoppingcart").hide();
                        $.unblockUI();
                        /*--------------*/
                    },
                    onSubmit: (cardFormData) => {
                        //console.log("cardFormData->");console.log(cardFormData);

                        // callback llamado cuando el usuario haga clic en el botón enviar los datos
                        // ejemplo de envío de los datos recolectados por el Brick a su servidor
                        return new Promise(function (resolve, reject) {
                            fetch("./MercadoPago.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify(cardFormData)
                            })
                                .then(response => {
                                    // recibir el resultado del pago
                                    _NMF._lastResponse = response.json();
                                    _NMF._lastResponse.then(function (x) {
                                        //console.log("RESPONSE->");console.log(x);
                                        $(".btn-close-payment").click();
                                        var _message = "";
                                        if (x.status == "approved") {
                                            _message = (x.id + " " + x.status_detail);
                                            _NMF.onModalAlert("Información", "Su pago ha sido procesado exitosamente<br/>" + _message, "success");
                                            _NMF.onResetShoppingCart();
                                        } else {
                                            _message = x.status_detail;
                                            _NMF.onModalAlert("Alerta", "El pago no ha podido ser procesado<br/>" + _message, "danger");
                                        }
                                    });
                                    resolve();
                                })
                                .catch(error => {
                                    // tratar respuesta de error al intentar crear el pago
                                    console.log("ERROR->");
                                    console.log(error);
                                    reject();
                                });
                        });
                    },
                    onError: function (error) {
                        // callback llamado para todos los casos de error de Brick
                    },
                },
            };
            const paymentBrickController = await bricksBuilder.create('payment', 'paymentBrick_container', settings);
        };
        renderPaymentBrick(bricksBuilder);
    },
    onCheckStatusPayment: function (_id) {
        var _idTransfer = _id;
        var _json = { "where": ("id=" + _idTransfer) };
        _AJAX._blockUI = false;
        _API.UiCheckStatusPayment(_json).then(function (datajson) {
            if (datajson.data[0].status != "INICIADO") {
                clearInterval(_NMF._TMR_PAY_BOTONPAGO);
                if (datajson.data[0].status == "APROBADO") {
                    _NMF.onModalAlert("Operación exitosa", "Su pago ha sido procesado. #" + _idTransfer + " " + _TOOLS.getNow(), "alert-success");
                    _NMF.onResetShoppingCart();
                } else {
                    _NMF.onModalAlert("Alerta", "Su pago no ha podido ser procesado.  Reintente con otro medio de pago.", "alert-danger");
                }
            }
        }).catch(function (error) {
            alert(error.message);
        });
    },
    /*------------------------------------------*/

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
            _NMF.onModalInitial("normal");
        };
        _NEOVIDEO.onParticipantJoined = function (data) {
            $(".active-video").removeClass("d-none");
            _NMF.onEvalShoppingCartVisibility();
            _NMF.onDestroyModal("#initWaiting");
        };
        _NEOVIDEO.onParticipantLeft = function (data) {
            $(".active-video").addClass("d-none");
            _NEOVIDEO.onTurnOffVideo();
            _NMF.onDestroyModal("#initWaiting");
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

            _NMF._TIMER_STATUS = setInterval(function () { _NMF.onCommunicationStatus(); }, 5000);
            _NMF.onDestroyAllWindows();
            _NMF.onModalWaiting();
            var _params = {
                "id_group": 1088,
                "subject": "Se ha solicitado atención en Tienda MIL",
                "body": "Un cliente ha iniciado una solicitud de atención y está en espera."
            };
            _API.UiSendPushToGroup(_params);
        });
    },
    onJoinOpenSession: function (_this) {
        if (_NMF._live == 0) { alert("¡Ha finalizado el vivo!"); return; }

        _NEOVIDEO.onDisconnect = function (data) {
            $(".active-video").addClass("d-none");
            if (_NMF._live != 0) { setTimeout(function () { _NMF.onModalInitial("normal"); }, 3000); }
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
        _NEOVIDEO.onJoinOpenSession(_NMF._live).then(function (data) {
            $('[name ^= "jitsiConferenceFrame"]').css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
            $(".active-video").removeClass("d-none");
            $(_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.target).fadeIn("slow");
            _NMF.onDestroyAllWindows();
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
        var _obj = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-raw")));
        $(".item-received-" + _obj.id_item + "-" + _obj.id).remove();
        _NMF._itemsShoppingCart.push(_TOOLS.utf8_to_b64(JSON.stringify(_obj)));
        _NMF.onEvalShoppingCartVisibility();
        //_TOOLS.setCookie("shoppingcarttiendamil", JSON.stringify(_NMF._itemsShoppingCart), 1);
        _NMF.onDestroyModal("#initReceived");
    },
    onResetShoppingCart: function() {
        $(".trItemCart").remove();
        _NMF._itemsShoppingCart = [];
        $(".btn-shoppingcart").addClass("d-none");
        $(".badge-shoppingcart").addClass("d-none");
    },
    onCommunicationStatus: function () {
        _NMF.onEvalShoppingCartVisibility();
        _NEOVIDEO.onGetRelatedData({ "id": _NEOVIDEO._id_transaction }).then(function (status) {
            if (status.status == "OK") {
                $.each(status.records, function (i, item) {
                    var _obj = JSON.parse(_TOOLS.b64_to_utf8(item.raw_data));
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
            var _prior = _NMF._live;
            _NMF._live = 0;
            $.each(data.records, function (i, item) {
                if (i == 0) { _NMF._live = item.id; }
            });
            if (_prior != _NMF._live) { _NMF.onModalInitial("normal"); }
        });
    },

    onRemoveItem: function (_this) {
        var _obj = JSON.parse(_TOOLS.b64_to_utf8(_this.attr("data-raw")));
        $(".item-received-" + _obj.id_item + "-" + _obj.id).remove();
        //_TOOLS.setCookie("shoppingcarttiendamil", JSON.stringify(_NMF._itemsShoppingCart), 1);
        _NMF.onDestroyModal("#initReceived");
    },
    onDeleteItemCart: function (_this) {
        if (!confirm("¿Confirma quitar el producto de su carrito de compras?")) { return false; }
        var _index = _this.attr("data-index");
        $(".trItemCart-" + _index).fadeOut("fast", function () {
            $(".trItemCart-" + _index).remove();
            _NMF.onTotalizeCart();
            _NMF._itemsShoppingCart.splice(_index, 1);
            _NMF.onEvalShoppingCartVisibility();
            //_TOOLS.setCookie("shoppingcarttiendamil", JSON.stringify(_NMF._itemsShoppingCart), 1);
            if (_NMF._itemsShoppingCart == 0) { $(".btn-close-cart").click(); }
        });
    },
    onEvalShoppingCartVisibility: function () {
        $(".badge-shoppingcart").html(_NMF._itemsShoppingCart.length);
        if (_NMF._itemsShoppingCart.length != 0) {
            $(".btn-shoppingcart").removeClass("d-none");
            $(".badge-shoppingcart").removeClass("d-none");
        } else {
            $(".btn-shoppingcart").addClass("d-none");
            $(".badge-shoppingcart").addClass("d-none");
        }
    },
    onItemCatalogo: function (_obj) {
        var _record = _TOOLS.utf8_to_b64(JSON.stringify(_obj));
        var _html = "";
        _html += "<div class='item-catalogo col-5 shadow p-1 mb-2 mx-2 btn-see-details' data-raw='" + _record + "'>";
        _html += "	<img src='" + _obj.image + "' style='border-radius:15px;width:100%;' alt='" + _obj.description + "'/>";
        _html += "  <p class='m-0 px-1'>" + _obj.description + "</p>";
        _html += "</div>";
        return _html;
    },
}
