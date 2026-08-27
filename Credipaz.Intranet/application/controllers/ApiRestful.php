<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class ApiRestful extends MY_Controller {
    private $module=MOD_API_ROOT;
    private $model="Base";
    private $table="Base";

    public function __construct() {
        parent::__construct();
    }
    public function status()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST["id_app"] = 11;
            $_POST["username"] = "neodata";
            $_POST["password"] = "wQ5GEeN5Fz%hSB\$sFeUi";
            $_POST['function'] = 'authenticate';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $_POST['id_type_user'] = "77";
            $_POST['status'] = 1;
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function authenticate(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $id_app=$_POST["id_app"];
            if (!isset($_POST["id_app"])) {throw new Exception(lang("error_5120"),5120);}
            if ($id_app==null) {throw new Exception(lang("error_5120"),5120);}
            if ($id_app=="") {throw new Exception(lang("error_5120"),5120);}
            if ($id_app=="0") {throw new Exception(lang("error_5120"),5120);}
            if ($id_app==0) {throw new Exception(lang("error_5120"),5120);}

            if (!isset($_POST["username"])) {throw new Exception(lang("error_5104"),5104);}
            if (!isset($_POST["password"])) {throw new Exception(lang("error_5105"),5105);}
            $_POST['function'] = 'authenticate';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $_POST['id_type_user'] = "all";
            $_POST['callsource'] = "api";
            $_POST['external_operator'] = "1";
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function authenticateexternal() {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'authenticate';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $_POST['id_type_user'] = "all";
            $_POST['callsource'] = "api";
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function verifytoken() {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'verifytoken';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function documentationinterface() {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'documentationinterface';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function menuinterface() {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'menuinterface';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function authenticate(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $id_app=$_POST["id_app"];
            if (!isset($_POST["id_app"])) {throw new Exception(lang("error_5120"),5120);}
            if ($id_app==null) {throw new Exception(lang("error_5120"),5120);}
            if ($id_app=="") {throw new Exception(lang("error_5120"),5120);}
            if ($id_app=="0") {throw new Exception(lang("error_5120"),5120);}
            if ($id_app==0) {throw new Exception(lang("error_5120"),5120);}

            if (!isset($_POST["username"])) {throw new Exception(lang("error_5104"),5104);}
            if (!isset($_POST["password"])) {throw new Exception(lang("error_5105"),5105);}
            $_POST['function'] = 'authenticate';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $_POST['id_type_user'] = "all";
            $_POST['callsource'] = "api";
            $_POST['external_operator'] = "1";
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function authenticateMobile($values)
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'authenticateMobile';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function userValuePwa($values)
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['function'] = 'userValuePwa';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function userInformation($values)
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['function'] = 'userInformation';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function resetUserMobile($values)
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['function'] = 'resetUserMobile';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
}
