<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
if(!isset($parameters["records"]["data"][0])) {$new=true;}
$html=buildHeaderAbmStd($parameters,$title);

$html.="<div class='body-abm border border-light p-2 rounded shadow-sm' >";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"code","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"description","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","id_type_command",array("col"=>"col-md-6"));
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
