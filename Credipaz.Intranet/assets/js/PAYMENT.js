var _COIN = {
    _log: true,
    _SERVER: "",
    _CREDENTIALS: null,
    _PIN: "",
    _AUTHENTICATION: null,
    _CREDITCARDS: null,
    _QUOTES: null,
    _TRANSACTION: null,
    _cboCards: "",
    _cboQuotes: "",
    _cardExpirationMonth: "",
    _cardExpirationYear: "",
    _cardFullName: "",
    _creditCardId: "",
    _cardNumber: "",
    _identificationNumber: "",
    _securityCode: "",
    _quoteId: "",
    _amount: "",
    _concept: "",
    _reference: "",
    initialize: function (_log, _server, _credentials, _pin, _selector_cards, _selector_quotes) {
        _COIN._log = _log;
        _COIN._SERVER = _server;
        _COIN._CREDENTIALS = _credentials;
        _COIN._PIN = _pin;
        _COIN._AUTHENTICATION = null;
        _COIN._CREDITCARDS = null;
        _COIN._QUOTES = null;
        _COIN._TRANSACTION = null;
        _COIN._cboCards = _selector_cards;
        _COIN._cboQuotes = _selector_quotes;
        _COIN._creditCardId = "";
        _COIN._quoteId = "";
        _COIN.setCreditCardData("", "", "", "", "", "");
        _COIN.setTransactionData("", "", "");
    },
    setCreditCardData: function (_cardNumber, _securityCode, _cardExpirationMonth, _cardExpirationYear, _cardFullName, _identificationNumber) {
        _COIN._cardExpirationMonth = _cardExpirationMonth;
        _COIN._cardExpirationYear = _cardExpirationYear;
        _COIN._cardFullName = _cardFullName;
        _COIN._cardNumber = _cardNumber;
        _COIN._identificationNumber = _identificationNumber;
        _COIN._securityCode = _securityCode;
    },
    setTransactionData: function (_amount, _concept, _reference) {
        _COIN._amount = _amount;
        _COIN._concept = _concept;
        _COIN._reference = _reference;
    },
    authenticate: function (_this) {
        return new Promise(
            function (resolve, reject) {
                var _url = (_COIN._SERVER + "/login/authenticate");
                var xhttp = new XMLHttpRequest();
                xhttp.open("POST", _url, true);
                xhttp.setRequestHeader("content-type", "application/json;charset=utf-8");
                xhttp.setRequestHeader("accept", "application/json");
				xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        _COIN._AUTHENTICATION = JSON.parse(xhttp.responseText);
						//if (_COIN._log) {console.log(_COIN._AUTHENTICATION);}
                    };
                };
                xhttp.onloadend = function () { resolve(); };
                xhttp.send(JSON.stringify(_COIN._CREDENTIALS));
            });
    },
    getCreditCards: function (_this) {
        var _url = (_COIN._SERVER + "/creditcard");
        var def = $.Deferred();
        $.ajax({
            url: _url,
            type: "GET",
            contentType: "application/json; charset=utf-8",
            accept: "application/json",
            headers: { "Authorization": ("Bearer " + _COIN._AUTHENTICATION.Token) },
            success: function (response) {
                _COIN._CREDITCARDS = response;
                //if (_COIN._log) { console.log(_COIN._CREDITCARDS); }
                $.each(_COIN._CREDITCARDS.CreditCards, function (i, item) {
                    if (i == 0) {
                        $(_COIN._cboCards).empty();
                        //$(_COIN._cboCards).append($("<option>", { value: -1, text: "[Tarjeta]" }));
                    } else {
                        $(_COIN._cboCards).append($("<option>", { value: item.Id, text: item.Name }));
                    }
                });
                def.resolve(_COIN._CREDITCARDS);
            },
            error: function (error) {
                def.reject(error);
            }
        });
        return def;
    },
    getQuotes: function () {
        var _url = (_COIN._SERVER + "/creditcard/quotes?amount=" + _COIN._amount + "&creditCardId=" + _COIN._creditCardId);
        var def = $.Deferred();
        _COIN._creditCardId = $(_COIN._cboCards).val();
        $.ajax({
            url: _url,
            type: "GET",
            contentType: "application/json; charset=utf-8",
            accept: "application/json",
            headers: { "Authorization": ("Bearer " + _COIN._AUTHENTICATION.Token) },
			success: function (response) {
                _COIN._QUOTES = response;
                //if (_COIN._log) { console.log(_COIN._QUOTES); }
                if (_COIN._QUOTES.Quotes != null) {
                    $.each(_COIN._QUOTES.Quotes, function (i, item) {
						if (i == 0) { $(_COIN._cboQuotes).empty(); }
                        var _text = "Cuota";
						if (parseInt(item.Quote.Value) > 1) { _text = "Cuotas"; }
						if (item.Quote.Value == 1) {
							_text = (item.Quote.Value + " " + _text + " - Total a pagar $" + item.Commission.TotalToPay.toFixed(2));
							$(_COIN._cboQuotes).append($("<option data-amount='" + item.Commission.TotalToPay.toFixed(2) + "' value='" + item.Quote.Id + "'>" + _text + "</option>"));
						}
                    });
                    $(_COIN._cboQuotes).change();
                } else {
                    _COIN.getQuotes();
                }
                def.resolve(_COIN._QUOTES);
            },
            error: function (error) {
                def.reject(error);
            }
        });
        return def;
    },
    setQuote: function () {
        _COIN._quoteId = $(_COIN._cboQuotes).val();
        //if (_COIN._log) { console.log("quoteId: " + _COIN._quoteId); }
    },
    getScriptPayment: function () {
        var _url = (_COIN._SERVER + "/paymentgateway/pay");
        var def = $.Deferred();
        $.ajax({
            url: _url,
            type: "POST",
            contentType: "application/json; charset=utf-8",
            headers: { "Authorization": ("Bearer " + _COIN._AUTHENTICATION.Token) },
            success: function (fncString) { def.resolve(fncString); },
            error: function (error) { def.reject(error); }
        });
        return def;
    },
	processCreditCardPayment: function (_fulldata) {
        return new Promise(
            function (resolve, reject) {
				try {
					//_fulldata["Resultado"] = "";
					//_fulldata["Respuesta"] = "";
					//_fulldata["Transaccion"] = "";
					//_AJAX.UiRegistrarCobranza(_fulldata);
					//resolve(_COIN._TRANSACTION);
					//return false;
					//if (!confirm("monto: " + _COIN._amount)) { return false; }
					_COIN.getScriptPayment().then(
                        function (processCreditCardString) {
                            var paymentInfo = {};
							paymentInfo.cardExpirationMonth = _COIN._cardExpirationMonth;
							paymentInfo.cardExpirationYear = _COIN._cardExpirationYear;
                            paymentInfo.cardFullName = _COIN._cardFullName;
                            paymentInfo.creditCardId = _COIN._creditCardId;
                            paymentInfo.cardNumber = _COIN._cardNumber;
                            paymentInfo.identificationNumber = _COIN._identificationNumber;
                            paymentInfo.securityCode = _COIN._securityCode;
                            paymentInfo.quoteId = _COIN._quoteId;
                            paymentInfo.amount = _COIN._amount;
                            paymentInfo.concept = _COIN._concept; // Agregar TAR xxxxx CRE xxxxx
							paymentInfo.reference = _COIN._reference;
                            eval(processCreditCardString);
                            CardProcessor.processCreditCard(paymentInfo, _COIN._PIN, _COIN._AUTHENTICATION.Token).then(
                                function (response) {
                                    _COIN._TRANSACTION = response;
                                    //if (_COIN._log) { console.log(_COIN._TRANSACTION); }
                                    if (_COIN._TRANSACTION.hasError) {
                                        reject(_COIN._TRANSACTION);
                                    } else {
										_fulldata["Resultado"] = _COIN._TRANSACTION.status;
										_fulldata["Respuesta"] = JSON.stringify(_COIN._TRANSACTION);
										if (_COIN._TRANSACTION.status != "rejected") {
											_fulldata["Transaccion"] = _COIN._TRANSACTION.apiReference;
											_AJAX.UiRegistrarCobranza(_fulldata).then(function (data) {
												_COIN._TRANSACTION["now"] = data.now;
												resolve(_COIN._TRANSACTION);
											});
                                        } else {
                                            reject({ "hasError":true,"error": "Se rechazó el pago de la tarjeta." });
                                        }
									}
                                },
								function (err) {
									reject(err);
								})
                        },
                        function (err) { reject(err);})
                } catch (err) {
                    reject(err);
                }
            });
    },
};

