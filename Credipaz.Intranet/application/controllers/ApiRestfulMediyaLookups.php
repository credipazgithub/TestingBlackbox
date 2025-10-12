<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class ApiRestfulMediyaLookups extends MY_Controller {
    private $module=MOD_API_LOOKUPS;
    private $model="Lookups";
    private $table="Lookups";

    public function __construct() {
        parent::__construct();
    }
    public function nacionalidades(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "NacionalidadDbClub";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function estadosCiviles(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "EstadoCivilDbClub";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function ocupaciones(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "OcupacionDbClub";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function modosDePago(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "ModoPagoDBClubAPI";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function tarjetasHabilitadas(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "MarcaFormaPagoDBClubAPI";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function tiposDeAdicionales()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "TipoAdicionalDBClubAPI";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function parentescos()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "ParentescoDbClub";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function farmacias()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['function'] = 'beneficios';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "farmacias";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function centros()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['function'] = 'beneficios';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "centrosMedicos";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function tiposDeCentros()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['function'] = 'listar';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "tiposDeCentrosMedicos_dbIntranet";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function planes()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'planes';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "planesMediya";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function comercializadoras()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'comercializadoras';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = "comercializadoras";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
}
