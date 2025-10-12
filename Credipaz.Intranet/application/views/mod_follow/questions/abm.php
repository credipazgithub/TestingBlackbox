<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-10","name"=>"description","forcelabel"=>"Pregunta","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"priority","type"=>"number","class"=>"form-control number dbase validate"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"class","forcelabel"=>"Valores para [class]","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","id_type_segment",array("col"=>"col-md-4"));
$html.=getHtmlResolved($parameters,"controls","id_type_protocol",array("col"=>"col-md-4"));
$html.=getHtmlResolved($parameters,"controls","id_type_control",array("col"=>"col-md-4"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getTextArea($parameters,array("col"=>"col-md-12","class"=>"form-control text dbase","rows"=>"10","name"=>"possible_values","format"=>"text"));
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
