<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container marco card" style="margin-top:10px;">
    <div class="row" style="margin-top:15px;">
        <div class="form-group col-12">
            <h3 style="color:blue;">Notificación interna de pago</h3>
            <br/>
            <h5><b>Por favor cierre esta solapa de su navegador para continuar</b></h5>
            <div class="d-none">
			    <?php echo "POST: ".serialize($post)."<br/>";?>
			    <?php echo "GET: ".serialize($get)."<br/>";?>
            </div>
        </div>
    </div>
</div>
