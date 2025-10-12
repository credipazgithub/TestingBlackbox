<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Sorteos_ranges extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>true,
            );
            $values["columns"]=array(
                array("field"=>"id","format"=>"code"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"date_from","format"=>"date"),
                array("field"=>"date_to","format"=>"date"),
                array("field"=>"","format"=>null),
            );
            $values["order"]="id DESC";
            $values["records"]=$this->get($values);
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_CLUB_REDONDO."/sorteos_ranges/abm");
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
            $id=(int)$values["id"];
            if($id==0){
                if ($fields==null){
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                        'date_from' => secureEmptyNull($values,"date_from"),
                        'date_to' => secureEmptyNull($values,"date_to"),
                    );
                }
            } else {
                if ($fields==null){
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'fum' => $this->now,
                        'date_from' => secureEmptyNull($values,"date_from"),
                        'date_to' => secureEmptyNull($values,"date_to"),
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
