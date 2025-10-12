<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Folders_userview extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

	private function replaceType($values,$findme,$findmeAlt){
	    /*eN DESARROLLO, para evantual replace!*/
		$where=$values["where"];
		$pos=strpos($where, $findme);
		if ($pos !== false) {}
		$pos=strpos($where, $findmeAlt);
		if ($pos !== false) {}
		return $where;
	}

    public function brow($values){
        try {
            $this->view="vw_folders";
            if($values["where"]!=""){$values["where"].=" AND ";}
            if(isset($values["forced_field"]) and isset($values["forced_value"])) {
				$values["where"]=($values["forced_field"]."='".$values["forced_value"]."' AND ");
			}
            $values["fields"] = "*,(SELECT count(id) FROM " . MOD_FOLDERS . "_folder_items_log as l WHERE l.id_folder_item in (select id from " . MOD_FOLDERS . "_folder_items where id_folder=mod_folders_vw_folders.id) AND l.id_user=" . $values["id_user_active"] . ") as unviewed";
            $values["where"].=("offline is NULL AND id_type_control_point=4 AND id IN (SELECT id_folder FROM ".MOD_FOLDERS."_rel_folders_groups WHERE id_group IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"]."))");
            $values["order"] = "created DESC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"created","format"=>"datetime"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"type_folder","format"=>"type"),
                array("field"=>"type_control_point","format"=>"warning"),
                array("field"=>"priority","forcedlabel"=>"","true"=>"<span class='material-icons' style='color:red;'>bolt</span>","false"=>"","format"=>"conditional#bool"),
                array("field"=>"offline","format"=>"datetime"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description","keywords")),
                array("name"=>"browser_id_type_folder", "operator"=>"=","fields"=>array("code_type_folder")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Tipo</span>".comboTypeFoldersCode($this,array("where"=>"id!=9","order"=>"description ASC","pagesize"=>-1)),
            );
            $values["conditionalBackground"]=array(
                array("field"=>"offline","operator"=>"!=","value"=>"","color"=>"darkorange"),
                array("field"=>"unviewed","operator"=>"=","value"=>"0","color"=>"pink"),
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
            $values["interface"]=(MOD_FOLDERS."/folders/abm_userview");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $opts=array(
                "module"=>MOD_FOLDERS,
                "model"=>"Folder_items",
                "view"=>"vw_folder_items",
                "where"=>"id_folder=".$values["id"],
                "fields"=>"*,(SELECT count(id) FROM ".MOD_FOLDERS."_folder_items_log as l WHERE l.id_folder_item=".MOD_FOLDERS."_vw_folder_items.id AND l.id_user=".$values["id_user_active"].") as viewed",
            );
            $values["attached_files"] = parent::getAttachments($values,$opts);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
