<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Mediya extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function subdiario($values){
        try {
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".$values["model"]));
            $html=$this->load->view(MOD_DBCENTRAL."/mediya/subdiario",$data,true);
            logGeneral($this,$values,__METHOD__);
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
	public function reportSubdiario($values) {
	   try {
			$titleCSV=array("Subdiario MEDIya del ".date(FORMAT_DATE_DMY, strtotime($values["FechaDesde"]))." al ".date(FORMAT_DATE_DMY, strtotime($values["FechaHasta"])));
			$title="<h3>".$titleCSV[0]." <a href='#' target='_blank' class='btn-download btn btn-md btn-primary'><span class='material-icons'>download</span> Descargar CSV</a></h3>";
			$headerCSV=array("Fecha","Prefijo","Tipo","NroComprobante","Nombre","NroDocumento","Concepto","Importe");

			$sql=("EXEC dbclub..rpt_SubdiarioMediYa @FechaDesde='".$values["FechaDesde"]."',@FechaHasta='".$values["FechaHasta"]."'");
			$return=$this->getRecordsAdHoc($sql);
			$detail="<table style='width:100%;'>";
			$detail.="<tr style='font-size:14px;color:white;background-color:grey;'>";
			for ($i = 0; $i < count($headerCSV); $i++) {
				$align="align='left' style='padding-left:10px;'";
				if ($i<=2){$align="align='center' style='padding-left:0px;'";}
				if ($i==3 or $i==5 or $i==7){$align="align='right' style='padding-left:0px;'";}
				$detail.="<td ".$align.">".$headerCSV[$i]."</td>";
			}
			$detail.="<td></td>";
			$detail.="</tr>";

			$blank[]="";
			$csv=fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
			fputs($csv,$bom=(chr(0xEF).chr(0xBB).chr(0xBF)));
			fputcsv($csv,$blank,";");
			fputcsv($csv, $titleCSV,";");
			fputcsv($csv,$blank,";");
			fputcsv($csv, $headerCSV,";");

			foreach ($return as $record){
				$lineCSV=array(date(FORMAT_DATE_DMY, strtotime($record["Fecha"])),$record["Prefijo"],$record["Tipo"],$record["NroComprobante"],$record["Nombre"],$record["NroDocumento"],$record["Concepto"],$record["Importe"]);
				fputcsv($csv,$lineCSV,";");
				$detail.="<tr>";
				for ($i = 0; $i < count($lineCSV); $i++) {
					$align="align='left' style='padding-left:10px;'";
					if ($i<=2){$align="align='center' style='padding-left:0px;'";}
					if ($i==3 or $i==5 or $i==7){$align="align='right' style='padding-left:0px;'";}
					$detail.="<td ".$align.">".$lineCSV[$i]."</td>";
				}

				$rec=array(
				"Fecha"=>date(FORMAT_DATE_DMY, strtotime($record["Fecha"])),
				"Prefijo"=>$record["Prefijo"],
				"Tipo"=>$record["Tipo"],
				"NroComprobante"=>$record["NroComprobante"],
				"Nombre"=>$record["Nombre"],
				"NroDocumento"=>$record["NroDocumento"],
				"Concepto"=>$record["Concepto"],
				"Importe"=>$record["Importe"],
				"CAE"=>$record["CAE"],
				"VtoCAE"=>$record["VtoCAE"],
				"Identificacion"=>$record["Identificacion"],
				"QR"=>$record["QR"],
				"logoAFIP"=>"data:image/png;base64,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"
				);

				$detail.="<td><a href='#' class='btn btn-success btn-sm btn-raised btn-see-comprobante' data-raw='".base64_encode(json_encode($rec))."'>Ver</a></td>";
				$detail.="</tr>";
			}
			$detail.="</table>";
			$html=$title.$detail;

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
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}

