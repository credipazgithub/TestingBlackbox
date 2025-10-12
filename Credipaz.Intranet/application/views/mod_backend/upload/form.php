<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if (!isset($UsuarioAlta)){$UsuarioAlta="INT";}
if (!isset($prefijo)){$prefijo="";}
?>
<div class="container-full marco p-1">
    <div class="row mx-0 shadow rounded" style="background-color:whitesmoke;">
        <div class="col-12 pt-1 m-0">
            <h1 class="m-0 p-0" style="font-weight:bold;color:rgb(0, 71, 186);"><img src="https://intranet.credipaz.com/assets/img/xlogo.png" style="width:70px;"/>Subir documentación solicitada</h1>
        </div>
    </div>
    <div class="row m-2 p-2">
		<div id='drop_zone' class='drop_zone'><p>Arrastre y suelte archivos a adjuntar en esta zona</p></div>
    </div>
    <div class="row">
		<ul class='ls-images' style='padding:0px;'></ul>
    </div>
    <hr/>
    <div class="row">
        <div class="col-6" style="padding-top:15px;">
            <div id="widget"></div>
        </div>
        <div class="col-6" style="padding-top:15px;">
            <a href="#" class="btnAction btnAccept btn btn-sm btn-success btn-raised pull-right"><?php echo lang('b_accept');?></a>
            <a href="#" class="btnAction btnCancel btn btn-sm btn-danger btn-raised pull-right"><?php echo lang('b_cancel');?></a>
        </div>
    </div>
</div>
<script>
    $.getScript('<?php echo $prefijo?>./application/views/mod_backend/upload/form.js', function() {
    _mode="<?php echo $UsuarioAlta;?>";
    _callback = "<?php echo $callback;?>";
    _referente = "<?php echo $referente;?>";
    _track = "<?php echo $track;?>";
    switch (_mode) {
	    case "WEB":
            $("body").css({
              "background-image":"url('https://intranet.credipaz.com/assets/img/background.png')",
              "padding-left":"25px",
              "padding-right":"25px",
              "padding-bottom":"250px",
              "background-repeat": "auto",
              "background-size":"cover"
            });
            $(".marco").css({"style":"padding-top:0px;"});
		    $.getScript("https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit", function () {
			    $(".btnAccept").addClass("d-none");
		    });
		    break;
	    default:
            $(".marco").removeClass("container-full").addClass("container");
		    $(".btnAccept").removeClass("d-none");
		    break;
    }
});
</script>
