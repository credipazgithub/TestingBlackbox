<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<h4>Se informa la atención del paciente en espera</h4>
<p><?php echo $patient["name_club_redondo"].", socio Nº".$patient["id_club_redondo"].", teléfono Nº".$patient["telefono"]." - ".$patient["especialidad"];?></p>
<p>
   <?php echo "Solicitado: <b>".date(FORMAT_DATE_DMYHMS, strtotime($patient["cc_created"]))."</b><br/>";
         echo "Atendido: <b>".date(FORMAT_DATE_DMYHMS, strtotime($patient["created"]))."</b><br/>";
         echo "Demora: <b>".$patient["demora"]."</b><br/> ";
         echo "Finalizado: <b>".date(FORMAT_DATE_DMYHMS, strtotime($patient["fum"]))."</b><br/>";
   ?>
</p>

