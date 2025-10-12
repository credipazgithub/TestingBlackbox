var _API = {
    /*
     * Calls
     * */
	UiGetUserAreas: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/getUserAreas"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiCatalogoMIL: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/catalogoMIL"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
    UiInitTransactionFiserv: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.backend/transactionPayment"; //method
                _AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiCheckStatusPayment: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.backend/checkStatusPayment"; //method
                _AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    UiSendPushToGroup: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.backend/sendPushToGroup"; //method
                _AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
};
