<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class Verify extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
    }
    public function email($base64)
    {
        try {
            $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $NETCORECPFINANCIAL->verifyEmail($base64);
            $data["pre"] = "../";
            $this->load->view("common/_external_header", $data);
            $this->load->view(MOD_EXTERNAL . "/formVerify", $data);
            $this->load->view("common/_external_footer", $data);

        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
}
