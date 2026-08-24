/**
 * /
 * Requerided functions for all applications!
 * Must be customized for each implementation
 */
var _API_deprecated = {
    UiGet: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "get";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    }, //2 
    UiGetWebPosts: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_web_posts";
                _json["table"] = "web_posts";
                _json["model"] = "web_posts";
                _json["function"] = "get";
                _json["where"] = ("id=" + _json["id"]);
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    }, //+2
    UiApplicationMobileFunction: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, "api.pwa/getApplicationMobileFunction").then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },//borrar
    UiAuthenticateMobile: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_backend";
                _json["table"] = "users";
                _json["model"] = "users";
                _json["function"] = "authenticateMobile";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiTestUserValuePWA: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_backend";
                _json["table"] = "users";
                _json["model"] = "users";
                _json["function"] = "testUserValuePWA";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiGetUserInformation: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_backend";
                _json["table"] = "external";
                _json["model"] = "external";
                _json["function"] = "getUserInformation";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiSaveMessage: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "directTelemedicina";
                _json["module"] = "mod_telemedicina";
                _json["table"] = "messages";
                _json["model"] = "messages";
                _json["method"] = "api.backend/neocommandTransparent"; //method
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },

    UiDelete: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "delete";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },//+1
    UiStatusTelemedicina: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_telemedicina";
                _json["table"] = "charges_codes";
                _json["model"] = "charges_codes";
                _json["function"] = "statusTelemedicina";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) {
                    resolve(data);
                }).catch(function (err) {
                    reject(err);
                });
            });
    },
    UiGeneratePaycode: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "generatePaycode";
                _json["module"] = "mod_telemedicina";
                _json["table"] = "charges_codes";
                _json["model"] = "charges_codes";
                _json["method"] = "api.backend/neocommandTransparent"; //method
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiGetCupons: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_club_redondo";
                _json["table"] = "beneficios";
                _json["model"] = "beneficios";
                _json["function"] = "getCuponsRefactored";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiGetImage: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_club_redondo";
                _json["table"] = "beneficios";
                _json["model"] = "beneficios";
                _json["function"] = "getImage";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },

    UiViewMessagesTelemedicina: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "verifyMessage";
                _json["module"] = "mod_telemedicina";
                _json["table"] = "messages";
                _json["model"] = "messages";
                _json["method"] = "api.backend/neocommandTransparent"; //method
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiComprobantesTelemedicina: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_telemedicina";
                _json["table"] = "charges_codes";
                _json["model"] = "charges_codes";
                _json["function"] = "comprobantesTelemedicina";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiRecetasTelemedicina: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_telemedicina";
                _json["table"] = "messages";
                _json["model"] = "messages";
                _json["function"] = "recetasTelemedicina";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiTransformedImage: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_backend";
                _json["table"] = "external";
                _json["model"] = "external";
                _json["function"] = "getTransformedImage";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiNetCoreCPFinancialTransparent: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_external";
                _json["table"] = "NetCoreCPFinancial";
                _json["model"] = "NetCoreCPFinancial";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiRegistrarCobranza: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "registrarCobranza";
                _json["module"] = "mod_external";
                _json["table"] = "NetCoreCPFinancial";
                _json["model"] = "NetCoreCPFinancial";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiBuildFormFiserv: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "buildFormFiserv";
                _json["module"] = "mod_payments";
                _json["table"] = "payments_fiserv";
                _json["model"] = "payments_fiserv";
                _json["method"] = "api.backend/neocommandTransparent"; //method
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiInitTransactionFiserv: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "save";
                _json["module"] = "mod_payments";
                _json["table"] = "Transactions";
                _json["model"] = "Transactions";
                _json["method"] = "api.backend/neocommandTransparent"; //method
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiGetCredenciales: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.pwa/GetCredenciales"; //method
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
};
