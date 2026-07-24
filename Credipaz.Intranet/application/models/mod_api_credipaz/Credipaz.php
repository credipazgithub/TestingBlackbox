<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Credipaz extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function iniciarTransaccionPago($values){
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {throw new Exception(lang("api_error_1026"), 1026);}
            $values["Id_type_channel"] = keySecureZero($values, "Id_type_channel");
            if ($values["Id_type_channel"] == 0) {throw new Exception(lang("api_error_1050"), 1050);}
            $values["Identificacion"]=keySecureString($values,"Identificacion");
            if ($values["Identificacion"] == "") {throw new Exception(lang("api_error_1051"), 1051);}
            $values["Moneda"]=keySecureString($values,"Moneda");
            if ($values["Moneda"] == "") {throw new Exception(lang("api_error_1052"), 1052);}
            $values["Monto"]=keySecureString($values,"Monto");
            if ($values["Monto"] == "") {throw new Exception(lang("api_error_1053"), 1053);}
            $values["Raw_request"]=keySecureString($values,"Raw_request");
            if ($values["Raw_request"] == "") {throw new Exception(lang("api_error_1054"), 1054);}
            $values["Channel"]=keySecureValInArray($values, "Channel",['FSRV']);
            if ($values["Channel"] == "") {throw new Exception(lang("api_error_1055"), 1055);}
            $fields = array(
				'Code' => opensslRandom(8),
				'Description' => 'Pago vía agente externo',
				'Id_type_channel' => $values["Id_type_channel"],
				'Identificacion' => $values["Identificacion"],
				'Currency_request' => $values["Moneda"],
				'Dni_request' => (string)$values["NroDocumento"],
				'Amount_request' => $values["Monto"],
				'Raw_request' => $values["Raw_request"],
				'Channel' => $values["Channel"],
			);
            $headers = array('Content-Type:application/json','Authorization: Bearer ');
	        $ret = API_callAPI("/Pagos/IniciarTransaccion/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function consultarEstadoTransaccionPago($values){
        try {
            $values["IdTransaccion"] = keySecureZero($values, "IdTransaccion");
            if ($values["IdTransaccion"] == 0) {throw new Exception(lang("api_error_1056"), 1056);}
            $fields = array('IdTransaccion' => $values["IdTransaccion"]);
            $headers = array('Content-Type:application/json','Authorization: Bearer ');
	        $ret = API_callAPI("/Pagos/ConsultarTransaccion/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function terminarTransaccionPago($values){
        try {
            $values["IdTransaccion"] = keySecureZero($values, "IdTransaccion");
            if ($values["IdTransaccion"] == 0) {throw new Exception(lang("api_error_1056"), 1056);}
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {throw new Exception(lang("api_error_1026"), 1026);}
            $values["Status"]=keySecureString($values,"Status");
            if ($values["Status"] == "") {throw new Exception(lang("api_error_1063"), 1063);}
            $values["Moneda"]=keySecureString($values,"Moneda");
            if ($values["Moneda"] == "") {throw new Exception(lang("api_error_1052"), 1052);}
            $values["Monto"]=keySecureString($values,"Monto");
            if ($values["Monto"] == "") {throw new Exception(lang("api_error_1053"), 1053);}
            $values["Card_name"]=keySecureString($values,"Card_name");
            if ($values["Card_name"] == "") {throw new Exception(lang("api_error_1057"), 1057);}
            $values["Card_response"]=keySecureString($values,"Card_response");
            if ($values["Card_response"] == "") {throw new Exception(lang("api_error_1058"), 1058);}
            $values["Partial_card_number"] = keySecureZero($values, "Partial_card_number");
            if ($values["Partial_card_number"] == 0) {throw new Exception(lang("api_error_1059"), 1059);}
            $values["Message"]=keySecureString($values,"Message");
            if ($values["Message"] == "") {throw new Exception(lang("api_error_1060"), 1060);}
            $values["Raw_response"]=keySecureString($values,"Raw_response");
            if ($values["Raw_response"] == "") {throw new Exception(lang("api_error_1061"), 1061);}
            $values["Registro_externo"]=keySecureString($values,"Registro_externo");
            if ($values["Registro_externo"] == "") {throw new Exception(lang("api_error_1062"), 1062);}

            $fields = array(
				"Id"=>$values["IdTransaccion"],
                'Status' => $values["Status"],
                'Dni_response' => $values["NroDocumento"],
                'Currency_response' => $values["Moneda"],
                'Amount_response' => $values["Monto"],
                'Card_name' => $values["Card_name"],
                'Card_response' => $values["Card_response"],
                'Partial_card_number' => $values["Partial_card_number"],
                'Message' => $values["Message"],
                'Raw_response' => $values["Raw_response"],
                'Registro_externo' => $values["Registro_externo"],
			);
            $headers = array('Content-Type:application/json','Authorization: Bearer ');
	        $ret = API_callAPI("/Pagos/TerminarTransaccion/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function segmentosDeuda($values){
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {throw new Exception(lang("api_error_1026"), 1026);}
            $NroDocumento = (int) $values["NroDocumento"];
            $values["Sexo"] = keySecureValInArray($values, "Sexo",['F','M']);
            if ($values["Sexo"] == ""){$values["Sexo"]=null;}
            $values["Platform"]=keySecureString($values,"Platform");
            $values["Interface"]=keySecureString($values,"Interface");
            $values["Gateway"]=keySecureString($values,"Gateway");
            $values["Segmentos"]=keySecureString($values,"Segmentos");

            $fields=array(
                "Documento" => $NroDocumento,
                "Sexo" => $values["Sexo"],
                "Platform" => $values["Platform"],
                "Interface" => $values["Interface"],
                "Gateway" => $values["Gateway"],
                "Segmentos" => $values["Segmentos"]
            );
            $headers = array('Content-Type:application/json','Authorization: Bearer ');
	        $ret = API_callAPI("/Pagos/InterfaceSegmentosDeDeuda/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function resumenTarjeta($values){
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {throw new Exception(lang("api_error_1026"), 1026);}
            $NroDocumento = (int) $values["NroDocumento"];

            $values["Sexo"] = keySecureValInArray($values, "Sexo",['F','M']);
            if ($values["Sexo"] == ""){throw new Exception(lang("api_error_1002"), 1002);}

            $values["iYear"] = keySecureZero($values, "iYear");
            if ($values["iYear"] == 0) {throw new Exception(lang("api_error_1027"), 1027);}
            $iYear = (int) $values["iYear"];

            $values["iMonth"] = keySecureZero($values, "iMonth");
            if ($values["iMonth"] == 0) {throw new Exception(lang("api_error_1028"), 1028);}
            $iMonth = (int) $values["iMonth"];

            $fields=array(
                "NroDocumento" => $NroDocumento,
                "Sexo" => $values["Sexo"],
                "iYear" => $iYear,
                "iMont" => $iMonth
            );
            $headers = array('Content-Type:application/json','Authorization: Bearer ');
	        $ret = API_callAPI("/Cabal/Resumen/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function cedidos($values)
    {
        try {
            $NroDocumento = null;
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] != 0) {$NroDocumento = (int) $values["NroDocumento"];}
            $values["IdEntidad"] = keySecureZero($values, "IdEntidad");
            if ($values["IdEntidad"] == 0) {throw new Exception(lang("api_error_1049"), 1049);}

            $fields = array(
                "NroDocumento" => $NroDocumento,
                "Id_user_cedido" => $values["IdEntidad"],
                "Interno" => "N",
                "Download" => "false",
                "FechaCesion" => $values["Cesion"]
            );

            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Credito/GetCedido/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            $merged["data"] = $ret["records"];
            return $merged;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function cesiones($values)
    {
        try {
            $values["IdEntidad"] = keySecureZero($values, "IdEntidad");
            if ($values["IdEntidad"] == 0) {throw new Exception(lang("api_error_1049"), 1049);}

            $fields = array("Id_user_cedido" => $values["IdEntidad"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Credito/GetCesiones/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $merged["link"] = $ret["mensaje"];
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            $merged["data"] = $ret["records"];
            return $merged;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function archivo($values)
    {
        try {
            if (!isset($values["File"])) {$values["File"] = "";}
            if (!isset($values["Key"])) {$values["Key"] = "";}
            if ($values["File"] == "") {throw new Exception(lang("api_error_1030"), 1030);}
            if ($values["Key"] == "") {throw new Exception(lang("api_error_1029"), 1029);}
            $fields = array("RutaOrigen" => $values["Key"], "Archivo"=> $values["File"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $url="/Utilidades/BridgeFileGet?RutaOrigen=".$values["Key"]."&Archivo=". $values["File"];
            $ret = API_callAPIGet($url."&Archivo=". $values["File"], $headers, json_encode($fields));
            $ret = json_decode($ret, true);
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            $merged["base64"] = $ret["mensaje"];
            $merged["mime"] = "application/pdf";
            return $merged;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    public function infoCabalTitular($values)
    {
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {
                throw new Exception(lang("api_error_1026"), 1026);
            }
            $NroDocumento = (int) $values["NroDocumento"];

            $values["Sexo"] = keySecureValInArray($values, "Sexo",['F','M']);
            if ($values["Sexo"] == "") {throw new Exception(lang("api_error_1002"), 1002);}

            $fields = array("NroDocumento" => $NroDocumento, "Sexo"=> $values["Sexo"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');

            $ret = API_callAPI("/Cabal/GetRowsTarjeta/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);
            $i = 0;
            $merged["data"]=[];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["DNI"]=$ret["records"][$i]["nDoc"];
                $merged["data"][$i]["Nombre"]=$ret["records"][$i]["sNombre"];
                $merged["data"][$i]["Codigo"]=$ret["records"][$i]["sCodigo"];
                $merged["data"][$i]["PAN"]=$ret["records"][$i]["masked_pan"];
                $merged["data"][$i]["Habilitacion"]=$ret["records"][$i]["fHabilitacion"];
                $merged["data"][$i]["Activacion"]=$ret["records"][$i]["fActivacion"];
                $merged["data"][$i]["VigenteDesde"]=$ret["records"][$i]["fDesde"];
                $merged["data"][$i]["VigenteHasta"]=$ret["records"][$i]["fHasta"];
                $merged["data"][$i]["CodigoBaja"] = $ret["records"][$i]["nCodBaja"];
                $merged["data"][$i]["Adicionales"]=$ret["records"][$i]["nCantAdicionales"];
                $merged["data"][$i]["Estado"]=$ret["records"][$i]["sLKEstado"];
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function infoCabalAdicional($values)
    {
        try {
            $values["NroDocumentoT"] = keySecureZero($values, "NroDocumentoT");
            if ($values["NroDocumentoT"] == 0) {
                throw new Exception(lang("api_error_1034"), 1034);
            }
            $NroDocumentoT = (int) $values["NroDocumentoT"];
            
            $values["NroDocumentoA"] = keySecureZero($values, "NroDocumentoA");
            $NroDocumentoA = (int) $values["NroDocumentoA"];

            $values["SexoT"] = keySecureValInArray($values, "SexoT",['F','M']);
            if ($values["SexoT"] == "") {
                throw new Exception(lang("api_error_1036"), 1036);
            }
            $values["SexoA"] = keySecureValInArray($values, "SexoA",['F','M']);
            if ($values["SexoA"] == "") {
                throw new Exception(lang("api_error_1037"), 1037);
            }

            $fields = array(
                "NroDocumentoT" => $NroDocumentoT,
                "NroDocumentoA" => $NroDocumentoA,
                "SexoT" => $values["SexoT"],
                "SexoA" => $values["SexoA"]
            );
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');

            $ret = API_callAPI("/Cabal/GetRowsAdicionales/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $i = 0;
            $merged["data"] = [];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["DNI"] = $ret["records"][$i]["nDoc"];
                $merged["data"][$i]["Nombre"] = $ret["records"][$i]["sNombre"];
                $merged["data"][$i]["Codigo"] = $ret["records"][$i]["sCodigoTarjeta"];
                $merged["data"][$i]["PAN"] = $ret["records"][$i]["masked_pan"];
                $merged["data"][$i]["Habilitacion"] = $ret["records"][$i]["fHabilitacion"];
                $merged["data"][$i]["Parentesco"] = $ret["records"][$i]["Parentesco"];
                $merged["data"][$i]["Adicional"] = $ret["records"][$i]["nAdicional"];
                $merged["data"][$i]["VersionPlastico"] = $ret["records"][$i]["nVersionPlastico"];
                $merged["data"][$i]["Estado"] = $ret["records"][$i]["sLKEstado"];
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);

            return $merged;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function infoCabalTracking($values)
    {
        try {
            $values["Cuenta"] = keySecureNumbers($values, "Cuenta");
            if ($values["Cuenta"] == 0) {
                throw new Exception(lang("api_error_1033"), 1033);
            }
            $Cuenta = (int) $values["Cuenta"];

            $fields = array("Cuenta" => $Cuenta);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Cabal/GetRowsTarjetaTracking/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $i = 0;
            $merged["data"] = [];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["Codigo"] = $ret["records"][$i]["sCodigo"];
                $merged["data"][$i]["PAN"] = $ret["records"][$i]["masked_pan"];
                $merged["data"][$i]["Adicional"] = $ret["records"][$i]["nAdicional"];
                $merged["data"][$i]["Causa"] = $ret["records"][$i]["sCausa"];
                $merged["data"][$i]["Ubicacion"] = $ret["records"][$i]["sLKUbicacion"];
                $merged["data"][$i]["Situacion"] = $ret["records"][$i]["sLKSituacion"];
                $merged["data"][$i]["Lote"] = $ret["records"][$i]["nLote"];
                $merged["data"][$i]["LoteCorreo"] = $ret["records"][$i]["nLoteCorreo"];
                $merged["data"][$i]["Sucursal"] = $ret["records"][$i]["Sucursal"];
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);

            return $merged;
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function infoCabalLimites($values)
    {
        try {
            $values["Cuenta"] = keySecureNumbers($values, "Cuenta");
            if ($values["Cuenta"] == 0) {
                throw new Exception(lang("api_error_1033"), 1033);
            }
            $Cuenta = (int) $values["Cuenta"];

            $fields = array("Cuenta" => $Cuenta);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');

            $ret = API_callAPI("/Cabal/GetRowsTarjeta/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);
            $i = 0;
            $merged["data"] = [];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["DNI"] = $ret["records"][$i]["nDoc"];
                $merged["data"][$i]["Nombre"] = $ret["records"][$i]["sNombre"];
                $merged["data"][$i]["Codigo"] = $ret["records"][$i]["sCodigo"];
                $merged["data"][$i]["PAN"] = $ret["records"][$i]["masked_pan"];
                $merged["data"][$i]["Habilitacion"] = $ret["records"][$i]["fHabilitacion"];
                $merged["data"][$i]["Activacion"] = $ret["records"][$i]["fActivacion"];
                $merged["data"][$i]["VigenteDesde"] = $ret["records"][$i]["fDesde"];
                $merged["data"][$i]["VigenteHasta"] = $ret["records"][$i]["fHasta"];
                $merged["data"][$i]["CodigoBaja"] = $ret["records"][$i]["nCodBaja"];
                $merged["data"][$i]["Adicionales"] = $ret["records"][$i]["nCantAdicionales"];
                $merged["data"][$i]["Estado"] = $ret["records"][$i]["sLKEstado"];
                $merged["data"][$i]["LimiteCupones"] = $ret["records"][$i]["nLimiteCupones"];
                $merged["data"][$i]["LimiteCuotas"] = $ret["records"][$i]["nLimiteCuotas"];
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function infoCabalSaldos($values)
    {
        try {
            $values["Cuenta"] = keySecureNumbers($values, "Cuenta");
            if ($values["Cuenta"] == 0) {
                throw new Exception(lang("api_error_1033"), 1033);
            }
            $Cuenta = (int) $values["Cuenta"];

            $fields = array("Cuenta" => $Cuenta);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Cabal/GetRowsDatosCierre/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $i = 0;
            $merged["data"] = [];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["Vector"] = $ret["records"][$i]["sVector"];
                $merged["data"][$i]["SaldoAnterior"] = $ret["records"][$i]["nSaldoAnterior"];
                $merged["data"][$i]["PagoMinimoAnterior"] = $ret["records"][$i]["nPagoMinimoAnt"];
                $merged["data"][$i]["InteresesCompensatorios"] = $ret["records"][$i]["nIntCompesatorios"];
                $merged["data"][$i]["InteresesPunitorios"] = $ret["records"][$i]["nIntPunitorios"];
                $merged["data"][$i]["InteresFinanciero"] = $ret["records"][$i]["nIntFinanciero"];
                $merged["data"][$i]["InteresesFinanciacion"] = $ret["records"][$i]["nInteresFinanciecion"];
                $merged["data"][$i]["Pago"] = $ret["records"][$i]["nPago"];
                $merged["data"][$i]["Compras"] = $ret["records"][$i]["nCompras"];
                $merged["data"][$i]["AjustesSinIVA"] = $ret["records"][$i]["nAjustesSinIVA"];
                $merged["data"][$i]["AjustesConIVA"] = $ret["records"][$i]["nAjustesConIVA"];
                $merged["data"][$i]["SeguroOptativo"] = $ret["records"][$i]["nSeguro_Optativo"];
                $merged["data"][$i]["GastosAdministrativos"] = $ret["records"][$i]["nGastosADM"];
                $merged["data"][$i]["RiesgoDesembolsoEfectivo"] = $ret["records"][$i]["nRiesgoDesemEfvo"];
                $merged["data"][$i]["SeguroVida"] = $ret["records"][$i]["nSeguroVida"];
                $merged["data"][$i]["CargoResumen"] = $ret["records"][$i]["nCargoResumen"];
                $merged["data"][$i]["ServicioMedico"] = $ret["records"][$i]["nServicioMedico"];
                $merged["data"][$i]["Sellado"] = $ret["records"][$i]["nSellado"];
                $merged["data"][$i]["IVA"] = $ret["records"][$i]["nIVA"];
                $merged["data"][$i]["SaldoActual"] = $ret["records"][$i]["nSaldoActual"];
                $merged["data"][$i]["PagoMinimo"] = $ret["records"][$i]["nPagoMinimo"];
                $merged["data"][$i]["PagoSugerido"] = $ret["records"][$i]["nPagoSugerido"];
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function infoCabalPagosPeriodo($values)
    {
        try {
            $values["Cuenta"] = keySecureNumbers($values, "Cuenta");
            if ($values["Cuenta"] == 0) {
                throw new Exception(lang("api_error_1033"), 1033);
            }
            $Cuenta = (int) $values["Cuenta"];

            $values["iYear"] = keySecureZero($values, "iYear");
            if ($values["iYear"] == 0) {
                throw new Exception(lang("api_error_1027"), 1027);
            }
            $iYear = (int) $values["iYear"];

            $values["iMonth"] = keySecureZero($values, "iMonth");
            if ($values["iMonth"] == 0) {
                throw new Exception(lang("api_error_1028"), 1028);
            }
            $iMonth = (int) $values["iMonth"];

            $fields = array(
                "Cuenta" => $Cuenta,
                "iYear" => $iYear,
                "iMont" => $iMonth
            );
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Cabal/GetRowsPagosPeriodo/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $i = 0;
            $merged["data"] = [];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["Orden"] = $ret["records"][$i]["nOrden"];
                $merged["data"][$i]["Recaudadora"] = $ret["records"][$i]["sEntidadRecaudadora"];
                $merged["data"][$i]["Importe"] = $ret["records"][$i]["nImportePago"];
                $merged["data"][$i]["FechaPago"] = $ret["records"][$i]["fPago"];
                $merged["data"][$i]["Periodo"] = $ret["records"][$i]["Periodo"];
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function infoCabalUltimosConsumos($values)
    {
        try {
            $values["Cuenta"] = keySecureNumbers($values, "Cuenta");
            if ($values["Cuenta"] == 0) {
                throw new Exception(lang("api_error_1033"), 1033);
            }
            $Cuenta = (int) $values["Cuenta"];

            $fields = array("Cuenta" => $Cuenta);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Cabal/GetRowsUltimosMovimientos/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $i = 0;
            $merged["data"] = [];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["Transaccion"] = $ret["records"][$i]["nTransaccion"];
                $merged["data"][$i]["Codigo"] = $ret["records"][$i]["nTransaccionCodigo"];
                $merged["data"][$i]["Descripcion"] = $ret["records"][$i]["sTransaccionDesc"];
                $merged["data"][$i]["Autorizacion"] = $ret["records"][$i]["nCodigoAutorizacion"];
                $merged["data"][$i]["Cupon"] = $ret["records"][$i]["nCupon"];
                $merged["data"][$i]["Importe"] = $ret["records"][$i]["nImportePago"];
                $merged["data"][$i]["Cuotas"] = $ret["records"][$i]["nCuotas"];
                $merged["data"][$i]["Fecha"] = $ret["records"][$i]["fFecha"];
                $merged["data"][$i]["Verificacion"] = $ret["records"][$i]["fVerificacion"];
                $merged["data"][$i]["Proceso"] = $ret["records"][$i]["fProceso"];
                $merged["data"][$i]["Estado"] = $ret["records"][$i]["sLKEstado"];
                $merged["data"][$i]["Periodo"] = $ret["records"][$i]["Periodo"];
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function infoCabalFechasImportantes($values)
    {
        try {
            $values["Cuenta"] = keySecureNumbers($values, "Cuenta");
            if ($values["Cuenta"] == 0) {
                throw new Exception(lang("api_error_1033"), 1033);
            }
            $Cuenta = (int) $values["Cuenta"];

            $fields = array("Cuenta" => $Cuenta);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Cabal/GetRowsFechasImportantes/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $i = 0;
            $merged["data"] = [];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["Periodo"] = $ret["records"][$i]["Periodo"];
                $merged["data"][$i]["VencimientoPagoMinimo"] = $ret["records"][$i]["fVtoPagoMinimo"];
                $merged["data"][$i]["Ingreso"] = $ret["records"][$i]["fFechaIngreso"];
                $merged["data"][$i]["Habilitacion"] = $ret["records"][$i]["fFechaHabilitacion"];
                $merged["data"][$i]["VigenteDesde"] = $ret["records"][$i]["fVigDesde"];
                $merged["data"][$i]["VigenteHasta"] = $ret["records"][$i]["fVigHasta"];
                $merged["data"][$i]["Activacion"] = $ret["records"][$i]["fActivacion"];
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function infoCabalPagoLink($values)
    {
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {
                throw new Exception(lang("api_error_1026"), 1026);
            }
            $NroDocumento = (int) $values["NroDocumento"];

            $values["Sexo"] = keySecureValInArray($values, "Sexo",['F','M']);
            if ($values["Sexo"] == "") {
                throw new Exception(lang("api_error_1002"), 1002);
            }

            $fields = array("NroDocumento" => $NroDocumento, "Sexo"=> $values["Sexo"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');

            $ret = API_callAPI("/Cabal/GetLinkPago/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);
            if ($ret["mensaje"] == "") {$ret["mensaje"] = "Link no disponible para este DNI";}
            $merged["data"] = [];
            $merged["data"][0]["Link"] = $ret["mensaje"];
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    public function infoCreditoPagoLink($values)
    {
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {
                throw new Exception(lang("api_error_1026"), 1026);
            }
            $NroDocumento = (int) $values["NroDocumento"];

            $values["Sexo"] = keySecureValInArray($values, "Sexo",['F','M']);
            if ($values["Sexo"] == "") {
                throw new Exception(lang("api_error_1002"), 1002);
            }

            $fields = array("NroDocumento" => $NroDocumento, "Sexo" => $values["Sexo"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');

            $ret = API_callAPI("/Credito/GetLinkPago/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);
            if ($ret["mensaje"] == "") {
                $ret["mensaje"] = "Link no disponible para este DNI";
            }
            $merged["data"] = [];
            $merged["data"][0]["Link"] = $ret["mensaje"];
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function infoCredito($values)
    {
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {
                throw new Exception(lang("api_error_1026"), 1026);
            }
            $NroDocumento = (int) $values["NroDocumento"];

            $values["Sexo"] = keySecureValInArray($values, "Sexo",['F','M']);
            if ($values["Sexo"] == "") {
                throw new Exception(lang("api_error_1002"), 1002);
            }

            $fields = array("NroDocumento" => $NroDocumento, "Sexo" => $values["Sexo"], "sLKEstado" => "ACT");
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Credito/GetRowsCredito/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $i = 0;
            $merged["data"] = [];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["id"] = $ret["records"][$i]["IdSolicitud"];
                $merged["data"][$i]["credito"] = $ret["records"][$i]["nSolicitud"];
                $merged["data"][$i]["fecha"] = $ret["records"][$i]["dFechaAltaF"];
                $merged["data"][$i]["vencimiento1"] = $ret["records"][$i]["dVtoCuota1F"];
                $merged["data"][$i]["proximoVencimiento"] = $ret["records"][$i]["dVtoAPagarF"];
                $merged["data"][$i]["ultimoPago"] = $ret["records"][$i]["dUltimoPagoF"];
                $merged["data"][$i]["monto"] = $ret["records"][$i]["nMonto"];
                $merged["data"][$i]["deuda"] = $ret["records"][$i]["nDeuda"];
                $merged["data"][$i]["cuotas"] = $ret["records"][$i]["nCuotas"];
                $merged["data"][$i]["importeCuota"] = $ret["records"][$i]["nCuotasImporte"];
                $merged["data"][$i]["cuotasRetenidas"] = $ret["records"][$i]["nCuotasRetenidas"];
                $merged["data"][$i]["gastos"] = $ret["records"][$i]["nGastosImporte"];
                $merged["data"][$i]["seguro"] = $ret["records"][$i]["nSeguro"];
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function infoCreditoDeuda($values)
    {
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {throw new Exception(lang("api_error_1026"), 1026);}
            $NroDocumento = (int) $values["NroDocumento"];
    
            $values["Sexo"] = keySecureValInArray($values, "Sexo",['F','M']);
            if ($values["Sexo"] == "") {throw new Exception(lang("api_error_1002"), 1002);}

            $fields = array("NroDocumento" => $NroDocumento,"Sexo"=> $values["Sexo"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Credito/GetDeudaCredito/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $i = 0;
            $merged["data"] = [];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["credito"] = $ret["records"][$i]["Credito"];
                $merged["data"][$i]["codigoBarras"] = $ret["records"][$i]["Codigo_De_Barras"];
                $merged["data"][$i]["cuota"] = ($ret["records"][$i]["Cuota"]."/". $ret["records"][$i]["Cuotas"]);
                $merged["data"][$i]["importe1"] = $ret["records"][$i]["ImporteTotal1"];
                $merged["data"][$i]["importe2"] = $ret["records"][$i]["ImporteTotal2"];
                $merged["data"][$i]["gastosPagoElectronico"] = $ret["records"][$i]["GastosPagoElectronico"];
                $merged["data"][$i]["punitorios"] = $ret["records"][$i]["Cuota_Punitorios_Vto_2"];
                $merged["data"][$i]["Vencimiento1"] = date(FORMAT_DATE_DMYHMS, strtotime($ret["records"][$i]["FechaVto1"]));
                $merged["data"][$i]["Vencimiento2"] = date(FORMAT_DATE_DMYHMS, strtotime($ret["records"][$i]["FechaVto2"]));
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function infoCreditoCuotas($values)
    {
        try {
            /*aca se devuelven los movmeintos del credito*/
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {
                throw new Exception(lang("api_error_1026"), 1026);
            }
            $NroDocumento = (int) $values["NroDocumento"];

            $values["Sexo"] = keySecureValInArray($values, "Sexo",['F','M']);
            if ($values["Sexo"] == "") {
                throw new Exception(lang("api_error_1002"), 1002);
            }

            $fields = array("NroDocumento" => $NroDocumento, "Sexo" => $values["Sexo"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Credito/GetCuotasCredito/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $i = 0;
            $merged["data"] = [];
            foreach ($ret["records"] as $item) {
                $merged["data"][$i]["credito"] = $ret["records"][$i]["nSolicitud"];
                $merged["data"][$i]["cuota"] = ($ret["records"][$i]["nCuota"] . "/" . $ret["records"][$i]["nCuotas"]);
                $merged["data"][$i]["importeCapital"] = $ret["records"][$i]["nImporteCapital"];
                $merged["data"][$i]["importeIntereses"] = $ret["records"][$i]["nImporteIntereses"];
                $merged["data"][$i]["importeSeguro"] = $ret["records"][$i]["nImporteSeguro"];
                $merged["data"][$i]["importePago"] = $ret["records"][$i]["nImportePago"];
                $merged["data"][$i]["FechaPago"] = $ret["records"][$i]["dFechaPagoF"];
                $merged["data"][$i]["Vencimiento1"] = $ret["records"][$i]["dFechaVtoF"];
                $merged["data"][$i]["Vencimiento2"] = $ret["records"][$i]["dFechaVto2F"];
                $merged["data"][$i]["estado"] = $ret["records"][$i]["sLKEstado"];
                $i++;
            }
            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            return $merged;
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
}
