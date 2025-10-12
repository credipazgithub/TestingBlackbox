<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
if(!isset($parameters["readonly"])){$parameters["readonly"]=false;}
$ro=$parameters["readonly"];
$messageAlert="<div class='card alert alert-primary'>";
$messageAlert.="   <div class='row'>";
$messageAlert.="      <div class='col-6'>";
$messageAlert.="         <h5>Últimas acciones</h5>";
$messageAlert.="         <ul>";
foreach ($parameters["folders_log"] as $log){
    $messageAlert.="        <li>".date(FORMAT_DATE_DMYHMS, strtotime($log["created"]))." <span class='badge badge-success'>".$log["type_control_point"]."</span> <b>".$log["username"]."</b></li>";
}
$messageAlert.="         </ul>";
$messageAlert.="      </div>";
$messageAlert.="      <div class='col-6'>";
$messageAlert.="      <b>".$parameters["estadoActual"]."</b><br/>";
$messageAlert.="         <ul>";
//foreach ($parameters["folders_groups"]["data"] as $gr){
//    $messageAlert.="        <li>"."<span class='badge badge-primary' style='font-size:14px;'>".$gr["description"]."</span></li>";
//}
foreach ($parameters["users_auth"] as $gr) {
    $messageAlert .= "        <li>" . "<span class='badge badge-primary' style='font-size:14px;'>" . $gr["username"] . "</span></li>";
}
$messageAlert.="         </ul>";
$messageAlert.="      </div>";
$messageAlert.="   </div>";
$messageAlert.="</div>";
$parameters["messageAlert"]=$messageAlert;

if(!isset($parameters["records"]["data"][0])) {$new=true;}
$html=buildHeaderAbmStd($parameters,$title);

$html.="<div class='body-abm border border-light p-2 rounded shadow-sm' >";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row d-none'>";
$html.=getInput($parameters,array("name"=>"code","type"=>"hidden","class"=>"dbase"));
$html.=getInput($parameters,array("name"=>"id_type_control_point","type"=>"hidden","class"=>"dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"priority","type"=>"checkbox","class"=>"form-control text dbase"));
$html.="   <div class='col-10'>";
$html.="      <span class='badge badge-danger' style='font-size:1.25rem;display:block;word-wrap:break-word;white-space:normal;'>Si se marca 'Prioridad', se enviará una notificación más completa y llamativa al momento de la publicación</span>";
$html.="   </div>";
$html.="</div>";

$html.="<div class='form-row'>";
if($new){$html.= formatHtmlValue(null,null,lang('msg_initial_data'),"status",array("col"=>"col-md-12"));}else{$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"type_control_point","readonly"=>true,"format"=>"type"));}
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","id_type_folder",array("col"=>"col-md-6"))."</div>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"date_validity","type"=>"date","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"min_reviews","type"=>"number","class"=>"form-control text dbase validate"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"description","type"=>"text","class"=>"form-control dbase validate"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"keywords","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$ops= array("col"=>"col-md-8 pt-2","module"=>"mod_folders","name"=>"folders","relation"=>"folders","forcelabel"=>lang('msg_relationed_files'),"accept"=>".pdf,.doc,.docx,.jpg,.jpeg");

if(!$new){$parameters["readonly"]=((int)$parameters["id_user_active"]!=(int)$parameters["records"]["data"][0]["id_user"]);}
if ($parameters["godaction"]){$parameters["readonly"]=false;}
if($new){
   $ops["allow_delete"]=true;
} else {
   $ops["allow_delete"]=true;
   $parameters["readonly"]=((int)$parameters["records"]["data"][0]["id_type_control_point"]==3) ;
}
$ops["allow_read"]=false;
$html.=getFile($parameters,$ops,$attached_files["data"]);
$parameters["readonly"]=$ro;

$ops= array("col"=>"col-md-4 pt-2","name"=>"message");
$html.=getMessagesList($parameters,$ops,$attached_messages["data"]);
$html.="   </div>";
$html.="</div>";
                
$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","id_group",array("col"=>"col-md-12 pt-2"));
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
    $('.multiselect').selectpicker();
    $("body").off("change", ".id_type_folder").on("change", ".id_type_folder", function () {
        $("#min_reviews").val(1).prop("disabled",false);
        if (parseInt($(this).val())==9) {$("#min_reviews").val(2).prop("disabled",true);}
    });
</script>

