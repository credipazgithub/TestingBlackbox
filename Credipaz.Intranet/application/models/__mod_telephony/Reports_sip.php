<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Reports_sip extends My_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function form($values){
        try {
            $values["interface"]=(MOD_TELEPHONY."/reports_sip/form");
            $parameters=array(
                "mode"=>"MULTISELECT",
                "model"=>(MOD_BACKEND."/users"),
                "table"=>"users",
                "name"=>"username",
                "class"=>"multiselect form-control dbase username",
                "empty"=>false,
                "id_actual"=>null,
                "id_field"=>"username",
                "description_field"=>"username",
                "get"=>array("where"=>"username in (SELECT username FROM ".MOD_TELEPHONY."_users_sip)","order"=>"username ASC","pagesize"=>-1),
            );
            $values["controls"]=array("username"=>getCombo($parameters,$this));
            return parent::form($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

}
