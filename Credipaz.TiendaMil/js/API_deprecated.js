var _API_deprecated = {
    /*
     * Calls
     * */
	UiGetUserAreas: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = "api.backend/getUserAreas"; //method
				_AJAX_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	//Credipaz/funciones/obtenerUserAreas | {last_area}
};
