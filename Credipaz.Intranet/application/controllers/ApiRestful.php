<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class ApiRestful extends MY_Controller {
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
            $_POST["password"] = "1";
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'authenticate';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $_POST['id_type_user'] = "77";
            $this->neocommand(true);
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
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'authenticate';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $_POST['id_type_user'] = "all";
            $_POST['callsource'] = "api";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
}


