<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-full marco form-subdiario">
    <div class="form-row">
        <div class="col-6 p-1 m-0">
			<h1 class="m-2" style="margin:0px;padding:0px;font-size:26px;font-weight:bold;color:rgb(0, 71, 186);">
				<img src="./assets/img/mediya2.png" style="height:70px;margin-right:10px;" />
			</h1>
        </div>
        <div class="col-6 p-1 pt-3 m-0">
            <div class="filtros">
                <div class='fechas' style='padding-right:5px;display:inline-block;'>
                    <span class="badge badge-primary">Desde</span> <input id="FechaDesde" name="FechaDesde" type="date" class="form-control date FechaDesde validate" />
                </div>
                <div class='fechas' style='padding-right:5px;display:inline-block;'>
                    <span class="badge badge-primary">Hasta</span> <input id="FechaHasta" name="FechaHasta" type="date" class="form-control date FechaHasta validate" />
                </div>
               <div style='padding-right:5px;display:inline-block;'>
                    <button class="btn btn-md btn-execute" type="button" style="color:white;background-color:rgb(255, 0, 153);">consultar</button>
                </div>
            </div>
        </div>
        <div class="col-12 p-1 m-0">
            <div class="resultados p-2"></div>
        </div>
    </div>
</div>

<script>
    $.getScript('./application/views/mod_dbcentral/mediya/subdiario.js', function() {
 
	});
</script>
