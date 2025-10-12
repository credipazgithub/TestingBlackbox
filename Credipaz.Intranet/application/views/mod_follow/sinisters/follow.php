<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($parameters["is_doctor"],JSON_PRETTY_PRINT));
/*---------------------------------*/
$is_doctor=$parameters["is_doctor"];
$id_type_status=(int)$parameters["records"]["data"][0]["id_type_status"];
$type_status=$parameters["records"]["data"][0]["type_status"];
$username=$parameters["profile"]["data"][0]["username"];
$id_sinister=$parameters["records"]["data"][0]["id"];
$actual=(int)$parameters["records"]["data"][0]["actual_review"];
$limit=((int)$parameters["records"]["data"][0]["actual_review"]+1);
$occurs=(int)$parameters["records"]["data"][0]["occurs"];
if ($limit>$occurs){$limit=$occurs;}
if($id_type_status==4){$limit=(int)$parameters["records"]["data"][0]["actual_review"];}

$html="<div class='body-abm border border-light p-2 rounded shadow-sm' style='position:relative;'>";
$html.="   <div class='row'>";
$html.="      <div class='col-3 p-0 m-0 pt-2 col-izq'>";
$html.="         <table id='tbl-izq' class='table table-sm' style='width:100%;'>";
$html.="            <tr class='table-secondary'><td colspan='2'>".getInput($parameters,array("custom"=>"data-id='".$id_sinister."'","col"=>"col-md-12","name"=>"audit_control","type"=>"checkbox","class"=>"form-control checkbox audit_control"))."</td></tr>";
$html.="            <tr class='table-secondary'><td colspan='2'>".getInput($parameters,array("custom"=>"data-id='".$id_sinister."'","col"=>"col-md-12","name"=>"full_vacuna","type"=>"checkbox","class"=>"form-control checkbox full_vacuna"))."</td></tr>";
$html.="            <tr class='table-warning'><td><b>Estado</b></td><td>".$parameters["records"]["data"][0]["type_status"]."</td></tr>";
$html.="            <tr class='table-info'><td><b>Protocolo</b></td><td>".$parameters["records"]["data"][0]["type_protocol"]."</td></tr>";
$html.="            <tr class='table-danger'><td><b>Días</b></td><td>".$parameters["records"]["data"][0]["days_accident"]."</td></tr>";
$html.="            <tr class='table-secondary'><td><b>ART</b></td><td>".$parameters["records"]["data"][0]["type_art"]."</td></tr>";
$html.="            <tr class='table-secondary'><td><b>Siniestro</b></td><td>".$parameters["records"]["data"][0]["module_sinister"]."</td></tr>";
$html.="            <tr class='table-secondary'><td><b>Contingencia</b></td><td>".$parameters["records"]["data"][0]["type_contingency"]."</td></tr>";
if ($parameters["records"]["data"][0]["accident_date"]!=""){$html.="<tr class='table-secondary'><td><b>Fecha PCR</b></td><td>".date(FORMAT_DATE_DMYHMS, strtotime($parameters["records"]["data"][0]["accident_date"]))."</td></tr>";}
$html.="            <tr><td colspan='2' style='color:white;background-color:black;font-weight:bold;'>DATOS DEL TRABAJADOR</td></tr>";
$html.="            <tr class='table-secondary'><td><b>Nombre</b></td><td>".$parameters["records"]["data"][0]["name"]." ".$parameters["records"]["data"][0]["surname"]."</td></tr>";
$html.="            <tr class='table-secondary'><td><b>Documento</b></td><td>".$parameters["records"]["data"][0]["document"]."</td></tr>";
$html.="            <tr><td colspan='2' style='color:white;background-color:black;font-weight:bold;'>DATOS DEL EMPLEADOR</td></tr>";
$html.="            <tr class='table-secondary'><td><b>Empresa</b></td><td>".$parameters["records"]["data"][0]["company_name"]."</td></tr>";
$html.="            <tr class='table-secondary'><td><b>CUIT</b></td><td>".$parameters["records"]["data"][0]["company_cuit"]."</td></tr>";
$html.="            <tr><td colspan='2' style='color:white;background-color:black;font-weight:bold;'>DATOS DEL PRESTADOR</td></tr>";
$html.="            <tr class='table-secondary'><td><b>Establecimiento</b></td><td>".$parameters["records"]["data"][0]["sanity_name"]."</td></tr>";
$html.="            <tr><td colspan='2' style='color:white;background-color:black;font-weight:bold;'>DESCRIPCIÓN DEL MOTIVO DE LA CONSULTA</td></tr>";
$html.="            <tr class='table-secondary'><td><b>Motivo</b></td><td>".$parameters["records"]["data"][0]["sinopsys"]."</td></tr>";
if ($parameters["records"]["data"][0]["sinopsys1"]!=""){$html.="<tr class='table-secondary'><td colspan='2'>".$parameters["records"]["data"][0]["sinopsys1"]."</td></tr>";}
if ($parameters["records"]["data"][0]["sinopsys2"]!=""){$html.="<tr class='table-secondary'><td colspan='2'>".$parameters["records"]["data"][0]["sinopsys2"]."</td></tr>";}
$html.="            <tr class='table-secondary'><td><b>Diagnóstico</b></td><td>".$parameters["records"]["data"][0]["prognosys"]."</td></tr>";
if ($parameters["records"]["data"][0]["prognosys1"]!=""){$html.="<tr class='table-secondary'><td colspan='2'>".$parameters["records"]["data"][0]["prognosys1"]."</td></tr>";}
if ($parameters["records"]["data"][0]["prognosys2"]!=""){$html.="<tr class='table-secondary'><td colspan='2'>".$parameters["records"]["data"][0]["prognosys2"]."</td></tr>";}
$html.="            <tr class='table-secondary'><td><b>Indicaciones</b></td><td>".$parameters["records"]["data"][0]["indications"]."</td></tr>";
if ($parameters["records"]["data"][0]["indications1"]!=""){$html.="<tr class='table-secondary'><td colspan='2'>".$parameters["records"]["data"][0]["indications1"]."</td></tr>";}
if ($parameters["records"]["data"][0]["indications2"]!=""){$html.="<tr class='table-secondary'><td colspan='2'>".$parameters["records"]["data"][0]["indications2"]."</td></tr>";}
$html.="            <tr class='table-danger'>";
$next_revision=$parameters["records"]["data"][0]["next_revision_date"];
if ($next_revision!="") {$next_revision=date(FORMAT_DATE_DMYHMS, strtotime($next_revision));}
$html.="               <td><b>Prox.revisión</b></td><td>".$next_revision."</td>";
$html.="            </tr>";
$html.="         </table>";
$parameters["readonly"]=true;
$html.=buildFooterAbmStd($parameters);
//$html.="         <button type='button' class='btn-raised btn-abm-cancel btn btn-md btn btn-block btn-info'><i class='material-icons'>close</i></span>".lang("b_close")."</button>";
$html.="      </div>";

