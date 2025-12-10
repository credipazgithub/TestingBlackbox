<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Credipaz extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function resumenTarjeta($values){
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {throw new Exception(lang("api_error_1026"), 1026);}
            $NroDocumento = (int) $values["NroDocumento"];

            $values["Sexo"] = keySecureSexo($values, "Sexo");
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
            $headers = array('Content-Type:application/json','Authorization: Bearer '.API_Authenticate());
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

            //$values["Sexo"] = keySecureSexo($values, "Sexo");
            //if ($values["Sexo"] == "") {
            //    throw new Exception(lang("api_error_1002"), 1002);
            //}
            $fields = array(
                "NroDocumento" => $NroDocumento,
                "Sexo" => $values["Sexo"],
                "Id_user" => $values["id_user_active"],
                "Interno" => $values["interno"],
                "Download" => ($values["download"]=="true"),
                "FechaCesion" => $values["FechaCesion"]
            );


            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
            $ret = API_callAPI("/Credito/GetCedido/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            if ($NroDocumento != null) {
                $i = 0;
                foreach ($ret["records"] as $item) {
                    $idSolicitud = (int) $item["IdSolicitud"];
                    $idTransaccion = (int) $item["IdentificadorInformes"];
                    $idRequest = (int) $item["IdRequest"];
                    $fcd = array("idTransaccion" => $idTransaccion, "idRequest" => $idRequest, "dni" => (string) $NroDocumento, "segmento" => (string) $idSolicitud);
                    $url = ("/Utilidades/TraerCarpetaDigitalGet?_skip=true&idTransaccion=" . $idTransaccion . "&idRequest=" . $idRequest . "&dni=" . (string) $NroDocumento . "&segmento=" . (string) $idSolicitud);
                    $retCarpeta = API_callAPIGet($url, $headers, json_encode($fcd));
                    $retCarpeta = json_decode($retCarpeta, true);

                    $i2 = 0;
                    foreach ($retCarpeta["records"] as $item) {
                        $retCarpeta["records"][$i2]["title"] = $retCarpeta["records"][$i2]["filename"];
                        $retCarpeta["records"][$i2]["filename"] = $retCarpeta["records"][$i2]["fullFilename"];
                        $retCarpeta["records"][$i2]["key"] = base64_encode($retCarpeta["records"][$i2]["path"]);

                        unset($retCarpeta["records"][$i2]["fullFilename"]);
                        unset($retCarpeta["records"][$i2]["created"]);
                        unset($retCarpeta["records"][$i2]["mimeType"]);
                        unset($retCarpeta["records"][$i2]["path"]);
                        unset($retCarpeta["records"][$i2]["size"]);

                        $fcd = array("RutaOrigen" => base64_decode($retCarpeta["records"][$i2]["key"]), "Archivo" => $retCarpeta["records"][$i2]["title"]);
                        $retFile = API_callAPI("/Utilidades/BridgeFilePost/", $headers, json_encode($fields));
                        $retFile = json_decode($retFile, true);

                        $i2++;
                    }
                    $ret["records"][$i]["CarpetaDigital"] = $retCarpeta["records"];
                    $i++;
                }
            }
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
    public function cesiones($values)
    {
        try {
            $fields = array("Id_user" => $values["id_user_active"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
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
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
            $ret = API_callAPIGet("/Utilidades/BridgeFileGet?RutaOrigen=".$values["Key"]."&Archivo=". $values["File"], $headers, json_encode($fields));
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

            $values["Sexo"] = keySecureSexo($values, "Sexo");
            if ($values["Sexo"] == "") {throw new Exception(lang("api_error_1002"), 1002);}

            $fields = array("NroDocumento" => $NroDocumento, "Sexo"=> $values["Sexo"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());

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

            $values["SexoT"] = keySecureSexo($values, "SexoT");
            if ($values["SexoT"] == "") {
                throw new Exception(lang("api_error_1036"), 1036);
            }
            $values["SexoA"] = keySecureSexo($values, "SexoA");
            if ($values["SexoA"] == "") {
                throw new Exception(lang("api_error_1037"), 1037);
            }

            $fields = array(
                "NroDocumentoT" => $NroDocumentoT,
                "NroDocumentoA" => $NroDocumentoA,
                "SexoT" => $values["SexoT"],
                "SexoA" => $values["SexoA"]
            );
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());

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
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
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
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());

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
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
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
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
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
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
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
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
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

            $values["Sexo"] = keySecureSexo($values, "Sexo");
            if ($values["Sexo"] == "") {
                throw new Exception(lang("api_error_1002"), 1002);
            }

            $fields = array("NroDocumento" => $NroDocumento, "Sexo"=> $values["Sexo"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());

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

            $values["Sexo"] = keySecureSexo($values, "Sexo");
            if ($values["Sexo"] == "") {
                throw new Exception(lang("api_error_1002"), 1002);
            }

            $fields = array("NroDocumento" => $NroDocumento, "Sexo" => $values["Sexo"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());

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

            $values["Sexo"] = keySecureSexo($values, "Sexo");
            if ($values["Sexo"] == "") {
                throw new Exception(lang("api_error_1002"), 1002);
            }

            $fields = array("NroDocumento" => $NroDocumento, "Sexo" => $values["Sexo"], "sLKEstado" => "ACT");
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
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
    
            $values["Sexo"] = keySecureSexo($values, "Sexo");
            if ($values["Sexo"] == "") {throw new Exception(lang("api_error_1002"), 1002);}

            $fields = array("NroDocumento" => $NroDocumento,"Sexo"=> $values["Sexo"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
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

            $values["Sexo"] = keySecureSexo($values, "Sexo");
            if ($values["Sexo"] == "") {
                throw new Exception(lang("api_error_1002"), 1002);
            }

            $fields = array("NroDocumento" => $NroDocumento, "Sexo" => $values["Sexo"]);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
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
