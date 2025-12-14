<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Transactions extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
			$fields=null;
            if($id==0){
				$fields = array(
					'code' => opensslRandom(8),
					'description' => 'Pago vía agente externo',
					'created' => $this->now,
					'verified' => $this->now,
					'fum' => $this->now,
					'id_type_channel' => secureEmptyNull($values,"id_type_channel"),
					'identificacion' => $values["identificacion"],
					'status' => "INICIADO",
					'currency_request' => $values["currency_request"],
					'dni_request' => $values["dni_request"],
					'amount_request' => $values["amount_request"],
					'raw_request' => $values["raw_request"],
					'channel' => $values["channel"],
					'registro_externo' => $values["registro_externo"],
				);
            } else {
				$fields = array(
					'fum' => $this->now,
					'status' => $values["status"],

					'currency_response' => $values["currency_response"],
					'dni_response' => $values["dni_response"],
					'amount_response' => $values["amount_response"],
					'card_response' => $values["card_response"],

					'card_name' => $values["card_name"],
					'partial_card_number' => $values["partial_card_number"],
					'message' => $values["message"],
					'raw_response' => $values["raw_response"],
					'registro_externo' => $values["registro_externo"]
				);
            }
            return parent::save(array("id"=>$id),$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function form($values){
        try {
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".$values["model"]));
            $html=$this->load->view(MOD_PAYMENTS."/transactions/form",$data,true);
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
    public function infoPayments($values){
        try {
            $rango=date(FORMAT_DATE_DMY, strtotime($values["fecha_desde"]))." al ".date(FORMAT_DATE_DMY, strtotime($values["fecha_hasta"]));
            $fecha_desde=($values["fecha_desde"]." 00:00:00.000");
            $fecha_hasta=($values["fecha_hasta"]." 23:59:59.999");
			$sql="SELECT COUNT(*) as cantidad,sum(cast(amount_rel as float)) as importe,'Pagos enviados para procesamiento interno' as [message] FROM mod_backend_log_general WHERE [action]='Payments::setItemPagoResponse' AND id_rel IN ";
            $sql.=" (SELECT Id FROM mod_payments_transactions WHERE [status]='APROBADO' AND created>='".$fecha_desde."' AND created<='".$fecha_hasta."')";
			$aprobadas=$this->getRecordsAdHoc($sql);

            $sql="SELECT COUNT(*) as cantidad,sum(Importe) as importe,'Pagos recibidos para procesamiento interno' as [message] FROM dbcentral.dbo.RespuestaCobranza WHERE Id_mod_payments_transactions IN ";
            $sql.=" (SELECT Id FROM mod_payments_transactions WHERE [status]='APROBADO' AND created>='".$fecha_desde."' AND created<='".$fecha_hasta."')";
			$recibidas=$this->getRecordsAdHoc($sql);

            $sql="SELECT COUNT(*) AS cantidad,'APROBADO' as [status],'Transacciones de pago enviadas a proceso interno' as [message] FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('aprobado')";
            $sql.=" UNION ";
            $sql.="SELECT COUNT(*) AS cantidad,'INICIADO' as [status],'Sin respuesta de la plataforma externa' as [message] FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('iniciado')";
            $sql.=" UNION ";
            $sql.="SELECT COUNT(*) AS cantidad,[status],[message] FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('fallado','negado') GROUP BY [status], [message]";
			$desglose=$this->getRecordsAdHoc($sql);

            $sql="SELECT COUNT(*) AS cantidad,sum(cast(amount_request as float)) as importe,'Aprobadas en plataforma externa' as [message] FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('aprobado')";
			$transacciones=$this->getRecordsAdHoc($sql);

            $sql="SELECT COUNT(id) AS cantidad FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('iniciado')";
            $sql.="  AND identificacion IN (SELECT identificacion FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN('APROBADO'))";
			$iniciado_aprobado=$this->getRecordsAdHoc($sql);

            $sql="SELECT COUNT(id) AS cantidad FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('iniciado')";
            $sql.="  AND identificacion IN (SELECT identificacion FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN('FALLADO'))";
			$iniciado_fallado=$this->getRecordsAdHoc($sql);

            $sql="SELECT COUNT(id) AS cantidad FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('iniciado')";
            $sql.="  AND identificacion IN (SELECT identificacion FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN('NEGADO'))";
			$iniciado_negado=$this->getRecordsAdHoc($sql);


            $sql="SELECT cast((SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE status IN ('iniciado')) / (SELECT cast(COUNT(id) as float) FROM mod_payments_transactions) as float)*100 as porcentaje";
			$promedio_iniciado=$this->getRecordsAdHoc($sql);
            $sql="SELECT cast((SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE status IN ('aprobado')) / (SELECT cast(COUNT(id) as float) FROM mod_payments_transactions) as float)*100 as porcentaje";
			$promedio_aprobado=$this->getRecordsAdHoc($sql);
            $sql="SELECT cast((SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE status IN ('fallado')) / (SELECT cast(COUNT(id) as float) FROM mod_payments_transactions) as float)*100 as porcentaje";
			$promedio_fallado=$this->getRecordsAdHoc($sql);
            $sql="SELECT cast((SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE status IN ('negado')) / (SELECT cast(COUNT(id) as float) FROM mod_payments_transactions) as float)*100 as porcentaje";
			$promedio_negado=$this->getRecordsAdHoc($sql);

            $sql="SELECT cast((SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('iniciado')) / (SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."') as float)*100 as porcentaje";
			$rango_iniciado=$this->getRecordsAdHoc($sql);
            $sql="SELECT cast((SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('aprobado')) / (SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."') as float)*100 as porcentaje";
			$rango_aprobado=$this->getRecordsAdHoc($sql);
            $sql="SELECT cast((SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('fallado')) / (SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."') as float)*100 as porcentaje";
			$rango_fallado=$this->getRecordsAdHoc($sql);
            $sql="SELECT cast((SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."' AND status IN ('negado')) / (SELECT cast(COUNT(id) as float) FROM mod_payments_transactions WHERE created>='".$fecha_desde."' AND created<='".$fecha_hasta."') as float)*100 as porcentaje";
			$rango_negado=$this->getRecordsAdHoc($sql);

            $cantidad_externo=(float)$aprobadas[0]["cantidad"];
            $cantidad_interno=(float)$recibidas[0]["cantidad"];
            $cantidad_diferencia=($cantidad_externo-$cantidad_interno);

            $importe_externo=(float)$aprobadas[0]["importe"];
            $importe_interno=(float)$recibidas[0]["importe"];
            $importe_diferencia=(int)($importe_externo-$importe_interno);

            $pIni=(float)$promedio_iniciado[0]["porcentaje"];
            $rIni=(float)$rango_iniciado[0]["porcentaje"];
            $pApro=(float)$promedio_aprobado[0]["porcentaje"];
            $rApro=(float)$rango_aprobado[0]["porcentaje"];
            $pFall=(float)$promedio_fallado[0]["porcentaje"];
            $rFall=(float)$rango_fallado[0]["porcentaje"];
            $pNeg=(float)$promedio_negado[0]["porcentaje"];
            $rNeg=(float)$rango_negado[0]["porcentaje"];

            $html="<div class='m-0 col-7'>";
            $html.="<h3>Consistencia de información procesada</h3>";
            $html.="<table class='table-condensed' style='color:grey;width:100%;' align='center'>";
            $html.="  <tr style='font-weight:bold;background-color:ivory;'>";
            $html.="     <td align='right'>Operaciones</td>";
            $html.="     <td align='right'>Total</td>";
            $html.="     <td align='left'>Mensaje</td>";
            $html.="     <td align='right'></td>";
            $html.="  </tr>";
            $html.="  <tbody>";
            $html.="    <tr>";
            $html.="       <td align='right'>".$transacciones[0]["cantidad"]."</td>";
            $html.="       <td align='right'>$ ".number_format($transacciones[0]["importe"],2,",",".")."</td>";
            $html.="       <td align='left'>".$transacciones[0]["message"]."</td>";
            $html.="       <td align='right'>[CONSISTENCIA]</td>";
            $html.="    </tr>";
            $html.="    <tr>";
            $html.="       <td align='right'>".$cantidad_externo."</td>";
            $html.="       <td align='right'>$ ".number_format($importe_externo,2,",",".")."</td>";
            $html.="       <td align='left'>".$aprobadas[0]["message"]."</td>";
            $html.="       <td align='right'>[CONSISTENCIA2]</td>";
            $html.="    </tr>";
            $html.="    <tr>";
            $html.="       <td align='right'>".$cantidad_interno."</td>";
            $html.="       <td align='right'>$ ".number_format($importe_interno,2,",",".")."</td>";
            $html.="       <td align='left'>".$recibidas[0]["message"]."</td>";
            $html.="       <td align='right'>[CONSISTENCIA2]</td>";
            $html.="    </tr>";
            $msg_cantidad="N/A";
            if ($cantidad_diferencia>0){$msg_cantidad="<b style='color:red;'>Hay más operaciones enviadas que recibidas</b>";}
            if ($cantidad_diferencia<0){$msg_cantidad="<b style='color:rd;'>Hay más operaciones recibidas que enviadas</b>";}
            if ($cantidad_diferencia==0){$msg_cantidad="<b style='color:green;'>La información es consistente</b>";}
              $html=str_replace("[CONSISTENCIA2]",$msg_cantidad,$html);

            $html.="    <tr><td colspan='4'><hr/></td></tr>";
            if ($importe_diferencia!=0){
                $html.="    <tr><td colspan='4'><hr/></td></tr>";
                $html.="    <tr style='color:".$color."'>";
                $html.="       <td align='right'><b>".abs($importe_diferencia)."</b></td>";
                $html.="       <td align='right'></td>";
                $html.="       <td colspan='2' align='right'>Importe de diferencia</td>";
                $html.="    </tr>";
            }
            $html.="  </tbody>";
            $html.="</table>";
            $html.="<br/>";

            $html.="<h3>Detale de resultados transaccionales</h3>";
            $html.="<table class='table-condensed' style='color:grey;width:100%;' align='center'>";
            $html.="  <tr style='font-weight:bold;background-color:ivory;'>";
            $html.="     <td align='right'>Operaciones</td>";
            $html.="     <td align='center'>Estado</td>";
            $html.="     <td align='left'>Mensaje</td>";
            $html.="     <td align='left'></td>";
            $html.="  </tr>";
            $html.="  <tbody>";
            foreach($desglose as $item){
                $sep=false;
                $consistencia="";
                $addLine="";
                switch($item["status"]){
                   case "APROBADO":
                       $sep=true;
                       if((int)$transacciones[0]["cantidad"]==(int)$item["cantidad"]) {
                          $consistencia="<b style='color:green;'>La información es consistente</b>";
                       } else{
                          $consistencia="<b style='color:red;'>La información NO es consistente</b>";
                       }
                       break;
                   case "INICIADO":
                       $sep=true;
                       $consistencia="<p>".$iniciado_aprobado[0]["cantidad"]." finalmente con estado APROBADO"."</p>";
                       $consistencia.="<p>".$iniciado_fallado[0]["cantidad"]." finalmente con estado NEGADO"."</p>";
                       $consistencia.="<p>".$iniciado_negado[0]["cantidad"]." finalmente con estado FALLADO"."</p>";
                       $addLine="<tr><td colspan='4'><h5 style='color:darkred;'>Errores reportados por la plataforma externa</h5></td></tr>";
                       break;
                }
                $html=str_replace("[CONSISTENCIA]",$consistencia,$html);
                $html.="    <tr valign='top'>";
                $html.="       <td align='right'>".$item["cantidad"]."</td>";
                $html.="       <td align='center'>".$item["status"]."</td>";
                $html.="       <td align='left'>".$item["message"]."</td>";
                $html.="       <td align='right'>".$consistencia."</td>";
                $html.="    </tr>";
                if($sep) {
                   $html.="<tr><td colspan='4'><hr/></td></tr>";
                   $html.=$addLine;
                }
            }
            $html.="  </tbody>";
            $html.="</table>";
            $html.="</div>";
            $html.="<div class='m-0 col-4'>";
            $html.="<h3>Valores normales</h3>";
            $html.="<table class='table-condensed' style='color:grey;width:100%;' align='center'>";
            $html.="  <tr style='font-weight:bold;background-color:ivory;'>";
            $html.="     <td align='left'>Operación</td>";
            $html.="     <td align='right'>Histórico</td>";
            $html.="     <td align='right'>".$rango."</td>";
            $html.="     <td align='center'></td>";
            $html.="  </tr>";
            $html.="  <tbody>";
            $html.="    </tr>";
            $icon="expand_less";
            if ($pIni>$rIni){$icon="expand_more";}
            $td="<span class='material-icons'>".$icon."</span>";
            $html.="    <tr>";
            $html.="       <td align='left'>INICIADO</td>";
            $html.="       <td align='right'>".number_format($pIni,2,",",".")."%</td>";
            $html.="       <td align='right'>".number_format($rIni,2,",",".")."%</td>";
            $html.="       <td align='center'>".$td."</td>";
            $html.="    </tr>";

            $icon="expand_less";
            if ($pApro>$rApro){ $icon="expand_more";}
            $td="<span class='material-icons'>".$icon."</span>";
            $html.="    <tr>";
            $html.="       <td align='left'>APROBADO</td>";
            $html.="       <td align='right'>".number_format($pApro,2,",",".")."%</td>";
            $html.="       <td align='right'>".number_format($rApro,2,",",".")."%</td>";
            $html.="       <td align='center'>".$td."</td>";
            $html.="    </tr>";
            $icon="expand_less";
            if ($pFall>$rFall){ $icon="expand_more";}
            $td="<span class='material-icons'>".$icon."</span>";
            $html.="    <tr>";
            $html.="       <td align='left'>FALLADO</td>";
            $html.="       <td align='right'>".number_format($pFall,2,",",".")."%</td>";
            $html.="       <td align='right'>".number_format($rFall,2,",",".")."%</td>";
            $html.="       <td align='center'>".$td."</td>";
            $html.="    </tr>";
            $icon="expand_less";
            if ($pNeg>$rNeg){ $icon="expand_more";}
            $td="<span class='material-icons'>".$icon."</span>";
            $html.="    <tr>";
            $html.="       <td align='left'>NEGADO</td>";
            $html.="       <td align='right'>".number_format($pNeg,2,",",".")."%</td>";
            $html.="       <td align='right'>".number_format($rNeg,2,",",".")."%</td>";
            $html.="       <td align='center'>".$td."</td>";
            $html.="    </tr>";
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
