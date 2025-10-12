<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//ERROR BEHAVIOUR
function logProviders($obj,$values,$method){
    try {
        if(!isset($values["id_user_active"]) or $obj->table=="folders_log"){throw new Exception(lang('error_9999'),9999);}
        if(!isset($values["id"])){$values["id"]=null;}
        $FOLDERS_LOG=$obj->createModel(MOD_PROVIDERS,"Folders_log","Folders_log");
        $fields = array(
            'code' => opensslRandom(16),
            'description' => lang('msg_log_folders'),
            'created' => $obj->now,
            'verified' => $obj->now,
            'offline' => null,
            'fum' => $obj->now,
            'id_user' => $values["id_user_active"],
            'id_folder' => $values["id"],
            'id_type_control_point' => $values["id_type_control_point"],
            'processed' => $obj->now,
            'tag_processed' => $method,
        );
        return $FOLDERS_LOG->save(array("id"=>0),$fields);
    } catch(Exception $e) {
        if ($e->getCode()!==9999){return logError($e,__METHOD__ );} else {return null;}
    }
}
function logFolderItemsProviders($obj,$values,$method){
    try {
        if(!isset($values["id_user_active"]) or $obj->table=="folders_log"){throw new Exception(lang('error_9999'),9999);}
        if(!isset($values["id"])){$values["id"]=null;}
        $FOLDER_ITEMS_LOG=$obj->createModel(MOD_PROVIDERS,"Folder_items_log","Folder_items_log");
        $fields = array(
            'code' => opensslRandom(16),
            'description' => lang('msg_log_folder_items'),
            'created' => $obj->now,
            'verified' => $obj->now,
            'offline' => null,
            'fum' => $obj->now,
            'id_user' => $values["id_user_active"],
            'id_folder_item' => $values["id"],
            'processed' => $obj->now,
            'tag_processed' => $method,
        );
        return $FOLDER_ITEMS_LOG->save(array("id"=>0),$fields);
    } catch(Exception $e) {
        if ($e->getCode()!==9999){return logError($e,__METHOD__ );} else {return null;}
    }
}
function unLogFolderItemsProviders($obj,$values){
    try {
        if(!isset($values["id_user_active"]) or $obj->table=="folders_log"){throw new Exception(lang('error_9999'),9999);}
        if(!isset($values["id"])){$values["id"]=null;}
        $FOLDER_ITEMS_LOG=$obj->createModel(MOD_PROVIDERS,"Folder_items_log","Folder_items_log");
        return $FOLDER_ITEMS_LOG->deleteByWhere("id_folder_item=".$values["id"]." AND id_user=".$values["id_user_active"]);
    } catch(Exception $e) {
        if ($e->getCode()!==9999){return logError($e,__METHOD__ );} else {return null;}
    }
}

//HTML COMBOS
function comboTypeFoldersProviders($obj){
    $parameters=array(
        "model"=>(MOD_PROVIDERS."/Type_folders"),
        "table"=>"type_folders",
        "name"=>"browser_id_type_folder",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
function comboTypeFolderItemsProviders($obj){
    $parameters=array(
        "model"=>(MOD_PROVIDERS."/Type_folder_items"),
        "table"=>"type_folder_items",
        "name"=>"browser_id_type_folder_item",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
function comboTypeControlPointsProviders($obj,$get=array("order"=>"description ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_PROVIDERS."/Type_control_points"),
        "table"=>"type_control_points",
        "name"=>"browser_id_type_control_point",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
