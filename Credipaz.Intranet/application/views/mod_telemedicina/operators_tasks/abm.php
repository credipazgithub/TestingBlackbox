<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$id_charge_code=$parameters["charges_codes"]["data"][0]["id"];
$id_type_task_close=$parameters["records"]["data"][0]["id_type_task_close"];
$especialidad=$parameters["especialidad"]["data"][0]["description"];
$supervision="";
if(!isset($parameters["readonly"])){$parameters["readonly"]="0";}
if($parameters["readonly"]==""){$parameters["readonly"]="0";}
if($parameters["readonly"]){$supervision=" SUPERVISIÓN ";}

$title="<span class='badge badge-secondary'>Código de pago activo:</span> <span class='badge badge-warning'>".$parameters["charges_codes"]["data"][0]["id"]."</span> ".$supervision.lang('msg_medical_care');

$html=buildHeaderAbmStd($parameters,$title);

$styleEstado="font-weight:bold;color:black;";
$styleEstado=getStyleforClubRedondo($parameters["club_redondo"]["Estado"]);

$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";
$html.="<div class='row d-none'>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"request_pictures","type"=>"text","class"=>"d-none form-control text dbase"));
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-4","name"=>"code","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("readonly"=>true,"col"=>"col-md-8","name"=>"description","type"=>"text","class"=>"form-control text dbase validate"));
$html.="</div>";

$html.="<table class='table table-condensed' style='width:100%;'>";
$html.="   <tr>";
$html.="      <td style='width:50%;'>";
$html.="         <span class='badge badge-info' style='font-size:18px;'>".$especialidad."</span>";
$html.=getHtmlResolved($parameters,"controls","refiere",array("col"=>"col-md-12"));
$html.=getTextArea($parameters,array("col"=>"col-md-12","name"=>"motivo","class"=>"form-control text dbase"));
$html.=getTextArea($parameters,array("col"=>"col-md-12","name"=>"evolucion","class"=>"form-control text dbase"));
$html.=getTextArea($parameters,array("col"=>"col-md-12","name"=>"diagnostico","class"=>"form-control text dbase"));
$html.=getTextArea($parameters,array("col"=>"col-md-12","name"=>"indicaciones","class"=>"form-control text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"derivado_consulta","type"=>"checkbox","class"=>"form-control text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"derivado_especialista","type"=>"checkbox","class"=>"form-control text dbase"));
$html.=getHtmlResolved($parameters,"controls","id_type_task_close",array("col"=>"col-md-12"));
$html.=getTextArea($parameters,array("col"=>"col-md-12","name"=>"note_close","class"=>"form-control text dbase div_note_close d-none"));
$html.="      </td>";
$html.="      <td id='patient' class='shadow' style='width:50%;'>";
$html.="         <div id='client_data' style='width:100%;padding:3px;margin-bottom:5px;' class='card rx-hidden'>";
$html.="            <h5>Datos del asociado</h5>";

if((int)$parameters["club_redondo"]["Empresa"]==999){
	$html.="        <table style='width:100%;'>";
	$html.="           <tr>";
	$html.="              <td style='font-size:1.25em;font-weight:bold;color:white;background-color:#E9148B;'>Empleado en CREDIPAZ</td>";
	$html.="           </tr>";
	$html.="        </table>";
}

$html.="            <table style='width:100%;'>";
$html.="               <tr>";
$html.="                  <td>";
$html.="                     <label for='cboSwiss'>Seleccione paciente</label>";
$html.="                     <select id='cboSwiss' name='cboSwiss' class='form-control cboSwiss'>";
$bHasData=false;
foreach($parameters["swiss"]["message"]["records"] as $swiss){
   $record=base64_encode(json_encode($swiss));
   $html.="<option data-record='".$record."'>".$swiss["Nombre"]." - ".$swiss["Tipo"]."</option>";
   $bHasData=true;
}
//if (!$bHasData) {
//   $html.="<option data-record='".$record."'>".$parameters["club_redondo"]["ApellidoNombre"]." - Titular</option>";
//}
$html.="                     </select>";
$html.="                  </td>";
//$html.="                  <td>";
//$html.="                     <label for='cboGerdanna'>MediYa</label>";
//$html.="                     <select id='cboGerdanna' name='cboGerdanna' class='form-control cboGerdanna'>";
//foreach($parameters["gerdanna"]["message"]["records"] as $gerdanna){
//   $record=base64_encode(json_encode($gerdanna));
//   $html.="<option data-record='".$record."'>".$gerdanna["Nombre"]." - ".$gerdanna["Tipo"]."</option>";
//}
//$html.="                     </select>";
//$html.="                  </td>";

