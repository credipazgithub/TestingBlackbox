<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Ingresos extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function consulta($values){
        try {
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".$values["model"]));
            $html=$this->load->view(MOD_DBCENTRAL."/ingresos/consulta",$data,true);
            
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
	public function reportConsulta($values) {
	   try {
	        $total=0;
			$descSexo="Femenino";
			if ($values["sexo"]=="M"){$descSexo="Masculino";}
			$titleCSV=array("Consulta de ingresos DNI ".$values["dni"]." - ".$descSexo);
			$title="<h4>".$titleCSV[0]." <a href='#' target='_blank' class='btn-download btn btn-md btn-primary'><span class='material-icons'>download</span> Descargar CSV</a></h4>";
			$headerCSV=array("","Empleador","IdCliente","Legajo","Nombre Registrado","Ingreso Registrado","Ingreso Actual","");
			$sql=("EXEC DBCentral.dbo.NS_IngresosPersona ".$values["dni"].",'".$values["sexo"]."'");

			$return=$this->getRecordsAdHoc($sql);
			$detail="<table style='width:100%;'>";
			$detail.="<tr style='font-size:14px;color:white;background-color:grey;'>";
			$detail.="<td></td>";
			for ($i = 0; $i < count($headerCSV); $i++) {
				$align="align='left' style='padding-left:10px;'";
				if ($i==2 or $i==3 or $i==5 or $i==6){$align="align='right' style='padding-left:0px;'";}
				$detail.="<td ".$align.">".$headerCSV[$i]."</td>";
			}
			$detail.="</tr>";

			$blank[]="";
			$csv=fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
			fputs($csv,$bom=(chr(0xEF).chr(0xBB).chr(0xBF)));
			fputcsv($csv,$blank,";");
			fputcsv($csv, $titleCSV,";");
			fputcsv($csv,$blank,";");
			fputcsv($csv, $headerCSV,";");
			$y=0;
			foreach ($return as $record){
				$lineCSV=array("",$record["Empleador"],$record["IdCliente"],$record["Legajo"],$record["Nombre Registrado"],$record["Ingreso Registrado"],$record["Ingreso Actual"],$record["Mensaje"]);
				fputcsv($csv,$lineCSV,";");
				$detail.="<tr class='tr-".$y."'>";

				$detail.="<td>";
				if(count($return)>1) {
				   $detail.="<a href='#' data-remove='.tr-".$y."' class='btn btn-sm btn-exclude btn-success btn-raised'>Excluir</a>";
				}
				$detail.="</td>";

				for ($i = 0; $i < count($lineCSV); $i++) {
				    $class="";
					$align="align='left' style='padding-left:10px;'";
					if ($i==2 or $i==3 or $i==5 or $i==6){$align="align='right' style='padding-left:0px;'";}
					if ($i==2){
					   $class=" class='cliente' data-cliente='".$lineCSV[$i]."'";
					}
					if ($i==5){
					   $lineCSV[$i]=("$ ".number_format($lineCSV[$i], 2, ',', '.'));
					}
					if ($i==6){
					   $total+=(float)$lineCSV[$i];
					   $class=" class='amount' data-importe='".$lineCSV[$i]."'";
					   $lineCSV[$i]=("$ ".number_format($lineCSV[$i], 2, ',', '.'));
					}
					if ($i==7){
					   $class=" class='mensaje d-none' data-mensaje='".$lineCSV[$i]."'";
					}
					$detail.="<td ".$align.$class.">".$lineCSV[$i]."</td>";
				}
				$detail.="</tr>";
				$y+=1;
			}
			$detail.="<tr style='background-color:silver;font-weight:bold;'>";
			$detail.="   <td colspan='7'>TOTAL</td>";
			$detail.="   <td class='total' align='right' data-total='".$total."'>$ ".number_format($total, 2, ',', '.')."</td>";
			$detail.="   <td></td>";
			$detail.="</tr>";
			$detail.="</table>";
			$html=$title.$detail;
			$html.="<table style='width:100%;'>";
			$html.="<tr>";
			$html.="   <td align='center'>";
			$html.="      <hr/><a href='#' class='btn btn-md btn-update btn-success btn-raised'>Actualizar</a>";
			$html.="   </td>";
			$html.="</tr>";
			$html.="</table>";

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
	public function reportUpdate($values) {
	   try {
			$profile=getUserProfile($this,$values["id_user_active"]);
			$sql=("EXEC dbcentral..ActualizarIngresosCliente @IdCliente ='".$values["IdCliente"]."', @Ingresos='".$values["Ingresos"]."', @Usuario='".$profile["data"][0]["username"]."' ");
			$this->execAdHoc($sql);

			return array(
				"code"=>"2000",
				"status"=>"OK",
				"message"=>"",
				"function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
				"compressed"=>false
			);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}

