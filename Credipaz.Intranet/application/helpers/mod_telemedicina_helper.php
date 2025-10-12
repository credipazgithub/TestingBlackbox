<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboDoctorsUsername($obj,$get=array("order"=>"username ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_TELEMEDICINA."/Doctors"),
        "table"=>"doctors",
        "name"=>"browser_id_doctor",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"username",
        "description_field"=>"username",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
function comboDoctors($obj,$get=array("where"=>"offline is null","order"=>"username ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_TELEMEDICINA."/Doctors"),
        "table"=>"doctors",
        "name"=>"browser_id_doctor",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"username",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
function comboTypeTaskClose($obj,$get=array("order"=>"description ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_TELEMEDICINA."/Type_tasks_close"),
        "table"=>"type_tasks_close",
        "name"=>"browser_id_type_task_close",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
