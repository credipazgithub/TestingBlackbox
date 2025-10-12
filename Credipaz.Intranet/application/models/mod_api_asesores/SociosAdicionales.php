<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class SociosAdicionales extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function listar($values){
        try {
            $values["id_socio"] = keySecureZero($values, "id_socio");
            if ($values["id_socio"] == 0) {throw new Exception(lang("api_error_1011"), 1011);}
            $fields = array("Id" => (int) $values["id_socio"]);
            $headers = array('Content-Type:application/json','Authorization: Bearer '.API_Authenticate());
	        $ret = API_callAPI("/Mediya/GetAdicionales/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);

            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function alta($values){
        try {
            $values["id_socio"] = keySecureZero($values, "id_socio");
            if ($values["id_socio"] == 0) {throw new Exception(lang("api_error_1011"), 1011);}
    
            $values["id_tipo_adicional"] = keySecureZero($values, "id_tipo_adicional");
            if ($values["id_tipo_adicional"] == 0) {throw new Exception(lang("api_error_1012"), 1012);}

            $values["id_parentesco"] = keySecureZero($values, "id_parentesco");
            if ($values["id_parentesco"] == 0) {throw new Exception(lang("api_error_1013"), 1013);}

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
            $fields=array(
                 "IDSocio"=>(int)$values["id_socio"],
                 "IDFamiliar"=>0,
                 "IdTipoAdicional" => (int) $values["id_tipo_adicional"],
                 "Nombre"=>(string)$values["nombre"],//
                 "Apellido"=>(string)$values["apellido"],//
                 "NroDocumento"=>(int)$values["dni"],//
                 "Sexo"=>(string)$values["sexo"],//
                 "IDParentesco" => (int) $values["id_parentesco"],
                 "FechaNacimiento" => date(FORMAT_DATE_DB, strtotime($values["fechaNacimiento"])),
                 "AreaTelefono" => (string) $values["area"],//
                 "Telefono" => (string) $values["telefono"],//
                 "sEmail"=>(string)$values["email"],//
                 "Username"=>(string)$values["username"],
            );
	        $headers = array('Content-Type:application/json','Authorization: Bearer '.API_Authenticate());
	        $ret = API_callAPI("/Mediya/SetAdicional/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
           
            $ret["timestamp"]=date(FORMAT_DATE);
            return $ret;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
