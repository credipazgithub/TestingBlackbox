<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$col="7";
$new=false;
if(!isset($parameters["records"]["data"][0])) {$new=true;$col="12";}

$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";


$html.="<div class='col-".$col." p-0 m-0'>";
$html.="   <div class='row p-0 m-0'>";
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"module_sinister","type"=>"text","class"=>"form-control text dbase validate"));
if ($new){
	$html.=getHtmlResolved($parameters,"controls","id_type_protocol",array("col"=>"col-md-8"));
} else {
   $html.="<div class='col-8'><span class='badge badge-primary'><h4 class='p-0 m-0'>".$parameters["records"]["data"][0]["type_status"]."</h4></span></div>";
}
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"name","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"surname","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"document","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"email","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"personal_phone","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"family_phone","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"family_contact","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"family_relation","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"test_confirm","type"=>"checkbox","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"test_type","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"test_date","type"=>"date","class"=>"form-control date dbase validate"));
$html.="   </div>";
$html.="</div>";

if (!$new){
	$html.="<div class='col-4 p-0 m-0'>";
	$html.="   <span class='badge badge-warning'><h4 class='p-0 m-0'>".$parameters["records"]["data"][0]["type_protocol"]."</h4></span>";

	$html.="</div>";
}

$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