$titleRevisiones="Controlar revisiones - <span class='badge badge-success'>".$type_status."</span>";
if ($id_type_status==2){$titleRevisiones="Completar o controlar revisiones - <span class='badge badge-danger'>".$type_status."</span>";}

$html.="      <div class='col-9 p-0 m-0'>";
$html.="		<ul class='nav nav-tabs'>";
$html.="		 <li class='nav-item'>";
$html.="		    <a class='nav-link active' data-toggle='tab' href='#revisiones'>REVISIONES</a>";
$html.="		 </li>";
$html.="		 <li class='nav-item'>";
$html.="		     <a class='nav-link' data-toggle='tab' href='#alta'>ALTA MÉDICA</a>";
$html.="  	     </li>";
$html.="		</ul>";
$html.="		<div class='tab-content p-0 m-0'>";
$html.="		   <div class='tab-pane container active' id='revisiones'>";
$html.="			<h5 class='p-0 px-2 m-0'>".$titleRevisiones."</h5>";
$html.="			<ul class='nav nav-tabs pl-2'>";
for ($x = 1; $x <= $limit; $x++) {
	$active="";
    if($x==$limit){$active="active";}
	$html.="<li class='nav-item'> <a class='nav-link ".$active."' data-toggle='tab' href='#tab".$x."'>".$x."</a></li>";
}
$html.="			</ul>";
$html.="            <div class='tab-content'>";
for ($x = 1; $x <= $limit; $x++) {
	$last_segment="";
	$active="";
    if($x==$limit){$active="active";}
	/*Check if status is correct and last tab is loading!*/
	$newRevision=($id_type_status==2 and ($x==$limit));
	if($actual==$limit){$newRevision=false;}
	if ($newRevision){
	    $data=$parameters["questions"];
	    $date=date(FORMAT_DATE_DMYHMS);
		$message="<span style='margin-left:10px;padding:3px;color:red;background-color:IVORY;'>PENDIENTE</span>";
		$style="border-radius:5px;color:white;background-color:rgb(233,20,139);width:100%;";
		if($is_doctor){
			$acceptBtn.="<hr/><button type='button' class='btn-raised btn-follow-accept btn btn-md btn-block btn-success'><i class='material-icons'>done</i></span>".lang("b_accept")."</button>";
		} else {
			$acceptBtn.="<hr/><div class='pb-1'><h3 style='color:red;'>Ud. no es médico.  No puede generar revisiones.</h3></div>";
		}
		$borderTab="rgb(233,20,139)";
	} else {
		$data=$parameters["rel_sinisters_questions"][$x-1];
	    $date=date(FORMAT_DATE_DMYHMS,strtotime($data[0]["created"]));
		$username=$data[0]["username"];
		$url0=(getServer()."/revisionArt/".$id_sinister."/".$x."/0/".$values["id_user_active"]);
		$url1=(getServer()."/revisionArt/".$id_sinister."/".$x."/1/".$values["id_user_active"]);
		$message="<span style='margin-left:10px;padding:3px;color:darkgreen;background-color:ivory;'>REVISIÓN COMPLETA</span> ";
		$url=(getServer()."/revisionArt/".$id_sinister."/".$x."/2/".$values["id_user_active"]);


		//------------------------------
		//Impresión de REVISION!
		//------------------------------
		$url=(getServer()."/revisionArt/".$id_sinister."/".$x."/2/".$values["id_user_active"]);

		//Dropdown!
		//$ddREV2=("<a href='".$url."' target='_blank' class='dropdown-item'>Con firma</a>");
		//$url=(getServer()."/revisionArt/".$id_sinister."/".$x."/1/".$values["id_user_active"]);
		//$ddREV1=("<a href='".$url."' target='_blank' class='dropdown-item'>Sin firma</a>");
		//$PDFReview="<div class='dropdown' style='display:inline;'>";
		//$PDFReview.="  <button type='button' class='btn btn-sm btn-success btn-raised dropdown-toggle' data-toggle='dropdown' style='background-color:;'>Revisión +</button>";
		//$PDFReview.="  <div class='dropdown-menu'>".$ddREV1.$ddREV2."</div>";
		//$PDFReview.="</div>";

		//Button!
		$PDFReview="  <button type='button' class='btn btn-sm btn-success btn-raised' style='color:white;'><a href='".$url."' target='_blank' style='color:white;'>Revisión ".$x."</a></button>";
		//------------------------------

		$message.=$PDFReview;

		$style="border-radius:5px;color:white;background-color:green;width:100%;";
		$acceptBtn.="";
		$borderTab="green";
	}
	$html.="<div class='m-1 p-0 p-2 mb-5 tab-pane container ".$active."' id='tab".$x."' style='border-radius:5px;border:solid 1px ".$borderTab.";'>";
	$html.=("<div class='p-1' style='".$style."'>Fecha: ".$date." Médico responsable: <b>".$username."</b> ".$message."</div>");
    foreach ($data as $record){
	    $possible_values=base64_encode($record["possible_values"]);
		if($last_segment!=$record["type_segment"]){
		   $last_segment=$record["type_segment"];
		   $html.="<div class='row m-1 p-0 px-1' style='color:white;background-color:black;font-weight:bold;'>".$last_segment."</div>";
		}
	    $html.="<div class='row question m-0 mb-1 p-0 pl-1' data-id='".$record["id"]."' data-possible-values='".$possible_values."'>";
	    $html.="   <div class='col-3 m-0 p-0' style='border:solid 0px silver;border-right:solid 0px transparent;'>".$record["description"]."</div>";
	    $html.="   <div class='col-9 m-0 p-0' style='border:solid 0px silver;border-left:solid 0px transparent;'>";
		/*Resolves control builds from dynamics!*/
		$id_question=$record["id"];
		$value=$record["value"];
		$code=$record["code_type_control"];
		$key=("key_".$id_sinister."_".$id_question."_".$x."_".$code);
		$class="class='form-control dbquestion dbquestion-".$x." ".$record["class"]."'";
		$custom="data-id-sinister='".$id_sinister."' data-id-question='".$id_question."' data-revision='".$x."'";
		switch($code){
		   case "TEXTAREA":
		      $custom.=" maxlength='580'";
		      break;
		}
		$possible_values=$record["possible_values"];
		$params=array("value"=>$value,"code"=>$code,"key"=>$key,"class"=>$class,"custom"=>$custom,"possible_values"=>$possible_values);
		$html.=getFromControls($params,!$newRevision);
		$html.="   </div>";
		$html.="</div>";
	}
	$html.=$acceptBtn;
	$html.="        </div>";
}
$html.="            </div>";
$html.="		   </div>";
$rec["records"]["data"]=$parameters["discharge"];

