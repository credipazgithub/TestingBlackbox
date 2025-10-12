<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="d-flex" id="wrapper">
	<?php   
	$allow="";
	foreach ($menu as $item){
		foreach ($item["submenu"] as $subitem){
			$btn=("btn-".$subitem["code"]);
			if($btn=="btn-m_mediya_subdiario"){
				$allow=$btn;
				$area=($subitem["data_module"]."-".$subitem["data_model"]."-".$subitem["data_table"]."-".$subitem["data_action"]);
				echo "<a href='#' data-area='".$area."' class='d-none btn-menu-click ".$btn."' data-module='".$subitem["data_module"]."' data-model='".$subitem["data_model"]."' data-table='".$subitem["data_table"]."' data-action='".$subitem["data_action"]."'>".$area."</a>";
				break 2;
			}
		}
	}
	?>

    <div id="page-content-wrapper">
        <div class="d-flex" style="height:50px;border-bottom:2px solid black;">
            <div class="col-12 ml-auto m-auto p-auto" style="max-height:47px;">
                <div class="float-right status-ajax-calls d-none p-0 m-0">
                    <img class="rounded-circle shadow img-user" src="https://intranet.credipaz.com/assets/img/user.jpg" style="height:38px;"/>
                    <span class="text-break font-weight-lighter badge badge-primary mx-0 px-2 raw-username_active d-none d-sm-inline"></span>
                    <a href="./mediya" class="btn btn-raised btn-sm"><i class='material-icons'>logout</i></a>
                </div>
            </div>
        </div>
        <div class="container-fluid dyn-area browser p-0 m-0"></div>
        <div class="container-fluid dyn-area abm d-none"></div>
        <div class="alert-frame" style="position:fixed;bottom:50px;"></div>
    </div>
</div>

<script>
   var _allow="<?php echo $allow;?>";
   if(_allow==""){
      alert("No tiene permisos de acceso a MEDIya");
	  window.location="./mediya";
   } else {
	  $("."+_allow).click();
   }
</script>
