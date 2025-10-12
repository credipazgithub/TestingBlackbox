<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="container-flex" style="padding:5px;">
    <h4><?php echo lang('m_users_sip');?></h4>
    <div id="document">
        <div class="form-group label-floating">
            <div class="input-group">
            <span class="input-group-addon"><?php echo lang('p_username');?></span>
            <input type="number" id="username" name="username" class="form-control username validate"/>
            <span class="input-group-btn">
                <button data-type="validate" type="button" class="btn btn-sm btn-primary btn-raised btn-status-sip"><?php echo lang('p_search');?></button>
            </span>
            </div>
        </div>
    </div>
    <?php echo $this->load->view(MOD_TELEPHONY."/common/general_result",null,true);?>
</div>
<script>
    $.getScript( './application/views/mod_telephony/users_sip/form.js', function() { });
</script>
