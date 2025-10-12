<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboTypePosts($obj){
    $parameters=array(
        "model"=>(MOD_WEB_POSTS."/Type_posts"),
        "table"=>"type_posts",
        "name"=>"browser_id_type_post",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>array("order"=>"description ASC","pagesize"=>-1),
    );
    return getCombo($parameters,$obj);
}
