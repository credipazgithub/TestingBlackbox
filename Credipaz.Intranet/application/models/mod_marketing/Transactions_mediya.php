<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Transactions_mediya extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function form($values){
        try {
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".$values["model"]));
            $html=$this->load->view(MOD_MARKETING."/transactions_mediya/form",$data,true);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function infoIndicadores($values){
        try {
            $rango=date(FORMAT_DATE_DMY, strtotime($values["fecha_desde"]))." al ".date(FORMAT_DATE_DMY, strtotime($values["fecha_hasta"]));
            $fecha_desde=($values["fecha_desde"]." 00:00:00.000");
            $fecha_hasta=($values["fecha_hasta"]." 23:59:59.999");
            $sql="SELECT count(t.id) as total,p.producto,p.Id FROM DBCentral.dbo.transaccion as t";
            $sql.=" LEFT JOIN DBCentral.dbo.Producto as p ON p.id=t.tipo";
            $sql.=" WHERE t.tipo IN (16,161,261) AND FechaAlta>='".$fecha_desde."' AND FechaAlta<='".$fecha_hasta."'";
            $sql.=" GROUP BY p.producto, p.Id";
			$totales=$this->getRecordsAdHoc($sql);
            $html="<div class='card p-2'>";
            $html.="<h3>Operaciones Landing Mediya por producto</h3>";
            $html.="<table class='table-condensed' style='color:black;width:100%;' align='center'>";
            $html.="  <tr style='font-weight:bold;background-color:ivory;'>";
            $html.="     <td align='left'>Tipo</td>";
            $html.="     <td align='left'>Producto</td>";
            $html.="     <td align='right'>Operaciones</td>";
            $html.="     <td align='right'></td>";
            $html.="  </tr>";
            $html.="  <tbody>";
            foreach($totales as $item){
                $html.="    <tr>";
                $html.="       <td align='left'>".$item["Id"]."</td>";
                $html.="       <td align='left'>".$item["producto"]."</td>";
                $html.="       <td align='right'>".$item["total"]."</td>";
                $html.="    </tr>";
            }
            $html.="  </tbody>";
            $html.="</table>";
            $html.="</div>";

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "report"=>$html,
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
