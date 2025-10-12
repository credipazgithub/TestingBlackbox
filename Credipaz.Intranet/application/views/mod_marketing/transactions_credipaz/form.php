<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-full m-0 p-1">
    <h2 style="text-align:left;margin:0px;padding:0px;font-size:22px;font-weight:bold;color:#dd127b;font-family: 'Poppins-Bold' !important;">Indicadores de marketing CREDIPAZ</h2>        
    <div class="row mt-2 align-items-end">
        <div class="col-4 pt-2">
            <label>Desde el </label>
            <input type="date" class="date form-control fecha_desde validate" id="fecha_desde" name="fecha_desde" placeholder="Fecha desde" />
        </div>
        <div class="col-4 pt-2">
            <label>Hasta el </label>
            <input type="date" class="date form-control fecha_hasta validate" id="fecha_hasta" name="fecha_hasta" placeholder="Fecha hasta" />
        </div>
        <div class="col-4 pt-2 align-">
			<button class="ml-1 btn btn-md btn-primary btn-raised btn-execute" type="button">Consultar</button>
        </div>
    </div>
</div>
<div class="container-full datos-informados m-0 mt-2 p-1 d-none"></div>

<script>
    $.getScript('./application/views/mod_marketing/transactions_credipaz/form.js', function() {

	});
</script>
