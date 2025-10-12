<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Beneficios_locations extends MY_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            if (!isset($values["code"])){$values["code"]=null;}
            $id=(int)$values["id"];
            if($id==0){
                if ($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                        'productId' => $values["productId"],
                        'address' => $values["address"],
                        'neighborhood' => $values["neighborhood"],
                        'place' => $values["place"],
                        'state' => $values["state"],
                        'lat' => $values["lat"],
                        'lng' => $values["lng"],
                        'id_beneficio' => $values["id_beneficio"],
                    );
                }
            } else {
                if ($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'fum' => $this->now,
                        'productId' => $values["productId"],
                        'address' => $values["address"],
                        'neighborhood' => $values["neighborhood"],
                        'place' => $values["place"],
                        'state' => $values["state"],
                        'lat' => $values["lat"],
                        'lng' => $values["lng"],
                        'id_beneficio' => $values["id_beneficio"],
                    );
                }
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
