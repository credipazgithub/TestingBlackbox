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
    }, 
    // llamada desde: onAddEspecialidades => Mediya/auxiliares/especialidades | { }
    // llamada desde: onCheckStatusPaymentTelemedicina => Credipaz/consultarEstadoTransaccionPago | {IdTransaccion}

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
    }, 
    //Websites/funciones/webpost | {id}

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
    //Production/authenticateMobile | {Username,Password}

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
    //Production/uservaluepwa | {type,documentNumber}

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
    //Production/userinformation | { id_app_ext, email}

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
    //Mediya/Telemedicina/enviarImagenAlMedico | {idUser,idChargeCode,raw_data}

    UiDelete: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["function"] = "delete";
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    //Production/resetusermobile | {email}

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
    //Mediya/Telemedicina/estadoSolicitudMobile | {id_transaction}

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
    //Telemedicina/generarcodigopago | {idUser,idSocio,idCliente,idPayment,importe,code,codePayment,especialidad}

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
    //Credipaz/funciones/beneficios | {dni,mode_categoria,type_categoria,search,coords,near,lat,lng,page}

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
    //Credipaz/funciones/imagenbeneficio | {id,type}

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
    //Mediya/Telemedicina/marcarmensajeleido | {id}

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
    //Mediya/Telemedicina/obtenerComprobantes | {idUser}

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
    //Mediya/Telemedicina/obtenerRecetas | {idSocio,idChargeCode,idTypeDirection,idTypeItem}

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
    //Credipaz/funciones/armarformfiserv | {total,dni,itemsPagos,parentUri}

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
    //Credipaz/iniciarTransaccionPago | {NroDocumento,Id_type_channel,Identificacion,Raw_request,Channel,Moneda,Monto}

    UiGetCredenciales: function (_json) {
        return new Promise(
            function (resolve, reject) {
                _json["method"] = "api.pwa/GetCredenciales"; //method
                _HTTPREQUEST_deprecated.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
            });
    },
    //Asesores/socios/credenciales | {NroDocumento,Sexo,Tipo}
};
