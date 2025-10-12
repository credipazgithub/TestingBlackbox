<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Persons extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
            $this->view="vw_persons";
            $values["order"]="created DESC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>true,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"created","format"=>"date"),
                array("field"=>"document","format"=>"number"),
                array("field"=>"incident","format"=>"text"),
                array("field"=>"type_status","format"=>"type"),
                array("field"=>"type_protocol","format"=>"type"),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","incident","document")),
                array("name"=>"browser_id_type_status", "operator"=>"=","fields"=>array("id_type_status")),
                array("name"=>"browser_id_type_protocol", "operator"=>"=","fields"=>array("id_type_protocol")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Estado</span>".comboTypeStatusFollow($this),
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
            $profile=getUserProfile($this,$values["id_user_active"]);
            $this->view="vw_persons";
            $values["interface"]=(MOD_FOLLOW."/persons/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
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
            $values["controls"]=array(
                "id_type_protocol"=>getCombo($parameters_id_type_protocol,$this),
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
            if($id==0){
			    if ($fields==null) {
					$fields = array(
						'code' => $values["code"],
						'description' => $values["description"],
						'created' => $this->now,
						'verified' => $this->now,
						'offline' => null,
						'fum' => $this->now,
						'module_sinister' => $values["module_sinister"],
						'name' => $values["name"],
						'surname' => $values["surname"],
						'document' => $values["document"],
						'test_confirm' => $values["test_confirm"],
						'test_type' => $values["test_type"],
						'test_date' => $values["test_date"],
						'personal_phone' => $values["personal_phone"],
						'family_phone' => $values["family_phone"],
						'family_contact' => $values["family_contact"],
						'family_relation' => $values["family_relation"],
						'email' => $values["email"],
						'id_user'=>$values["id_user_active"],
						'id_user_asigned'=>null,
						'id_type_status' => 1,
						'id_type_protocol' => secureEmptyNull($values,"id_type_protocol"),
					);
				}
            } else {
				if ($fields==null) {                
					$fields = array(
						'code' => $values["code"],
						'description' => $values["description"],
						'fum' => $this->now,
						'module_sinister' => $values["module_sinister"],
						'name' => $values["name"],
						'surname' => $values["surname"],
						'document' => $values["document"],
						'test_confirm' => $values["test_confirm"],
						'test_type' => $values["test_type"],
						'test_date' => $values["test_date"],
						'personal_phone' => $values["personal_phone"],
						'family_phone' => $values["family_phone"],
						'family_contact' => $values["family_contact"],
						'family_relation' => $values["family_relation"],
						'email' => $values["email"],
						'id_user'=>$values["id_user_active"],
						'id_user_asigned'=>secureEmptyNull($values,"id_user_asigned"),
						//'id_type_status' => secureEmptyNull($values,"id_type_status"),
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
