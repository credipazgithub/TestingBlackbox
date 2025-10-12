<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Load_medias extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
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
            if (!isset($values["image"])){$values["image"]=null;}
            if (!isset($values["phone"])){$values["phone"]=null;}
            $id=(int)$values["id"];
            $fields=null;
            if($id==0){
                $fields = array(
                  'code' => $values["code"],
                  'description' => $values["description"],
                  'created' => $this->now,
                  'verified' => $this->now,
                  'offline' => null,
                  'fum' => $this->now,
                  'id_type_load_media'=>$values["id_type_load_media"],
                  'id_user'=>$values["id_user_active"]
                );
            } else {
                $fields = array(
                    'description' => $values["description"],
                    'fum' => $this->now,
                );
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function delete($values){
        try {
           return $this->save(array("id"=>$values["id"],"offline"=>$this->now));
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
}
