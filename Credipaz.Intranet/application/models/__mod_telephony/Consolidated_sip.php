<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Consolidated_sip extends My_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function form($values){
        try {
            $values["interface"]=(MOD_TELEPHONY."/consolidated_sip/form");
            return parent::form($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
