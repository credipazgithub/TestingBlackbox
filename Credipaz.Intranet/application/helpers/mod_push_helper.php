<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboTypeTargets($obj){
    $parameters=array(
        "model"=>(MOD_PUSH."/Type_targets"),
        "table"=>"type_targets",
        "name"=>"browser_id_type_target",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
function comboTypeCommands($obj){
    $parameters=array(
        "model"=>(MOD_PUSH."/Type_commands"),
        "table"=>"type_commands",
        "name"=>"browser_id_type_command",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
function comboTypeSubscriptions($obj){
    $parameters=array(
        "model"=>(MOD_PUSH."/Type_subscriptions"),
        "table"=>"type_subscriptions",
        "name"=>"browser_id_type_subscription",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
