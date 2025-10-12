/**
 * /
 * Requerided functions for all applications!
 * Must be customized for each implementation
 */
var _API = {
    UiGet: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "get";
                _HTTPREQUEST.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiGetWebPosts: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_web_posts";
                _json["table"] = "web_posts";
                _json["model"] = "web_posts";
                _json["function"] = "get";
                _json["where"] = ("id=" + _json["id"]);
                _HTTPREQUEST.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiApplicationMobileFunction: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _HTTPREQUEST.ExecuteDirect(_json, "api.pwa/getApplicationMobileFunction").then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiAuthenticateMobile: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_backend";
                _json["table"] = "users";
                _json["model"] = "users";
                _json["function"] = "authenticateMobile";
                _HTTPREQUEST.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiTestUserValuePWA: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_backend";
                _json["table"] = "users";
                _json["model"] = "users";
                _json["function"] = "testUserValuePWA";
                _HTTPREQUEST.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiGetUserInformation: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["module"] = "mod_backend";
                _json["table"] = "external";
                _json["model"] = "external";
                _json["function"] = "getUserInformation";
                _HTTPREQUEST.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiDelete: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "delete";
                _HTTPREQUEST.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
};
