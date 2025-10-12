<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

    <div class="container-full marco form-statics">
        <h1 class="m-2" style="margin:0px;padding:0px;font-size:26px;font-weight:bold;color:rgb(0, 71, 186);"><?php echo $title;?></h1>
        <div class="form-row">
            <div class="col-9 p-1 m-0">
                <div class="filtros">
                    <div class='fechas' style='padding-right:5px;display:inline-block;'>
                        <span class="badge badge-primary">Desde</span> <input id="FechaDesde" name="FechaDesde" type="date" class="form-control date FechaDesde validate dbaseStatics" />
                    </div>
                    <div class='fechas' style='padding-right:5px;display:inline-block;'>
                        <span class="badge badge-primary">Hasta</span> <input id="FechaHasta" name="FechaHasta" type="date" class="form-control date FechaHasta validate dbaseStatics" />
                    </div>
                    <div class='medicos' style='padding-right:5px;display:inline-block;'>
                        <span class="badge badge-primary">Médico</span> <?php echo $cboDoctors;?>
                    </div>
                    <div style='padding-right:5px;display:inline-block;'>
                        <button class="btn btn-md btn-execute" type="button" style="color:white;background-color:rgb(255, 0, 153);">consultar</button>
                    </div>
                </div>
                <div class="resultados p-2"></div>
            </div>
        </div>
    </div>
<script>
    $.getScript('./application/views/mod_follow/sinisters/form.js', function() {});
</script>
