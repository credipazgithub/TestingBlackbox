<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$col="col-4";
//if ($auditor) {$col="col-6";}
?>
<div class="container-full marco">
    <h2 style="margin:0px;padding:0px;font-size:60px;font-weight:bold;color:rgb(0, 71, 186);"><?php echo $title;?></h2>
        <?php 
           $html="<div class='row'>";
           $html.="  <div class='".$col." card m-0'>";
           $html.="    <h3>En espera</h3>";
           $html.="  </div>";
           $html.="  <div class='".$col." card m-0'>";
           $html.="    <h3>Siendo atendidos</h3>";
           $html.="  </div>";

           //if (!$auditor) {
               $html.="  <div class='".$col." card m-0'>";
               $html.="    <h3>Últimas atenciones</h3>";
               $html.="  </div>";
           //}
           $html.="</div>";
           echo $html;

           $html="<div class='row'>";
           $html.="<div class='".$col." card m-0'>";
           $html.="<table class='table table-condensed'>";
           foreach ($espera["data"] as $record){
              $badge="badge-success";
              if ((int)$record["seconds"]>300) {$badge="badge-success";}
              if ((int)$record["seconds"]>600) {$badge="badge-warning";}
              if ((int)$record["seconds"]>900) {$badge="badge-danger";}
              $html.="<tr>";
              $html.="   <td><span class='badge badge-info' style='font-size:12px;'>".$record["name_club_redondo"]."</span></td>";
              $html.="   <td><span class='badge ".$badge."' style='font-size:12px;'>".$record["elapsed"]."</span></td>";
              //if (!$auditor) {
                 $html.="   <td>".$record["especialidad"]."</td>";
              //} else {
              //   $html.="   <td><a href='#' clasS='btn btn-raised btn-sm btn-danger btn-cancel-telemedicina' data-id='".$record["id"]."' data-table='charges_codes'>Cancelar #".$record["id"]."</a></td>";
              //}
              $html.="</tr>";
           }
           $html.="</table>";
           $html.="</div>";
           echo $html;

           $html="<div class='".$col." card m-0'>";
           $html.="<table class='table table-condensed'>";
           foreach ($encurso["data"] as $record){
              $badge="badge-success";
              if ((int)$record["seconds"]>300) {$badge="badge-success";}
              if ((int)$record["seconds"]>600) {$badge="badge-info";}
              if ((int)$record["seconds"]>900) {$badge="badge-warning";}
              $html.="<tr>";
              $audited="<td><span class='material-icons' style='color:green;'>visibility</span></td>";
              if ($record["last_date_audit"]=="") {$audited="<td><span class='material-icons' style='color:red;'>visibility_off</span></td>";}
              $html.=$audited;
              $html.="   <td><span class='badge badge-info' style='font-size:12px;'>".$record["name_club_redondo"]."</span></td>";
              $html.="   <td><span class='badge badge-secondary' style='font-size:12px;'>".$record["doctor"]."</span></td>";
              if (!$auditor) {
                 $html.="   <td><span class='badge ".$badge."' style='font-size:12px;'>".$record["elapsed"]."</span></td>";
              } else {
                 $html.="   <td><a href='#' clasS='btn btn-raised btn-sm btn-danger btn-cancel-telemedicina' data-id='".$record["id"]."' data-table='operators_tasks'>Cancelar #".$record["id"]."</a></td>";
              }
              $html.="</tr>";
           }
           $html.="</table>";
           $html.="</div>";
           echo $html;
           //if (!$auditor) {
               $html="<div class='".$col." card m-0'>";
               $html.="<table class='table table-condensed'>";
               foreach ($finalizadas["data"] as $record){
                  $badge="badge-success";
                  if ((int)$record["seconds"]>300) {$badge="badge-success";}
                  if ((int)$record["seconds"]>600) {$badge="badge-warning";}
                  if ((int)$record["seconds"]>900) {$badge="badge-danger";}
                  $html.="<tr>";
                  $html.="   <td><span class='badge badge-info' style='font-size:12px;'>".$record["name_club_redondo"]."</span></td>";
                  $html.="   <td><span class='badge badge-secondary' style='font-size:12px;'>".$record["doctor"]."</span></td>";
                  $html.="   <td><span class='badge ".$badge."' style='font-size:12px;'>".$record["elapsed"]."</span></td>";
                  $html.="</tr>";
               }
               $html.="</table>";
               $html.="</div>";

               echo $html;
           //}
           echo "</div>";
        ?>
</div>
<script>
    $.getScript('<?php echo $prefijo?>./application/views/mod_telemedicina/audit/form.js', function() {

    });
</script>
