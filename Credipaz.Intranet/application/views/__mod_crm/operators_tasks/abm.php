<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$id_type_contact_channel=0;
if (!isset($parameters["records"]["data"][0]["id_type_task_close"])) {$parameters["records"]["data"][0]["id_type_task_close"]="";}
if (!isset($parameters["records"]["data"][0]["id_type_contact_channel"])) {$parameters["records"]["data"][0]["id_type_contact_channel"]="";}
if ($parameters["records"]["data"][0]["id_type_task_close"]!="") {$parameters["readonly"]=true;}
if ($parameters["records"]["data"][0]["id_type_contact_channel"]!="") {$id_type_contact_channel=$parameters["records"]["data"][0]["id_type_contact_channel"];}
$html=buildHeaderAbmStd($parameters,$title);
$html.="<div class='body-abm d-flex border border-light p-2 rounded shadow-sm' >";
$html.="<form style='width:100%;' autocomplete='off'>";
$html.=getInput($parameters,array("nolabel"=>true,"col"=>"col-md-12","name"=>"id","type"=>"hidden","class"=>"form-control text dbase"));
$html.=getInput($parameters,array("nolabel"=>true,"col"=>"col-md-12","name"=>"id_user","type"=>"hidden","class"=>"form-control text dbase"));
$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","subject",array("nolabel"=>true,"col"=>"col-md-12"));
$html.="</div>";
$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","created",array("col"=>"col-md-2"));
$html.=getHtmlResolved($parameters,"controls","from",array("col"=>"col-md-3"));
$html.=getHtmlResolved($parameters,"controls","id_contact_channel",array("col"=>"col-md-3"));
$html.=getHtmlResolved($parameters,"controls","id_operator",array("col"=>"col-md-4"));
$html.="</div>";
$hidden="";
$cols="col-md-6";
switch ((int)$id_type_contact_channel) {
      case 4://Push / Mobile
      case 5://Facebook
         $hidden="d-none";
         $cols="col-md-8";
         break;
      case 6: //messenger
         $hidden="d-none";
         $cols="col-md-8";
         break;
   }
$html.="<div class='form-row py-3 my-2'>";
$html.="   <div class='col-md-3 ".$hidden."'>";
$html.=getHtmlResolved($parameters,"controls","id_tarjeta",array("col"=>"col-md-12"));
$html.=getHtmlResolved($parameters,"controls","id_credito",array("col"=>"col-md-12"));
$html.=getHtmlResolved($parameters,"controls","id_club_redondo",array("col"=>"col-md-12"));
$html.="   </div>";
$html.="   <div class='col-md-3 ".$hidden."'>";
$html.=getHtmlResolved($parameters,"controls","id_myd",array("col"=>"col-md-12"));
$html.=getHtmlResolved($parameters,"controls","id_mil",array("col"=>"col-md-12"));
$html.=getHtmlResolved($parameters,"controls","id_otro",array("col"=>"col-md-12"));
$html.="   </div>";
$html.="   <div class='card ".$cols."'>";
$btn="";
switch((int)$id_type_contact_channel) {
    case 6: //messenger
        $btn="<a data-table='".MOD_CRM."|operators_tasks' data-id='".$record["id"]."' data-code='".$parameters["obj_target"]."' data-token='".$parameters["access_token"]."' class='btn btn-sm btn-primary btn-raised btn-response-messenger'>".lang('b_response')."</a>";
        break;
    default:
        break;
}
$html.=$btn.getHtmlResolved($parameters,"controls","body",array("col"=>"col-md-12"));
$html.="   </div>";
$html.="</div>";

$html.="<div class='form-row'>";
$html.=getHtmlResolved($parameters,"controls","statusBar",array("nolabel"=>true,"col"=>"col-md-12"));
$html.="</div>";

$html.="</form>";
$html.="</div>";
$html.=buildFooterAbmStd($parameters);
echo $html;
?>

<script>
    $('.trumbo').trumbowyg({lang: 'es_ar'});
    var _hidden="<?php echo $hidden;?>";
    if (_hidden!="") {$(".btn-taskCLose").click();}
</script>
