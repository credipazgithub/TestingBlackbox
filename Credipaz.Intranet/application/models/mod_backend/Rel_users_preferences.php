<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Rel_users_preferences extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function save($values,$fields=null){
        try {
		    /*Reset user preferences for further reload*/
			$sql=("DELETE ".MOD_BACKEND."_Rel_users_preferences WHERE id_user=".$values["id_user_active"]);
			$this->execAdHoc($sql);
		    /*one for each preferencess, manually setted!*/
			$sql=("INSERT INTO ".MOD_BACKEND."_Rel_users_preferences (id_user,id_preference,value) VALUES (".$values["id_user_active"].",1,".$values["gridrows"].")");
			$this->execAdHoc($sql);
			$sql=("INSERT INTO ".MOD_BACKEND."_Rel_users_preferences (id_user,id_preference,value) VALUES (".$values["id_user_active"].",2,".$values["doctorsign"].")");
			$this->execAdHoc($sql);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null
                );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
