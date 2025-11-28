<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class RtCargaVirtual extends MY_Model {
    //Desarrollo y testing
    private $cargavirtualTest=array("server"=>"http://10.0.0.80:8088/rtcargavirtual/cargavirtual.asmx?wsdl","username"=>"wscredipaz","password"=>"wscredipaz2019*","idPtoVta"=>"54120");
   
    //Primaria produccion
    private $cargavirtual=array("server"=>"http://172.16.20.1:8080/rtcargavirtual/cargavirtual.asmx?wsdl","username"=>"credipsa","password"=>"credi1806","idPtoVta"=>"96045");
    private $cargavirtual_alt=array("server"=>"http://172.16.30.1:8080/rtcargavirtual/cargavirtual.asmx?wsdl","username"=>"credipsa","password"=>"credi1806","idPtoVta"=>"96045");

    public function __construct()
    {
        parent::__construct();
    }

    public function cargaVirtual($values){
        try {
            $id_user=$values["id_user_active"];
            $uid=null;
            $id_credipaz=$values["id_credipaz"];
            $cuenta=$values["cuenta"];
            $LOAD_MEDIAS=$this->createModel(MOD_BACKEND,"Load_medias","Load_medias");
            $load_media=$LOAD_MEDIAS->get(array("page"=>1,"where"=>"id=".$values["id_load_media"]));
            $type_media=$load_media["data"][0]["type_load_media"];
            $code=$load_media["data"][0]["code"];
            $importe=$values["importe"];
            $id_producto=184; //Default SUBE
            $nTransaccionCodigo=30105;//Default SUBE
            switch($type_media){
                case "SUBE":
                   $id_producto=184;
                   $nTransaccionCodigo=30105;
                   break;
                case "CLARO":
                   $id_producto=20;
                   $nTransaccionCodigo=30106;
                   break;
                case "MOVISTAR":
                   $id_producto=21;
                   $nTransaccionCodigo=30106;
                   break;
                case "PERSONAL":
                   $id_producto=23;
                   $nTransaccionCodigo=30106;
                   break;
                case "NEXTEL":
                   $id_producto=33;
                   $nTransaccionCodigo=30106;
                   break;
            }
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->cargavirtual["server"],$options);
            $soapClient->__setLocation($this->cargavirtual["server"]);
            $codeSimple=substr($code, -10);
            $transac=$codeSimple.date("YmdHis");
            $soapParams=array(
                "usuario" => $this->cargavirtual["username"],
                "clave" => $this->cargavirtual["password"],
                "idPtoVta" => $this->cargavirtual["idPtoVta"],
                "idVendedor" => 1, //Fijo o identificacion interna
                "canal" => 8, //Fijo venta retail
                "producto" => $id_producto, //Producto para recarga
                "nroTelefono" =>$codeSimple,
                "importe" => $importe,
                "nroTransaccion" => $transac,
            );
            $response = $soapClient->__soapCall("RecargaOnline", array($soapParams));
            $response=objectToArrayRecusive($response);
            $response["importe"]=$importe;
            $status=$response["RecargaOnlineResult"]["Estado"];
            $ok=false;
            switch($status) {
                case "CombinacionUsernamePasswordIncorrecta":
                    $response["RecargaOnlineResult"]["Estado"]="0001 - Falla en la autenticación.  Contacte a soporte.";
                    break;
                case "CanalSuspendidoInhabilitadoParaOperar":
                    $response["RecargaOnlineResult"]["Estado"]="0002 - Falla en el canal de operación.  Contacte a soporte.";
                    break;
                case "ErrorDeAplicacion":
                    /*Utilizar el Nro transacción para anular la carga*/
                    $this->anularCargaVirtual($type_media,$response["RecargaOnlineResult"]["NroTransaccion"],$importe,$id_user,$uid,$id_credipaz);
                    $response["RecargaOnlineResult"]["Estado"]="0003 - Falla en la comunicación.  Reintente.";
                    break;
                case "NumeroDeLineaInexistente":
                    $response["RecargaOnlineResult"]["Estado"]="0004 - La tarjeta provista, no es válida.  Reintente con otra tarjeta.";
                    break;
                case "ElAbonadoNoEstaEnCondicionesDeRecibirSaldo":
                    $response["RecargaOnlineResult"]["Estado"]="0005 - Falla en las condiciones de recepción.  Contacte a soporte.";
                    break;
                case "MontoARecargarNoSuperaElMinimoPermitido":
                    $response["RecargaOnlineResult"]["Estado"]="0006 - El monto mínimo no es correcto.  Reintente.";
                    break;
                case "MontoARecargarSuperaElMaximoPermitido":
                    $response["RecargaOnlineResult"]["Estado"]="0007 - El monto máximo no es correcto.  Reintente.";
                    break;
                case "NoTieneSaldoDisponible":
                    $response["RecargaOnlineResult"]["Estado"]="0008 - No tiene saldo disponible.";
                    break;
                case "FormatoDeTransaccionInvalida":
                    $response["RecargaOnlineResult"]["Estado"]="0009 - Falla en el formato de transacción.  Contacte a soporte.";
                    break;
                case "TransaccionInexistente":
                    $response["RecargaOnlineResult"]["Estado"]="0010 - Falla en la transacción.  Contacte a soporte.";
                    break;
                case "ParametrosDeTransaccionInconsistente":
                    $response["RecargaOnlineResult"]["Estado"]="0011 - Falla de inconsistencia.  Contacte a soporte.";
                    break;
                case "TransaccionImposibleDeReversar":
                    $response["RecargaOnlineResult"]["Estado"]="0012 - Falla de reversión.  Contacte a soporte.";
                    break;
                case "TiempoDeReversaExpirado":
                    $response["RecargaOnlineResult"]["Estado"]="0013 - Falla de tiempo de expiración.  Contacte a soporte.";
                    break;
                case "NoSePuedeCalcularElVencimientoDelSaldoAAsignarAlAbonado":
                    $response["RecargaOnlineResult"]["Estado"]="0014 - Falla de cálculo de vencimiento.  Contacte a soporte.";
                    break;
                case "NumeroDeTransaccionDuplicada":
                    $response["RecargaOnlineResult"]["Estado"]="0015 - Falla de duplicidad.  Contacte a soporte.";
                    break;
                case "ProveedorNoHabilitado":
                    $response["RecargaOnlineResult"]["Estado"]="0016 - Falla de habilitación.  Contacte a soporte.";
                    break;
                case "CanalIncorrecto":
                    $response["RecargaOnlineResult"]["Estado"]="0017 - Falla de canal.  Contacte a soporte.";
                    break;
                case "TransaccionPendienteAcreditacion":
                    $response["RecargaOnlineResult"]["Estado"]="0018 - Existe transacción pendiente de acreditación.";
                    break;
                case "TransaccionDuplicada":
                    $response["RecargaOnlineResult"]["Estado"]="0019 - Falla de duplicidad.  Contacte a soporte.";
                    break;
                case "TransaccionProcesada":
                    $response["RecargaOnlineResult"]["Estado"]="0020 - Falla de proceso.  Contacte a soporte.";
                    break;
                case "ProveedorNoDisponible":
                    $response["RecargaOnlineResult"]["Estado"]="0021 - Falla de disponibilidad.  Contacte a soporte.";
                    break;
                case "TelefonoInhabilitadoPorSeguridad":
                    $response["RecargaOnlineResult"]["Estado"]="0022 - No puede procesar cargas consecutivas en una misma tarjeta, por razones de seguridad.  Reintente en al menos 10 minutos.";
                    break;
                case "Exito":
                    $ok=true;
                    break;
                default:
                    $response["RecargaOnlineResult"]["Estado"]="0023 - No se pudo procesar la carga.  Reintente en algunas horas.";
                    break;
            }
            $values=array("id"=>$transac,"id_user_active"=>0);
            $custom_trace=array(
                "raw_request"=>htmlspecialchars($soapClient->__getLastRequest(), ENT_QUOTES),
                "raw_response"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "status"=>"Carga: ".$transac,
                "importe"=>($importe*-1),
                "uid"=>$uid,
                "id_user"=>$id_user,
                "id_credipaz"=>$id_credipaz
            );
            $save=logGeneral($this,$values,__METHOD__,$custom_trace);

            if(!$ok){
                $response["RecargaOnlineResult"]["Estado"]="WS - Error ".$response["RecargaOnlineResult"]["Estado"];
            } else {
                $values=array(
                    "nOrigenEntidad"=>1,
                    "nOrigenSucursal"=>100,
                    "nOrigenRubro"=>0,
                    "nOrigenComercio"=>0,
                    "nOrigenDV"=>0,
                    "sOrigenDenominacion"=>"",
                    "sPAN"=>"",
                    "sCodigoTarjeta"=>$cuenta,
                    "nCuentaMiembro"=>0,
                    "nCodigoRed"=>1,
                    "nTransaccionCodigo"=>$nTransaccionCodigo,
                    "sTransaccionDesc"=>"RECARGA ". $type_media,
                    "dTransaccionFecha"=>date("Y-m-d"),
                    "nImporte"=>$importe,
                    "nCuotas"=>1,
                    "nCodigoAutorizacion"=>0,
                    "nCupon"=>$response["RecargaOnlineResult"]["NroTransaccion"],
                    "dLiquidacionFecha"=>date("Y-m-d H:i:s"),
                    "sLiquidacionPeriodo"=>"",
                    "sPlanFinanciacion"=>"",
                    "nLote"=>0,
                    "nTransaccion"=>$save["data"]["id"],
                    "lProcesado"=>0,
                    "dFechaProceso"=>null,
                    "lVerificado"=>0,
                    "dFechaVerificacion"=>null,
                    "sLKEstado"=>"VIG",
                    "sObservaciones"=>("CARGA:". $type_media),
                    "sAudAltaUsuario"=> $type_media,
                    "dAudAltaFecha"=>date("Y-m-d H:i:s"),
                    "sAudModiUsuario"=>null,
                    "dAudModiFecha"=>null,
                    "nCargoTotal"=>0
                );
                $TARMOVIMIENTO=$this->createModel(MOD_DBCENTRAL,"tarMovimiento","dbCentral.dbo.tarMovimiento");
                $TARMOVIMIENTO->save(array("id"=>0,$values));
                $values=array(
                    "Fecha"=>date("Y-m-d H:i:s"),
                    "Producto"=>"Carga Elect. ". $type_media,
                    "TransaccionCP"=>$response["RecargaOnlineResult"]["NroTransaccion"],
                    "TransaccionCV"=>$transac,
                    "Identificador"=>$codeSimple,
                    "Importe"=>$importe,
                    "IdCliente"=>$id_credipaz,
                    "Cuenta"=>$cuenta
                );
                
                $LOGCARGAVIRTUAL=$this->createModel(MOD_DBCENTRAL,"logCargaVirtual","dbCentral.dbo.LogCargaVirtual");
                $LOGCARGAVIRTUAL->save(array("id"=>0,$values));
            }
            $status="OK";
            if ($response["RecargaOnlineResult"]["Estado"]=="Exito") {
                $mode="TICKET";
                $msg="Hemos procedido a efectuar la recarga solicitada<br/>";
                $msg.="<b>Recuerde que debe acreditar la carga realizada en cualquier Terminal Automática, dispositivo de Conexión Móvil o desde la app Carga SUBE</b>";
                $ticket=array(
                    "transaccion"=>$response["RecargaOnlineResult"]["NroTransaccion"],
                    "importe"=>$response["importe"],
                    "image"=>("img/".$type_media.".png"),
                    "fecha"=>date("Y-m-d"),
                    "hora"=>date("H:i:s"),
                    "datos"=>$msg
                );
                $saveToTicket=$ticket;
                $saveToTicket["datos"]="";
                $values=array(
                    "code"=>$type_media,
                    "description"=>("Carga de ".$type_media),
                    "created"=>$this->now,
                    "verified"=>$this->now,
                    "fum"=>$this->now,
                    "id_user"=>$id_user,
                    "json"=>json_encode($saveToTicket)
                );
                $TICKETS=$this->createModel(MOD_BACKEND,"Tickets","Tickets");
                $TICKETS->save(array("id"=>0,$values));
            } else {
                $status="ERROR";
                $result="No se ha podido efectuar la recarga<br/>";
                $result.="El servicio a informado:<b>".$response["RecargaOnlineResult"]["Estado"]."</b>";
            }

            return array(
               "status"=>$status,
               "mode"=>$mode,
               "result"=>$result,
               "ticket">=$ticket,
               "msg"=>$msg
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    private function anularCargaVirtual($type_load,$transac,$importe,$id_user,$uid,$id_credipaz){
        try {
            $id_producto=184; //Default SUBE
            switch($type_load){
                case "SUBE":
                   $id_producto=184;
                   break;
                case "CLARO":
                   $this->cargavirtual=$this->cargavirtualTest;
                   $id_producto=20;
                   break;
                case "MOVISTAR":
                   $this->cargavirtual=$this->cargavirtualTest;
                   $id_producto=21;
                   break;
                case "PERSONAL":
                   $this->cargavirtual=$this->cargavirtualTest;
                   $id_producto=23;
                   break;
                case "NEXTEL":
                   $this->cargavirtual=$this->cargavirtualTest;
                   $id_producto=33;
                   break;
            }
            /*
            Verificar con cUrl si la .10. esta activa.
            Si viene 200 en el response, no cambiar nada si no, setear la .20.
            -Aca va el cambio de red, por la vpn alternativa!!!!!!
            */
            $http_code=cUrlStatusCode($url);
            if ((string)$http_code!="200"){$this->cargavirtual=$this->cargavirtual_alt;}
            /*---------------------*/

            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->cargavirtual["server"],$options);
            $soapClient->__setLocation($this->cargavirtual["server"]);
            $soapParams=array(
                "usuario" => $this->cargavirtual["username"],
                "clave" => $this->cargavirtual["password"],
                "idPtoVta" => $this->cargavirtual["idPtoVta"],
                "canal" => 8, //Fijo venta retail
                "producto" => $id_producto, //Producto para recarga
                "nroTransaccion" => $transac,
            );
            $response = $soapClient->__soapCall("AnulacionRecargaOnline", array($soapParams));
            $response=objectToArrayRecusive($response);
            $values=array("id"=>$transac,"id_user_active"=>0);
            $custom_trace=array(
                "raw_request"=>htmlspecialchars($soapClient->__getLastRequest(), ENT_QUOTES),
                "raw_response"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "status"=>"Anular: ".$transac,
                "importe"=>($importe*-1),
                "uid"=>$uid,
                "id_user"=>$id_user,
                "id_credipaz"=>$id_credipaz
            );
            //logGeneral($this,$values,__METHOD__,$custom_trace);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }
    private function cUrlRestfulPost($url,$headers, $fields=null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        if (is_array($headers)) {curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);}
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $jsonResponse = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        $response = $jsonResponse;
        return $response;
    }
}
