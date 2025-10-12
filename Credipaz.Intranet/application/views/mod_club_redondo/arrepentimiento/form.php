<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="mt-4 form-alta container marco text-center">
    <h2 class="nd-asociate mb-2" style="color:rgb(0, 71, 186);">SOLICITUD DE ARREPENTIMIENTO</h2>
    <div class="row">
        <div class="col-12">
            <input type="text" class="boxedfield validatemodal dbasearrepentimiento Nombre" id="Nombre" name="Nombre" placeholder="Nombre" />
        </div>
        <div class="col-12">
            <input type="text" class="boxedfield validatemodal dbasearrepentimiento Apellido" id="Apellido" name="Apellido" placeholder="Apellido" />
        </div>
        <div class="col-12">
            <input type="text" class="boxedfield validatemodal dbasearrepentimiento DNI" id="DNI" name="DNI" placeholder="DNI" />
        </div>
        <div class="col-12">
            <input type="text" class="boxedfield validatemodal dbasearrepentimiento Telefono" id="Telefono" name="Telefono" placeholder="Teléfono celular" />
        </div>
        <div class="col-12">
            <input type="text" class="boxedfield validatemodal dbasearrepentimiento Email" id="Email" name="Email" placeholder="Email" />
        </div>
        <div class="col-12">
            <h5 class="nosite" style="font-size:25px;font-weight:bold;color:rgb(0, 71, 186);">Seleccione Género</h5>
            <table style="width:100%;">
                <tr>
                    <td align="center" valign="bottom">
						<span class="pt-2" style="font-size:18px;font-weight:bold;color:rgb(0, 71, 186);">Femenino</span>
                        <div class="form-check radio-primary pl-5" style="display:inline;"> 
                            <input type="radio" name="sexo" id="sexo" value="F" class="sex-F form-check-input" style="width:28px;height:28px;" />
                        </div>
                    </td>
                    <td align="center" style="width:5%;"></td>
                    <td align="center" valign="bottom">
						<span class="pt-2" style="font-size:18px;font-weight:bold;color:rgb(0, 71, 186);">Masculino</span>
                        <div class="form-check radio-primary pl-5" style="display:inline;">
                            <input type="radio" name="sexo" id="sexo" value="M" class="sex-M form-check-input" style="width:28px;height:28px;" />
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <hr style="border:solid 2px rgb(0, 71, 186);"/>

    <div class="row">
        <div class="col-12 text-center">
            <div id="widget" style="width:100%;text-align: center;display:flex;"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-right">
            <a href="#" disabled class="btnAction btnAccept btn btn-md btn-success btn-raised pull-right" style="color:black;border-radius:10px;background-color:white !important;"><?php echo lang('b_accept');?></a>
        </div>
    </div>
</div>
<hr class="nosite" style="border:solid 2px #ff24ff;"/>

<script>
	window.addEventListener('load', function() {
		setTimeout(function(){
			let message = { height: document.body.scrollHeight, width: document.body.scrollWidth };	
			window.top.postMessage(message, "*");
		},100);
	});

	var _server = (window.location.protocol+"//"+window.location.hostname+":"+window.location.port + "/");
    $.getScript(_server + '/application/views/mod_club_redondo/arrepentimiento/form.js', function() {
		$.getScript("https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit", function () {
			$(".btnAccept").addClass("d-none");
		});
    });
</script>
