var _fnc = new Object();
var oAppMsgError = new Object();

oAppMsgError.onShow = function () {
    setTimeout(function () {
        $(".topMenu").addClass("d-none");
        $(".responseMessage").html(_AJAX._responseMessage);
    }, 250);
};

_fnc.getReference = function () {
    return oAppMsgError;
};
