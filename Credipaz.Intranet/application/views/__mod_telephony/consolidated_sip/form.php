<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="container-flex" style="padding:5px;">
    <h4><?php echo lang('m_consolidated_sip');?></h4>
    <div id="document">
        <div class="form-group label-floating">
            <div class="input-group">
            <span class="input-group-btn">
                <button data-type="validate" type="button" class="btn btn-sm btn-primary btn-raised btn-status-sip-all d-none"><?php echo lang('p_search');?></button>
            </span>
            </div>
        </div>
    </div>
    <?php echo $this->load->view(MOD_TELEPHONY."/common/general_result",null,true);?>
</div>
<script>
    $.getScript( './application/views/mod_telephony/consolidated_sip/form.js', function() { });
</script>
