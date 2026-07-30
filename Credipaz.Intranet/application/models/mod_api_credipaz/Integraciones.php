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
                    $values["Sexo"] = keySecureValInArray($values, "Sexo",['F','M']);
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
	        $ret = API_callAPI("/Integraciones/GetClientsByDocument/",json_encode($fields));
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
            $ret = API_callAPI("/Integraciones/GetProducts/", json_encode($fields));
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
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["producto"] = keySecureValInArray($values, "producto",['CABAL','VISA']);
            if ($values["producto"] == "") {throw new Exception(lang("api_error_1039"), 1039);}
            $Producto = $values["producto"];

            $fields = array("IdCliente" => $IdCliente, "Producto"=> $Producto);
            $ret = API_callAPI("/Integraciones/GetProductsConsolidatedPosition/", json_encode($fields));
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
    public function GetLoan($values) {
        try {
            $values["IdSolicitud"] = keySecureZero($values, "IdSolicitud");
            if ($values["IdSolicitud"] == 0) {throw new Exception(lang("api_error_1040"), 1040);}
            $IdSolicitud = (int) $values["IdSolicitud"];

            $fields = array("IdSolicitud" => $IdSolicitud);
            $ret = API_callAPI("/Integraciones/GetLoan/", json_encode($fields));
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
    public function GetLoanFees($values) {
        try {
            $values["IdSolicitud"] = keySecureZero($values, "IdSolicitud");
            if ($values["IdSolicitud"] == 0) {throw new Exception(lang("api_error_1040"), 1040);}
            $IdSolicitud = (int) $values["IdSolicitud"];

            $fields = array("IdSolicitud" => $IdSolicitud);
            $ret = API_callAPI("/Integraciones/GetLoanFees/", json_encode($fields));
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
    public function GetLoanPayments($values) {
        try {
            $values["IdSolicitud"] = keySecureZero($values, "IdSolicitud");
            if ($values["IdSolicitud"] == 0) {throw new Exception(lang("api_error_1040"), 1040);}
            $IdSolicitud = (int) $values["IdSolicitud"];

            $fields = array("IdSolicitud" => $IdSolicitud);
            $ret = API_callAPI("/Integraciones/GetLoanPayments/", json_encode($fields));
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
    public function GetLoanRates($values) {
        try {
            $values["IdSolicitud"] = keySecureZero($values, "IdSolicitud");
            if ($values["IdSolicitud"] == 0) {throw new Exception(lang("api_error_1040"), 1040);}
            $IdSolicitud = (int) $values["IdSolicitud"];

            $fields = array("IdSolicitud" => $IdSolicitud);
            $ret = API_callAPI("/Integraciones/GetLoanRates/", json_encode($fields));
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

    public function GetProductBankStatements($values)
    {
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];
            
            $fields = array("IdCliente" => $IdCliente);
            $ret = API_callAPI("/Integraciones/GetProductBankStatements/", json_encode($fields));
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
    public function GetProductBankStatementFile($values)
    {
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["IdResumen"] = keySecureZero($values, "IdResumen");
            if ($values["IdResumen"] == 0) {throw new Exception(lang("api_error_1048"), 1048);}
            $IdResumen = (int) $values["IdResumen"];

            $fields = array("IdResumen" => $IdResumen, "IdCliente"=> $IdCliente);
            $ret = API_callAPI("/Integraciones/GetProductBankStatementFile/", json_encode($fields));
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
    public function SendSmsToken($values){
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["token"] = keySecureZero($values, "token");
            if ($values["token"] == 0) {throw new Exception(lang("api_error_1042"), 1042);}
            $Token = $values["token"];

            $values["NroDocumento"] = keySecureZero($values, "NroDocumento");
            if ($values["NroDocumento"] == 0) {throw new Exception(lang("api_error_1026"), 1026);}
            $NroDocumento = (int) $values["NroDocumento"];

            if (isset($values["Sexo"])) {
                if ($values["Sexo"] != "") {
                    $values["Sexo"] = keySecureValInArray($values, "Sexo",['F','M']);
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

            $fields = array("IdCliente"=>$IdCliente, "Token">=$Token, "NroDocumento" => $NroDocumento, "email" => $Email, "area" => $area, "telefono" => $telefono);
	        $ret = API_callAPI("/Integraciones/SendSmsToken/",json_encode($fields));
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

    public function GetCreditCardCurrentBalances($values){
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["producto"] = keySecureValInArray($values, "producto",['CABAL','VISA']);
            if ($values["producto"] == "") {throw new Exception(lang("api_error_1039"), 1039);}
            $Producto = $values["producto"];

            $values["IdTarjeta"] = keySecureZero($values, "IdTarjeta");
            if ($values["IdTarjeta"] == 0) {throw new Exception(lang("api_error_1043"), 1043);}
            $IdTarjeta = (int) $values["IdTarjeta"];

            $fields = array("IdCliente"=>$IdCliente, "producto"=>$Producto, "IdTarjeta" => $IdTarjeta);
            $ret = API_callAPI("/Integraciones/GetCreditCardCurrentBalances/",json_encode($fields));
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
    public function GetCreditCardCurrentMovements($values){
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["producto"] = keySecureValInArray($values, "producto",['CABAL','VISA']);
            if ($values["producto"] == "") {throw new Exception(lang("api_error_1039"), 1039);}
            $Producto = $values["producto"];

            $values["IdTarjeta"] = keySecureZero($values, "IdTarjeta");
            if ($values["IdTarjeta"] == 0) {throw new Exception(lang("api_error_1043"), 1043);}
            $IdTarjeta = (int) $values["IdTarjeta"];

            $values["FechaDesde"] = keySecureDate($values, "FechaDesde", "Y-m-d");
            if ($values["FechaDesde"] == "") {throw new Exception(lang("api_error_1044"), 1044);}
            $FechaDesde=$values["FechaDesde"];

            $values["FechaHasta"] = keySecureDate($values, "FechaHasta", "Y-m-d");
            if ($values["FechaHasta"] == "") {throw new Exception(lang("api_error_1045"), 1045);}
            $FechaHasta=$values["FechaHasta"];

            $fields = array("IdCliente"=>$IdCliente, "producto"=>$Producto, "IdTarjeta" => $IdTarjeta, "FechaDesde"=>$FechaDesde, "FechaHasta"=>$FechaHasta);
	        $ret = API_callAPI("/Integraciones/GetCreditCardCurrentMovements/",json_encode($fields));
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
    public function GetCreditCardStatementMovements($values){
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["producto"] = keySecureValInArray($values, "producto",['CABAL','VISA']);
            if ($values["producto"] == "") {throw new Exception(lang("api_error_1039"), 1039);}
            $Producto = $values["producto"];

            $values["IdTarjeta"] = keySecureZero($values, "IdTarjeta");
            if ($values["IdTarjeta"] == 0) {throw new Exception(lang("api_error_1043"), 1043);}
            $IdTarjeta = (int) $values["IdTarjeta"];

            $values["resumen"] = keySecureDate($values, "resumen", "Y-m");
            if ($values["resumen"] == "") {throw new Exception(lang("api_error_1046"), 1046);}
            $Resumen=$values["resumen"];

            $fields = array("IdCliente"=>$IdCliente, "producto"=>$Producto, "IdTarjeta" => $IdTarjeta, "Resumen"=>$Resumen);
	        $ret = API_callAPI("/Integraciones/GetCreditCardStatementMovements/",json_encode($fields));
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
    public function GetCreditCardDueDate($values){
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["producto"] = keySecureValInArray($values, "producto",['CABAL','VISA']);
            if ($values["producto"] == "") {throw new Exception(lang("api_error_1039"), 1039);}
            $Producto = $values["producto"];

            $values["IdTarjeta"] = keySecureZero($values, "IdTarjeta");
            if ($values["IdTarjeta"] == 0) {throw new Exception(lang("api_error_1043"), 1043);}
            $IdTarjeta = (int) $values["IdTarjeta"];

            $fields = array("IdCliente"=>$IdCliente, "producto"=>$Producto, "IdTarjeta" => $IdTarjeta);
	        $ret = API_callAPI("/Integraciones/GetCreditCardDueDate/",json_encode($fields));
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
    public function GetCreditCardDetails($values){
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["producto"] = keySecureValInArray($values, "producto",['CABAL','VISA']);
            if ($values["producto"] == "") {throw new Exception(lang("api_error_1039"), 1039);}
            $Producto = $values["producto"];

            $values["IdTarjeta"] = keySecureZero($values, "IdTarjeta");
            if ($values["IdTarjeta"] == 0) {throw new Exception(lang("api_error_1043"), 1043);}
            $IdTarjeta = (int) $values["IdTarjeta"];

            $fields = array("IdCliente"=>$IdCliente, "producto"=>$Producto, "IdTarjeta" => $IdTarjeta);
	        $ret = API_callAPI("/Integraciones/GetCreditCardDetails/",json_encode($fields));
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
    public function GetCreditCardExtensions($values){
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["producto"] = keySecureValInArray($values, "producto",['CABAL','VISA']);
            if ($values["producto"] == "") {throw new Exception(lang("api_error_1039"), 1039);}
            $Producto = $values["producto"];

            $values["IdTarjeta"] = keySecureZero($values, "IdTarjeta");
            if ($values["IdTarjeta"] == 0) {throw new Exception(lang("api_error_1043"), 1043);}
            $IdTarjeta = (int) $values["IdTarjeta"];

            $fields = array("IdCliente"=>$IdCliente, "producto"=>$Producto, "IdTarjeta" => $IdTarjeta);
	        $ret = API_callAPI("/Integraciones/GetCreditCardExtensions/",json_encode($fields));
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
    public function GetCreditCardStatements($values){
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["producto"] = keySecureValInArray($values, "producto",['CABAL','VISA']);
            if ($values["producto"] == "") {throw new Exception(lang("api_error_1039"), 1039);}
            $Producto = $values["producto"];

            $values["IdTarjeta"] = keySecureZero($values, "IdTarjeta");
            if ($values["IdTarjeta"] == 0) {throw new Exception(lang("api_error_1043"), 1043);}
            $IdTarjeta = (int) $values["IdTarjeta"];

            $values["resumen"] = keySecureDate($values, "resumen", "Y-m");
            if ($values["resumen"] == "") {throw new Exception(lang("api_error_1046"), 1046);}
            $Resumen=$values["resumen"];

            $fields = array("IdCliente"=>$IdCliente, "producto"=>$Producto, "IdTarjeta" => $IdTarjeta, "Resumen"=>$Resumen);
	        $ret = API_callAPI("/Integraciones/GetCreditCardStatements/",json_encode($fields));
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
    public function UpdateCreditCards($values){
        try {
            $values["IdCliente"] = keySecureZero($values, "IdCliente");
            if ($values["IdCliente"] == 0) {throw new Exception(lang("api_error_1038"), 1038);}
            $IdCliente = (int) $values["IdCliente"];

            $values["producto"] = keySecureValInArray($values, "producto",['CABAL','VISA']);
            if ($values["producto"] == "") {throw new Exception(lang("api_error_1039"), 1039);}
            $Producto = $values["producto"];

            $values["IdTarjeta"] = keySecureZero($values, "IdTarjeta");
            if ($values["IdTarjeta"] == 0) {throw new Exception(lang("api_error_1043"), 1043);}
            $IdTarjeta = (int) $values["IdTarjeta"];

            $values["accion"] = keySecureValInArray($values, "accion",['BLOCK','UNBLOCK']);
            if ($values["accion"] == "") {throw new Exception(lang("api_error_1047"), 1047);}
            $Accion=$values["accion"];

            $fields = array("IdCliente"=>$IdCliente, "producto"=>$Producto, "IdTarjeta" => $IdTarjeta, "Accion"=>$Accion);
	        $ret = API_callAPI("/Integraciones/UpdateCreditCards/",json_encode($fields));
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

}
