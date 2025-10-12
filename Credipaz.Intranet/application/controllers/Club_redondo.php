<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class Club_redondo extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }
    public function authenticate(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            if (!isset($_POST["username"])) {throw new Exception(lang("error_5104"),5104);}
            if (!isset($_POST["password"])) {throw new Exception(lang("error_5105"),5105);}
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'authenticate';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getClubRedondoSocioByDni(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'GetClubRedondoSocioByDni';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'ClubRedondoWS';
            $_POST['table'] = 'ClubRedondoWS';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
}
