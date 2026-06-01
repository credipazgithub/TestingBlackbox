<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
$Liquidacion="";
$EstadoInicial="";
$Estado="";
if(!isset($parameters["records"]["data"][0])) {$new=true;}
if (!$new){
	$Liquidacion=$parameters["records"]["data"][0]["Liquidacion"];
	$EstadoInicial=$parameters["records"]["data"][0]["EstadoInicial"];
	$Estado=$parameters["records"]["data"][0]["Estado"];
}

$title="Empresa";
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";

$html.=" <div class='col-12'>";
$html.="  <form style='width:100%;' autocomplete='off'>";
$html.="   <div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"Nombre","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"RazonSocial","type"=>"text","class"=>"form-control text dbase validate"));

$html.="      <div class='col-md-2'>";
$html.="         <label>Estado</label><br/>";
$html.="         <select id='Estado' name='Estado' class='form-control Estado dbase validate'>";
$html.="            <option selected value=''>[Seleccione]</option>";
$html.="            <option value='VIG'>Vigente</option>";
$html.="            <option value='ANU'>Anulada</option>";
$html.="            <option value='INH'>Inhabilitada</option>";
$html.="         </select>";
$html.="      </div>";

$html.=getInput($parameters,array("col"=>"col-md-5","name"=>"CUIT","type"=>"number","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-5","name"=>"Telefono","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"Direccion","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"Localidad","type"=>"text","class"=>"form-control text dbase"));
$html.="   </div>";

$html.="   <h5>Comportamiento</h5>";
$html.="   <div class='row'>";

$html.="      <div class='col-md-4'>";
$html.="         <label>Estado inicial del socio</label><br/>";
$html.="         <select id='EstadoInicial' name='EstadoInicial' class='form-control EstadoInicial dbase validate'>";
$html.="            <option selected value=''>[Seleccione]</option>";
$html.="            <option value='PPI'>Pendiente</option>";
$html.="            <option value='VIG'>Vigente</option>";
$html.="         </select>";
$html.="      </div>";

$html.="      <div class='col-md-4'>";
$html.="         <label>Modo de liquidación</label><br/>";
$html.="         <select id='Liquidacion' name='Liquidacion' class='form-control Liquidacion dbase validate'>";
$html.="            <option selected value=''>[Seleccione]</option>";
$html.="            <option value='0'>Recauda Credipaz</option>";
$html.="            <option value='1'>Abona Empresa</option>";
$html.="         </select>";
$html.="      </div>";
$html.=getHtmlResolved($parameters,"controls","IdPlanes",array("col"=>"col-md-4"));
$html.="   <div class='row'>";
$html.=getInput($parameters,array("col"=>"col-md-2","forcelabel"=>"Cuota titular","name"=>"ImporteCuota","type"=>"number","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","forcelabel"=>"Lista 2 titular","name"=>"ImporteCuotaLista2","type"=>"number","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","forcelabel"=>"Adicional mayor","name"=>"AdicMayor1","type"=>"number","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","forcelabel"=>"Lista 2 adicional mayor","name"=>"AdicMayor2","type"=>"number","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","forcelabel"=>"Adicional menor","name"=>"AdicMenor1","type"=>"number","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","forcelabel"=>"Lista 2 adicional menor","name"=>"AdicMenor2","type"=>"number","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"COMprimeraREC","type"=>"number","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"COMREC","type"=>"number","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"DiasVtoFactura","type"=>"number","class"=>"form-control text dbase"));
$html.=getHtmlResolved($parameters,"controls","IdEmpresas",array("col"=>"col-md-6"));


$html.="   </div>";


$html.="  </form>";
$html.="</div>";

$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
   $(".Estado").val("<?php echo $Estado;?>");
   $(".EstadoInicial").val("<?php echo $EstadoInicial;?>");
   $(".Liquidacion").val("<?php echo $Liquidacion;?>");
</script>
