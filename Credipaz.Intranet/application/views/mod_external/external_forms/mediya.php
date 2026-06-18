<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
$additional=$_GET["p"];
$additional=json_decode(base64_decode($additional), true);

$username=$additional["user"];
$id=$additional["id"];
$id_sucursal=$additional["sucursal"];

if ($username==""){
   $username="anonimo";
   //if($pUser!=null) {$username=$pUser;}
}
if ($id==""){$id="0";}
if ($id_sucursal==""){
   $id_sucursal="100";
   //if(pSucursal!=null) {$id_sucursal=$pSucursal;}d
}

if (!isset($auth)){$auth=false;}
$login="";
$hidde="";
$hideHead="";
if($auth){
	$hidde="d-none";
	$login="<div class='container form-login'>";
	$login.="   <div class='row'>";
	$login.="	   <div class='col-sm-9 col-md-7 col-lg-5 mx-auto'>";
	$login.="	      <div class='card card-signin my-5'>";
	$login.="	         <div class='card-body'>";
	$login.=buildLogin('loginAltaClubRedondo','.form-login','.form-alta',lang('p_number_doc'));
	$login.="	         </div>";
	$login.="	      </div>";
	$login.="	   </div>";
	$login.="	</div>";
	$login.="</div>";
} else {
    $hideHead="d-none";
}

$hiddeWhenNew="";
$hiddeInit="off-init ";
$showInit="on-init d-none";
$htmlVerify="";
$edit=false;
if($id==0){
    $hiddeWhenNew="d-none";
    $hiddeInit.=" d-none";
    $showInit="on-init ";
    $htmlVerify="<div class='col-md-2 col-sm-6 mb-2 mt-4 col-verify'><a href='#' class='btn btn-raised btn-info btnVerifyDni'>Verificar</a></div>";
} else {
    $title="Mediya - Consulta";
    $edit=true;
}
if (!isset($UsuarioAlta)){$UsuarioAlta="INT";}
if (!isset($pre)){$pre="";}
?>

<div class="container-full marco nosite" style="background-color:#2648b6;">
	<h1 class="notweb pl-2" style="color:white;"><?php echo $title;?></h1>
    <span class="pl-2 badge badge-warning info-verify"></span>
</div>
<?php echo $login;?>