$dischargeRO=isset($parameters["discharge"][0]);
$titleAlta="Generación de alta médica - <span class='badge badge-success'>".$type_status."</span>";
if ($dischargeRO){
    $titleAlta="Alta médica generada: ".date(FORMAT_DATE_DMYHMS, strtotime($rec["records"]["data"][0]["created"]))." - <span class='badge badge-danger'>".$type_status."</span>";
	$url=(getServer()."/altamedicaArt/|ID|/2");
	$ddAM2=("<a href='".$url."' target='_blank' class='dropdown-item'>Con firma</a>");
	$url=(getServer()."/altamedicaArt/|ID|/1");
	$ddAM1=("<a href='".$url."' target='_blank' class='dropdown-item'>Sin firma</a>");
	$PDFAlta="<div class='dropdown' style='display:inline;'>";
	$PDFAlta.="  <button type='button' class='btn btn-sm btn-success btn-raised dropdown-toggle' data-toggle='dropdown' style='background-color:;'>Alta +</button>";
	$PDFAlta.="  <div class='dropdown-menu'>".$ddAM1.$ddAM2."</div>";
	$PDFAlta.="</div>";
	$titleAlta.=$PDFAlta;
}

$html.="		   <div class='tab-pane container fade mt-1' id='alta' style='border-radius:5px;border:solid 1px rgb(233,20,139);'>";
$html.="		      <h5 class='p-0 px-2 m-0 my-2'>".$titleAlta."</h5>";

