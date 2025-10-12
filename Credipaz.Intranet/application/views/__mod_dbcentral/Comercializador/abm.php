<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
if(!isset($parameters["records"]["data"][0])) {$new=true;}
$title="Vendedor";
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";

$html.=" <div class='col-12'>";
$html.="  <form style='width:100%;' autocomplete='off'>";
$html.="   <div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","IdEmpresa",array("col"=>"col-md-12"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"Nombre","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"NroDocumento","type"=>"number","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"Email","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"Password","type"=>"text","class"=>"form-control text dbase validate"));
$html.="   </div>";
$html.="  </form>";
$html.="</div>";

$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
