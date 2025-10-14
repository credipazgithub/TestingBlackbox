var _TOOLS = {
	_timerIcon: 0,
	_tmrClock: 0,
	latitude: null,
	longitude: null,
	altitude: null,
	accuracy: null,
	heading: null,
	speed: null,
	timestamp: null,
	observable: function (value) {
		var listeners = [];
		function notify(newValue) {
			listeners.forEach(function (listener) { listener(newValue); });
		}
		function accessor(newValue) {
			if (arguments.length && newValue !== value) {
				value = newValue;
				notify(newValue);
			}
			return value;
		}
		accessor.subscribe = function (listener) { listeners.push(listener); };
		return accessor;
	},
	formatMoney: function (_val, _dec = 2) {
		if (isNaN(_val)) { _val = 0; }
		return new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS', minimumFractionDigits: _dec, maximumFractionDigits: _dec }).format(_val);
	},
	todayYYYYMMDD: function (_separator) {
		var currentDate = new Date();
		var day = currentDate.getDate();
		var month = currentDate.getMonth() + 1;
		var year = currentDate.getFullYear();
		if (day < 10) { day = "0" + day; }
		if (month < 10) { month = "0" + month; }
		return (year + _separator + month + _separator + day);
	},
	toDeg: function (r) { return r * 180 / Math.PI; },
	getNow: function () {
		var currentDate = new Date();
		var second = currentDate.getSeconds();
		var minute = currentDate.getMinutes();
		var hour = currentDate.getHours();
		var day = currentDate.getDate();
		var month = currentDate.getMonth() + 1;
		var year = currentDate.getFullYear();
		if (day < 10) { day = "0" + day; }
		if (month < 10) { month = "0" + month; }
		if (hour < 10) { hour = "0" + hour; }
		if (minute < 10) { minute = "0" + minute; }
		if (second < 10) { second = "0" + second; }
		return day + "/" + month + "/" + year + " " + hour + ":" + minute + ":" + second;
	},
	getAge: function (dateString) {
		return moment().diff(moment(dateString, 'YYYYMMDD'), 'years');
	},
	getNowYYYYMMDD: function () {
		var currentDate = new Date();
		var second = currentDate.getSeconds();
		var minute = currentDate.getMinutes();
		var hour = currentDate.getHours();
		var day = currentDate.getDate();
		var month = currentDate.getMonth() + 1;
		var year = currentDate.getFullYear();
		if (day < 10) { day = "0" + day; }
		if (month < 10) { month = "0" + month; }
		if (hour < 10) { hour = "0" + hour; }
		if (minute < 10) { minute = "0" + minute; }
		if (second < 10) { second = "0" + second; }
		return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
	},
	isValidDate: function (date) {
		var fecha = moment(date);
		return fecha.isValid();
	},
	isValidEmail: function (email) {
		var em = /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
		return em.test(email);
	},
	validate: function (_selector, _seeAlert) {
		if (_seeAlert == undefined) { _seeAlert = false; }
		var _ret = true;
		$(_selector).each(function () { _ret = _TOOLS.formatValidation($(this)) && _ret; });
		if (!_ret && _seeAlert) {
			_FUNCTIONS.onAlert({ "message": "Complete los datos requeridos", "class": "alert-danger" });
		}
		return _ret;
	},
	formatValidation: function (_obj) {
		var _ret = true;
		var property = _obj.attr('name');
		switch (_obj.prop("tagName")) {
			case "TEXTAREA":
			case "INPUT":
				switch (_obj.attr("type")) {
					case "date":
						if (!_TOOLS.isValidDate(_obj.val())) { _ret = false; }
						break;
					case "email":
						if (!_TOOLS.isValidEmail(_obj.val())) { _ret = false; }
						break;
					case "radio":
						_ret = ($("input[name='" + property + "']:checked").val() != undefined);
						if (!_ret) {
							_obj.parent().css("border", "solid 1px red");
						} else {
							_obj.parent().css("border", "solid 0px transparent");
						}
						break;
					case "checkbox":
						var _checked = _obj.is(":checked");
						if (!_checked) { _ret = false; }
						break;
					default:
						if (_obj.hasClass("data-list")) {
							if (_obj.attr("data-selected-id") == "" || _obj.attr("data-selected-id") == undefined) { _ret = false; }
						} else {
							if (_obj.val() == "") { _ret = false; }
						}
						break;
				}
				break;
			case "SELECT":
				if (_obj.val() == "-1" || _obj.val() == undefined || _obj.val() == null || _obj.val() == "") { _ret = false; }
				break;
		}
		if (_ret) {
			_obj.removeClass("is-invalid").addClass("is-valid");
			$(".invalid-" + _obj.prop("name")).html("").addClass("d-none");
		} else {
			_obj.removeClass("is-valid").addClass("is-invalid");
			var _msg = _obj.attr("placeholder");
			if (_msg == undefined) { _msg = "el valor de selección"; }
			$(".invalid-" + _obj.prop("name")).html("Debe completar " + _msg).removeClass("d-none");
		}
		//if (!_ret) { alert(property);}
		return _ret;
	},
	getFormValues: function (_selector, _this) {
		try {
			var _jsonSave = {};
			$(_selector).each(function () {
				var property = $(this).attr('name');
				var value = "";
				switch ($(this).attr("data-type")) {
					case "select":
						if ($(this).length == 0) { value = ""; } else { value = $(this).val(); }
						if (value == null || value == "-1" || value == "0") { value = ""; }
						break;
					case "radio":
						value = $("input[name='" + property + "']:checked").val();
						if (value == undefined) { value = ""; }
						break;
					case "checkbox":
						if ($(this).prop("checked")) {
							value = $(this).val();
							if (parseInt(value) == 0 || value == '') { value = 1; }
						} else {
							value = 0;
						}
						break;
					default:
						value = $(this).val();
						break;
				}
				_jsonSave[property] = value;
			});
			//Process attached files
			/* GENERAL */
			var _newFiles = [];
			var _newLinks = [];
			var _delFiles = [];
			var _delLinks = [];
			var _newMessages = [];

			$(".new-file").each(function () {
				var _sanitized = $(this).attr('data-filename');
				_newFiles.push({ "src": $(this).attr('src'), "filename": _sanitized });
			});
			$(".new-link").each(function () { _newLinks.push({ "src": $(this).attr('data-link'), "link": $(this).attr('data-filename') }); });
			$(".del-file").each(function () { _delFiles.push({ "id": $(this).attr('data-id') }); });
			$(".del-link").each(function () { _delLinks.push({ "id": $(this).attr('data-id') }); });
			$(".new-message").each(function () { _newMessages.push({ "message": $(this).html() }); });

			/* MOD_FOLDERS */
			var _newFolderItems = [];
			$(".new-folder-item").each(function () {
				_newFolderItems.push(
					{
						"src": $(this).attr('data-result'),
						"filename": $(this).attr('data-filename'),
						"description": $(this).attr('data-description'),
						"keywords": $(this).attr('data-keywords'),
						"id_type_folder_item": $(this).attr('data-type'),
						"priority": $(this).attr('data-priority'),
					});
			});

			_jsonSave["new-files"] = _newFiles;
			_jsonSave["new-links"] = _newLinks;
			_jsonSave["del-files"] = _delFiles;
			_jsonSave["del-links"] = _delLinks;
			_jsonSave["new-messages"] = _newMessages;
			_jsonSave["new-folder-items"] = _newFolderItems;
			if (_this != null) {
				_jsonSave["id"] = _this.attr("data-id");
				_jsonSave["module"] = _this.attr("data-module");
				_jsonSave["model"] = _this.attr("data-model");
				_jsonSave["table"] = _this.attr("data-table");
				if (_this.attr("data-page") == undefined) { _this.attr("data-page", 1); };
				_jsonSave["page"] = _this.attr("data-page");
			}
		} catch (rex) { };
		return _jsonSave;
	},
	UUID: function () {
		var s = [];
		var hexDigits = "0123456789abcdef";
		for (var i = 0; i < 36; i++) { s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1); }
		s[14] = "4";
		s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
		s[8] = s[13] = s[18] = s[23] = "-";
		var uuid = s.join("");
		return uuid;
	},
	iconByMime: function (_file_type, _data) {
		var _icon = "";
		switch (true) {
			case (_file_type.indexOf("image") != -1):
				_icon = "./assets/img/image.png";
				break;
			case (_file_type.indexOf("wav") != -1):
			case (_file_type.indexOf("mp3") != -1):
			case (_file_type.indexOf("audio") != -1):
				_icon = "./assets/img/audio.png";
				break;
			case (_file_type.indexOf("video") != -1):
			case (_file_type.indexOf("youtube") != -1):
			case (_file_type.indexOf("video") != -1):
				_icon = "./assets/img/video.png";
				break;
			case (_file_type.indexOf("pdf") != -1):
				_icon = "./assets/img/pdf.png";
				break;
			default:
				_icon = "./assets/img/file.png";
				break;
		}
		return _icon;
	},
	diffSeconds: function (_from, _to) {
		_from = moment(_from);
		_to = moment(_to);
		var _duration = moment.duration(_from.diff(_to));
		return _duration.asSeconds();
	},
	prettyPrint: function (obj) {
		return JSON.stringify(obj, undefined, 4);
	},
	NASort: function (a, b) {
		if (a.innerHTML == 'NA') {
			return 1;
		}
		else if (b.innerHTML == 'NA') {
			return -1;
		}
		return (a.innerHTML > b.innerHTML) ? 1 : -1;
	},
	replaceAll: function (str, find, replace) {
		return str.replace(new RegExp(find, 'g'), replace);
	},
	loadCombo: function (datajson, params) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (params.default == undefined) { params.default = "[Seleccione]"; }
					$(params.target).empty();
					if (params.selected == -1) { $(params.target).append('<option selected value="-1">' + params.default + '</option>'); }
					$.each(datajson.data, function (i, item) {
						$(params.target).append('<option value="' + item[params.id] + '">' + item[params.description] + '</option>');
					});
					resolve(true);
				} catch (rex) {
					reject(rex);
				}
			});
	},
	loadBrowser: function (datajson, params) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _full = false;
					var _html = "";
					$.each(datajson.data, function (i, item) {
						if (i == 0) {
							_full = true;
							_html += "<table class='table table-condensed'>";
							_html += " <thead>";
							_html += "  <tr>";
							$.each(params.cols, function (i, col) {
								_html += "<th><b>" + col.title + "</b></th>";
							});
							_html += "  </tr>";
							_html += " </thead>";
							_html += " <tbody>";
						}
						_html += "<tr>";
						$.each(params.cols, function (i, col) {
							_html += "<td>" + item[col.field] + "</td>";
						});
						_html += "</tr>";
					});
					if (_full) {
						_html += " </tbody>";
						_html += "</table>";
					}
					$(params.target).html(_html);
					resolve(true);
				} catch (rex) {
					reject(rex);
				}
			});
	},
	successTelemetry: function (position) {
		_TOOLS.latitude = position.coords.latitude;
		_TOOLS.longitude = position.coords.longitude;
		_TOOLS.altitude = position.coords.altitude;
		_TOOLS.accuracy = position.coords.accuracy;
		_TOOLS.heading = position.coords.heading;
		_TOOLS.speed = position.coords.speed;
		_TOOLS.timestamp = position.coords.timestamp;
	},
	errorTelemetry: function (error) {
		_TOOLS.latitude = null;
		_TOOLS.longitude = null;
		_TOOLS.altitude = null;
		_TOOLS.accuracy = null;
		_TOOLS.heading = null;
		_TOOLS.speed = null;
		_TOOLS.timestamp = null;
	},
	createFileItem: function (_name, _result) {
		var _id = _TOOLS.UUID();
		return "<li class='list-group-item attach " + _id + "' data-name='" + _name + "' data-url='" + _result + "' style='padding:10px;'>Se ha adjuntado <span class='badge badge-success'>" + _name + "</span><a href='#' class='btn btn-xs btn-deattach btn-danger pull-right' data-id='" + _id + "' style='margin:0px;'><i class='material-icons'>delete_forever</i></a></li>"
	},
	utf8_to_b64: function (str) { return window.btoa(unescape(encodeURIComponent(str))); },
	b64_to_utf8: function (str) { str = str.replace(/\s/g, ''); return decodeURIComponent(escape(window.atob(str))); },
	onClock: function (_target) {
		var d = new Date();
		if ($(_target) == undefined) { clearInterval(_TOOLS._tmrClock); }
		$(_target).html(d.toLocaleTimeString()).removeClass("hidden");
	},
	toDataURL: function (src, callback, outputFormat) {
		var img = new Image();
		img.crossOrigin = 'Anonymous';
		img.onload = function () {
			var canvas = document.createElement('CANVAS');
			var ctx = canvas.getContext('2d');
			var dataURL;
			canvas.height = this.naturalHeight;
			canvas.width = this.naturalWidth;
			ctx.drawImage(this, 0, 0);
			dataURL = canvas.toDataURL(outputFormat);
			callback(dataURL);
		};
		img.src = src;
		if (img.complete || img.complete === undefined) {
			img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
			img.src = src;
		}
	},
	arrayBufferToBase64: function (buffer) {
		var binary = '';
		var bytes = new Uint8Array(buffer);
		var len = bytes.byteLength;
		for (var i = 0; i < len; i++) { binary += String.fromCharCode(bytes[i]); }
		return window.btoa(binary);
	},
	toSHS256: function (ascii) {
		function rightRotate(value, amount) {
			return (value >>> amount) | (value << (32 - amount));
		};
		var mathPow = Math.pow;
		var maxWord = mathPow(2, 32);
		var lengthProperty = 'length'
		var i, j; // Used as a counter across the whole file
		var result = ''
		var words = [];
		var asciiBitLength = ascii[lengthProperty] * 8;
		//* caching results is optional - remove/add slash from front of this line to toggle
		// Initial hash value: first 32 bits of the fractional parts of the square roots of the first 8 primes
		// (we actually calculate the first 64, but extra values are just ignored)
		var hash = sha256.h = sha256.h || [];
		// Round constants: first 32 bits of the fractional parts of the cube roots of the first 64 primes
		var k = sha256.k = sha256.k || [];
		var primeCounter = k[lengthProperty];
		/*/
		var hash = [], k = [];
		var primeCounter = 0;
		//*/
		var isComposite = {};
		for (var candidate = 2; primeCounter < 64; candidate++) {
			if (!isComposite[candidate]) {
				for (i = 0; i < 313; i += candidate) {
					isComposite[i] = candidate;
				}
				hash[primeCounter] = (mathPow(candidate, .5) * maxWord) | 0;
				k[primeCounter++] = (mathPow(candidate, 1 / 3) * maxWord) | 0;
			}
		}
		ascii += '\x80' // Append Ƈ' bit (plus zero padding)
		while (ascii[lengthProperty] % 64 - 56) ascii += '\x00' // More zero padding
		for (i = 0; i < ascii[lengthProperty]; i++) {
			j = ascii.charCodeAt(i);
			if (j >> 8) return; // ASCII check: only accept characters in range 0-255
			words[i >> 2] |= j << ((3 - i) % 4) * 8;
		}
		words[words[lengthProperty]] = ((asciiBitLength / maxWord) | 0);
		words[words[lengthProperty]] = (asciiBitLength)
		// process each chunk
		for (j = 0; j < words[lengthProperty];) {
			var w = words.slice(j, j += 16); // The message is expanded into 64 words as part of the iteration
			var oldHash = hash;
			// This is now the undefinedworking hash", often labelled as variables a...g
			// (we have to truncate as well, otherwise extra entries at the end accumulate
			hash = hash.slice(0, 8);
			for (i = 0; i < 64; i++) {
				var i2 = i + j;
				// Expand the message into 64 words
				// Used below if 
				var w15 = w[i - 15], w2 = w[i - 2];
				// Iterate
				var a = hash[0], e = hash[4];
				var temp1 = hash[7]
					+ (rightRotate(e, 6) ^ rightRotate(e, 11) ^ rightRotate(e, 25)) // S1
					+ ((e & hash[5]) ^ ((~e) & hash[6])) // ch
					+ k[i]
					// Expand the message schedule if needed
					+ (w[i] = (i < 16) ? w[i] : (
						w[i - 16]
						+ (rightRotate(w15, 7) ^ rightRotate(w15, 18) ^ (w15 >>> 3)) // s0
						+ w[i - 7]
						+ (rightRotate(w2, 17) ^ rightRotate(w2, 19) ^ (w2 >>> 10)) // s1
					) | 0
					);
				// This is only used once, so *could* be moved below, but it only saves 4 bytes and makes things unreadble
				var temp2 = (rightRotate(a, 2) ^ rightRotate(a, 13) ^ rightRotate(a, 22)) + ((a & hash[1]) ^ (a & hash[2]) ^ (hash[1] & hash[2])); // maj
				hash = [(temp1 + temp2) | 0].concat(hash); // We don't bother trimming off the extra ones, they're harmless as long as we're truncating when we do the slice()
				hash[4] = (hash[4] + temp1) | 0;
			}
			for (i = 0; i < 8; i++) { hash[i] = (hash[i] + oldHash[i]) | 0; }
		}
		for (i = 0; i < 8; i++) {
			for (j = 3; j + 1; j--) {
				var b = (hash[i] >> (j * 8)) & 255;
				result += ((b < 16) ? 0 : '') + b.toString(16);
			}
		}
		return result;
	},
	copyToClipboard: function (_this) {
		var _id = _this.attr("data-source");
		var textToCopy = $('#' + _id).val();
		var tempTextarea = $('<textarea>');
		$('body').append(tempTextarea);
		tempTextarea.val(textToCopy).select();
		document.execCommand('copy');
		tempTextarea.remove();
		_FUNCTIONS.onAlert({ "message": "Se han copiado los datos al portapapeles.  Puede utilizarlos donde desee.", "class": "alert-info" });
	},
	toFullscreen: function (_id) {
		const element = document.getElementById(_id);
		if (screenfull.isEnabled) {
			screenfull.request(element);
		}
	},
	markRequired: function (_selector) {
		$(_selector).each(function () {
			$(this).css({ "border-right": "solid 4px magenta" });
		});
	},
	getAge: function (dateString) {
		var today = new Date();
		var birthDate = new Date(dateString);
		var age = today.getFullYear() - birthDate.getFullYear();
		var m = today.getMonth() - birthDate.getMonth();
		if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
			age--;
		}
		return parseInt(age);
	},
	openFullscreen: function (elem) {
		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} else if (elem.webkitRequestFullscreen) { /* Safari */
			elem.webkitRequestFullscreen();
		} else if (elem.msRequestFullscreen) { /* IE11 */
			elem.msRequestFullscreen();
		}
	},
	closeFullscreen: function () {
		if (document.exitFullscreen) {
			document.exitFullscreen();
		} else if (document.webkitExitFullscreen) { /* Safari */
			document.webkitExitFullscreen();
		} else if (document.msExitFullscreen) { /* IE11 */
			document.msExitFullscreen();
		}
	},
	LPAD: function (str, char, length) {
		while (String(str).length < length) {
			str = (char + str);
		}
		return str;
	},
	getBase64Image: function (img) {
		var canvas = document.createElement("canvas");
		canvas.width = img.width;
		canvas.height = img.height;
		var ctx = canvas.getContext("2d");
		ctx.drawImage(img, 0, 0);
		var dataURL = canvas.toDataURL("image/png");
		return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
	},
	filterTable: function (_input, _table, _index) {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById(_input);
		filter = input.value.toUpperCase();
		table = document.getElementById(_table);
		tr = table.getElementsByTagName("tr");
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[_index];
			if (td) {
				txtValue = (td.textContent || td.innerText);
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			}
		}
	},
	copySimple: function (_id) {
		var copyText = document.getElementById(_id);
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices
		navigator.clipboard.writeText(copyText.value);
		alert("Se ha copiado al portapapeles: " + copyText.value);
	},
	itemToControl: function (_arr, _key, _renull) {
		if (_renull != "") {
			if (_arr[_key] == "") { _arr[_key] = _renull; }
		}
		try { $("." + _key).val(_arr[_key].toString().trim()); } catch (ex) { }
	},
	checkLen: function (_key, _label, _min) {
		var _val = $("." + _key).val();
		var _len = $("." + _key).attr("maxlength");
		if (_min != undefined) { _len = _min; }
		if (_val != "") {
			if (_val.length != _len) {
				alert("El " + _label + " debe tener " + _len + " caracteres");
				return false;
			}
		}
		return true;
	},
	drawCredential: function (_canva, _file, _mode, data) {
		return new Promise(
			function (resolve, reject) {
				try {
					var canvas = document.createElement('canvas');
					canvas.width = 640;
					canvas.height = 406;

					//const canvas = document.getElementById(_canva);
					const ctx = canvas.getContext('2d');
					let img = new Image();
					img.addEventListener("load", () => {
						ctx.clearRect(0, 0, canvas.width, canvas.height);
						ctx.reset();
						ctx.drawImage(img, 0, 0);
						var _NroCredencial = data.NroCredencial;
						var _Nombre = data.Nombre;
						var _FechaIngreso = data.FechaIngreso;
						var _FechaNacimiento = data.FechaNacimiento;
						switch (_mode) {
							case "clubredondo":
								break;
							case "swiss":
								ctx.fillStyle = "rgb(76, 76, 76)";
								ctx.font = '25px Lato-black';
								ctx.fillText(_NroCredencial, 54, 150);
								ctx.fillText(_Nombre, 54, 185);
								ctx.font = '20px Lato-black';
								ctx.fillText(_FechaIngreso, 110, 222);
								ctx.fillText(_FechaNacimiento, 320, 222);
								break;
							case "gerdanna":
								ctx.fillStyle = "rgb(255, 255, 255)";
								ctx.font = '24px Roboto-light';
								ctx.fillText(_Nombre, 40, 285);
								ctx.font = '24px Roboto-black';
								ctx.fillText(_NroCredencial, 40, 320);
								break;
						}
						var _b64 = canvas.toDataURL("image/png");
						ctx.clearRect(0, 0, canvas.width, canvas.height);
						ctx.reset();
						resolve(_b64);
					});
					img.src = _file;
				} catch (err) {
					reject(null);
				}
			});
	},
	drawCredentialSwiss: function (item) {
		$(".area-swiss").removeClass("card-loader").removeClass("skeleton").addClass("d-none");
		var _img = (window.location.protocol + "//" + window.location.host + "//" + "assets//credipaz//img//swiss.png");
			_TOOLS.drawCredential("canvasSwiss", _img, "swiss", item).then(function (data) {
				_TOOLS.drawItemCredential(data, item, "swiss");
			});
		$(".area-swiss").removeClass("d-none");
		$(".title-credencial").show();
	},
	drawCredentialGerdanna: function (item) {
		$(".area-gerdanna").removeClass("card-loader").removeClass("skeleton").addClass("d-none");
		var _img = (window.location.protocol + "//" + window.location.host + "//" + "assets//credipaz//img//gerdanna.png");
			_TOOLS.drawCredential("canvasGerdanna", _img, "gerdanna", item).then(function (data) {
				_TOOLS.drawItemCredential(data, item, "gerdanna");
			});
		$(".area-gerdanna").removeClass("d-none");
		$(".title-credencial").show();
	},
	drawItemCredential: function (data, _record, _tipo) {
		$(".area-" + _tipo).removeClass("skeleton");
		$(".area-" + _tipo).removeClass("card-loader");
		var _title = _record.Nombre;
		var _html = "";// "<p class='p-0 m-0' style='text-align:left;font-family:Roboto-regular;font-size:0.65em;color:darkgrey;'>" + _title + "</p>";
		_html += "<img class='img-tarjeta p-1' src='" + data + "' alt='Credencial' style='width:100%;' />";
		if (parseInt(_record.IDParentesco) == 1) {
			$(".area-" + _tipo).prepend(_html);
		} else {
			$(".area-" + _tipo).append(_html);
		}
	},
	onlyNumbers: function (_this) {
		var validNumber = new RegExp(/^\d*\.?\d*$/);
		if (!validNumber.test(_this.val())) { _this.val(""); }
		//_this.val(_this.val().replace(/[^0-9]/g, ''));
	},
};

