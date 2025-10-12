<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "DATOS email ".json_encode($patient,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<h4>Se alerta por atención sin grabación de auditoría activa</h4>
<h5>Código de atención: <b><?php echo $patient["id"];?></b></h5>
<p><?php echo $patient["name_club_redondo"].", socio Nº".$patient["id_club_redondo"].", teléfono Nº".$patient["telefono"]." - ".$patient["especialidad"];?></p>
<p><?php echo "Solicitado: ".date(FORMAT_DATE_DMYHMS, strtotime($patient["created"]));?></b></p>