$html.="               </tr>";
$html.="            </table>";

$html.="            <table style='width:100%;'>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>Nombre</td><td class='nombre'>".$parameters["club_redondo"]["ApellidoNombre"]."</td>";
$html.="                  <td style='font-weight:bold;'>Documento</td><td class='dni'>".$parameters["club_redondo"]["DNI"]."</td>";
$html.="               </tr>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>Sexo</td><td class='sexo''>".$parameters["club_redondo"]["Sexo"]."</td>";
$html.="                  <td style='font-weight:bold;'>Edad</td><td class='edad'>".getAge($parameters["club_redondo"]["FechaNacimiento"])."</td>";
$html.="               </tr>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>Email</td><td>".$parameters["club_redondo"]["Email"]."</td>";
$html.="                  <td style='font-weight:bold;'>Teléfono</td><td>".$parameters["club_redondo"]["Telefono"]."</td>";
$html.="               </tr>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>Obra social</td><td>".$parameters["club_redondo"]["obra_social"]."</td>";
$html.="                  <td style='font-weight:bold;'>Número y plan</td><td>".$parameters["club_redondo"]["nro_obra_social"]." - ".$parameters["club_redondo"]["obra_social_plan"]."</td>";
$html.="               </tr>";
$html.="            </table>";
$html.="         </div>";
$html.="         <div id='client_cr' style='width:100%;padding:3px;margin-bottom:5px;' class='card rx-hidden'>";
$html.="            <h5>MediYa</h5>";
$html.="            <table style='width:100%;'>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>Nº de socio</td><td class='idsocio'>".$parameters["club_redondo"]["ClubRedondo"]."</td>";
$html.="                  <td style='font-weight:bold;'>Nº Cred.Swiss Medical</td><td class='nrocredencial'>".$parameters["club_redondo"]["PANSwiss"]."</td>";
$html.="                  <td style='font-weight:bold;'></td><td></td>";
$html.="               </tr>";
$html.="               <tr>";
$html.="                  <td style='font-weight:bold;'>Fecha alta</td><td class='fechaalta'>".date(FORMAT_DATE_DMY, strtotime($parameters["club_redondo"]["FechaAlta"]))."</td>";
$html.="                  <td style='font-weight:bold;'>Tipo de socio</td><td class='tipo'>".$parameters["club_redondo"]["TipoSocio"]."</td>";
$html.="                  <td style='font-weight:bold;'>Estado</td><td style='".$styleEstado."'>".$parameters["club_redondo"]["Estado"]."</td>";
$html.="               </tr>";
$html.="            </table>";
$html.="         </div>";
$auditoria="N";

$labelInit="Iniciar videoconsulta";
$lblMessage="El paciente automáticamente será ingresado a la consulta";
if($parameters["readonly"]){
    $auditoria="S";
    $labelInit="Supervisar la consulta";
    $lblMessage="Ud. está participando como supervisor.  El control de la atención al paciente es responsabilidad del médico actuante.";
} 
else {
   if ($parameters["records"]["data"][0]["id_type_emergency"]!=""){
      $html.="<div class='card ambulance rx-hidden' style='padding:5px;background-color:".$parameters["records"]["data"][0]["type_emergency_color"]."'>";
      $html.="<b>Ambulancia solicitada</b> ".date(FORMAT_DATE_DMYHMS, strtotime($parameters["records"]["data"][0]["emergency_request"]));
      $html.="<br/>";
      $html.="<b>Clasificación TRIAGE</b> ".$parameters["records"]["data"][0]["type_emergency"];
      $html.="</div>";
   } else {
      $html.="<div class='ambulance rx-hidden'><a href='#' class='btn btn-sm btn-raised btn-warning btn-block btn-emergency' data-id='".$parameters["records"]["data"][0]["id"]."'><span class='material-icons'>medical_services</span> SOLICITAR AMBULANCIA</a></div>";
   }
}

