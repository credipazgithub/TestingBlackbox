var _fnc = new Object();
var oAppGetTokenizar = new Object();

oAppGetTokenizar.onShow = function () {
    setTimeout(function () {
        $(".headerBar").css({ "background": "rgb(110, 3, 73)", "background": "linear-gradient(0deg, rgba(110, 3,73,1) 0%, rgba(224,0,125,1) 69%)" });
        $(".imgHeaderFirma").attr("src", "img/logo-yellow.png");
        $(".wId_type_medio_cobro").val(_AJAX._id_type_medio_cobro);
        var currentYear = new Date().getFullYear();
        var lastYear = (currentYear + 10);
        for (var i = currentYear; i <= lastYear; i++) {
            $(".wYY").append("<option value='" + i.toString().slice(-2) + "'>" + i + "</option>");
        }
        switch (_AJAX._formularioFirma) {
            case "tokenizar-tdnt": //Tokenizaciones-tarjetas debito Neutrales
                $(".areaDocumento").removeClass("d-none");
                $(".areaMensaje").html("Los datos se registrarán solo para el uso autorizado por el cliente.");
                break;
            case "tokenizar-tdcp": //Tokenizaciones-tarjetas debito Credipaz
                _API.UiOnboardingGetRequest({ "id": _AJAX._KEY, "end": "AK" }).then(function (data) {
                    $(".IdTransaccion").val(data.data.IdTransaccion);
                    $(".areaMensaje").html("Los datos se registrarán a la transacción " + data.data.IdTransaccion);
                });
                break;
            case "tokenizar-tdmy": //Tokenizaciones-tarjetas debito Mediya
                $(".headerBar").css({ "background": "#0dabff" });
                $(".imgHeaderFirma").attr("src", "img/logo-mediya.png");               $(".IdSocio").val(_AJAX._KEY);
                $(".areaMensaje").html("Los datos se registrarán al socio " + _AJAX._KEY);
                break;
        }
        $("body").off("click", ".btn-ok-tokenizar").on("click", ".btn-ok-tokenizar", function () {
            if (!_TOOLS.validate(".wvalidate")) { return false; }
            var _this = $(this);
            _this.addClass("d-none");
            var _params = _TOOLS.getFormValues(".dbase");
            _API.UiOnboardingTokenizar(_params).then(function (data) {
                if (data.message.logica) {
                    //_NMF.onModalAlert("Registro de datos", "Datos de tarjeta enviados", "info");
                    _AJAX._responseTitle = "Datos de tarjeta";
                    _AJAX._responseMessage = "¡Gracias por enviar los datos solicitados!";
                    _NMF.onTryPage(null, "msg-ok");
                } else {
                    _NMF.onModalAlert("Error", data.message.mensaje, "danger");
                    _this.removeClass("d-none");
                }
            }).catch(function (err) {
                _NMF.onModalAlert("Error", err.mensaje, "danger");
                _this.removeClass("d-none");
            });
        });
    }, 250);
};

_fnc.getReference = function () {
    return oAppGetTokenizar;
};
