<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?><div class="container-full print" style="margin-top:25px;font-size:12px;">
<?php
    $html= ("<h4>".$title."</h4>");
	$html.= "<table class='table table-condensed'>";
	$html.= " <tr>";
	$html.= "   <th>Usuario Intranet</th>";
	$html.= "   <th>Teléfono</th>";
	$html.= "   <th>Status</th>";
	$html.= " </tr>";
    foreach($records as $row){
		$html.= "<tr>";
		$html.= "   <td>".$row["username"]."</td>";
		$html.= "   <td>".$row["telefono"]."</td>";
        if((int)$row["total"]==0){
		   $html.= "   <td><span class='badge badge-success blink_me'>En curso</span></td>";
        } else {
		   $html.= "   <td><span class='badge badge-info'>Finalizada</span></td>";
        }
		$html.= "</tr>";
    }
	$html.= "</table>";
    echo $html;
?>
</div>

