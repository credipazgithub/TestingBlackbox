var _fnc = new Object();
var oAppGetTokenizar = new Object();

oAppGetTokenizar.onShow = function () {
    setTimeout(function () {
        $(".headerBar").css({ "background": "rgb(110, 3, 73)", "background": "linear-gradient(0deg, rgba(110, 3,73,1) 0%, rgba(224,0,125,1) 69%)" });
        $(".imgHeaderFirma").attr("src", "img/logo-yellow.png");
        $(".wId_type_medio_cobro").val(_AJAX_deprecated._id_type_medio_cobro);
        var currentYear = new Date().getFullYear();
        var lastYear = (currentYear + 10);
        for (var i = currentYear; i <= lastYear; i++) {
            $(".wYY").append("<option value='" + i.toString().slice(-2) + "'>" + i + "</option>");
        }
        switch (_AJAX_deprecated._formularioFirma) {
            case "tokenizar-tdnt": //Tokenizaciones-tarjetas debito Neutrales
                $(".wIdEmpresaOrigen").val("1");
                $(".areaDocumento").removeClass("d-none");
                $(".areaMensaje").html("Los datos se registrarán solo para el uso autorizado por el cliente.");
                break;
            case "tokenizar-tdcp": //Tokenizaciones-tarjetas debito Credipaz
                _API_deprecated.UiOnboardingGetRequest({ "id": _AJAX_deprecated._KEY, "end": "AK" }).then(function (data) {
                    $(".wIdEmpresaOrigen").val("1");
                    $(".IdTransaccion").val(data.data.IdTransaccion);
                    $(".areaMensaje").html("Los datos se registrarán a la transacción " + data.data.IdTransaccion);
                });
                break;
            case "tokenizar-tdmy": //Tokenizaciones-tarjetas debito Mediya
                $(".headerBar").css({ "background": "#0dabff" });
                $(".imgHeaderFirma").attr("src", "img/logo-mediya.png");
                $(".IdSocio").val(_AJAX_deprecated._KEY);
                $(".wIdEmpresaOrigen").val("3");
                $(".areaMensaje").html("Los datos se registrarán al socio " + _AJAX_deprecated._KEY);
                break;
        }
        $("body").off("click", ".btn-ok-tokenizar").on("click", ".btn-ok-tokenizar", function () {
            if (!_T.validate(".wvalidate")) { return false; }
            var _this = $(this);
            _this.addClass("d-none");
            var _params = _T.getFormValues(".dbase");
            _API_deprecated.UiOnboardingTokenizar(_params).then(function (data) {
                if (data.message.logica) {
                    //_F.onModalAlert("Registro de datos", "Datos de tarjeta enviados", "info");
                    _AJAX_deprecated._responseTitle = "Datos de tarjeta";
                    _AJAX_deprecated._responseMessage = "¡Gracias por enviar los datos solicitados!";
                    _F.onTryPage(null, "msg-ok");
                } else {
                    _F.onModalAlert("Error", data.message.mensaje, "danger");
                    _this.removeClass("d-none");
                }
            }).catch(function (err) {
                _F.onModalAlert("Error", err.mensaje, "danger");
                _this.removeClass("d-none");
            });
        });
    }, 250);
};

_fnc.getReference = function () {
    return oAppGetTokenizar;
};
