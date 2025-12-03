<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class Mobile extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function getIfaceConfiguration(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'getIfaceConfiguration';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function authenticate(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
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
    public function authenticateTransparent(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'authenticate';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $_POST['transparent'] = true;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }

    public function getIdentityInformation(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'getIdentityInformation';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getUserInformation(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'getUserInformation';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function saveNewUser(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'saveNewUser';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function forgotPassword(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'forgotPassword';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getNavigateMobile(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getTokenFirecloud(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'tokenFireCloud';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function saveMobile(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
}
