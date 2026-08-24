var _API = {
    _TS: 0,
    _ROOT: "",
    _TIMER_ALERT: 0,
    _TIMER_LAZY: 0,
    tools: null,
    id_user_log: null,
    id_app_external: 0,
    id_sucursal: 100,
    sucursal: "CASA CENTRAL",
    loginRequired: false,
    postLogin: false,
    externalUserMode: 0,
    doctorRequired:false,
    imageLogin: "./img/loginDefault.png",
    subsystem: "",
    configuration: null,
    authentication: null,
    telemedicina: { "isDoctor": 0, "doctorName": "", "doctorFirma": "", "doctorMatricula": "" },
    branchConfiguration: null,
    urlParameters: null,
    inited: false,
    verbose: false,

    /* Funciones core de configuración, carga de archivos y llamadas a servicios externos */
    call: function (endpoint, data) {
        /* NO AUTENTICA
        Función directa para llamadas genéricas, sin autenticación previa 
        Parámetros:
        endpoint: punto de acceso a la API
        data: objeto json con los parámetros a enviar en la llamada, deben incluirse id_user, token y id_app
        */
        return new Promise(
            function (resolve, reject) {
                var _url = (_API.configuration.server + endpoint);
                _API.log("call->data->" + _url, data);
                $.ajax({
                    "type": "POST",
                    "dataType": "json",
                    "url": _url,
                    "data": data,
                    "success": function (response) {
                        _API.log("call->response", response);
                        resolve(response);
                    },
                    "error": function (xhr, status, error) { reject(error); }
                });
            });
    },
    authenticate: function () {
        /*
        Función directa para llamadas de autenticación del desarrollador 
        */
        return new Promise(
            function (resolve, reject) {
                /* Se auto asignan los parámetros basados en los datos de configServers.js */
                var data = {
                    "id_app": _API.configuration.id_app,
                    "username": _API.configuration.username,
                    "password": _API.configuration.password,
                    "version": _API.configuration.version
                };
                /* Llamada a la autenticación */
                _API.call("production/authenticate", data)
                    .then(function (auth) {
                        /* Asignación de valores de autenticación */
                        _API.authentication = auth;
                        resolve(auth);
                    })
                    .catch(function (err) {
                        _API.auth = null;
                        _API.log("authenticate error", err);
                        _API.onShowUnauthorized("Servicio de autenticación no disponible.");
                        reject(err);
                    });
            });
    },
    method: function (endpoint, data) {
        /* AUTOAUTENTICA
        Función genérica para hacer cualquier llamada a la API, 
        incluyendo la autenticación previa con los datos del desarrollador tomados de configServers.js
        Parámetros:
        endpoint: punto de acceso a la API
        data: objeto json con los parámetros a enviar en la llamada, NO deben incluirse id_user, token y id_app
        */
        return new Promise(
            function (resolve, reject) {
                /* Llamada de auto autenticación */
                _API.authenticate()
                    .then(function (auth) {
                        /* Agregado de valores de la autenticación correcta al objeto data */
                        data["id_user_active"] = _API.authentication.data.id;
                        data["token_authentication"] = _API.authentication.data.token_authentication;
                        data["id_app"] = _API.configuration.id_app;
                        /* Llamada directa al método de la API con los valores completos */
                        _API.call(endpoint, data)
                            .then(function (response) {
                                resolve(response);
                            })
                            .catch(function (err) {
                                _API.log("method error->" + endpoint, err);
                                reject(err);
                            });
                    }).catch(function (err) {
                        _API.log("method authenticate error->" + endpoint, err);
                    });
            });
    },
    loadExternalLibraries: function () {
        /*Carga de librerias de terceros y configuracion de las mismas */
        $.getScript("https://jvideo1.gruponeodata.com/external_api.js?" + _API._TS).done(function (script, textStatus) {
            $.getScript(("js/Neodata/NEOAUTHENTICATION.js?" + _API._TS)).done(function (script, textStatus) {
                $.getScript("js/Neodata/NEOVIDEO-jvideo1.js?" + _API._TS).done(function (script, textStatus) {
                    _NEOAUTHENTICATION._SERVER = _API.configuration.authenticationServer;
                    _NEOVIDEO._SERVER = _API.configuration.videoServer;
                    _NEOVIDEO._id_application = _API.configuration.id_neo_app;
                });
            });
        });
    },

    /* API user spaceballs*/
    UiGet: function (_params) {
        return new Promise(
            function (resolve, reject) {
                _API.method("mediya/algo",_params).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    }, //2 

};
