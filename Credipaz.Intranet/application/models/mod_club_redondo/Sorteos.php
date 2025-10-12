<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Sorteos extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function participar($values){
        try {
            $code="";
            switch((int)$values["id_app"]) {
               case 2: // Credipaz
                  $code="SORTEO-CREDIPAZ";
                  break;
               case 5: // Club Redondo
                  $code="SORTEO-CLUB REDONDO";
                  break;
               default:
                  $code="SORTEO-OTRO";
                  break;
            }

            if (!isset($values["id_sorteo_range"])){$values["id_sorteo_range"]=null;}
            $id_credipaz=$values["id_credipaz"];
            if ($id_credipaz==0){$id_credipaz=null;}
            $id_club_redondo=$values["id_club_redondo"];
            if ($id_club_redondo==0){$id_club_redondo=null;}
            $fields=array(
                'code' =>$code,
                'description' => "Participar en sorteo",
                'created' => $this->now,
                'verified' => $this->now,
                'fum' => $this->now,
                'id_user' => $values["id_user_active"],
                'id_club_redondo' => $id_club_redondo,
                'id_credipaz' => $id_credipaz,
                'id_sorteo_range' => $values["id_sorteo_range"],
            );
            $this->save(array("id"=>0),$fields);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }


}
