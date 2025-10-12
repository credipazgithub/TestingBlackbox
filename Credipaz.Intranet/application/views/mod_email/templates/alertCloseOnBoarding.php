<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<h1><?php echo $subject;?></h1>

<h4>Estimado/a <?php echo $Nombre." ".$Apellido;?></h4>
<p>Ya está lista tu operación de <b><?php echo $producto;?></b>.</p>

<p>Hacé click en el link para firmar la solicitud y finalizar el proceso</p>

<table><tr><td><a style="border:solid 1px silver;padding:4px;font-size:1.5em;" href="<?php echo $link;?>">¡Click aquí!</a></td></tr></table>

<p>En caso que necesites contactarte con nosotros, podes hacerlo en los siguientes canales de atención al cliente</p>
<ul>
	<li>Teléfono: <b>0810 333 9009</b>, de Lunes a Sábados de 9 a 20hs</li>
	<li>Email: <b>info@credipaz.com</b></li>  
	<li>Sucursales Credipaz: <a href="https://www.credipaz.com/sucursales" target="_blank">https://www.credipaz.com/sucursales</a></li>
</ul>

<p>Saludos cordiales,</p>
<p><b>Equipo Credipaz</b></p>
