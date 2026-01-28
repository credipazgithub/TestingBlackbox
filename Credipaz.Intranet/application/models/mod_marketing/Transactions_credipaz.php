<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Transactions_credipaz extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    private function getConvertidaSql($canal, $fecha_desde,$fecha_hasta)
    {
        $sqlConvertidas = "SELECT count(t.id) as total FROM DBCentral.dbo.Transaccion as t LEFT JOIN DBCentral.dbo.Producto as p ON p.id=t.tipo";
        $sqlConvertidas .= " WHERE t.tipo IN (1,2,3,4,5,6,7,8,9,10) AND t.FechaAlta>='".$fecha_desde."' AND t.FechaAlta<='".$fecha_hasta."' AND t.idEstadoTransaccion=7 AND ";
        $sqlConvertidas .= " t.NroDocumento IN (SELECT t1.NroDocumento FROM dbCentral.dbo.Transaccion as t1 WHERE t1.tipo IN (". $canal.") AND t1.FechaAlta>DATEADD(month,-1,'" . $fecha_desde . "'))";
        return $sqlConvertidas;
    }

    public function form($values){
        try {
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".$values["model"]));
            $html=$this->load->view(MOD_MARKETING."/transactions_credipaz/form",$data,true);
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

            $sql="SELECT count(l.id) as total,p.producto,p.Id FROM ".MOD_BACKEND."_log_links as l LEFT JOIN DBCentral.dbo.Producto as p ON p.id=l.ValorFiltro";
            $where=" WHERE l.ValorFiltro IN (14,141,241) AND created>='".$fecha_desde."' AND created<='".$fecha_hasta."'";
            $group=" GROUP BY p.producto, p.Id ";
            $order=" ORDER BY p.producto ASC";
			$accedidasEfectivo=$this->getRecordsAdHoc($sql.$where.$group.$order);

            $sql="SELECT count(l.id) as total,p.producto,p.Id, p.notas FROM ".MOD_BACKEND."_log_links as l LEFT JOIN DBCentral.dbo.Producto as p ON p.id=l.ValorFiltro";
            $where=" WHERE l.ValorFiltro IN (15,151,251) AND created>='".$fecha_desde."' AND created<='".$fecha_hasta."'";
            $group=" GROUP BY p.producto, p.Id, p.notas ";
            $order=" ORDER BY p.producto ASC";
			$accedidasTarjeta=$this->getRecordsAdHoc($sql.$where.$group.$order);

            $sql="SELECT count(t.id) as total,p.producto,p.Id, p.notas FROM DBCentral.dbo.NS_vw_Transaccion as t LEFT JOIN DBCentral.dbo.Producto as p ON p.id=t.tipo";
            $where=" WHERE t.tipo IN (14,141,241) AND FechaAlta>='".$fecha_desde."' AND FechaAlta<='".$fecha_hasta."'";
            $group=" GROUP BY p.producto, p.Id, p.notas ";
            $order=" ORDER BY p.producto ASC";

			$totales=$this->getRecordsAdHoc($sql.$where.$group.$order);
            $rechazadas = $this->getRecordsAdHoc($sql . $where . " AND (t.idEstadoTransaccion=5 OR (t.valoraStats>0 AND t.valoraStats<10000)) " . $group . $order);

            $retenidas = $this->getRecordsAdHoc($sql . $where . " AND t.idEstadoTransaccion=10" . $group . $order);

            $s14 = $this->getConvertidaSql(14, $fecha_desde,$fecha_hasta);
            $s241 = $this->getConvertidaSql(241, $fecha_desde,$fecha_hasta);
            $convertidas14 = $this->getRecordsAdHoc($s14);
            $convertidas241 = $this->getRecordsAdHoc($s241);

            $html="<div class='container-full p-1 shadow-sm'>";
            $html.="<table class='table-condensed' style='color:black;width:100%;' align='center'>";
            $html.="  <tbody>";
            $html.="  <tr style='font-weight:bold;background-color:ivory;'>";
            $html .= "     <td align='left'><h5 style='color:darkgreen;font-weight:bold;' class='p-0 m-0'>Efectivo</h5></td>";
            $html .= "     <td align='left'></td>";
            $html.="     <td align='right'><span class='badge badge-dark'>Accedidas</span></td>";
            $html.="     <td align='right'>Leds</td>";
            $html.="     <td align='right' style='color:red;'>Rechazadas</td>";
            $html.="     <td align='right' style='color:darkred;'>Ingestionables</td>";
            $html .= "     <td align='right' style='color:green;'>Aceptadas</td>";
            $html .= "     <td align='right' style='color:blue;'>Convertidas</td>";
            $html.="  </tr>";
            $i=0;
            foreach($totales as $item){
                $totalConvertidasSucursales = 0;
                $accTot=0;
                $no=0;
                $nogestion = 0;
                $all=(int)$item["total"];
                foreach($accedidasEfectivo as $acc){if ((int)$acc["Id"]==(int)$item["Id"]) {$accTot=(int)$acc["total"];}}
                foreach($rechazadas as $rech){if ((int)$rech["Id"]==(int)$item["Id"]) {$no=(int)$rech["total"];}}
                foreach ($retenidas as $rech) {if ((int) $rech["Id"] == (int) $item["Id"]) {$nogestion = (int) $rech["total"];}}
                $si=($all-$no);

                $conv = 0;
                $sidTD = "";
                switch((int)$item["Id"]){
                    case 14:
                        $conv = (int) $convertidas14[0]["total"];
                        break;
                    case 141:
                        $sidTD = "td141";
                        break;
                    case 241:
                        $conv = (int) $convertidas241[0]["total"];
                        break;
                }

                $html.="    <tr>";
                $html .= "       <td align='left'>" . $item["producto"] . "</td>";
                $html .= "       <td align='left'><span class='badge badge-info'>" . $item["notas"] . "</span></td>";
                $html.="       <td align='right'><span class='badge badge-dark'>".$accTot."</span></td>";
                $html.="       <td align='right'>".$all."</td>";
                $html.="       <td align='right' style='color:red;'>".$no."</td>";
                $html.="       <td align='right' style='color:darkred;'>" . $nogestion . "</td>";
                $html .= "     <td align='right' style='color:green;'>" . $si . "</td>";
                $html .= "     <td align='right' style='color:blue;' class='". $sidTD."'><b>" . $conv . "</b></td>";
                $html.="    </tr>";

                if ((int)$item["Id"]==141){
                    $html.="    <tr>";
                    $html.="       <td align='left' colspan='5' class='pl-3'><b style='color:navy;'>Detalle por sucursal</b></td>";
                    $html.="    </tr>";
                    $sql2="SELECT count(t.id) as total,t.IdSucursal, s.sDescripcion, s.sEmail FROM DBCentral.dbo.transaccion as t LEFT JOIN DBCentral.dbo.stdSucursal as s ON s.nID=t.IdSucursal";
                    $where2=" WHERE t.tipo IN (141) AND FechaAlta>='".$fecha_desde."' AND FechaAlta<='".$fecha_hasta."'";
                    $group2=" GROUP BY t.IdSucursal, s.sDescripcion, s.sEmail ";
                    $order2=" ORDER BY s.sDescripcion ASC";
			        $detalles=$this->getRecordsAdHoc($sql2.$where2.$group2.$order2);
                    foreach($detalles as $item2){
                        $sql3="SELECT count(l.id) as total FROM ".MOD_BACKEND."_log_links as l LEFT JOIN DBCentral.dbo.Producto as p ON p.id=l.ValorFiltro";
                        $sql3.=" WHERE l.ValorFiltro IN (141) AND l.ValorSucursal=".$item2["IdSucursal"]." AND created>='".$fecha_desde."' AND created<='".$fecha_hasta."'";
			            $accesosSucursal=$this->getRecordsAdHoc($sql3);

                        $sql3="SELECT count(t.id) as total FROM DBCentral.dbo.NS_vw_Transaccion as t ";
                        $sql3.=" WHERE t.tipo IN (141) AND FechaAlta>='".$fecha_desde."' AND FechaAlta<='".$fecha_hasta."' AND (t.idEstadoTransaccion=5 OR (t.valoraStats>0 AND t.valoraStats<10000)) AND t.IdSucursal=".$item2["IdSucursal"];
                        $rechazosSucursal=$this->getRecordsAdHoc($sql3);

                        $sql3 = "SELECT count(t.id) as total FROM DBCentral.dbo.transaccion as t ";
                        $sql3 .= " WHERE t.tipo IN (141) AND FechaAlta>='" . $fecha_desde . "' AND FechaAlta<='" . $fecha_hasta . "' AND t.idEstadoTransaccion=10 AND t.IdSucursal=" . $item2["IdSucursal"];
                        $retenidasSucursal = $this->getRecordsAdHoc($sql3);

                        $sql4 = "SELECT count(id) as total FROM dbCentral.dbo.transaccion WHERE tipo IN (1,2,3,4) AND idEstadoTransaccion=7 AND IdSucursal=" . $item2["IdSucursal"]. " AND fechaalta>='" . $fecha_desde . "' AND fechaalta<='" . $fecha_hasta . "' AND NroDocumento IN (SELECT t.NroDocumento FROM dbCentral.dbo.Transaccion as t WHERE t.tipo IN (14,141,241) AND t.FechaAlta>DATEADD(month,-1,'" . $fecha_desde . "'))";
                        $convertidasSucursal = $this->getRecordsAdHoc($sql4);

                        $total=$item2["total"];
                        $reject=(int)$rechazosSucursal[0]["total"];
                        $accesos = (int) $accesosSucursal[0]["total"];
                        $nogestion = (int) $retenidasSucursal[0]["total"];
                        $convertidas = (int) $convertidasSucursal[0]["total"];
                        $totalConvertidasSucursales += $convertidas;
                        $accept=($total-$reject);
                        $html.="    <tr>";
                        $html.="       <td align='left' class='pl-5'>".$item2["sDescripcion"]."</td>";
                        $html.="       <td align='left'>".$item2["sEmail"]."</td>";
                        $html.="       <td align='right'>";
                        if ($accesos!=0) {$html.="<i style='font-size:0.75rem;'>(parcial)</i> <span class='badge badge-secondary'>".$accesos."</span>";}else{$html.="---";}
                        $html.="       </td>";
                        $html.="       <td align='right'>".$total."</td>";
                        $html .= "       <td align='right' style='color:red;'>" . $reject . "</td>";
                        $html .= "       <td align='right' style='color:darkred;'>" . $nogestion . "</td>";
                        $html .= "       <td align='right' style='color:green;'>" . $accept . "</td>";
                        $html .= "       <td align='right' style='color:blue;'><b>". $convertidas."</b></td>";
                        $html.="    </tr>";
                    }
                }
                $i+=1;
            }
            $html.="  <tr><td colspan='5'><hr/></td></tr>";

            $where=" WHERE t.tipo IN (15,151,251) AND FechaAlta>='".$fecha_desde."' AND FechaAlta<='".$fecha_hasta."'";
			$totales=$this->getRecordsAdHoc($sql.$where.$group.$order);
			$rechazadas=$this->getRecordsAdHoc($sql.$where." AND (t.rechazada=1 OR (t.valoraStats>0 AND t.valoraStats<10000)) ".$group.$order);
            $retenidas = $this->getRecordsAdHoc($sql . $where . " AND t.idEstadoTransaccion=10" . $group . $order);

            $html.="  <tr style='font-weight:bold;background-color:ivory;'>";
            $html.="     <td align='left' colspan='2'><h5 style='color:darkgreen;font-weight:bold;' class='p-0 m-0'>Tarjeta</h5></td>";
            $html .= "     <td align='right'><span class='badge badge-dark'>Accedidas</span></td>";
            $html .= "     <td align='right'>Leds</td>";
            $html .= "     <td align='right' style='color:red;'>Rechazadas</td>";
            $html .= "     <td align='right' style='color:darkred;'>Ingestionables</td>";
            $html .= "     <td align='right' style='color:green;'>Aceptadas</td>";
            $html .= "     <td align='right' style='color:blue;'>Convertidas</td>";
            $html.="  </tr>";
            $i=0;
            foreach($totales as $item){
                $accTot=0;
                $no=0;
                $all=(int)$item["total"];
                foreach($accedidasTarjeta as $acc){if ((int)$acc["Id"]==(int)$item["Id"]) {$accTot=(int)$acc["total"];}}
                foreach($rechazadas as $rech){if ((int)$rech["Id"]==(int)$item["Id"]) {$no=(int)$rech["total"];}}
                foreach ($retenidas as $rech) {if ((int) $rech["Id"] == (int) $item["Id"]) {$nogestion = (int) $rech["total"];}}
                $si = ($all - $no);

                $html.="    <tr>";
                $html.="       <td align='left'>".$item["producto"]." </td>";
                $html.="       <td align='left'><span class='badge badge-info'>" . $item["notas"] . "</span></td>";
                $html.="       <td align='right'><span class='badge badge-dark'>".$accTot."</span></td>";
                $html.="       <td align='right'>".$all."</td>";
                $html .= "       <td align='right' style='color:red;'>" . $no . "</td>";
                $html .= "       <td align='right' style='color:darkred;'>" . $nogestion . "</td>";
                $html.="       <td align='right' style='color:green;'>".$si."</td>";
                $html.="       <td align='right' style='color:blue;'<b>0</b></td>";
                $html.="    </tr>";
                if ((int)$item["Id"]==151){
                    $html.="    <tr>";
                    $html.="       <td align='left' colspan='5' class='pl-3'><b style='color:navy;'>Detalle por sucursal</b></td>";
                    $html.="    </tr>";
                    $sql2="SELECT count(t.id) as total,t.IdSucursal, s.sDescripcion, s.sEmail FROM DBCentral.dbo.transaccion as t LEFT JOIN DBCentral.dbo.stdSucursal as s ON s.nID=t.IdSucursal";
                    $where2=" WHERE t.tipo IN (151) AND FechaAlta>='".$fecha_desde."' AND FechaAlta<='".$fecha_hasta."'";
                    $group2=" GROUP BY t.IdSucursal, s.sDescripcion, s.sEmail ";
                    $order2=" ORDER BY s.sDescripcion ASC";
			        $detalles=$this->getRecordsAdHoc($sql2.$where2.$group2.$order2);
                    foreach($detalles as $item2){
                        $sql3="SELECT count(l.id) as total FROM ".MOD_BACKEND."_log_links as l LEFT JOIN DBCentral.dbo.Producto as p ON p.id=l.ValorFiltro";
                        $sql3.=" WHERE l.ValorFiltro IN (151) AND l.ValorSucursal=".$item2["IdSucursal"]." AND created>='".$fecha_desde."' AND created<='".$fecha_hasta."'";
			            $accesosSucursal=$this->getRecordsAdHoc($sql3);

                        $sql3="SELECT count(t.id) as total FROM DBCentral.dbo.NS_vw_Transaccion as t ";
                        $sql3.=" WHERE t.tipo IN (151) AND FechaAlta>='".$fecha_desde."' AND FechaAlta<='".$fecha_hasta."' AND (t.idEstadoTransaccion=5 OR (t.valoraStats>0 AND t.valoraStats<10000)) AND t.IdSucursal=".$item2["IdSucursal"];
			            $rechazosSucursal=$this->getRecordsAdHoc($sql3);

                        $sql3 = "SELECT count(t.id) as total FROM DBCentral.dbo.transaccion as t ";
                        $sql3 .= " WHERE t.tipo IN (151) AND FechaAlta>='" . $fecha_desde . "' AND FechaAlta<='" . $fecha_hasta . "' AND t.idEstadoTransaccion=10 AND t.IdSucursal=" . $item2["IdSucursal"];
                        $retenidasSucursal = $this->getRecordsAdHoc($sql3);

                        $total=$item2["total"];
                        $reject=(int)$rechazosSucursal[0]["total"];
                        $accesos=(int)$accesosSucursal[0]["total"];
                        $nogestion = (int) $retenidasSucursal[0]["total"];
                        $accept = ($total - $reject);
                        $html.="    <tr>";
                        $html.="       <td align='left' class='pl-5'>".$item2["sDescripcion"]."</td>";
                        $html.="       <td align='left'>".$item2["sEmail"]."</td>";
                        $html.="       <td align='right'>";
                        if ($accesos!=0) {$html.="<i style='font-size:0.75rem;'>(parcial)</i> <span class='badge badge-secondary'>".$accesos."</span>";}else{$html.="---";}
                        $html.="       </td>";
                        $html.="       <td align='right'>".$total."</td>";
                        $html.="       <td align='right' style='color:red;'>".$reject."</td>";
                        $html.="       <td align='right' style='color:darkred;'>" . $nogestion . "</td>";
                        $html.="       <td align='right' style='color:green;'>".$accept."</td>";
                        $html.="    </tr>";
                    }
                }
                $i+=1;
            }

            $html.="  </tbody>";
            $html.="</table>";
            $html.="</div>";
            $html .= "<script>setTimeout(function(){\$(\".td141\").html(\"<b>". $totalConvertidasSucursales."</b>\");},500);</script>";
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
