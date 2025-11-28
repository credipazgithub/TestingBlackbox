<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Users extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $this->view="vw_users";
			if ($values["where"]!=""){$values["where"].=" AND ";}
			$values["where"].="id_type_user IN (81,85,87)";
            $values["order"]="username ASC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>true,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"image","format"=>"image"),
                array("field"=>"username","format"=>"email"),
                array("field"=>"type_user","format"=>"type"),
                array("field"=>"master_image","format"=>"image"),
                array("field"=>"master_account","format"=>"text"),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("username","type_user","master_account")),
                array("name"=>"browser_id_master", "operator"=>"=","fields"=>array("id_master")),
                array("name"=>"browser_id_type_user", "operator"=>"=","fields"=>array("id_type_user")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Empresa</span>".comboMasters($this),
                "<span class='badge badge-primary'>Tipo</span>".comboTypeUsers($this,array("order"=>"description ASC","pagesize"=>-1,"where"=>"id IN (81,85,87)")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_BACKEND."/users/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters_id_type_user=array(
                "model"=>(MOD_BACKEND."/Type_users"),
                "table"=>"type_users",
                "name"=>"id_type_user",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_user"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1,"where"=>"id IN (81,85,87)"),
            );
            $parameters_id_master=array(
                "model"=>(MOD_BACKEND."/Masters"),
                "table"=>"masters",
                "name"=>"id_master",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_master"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_application=array(
                "model"=>(MOD_BACKEND."/Applications"),
                "table"=>"applications",
                "name"=>"id_application",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_BACKEND."/Rel_users_applications"),"table"=>"rel_users_applications","id_field"=>"id_user","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );
            $parameters_id_group=array(
                "model"=>(MOD_BACKEND."/Groups"),
                "table"=>"groups",
                "name"=>"id_group",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_BACKEND."/Rel_users_groups"),"table"=>"rel_users_groups","id_field"=>"id_user","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );
            $parameters_id_channel=array(
                "model"=>(MOD_BACKEND."/Channels"),
                "table"=>"channels",
                "name"=>"id_channel",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_BACKEND."/Rel_channels_users"),"table"=>"rel_channels_users","id_field"=>"id_user","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );
            $values["controls"]=array(
                "id_master"=>getCombo($parameters_id_master,$this),
                "id_type_user"=>getCombo($parameters_id_type_user,$this),
                "id_application"=>getMultiSelect($parameters_id_application,$this),
                "id_group"=>getMultiSelect($parameters_id_group,$this),
                "id_channel"=>getMultiSelect($parameters_id_channel,$this)
            );

            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            $id_app = 0;
            if (!isset($values["id"])){$values["id"]=0;}
            if (!isset($values["image"])){$values["image"]=null;}
            if (!isset($values["phone"])) {$values["phone"] = null;}
            if (isset($fields["id_application"])) {
                $id_app = (int) $fields["id_application"];
                unset($fields["id_application"]);
            }
            $id=(int)$values["id"];
            $id_type_user = secureEmptyNull($values, "id_type_user");
            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                        'id_type_user' => $id_type_user,
                        'id_master' => secureEmptyNull($values,"id_master"),
                        'username' => $values["username"],
                        'password' => md5($values["password"]),
                        'image' => $values["image"],
                        'phone' => $values["phone"],
                        'token_push' => null,
                        'token_authentication' => null,
                        'token_transaction' => null,
                        'token_authentication_expire' => null,
                        'token_authentication_created' => null,
                        'token_transaction_expire' => null,
                        'token_transaction_created' => null,
                        'uid_firecloud' => null,
                    );
                }
                $sql="SELECT id FROM dbIntranet.dbo.".MOD_BACKEND."_users WHERE username='".$values["username"]."' AND id_type_user=". $id_type_user;
			    $control=$this->getRecordsAdHoc($sql);
                if (sizeof($control) != 0) {
                    $id=$control[0]["id"];
                    $values["id"] = $id;
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'fum' => $this->now,
                        'id_type_user' => $id_type_user,
                        'id_master' => secureEmptyNull($values,"id_master"),
                        'username' => $values["username"],
                        'image' => $values["image"],
                        'phone' => $values["phone"],
                        'token_authentication' => null,
                    );
                    if(strlen($values["password"])){$fields+=["password"=>md5($values["password"])];}
                }
            }
            $saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){
                if ($id_app != 0) {$values["id_application"] = $id_app;}
               $params_apps=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_users_applications",
                    "table"=>"rel_users_applications",
                    "key_field"=>"id_user",
                    "key_value"=>$saved["data"]["id"],
                    "rel_field"=>"id_application",
                    "rel_values"=>(isset($values["id_application"]) ? $values["id_application"] :array())
               );
               $params_groups=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_users_groups",
                    "table"=>"rel_users_groups",
                    "key_field"=>"id_user",
                    "key_value"=>$saved["data"]["id"],
                    "rel_field"=>"id_group",
                    "rel_values"=>(isset($values["id_group"]) ? $values["id_group"] :array())
               );
               $params_channels=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_channels_users",
                    "table"=>"rel_channels_users",
                    "key_field"=>"id_user",
                    "key_value"=>$saved["data"]["id"],
                    "rel_field"=>"id_channel",
                    "rel_values"=>(isset($values["id_channel"]) ? $values["id_channel"] :array())
               );
               parent::saveRelations($params_apps);
               parent::saveRelations($params_groups);
               parent::saveRelations($params_channels);
            }
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function delete($values){
        try {
            if((int)$values["id"]==0) {
                $user=$this->get(array("where"=>"username='".$values["email"]."'"));
                if(isset($user["data"][0])){
                   $values["id"]=$user["data"][0]["id"];
                }
            }
            $deleted=parent::delete($values);
            if($deleted["status"]=="OK"){
               $params_apps=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_users_applications",
                    "table"=>"rel_users_applications",
                    "key_field"=>"id_user",
                    "key_value"=>$values["id"],
               );
               $params_groups=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_users_groups",
                    "table"=>"rel_users_groups",
                    "key_field"=>"id_user",
                    "key_value"=>$values["id"],
               );
               $params_channels=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_channels_users",
                    "table"=>"rel_channels_users",
                    "key_field"=>"id_user",
                    "key_value"=>$values["id"],
               );
               parent::deleteRelations($params_apps);
               parent::deleteRelations($params_groups);
               parent::deleteRelations($params_channels);
            }
            return $deleted;
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    public function logout($values){
        try {
            $this->load->library("session");
            $this->session->set_userdata(array("logged"=>false));
            $this->psession=$this->session;
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function authenticate($values){
        try {
            if (!isset($values["callsource"])) {$values["callsource"] = "";}
            if (!isset($values["id_app"])) {$values["id_app"] = 0;}
            if (!isset($values["version"])) {$values["version"] = "";}
            if (!isset($values["id_type_user"])) {$values["id_type_user"] = 78;}
            $id_type_user=$values["id_type_user"];
            if(!isset($values["external_operator"])){$values["external_operator"]=0;}
			$values["external_operator"]=(int)$values["external_operator"];
			$values["username"]=trim($values["username"]);
			$values["password"]=trim($values["password"]);
            $values["id_user_active"]=0;
            logGeneralCustom($this,$values,"Users::TryLogin","username:".$values["username"]." password:".md5($values["password"]));

            $id_app=(int)$values["id_app"];
            switch($id_app){
               case 2: // credipaz, mobile
               case 5: // club redondo, mobile
                  return $this->authenticateMobile($values);
               case 0:
                  $id_app=7; // intranet
                  break;
            }
            $this->view="vw_users";
            if(!isset($values["transparent"])){$values["transparent"]=false;}
            if(!isset($values["try"])){$values["try"]="LOCAL";}
            if(!isset($values["scoope"])){$values["scoope"]="backend";}
            if($values["transparent"]){$values["scoope"]="site";}
            $values["page"]=1;
			if((int)$values["external_operator"]==1){
                $id_type_user="81,85,87";
                $values["try"]="LOCAL";
            }
            switch ($values["try"]) {
               case "LDAP":
                    $ldap=$this->adLayerExecuteWS("groups","authenticate","",strtoupper($values["username"]),$values["password"]);
                    if ($ldap["mode"]==$values["try"]) {
                        $values["where"]=("UPPER(username)='".strtoupper($values["username"])."'");
                    } else {
                        throw new Exception(lang("error_5203"),5203);
                    }
                    break;
               case "LOCAL":
                    $values["where"] = "";
                    if ($id_type_user != "all") {$values["where"] .= (" id_type_user IN (" . $id_type_user . ") AND ");}
                    $values["where"] .= (" UPPER(username)='" . strtoupper($values["username"]) . "' AND password='" . md5($values["password"]) . "' AND offline IS null AND id IN (SELECT id_user FROM " . MOD_BACKEND . "_rel_users_applications WHERE id_application=" . $id_app . ")");
                    break;
            }
            $users=parent::get($values);
            if ((int)$users["totalrecords"]==0){ throw new Exception(lang("error_5200"),5200);}
            $values["id_user_active"]=$users["data"][0]["id"];
            $type=$users["data"][0]["id_type_user"];
            $ret=$this->buildTokenAuthentication($users,$values["scoope"],$values["transparent"]);
            if (isset($ret["data"]) and $values["callsource"]=="api"){
                if ((int) $type == 87) {
                    /* Registra version APP enviada en la autenticación*/
                    $sql = "UPDATE " . MOD_BACKEND . "_users SET version='" . $values["version"] . "' WHERE id=" . $values["id_user_active"];
                    $this->execAdHoc($sql);
                    /*controla acciones de habilitación del usuario en cuanto a empresario*/
                    $eval = API_EvaluarHabilitado((string) $ret["data"]["idEmpresario"]);
                    $ret["data"]["habilitado"] = $eval["habilitado"];
                    $ret["data"]["detalle"] = $eval["detalle"];
                }
               unset($ret["data"]["idEmpresario"]);
               unset($ret["data"]["code"]);
               unset($ret["data"]["description"]);
               unset($ret["data"]["image"]);
               unset($ret["data"]["master_image"]);
               unset($ret["data"]["id_type_user"]);
               unset($ret["data"]["documentNumber"]);
               unset($ret["data"]["documentType"]);
               unset($ret["data"]["documentSex"]);
               unset($ret["data"]["documentArea"]);
               unset($ret["data"]["documentPhone"]);
               unset($ret["data"]["documentName"]);
               unset($ret["data"]["IdSolicitud"]);
               unset($ret["data"]["sip_device"]);
               unset($ret["data"]["sip_username"]);
               unset($ret["data"]["sip_password"]);
               unset($ret["data"]["telemedicina_rol"]);
               unset($ret["data"]["channels"]);
            } else  {
                $NETCORECPFINANCIAL=$this->createModel(MOD_EXTERNAL,"NetCoreCPFinancial","NetCoreCPFinancial");
                $details=$NETCORECPFINANCIAL->GetUserDetails($values);
                $profile = getUserProfile($this, $values["id_user_active"]);
                $ret["data"]["tiendamil_rol"]= (evalPermissions("X_TIENDA_MIL", $profile["data"][0]["groups"]));
                $ret["details"] = $details["data"];
            }
            return $ret;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function authenticateMobile($values){
        try {
            if (isset($values["password"])){$values["password"]=md5($values["password"]);}else{$values["password"]="";}
            if ($values["field"] == "username") {$values["dni"] = explode("@", @$values["value"])[0];}
            $bInsert=true;
            $id=0;
            $verificated=false;
            $id_app=(int)$values["id_app"];
            $id_type_user = 0;
            $sufix="";
            $sufix2="";
            $uid=$values["uid"];
            $username=$values["email"];
            $password=$values["password"];
            $dni=$values["dni"];
            $sex=$values["sex"];
            $area=$values["area"];
            $phone=$values["phone"];
            $viable=$values["viable"];
            if (!isset($values["name"])){$values["name"]="";}
            if (trim($values["name"])==""){
                $sql="SELECT * FROM DBClub.dbo.Persona WHERE Nrodocumento='".$values["dni"]."'";
			    $persona=$this->getRecordsAdHoc($sql);
                if (sizeof($persona)!=0) {
                   $values["name"]=$persona[0]["Nombre"]." ".$persona[0]["Apellido"];
                } else {
                    $sql="SELECT Nombre FROM DBCentral.dbo.AFIPpadron WHERE nDoc=".$values["dni"];
			        $persona=$this->getRecordsAdHoc($sql);
                    if (sizeof($persona)!=0) {$values["name"]=end(explode(" ",trim($persona[0]["Nombre"])));}
                }
            }
            $documentName=$values["name"];
            $IdSolicitud=$values["IdSolicitud"];
            if(!is_numeric($IdSolicitud)){$IdSolicitud=0;}
            switch($id_app){
               case 2: // credipaz, mobile
                  $id_type_user=80;
                  $sufix="credipaz.com";  
                  $sufix2="credipaz";
                  break;
               case 5: // mediya, mobile
                  $id_type_user=82; 
                  $sufix="clubredondo.com"; 
                  $sufix2="clubredondo";
                  break;
            }

            $sql="UPDATE mod_backend_users SET documentnumber=username WHERE id_type_user=".$id_type_user." AND documentNumber='".$dni."'";
            $this->dbLayerExecuteWS("nothing",$sql,"",null);

            $user=$this->get(array("where"=>"UPPER(username)='".strtoupper($username)."' AND id_type_user=".$id_type_user));
            $token_authentication="void";
           
            if ((int)$user["totalrecords"]!=0) {
               $bInsert=false;
               //con control de password
               //$user=$this->get(array("where"=>"username='".$username."' AND password='".$password."' AND id_type_user=".$id_type_user));
               //sin control de password
               $user=$this->get(array("where"=>"UPPER(username)='". strtoupper($username)."' AND id_type_user=".$id_type_user));
               $verificated=($user["data"][0]["verified"]!=null);
               $id=$user["data"][0]["id"];
               $user["data"][0]["documentName"]=$documentName;
            } else {
                $data = array(
                    'uid_firecloud' => $uid,
                    'username' => $username,
                    'documentType'=>'dni',
                    'documentNumber'=>$dni,
                    'documentSex'=>$sex,
                    'documentArea'=>$area,
                    'documentPhone'=>$phone,
                    'password'=>$password,
                    'id_type_user'=>$id_type_user,
                    'created'=>$this->now,
                    'verified'=>$this->now,
                    'fum'=>$this->now,
                    'viable'=>$viable,
                    'documentName'=>$documentName,
                    'IdSolicitud'=>$IdSolicitud,
                    'id_application'=>$id_app
                );
				//if ($password!="") {$data["password"]=$password;}
                $saved=$this->save(array("id"=>0),$data);
                if ($saved["status"]!="OK"){throw new Exception($saved["message"],(int)$saved["code"]);}
                if (isset($saved["data"]["id"])) {$id=$saved["data"]["id"];}
            }
            $token_authentication=$this->generateApiKey(30,$id);
            $data = array('token_authentication' => $token_authentication,'documentName'=>$documentName);
            if ((int) $id != 0) {$saved = $this->save(array("id" => $id), $data);}
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "verificated"=>$verificated,
                "token_authentication"=>$token_authentication,
                "userdata"=>$user["data"][0],
                "clubredondo"=>getIdUserClubRedondo($this,$dni)["message"],
                "id"=>$id
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function reAuthenticate($values){
        try {
            if(!isset($values["mode"])){$values["mode"]="backend";}
            $values["page"]=1;
            $values["where"]=("id='".$values["id_user_active"]."' AND token_authentication='".$values["token_authentication"]."' AND offline IS null");
            $users=parent::get($values);
            return $this->buildTokenAuthentication($users,$values["mode"],false);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function revalidatePassword($values){
        try {
            if(!isset($values["username"])){$values["username"]=$values["username_active"];}
            $ldap=$this->adLayerExecuteWS("groups","authenticate","",$values["username"],$values["password"]);
            if ($ldap["status"]=="OK") {
                $values["where"]=("username='".$values["username"]."'");
            } else {
                throw new Exception(lang("error_5203"),5203);
            }
            $ret=parent::get($values);
            return $ret;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function getMenuTree($values){
        $FUNCTIONS=$this->createModel(MOD_BACKEND,"Functions","Functions");
        return $FUNCTIONS->menuTree($values);
    }
    public function generateTokenTransaction($values){
        try {
            /*--------------------------------------------------------------*/
            /* Generate token_authentication, with each user authentication */
            /*--------------------------------------------------------------*/
            $values["len"]=8;
            $auth=$this->userTokenTransaction($values);
            if ($auth["status"]=="ERROR") {throw new Exception($auth["message"],(int)$auth["code"]);}
            /*--------------------------------------------------------------*/
            $data=array(
            "token_transaction"=>$auth["data"]["token_transaction"],
            "token_transaction_created"=>$auth["data"]["token_transaction_created"],
            "token_transaction_expire"=>$auth["data"]["token_transaction_expire"],
            );
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"TokenTransaction",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data,
                );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function generateTokenPush($values){
        try {
            $values["id"]=$values["id_user_active"];
            $fields = array('token_push' => $values["token_push"]);
            return parent::save($values,$fields);
        } catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function verifyTokenAuthentication($values){
        try {
            $values["page"]=1;
            $values["where"]=("id=".$values["id_user_active"]." AND token_authentication='".$values["token_authentication"]."' AND offline IS null");
            $values["order"]="";
            $users=$this->get($values);
            if ($users["status"]=="OK") {
                if (isset($users["data"][0])) {
                    $token_authentication_expire=$this->encryption->decrypt($users["data"][0]["token_authentication_expire"]);
                    //if(strtotime("now") > strtotime($token_authentication_expire)){throw new Exception(lang("error_5400"),5400);}
                    return array(
                        "code"=>"2000",
                        "status"=>"OK",
                        "message"=>"",
                        "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                        "data"=>array("id"=>$users["data"][0]["id"]),
                        );
                } else {
                    throw new Exception(lang("error_5401"),5401);
                }
            } else {
                throw new Exception($users["status"]["message"],(int)$users["status"]["code"]);
            }
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function verifyTokenTransaction($values){
        try {
            $values["page"]=1;
            $values["where"]=("phone='".$values["phone"]."' AND token_transaction='".$values["token_transaction"]."' AND offline IS null");
            $users=parent::get($values);
            if ($users["status"]=="OK") {
                if (isset($users["data"][0])) {
                    $token_transaction_expire=$this->encryption->decrypt($users["data"][0]["token_transaction_expire"]);
                    if(strtotime("now") > strtotime($token_transaction_expire)){throw new Exception(lang("error_5500"),5500);}
                    return array(
                        "code"=>"2000",
                        "status"=>"OK",
                        "message"=>"",
                        "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                        "data"=>array("id"=>$users["data"][0]["id"]),
                        );
                } else {
                    throw new Exception(lang("error_5501"),5501);
                }
            } else {
                throw new Exception($users["status"]["message"],(int)$users["status"]["code"]);
            }
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function testUserValue($values){
        try {
            $sql="";
            $posibles=false;
            if(!isset($values["mode"])){$values["mode"]="new";}
            if(!isset($values["uid"])){$values["uid"]=0;}
            $id_app=(int)$values["id_app"];
            $id_type_user=80; // default Credipaz, mobile
            $sufix="credipaz.com"; // default Credipaz, mobile
            switch($id_app){
               case 2: // credipaz, mobile
                  $id_type_user=80;
                  $sufix="credipaz.com";  
                  break;
               case 5: // club redondo, mobile
                  $id_type_user=82; 
                  $sufix="clubredondo.com"; 
                  break;
            }
            switch($values["mode"]){
                case "document":
                    $posibles=true;
                    $sql="SELECT * FROM mod_backend_users WHERE id_type_user=".$id_type_user." AND documentNumber='".$values["documentNumber"]."' OR username='".$values["documentNumber"]."@".$sufix."'";// AND documentSex='".$values["documentSex"]."'";
                    break;
                case "new":
                    $sql="SELECT * FROM mod_backend_users WHERE id_type_user=".$id_type_user." AND ".$values["field"]."='".$values["value"]."'";
                    break;
                case "email":
                    $sql="SELECT * FROM mod_backend_users WHERE id_type_user=".$id_type_user." AND UPPER(username)='".strtoupper($values["email"])."' AND password='".md5($values["password"])."'";
					break;
            }
            $record=$this->getRecordsAdHoc($sql);
            $EXTERNAL=$this->createModel(MOD_BACKEND,"External","External");
            $retNames=$EXTERNAL->getIdentityInformation($values);
            if(!isset($record[0]["id"])){
                $names=[];
                if ($posibles) {
                    foreach($retNames["message"] as $item) {
                        $viable=1;
                        $IdSolicitud=0;
                        array_push($names,array("name"=>$item["Value"],"viable"=>$viable,"IdSolicitud"=>$IdSolicitud));
                    }
                }
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>"",
                    "names"=>$names,
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "exists"=>false
                );
            } else {
                foreach($retNames as $item) {
                    $viable=0;
                    if ($item["Estado"]=="Aprobado"){$viable=1;}
                    $this->save(array("id"=>$record[0]["id"]),array("viable"=>$viable));
                }
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>"El valor ya existe",
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "exists"=>true
                );
            }
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function testUserValuePWA($values){
        try {
            $sql="";
            $posibles=true;
            if(!isset($values["password"])){$values["password"]="";}
            if(!isset($values["uid"])){$values["uid"]=0;}
            $id_app=(int)$values["id_app"];
            $id_type_user=80; // default Credipaz, mobile
            $sufix="credipaz.com"; // default Credipaz, mobile
            switch($id_app){
               case 2: // credipaz, mobile
                  $id_type_user=80;
                  $sufix="credipaz.com";  
                  break;
               case 5: // club redondo, mobile
                  $id_type_user=82; 
                  $sufix="clubredondo.com"; 
                  break;
            }

            $sql="SELECT * FROM mod_backend_users WHERE id_type_user=".$id_type_user." AND documentNumber='".$values["documentNumber"]."' OR username='".$values["documentNumber"]."@".$sufix."'";
			if ($values["password"]!="") {
	            $posibles=false;
                $sql="SELECT * FROM mod_backend_users WHERE id_type_user=".$id_type_user." AND documentNumber='".$values["documentNumber"]."' AND password='".md5($values["password"])."'";
			}
            $record=$this->getRecordsAdHoc($sql);
            $EXTERNAL=$this->createModel(MOD_BACKEND,"External","External");
            $retNames=$EXTERNAL->getIdentityInformation($values);
            if(!isset($record[0]["id"])){
                $names=[];
                if ($posibles) {
                    foreach($retNames["message"] as $item) {
                        $viable=1;
                        $IdSolicitud=0;
                        array_push($names,array("name"=>$item["Value"],"viable"=>$viable,"IdSolicitud"=>$IdSolicitud));
                    }
                }
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>"",
                    "names"=>$names,
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "exists"=>false
                );
            } else {
                foreach($retNames as $item) {
                    $viable=0;
                    //if ($item["Estado"]=="Aprobado"){$viable=1;}
                    $this->save(array("id"=>$record[0]["id"]),array("viable"=>$viable));
                }
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>"El valor ya existe",
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "exists"=>true
                );
            }
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function verifyAccount($values){
        try {
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"El valor ya existe",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "verificated"=>true
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function tokenFireCloud($values){
	    try {
			if(!isset($values["id_user_active"])){$values["id_user_active"]=0;}
			$fields=array("uid_firecloud"=>$values["token"],"token_push"=>$values["token"]);
			$this->updateByWhere($fields,"id='".$values["id_user_active"]."'");
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
	}
    
    public function simData($values){
        if(!isset($values["carrierName"])){$values["carrierName"]="NO CARRIERNAME";}
        if(!isset($values["countryCode"])){$values["countryCode"]="NO COUNTRYCODE";}
        if(!isset($values["mcc"])){$values["mcc"]="NO MCC";}
        if(!isset($values["mnc"])){$values["mnc"]="NO MNC";}
        if(!isset($values["phoneNumber"])){$values["phoneNumber"]="NO PHONENUMBER";}
        if(!isset($values["callState"])){$values["callState"]="NO CALLSTATE";}
        if(!isset($values["dataActivity"])){$values["dataActivity"]="NO DATAACTIVITY";}
        if(!isset($values["networkType"])){$values["networkType"]="NO NETWORKTYPE";}
        if(!isset($values["phoneType"])){$values["phoneType"]="NO PHONETYPE";}
        if(!isset($values["simState"])){$values["simState"]="NO SIMSTATE";}
        $carrierName=$values["carrierName"];
        $countryCode=$values["countryCode"];
        $mcc=$values["mcc"];
        $mnc=$values["mnc"];
        $phoneNumber=$values["phoneNumber"];
        $callState=$values["callState"];
        $dataActivity=$values["dataActivity"];
        $networkType=$values["networkType"];
        $phoneType=$values["phoneType"];
        $simState=$values["simState"];
        /*
        data.callState
        // 0 CALL_STATE_IDLE	No activity
        // 1 CALL_STATE_RINGING	Ringing. A new call arrived and is ringing or waiting. In the latter case, another call is already active.
        // 2 CALL_STATE_OFFHOOK	Off-hook. At least one call exists that is dialing, active, or on hold, and no calls are ringing or waiting.
        data.dataActivity
        // 0 DATA_ACTIVITY_NONE	No traffic.
        // 1 DATA_ACTIVITY_IN	Currently receiving IP PPP traffic.
        // 2 DATA_ACTIVITY_OUT	Currently sending IP PPP traffic.
        // 3 DATA_ACTIVITY_INOUT	Currently both sending and receiving IP PPP traffic.
        // 4 DATA_ACTIVITY_DORMANT	Data connection is active, but physical link is down
        data.networkType
        // 0 NETWORK_TYPE_UNKNOWN	unknown
        // 1 NETWORK_TYPE_GPRS	GPRS
        // 2 NETWORK_TYPE_EDGE	EDGE
        // 3 NETWORK_TYPE_UMTS	UMTS
        // 4 NETWORK_TYPE_CDMA	CDMA: Either IS95A or IS95B
        // 5 NETWORK_TYPE_EVDO_0	EVDO revision 0
        // 6 NETWORK_TYPE_EVDO_A	EVDO revision A
        // 7 NETWORK_TYPE_1xRTT	1xRTT
        // 8 NETWORK_TYPE_HSDPA	HSDPA
        // 9 NETWORK_TYPE_HSUPA	HSUPA
        // 10 NETWORK_TYPE_HSPA	HSPA
        // 11 NETWORK_TYPE_IDEN	iDen
        // 12 NETWORK_TYPE_EVDO_B	EVDO revision B
        // 13 NETWORK_TYPE_LTE	LTE
        // 14 NETWORK_TYPE_EHRPD	eHRPD
        // 15 NETWORK_TYPE_HSPAP	HSPA+
        data.phoneType
        // 0 PHONE_TYPE_NONE	none
        // 1 PHONE_TYPE_GSM	GSM
        // 2 PHONE_TYPE_CDMA	CDMA
        // 3 PHONE_TYPE_SIP	SIP
        data.simState
        // 0 SIM_STATE_UNKNOWN	Unknown. Signifies that the SIM is in transition between states. For example, when the user inputs the SIM pin under PIN_REQUIRED state, a query for sim status returns this state before turning to SIM_STATE_READY.
        // 1 SIM_STATE_ABSENT	No SIM card is available in the device
        // 2 SIM_STATE_PIN_REQUIRED	Locked: requires the user's SIM PIN to unlock
        // 3 SIM_STATE_PUK_REQUIRED	Locked: requires the user's SIM PUK to unlock
        // 4 SIM_STATE_NETWORK_LOCKED	Locked: requires a network PIN to unlock
        // 5 SIM_STATE_READY	Ready
        */
        $fields=array(
            "carrierName"=>$carrierName,
            "countryCode"=>$countryCode,
            "mcc"=>$mcc,
            "mnc"=>$mnc,
            "phone"=>$phoneNumber,
            "phoneNumber"=>$phoneNumber,
            "callState"=>$callState,
            "dataActivity"=>$dataActivity,
            "networkType"=>$networkType,
            "phoneType"=>$phoneType,
            "simState"=>$simState
        );
        $this->updateByWhere($fields,"token_authentication='".$values["uid"]."'");
    }
	public function changePassword($values){
        try {
		    $values["email"]=base64_decode($values["email"]);
			if ($values["email"]=="daniel@neodata.com.ar"){$values["email"]="op";}
            $sql="UPDATE mod_backend_users SET password='".md5($values["password"])."' WHERE id_type_user=".$values["id_type_user"]." AND username='".$values["email"]."'";
            $this->dbLayerExecuteWS("nothing",$sql,"",null);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"El valor ya existe",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "verificated"=>true
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
	}
	public function informUserArea($values){
        try {
			$sql="UPDATE ".MOD_BACKEND."_users SET last_area='".$values["last_area"]."',last_access_area=getdate() WHERE id=".$values["id_user_active"];
			$this->execAdHoc($sql);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
	}
	public function getUserAreas($values){
        try {
			$seconds=9999;
			$users=parent::get(array("order"=>"2 ASC","where"=>"last_area='".$values["last_area"]."'","fields"=>"last_area,datediff(second,last_access_area,getdate()) as seconds"));
			if ((int)$users["totalrecords"]!=0){$seconds=$users["data"][0]["seconds"];}
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
				"area"=>$users["data"][0]["last_area"],
				"seconds"=>$users["data"][0]["seconds"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
	}

    private function buildTokenAuthentication($users,$scoope,$transparent){
        try {
            if ($users["status"]=="OK") {
                if (isset($users["data"][0])) {
                    $image=(PREFIX_FILEGET.FILES_USUARIOS.strtoupper($users["data"][0]["username"]).".jpg");
                    $data=array(
                            "id"=>$users["data"][0]["id"],
                            "code"=>$users["data"][0]["code"],
                            "description"=>$users["data"][0]["description"],
                            "username"=>$users["data"][0]["username"],
                            "idEmpresario"=>$users["data"][0]["idEmpresario"],
                            "image"=>$image,
                            "master_account"=>$users["data"][0]["master_account"],
                            "master_image"=>$users["data"][0]["master_image"],
                            "id_type_user"=>$users["data"][0]["id_type_user"],
                            "documentNumber"=>$users["data"][0]["documentNumber"],
                            "documentType"=>$users["data"][0]["documentType"],
                            "documentSex"=>$users["data"][0]["documentSex"],
                            "documentArea"=>$users["data"][0]["documentArea"],
                            "documentPhone"=>$users["data"][0]["documentPhone"],
                            "documentName"=>$users["data"][0]["documentName"],
                            "IdSolicitud"=>$users["data"][0]["IdSolicitud"],
                    );
                    switch($scoope) {
                       case "site": //Verification Token NOT GENERATED! That's ok!
                          $session=array(
                              "logged"=>true,
                              "id_user"=>$users["data"][0]["id"],
                              "id_type_user"=>$users["data"][0]["id_type_user"],
                              "type_user"=>$users["data"][0]["type_user"],
                              "id_master"=>$users["data"][0]["id_master"],
                              "username"=>$users["data"][0]["username"],
                              "image"=>$image,
                              "master_account"=>$users["data"][0]["master_account"],
                              "master_image"=>$users["data"][0]["master_image"],
                              "documentNumber"=>$users["data"][0]["documentNumber"],
                              "documentType"=>$users["data"][0]["documentType"],
                              "documentSex"=>$users["data"][0]["documentSex"],
                              "documentArea"=>$users["data"][0]["documentArea"],
                              "documentPhone"=>$users["data"][0]["documentPhone"],
                              "documentName"=>$users["data"][0]["documentName"],
                              "IdSolicitud"=>$users["data"][0]["IdSolicitud"],
                          );
                          $this->load->library("session");
                          $this->session->set_userdata($session);
                          $this->psession=$this->session->userdata;
                          break;
                       default: // Verification Token GENERATED. 
                          $i=0;
                          $REL_CHANNELS_USERS=$this->createModel(MOD_BACKEND,"Rel_channels_users","Rel_channels_users");
                          foreach ($users["data"] as $record){
                              $rel_channel_user=$REL_CHANNELS_USERS->get(array("page"=>1,"where"=>("id_user=".$record["id"])));
                              if ($rel_channel_user["status"]=="OK"){
                                  if (isset($rel_channel_user["data"])) {$users["data"][$i]["channels"]=$rel_channel_user["data"];}
                              }
                              $i+=1;
                          }
                          /*--------------------------------------------------------------*/
                          /* Generate token_authentication, with each user authentication */
                          /*--------------------------------------------------------------*/
                          $params=array("id"=>(int)$users["data"][0]["id"],"len"=>128);
                          $auth=$this->userTokenAuthentication($params,$transparent);
                          if ($auth["status"]=="ERROR") {throw new Exception(lang("error_5201"),5201);}
                          /*--------------------------------------------------------------*/
                          /*
                          $USERS_SIP=$this->createModel(MOD_TELEPHONY,"Users_sip","Users_sip");
                          $user_sip=$USERS_SIP->get(array("page"=>1,"where"=>("username='".$users["data"][0]["username"]."'")));
                          $data["sip_device"]="";
                          $data["sip_username"]="";
                          $data["sip_password"]="";
                          if ($user_sip["status"]=="OK"){
                              $data["sip_device"]=$user_sip["data"][0]["sip_device"];
                              $data["sip_username"]=$user_sip["data"][0]["sip_username"];
                              $data["sip_password"]=$user_sip["data"][0]["sip_password"];
                          }
                          */
                          $DOCTORS=$this->createModel(MOD_TELEMEDICINA,"Doctors","Doctors");
                          $doctor=$DOCTORS->get(array("page"=>1,"where"=>"username='".$users["data"][0]["username"]."'"));
                          $data["telemedicina_rol"]="";
                          if ((int)$doctor["totalrecords"]!=0){$data["telemedicina_rol"]="doctor";}

                          $data["token_authentication"]=$auth["data"]["token_authentication"];
                          $data["token_authentication_created"]=$auth["data"]["token_authentication_created"];
                          $data["token_authentication_expire"]=$auth["data"]["token_authentication_expire"];
                          $data["channels"]=$users["data"][0]["channels"];
                          break;
                    }
                    return array(
                        "code"=>"2000",
                        "status"=>"OK",
                        "message"=>"Authenticated",
                        "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                        "data"=>$data,
                        );
                } else {
                    throw new Exception(lang("error_5200"),5200);
                }
            } else {
                throw new Exception($users["status"]["message"],(int)$users["status"]["code"]);
            }
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    private function userTokenAuthentication($values,$transparent){
        try {
            if (!is_numeric($values["id"]) or !isset($values["id"])){$values["id"]=0;}
            if (!is_numeric($values["len"]) or !isset($values["len"])){$values["len"]=256;}
            if (!$transparent) {
                $token_authentication = opensslRandom((int)$values["len"]);
                $token_authentication_expire = $this->encryption->encrypt((string)date(FORMAT_DATE,strtotime(TOKEN_AUTHENTICATION_TTL,strtotime($this->now))));
                $token_authentication_created = $this->encryption->encrypt((string)date(FORMAT_DATE,strtotime($this->now)));
                $fields = array(
                    'token_authentication' => $token_authentication,
                    'token_authentication_expire' => $token_authentication_expire,
                    'token_authentication_created' => $token_authentication_created,
                    );
                $save=parent::save($values,$fields);
            } else {
                $values["where"]=("id=".$values["id"]);
                $user=$this->get($values);
                $fields = array(
                    'token_authentication' => $user["data"][0]["token_authentication"],
                    'token_authentication_expire' => $user["data"][0]["token_authentication_expire"],
                    'token_authentication_created' => $user["data"][0]["token_authentication_created"],
                    );
            }
            $fields["token_authentication_expire"]=$this->encryption->decrypt($token_authentication_expire);
            $fields["token_authentication_created"]=$this->encryption->decrypt($token_authentication_created);

            if ($save["status"]=="OK") {
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>"Authenticated",
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "data"=>$fields,
                    );
            } else {
                throw new Exception(lang("error_5201"),5201);
            }
        } catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    private function userTokenTransaction($values){
        try {
            $values["id"]=$values["id_user_active"];
            $token_transaction = getSecureRandomize(11111111, 99999999);
            $token_transaction_expire = $this->encryption->encrypt((string)date(FORMAT_DATE,strtotime(TOKEN_TRANSACTION_TTL,strtotime($this->now))));
            $token_transaction_created = $this->encryption->encrypt((string)date(FORMAT_DATE,strtotime($this->now)));

            $fields = array(
                'token_transaction' => $token_transaction,
                'token_transaction_expire' => $token_transaction_expire,
                'token_transaction_created' => $token_transaction_created,
                );
            $save=parent::save($values,$fields);
            $fields["token_transaction_expire"]=$this->encryption->decrypt($token_transaction_expire);
            $fields["token_transaction_created"]=$this->encryption->decrypt($token_transaction_created);

            if ($save["status"]=="OK") {
                return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>"",
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                    "data"=>$fields,
                    );
            } else {
                throw new Exception(lang("error_5201"),5201);
            }
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    private function generateApiKey($length,$id_user)
        {
            $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0987654321'.time();
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = $length; $i > 0; $i--){$randomString .= $characters[rand(0, $charactersLength - 1)];}
            $token_authentication=md5($randomString);
            $this->save(array("id"=>$id_user),array("token_authentication"=>$token_authentication));
            return $token_authentication;
        }

    public function createTokenJWT($values) {
        try {
            $videoDoctorStatus=1;
            if(!isset($values["auditoria"])){$values["auditoria"]="N";}
            if(!isset($values["roomname"])){$values["roomname"]="*";}
            $jwtToken = "";
            if ($values["auditoria"]=="N") {
                $tokenData=array(
                    "aud"=>SERVER_AUD,
                    "iss"=>SERVER_ISS,
                    "sub"=>SERVER_SUB,
                    "room"=>$values["roomname"],
                );
                $jwtToken=encodeTokenJWT($tokenData);
            } else {
                $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
                $charge_code=$CHARGES_CODES->get(array("fields"=>"code,videoDoctorStatus","page"=>1,"where"=>("id=".$values["id_charge_code"])));
                if ($charge_code["status"]=="OK"){
                    $jwtToken=$charge_code["data"][0]["code"];
                    $videoDoctorStatus=$charge_code["data"][0]["videoDoctorStatus"];
                }
            }
            return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "message"=>"",
                    "token"=>$jwtToken,
                    "videoDoctorStatus"=>$videoDoctorStatus,
                    "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
               );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
