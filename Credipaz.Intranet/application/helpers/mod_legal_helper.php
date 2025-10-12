<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboLawyers($obj,$get=array("order"=>"username ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_LEGAL."/Lawyers"),
        "table"=>"lawyers",
        "name"=>"browser_id_lawyer",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"username",
        "description_field"=>"username",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
