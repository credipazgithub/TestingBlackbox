<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="mt-4 form-alta container marco text-center">
    <h2 class="nd-asociate mb-2" style="color:rgb(0, 71, 186);">CONSULTA LEGAL</h2>
    <div class="row">
        <div class="col-12">
			<select class='boxedfield validatemodal dbaselegales id_type_request' id='id_type_request' name='id_type_request'>
				<option value='' selected>[Elija motivo de la consulta]</option>
				<option value='1'>ACCIDENTE</option>
				<option value='2'>LABORAL</option>
				<option value='3'>DESALOJO</option>
				<option value='4'>SUCESION</option>
				<option value='5'>FAMILIA</option>
				<option value='6'>OTRO</option>
			</select>
        </div>
        <div class="col-12">
            <input type="text" class="boxedfield validatemodal dbaselegales DNI" id="DNI" name="DNI" placeholder="DNI" />
        </div>
        <div class="col-12">
            <input type="text" class="boxedfield validatemodal dbaselegales Telefono" id="Telefono" name="Telefono" placeholder="Teléfono para contacto" />
        </div>
        <div class="col-12 mt-3">
            <textarea class="boxedfield validatemodal dbaselegales Motivo" id="Motivo" name="Motivo" placeholder="Denos algunos detalles"></textarea>
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
    $.getScript(_server + '/application/views/mod_club_redondo/legales/form.js', function() {
		$.getScript("https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit", function () {
   		   $(".btnAccept").addClass("d-none");
		});
    });
</script>
