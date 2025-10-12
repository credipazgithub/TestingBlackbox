<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class filesystem extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function fileLoader($values){
        try {
            $file=base64_decode($values["data"]);
            $filename=basename($file);
            $ret["message"]=getFileBinSSH($file);
            $ret["mime"]=getMimeType($file);
            $ret["mode"]=$values["mode"];
            $ret["filename"]=$filename;
            $ret["indisk"]=true;
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
