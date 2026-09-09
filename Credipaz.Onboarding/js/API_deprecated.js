/**
 * /
 * Requerided functions for all applications!
 * Must be customized for each implementation
 */
var _API_deprecated = {
    UiGetFormulario: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.pwa/GetFormulario"; //method
                _AJAX_deprecated.ExecuteDirect(_json, null).then(function (data) {
                    resolve(data);
                }).catch(function (err) {
                    reject(err);
                });
            });
    },
    //Credipaz/funciones/obtenerformulario

    UiFirmarFormulario: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.pwa/FirmarFormulario"; //method
                _AJAX_deprecated.ExecuteDirect(_json, null).then(function (data) {
                    resolve(data);
                }).catch(function (err) {
                    reject(err);
                });
            });
    },
    //Credipaz/funciones/firmarformulario

    UiOnboardingFinalIdVerification: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX_deprecated._serverBlackBox;
                _json.method = "api.pwa/onboardingFinalIdVerification";
                _AJAX_deprecated._blockUI = false;
                _json["modo"] = _AJAX_deprecated._modo;
                _AJAX_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    //Credipaz/funciones/finalverificacion

    UiOnboardingSaveRequest: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX_deprecated._serverBlackBox;
                _json.method = "api.pwa/onboardingSaveRequestCore";
                _AJAX_deprecated._blockUI = false;
                _json["modo"] = _AJAX_deprecated._modo;
                _AJAX_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    //Credipaz/funciones/grabarrequest
    //VERIFICAR CON DETALLE DONDE SE USA EL "tokenId" que devuelve la llamda en algunos casos!!!!!
    //Esto tiene que ver con el circuito externo de control de Idemia y el Renaper

    UiOnboardingGetRequest: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX_deprecated._serverBlackBox;
                _json.method = "api.pwa/onboardingGetRequestCore";
                _AJAX_deprecated._blockUI = true;
                _AJAX_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    //Credipaz/funciones/obtenerrequest

    UiOnboardingFinalRequest: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX_deprecated._serverBlackBox;
                _json.method = "api.pwa/onboardingFinalRequestCore";
                _AJAX_deprecated._blockUI = true;
                _AJAX_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    //Credipaz/funciones/finalrequest

    UiOnboardingTokenizar: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.pwa/Tokenizar"; //method
                _AJAX_deprecated.ExecuteDirect(_json, null).then(function (data) {
                    resolve(data);
                }).catch(function (err) {
                    reject(err);
                });
            });
    },
    //Credipaz/funciones/tokenizartarjeta
    // esta llamada: var _params = _T.getFormValues(".dbase");
    // en la funcion que implemta el endpoint, ya arma correctamente los valores de parametro que
    // requiere el nuevo endpoint, que  los toma de los objetos de clase dbase del html get-tokenizar.html
    // ver de esos valores que quedan en _params, donde esta todo lo que se necesita
};
