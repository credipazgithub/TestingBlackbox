var _NMF = {
    _app:0,
    _KEY_CAPTCHA: "6Lfzw_siAAAAAF-L7K2rSPBogXe0A2Ygtu0D9qOD",
    _server: "",
    readConfigServers: function (_key) {
        return new Promise(
            function (resolve, reject) {
                fetch("./Recursos/configServers.json?" + _TOOLS.UUID()) 
                    .then(response => {
                        if (!response.ok) { throw new Error(`HTTP error! status: ${response.status}`); }
                        return response.text();
                    })
                    .then(_ret => {
                        var data = JSON.parse(_ret);
                        var _item = data.find(item => item.key === _key);
                        resolve(_item);
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        reject(error);
                    });
            });
    },
    onBack: function (_this) {
        return new Promise(
            function (resolve, reject) {
                try {
                    _NMF._app = 0;
                    $(".volver").addClass("d-none");
                    $(".main").removeClass("d-none");
                    $(".imgSelected").attr("src", "img/wait.gif");
                    $(".cboarea").addClass("d-none");
                    $(".areavalor").addClass("d-none");
                    $(".valor").val("").attr("placeholder", "");
                    $(".btn-exec").addClass("d-none");
                    $(".cbofarmacia").removeClass("dbase");
                    $(".cboambulancia").removeClass("dbase");
                    $(".cbomedico").removeClass("dbase");
                    resolve(true);
                } catch (rex) {
                    reject(rex);
                }
            });
    },
    onAccion: function (_this) {
        return new Promise(
            function (resolve, reject) {
                try {
                    var _data = _this.attr("data-id");
                    _NMF._app = parseInt(_this.attr("data-app"));
                    $(".main").addClass("d-none");
                    $(".volver").removeClass("d-none");
                    $(".imgSelected").attr("src", ("img/" + _data + ".png"));
                    $(".area" + _data).removeClass("d-none");
                    $(".cbo" + _data).addClass("dbase");
                    _NMF.onDrawCaptcha();
                    resolve(true);
                } catch (rex) {
                    reject(rex);
                }
            });
    },
    onDrawCaptcha: function () {
        $.getScript("https://www.google.com/recaptcha/api.js?onload=onLoadCallback&render=explicit", function () { });
    },
    onChangeDocumento: function (_this) {
        return new Promise(
            function (resolve, reject) {
                try {
                    var _data = _this.val();
                    switch (_data) {
                        case "":
                            $(".areavalor").addClass("d-none");
                            $(".valor").attr("placeholder", "");
                            break;
                        case "D":
                            $(".areavalor").removeClass("d-none");
                            $(".valor").attr("placeholder", "Ingrese en documento...");
                            break;
                        case "T":
                            $(".areavalor").removeClass("d-none");
                            $(".valor").attr("placeholder", "Ingrese la tarjeta...");
                            break;
                        case "C":
                            $(".areavalor").removeClass("d-none");
                            $(".valor").attr("placeholder", "Ingrese la credencial...");
                            break;
                        case "M":
                            $(".areavalor").removeClass("d-none");
                            $(".valor").attr("placeholder", "Ingrese la tarjeta...");
                            break;
                    }
                    resolve(true);
                } catch (rex) {
                    reject(rex);
                }
            });
    },
    onSendToServer: function (_this) {
        if (!_TOOLS.validate(".validate")) {
            alert("¡Por favor complete los datos solicitados!");
            return false;
        }
        var _mode = "BridgeAutorizarSocioDS";
        switch ($(".cbodocumento").val()) {
            case "M":
                _mode = "BridgeAutorizarSocioMediya";
                break;
            default:
                break;
        }
        var PAN = " ";
        var PANClubRedondo = " ";
        var NroDocumento = parseInt($(".valor").val());
        var IdPrestador = 0;
        switch (_NMF._app) {
            case 1:
                IdPrestador = parseInt($(".cbofarmacia").val());
                break;
            case 2:
                IdPrestador = parseInt($(".cboambulancia").val());
                break;
            case 3:
                IdPrestador = parseInt($(".cbomedico").val());
                break;
        }
        var _endpointURI = (_NMF._server + "api.pwa/" + _mode);
        $(".btn-exec").fadeOut("fast");
        var formData = new FormData();
        formData.append('IdPrestador', IdPrestador);
        formData.append('NroDocumento', NroDocumento);
        formData.append('PAN', PAN);
        formData.append('PANClubRedondo', PANClubRedondo);
        var request = $.ajax({
            url: _endpointURI,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false
        });
        request.done(function (msg) {
            var _html = "<table class='table table-condensed'>";
            console.log(msg.data);
            if (msg.data[0]==null || msg.data[0].Nombre == "Desconocido") {
                _html += "<tr><td colspan='2' style='color:red;'><b>RECHAZADO</b><br/>No hay usuario habilitado para el servicio con esos datos</td></tr>";
                $(".result").css({ "border": "double 3px red"});
            } else {
                _html += "<tr><td>Nombre:</td><td><b>" + msg.data[0].Nombre + "</b></td></tr>";
                $(".result").css({ "border": "double 3px green"});
            }
            _html += "<tr><td>ID:</td><td><b>" + msg.data[0].IDAutorizacion + "</b></td></tr>";
            _html += "<tr><td>Nº:</td><td><b>" + msg.data[0].NroAutorizacion + "</b></td></tr>";
            _html += "</table>";
            $(".result").html(_html);
            $(".btn-exec").fadeIn();
        });
        request.fail(function (jqXHR, textStatus) {
            alert("Se ha producido un error: " + textStatus);
            $(".btn-exec").fadeIn();
        });
    },
    onGet: function (_method) {
        var URL = (_NMF._server + "api.pwa/SIA" + _method);
        $.get(URL, function (data, textStatus, jqXHR) {
            $(".cbo" + _method).html("");
            $(".cbo" + _method).append("<option selected value=''>SELECCIONAR...</option>");
            $.each(data.data, function (i, obj) {
                $(".cbo" + _method).append("<option value='" + obj.IdPrestador + "'>" + obj.Descripcion + "</option>");
            });
        });
    },
};
function onLoadCallback() {
    grecaptcha.render('widget', { 'sitekey': _NMF._KEY_CAPTCHA, 'callback': onVerifyCallback });
};
function onVerifyCallback(response) {
    $(".btn-exec").removeClass("d-none");
};
