<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class Pwa extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function AlertTelegramTiendaMil()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'AlertTelegramTiendaMil';
            $_POST['module'] = MOD_PUSH;
            $_POST['model'] = 'Telegram';
            $_POST['table'] = 'Telegram';
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function Tokenizar()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'Tokenizar';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function GetFormulario()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'GetFormulario';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function FirmarFormulario()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'FirmarFormulario';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function ValidateCBU(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'ValidateCBU';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    public function lookup2(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'lookup';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    public function GetHistorialDePagos(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'GetHistorialDePagos';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function GetCredenciales(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'GetCredenciales';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            //$_POST["NroDocumento"]=13760135;
            //$_POST["Tipo"]="SWISS";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function RecalcularImporteCuotaCredito(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'RecalcularImporteCuotaCredito';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            //$_POST["Capital"]=15000;
            //$_POST["Plan"]=427676;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
	public function pagosMercadoPago(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $MERCADO_PAGO=$this->createModel(MOD_EXTERNAL,"Mercado_pago","Mercado_pago");
			$return=$MERCADO_PAGO->pagosMercadoPago();
            $this->output($return);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    public function onboardingFirstVerificationCore(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'onboardingFirstVerification';
            $_POST['module'] = MOD_ONBOARDING;
            $_POST['model'] = 'requests_core';
            $_POST['table'] = 'requests_core';
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}

    public function onboardingSaveRequestCore(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'onboardingSaveRequest';
            $_POST['module'] = MOD_ONBOARDING;
            $_POST['model'] = 'requests_core';
            $_POST['table'] = 'requests_core';
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    public function onboardingGetRequestCore(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'onboardingGetRequest';
            $_POST['module'] = MOD_ONBOARDING;
            $_POST['model'] = 'requests_core';
            $_POST['table'] = 'requests_core';
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    public function getTransaccionOriginal()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'GetTransaccionOriginal';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function onCheckEnGestion()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'OnCheckEnGestion';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommandTransparent(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function onboardingFinalRequestCore(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'onboardingFinalRequest';
            $_POST['module'] = MOD_ONBOARDING;
            $_POST['model'] = 'requests_core';
            $_POST['table'] = 'requests_core';
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    public function onboardingFinalIdVerification(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'onboardingFinalIdVerification';
            $_POST['module'] = MOD_ONBOARDING;
            $_POST['model'] = 'requests_core';
            $_POST['table'] = 'requests_core';
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}

    /*--------------------------------------------*/
    public function getIdVideoFromChargeCode(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'getIdVideoFromChargeCode';
            $_POST['module'] = MOD_TELEMEDICINA;
            $_POST['model'] = 'Charges_codes';
            $_POST['table'] = 'Charges_codes';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getVademecums(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'get';
            $_POST['module'] = MOD_TELEMEDICINA;
            $_POST['model'] = 'Type_vademecum';
            $_POST['table'] = 'Type_vademecum';
            $_POST['page'] = 1;
            $_POST['pagesize'] = -1;
            $_POST['order'] = "description ASC";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function directEmail()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'directEmail';
            $_POST['module'] = MOD_EMAIL;
            $_POST['model'] = 'Email';
            $_POST['table'] = 'Email';
            $_POST["from"] = "intranet@mediya.com.ar";
            $_POST["alias_from"] = "intranet@mediya.com.ar";
            //$_POST["email"] = "daniel@neodata.com.ar";
            //$_POST["subject"] = "Solicitud de baja Mediya";
            //$_POST["body"] = "test";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }

    public function alertaDinamica($page){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'alertaDinamica';
            $_POST['module'] = MOD_EMAIL;
            $_POST['model'] = 'Email';
            $_POST['table'] = 'Email';
            $_POST['md5'] = $page;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function onboardingPoll(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'save';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'Polls';
            $_POST['table'] = 'Polls';
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    public function getTypeShortcuts(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'get';
            $_POST['module'] = MOD_ONBOARDING;
            $_POST['model'] = 'Type_shortcuts';
            $_POST['table'] = 'Type_shortcuts';
            $_POST['order'] = 'code ASC';
            $_POST['where'] = 'id NOT IN (4,5)';

            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    public function getCuponsRefactored(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'getCuponsRefactored';
            $_POST['module'] = MOD_CLUB_REDONDO;
            $_POST['model'] = 'beneficios';
            $_POST['table'] = 'beneficios';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getCuponImage(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'getImage';
            $_POST['module'] = MOD_CLUB_REDONDO;
            $_POST['model'] = 'beneficios';
            $_POST['table'] = 'beneficios';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getWebPost(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'getByCode';
            $_POST['module'] = MOD_WEB_POSTS;
            $_POST['model'] = 'Web_posts';
            $_POST['table'] = 'Web_posts';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getWebPostByType(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'getByCodeByType';
            $_POST['module'] = MOD_WEB_POSTS;
            $_POST['model'] = 'Web_posts';
            $_POST['table'] = 'Web_posts';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getSucursales(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'get';
            $_POST['order'] = 'description ASC';
            $_POST['pagesize'] = -1;
            $_POST['page'] = 1;
            $_POST['module'] = MOD_PLACES;
            $_POST['model'] = 'Sucursales';
            $_POST['table'] = 'Sucursales';
            $_POST['view'] = 'vw_sucursales';
			$this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
	public function getApplicationMobileFunction(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'applicationMobileFunction';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
			$this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
	public function getDirections(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'directions';
            $_POST['module'] = MOD_PLACES;
            $_POST['model'] = 'places';
            $_POST['table'] = 'places';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
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
    public function reAuthenticate(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            if (!isset($_POST["id_user_active"]) or $_POST["id_user_active"]=="") {throw new Exception(lang("error_5107"),5107);}
            if (!isset($_POST["token_authentication"]) or $_POST["token_authentication"]=="") {throw new Exception(lang("error_5109"),5109);}
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'reAuthenticate';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
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
    public function getMenuTree(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'getMenuTree';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getNavigate(){
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
    public function getNeoCommand(){
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
    public function getNeoCommandTransparent(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function expressFile(){
        $mime=getMimeType($_GET['file']);
        $bin=getFileBinSSH($_GET['file']);
        $this->output
            ->set_status_header(200)
            ->set_content_type($mime)
            ->set_output($bin)
            ->_display();
    }
    public function save(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'save';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function get(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['function'] = 'get';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function logGeneral()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'log_general';
            $_POST['table'] = 'log_general';
            $_POST['function'] = 'forceLogGeneral';
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }

    public function websockConnect()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'websocks';
            $_POST['table'] = 'websocks';
            $_POST['function'] = 'connect';
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function websockDisconnect()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'websocks';
            $_POST['table'] = 'websocks';
            $_POST['function'] = 'disconnect';
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
}
