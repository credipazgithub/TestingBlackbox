<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
$parameters["readonly"]=true;

if(!isset($parameters["records"]["data"][0])) {$new=true;}
$html="<div class='row'>";
$html.="   <div class='col-8'>";
if($new){
   $created=date(FORMAT_DATE_DMY, strtotime($parameters["records"]["data"][0]["created"]));
   $html.= formatHtmlValue(null,null,lang('msg_initial_data'),"status",array("col"=>"col-md-12"));
} else { 
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

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-6","name"=>"created","type"=>"date","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-6","name"=>"total_amount","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-4","name"=>"type_folder","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-4","name"=>"type_pay_condition","type"=>"number","class"=>"form-control number dbase"));
$html.=getHtmlResolved($parameters,"controls","id_type_status_contable",array("col"=>"col-md-4",));
$html.="</div>";
$html.="<div class='form-row'>";
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-2","name"=>"company","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-4","name"=>"provider","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-3","name"=>"date_validity","type"=>"date","class"=>"form-control text dbase"));
//$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-3","name"=>"date_pay","type"=>"date","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-12","name"=>"description","type"=>"text","class"=>"form-control dbase"));
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-12","name"=>"keywords","type"=>"text","class"=>"form-control dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$ops= array("col"=>"col-md-8 pt-2","module"=>"mod_providers","name"=>"folders","relation"=>"folders","forcelabel"=>lang('msg_relationed_files'),"accept"=>".pdf,.doc,.docx,.jpg,.jpeg");
if ($parameters["godaction"]){$parameters["readonly"]=false;}
$ops["allow_delete"]=false;
$ops["allow_read"]=false;
$html.=getFile($parameters,$ops,$attached_files["data"]);
$html.="   <div class='col-4'>";
$html.="      <h4>".lang('p_id_type_sector')."</h4>";
$html.="      <ul>";
foreach ($parameters["type_sectors"] as $sector){$html.="<li>"."<span class='badge badge-warning'>".$sector["description"]."</span></li>";}
$html.="      </ul>";
$html.="   </div>";
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
    $('.multiselect').selectpicker();
</script>

