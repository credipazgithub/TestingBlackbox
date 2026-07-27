/* Objeto con todas las funciones de la rama */
var _F = {
	/* FUNCION DE INICIALIZACION */
	onInit: function () {
		return new Promise(
			function (resolve, reject) {
				try {
					$("body").load((_API._ROOT + "/html/index.html?" + _API._TS), function () {
						_API.inited = true;
						$(".logoImage").attr("src", (_API._ROOT + "/img/logo.png?" + _API._TS));
						resolve(null);
					});
				} catch (err) {
					reject(err);
				}
			}
		);
	},
	/* FUNCION DE DESTRUCCION DE INTERFACE */
	onDestroy: function () {
		return new Promise(
			function (resolve, reject) {
				try {
					$("body").html("");
					_API.inited = false;
					_API._ROOT = "";
					resolve(response);
				} catch (err) {
					reject(err);
				}
			}
		);
	},

	/* FUNCION DE TESTEO */
	onTest: function (_this) {
		var _target = _this.attr("data-target");
		var _alert = _this.attr("data-alert");
		var _message = _this.attr("data-message");
		alert(_alert);
		$(_target).html(_message);
	},

	/* FUNCIONES IMPLEMENTADAS */
	onCancelTelemedicina: function (_this) {
		if (!confirm("Se cancelará la atención seleccionada. ¿Confirma?")) { return false; }
		_API.onWait(true);
		_API.method("/telemedicina/cancelar", { "Id": _this.attr("data-id") })
			.then(function (data) {
				_F.onMonitoreo(null);
				_API.onWait(false);
			}).catch(function (e) {
				_API.onWait(false);
			});
	},
	onBuildArea: function (iModo, _title) {
		_API.method("/telemedicina/monitoreo", { "iModo": iModo })
			.then(function (data) {
				var _html = "";
				if (data.records.length > 0) {
					var vHeaders = [];
					var vColumns = [];
					var vRules = [];
					switch (iModo) {
						case 1:
							vColumns = ["f_name_club_redondo", "f_elapsed", "especialidad"];
							break;
						case 2:
							vColumns = ["f_name_club_redondo", "f_doctor", "f_elapsed", "cancelar"];
							break;
						case 3:
							vColumns = ["f_name_club_redondo", "f_doctor", "f_elapsed"];
							break;
					}
					_html = _API.onBuildTable(("tblMonitoreo" + iModo), _title, data.records, vHeaders, vColumns, vRules, "", "");
				}
				$((".areaResultado-" + iModo)).html(_html);
			});
	},
	onMonitoreo: function (_this) {
		var _html = "";
		_html += "<div class='row'>";
		_html += "<div class='col-2 areaResultado-1 p-1 shadow-sm'></div>";
		_html += "<div class='col-5 areaResultado-2 p-1 shadow-sm'></div>";
		_html += "<div class='col-5 areaResultado-3 p-1 shadow-sm'></div>";
		_html += "</div>";
		$(".areaResultado").html(_html).removeClass("d-none");
		_F.onBuildArea(1, "En Espera");
		_F.onBuildArea(2, "Siendo atendidos");
		_F.onBuildArea(3, "Últimas atenciones");
	},
	onSupervision: function (_this) {
		_API.method("/telemedicina/supervision", {})
			.then(function (data) {
				var _html = "";
				if (data.records.length > 0) {
					var vHeaders = ["", "", "Creado", "Paciente", "Código", "Médico", "El paciente refiere", "Cierre"];
					var vColumns = ["btnEdit", "notas", "f_created", "f_name_club_redondo", "f_code", "f_doctor", "refiere", "f_type_task_close"];
					var vRules = [];
					_html = _API.onBuildTable(("tblSupervision"), "Supervisión", data.records, vHeaders, vColumns, vRules, "", "");
				}
				$(".areaResultado").html(_html).removeClass("d-none");
			});
	},
	onConsultas: function (_this) {
		_API.method("/telemedicina/consultas", { "idUser": _API.authentication.data.id })
			.then(function (data) {
				var _html = "";
				if (data.records.length > 0) {
					var vHeaders = ["", "", "Creado", "Paciente", "Código", "", "Médico", "El paciente refiere", "Cierre"];
					var vColumns = ["btnEdit", "notas", "f_created", "f_name_club_redondo", "f_code", "enCurso", "f_doctor", "refiere", "f_type_task_close"];
					var vRules = [];
					_html = _API.onBuildTable(("tblConsultas"), "Consultas", data.records, vHeaders, vColumns, vRules, "", "");
				}
				$(".areaResultado").html(_html).removeClass("d-none");
			});
	},
	onPostClose: function (_this) {
		var _id = _this.attr("data-id");
		$.get((_API._ROOT + "/html/postclose.html?" + _API._TS), function (_html) {
			_API.onShowModal("modalPostClose", "", _html, "modal-lg").then(function (_ret) {
				_API.method("/telemedicina/monitoreo", { "iModo": 4, "Id": _id })
					.then(function (data) {
						if (data.records[0].post_close != "") { $(".tNotasAnteriores").html("Notas anteriores:<br/>" + data.records[0].post_close); }
						$(".tPaciente").html(data.records[0].name_club_redondo);
						$(".tRefiere").html(data.records[0].refiere);
						$(".tMotivo").html(data.records[0].motivo);
						$(".tEvolucion").html(data.records[0].evolucion);
						$(".tDiagnostico").html(data.records[0].diagnostico);
						$(".tIndicaciones").html(data.records[0].indicaciones);
						var _eMed = "";
						_eMed = "<table class='table table-sm table-borderless'>";
						_eMed += "<tr style='font-weight:bold;'><td>Consulta</td><td>Emergencia</td><td>Especialista</td></tr>";
						_eMed += "<tr><td>" + _F.onResolveSiNo(data.records[0].derivado_consulta) + "</td><td>" + _F.onResolveSiNo(data.records[0].derivado_emergencia) + "</td><td>" + _F.onResolveSiNo(data.records[0].derivado_especialista) + "</td></tr>";
						_eMed += "</table>";
						$(".tDerivaciones").html(_eMed);
						$(".wfooter").remove();
						$(".btn-cancel-modal").attr("data-modal", "modalPostClose");
						$(".btnSaveNuevaNota").attr("data-modal", "modalPostClose");
						$(".btnSaveNuevaNota").attr("data-id", _id);
					});
			})
		});
	},
	onEditChargeCode: function (_this) {
		var _id = _this.attr("data-id");
		$.get((_API._ROOT + "/html/editchargecode.html?" + _API._TS), function (_html) {
			_API.onShowModal("modalEditChargeCode", "", _html, "modal-xl").then(function (_ret) {
				_API.method("/telemedicina/monitoreo", { "iModo": 4, "Id": _id })
					.then(function (data) {
						$(".wfooter").remove();
						$(".btn-cancel-modal").attr("data-modal", "modalEditChargeCode");
						$(".btnSaveAtencion").attr("data-modal", "modalEditChargeCode");
						$(".btnSaveAtencion").attr("data-id", _id);
						$(".codigo").html(data.records[0].code);
						$(".tNombre").html(data.records[0].Nombre);
						$(".tDocumento").html(data.records[0].NroDocumento);
						$(".tSexo").html(data.records[0].Sexo);
						$(".tIdSocio").html(data.records[0].id_club_redondo);
						$(".tFechaAlta").html(data.records[0].FechaAlta);
						$(".tTipoSocio").html(data.records[0].TipoSocio);
						$(".especialidad").html(data.records[0].especialidad.replaceAll("_", " "));
						$(".tMotivo").html(data.records[0].motivo);
						$(".tEvolucion").html(data.records[0].evolucion);
						$(".tDiagnostico").html(data.records[0].diagnostico);
						$(".tIndicaciones").html(data.records[0].indicaciones);
						$(".tCierreIrregular").html(data.records[0].note_close);
						$(".tEdad").html(data.records[0].Edad);
						$(".tEmail").html(data.records[0].Email);
						$(".tTelefono").html(data.records[0].Telefono);
						$(".tEstado").html(data.records[0].Estado);
						_F.onCheckFromValue(data.records[0].derivado_consulta, "#chkPresencial");
						_F.onCheckFromValue(data.records[0].derivado_especialista, "#chkEspecialista");

						/* Faltan datos */
//						$(".tObraSocial").val(data.records[0].ObraSocial);
//						$(".tNumeroPlan").val(data.records[0].NumeroPlan);
//						$(".tCredencialSwiss").val(data.records[0].motivo);

						_API.onLoadComboAjax("/telemedicina/tiposcierre", ".tCierre", data.records[0].type_task_close, "");
					});
			})
		});
	},
	onSaveNuevaNota: function (_this) {
		if (!_API.tools.validate(".validatePostClose", false)) { return false; }
		var _id = _this.attr("data-id");
		_API.method("/telemedicina/postcierre", { "Id": _id, "Nota": $(".nuevaNota").val() })
			.then(function (data) {
				$(".btn-cancel-modal").click();
			});
	},
	onResolveSiNo: function (_str) {
		var _ret = "NO";
		if (_str == undefined || _str == "") { _str = 0; }
		_str = parseInt(_str);
		if (_str == 1) { _rt = "SI"; }
		return _ret;
	},
	onCheckFromValue: function (_value, _selector) {
		if (_value == undefined || _value == "") { _value = 0; }
		_value = parseInt(_value);
		$(_selector).prop("checked", (_value == 1));
	},
	onChangeTypeClose: function (_this) {
		var _val = _this.val();
		$(".areaCierreIrregular").addClass("d-none");
		if (_val != "") {
			switch (parseInt(_val)) {
				case 2:
				case 4:
					$(".areaCierreIrregular").removeClass("d-none");
					break;
			};
		};
	},
}