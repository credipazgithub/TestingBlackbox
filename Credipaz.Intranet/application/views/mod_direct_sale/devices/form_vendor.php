<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-full modeMonitoreo">
	<h1>Consola del operador</h1>
	<a href='#' class='btn btn-sm btn-raised btn-primary btn-connect-monitor'>
		<span class='material-icons'>play_arrow</span> Activar monitoreo
	</a>
	<a href='#' class='btn btn-sm btn-raised btn-warning btn-disconnect-monitor d-none'>
		<span class='material-icons'>stop</span> Desactivar monitoreo
	</a>
	<?php
	$html="<div class='row divMonitors d-none'>";
	foreach ($devices["data"] as $item){
		$id=$item["id"];
		$html.="<div class='align-items-center shadow col-3 p-2 m-0' style='position:relative;'>";
		$html.="   <span class='alert-requested-".$id." d-none material-icons' style='position:absolute;left:10px;top:10px;color:red;font-size:40px;'>notification_important</span>";
		$html.="   <img class='img-responsive imgMonitor imgMonitor-".$item["id"]."' src='https://intranet.credipaz.com/assets/img/wait.gif' style='width:100%;'/>";
	  	$html.="   <div class='card-body p-2 m-0'>";
	   	$html.="	  <h5 class='card-title'>".$item["description"]."</h5>";
   		$html.="      <p class='message-".$id." card-text'></p>";
   		$html.="	  <p class='disconnected-".$id." d-none card-text pt-2'><span class='py-2 badge badge-danger' style='font-size:16px;width:100%;'>No disponible</span></p>";
   		$html.="      <a data-full-name='".$profile["username"]."' data-alias='".$profile["description"]."' data-height='600' data-platform-name='Videoconsulta' data-room-name='DEVICE".$id."' data-domain='".SERVER_SUB."' data-target='#meet' data-id-device='".$id."' data-id='".$id."' href='#' class='btn-open-session connected-".$id." d-none btn btn-primary btn-raised btn-block' data-id_transaction='0'>Inicie atención</a>";
	  	$html.="   </div>";
		$html.="</div>";
	}
	$html.="</div>";
	echo $html;
	?>
</div>
<div class="container-full modeVideochat d-none">
	<div class='row'>
	   <div class='col-6 card shadow mx-1 p-2'>
	      <h2>Asistencia a tótem</h2>
		  <?php echo $browser_id_request;?>

		  <div class="container-full area-form area-form-1 adm-request">
		     
		  </div>

	   </div>

	   <div class='col-5'>
	      <div id='meet' style='width:100%;display:block;' class='shadow p-2 m-0'></div>
 		  <!--
		  <a data-id='' href='#' class='btn-close-session btn btn-primary btn-raised'>Cerrar sesión</a>
		  -->
	   </div>
	</div>
</div>
<script>
    var today = new Date();
    $.getScript('./application/views/mod_direct_sale/devices/form_vendor.js?' + today.toDateString(), function() {});
</script>
