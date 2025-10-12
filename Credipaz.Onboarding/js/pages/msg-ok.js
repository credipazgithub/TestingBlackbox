var _fnc = new Object();
var oAppMsgOk = new Object();

oAppMsgOk.onShow = function () {
    setTimeout(function () {
        $(".topMenu").addClass("d-none");
        $(".responseTitle").html(_AJAX._responseTitle);
        $(".responseMessage").html(_AJAX._responseMessage);
    }, 250);
};

_fnc.getReference = function () {
    return oAppMsgOk;
};
