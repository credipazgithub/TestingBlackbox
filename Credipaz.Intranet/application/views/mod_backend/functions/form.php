<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-full marco">
    <div class='bg-default clearfix'>
       <h2 class='title-abm float-left'><?php echo $title;?></h3>
    </div>
    <div class="row">
        <div class="col-4">
            <h5 style="font-size:30px;font-weight:bold;color:rgb(0, 71, 186);">Usuarios</h5>
            <div class='form-row'><?php echo $controls["id_user_map"];?></div>
            <div class="card shadow mt-2 div-users"></div>
        </div>
        <div class="col-4">
            <h5 style="font-size:30px;font-weight:bold;color:rgb(0, 71, 186);">Grupos</h5>
            <div class='form-row'><?php echo $controls["id_group"];?></div>
            <div class="card shadow mt-2 div-groups"></div>
        </div>
        <div class="col-4">
            <h5 style="font-size:30px;font-weight:bold;color:rgb(0, 71, 186);">Funciones</h5>
            <div class='form-row'><?php echo $controls["id_function"];?></div>
            <div class="card shadow mt-2 div-functions"></div>
        </div>
    </div>
</div>
<script>
    $.getScript('./application/views/mod_backend/functions/form.js', function() {});
</script>
