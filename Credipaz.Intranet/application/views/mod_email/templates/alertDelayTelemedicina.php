<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "DATOS email ".json_encode($patient,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<h4>Se alerta por la demora en la atención</h4>
<p><?php echo $patient["name_club_redondo"].", socio Nº".$patient["id_club_redondo"].", teléfono Nº".$patient["telefono"]." - ".$patient["especialidad"];?></p>
<p><?php echo "Solicitado: ".date(FORMAT_DATE_DMYHMS, strtotime($patient["created"]))." <b>Demora al momento del alerta: ".$patient["elapsed"];?></b></p>


