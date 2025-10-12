<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm' >";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"code","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-10","name"=>"description","type"=>"text","class"=>"form-control text dbase validate"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"scheduled","type"=>"date","class"=>"form-control date dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"validto","type"=>"date","class"=>"form-control date dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"keywords_positive","type"=>"text","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"keywords_negative","type"=>"text","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","id_contact_channel",array("col"=>"col-md-4"));
$html.=getHtmlResolved($parameters,"controls","id_type_thread",array("col"=>"col-md-4",));
$html.=getHtmlResolved($parameters,"controls","id_thread_condition",array("col"=>"col-md-4",));
$html.="</div>";

$html.="<div class='form-row'>";
$html.="  <div class='col-md-8'>";
$html.=getTextArea($parameters,array("col"=>"col-md-12","name"=>"body","class"=>"form-control text dbase trumbo"));
$html.=getTextArea($parameters,array("col"=>"col-md-12","name"=>"message","class"=>"form-control text dbase"));
$html.=getTextArea($parameters,array("col"=>"col-md-12","name"=>"short_message","class"=>"form-control text dbase"));
$html.="  </div>";
$ops= array(
        "forcelabel"=>lang('msg_relationed_files'),
        "size"=>"52",
        "name"=>"image",
        "class"=>"dbase d-none",
        "type"=>"base64",
        "format"=>"jpeg",
        "quality"=>0.5,
        "crop"=>"square",
        "multi"=>true,
        "col"=>"col-md-4",
    );
$html.=getImage($parameters,$ops,$attached_files["data"]);
$html.="</div>";


$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>
<script>
    $('.trumbo').trumbowyg({lang: 'es_ar'});
    $('.multiselect').selectpicker();
</script>

