<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboDireccion($parameters){
   $html="<select data-type='select' id='".$parameters["name"]."' name='".$parameters["name"]."' class='".$parameters["name"]." ".$parameters["class"]."'>";
   try {
        $html.="<option value=''>".lang('p_select_combo')."</option>";
        $html.="<option value='ENTRANTE'>".lang('msg_in_call')."</option>";
        $html.="<option value='SALIENTE'>".lang('msg_out_call')."</option>";
   } catch(Exception $e){}
   $html.="</select>";
   $html.="<div class='invalid-feedback invalid-".$parameters["name"]." d-none'/>";
   
   return $html;
}
