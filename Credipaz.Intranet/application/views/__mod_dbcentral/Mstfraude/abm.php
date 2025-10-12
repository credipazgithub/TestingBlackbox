<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
$disabled=" disabled ";
$sSexo=0;
if(!isset($parameters["records"]["data"][0])) {
	$disabled="";
	$new=true;
} else {
	$sSexo=$parameters["records"]["data"][0]["sSexo"];
}
$title="Registro de fraude";
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm'>";

$html.=" <div class='col-12'>";
$html.="  <form style='width:100%;' autocomplete='off'>";
$html.="   <div class='form-row'>";
$html.=getInput($parameters,array("custom"=>$disabled,"col"=>"col-md-5","name"=>"nDoc","type"=>"text","class"=>"form-control text dbase validate"));
$html.="    <div class='col-2'>";
$html.="      <label for='sSexo'>Sexo</label>";
$html.="      <select id='sSexo' ".$disabled." name='sSexo' class='sSexo dbase form-control validate'>";
$html.="         <option selected value='0'>[Seleccione]</option>";
$html.="         <option value='M'>Masculino</option>";
$html.="         <option value='F'>Femenino</option>";
$html.="      </select>";
$html.="    </div>";

$html.=getInput($parameters,array("custom"=>$disabled,"col"=>"col-md-5","name"=>"CUIL","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"sMotivo","type"=>"text","class"=>"form-control text dbase validate"));
$html.="   </div>";
$html.="  </form>";
$html.="</div>";

$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
   $(".sSexo").val("<?php echo $sSexo;?>");
</script>
