<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboTypeActions($obj,$get=array("order"=>"description ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_DIRECT_SALE."/Type_actions"),
        "table"=>"type_actions",
        "name"=>"browser_id_type_action",
        "class"=>"form-control browser_id_type_action",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
function comboTypeStatusSales($obj,$get=array("order"=>"description ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_DIRECT_SALE."/Type_status"),
        "table"=>"type_status",
        "name"=>"browser_id_type_status",
        "class"=>"form-control browser_id_type_status",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
