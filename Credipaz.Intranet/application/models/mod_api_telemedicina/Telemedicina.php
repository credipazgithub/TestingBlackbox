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
            $ret = API_callAPIfields("/Mediya/GrillaMonitoreoTelemedicina/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function supervision($values){
        try {
            $ret = API_callAPI("/Mediya/GrillaChargesCodesTelemedicina/", null);
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
            $ret = API_callAPIfields("/Mediya/GrillaChargesCodesTelemedicina/", $fields);
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
            $ret = API_callAPIfields("/Mediya/CancelTelemedicina/", $fields);
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
            $ret = API_callAPIfields("/Mediya/SavePostCierre/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function solicitarambulancia($values){
        try {
            $values["Id"] = keySecureNumbers($values, "Id");
            if ($values["Id"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
            $values["Tipo"] = keySecureNumbers($values, "Tipo");
            if ($values["Tipo"] == "") {throw new Exception(lang("api_error_1069"), 1069);}

            $fields = ["Id_type" => $values["Tipo"],"Id"=>$values["Id"], "Descripcion"=>$values["Nota"]];
            $ret = API_callAPIfields("/Mediya/SolicitarAmbulancia/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function atencionesanteriores($values){
        try {
            $values["idSocio"] = keySecureNumbers($values, "idSocio");
            if ($values["idSocio"] == "0") {throw new Exception(lang("api_error_1070"), 1070);}
            $fields = ["Id" => $values["idSocio"]];
            $ret = API_callAPIfields("/Mediya/AtencionesAnteriores/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function mensajes($values){
        try {
            $values["idSocio"] = keySecureZero($values, "idSocio");
            if ($values["idSocio"] == 0) {$values["idSocio"]=null;}
            $values["idChargeCode"] = keySecureZero($values, "idChargeCode");
            if ($values["idChargeCode"] == 0) {$values["idChargeCode"]=null;}
            $values["idTypeDirection"] = keySecureValInArray($values, "idTypeDirection",['1','2']);
            if ($values["idTypeDirection"] == "") {throw new Exception(lang("api_error_1071"), 1071);}
            $values["idTypeItem"] = keySecureValInArray($values, "idTypeItem",['1','2']);
            if ($values["idTypeItem"] == "") {throw new Exception(lang("api_error_1072"), 1072);}
            $fields = ["Id_socio" => $values["idSocio"],"Id" => $values["idChargeCode"],"Id_clasificacion" => $values["idTypeDirection"],"Id_type" => $values["idTypeItem"]];
            $ret = API_callAPIfields("/Mediya/Mensajes/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
