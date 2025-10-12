var _p = null;
var _m = null;
var _KEY_CAPTCHA = "6Lfzw_siAAAAAF-L7K2rSPBogXe0A2Ygtu0D9qOD";
var verifyCallback = function (response) {
    $(".btn-accept-landing").removeClass("d-none");
};
var _objSucursales = null;

function sendToServer(_this) {
    var _bFrame = true;
    var _endpointURI = (_AJAX._SERVER + "api.landing/tiendamil");
    if (!_TOOLS.validate(".validate")) {
        alert("¡Por favor complete los datos solicitados!");
        return false;
    }
    var jsonRequest = _TOOLS.getFormValues(".tows");
    jsonRequest["target"] = "tiendamil";
    jsonRequest["TipoTransaccion"] = 20;
    jsonRequest["Circuito"] = 0; //circuito corto, solo email a sucursal
    jsonRequest["Website"] = 1;  //fuerza canal digital si el circuito es 0
    //Si no viene el parametro p, 
    if (_p == undefined) { jsonRequest["Circuito"] = 0; }
    if (_m == undefined) {_bFrame = false;}
    var request = $.ajax({
        url: _endpointURI,
        method: "POST",
        data: jsonRequest,
        dataType: "json"
    });
    $(".btn-accept-landing").fadeOut();
    request.done(function (msg) {
        alert("¡Los datos han sido enviados correctamente!");
        $(".form-control").val("");
        $(".sex").val("-1");
        setTimeout(function () {
            $(".btn-accept-landing").fadeIn("slow");
        }, 500);
    });
    request.fail(function (jqXHR, textStatus) {
        alert("Se ha producido un error: " + textStatus);
        $(".btn-exec").fadeIn();
    });
    $("#send").attr("disabled", true);
}
function enabledSubmit(response) {
    document.getElementsByName("send")[0].disabled = false;
}
function onloadCallback() {
    grecaptcha.render('widget', { 'sitekey': _KEY_CAPTCHA, 'callback': verifyCallback });
};
function drawCaptcha() {
    $.getScript("https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit", function () { });
}
