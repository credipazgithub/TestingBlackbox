<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Users extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
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
    public function brow($values)
    {
        try {
            $this->view = "vw_users";
            if ($values["where"] != "") {
                $values["where"] .= " AND ";
            }
            $values["where"] .= "id_type_user IN (81,85,87)";
            $values["order"] = "username ASC";
            $values["records"] = $this->get($values);
            $values["buttons"] = array(
                "new" => true,
                "edit" => true,
                "delete" => true,
                "offline" => false,
            );
            $values["columns"] = array(
                //array("field"=>"id","format"=>"number"),
                array("field" => "image", "format" => "image"),
                array("field" => "username", "format" => "email"),
                array("field" => "type_user", "format" => "type"),
                array("field" => "master_image", "format" => "image"),
                array("field" => "master_account", "format" => "text"),
                array("field" => "", "format" => null),
            );
            $values["filters"] = array(
                array("name" => "browser_search", "operator" => "like", "fields" => array("username", "type_user", "master_account")),
                array("name" => "browser_id_master", "operator" => "=", "fields" => array("id_master")),
                array("name" => "browser_id_type_user", "operator" => "=", "fields" => array("id_type_user")),
            );
            $values["controls"] = array(
                "<span class='badge badge-primary'>Empresa</span>" . comboMasters($this),
                "<span class='badge badge-primary'>Tipo</span>" . comboTypeUsers($this, array("order" => "description ASC", "pagesize" => -1, "where" => "id IN (81,85,87)")),
            );
            return parent::brow($values);
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function edit($values)
    {
        try {
            $values["interface"] = (MOD_BACKEND . "/users/abm");
            $values["page"] = 1;
            $values["where"] = ("id=" . $values["id"]);
            $values["records"] = $this->get($values);

            $parameters_id_type_user = array(
                "model" => (MOD_BACKEND . "/Type_users"),
                "table" => "type_users",
                "name" => "id_type_user",
                "class" => "form-control dbase",
                "empty" => true,
                "id_actual" => secureComboPosition($values["records"], "id_type_user"),
                "id_field" => "id",
                "description_field" => "description",
                "get" => array("order" => "description ASC", "pagesize" => -1, "where" => "id IN (81,85,87)"),
            );
            $parameters_id_master = array(
                "model" => (MOD_BACKEND . "/Masters"),
                "table" => "masters",
                "name" => "id_master",
                "class" => "form-control dbase",
                "empty" => true,
                "id_actual" => secureComboPosition($values["records"], "id_master"),
                "id_field" => "id",
                "description_field" => "description",
                "get" => array("order" => "description ASC", "pagesize" => -1),
            );
            $parameters_id_application = array(
                "model" => (MOD_BACKEND . "/Applications"),
                "table" => "applications",
                "name" => "id_application",
                "class" => "multiselect dbase",
                "actual" => array("model" => (MOD_BACKEND . "/Rel_users_applications"), "table" => "rel_users_applications", "id_field" => "id_user", "id_value" => $values["id"]),
                "id_field" => "id",
                "description_field" => "description",
                "options" => array("order" => "description ASC", "pagesize" => -1),
                "function" => "get",
            );
            $parameters_id_group = array(
                "model" => (MOD_BACKEND . "/Groups"),
                "table" => "groups",
                "name" => "id_group",
                "class" => "multiselect dbase",
                "actual" => array("model" => (MOD_BACKEND . "/Rel_users_groups"), "table" => "rel_users_groups", "id_field" => "id_user", "id_value" => $values["id"]),
                "id_field" => "id",
                "description_field" => "description",
                "options" => array("order" => "description ASC", "pagesize" => -1),
                "function" => "get",
            );
            $parameters_id_channel = array(
                "model" => (MOD_BACKEND . "/Channels"),
                "table" => "channels",
                "name" => "id_channel",
                "class" => "multiselect dbase",
                "actual" => array("model" => (MOD_BACKEND . "/Rel_channels_users"), "table" => "rel_channels_users", "id_field" => "id_user", "id_value" => $values["id"]),
                "id_field" => "id",
                "description_field" => "description",
                "options" => array("order" => "description ASC", "pagesize" => -1),
                "function" => "get",
            );
            $values["controls"] = array(
                "id_master" => getCombo($parameters_id_master, $this),
                "id_type_user" => getCombo($parameters_id_type_user, $this),
                "id_application" => getMultiSelect($parameters_id_application, $this),
                "id_group" => getMultiSelect($parameters_id_group, $this),
                "id_channel" => getMultiSelect($parameters_id_channel, $this)
            );

            return parent::edit($values);
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function save($values, $fields = null)
    {
        try {
            $id_app = 0;
            if (!isset($values["id"])) {
                $values["id"] = 0;
            }
            if (!isset($values["image"])) {
                $values["image"] = null;
            }
            if (!isset($values["phone"])) {
                $values["phone"] = null;
            }
            if (isset($fields["id_application"])) {
                $id_app = (int) $fields["id_application"];
                unset($fields["id_application"]);
            }
            $id = (int) $values["id"];
            $id_type_user = secureEmptyNull($values, "id_type_user");
            if ($id == 0) {
                if ($fields == null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                        'id_type_user' => $id_type_user,
                        'id_master' => secureEmptyNull($values, "id_master"),
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
                if ($id_type_user == "") {
                    $id_type_user = 0;
                }
                $sql = "SELECT id FROM dbIntranet.dbo." . MOD_BACKEND . "_users WHERE username='" . $values["username"] . "' AND id_type_user=" . $id_type_user;
                $control = $this->getRecordsAdHoc($sql);
                if (sizeof($control) != 0) {
                    $id = $control[0]["id"];
                    $values["id"] = $id;
                }
            } else {
                if ($fields == null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'fum' => $this->now,
                        'id_type_user' => $id_type_user,
                        'id_master' => secureEmptyNull($values, "id_master"),
                        'username' => $values["username"],
                        'image' => $values["image"],
                        'phone' => $values["phone"],
                        'token_authentication' => null,
                    );
                    if (strlen($values["password"])) {
                        $fields += ["password" => md5($values["password"])];
                    }
                }
            }
            $saved = parent::save($values, $fields);
            if ($saved["status"] == "OK") {
                if ($id_app != 0) {
                    $values["id_application"] = $id_app;
                }
                $params_apps = array(
                    "module" => MOD_BACKEND,
                    "model" => "Rel_users_applications",
                    "table" => "rel_users_applications",
                    "key_field" => "id_user",
                    "key_value" => $saved["data"]["id"],
                    "rel_field" => "id_application",
                    "rel_values" => (isset($values["id_application"]) ? $values["id_application"] : array())
                );
                $params_groups = array(
                    "module" => MOD_BACKEND,
                    "model" => "Rel_users_groups",
                    "table" => "rel_users_groups",
                    "key_field" => "id_user",
                    "key_value" => $saved["data"]["id"],
                    "rel_field" => "id_group",
                    "rel_values" => (isset($values["id_group"]) ? $values["id_group"] : array())
                );
                $params_channels = array(
                    "module" => MOD_BACKEND,
                    "model" => "Rel_channels_users",
                    "table" => "rel_channels_users",
                    "key_field" => "id_user",
                    "key_value" => $saved["data"]["id"],
                    "rel_field" => "id_channel",
                    "rel_values" => (isset($values["id_channel"]) ? $values["id_channel"] : array())
                );
                parent::saveRelations($params_apps);
                parent::saveRelations($params_groups);
                parent::saveRelations($params_channels);
            }
            return $saved;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function delete($values)
    {
        try {
            if ((int) $values["id"] == 0) {
                $user = $this->get(array("where" => "username='" . $values["email"] . "'"));
                if (isset($user["data"][0])) {
                    $values["id"] = $user["data"][0]["id"];
                }
            }
            $deleted = parent::delete($values);
            if ($deleted["status"] == "OK") {
                $params_apps = array(
                    "module" => MOD_BACKEND,
                    "model" => "Rel_users_applications",
                    "table" => "rel_users_applications",
                    "key_field" => "id_user",
                    "key_value" => $values["id"],
                );
                $params_groups = array(
                    "module" => MOD_BACKEND,
                    "model" => "Rel_users_groups",
                    "table" => "rel_users_groups",
                    "key_field" => "id_user",
                    "key_value" => $values["id"],
                );
                $params_channels = array(
                    "module" => MOD_BACKEND,
                    "model" => "Rel_channels_users",
                    "table" => "rel_channels_users",
                    "key_field" => "id_user",
                    "key_value" => $values["id"],
                );
                parent::deleteRelations($params_apps);
                parent::deleteRelations($params_groups);
                parent::deleteRelations($params_channels);
            }
            return $deleted;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function logout($values)
    {
        try {
            $this->load->library("session");
            $this->session->set_userdata(array("logged" => false));
            $this->psession = $this->session;
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    public function authenticate($values)
    {
        try {
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
                $values["id_type_user"] = "81,85,87";
                $values["try"] = "LOCAL";
            }
            logGeneralCustom($this, $values, "Users::TryLogin", "username:" . $values["username"] . " password:" . md5($values["password"]));

            /***************************/
            /*Divert for mobile auth!  */
            /***************************/
            switch ((int) $values["id_app"]) {
                case 2: // credipaz, mobile
                case 5: // club redondo, mobile
                    /*para moviles*/
                    return $this->authenticateMobile($values);
                default:
                    /*para Intranet*/
                    $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
                    $users = $NETCORECPFINANCIAL->BridgeDirectAuthenticate($values);
                    if ($users["status"] != "OK") {throw new Exception(lang("error_5200"), 5200);}
                    return array(
                        "code" => "2000",
                        "status" => "OK",
                        "message" => "",
                        "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                        "data" => $users["data"][0]
                    );
            }
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function testUserValuePWA($values)
    {
        try {
            $sql = "";
            $posibles = true;
            if (!isset($values["password"])) {
                $values["password"] = "";
            }
            if (!isset($values["uid"])) {
                $values["uid"] = 0;
            }
            $id_app = (int) $values["id_app"];
            $id_type_user = 80; // default Credipaz, mobile
            $sufix = "credipaz.com"; // default Credipaz, mobile
            switch ($id_app) {
                case 2: // credipaz, mobile
                    $id_type_user = 80;
                    $sufix = "credipaz.com";
                    break;
                case 5: // club redondo, mobile
                    $id_type_user = 82;
                    $sufix = "clubredondo.com";
                    break;
            }

            $sql = "SELECT * FROM mod_backend_users WHERE id_type_user=" . $id_type_user . " AND documentNumber='" . $values["documentNumber"] . "' OR username='" . $values["documentNumber"] . "@" . $sufix . "'";
            if ($values["password"] != "") {
                $posibles = false;
                $sql = "SELECT * FROM mod_backend_users WHERE id_type_user=" . $id_type_user . " AND documentNumber='" . $values["documentNumber"] . "' AND password='" . md5($values["password"]) . "'";
            }
            $record = $this->getRecordsAdHoc($sql);
            $EXTERNAL = $this->createModel(MOD_BACKEND, "External", "External");
            $retNames = $EXTERNAL->getIdentityInformation($values);
            if (!isset($record[0]["id"])) {
                $names = [];
                if ($posibles) {foreach ($retNames["message"] as $item) {array_push($names, array("name" => $item["Value"], "viable" => 1, "IdSolicitud" => 0));}}
                return array(
                    "code" => "2000",
                    "status" => "OK",
                    "message" => "",
                    "names" => $names,
                    "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                    "exists" => false
                );
            } else {
                foreach ($retNames as $item) {$this->save(array("id" => $record[0]["id"]), array("viable" => 0));}
                return array(
                    "code" => "2000",
                    "status" => "OK",
                    "message" => "El valor ya existe",
                    "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                    "exists" => true
                );
            }
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function changePassword($values)
    {
        try {
            $values["email"] = base64_decode($values["email"]);
            $sql = "UPDATE mod_backend_users SET password='" . md5($values["password"]) . "' WHERE id_type_user=" . $values["id_type_user"] . " AND username='" . $values["email"] . "'";
            $this->dbLayerExecuteWS("nothing", $sql, "", null);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "El valor ya existe",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "verificated" => true
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function informUserArea($values)
    {
        try {
            $sql = "UPDATE " . MOD_BACKEND . "_users SET last_area='" . $values["last_area"] . "',last_access_area=getdate() WHERE id=" . $values["id_user_active"];
            $this->execAdHoc($sql);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function createTokenMobile($length, $id_user)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0987654321' . time();
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = $length; $i > 0; $i--) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $token_authentication = md5($randomString);
        $this->save(array("id" => $id_user), array("token_authentication" => $token_authentication));
        return $token_authentication;
    }
    
    /**
     * encarar el reemplazo por metodo unico de cpfinancials!
     */
    public function authenticateMobile($values)
    {
        try {
            if (isset($values["password"])) {$values["password"] = md5($values["password"]);} else {$values["password"] = "";}
            if ($values["field"] == "username") {$values["dni"] = explode("@", @$values["value"])[0];}
            $id = 0;
            $verificated = false;
            $id_app = (int) $values["id_app"];
            $id_type_user = 0;
            $username = $values["email"];
            $password = $values["password"];
            $dni = $values["dni"];
            $sex = $values["sex"];
            $area = $values["area"];
            $phone = $values["phone"];
            if (!isset($values["name"])) {$values["name"] = "";}
            if (trim($values["name"]) == "") {
                $sql = "SELECT * FROM DBClub.dbo.Persona WHERE NroDocumento='" . $values["dni"] . "'";
                $persona = $this->getRecordsAdHoc($sql);
                if (sizeof($persona) != 0) {
                    $values["name"] = $persona[0]["Nombre"] . " " . $persona[0]["Apellido"];
                } else {
                    $sql = "SELECT Nombre FROM DBCentral.dbo.AFIPpadron WHERE nDoc=" . $values["dni"];
                    $persona = $this->getRecordsAdHoc($sql);
                    if (sizeof($persona) != 0) {
                        $values["name"] = end(explode(" ", trim($persona[0]["Nombre"])));
                    }
                }
            }
            $documentName = $values["name"];
            switch ($id_app) {
                case 2: // credipaz, mobile
                    $id_type_user = 80;
                    break;
                case 5: // mediya, mobile
                    $id_type_user = 82;
                    break;
            }

            $sql = "UPDATE mod_backend_users SET documentnumber=username WHERE id_type_user=" . $id_type_user . " AND documentNumber='" . $dni . "'";
            $this->dbLayerExecuteWS("nothing", $sql, "", null);

            $user = $this->get(array("where" => "UPPER(username)='" . strtoupper($username) . "' AND id_type_user=" . $id_type_user));
            $token_authentication = "void";

            if ((int) $user["totalrecords"] != 0) {
                //con control de password
                //$user=$this->get(array("where"=>"username='".$username."' AND password='".$password."' AND id_type_user=".$id_type_user));
                //sin control de password
                $user = $this->get(array("where" => "UPPER(username)='" . strtoupper($username) . "' AND id_type_user=" . $id_type_user));
                $verificated = ($user["data"][0]["verified"] != null);
                $id = $user["data"][0]["id"];
                $user["data"][0]["documentName"] = $documentName;
            } else {
                $data = array(
                    'uid_firecloud' => '',
                    'username' => $username,
                    'documentType' => 'dni',
                    'documentNumber' => $dni,
                    'documentSex' => $sex,
                    'documentArea' => $area,
                    'documentPhone' => $phone,
                    'password' => $password,
                    'id_type_user' => $id_type_user,
                    'created' => $this->now,
                    'verified' => $this->now,
                    'fum' => $this->now,
                    'viable' => 0,
                    'documentName' => $documentName,
                    'IdSolicitud' => 0,
                    'id_application' => $id_app
                );
                $saved = $this->save(array("id" => 0), $data);
                if ($saved["status"] != "OK") {throw new Exception($saved["message"], (int) $saved["code"]);}
                if (isset($saved["data"]["id"])) {$id = $saved["data"]["id"];}
            }
            $token_authentication = $this->createTokenMobile(30, $id);
            $data = array('token_authentication' => $token_authentication, 'documentName' => $documentName);
            if ((int) $id != 0) {$saved = $this->save(array("id" => $id), $data);}
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "verificated" => $verificated,
                "token_authentication" => $token_authentication,
                "userdata" => $user["data"][0],
                "clubredondo" => getIdUserClubRedondo($this, $dni)["message"],
                "id" => $id
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
}
