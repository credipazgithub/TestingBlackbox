<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Informes extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function form($values){
        try {
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".$values["model"]));
            $html=$this->load->view(MOD_ONBOARDING."/Informes/form",$data,true);
            
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
            $fecha_desde=($values["fecha_desde"]." 00:00:00.000");
            $fecha_hasta=($values["fecha_hasta"]." 23:59:59.999");

            $html = "<div class='card p-2'>";

            $sql = "SELECT count(t.id) as total FROM DBCentral.dbo.transaccion as t";
            $sql .= " WHERE t.tipo IN (1,2,3,4,17,351,361,451,461,551,552) AND FechaAlta>='" . $fecha_desde . "' AND FechaAlta<='" . $fecha_hasta . "'";
            $totalgeneral = $this->getRecordsAdHoc($sql);
            $tgeneral = $totalgeneral[0]["total"];
            $html .= "<table class='table-condensed' style='color:black;width:100%;' align='center'>";
            $html .= "  <tr style='background-color:silver;'>";
            $html .= "     <td align='left'><h3>Producto</h3></td>";
            $html .= "     <td align='right'><h3>Operaciones</h3></td>";
            $html .= "     <td></td>";
            $html .= "     <td></td>";
            $html .= "  </tr>";
            $html .= "    <tr style='background-color:lightgreen;'>";
            $html .= "       <td align='left'><h4>Todos los productos</h4></td>";
            $html .= "       <td align='right' class='pr-4'><h4>" . $tgeneral . "</h4></td>";
            $html .= "       <td align='right'><h4>100%</h4></td>";
            $html .= "       <td align='left' class='pl-1'></td>";
            $html .= "    </tr>";



            $sql="SELECT count(t.id) as total,p.producto,p.Id FROM DBCentral.dbo.transaccion as t";
            $sql.=" LEFT JOIN DBCentral.dbo.Producto as p ON p.id=t.tipo";
            $sql.=" WHERE t.tipo IN (1,2,3,4,17,351,361,451,461,551,552) AND FechaAlta>='".$fecha_desde."' AND FechaAlta<='".$fecha_hasta."'";
            $sql.=" GROUP BY p.producto, p.Id";
			$totales=$this->getRecordsAdHoc($sql);

            $html.="  <tbody>";
            foreach($totales as $item){
                $tprod=$item["total"];
                $porc = round( ($tprod / $tgeneral) * 100,2);
                $html.="    <tr style='background-color:ivory;'>";
                $html.="       <td align='left'><h5>".$item["producto"]."</h5></td>";
                $html.= "       <td align='right' class='pr-4'><h5>" . $tprod . "</h5></td>";
                $html.= "       <td align='right'><h5>". $porc."%</h5></td>";
                $html .= "       <td align='left' class='pl-1'><h5>de ".$tgeneral."</h5></td>";
                $html.="    </tr>";
                
                $sql = "SELECT count(t.id) as total,e.description,e.message,e.color FROM DBCentral.dbo.transaccion as t ";
                $sql.= " LEFT JOIN DBCentral.dbo.estadotransaccion as e ON e.id=t.idEstadoTransaccion ";
                $sql .= " WHERE t.tipo IN (" . $item["Id"] . ") AND FechaAlta>='" . $fecha_desde . "' AND FechaAlta<='" . $fecha_hasta . "'";
                $sql.= " GROUP BY e.description,e.message,e.color ORDER BY e.description ASC";
                $detalles = $this->getRecordsAdHoc($sql);
                foreach ($detalles as $rec) {
                    $trec = $rec["total"];
                    $porc = round(($trec / $tprod) * 100, 2);
                    $html .= "    <tr>";
                    $html .= "       <td align='left' class='pl-5'>" . $rec["description"] . " <span class='badge badge-info' style='background-color:". $rec["color"]." !important;'>" . $rec["message"] ."</span></td>";
                    $html .= "       <td align='right' class='pl-4'><i>" . $trec . "</i></td>";
                    $html .= "       <td align='right'><i>" . $porc . "%</i></td>";
                    $html .= "       <td align='left' class='pl-1'><i>de " . $tprod . "</i></td>";
                    $html .= "    </tr>";
                }
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
