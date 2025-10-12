<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Intranet extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function getIfaceConfiguration($values){
        $data=array(
           "colors"=>array("brand"=>"rgb(235,0,139)"),
           "images"=>array("logo"=>"https://intranet.credipaz.com/assets/logos/intranet.png"),
           "texts"=>array("title"=>"Intranet"),
           "timers"=>array("shortTimerInterval"=>5000,"mediumTimerInterval"=>60000,"longTimerInterval"=>300000)
        );
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"Interface configuration",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>$data,
            "compressed"=>false
        );
    }
    public function firstStepAuth($values)
    {
        return null;
    }
    public function secondStepAuth($values)
    {
        return null;
    }
    public function saveNewUser($values)
    {
        return null;
    }
    public function forgotPassword($values)
    {
        return null;
    }
}
