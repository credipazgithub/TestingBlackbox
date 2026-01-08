var _fnc = new Object();
var _formulario = "";
var oAppGetFirmar = new Object();
oAppGetFirmar.onShow = function () {
    _AJAX._BPAM["Formulario"] = _AJAX._formularioFirma;
    _AJAX._BPAM["ValueForRetrieve"] = _AJAX._KEY;

    switch (_AJAX._formularioFirma) {
        case "adhesionmediya":
            $(".headerBar").css({ "background": "#0dabff"});
            $(".imgHeaderFirma").attr("src", "img/logo-mediya.png");
            break;
        default:
            $(".headerBar").css({ "background": "rgb(110, 3, 73)", "background": "linear-gradient(0deg, rgba(110, 3,73,1) 0%, rgba(224,0,125,1) 69%)" });
            $(".imgHeaderFirma").attr("src", "img/logo-yellow.png");
            break;
    }




    _API.UiGetFormulario(_AJAX._BPAM).then(function (data) {
        _NMF.onBuildFirmaDocumento(data, _AJAX._formularioPrefijoCarpetaDigital, "", 1, 50, 380);
    });
}
_fnc.getReference = function () {
    return oAppGetFirmar;
};
