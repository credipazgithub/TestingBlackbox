<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Crypto extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function readTokenJWT($values) {
        try {
            return decodeTokenJWT($values);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function createTokenJWT($values) {
        try {
            return encodeTokenJWT($values);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
