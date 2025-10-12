<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboTypeStatus($obj,$get=array("order"=>"description ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_ONBOARDING."/Type_status"),
        "table"=>"type_status",
        "name"=>"browser_id_type_status",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
function comboTypeRequest($obj){
    $parameters=array(
        "model"=>(MOD_ONBOARDING."/Type_request"),
        "table"=>"type_request",
        "name"=>"browser_id_type_request",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
