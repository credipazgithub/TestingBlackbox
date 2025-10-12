<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class consulta extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
	public function rpt_VentasPorEmpresaDetalle($values){
	    $return=$this->goforData($values);
		$titleCSV=array("Detalle de ventas por vendedor");
		$title="<h3>".$titleCSV[0]." <a href='#' target='_blank' class='btn-download btn btn-md btn-primary'><span class='material-icons'>download</span> Descargar CSV</a></h3>";
		$headerCSV=array("Vendedor","Alta","Nº socio","Estado","Nombre","Documento","Teléfono","Email");

		$detail="<table style='width:100%;'>";
		$detail.="<tr style='font-size:16px;color:white;background-color:grey;'>";
		for ($i = 0; $i < count($headerCSV); $i++) {$detail.="<td>".$headerCSV[$i]."</td>";}
		$detail.="</tr>";
		$last_break="";
		$sum=0;

		$blank[]="";
		$csv=fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
		fputs($csv,$bom=(chr(0xEF).chr(0xBB).chr(0xBF)));
		fputcsv($csv,$blank,";");
		fputcsv($csv, $titleCSV,";");
		fputcsv($csv,$blank,";");
		fputcsv($csv, $headerCSV,";");

        foreach ($return as $record){
			$lineCSV=array($record["Vendedor"],$record["FechaAlta"],$record["NroSocio"],$record["Estado"],$record["NombreSocio"],$record["DocumentoSocio"],$record["Telefono"],$record["Email"]);
			fputcsv($csv,$lineCSV,";");

		    $break="";
		    if ($last_break!=$lineCSV[0]){
				$last_break=$lineCSV[0];
				$break=$last_break;
			}
			$detail.="<tr style='font-size:14px;color:black;'>";
			$detail.="   <td>".$break."</td>";
			for ($i = 1; $i < count($lineCSV); $i++) {$detail.="<td>".$lineCSV[$i]."</td>";}
			$detail.="</tr>";
			$sum+=1;
		}
		$totalCSV=array("Total de ventas ".$sum);
		$total="<h5>".$totalCSV[0]."</h5>";
		$detail.="</table>";
		$html = ($title.$total.$detail);

		fputcsv($csv,$blank,";");
		fputcsv($csv, $totalCSV,";");
		rewind($csv);
		$outputCSV=stream_get_contents($csv);
		fclose($csv);
		$filename=(FILES_CSV.opensslRandom(16).".csv");
		file_put_contents(("./".$filename), $outputCSV);
		$limit=(60*60*2);
		foreach (glob("./".FILES_CSV."*") as $file){if(time() - filectime($file) > $limit){unlink($file);}}

		return array(
			"code"=>"2000",
			"status"=>"OK",
            "message"=>compress($this,$html),
			"csv"=>$filename,
			"function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
			"data"=>null,
			"compressed"=>true
		);
	}
	public function rpt_seguimientoCOVID($values){
	    $doctor=$values["browser_id_doctor"];
		$sql=("EXEC dbclub..rpt_seguimientoCOVID_Detalle @FechaDesde='".$values["FechaDesde"]."', @FechaHasta='".$values["FechaHasta"]."' ");
		$return=$this->getRecordsAdHoc($sql);

		$sql=("EXEC dbclub..rpt_seguimientoCOVID_Agrupado @FechaDesde='".$values["FechaDesde"]."', @FechaHasta='".$values["FechaHasta"]."' ");
		$returnTotals=parent::getRecordsAdHoc($sql);

		$titleCSV=array("Seguimiento de pacientes");
		$title="<h3>".$titleCSV[0]." <a href='#' target='_blank' class='btn-download btn btn-md btn-primary'><span class='material-icons'>download</span> Descargar CSV</a></h3>";
		$headerCSV=array("Médico","Nº siniestro","Nombre","Revisión","Fecha","Médico");

		$detail="<table style='width:100%;'>";
		$detail.="<tr style='font-size:12px;color:white;background-color:grey;'>";
		for ($i = 0; $i < count($headerCSV); $i++) {
		    $align="";
		    if ($i==1 or $i==3){$align="align='center'";}
			$detail.="<td ".$align." style='border:solid 1px white;'>".$headerCSV[$i]."</td>";
		}
		$detail.="</tr>";
		$last_break="";

		$blank[]="";
		$csv=fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
		fputs($csv,$bom=(chr(0xEF).chr(0xBB).chr(0xBF)));
		fputcsv($csv,$blank,";");
		fputcsv($csv, $titleCSV,";");
		fputcsv($csv,$blank,";");
		fputcsv($csv, $headerCSV,";");
		$c=0;
        $vtot=[];
        foreach ($return as $record){
  	        if(($doctor=="" or $doctor==$record["Médico"])){
				$lineCSV=array($record["Médico"],$record["NroSiniestro"],$record["Nombre"],$record["Revision"],$record["Fecha"],$record["Médico"]);
				fputcsv($csv,$lineCSV,";");
				$break="";
				if ($last_break!=$lineCSV[0]){
					if ($c!=0){
						$detail.="<tr style='font-size:14px;color:black;border-bottom:solid 1px white;'>";
						$detail.="   <td style='color:white;background-color:grey;' align='right'></td>";
						$detail.="   <td style='color:white;background-color:grey;border:solid 1px white;' align='center'>Total</td>";
						$detail.="   <td style='color:white;background-color:grey;border:solid 1px white;' align='left'>".$sum."</td>";
						$detail.="   <td style='color:white;background-color:grey;' align='right'></td>";
						$detail.="   <td style='color:white;background-color:grey;' align='right'></td>";
						$detail.="   <td style='color:white;background-color:grey;' align='right'></td>";
						$detail.="</tr>";
				        $vtot[$last_break]=$sum;
					}
					$last_break=$lineCSV[0];
					$break=$last_break;
					$sum=0;
				}
				$detail.="<tr style='font-size:14px;color:black;'>";
				$detail.="   <td style='color:white;background-color:grey;'>".$break."</td>";
				for ($i = 1; $i < count($lineCSV); $i++) {
				   $align="";
    			   if ($i==1 or $i==3){$align="center";}
				   $detail.="<td style='color:black;background-color:white;' align='".$align."'>".$lineCSV[$i]."</td>";
				}
				$detail.="</tr>";
				$sum+=1;
    			$c+=1;
			}
		}
		if ($c!=0){
			$detail.="<tr style='font-size:14px;color:black;border-bottom:solid 1px white;'>";
			$detail.="   <td style='color:white;background-color:grey;' align='right'></td>";
			$detail.="   <td style='color:white;background-color:grey;border:solid 1px white;' align='center'>Total</td>";
			$detail.="   <td style='color:white;background-color:grey;border:solid 1px white;' align='left'>".$sum."</td>";
			$detail.="   <td style='color:white;background-color:grey;' align='right'></td>";
			$detail.="   <td style='color:white;background-color:grey;' align='right'></td>";
			$detail.="   <td style='color:white;background-color:grey;' align='right'></td>";
			$detail.="</tr>";
	        $vtot[$last_break]=$sum;
		}
		$detail.="</table>";

		$total="<table style='border:solid 1px grey;'>";
		$total.="   <tr style='font-size:12px;color:white;background-color:grey;'>";
		$total.="      <td class='px-3' align='left' style='border:solid 1px grey;'>Médico</td>";
		$total.="      <td class='px-3' align='right' style='border:solid 1px grey;'>Revisiones</td>";
		$total.="   </tr>";

		foreach ($returnTotals as $record){
  	        if(($doctor=="" or $doctor==$record["Médico"])){
				$total.="<tr style='font-size:12px;color:black;background-color:white;'>";
				$total.="   <td class='px-3' align='left'>".$record["Médico"]."</td>";
				$total.="   <td class='px-3' align='right'>".$record["Revisiones"]."</td>";
				$total.="</tr>";
			}
		}
		$total.="</table>";

		$html = ($title.$total.$detail);

		fputcsv($csv,$blank,";");
		rewind($csv);
		$outputCSV=stream_get_contents($csv);
		fclose($csv);
		$filename=(FILES_CSV.opensslRandom(16).".csv");
		file_put_contents(("./".$filename), $outputCSV);
		$limit=(60*60*2);
		foreach (glob("./".FILES_CSV."*") as $file){if(time() - filectime($file) > $limit){unlink($file);}}

		return array(
			"code"=>"2000",
			"status"=>"OK",
            "message"=>compress($this,$html),
			"csv"=>$filename,
			"function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
			"data"=>null,
			"compressed"=>true
		);
	}

	private function goforData($values){
	    $result=null;
	    switch($values["system"]){
			case "storedprocedure":
				$sql=("EXEC ".$values["command"]." @IdEmpresa=".$values["IdEmpresa"].", @FechaDesde='".$values["FechaDesde"]."', @FechaHasta='".$values["FechaHasta"]."' ");
				$result=$this->getRecordsAdHoc($sql);
				break;
	    }
		return $result;
	}
}

