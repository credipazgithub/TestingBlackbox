<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<h1><?php echo $subject;?></h1>

<h4>Estimado/a <?php echo $Nombre." ".$Apellido;?></h4>
<p>Este es el código que debes ingresar para terminar el proceso de <b><?php echo $producto;?></b>.</p>

<table><tr><td><h2 style="border:solid 1px silver;padding:4px;font-size:2.5em;"><?php echo implode(' ',str_split($codigo));?></h2></td></tr></table>

<p>En caso que necesites contactarte con nosotros, podes hacerlo en los siguientes canales de atención al cliente</p>
<ul>
	<li>Teléfono: <b>0810 333 9009</b>, de Lunes a Sábados de 9 a 20hs</li>
	<li>Email: <b>info@credipaz.com</b></li>  
	<li>Sucursales Credipaz: <a href="https://www.credipaz.com/sucursales" target="_blank">https://www.credipaz.com/sucursales</a></li>
</ul>

<p>Saludos cordiales,</p>
<p><b>Equipo Credipaz</b></p>
