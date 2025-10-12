<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class Landing extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }
    public function index($page=null){
        $this->load->library("session");
        $this->psession=$this->session->userdata;
        if($page==null){$page="features";}
        switch($page) {
           case "logout":
              $this->session->set_userdata(array("logged"=>false));
              break;
        }
        $this->status=$this->init();
        $data["title"] = TITLE_GENERAL;
        $data["title_page"] = TITLE_PAGE;
        $data["status"] = $this->status;
        $data["language"] = $this->language;
        $data["header"] = $this->load->view('landing/_header',$data, true);
        $data["session"] = $this->session->userdata;
        if ($this->session->has_userdata("logged") and $this->session->userdata["logged"]===true) {
            $data["navbar"] = $this->load->view('landing/_navbar_logged',$data, true);
        } else {
            $data["navbar"] = $this->load->view('landing/_navbar',$data, true);
        }
        $data["footer"] = $this->load->view('landing/_footer',$data, true);
        $data["controls"]=array("comboMasters"=>comboMasters($this));

        try {
            if (!$this->ready){throw new Exception(lang("error_5002"),5002);}
            $data["body"] = $this->load->view('mod_website/theme_simple/'.$page,$data, true);
            $this->load->view('mod_website/theme_simple/index',$data);
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
	}

    public function logLink(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'save';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'log_links';
            $_POST['table'] = 'log_links';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }

    public function crleads(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['target'] = 'crleads';
            $_POST['function'] = 'landing';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function tarjetabaja(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['target'] = 'tarjetabaja';
            $_POST['function'] = 'landing';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function tarjetaarrepentimiento(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['target'] = 'tarjetaarrepentimiento';
            $_POST['function'] = 'landing';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function efectivo(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['target'] = 'efectivo';
            $_POST['function'] = 'landing';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function tarjeta(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['target'] = 'tarjeta';
            $_POST['function'] = 'landing';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
	        $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function tiendamil()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['target'] = 'tiendamil';
            $_POST['function'] = 'landing';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function mediyaCanalDigital(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['target'] = 'mediyaCanalDigital';
            $_POST['function'] = 'landing';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
	        $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function mediyaContactCenter(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['target'] = 'mediyaContactCenter';
            $_POST['function'] = 'landing';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
	        $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function mediyaSucursales(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['target'] = 'mediyaSucursales';
            $_POST['function'] = 'landing';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
	        $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
}
