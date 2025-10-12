<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>

<h1><?php echo $lang['msg_legalRequestFirst'];?></h1>
<h3>Se ha efectuado un primer contacto con la solicitud de asesoría legal de referencia</h3>

<ul>
<li>ID: <?php echo $operators_tasks["id"];?></li>
<li>Nombre: <?php echo $operators_tasks["name_club_redondo"];?></li>
<li>CR: <?php echo $operators_tasks["id_club_redondo"];?></li>
<li>Teléfono: <?php echo $operators_tasks["telefono"];?></li>
<li>Asignado a: <?php echo $operators_tasks["lawyer"];?></li>
<li>Fecha agenda contacto: <?php echo $operators_tasks["scheduled_date"]." a las ".$operators_tasks["scheduled_time"];?></li>
</ul>

<h4>Para detalles de la solicitud, acceda con su cuenta a <a href="https://intranet.credipaz.com" target="_blank">Intranet Credipaz</a></h4>
