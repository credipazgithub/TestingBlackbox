<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Credipaz extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function getIfaceConfiguration($values)
    {
        $data = array(
            "colors" => array("brand" => "rgb(235,0,139)"),
            "images" => array("logo" => "https://intranet.credipaz.com/assets/logos/app-credipaz.png"),
            "texts" => array("title" => "Credipaz Mobile"),
            "timers" => array("shortTimerInterval" => 2500, "mediumTimerInterval" => 30000, "longTimerInterval" => 300000)
        );
        return array(
            "code" => "2000",
            "status" => "OK",
            "message" => "Interface configuration",
            "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
            "data" => $data,
            "compressed" => false
        );
    }
    public function firstStepAuth($values)
    {
        $CLUBREDONDOWS=$this->createModel(MOD_EXTERNAL,"ClubRedondoWS","ClubRedondoWS");
        return $CLUBREDONDOWS->getIdentityInformation($values);
    }
    public function secondStepAuth($values)
    {
        if(isset($values["email"])){
            $sql="SELECT * FROM mod_backend_users WHERE id_type_user=80 AND username='".$values["email"]."'";
            $ret=$this->getRecordsAdHoc($sql);
            if(isset($ret[0]["id"])){
                $values["dni"]=$ret[0]["documentNumber"];
                $values["sex"]=$ret[0]["documentSex"];
                $values["name"]=$ret[0]["documentName"];
                $values["area"]=$ret[0]["documentArea"];
                $values["phone"]=$ret[0]["documentPhone"];
                $values["IdSolicitud"]=$ret[0]["IdSolicitud"];
            }
        }
        $CLUBREDONDOWS=$this->createModel(MOD_EXTERNAL,"ClubRedondoWS","ClubRedondoWS");
        $result=$CLUBREDONDOWS->getUserInformation($values,"CP");

        $APPLICATIONS=$this->createModel(MOD_BACKEND,"Applications","Applications");
        $ret=$APPLICATIONS->get(array("id"=>2));
		$result["version"]=$ret["data"][0]["version"];
        return $result;
   }
}