$html.="      <a href='#' class='btn btn-sm btn-raised btn-primary btn-block btn-videoconferencia d-none' data-token='". $parameters["charges_codes"]["data"][0]["code"]."' data-auditoria='".$auditoria."' data-target='#meet' data-id-charge-code='".$id_charge_code."' data-full-name='".$parameters["chat_fullname"]."' data-alias='".$parameters["chat_alias"]."' data-height='".$parameters["chat_height"]."' data-platform-name='".$parameters["chat_platformname"]."' data-room-name='".$parameters["chat_roomname"]."' data-domain='".$parameters["chat_domain"]."'>".$labelInit."</a>";
$html.="      <div style='width:100%;color:black;' class='alert alert-info meet-wait d-none text-center'>";
$html.="         <p>".$lblMessage."</p>";
$html.="         <p><b>Aguarde por favor</b> <span class='badge badge-primary meet-countdown' style='font-size:20px;'></span></p>";
$html.="      </div>";
$html.="      <div id='meet' style='width:100%;' class='card'></div>";
$html.="      <div class='btn-group area-controls' role='group'>";
$html.="        <button id='recordStart' type='button' class='btn btn-sm btn-secondary btn-record-start d-none'>Grabar</button>";
$html.="        <button id='recordStop' type='button' class='btn btn-sm btn-secondary btn-record-stop d-none'>Detener</button>";
$html.="        <button id='recordPlay' type='button' class='btn btn-sm btn-secondary btn-record-play d-none'>Reproducir</button>";
$html.="        <button id='recordSave' type='button' class='btn btn-sm btn-secondary btn-record-save d-none'>Guardar</button>";
$html.="      </div>";
$html.="      <div class='view-audit d-none'><video controls playsinline autoplay id='recordPlayback' style='width:100%;height:auto;'></video></div>";

$html.="      <div style='width:100%;color:black;' class='alert-elapsed alert alert-success d-none text-center'>";
$html.="         <table style='width:100%;'>";
$html.="            <tr>";
$html.="               <td>Tiempo de consulta <span class='badge badge-primary time-elapsed'></span></td>";
$html.="               <td><button type='button' data-id='".$id_charge_code."' class='btn btn-raised btn-info btn-sm btn-request-pictures' data-target='patient'><i class='material-icons'>photo_camera</i> Solicitar imágenes</button></td>";
$html.="            </tr>";
$html.="         </table>";
$html.="         <p><span class='badge badge-danger time-exceeded d-none' style='font-size:16px;'>Tiempo estimado EXCEDIDO.</span></p>";
$html.="      </div>";

/*
$html.="      <a href='#' data-mode='new' class='my-2 btn btn-sm btn-raised btn-dark btn-telemedicina-msg view-medic d-none rx-hidden' ";
$html.="         data-iface='receta' ";
$html.="         data-id-type-item='2' ";
$html.="         data-id-charge-code='".$id_charge_code."'";
$html.="         data-nombre_paciente='".$parameters["club_redondo"]["ApellidoNombre"]."' ";
$html.="         data-nro_documento='".$parameters["club_redondo"]["DNI"]."' ";
$html.="         data-nro_club_redondo='".$parameters["club_redondo"]["PANClub"]."' ";
$html.="         data-nro_swiss='".$parameters["club_redondo"]["PANSwiss"]."' ";
$html.="         data-obra_social='".$parameters["club_redondo"]["obra_social"]."' ";
$html.="         data-obra_social_plan='".$parameters["club_redondo"]["obra_social_plan"]."' ";
$html.="         data-nro_obra_social='".$parameters["club_redondo"]["nro_obra_social"]."' ";
$html.="         data-matricula='".$parameters["matricula"]."' ";
$html.="         data-medico='".$parameters["medico"]."' ";
$html.="         data-firma='".$parameters["firma"]."' ";
$html.=">Receta de contingencia</a>";
*/
if ($parameters["club_redondo"]["PANSwiss"]!="") {
	$html.="      <a href='#' data-mode='new' class='btn-receta my-2 btn btn-sm btn-raised btn-info btn-telemedicina-msg-pdf view-medic d-none rx-hidden' ";
	$html.="         data-dni='' ";
	$html.="         data-nombre='' ";
	$html.="         data-apellido='' ";
	$html.="         data-sexo='' ";
	$html.="         data-fechanacimiento='' ";
	$html.="         data-panswiss='' ";
	$html.="         data-iface='receta' ";
	$html.="         data-id-type-item='2' ";
	$html.="         data-id-charge-code='".$id_charge_code."'";
	$html.=">Receta Swiss</a>";
} else {
    $html.="<b>No se puede emitir receta. <span style='color:red;'>El asociado no tiene Swiss PAN activo.</span></b>";
}

