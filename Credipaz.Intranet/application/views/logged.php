<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
<div class="d-flex" id="wrapper">
    <div class="shadow sidebar-wrapper" id="sidebar-wrapper">
        <div class="m-0" id="data-menu-close" style="height:48px;width:100%;background-color:white;">
            <span class="btn-toggle-menu btn btn-menu-close btn-sm float-left" style="background-color:white;">
			   <i class="material-icons mt-1" style="background-color:white;">arrow_back_ios</i>
			</span>
			<img src=<?php echo INTRANET."/assets/credipaz/img/small.png";?> style="width:10rem;padding-top:8px;">
            <span class="mx-0 px-1 waiter wait-ajax"></span>
        </div>
        <div class="p-1">
            <input id="xSearchDNI" name="xSearchDNI" class="form-control-inline xSearchDNI number" type="number" value="" placeholder="Buscar DNI..." title="Buscar datos del cliente, dado el DNI"/>
            <a href="#" class="btn btn-sm btn-primary btn-xSearchDNI"><i class="material-icons">search</i></a>
        </div>
        <div id="accordion" role="tablist" class="pt-0 mt-0 list-group side-menu">
            <?php   
            $html="";
            foreach ($menu as $item){
                $id=$item["id"];
                $ops=array("value"=>$item["running"],"dyncolor"=>true);
                $running=getProgressBar(null,$ops);
                $html.="<div class='p-0 m-0' role='tab' id='heading-".$id."' style='position:relative;'>";
                $html.="   <a class='list-group-item bg-secondary' data-toggle='collapse' href='#menu-".$id."' aria-expanded='true' aria-controls='menu-".$id."' style='color:whitesmoke;'>";
                $html.="      <table style='width:100%;'>";
                $html.="         <tr>";
                $html.="            <td valign='middle' style='width:30px;'><i class='material-icons'>".$item["icon"]."</i></td>";
                $html.="            <td valign='middle'>".ucfirst(lang($item["code"]))."</td>";
                $html.="         </tr>";
                $html.="         <tr>";
                $html.="            <td colspan='2' align='right' valign='middle'>".$running."</td>";
                $html.="         </tr>";
                $html.="      </table>";
                $html.="   </a>";
                if ($item["show_brief"]==1 && $item["brief"]!="") {$html.=getHelpButton($item,array("title"=>"code","body"=>"brief"));}
                $html.="</div>";
                $html.="<div id='menu-".$id."' class='collapse' role='tabpanel' aria-labelledby='heading-".$id."' data-parent='#accordion'>";
                foreach ($item["submenu"] as $subitem){
				    $area=($subitem["data_module"]."-".$subitem["data_model"]."-".$subitem["data_table"]."-".$subitem["data_action"]);
                    $area = str_replace('[INTERFACES]', INTERFACES, $area);
                    $data_table = str_replace('[INTERFACES]', INTERFACES, $subitem["data_table"]);
                    $ops=array("value"=>$subitem["running"],"dyncolor"=>true);
                    $running=getProgressBar(null,$ops);
                    $html.="  <div style='position:relative;'>";
                    $html.="    <a href='#' data-area='".$area."' class='list-group-item bg-light btn-menu-click btn-".$subitem["code"]."' data-alert='".$subitem["alert_build"]."' data-module='".$subitem["data_module"]."' data-model='".$subitem["data_model"]."' data-table='".$data_table."' data-action='".$subitem["data_action"]."'>";
                    $html.="      <table style='width:100%;'>";
                    $html.="         <tr>";
                    $html.="            <td valign='middle' style='width:30px;'><i class='material-icons'>".$subitem["icon"]."</i></td>";
                    $html.="            <td valign='middle' class='label-menu'>".ucfirst(lang($subitem["code"]))."</td>";
                    $html.="         </tr>";
                    $html.="         <tr>";
                    if($subitem["alert_build"]==1) {
                        $html.="<td colspan='2'><span class='badge badge-danger'><i class='material-icons' style='font-size:14px;'>build</i> ".lang('msg_not_use')."</span></td>";
                    }else{
                        $html.="<td colspan='2' valign='middle'>".$running."</td>";
                    }
                    $html.="         </tr>";
                    $html.="      </table>";
                    $html.="    </a>";
                    if ($subitem["show_brief"]==1 && $subitem["brief"]!="") {$html.=getHelpButton($subitem,array("title"=>"code","body"=>"brief"));}
                    $html.="  </div>";
                }
                $html.="</div>";
            }
            echo $html;
            ?>
        </div>
    </div>

    <div id="page-content-wrapper">
        <div class="d-flex" style="height:50px;border-bottom:2px solid black;">
            <div class="info-heading col-1 m-0 p-0 bg-white d-none" style="max-height:47px;">
                <div class="p-1">
                    <span class="btn-toggle-menu btn btn-menu-open btn-sm pb-0 mb-0"><i class="material-icons">menu</i></span>
                    <span class="mx-0 px-1 waiter wait-ajax"></span>
                </div>
            </div>
            <div class="col-12 ml-auto m-auto p-auto bg-white" style="max-height:47px;">
                <div class='float-left p-0 m-0 mt-2' style='font-size:12px;'>
                    <span class='text-monospace text-break font-weight-lighter badge badge-light mx-0 px-1 elapsed-time d-none d-sm-inline'></span>
                    <span class='text-monospace text-break font-weight-lighter badge badge-info mx-0 px-1 execution-mode d-sm-inline'><?php echo strtoupper(ENVIRONMENT);?></span>
                    <span class='text-monospace text-break font-weight-lighter badge badge-success mx-0 px-1 status-last-call d-none d-sm-inline'></span>
                    <span class='text-monospace text-break font-weight-lighter badge badge-danger mx-0 px-1 status-message d-none'></span>
                </div>
                <div class="float-right status-ajax-calls d-none p-0 m-0">
                    <a href='#' data-action='pause' class='btn-doctor-atencion doctor-on btn btn-sm btn-danger btn-raised m-0 p-1 d-none'><?php echo lang('msg_doctor_on');?></a>
                    <a href='#' data-action='active' class='btn-doctor-atencion doctor-off btn btn-sm btn-success btn-raised m-0 p-1 d-none'><?php echo lang('msg_doctor_off');?></a>
                    <span id='barTelemedicina' class='d-none barTelemedicina m-0 p-1' style='opacity:1;padding:0px;margin:0px;'></span>
                    <input id='pacientesTelemedicina' name='pacientesTelemedicina' class='pacientesTelemedicina' type='hidden' value='0' />
                    <input id='myStatusTelemedicina' name='myStatusTelemedicina' class='myStatusTelemedicina' type='hidden' value='0' />
                    <audio autoplay id='ringerAlertas' class='d-none'><source src='' type='audio/mpeg'></audio>
                    <a href="#" class="btn btn-sm btn-dark mx-0 p-2 btn-config"><i class='material-icons'>manage_accounts</i></a>
                    <a href="#" class="btn btn-sm btn-dark mx-0 p-2 btn-silence"><i class='material-icons icon-silence'>volume_up</i></a>
                    <a class="btn btn-sm btn-dark text-break mx-0 p-2 raw-messages_alert_NO d-none" title="<?php echo lang('msg_notreaded');?>"></a>
                    <a href="#" class="btn btn-sm btn-success text-break mx-0 p-2 raw-folder_alert d-none btn-menu-click btn-m_folder_items" data-module="mod_folders" data-model="folders_userview" data-table="folders" data-action="brow" data-page="1" title="<?php echo lang('msg_notviewed');?>"></a>
                    <img class="rounded-circle shadow img-master" src="https://intranet.credipaz.com/assets/img/user.jpg" style="height:38px;"/>
                    <span class="text-break font-weight-lighter badge badge-primary mx-0 px-2 raw-master_account d-none d-sm-inline"></span>
                    <img class="rounded-circle shadow img-user" src="https://intranet.credipaz.com/assets/img/user.jpg" style="height:38px;"/>
                    <span class="text-break font-weight-lighter badge badge-primary mx-0 px-2 raw-username_active d-none d-sm-inline"></span>
                    <a href="#" class="btn btn-dark btn-sm btn-logout"><i class='material-icons'>logout</i></a>
                </div>
            </div>
        </div>
        <div class="container-fluid dyn-area browser p-0 m-0"></div>
        <div class="container-fluid dyn-area abm d-none"></div>
        <div class="alert-frame" style="position:fixed;bottom:50px;"></div>
    </div>
</div>
<script>
    $(".btn-m_home").click();
    switch (parseInt(_AJAX._id_type_user_active)) {
        case 77:
        case 78:
            break;
        default:
            $(".xSearchDNI").remove();
            $(".btn-xSearchDNI").remove();
            $(".btn-config").remove();
            $(".img-user").remove();
            break;
    };
</script>
