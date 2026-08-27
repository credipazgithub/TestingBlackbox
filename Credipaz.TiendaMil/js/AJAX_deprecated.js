var _AJAX_deprecated = {
    _isPWA:false,
    _switch_site:"",
    _SERVER: "",
    _blockUI: true,
    _ready: false,
    _id_application: null,
    _application: "",
    _id_user: 0,
    _username: "",
    _password: "",
    _referrer: "",
    _profile: "",
    _name: "",
    _API_KEY:"",
    _token: "",
    _token_type: "",
    _init_page: "",
    formatFixedParameters: function (_json) {
        _json["id_user"] = _AJAX_deprecated._id_user;
        _json["id_user_active"] = 0;
        _json["id_application"] = _AJAX_deprecated._id_application;
        _json["token"] = _AJAX_deprecated._token;
        if (_json["server"] == undefined) { _json["server"] = _AJAX_deprecated._SERVER; }
        if (_json["exit"] == undefined) { _json["exit"] = "output"; } //download
        if (_json["mime"] == undefined) { _json["mime"] = "application/json"; } // "text/xml" or other (must be supported)
        if (_json["function"] == undefined) { _json["function"] = ""; }
        if (_json["model"] == undefined) { _json["model"] = ""; }
        if (_json["method"] == undefined) { _json["method"] = "api/getNeoCommand"; }
        return _json;
    },
    initialize: function () {
        _AJAX_deprecated._ready = true;
    },
    ExecuteDirect: function (_json, _method) {
        return new Promise(
            function (resolve, reject) {
                try {
                    if (_method != null) { _json["method"] = _method; }
                    _AJAX_deprecated.Execute(_json).then(function (datajson) {
                        if (datajson.status != undefined) {
                            if (datajson.status == "OK" || datajson.status == "OK") {
                                resolve(datajson);
                            } else {
                                if (parseInt(datajson.code) == -1) {
                                    $(".splash").remove();
                                    $(".login").remove();
                                    $(".main").remove();
                                    $(".deprecated").removeClass("d-none").fadeIn("fast");
                                }
                                reject(datajson);
                            }
                        } else {
                            resolve(datajson);
                        }
                    });
                } catch (rex) {
                    reject(rex);
                }
            });
    },
    Execute: function (_json) {
        return new Promise(
            function (resolve, reject) {
                try {
                    var _params = _AJAX_deprecated.formatFixedParameters(_json);
                    var _data = JSON.stringify(_params);
                    var ajaxRq = $.ajax({
                        type: "POST",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        url: (_json.server + _json.method),
                        data: _json,
                        beforeSend: function () {
                            if (_AJAX_deprecated._blockUI) { $.blockUI({ message: "<img src='./img/wait.gif' style='width:100%;'/>", css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } }); }
                        },
                        complete: function () {
                            $.unblockUI();
                            _AJAX_deprecated._blockUI = true;
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            reject(thrownError);
                        },
                        success: function (datajson) {
                            if (datajson == null) {
                                resolve(datajson);
                            } else {
                                resolve(datajson);
                            }
                        }
                    });
                } catch (rex) {
                    reject(rex);
                }
            }
        )
    },
    Load: function (_file) {
        return new Promise(
            function (resolve, reject) {
                var ajaxRq = $.ajax({
                    type: "GET",
                    timeout: 10000,
                    dataType: "html",
                    async: false,
                    cache: false,
                    url: _file,
                    success: function (data) { resolve(data); },
                    error: function (xhr, msg) { reject(msg); }
                });
            });
    },
};
