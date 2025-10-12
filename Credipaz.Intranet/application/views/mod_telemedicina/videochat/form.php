<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
?>

<input type="hidden" id="id_charge_code" name="id_charge_code" value="<?php echo $id_charge_code;?>" />
<input type="hidden" id="fullname" name="fullname" value="<?php echo $fullname;?>" />
<input type="hidden" id="alias" name="alias" value="<?php echo $alias;?>" />
<input type="hidden" id="platformname" name="platformname" value="<?php echo $platformname;?>" />
<input type="hidden" id="roomname" name="roomname" value="<?php echo $roomname;?>" />
<input type="hidden" id="domain" name="domain" value="<?php echo $domain;?>" />
<input type="hidden" id="close_mode" name="close_mode" value="<?php echo $close_mode;?>" />

<div style='width:100%;color:black;' class='alert alert-info meet-wait d-none text-center'>
   <p>Su médico lo atenderá en <span class='badge badge-primary meet-countdown' style='font-size:20px;'></span></p>
</div>
<div id="meet" class="p-0" style="width:100%;height:100%;"></div>

