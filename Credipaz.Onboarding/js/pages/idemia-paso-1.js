var _fnc = new Object();
var oAppIdemiaPaso1 = new Object();

oAppIdemiaPaso1.onShow = function () {
    setTimeout(function () {
        $(".idemia-paso-1").removeClass("d-none");
        $("#externaltxid").val(_NMF._ClientData._solicitudData.id + "-1pre-" + _NMF._ClientData._solicitudData.sqIdemia);
        $("#dni").val(_NMF._ClientData._solicitudData.Documento);
        $("#sexo").val(_NMF._ClientData._solicitudData.Sexo);
        $(".topMenu").addClass("d-none");

        if (!_TOOLS.isMobileDevice()) {
            $(".msg-2").html("Debe continuar la operación desde un teléfono móvil");
            _NMF.onDrawQRUrl("idemia-paso-1", true);
        } else {
            $.blockUI({ message: "<img src='./img/wait.gif' style='width:100px;' />", css: { width: '100px', border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
            _NMF._ClientData._solicitudData.controlPoint = "VALIDACION DNI";
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
    return oAppIdemiaPaso1;
};
