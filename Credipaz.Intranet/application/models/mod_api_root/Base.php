<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Base extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function authenticateMobile($values)
    {
        $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
        return $NETCORECPFINANCIAL->BridgeAuthenticateMobile($values);
    }
    public function authenticate($values)
    {
        try {
            if (!isset($values["status"])) {$values["status"] = 0;}
            if (!isset($values["callsource"])) {$values["callsource"] = "";}
            if (!isset($values["id_app"])) {$values["id_app"] = 0;}
            if ((int) $values["id_app"] == 0) {$values["id_app"] = 7;}
            if (!isset($values["version"])) {$values["version"] = "";}
            if (!isset($values["id_type_user"])) {$values["id_type_user"] = 78;}
            if (!isset($values["external_operator"])) {$values["external_operator"] = 0;}
            $values["external_operator"] = (int) $values["external_operator"];
            $values["username"] = trim($values["username"]);
            $values["password"] = trim($values["password"]);
            $values["id_user_active"] = 0;
            if (!isset($values["try"])) {$values["try"] = "LOCAL";}
            if (!isset($values["scoope"])) {$values["scoope"] = "backend";}

            if ((int) $values["external_operator"] == 1) {
                $values["id_type_user"] = "80,81,82,85,87,88";
                $values["try"] = "LOCAL";
            } else {
            //    $values["id_type_user"] = "77,78";
                $values["try"] = "LDAP";
             }
            logGeneralCustom($this, $values, "Users::TryLogin", "username:" . $values["username"] . " password:" . md5($values["password"]));
            /***************************/
            /*Divert for mobile auth!  */
            /***************************/
            $merged=null;
            switch ((int) $values["id_app"]) {
                case 2: // credipaz, mobile
                case 5: // Mediya, mobile
                    /*para moviles*/
                    $merged=$this->authenticateMobile($values);
                default:
                    /*para Intranet*/
                    $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
                    $users = $NETCORECPFINANCIAL->BridgeDirectAuthenticate($values);
                    if ($users["status"] != "OK") {throw new Exception(lang("error_5200"), 5200);}
                    $merged=array(
                        "code" => "2000",
                        "status" => "OK",
                        "message" => "",
                        "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                        "data" => $users["data"][0]
                    );
            }
            if ((int)$values["status"]==1){
                $merged=null;
                $merged["code"]="200";
                $merged["status"]="OK";
                $merged["timestamp"]=date(FORMAT_DATE);
                $merged["message"]="Online";
            }
            return $merged;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function verifytoken($values){
        try {
            $fields = array("Id_app" => $values["id_app"],"Id_user" => $values["id_user_activate"],"Token_authentication" => $values["token_authentication"]);
            $ret = API_callAPI("/Intranet/VerifyToken/",json_encode($fields));
	        $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function documentationinterface($values){
        try {
            $fields = array("Id_app" => $values["id_app"],"Id_user" => $values["id_user_activate"],"Token_authentication" => $values["token_authentication"]);
	        $ret = API_callAPI("/Intranet/DocumentationInterface/",json_encode($fields));
	        $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function menuinterface($values){
        try {
            $fields = array("Id_app" => $values["id_app"],"Id_user" => $values["id_user_activate"],"Token_authentication" => $values["token_authentication"]);
	        $ret = API_callAPI("/Intranet/MenuInterface/",json_encode($fields));
	        $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
