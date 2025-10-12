<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<h1><?php echo $subject;?></h1>

<h3>Se ha recibido un pedido de autorización, con los siguiente datos</h3>

<?php
$html="<table>";
$html.="   <tr><td><b>CUIT</b><td><td>".$cuit."</td></tr>";
$html.="   <tr><td><b>Razón social</b><td><td>".$socialname."</td></tr>";
$html.="   <tr><td><b>Nombre de fantasía</b><td><td>".$fantasyname."</td></tr>";
$html.="   <tr><td><b>Domicilio</b><td><td>".$address."</td></tr>";
$html.="   <tr><td><b>Localidad</b><td><td>".$locality."</td></tr>";
$html.="   <tr><td><b>Código postal</b><td><td>".$zipcode."</td></tr>";
$html.="   <tr><td><b>Teléfono</b><td><td>".$sDiscado1."-".$sTelefono1."</td></tr>";
$html.="   <tr><td><b>E-mail</b><td><td>".$sEmail."</td></tr>";
$html.="   <tr><td><b>Encargado</b><td><td>".$manager."</td></tr>";
$html.="   <tr><td><b>Horario de atención</b><td><td>".$hours."</td></tr>";
$html.="   <tr><td><b>Horario de contacto</b><td><td>".$contacttime."</td></tr>";
$html.="   <tr><td><b>Comentarios</b><td><td>".$comments."</td></tr>";
$html.="</table>";
echo $html;
?>
