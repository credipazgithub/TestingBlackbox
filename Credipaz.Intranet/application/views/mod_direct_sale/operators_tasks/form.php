<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-full no-gutters loaded-catalog d-none">
	<audio autoplay id='ringerTiendaMil' class='d-none'><source src='' type='audio/mpeg'></audio>
	<h4 class="pt-1"><img src="./assets/img/tiendamil.png" style="width:75px;"/> Vendedor/a disponible</h4>
	<hr/>
	<div class="row no-gutters d-none" style="display:none;">
		<div class="col-12">
			<h5>Transmisión en vivo</h5>
			<p class="p-2 my-1" style='color:white;background-color:grey;'>Cuando se activa el modo de transmisión en vivo, los clientes ingresarán automáticamente a la transmisión en vivo, con su cámara y micrófono apagados.  Los vendedores que no formen parte del vivo, ingresarán del mismo modo que los clientes.</p>
			<a href="#" class="btn btn-md btn-raised btn-success btn-live-start">Iniciar transmisión en vivo</a>
			<a href="#" class="btn btn-md btn-raised btn-danger btn-live-stop d-none">Detener transmisión en vivo</a>
			<div class="divLive"></div>
		</div>
	</div>

	<div class="row no-gutters mt-4">
		<div class="col-6">
			<h5>Clientes en espera</h5>
			<div class="divWaiting"></div>
		</div>
		<div class="col-6 text-center">
		    <div class="row text-center">
			<?php
               $html = "";
			   foreach ($cams as $record) {
				  $url=$record["url"];
                  if ($record["offline"] == "") {
                    $html .= "<div class='col-3 shadow-md p-2 text-center mb-2 ml-1' style='background-color:ghostwhite;border:solid 1px grey;border-radius:15px;'>";
                    $html .= "   <a href='" . $url . "' target='_blank'>" . $record["description"] . " <img src='https://intranet.credipaz.com/assets/img/search.gif' style='width:24px;'/><br/><span class='badge badge-success'>Transmitiendo</span></a>";
                    $html .= "</div>";
                  } else {
                    $html .= "<div class='col-3 shadow-md p-2 text-center mb-2 ml-1' style='background-color:ivory;border:dotted 1px grey;border-radius:15px;'>";
                    $html .= $record["description"]."<br/><span class='badge badge-danger'>Apagada</span>";
                    $html .= "</div>";
                  }
			   }
               //echo $html;
            ?>
		    </div>
		</div>
	</div>
	<div class="container msgInit">
		<div class="row">
			<div class="col-12 p-2 text-center" style="border:double 3px red;">
				<p>Verifique las solapas adicionales que se han abierto al ingresar, debe iniciar sesión en cada cámara.</p>
				<p>Usuario: <b>admin</b></p>
				<p>Password: <b>credipaz2022</b></p>
				<p>Una vez verifique que las cámaras estén funcionando presione <a href="#" class="btnInicarCamaras btn btn-sm btn-raised btn-success">Iniciar cámaras</a></p>
			</div>
		</div>
	</div>
</div>


<div class="container-full no-gutters loading-catalog">
	<div class="row no-gutters mt-4">
		<div class="col-4">
		    <img src="https://intranet.credipaz.com/assets/img/wait.gif" style="width:96px;" />
		</div>
		<div class="col-8">
			<span class="p-1 badge badge-warning" style="font-size:1em;">Cargando el catálogo.<br/>Aguarde unos instantes...</span>
		</div>
	</div>
</div>

<?php
$html = "<div class='p-1 barraCamaras d-none' style='border:inset 2px grey;position:absolute;top:80px;right:1px;background-color:ghostwhite;z-index:9999999;'>";
foreach ($cams as $record) {
    $key = ("cam" . $record["id"]);
    $keyB = ("bad" . $record["id"]);
    $keyX = ("adm" . $record["id"]);
    $url = $record["url"];
    $named = $record["named"];
    $html .= "<div class='text-center mt-1 mb-1' style='width:100px;'>";
    if ($record["offline"] == "") {
        $html .= "<div id='". $key."' class='camera p-1 ". $key."' data-msg='" . $keyB . "' data-src='" . $named . "' style='width:100px;height:40px;font-size:11px;background-color:ghostwhite;border:solid 1px silver;border-radius:15px;cursor:pointer;'>";
        $html .= "   <span class='badge badge-dark " . $keyB . "' style='display:block;'>" . $record["description"] . "</span>";
        $html .= "   <span class='badge badge-success " . $keyB . "' style='display:block;'>Transmitiendo</span>";
        $html .= "</div>";
        $html .= "   <a data-top='" . $key . "' href='" . $url . "' target='_blank' class='d-none admCam " . $keyX . " pl-1 btn btn-raised btn-sm btn-info' style='position:absolute;right:0;font-size:9px;'>A</a>";
    } else {
        $html .= $record["description"] . "<br/><span class='badge badge-danger'>Apagada</span>";
    }
    $html .= "</div>";
}
$html .= "<div class='areaClose text-center mt-2 mb-1 pt-2 d-none' style='width:100px;border-top:solid 1px grey;'>";
$html .= "   <a href='#' class='btn btn-sm btn-warning btn-raised btnCerrarCamara'>Cerrar</a>";
$html .= "</div>";
$html .= "</div>";
echo $html;
?>

<script>
    $.getScript('./application/views/mod_direct_sale/operators_tasks/form.js?' + _TOOLS.UUID(), function() {
		buildInterface();
		$(".camera").each(function () { initCamera($(this).attr("data-src")); });

		$("body").off("click", ".camera").on("click", ".camera", function (e) {
			$(".camera").css({ "border": "solid 1px silver" });
			$(this).css({ "border": "solid 2px #E9148B" });
			toggleCamera($(this).attr("data-src"), $(this).attr("id"));
		});
		$("body").off("click", ".btnCerrarCamara").on("click", ".btnCerrarCamara", function (e) {
			$(".areaClose").addClass("d-none");
			$(".imgBig").remove();
			$(".camera").css({ "border": "solid 1px silver" });
		});
		$("body").off("click", ".btnInicarCamaras").on("click", ".btnInicarCamaras", function (e) {
			for (var i = 0; i < _LW.length; i++) { _LW[i].close(); }
			$(".msgInit").remove();
			$(".barraCamaras").removeClass("d-none");
			setTimeout(function () {
				$(".admCam").each(function () {
					$(this).removeClass("d-none");
					var _top = ($("." + $(this).attr("data-top")).offset().top - $(".barraCamaras").offset().top - 2);
					$(this).css({ "top": _top + "px" });
				});
			}, 100);
		});
	});
</script>
