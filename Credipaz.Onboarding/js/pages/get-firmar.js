var _fnc = new Object();
var _formulario = "";
var oAppGetFirmar = new Object();
oAppGetFirmar.onShow = function () {
    _AJAX._BPAM["Formulario"] = _AJAX._formularioFirma;
    _AJAX._BPAM["ValueForRetrieve"] = _AJAX._KEY;

    _API.UiGetFormulario(_AJAX._BPAM).then(function (data) {
        _NMF.onBuildFirmaDocumento(data, _AJAX._formularioPrefijoCarpetaDigital, "", 1, 50, 380);
    });
}
_fnc.getReference = function () {
    return oAppGetFirmar;
};
