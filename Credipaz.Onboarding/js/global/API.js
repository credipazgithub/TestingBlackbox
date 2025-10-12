/**
 * /
 * Requerided functions for all applications!
 * Must be customized for each implementation
 */
var _API = {
    UiGetFormulario: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.pwa/GetFormulario"; //method
                _AJAX.ExecuteDirect(_json, null).then(function (data) {
                    resolve(data);
                }).catch(function (err) {
                    reject(err);
                });
            });
    },
    UiFirmarFormulario: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.pwa/FirmarFormulario"; //method
                _AJAX.ExecuteDirect(_json, null).then(function (data) {
                    resolve(data);
                }).catch(function (err) {
                    reject(err);
                });
            });
    },
    UiOnboardingFinalIdVerification: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX._serverBlackBox;
                _json.method = "api.pwa/onboardingFinalIdVerification";
                _AJAX._blockUI = false;
                _json["modo"] = _AJAX._modo;
                _AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiOnboardingSaveRequest: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX._serverBlackBox;
                _json.method = "api.pwa/onboardingSaveRequestCore";
                _AJAX._blockUI = false;
                _json["modo"] = _AJAX._modo;
                _AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiOnboardingGetRequest: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX._serverBlackBox;
                _json.method = "api.pwa/onboardingGetRequestCore";
                _AJAX._blockUI = true;
                _AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiOnboardingFinalRequest: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX._serverBlackBox;
                _json.method = "api.pwa/onboardingFinalRequestCore";
                _AJAX._blockUI = true;
                _AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiGetLookup2: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json.server = _AJAX._serverBlackBox;
                _json.method = "api.pwa/lookup2";
                _AJAX._blockUI = false;
                _AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiLogGeneral: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.pwa/logGeneral"; //method
                _AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
};
