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
        try {
            if (!isset($values["nombre"])) {$values["nombre"] = "";}
            if ($values["field"] == "username") {$values["dni"] = explode("@", @$values["value"])[0];}
            $fields=array(
                "Password"=>md5($values["password"]),
                "PasswordPlain"=>$values["password"],
                "Id_app"=>(int)$values["id_app_mobile"],
                "Dni"=>$values["dni"],
                "Sex"=>$values["sexo"],
                "Usuario"=>$values["email"],
                "Area"=>$values["area"],
                "Telefono"=>$values["telefono"],
                "Nombre"=>$values["nombre"]
            );
            $url = (CPFINANCIALS."/Intranet/BridgeAuthenticateMobile");
            $result = API_callAPI($url, json_encode($fields));
            $result = json_decode($result, true);

            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "verificated" =>  ($result["records"][0]["verified"] != null),
                "token_authentication" => $result["records"][0]["token_authentication"],
                "userdata" => $result["records"][0],
                "clubredondo" => getIdUserMediya($this, $values["dni"])["message"],
                "id" => $result["records"][0]["id"]
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
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
    public function userValuePwa($values)
    {
        try {
            $id_app_mobile = (int)keySecureNumbers($values, "id_app_mobile");
            if ($id_app_mobile==0){ throw new Exception(lang("error_5121"), 5121);}
            $password=keySecureString($values,"password");
            $dni=(int)keySecureNumbers($values, "dni");
            $sexo=keySecureString($values,"sexo");

            $id_type_user = 80; // default Credipaz, mobile
            $sufix = "credipaz.com"; // default Credipaz, mobile
            switch ($id_app_mobile) {
                case 2: // credipaz, mobile
                    $id_type_user = 80;
                    $sufix = "credipaz.com";
                    break;
                case 5: // Mediya, mobile
                    $id_type_user = 82;
                    $sufix = "clubredondo.com";
                    break;
            }
            $fields = array("dni"=>$dni,"sexo"=>$sexo,"Id_type_user"=>$id_type_user,"Username"=>($dni."@".$sufix),"id_type_user"=>$id_type_user,"password"=>md5($password));
	        $ret = API_callAPIfields("/Intranet/DatosPersona/",$fields);
	        $ret = json_decode($ret, true);
            if (count($ret["records"])!=0) {
                $return=array(
                    "code" => "2000","status" => "OK","message" => "",
                    "names" => $ret["records"],
                    "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                    "exists" => false
                );
                if (count($ret["records"])==1) {
                    if ((int)$ret["records"][0]["exists"]==1) {
                        $return["exists"]=true;
                        $return["message"]="El valor ya existe";
                        unset($return["names"]);
                    }
                }
            }
            return $return;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function userInformation($values){
        try {
            $id_app_mobile = (int)keySecureNumbers($values, "id_app_mobile");
            if ($id_app_mobile==0){ throw new Exception(lang("error_5121"), 5121);}
            if (strpos($values["dni"],"@")!==false) {$values["dni"]=explode("@",$dni)[0];}
            $dni=(int)keySecureNumbers($values, "dni");
            $sexo=keySecureString($values,"sexo");
            $fields=array("dni"=>$dni,"sexo"=>$sexo,"id_app_mobile"=>$id_app_mobile);
            $url="";
            switch($id_app_mobile){
                case 2:
                    $url="/Intranet/DatosGeneralesCP/";
                    break;
                case 5:
                    $url="/Intranet/DatosGeneralesCR/";
                    break;
            }
	        $ret=API_callAPIfields($url,$fields);
	        $ret=json_decode($ret, true);
            if (count($ret["records"])!=0) {
                $return=array("code"=>"2000","status"=>"OK","message"=>"");
                $result["registered"]=($ret["records"][0]["id"]!="");
                $result["userdata"]=$ret["records"][0];
                $result["message"]=$ret["records"][0];
                $result["scope"] = $ret["records"][0]["scope"];
                $result["version"]=999;
            } else {
                $result=null;
            }
            return $result;
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function resetUserMobile($values){
        try {
            $email=keySecureString($values,"email");
            if ($email==""){ throw new Exception(lang("api_error_1009"), 1009);}
            $fields=array("email"=>$email);
	        $ret=API_callAPIfields("/Intranet/ResetUser/",$fields);
	        $ret=json_decode($ret, true);
            return $ret;
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
}