<div class="mt-1 form-alta container-full marco <?php echo $hidde;?>">
    <input type="hidden" class="form-control dbaseAdherir Id" id="Id" name="Id" value="<?php echo $id;?>"/>
    <input type="hidden" class="form-control TarjetaCPHabilitada" id="TarjetaCPHabilitada" name="TarjetaCPHabilitada" value="0"/>
    <input type="hidden" class="form-control TarjetaCP" id="TarjetaCP" name="TarjetaCP" value=""/>
    <input type="hidden" class="form-control dbaseAdherir IDEmpresa" id="IDEmpresa" name="IDEmpresa" value="0"/>
    <input type="hidden" class="form-control dbaseAdherir IDVendedor" id="IDVendedor" name="IDVendedor" value="0"/>
    <input type="hidden" class="form-control dbaseAdherir IDSucursal" id="IDSucursal" name="IDSucursal" value="<?php echo $id_sucursal;?>"/>
    <input type="hidden" class="form-control dbaseAdherir username" id="username" name="username" value="<?php echo $username;?>"/>
    <input type="hidden" class="form-control dbaseAdherir Latitud" id="Latitud" name="Latitud" value="0"/>
    <input type="hidden" class="form-control dbaseAdherir Longitud" id="Longitud" name="Longitud" value="0"/>
	<div class="dataAuth" style="position:absolute;right:10px;top:10px;"></div>
    <div class="row no-gutters">
        <div class="col-12 px-2">
            <div class="row">
                <div class="col-6">
                    <h5 class="nosite" style="font-size:20px;font-weight:bold;color:rgb(0, 71, 186);">Datos personales 
                       <a href="#" data-titular="S" data-dni="" data-sexo="" data-id_socio="<?php echo $id;?>" class="<?php echo $hiddeWhenNew;?> btn btn-sm btn-info btn-raised btnVerCredenciales">Ver credenciales</a>
                       <a href="#" data-id_socio="<?php echo $id; ?>" class="<?php echo $hiddeWhenNew; ?> btn btn-sm btn-secondary btn-raised btnVerHistorialDePagos">Ver historial de pagos</a>                    
                    </h5>
                </div>
                <div class="col-6 toolBar"></div>
                <div class="col-2 col-md-4 col-sm-6 mb-2">
                    <label for="NroDocumento">DNI</label>
                    <input type="number" maxlength="9" 
                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                       class="form-control validateAdherir number validateInit dbaseAdherir NroDocumento" id="NroDocumento" name="NroDocumento" placeholder="DNI" />
                </div>
                <div class="col-2 col-md-4 col-sm-6 mb-2">
                    <label for="Sexo">Sexo</label>
                    <select class="form-control validateAdherir validateInit dbaseAdherir Sexo" id="Sexo" name="Sexo">
                        <option value="" selected>[Sexo]</option>
                        <option value="F">Femenino</option>
                        <option value="M">Masculino</option>
                    </select>
                </div>
                <?php echo $htmlVerify;?>
                <div class="col-4 col-sm-6 mb-2 <?php echo $hiddeInit;?>">
                    <label for="Nombre">Nombre</label>
                    <input type="text" maxlength="30" class="form-control validateAdherir dbaseAdherir Nombre" id="Nombre" name="Nombre" placeholder="Nombre" />
                </div>
                <div class="col-4 col-sm-6 mb-2 <?php echo $hiddeInit;?>">
                    <label for="Apellido">Apellido</label>
                    <input type="text" maxlength="30" class="form-control validateAdherir dbaseAdherir Apellido" id="Apellido" name="Apellido" placeholder="Apellido" />
                </div>
            </div>
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-3 col-md-4 col-sm-6 mb-2">
                    <label for="IdEstadoCivil">Estado civil</label>
                    <select id="IdEstadoCivil" name="IdEstadoCivil" class="form-control validateAdherir dbaseAdherir IdEstadoCivil"></select>
                </div>
                <div class="col-3 col-md-4 col-sm-6 mb-2">
                    <label for="IdNacionalidad">Nacionalidad</label>
                    <select id="IdNacionalidad" name="IdNacionalidad" class="form-control validateAdherir dbaseAdherir IdNacionalidad"></select>
                </div>
                <div class="col-3 col-md-4 col-sm-6 mb-2">
                    <label for="IdOcupacion">Ocupación</label>
                    <select id="IdOcupacion" name="IdOcupacion" class="form-control validateAdherir dbaseAdherir IdOcupacion"></select>
                </div>
                <div class="col-3 col-md-4 col-sm-6 mb-2">
                    <label for="CUIL">CUIL</label>
                    <input type="number" maxlength="11" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control dbaseAdherir CUIL" id="CUIL" name="CUIL" placeholder="CUIL (sin guiones ni barras)" />
                </div>
            </div>
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-2 mb-2">
                    <label for="FechaNacimiento">Nacimiento</label>
                    <input type="date" class="form-control date validateAdherir dbaseAdherir FechaNacimiento" id="FechaNacimiento" name="FechaNacimiento" placeholder="Nacimiento" />
                </div>
                <div class="col-2 mb-2">
                    <label for="AreaTelefono">Área</label>
                    <select id="AreaTelefonoSocio" name="AreaTelefonoSocio" class="form-control validateAdherir dbaseAdherir AreaTelefonoSocio"></select>
                </div>
                <div class="col-4 mb-2">
                    <label for="Telefono">Teléfono (no poner 15 en celulares)</label>
                    <input type="number" maxlength="8" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control validateAdherir dbaseAdherir TelefonoSocio" id="TelefonoSocio" name="TelefonoSocio" placeholder="Teléfono" />
                </div>
                <div class="col-4 mb-2">
                    <label for="Email">Email</label>
                    <input type="email" maxlength="50" class="form-control dbaseAdherir Email" id="Email" name="Email" placeholder="Email" />
                </div>
            </div>
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-12">
                    <h5 class="nosite" style="font-size:20px;font-weight:bold;color:rgb(0, 71, 186);">Domicilio 
                       <a href="#" data-lat="0" data-lng="0" class="<?php echo $hiddeWhenNew;?> btn btn-sm btn-info btn-raised btnVerMapa">Ver en mapa</a>
                    </h5>
                </div>
                <div class="col-4 col-5 col-sm-6 mb-2">
                    <label for="Calle">Calle</label>
                    <input type="text" maxlength="50" class="form-control validateAdherir dbaseAdherir Calle" id="Calle" name="Calle" placeholder="Calle" />
                </div>
                <div class="col-2 col-md-3 col-sm-4 mb-2">
                    <label for="Numeracion">Nº</label>
                    <input type="text" maxlength="6" class="form-control validateAdherir dbaseAdherir Numeracion" id="Numeracion" name="Numeracion" placeholder="Nº" />
                </div>
                <div class="col-2 col-md-3 col-sm-4 mb-2">
                    <label for="Piso">Piso</label>
                    <input type="text" maxlength="3" class="form-control dbaseAdherir Piso" id="Piso" name="Piso" placeholder="Piso" />
                </div>
                <div class="col-2 col-md-3 col-sm-4 mb-2">
                    <label for="DptoOficLoc">Dpto.</label>
                    <input type="text" maxlength="4" class="form-control dbaseAdherir DptoOficLoc" id="DptoOficLoc" name="DptoOficLoc" placeholder="Dpto.,Of.,Loc." />
                </div>
                <div class="col-2 col-md-3 col-sm-4 mb-2">
                    <label for="Torre">Torre</label>
                    <input type="text" maxlength="3" class="form-control dbaseAdherir Torre" id="Torre" name="Torre" placeholder="Torre" />
                </div>
            </div>
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-2 col-md-3 col-sm-12 mb-2">
                    <label for="CodigoPostal">C.Postal</label>
                    <input type="text" maxlength="8" class="form-control dbaseAdherir CodigoPostal" id="CodigoPostal" name="CodigoPostal" placeholder="Código Postal" />
                </div>
                <div class="col-4 col-md-5 col-sm-12 mb-2">
                    <label for="Localidad">Localidad</label>
                    <input type="text" maxlength="50" class="form-control validateAdherir dbaseAdherir Localidad" id="Localidad" name="Localidad" placeholder="Localidad" />
                </div>
                <div class="col-4 col-md-5 col-sm-12 mb-2">
                    <label for="Provincia">Provincia</label>
                    <input type="text" maxlength="50" class="form-control validateAdherir dbaseAdherir Provincia" id="Provincia" name="Provincia" placeholder="Provincia" />
                </div>
            </div>

            <?php
               //Controla si se activa o no el metodo de pago en modo edicion
               $disabled="";
               if($edit) {$disabled="disabled";}
            ?>
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-8 mb-2 <?php echo $formaspago;?>">
                    <h5 class="nosite" style="font-size:20px;font-weight:bold;color:rgb(0, 71, 186);">Seleccione forma de pago</h5>
                    <select class='form-control validateAdherir dbaseAdherir IdModoPago noValidateEdit' id='IdModoPago' name='IdModoPago'  <?php echo $disabled;?>></select>
                </div>
                <div class="dataEmpresa col-4 mb-2 <?php echo $formaspago;?>">
                </div>
            </div>


            <div class="row <?php echo $hiddeInit;?>" style="margin-top:15px;">
                <div class="form-group DAC d-none adds col-12 mb-2">
                    <label for="CBU">CBU</label>
                    <input type="number" maxlength="22" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control dbaseAdherir CBU relative noValidateEdit" id="CBU" name="CBU" placeholder="CBU (sin espacios ni guiones)" />
                </div>
                <div class="form-group DAT d-none adds col-12 mb-2">
                    <label for="Marca">Tarjeta</label>
                    <select class="form-control dbaseAdherir Marca relative noValidateEdit" id="Marca" name="Marca"></select>
                </div>
                <div class="form-group DAT d-none adds col-12 mb-2">
                    <label for="PAN">Nº de tarjeta</label>
                    <input type="number" maxlength="16" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control dbaseAdherir PAN relative noValidateEdit" id="PAN" name="PAN" placeholder="Nº tarjeta" />
                </div>
                <div class="form-group DAT d-none adds col-12 mb-2">
                    <label for="NombreTarjeta">Nombre en la tarjeta</label>
                    <input type="text" maxlength="50" class="form-control dbaseAdherir NombreTarjeta relative" id="NombreTarjeta" name="NombreTarjeta" placeholder="Nombre en la tarjeta" />
                </div>
                <div class="form-group DAT d-none adds col-12 mb-2">
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <label for="MesVTO">Mes</label>
                                    <select class="form-control dbaseAdherir MesVTO relative noValidateEdit" id="MesVTO" name="MesVTO">
                                        <option value="" selected>[Mes]</option>
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                </td>
                                <td>
                                    <label for="AnioVTO">Año</label>
                                    <select class="form-control dbaseAdherir AnioVTO relative noValidateEdit" id="AnioVTO" name="AnioVTO"></select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php 
               if($edit) {
                  $html="";   
                  $html.="<div class='row'>";
                  $html.="   <div class='col-12'>";
                  $html.="      <h5 class='nosite' style='font-size:20px;font-weight:bold;color:rgb(0, 71, 186);'>Adicionales</h5>";
                  $html.="   </div>";
                  $html.="   <div class='col-12 adicionales'></div>";
                  $html.="</div>";
               }
               echo $html;
            ?>
            <hr class="notweb" />
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-4 text-left px-4 areaPay d-none">
                    <a href="#" disabled class="btnPayEdit btn btn-md btn-info btn-raised" target="_blank"><i class='material-icons'>paid</i>Pagar</a>
                </div>

                <div class="col-4 text-right px-4">
                    <a href="#" disabled class="btnAction btnAccept btn btn-md btn-success btn-raised d-none <?php echo $hiddeInit;?>" style="background-color:#2648b6;"><i class='material-icons'>done</i><?php echo lang('b_accept');?></a>
                </div>
                <div class="col-4 text-left px-4">
                    <?php 
                    if (!$auth) {
                       echo "<a href='#' class='btnAction btnCancel btn btn-md btn-danger btn-raised'><i class='material-icons'>close</i>".lang('b_cancel')."</a>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <!--
        <div class="col-6 pl-2">
            <iframe class="iPago" src="https://intranet.credipaz.com/assets/img/vacio.gif" style="width:100%;height:100vh;border:solid 0px white;"></iframe>
        </div>
        -->
    </div>
</div>

<script>
    $.getScript('<?php echo $pre?>../application/views/mod_external/external_forms/mediya.js', function() {
        var currentTime = new Date();
        var year = currentTime.getFullYear();
        var yearTop = (year + 19);
        for (i = year; i < yearTop; i++) {$(".AnioVTO").append("<option value='" + (i - 2000) + "'>" + (i - 2000) + "</option>");}
        $(".AnioVTO").append("<option value='' selected>[Año]</option>");
        _new = (parseInt($(".Id").val()) == 0);
        _auth = "<?php echo $auth;?>";
		$(".btnAccept").removeClass("d-none");
        if(!_new){
           $(".noValidateEdit").removeClass("validateAdherir");
        }
    });
</script>