$html.="      <a href='#' data-mode='new' class='my-2 btn btn-sm btn-raised btn-secondary btn-telemedicina-msg view-medic d-none rx-hidden' ";
$html.="         data-iface='nota' ";
$html.="         data-id-type-item='2' ";
$html.="         data-id-charge-code='".$id_charge_code."'";
$html.="         data-nombre_paciente='".$parameters["club_redondo"]["ApellidoNombre"]."' ";
$html.="         data-nro_documento='".$parameters["club_redondo"]["DNI"]."' ";
$html.="         data-nro_club_redondo='".$parameters["club_redondo"]["PANClub"]."' ";
$html.="         data-nro_swiss='".$parameters["club_redondo"]["PANSwiss"]."' ";
$html.="         data-obra_social='".$parameters["club_redondo"]["obra_social"]."' ";
$html.="         data-obra_social_plan='".$parameters["club_redondo"]["obra_social_plan"]."' ";
$html.="         data-nro_obra_social='".$parameters["club_redondo"]["nro_obra_social"]."' ";
$html.="         data-matricula='".$parameters["matricula"]."' ";
$html.="         data-medico='".$parameters["medico"]."' ";
$html.="         data-firma='".$parameters["firma"]."' ";
$html.=">Confeccionar orden/indicación</a>";

$html.="      <div class='panel-group rx-hidden' id='accordion'>";
$html.="        <div class='panel panel-default'>";
$html.="          <div class='panel-heading card' style='padding:5px;margin-bottom:5px;'>";
$html.="            <h5 class='panel-title'>";
$html.="              <a data-toggle='collapse' data-parent='#accordion' href='#imagenes' class='btnLoadMessagesTelemedicina' data-id='".$id_charge_code."'>Imágenes recibidas</a>";
$html.="            </h5>";
$html.="          </div>";
$html.="          <div id='imagenes' class='panel-collapse collapse'>";
$html.="             <div class='div-imagenes'></div>";
$html.="          </div>";
$html.="        </div>";
$html.="        <div class='panel panel-default'>";
$html.="          <div class='panel-heading card' style='padding:5px;margin-bottom:5px;'>";
$html.="            <h5 class='panel-title'>";
$html.="              <a data-toggle='collapse' data-parent='#accordion' href='#evaluacion'>Evaluación médica</a>";
$html.="            </h5>";
$html.="          </div>";
$html.="          <div id='evaluacion' class='panel-collapse collapse'>";
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"temperatura","type"=>"number","class"=>"form-control numer dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"ta_constatada","type"=>"text","class"=>"form-control text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"tos","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"expectoracion","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"odinofagia","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"disfagia","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"disnea","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"nauseas","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"vomitos","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"dolor_abdominal","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"diarrea","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"proctorragia","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"disuria","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"polaquiuria","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"edemas","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"parestesias","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"calambres","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"insensibilidad_miembro","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"cefaleas","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"migrana_antecedente","type"=>"tristate","class"=>"text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"migrana_medicada","type"=>"text","class"=>"form-control text dbase"));
$html.=getInputMicro($parameters,array("col"=>"col-md-12","name"=>"otras_evaluaciones","type"=>"textarea","class"=>"form-control textarea dbase"));
$html.="          </div>";
$html.="        </div>";

$html.="        <div class='panel panel-default'>";
$html.="          <div class='panel-heading card' style='padding:5px;margin-bottom:5px;'>";
$html.="            <h5 class='panel-title'>";
$html.="              <a data-toggle='collapse' data-parent='#accordion' href='#recetas' class='btnLoadMessagesTelemedicina btnSoloRecetas' data-id='".$id_charge_code."'>Recetas</a>";
$html.="            </h5>";
$html.="          </div>";
$html.="          <div id='recetas' class='panel-collapse collapse in'>";
$html.="             <div class='div-comunicaciones'></div>";
$html.="          </div>";
$html.="        </div>";

