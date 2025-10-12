<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>

<h1><?php echo $lang['msg_foraproval_alert'];?></h1>
<h3>Hay expedientes que requieren su REVISIÓN</h3>
<br/>
<ul>
   <li>ID: <?php echo $id;?></li>
   <li>Proveedor: <?php echo $provider;?></li>
</ul>
<br/>
<h4>Acceda con su cuenta a <a href="https://intranet.credipaz.com" target="_blank">Intranet Credipaz</a> y revise los expedientes pendientes de aprobación</h4>
