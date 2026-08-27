var _fnc = new Object();
var oAppMsgFirmado = new Object();

oAppMsgFirmado.onShow = function () {
    setTimeout(function () {
        $(".topMenu").addClass("d-none");
        $(".responseMessage").html(_AJAX_deprecated._responseMessage);
        $(".btnSeeDocument").attr("href", _F._ClientData.link_extract);
        $(".btnSeeCertificate").attr("href", _F._ClientData.link_certificate);
    }, 250);
};

_fnc.getReference = function () {
    return oAppMsgFirmado;
};
