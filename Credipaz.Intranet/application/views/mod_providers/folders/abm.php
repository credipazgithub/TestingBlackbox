<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
$created=date(FORMAT_DATE_DMY);
if(!isset($parameters["readonly"])){$parameters["readonly"]=false;}
$ro=$parameters["readonly"];

if(!isset($parameters["records"]["data"][0])) {$new=true;}
$html="<div class='row'>";
$html.="   <div class='col-8'>";
if($new){
   $html.= formatHtmlValue(null,null,lang('msg_initial_data'),"status",array("col"=>"col-md-12"));
} else { 
   $created=date(FORMAT_DATE_DMY, strtotime($parameters["records"]["data"][0]["created"]));
   $html.="<h5><span class='badge badge-primary'># ".$parameters["records"]["data"][0]["id"]."</span> ".lang('p_type_control_point')." <span class='badge badge-success'>".$parameters["records"]["data"][0]["type_control_point"]."</span></h5>".$parameters["ddChangeStatus"];
}
$html.="   </div>";
$html.="   <div class='col-4'>";
if(!$new) {
    $wData=false;
    $messageAlert.="      <div class='card alert alert-primary'>";
    $messageAlert.="         <h5>Histórico de cambios de estado</h5>";
    $messageAlert.="         <ul>";
    foreach ($parameters["folders_log"] as $log){$wData=true;$messageAlert.="<li>".date(FORMAT_DATE_DMYHMS, strtotime($log["created"]))." <span class='badge badge-success'>".$log["type_control_point"]."</span> <b>".$log["username"]."</b></li>";}
    $messageAlert.="         </ul>";
    $messageAlert.="      </div>";
    if ($wData){$html.=$messageAlert;}
}
$html.="   </div>";
$html.="</div>";

$html.=buildHeaderAbmStd($parameters,$title);

$html.="<div class='body-abm border border-light p-2 rounded shadow-sm' >";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row d-none'>";
$html.=getInput($parameters,array("name"=>"code","type"=>"hidden","class"=>"dbase"));
$html.=getInput($parameters,array("name"=>"id_type_control_point","type"=>"hidden","class"=>"dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.="<div class='col-6'><label>".lang('p_created')."</label><br/>".$created."</div>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"total_amount","type"=>"number","class"=>"form-control text dbase validate","custom"=>"step='any'"));
$html.=getHtmlResolved($parameters,"controls","id_company",array("col"=>"col-md-4",));
$html.=getHtmlResolved($parameters,"controls","id_type_pay_condition",array("col"=>"col-md-4",));
$html.=getHtmlResolved($parameters,"controls","id_type_status_contable",array("col"=>"col-md-4",));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","id_provider",array("col"=>"col-md-4",));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"date_validity","type"=>"date","class"=>"form-control text dbase validate"));
//$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"date_pay","type"=>"date","class"=>"form-control text dbase validate"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"description","type"=>"text","class"=>"form-control dbase validate"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"keywords","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$ops= array("col"=>"col-md-8 pt-2","module"=>"mod_providers","name"=>"folders","relation"=>"folders","forcelabel"=>lang('msg_relationed_files'),"accept"=>".pdf,.doc,.docx,.jpg,.jpeg");
if(!$new){
    $parameters["readonly"]=((int)$parameters["id_user_active"]!=(int)$parameters["records"]["data"][0]["id_user"]);
    $ops["allow_delete"]=!$parameters["readonly"];
}
if ($parameters["godaction"]){
    $ops["allow_delete"]=true;
    $parameters["readonly"]=false;
}
$html.=getFile($parameters,$ops,$attached_files["data"]);
$parameters["readonly"]=$ro;
$html.=getHtmlResolved($parameters,"controls","id_type_sector",array("col"=>"col-md-4 pt-2"));
$html.="   </div>";
$html.="</div>";
                

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
    $('.multiselect').selectpicker();
    $('.singleselect').selectpicker();
</script>

