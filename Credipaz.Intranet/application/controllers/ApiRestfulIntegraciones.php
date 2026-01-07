<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class ApiRestfulIntegraciones extends MY_Controller {
    private $module=MOD_API_CREDIPAZ;
    private $model="Integraciones";
    private $table="Integraciones";

    public function __construct() {
        parent::__construct();
    }

    public function GetClientsByDocument()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetClientsByDocument';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetProducts()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetProducts';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetProductsConsolidatedPosition()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $ip_address = $_SERVER["REMOTE_ADDR"];
            $this->status = $this->init();
            $_POST['function'] = 'GetProductsConsolidatedPosition';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetLoan(){
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetLoan';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetLoanFees() {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetLoanFees';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetLoanRates() {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetLoanRates';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetProductBankStatements(){
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetProductBankStatements';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetProductBankStatementFile(){
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetProductBankStatementFile';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function SendSmsToken()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'SendSmsToken';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }



    public function GetCreditCardCurrentBalances()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetCreditCardCurrentBalances';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetCreditCardCurrentMovements()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetCreditCardCurrentMovements';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetCreditCardStatementMovements()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetCreditCardStatementMovements';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetCreditCardDueDate()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetCreditCardDueDate';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetCreditCardDetails()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetCreditCardDetails';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetCreditCardExtensions()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetCreditCardExtensions';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetCreditCardStatements()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'GetCreditCardStatements';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function UpdateCreditCards()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['function'] = 'UpdateCreditCards';
            $_POST['module'] = $this->module;
            $_POST['model'] = $this->model;
            $_POST['table'] = $this->table;
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
}
