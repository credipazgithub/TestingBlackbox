<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="container-full">
    <iframe id="neoweb_iframe" class="neoweb_iframe" src="" frameborder="0" style="height:500vh;width:100%;" />
</div>
<script>
    var _src = "<?php echo $parameters["table"]; ?>";
    _src =_src.replace("[ID_SUCURSAL]", _AJAX._id_sucursal);
    _src =_src.replace("[SUCURSAL]", _AJAX._sucursal);
    $(".neoweb_iframe").attr("src", _src);

</script>