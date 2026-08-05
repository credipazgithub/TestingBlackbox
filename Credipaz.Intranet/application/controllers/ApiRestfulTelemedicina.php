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
    public function tiposemergencia()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = MOD_API_LOOKUPS;
            $_POST['model'] = "Lookups";
            $_POST['table'] = "NS_mod_telemedicina_type_emergency";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function tiposcierre()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = MOD_API_LOOKUPS;
            $_POST['model'] = "Lookups";
            $_POST['table'] = "NS_mod_telemedicina_type_tasks_close";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
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
    public function supervision(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'supervision';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function consultas(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'consultas';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function cancelar(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'cancelar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function postcierre(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'postcierre';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function solicitarambulancia(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'solicitarambulancia';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function atencionesanteriores(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'atencionesanteriores';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function mensajes(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'mensajes';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function farmalinkreceta(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'farmalinkreceta';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function atencionespontanea()
    {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'atencionespontanea';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $_POST['code'] = -999;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function grabarordenmedica(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'grabarordenmedica';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function grabarreceta(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'grabarreceta';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function grabaratencion(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'grabaratencion';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function cambiarestadodoctor(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'cambiarestadodoctor';
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
