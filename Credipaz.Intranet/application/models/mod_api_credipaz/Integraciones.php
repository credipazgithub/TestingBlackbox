<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Integraciones extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function GetClientsByDocument($values){
        try {
            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {throw new Exception(lang("api_error_1026"), 1026);}
            $NroDocumento = (int) $values["NroDocumento"];

            if (isset($values["Sexo"])) {
                if ($values["Sexo"] != "") {
                    $values["Sexo"] = keySecureSexo($values, "Sexo");
                    if ($values["Sexo"] == "") {
                        throw new Exception(lang("api_error_1002"), 1002);
                    }
                }
            }

            $values["email"] = keySecureString($values, "email");
            if ($values["email"] == "") {throw new Exception(lang("api_error_1009"), 1009);}
            $Email = $values["email"];

            $values["area"] = keySecureZero($values, "area");
            if ($values["area"] == 0) {throw new Exception(lang("api_error_1003"), 1003);}
            $area = $values["area"];

            $values["telefono"] = keySecureZero($values, "telefono");
            if ($values["telefono"] == 0) {throw new Exception(lang("api_error_1004"), 1004);}
            $telefono = $values["telefono"];

            $fields = array("NroDocumento" => $NroDocumento, "email" => $Email, "area" => $area, "telefono" => $telefono);

            $headers = array('Content-Type:application/json','Authorization: Bearer '.API_Authenticate());
	        $ret = API_callAPI("/Integraciones/GetClientsByDocument/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);

            $merged["code"] = "200";
            $merged["error"] = "";
            $merged["status"] = "OK";
            $merged["timestamp"] = date(FORMAT_DATE);
            $merged["data"] = $ret["records"];
            return $merged;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function GetProducts($values)
    {
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];
            
            $fields = array("IdCliente" => $IdCliente);

            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
            $ret = API_callAPI("/Integraciones/GetProducts/", $headers, json_encode($fields));
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
    public function GetProductsConsolidatedPosition($values)
    {
        try {


            log_message("error", "RELATED ".json_encode($values,JSON_PRETTY_PRINT));


            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["producto"] = keySecureProducto($values, "producto");
            if ($values["producto"] == "") {throw new Exception(lang("api_error_1039"), 1039);}
            $Producto = $values["producto"];

            $fields = array("IdCliente" => $IdCliente, "Producto"=> $Producto);

            $headers = array('Content-Type:application/json', 'Authorization: Bearer ' . API_Authenticate());
            $ret = API_callAPI("/Integraciones/GetProductsConsolidatedPosition/", $headers, json_encode($fields));
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
}
