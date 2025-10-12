<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboTypePlaces($obj){
    $parameters=array(
        "model"=>(MOD_PLACES."/Type_places"),
        "table"=>"type_places",
        "name"=>"browser_id_type_place",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
