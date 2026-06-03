<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="container-flex" style="padding:5px;">
    <h4><?php echo lang('m_validate_cbu');?></h4>
    <ul class="nav nav-tabs">
        <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#divcbu"><?php echo lang('b_by_cbu');?></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#divalias"><?php echo lang('b_by_alias');?></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#divdni"><?php echo lang('b_by_dni');?></a></li>
    </ul>
    <div class="tab-content p-2">
        <div id="divcbu" class="tab-pane fade in active">
            <div class="row">
               <div class="col-1"><span class="input-group-addon">CBU</span></div>
               <div class="col-2"><input type="text" id="cbu" name="cbu" class="form-control cbu validate"/></div>
               <div class="col-2"><button data-type="cbu" type="button" class="btn btn-sm btn-primary btn-raised btn-consultar-cbu btn-exec"><?php echo lang('p_search');?></button></div>
            </div>
        </div>
        <div id="divalias" class="tab-pane fade in">
            <div class="row">
               <div class="col-1"><span class="input-group-addon">Alias</span></div>
               <div class="col-2"><input type="text" id="alias" name="alias" class="form-control alias validate"/></div>
               <div class="col-2"><button data-type="alias" type="button" class="btn btn-sm btn-primary btn-raised btn-consultar-alias btn-exec"><?php echo lang('p_search');?></button></div>
            </div>
        </div>
        <div id="divdni" class="tab-pane fade in">
            <div class="row">
               <div class="col-1"><span class="input-group-addon">DNI</span></div>
               <div class="col-2"><input type="number" id="dni" name="dni" class="form-control dni validate"/></div>
               <div class="col-2"><button data-type="dni" type="button" class="btn btn-sm btn-primary btn-raised btn-consultar-dni btn-exec"><?php echo lang('p_search');?></button></div>
            </div>
        </div>
    </div>
    <div class="container-flex resultado px-2" style="display:none;"></div>
</div>
<script>
    $.getScript( './application/views/mod_validate_cbu/validate_cbu/form.js', function() { });
</script>
