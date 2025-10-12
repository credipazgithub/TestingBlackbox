<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$validate="";
$acccesoExterno=$parameters["acccesoExterno"];
if (!$acccesoExterno){$validate="validate";}

$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm' >";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-4","name"=>"code","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-8","name"=>"description","type"=>"text","class"=>"form-control text dbase validate"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"date_from","type"=>"date","class"=>"form-control date dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"date_to","type"=>"date","class"=>"form-control date dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getTextArea($parameters,array("col"=>"col-md-12","rows"=>"10","name"=>"sinopsys","class"=>"form-control text dbase trumbo"));
$html.=getTextArea($parameters,array("col"=>"col-md-12","rows"=>"10","name"=>"des_legales","class"=>"form-control text dbase trumbo"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","id_type_beneficio",array("col"=>"col-md-3"));
$html.=getHtmlResolved($parameters,"controls","id_type_category",array("col"=>"col-md-3",));
$html.=getHtmlResolved($parameters,"controls","id_type_execution",array("col"=>"col-md-3",));
$html.="</div>";

$html.="<div class='form-row'>";
$html.="   <div class='col-6'>";
$ops= array(
        "nolabel"=>false,
        "name"=>"image",
        "class"=>"dbase d-none",
        "type"=>"base64",
        "format"=>"jpeg",
        "quality"=>0.5,
        "crop"=>"square",
        "col"=>"col-md-12"
    );
$html.=getImage($parameters,$ops);
$html.="   </div>";
$html.="   <div class='col-6'>";
$ops= array(
        "nolabel"=>false,
        "name"=>"image_apaisada",
        "class"=>"dbase d-none",
        "type"=>"base64",
        "format"=>"jpeg",
        "quality"=>0.5,
        "crop"=>"square",
        "col"=>"col-md-12"
    );
$html.=getImage($parameters,$ops);
$html.="   </div>";
$html.="</div>";

$html.="<div class='form-row no-mil'>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"address","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"location","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row no-mil'>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"city","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"province","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row no-mil'>";
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"lat","custom"=>"step='any'","type"=>"number","class"=>"form-control number dbase lat"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"lng","custom"=>"step='any'","type"=>"number","class"=>"form-control number dbase lng"));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"amount","custom"=>"","type"=>"text","class"=>"form-control text dbase amount"));
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
	$("body").off("change", ".id_type_category").on("change", ".id_type_category", function () {
	   $(".no-mil").removeClass("d-none");
	   if (parseInt($(this).val())==259){$(".no-mil").addClass("d-none");}
	});
	$(".id_type_category").change();
</script>
