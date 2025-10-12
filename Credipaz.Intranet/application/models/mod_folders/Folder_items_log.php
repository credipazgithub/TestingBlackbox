<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Folder_items_log extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function form($values){
        try {
            $values["interface"]=(MOD_FOLDERS."/folder_items_log/form");
            return parent::form($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function markUserRead($values){
	    try {
            $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
            $user=$USERS->get(array("where"=>"username='".$values["mark_username"]."'"));
			$id_user=$user["data"][0]["id"];

			$sql="INSERT INTO mod_folders_folder_items_log (code,description,created,verified,offline,fum,id_user,id_folder_item,processed,tag_processed) ";
			$sql.=" SELECT null,null,getdate(),getdate(),null,getdate(),".$id_user.",id,getdate(),'Visto' FROM mod_folders_folder_items";
			/* Filtrado por groups del folder by user */
			//$sql="INSERT INTO mod_folders_folder_items_log (code,description,created,verified,offline,fum,id_user,id_folder_item,processed,tag_processed) ";
			//$sql.=" SELECT null,null,getdate(),getdate(),null,getdate(),".$id_user.",id,getdate(),'Visto' FROM mod_folders_folder_items WHERE id_folder IN ";
			//$sql.=" (SELECT id_folder FROM mod_folders_rel_folders_groups WHERE id_group IN (SELECT id_user FROM mod_backend_rel_users_groups WHERE id_user=".$id_user."))";
			$this->execAdHoc($sql);
			return array(
				"code"=>"2000",
				"status"=>"OK",
				"message"=>"",
				"function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
	        );
		} catch (Exception $e){
            return logError($e,__METHOD__ );
        }

	/*
        try {
            $fieldList="created,verified,fum,processed,id_user,id_folder_item";
            $selectToInsert="SELECT getdate(),getdate(),getdate(),getdate(),u.id,f.id as id_folder_item FROM ".MOD_BACKEND."_users as u JOIN ".MOD_FOLDERS."_folder_items as f on f.id!=0 AND u.username='".$values["mark_username"]."'";
            $params=array("fieldList"=>$fieldList,"selectToInsert"=>$selectToInsert);
            return $this->insertBySelect($params);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
		*/

    }

}
