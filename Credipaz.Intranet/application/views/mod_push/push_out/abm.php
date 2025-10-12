<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$new=false;
$edit=false;
if(!isset($parameters["records"]["data"][0])) {
   $edit=true;
   $new=true;
} else {
   $edit=((int)$parameters["records"]["data"][0]["send_message"]==0);   
   $parameters["readonly"]=((int)$parameters["records"]["data"][0]["send_message"]==1);
}
$html=buildHeaderAbmStd($parameters,$title);

$html.="<div class='body-abm border border-light p-0 m-0 rounded shadow-sm' >";
$html.="<div class='row'>";
$html.="<input type='hidden' id='testing' name='testing' class='dbase testing' value='0'/>";
$html.=getInput($parameters,array("col"=>"col-md-12","class"=>"form-control text dbase validate","name"=>"subject","readonly"=>!$edit,"format"=>"text"));
$html.=getInput($parameters,array("col"=>"col-md-12","class"=>"form-control text dbase","name"=>"image_url","readonly"=>!$edit,"format"=>"text"));
$html.=getTextArea($parameters,array("col"=>"col-md-12","class"=>"form-control text dbase validate body","rows"=>"5","name"=>"body","readonly"=>!$edit,"format"=>"text"));
$html.="</div>";
$html.="<div class='row'>";
if ($new or $edit) {
	$html.="<div class='col-6'>";
    $html.=getHtmlResolved($parameters,"controls","id_type_subscription",array("col"=>"col-md-12"));
    $html.=getHtmlResolved($parameters,"controls","group",array("col"=>"col-md-12"));
    $html.=getHtmlResolved($parameters,"controls","id_type_command",array("col"=>"col-md-12"));
    $html.=getHtmlResolved($parameters,"controls","id_type_target",array("col"=>"col-md-12"));
    $html.=getHtmlResolved($parameters,"controls","test_push",array("col"=>"col-md-12"));
	$html.=getInput($parameters,array("col"=>"col-md-12","name"=>"send_message","type"=>"checkbox","class"=>"form-control text dbase"));
	$html.="</div>";
	$html.="<div class='col-6 area-beneficios d-none'>";
	$html.="  <input type='hidden' id='id_beneficio' name='id_beneficio' class='dbase id_beneficio' value='".$parameters["records"]["data"][0]["id_beneficio"]."'/>";
	$html.="  <label for='search_beneficio'>Buscar beneficio a vincular al push</label>";
	$html.="  <input data-target='.beneficios_to_link' id='search_beneficio' name='search_beneficio' type='text' class='form-control search_beneficio' placeholder='".lang('p_search')."' aria-label='".lang('p_search')."' />";
	$html.="  <div class='card beneficios_to_link' style='width:100%;'></div>";
	$html.="  <div class='p-2 card beneficio_detalle' style='width:100%;'></div>";
	$html.="</div>";
}
$html.="</div>";

$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>

<script>
	$("body").off("change", ".id_type_command").on("change", ".id_type_command", function (e) {
	   switch(parseInt($(this).val())){
	      case 2:// click sobre un objeto
		    $(".area-beneficios").removeClass("d-none");
		    break;
		  default:
		    $(".area-beneficios").addClass("d-none");
			$(".beneficios_to_link").html("");
			$(".beneficio_detalle").html("");
			$("#id_beneficio").val(0);
			$("#search_beneficio").val("");
		    break;
	   }
	});
	$("body").off("keyup", ".dni").on("keyup", ".dni", function (e) {
	   if ($(this).val()=="") {
	       $(".btn-test-push").addClass("d-none");
		   $(".btn-abm-accept").removeClass("d-none");
	   } else {
		   $(".btn-abm-accept").addClass("d-none");
	       $(".btn-test-push").removeClass("d-none");
		   $("#send_message").prop("checked",false);
	   }
	});
	$("body").off("keyup", ".search_beneficio").on("keyup", ".search_beneficio", function (e) {
		_FUNCTIONS.onSearchBeneficios($(this));
	});

	$("body").off("click", ".pick-beneficio").on("click", ".pick-beneficio", function () {
	    $(".pick-beneficio").css({"background-color":"white"});
	    $(this).css({"background-color":"rgb(235, 0, 139)"});
		var _rec = JSON.parse(_TOOLS.b64_to_utf8($(this).attr("data-rec")));
		var _html="<h3>"+_rec.description+"</h3>";
		_html+="<p>ID: "+_rec.id + " Code: "+ _rec.code + "</p>";
		_html+="<p>"+_rec.address + "</p>";
		_html+=_rec.legales;
		$("#id_beneficio").val(_rec.id);
		$(".beneficio_detalle").html(_html);
	});

	$(".id_type_command").change();
	$(".search_beneficio").val($("#id_beneficio").val());
	_FUNCTIONS.onSearchBeneficios($(".search_beneficio"));
	setTimeout(function(){$(".pick-beneficio").click();},1000);
</script>
