var exitApp = false;
var intval = setInterval(function () { exitApp = false; }, 1000);
var FirebasePlugin;

$("body").off("click", ".btn-data-page").on("click", ".btn-data-page", function () {
    _F.onChangePage($(this));
});
$("body").off("click", ".btnInfiniteLoader").on("click", ".btnInfiniteLoader", function () {
    _F.onNextResultPage($(this));
});
$("body").off("click", ".btn-action-login").on("click", ".btn-action-login", function () {
    if (!_T.validate(".loginValidate")) {
        _F.onModalAlert("Datos faltantes", "Complete los datos requeridos", "warning");
        return false;
    }
    _F._auth_user_data.dni = $(".dni").val();
    _F._auth_user_data.password = $(".password").val();
    _F._auth_user_data.sex = $(".sex").val();
    _F._auth_user_data.email = (_F._auth_user_data.dni + _HTTPREQUEST_deprecated._sufixEmail);
    _F.onActionLogin($(this));
});
$("body").off("click", ".btn-action-logout").on("click", ".btn-action-logout", function () {
    _F.onActionLogout($(this));
});
$("body").off("click", ".btn-action-exit").on("click", ".btn-action-exit", function () {
    _F.onActionExit($(this));
});
$("body").off("click", ".btn-action-forget").on("click", ".btn-action-forget", function () {
    _F.onActionForget($(this));
});

$("body").off("click", ".toggle-secret").on("click", ".toggle-secret", function () {
    _F.onToggleSecret($(this), ".password");
});
$("body").off("click", ".toggle-sidebar").on("click", ".toggle-sidebar", function () {
    _F.onToggleSidebar($(this));
});
$("body").off("click", ".close-sidebar").on("click", ".close-sidebar", function () {
    _F.onCloseSidebar($(this));
});
$("body").off("click", ".btn-back-sidebar").on("click", ".btn-back-sidebar", function () {
    _F.onToggleSidebar($(this));
    $(".page-sections").html("");
});
$("body").off("click", ".btn-action-accept").on("click", ".btn-action-accept", function () {
    _F.onAcceptCheckBox($(this));
});
$("body").off("click", ".model-by-id").on("click", ".model-by-id", function () {
    _F.onModalByIdPost($(this));
});
$("body").off("click", ".btn-close-modal").on("click", ".btn-close-modal", function () {
    _F.onDestroyModal("#modal-html");
});
$("body").off("click", ".btn-i-am").on("click", ".btn-i-am", function () {
    _F.onSelectMyName($(this));
});
$("body").off("click", ".btn-take-picture").on("click", ".btn-take-picture", function () {
    $(".fileLibrary").click();
});
$("body").off("change", ".fileLibrary").on("change", ".fileLibrary", function () {
    _PHOTO.onGetPicture($(this));
});

$("body").off("input", ".onlyNumbers").on("input", ".onlyNumbers", function () {
    _T.onlyNumbers($(this));
});
$("body").off("click", ".btn-generate-paycode").on("click", ".btn-generate-paycode", function () {
    _PAY.onGeneratePermisoTelemedicina($(this));
});
$("body").off("click", ".btn-see-message").on("click", ".btn-see-message", function () {
    _F.onViewDirectTelemedicina($(this));
});
$("body").off("click", ".btn-see-message-pdf").on("click", ".btn-see-message-pdf", function () {
    _F.onViewDirectTelemedicinaPDF($(this));
});
$("body").off("click", ".btn-comprobantes").on("click", ".btn-comprobantes", function () {
    _F.onBtnComprobantes($(this));
});
$("body").off("click", ".btn-see-comprobante").on("click", ".btn-see-comprobante", function () {
    _F.onSeeComprobante($(this));
});
$("body").off("click", ".btnGetPDF").on("click", ".btnGetPDF", function () {
    _F.onGetPDF($(this));
});
$("body").off("click", ".btn-hd").on("click", ".btn-hd", function () {
    if ($(".canvaImg").is(":visible")) {
        $(".canvaImg").hide();
    } else {
        $(".canvaImg").show();
    }
});

$("body").off("click", ".btnVerCupon").on("click", ".btnVerCupon", function () {
    _F.onVerCupon($(this),true);
});
$("body").off("click", ".btnVerCuponNoCanjea").on("click", ".btnVerCuponNoCanjea", function () {
    //_F.onVerCupon($(this),false);
});
$("body").off("click", ".closeModal").on("click", ".closeModal", function () {
    _F.onDestroyModal($(this).attr("data-modal"));
});
$("body").off("click", ".see-map").on("click", ".see-map", function () {
    _F.onSeeMap($(this));
});
$("body").off("click", ".see-listado").on("click", ".see-listado", function () {
    _F.onSeeListado($(this));
});

$("body").off("click", ".btn-search-map").on("click", ".btn-search-map", function () {
    _F.onSearchMap($(this));
});
$("body").off("click", ".btn-near-map").on("click", ".btn-near-map", function () {
    _F.onNearMap($(this));
});
$("body").off("click", ".item-mapa").on("click", ".item-mapa", function (e) {
    _F.onSelectItemMapa($(this));
});
$("body").off("keypress", ".searcher").on("keypress", ".searcher", function (e) {
    var _this = $(this);
    clearTimeout(_F._TIMER_LAZY);
    _F._TIMER_LAZY = setTimeout(function () {
        _F.onChangePage(_this);
        setTimeout(function () { _F.onSearchFromBar(_this); }, 200);
    }, 500);
});
$("body").off("click", ".noinstall").on("click", ".noinstall", function () {
    _F.onNoInstallApp();
});
$("body").off("click", ".install").on("click", ".install", function () {
    installApp();
});
$("body").off("click", ".fixinstall").on("click", ".fixinstall", function () {
    _DB.Set("pwa_install", { "ask": false });
    _F.onDestroyModal("#alterModal");
});
$("body").off("click", ".fixnoinstall").on("click", ".fixnoinstall", function () {
    _DB.Set("pwa_install", { "ask": true });
    _F.onDestroyModal("#alterModal");
});
$("body").off("click", ".toggle-cobertura").on("click", ".toggle-cobertura", function () {
    _F.onToggleCobertura($(this));
});
function initAll() {
    $.getScript((_F._cdn_server + "maps/markerwithlabel.js"), function () {
        $.getScript((_F._cdn_server + "maps/GMAP.js"), function () {
            $.getScript("https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js", function () {
                window.jsPDF = window.jspdf.jsPDF;
            });
        });
    });
}
