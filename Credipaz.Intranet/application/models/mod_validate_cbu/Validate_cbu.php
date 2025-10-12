<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Validate_cbu extends My_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function form($values){
        try {
            $values["interface"]=(MOD_VALIDATE_CBU."/validate_cbu/form");
            return parent::form($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    function report($values){
        try {
            $data=[];
            if(!isset($values["cbu"])){$values["cbu"]="";}
            if(!isset($values["alias"])){$values["alias"]="";}
            switch ($values["type"]) {
                case "validate_cbu":
                    break;
                case "validate_alias":
                    break;
            }

            /*
            Build, the consult to external ws here!
            Auth, first... send bearer, second!
            */
            $data["data_type"]=$values["type"];
            $html=$this->load->view(MOD_VALIDATE_CBU."/validate_cbu/report",$data,true);
            return array("status"=>"OK","message"=>compress($this,$html),"compressed"=>true);
        } catch(Exception $e) {
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $html=$this->load->view(MOD_VALIDATE_CBU."/common/error",$data,true);
            return array("status"=>"OK","message"=>compress($this,$html),"compressed"=>true);
        }
    }
}