$html.="		      <div clasS='row'>";
$html.=getTextArea($rec,array("custom"=>"maxlength='340'","readonly"=>$dischargeRO,"rows"=>"5","col"=>"col-md-12","name"=>"sinopsys_discharge","class"=>"form-control text dbaseDischarge"));
$html.=getTextArea($rec,array("custom"=>"maxlength='350'","readonly"=>$dischargeRO,"rows"=>"5","col"=>"col-md-12","name"=>"prognosys_discharge","class"=>"form-control text dbaseDischarge"));
$html.=getTextArea($rec,array("custom"=>"maxlength='460'","readonly"=>$dischargeRO,"rows"=>"5","col"=>"col-md-12","name"=>"indications_discharge","class"=>"form-control text dbaseDischarge"));
$html.="              </div>";

$html.="		      <div clasS='row'>";
$html.="		         <div clasS='col-6 py-1'>";
$html.=getInput($rec,array("format"=>"yesno","forcelabel"=>"CONSTANCIA DE ALTA MÉDICA","readonly"=>$dischargeRO,"col"=>"col-md-12","name"=>"is_medical_discharge","type"=>"checkbox","class"=>"form-control text dbaseDischarge"));
$html.="		            <div clasS='row'>";
$possible_values=array(array("label"=>"Si","value"=>"1"),array("label"=>"No","value"=>"0"));
$html.="<div class='col-12 mt-1' style='color:white;background-color:black;font-weight:bold;'>Detalles de tratamiento pendiente</div>";
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"more_treatment","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"odontology","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"dermatology","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"psicoterapy","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"text","readonly"=>$dischargeRO,"col"=>"col-md-8","forcelabel"=>"Otra","name"=>"other","type"=>"text","class"=>"form-control text dbaseDischarge"));

