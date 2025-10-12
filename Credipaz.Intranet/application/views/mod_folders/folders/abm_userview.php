<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
if(!isset($parameters["records"]["data"][0])) {$new=true;}
$bPriority=(int)($parameters["records"]["data"][0]["priority"]);
$parameters["readonly"]=true;
$html=buildHeaderAbmStd($parameters,$title);

$html.="<div class='body-abm border border-light p-2 rounded shadow-sm' >";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row'>";
if($new){$html.= formatHtmlValue(null,null,lang('msg_initial_data'),"status",array("col"=>"col-md-12"));}else{$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"type_control_point","readonly"=>true,"format"=>"type"));}
if ($bPriority){
   $html.="<div class='col-4 p-2'>";
   $html.="   <span class='badge badge-danger' style='font-size:1.5rem;display:block;word-wrap:break-word;white-space:normal;'>";
   $html.="   <img src='./assets/img/alert.png' style='width:60px;'/>";
   $html.="Notificación prioritaria";
   $html.="   </span>";
   $html.="</div>";
}
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-4","name"=>"type_folder","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-4","name"=>"date_validity","type"=>"date","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-12","name"=>"description","type"=>"text","class"=>"form-control dbase validate"));
$ops= array("col"=>"col-md-12 pt-2","name"=>"folders","relation"=>"folders","forcelabel"=>lang('msg_relationed_files'),"accept"=>".pdf,.doc,.docx,.jpg,.jpeg");
$html.=getFile($parameters,$ops,$attached_files["data"]);
$html.="</div>";
                
$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
    $('.multiselect').selectpicker();
</script>

