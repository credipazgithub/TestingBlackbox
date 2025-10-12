<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class ApiRestfulWebsitesLookups extends MY_Controller {
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
    public function canalesDeLandings()
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
            $_POST['table'] = "Producto";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function canalesDeAcciones()
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
            $_POST['table'] = "producto_acciones";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
}
