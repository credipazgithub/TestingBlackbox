<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<footer class="pt-1 my-md-1 pt-md-2 border-top">
    <div class="row">
        <div class="col-12 col-md">
            <img class="mb-2" src="/assets/img/small.png" alt="" width="24" height="24">eo Hub
            <small class="d-inline mb-0 text-muted">&copy; 2018-<?php echo date("Y");?></small>
        </div>
    </div>
</footer>

<script language="javascript">
	var today = new Date();
    $.getScript("/assets/js/landing/_INIT_EVENTS.js?" + today.toDateString()).done(function (script, textStatus) {});
</script>
