<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container marco card" style="margin-top:10px;">
    <div class="row" style="margin-top:15px;">
        <div class="form-group col-12">
            <h2 style="color:red;">Su pago no ha podido ser procesado</h2>
            <br/>
            <div class="p-2 card">
			    <p>Estado: <?php echo $post["status"]."<br/>";?></p>
			    <p>Motivo: <?php echo $post["fail_reason"]."<br/>";?></p>
                <p style='font-weight:bold;color:red;'>Revise los datos de su tarjeta, consulte con su banco o tarjeta y reintente</p>
            </div>
            <br/>
            <h5><b>Por favor cierre esta solapa de su navegador para continuar</b></h5>
            <div class="">
			    <?php echo "POST: ".serialize($post)."<br/>";?>
			    <?php echo "GET: ".serialize($get)."<br/>";?>
            </div>
        </div>
    </div>
</div>
