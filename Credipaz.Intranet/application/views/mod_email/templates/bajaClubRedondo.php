<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<h1><?php echo $subject;?></h1>

<h3>Se ha recibido un pedido de baja Mediya, con los siguiente datos</h3>

<?php
$html="<table>";
$html.="   <tr><td><b>Nombre y apellido</b><td><td>".$Nombre." ".$Apellido."</td></tr>";
$html.="   <tr><td><b>DNI</b><td><td>".$DNI."</td></tr>";
$html.="   <tr><td><b>Sexo</b><td><td>".$Sexo."</td></tr>";
$html.="   <tr><td><b>Teléfono</b><td><td>".$Telefono."</td></tr>";
$html.="   <tr><td><b>E-mail</b><td><td>".$Email."</td></tr>";
$html.="   <tr><td><b>Motivo</b><td><td>".$Motivo."</td></tr>";
$html.="</table>";
echo $html;
?>
