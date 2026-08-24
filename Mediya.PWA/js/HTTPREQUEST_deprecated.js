var _HTTPREQUEST_deprecated = {
    server: "",
    _fail_conect: false,
    _waiter:false,
    _channels:null,
    _viewMode: "",
    _ready: false,
    _id_app: null,
    _id_root: null,
    _sufix: "",
    _sufix2: "",
    _id_user_active: 0,
    _id_club_redondo: 0,
    _id_type_user_active: 0,
    _username_active: "",
    _master_account : "",
    _image_active : "",
    _master_image_active : "",
    _email_active: "",
    _token_authentication: "",
    _token_authentication_created: "",
    _token_authentication_expire: "",
    _firebase_password: "123456",
    _firebase_user: null,
    _sufixEmail: "",
    formatFixedParameters: function (_json) {
        _json["id_app"] = _HTTPREQUEST_deprecated._id_app;
        _json["id_root"] = _HTTPREQUEST_deprecated._id_root;
        _json["id_user_active"] = _HTTPREQUEST_deprecated._id_user_active;
        _json["id_club_redondo"] = _HTTPREQUEST_deprecated._id_club_redondo;
        _json["username_active"] = _HTTPREQUEST_deprecated._username_active;
        _json["token_authentication"] = _HTTPREQUEST_deprecated._token_authentication;
        _json["sufix"] = _HTTPREQUEST_deprecated._sufix;
        _json["sufix2"] = _HTTPREQUEST_deprecated._sufix2;
        if (_json["exit"] == undefined) { _json["exit"] = "output"; } //download
        if (_json["mime"] == undefined) { _json["mime"] = "application/json"; } // "text/xml" or other (must be supported)
        if (_json["function"] == undefined) { _json["function"] = ""; }
        if (_json["model"] == undefined) { _json["model"] = ""; }
        if (_json["method"] == undefined) { _json["method"] = "api.backend/neocommandTransparent"; }
        return _json;
    },
    initialize: function (_user_model) {
        if (_HTTPREQUEST_deprecated._remote_mode) { if (!_HTTPREQUEST_deprecated._compiled && _HTTPREQUEST_deprecated._here.indexOf("localhost")) { _HTTPREQUEST_deprecated.server = _HTTPREQUEST_deprecated._here; } }
        if (_HTTPREQUEST_deprecated._user_active == null) { _HTTPREQUEST_deprecated._user_active = _user_model; }
        _HTTPREQUEST_deprecated._ready = true;
    },
    ExecuteDirect: function (_json, _method) {
        return new Promise(
            function (resolve, reject) {
                try {
                    var _alert = (_json["function"] == "statusTelemedicina");
                    if (_method != null) { _json["method"] = _method; }
                    _HTTPREQUEST_deprecated.Execute(_json).then(function (datajson) {
                        if (_alert) { _alert = _alert;}
                        if (datajson.status != undefined) {
                            if (datajson.status == "OK" || datajson.status == "OK") {
                                resolve(datajson);
                            } else {
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
                    var _alert = (_json["function"] == "statusTelemedicina");
                    if (!_HTTPREQUEST_deprecated._ready) { _HTTPREQUEST_deprecated.initialize(null); }
                    _all = _HTTPREQUEST_deprecated.formatFixedParameters(_json);
                    var _tmp = JSON.stringify(_all);
                    var ajaxRq = $.ajax({
                        type: "POST",
                        dataType: "json",
                        crossDomain: true,
                        url: (_HTTPREQUEST_deprecated.server + _json.method),
                        data: _json,
                        beforeSend: function () { _HTTPREQUEST_deprecated.onBeforeSendExecute(); },
                        complete: function () { _HTTPREQUEST_deprecated.onCompleteExecute(); },
                        error: function (xhr, ajaxOptions, thrownError) {
                            if (!_HTTPREQUEST_deprecated._fail_conect) {
                                _HTTPREQUEST_deprecated._fail_conect = true;
                                alert("No hay conexión a Internet activa: " + (_HTTPREQUEST_deprecated.server + _json.method));
                                window.location.href = "offline.html";
                                resolve(null);
                            } else {
                                reject(thrownError);
                            }
                        },
                        success: function (datajson) {
                            _HTTPREQUEST_deprecated._fail_conect = false;
                            if (datajson == null) {
                                datajson = { "results": null };
                                resolve(datajson);
                            } else {
                                if (datajson.compressed == null) { datajson.compressed = false; }
                                if (datajson.compressed == undefined) { datajson.compressed = false; }
                                if (datajson != null && datajson.compressed) {
                                    var zip = new JSZip();
                                    JSZip.loadAsync(window.atob(datajson.message)).then(function (zip) {
                                        zip.file("compressed.tmp").async("string").then(
                                            function success(content) {
                                                datajson.message = content;
                                                resolve(datajson);
                                            },
                                            function error(err) {
                                                reject(err);
                                            });
                                    });
                                } else {
                                    resolve(datajson);
                                }
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
    onBeforeSendExecute: function () {
        if (_HTTPREQUEST_deprecated._waiter) { }
    },
    onCompleteExecute: function () {
        _HTTPREQUEST_deprecated._waiter = false;
    },
};