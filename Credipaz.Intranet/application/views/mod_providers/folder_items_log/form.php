<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="container-flex" style="padding:5px;">
    <h4><?php echo lang('m_mark_user_read');?></h4>
    <div id="legajo" class="container">
        <form class="form-horizontal">
            <fieldset>
                <div class="form-group label-floating">
                    <div class="input-group">
                    <span class="input-group-addon"><?php echo lang('p_username');?></span>
                    <input type="text" id="mark_username" name="mark_username" class="form-control mark_username validate"/>
                    <span class="input-group-btn">
                        <button data-type="validate" type="button" class="btn btn-sm btn-primary btn-raised btn-consultar"><?php echo lang('b_process');?></button>
                    </span>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<script>
    $.getScript( './application/views/MOD_PROVIDERS/folder_items_log/form.js', function() { });
</script>
