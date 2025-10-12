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
            $_POST["comments"] = '[{"Tipo":"TAR","Identificacion":"0102413098","Importe":"1.00","idTransfer":40191}]';
            $_POST["approval_code"] = "Y:192178:4625678746:PPXX:1921784351";
            $_POST["status"] = "APROBADO";
            $_POST["currency"] = "032";
            $_POST["chargetotal"] = "1,00";
            $_POST["ccbrand"] = "VISA";
            $_POST["bname"] = "juan gomez";
            $_POST["cardnumber"] = "(VISA) ... 0005";
            $this->FiservOk(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function FiservError()
    {
        $this->FiservOk(false);
    }
    public function FiservOk($ok=true)
    {
        try {
            $data["get"] = $_GET;
            $data["post"] = $_POST;
            $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $CLUBREDONDOWS = $this->createModel(MOD_EXTERNAL, "ClubRedondoWS", "ClubRedondoWS");
            $TRANSACTIONS = $this->createModel(MOD_PAYMENTS, "Transactions", "Transactions");
            $comments = json_decode($_POST["comments"], true);
            $id = $comments[0]["idTransfer"];
            $record = $TRANSACTIONS->get(array("where" => "id=" . $id));
            $dni_request = $record["data"][0]["dni_request"];
            $status = $record["data"][0]["status"];

            if ($status == "INICIADO") {
                $registro_externo = explode(":", $_POST["approval_code"])[1];
                $params = array(
                    'id' => $id,
                    'status' => $_POST["status"],
                    'currency_response' => $_POST["currency"],
                    'dni_response' => "",
                    'amount_response' => str_replace(',', '.', $_POST["chargetotal"]),
                    'card_response' => $_POST["ccbrand"],
                    'card_name' => $_POST["bname"],
                    'partial_card_number' => $_POST["cardnumber"],
                    'message' => $_POST["approval_code"],
                    'raw_response' => serialize($_POST),
                    'registro_externo' => $registro_externo,
                );
                $saved = $NETCORECPFINANCIAL->PagosTerminarTransaccion($params);
                $page = "fiserv-error";
                if ($ok and $id != null) {
                    $page = "fiserv-ok";
                    if ($_POST["status"] == "APROBADO") {
                        $params2 = array(
                            "id" => $id,
                            "servicioPago" => "FSRV",
                            "NroDocumento" => $dni_request,
                            "TipoUsuario" => "CP",
                            "itemsPagos" => $comments,
                            "origen" => 7, //Web intranet - Btn de pago implementado!
                            "MedioPago" => $_POST["ccbrand"],
                            "Resultado" => $_POST["status"],
                            "Transaccion" => $registro_externo,
                            "Respuesta" => serialize($_POST),
                            "posProceso" => "pagosonline",
                            "Registro_externo" => (string) $registro_externo
                        );
                        $CLUBREDONDOWS->registrarCobranza($params2);
                    }
                }
            }
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
