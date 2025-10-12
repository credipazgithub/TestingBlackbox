<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class ApiRestfulCredipazLookups extends MY_Controller {
    private $module=MOD_API_LOOKUPS;
    private $model="Lookups";
    private $table="Lookups";

    public function __construct() {
        parent::__construct();
    }
    public function sucursales()
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
            $_POST['table'] = "NS_Sucursales_Activas";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function nacionalidades()
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
            $_POST['table'] = "Nacionalidad";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function estadosCiviles()
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
            $_POST['table'] = "EstadoCivil";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function ocupaciones()
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
            $_POST['table'] = "ocupacion";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function productos()
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
            $_POST['table'] = "producto_all";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function empresas()
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
            $_POST['table'] = "NS_Empresas";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function provincias()
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
            $_POST['table'] = "Provincia";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function sexos()
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
            $_POST['table'] = "Sexo";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
}
