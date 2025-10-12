var exitApp = false;
var intval = setInterval(function () { exitApp = false; }, 1000);
var FirebasePlugin;
$("body").off("click", ".btnVerPANCABAL").on("click", ".btnVerPANCABAL", function () {
    var _this = $(this);
    _this.hide();
    _NMF.onVerPanCabal(null, true);
    setTimeout(function () { _NMF.onVerPanCabal(null, false); _this.show(); }, 10000)
});
$("body").off("click", ".btn-data-page").on("click", ".btn-data-page", function () {
    _NMF.onChangePage($(this));
});
$("body").off("click", ".btn-action-login").on("click", ".btn-action-login", function () {
    if (!_TOOLS.validate(".loginValidate")) {
        _NMF.onModalAlert("Datos faltantes", "Complete los datos requeridos", "warning");
        return false;
    }
    _NMF._auth_user_data.dni = $(".dni").val();
    _NMF._auth_user_data.password = $(".password").val();
    _NMF._auth_user_data.sex = $(".sex").val();
    _NMF._auth_user_data.email = (_NMF._auth_user_data.dni + _HTTPREQUEST._sufixEmail);
    _NMF.onActionLogin($(this));
});
$("body").off("click", ".btn-action-logout").on("click", ".btn-action-logout", function () {
    _NMF.onActionLogout($(this));
});
$("body").off("click", ".btn-action-exit").on("click", ".btn-action-exit", function () {
    _NMF.onActionExit($(this));
});
$("body").off("click", ".btn-action-forget").on("click", ".btn-action-forget", function () {
    _NMF.onActionForget($(this));
});
$("body").off("click", ".toggle-secret").on("click", ".toggle-secret", function () {
    _NMF.onToggleSecret($(this), ".password");
});
$("body").off("click", ".toggle-sidebar").on("click", ".toggle-sidebar", function () {
    _NMF.onToggleSidebar($(this));
});
$("body").off("click", ".close-sidebar").on("click", ".close-sidebar", function () {
    _NMF.onCloseSidebar($(this));
});
$("body").off("click", ".btn-back-sidebar").on("click", ".btn-back-sidebar", function () {
    _NMF.onToggleSidebar($(this));
    $(".page-sections").html("");
});
$("body").off("click", ".btn-action-accept").on("click", ".btn-action-accept", function () {
    _NMF.onAcceptCheckBox($(this));
});
$("body").off("click", ".model-by-id").on("click", ".model-by-id", function () {
    _NMF.onModalByIdPost($(this));
});
$("body").off("click", ".btn-close-modal").on("click", ".btn-close-modal", function () {
    _NMF.onDestroyModal("#modal-html");
});
$("body").off("click", ".btn-i-am").on("click", ".btn-i-am", function () {
    _NMF.onSelectMyName($(this));
});
$("body").off("click", ".closeModal").on("click", ".closeModal", function () {
    _NMF.onDestroyModal($(this).attr("data-modal"));
});
$("body").off("click", ".noinstall").on("click", ".noinstall", function () {
    _NMF.onNoInstallApp();
});
$("body").off("click", ".install").on("click", ".install", function () {
    installApp();
});
$("body").off("click", ".fixinstall").on("click", ".fixinstall", function () {
    _DB.Set("pwa_install", { "ask": false });
    _NMF.onDestroyModal("#alterModal");
});
$("body").off("click", ".fixnoinstall").on("click", ".fixnoinstall", function () {
    _DB.Set("pwa_install", { "ask": true });
    _NMF.onDestroyModal("#alterModal");
});
