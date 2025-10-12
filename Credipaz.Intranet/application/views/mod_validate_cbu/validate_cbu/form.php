<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="container-flex" style="padding:5px;">
    <h4><?php echo lang('m_validate_cbu');?></h4>
    <ul class="nav nav-tabs">
        <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#cbu"><?php echo lang('b_by_cbu');?></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#alias"><?php echo lang('b_by_alias');?></a></li>
    </ul>
    <div class="tab-content p-2">
        <div id="cbu" class="tab-pane fade in active">
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group label-floating">
                        <div class="input-group">
                        <span class="input-group-addon">CBU</span>
                        <input type="text" id="cbu" name="cbu" class="form-control cbu validate"/>
                        <span class="input-group-btn pl-2">
                            <button data-type="cbu" type="button" class="btn btn-sm btn-primary btn-raised btn-consultar-cbu"><?php echo lang('p_search');?></button>
                        </span>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div id="alias" class="tab-pane fade in">
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group label-floating">
                        <div class="input-group">
                        <span class="input-group-addon">Alias</span>
                        <input type="text" id="alias" name="alias" class="form-control alias validate"/>
                        <span class="input-group-btn pl-2">
                            <button data-type="alias" type="button" class="btn btn-sm btn-primary btn-raised btn-consultar-alias"><?php echo lang('p_search');?></button>
                        </span>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="card resultado py-2" style="display:none;"></div>
</div>
<script>
    $.getScript( './application/views/mod_validate_cbu/validate_cbu/form.js', function() { });
</script>
