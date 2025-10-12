<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
class Users_sip extends My_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function form($values){
        try {
            $values["interface"]=(MOD_TELEPHONY."/users_sip/form");
            return parent::form($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    function register($values){
        try {
            $username=$values["username_active"];
            $data = array(
                'username' => $username,
                'sip_device' => $values["telephony_device"],
                'sip_username' => $values["telephony_username"],
                'sip_password' => $values["telephony_password"],
                'processed'=>date("Y-m-d H:i:s")
            );
            $users=$this->get(array("where"=>"username='".$username."'"));
            if(!isset($users["data"][0])){
                $this->save(array("id"=>0,$data));
            } else {
                $this->updateByWhere($data,"username='".$username."'");
            }
            return array("status"=>"OK","message"=>"");
        } catch(Exception $e) {
            return array("status"=>"ERROR","message"=>$e->getMessage());
        }
    }

}
