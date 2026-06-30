<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class Webhooks extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function FiservOkTest()
    {
        try {
        /*
{"txndate_processed":"25\/06\/26 10:51:36","ccbin":"551792","timezone":"America\/Buenos_Aires",
"number_of_installments":"1","oid":"C-9cddb571-87b1-4934-8bf4-fc37a5c878c6","c8bfbac831f8a5eb709f":"",
"cccountry":"ARG","expmonth":"12","hash_algorithm":"SHA256","endpointTransactionId":"4198814708",
"currency":"032","processor_response_code":"00","chargetotal":"267913,01","terminal_id":"98254715",
"associationResponseCode":"00","approval_code":"Y:554848:0320154219:PPXX:4198814708",
"comments":"[{"Tipo":"CRE","Identificacion":1556607,"Importe":"102385.01","idTransfer":1},{"Tipo":"CRE","Identificacion":1556607,"Importe":"86328.00"},{"Tipo":"CRE","Identificacion":1556607,"Importe":"79200.00"}]",
"expyear":"2029","response_hash":"30dbddd588cb5f561bc955f49474a61b9619910bfe8cb5f6cb3c214af985d795","tdate":"1782395496",
"installments_interest":"false","associationResponseMessage":"Approved or completed successfully",
"bname":"Ariotti Alberto m","ccbrand":"MASTERCARD","customerid":"1556607","refnumber":"      419881",
"txntype":"sale","paymentMethod":"M","referencedMerchantTransactionID":"1","merchantAdviceCodeIndicator":"  ",
"txndatetime":"2026:06:25-10:47:53","cardnumber":"(MASTERCARD) ... 7004","ipgTransactionId":"850320154219",
"cardFunction":"debit","status":"APROBADO"}*/
  $_POST["comments"]='[{"Tipo":"CRE","Identificacion":1556607,"Importe":"102385.01","idTransfer":1},{"Tipo":"CRE","Identificacion":1556607,"Importe":"86328.00"},{"Tipo":"CRE","Identificacion":1556607,"Importe":"79200.00"}]';
           $_POST["approval_code"]="Y:554848:0320154219:PPXX:4198814708";
            $_POST["status"]="APROBADO";
            $_POST["currency"]="032";
            $_POST["chargetotal"]="267913,01";
            $_POST["ccbrand"]="MASTERCARD";
            $_POST["bname"]="Ariotti Alberto m";
            $_POST["cardnumber"]="(MASTERCARD) ... 7004";
            $this->FiservOk();
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function FiservError()
    {
        $this->FiservOk("fiserv-error");
    }
    public function FiservNotify()
    {
        $this->FiservOk("fiserv-notify");
    }
    public function FiservOk($page="fiserv-ok")
    {
        try {
            $data["get"] = $_GET;
            $data["post"] = $_POST;
		    $comments=json_decode($_POST["comments"], true);
            $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $NETCORECPFINANCIAL->Webhook($_POST);
            /*Funcionalidad consolidada de registro de pagos*/
            $NETCORECPFINANCIAL->PagoFiserv($page, $_POST,json_encode($comments));
            $this->load->view("common/_external_header", $data);
            $data["post"] = $_POST;
            $data["get"] = $_GET;
            $this->load->view(MOD_PAYMENTS . "/payments/" . $page, $data);
            $this->load->view("common/_external_footer", $data);
        } catch (Exception $e) {
            $data["code"] = $e->getCode();
            $data["message"] = $e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error', $data);
        }
    }

    public function CardCred()
    {
        try {
            $ip_address = $_SERVER["REMOTE_ADDR"];
            /*-----------------------------*/
            /*Evaluacion de IP en Whitelist*/
            /*-----------------------------*/
            if (!in_array("ALL", CARDCRED_WHITELIST)) {
                if (!in_array($ip_address, CARDCRED_WHITELIST)) {
                    throw new Exception("NO PROCESADO");
                }
            }
            /*-----------------------------*/
            $input = file_get_contents('php://input');
            if ($input == "") {
                $input = "Empty";
            }
            $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $decoded_data = json_decode($input, true);

            $scope = "Error";
            $raw_data = $input;
            $error_flag = 1;
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_data)) {
                $scope = $decoded_data[0]["scope"];
                $raw_data = json_encode($decoded_data);
                $error_flag = 0;
            }
            $values = array("raw_data" => $raw_data, "scope" => $scope, "referrer" => $ip_address, "error_flag" => $error_flag);
            $NETCORECPFINANCIAL->saveCardCred($values);
            $this->output->set_status_header(200)->set_content_type("text/html", 'utf-8')->set_output("RECIBIDO");
        } catch (Exception $e) {
            $this->output->set_status_header(403)->set_content_type("text/html", 'utf-8')->set_output("NO PROCESADO");
        }
    }
    public function Visa()
    {
        try {
            $ip_address = $_SERVER["REMOTE_ADDR"];
            /*-----------------------------*/
            /*Evaluacion de IP en Whitelist*/
            /*-----------------------------*/
            if (!in_array("ALL", VISA_WHITELIST)) {
                if (!in_array($ip_address, VISA_WHITELIST)) {
                    throw new Exception("NO PROCESADO");
                }
            }
            /*-----------------------------*/
            $input = file_get_contents('php://input');
            if ($input == "") {
                $input = "Empty";
            }
            $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $decoded_data = json_decode($input, true);

            $scope = "Error";
            $raw_data = $input;
            $error_flag = 1;
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_data)) {
                $scope = $decoded_data[0]["Message"];
                $raw_data = json_encode($decoded_data);
                $error_flag = 0;
            }
            $values = array("raw_data" => $raw_data, "scope" => $scope, "referrer" => $ip_address, "error_flag" => $error_flag);
            $NETCORECPFINANCIAL->saveVisa($values);
            $this->output->set_status_header(200)->set_content_type("text/html", 'utf-8')->set_output("RECIBIDO");
        } catch (Exception $e) {
            $this->output->set_status_header(403)->set_content_type("text/html", 'utf-8')->set_output("NO PROCESADO");
        }
    }
}
