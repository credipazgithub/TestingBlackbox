var _fnc = new Object();
var oAppFirmaCreditos = new Object();

oAppFirmaCreditos.onShow = function () {
    $(".headerBar").css({ "background": "rgb(110, 3, 73)", "background": "linear-gradient(0deg, rgba(110, 3,73,1) 0%, rgba(224,0,125,1) 69%)" });
    $(".imgHeaderFirma").attr("src", "img/logo-yellow.png");
    setTimeout(function () {
        $(".topMenu").addClass("d-none");
        $("#qrcode").html("").addClass("d-none");
        _API_deprecated.UiOnboardingGetRequest({ "id": _AJAX_deprecated._KEY, "end": "AK" }).then(function (data) {
            _F.onSetSolicitudData(data);
            switch (parseInt(data.data.id_type_request)) {
                case 1: // credito Credipaz
                    $(".btnSeeRequestPagare").removeClass("d-none");
                    break;
                case 3: // credito Amutra
                    $(".btnSeeRequestAmutra").removeClass("d-none");
                    break;
            }
            switch (parseInt(data.data.id_type_request)) {
                case 1: // credito Credipaz
                case 2: // refinanciacion credito Credipaz
                case 3: // refinanciacion credito Amutra
                case 4: // refinanciacion credito Amutra 
                case 5: // credito Credipaz Vivienda
                case 6: // credito Credipaz Hogar
                case 7: // credito Credipaz Consumo
                case 8: // credito Amutra Vivienda
                case 9: // credito Amutra Hogar
                case 10: // credito Amutra Consumo
                case 563: // refinanciacion tarjeta CABAL
                    switch (parseInt(_F._ClientData._solicitudData.id_type_modo_pago)) {
                        case 1: // efectivo
                            $(".EfectivoArea").removeClass("d-none");
                            break;
                        default: // cbu y otras formas a definir
                            $(".CBUArea").removeClass("d-none");
                            break;
                    }
                case 566: // refinanciacion tarjeta CABAL/CABAL
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
                        _F._ClientData._solicitudData.img_additional = "";
                        $(".img-firma").attr("src", "./img/placeholder.png");
                        $(".afirmar").removeClass("d-none");
                        $(".firmado").addClass("d-none");
                    });
                    $("body").off("click", ".btn-ok-sign").on("click", ".btn-ok-sign", function () {
                        if (!_F._ClientData.dirty) {
                            _F.onModalAlert("Control de firma", "Debe firmar la solicitud", "warning");
                            return false;
                        }
                        _F._ClientData._solicitudData.img_additional = sketchpad.toImage();
                        $(".img-firma").attr("src", _F._ClientData._solicitudData.img_additional);
                        $(".msgVerificacion").html("");
                        $(".btn-clear-sign").addClass("d-none");
                        $(".afirmar").addClass("d-none");
                        $(".firmado").removeClass("d-none");
                        _F.onEmitirProducto(_F._ClientData._solicitudData.img_additional);
                    });

                    sketchpad.addEventListener('dirty', () => _F._ClientData.dirty = true);
                    sketchpad.addEventListener('clean', () => _F._ClientData.dirty = false);
                    sketchpad.addEventListener('strokestart', function () { });
                    sketchpad.addEventListener('strokeend', function () { });
                    $(".sketchpad").css("border", "solid 1px red");
                    if (_AJAX_deprecated._monopage) { $(".btnBack").remove(); }
                    break;
                case 17: // Verificacion de identidad
                    _AJAX_deprecated._responseTitle = "¡Gracias por confiar en nosotros!";
                    _AJAX_deprecated._responseMessage = "Proceso de verificación de identidad finalizado.";
                    _F.onTryPage(null, "msg-ok");
                    break;
                default:
                    break;
            }
        }).catch(function (err) {
            console.log("ERR");
            console.log(err);
        });
    }, 500);
};

_fnc.getReference = function () {
    return oAppFirmaCreditos;
};
