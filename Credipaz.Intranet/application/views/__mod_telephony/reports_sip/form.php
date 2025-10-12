<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="container-flex" style="padding:5px;">
    <h4><?php echo lang('m_reports_sip');?></h4>
    <form class="form-horizontal">
        <fieldset>
            <div class="form-group label-floating">
                <div class="input-group">
                    <span class="badge badge-dark"><?php echo lang('p_username');?></span>
                    <?php echo $parameters["controls"]["username"];?>
                    <span class="badge badge-dark"><?php echo lang('p_date_from');?></span>
                    <input type="date" id="report_from" name="report_from" class="form-control report_from validate"/>
                    <span class="badge badge-dark"><?php echo lang('p_date_to');?></span>
                    <input type="date" id="report_to" name="report_to" class="form-control report_to validate"/>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-primary btn-raised dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('b_request');?> <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="#" data-title="<?php echo lang('msg_rpt_active_sip');?>" data-report="Logout" class="btn-execute-sip"><?php echo lang('b_rpt_active_sip');?></a></li>
                            <li><a href="#" data-title="<?php echo lang('msg_rpt_call_sip');?>" data-report="Hangup" class="btn-execute-sip"><?php echo lang('b_rpt_call_sip');?></a></li>
                            <li><a href="#" data-title="<?php echo lang('msg_rpt_pause_sip');?>" data-report="Unpause" class="btn-execute-sip"><?php echo lang('b_rpt_pause_sip');?></a></li>
                            <li><a href="#" data-title="<?php echo lang('msg_rpt_entrantes');?>" data-report="Entrantes" class="btn-execute-sip"><?php echo lang('b_rpt_entrantes');?></a></li>
                            <li><a href="#" data-title="<?php echo lang('msg_rpt_salientes');?>" data-report="Salientes" class="btn-execute-sip"><?php echo lang('b_rpt_salientes');?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
    <?php echo $this->load->view(MOD_TELEPHONY."/common/general_result",null,true);?>
</div>
<script>
    $.getScript( './application/views/mod_telephony/reports_sip/form.js', function() {});
</script>
