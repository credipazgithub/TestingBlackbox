<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Planes extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $this->view="dbcentral.dbo.planes";
            $values["fields"]="*";
            $values["order"]="descripcion ASC";
            $values["title"]="Planes";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"fechaAlta","format"=>"datetime"),
                array("field"=>"nombre","format"=>"text"),
                array("field"=>"descripcion","format"=>"text"),
                array("field"=>"estado","format"=>"status"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("nombre","descripcion")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
