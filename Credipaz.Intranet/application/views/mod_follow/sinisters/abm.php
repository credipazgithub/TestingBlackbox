<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
if(!isset($parameters["records"]["data"][0])) {
   $new=true;
   $version="0";
} else {
   $version=$parameters["records"]["data"][0]["version"];
}

$html=buildHeaderAbmStd($parameters,$title);

$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="   <div class='row p-0 m-0'>";

$html.="<input type='hidden' id='id_type_status' name='id_type_status' class='dbase' value='".$parameters["records"]["data"][0]["id_type_status"]."'/>";
//$html.="<input type='hidden' id='version' name='version' class='dbase' value='".$version."'/>";

$html.=getHtmlResolved($parameters,"controls","id_type_art",array("col"=>"col-md-6"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"module_sinister","type"=>"text","class"=>"form-control text dbase validate module-sinister-validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"version","type"=>"number","class"=>"form-control number dbase validate module-sinister-validate"));
//$html.="<div class='col-md-1'><label for='module_sinister'>".lang("p_version")."</label><div class='p-1 bg-primary' style='border-radius:5px;color:white;'>/ ".$version."</div></div>";

$html.=getHtmlResolved($parameters,"controls","id_type_protocol",array("col"=>"col-md-6"));
$html.=getHtmlResolved($parameters,"controls","id_type_contingency",array("col"=>"col-md-6"));

/*DATOS DEL TRABAJADOR*/
$html.="<div class='col-12' style='color:white;background-color:black;font-weight:bold;'>DATOS DEL TRABAJADOR</div>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"name","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"surname","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"document","type"=>"text","class"=>"form-control text dbase validate"));
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

$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"birthdate","type"=>"date","class"=>"form-control date dbase no-validate"));
$html.=getInput($parameters,array("col"=>"col-md-5","name"=>"personal_address_street","type"=>"text","class"=>"form-control text dbase no-validate"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"personal_address_number","type"=>"text","class"=>"form-control text dbase no-validate"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"personal_address_floor","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"personal_address_apto","type"=>"text","class"=>"form-control text dbase"));

$html.=getInput($parameters,array("col"=>"col-md-5","name"=>"personal_address_location","type"=>"text","class"=>"form-control text dbase no-validate"));
$html.=getInput($parameters,array("col"=>"col-md-5","name"=>"personal_address_province","type"=>"text","class"=>"form-control text dbase no-validate"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"personal_address_postal_code","type"=>"text","class"=>"form-control text dbase"));

$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"personal_prefix_phone","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"personal_phone","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"personal_prefix_cel","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"personal_cel","type"=>"number","class"=>"form-control number dbase"));

$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"personal_email","type"=>"text","class"=>"form-control text dbase no-validate"));

/*DATOS DEL EMPLEADOR*/
$html.="<div clasS='col-12' style='color:white;background-color:black;font-weight:bold;'>DATOS DEL EMPLEADOR</div>";
$html.=getInput($parameters,array("col"=>"col-md-8","name"=>"company_name","type"=>"text","class"=>"form-control text dbase no-validate"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"company_cuit","type"=>"text","class"=>"form-control text dbase no-validate"));

$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"company_address","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"company_prefix_phone","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"company_phone","type"=>"number","class"=>"form-control number dbase"));

$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"company_contact","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"company_contact_prefix_cel","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"company_contact_cel","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"company_email","type"=>"text","class"=>"form-control text dbase"));

$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"accident_place","type"=>"text","class"=>"form-control text dbase"));

$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"accident_address","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"accident_prefix_phone","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"accident_phone","type"=>"number","class"=>"form-control number dbase"));

$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"accident_contact","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"accident_contact_prefix_cel","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"accident_contact_cel","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"accident_email","type"=>"text","class"=>"form-control text dbase"));

