var _WEBSOCKET = {
	socket: null,
	_buildAlert: false,
	_targetRoot: "body",
	_targetAlert: "broadcastAlert",
	_targetReturn: "log",
	_targetClose: "closeAlert",
	_host: "ws://localhost:9000",
	_userIntranet: "neodata",
	_styleAlert: "width:100%;text-align:center;padding:5px;position:absolute;left:0px;top:0px;border:double 3px red;background-color:ivory;z-index:99999999;",
	_styleCloseAlert:"cursor:pointer;position:absolute;right:10px;font-weight:bold;font-size:1.5rem;",
	destroy: function (_this) {
		if (_WEBSOCKET.socket != null) {
			_WEBSOCKET.socket.close();
			_WEBSOCKET.socket = null;
		}
	},
	error: function (err) {
		console.log("ERROR");
		console.log(err);
	},
	connect: function (_this) {
		try {
			_WEBSOCKET.socket = new WebSocket(_WEBSOCKET._host);
			_WEBSOCKET.socket.onopen = function (_ret) {
				_WEBSOCKET.response(_ret.data);
				_WEBSOCKET.authenticate(_this);
			};
			_WEBSOCKET.socket.onclose = function () {
				_WEBSOCKET.response(null);
			};
			_WEBSOCKET.socket.onmessage = function (_ret) {
				var _json = _WEBSOCKET.response(_ret.data);
				if (_WEBSOCKET._buildAlert && _json != null && _json.Estado == "BROADCAST") {
					var _html = "<div class='" + _WEBSOCKET._targetAlert + "' style='" + _WEBSOCKET._styleAlert + "'>";
					_html += "      <span style='" + _WEBSOCKET._styleCloseAlert + "' class='" + _WEBSOCKET._targetClose + "'>X</span>";
					_html += "      <h2>Aviso general</h2>"
					_html += "      <h4>El siguiente mensaje es para información de todos los usuarios de la red</h4>"
					_html += "      <h3>" + _json.Mensaje + "</h3>";
					_html += "   </div>";
					_WEBSOCKET.closeAlert(false);
					$(_WEBSOCKET._targetRoot).prepend(_html);
				}
			};
		}
		catch (ex) { _WEBSOCKET.error(ex); }
	},
	disconnect: function (_this) {
		try {
			_WEBSOCKET.destroy(_this);
		} catch (ex) { _WEBSOCKET.error(ex); }
	},
	reconnect: function (_this) {
		try {
			_WEBSOCKET.disconnect(_this);
			_WEBSOCKET.connect(_this);
		} catch (ex) { _WEBSOCKET.error(ex); }
	},
	authenticate: function (_this) {
		try {
			_WEBSOCKET.socket.send("authenticate|" + _WEBSOCKET._userIntranet);
		} catch (ex) { _WEBSOCKET.error(ex); }
	},
	send: function (_this) {
		try {
			var _source = _this.attr("data-source");
			var msg = $(_source).val();
			if (msg != "") {
				$(_source).val("");
				$(_source).focus();
				_WEBSOCKET.socket.send((msg + "|" + _WEBSOCKET._userIntranet));
			}
		} catch (ex) { _WEBSOCKET.error(ex); }
	},
	response: function (msg) {
		try {
			console.log(msg);
			var _json = null;
			if (msg == undefined || msg == null) { msg = '{"Estado":"OK","Comando":"closed","Mensaje":"Desconectado"}'; }
			_json = JSON.parse(msg);
			if (_json.Comando != _json.Mensaje) {
				if (_WEBSOCKET._targetReturn != undefined && _WEBSOCKET._targetReturn != "") {
					$(_WEBSOCKET._targetReturn).html(JSON.stringify(_json, undefined, 2));
				}
			} else {
				_json = null;
			}
			return _json;
		} catch (ex) { _WEBSOCKET.error(ex); }
	},
	closeAlert: function (_fade) {
		if (_fade) {
			$("." + _WEBSOCKET._targetAlert).fadeOut("slow", function () {
				$("." + _WEBSOCKET._targetAlert).remove();
			});
		} else {
			$("." + _WEBSOCKET._targetAlert).remove();
		}
	}
};

$("body").off("click", ".btnConnect").on("click", ".btnConnect", function () {
	_WEBSOCKET.connect($(this));
});
$("body").off("click", ".btnSend").on("click", ".btnSend", function () {
	_WEBSOCKET.send($(this));
});
$("body").off("click", ".btnDisconnect").on("click", ".btnDisconnect", function () {
	_WEBSOCKET.disconnect($(this));
});
$("body").off("click", ".btnReconnect").on("click", ".btnReconnect", function () {
	_WEBSOCKET.reconnect($(this));
});
$("body").off("click", ("." + _WEBSOCKET._targetClose)).on("click", ("." + _WEBSOCKET._targetClose), function () {
	_WEBSOCKET.closeAlert(true);
});
