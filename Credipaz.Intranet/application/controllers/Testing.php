<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class Testing extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }
    public function IdemiaAuth()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'IdemiaAuth';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $_POST['modo'] = "TOTEM";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function IdemiaGetIdtx()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'IdemiaGetIdtx';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $_POST['idtx'] = '3458bd04-ba6c-4600-9d95-2b64959b728d';
            //$_POST['idtx'] = '65dde06f-ea14-4b71-919a-f17505e9349c';
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function ConsultarValidarDNI()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'ConsultarValidarDNI';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';

            $_POST['Producto'] = 'CONSULTA_DNI';
            $_POST['idtx'] = '07d91019-f5a9-4a43-9e60-9b2ec247c0f9';
            $_POST['idRequest'] = 49340;
            $_POST['Formato'] = "HTML";
            $_POST['FormatReport'] = "H";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function ConsultarValidarVida()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'ConsultarValidarVida';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';

            $_POST['Producto'] = 'CONSULTA_VIDA';
            $_POST['idtx'] = '93c2469b-5922-4bbe-8f8e-6daa776031a9';
            $_POST['idRequest'] = 49340;
            $_POST['Formato'] = "HTML";
            $_POST['FormatReport'] = "H";
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }

    public function getDataClienteTest()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'GetDataCliente';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $_POST['NroDocumento'] = '23044319';
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }

    public function testFinalCredito(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'onboardingFinalRequest';
            $_POST['module'] = MOD_ONBOARDING;
            $_POST['model'] = 'Requests_Core';
            $_POST['table'] = 'Requests_Core';
            $_POST['id_forzado'] = 47219;
            //$_POST['id_forzado'] = 26834;
            $_POST['CBU'] = '0140050203518150382783';

            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function rebuildCreditoEmitido(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'onboardingFinalRequest';
            $_POST['module'] = MOD_ONBOARDING;
            $_POST['model'] = 'Requests_Core';
            $_POST['table'] = 'Requests_Core';
			$_POST['id_forzado'] = 5100;
			$_POST['idSolicitudCredito'] = 785427;
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function testSMS(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'send';
            $_POST['module'] = MOD_SMS;
            $_POST['model'] = 'sms';
            $_POST['table'] = 'sms';
            $_POST['mensaje'] = 'Test SMS Neodata, via Teleprom';
            $_POST['telefono'] = '+5491161603238'; // Fernando
            $_POST['identificador'] = 'idxxxxxx';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getReverse(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'getReverse';
            $_POST['module'] = MOD_PLACES;
            $_POST['model'] = 'Places';
            $_POST['table'] = 'Places';
            $_POST['address'] = 'CHAVEZ	1063 ITUZAINGó';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
}
