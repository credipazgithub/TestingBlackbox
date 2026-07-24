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
            $values["iModo"] = keySecureValInArray($values, "iModo",['1','2','3']);
            if ($values["iModo"] == "") {throw new Exception(lang("api_error_1066"), 1066);}
            $fields = ["iModo" => $values["iModo"]];
            $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ');
            $ret = API_callAPIfields("/Mediya/GrillaMonitoreoTelemedicina/", $headers, $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
