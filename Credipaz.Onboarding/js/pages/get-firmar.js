var _fnc = new Object();
var _formulario = "";
var oAppGetFirmar = new Object();
oAppGetFirmar.onShow = function () {
    _AJAX_deprecated._BPAM["Formulario"] = _AJAX_deprecated._formularioFirma;
    _AJAX_deprecated._BPAM["ValueForRetrieve"] = _AJAX_deprecated._KEY;

    switch (_AJAX_deprecated._formularioFirma) {
        case "adhesionamutramediya":
        case "adhesiongrupofamiliarmediya":
        case "adhesionmediya":
            $(".headerBar").css({ "background": "#0dabff"});
            $(".imgHeaderFirma").attr("src", "img/logo-mediya.png");
            break;
        default:
            $(".headerBar").css({ "background": "rgb(110, 3, 73)", "background": "linear-gradient(0deg, rgba(110, 3,73,1) 0%, rgba(224,0,125,1) 69%)" });
            $(".imgHeaderFirma").attr("src", "img/logo-yellow.png");
            break;
    }

    _API_deprecated.UiGetFormulario(_AJAX_deprecated._BPAM).then(function (data) {
        _F.onBuildFirmaDocumento(data, _AJAX_deprecated._formularioPrefijoCarpetaDigital, "", 1, 50, 380);
    });
}
_fnc.getReference = function () {
    return oAppGetFirmar;
};
