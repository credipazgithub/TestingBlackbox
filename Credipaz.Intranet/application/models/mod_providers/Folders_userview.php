<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Folders_userview extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
            $fullsystem=(evalPermissions("FULLSYSTEM",$profile["data"][0]["groups"]));
            $this->view="vw_folders";
            if($values["where"]!=""){$values["where"].=" AND ";}
            if(isset($values["forced_field"]) and isset($values["forced_value"])) {$values["where"]=($values["forced_field"]."='".$values["forced_value"]."' AND ");}
            if ($fullsystem) {
                $values["where"].="id_type_control_point in (2,3,6,7,8,11)";
            } else {
               $values["where"].=("id_type_control_point in (2,3,6,7,8,11) AND id IN (SELECT id_folder FROM ".MOD_PROVIDERS."_rel_folders_groups WHERE id_group IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"]."))");
            }
            $values["order"]="created DESC";
            $values["records"]=$this->get($values);

            //$ops=array();
            //$TYPE_CONTROL_POINTS=$this->createModel(MOD_PROVIDERS,"Type_control_points","Type_control_points");
            //$records=$TYPE_CONTROL_POINTS->get(array("order"=>"id ASC","pagesize"=>-1));
            //foreach($records["data"] as $record){
            //    array_push($ops,array("name"=>secureField($record,"description"),"class"=>"btn-folder-change-status","datax"=>"data-module='MOD_PROVIDERS' data-status='".secureField($record,"id")."' data-id=|ID|"));
            //};
            //$ddChangeStatus=getDropdown(array("class"=>"btn-primary btn-title-change-status-|ID|","name"=>"|DESCRIPTION|"),$ops);

            $values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );

            $values["columns"]=array(
                array("field"=>"id","format"=>"number"),
                array("field"=>"created","format"=>"date"),
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
                array("name"=>"browser_id_type_control_point", "operator"=>"=","fields"=>array("id_type_control_point")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Punto de control</span>".comboTypeControlPointsProviders($this,array("where"=>"id IN (2,3,7,8) ","order"=>"description ASC","pagesize"=>-1)),
            );
            $values["conditionalBackground"]=array(
                array("field"=>"id_type_control_point","value"=>"6","color"=>"darkorange"),
                array("field"=>"id_type_control_point","value"=>"2","color"=>"gold"),
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
            $this->view="vw_folders";
            $values["godaction"]=false;
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

            $ops=array();
            $TYPE_CONTROL_POINTS=$this->createModel(MOD_PROVIDERS,"Type_control_points","Type_control_points");
            $records=$TYPE_CONTROL_POINTS->get(array("where"=>"id IN (6,7,8)","order"=>"id ASC","pagesize"=>-1));
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

			$status_contable=$values["records"]["data"][0]["type_status_contable"];
			if($status_contable==""){$status_contable="SIN ESPECIFICAR";}
            $values["controls"]=array(
                "id_type_status_contable"=>"<br/><div class='badge badge-info'>".$status_contable."</div>",
            );

            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
