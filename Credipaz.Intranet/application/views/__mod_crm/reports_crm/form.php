<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="container-flex" style="padding:5px;">
    <h4><?php echo lang('m_reports_crm');?></h4>
    <form class="form-horizontal">
        <fieldset>
            <div class="form-group label-floating">
                <div class="shadow p-1">
					<table>
						<tr>
							<td>
								<span class="badge badge-primary"><?php echo lang('p_id_type_contact_channel');?></span>
								<?php echo $parameters["controls"]["id_type_contact_channel"];?>
							</td>
							<td>
								<span class="badge badge-primary"><?php echo lang('p_id_tarjeta');?></span>
								<?php echo $parameters["controls"]["id_tarjeta"];?>
							</td>
							<td>
		                        <span class="badge badge-primary"><?php echo lang('p_id_otro');?></span>
				                <?php echo $parameters["controls"]["id_otro"];?>
							</td>
							<td>
		                        <span class="badge badge-primary"><?php echo lang('p_id_myd');?></span>
								<?php echo $parameters["controls"]["id_myd"];?>
							</td>
						</tr>
						<tr>
							<td>
				                <span class="badge badge-primary"><?php echo lang('p_id_mil');?></span>
		                        <?php echo $parameters["controls"]["id_mil"];?>
							</td>
							<td>
		                        <span class="badge badge-primary"><?php echo lang('p_id_credito');?></span>
								<?php echo $parameters["controls"]["id_credito"];?>
							</td>
							<td>
		                        <span class="badge badge-primary"><?php echo lang('p_id_type_task_close');?></span>
				                <?php echo $parameters["controls"]["id_type_task_close"];?>
							</td>
							<td>
								<span class="badge badge-primary"><?php echo lang('p_username');?></span>
								<?php echo $parameters["controls"]["username"];?>
							</td>
						</tr>
						<tr>
							<td>
								<span class="badge badge-primary"><?php echo lang('p_date_from');?></span>
								<input type="date" id="report_from" name="report_from" class="form-control report_from validate"/>
							</td>
							<td>
								<span class="badge badge-primary"><?php echo lang('p_date_to');?></span>
								<input type="date" id="report_to" name="report_to" class="form-control report_to validate"/>
							</td>
							<td align="center" valign="middle">
			                    <button type="button" class="btn btn-md btn-primary btn-raised btn-execute-crm" data-title="<?php echo lang('msg_rpt_crm');?>" aria-haspopup="true" aria-expanded="false"><?php echo lang('b_request');?></button>
							</td>
						</tr>
					</table>
                </div>
            </div>
        </fieldset>
    </form>
    <?php echo $this->load->view(MOD_CRM."/common/general_result",null,true);?>
</div>
<script>
    $.getScript( './application/views/mod_crm/reports_crm/form.js', function() {});
</script>