$html.="<div class='col-12 mt-1' style='color:white;background-color:black;font-weight:bold;'>Datos de cierre</div>";
$html.=getInput($rec,array("format"=>"datetime","readonly"=>$dischargeRO,"col"=>"col-md-6","name"=>"next_revision_date_discharge","type"=>"datetime-local","class"=>"form-control date dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-6","name"=>"requalification","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"datetime","readonly"=>$dischargeRO,"col"=>"col-md-6","name"=>"back_work","type"=>"datetime-local","class"=>"form-control date dbaseDischarge"));
$html.=getInput($rec,array("format"=>"datetime","readonly"=>$dischargeRO,"col"=>"col-md-6","name"=>"treatment_end_date","type"=>"datetime-local","class"=>"form-control date dbaseDischarge"));

$html.="<div class='col-12 mt-1' style='color:white;background-color:black;font-weight:bold;'>Motivo de cese de ILT</div>";
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"medical_discharge","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"reject","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"death","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"treatment_end","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"referral","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"text","readonly"=>$dischargeRO,"col"=>"col-md-4","name"=>"type_referral","type"=>"text","class"=>"form-control text dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"inculpable_disease","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"text","readonly"=>$dischargeRO,"col"=>"col-md-8","name"=>"inculpable_disease_detail","type"=>"text","class"=>"form-control text dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-6","name"=>"sequels","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-6","name"=>"maintenance_services","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.="		            </div>";
$html.="                 </div>";

$html.="		         <div clasS='col-6 py-1'>";
$html.=getInput($rec,array("format"=>"yesno","forcelabel"=>"CONSTANCIA DE FIN DE TRATAMIENTO","readonly"=>$dischargeRO,"col"=>"col-md-12","name"=>"is_treatment_end","type"=>"checkbox","class"=>"form-control text dbaseDischarge"));
$html.="		            <div clasS='row'>";
$html.="<div class='col-12 mt-1' style='color:white;background-color:black;font-weight:bold;'>Detalles del fin del tratamiento</div>";
$html.=getInput($rec,array("format"=>"datetime","readonly"=>$dischargeRO,"col"=>"col-md-12","name"=>"treatment_end_date2","type"=>"datetime-local","class"=>"form-control date dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-4","name"=>"sequels2","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-6","name"=>"requalification2","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.=getInput($rec,array("format"=>"yesno","readonly"=>$dischargeRO,"possible_values"=>$possible_values,"col"=>"col-md-6","name"=>"maintenance_services2","type"=>"radiobutton","class"=>"form-control radio dbaseDischarge"));
$html.="		            </div>";
$html.="                 </div>";
$html.="              </div>";
if(!$dischargeRO){
   if($is_doctor){
      $html.="<hr/><div class='pb-1'><button type='button' data-id-sinister='".$id_sinister."' class='btn-raised btn-discharge-accept btn btn-md btn-block btn-success'><i class='material-icons'>done</i></span>".lang("b_accept")."</button></div>";
   } else {
      $html.="<hr/><div class='pb-1'><h3 style='color:red;'>Ud. no es médico.  No puede generar alta médica.</h3></div>";
   }
}
$html.="		   </div>";

$html.="		</div>";
$html.="      </div>";
$html.="   </div>";
$html.="</div>";

$html.=buildFloatNotes(array("id"=>"medical_notes","label"=>lang('p_medical_notes'),"value"=>$parameters["records"]["data"][0]["medical_notes"]));
echo $html;
?>

<script>
    setTimeout(function(){
		var _left = ($("#accordion").width()+9);
		var _width = $(".body-abm").width();
		
		$(".body-abm").css({"margin-top":"125px"});
		$(".float_medical_notes").css({"width":(_width + "px"),"left": (_left + "px"),"top":"55px"});
		$("#medical_notes").attr("disabled",true);

	},500);
</script>


