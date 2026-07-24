<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class ApiRestfulTelemedicina extends MY_Controller {
    private $module=MOD_API_TELEMEDICINA;
    private $model="Telemedicina";
    private $table="Telemedicina";

    public function __construct() {
        parent::__construct();
    }
    public function monitoreo(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'monitoreo';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
}
