$("body").off("click", ".btnInformeCesion").on("click", ".btnInformeCesion", function () {
	var _title = $(this).attr("data-title");
	var _json = {
		"token_authentication": _AJAX._token_authentication,
		"id_user_active": _AJAX._id_user_active,
		"id_app": _AJAX._id_app,
		"Key": $(this).attr("data-key"),
		"File": $(this).attr("data-file"),
	}
	_AJAX.docUiExecute(_AJAX.server + "credipaz/archivo", _json).then(function (data) {
		var _html = "<iframe src='data:" + data.mime + ";base64," + data.base64 + "' style='border:solid 0px red;height:640px;width:100%;'></iframe>";
		_FUNCTIONS.onShowHtmlModal(_title, _html, function () {
			$(".modal-dialog").addClass("modal-lg").addClass("modal-dialog-centered");
		});
	});

});
$("body").off("click", ".btn-downloadZip").on("click", ".btn-downloadZip", function () {
	FillGrid($(this).attr("data-dni"), true);
});
$("body").off("click", ".btn-informes").on("click", ".btn-informes", function () {
	FillGrid($(this).attr("data-dni"), false);
});
$("body").off("keyup", ".dni").on("keyup", ".dni", function (e) {
	clearTimeout(_FUNCTIONS._TIMER_LAZY);
	var _key = $(this).val();
	if (_key == "") { $(".rLine").removeClass("d-none"); return false; }
	_FUNCTIONS._TIMER_LAZY = setTimeout(function () {
		$(".rLine").addClass("d-none");
		$('div[class*="'+_key+'"]').removeClass("d-none");
	}, 500);
});

$("body").off("change", ".cboCesion").on("change", ".cboCesion", function (e) {
	$(".dni").val("");
	FillGrid("", false);
});

function FillGrid(_dni, _download) {
	try {
		var _interno = $(".interno").val();
		$.blockUI({ message: '<img src="https://intranet.credipaz.com/assets/img/wait.gif" />', css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
		var _json = {
			"token_authentication": _AJAX._token_authentication,
			"id_user_active": $(".cboBanco").val(),
			"id_app": _AJAX._id_app,
			"NroDocumento": _dni,
			"interno": _interno,
			"download": _download,
			"FechaCesion": $(".cboCesion").val()
		}
		if (_download) {
			if (!confirm("Está a punto de ejecutar un proceso potencialmente largo, que puede tardar varios minutos.\n¡Por favor, no cierre el navegador, el archivo se descargará automáticamente al concluir!")) { return false; };
		}
		_AJAX.docUiExecute(_AJAX.server + "credipaz/cedidos", _json).then(function (data) {
			if (!_download) {
				var _html = "";
				_html += "      <div class='row shadow-sm p-1 mb-1' style='background-color:silver;'>";
				_html += "         <div class='col-1'><b>Fecha</b></div>";
				_html += "         <div class='col-2'><b>DNI</b></div>";
				_html += "         <div class='col-3'><b>Titular</b></div>";
				_html += "         <div class='col-1'></div>"; 
				_html += "         <div class='col-4'></div>";
				_html += "      </div>";
				$.each(data.data, function (i, item) {
					_html += "<div class='row p-1 rLine c-" + item.NroDocumento + "'>";
					_html += "   <div class='col-1'>" + item.fFecha + "</div>";
					_html += "   <div class='col-2'>" + item.NroDocumento + " " + item.Sexo + "</div>";
					_html += "   <div class='col-3'>" + item.Nombre + "</div>";
					_html += "   <div class='col-1'>";
					if (item.FechaCedido != "") {
						_html += "<span class='badge badge-success'>Cedido: " + item.FechaCedido + "</span>";
					} else {
						_html += "<span class='badge badge-danger'>Aún no cedido</span>";
					}
					_html += "</div>";
					if (_dni == "") {
						_html += "<div class='col-4'><a href='#' class='btn btn-primary btn-informes' data-dni='" + item.NroDocumento + "'>Informes</a></div>";
					} else {
						_html += "<div class='col-4'>";
						_html += "   <div class='dropdown'>";
						_html += "      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>Seleccione informe</button>";
						_html += "      <div class='dropdown-menu'>";
						$.each(item.CarpetaDigital, function (i2, item2) {
							switch (item2.title) {
								case "CONSUMO":
								case "EDAD":
								case "FIRMA":
								case "MOROSIDAD":
								case "FRAUDULENTO":
								case "INGRESOS":
								case "OCUPACION":
								case "CHECKLIST":
									break;
								default:
									_html += "<a href='#' class='dropdown-item btnInformeCesion' data-title='" + item2.title + "' data-file='" + item2.filename + "' data-key='" + item2.key + "'>Informe " + item2.title + "</a>";
									break;
							}
						});
						_html += "      </div>";
						_html += "   </div>";
						_html += "</div>";
					}
					_html += "</div>";
				});
				if (_dni != "") {
					$(".search").addClass("d-none");
					$(".back").removeClass("d-none");
				} else {
					$(".search").removeClass("d-none");
					$(".back").addClass("d-none");
				}
				$(".listado").html(_html);
			} else {
				var newLink = $('<a>', {href: data.link, target: '_blank', text: '', id: 'aDownload' });
				$('body').append(newLink);
				$('#aDownload')[0].click();
				setTimeout(function () { $('#aDownload').remove(); }, 250);
			}
			$.unblockUI();
		}).catch(function (err) {
		});
	}
	catch (rex) {
		$.unblockUI();
		_FUNCTIONS.onShowInfo("No se ha podido efectuar la consulta", "Alerta");
	}
}