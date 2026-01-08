<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Socios extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function listar($values){
        try {
            if(!isset($values["segmento"])){$values["segmento"]="";}
            switch($values["segmento"]){
               case "pendientes":
               case "activos":
               case "caidos":
               case "candidatos":
                    $fields=array("Id_externo"=>(int)$values["id_user_active"],"segmento"=>$values["segmento"]);
                  break;
               case "listar":
               case "profile":
                  $values["dni"]=keySecureZero($values,"dni");
                  if ($values["dni"]==0){throw new Exception(lang("api_error_1001"),1001);}

                  if(!isset($values["sexo"])){$values["sexo"]="";}
                  $values["sexo"]=strtoupper($values["sexo"]);
                  $fields=array(
                      "Id_externo"=>(int)$values["id_user_active"],
                      "segmento"=>$values["segmento"],
                      "NroDocumento"=>$values["dni"],
                      "Sexo"=>$values["sexo"]
                  );
                  break;
               default:
                  throw new Exception(lang("api_error_1000"),1000);
            }

            $eval=API_EsFuncional((int)$values["id_user_active"]);
            $first=array();
            $first["data"]["habilitado"]=$eval["habilitado"];
            $first["data"]["detalle"]=$eval["detalle"];
            
	        $headers = array('Content-Type:application/json','Authorization: Bearer ');
	        $ret = API_callAPI("/Mediya/GetRowsAsesoresSocios/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
            $second["code"]=$ret["codigo"];
            $second["error"]=$ret["error"];
            $second["status"]=$ret["estado"];
            $second["message"]=$ret["mensaje"];
            $second["function"]=$ret["function"];
            $second["timestamp"]=date(FORMAT_DATE);
            $ret["data"]["records"]=$ret["records"];
            $merged["data"]=array_merge($first["data"],$ret["data"]);
            $last=array_merge($second,$merged);

            return $last;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function profile($values)
    {
        try {
            $values["dni"] = keySecureZero($values, "dni");
            if ($values["dni"] == 0) {
                throw new Exception(lang("api_error_1001"), 1001);
            }
            if (!isset($values["sexo"])) {
                $values["sexo"] = "";
            }
            $values["sexo"] = strtoupper($values["sexo"]);
            $fields = array(
                "NroDocumento" => $values["dni"],
                "Sexo" => $values["sexo"]
            );

            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');
            $ret = API_callAPI("/Mediya/GetProfileSocio/", $headers, json_encode($fields));
            $ret = json_decode($ret, true);
            $second["code"] = $ret["codigo"];
            $second["error"] = $ret["error"];
            $second["status"] = $ret["estado"];
            $second["message"] = $ret["mensaje"];
            $second["function"] = $ret["function"];
            $second["timestamp"] = date(FORMAT_DATE);
            $ret["data"]["records"] = $ret["records"];
            $last = array_merge($second, $ret["data"]);

            return $last;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function altaModificar($values){
        try {
            $idSocio=(int)$values["idSocio"];

            $values["dni"]=keySecureZero($values,"dni");
            if ($values["dni"]==0){throw new Exception(lang("api_error_1001"),1001);}

            if(!isset($values["sexo"])){$values["sexo"]="";}
            $values["sexo"]=strtoupper($values["sexo"]);
            switch($values["sexo"]){
               case "M":
               case "F":
                  break;
               default:
                  throw new Exception(lang("api_error_1002"),1002);
            }

            $values["area"]=keySecureZero($values,"area");
            if ($values["area"]==0){throw new Exception(lang("api_error_1003"),1003);}

            $values["telefono"]=keySecureZero($values,"telefono");
            if ($values["telefono"]==0){throw new Exception(lang("api_error_1004"),1004);}

            if (!(bool)strtotime($values["fechaNacimiento"])){throw new Exception(lang("api_error_1006"),1006);}

            $eval=API_EsFuncional((int)$values["id_user_active"]);
            $first=array();
            $first["data"]["habilitado"]=$eval["habilitado"];
            $first["data"]["detalle"]=$eval["detalle"];

            $address=((string)$values["calle"]." ".(string)$values["numeroPuerta"].", ".(string)$values["codigoPostal"].", ".(string)$values["Provincia"].", Argentina");
            $PLACES=$this->createModel(MOD_PLACES,"Places","Places");
            $dataGeo=$PLACES->getReverse(array("address"=>$address));

            $fields=array(
                 "IDSocio"=>$idSocio,
                 "IDCliente"=>0,
                 "Nombre"=>(string)$values["nombre"],//
                 "Apellido"=>(string)$values["apellido"],//
                 "NroDocumento"=>(int)$values["dni"],//
                 "Sexo"=>(string)$values["sexo"],//
                 "Email"=>(string)$values["email"],//
                 "AreaTelefono"=>(string)$values["area"],//
                 "Telefono"=>(string)$values["telefono"],//
                 "FechaNacimiento"=>date(FORMAT_DATE_DB, strtotime($values["fechaNacimiento"])),
                 "CUIL"=>(string)$values["cuil"],
                 "Calle"=>(string)$values["calle"],
                 "Numeracion"=>(string)$values["numeroPuerta"],
                 "Piso"=>(string)$values["piso"],
                 "DptoOficLoc"=>(string)$values["departamento"],
                 "Torre"=>(string)$values["torre"],
                 "CodigoPostal"=>(string)$values["codigoPostal"],
                 "Provincia"=>(string)$values["provincia"],
                 "Localidad"=>(string)$values["localidad"],
                 
                 "IDEmpresa"=>0,
                 "IDVendedor"=>0, //Se resuelve utilizando el id_user_active enviado al stored en idAsesor
                 "IDEmpresario"=>0, //Se resuelve en el stored
                 "IDCartera"=>1, 
                 "IDSucursal"=>100,
                 "IDCanal"=>1, 
                 "IDListaPrecio"=>1, 
                 
                 "Latitud"=>(string)$dataGeo["lat"],
                 "Longitud"=>(string)$dataGeo["lng"],
                 
                 "Identificacion"=>(string)$values["identificacion"],
                 "Marca"=>(string)$values["marca"],
                 "PAN"=>(string)$values["pan"],
                 "PANSocio"=>(string)$values["panSocio"],
                 "NombreTarjeta"=>(string)$values["nombreEnTarjeta"],
                 "MesVTO"=>(int)$values["mesVencimiento"],
                 "AnioVTO"=>(int)$values["anioVencimiento"],
                 "Username"=>(string)$values["username"],
                 "IdAsesor"=>(int)$values["id_user_active"]
            );
            if (isset($values["id_nacionalidad"]) && is_nan($values["id_nacionalidad"])) {throw new Exception(lang("api_error_1018"), 1018);}
            if (isset($values["id_estado_civil"]) && is_nan($values["id_estado_civil"])) {throw new Exception(lang("api_error_1019"), 1019);}
            if (isset($values["id_ocupacion"]) && is_nan($values["id_ocupacion"])) {throw new Exception(lang("api_error_1020"), 1020);}
            if (isset($values["id_modo_pago"]) && is_nan($values["id_modo_pago"])) {throw new Exception(lang("api_error_1021"), 1021);}
            $fields["IDNacionalidad"]=(int)$values["id_nacionalidad"];
            $fields["IDEstadoCivil"]=(int)$values["id_estado_civil"];
            $fields["IDOcupacion"]=(int)$values["id_ocupacion"];
            $fields["IDModoPago"]=(int)$values["id_modo_pago"];

            $headers = array('Content-Type:application/json','Authorization: Bearer ');
            
            /*Solo si es ALTA ! Paso 1 segun respuesta sale por error o continua*/
            if ($idSocio == 0) {
                $ret = API_callAPI("/Mediya/VerificarReglasAlta/", $headers, json_encode($fields));
                $ret = json_decode($ret, true);
                if ($ret["codigo"] != "200") {throw new Exception($ret["mensaje"], $ret["codigo"]);}
            }
            
            $ret = API_callAPI("/Mediya/SetTitular/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
           
            $ret["timestamp"]=date(FORMAT_DATE);
            return $ret;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function infoMediyaPagoLink($values)
    {
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {
                throw new Exception(lang("api_error_1026"), 1026);
            }
            $NroDocumento = (int) $values["NroDocumento"];

            $fields = array("NroDocumento" => $NroDocumento);
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ');

            $ret = API_callAPI("/Mediya/GetLinkPago/", $headers, json_encode($fields));
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
}
