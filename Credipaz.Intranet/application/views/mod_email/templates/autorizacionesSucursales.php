<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<h1><?php echo $subject;?></h1>

<h3>Se ha recibido un pedido de autorización, con los siguiente datos</h3>

<?php
$html="<table>";
$html.="   <tr><td><b>Nombre y apellido</b><td><td>".$name." ".$surname."</td></tr>";
$html.="   <tr><td><b>Sexo</b><td><td>".$sSexo."</td></tr>";
$html.="   <tr><td><b>Documento</b><td><td>".$nDocumento."</td></tr>";
$html.="   <tr><td><b>Teléfono</b><td><td>".$sDiscado1."-".$sTelefono1."</td></tr>";
$html.="   <tr><td><b>E-mail</b><td><td>".$sEmail."</td></tr>";
$html.="</table>";
echo $html;
?>
