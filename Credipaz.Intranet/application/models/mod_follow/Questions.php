<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Questions extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $this->view="vw_questions";
            $values["order"]="type_segment_priority ASC, id ASC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>true,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"type_segment","format"=>"type"),
                array("field"=>"priority","format"=>"number"),
                array("field"=>"description","format"=>"shorten"),
                array("field"=>"type_protocol","format"=>"type"),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description")),
                array("name"=>"browser_id_type_segment", "operator"=>"=","fields"=>array("id_type_segment")),
                array("name"=>"browser_id_type_protocol", "operator"=>"=","fields"=>array("id_type_protocol")),
            );

            $values["controls"]=array(
                "<span class='badge badge-primary'>Segmento</span>".comboTypeSegments($this),
                "<span class='badge badge-primary'>Protocolo</span>".comboTypeProtocols($this),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $this->view="vw_questions";
            $values["interface"]=(MOD_FOLLOW."/questions/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_id_type_segment=array(
                "model"=>(MOD_FOLLOW."/Type_segments"),
                "table"=>"type_segments",
                "name"=>"id_type_segment",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_segment"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_protocol=array(
                "model"=>(MOD_FOLLOW."/Type_protocols"),
                "table"=>"type_protocols",
                "name"=>"id_type_protocol",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_protocol"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_control=array(
                "model"=>(MOD_BACKEND."/Type_controls"),
                "table"=>"type_controls",
                "name"=>"id_type_control",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_control"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
            $values["controls"]=array(
                "id_type_segment"=>getCombo($parameters_id_type_segment,$this),
                "id_type_protocol"=>getCombo($parameters_id_type_protocol,$this),
                "id_type_control"=>getCombo($parameters_id_type_control,$this),
            );
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
            $fields=null;
            if($id==0){
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'created' => $this->now,
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    'id_type_segment' => secureEmptyNull($values,"id_type_segment"),
                    'id_type_control' => secureEmptyNull($values,"id_type_control"),
                    'possible_values' => $values["possible_values"],
                    'id_type_protocol' => secureEmptyNull($values,"id_type_protocol"),
                    'priority' => $values["priority"],
                    'class' => $values["class"],
                );
            } else {
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'id_type_segment' => secureEmptyNull($values,"id_type_segment"),
                    'id_type_control' => secureEmptyNull($values,"id_type_control"),
                    'possible_values' => $values["possible_values"],
                    'id_type_protocol' => secureEmptyNull($values,"id_type_protocol"),
                    'priority' => $values["priority"],
                    'class' => $values["class"],
                );
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
