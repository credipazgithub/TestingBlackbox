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
{"txndate_processed":"11\/06\/26 9:06:13","ccbin":"551792","timezone":"America\/Buenos_Aires","number_of_installments":"1",
"oid":"C-9b47d431-cd42-42d0-b65b-21372a3f4df9","cccountry":"ARG","expmonth":"04","3747855e980890d84b1f":"","hash_algorithm":"SHA256",
"endpointTransactionId":"2413474179","currency":"032","processor_response_code":"00","chargetotal":"76328,00","terminal_id":"98254715",
"associationResponseCode":"00","approval_code":"Y:584498:0219204693:PPXX:2413474179","comments":"
"expyear":"2029","response_hash":"e3bad2da81a3c8cf21496082c02006f181f042736aedd94bf7e9f1be2a7ae758","tdate":"1781179573",
"installments_interest":"false","associationResponseMessage":"Approved or completed successfully","bname":"Antivero Pamela Adriana",
"ccbrand":"MASTERCARD","customerid":"0107137938","refnumber":"      241347","txntype":"sale","paymentMethod":"M",
"referencedMerchantTransactionID":"324767","merchantAdviceCodeIndicator":"  ","txndatetime":"2026:06:11-09:04:28","cardnumber":"(MASTERCARD) ... 1003",
"ipgTransactionId":"850219204693","cardFunction":"debit","status":"APROBADO"}
*/
  $_POST["comments"]='[{"Tipo":"CRE","Identificacion":1535048,"Importe":"130026.10","idTransfer":325307}]';
           $_POST["approval_code"]="Y:502266:0273224030:PPXX:8173094432";
            $_POST["status"]="APROBADO";
            $_POST["currency"]="032";
            $_POST["chargetotal"]="130026,10";
            $_POST["ccbrand"]="MASTERCARD";
            $_POST["bname"]="Gómez Lorena elisab";
            $_POST["cardnumber"]="(MASTERCARD) ... 5687";
            /*
            $_POST["comments"]='[{"Tipo":"TAR","Identificacion":"0114167038","Importe":"71300.00","idTransfer":302446},{"Tipo":"CRE","Identificacion":1547052,"Importe":"386647.00"}]';
            $_POST["approval_code"]="Y:765956:9012308699:PPXX:9611654723";
            $_POST["status"]="APROBADO";
            $_POST["currency"]="032";
            $_POST["chargetotal"]="592659,43";
            $_POST["ccbrand"]="VISA";
            $_POST["bname"]="Gómez Lorena elisab";
            $_POST["cardnumber"]="(VISA) ... 6022";
            */
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
