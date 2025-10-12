<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Type_status extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"code","format"=>"code"),
                array("field"=>"description","format"=>"text"),
            );
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
}
