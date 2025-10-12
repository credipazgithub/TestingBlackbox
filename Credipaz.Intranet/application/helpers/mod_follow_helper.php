<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboTypeCancelations($obj){
    $parameters=array(
        "model"=>(MOD_FOLLOW."/Type_cancelations"),
        "table"=>"type_status",
        "name"=>"browser_id_type_cancelation",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
function comboTypeStatusFollow($obj){
    $parameters=array(
        "model"=>(MOD_FOLLOW."/Type_status"),
        "table"=>"type_status",
        "name"=>"browser_id_type_status",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
function comboTypePriorities($obj){
    $parameters=array(
        "model"=>(MOD_FOLLOW."/Type_priorities"),
        "table"=>"type_priorities",
        "name"=>"browser_id_type_priority",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
function comboTypeSegments($obj){
    $parameters=array(
        "model"=>(MOD_FOLLOW."/Type_segments"),
        "table"=>"type_segments",
        "name"=>"browser_id_type_segment",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
function comboTypeProtocols($obj){
    $parameters=array(
        "model"=>(MOD_FOLLOW."/Type_protocols"),
        "table"=>"type_protocols",
        "name"=>"browser_id_type_protocol",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
function comboTypeContingency($obj){
    $parameters=array(
        "model"=>(MOD_FOLLOW."/Type_contingency"),
        "table"=>"type_contingency",
        "name"=>"browser_id_type_contingency",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
function comboTypeArts($obj){
    $parameters=array(
        "model"=>(MOD_FOLLOW."/Type_arts"),
        "table"=>"type_arts",
        "name"=>"browser_id_type_art",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
