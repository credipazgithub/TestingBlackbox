<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Providers extends MY_Model {
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
                "offline"=>false,
            );

            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"code","format"=>"code"),
                array("field"=>"social_name","format"=>"text"),
                array("field"=>"cuit","format"=>"text"),
                array("field"=>"iibb","format"=>"text"),
                array("field"=>"phone","format"=>"text"),
                array("field"=>"email","format"=>"email"),
            );

            $values["order"]="social_name ASC";
            $values["records"]=$this->get($values);
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","social_name","cuit","iibb","address")),
            );
            return parent::brow($values);
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
                    'social_name'=>$values["social_name"],
                    'address'=>$values["address"],
                    'location'=>$values["location"],
                    'zip_code'=>$values["zip_code"],
                    'province'=>$values["province"],
                    'phone'=>$values["phone"],
                    'cuit'=>$values["cuit"],
                    'iibb'=>$values["iibb"],
                    'email'=>$values["email"],
                    'min_reviews' => $values["min_reviews"],
                );
            } else {
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'social_name'=>$values["social_name"],
                    'address'=>$values["address"],
                    'location'=>$values["location"],
                    'zip_code'=>$values["zip_code"],
                    'province'=>$values["province"],
                    'phone'=>$values["phone"],
                    'cuit'=>$values["cuit"],
                    'iibb'=>$values["iibb"],
                    'email'=>$values["email"],
                    'min_reviews' => $values["min_reviews"],
                );
            }
            $saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){
               $params_sectors=array(
                    "module"=>MOD_PROVIDERS,
                    "model"=>"Rel_providers_type_sectors",
                    "table"=>"Rel_providers_type_sectors",
                    "key_field"=>"id_provider",
                    "key_value"=>$saved["data"]["id"],
                    "rel_field"=>"id_type_sector",
                    "rel_values"=>(isset($values["id_type_sector"]) ? $values["id_type_sector"] :array())
               );
               parent::saveRelations($params_sectors);
            }
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_PROVIDERS."/providers/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_id_type_sector=array(
                "model"=>(MOD_PROVIDERS."/Type_sectors"),
                "table"=>"type_sectors",
                "name"=>"id_type_sector",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_PROVIDERS."/Rel_providers_type_sectors"),"table"=>"rel_providers_type_sectors","id_field"=>"id_provider","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );
            $values["controls"]=array(
                "id_type_sector"=>getMultiSelect($parameters_id_type_sector,$this),
            );
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function getSectorsByProvider($values){
        try {
            $TYPE_SECTORS=$this->createModel(MOD_PROVIDERS,"Type_sectors","Type_sectors");
            $opts=array(
                    "where"=>"id IN (SELECT id_type_sector FROM ".MOD_PROVIDERS."_rel_providers_type_sectors WHERE id_provider=".$values["id_provider"].")",
                    "order"=>"description ASC",
                    "pagesize"=>-1);
            $records=$TYPE_SECTORS->get($opts);
            if ((int)$records["totalrecords"]==0){
                $opts=array("order"=>"description ASC","pagesize"=>-1);
                $records=$TYPE_SECTORS->get($opts);
            }
            return $records;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
