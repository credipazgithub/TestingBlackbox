<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Telemedicina extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function monitoreo($values){
        try {
            $values["Id"] = keySecureZero($values, "Id");
            if ($values["Id"] == 0) {$values["Id"]=null;}
            $values["iModo"] = keySecureValInArray($values, "iModo",['1','2','3','4']);
            if ($values["iModo"] == "") {throw new Exception(lang("api_error_1066"), 1066);}

            $fields = ["Id_type" => $values["iModo"],"Id"=>$values["Id"]];
            $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ');
            $ret = API_callAPIfields("/Mediya/GrillaMonitoreoTelemedicina/", $headers, $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function supervision($values){
        try {
	        $headers = array('Content-Type:application/json','Authorization: Bearer ');
            $ret = API_callAPI("/Mediya/GrillaChargesCodesTelemedicina/", $headers, null);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function consultas($values){
        try {
            $values["idUser"] = keySecureNumbers($values, "idUser");
            if ($values["idUser"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
            $fields = ["idUser" => $values["idUser"]];
            $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ');
            $ret = API_callAPIfields("/Mediya/GrillaChargesCodesTelemedicina/", $headers, $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function cancelar($values){
        try {
            $values["Id"] = keySecureNumbers($values, "Id");
            if ($values["Id"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
            $fields = ["Id" => $values["Id"]];
            $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ');
            $ret = API_callAPIfields("/Mediya/CancelTelemedicina/", $headers, $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function postcierre($values){
        try {
            $values["Id"] = keySecureNumbers($values, "Id");
            if ($values["Id"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
            $values["Nota"]=keySecureString($values,"Nota");
            if ($values["Nota"] == "") {throw new Exception(lang("api_error_1068"), 1068);}
            $fields = ["Id" => $values["Id"], "Descripcion" => $values["Nota"]];
            $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ');
            $ret = API_callAPIfields("/Mediya/SavePostCierre/", $headers, $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
