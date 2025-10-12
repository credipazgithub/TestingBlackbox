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
            $values["interface"]=(MOD_PROVIDERS."/folder_items_log/form");
            return parent::form($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function markUserRead($values){
        try {
            $fieldList="created,verified,fum,processed,id_user,id_folder_item";
            $selectToInsert="SELECT getdate(),getdate(),getdate(),getdate(),u.id,f.id as id_folder_item FROM ".MOD_BACKEND."_users as u JOIN ".MOD_PROVIDERS."_folder_items as f on f.id!=0 AND u.username='".$values["mark_username"]."'";
            $params=array("fieldList"=>$fieldList,"selectToInsert"=>$selectToInsert);
            return $this->insertBySelect($params);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
