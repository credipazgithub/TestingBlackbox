<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Lookups extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function listar($values){
        try {
 	        $fields=array("Id"=>(int)$values["id_user_active"]);
	        $headers = array('Content-Type:application/json','Authorization: Bearer '.API_Authenticate());
	        $ret = API_callAPIGet(("/Lookups/GetLookUp/?Tipo=".$values["table"]),$headers,json_encode($fields));
	        $ret = json_decode($ret, true);

            $merged["code"]="200";
            $merged["error"]="";
            $merged["status"]="OK";
            $merged["timestamp"]=date(FORMAT_DATE);
            $merged["data"]=$ret;
            return $merged;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function planes($values)
    {
        try {
            if (isset($values["adicMayores"]) && $values["adicMayores"] != "") {
                $values["adicMayores"] = keySecureZero($values, "adicMayores");
                if ($values["adicMayores"] < 0) {
                    throw new Exception(lang("api_error_1024"), 1024);
                }
            }
            if (isset($values["adicMenores"]) && $values["adicMenores"] != "") {
                $values["adicMenores"] = keySecureZero($values, "adicMenores");
                if ($values["adicMenores"] < 0) {
                    throw new Exception(lang("api_error_1025"), 1025);
                }
            }
            $fields = array(
                "adicMayores" => (int) $values["adicMayores"],
                "adicMenores" => (int) $values["adicMenores"],
            );
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
            $ret = API_callAPI(("/Mediya/GetPlanes/"), $headers, json_encode($fields));
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
    public function beneficios($values)
    {
        try {
            if (isset($values["latitud"]) && $values["latitud"]!="") {
                $values["latitud"] = keySecureZero($values, "latitud");
                if ($values["latitud"] == 0) {throw new Exception(lang("api_error_1014"), 1014);}
            }
            if (isset($values["longitud"]) && $values["longitud"] != "") {
                $values["longitud"] = keySecureZero($values, "longitud");
                if ($values["longitud"] == 0) {throw new Exception(lang("api_error_1015"), 1015);}
            }
            $values["radio"] = keySecureZero($values, "radio");
            if ((int) $values["radio"] < 0) {throw new Exception(lang("api_error_1016"), 1016);}

            $fields = array(
                "latitud" => (string)$values["latitud"],
                "longitud" => (string)$values["longitud"],
                "radio" => (int) $values["radio"],
                "Segmento" => $values["table"],
            );
            switch ($values["table"]) {
                case "centrosMedicos":
                    if (isset($values["idTipoCentroMedico"])) {
                        $values["idTipoCentroMedico"] = keySecureZero($values, "idTipoCentroMedico");
                        if ($values["idTipoCentroMedico"] == 0) {throw new Exception(lang("api_error_1017"), 1017);}
                    }
                    $fields["id_type_category"] = (int) $values["idTipoCentroMedico"];
                    break;
            }

            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
            $ret = API_callAPI(("/Mediya/GetBeneficios/"), $headers, json_encode($fields));
            $ret = json_decode($ret, true);

            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            $merged["data"] = $ret;
            return $merged;

        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function comercializadoras($values)
    {
        try {
            $values["id_empresa"] = keySecureZero($values, "id_empresa");
            $fields = array(
                "Id" => (int) $values["id_empresa"],
            );
            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
            $ret = API_callAPI(("/Mediya/GetComercilizadoras/"), $headers, json_encode($fields));
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
    }}
