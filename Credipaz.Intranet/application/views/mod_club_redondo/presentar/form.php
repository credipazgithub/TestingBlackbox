<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
if (!isset($UsuarioAlta)){$UsuarioAlta="INT";}
if (!isset($prefijo)){$prefijo="";}
?>

<img class="notweb" src="https://intranet.credipaz.com/assets/credipaz/img/clubRedondo.png" style="width:120px;position:absolute;left:25px;top:15px;"/>
<h1 class="notweb" style="margin:0px;padding:0px;font-size:35px;font-weight:bold;color:white;position:absolute;left:150px;top:40px;">Presentar nuevo socio</h1>

<div class="container marco">
    <div class="row">
        <div class="col-12" style="padding-top:10px;">
            <input type="text" class="boxedfield validatemodal dbasepresentar DocumentoSocio" id="DocumentoSocio" name="DocumentoSocio" placeholder="DNI" />
        </div>
    </div>
    <div class="row">
        <div class="col-12" style="padding-top:10px;">
            <input type="text" class="boxedfield validatemodal dbasepresentar DNI" id="DNI" name="DNI" placeholder="DNI del socio a presentar" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12" style="padding-top:10px;">
			<h5 style="font-size:18px;font-weight:bold;color:rgb(0, 71, 186);">Seleccione Género</h5>
			<table style="width:100%;">
				<tr>
					<td align="center" valign="bottom">
						<span class="pt-2" style="font-size:14px;font-weight:bold;color:rgb(0, 71, 186);">Femenino</span>
						<div class="form-check radio-primary pl-5" style="display:inline;"> 
							<input type="radio" name="sexo" id="sexo" value="F" class="sex-F form-check-input" style="width:24px;height:24px;" />
						</div>
					</td>
					<td align="center" style="width:5%;"></td>
					<td align="center" valign="bottom">
						<span class="pt-2" style="font-size:14px;font-weight:bold;color:rgb(0, 71, 186);">Masculino</span>
						<div class="form-check radio-primary pl-5" style="display:inline;">
							<input type="radio" name="sexo" id="sexo" value="M" class="sex-M form-check-input" style="width:24px;height:24px;" />
						</div>
					</td>
				</tr>
			</table>
        </div>
    </div>
    <hr class="notweb" style="border:solid 2px rgb(0, 71, 186);"/>
    <div class="row">
        <div class="col-md-6 col-sm-12" style="padding-top:15px;">
            <div id="widget"></div>
        </div>
        <div class="col-md-6 col-sm-12" style="padding-top:15px;">
            <a href="#" class="btnAction btnAccept btn btn-md btn-success btn-raised pull-right" style="border-radius:10px;"><?php echo lang('b_accept');?></a>
            <a href="#" class="btnAction btnCancel btn btn-md btn-danger btn-raised pull-right" style="border-radius:10px;"><?php echo lang('b_cancel');?></a>
        </div>
    </div>
</div>
<hr class="notweb" style="border:solid 2px #ff24ff;"/>
<script>
    $.getScript('<?php echo $prefijo?>./application/views/mod_club_redondo/presentar/form.js', function() {
    _mode="<?php echo $UsuarioAlta;?>";
    _callback = "<?php echo $callback;?>";
    _referente = "<?php echo $referente;?>";
    _track = "<?php echo $track;?>";
    switch (_mode) {
	    case "SITE":
                $("body").css({
                  "padding-left":"0px",
                  "padding-right":"0px",
                  "padding-bottom":"0px",
                  "background-repeat": "no-repeat",
                  "background-size":"auto"
                });
            $(".marco").css({"style":"padding-top:0px;"});
		    $(".web").removeClass("d-none");
		    $(".notweb").addClass("d-none");
			$('.boxedfield').addClass('custom-field').removeClass('boxedfield');

		    $.getScript("https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit", function () {
			    $(".btnAccept").addClass("d-none");
		    });
		    break;
	    default:
		    $(".web").addClass("d-none");
		    $(".notweb").removeClass("d-none");
		    $(".btnAccept").removeClass("d-none");
		    break;
    }
});
</script>
