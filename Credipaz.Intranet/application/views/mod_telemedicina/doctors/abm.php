<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm' >";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"name","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"surname","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"email","type"=>"text","class"=>"form-control text dbase validate"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-5","name"=>"username","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-5","name"=>"license","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"test","type"=>"checkbox","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"mn","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"mp","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-9","name"=>"dni","type"=>"number","class"=>"form-control number dbase validate"));
$html.="<div class='col-3'>";
$html.="  <label>Sexo</label><br/>";
$html.="  <table style='width:100%;'>";
$html.="     <tr>";
if($parameters["records"]["data"][0]["sex"]=="M") {$checked='checked';}else{$checked='';} 
$html.="<td width='50%'>Masculino <input ".$checked." style='height:20px;' data-type='radio' value='M' class='form-control dbase validate' type='radio' name='sex' id='sex' data-clear-btn='false' /></td>";
if($parameters["records"]["data"][0]["sex"]=="F") {$checked='checked';}else{$checked='';} 
$html.="<td width='50%'>Femenino <input ".$checked." style='height:20px;' data-type='radio' value='F' class='form-control dbase validate' type='radio' name='sex' id='sex' data-clear-btn='false' /></td>";
$html.="     </tr>";
$html.="  </table>";
$html.="</div>";
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"phone","type"=>"number","class"=>"form-control number dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"birthday","type"=>"date","class"=>"form-control date dbase validate"));
$html.="</div>";

$html.="<div class='form-row'>";
$ops= array(
        "nolabel"=>true,
        "name"=>"image",
        "class"=>"dbase d-none",
        "type"=>"base64",
        "format"=>"jpeg",
        "quality"=>0.5,
        "crop"=>"square",
        "col"=>"col-md-6",
    );
$html.=getImage($parameters,$ops);
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
