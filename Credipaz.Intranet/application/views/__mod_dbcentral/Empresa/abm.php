<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
if(!isset($parameters["records"]["data"][0])) {$new=true;}
$title="Empresa";
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";

$html.=" <div class='col-12'>";
$html.="  <form style='width:100%;' autocomplete='off'>";
$html.="   <div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"Nombre","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"RazonSocial","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"CUIT","type"=>"number","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"ImporteCuota","type"=>"number","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"Telefono","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"Direccion","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"Localidad","type"=>"text","class"=>"form-control text dbase"));
$html.="   </div>";
$html.="  </form>";
$html.="</div>";

$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
