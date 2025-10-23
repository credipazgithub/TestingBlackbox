var _fnc = new Object();
var oAppFirmaCreditos = new Object();

oAppFirmaCreditos.onShow = function () {
    setTimeout(function () {
        $(".topMenu").addClass("d-none");
        $("#qrcode").html("").addClass("d-none");
        _API.UiOnboardingGetRequest({ "id": _AJAX._KEY, "end": "AK" }).then(function (data) {
            _NMF.onSetSolicitudData(data);
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
                case 5: // credito Credipaz Vivienda
                case 6: // credito Credipaz Hogar
                case 7: // credito Credipaz Consumo
                case 3: // credito Amutra
                case 8: // credito Amutra Vivienda
                case 9: // credito Amutra Hogar
                case 10: // credito Amutra Consumo
                    switch (parseInt(_NMF._ClientData._solicitudData.id_type_modo_pago)) {
                        case 1: // efectivo
                            $(".EfectivoArea").removeClass("d-none");
                            break;
                        default: // cbu y otras formas a definir
                            $(".CBUArea").removeClass("d-none");
                            break;
                    }
                case 351: // Tarjeta titular
                case 451: // Tarjeta adicional
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
                        _NMF._ClientData._solicitudData.img_additional = "";
                        $(".img-firma").attr("src", "./img/placeholder.png");
                        $(".afirmar").removeClass("d-none");
                        $(".firmado").addClass("d-none");
                    });
                    $("body").off("click", ".btn-ok-sign").on("click", ".btn-ok-sign", function () {
                        if (!_NMF._ClientData.dirty) {
                            _NMF.onModalAlert("Control de firma", "Debe firmar la solicitud", "warning");
                            return false;
                        }
                        _NMF._ClientData._solicitudData.img_additional = sketchpad.toImage();
                        $(".img-firma").attr("src", _NMF._ClientData._solicitudData.img_additional);
                        $(".msgVerificacion").html("");
                        $(".btn-clear-sign").addClass("d-none");
                        $(".afirmar").addClass("d-none");
                        $(".firmado").removeClass("d-none");
                        _NMF.onEmitirProducto(_NMF._ClientData._solicitudData.img_additional);
                    });

                    sketchpad.addEventListener('dirty', () => _NMF._ClientData.dirty = true);
                    sketchpad.addEventListener('clean', () => _NMF._ClientData.dirty = false);
                    sketchpad.addEventListener('strokestart', function () { });
                    sketchpad.addEventListener('strokeend', function () { });
                    $(".sketchpad").css("border", "solid 1px red");
                    if (_AJAX._monopage) { $(".btnBack").remove(); }
                    break;
                case 17: // Verificacion de identidad
                    _AJAX._responseTitle = "¡Gracias por confiar en nosotros!";
                    _AJAX._responseMessage = "Proceso de verificación de identidad finalizado.";
                    _NMF.onTryPage(null, "msg-ok");
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
