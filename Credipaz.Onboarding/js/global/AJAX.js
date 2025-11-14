var _AJAX = {
    _BPAM: {},
    _KEY: 0,
    _id_type_medio_cobro: 1,
    _formularioFirma: "",
    _formularioPrefijoCarpetaDigital: "",
    _idFoto: 0,
    _descriptionFoto: "",
    _customFoto: "",
    _scopeFoto: "",
    _nodeFoto:"",
    _responseTitle: "",
    _responseMessage: "",
    _justSign:false,
    _test:"N",
    _tipo: 1,
    _modo: "NORMAL",
    _monopage: false,
    _decision: "INITIAL",
    _externalid: "",
    _idtx: 0,
    _blockUI: true,
    _serverBlackBox: "",
    _ready: false,
    _id_user: null,
    _username: "",
    _id_application: 0,
    _init_page: "",
    formatFixedParameters: function (_json) {
        _json["id_user"] = _AJAX._id_user;
        _json["id_application"] = _AJAX._id_application;
        _json["token"] = "";
        _json["test"] = _AJAX._test;
        if (_json["server"] == undefined) { _json["server"] = _AJAX._serverBlackBox; }
        if (_json["exit"] == undefined) { _json["exit"] = "output"; } //download
        if (_json["mime"] == undefined) { _json["mime"] = "application/json"; } // "text/xml" or other (must be supported)
        if (_json["function"] == undefined) { _json["function"] = ""; }
        if (_json["model"] == undefined) { _json["model"] = ""; }
        if (_json["method"] == undefined) { _json["method"] = "api.pwa/neocommand"; }
        return _json;
    },
    initialize: function (_user_model) {
        if (_AJAX._remote_mode) { if (!_AJAX._compiled && _AJAX._here.indexOf("localhost")) { _AJAX.server = _AJAX._here; } }
        if (_AJAX._user_active == null) { _AJAX._user_active = _user_model; }
        _AJAX._ready = true;
    },
    ExecuteDirect: function (_json, _method) {
        return new Promise(
            function (resolve, reject) {
                try {
                    if (_method != null) { _json["method"] = _method; }
                    _AJAX.Execute(_json).then(function (datajson) {
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
                    if (!_AJAX._ready) { _AJAX.initialize(null); }
                    var _params = _AJAX.formatFixedParameters(_json);
                    var _data = JSON.stringify(_params);
                    jQuery.support.cors = true;
                    var ajaxRq = $.ajax({
                        type: "POST",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        url: (_json.server + _json.method),
                        data: _json,
                        beforeSend: function () {
                            if (_AJAX._blockUI) {
                                $.blockUI({ message: "<img src='./img/wait.gif' style='width:100px;' />", css: { width: '100px', border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
                            }
                        },
                        complete: function () {
                            $.unblockUI();
                            _AJAX._blockUI = true;
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
    readConfigServers: function (_key) {
        return new Promise(
            function (resolve, reject) {
                fetch("./Recursos/configServers.json")
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
};
