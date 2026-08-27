/*Todo va wrapped dentro del llamado a la configuracion de server Blacbox */
_AJAX_deprecated.readConfigServers("Intranet").then(function (data) {
    /* *******************************************
    *  General configuration!
    ****************************************** * */
    _AJAX_deprecated._serverBlackBox = data.url;
    _AJAX_deprecated._modo = "NORMAL";
    _AJAX_deprecated._bforceGoto = false;
    /*********************************************/

    /* *******************************************
    * Asigna los parámetros de la url
    ****************************************** * */
    var _parameters = _T.onAllUrlParams();

    /* evaluar si debe entar a firma especifica */
    if (_parameters.id_user_active != undefined) { _AJAX_deprecated._id_user = _parameters.id_user_active; }
    if (_parameters.username != undefined) { _AJAX_deprecated._username = _parameters.username; }
    if (_parameters.verificated != undefined) {
        if (_parameters.verificated.includes("-")) { _AJAX_deprecated.verificated = _parameters.externalid.split("-")[0]; }
        _AJAX_deprecated._decision = "CONTINUE";
    }
    if (_parameters.idtx != undefined) { _AJAX_deprecated._idtx = _parameters.idtx; }
    if (_parameters.externalid != undefined) {
        _AJAX_deprecated._externalid = _parameters.externalid;
        _AJAX_deprecated._KEY = _parameters.externalid;
        if (_parameters.externalid.includes("-")) {
            var _xval = _parameters.externalid.split("-")[0];
            _AJAX_deprecated._externalid = _xval;
            _AJAX_deprecated._KEY = _xval;
        }
        _AJAX_deprecated._decision = _parameters.decision;
    }
    /*********************************************/

    /********************************************
    * Control de navegación
    ****************************************** * */
    var _responseIdemia = false;
    switch (_AJAX_deprecated._decision.toUpperCase()) {
        case "":
        case "HIT":
            _responseIdemia = true;
            _AJAX_deprecated._responseTitle = "Se ha efectuado el proceso de verificación de identidad.";
            _AJAX_deprecated._responseMessage = "El resultado ha quedado vinculado a la operación, para su evaluación comercial.";
            _AJAX_deprecated._init_page = "msg-ok"; // Fin de toma de datos de idemia!
            break;
        case "NO VIGENTE":
        case "NO%20VIGENTE":
        case "ERR_RNP":
        case "NO_HIT":
        case "NOT_FOUND":
            _AJAX_deprecated._responseTitle = "¡Gracias por confiar en nosotros!";
            _AJAX_deprecated._responseMessage = "Un operador lo contactará para terminar el proceso de emisión.";
            _AJAX_deprecated._init_page = "msg-ok"; // Frena avisando que un operador lo contactará
            break;
        case "FORZADO":
            break;
        case "NULL":
        case "VIGENTE":
            _AJAX_deprecated._decision = "VIGENTE";
            _AJAX_deprecated._init_page = "idemia-paso-2";
            break;
        case "CONTINUE":
            if (_parameters.verificated != undefined) { _AJAX_deprecated._KEY = _parameters.verificated; }
            _AJAX_deprecated._monopage = (_parameters.monopage != undefined);
            _AJAX_deprecated._init_page = _parameters.monopage;
            /*------------------------------------------------------------------------------------------------------*/
            /* Asignacion de pagina a navegar, configuracion de valores y comportamiento e indicacion de solo firma */
            /*------------------------------------------------------------------------------------------------------*/
            switch (_parameters.monopage.toLowerCase()) {
                case "firma-creditos":
                    break;
                case "tokenizar-tdnt": //Tokenizaciones-tarjetas debito Neutrales
                case "tokenizar-tdcp": //Tokenizaciones-tarjetas debito Credipaz
                case "tokenizar-tdmy": //Tokenizaciones-tarjetas debito Mediya
                    _AJAX_deprecated._bforceGoto = true;
                    _AJAX_deprecated._id_type_medio_cobro = 1;
                    _AJAX_deprecated._formularioFirma = _parameters.monopage;
                    _AJAX_deprecated._justSign = true;
                    _AJAX_deprecated._init_page = "get-tokenizar";
                    break;
                case "cambiolimitecredito": //formulario de cambio de limite de credito
                    _AJAX_deprecated._formularioFirma = _parameters.monopage;
                    _AJAX_deprecated._formularioPrefijoCarpetaDigital = "CLC";
                    _AJAX_deprecated._justSign = true;
                    _AJAX_deprecated._init_page = "get-firmar";
                    break;
                case "adhesiongrupofamiliarmediya":
                case "adhesionamutramediya":
                case "adhesionmediya": //formulario
                    _AJAX_deprecated._formularioFirma = _parameters.monopage;
                    _AJAX_deprecated._formularioPrefijoCarpetaDigital = "MEDIYA";
                    _AJAX_deprecated._justSign = true;
                    _AJAX_deprecated._init_page = "get-firmar";
                    break;
                case "debitoemergenciasmedicas": //formulario
                    _AJAX_deprecated._formularioFirma = _parameters.monopage;
                    _AJAX_deprecated._formularioPrefijoCarpetaDigital = "TAR";
                    _AJAX_deprecated._justSign = true;
                    _AJAX_deprecated._init_page = "get-firmar";
                    break;
                case "idemia-paso-1": //proceso de idemia
                    _AJAX_deprecated._monopage = false;// al finalizar sigue la ejecucion a otra pagina!
                    break;
                case "dni-frente": //Captura dni frente
                    _AJAX_deprecated._idFoto = 3;
                    _AJAX_descriptionFoto = "DNI frente";
                    _AJAX_customFoto = "Tomá una foto del frente de tu DNI";
                    _AJAX_scopeFoto = "dni_frente";
                    _AJAX_nodeFoto = "img_dni_frente";
                    _AJAX_deprecated._init_page = "get-foto";
                    break;
                case "dni-dorso": //Captura dni dorso
                    _AJAX_deprecated._idFoto = 4;
                    _AJAX_descriptionFoto = "DNI dorso";
                    _AJAX_customFoto = "Tomá una foto del dorso de tu DNI";
                    _AJAX_scopeFoto = "dni_dorso";
                    _AJAX_nodeFoto = "img_dni_dorso";
                    _AJAX_deprecated._init_page = "get-foto";
                    break;
                case "foto-rostro": //Captura foto del rostro
                    _AJAX_deprecated._idFoto = 5;
                    _AJAX_descriptionFoto = "Rostro";
                    _AJAX_customFoto = "Tomá una foto de tu rostro";
                    _AJAX_scopeFoto = "foto_cara";
                    _AJAX_nodeFoto = "img_foto_cara";
                    _AJAX_deprecated._init_page = "get-foto";
                    break;
                case "foto-ingresos": //Captura foto de comprobante de ingresos
                    _AJAX_deprecated._idFoto = 2;
                    _AJAX_descriptionFoto = "Comprobante de ingresos";
                    _AJAX_customFoto = "Tomá una foto del último recibo de sueldo o haberes (si es quincenal, los últimos 2 y si cuenta con ingresos variables, los últimos 3 recibos)";
                    _AJAX_scopeFoto = "comprobante_ingreso";
                    _AJAX_nodeFoto = "img_comprobante_ingreso";
                    _AJAX_deprecated._init_page = "get-foto";
                    break;
                default:
                    _AJAX_deprecated._responseTitle = "¡Gracias por confiar en nosotros!";
                    _AJAX_deprecated._responseMessage = "Un operador lo contactará para terminar el proceso de emisión.";
                    _AJAX_deprecated._init_page = "msg-ok"; // Frena avisando que un operador lo contactará
                    break;
            }
            break;
        default:
            _AJAX_deprecated._responseTitle = "¡Gracias por confiar en nosotros!";
            _AJAX_deprecated._responseMessage = "Un operador lo contactará para terminar el proceso de emisión.";
            _AJAX_deprecated._init_page = "msg-ok"; // Frena avisando que un operador lo contactará
            break;
    }

    /** Init! NO PROCESA EL BLOQUE SI ESTAMOS EN MODO FIRMA DE DOCUMENTOS! **/
    /* toda esta logica controla si se puede o no firma y que acciones tomar en casos 
       particulares de respuesta de Idemia y/o cierre de verificaciones */
    if (_AJAX_deprecated._KEY != 0 && !_AJAX_deprecated._justSign) {
        var _val = { "id": _AJAX_deprecated._KEY, "idtx": _AJAX_deprecated._idtx, "decision": _AJAX_deprecated._decision, "externalid": _AJAX_deprecated._externalid, "end": "NAK" };
        _API_deprecated.UiOnboardingGetRequest(_val).then(function (data) {
            /* Asigna los datos obtenidos del request a la estructura interna */
            _F.onSetSolicitudData(data);

            /* Evalua si es respuesta de idemia y si es solamente una verificacion */
            if (_responseIdemia && parseInt(data.data.Tipo) == 17) {
                /* Bloque al finalizar el proceso de Idemia */
                _API_deprecated.UiOnboardingFinalIdVerification({ "id": _AJAX_deprecated._KEY }).then(function (data) {
                    _AJAX_deprecated._responseTitle = "¡Gracias por confiar en nosotros!";
                    _AJAX_deprecated._responseMessage = "Proceso de verificación de identidad finalizado.";
                    _AJAX_deprecated._init_page = "msg-ok";
                });
            } else {
                /* Si NO es respuesta de idemia Y No es solo una verificacion */
                if (data.status == "OK") {
                    var _bDecided = (data.data.decision == "HIT" || data.data.decision == "NO_HIT" || data.data.decision == "ERR_RNP");
                    if (data.data.controlPoint == "EMITIDO" || (_AJAX_deprecated._init_page == "idemia-paso-1" && _bDecided)) {
                        /* El link de firma ya fue utilizado y la firma enviada */
                        _AJAX_deprecated._responseMessage = "Link no disponible.  La operación referenciada ha finalizado.";
                        _AJAX_deprecated._init_page = "msg-error";
                    }
                } else {
                    /* Si la respuesta dada para el el Request no viene OK */
                    _AJAX_deprecated._responseMessage = data.message;
                    _AJAX_deprecated._init_page = "msg-error";
                }
            }
            _F.onTryPage(null, _AJAX_deprecated._init_page);
        }).catch(function (err) {
            /* Si se produce cualquier error no controlado! */
            _AJAX_deprecated._responseMessage = err.message;
            _F.onTryPage(null, "msg-error");
        });
    }
    if ((_AJAX_deprecated._KEY != 0 && _AJAX_deprecated._justSign) || _AJAX_deprecated._bforceGoto) {
        _F.onTryPage(null, _AJAX_deprecated._init_page);
    }
});
