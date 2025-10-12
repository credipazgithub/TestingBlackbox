<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container marco card" style="margin-top:10px;">
    <div class="row" style="margin-top:15px;">
        <div class="form-group col-12">
            <h2 style="color:green;">TEST - Su pago se ha procesado con éxito</h2>
            <br/>
            <h5><b>Por favor cierre esta solapa de su navegador para continuar y acceder al recibo de la operación</b></h5>
            <div class="d-none">
			    <?php echo $post["txndate_processed"]."<br/>";?>
			    <?php echo $post["oid"]."<br/>";?>
			    <?php echo $post["cardnumber"]."<br/>";?>
			    <?php echo $post["comments"]."<br/>";?>
			    <?php echo "POST: ".serialize($post)."<br/>";?>
			    <?php echo "GET: ".serialize($get)."<br/>";?>
            </div>
        </div>
    </div>
</div>
