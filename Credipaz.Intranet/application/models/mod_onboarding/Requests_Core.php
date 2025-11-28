<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Requests_Core extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function form($values){
        try {
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".$values["model"]));
            $html=$this->load->view(MOD_ONBOARDING."/requests_core/form",$data,true);
            
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
	public function saveSpecial($values,$fields=null){
        try {
		    $this->table="requests";
		    $this->view="requests";
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
			$record=$this->get(array("fields"=>"id_type_status","where"=>("id=".$id)));
			$id_type_status_previous=(int)$record["data"][0]["id_type_status"];
			$id_type_status=(int)$values["id_type_status"];
			$fields=array(
			    "id_type_status"=>$id_type_status,
			    "Documento"=>$values["Documento"],
			    "Nombre"=>$values["Nombre"],
			    "Apellido"=>$values["Apellido"],
                'offline' => null,
                'fum' => $this->now,
				"Documento"=> $values["Documento"],
				"Sexo"=> $values["Sexo"],
				"Email"=> $values["Email"],
				"prefijoTelefono"=> $values["prefijoTelefono"],
				"Telefono"=> $values["Telefono"],
				"prefijoTelefonoAlt"=> $values["prefijoTelefonoAlt"],
				"TelefonoAlt"=> $values["TelefonoAlt"],
				"Nacionalidad"=> secureEmptyNull($values,"Nacionalidad"),
				"FechaNacimiento"=> $values["FechaNacimiento"],
				"EstadoCivil"=> secureEmptyNull($values,"EstadoCivil"),
				"Calle"=> $values["Calle"],
				"Numero"=> $values["Numero"],
				"Piso"=> $values["Piso"],
				"Departamento"=> $values["Departamento"],
				"CodigoPostal"=> $values["CodigoPostal"],
				"EntreCalles"=> $values["EntreCalles"],
				"Barrio"=> $values["Barrio"],
				"Localidad"=> $values["Localidad"],
				"Provincia"=> $values["Provincia"],
				"Vivienda"=> secureEmptyNull($values,"Vivienda"),
				"iva"=> secureEmptyNull($values,"iva"),
				"cuil"=> $values["cuil"],
				"Ocupacion"=> secureEmptyNull($values,"Ocupacion"),
				"RazonSocial"=> $values["RazonSocial"],
				"cuit"=> $values["cuit"],
				"Seccion"=> $values["Seccion"],
				"Legajo"=> $values["Legajo"],
				"Cargo"=> $values["Cargo"],
				"Rubro"=> secureEmptyNull($values,"Rubro"),
				"IngresoMensual"=> $values["IngresoMensual"],
				"FechaIngreso"=> $values["FechaIngreso"],
				"Antiguedad"=> $values["Antiguedad"],
				"CalleEmpresa"=> $values["CalleEmpresa"],
				"NumeroEmpresa"=> $values["NumeroEmpresa"],
				"PisoEmpresa"=> $values["PisoEmpresa"],
				"DepartamentoEmpresa"=> $values["DepartamentoEmpresa"],
				"CodigoPostalEmpresa"=> $values["CodigoPostalEmpresa"],
				"EntreCallesEmpresa"=> $values["EntreCallesEmpresa"],
				"LocalidadEmpresa"=> $values["LocalidadEmpresa"],
				"ProvinciaEmpresa"=> $values["ProvinciaEmpresa"],
				"prefijoTelefonoEmpresa"=> $values["prefijoTelefonoEmpresa"],
				"TelefonoEmpresa"=> $values["TelefonoEmpresa"],
				"prefijoTelefonoAltEmpresa"=> $values["prefijoTelefonoAltEmpresa"],
				"TelefonoAltEmpresa"=> $values["TelefonoAltEmpresa"],
				"id_type_reject"=> $values["id_type_reject"],
				"note_reject"=> $values["note_reject"],
			);
			$fields["img_comprobante_servicio"]=$values["img_comprobante_servicio"];
			$fields["img_comprobante_ingreso"]=$values["img_comprobante_ingreso"];
			$fields["img_dni_frente"]=$values["img_dni_frente"];
			$fields["img_dni_dorso"]=$values["img_dni_dorso"];

			$ret=parent::save($values,$fields);
			if ($id_type_status_previous!=3 and $id_type_status==3){
				$record=$this->get(array("where"=>("id=".$id)));
	            $EMAIL=$this->createModel(MOD_EMAIL,"Email","Email");
				$link=("https://onboarding.credipaz.com?verificated=".$id);
				if ((int)$record["data"][0]["id_user"]!=0) {$link=("https://totem.credipaz.com?verificated=".$id);}

				$data=array(
					"subject"=>"Resolución de operación - CREDIPAZ",
					"link"=>$link,
					"nombre"=>$record["data"][0]["Nombre"],
					"apellido"=>$record["data"][0]["Apellido"],
					"producto"=>$record["data"][0]["description"],
				);
				$params=array(
					"from"=>"intranet@mediya.com.ar",
					"alias_from"=>lang('msg_internal_alerts'),
					"email"=>$record["data"][0]["Email"],
					"subject"=>"Resolución de operación - CREDIPAZ",
					"body"=>$this->load->view(MOD_EMAIL.'/templates/alertCloseOnBoarding',$data, true),
				);
				$EMAIL->directEmail($params);
			}
			return $ret;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
	public function save($values,$fields=null){
        try {
		    $this->table="requests";
		    $this->view="requests";
            if (!isset($values["id"])){$values["id"]=0;}
            if (!isset($values["id_user_generator"])){$values["id_user_generator"]=0;}
            if (!isset($values["id_type_status"])){$values["id_type_status"]=null;}
            $id=(int)$values["id"];
			$id_type_request=secureEmptyNull($values,"Tipo");

			switch((int)$id_type_request){
				case 1:
					$description="Solicitud de tu crédito";
					break;
				case 2:
					$description="Solicitud de tarjeta de crédito";
					break;
				case 3:
					$description="Solicitud de Mediya";
					break;
				case 4:
 				    $description="Renovación de crédito";
					break;
				case 17:
					$description="Venta MIL";
					break;
			}

			$id_type_status=$values["id_type_status"];
			$bNew=true;

            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'code' =>opensslRandom(8),
                        'description' => 'Solicitud PREFLIGHT',
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => $this->now,
                        'fum' => $this->now,
						'controlPoint'=>"ALTA",
						'id_type_status'=>1,
						"Apellido"=> $values["Apellido"],
						"Nombre"=> $values["Nombre"],
						"Tipo"=> $id_type_request,
						"Documento"=> $values["Documento"],
						"Sexo"=> $values["Sexo"],
						"Email"=> $values["Email"],
						"prefijoTelefono"=> $values["prefijoTelefono"],
						"Telefono"=> $values["Telefono"],
						"Ocupacion"=> secureEmptyNull($values,"Ocupacion"),
						"id_user"=> (int)$values["id_user_generator"],
						"idTransaccion"=> secureEmptyNull($values,"idTransaccion"),
						"id_type_request"=>$id_type_request
                    );
                }
            } else {
				$bNew=false;
				$record=$this->get(array("fields"=>"id_type_status,controlPoint,decision,idtransaccion","where"=>("id=".$id)));
				$id_type_status_previous=(int)$record["data"][0]["id_type_status"];
				$controlPoint_previous=$record["data"][0]["controlPoint"];
				$decision_previous=(string)$record["data"][0]["decision"];
				$id_transaccion=(int)$record["data"][0]["idtransaccion"];

				if ($controlPoint_previous=="EMITIDO"){throw new Exception(lang("error_5216"),5216);}
                if($fields==null) {
				    $values["Antiguedad"]=0;
                    $fields = array(
                        'offline' => null,
                        'fum' => $this->now,
						"NroSolicitud"=> $values["NroSolicitud"],
						"controlPoint"=> $values["controlPoint"],
						"permiteNuevo"=> $values["permiteNuevo"],
						"permiteRenovacion"=> $values["permiteRenovacion"],
						"procesandoNuevo"=> $values["procesandoNuevo"],
						"procesandoRenovacion"=> $values["procesandoRenovacion"],
						"min"=> $values["min"],
						"max"=> $values["max"],
						"default"=> $values["default"],
						"Capital"=> $values["Capital"],
						"importe"=> $values["importe"],
						"cuotas"=> $values["cuotas"],
						"tasa"=> $values["tasa"],
						"idplan"=> $values["idplan"],
						"idcomercio"=> $values["idcomercio"],
						"TNA"=> $values["TNA"],
						"TEA"=> $values["TEA"],
						"CFTNA"=> $values["CFTNA"],
						"CFTEA"=> $values["CFTEA"],
						"Email"=> $values["Email"],
						"prefijoTelefono"=> $values["prefijoTelefono"],
						"Telefono"=> $values["Telefono"],
						"prefijoTelefonoAlt"=> $values["prefijoTelefonoAlt"],
						"TelefonoAlt"=> $values["TelefonoAlt"],
						"Nacionalidad"=> secureEmptyNull($values,"Nacionalidad"),
						"FechaNacimiento"=> $values["FechaNacimiento"],
						"EstadoCivil"=> secureEmptyNull($values,"EstadoCivil"),
						"Calle"=> $values["Calle"],
						"Numero"=> $values["Numero"],
						"Piso"=> $values["Piso"],
						"Departamento"=> $values["Departamento"],
						"CodigoPostal"=> $values["CodigoPostal"],
						"EntreCalles"=> $values["EntreCalles"],
						"Barrio"=> $values["Barrio"],
						"Localidad"=> $values["Localidad"],
						"Provincia"=> $values["Provincia"],
						"Vivienda"=> secureEmptyNull($values,"Vivienda"),
						"iva"=> secureEmptyNull($values,"iva"),
						"cuil"=> $values["cuil"],
						"Ocupacion"=> secureEmptyNull($values,"Ocupacion"),
						"RazonSocial"=> $values["RazonSocial"],
						"cuit"=> $values["cuit"],
						"Seccion"=> $values["Seccion"],
						"Legajo"=> $values["Legajo"],
						"Cargo"=> $values["Cargo"],
						"Rubro"=> secureEmptyNull($values,"Rubro"),
						"IngresoMensual"=> $values["IngresoMensual"],
						"FechaIngreso"=> $values["FechaIngreso"],
						"Antiguedad"=> $values["Antiguedad"],
						"CalleEmpresa"=> $values["CalleEmpresa"],
						"NumeroEmpresa"=> $values["NumeroEmpresa"],
						"PisoEmpresa"=> $values["PisoEmpresa"],
						"DepartamentoEmpresa"=> $values["DepartamentoEmpresa"],
						"CodigoPostalEmpresa"=> $values["CodigoPostalEmpresa"],
						"EntreCallesEmpresa"=> $values["EntreCallesEmpresa"],
						"LocalidadEmpresa"=> $values["LocalidadEmpresa"],
						"ProvinciaEmpresa"=> $values["ProvinciaEmpresa"],
						"prefijoTelefonoEmpresa"=> $values["prefijoTelefonoEmpresa"],
						"TelefonoEmpresa"=> $values["TelefonoEmpresa"],
						"prefijoTelefonoAltEmpresa"=> $values["prefijoTelefonoAltEmpresa"],
						"TelefonoAltEmpresa"=> $values["TelefonoAltEmpresa"]
					);
					if (is_string($values["raw_verify"])){$fields["raw_verify"]=$values["raw_verify"];}
					if ($values["pdf_solicitud"]!=""){$fields["pdf_solicitud"]=$values["pdf_solicitud"];}
					if ($values["img_additional"]!=""){$fields["img_additional"]=$values["img_additional"];}

                    /**
                     */
                    $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
                    if ($values["img_foto_cara"] != "") {
                        $params = array("idTransaccion"=>$id_transaccion,"idRequest" => $id, "base64" => $values["img_foto_cara"], "Producto" => "8", "Formato" => "HTML");
                        $NETCORECPFINANCIAL->ConsultaValidarImagenManual($params);
                    }
                    if ($values["img_comprobante_servicio"] != "") {
                        $params = array("idTransaccion" => $id_transaccion,"idRequest" => $id, "base64" => $values["img_comprobante_servicio"], "Producto" => "10", "Formato" => "HTML");
                        $NETCORECPFINANCIAL->ConsultaValidarImagenManual($params);
                    }
                    if ($values["img_comprobante_ingreso"] != "") {
                        $params = array("idTransaccion" => $id_transaccion,"idRequest" => $id, "base64" => $values["img_comprobante_ingreso"], "Producto" => "9", "Formato" => "HTML");
                        $NETCORECPFINANCIAL->ConsultaValidarImagenManual($params);
                    }
                    if ($values["img_dni_frente"] != "") {
                        $params = array("idTransaccion" => $id_transaccion,"idRequest" => $id, "base64" => $values["img_dni_frente"], "Producto" => "7", "Formato" => "HTML");
                        $NETCORECPFINANCIAL->ConsultaValidarImagenManual($params);
                    }
                    if ($values["img_dni_dorso"] != "") {
                        $params = array("idTransaccion" => $id_transaccion,"idRequest" => $id, "base64" => $values["img_dni_dorso"], "Producto" => "-7", "Formato" => "HTML");
                        $NETCORECPFINANCIAL->ConsultaValidarImagenManual($params);
                    }
                }
            }
			if ($id_type_status!=null){$fields["id_type_status"]=$id_type_status;}
			switch(strtoupper($decision_previous)){
				case "HIT":
				case "NO_HIT":
				case "ERR_RNP":
				//case "VIGENTE":
				case "NO%20VIGENTE":
				case "NO VIGENTE":
					unset($fields["decision"]);
					break;
			}
			if (isset($fields["decision"]) and $id_type_status_previous==1){$fields["id_type_status"]=2;}
			$ret=parent::save(array("id"=>$id),$fields);

			if (isset($fields["decision"])) {
			    try {
			       $vals=array("id"=>$id,"type_rel"=>"id","identify_rel"=>$fields["decision"]);
		           //logGeneralCustom($this,$vals,"Onboarding::Save","decision: ".$fields["decision"]);
				} catch(Exception $err){}
	        }
			return $ret;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
	public function onboardingFirstVerification($values){
		try {
			$bCredito=((int)$values["Tipo"]==1);
		    $this->table="requests";
		    $this->view="requests";
			//-----------------------------------------------------------------
			//Forzando sucursal para testeo cuando se esta en localhost!!!!!!!!
			//-----------------------------------------------------------------
			//if (strpos(getServer(),"localhost")!==false) {$values["id_user_generator"]=208353;}
			//-----------------------------------------------------------------

		   $fields = array();
		   /*
			   Valores a retorna, recibidos en llamada original
		   */
		    $fields["id"]=0;
			$fields["Apellido"]=$values["Apellido0"];
			$fields["Nombre"]=$values["Nombre0"];
			$fields["Tipo"]=$values["Tipo0"];//1, // 1 si es alta de crédito o 17 si es Venta MIL $values["Tipo0"];
			$fields["Documento"]=$values["Documento0"];
			$fields["Sexo"]=$values["Sexo0"];
			$fields["Email"]=$values["Email0"];
			$fields["prefijoTelefono"]=$values["prefijoTelefono0"];
			$fields["Telefono"]=$values["Telefono0"];
			$fields["Ocupacion"]=$values["Ocupacion0"];

			$defaultSucursal=100;
            if (!isset($values["idSolicitudOriginal"])) {$values["idSolicitudOriginal"] = 0;}
            if(!isset($values["id_sucursal"])){$values["id_sucursal"]=$defaultSucursal;}
			if($values["id_sucursal"]==""){$values["id_sucursal"]=$defaultSucursal;}

			if(!isset($values["username"])){$values["username"]="";}
			$username=$values["username"];
			$USERS=$this->createModel(MOD_BACKEND,"Users","Users");
			if ($values["username"]!=""){
				$record=$USERS->get(array("where"=>"username LIKE '%@".$values["username"]."%'"));
				if((int)$record["totalrecords"]!=0){
					$values["id_user_generator"]=$record["data"][0]["id"];
					if ((int)$record["data"][0]["id_sucursal"]!=0){
						$values["id_sucursal"]=(int)$record["data"][0]["id_sucursal"];
					}
				}
		    }
			$fields["id_user"]=$values["id_user_generator"];
			$saved=$this->save($fields);
			$id=$saved["data"]["id"];
            $values["idRequest"]=$id;

			//$fields["id"]=$id;
			unset($fields["id"]);
			/*
			ID de la solicitud generada
			*/
			$fields["NroSolicitud"]=$id;
			$fields["controlPoint"]="VERIFICADO";

			/*
			   Llamada al WS de verificacion inicial de CP
			*/
			$NETCORECPFINANCIAL=$this->createModel(MOD_EXTERNAL,"NetCoreCPFinancial","NetCoreCPFinancial");
			$financial=$NETCORECPFINANCIAL->OnBoard($values);

			/*
			   Se debe determinar si se sigue o no!
			*/
			$rechazado=false;
			if (strpos((string)$financial,"internal error")!==false) {$rechazado=true;}
			if ($rechazado) {throw new Exception(lang("error_10000"),10000);}
			if($financial["status"]=="ERROR"){throw new Exception($financial["message"],$financial["code"]);}

			/*
			Datos fijos vacios al inicio de la solicitud
			*/
			$cliente=json_decode($financial["message"]["0"]["cliente"],true);
			$cliente=$cliente[0];
			$scoring=json_decode($financial["message"]["0"]["scoring"],true);
			$fields["img_foto_cara"]= "";
			$fields["img_comprobante_servicio"]= "";
			$fields["img_comprobante_ingreso"]= "";
			$fields["img_dni_frente"]= "";
			$fields["img_dni_dorso"]= "";
			$fields["pdf_solicitud"]= "";
			$fields["procesandoNuevo"]=0;
			$fields["procesandoRenovacion"]=0;
			$fields["importe"]=0;
			$fields["cuotas"]=0;
			$fields["tasa"]=0;
			$fields["idplan"]=null;
			$fields["idcomercio"]=null;

			/*
			   Datos que debe devolver el WS de CP!
			   Datos financieros, relacionados a la pre validacion de los productos a requerir
			*/
			$min=10000;

			$capital=(string)$scoring[0]["Monto a Ofrecer"];
			$part=explode(".",$capital);
			$capital=$part[0];

			if((float)$capital<$min){$min=$capital;}

			$fields["permiteNuevo"]=1;
			$fields["permiteRenovacion"]=0;
			$fields["min"]= $min;
			$fields["max"]= $capital;
			$fields["default"]= $capital;
			$fields["Capital"]= $capital;
			$fields["raw_verify"]= json_encode($financial["message"]);
			
			$fields["TNA"]= 100.25; //Agregar para recalculo de cuotas
			$fields["TEA"]= 200.50; //Agregar para recalculo de cuotas
			$fields["CFTNA"]= 175.75; //Agregar para recalculo de cuotas
			$fields["CFTEA"]= 315.75; //Agregar para recalculo de cuotas
			
			/*
			   Datos que debe devolver el WS de CP!
			   Datos del cliente, nuevo o ya existente
			*/
			$dFechaNac=$cliente["dFechaNac"];
			$LAB_dFechaIngreso=$cliente["LAB_dFechaIngreso"];

			$fields["prefijoTelefonoAlt"]=trim($cliente["sDomiTETelediscado"]);
			$fields["TelefonoAlt"]=trim($cliente["lDomiTEHab"]);
			$fields["Nacionalidad"]=trim($cliente["sLKNacionalidad"]);
			if($dFechaNac!="") {$fields["FechaNacimiento"]=trim(explode("T",$dFechaNac)[0]);}
			$fields["EstadoCivil"]= trim($cliente["sLKEstadoCivil"]);
			$fields["Calle"]=trim($cliente["sDomiCalle"]);
			$fields["Numero"]=trim($cliente["sDomiNro"]);
			$fields["Piso"]=trim($cliente["sDomiPisoDpto"]);
			$fields["Departamento"]=trim($cliente["sDomiPisoDpto"]);
			$fields["CodigoPostal"]=trim($cliente["sDomiCP"]);
			$fields["EntreCalles"]=trim($cliente["sDomiEntre"]);
			$fields["Barrio"]=trim($cliente["sDomiBarrio"]);
			$fields["Localidad"]=trim($cliente["sLKDomiLocalidad"]);
			$fields["Provincia"]=trim($cliente["sDomiPcia"]);
			$fields["Vivienda"]=trim($cliente["sLKTipoVivienda"]);
			$fields["iva"]= trim($cliente["sLKCondIVA"]);
			$fields["cuil"]= trim($cliente["nCUIL"]);
			
			$fields["RazonSocial"]= trim($cliente["LAB_sRazonSocial"]);
			$fields["cuit"]= trim($cliente["LAB_sCUIT"]);
			$fields["Seccion"]= trim($cliente["LAB_sSeccion"]);
			$fields["Legajo"]= trim($cliente["LAB_sLegajo"]);
			$fields["Cargo"]= trim($cliente["LAB_sCargo"]);
			$fields["Rubro"]=trim($cliente["LAB_sLKRubroLaboral"]);
			$fields["IngresoMensual"]=trim($cliente["LAB_nIngresoMensual"]);
			if($LAB_dFechaIngreso!="") {$fields["FechaIngreso"]=trim(explode("T",$LAB_dFechaIngreso)[0]);}

			$fields["Antiguedad"]= trim($cliente["LAB_sAntiguedad"]);
			$fields["CalleEmpresa"]= trim($cliente["LAB_sDomiCalle"]);
			$fields["NumeroEmpresa"]= trim($cliente["LAB_sDomiNro"]);
			$fields["PisoEmpresa"]= trim($cliente["LAB_sDomiPisoDpto"]);
			$fields["DepartamentoEmpresa"]= trim($cliente["LAB_sDomiPisoDpto"]);
			$fields["CodigoPostalEmpresa"]= trim($cliente["LAB_sDomiCP"]);
			$fields["EntreCallesEmpresa"]= trim($cliente["LAB_sDomiEntre"]);
			$fields["LocalidadEmpresa"]= trim($cliente["LAB_sLKDomiLocalidad"]);
			$fields["ProvinciaEmpresa"]= trim($cliente["LAB_sDomiPcia"]);
			$fields["prefijoTelefonoEmpresa"]=  trim($cliente["LAB_sDomiTETelediscado"]);
			$fields["TelefonoEmpresa"]=  trim($cliente["LAB_sDomiTE"]);
			$fields["prefijoTelefonoAltEmpresa"]=  trim($cliente["LAB_sDomiTETelediscado"]);
			$fields["TelefonoAltEmpresa"]=  trim($cliente["LAB_sDomiTE"]);
			$fields["idTransaccion"]=$financial["message"]["0"]["idTransaccion"];
			$saved=$this->save(array("id"=>$id),$fields);
			$id=$saved["data"]["id"];
			$record=$this->get(array("where"=>("id=".$id)));
			//if($cliente["codigo"]=="401"){throw new Exception($cliente["mensaje"],10004);}
			if ($bCredito) {
				try {
					$PUSH_OUT=$this->createModel(MOD_PUSH,"Push_out","Push_out");
					$params=array(
						"id_group"=>1094, // OperadoresOnboarding
						"subject"=>lang('msg_direct_onboarding_push_alert'),
						"body"=>"Se ha recibido una solicitud de crédito vía Onboarding"
					);
					$PUSH_OUT->sendToGroup($params);
				} catch(Exception $err) {}
			}
            return array(
                "code"=>"2000",
                "status"=>"OK",
				"data"=>$record["data"][0],
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch (Exception $e){
			$ret=logError($e,__METHOD__ );
            return $ret;
        }
	}
    public function onboardingSaveRequest($values){
		try {	
		    $this->table="requests";
		    $this->view="requests";
			$this->save($values,null);
			$ret=array("tokenId"=>null);
			switch($values["controlPoint"]){
                case "DATOS LABORALES":
                    $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
                    $ret = $NETCORECPFINANCIAL->ConsultaValidarDatosLaborales($values);
                    break;
			    case "VALIDACION DNI":
			    case "VALIDACION ROSTRO":
				  /*Hacer las llamadas de Idemia para generar el "tokenId" que va a usarse como seckey en el post al sitio externo!*/
                  $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
                  $ret=$NETCORECPFINANCIAL->IdemiaAuth($values);
			      break;
				default:
				  break;
			}
			if ($ret["tokenId"]==null){$ret["message"]="No se ha podido acceder a la plataforma de verificación de identidad";}
			return $ret;
        }
        catch (Exception $e){
			return logError($e,__METHOD__ );
        }
	}
	public function onboardingGetRequest($values){
		try {
		    $this->table="requests";
		    $this->view="vw_requests";
		    if(!isset($values["id"])){$values["id"]=0;}
		    if(!isset($values["idtx"])){$values["idtx"]=0;}
			if($values["id"]==""){$values["id"]=0;}
		    $id=(int)$values["id"];

			$record=$this->get(array("fields"=>"idtx,Sexo,Documento","where"=>("id=".$id)));
            $sexoPrevio=$record["data"][0]["Sexo"];
			$documentoPrevio=$record["data"][0]["Documento"];
			$secondIdtx=($record["data"][0]["idtx"]!="" and $record["data"][0]["idtx"]!="0" );

            $end=$values["end"];
			if (isset($values["decision"]) and $id!=0) {
				$fields=array('externalid'=>$values["externalid"],'decision'=>$values["decision"]);
                $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
				/*Genera informes de vida y dni*/
                if($secondIdtx){
				    if ($values["idtx"]!="0" and $values["idtx"]!="") {
                        $params = array("idRequest" => $id,"idtx" => $values["idtx"],"Producto" => "8","Formato" => "HTML");
                        $NETCORECPFINANCIAL->ConsultaValidarVida($params);
					}
				} else {
					if ($values["idtx"]!="" and $values["idtx"]!="0"){
                        $params = array("idRequest" => $id,"idtx" => $values["idtx"],"Producto" => "7","Formato" => "HTML");
						$NETCORECPFINANCIAL->ConsultaValidarDNI($params);
					}
				}
			}
			$record=$this->get(array("where"=>("id=".$id)));

            if ($end=="AK"){
				/*Se debe setear aqui el codigo SMS para averificar el requests.id*/
				$record["data"][0]["codigo_sms_a_verificar"]=mt_rand(10000,99999);//"11111";
			}

            return array(
                "code"=>"2000",
                "status"=>"OK",
				"data"=>$record["data"][0],
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
	}
    public function onboardingFinalRequest($values){
		try {
            /*
             * Control de variables y acceso a registro para datos de emisión
             * */
            if (!isset($values["img_additional"])) {$values["img_additional"] = "";}
            if (!isset($values["pdf_solicitud"])) {$values["pdf_solicitud"] = "";}
            if (!isset($values["lat"])) {$values["lat"] = 0;}
            if (!isset($values["lng"])) {$values["lng"] = 0;}
            if (isset($values["id_forzado"])){$values["id"]=$values["id_forzado"];}
            $id = (int) $values["id"];
	    	/*Levantar datos del request*/
		    $this->table="requests";
			$this->view="vw_requests_full";
			$record=$this->get(array("where"=>("id=".$id)));
            $tipo = (int)$record["data"][0]["Tipo"];
			
            /*
             * Seleccionar accion de emision, segun producto indicado en Tipo
             * */
            $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            /* Llamada a la emision del producto */
			$param_emision = array("lat"=>$values["lat"],"lng"=> $values["lng"],"pdf_solicitud" => $values["pdf_solicitud"], "img_additional" => $values["img_additional"], "IdRequest" => $id, "sAltaUsuario" => "onboarding");
            $financial = $NETCORECPFINANCIAL->EmisionProducto($param_emision);
            $idSolicitudCredito = (int) $financial["message"]["credito"];
            $linkExtract = $financial["message"]["link_extract"];
            $linkCertificate = $financial["message"]["link_certificate"];
            if ($idSolicitudCredito == 0) {throw new Exception(lang("error_10003") . $financial["message"]["mensaje"], 10003);}
            return array(
                "code"=>"2000",
                "status"=>"OK",
				"data"=>$financial,
				"link_extract"=>$linkExtract,
				"link_certificate"=>$linkCertificate,
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
	}
    public function onboardingFinalIdVerification($values)
    {
        try {
            $id = $values["id"];
            $this->view = "vw_requests_full";
            $record = $this->get(array("where" => ("id=" . $id)));
            $dni = $record["data"][0]["Documento"];
            saveFileInCarpetaDigital($this, $dni, "", 0, "rostro.jpg", $record["data"][0]["img_foto_cara"]);
            saveFileInCarpetaDigital($this, $dni, "", 0, "DNI1.jpg", $record["data"][0]["img_dni_frente"]);
            saveFileInCarpetaDigital($this, $dni, "", 0, "DNI2.jpg", $record["data"][0]["img_dni_dorso"]);

            try {
                $sql = (string) "EXEC DBCentral.dbo.NS_Transaccion_Cierre @IdRequest='" . $id . "', @idEstado=7";
                $result = $this->getRecordsAdHoc($sql);
            } catch (Exception $x) {
            }
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
	public function getFieldById($values){
       $this->table="requests";
       $this->view="requests";
	   return $this->get(array("fields"=>$values["image"],"where"=>"id=".$values["id"]));
	}
	private function cUrlRestfulPost($url,$headers, $fields=null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        if (is_array($headers)) {curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);}
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        return $response;
    }
}
