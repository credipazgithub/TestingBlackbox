<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Iframes extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function form($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
            $id_sucursal=$profile["data"][0]["nIDSucursal"];
            if ($id_sucursal=="") {$id_sucursal=100;}
            $sep="?";
            if (strpos($values["table"],"?")!==false){$sep="&";}

            $values["table"].=$sep."id_user_active=".$values["id_user_active"]."&username=".$values["username_active"]."&id_sucursal=[ID_SUCURSAL]&sucursal=[SUCURSAL]";//.$id_sucursal;
            $data["parameters"]=$values;
            $html=$this->load->view(MOD_EXTERNAL."/iframe/form",$data,true);
            logGeneral($this,$values,__METHOD__);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}

