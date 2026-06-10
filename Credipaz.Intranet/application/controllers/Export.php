<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
class Export extends MY_Controller {
    function __contruct(){
        parent::__construct();
    }


    function outPdf($html)
    {
        $base64_string = html2pdfBase64($this, base64_encode($html));
        $response = array('status' => 'success', 'pdf_base64' => $base64_string, 'data_url' => 'data:application/pdf;base64,' . $base64_string);
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
	function resumenLegal($id_operator_task){
        try {
			$OPERATORS_TASKS=$this->createModel(MOD_LEGAL,"operators_tasks","operators_tasks");
            $this->outPdf($OPERATORS_TASKS->buildResumenLegal($id_operator_task));
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
}
