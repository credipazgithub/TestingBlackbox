<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Type_sectors extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>true,
                "offline"=>false,
            );
            $values["records"]=$this->get($values);
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_PROVIDERS."/type_sectors/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_id_group=array(
                "model"=>(MOD_BACKEND."/Groups"),
                "table"=>"groups",
                "name"=>"id_group",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_PROVIDERS."/Rel_type_sectors_groups"),"table"=>"rel_type_sectors_groups","id_field"=>"id_type_sector","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );
            $values["controls"]=array(
                "id_group"=>getMultiSelect($parameters_id_group,$this),
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
                if($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'fum' => $this->now,
                    );
                }
            }
            $saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){
               $params_groups=array(
                    "module"=>MOD_PROVIDERS,
                    "model"=>"Rel_type_sectors_groups",
                    "table"=>"rel_type_sectors_groups",
                    "key_field"=>"id_type_sector",
                    "key_value"=>$saved["data"]["id"],
                    "rel_field"=>"id_group",
                    "rel_values"=>(isset($values["id_group"]) ? $values["id_group"] :array())
               );
               parent::saveRelations($params_groups);
            }
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
