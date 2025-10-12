var _fnc = new Object();
var oAppdIdemiaPaso2 = new Object();

oAppdIdemiaPaso2.onShow = function () {
    setTimeout(function () {
        $("#externaltxid").val(_NMF._ClientData._solicitudData.id + "-2" + _NMF._ClientData._solicitudData.sqIdemia);
        $("#dni").val(_NMF._ClientData._solicitudData.Documento);
        $("#sexo").val(_NMF._ClientData._solicitudData.Sexo);
        $(".topMenu").addClass("d-none");

        if (!_TOOLS.isMobileDevice()) {
            $(".msg-2").html("Debe continuar la operación desde un teléfono móvil");
            _NMF.onDrawQRUrl("idemia-paso-2",true);
        } else {
            $.blockUI({ message: "<img src='./img/wait.gif' style='width:100px;' />", css: { width: '100px', border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
            _NMF._ClientData._solicitudData.controlPoint = "VALIDACION ROSTRO";
            _API.UiOnboardingSaveRequest(_NMF._ClientData._solicitudData).then(function (data) {
                if (data.tokenId == null) {
                    _NMF.onModalAlert("Se ha producido un error", data.message, "warning");
                    return false;
                }
                $("#seckey").val(data.tokenId);
                setTimeout(function () { $('form#idemia').submit(); }, 1);
            }).catch(function (err) {
                _NMF.onModalAlert("Se ha producido un error", err, "warning");
            });
        }
    }, 100);
};

_fnc.getReference = function () {
    return oAppdIdemiaPaso2;
};
