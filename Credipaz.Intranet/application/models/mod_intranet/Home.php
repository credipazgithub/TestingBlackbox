<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Home extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function form($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
			$values["profile"]=$profile;
            $values["interface"]=(MOD_INTRANET."/home/form");

            return parent::form($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
