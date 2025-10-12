<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-full m-0 p-1">
    <?php 
       switch($platform) {
          case "link-cuotainicial-clubredondo":
             break;
          case "link-cuota-clubredondo":
             break;
          case "pwa-credipaz":
             break;
          case "pwa-clubredondo":
             break;
          case "movil-clubredondo":
             echo "<img src='".INTRANET."/assets/credipaz/img/clubRedondo.png' style='width:75px;'/>";
             break;
          case "movil-credipaz":
             echo "<img src='" . INTRANET . "/assets/credipaz/img/small.png' style='width:150px;'/>";
             break;
       }
    ?>
    <input type="hidden" id="linkDni" name="linkDni" class="linkDni" value="<?php echo $link;?>" />
    <input type="hidden" id="importe" name="importe" class="importe" value=""/>
    <h3 style="text-align:left;margin:0px;padding:0px;font-size:20px;font-weight:bold;color:#dd127b;font-family: 'Poppins-Bold' !important;">
       <?php echo $title;?>
    </h3>        
    <div class="row mt-2">
        <div class="col-12 get-dni text-center d-none">
			<h3 style="text-align:left;margin:0px;padding:0px;font-size:14px;font-weight:bold;color:black;font-family: 'Poppins-Bold' !important;">DNI para acceder a tu cuenta</h3>        
		</div>
        <div class="col-12 get-dni pt-2 d-none">
            <input type="number" style="width:150px;border-radius:2px;padding-left:10px;border:solid 1px silver;background-color:ghostwhite;"class="number dni_tarjeta formData" id="dni" name="dni" placeholder="Nº de DNI" value="<?php echo $dni;?>" />
			<button class="ml-1 btn btn-sm btn-primary btn-raised btn-deuda-fiserv" type="button" style="color:white;background-color:rgb(255, 0, 153);border-radius:12px;padding-top:5px;padding-bottom:5px;padding-left:25px;padding-right:25px;">Confirmar</button>
        </div>
        <div class='div-msg-web col-12 mt-2 mt-3' style='background-color:ghostwhite;display:none;'></div>
        <div class="div-datos form-group col-12 mt-3 d-none">
            <div class="datos-informados" style="padding:5px;background-color:ghostwhite;"></div>
			<div class="data-payment1 p-4 d-none" style="background-color:ghostwhite;">
			   <div class="divForm"></div>
			</div>
        </div>
		<div class="data-payment2 p-0" style="background-color:transparent;">
		</div>
    </div>
</div>

<script>
	var today = new Date();
    $(".data-payment2").html('<iframe id="iframe_fiserv" name="iframe_fiserv" class="iframe_fiserv" src="" frameborder="0" style="height:100vh;width:100%;" />');
	$.getScript(window.location.origin+'/application/views/mod_payments/payments/full.js?' + today.toDateString(), function() {
		$.getScript(window.location.origin+"/assets/js/PAYMENT.js?" + today.toDateString()).done(function (script, textStatus) {
		    _dni = "<?php echo $dni;?>";
		    _gateway = "<?php echo $gateway;?>";
		    _form = "<?php echo $form;?>";
		    if (_dni==""){ 
                $(".get-dni").removeClass("d-none");
            } else {
                _FUNCTIONS.onLoadPaymentData(250,_form,_gateway);
            }
		});
	});
</script>

<script type="text/javascript">
    window.addEventListener("message", receiveMessage, false);
    function receiveMessage(event) {
        if (event.origin != "https://test.ipg-online.com"){return;}
        var elementArr = event.data.elementArr;
        forwardForm(event.data, elementArr);
    }
    function forwardForm(responseObj, elementArr) {
        var newForm = document.createElement("form");
        newForm.setAttribute('method',"post");
        newForm.setAttribute('action',responseObj.redirectURL);
        newForm.setAttribute('id',"newForm");
        newForm.setAttribute('name',"newForm");
        document.body.appendChild(newForm);
        for(var i = 0 ; i < elementArr.length; i++) {
            var element = elementArr[i];
            var input = document.createElement("input");
            input.setAttribute("type", "text");
            input.setAttribute("name", element.name);
            input.setAttribute("value", element.value);
            document.newForm.appendChild(input);
        }
		alert("message received!");
    }
</script>
