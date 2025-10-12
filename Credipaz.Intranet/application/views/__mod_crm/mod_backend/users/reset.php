<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container marco">
    <img src="https://intranet.credipaz.com/assets/credipaz/img/small.png" style="width:200px;padding-top:20px;"/>
    <h2><?php echo $title;?></h2>
    <h2><?php echo $additional;?></h2>
	<input type="hidden" id="email" name="email" class="dbase email" value="<?php echo $additional;?>"/>

	<table>
	   <tr>
	      <td>
		     <label for="password">Nueva clave</label><br/>
			 <input type="password" id="password" name="password" class="dbase password form-control" value=""/>
		  </td>
	   </tr>
	   <tr>
	      <td>
		     <label for="password_confirm">Confirmar nueva clave</label><br/>
			 <input type="password" id="password_confirm" name="password_confirm" class="dbase password_confirm form-control" value=""/>
		  </td>
	   </tr>
	</table>
	<hr/>
	<a href="#" class="btn btn-success btn-raised btnChangePassword">Cambiar clave</a>

</div>
<script>
	$("body").off("click", ".btnChangePassword").on("click", ".btnChangePassword", function () {
		if (!_TOOLS.validate(".dbase", false)) {
			_FUNCTIONS.onShowAlert("Complete los datos requeridos", "Datos faltantes");
			return false;
		}
		var _json = _TOOLS.getFormValues(".dbase", null);
		_json["id_type_user"]=85;//External_operator!
		if (_json.password!=_json.password_confirm) {
			_FUNCTIONS.onShowAlert("La nueva clave y su confirmación, no coinciden", "Datos incorrectos");
			return false;
		}
		_AJAX.UiChangePassword(_json).then(function(data){
			if(data.status=="OK"){
				alert("Se ha efectuado el blanqueo de contraseña");
				window.location = "/"; 
			}
		}).catch(function(err){
			_FUNCTIONS.onShowAlert("No se ha efectuado el blanqueo de contraseña", "Error de datos");
		});
	});

</script>
