<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Canjes extends MY_Model {
    private $QRVerify="https://www.credipaz.com";

    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"","format"=>null),
                array("field"=>"code","format"=>"code"),
                array("field"=>"description","format"=>"text"),
            );

            $values["order"]="description ASC";
            $values["records"]=$this->get($values);
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {

            if (!isset($values["id"])){$values["id"]=0;}
            if (!isset($values["code"])){$values["code"]=null;}
            if (!isset($values["verification"])){$values["verification"]=null;}
            $id=(int)$values["id"];
            $fields=null;
            if ($id==0){
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'created' => $this->now,
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    'id_beneficio'=>secureEmptyNull($values,"id_beneficio"),
                    'id_club_redondo'=>secureEmptyNull($values,"id_club_redondo"),
                    'id_type_status_canje'=>1,
                    'verification'=>$values["verification"],
                    'qr_code'=>$values["qr_code"],
                    'status_canje'=>$values["status_canje"],
                    'message_canje'=>$values["message_canje"],
                );
            } else {
                if (!isset($values["id_type_status_canje"])){$values["id_type_status_canje"]=1;} // DEFAULT CIERRE ok CANJE EN UPDATE
                $BENEFICIOS=$this->createModel(MOD_CLUB_REDONDO,"Beneficios","Beneficios");
                $record=$BENEFICIOS->get(array("page"=>1,"where"=>"id=".$values["id_beneficio"]));
                if ((int)$record["data"][0]["lat"]!=0 and (int)$record["data"][0]["lng"]!=0) {
                    $geoCupon=$BENEFICIOS->getCupons(array("id_beneficio"=>$values["id_beneficio"],"radio"=>"0.2","mode_categoria"=>"reimprimir","lat"=>$values["lat"],"lng"=>$values["lng"]));
                    //Si se reimprime a <200 mts de geo beneficio, se cierra el canje
                    if ($geoCupon["totalrecords"]!=0){$values["id_type_status_canje"]=2;}
                };
                $fields = array(
                    'fum' => $this->now,
                    'id_type_status_canje'=>$values["id_type_status_canje"],
                );
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function dropCanjes(){
        $fields=array("id_type_status_canje"=>"3"); // Failed, first offline status, with NO canje
        $this->updateByWhere($fields,"getdate()>DATEADD(day,1,created) AND id_type_status_canje=1");
        $fields=array("id_type_status_canje"=>"4"); // Droped, second offline status, with NO canje
        $this->updateByWhere($fields,"getdate()>DATEADD(day,2,created) AND id_type_status_canje=3");
    }
}
