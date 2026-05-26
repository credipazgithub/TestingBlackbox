<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class NetCoreCPFinancial extends MY_Model {
    private $api_server= CPFINANCIALS;
	private $auth_credentials=array("usuario"=>"credipaz","password"=>"CreD1p4z2022");

    public function __construct()
    {
        parent::__construct();
    }

    public function BridgeAuthenticateMobile($values)
    {
        try {
            if (!isset($values["name"])) {$values["name"] = "";}
            if ($values["field"] == "username") {$values["dni"] = explode("@", @$values["value"])[0];}
            $headers = $this->Authenticate();
            $fields=array(
                "Password"=>md5($values["password"]),
                "PasswordPlain"=>$values["password"],
                "Id_app"=>(int) $values["id_app"],
                "Dni"=>$values["dni"],
                "Sex"=>$values["sex"],
                "Usuario"=>$values["email"],
                "Area"=>$values["area"],
                "Telefono"=>$values["phone"],
                "Nombre"=>$values["name"]
            );
            $url = (CPFINANCIALS . "/Intranet/BridgeAuthenticateMobile");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);

            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "verificated" =>  ($result["records"][0]["verified"] != null),
                "token_authentication" => $result["records"][0]["token_authentication"],
                "userdata" => $result["records"][0],
                "clubredondo" => getIdUserMediya($this, $values["dni"])["message"],
                "id" => $result["records"][0]["id"]
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    public function BridgeLookup($params)
    {
        try {
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/Intranet/BridgeLookup");
            $result = $this->callAPI($url, $headers, json_encode($params));
            $result = json_decode($result, true);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result["records"],
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function BridgeAutorizarSocioDS($params)
    {
        try {
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/Mediya/AutorizarSocioDS");
            $result = $this->callAPI($url, $headers, json_encode($params));
            $result = json_decode($result, true);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result["records"],
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function BridgeAutorizarSocioMediya($params)
    {
        try {
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/Mediya/AutorizarSocioMEDIYA");
            $result = $this->callAPI($url, $headers, json_encode($params));
            $result = json_decode($result, true);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result["records"],
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    public function GenerarLinkFarmalink($params)
    {
        try {
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/Farmalink/GenerarLink");
            $result = $this->callAPI($url, $headers, json_encode($params));
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function BridgeDirectMenu($params)
    {
        try {
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/Intranet/BridgeDirectMenu");
            $result = $this->callAPI($url, $headers, json_encode($params));
            $result = json_decode($result, true);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result["records"],
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function HtmlToPdfBase64($plainString)
    {
        try {
            $headers = $this->Authenticate();
            $fields = array("PlainString" => $plainString);
            $url = (CPFINANCIALS . "/Utilidades/HtmlToPdfBase64");
            $result = $this->callAPI($url, $headers, json_encode($fields));

            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $result,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function BridgeDirectCommand($key,$command,$mode)
    {
        try {
            $headers = $this->Authenticate();
            $fields = array("Key" => $key, "Command" => base64_encode($command), "Mode" => $mode);
            $url = (CPFINANCIALS . "/Intranet/BridgeDirectCommand");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $result,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function BridgeDirectEmail($to, $from, $body, $subject)
    {
        try {
            
            $headers = $this->Authenticate();
            $fields = array("To" => base64_encode($to), "From" => base64_encode($from), "Body" => base64_encode($body), "Subject"=> base64_encode($subject));
            $url = (CPFINANCIALS . "/Intranet/BridgeDirectEmail");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $result,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function BridgeDirectAuthenticate($values)
    {
        try {
            
            $headers = $this->Authenticate();
            $fields = array(
                "Usuario" => $values["username"],
                "Password" => md5($values["password"]),
                "PasswordPlain" => base64_encode($values["password"]),
                "ExternalOperator"=> $values["external_operator"],
                "Id_type_user"=> $values["id_type_user"],
                "Version"=> $values["version"],
                "Id_app"=> $values["id_app"],
                "CallSource"=> $values["callsource"],
                "Mode" => $values["try"],
                "Scope" => $values["scoope"],
            );
            $url = (CPFINANCIALS . "/Intranet/BridgeDirectAuthenticate?Usuario=".$fields["Usuario"]."&Password=". $fields["Password"]."&PasswordPlain=" . $fields["PasswordPlain"] . "&ExternalOperator=".$fields["ExternalOperator"]."&Id_type_user=".$fields["Id_type_user"]."&Version=".$fields["Version"]."&Id_app=".$fields["Id_app"]."&CallSource=".$fields["CallSource"]."&Mode=".$fields["Mode"]."&Scope=".$fields["Scope"]);
            $result = $this->cUrlRestful($url, $headers);
            $result = json_decode($result, true);
            if ($result["estado"] == "OK") {
                $result = $result["records"];
            } else {
                throw new Exception(lang('error_100') . ": " . $result["message"], 100);
            }
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function BridgeDirectTokenAuthentication($values)
    {
        try {
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/Intranet/BridgeDirectTokenAuthentication?Id=" . $values["id_user_active"]."&Token_authentication=". $values["token_authentication"]);
            $result = $this->cUrlRestful($url, $headers);
            $result = json_decode($result, true);
            if (!isset($result["records"][0])) {throw new Exception(lang("error_5401"), 5401);}
            /*Post procesamiento de login con validación tokenizada, deberia ir aqui si hace falta!*/

            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result["records"][0],
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    public function landing($values)
    {
        try {
            $landingNormalizada = true;
            $autorizarTarjeta = false;
            $result = null;
            if (!isset($values["Website"])) {
                $values["Website"] = 0;
            }
            if ((string) $values["nIDSucursal"] == "") {
                $values["nIDSucursal"] = -1;
            }
            if ((int) $values["nIDSucursal"] == -1) {
                $values["nIDSucursal"] = 100;
            }
            $circuito = (int) $values["Circuito"];
            $website = (int) $values["Website"];

            
            $headers = $this->Authenticate();
            switch ($values["target"]) {
                case "mediyaCanalDigital":
                    $producto = 261;
                    break;
                case "mediyaContactCenter":
                    $producto = 16;
                    break;
                case "mediyaSucursales":
                    $producto = 161;
                    break;
                case "tiendamil":
                    $producto = $values["TipoTransaccion"];
                    break;
                case "efectivo":
                    $producto = $values["TipoTransaccion"];
                    break;
                case "tarjeta":
                    $autorizarTarjeta = true;
                    $producto = $values["TipoTransaccion"];
                    break;
                default:
                    $producto = $values["TipoTransaccion"];
                    break;
            }
            if ($landingNormalizada) {
                $params = array(
                    "Circuito" => $circuito, // 1 circuito largo / 0 circuito corto
                    "Producto" => $producto, // Tarjeta 
                    "Origen" => 4,
                    "Nombre" => (string) $values["name"],
                    "Apellido" => (string) $values["surname"],
                    "Tipodocumento" => "DNI",
                    "Documento" => (string) $values["dni"],
                    "Sexo" => (string) $values["sex"],
                    "Email" => (string) $values["email"],
                    "Prefijo" => (string) $values["area"],
                    "Telefono" => (string) $values["phone"],
                    "IDSucursal" => (string) $values["nIDSucursal"],
                    "Usuario" => "Landing",
                    "IdTransaccion" => 0
                );
                $url = (CPFINANCIALS . "/Landing/Insertar/");
                $result = $this->callAPI($url, $headers, json_encode($params));
                $result = json_decode($result, true);
            }

            return array(
                "code" => "2000",
                "status" => "OK",
                "Resultado" => "OK",
                "message" => "Operación exitosa",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function OnBoard($values)
    {
        try {
            /*Controla el modo de acceso a la api, es en testing o en produccion*/
            $id_user_generator = (int) $values["id_user_generator"];
            if (!isset($values["test"])) {$values["test"] = "N";}
            if ((int) $values["id_sucursal"] == 0) {$values["id_sucursal"] = 100;}
            $id_type_request = (int) $values["id_type_request"];
            
            $headers = $this->Authenticate();
            $fields = array(
                "Origen" => 1,
                "Tipo" => $id_type_request, // 1 si es alta de crédito o 17 si es Venta MIL
                "Documento" => (int) $values["Documento0"],
                "Nombre" => (string) $values["Nombre0"],
                "Apellido" => (string) $values["Apellido0"],
                "Sexo" => (string) $values["Sexo0"],
                "Email" => (string) $values["Email0"],
                "CUIL" => $values["CUIL0"],
                "Area" => (string) $values["prefijoTelefono0"],
                "Telefono" => (string) $values["Telefono0"],
                "IDVendedor" => 0,
                "nIDSucursal" => (int) $values["id_sucursal"],
                "Ocupacion" => (string) $values["Ocupacion0"],
                "Usuario" => (string) $values["username"],
                "Id_user" => (int) $id_user_generator,
                "idRequest" => (int) $values["idRequest"],
                "idTransaccionOriginal"=>(int)$values["idTransaccionOriginal"]
            );
            $url = (CPFINANCIALS . "/Credito/OnBoard/");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);

            if (isset($result[0]["logica"])) {
                if ($result[0]["logica"] == false) {
                    throw new Exception($result[0]["mensaje"], 9999);
                }
            }
            if (isset($result["cliente"])) {
                $result["cliente"] = json_decode($result["cliente"], true);
            }
            if (isset($result["empleos"])) {
                $result["Empleo"] = json_decode($result["empleos"], true);
                unset($result["empleos"]);
            }
            if (isset($result["scoring"])) {
                $result["scoring"] = json_decode($result["scoring"], true);
            }
            $result["verify"] = "persona";
            if (!isset($result["nID"]) && (int) $result["nID"] != 0) {
                /*Beware!!!!! evaluate distinct strcutures if it's not client!!!*/
                $cuit = "";
                $razonSocial = "";
                foreach ($result["Empleo"] as $empleo) {
                    if ((int) $empleo["Correcto"] == 1) {
                        $cuit = $empleo["CUIT"];
                        $razonSocial = $empleo["Empleador"];
                    }
                }
                $result["verify"] = "empleo";
                $result["nID"] = 0;
                $result["nIDEmpresa"] = 1;
                $result["nIDSucursal"] = 100;
                $result["nCliente"] = 0;
                $result["sNombre"] = (string) $values["Nombre0"] . " " . (string) $values["Apellido0"];
                $result["dFechaNac"] = "";
                $result["sLKDocTipo"] = "DNI";
                $result["nDoc"] = (int) $values["Documento0"];
                $result["sLKEstadoCivil"] = "";
                $result["sSexo"] = (string) $values["Sexo0"];
                $result["sLKNacionalidad"] = "";
                $result["lDomiHab"] = "";
                $result["sDomiCalle"] = "";
                $result["sDomiNro"] = "";
                $result["sDomiPisoDpto"] = "";
                $result["sDomiEntre"] = "";
                $result["sDomiBarrio"] = "";
                $result["sLKDomiLocalidad"] = "";
                $result["sDomiCP"] = "";
                $result["sDomiPcia"] = "";
                $result["lDomiTEHab"] = "";
                $result["sDomiTETelediscado"] = (string) $values["prefijoTelefono0"];
                $result["sDomiTE"] = (string) $values["Telefono0"];
                $result["sLKDomiTEQuien"] = "";
                $result["sLKCondIVA"] = "";
                $result["nCUIT"] = "";
                $result["nCUIL"] = "";
                $result["sLKOcupacion"] = null;
                $result["sLKTipoVivienda"] = null;
                $result["nImporteAlquiler"] = 0;
                $result["sLKCalificacion"] = "N";
                $result["dFechaAlta"] = null;
                $result["dFechaBaja"] = null;
                $result["sLKEstado"] = "";
                $result["sLKControl"] = "";
                $result["sLKRelacion"] = "";
                $result["sAudAltaUsuario"] = null;
                $result["dAudAltaFecha"] = null;
                $result["sAudModiUsuario"] = null;
                $result["dAudModiFecha"] = null;
                $result["sEmail"] = (string) $values["Email0"];
                $result["sCBU"] = "";
                $result["sCuenta"] = "";
                $result["MAC"] = 0;
                $result["LAB_sRazonSocial"] = $razonSocial;
                $result["LAB_sCUIT"] = $cuit;
                $result["LAB_lDomiHab"] = "";
                $result["LAB_sDomiCalle"] = "";
                $result["LAB_sDomiNro"] = "";
                $result["LAB_sDomiPisoDpto"] = "";
                $result["LAB_sDomiEntre"] = "";
                $result["LAB_sLKDomiLocalidad"] = "";
                $result["LAB_sDomiCP"] = "";
                $result["LAB_sDomiPcia"] = "";
                $result["LAB_lDomiTEHab"] = "";
                $result["LAB_sDomiTETelediscado"] = "";
                $result["LAB_sDomiTE"] = "";
                $result["LAB_sDomiTEInt"] = "";
                $result["LAB_sCargo"] = "";
                $result["LAB_sLegajo"] = "";
                $result["LAB_sSeccion"] = "";
                $result["LAB_nIngresoMensual"] = 0;
                $result["LAB_dFechaIngreso"] = "";
                $result["LAB_sLKRubroLaboral"] = "";
                $result["LAB_nOtrosIngresos"] = 0;
                $result["LAB_sAntiguedad"] = "0";
            }
            /*Fallbacks de diferencias de estrcura en la respuesta*/
            if ($result["estado"] == "ERROR") {
                throw new Exception($result["mensaje"], 9999);
            }
            if (!isset($result["resultado"])) {
                $result["resultado"] = 0;
            }
            if ((int) $result["nCliente"] != 0) {
                $result["resultado"] = 0;
            }
            if ((int) $result["resultado"] != 0) {
                throw new Exception($result["mensaje"], 9999);
            }
            if (isset($result["0"])) {
                if (isset($result["0"]["resultado"])) {
                    if ((int) $result["0"]["resultado"] == 1) {
                        $result["resultado"] = 1;
                    }
                } else {
                    $result["resultado"] = 0;
                }
            }
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $result,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function Tokenizar($values)
    {
        try {
            
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/CardCred/SaveMediosCobro/");
            $result = $this->callAPI($url, $headers, json_encode($values));
            $result = json_decode($result, true);
            //if ($result["logica"]=="false") {throw new Exception($result["mensaje"], 9999);}
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $result,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function EmisionProducto($values)
    {
        try {

            
            $headers = $this->Authenticate();
            $fields = array("lat" => $values["lat"], "lng" => $values["lng"],"pdf_solicitud" => $values["pdf_solicitud"],"img_additional" => $values["img_additional"], "IdRequest" => (int) $values["IdRequest"], "sAltaUsuario" => (string) $values["sAltaUsuario"]);
            $url = (CPFINANCIALS . "/Credito/EmisionProducto/");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);

            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $result,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function GetFormulario($values)
    {
        try {
            $headers = $this->Authenticate();
            $fields = array(
                "Format" => (string)$values["Format"],
                "Formulario" => (string)$values["Formulario"],
                "ValueForRetrieve" => (string)$values["ValueForRetrieve"],
                "Username" => "neodata",
                "idEntidad" => 0
            );
            $url = (CPFINANCIALS . "/Utilidades/TraerFormularioAlt/");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);

            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $result,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function FirmarFormulario($values)
    {
        try {
            

            $headers = $this->Authenticate();
            $fields = array(
                "Format" => (string) $values["Format"],
                "Formulario" => (string) $values["Formulario"],
                "ValueForRetrieve" => (string) $values["ValueForRetrieve"],
                "Username" => "neodata",
                "segmento_carpeta_digital"=>(string)$values["segmento_carpeta_digital"],
                "idEntidad" => 0,
                "img_additional"=> $values["img_additional"],
                "pageToAlter" => (int) $values["pageToAlter"],
                "x" => (int) $values["x"],
                "y" => (int) $values["y"],
                "lat" => $values["lat"],
                "lng" => $values["lng"]
            );
            $url = (CPFINANCIALS . "/Utilidades/FirmarFormulario/");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);

            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $result,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function RecalcularImporteCuotaCredito($values){
	   $ret=array();
	   foreach($values["Planes"] as $item){
		   $sql="SELECT DBCentral.dbo.NS_FN_Prestamo_Calculo_ImporteCuota(".$values["Capital"].",".$item["Plan"].") as ImporteCuota";
		   array_push($ret,$this->getRecordsAdHoc($sql));
	   }
	   return $ret;
	}
    public function LogInVendedor($values){
        try {
            $sql="SELECT Id, IdEmpresa, Nombre, Admin AS EsAdmin FROM DBClub..CR_Vendedor WHERE Estado='VIG' AND NroDocumento=".$values["username"]." AND [PASSWORD]='".$values["password"]."'";
            $vendedor=$this->getRecordsAdHoc($sql);
			if (sizeof($vendedor)==0){throw new Exception(lang("error_5200"),9999);}
            
            $CONSULTA=$this->createModel(MOD_DBCENTRAL,"Consulta","DBCentral.dbo.consulta");
            $CONSULTA->view="DBCentral.dbo.consulta";
			$params=array("page"=>-1,"pagesize"=>-1,"order"=>"Descripcion ASC");
            $consulta=$CONSULTA->get($params);

            $EMPRESA=$this->createModel(MOD_DBCENTRAL,"Empresa","DBClub.dbo.empresa");
            $EMPRESA->view="DBClub.dbo.empresa";
			$where="Id not in (997,998,999)";

			if ((int)$vendedor[0]["IdEmpresa"]!=999){$where=("Id=".$vendedor[0]["IdEmpresa"]);}

			$params=array("page"=>-1,"pagesize"=>-1,"order"=>"Nombre ASC", "where"=>$where);
            $empresa=$EMPRESA->get($params);

			$params=array("page"=>-1,"pagesize"=>-1,"where"=>"Id=".$vendedor[0]["IdEmpresa"]);
            $additional=$EMPRESA->get($params);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$vendedor,
                "additional"=>$additional["data"],
				"consulta"=>$consulta["data"],
				"empresa"=>$empresa["data"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        } 
    }
    public function LogInComercializador($values){
        try {
            $sql="SELECT C.Id, C.IdEmpresa, E.Nombre AS NombreEmpresa, C.NroDocumento, C.Nombre, C.Estado, C.Email FROM DBCentral.dbo.Comercializador as C INNER JOIN DBCentral.dbo.empresacomercializadora as E ON C.idEmpresa=E.Id WHERE nrodocumento=".$values["username"]." AND password='".$values["password"]."'";
			$vendedor=$this->getRecordsAdHoc($sql);
			if (sizeof($vendedor)==0){throw new Exception(lang("error_5200"),9999);}

            $CONSULTA=$this->createModel(MOD_DBCENTRAL,"Consulta","DBCentral.dbo.consulta");
            $CONSULTA->view="DBCentral.dbo.consulta";
			$params=array("page"=>-1,"pagesize"=>-1,"order"=>"Descripcion ASC");
            $consulta=$CONSULTA->get($params);

            $EMPRESA=$this->createModel(MOD_DBCENTRAL,"Empresa","DBClub.dbo.empresa");
            $EMPRESA->view="DBClub.dbo.empresa";
			$where="Id not in (997,998,999)";

			/*FORZAR PRUEBA CON caRTASUR */
			//$vendedor[0]["IdEmpresa"]=14;

			if ((int)$vendedor[0]["IdEmpresa"]!=999){$where=("Id=".$vendedor[0]["IdEmpresa"]);}
			$params=array("page"=>-1,"pagesize"=>-1,"order"=>"Nombre ASC", "where"=>$where);
            $empresa=$EMPRESA->get($params);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$vendedor,
				"consulta"=>$consulta["data"],
				"empresa"=>$empresa["data"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        } 
    }
	public function GetHistorialDePagos($values){
        try {
			$headers = $this->Authenticate();
 		    $fields=array("IdSocio"=>(int)$values["IdSocio"]);

			$url=(CPFINANCIALS."/Mediya/GetHistorialDePagosAlt/");
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
	public function GetCredenciales($values){
        try {
			$headers = $this->Authenticate();
            if (strpos($values["NroDocumento"], "@") !== false) {$values["NroDocumento"] = explode("@", $values["NroDocumento"])[0];} 
 		    $fields=array("Tipo"=>strtoupper($values["Tipo"]),"NroDocumento"=>$values["NroDocumento"],"Sexo"=>$values["Sexo"]);
			$url=(CPFINANCIALS."/Mediya/GetCredencialesAlt/");
			$result = $this->callAPI($url,$headers,json_encode($fields));
			$result = json_decode($result, true);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$result["records"],
                "compressed"=>false
            );        
		}
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
	}
    public function GetTitularMediya($values){
        try {
			$headers = $this->Authenticate();
			if ((int)$values["Id"]==0){unset($values["Id"]);}
			if(isset($values["Id"])) {
	 		    $fields=array("Id"=>(int)$values["Id"]);
			} else {
 				$fields=array(
					"NroDocumento"=>(int)$values["NroDocumento"],
					"Sexo"=>(string)$values["Sexo"]
				);
			}
			$url=(CPFINANCIALS."/Mediya/GetTitular/");
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
	public function GetAdicionalesMediya($values){
        try {
			$headers = $this->Authenticate();
			if ((int)$values["Id"]==0){unset($values["Id"]);}
		    $fields=array("Id"=>(int)$values["Id"]);
			$url=(CPFINANCIALS."/Mediya/GetAdicionales/");
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
    public function SetTitularMediya($values){
        try {
			$headers = $this->Authenticate();
			if ($values["Latitud"]=="0" && $values["Longitud"]=="0") {
				$searchAddress=($values["Calle"]." ".$values["Numeracion"]." ".$values["Localidad"]." ".$values["Provincia"].", Argentina");
				$PLACES=$this->createModel(MOD_PLACES,"Places","Places");
				$reverse=$PLACES->getReverse(array("address"=>$searchAddress));
				$values["Latitud"]=$reverse["lat"];
				$values["Longitud"]=$reverse["lng"];
			}
			
			/*Fuerza el PAN si es enviado a que se asigne a Identificacion!*/
			$identificacion=(string)$values["CBU"];
			$PAN=(string)$values["PAN"];
			if ($PAN!=""){$identificacion=$PAN;}
            $fields=array(
			   "IDSocio"=>(int)$values["Id"],
			   "Nombre"=>(string)$values["Nombre"],
			   "Apellido"=>(string)$values["Apellido"],
			   "Sexo"=>(string)$values["Sexo"],
			   "NroDocumento"=>(int)$values["NroDocumento"],
			   "IDEstadoCivil"=>(int)$values["IdEstadoCivil"],
			   "IDNacionalidad"=>(int)$values["IdNacionalidad"],
			   "IDOcupacion"=>(int)$values["IdOcupacion"],
			   "CUIL"=>(string)$values["CUIL"],
			   "FechaNacimiento"=>$values["FechaNacimiento"],
			   "AreaTelefono"=>(string)$values["AreaTelefonoSocio"],
			   "Telefono"=>(string)$values["TelefonoSocio"],
			   "Email"=>(string)$values["Email"],
			   "Calle"=>(string)$values["Calle"],
			   "Numeracion"=>(string)$values["Numeracion"],
			   "Piso"=>(string)$values["Piso"],
			   "DptoOficLoc"=>(string)$values["DptoOficLoc"],
			   "Torre"=>(string)$values["Torre"],
			   "CodigoPostal"=>(string)$values["CodigoPostal"],
			   "Provincia"=>(string)$values["Provincia"],
			   "Localidad"=>(string)$values["Localidad"],
			   "IDModoPago"=>(int)$values["IdModoPago"],
			   "Identificacion"=>$identificacion,
			   "Marca"=>(string)$values["Marca"],
			   "PAN"=>$PAN,
			   "NombreTarjeta"=>(string)$values["NombreTarjeta"],
			   "MesVTO"=>(int)$values["MesVTO"],
			   "AnioVTO"=>(int)$values["AnioVTO"],
			   "IDEmpresa"=>(int)$values["IDEmpresa"],
			   "IDVendedor"=>(int)$values["IDVendedor"],
			   "IDSucursal"=>(int)$values["IDSucursal"],
			   "IDCanal"=>1, //Canal ?
			   "Username"=>(string)$values["username"],
			   "Latitud"=>(string)$values["Latitud"],
			   "Longitud"=>(string)$values["Longitud"],
			);
			log_message("error", "RELATED ".json_encode($fields,JSON_PRETTY_PRINT));
			$url=(CPFINANCIALS."/Mediya/SetTitular/");
			$result = $this->callAPI($url,$headers,json_encode($fields));
			$result = json_decode($result, true);
			log_message("error", "RELATED result ".json_encode($result,JSON_PRETTY_PRINT));

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
	public function SetAdicionalMediya($values){
        try {
			$headers = $this->Authenticate();
            $fields = array(
                "IDFamiliar" => (int) $values["a_IDFamiliar"],
                "idTipoAdicional" => (int) $values["a_IdTipoAdicional"],
                "IDSocio" => (int) $values["a_IDSocio"],
                "Nombre" => (string) $values["a_Nombre"],
                "Apellido" => (string) $values["a_Apellido"],
                "NroDocumento" => (int) $values["a_NroDocumento"],
                "Sexo" => (string) $values["a_Sexo"],
                "IDParentesco" => (int) $values["a_IdParentesco"],
                "FechaNacimiento" => $values["a_FechaNacimiento"],
                "AreaTelefono" => (string) $values["a_AreaTelefonoSocio"],
                "Telefono" => (string) $values["a_TelefonoSocio"],
                "sMail" => (string) $values["a_Email"],
                "Username" => (string) $values["a_username"],
            );
			$url=(CPFINANCIALS."/Mediya/SetAdicional/");
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
	public function DelAdicionalMediya($values){
        try {
			$headers = $this->Authenticate();
            $fields=array(
			   "IDFamiliar"=>(int)$values["id"],
			   "Username"=>(string)$values["username"],
			);
			$url=(CPFINANCIALS."/Mediya/DelAdicional/");
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
    public function VerifySolicitudTarjeta($values){
        try {
			/*Controla el modo de acceso a la api, es en testing o en produccion*/
		    if (!isset($values["test"])){$values["test"]="N";}
			if ($values["username_active"]==""){$values["username_active"]="web";}

		    $origen=(int)$values["origen"]; // 1 sucursal
			$id_type_request=(int)$values["id_type_request"]; // 351 Tarjeta de credito
			$headers = $this->Authenticate();
            $fields=array(
			    "Origen"=>$origen,
				"Tipo"=>$id_type_request,
				"Documento"=>(int)$values["nDoc"],
				"Nombre"=>(string)$values["sNombre"],
				"Sexo"=>(string)$values["sSexo"],
				"Email"=>(string)$values["sEmail"],
				"CUIL"=>$values["nCUIL"],
				"Telefono"=>(string)$values["sDomiTETelediscado"]."".(string)$values["sDomiTE"],
			    "IDVendedor"=>(int)$values["nIDVendedor"],
			    "nIDSucursal"=>(int)$values["nIDSucursal"],
				"Usuario"=>(string)$values["username_active"]
			);
			$url=(CPFINANCIALS."/Onboarding/VerifySolicitudTarjeta/");
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
		    //log_message("error", "RELATED LOGICA error ".json_encode($e,JSON_PRETTY_PRINT));
            return logError($e,__METHOD__ );
        }
    }
	public function GetTarjeta($values){
        try {
			if ($values["username_active"]==""){$values["username_active"]="web";}
            $headers = $this->Authenticate();
            $fields=array(
				 "Codigo"=>(string)$values["Codigo"],
				 "Usuario"=>(string)$values["Usuario"]
			);
			$url=(CPFINANCIALS."/Tarjetas/GetTarjeta/");
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
	public function GetAdicionalesTarjeta($values){
        try {
            $headers = $this->Authenticate();
            if ((int)$values["Codigo"]==0){unset($values["Codigo"]);}
		    $fields=array("Codigo"=>(int)$values["Codigo"]);
			$url=(CPFINANCIALS."/Tarjetas/GetAdicionales/");
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
	public function SetAdicionalTarjeta($values){
        try {
		    
			$headers = $this->Authenticate();
            $fields=array(
			   "nId"=>(int)$values["a_nId"],
			   "nIDSucursal"=>(int)$values["nIDSucursal"],
			   "_codigo"=>(string)$values["a_codigo"],
			   "sNombre"=>(string)$values["a_sNombre"],
			   "nDoc"=>(int)$values["a_nDoc"],
			   "sLKParentesco"=>(string)$values["a_sLKParentesco"],
			   "dFechaNacimiento"=>$values["a_dFechaNacimiento"],
			   "Username"=>(string)$values["a_username"],
			);
			$url=(CPFINANCIALS."/Tarjetas/SetAdicional/");
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
	public function SetEmitirTarjeta($values){
        try {
			/*Controla el modo de acceso a la api, es en testing o en produccion*/
		    if (!isset($values["test"])){$values["test"]="N";}
			if ($values["username"]==""){$values["username"]="web";}

		    
			$headers = $this->Authenticate();
			
            $fields=array(
			    "Origen"=>$origen,
				"Tipo"=>$id_type_request,
				"nDoc"=>(int)$values["nDoc"],
				"sSexo"=>(string)$values["sSexo"],
				"sNombre"=>(string)$values["sNombre"],
				"sEmail"=>(string)$values["sEmail"],
				"nCUIL"=>$values["nCUIL"],
				"sDomiTETelediscado"=>(string)$values["sDomiTETelediscado"],
				"sDomiTE"=>(string)$values["sDomiTE"],
			    "nIDVendedor"=>(int)$values["nIDVendedor"],
			    "nIDSucursal"=>(int)$values["nIDSucursal"],
				"nIDComercializadora"=>(int)$values["nIDComercializadora"],
				"sLKEstadoCivil"=>(string)$values["sLKEstadoCivil"],
				"sLKNacionalidad"=>(string)$values["sLKNacionalidad"],
				"sLKOcupacion"=>(string)$values["sLKOcupacion"],
				"dFechaNac"=>$values["dFechaNac"],
				"sCBU"=>(string)$values["sCBU"],
				"sLKTipoVivienda"=>(string)$values["sLKTipoVivienda"],
				"nImporteAlquiler"=>(int)$values["nImporteAlquiler"],
				"sDomiCalle"=>(string)$values["sDomiCalle"],
				"sDomiNro"=>(string)$values["sDomiNro"],
				"sDomiPisoDpto"=>(string)$values["sDomiPisoDpto"],
				"sDomiCP"=>(string)$values["sDomiCP"],
				"sDomiEntre"=>(string)$values["sDomiEntre"],
				"sDomiBarrio"=>(string)$values["sDomiBarrio"],
				"sLKDomiLocalidad"=>(string)$values["sLKDomiLocalidad"],
				"sDomiPcia"=>(string)$values["sDomiPcia"],
				"sRazonSocial"=>(string)$values["sRazonSocial"],
				"nCUIT1"=>(string)$values["nCUIT1"],
				"sDomiCalle1"=>(string)$values["sDomiCalle1"],
				"sDomiNro1"=>(string)$values["sDomiNro1"],
				"sDomiPisoDpto1"=>(string)$values["sDomiPisoDpto1"],
				"sDomiCP1"=>(string)$values["sDomiCP1"],
				"sDomiEntre1"=>(string)$values["sDomiEntre1"],
				"sLKDomiLocalidad1"=>(string)$values["sLKDomiLocalidad1"],
				"sDomiPcia1"=>(string)$values["sDomiPcia1"],
				"sCargo"=>(string)$values["sCargo"],
				"sLegajo"=>(string)$values["sLegajo"],
				"sSeccion"=>(string)$values["sSeccion"],
				"nIngresoMensual"=>(int)$values["nIngresoMensual"],
				"dFechaIngreso1"=>$values["dFechaIngreso1"],
				"sLKRubroLaboral"=>(string)$values[""],
				"nOtrosIngresos"=>(int)$values["sLKRubroLaboral"],
				"sAntiguedad"=>(string)$values["sAntiguedad"],
				"Latitud"=>(string)$values["Latitud"],
				"Longitud"=>(string)$values["Longitud"],
				"Usuario"=>(string)$values["username_active"]
			);
			$url=(CPFINANCIALS."/Tarjetas/SetEmitirTarjeta/");
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
		    //log_message("error", "RELATED LOGICA error ".json_encode($e,JSON_PRETTY_PRINT));
            return logError($e,__METHOD__ );
        }
	}
    public function lookup($values){
        try {
		    
			$headers = $this->Authenticate();
			$url=(CPFINANCIALS."/Lookups/GetLookUp?Tipo=".$values["Tipo"]);
			$result = $this->cUrlRestful($url,$headers);
			$result = json_decode($result, true);
			return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Operación exitosa",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$result,
                "compressed"=>false
            );        
		}
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
	public function ValidateCBU($values){
        try {
            $sql=(string)"EXEC DBCentral.dbo.NS_CBU_Verificar @sCBU='".$values["valorConsulta"]."'";
            $result=$this->getRecordsAdHoc($sql);

		    /*
			
			$headers = $this->Authenticate();
 		    $fields=array("TipoConsulta"=>strtoupper($values["tipoConsulta"]),"ValorConsulta"=>$values["valorConsulta"]);
			$url=(CPFINANCIALS."/Utilidades/ValidarCBU/");
			$result = $this->callAPI($url,$headers,json_encode($fields));
			$result = json_decode($result, true);
			*/

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$result,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$result[0]["resultado"],
                "compressed"=>false
            );        
		}
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
	}
    public function GetDeudaAPagar($values){
        try {
		    
			$headers = $this->Authenticate();
 		    $fields=array(
				 "sTipo"=>"D",
				 "sTipodoc"=>"DNI",
				 "sValor"=>(string)$values["Documento"],
				 "TipoDeuda"=>(string)$values["Deuda"],
				 "Origen"=>"IVR"
			);
			$url=(CPFINANCIALS."/Deuda/GetDeudaAPagar/");
			$result = $this->callAPI($url,$headers,json_encode($fields));
			$result = json_decode($result, true);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Operación exitosa",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$result["records"],
                "compressed"=>false
            );        
		}
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function PagosIniciarTransaccion($values){
        try {
		    
			$headers = $this->Authenticate();
			$fields = array(
				'Code' => opensslRandom(8),
				'Description' => 'Pago vía agente externo',
				'Id_type_channel' => (int)secureEmptyNull($values,"id_type_channel"),
				'Identificacion' => (string)$values["identificacion"],
				'Currency_request' => (string)$values["currency_request"],
				'Dni_request' => (string)$values["dni_request"],
				'Amount_request' => (float)$values["amount_request"],
				'Raw_request' => (string)$values["raw_request"],
				'Channel' => (string)$values["channel"],
			);
			$url=(CPFINANCIALS."/Pagos/IniciarTransaccion/");
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
    public function Webhook($values){
        try {
			$headers = $this->Authenticate();
 			$fields = array(
                'scope' => "fiserv",
                'raw_data' => json_encode($values),
                'referrer' => (string)$_SERVER["REMOTE_ADDR"]
			);
            $url=(CPFINANCIALS."/Pagos/Webhook/");
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
    public function PagosTerminarTransaccion($values){
        try {
			$headers = $this->Authenticate();
			$fields = array(
				"id"=>(int)$values["id"],
                'status' => (string)$values["status"],
                'currency_response' => (string)$values["currency_response"],
                'dni_response' => (string)$values["dni_response"],
                'amount_response' => (string)$values["amount_response"],
                'card_response' => (string)$values["card_response"],
                'card_name' => (string)$values["card_name"],
                'partial_card_number' => (string)$values["partial_card_number"],
                'message' => (string)$values["message"],
                'raw_response' => (string)$values["raw_response"],
                'registro_externo' => (string)$values["registro_externo"],
			);
            $url=(CPFINANCIALS."/Pagos/TerminarTransaccion/");
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
    public function ProcesarItemPago($values){
        try {
			$headers = $this->Authenticate();
			$url=(CPFINANCIALS."/Pagos/ProcesarItemPago/");
			$params=array(
				"TipoUsuario"=>(string)$values["TipoUsuario"],
				"Identificacion"=>(string)$values["Identificacion"],
				"Origen"=>(int)$values["origen"],
				"Tipo"=>(string)$values["Tipo"],
				"MedioPago"=>(string)$values["MedioPago"],
				"Importe"=>(string)$values["Importe"],
				"Resultado"=>(string)$values["Resultado"],
				"Transaccion"=>(string)$values["Transaccion"],
				"Respuesta"=>(string)$values["Respuesta"],
				"type_rel"=>(string)$values["type_rel"],
				"identify_rel"=>(string)$values["identify_rel"],
				"amount_rel"=>(string)$values["amount_rel"],
				"servicioPago"=>(string)$values["servicioPago"],
				"Id_mod_payments_transactions"=>(int)$values["Id_mod_payments_transactions"],
				"Transaccion"=>(string)$values["Transaccion"],
				"TransaccionOrigen"=>(string)$values["Registro_externo"],
			);
			$result = $this->callAPI($url,$headers,json_encode($params));
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
    public function CheckPlan($values){
        try {
		    
            
			$headers = $this->Authenticate();
 		    $fields=array("idPlan"=>(int)$values["idPlan"]);
			$url=(CPFINANCIALS."/Utilidades/ValidarPlan/");
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
    public function IdemiaAuth($values)
    {
        try {
            

            $headers = $this->Authenticate();
            $fields = array("modo" => $values["modo"]);
            $url = (CPFINANCIALS . "/Bureau/IdemiaAuth/");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "tokenId" => $result["text"],
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function IdemiaGetIdtx($values)
    {
        try {
            

            $headers = $this->Authenticate();
            $fields = array("idtx" => $values["idtx"]);
            $url = (CPFINANCIALS . "/Bureau/IdemiaGetIdtx?idtx=". $values["idtx"]);
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function ConsultaValidarDNI($values){
        try {
            

            $headers = $this->Authenticate();
            $fields = array(
                "idRequest" => (int) $values["idRequest"], 
                "idtx" => $values["idtx"],
                "Producto" => $values["Producto"],
                "Formato" => $values["Formato"],
                );
            $url = (CPFINANCIALS . "/Bureau/ConsultaValidarDNI");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function ConsultaValidarVida($values)
    {
        try {
            

            $headers = $this->Authenticate();
            $fields = array(
                "idRequest" => (int) $values["idRequest"],
                "idtx" => $values["idtx"],
                "Producto" => $values["Producto"],
                "Formato" => $values["Formato"],
            );
            $url = (CPFINANCIALS . "/Bureau/ConsultaValidarVida");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function ConsultaValidarImagenManual($values)
    {
        try {
            

            $headers = $this->Authenticate();
            $fields = array(
                "idRequest" => (int) $values["idRequest"],
                "base64" => $values["base64"],
                "Producto" => $values["Producto"],
                "Formato" => $values["Formato"],
            );
            $url = (CPFINANCIALS . "/Bureau/ConsultaValidarImagenManual");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function ConsultaValidarDatosLaborales($values)
    {
        try {
            
            $headers = $this->Authenticate();
            $fields = array(
                "idRequest" => (int) $values["id"],
                "RazonSocial" => $values["RazonSocial"],
                "cuit" => (int)$values["cuit"],
                "Rubro" => (int)$values["Rubro"],
                "IngresoMensual" => (int)$values["IngresoMensual"],
                "FechaIngreso" => $values["FechaIngreso"],
                "CalleEmpresa" => $values["CalleEmpresa"],
                "NumeroEmpresa" => $values["NumeroEmpresa"],
                "PisoEmpresa" => $values["PisoEmpresa"],
                "DepartamentoEmpresa" => $values["DepartamentoEmpresa"],
                "CodigoPostalEmpresa" => $values["CodigoPostalEmpresa"],
                "EntreCallesEmpresa" => $values["EntreCallesEmpresa"],
                "LocalidadEmpresa" => $values["LocalidadEmpresa"],
                "ProvinciaEmpresa" => $values["ProvinciaEmpresa"],
                "prefijoTelefonoEmpresa" =>(int)$values["prefijoTelefonoEmpresa"],
                "TelefonoEmpresa" => (int)$values["TelefonoEmpresa"]
            );
            $url = (CPFINANCIALS . "/Bureau/ConsultaValidarDatosLaborales");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function GetTransaccionOriginal($values)
    {
        try {
            

            $headers = $this->Authenticate();
            $fields = array("Id" => $values["Id"]);
            $url = (CPFINANCIALS . "/Transaccion/GetTransaccionOriginal/");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function GetUserDetails($values)
    {
        try {
            
            
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/Login/GetAdditionals?usuario=" . $values["username"]);
            $result = $this->cUrlRestful($url, $headers);
            $result = json_decode($result, true);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result["records"],
              "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function GetDataCliente($values)
    {
        try {
            

            $headers = $this->Authenticate();
            if ((int) $values["Codigo"] == 0) {unset($values["Codigo"]);}
            $fields = array("NroDocumento" => (int) $values["NroDocumento"]);
            $url = (CPFINANCIALS . "/Cliente/GetDataCliente/");
            $result = $this->callAPI($url, $headers, json_encode($fields));
            $result = json_decode($result, true);

            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $result,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function GetCuotas($values,$format)
    {
        try {
            
            $headers = $this->Authenticate();
            $params = base64_decode($values["express_key"]);
            switch($format)
            { 
                case "pdf":
                   $params = str_replace("Format=html", "Format=pdf",$params);
                    break;
                case "html":
                    $params = str_replace("Format=pdf", "Format=html",$params);
                    break;
            }
            $url = (CPFINANCIALS . "/Credito/GetCuotas?" . $params);
            $result = $this->cUrlRestful($url, $headers);
            return $result;

                $result = json_decode($result, true);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "Operación exitosa",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $result,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function saveCardCred($values)
    {
        try {
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/CardCred/Webhook");
            $result = $this->callAPI($url, $headers, json_encode($values));
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {return logError($e, __METHOD__);}
    }

    public function saveVisa($values)
    {
        try {
            
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/Visa/Webhook");
            $result = $this->callAPI($url, $headers, json_encode($values));
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function verifyEmail($base64)
    {
        try {
            
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/Verify/Email?base64=" . $base64);
            $result = $this->cUrlRestful($url, $headers);
            $result = json_decode($result, true);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $result,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => false
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function MessageTelemedicina($values)
    {
        try {
            
            $headers = $this->Authenticate();
            $url = (CPFINANCIALS . "/Telemedicina/Message");
            $result = $this->callAPI($url, $headers, json_encode($values));
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
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
        if ($values["dni"] == "") {$values["dni"] = 0;}
        switch($scope){
           case "CP":
              $sql=(string)"EXEC DBCentral.dbo.NS_Clientes_Datos_Generales_JSON @doc=".$values["dni"].", @sexo='".$values["sex"]."'";
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
              $sql = (string) "EXEC DBClub.dbo.NS_Socio_Datos_Generales_JSON @doc=" . $values["dni"] . ", @sexo='" . $values["sex"] . "'";
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
            if (!isset($values["documentSex"])) {$values["documentSex"] = "";}
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
	    $filename="Comprobante de pago ".opensslRandom(8).".pdf";
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
            if (!isset($params["id"])) {$params["id"] = 0;}
            $params["Importe"] = (float) $params["Importe"];
            $params["type_rel"] = $params["Tipo"];
            $params["identify_rel"] = $params["Identificacion"];
            $params["amount_rel"] = $params["Importe"];
            $params["Id_mod_payments_transactions"] = (int) $params["id"];
            logGeneralCustom($this, $params, "Payments::setItemPago", "idpt: " . $params["id"] . " Servicio:" . $params["servicioPago"] . " Tipo:" . $params["Tipo"] . " Identificacion:" . $params["Identificacion"] . " Importe:" . $params["Importe"] . " Resultado:" . $params["Resultado"] . " Transaccion:" . $params["Transaccion"] . " Respuesta:" . $params["Respuesta"]);
            $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $result = $NETCORECPFINANCIAL->ProcesarItemPago($params);
            logGeneralCustom($this, $params, "Payments::setItemPagoResponse", $result);
            /*Saving receipt for dni + payment*/
            $this->generateReceipt($params);
            return $result;
        } catch (Exception $e) {
            logGeneralCustom($this, $params, "Payments::setItemPagoResponseError", $e);
        }
    }
    private function Authenticate(){
        return array('Content-Type:application/json');
	}
    private function callAPI($url, $headers, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        if (is_array($headers)) {curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);}
        $response = curl_exec($ch);
        $response=trim($response, "\xEF\xBB\xBF");
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        return $response;
    }
    private function cUrlRestful($url,$headers){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_POST, 0);
        if (is_array($headers)) {curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);}
        $jsonResponse = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        $response = $jsonResponse;
        return $response;
    }
}
