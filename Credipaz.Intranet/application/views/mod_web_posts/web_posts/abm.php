<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm border border-light p-2 rounded shadow-sm' >";
$html.="<form style='width:100%;' autocomplete='off'>";

$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","id_type_post",array("col"=>"col-md-6",));
$html.=getInput($parameters,array("col"=>"col-md-6","name"=>"description","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-3","name"=>"code","type"=>"text","class"=>"form-control text dbase validate"));
$html.="</div>";
$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"rewrite","type"=>"text","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"tags","type"=>"text","class"=>"form-control text dbase validate"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"date_from","type"=>"date","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"date_to","type"=>"date","class"=>"form-control text dbase validate"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"priority","type"=>"number","class"=>"form-control text dbase validate"));
$html.=getHtmlResolved($parameters,"controls","id_parent",array("col"=>"col-md-6",));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"is_menu","type"=>"checkbox","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"is_fullscreen","type"=>"checkbox","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"hide_title","type"=>"checkbox","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("col"=>"col-md-2","name"=>"allow_comments","type"=>"checkbox","class"=>"form-control text dbase"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","id_section",array("col"=>"col-md-6"));
$html.=getHtmlResolved($parameters,"controls","id_group",array("col"=>"col-md-6"));
$html.="</div>";

$html.="<div class='form-row'>";
$html.="  <div class='col-md-8'>";
$html.=getTextArea($parameters,array("rows"=>"5","name"=>"body_post","class"=>"form-control text dbase trumbo"));
$html.=getTextArea($parameters,array("rows"=>"5","name"=>"brief_post","class"=>"form-control text dbase trumbo"));
$html.="  </div>";
$ops= array(
        "forcelabel"=>lang('msg_relationed_files'),
        "size"=>"52",
        "name"=>"image-simple",
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
