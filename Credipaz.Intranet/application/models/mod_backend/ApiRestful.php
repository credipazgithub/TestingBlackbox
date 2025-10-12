<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class ApiRestful extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function form($values){
        try {

            $FUNCTIONS=$this->createModel(MOD_BACKEND,"Functions","Functions");
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_add_credit_cards"));
            $data["menu"] = $FUNCTIONS->menuAPI($values);
            $html=$this->load->view(MOD_BACKEND."/apirestful/form",$data,true);
            logGeneral($this,$values,__METHOD__);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
