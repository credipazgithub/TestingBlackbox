<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$id=$parameters["id"];
$id_type_task_close=$parameters["records"]["data"][0]["id_type_task_close"];
$supervision="";
if(!isset($parameters["readonly"])){$parameters["readonly"]="0";}
if($parameters["readonly"]==""){$parameters["readonly"]="0";}

$title="".$supervision.lang('msg_legal_care');

$html=buildHeaderAbmStd($parameters,$title);

$styleEstado="font-weight:bold;color:black;";
$styleEstado=getStyleforClubRedondo($parameters["club_redondo"]["Estado"]);

$html.="<span class='badge badge-primary' style='font-size:18px;'>".$parameters["records"]["data"][0]["type_request"]."</span>";
$html.="<div class='body-abm'>";
$html.="  <table style='width:100%;'>";
$html.="   <tr>";
$html.="      <td class='shadow' style='width:50%;' valign='top'>";
$html.="         <div id='client_data' style='width:100%;padding:2px;margin-bottom:2px;'>";
$html.="            <h5 style='border-bottom:solid 1px silver;'>Datos del asociado</h5>";
if((int)$parameters["club_redondo"]["Empresa"]==999){
	$html.="        <table style='width:100%;'>";
	$html.="           <tr>";
	$html.="              <td style='font-size:1.25em;font-weight:bold;color:white;background-color:#E9148B;'>Empleado en CREDIPAZ</td>";
	$html.="           </tr>";
	$html.="        </table>";
}
$html.="            <table style='width:100%;'>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>Nombre</td><td>".$parameters["club_redondo"]["ApellidoNombre"]."</td>";
$html.="                  <td style='font-weight:bold;'>Documento</td><td>".$parameters["club_redondo"]["DNI"]."</td>";
$html.="               </tr>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>Sexo</td><td>".$parameters["club_redondo"]["Sexo"]."</td>";
$html.="                  <td style='font-weight:bold;'>Edad</td><td>".getAge($parameters["club_redondo"]["FechaNacimiento"])."</td>";
$html.="               </tr>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>Email</td><td>".$parameters["club_redondo"]["Email"]."</td>";
$html.="                  <td style='font-weight:bold;'>Teléfono</td><td>".$parameters["club_redondo"]["Telefono"]."</td>";
$html.="               </tr>";
$html.="            </table>";
$html.="            <h5 style='border-bottom:solid 1px silver;'>MediYa</h5>";
$html.="            <table style='width:100%;'>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>MediYa</td><td>".$parameters["club_redondo"]["ClubRedondo"]."</td>";
$html.="                  <td style='font-weight:bold;'>PAN</td><td>".$parameters["club_redondo"]["PANClub"]."</td>";
$html.="               </tr>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>Tipo de socio</td><td>".$parameters["club_redondo"]["TipoSocio"]."</td>";
$html.="                  <td style='font-weight:bold;'>Estado</td><td style='".$styleEstado."'>".$parameters["club_redondo"]["Estado"]."</td>";
$html.="               </tr>";
$html.="            </table>";
$html.="         </div>";
$html.="      </td>";
$html.="      <td style='width:50%;' valign='top'>";
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-6","name"=>"created","type"=>"date","class"=>"form-control date dbase","format"=>"datetime"));
$html.=getHtmlResolved($parameters,"controls","id_operator",array("col"=>"col-md-6"));
$html.=getTextArea($parameters,array("readonly"=>true,"col"=>"col-12","name"=>"motivo","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-12","name"=>"encomienda_profesional","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-12","name"=>"monto_reclamo","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","forcelabel"=>lang('p_scheduled_datetime'),"name"=>"scheduled","type"=>"datetime-local","class"=>"form-control date dbase validate"));
$html.="   </div>";
$html.="</div>";
$html.="      </td>";
$html.="   </tr>";
$html.="  </table>";
$html.="</div>";

$html.="<div class='row'>";
$html.="  <div class='col-6 mt-3'>";
$html.="   <a href='#' class='btn btn-md btn-raised btn-primary btn-operator-task-item' data-id_operator_task='".$id."' data-id='0' data-list='.ls-ot-items' data-title='".lang('b_activity_record')."'>".lang('b_activity_record')."</a>";
$html.="   <a href='#' class='btn btn-md btn-raised btn-warning btn-operator-task-item-auto' data-id_operator_task='".$id."' data-id='0'>".lang('b_activity_record_nocontact')." #".$parameters["records"]["data"][0]["nocontact"]."</a>";
$html.="   <ul class='ls-ot-items list-inline'>";

foreach ($parameters["operators_tasks_items"] as $item){
    $html.="<li style='width:100%;' class='list-group-item li-" .$item["id"]. "'>";
    $html.="<table class='table-condensed table-striped table-sm' style='width:100%;' >";
    $html.=" <tr>";
	$html.="    <td valign='top' class='shadow' cellspacing='2' style='background-color:white;width:15%;'>";
	$html.="       <b>".date(FORMAT_DATE_DMYHMS, strtotime($item["created"]))."</b></br>";
	$html.="       <i>".$item["lawyer"]."</i>";
	$html.="    </td>";
	$html.="    <td valign='top'>";
	$html.="       <u>".$item["description"]."</u>";
	$html.="       <div class='comment' style='width:100%;'>".$item["data"]."</div>";
	$html.="    </td>";
	$html.=" </tr>";
    $html.="</table>";
    $html.="</li>";
}
$html.="   </ul>";
$html.="  </div>";

$html.="  <div class='col-6 mt-3'>";
$html.="   <h3>Requerimientos anteriores</h3>";
$html.="   <ul class='ls-ot-previous list-inline'>";

foreach ($parameters["previous"] as $item){
    $html.="<li style='width:100%;' class='list-group-item li-" .$item["id"]. "'>";
    $html.="<table class='table-condensed table-striped table-sm' style='width:100%;' >";
    $html.=" <tr>";
	$html.="    <td valign='top' class='shadow' cellspacing='2' style='background-color:white;width:15%;'>";
	$html.="       <b>".date(FORMAT_DATE_DMYHMS, strtotime($item["created"]))."</b></br>";
	$html.="    </td>";
	$html.="    <td valign='top'>";
	$html.="       <div class='comment' style='width:100%;'><b>".$item["motivo"]."</b></div>";
	$html.="    </td>";
	$html.=" </tr>";

    foreach ($item["items"] as $contact){
		$html.=" <tr>";
		$html.="    <td valign='top' class='shadow' cellspacing='2' style='background-color:white;width:15%;'>";
		$html.=date(FORMAT_DATE_DMYHMS, strtotime($contact["created"]))."</br>";
		$html.="       <i>".$contact["lawyer"]."</i>";
		$html.="    </td>";
		$html.="    <td valign='top'>";
		$html.="       <u>".$contact["description"]."</u>";
		$html.="       <div class='comment' style='width:100%;'>".$contact["data"]."</div>";
		$html.="    </td>";
		$html.=" </tr>";
	}

    $html.="</table>";
    $html.="</li>";
}
$html.="   </ul>";
$html.="  </div>";
$html.="</div>";

$html.="<div style='width:100%;'>";
$html.=buildFooterAbmStd($parameters);
$html.="<img src='' class='img-test'/>";

$html.="</div>";
echo $html;
?>
<script>$(".comment").shorten();</script>
