<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class ClubRedondoWS extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function autorizarPrestacion($values){
        try {
		    $token=$this->Authenticate();
			$headers = array('Content-Type:application/json','Authorization: Bearer '.$token);
 		    $fields=array(
                 "NroDocumento"=>(int)$values["express_key"],
                 "codigoPrestador"=>(string)$values["express_code"],
                 "codigo"=>(string)$values["express_code"]
            );
			$url=(CPFINANCIALS."/Utilidades/AutorizarPrestacion/");
			$result = $this->callAPI($url,$headers,json_encode($fields));
			$result = json_decode($result, true);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );        
		}
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function getUserInformation($values,$scope){
        $sql="";
        if (strpos($values["dni"], "@") !== false) {$values["dni"] = explode("@", $values["dni"])[0];}
        switch($scope){
           case "CP":
              $sql=(string)"EXEC DBCentral.dbo.NS_Clientes_Datos_Generales_JSON @xmlData='<Consulta><Documento>".$values["dni"]."</Documento><Sexo>".$values["sex"]."</Sexo></Consulta>'";
              $result=$this->getRecordsAdHoc($sql);
              $result = objectToArrayRecusive($result);
              $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
              $user=$USERS->get(array("fields"=>"id,id_type_user,username,viable,documentArea,documentPhone,documentName,documentType,documentNumber,documentSex,created,fum","where"=>"username='".$values["dni"]."@clubredondo.com"."'"));
              $result["registered"]=(int)$user["totalrecords"];
              $result["userdata"]=null;
              if ((int)$user["totalrecords"]!=0){
                 $nro = explode('@', $user["data"][0]["documentNumber"])[0];
                 $sql="SELECT nombre, apellido FROM DBClub.dbo.persona WHERE NroDocumento=".$nro;
                 $socioX=$this->getRecordsAdHoc($sql);
                 $user["data"][0]["name"]=$socioX[0]["nombre"];
                 $result["registered"]=1;
                 $result["userdata"]=$user["data"][0];
              }
              $result["message"]=$result[0];
              break;
           case "CR":
              $sql=(string)"EXEC DBClub.dbo.NS_Socio_Datos_Generales_JSON @xmlData='<Consulta><Documento>".$values["dni"]."</Documento><Sexo>".$values["sex"]."</Sexo></Consulta>'";
              $result=$this->getRecordsAdHoc($sql);
              $result = objectToArrayRecusive($result);
              $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
              $user=$USERS->get(array("fields"=>"id,id_type_user,username,viable,documentArea,documentPhone,documentName,documentType,documentNumber,documentSex,created,fum","where"=>"username='".$values["dni"]."@clubredondo.com"."'"));
              $result["registered"]=(int)$user["totalrecords"];
              $result["userdata"]=null;
              if ((int)$user["totalrecords"]!=0){
                 $nro = explode('@', $user["data"][0]["documentNumber"])[0];
                 $sql="SELECT nombre, apellido FROM DBClub.dbo.persona WHERE NroDocumento=". $nro;
                 $socioX=$this->getRecordsAdHoc($sql);
                 $user["data"][0]["name"]=$socioX[0]["nombre"];
                 $result["registered"]=1;
                 $result["userdata"]=$user["data"][0];
              }
              $result["message"]=$result[0];
              break;
        }
        $result["scope"] = $scope;
        return $result;
    }
    public function getIdentityInformation($values){
        try {
            if (!isset($values["documentNumber"])) {$values["documentNumber"] = "";}
            if ($values["documentNumber"] != "") {
                $sql = (string) "EXEC DBCentral.dbo.NS_ObtenerDatosPersona @nroDoc=" . $values["documentNumber"] . ",@sexo='" . $values["documentSex"] . "'";
                $result = $this->getRecordsAdHoc($sql);
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (SOAPFault $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function EvaluarDocumento($values){
        try {
            if(!isset($values["Documento"])){$values["Documento"]="0";}
            if(!isset($values["Sexo"])){$values["Sexo"]="";}
            if (strtoupper($values["Sexo"])!="M" && strtoupper($values["Sexo"])!="F") {throw new Exception(lang("error_5218"),5218);}
            $sql="SELECT count(wt.nID) as total FROM dbCentral.dbo.wrkClienteTitular as wt ";
	        $sql.=" LEFT JOIN dbHistorico.dbo.CalificacionClientes as cc ON cc.nIDCliente=wt.nCliente ";
	        $sql.=" WHERE wt.sLKDocTipo='DNI' AND wt.nDoc='".$values["Documento"]."' AND wt.sSexo='".$values["Sexo"]."' AND ";
            $sql.=" (ISNULL(cc.nCreCasosPeriodo,0)!=0 OR ISNULL(cc.nTarResumenesUlt6Meses,0)!=0)";
            $cliente=$this->getRecordsAdHoc($sql);
            $data=array("EsCliente"=>((int)$cliente[0]["total"]!=0));

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data,
                "compressed"=>false
            );
        }
        catch (SOAPFault $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function AFIPalameEsta(){
        try {
		    $token=$this->Authenticate();
			$headers = array('Content-Type:application/json','Authorization: Bearer '.$token);
 		    $fields=array();
			$url=(CPFINANCIALS."/Utilidades/GenerarFacturasAFIPAutomaticas/");
			$result = $this->callAPI($url,$headers,json_encode($fields));
			$result = json_decode($result, true);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );        
		}
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
	public function responseTransactionAsync($values){
		//logGeneralCustom($this,$params,"Payments::callbackCOIN",json_encode($values));
		return array(
			"code"=>"2000",
			"status"=>"OK",
			"message"=>"",
			"function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
		);
	}
	public function traerLookUp($values){
        try {
            $sql="SELECT sItem AS id, sDescripcion AS description FROM DBCentral.dbo.stdlookup WHERE stabla='".$values["tabla"]."'";
            switch($values["tabla"]){
                 case "ModoPago":
                    //$sql="SELECT Id,Descripcion FROM DBClub.dbo.ModoPago";
                    break;
                 case "OpcionModoPago":
                    $sql="SELECT Id,Descripcion FROM DBClub.dbo.OpcionModoPago WHERE IdModoPago=".$values["key"];
                    break;
                case "Sucursales":
                case "Usuarios":
                    $sql="EXEC DBCentral.dbo.NS_lk_Sucursales_Activas";
                    break;
                case "EmpresaVentaCR":
                    $sql="EXEC DBClub.dbo.NS_GetEmpresasSel";
                    break;
                case "Ocupacion":
                    $sql="EXEC DBClub.dbo.NS_lkOcupacion";
                    break;
            }
            $result=$this->getRecordsAdHoc($sql);
            
			return array(
				"code"=>"2000",
				"status"=>"OK",
				"message"=>"",
				"function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
				"data"=>$result,
				"compressed"=>false
			);
		}
		catch (Exception $e) {
			return logError($e,__METHOD__ );
		}
	}
    public function EventoActual($values){
        try {
            $sql="SELECT * FROM DBCentral.dbo.Evento WHERE CAST(FLOOR(CAST(FechaEvento AS FLOAT)) AS DATETIME) = CAST(FLOOR(CAST(GETDATE() AS FLOAT)) AS DATETIME)";
            $result=$this->getRecordsAdHoc($sql);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function CheckInvitado($values){
        try {

			$segments = explode('/', $values["NroDocumento"]);
			$numSegments = count($segments); 
			$values["NroDocumento"] = ($segments[$numSegments - 1]);
            $sql="EXEC DBCentral.dbo.NS_CheckInvitado @IdEvento=".$values["IdEvento"].", @NroDocumento=".$values["NroDocumento"];
            $result=$this->getRecordsAdHoc($sql);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function RegistrarIngreso($values){
        try {
            $sql="EXEC DBCentral.dbo.NS_AsistenciaEvento_I @IdEvento=".$values["IdEvento"].", @Tipo='".$values["Tipo"]."', @NroDocumento=".$values["NroDocumento"];
            $result=$this->getRecordsAdHoc($sql);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }    
	public function FacturasPorPersona($values){
        try {
            $sql="EXEC DBClub.dbo.NS_FacturasPorPersona @Origen='".$values["origen"]."', @Identificacion=".$values["codigo"];
            $result=$this->getRecordsAdHoc($sql);

            $result[0]["QR"]=json_decode($result[0]["QR"], true);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function GetComprobante($values){
        try {
            $sql="EXEC DBClub.dbo.NS_GetComprobante @Empresa='".$values["empresa"]."', @Tipo='".$values["tipo"]."', @NroComprobante=".$values["nroComprobante"];
            $result=$this->getRecordsAdHoc($sql);
            $result[0]["QR"]=json_decode($result[0]["QR"], true);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function dataForPaymentsByType($values){
        try {
		    if ($values["gateway"]=="COIN"){$values["gateway"]="FSRV";}
		    if (!isset($values["form"])){$values["form"]="TAR,CRE,CICR,CRDO,SAM,MOR";}
            $types=explode(',', $values["form"]);

		    $tarjeta=null;
		    $credito=null;
            $sam = null;
            $acuerdo=null;
            $mora = null;
		    $cuota_inicial_cr=null;
		    $cuota_inicial_crdo=null;
            //$values["dni"]="28628058"; //DNI para testeo
            if(!isset($values["dni"])){throw new Exception("No se ha provisto DNI");}

            foreach ($types as $item) {
                switch ($item) {
                    case "TAR":
                        $tarjeta = $this->getPaymentsByType($values, $item);
                        if ($tarjeta[0]["Message"] == "Cuenta inhabilitada para el pago no presencial.") {
                            throw new Exception($tarjeta[0]["Message"]);
                        }
                        break;
                    case "CRE":
                        $credito = $this->getPaymentsByType($values, $item);
                        if ($credito[0]["Message"] == "Cuenta inhabilitada para el pago no presencial.") {
                            throw new Exception($credito[0]["Message"]);
                        }
                        break;
                    case "SAM":
                        $sam = $this->getPaymentsByType($values, $item);
                        if ($credito[0]["Message"] == "Cuenta inhabilitada para el pago no presencial.") {
                            throw new Exception($sam[0]["Message"]);
                        }
                        break;
                    case "MOR":
                        $mora = $this->getPaymentsByType($values, $item);
                        if ($mora[0]["Message"] == "Cuenta inhabilitada para el pago no presencial.") {
                            throw new Exception($mora[0]["Message"]);
                        }
                        break;
                    case "CICR":
                        $cuota_inicial_cr = $this->getPaymentsByType($values, $item);
                        if ($cuota_inicial_cr[0]["Message"] == "Cuenta inhabilitada para el pago no presencial.") {
                            throw new Exception($cuota_inicial_cr[0]["Message"]);
                        }
                        break;
                    case "CRDO":
                        $cuota_inicial_crdo = $this->getPaymentsByType($values, $item);
                        if ($cuota_inicial_crdo[0]["Message"] == "Cuenta inhabilitada para el pago no presencial.") {
                            throw new Exception($cuota_inicial_crdo[0]["Message"]);
                        }
                        break;
                }
            }
            $data=array("sam"=>$sam,
                        "tarjeta"=>$tarjeta,
                        "credito"=>$credito,
                        "acuerdo"=>$acuerdo,
                        "cuota_inicial_cr"=>$cuota_inicial_cr,
                        "cuota_inicial_crdo"=>$cuota_inicial_crdo,
                        "mora"=>$mora);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function getClubRedondoSocioByDni($values){
        try {
            $sql="SELECT ISNULL(S.IdSocio,0) AS NroSocio,ISNULL(S.Estado,'') AS Estado,p.cuit,p.nombre,p.apellido FROM dbo.Persona P INNER JOIN socio S ON P.IdPersona=S.IdPersona WHERE S.Estado NOT IN ('ANU') AND P.NroDocumento=".$values["NroDocumento"]." ORDER BY S.Idsocio DESC"; 
            $result=$this->getRecordsAdHoc($sql);
			$result["code"]=2000;
			$result["status"]="OK";
            return $result;
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function referirProspecto($values){
        try {
            if(!isset($values["IdSocio"])){$values["IdSocio"]=0;}
            if(!isset($values["DocumentoSocio"])){$values["DocumentoSocio"]=0;}
            $socio=$this->getClubRedondoSocioByDni(array("NroDocumento"=>$values["DocumentoSocio"]));
            if ($socio[0]!=null){
                $sql="EXEC DBClub.dbo.NS_ReferirSocio @IdSocio=".$values["IdSocio"].", @Documento=".$values["DNI"].", @Sexo='".$values["Sexo"]."'";
                $result=$this->getRecordsAdHoc($sql);
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function registrarCobranza($values){
        try {
            if(!isset($values["servicioPago"])){$values["servicioPago"]="COIN";}
            $result=null;
            if(!isset($values["posProceso"])){$values["posProceso"]="telemedicina";}
            switch($values["posProceso"]){
               case "telemedicina":
                  $params=array(
                      "id"=>-1,
				      "servicioPago"=>$values["servicioPago"],
				      "dni"=>$values["NroDocumento"],
                      "TipoUsuario"=>$values["TipoUsuario"],
                      "Identificacion"=>(string)$values["Identificacion"],
                      "origen"=>(int)$values["origen"],
                      "Tipo"=>"TM",
                      "MedioPago"=>$values["MedioPago"],
                      "Importe"=>(float)$values["Importe"],
                      "Resultado"=>$values["Resultado"],
                      "Transaccion"=>$values["Transaccion"],
                      "Respuesta"=>urlencode($values["Respuesta"]),
                  );
                  $result=$this->setItemPago($params);
				  $importe=(float)$values["Importe"];
				  $params=array("Tipo"=>"FV","Letra"=>"B","Prefijo"=>3,"NroDocumento"=>(int)$values["NroDocumento"],"Importe"=>$importe,"Concepto"=>"TM");
				  $this->FacturarMediya($params);
                  break;
               case "pagosonline":
                  $tarjeta=0;
                  $tarjetaId=null;
                  $credito=0;
                  $creditoId=null;
                  $acuerdo=0;
                  $acuerdoId=null;
                  foreach($values["itemsPagos"] as $item){
				     $importe=(float)$item["Importe"];
					 /*1 set itempago por cada cosa!!!*/
					 $params=array(
                          "id"=>$values["id"],
					      "servicioPago"=>$values["servicioPago"],
						  "dni"=>$values["NroDocumento"],
						  "TipoUsuario"=>$values["TipoUsuario"],
						  "Identificacion"=>(string)$item["Identificacion"],
						  "origen"=>(int)$values["origen"],
						  "Tipo"=>$item["Tipo"],
						  "MedioPago"=>$values["MedioPago"],
						  "Importe"=>$importe,
						  "Resultado"=>$values["Resultado"],
						  "Transaccion"=>$values["Transaccion"],
						  "Respuesta"=>urlencode($values["Respuesta"]),
                          "TransaccionOrigen"=>$values["TransaccionOrigen"]
					 );
					 if ($importe!=0){$result=$this->setItemPago($params);}   
                  }
                  break;
            }
		    $result["now"]=$this->now;
            return $result;
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function FacturarMediya($values){
        try {
            $socio=$this->getClubRedondoSocioByDni(array("NroDocumento"=>$values["NroDocumento"]));
            $sql="EXEC DBClub.dbo.NS_ComprobanteMediya_I ";
		    $sql.=" @Tipo='".$values["Tipo"]."'";
		    $sql.=", @Prefijo=".$values["Prefijo"];
		    $sql.=", @NroComprobante=-1";
		    $sql.=", @Letra='".$values["Letra"]."'";
		    $sql.=", @Concepto='".$values["Concepto"]."'";
		    $sql.=", @Importe=".$values["Importe"];
		    $sql.=", @NroDocumento='".$socio[0]["cuil"]."'";
		    $sql.=", @Identificacion=".$socio[0]["NroSocio"];
		    $sql.=", @Nombre='".$socio[0]["nombre"]." ".$socio[0]["apellido"]."'";
            $result=$this->getRecordsAdHoc($sql);
			/*Genera factura AFIP!*/
	        $this->AFIPalameEsta();
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result["FacturarMediyaResult"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function ComprobantesPorSocio($values){
        try {
            $sql="SELECT * FROM DBClub.dbo.ComprobanteMediya WHERE NroComprobante > 0 AND Identificacion=".$values["id_club_redondo"]." ORDER BY Id DESC";
            $result=$this->getRecordsAdHoc($sql);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result["ComprobantesPorSocioResult"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
	public function generateReceipt($params){
	    $filename="Comprobante de pago ".opensslRandom(16).".pdf";
		$html = "<div style='max-width:540px;width:100%;font-family:arial;border:solid 2px black;padding:5px;' class='data-pdf'>";
		$html .= "<input type='hidden' id='code' name='code' value='".$params["dni"]."' class='code dbaseComprobante'/>";
		$html .= "<input type='hidden' id='description' name='description' value='comprobanteCOIN' class='description dbaseComprobante'/>";
		$html .= "<input type='hidden' id='base64' name='base64' value='' class='base64 dbaseComprobante'/>";
		$html .= "<input type='hidden' id='filename' name='filename' value='".$filename."' class='filename dbaseComprobante'/>";
		$html .= "<input type='hidden' id='extension' name='extension' value='pdf' class='extension dbaseComprobante'/>";
		$html .= "      <table style='width:100%;font-family:calibri;padding:5px;'>";
		$html .= "         <tr>";
		$html .= "            <td align='center' valign='middle' style='border:solid 1px black;background-color:rgb(230,0,150);'><span style='font-weight:bold;font-size:40px;color:yellow;'>CREDIPAZ</span></td>";
		$html .= "         </tr>";
		$html .= "         <tr>";
		$html .= "            <td align='center' valign='middle' style='border-bottom:solid 1px silver;'><span style='font-weight:bold;font-size:24px;'>Comprobante de pago</span></td>";
		$html .= "         </tr>";
		switch ($params["Tipo"]) {
			case "TAR":
				$html .= "         <tr>";
				$html .= "            <td align='center' valign='middle' style='font-size:24px;'>TARJETA CABAL CREDIPAZ</td>";
				$html .= "         </tr>";
				break;
			case "CRE":
				$html .= "         <tr>";
				$html .= "            <td align='center' valign='middle' style='font-size:24px;'>CRÉDITO</td>";
				$html .= "         </tr>";
				break;
			case "ACU":
				$html .= "         <tr>";
				$html .= "            <td align='center' valign='middle' style='font-size:24px;'>ACUERDO DE PAGO</td>";
				$html .= "         </tr>";
				break;
		}
		$html .= "         <tr>";
		$html .= "            <td align='center' valign='middle' style='font-weight:bold;font-size:24px;'>$ ".$params["Importe"]."</td>";
		$html .= "         </tr>";
		$html .= "         <tr>";
		$html .= "            <td align='center' valign='middle'>";
		$html .= "               <table align='center' style='width:80%;padding:5px;' cellspacing='0'>";
		$html .= "                  <tr>";
		$html .= "                     <td align='left' valign='top' style='border-top:solid 1px black;'>Identificación</td>";
		$html .= "                     <td align='right' valign='top' style='border-top:solid 1px black;'>".$params["Identificacion"]."</td>";
		$html .= "                  </tr>";
		$html .= "                  <tr>";
		$html .= "                     <td align='left' valign='top' style='border-top:solid 1px black;'>Medio de pago</td>";
		$html .= "                     <td align='right' valign='top' style='border-top:solid 1px black;'>" .$params["MedioPago"]. "</td>";
		$html .= "                  </tr>";
		$html .= "                  <tr>";
		$html .= "                     <td align='left' valign='top' style='border-top:solid 1px black;'>Fecha de pago</td>";
		$html .= "                     <td align='right' valign='top' style='border-top:solid 1px black;'>".$this->now."</td>";
		$html .= "                  </tr>";
		$html .= "                  <tr>";
		$html .= "                     <td align='left' valign='top' style='border-top:solid 1px black;border-bottom:solid 1px black;'>Número de pago</td>";
		$html .= "                     <td align='right' valign='top' style='border-top:solid 1px black;border-bottom:solid 1px black;'>".$params["Transaccion"]."</td>";
		$html .= "                  </tr>";
		$html .= "               </table>";
		$html .= "            </td>";
		$html .= "         </tr>";
		$html .= "      </table>";
		$html .= "      <table align='center' style='width:80%;font-family:calibri;padding:5px;'>";
		$html .= "         <tr><td align='center' valign='middle'><b>CREDIPAZ S.A.</b></td></tr>";
		$html .= "         <tr><td align='center' valign='middle' style='border-top:solid 1px black;border-bottom:solid 1px black;'>Av.Pte.Perón 10175, Villa Gbor.Udaondo</br>Ituzaingó, Buenos Aires</td></tr>";
		$html .= "         <tr><td align='center' valign='middle' style='border-top:solid 1px black;border-bottom:solid 1px black;'>CUIT 30-54457180-6<br/>IVA Resp.inscripto a consumidor final</td></tr>";
		$html .= "         <tr><td align='center' valign='middle'>Orientación al consumidor Prov. de Bs.As.<br/>0800-222-9042</td></tr>";
		$html .= "      </table>";
		$html .= "   </div>";
        $FILES_BASE64=$this->createModel(MOD_BACKEND,"files_base64","files_base64");
		$data=array(
			"id"=>0,
			'code' => $params["dni"],
			'description' => "Comprobante de pago",
			'created' => $this->now,
			'verified' => $this->now,
			'offline' => null,
			'fum' => $this->now,
			'base64'=>base64_encode($html),
			'filename'=>$filename,
			'extension' => "pdf",
		);
		return $FILES_BASE64->save($data);
	}
    private function getPaymentsByType($values, $type)
    {
        $sql = "EXEC DBCentral.dbo.NS_Get_DeudaAPagar ";
        $sql .= " @sTipo='D'";
        $sql .= ", @sValor=" . $values["dni"];
        $sql .= ", @sTipodoc='DNI'";
        $sql .= ", @TipoDeuda='" . $type . "'";
        $result = $this->getRecordsAdHoc($sql);
        return $result;
    }
    private function setItemPago($params)
    {
        try {
            if (!isset($params["id"])) {
                $params["id"] = 0;
            }
            $params["Importe"] = (float) $params["Importe"];
            $params["type_rel"] = $params["Tipo"];
            $params["identify_rel"] = $params["Identificacion"];
            $params["amount_rel"] = $params["Importe"];
            $params["Id_mod_payments_transactions"] = (int) $params["id"];
            logGeneralCustom($this, $params, "Payments::setItemPago", "idpt: " . $params["id"] . " Servicio:" . $params["servicioPago"] . " Tipo:" . $params["Tipo"] . " Identificacion:" . $params["Identificacion"] . " Importe:" . $params["Importe"] . " Resultado:" . $params["Resultado"] . " Transaccion:" . $params["Transaccion"] . " Respuesta:" . $params["Respuesta"]);
            $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $result = $NETCORECPFINANCIAL->ProcesarItemPago($params);
            logGeneralCustom($this, $params, "Payments::setItemPagoResponse", $forlog);
            /*Saving receipt for dni + payment*/
            $this->generateReceipt($params);
            return $result;
        } catch (Exception $e) {
            logGeneralCustom($this, $params, "Payments::setItemPagoResponseError", $e);
        }
    }
}
