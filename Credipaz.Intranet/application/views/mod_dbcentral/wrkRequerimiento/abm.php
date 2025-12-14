<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
if(!isset($parameters["records"]["data"][0])) {$new=true;$col=12;}else{$col=8;}

$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";

$html.=" <div class='col-".$col."'>";
$html.="  <form style='width:100%;' autocomplete='off'>";
$html.="   <div class='form-row'>";
$html.="    <input type='hidden' clasS='dbase' id='sLKSector' name='sLKSector' value='".$parameters["sLKSector"]."'/>";
$html.=getInput($parameters,array("readonly"=>!$new,"col"=>"col-md-6","name"=>"sLKSector","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getHtmlResolved($parameters,"controls","nIDSubsistema",array("col"=>"col-md-6",));
$html.="   </div>";
$html.="   <div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","nIDTipo",array("col"=>"col-md-6",));
$html.=getHtmlResolved($parameters,"controls","sLKEstado",array("col"=>"col-md-6",));
$html.="   </div>";
$html.="   <div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"sAsunto","type"=>"text","class"=>"form-control text dbase validate"));
$html.="   </div>";
$html.="   <div class='form-row'>";
$html.=getTextArea($parameters,array("col"=>"col-md-12","name"=>"sDescripcion","class"=>"form-control text validate-sDescripcion"));
$html.="   </div>";
$html.="   <div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","sPrioridad",array("col"=>"col-md-6",));
if(!$new) {$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"sHorasEstimadas","type"=>"number","class"=>"form-control number dbase"));}
$html.="   </div>";
if(!$new) {
    $html.="<div class='form-row'>";
    $html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-6","name"=>"dFechaInicio","type"=>"date","class"=>"form-control date dbase"));
    $html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-6","name"=>"dFechaCierre","type"=>"date","class"=>"form-control date dbase"));
    $html.="</div>";
    $html.="<div class='form-row'>";
    $html.=getInput($parameters,array("col"=>"col-md-6","name"=>"dFechaDefinicion","type"=>"date","class"=>"form-control date dbase"));
    $html.=getInput($parameters,array("col"=>"col-md-6","name"=>"dFechaEF","type"=>"date","class"=>"form-control date dbase"));
    $html.="</div>";
    $html.="<div class='form-row'>";
    $html.=getInput($parameters,array("col"=>"col-md-2","name"=>"ISuspendido","type"=>"checkbox","class"=>"form-control text dbase"));
    $html.="</div>";
    $html.="<div class='form-row'>";
    $ops= array("alow_delete"=>false,"allow_red"=>false,"module"=>"mod_dbcentral","col"=>"col-md-12 pt-2","name"=>"filesystem","relation"=>"filesystem","forcelabel"=>lang('msg_relationed_files'),"accept"=>"*.*");
    $ro=$parameters["readonly"];
    $parameters["readonly"]=!$new;
    $html.=getFile($parameters,$ops,$attached_files);
    $parameters["readonly"]=$ro;
    $html.="   </div>";
    $html.="</div>";
}
$html.="  </form>";
$html.="</div>";

if(!$new) {
    $html.=" <div class='col-4'>";
    $html.="    <h4>Mensajes</h4>";
    $html.="    <div class='card msg-ticket'></div>";
    $html.=" </div>";
}

$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
