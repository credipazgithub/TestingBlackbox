var _fnc = new Object();
var oIntro = new Object();

oIntro.onShow = function () {
    /*
    setTimeout(function () {
        _API.UiCatalogoMIL({}).then(function (_datajson) {
            $(".loading-catalog").addClass("d-none");
            $(".loaded-catalog").removeClass("d-none");
            _NMF._activeCatalog = _datajson;
        });
    }, 100);
    */
    setTimeout(function () {
        $(".topMenu").addClass("d-none");
        $(".img-intro").css("opacity", 0).show();
        $(".app-intro").fadeIn("fast", function () {
            var _t = (($(window).height() - $(".img-intro").height()) / 2) - 175;
            var _l = ($(window).width() - $(".img-intro").width()) / 2;
            $(".img-intro").css("left", _l + "px")
            _l = ($(window).width() - $(".img-mas").width()) / 2;
            _l = ($(window).width() - $(".img-waiter").width()) / 2;
            $(".img-intro").velocity({ "top": _t + "px", "opacity": 1 }, { duration: 300, easing: "easeOutQuad", mobileHA: true });
            setTimeout(function () {
                $(".app-intro").fadeOut(1000, function () { });
                _NMF.onTryPage(null, _AJAX._init_page);
            }, 2000);
        });
    }, 100)
};

_fnc.getReference = function () {
    return oIntro;
};
