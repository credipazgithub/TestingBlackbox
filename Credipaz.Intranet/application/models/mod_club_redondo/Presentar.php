<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Presentar extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function form($values){
        try {
            $values["interface"]=(MOD_CLUB_REDONDO."/presentar/form");
            return parent::form($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
