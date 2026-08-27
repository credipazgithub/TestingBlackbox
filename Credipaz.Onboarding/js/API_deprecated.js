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
    //Credipaz/funciones/obtenerformulario | {Formulario,ValueForRetrieve,lat,lng,latitude,longitude}

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
    //Credipaz/funciones/firmarformulario | {documento,segmento_carpeta_digital,Formulario,pageToAlter,x,y,img_additional}

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
    //Credipaz/funciones/finalverificacion | {id}

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
    //Credipaz/funciones/grabarrequest | {id,control_point,img_foto_camara,img_comprobante_servicio,img_comprobante_ingreso,img_dni_frente,img_dni_dorso,raw_verify} [REVISAR DOCUMENTACION]

    UiOnboardingGetRequest: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX_deprecated._serverBlackBox;
                _json.method = "api.pwa/onboardingGetRequestCore";
                _AJAX_deprecated._blockUI = true;
                _AJAX_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    //Credipaz/funciones/obtenerrequest | {id,idtx,decision,externalid,end} [REVISAR DOCUMENTACION]

    UiOnboardingFinalRequest: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX_deprecated._serverBlackBox;
                _json.method = "api.pwa/onboardingFinalRequestCore";
                _AJAX_deprecated._blockUI = true;
                _AJAX_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    //Credipaz/funciones/finalrequest | {id,lat,lng,pdf_solicitud,img_additional} [REVISAR DOCUMENTACION]

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
    //Credipaz/funciones/tokenizartarjeta | {wId,wIdEmpresaOrigen,IdSocio,IdTransaccion,wId_type_medio_cobro,wPreferido,wYY,wMM,wCVV,wDocumento,wNumero,wNombre}
};