/*DATOS DEL PRESTADOR*/
$html.="<div clasS='col-12' style='color:white;background-color:black;font-weight:bold;'>DATOS DEL PRESTADOR</div>";
$html.=getInput($parameters,array("default"=>"MEDIYA S.A.","col"=>"col-md-8","name"=>"sanity_name","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("default"=>"30-70776862-9","col"=>"col-md-4","name"=>"sanity_cuit","type"=>"text","class"=>"form-control text dbase"));

$html.=getInput($parameters,array("default"=>"Sarmiento","col"=>"col-md-5","name"=>"sanity_address_street","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("default"=>"552","col"=>"col-md-2","name"=>"sanity_address_number","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("default"=>"17","col"=>"col-md-1","name"=>"sanity_address_floor","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-1","name"=>"sanity_address_apto","type"=>"text","class"=>"form-control text dbase"));

$html.=getInput($parameters,array("default"=>"CABA","col"=>"col-md-5","name"=>"sanity_address_location","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("default"=>"Bs.As.","col"=>"col-md-5","name"=>"sanity_address_province","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"sanity_address_postal_code","type"=>"text","class"=>"form-control text dbase"));

$html.=getInput($parameters,array("default"=>"011","col"=>"col-md-2","name"=>"sanity_prefix_phone","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("default"=>"48674081","col"=>"col-md-4","name"=>"sanity_phone","type"=>"number","class"=>"form-control number dbase"));
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"sanity_fax","type"=>"number","class"=>"form-control number dbase"));

$html.=getInput($parameters,array("default"=>"correofedpat@mediya.com.ar","col"=>"col-md-12","name"=>"sanity_email","type"=>"text","class"=>"form-control text dbase no-validate"));

/*DATOS DEL MOTIVO DE LA CONSULTA*/
$html.="<div clasS='col-12' style='color:white;background-color:black;font-weight:bold;'>DESCRIPCION DEL MOTIVO DE LA CONSULTA</div>";
$html.=getHtmlResolved($parameters,"controls","id_type_contingency_request",array("col"=>"col-md-6"));
$html.=getInput($parameters,array("custom"=>"REQUIRED","col"=>"col-md-6","name"=>"accident_date","type"=>"datetime-local","class"=>"form-control date dbase validate"));

$html.=getInput($parameters,array("custom"=>"REQUIRED","col"=>"col-md-6","name"=>"fault_date","type"=>"datetime-local","class"=>"form-control date dbase validate"));
$html.=getInput($parameters,array("custom"=>"REQUIRED","col"=>"col-md-6","name"=>"aid_date","type"=>"datetime-local","class"=>"form-control date dbase validate"));

$html.=getTextArea($parameters,array("col"=>"col-md-12","class"=>"form-control text dbase","rows"=>"5","name"=>"medical_notes","format"=>"text"));

$html.=getInput($parameters,array("custom"=>" maxlength='100' ","default"=>"ENFERMEDAD RESPIRATORIA AGUDA DEBIDO A COVID 19","col"=>"col-md-12","name"=>"sinopsys","type"=>"text","class"=>"form-control sinopsys text dbase no-validate"));
$html.=getInput($parameters,array("nolabel"=>true,"custom"=>" maxlength='120' ","col"=>"col-md-12","name"=>"sinopsys1","type"=>"text","class"=>"form-control sinopsys1 text dbase no-validate"));
$html.=getInput($parameters,array("nolabel"=>true,"custom"=>" maxlength='120' ","col"=>"col-md-12","name"=>"sinopsys2","type"=>"text","class"=>"form-control sinopsys2 text dbase no-validate"));

$html.=getInput($parameters,array("custom"=>" maxlength='110' ","default"=>"COVID 19 POSITIVO","col"=>"col-md-12","name"=>"prognosys","type"=>"text","class"=>"form-control prognosys text dbase no-validate"));
$html.=getInput($parameters,array("nolabel"=>true,"custom"=>" maxlength='120' ","col"=>"col-md-12","name"=>"prognosys1","type"=>"text","class"=>"form-control prognosys1 text dbase no-validate"));
$html.=getInput($parameters,array("nolabel"=>true,"custom"=>" maxlength='120' ","col"=>"col-md-12","name"=>"prognosys2","type"=>"text","class"=>"form-control prognosys2 text dbase no-validate"));

$html.=getInput($parameters,array("custom"=>" maxlength='100' ","col"=>"col-md-12","name"=>"indications","type"=>"text","class"=>"form-control indications text dbase no-validate"));
$html.=getInput($parameters,array("nolabel"=>true,"custom"=>" maxlength='120' ","col"=>"col-md-12","name"=>"indications1","type"=>"text","class"=>"form-control indications1 text dbase no-validate"));
$html.=getInput($parameters,array("nolabel"=>true,"custom"=>" maxlength='120' ","col"=>"col-md-12","name"=>"indications2","type"=>"text","class"=>"form-control indications2 text dbase no-validate"));

$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"stop_work","type"=>"checkbox","class"=>"form-control checkbox dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"free_date","type"=>"date","class"=>"form-control date dbase"));
$html.=getInput($parameters,array("custom"=>"REQUIRED","col"=>"col-md-3","name"=>"next_revision_date","type"=>"datetime-local","class"=>"form-control date dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"back_work","type"=>"date","class"=>"form-control date dbase"));

$html.=getTextArea($parameters,array("default"=>"BUENOS AIRES, ","col"=>"col-md-12","class"=>"form-control text dbase","rows"=>"5","name"=>"footer_place","format"=>"text"));

//$html.="<div clasS='col-12' style='color:white;background-color:black;font-weight:bold;'>AUDITORIA</div>";
//$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"audit_control","type"=>"checkbox","class"=>"form-control checkbox dbase"));
//$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"test_type","type"=>"text","class"=>"form-control text dbase no-validate"));
//$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"test_date","type"=>"date","class"=>"form-control date dbase no-validate"));
if (!$parameters["is_admin"]) {
	if ($parameters["is_doctor"]) {
		$html.="<div class='col-12 mt-1' style='color:white;background-color:black;font-weight:bold;'>CIERRE DE CARGA INICIAL</div>";
		$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"full_vacuna","type"=>"checkbox","class"=>"form-control checkbox dbase full_vacuna"));
		$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"pass_to_review","type"=>"checkbox","class"=>"form-control checkbox dbase pass_to_review"));
		$html.=getHtmlResolved($parameters,"controls","id_type_priority",array("col"=>"col-md-6 d-none area-priority"));
	}
    $html.="<div class='col-12 mt-1' style='color:white;background-color:black;font-weight:bold;'>CANCELACIÓN</div>";
    //$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"cancel_by_patient","type"=>"checkbox","class"=>"form-control checkbox dbase cancel_by_patient"));
	$html.=getHtmlResolved($parameters,"controls","id_type_cancelation",array("col"=>"col-md-6"));
}


$html.="   </div>";
$html.="</div>";
$parameters["readonly"]=$canceled;
$html.=buildFooterAbmStd($parameters);
echo $html;
?>

