var exitApp = false;
var intval = setInterval(function () { exitApp = false; }, 1000);
$("body").off("input", ".onlyNumbers").on("input", ".onlyNumbers", function () {
    $(this).val($(this).val().replace(/[^0-9]/g, ''));
});
$("body").off("click", ".btnPage").on("click", ".btnPage", function () {
    _NMF.onTryPage($(this), $(this).attr("data-scope"));
});
$("body").off("click", ".btnValidationStep").on("click", ".btnValidationStep", function () {
    _NMF.onValidateStep($(this));
});
$("body").off("click", ".btnCamera").on("click", ".btnCamera", function () {
    _NMF.onCameraOn($(this));
});
$("body").off("change", ".camera").on("change", ".camera", function (e) {
    _NMF.onCameraChange($(this), e);
});
$("body").off("click", ".btnCapture").on("click", ".btnCapture", function () {
    if (_TOOLS.validate(".validateCapture")) {
        _VIDEO.onCameraOn($(this));
    } else {
        _NMF.onModalAlert("Faltan datos", "Complete los datos requeridos", "danger");
    }
});
$("body").off("click", ".btnTakePicture").on("click", ".btnTakePicture", function () {
    _VIDEO.onTakePicture();
});
$("body").off("click", ".btnCancelPicture").on("click", ".btnCancelPicture", function () {
    _VIDEO.onCameraOff(true);
});
$("body").off("click", ".btnSeeRequest").on("click", ".btnSeeRequest", function () {
    _NMF.onSeeRequest($(this));
});
$("body").off("click", ".btnSeeRequestPagare").on("click", ".btnSeeRequestPagare", function () {
    _NMF.onSeeRequestPagare($(this));
});
$("body").off("click", ".btnSeeRequestAmutra").on("click", ".btnSeeRequestAmutra", function () {
    _NMF.onSeeRequestAmutra($(this));
});