$html.="        <div class='panel panel-default'>";
$html.="          <div class='panel-heading card' style='padding:5px;margin-bottom:5px;'>";
$html.="            <h5 class='panel-title'>";
$html.="              <a data-toggle='collapse' data-parent='#accordion' href='#atenciones'>Atenciones anteriores</a>";
$html.="            </h5>";
$html.="          </div>";
$html.="          <div id='atenciones' class='panel-collapse collapse'>";
$html.="             <div class='div-atenciones'></div>";
$html.="          </div>";
$html.="        </div>";
$html.="      </td>";
$html.="   </tr>";
$html.="</table>";

$html.="</div>";
$html.="<div style='width:100%;'>";
$html.=buildFooterAbmStd($parameters);
$html.="<img src='' class='img-test'/>";

$html.="</div>";
echo $html;
?>

<script>
	initAbmOperatorTasks(<?php echo $id_charge_code;?>,<?php echo $parameters["readonly"];?>,'<?php echo $id_type_task_close;?>',false);
	_FUNCTIONS._logo_receta_left = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFFmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDI0LTA0LTI5VDE4OjAwOjM2LTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyNC0wNS0wNFQxMTo0NToyOS0wMzowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyNC0wNS0wNFQxMTo0NToyOS0wMzowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpjYTEwZmJkZS03MWQzLWM1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";
	
	$("body").off("change", ".cboSwiss").on("change", ".cboSwiss", function () {
	    var _record=JSON.parse(_TOOLS.b64_to_utf8($( "#cboSwiss option:selected" ).attr("data-record")));
		$(".idsocio").html(_record.IdSocio)
		$(".tipo").html(_record.Tipo);
		$(".fechaalta").html(_record.FechaAlta);
		$(".nrocredencial").html(_record.NroCredencial);
		$(".nombre").html(_record.Nombre);
		$(".dni").html(_record.NroDocumento);
		$(".plan").html(_record.Plan);
		$(".sexo").html(_record.sexo);
		$(".edad").html(_record.Edad);
		$(".btn-receta").attr("data-dni",_record.NroDocumento);
		$(".btn-receta").attr("data-nombre",_record.name);
		$(".btn-receta").attr("data-apellido",_record.surname);
		$(".btn-receta").attr("data-sexo",_record.sexo);
		$(".btn-receta").attr("data-fechanacimiento",_record.FechaNacimiento);
		$(".btn-receta").attr("data-panswiss",$(".nrocredencial").html());
		$(".btn-telemedicina-msg").attr("data-nombre_paciente",(_record.surname+", "+_record.name));
		$(".btn-telemedicina-msg").attr("data-nro_documento",_record.NroDocumento);
		$(".btn-telemedicina-msg").attr("data-nro_swiss",_record.NroCredencial);
		$(".btn-telemedicina-msg").attr("data-nro_club_redondo",_record.NroDocumento);
	});

	$("body").off("click", ".btn-videoconferencia").on("click", ".btn-videoconferencia", function () {
		/*NeoVideo implementation! */
		$(this).fadeOut("slow");
		var _target = $(this).attr("data-target");
		var _auditoria = $(this).attr("data-auditoria");
		var _id_charge_code = $(this).attr("data-id-charge-code");
        _NEOVIDEO._id_application = 6;
        _NEOVIDEO._username = "credipaz";
        _NEOVIDEO._password = "08.!Rcp#@80";
		_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height = "600px";
		_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width = "100%";
		_NEOVIDEO._INTERFACE_CONFIG_OVERWRITE.TOOLBAR_BUTTONS = ['microphone', 'camera', 'hangup', 'chat', 'tileview'];
		_NEOVIDEO._CONFIG_OVERWRITE.disableSelfView = true;
		_NEOVIDEO._CONFIG_OVERWRITE.disableSelfViewSettings = true;

		_NEOVIDEO.onDisconnect = function () {
			$(".btn-videoconferencia").fadeIn("slow");
			clearInterval(_VIDEOCHAT._tmrVideoActive);
			$(".alert-elapsed").addClass("d-none");
			$(".time-elapsed").html("");
			$(".time-exceeded").addClass("d-none");
			$(".btn-videoconferencia").fadeIn("fast");
			$(_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.target).addClass("d-none").hide();
		};
		_NEOVIDEO.onJoinOpenSession($(this).attr("data-token"),true).then(function (conn) {
            $('[name ^= "jitsiConferenceFrame"]').css({ "height": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height, "width": _NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width });
			$(_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.target).removeClass("d-none").fadeIn("fast");

        }).catch(function (err) {});
	});
	$(".cboSwiss").change();
</script>
