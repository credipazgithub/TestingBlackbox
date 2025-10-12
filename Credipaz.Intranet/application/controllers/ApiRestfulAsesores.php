<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class ApiRestfulAsesores extends MY_Controller {
    private $module=MOD_API_ASESORES;
    private $model="Asesores";
    private $table="Asesores";

    public function __construct() {
        parent::__construct();
    }
    public function listar(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function alta(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'altaModificar';
            $_POST['idAsesor'] = 0;
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function modificar()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['function'] = 'altaModificar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function deshabilitar(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'cambiarEstado';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $_POST['endpoint'] = "Deshabilitar";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function habilitar(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'cambiarEstado';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $_POST['endpoint'] = "Habilitar";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
}
