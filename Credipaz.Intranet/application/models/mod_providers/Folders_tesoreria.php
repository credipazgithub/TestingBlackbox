<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Folders_tesoreria extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            //$profile=getUserProfile($this,$values["id_user_active"]);
            //$fullsystem=(evalPermissions("FULLSYSTEM",$profile["data"][0]["groups"]));
            $this->view="vw_folders";
            if($values["where"]!=""){$values["where"].=" AND ";}
            if(isset($values["forced_field"]) and isset($values["forced_value"])) {$values["where"]=($values["forced_field"]."='".$values["forced_value"]."' AND ");}
            $values["where"].="id_type_control_point !=1";
            $values["order"]="date_pay ASC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>array(
                    "conditions"=>array(
                           array("field"=>"id_type_control_point","operator"=>"=","value"=>"3"),
                        )
                    ),
                "delete"=>false,
                "offline"=>false,
            );

            $values["columns"]=array(
                array("field"=>"id","format"=>"number"),
                array("field"=>"date_pay","format"=>"date"),
                array("field"=>"provider","format"=>"text"),
                //array("field"=>"type_folder","format"=>"type"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"type_control_point","format"=>"type"),
                //array("field"=>"type_control_point","html"=>$ddChangeStatus,"format"=>"html#block"),
                array("field"=>"reviews","format"=>"status"),
                array("field"=>"aprovals","format"=>"status"),
                array("field"=>"","format"=>null),
            );

            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("id","code","description","keywords")),
                array("type"=>"date","name"=>"browser_date_from", "operator"=>">=","fields"=>array("date_pay")),
                array("type"=>"date","name"=>"browser_date_to", "operator"=>"<=","fields"=>array("date_pay")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>".lang('p_date_from')."</span> <input id='browser_date_from' name='browser_date_from' type='date' class='form-control'/>",
                "<span class='badge badge-primary'>".lang('p_date_to')."</span> <input id='browser_date_to' name='browser_date_to' type='date' class='form-control'/>",
            );
            $values["conditionalBackground"]=array(
                array("field"=>"id_type_control_point","value"=>"3","color"=>"lightgreen"),
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
            $values["godaction"]=(evalPermissions("GODACTION",$profile["data"][0]["groups"]));
            if (!$values["godaction"]){$values["godaction"]=(evalPermissions("ADMINISTRACION TESORERIA",$profile["data"][0]["groups"]));}
            $this->view="vw_folders";
            $values["interface"]=(MOD_PROVIDERS."/folders/abm_userview");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $opts=array(
                "module"=>MOD_PROVIDERS,
                "model"=>"Folder_items",
                "view"=>"vw_folder_items",
                "where"=>"id_folder=".$values["id"],
                "fields"=>"*,(SELECT count(id) FROM ".MOD_PROVIDERS."_folder_items_log as l WHERE l.id_folder_item=".MOD_PROVIDERS."_vw_folder_items.id AND l.id_user=".$values["id_user_active"].") as viewed",
            );
            $values["attached_files"] = parent::getAttachments($values,$opts);

            $parameters_id_type_status_contable=array(
                "model"=>(MOD_PROVIDERS."/Type_status_contables"),
                "table"=>"Type_status_contables",
                "name"=>"id_type_status_contable",
                "class"=>"form-control dbase id_type_status_contable",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_status_contable"),
                "id_field"=>"id",
                "description_field"=>"description"
            );

            $ops=array();
            $TYPE_CONTROL_POINTS=$this->createModel(MOD_PROVIDERS,"Type_control_points","Type_control_points");
            $records=$TYPE_CONTROL_POINTS->get(array("where"=>"id IN (9)","order"=>"id ASC","pagesize"=>-1));
            //$records=$TYPE_CONTROL_POINTS->get(array("order"=>"id ASC","pagesize"=>-1));
            foreach($records["data"] as $record){
                array_push($ops,array("name"=>secureField($record,"description"),"class"=>"btn-folder-change-status","datax"=>"data-module='MOD_PROVIDERS' data-status='".secureField($record,"id")."' data-id=".$values["id"]));
            };
            $values["ddChangeStatus"]=getButtonRibbon(array("class"=>"btn-primary btn-title-change-status-".$values["id"],"name"=>$values["records"]["data"][0]["type_control_point"]),$ops);
            $FOLDERS_LOG=$this->createModel(MOD_PROVIDERS,"Folders_log","Folders_log");
            $FOLDERS_LOG->view="vw_folders_log";
            $folders_log=$FOLDERS_LOG->get(array("order"=>"created DESC","where"=>"id_folder=".$values["id"],"pagesize"=>-1));
            $values["folders_log"]=$folders_log["data"];
            $TYPE_SECTORS=$this->createModel(MOD_PROVIDERS,"Type_sectors","Type_sectors");
            $type_sectors=$TYPE_SECTORS->get(array("order"=>"description ASC","where"=>"id IN (SELECT id_type_sector FROM ".MOD_PROVIDERS."_rel_folders_type_sectors WHERE id_folder=".$values["id"].")","pagesize"=>-1));
            $values["type_sectors"]=$type_sectors["data"];
            $values["controls"]=array(
                "id_type_status_contable"=>getCombo($parameters_id_type_status_contable,$this),
            );
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            $FOLDERS=$this->createModel(MOD_PROVIDERS,"Folders","Folders");
            $fields = array(
                'fum' => $this->now,
                'id_type_status_contable' => secureEmptyNull($values,"id_type_status_contable"),
            );
			$ret=$FOLDERS->updateByWhere($fields,"id='".$values["id"]."'");

            $inner=array(
                "code"=>null,
                "description"=>null,
                "created"=>$this->now,
                "verified"=>$this->now,
                "fum"=>$this->now,
                "id_folder"=>$values["id"],
                "id_user"=>$values["id_user_active"],
                "keywords"=>"=",
                "mime"=>null,
                "data"=>null,
                "basename"=>null,
                "id_type_folder_item"=>"=",
                "priority"=>null,
            );
            $opts=array("module"=>MOD_PROVIDERS,"model"=>"Folder_items","new"=>"new-folder-items","inner"=>$inner);
            return parent::saveAttachments($values,$values["id"],$opts);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

}
