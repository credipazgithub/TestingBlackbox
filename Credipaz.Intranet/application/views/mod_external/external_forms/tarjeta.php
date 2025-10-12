<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
$additional=$_GET["p"];
$additional=json_decode(base64_decode($additional), true);

$username=$additional["user"];
$codigo=$additional["codigo"];
$nroDocumento=$additional["nroDocumento"];
$id_sucursal=$additional["sucursal"];

if ($username==""){$username="anonimo";}
if ($codigo==""){$codigo="0";}
if ($id_sucursal==""){$id_sucursal="100";}

if (!isset($auth)){$auth=false;}
$login="";
$hidde="";
$hideHead="";
if($auth){
	$hidde="d-none";
	$login="<div class='container form-login'>";
	$login.="   <div class='row'>";
	$login.="	   <div class='col-sm-9 col-md-7 col-lg-5 mx-auto'>";
	$login.="	      <div class='card card-signin mt-5 mb-2'>";
	$login.="	         <div class='card-body'>";
	$login.=buildLogin('loginAltaTarjetas','.form-login','.form-alta',lang('p_number_doc'));
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
if($codigo==0){
    $hiddeWhenNew="d-none";
    $hiddeInit.=" d-none";
    $showInit="on-init ";
    $htmlVerify="<div class='col-md-2 col-sm-6 mb-2 mt-4 col-verify'><a href='#' class='btn btn-raised btn-info btnVerifyDni'>Verificar</a></div>";
} else {
    $title="Tarjeta - Consulta";
    $edit=true;
}
if (!isset($UsuarioAlta)){$UsuarioAlta="INT";}
if (!isset($pre)){$pre="";}

//para prueba
//$hiddeInit="";

?>

<div class="container-full marco nosite" style="background-color:#2648b6;">
	<h1 class="notweb pl-2" style="color:white;"><?php echo $title;?></h1>
    <span class="pl-2 badge badge-warning info-verify"></span>
</div>
<?php echo $login;?>

<div class="mt-1 form-alta container-full marco <?php echo $hidde;?>">
    <input type="hidden" class="form-control dbaseInit origen" id="origen" name="origen" value="1"/>
    <input type="hidden" class="form-control dbaseInit id_type_request" id="id_type_request" name="id_type_request" value="351"/>

    <input type="hidden" class="form-control dbaseInit dbaseAdherir sCodigo" id="sCodigo" name="sCodigo" value="<?php echo $codigo;?>"/>
    <input type="hidden" class="form-control dbaseInit dbaseAdherir nIDComercializadora" id="nIDComercializadora" name="nIDComercializadora" value="0"/>
    <input type="hidden" class="form-control dbaseInit dbaseAdherir nIDVendedor" id="nIDVendedor" name="nIDVendedor" value="0"/>
    <input type="hidden" class="form-control dbaseInit dbaseAdherir nIDSucursal" id="nIDSucursal" name="nIDSucursal" value="<?php echo $id_sucursal;?>"/>
    <input type="hidden" class="form-control dbaseInit dbaseAdherir username" id="username" name="username" value="<?php echo $username;?>"/>
    <input type="hidden" class="form-control dbaseInit dbaseAdherir Latitud" id="Latitud" name="Latitud" value="0"/>
    <input type="hidden" class="form-control dbaseInit dbaseAdherir Longitud" id="Longitud" name="Longitud" value="0"/>
	<div class="dataAuth" style="position:absolute;right:10px;top:10px;"></div>
    <div class="row no-gutters">
        <div class="col-12 px-2">
            <div class="row">
                <div class="col-3">
                    <h5 class="nosite" style="font-size:20px;font-weight:bold;color:rgb(0, 71, 186);">Datos personales</h5>
                </div>
                <div class="col-9 toolBar"></div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-6 mb-2">
                    <label for="nDoc">DNI</label>
                    <input type="number" maxlength="9" 
                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                       class="form-control validateAdherir number validateInit dbaseInit dbaseAdherir nDoc" id="nDoc" name="nDoc" placeholder="DNI" value="<?php echo $nroDocumento;?>"/>
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label for="sSexo">Sexo</label>
                    <select class="form-control validateAdherir validateInit dbaseInit dbaseAdherir sSexo" id="sSexo" name="sSexo">
                        <option value="" selected>[Sexo]</option>
                        <option value="F">Femenino</option>
                        <option value="M">Masculino</option>
                    </select>
                </div>
                <div class="col-md-8 col-sm-12 mb-2">
                    <label for="sNombre">Apellido y nombre</label>
                    <input type="text" maxlength="30" class="form-control validateInit dbaseInit dbaseAdherir sNombre" id="sNombre" name="sNombre" placeholder="Nombre" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-2">
                    <label for="sEmail">Email</label>
                    <input type="email" maxlength="50" class="form-control validateInit dbaseInit dbaseAdherir sEmail" id="sEmail" name="sEmail" placeholder="Email" />
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <label for="nCUIL">CUIL</label>
                    <input type="number" maxlength="11" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control validateInit dbaseInit dbaseAdherir nCUIL" id="nCUIL" name="nCUIL" placeholder="CUIL (sin guiones ni barras)" />
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label for="sDomiTETelediscado">Área</label>
                    <select id="sDomiTETelediscado" name="sDomiTETelediscado" class="form-control validateInit validateAdherir dbaseInit dbaseAdherir sDomiTETelediscado"></select>
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label for="sDomiTE">Teléfono</label>
                    <input type="number" maxlength="8" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control validateInit validateAdherir dbaseInit dbaseAdherir sDomiTE" id="sDomiTE" name="sDomiTE" placeholder="Teléfono" />
                </div>
                <?php echo $htmlVerify;?>
            </div>

            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="sLKEstadoCivil">Estado civil</label>
                    <select id="sLKEstadoCivil" name="sLKEstadoCivil" class="form-control validateAdherir dbaseAdherir sLKEstadoCivil"></select>
                </div>
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="sLKNacionalidad">Nacionalidad</label>
                    <select id="sLKNacionalidad" name="sLKNacionalidad" class="form-control validateAdherir dbaseAdherir sLKNacionalidad"></select>
                </div>
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="sLKOcupacion">Ocupación</label>
                    <select id="sLKOcupacion" name="sLKOcupacion" class="form-control validateAdherir dbaseAdherir sLKOcupacion"></select>
                </div>
            </div>
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-md-3 col-sm-6 mb-2">
                    <label for="dFechaNac">Nacimiento</label>
                    <input type="date" class="form-control validateAdherir dbaseAdherir dFechaNac" id="dFechaNac" name="dFechaNac" placeholder="Nacimiento" />
                </div>
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="sCBU">CBU</label>
                    <input type="number" maxlength="22" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control dbaseAdherir sCBU relative" id="sCBU" name="sCBU" placeholder="CBU (sin espacios ni guiones)" />
                </div>
            </div>

            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-12">
                    <h5 class="nosite" style="font-size:20px;font-weight:bold;color:rgb(0, 71, 186);">Domicilio 
                       <a href="#" data-lat="0" data-lng="0" class="<?php echo $hiddeWhenNew;?> btn btn-sm btn-info btn-raised btnVerMapa">Ver en mapa</a>
                    </h5>
                </div>
            </div>
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-md-6 col-sm-6 mb-2">
                    <label for="sLKTipoVivienda">Tipo de vivienda</label>
                    <select id="sLKTipoVivienda" name="sLKTipoVivienda" class="form-control validateAdherir dbaseAdherir sLKTipoVivienda"></select>
                </div>
                <div class="col-md-6 col-sm-12 mb-2">
                    <label for="nImporteAlquiler">Importe de alquiler</label>
                    <input type="number"  maxlength="12" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control dbaseAdherir nImporteAlquiler" id="nImporteAlquiler" name="nImporteAlquiler" placeholder="Importe alquiler" />
                </div>
            </div>
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="sDomiCalle">Calle</label>
                    <input type="text" maxlength="50" class="form-control validateAdherir dbaseAdherir sDomiCalle" id="sDomiCalle" name="sDomiCalle" placeholder="Calle" />
                </div>
                <div class="col-md-2 col-sm-4 mb-2">
                    <label for="sDomiNro">Nº</label>
                    <input type="text" maxlength="6" class="form-control validateAdherir dbaseAdherir sDomiNro" id="sDomiNro" name="sDomiNro" placeholder="Nº" />
                </div>
                <div class="col-md-2 col-sm-4 mb-2">
                    <label for="sDomiPisoDpto">Piso</label>
                    <input type="text" maxlength="3" class="form-control dbaseAdherir sDomiPisoDpto" id="sDomiPisoDpto" name="sDomiPisoDpto" placeholder="Piso" />
                </div>
                <div class="col-md-2 col-sm-12 mb-2">
                    <label for="sDomiCP">C.Postal</label>
                    <input type="text" maxlength="8" class="form-control dbaseAdherir sDomiCP" id="sDomiCP" name="sDomiCP" placeholder="Código Postal" />
                </div>
            </div>
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-md-6 col-sm-12 mb-2">
                    <label for="sDomiEntre">Entre calles</label>
                    <input type="text" maxlength="200" class="form-control dbaseAdherir sDomiEntre" id="sDomiEntre" name="sDomiEntre" placeholder="Entre calles" />
                </div>
                <div class="col-md-6 col-sm-12 mb-2">
                    <label for="sDomiBarrio">Barrio</label>
                    <input type="text" maxlength="150" class="form-control validateAdherir dbaseAdherir sDomiBarrio" id="sDomiBarrio" name="sDomiBarrio" placeholder="Barrio" />
                </div>
            </div>
            <div class="row <?php echo $hiddeInit;?>">
                <div class="col-md-6 col-sm-12 mb-2">
                    <label for="sLKDomiLocalidad">Localidad</label>
                    <select id="sLKDomiLocalidad" name="sLKDomiLocalidad" class="form-control validateAdherir dbaseAdherir sLKDomiLocalidad"></select>
                </div>
                <div class="col-md-6 col-sm-12 mb-2">
                    <label for="sDomiPcia">Provincia</label>
                    <select id="sDomiPcia" name="sDomiPcia" class="form-control validateAdherir dbaseAdherir sDomiPcia"></select>
                </div>
            </div>

            <div class="row laboral <?php echo $hiddeInit;?>">
                <div class="col-12">
                    <h5 class="nosite" style="font-size:20px;font-weight:bold;color:rgb(0, 71, 186);">Datos laborales</h5>
                </div>
            </div>
            <div class="row laboral <?php echo $hiddeInit;?>">
                <div class="col-md-6 col-sm-6 mb-2">
                    <label for="sRazonSocial">Razón social</label>
                    <input type="text" maxlength="50" class="valLab form-control dbaseAdherir sRazonSocial" id="sRazonSocial" name="sRazonSocial" placeholder="Razón social" />
                </div>
                <div class="col-md-6 col-sm-6 mb-2">
                    <label for="nCUIT1">CUIT</label>
                    <input type="text" maxlength="50" class="valLab form-control dbaseAdherir nCUIT1" id="nCUIT1" name="nCUIT1" placeholder="CUIT" />
                </div>
            </div>
            <div class="row laboral <?php echo $hiddeInit;?>">
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="sDomiCalle1">Calle</label>
                    <input type="text" maxlength="50" class="form-control dbaseAdherir sDomiCalle1" id="sDomiCalle1" name="sDomiCalle1" placeholder="Calle" />
                </div>
                <div class="col-md-2 col-sm-4 mb-2">
                    <label for="sDomiNro1">Nº</label>
                    <input type="text" maxlength="6" class="form-control dbaseAdherir sDomiNro1" id="sDomiNro1" name="sDomiNro1" placeholder="Nº" />
                </div>
                <div class="col-md-2 col-sm-4 mb-2">
                    <label for="sDomiPisoDpto1">Piso</label>
                    <input type="text" maxlength="3" class="form-control dbaseAdherir sDomiPisoDpto1" id="sDomiPisoDpto1" name="sDomiPisoDpto1" placeholder="Piso" />
                </div>
                <div class="col-md-2 col-sm-12 mb-2">
                    <label for="sDomiCP1">C.Postal</label>
                    <input type="text" maxlength="8" class="form-control dbaseAdherir sDomiCP1" id="sDomiCP1" name="sDomiCP1" placeholder="Código Postal" />
                </div>
            </div>
            <div class="row laboral <?php echo $hiddeInit;?>">
                <div class="col-md-4 col-sm-12 mb-2">
                    <label for="sDomiEntre1">Entre calles</label>
                    <input type="text" maxlength="200" class="form-control dbaseAdherir sDomiEntre1" id="sDomiEntre1" name="sDomiEntre1" placeholder="Entre calles" />
                </div>
                <div class="col-md-4 col-sm-12 mb-2">
                    <label for="sLKDomiLocalidad1">Localidad</label>
                    <select id="sLKDomiLocalidad1" name="sLKDomiLocalidad1" class="form-control dbaseAdherir sLKDomiLocalidad1"></select>
                </div>
                <div class="col-md-4 col-sm-12 mb-2">
                    <label for="sDomiPcia1">Provincia</label>
                    <select id="sDomiPcia1" name="sDomiPcia1" class="form-control dbaseAdherir sDomiPcia1"></select>
                </div>
            </div>
            <div class="row laboral <?php echo $hiddeInit;?>">

                <div class="col-md-2 col-sm-6 mb-2">
                    <label for="sDomiTETelediscado1">Área</label>
                    <select id="sDomiTETelediscado1" name="sDomiTETelediscado1" class="form-control dbaseAdherir sDomiTETelediscado1"></select>
                </div>
                <div class="col-md-6 col-sm-6 mb-2">
                    <label for="sDomiTE1">Teléfono</label>
                    <input type="number" maxlength="8" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control dbaseAdherir sDomiTE1" id="sDomiTE1" name="sDomiTE1" placeholder="Teléfono" />
                </div>
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="sDomiTEInt">Interno</label>
                    <input type="number" maxlength="8" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control dbaseAdherir sDomiTEInt" id="sDomiTEInt" name="sDomiTEInt" placeholder="Interno" />
                </div>
            </div>
            <div class="row laboral <?php echo $hiddeInit;?>">
                <div class="col-md-4 col-sm-12 mb-2">
                    <label for="sCargo">Cargo</label>
                    <input type="text" maxlength="50" class="form-control dbaseAdherir sCargo" id="sCargo" name="sCargo" placeholder="Cargo" />
                </div>
                <div class="col-md-4 col-sm-12 mb-2">
                    <label for="sLegajo">Legajo</label>
                    <input type="text" maxlength="50" class="form-control dbaseAdherir sLegajo" id="sLegajo" name="sLegajo" placeholder="Legajo" />
                </div>
                <div class="col-md-4 col-sm-12 mb-2">
                    <label for="sSeccion">Sección</label>
                    <input type="text" maxlength="50" class="form-control dbaseAdherir sSeccion" id="sSeccion" name="sSeccion" placeholder="Sección" />
                </div>
            </div>
            <div class="row laboral <?php echo $hiddeInit;?>">
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="nIngresoMensual">Ingreso mensual</label>
                    <input type="number" maxlength="10" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="valLab form-control dbaseAdherir nIngresoMensual" id="nIngresoMensual" name="nIngresoMensual" placeholder="Ingreso mensual" />
                </div>
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="dFechaIngreso1">Fecha de ingreso</label>
                    <input type="date" class="valLab form-control dbaseAdherir dFechaIngreso1" id="dFechaIngreso1" name="dFechaIngreso1" placeholder="Fecha de ingreso" />
                </div>
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="sLKRubroLaboral">Rubro</label>
                    <select id="sLKRubroLaboral" name="sLKRubroLaboral" class="valLab form-control dbaseAdherir sLKRubroLaboral"></select>
                </div>
            </div>
            <div class="row laboral <?php echo $hiddeInit;?>">
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="nOtrosIngresos">Otros ingresos</label>
                    <input type="number" maxlength="10" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control dbaseAdherir nOtrosIngresos" id="nOtrosIngresos" name="nOtrosIngresos" placeholder="Otros ingresos" />
                </div>
                <div class="col-md-4 col-sm-6 mb-2">
                    <label for="sAntiguedad">Antigüedad</label>
                    <input type="number" maxlength="2" 
                     oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                     class="form-control dbaseAdherir sAntiguedad" id="sAntiguedad" name="sAntiguedad" placeholder="Antigüedad" />
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
                <div class="col-6 text-right px-4">
                    <a href="#" disabled class="btnAction btnAccept btn btn-md btn-success btn-raised d-none <?php echo $hiddeInit;?>" style="background-color:#2648b6;"><i class='material-icons'>done</i><?php echo lang('b_accept');?></a>
                </div>
                <div class="col-6 text-left px-4">
                    <?php 
                    if (!$auth) {
                       echo "<a href='#' class='btnAction btnCancel btn btn-md btn-danger btn-raised'><i class='material-icons'>close</i>".lang('b_cancel')."</a>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $.getScript('<?php echo $pre?>../application/views/mod_external/external_forms/tarjeta.js', function() {
        _new = (parseInt($(".sCodigo").val()) == 0);
        _auth = "<?php echo $auth;?>";
		$(".btnAccept").removeClass("d-none");
    });
</script>
