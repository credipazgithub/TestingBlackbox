<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-full modeTotem">
	<div class="container-full divDevices">
		<h1>Consola de dispositivos</h1>
		<?php
		$html="";
		$html.="<div class='row'>";
		foreach ($devices["data"] as $item){
		    $id=$item["id"];
			$html.="<div class='col-2 align-items-center shadow m-0 p-2'>";
			$html.="   <img class='img-".$id."' src='./assets/img/kiosko.png' style='width:100%;'>";
			$html.="   <p>".$item["description"]."</p>";
			$html.="   <a href='#' data-id='".$id."' class='btn-connect-device-".$id." btn-connect-device btn btn-raised btn-success btn-xs btn-block'>Conectar</a>";
			$html.="   <span id='badge".$id."' class='badge badge-info' style='display:none;'>En uso <img src='./assets/img/search.gif' style='height:24px;'/></span>";
			$html.="</div>";
		}
		$html.="</div>";
		echo $html;
		?>
	</div>
	<div class="videoStop d-none">
	   <a href='#' class='btn btn-sm btn-raised btn-warning btn-disconnect-device' data-id=''><span class='material-icons'>stop</span> Desconectar</a>
	   <a href='#' class='btn btn-sm btn-raised btn-dark btn-fullscreen' data-id=''><span class='material-icons'>home_max</span> Pantalla completa</a>
	</div>

	<canvas style="left:0px;top:0px;border:solid 1px #ddd;background-color:white;display:none;" id="canvas"></canvas>    
	<canvas style="left:0px;top:0px;display:none;" id="canvas-resize"></canvas>    
	<div id="videoWrapper" class="text-center">
       <video id="video" class="video d-none" controls autoplay style="left:0px;top:0px;width:640px;height:480px;"></video>
	   <video id="videoLocal" class="videoLocal d-none" controlsList="nodownload nofullscreen noremoteplayback" autoplay style="left:0px;top:0px;width:100%;height:100%;"></video>

	   <a href='#' class='videoLocal d-none btn btn-sm btn-raised btn-success btn-request-credito px-4' data-id='' style="font-size:32px;display:block;position:absolute;bottom:20px;left:20px;padding:5px;border-radius:15px;">
	      Solicitar Crédito
	   </a>
	   <a href='#' class='videoLocal d-none btn btn-sm btn-raised btn-primary btn-request-operator px-4' data-id='' style="font-size:32px;display:block;position:absolute;bottom:20px;right:20px;padding:5px;border-radius:15px;">
	      Solicitar atención
	   </a>
	   <span class='alert-wait d-none alert alert-success px-4' data-id='' style="font-size:32px;display:block;position:absolute;bottom:20px;left:20px;padding:5px;">
	      <b>Aguarde unos instantes... Un operador lo atenderá a la brevedad</b>
	   </span>
	</div>

	<div id="creditWrapper" class="creditWrapper d-none text-center mt-2">
	   <iframe src="https://totem.credipaz.com/?id_user_active=<?php echo $id_user_active;?>" class="onboarding" width="100%" height="5000" style="border:solid 0px red;"/>

	   <a href='#' class='creditLocal d-none btn btn-sm btn-raised btn-info btn-request-new my-1 px-2' data-id='' style="font-size:22px;display:block;position:absolute;top:0px;left:20px;border-radius:5px;">
	      <span class='material-icons'>refresh</span> Nueva
	   </a>

	   <a href='#' class='creditLocal d-none btn btn-sm btn-raised btn-secondary btn-request-video px-4' data-id='' style="font-size:32px;display:block;position:absolute;bottom:20px;left:20px;padding:5px;border-radius:15px;">
	      Volver a publicidad
	   </a>
	   <a href='#' class='creditLocal d-none btn btn-sm btn-raised btn-primary btn-request-operator px-4' data-id='' style="font-size:32px;display:block;position:absolute;bottom:20px;right:20px;padding:5px;border-radius:15px;">
	      Solicitar atención
	   </a>
	   <span class='alert-wait d-none alert alert-success px-4' data-id='' style="font-size:32px;display:block;position:absolute;bottom:100px;left:20px;padding:5px;">
	      <b>Aguarde unos instantes... Un operador lo atenderá a la brevedad</b>
	   </span>
	</div>

</div>

<div class="modeVideochat d-none" style="position:absolute;left:0px;top:0px;width:100vw;height:100vh;">
	<div style='width:100%;color:black;margin-top:0px;padding:5px;' class='meet-wait text-center card'>
		<h4>¡Un representante comercial, quiere contactarlo!</h4>
		<h4><span class='badge badge-primary meet-countdown' style='font-size:40px;'></span></h4>
		<div class='meet-wait text-center' style='height:100vh;width:100vw;'>
			<img src="https://intranet.credipaz.com/assets/img/wait.gif" style="padding-top:15%;width:25%;" />
		</div>
	</div>
	<div id="meet" class="p-0 d-none meet" style="width:100vw;height:100vh;"></div>
</div>


<script>
    var today = new Date();
    $.getScript('./application/views/mod_direct_sale/devices/form_device.js?' + today.toDateString(), function() {
	   <?php foreach ($files as $item){ 
	      $item=str_replace("./","https://intranet.credipaz.com/",$item); ?>
	      _vPublish.push("<?php echo $item; ?>");
	   <?php }?>
    });
</script>
