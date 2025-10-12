<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class ReceiverBase64 extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    
    public function dataToLocal($values){
       if(!isset($values["behaviour"])){$values["behaviour"]=null;}
       if(!isset($values["extension"])){$values["extension"]="";}
       if ($values["extension"]==""){$values["extension"]="";}
       $code=opensslRandom(16);
       $fullpath=(FILES_LOCAL.("/".$code.".".$values["extension"]));
       saveBase64ToFile(array("data"=>$values["base64"],"path"=>FILES_LOCAL,"fullPath"=>$fullpath));
       return array(
          "code"=>"2000",
          "status"=>"OK",
          "message"=>"",
          "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
       );
    }
}
