var _fnc = new Object();
var oAppGetFoto = new Object();

oAppGetFoto.onShow = function () {
    $(".get-foto").append("<div class='foto-" + _AJAX._idFoto + "' style='width:100%;'></div>");
    setTimeout(function () {
        _NMF.onPagePhoto(_AJAX._idFoto, _AJAX_descriptionFoto, _AJAX_customFoto, _AJAX_scopeFoto, _AJAX_nodeFoto);
        _NMF.onDrawQRUrl("get-foto",false);
    }, 250);
};

_fnc.getReference = function () {
    return oAppGetFoto;
};
