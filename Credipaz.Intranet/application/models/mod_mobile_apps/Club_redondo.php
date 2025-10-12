<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Club_redondo extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function getIfaceConfiguration($values){
        $data=array(
           "colors"=>array("brand"=>"rgb(235,0,139)"),
           "images"=>array("logo"=>INTRANET."/assets/logos/app-clubredondo.png"),
           "texts"=>array("title"=>"Mediya"),
           "timers"=>array("shortTimerInterval"=>2500,"mediumTimerInterval"=>30000,"longTimerInterval"=>300000)
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
    public function firstStepAuth($values){
        try {
            $CLUBREDONDOWS=$this->createModel(MOD_EXTERNAL,"ClubRedondoWS","ClubRedondoWS");
            return $CLUBREDONDOWS->getIdentityInformation($values);
        }
        catch (SOAPFault $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function secondStepAuth($values) {
        try {

            if(isset($values["email"]) && $values["email"]!=""){
                $sql="SELECT * FROM mod_backend_users WHERE id_type_user=82 AND username='".$values["email"]."'";
                $ret=$this->getRecordsAdHoc($sql);
                if(isset($ret[0]["id"])){
                    $values["dni"]=$ret[0]["documentNumber"];
                    $values["sex"]=$ret[0]["documentSex"];
                }
            } 
            $CLUBREDONDOWS=$this->createModel(MOD_EXTERNAL,"ClubRedondoWS","ClubRedondoWS");
            $result=$CLUBREDONDOWS->getUserInformation($values,"CR");
			
		    //Retrieve version data from applications
			$APPLICATIONS=$this->createModel(MOD_BACKEND,"Applications","Applications");
			$ret=$APPLICATIONS->get(array("id"=>5));
			$result["version"]=$ret["data"][0]["version"];
			/*
			$swiss=false;
			if (isset($values["dni"])){
				$ret=listFilesSSH(FILES_SWISS_SSH,"pdf",$values["dni"]);
				$swiss=(sizeof($ret)>0);
			}
			*/
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
				//"swiss"=>$swiss,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (SOAPFault $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function saveNewUser($values){
        $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
        $data = array(
            'username' => $values["username"],
            'password'=>md5($values["password"]),
            'documentType'=>"dni",
            'documentNumber'=>$values["documentNumber"],
            'documentSex'=>$values["documentSex"],
            'documentArea'=>$values["area"],
            'documentPhone'=>$values["phone"],
            'id_type_user'=>$values["id_type_user"],
            'created'=>$this->now,
            'verified'=>$this->now,
            'fum'=>$this->now,
            'viable'=>0,
            'documentName'=>$values["documentName"],
            'IdSolicitud'=>$values["IdSolicitud"],
            'id_application'=>5
        );
        $saved=$USERS->save(array("id"=>0),$data);
        if ($saved["status"]!="OK"){throw new Exception($saved["message"],(int)$saved["code"]);}
        $params=array("try"=>"LOCAL","username"=>$values["username"],"password"=>$values["password"]);
        return $USERS->authenticate($params);
    }
    public function forgotPassword($values)
    {
        $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
        $data = array(
            'id'=>'0',
            'email' => ($values["documentNumber"]."@".$values["sufix"]),
        );
        return $USERS->delete($data);
    }
}
