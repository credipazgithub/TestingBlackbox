<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>

<h1><?php echo $lang['msg_legalRequestNew'];?></h1>
<h3>Se ha registrado el ingreso de una nueva solicitud de asesoría legal</h3>

<ul>
<li>ID: <?php echo $operators_tasks["id"];?></li>
<li>Nombre: <?php echo $operators_tasks["name_club_redondo"];?></li>
<li>CR: <?php echo $operators_tasks["id_club_redondo"];?></li>
<li>Teléfono: <?php echo $operators_tasks["telefono"];?></li>
</ul>

<h4>Para detalles de la solicitud, acceda con su cuenta a <a href="https://intranet.credipaz.com" target="_blank">Intranet Credipaz</a></h4>
