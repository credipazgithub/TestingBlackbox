$("body").off("input", ".onlyNumbers").on("input", ".onlyNumbers", function () {
    $(this).val($(this).val().replace(/[^0-9]/g, ''));
});
$("body").off("click", ".btnBack").on("click", ".btnBack", function () {
    _NMF.onBack($(this));
});
$("body").off("click", ".btnAccion").on("click", ".btnAccion", function () {
    _NMF.onAccion($(this));
});
$("body").off("change", ".cbodocumento").on("change", ".cbodocumento", function () {
    _NMF.onChangeDocumento($(this));
});
$("body").off("click", ".btn-exec").on("click", ".btn-exec", function () {
    _NMF.onSendToServer($(this));
});
