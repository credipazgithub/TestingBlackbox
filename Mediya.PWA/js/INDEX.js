var exitApp = false;
var intval = setInterval(function () { exitApp = false; }, 1000);
var FirebasePlugin;

$("body").off("click", ".btn-data-page").on("click", ".btn-data-page", function () {
    _NMF.onChangePage($(this));
});
$("body").off("click", ".btnInfiniteLoader").on("click", ".btnInfiniteLoader", function () {
    _NMF.onNextResultPage($(this));
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
$("body").off("click", ".btn-take-picture").on("click", ".btn-take-picture", function () {
    var permissions = cordova.plugins.permissions;
    var list = [permissions.CAMERA, permissions.RECORD_AUDIO];
    permissions.hasPermission(
        list,
        function (status) {
            if (!status.hasPermission) {
                permissions.requestPermissions(
                    list,
                    function (status) {
                        if (status.hasPermission) {
                            _PHOTO.onGetPicture($(this));
                        }
                    }, null);
            } else {
                _PHOTO.onGetPicture($(this));
            }
        }, null);
});
$("body").off("input", ".onlyNumbers").on("input", ".onlyNumbers", function () {
    _TOOLS.onlyNumbers($(this));
});
$("body").off("click", ".btn-generate-paycode").on("click", ".btn-generate-paycode", function () {
    _PAY.onGeneratePermisoTelemedicina($(this));
});
$("body").off("click", ".btn-generate-paycode-legal").on("click", ".btn-generate-paycode-legal", function () {
    _PAY.onGeneratePaycodeLegal($(this));
});
$("body").off("click", ".btn-vercanje").on("click", ".btn-vercanje", function () {
    _NMF.onVerCanje($(this));
});
$("body").off("click", ".btn-see-message").on("click", ".btn-see-message", function () {
    _NMF.onViewDirectTelemedicina($(this));
});
$("body").off("click", ".btn-see-message-pdf").on("click", ".btn-see-message-pdf", function () {
    _NMF.onViewDirectTelemedicinaPDF($(this));
});
$("body").off("click", ".btn-comprobantes").on("click", ".btn-comprobantes", function () {
    _NMF.onBtnComprobantes($(this));
});
$("body").off("click", ".btn-see-comprobante").on("click", ".btn-see-comprobante", function () {
    _NMF.onSeeComprobante($(this));
});
$("body").off("click", ".btnGetPDF").on("click", ".btnGetPDF", function () {
    _NMF.onGetPDF($(this));
});
$("body").off("click", ".btn-hd").on("click", ".btn-hd", function () {
    if ($(".canvaImg").is(":visible")) {
        $(".canvaImg").hide();
    } else {
        $(".canvaImg").show();
    }
});

$("body").off("click", ".btnVerCupon").on("click", ".btnVerCupon", function () {
    _NMF.onVerCupon($(this),true);
});
$("body").off("click", ".btnVerCuponNoCanjea").on("click", ".btnVerCuponNoCanjea", function () {
    //_NMF.onVerCupon($(this),false);
});
$("body").off("click", ".closeModal").on("click", ".closeModal", function () {
    _NMF.onDestroyModal($(this).attr("data-modal"));
});
$("body").off("click", ".see-map").on("click", ".see-map", function () {
    _NMF.onSeeMap($(this));
});
$("body").off("click", ".see-listado").on("click", ".see-listado", function () {
    _NMF.onSeeListado($(this));
});

$("body").off("click", ".btn-search-map").on("click", ".btn-search-map", function () {
    _NMF.onSearchMap($(this));
});
$("body").off("click", ".btn-near-map").on("click", ".btn-near-map", function () {
    _NMF.onNearMap($(this));
});
$("body").off("click", ".item-mapa").on("click", ".item-mapa", function (e) {
    _NMF.onSelectItemMapa($(this));
});
$("body").off("keypress", ".searcher").on("keypress", ".searcher", function (e) {
    var _this = $(this);
    clearTimeout(_NMF._TIMER_LAZY);
    _NMF._TIMER_LAZY = setTimeout(function () {
        _NMF.onChangePage(_this);
        setTimeout(function () { _NMF.onSearchFromBar(_this); }, 200);
    }, 500);
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
$("body").off("click", ".toggle-cobertura").on("click", ".toggle-cobertura", function () {
    _NMF.onToggleCobertura($(this));
});
function initAll() {
    $.getScript((_NMF._cdn_server + "maps/markerwithlabel.js"), function () {
        $.getScript((_NMF._cdn_server + "maps/GMAP.js"), function () {
            $.getScript("https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js", function () {
                window.jsPDF = window.jspdf.jsPDF;
            });
        });
    });
}
