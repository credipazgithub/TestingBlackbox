<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Farmalink extends MY_Model {
    //private $api_server="https://api.test.recetaonline.ar/api";
	//private $auth_credentials=array("username"=>"app-credipaz-user","accessKey"=>"BP-X2TqqAbEO4qWoduxzj4tcJf5nK5s2TdDb");

    //actual
	private $api_server="https://api.recetaonline.ar/boton-prescipcion-back/api";
	//nueva???
	//private $api_server="https://api.recetaonline.ar/api/GenerateForm/Generate";
	private $auth_credentials=array("username"=>"app-credipaz-user","accessKey"=>"BP-x4JH8qsumujXOW7s3giTxy2ftuClvevg0");

    public function __construct()
    {
        parent::__construct();
    }

    public function Generate($values){
        try {
			$dni=$values["dni"];
			$sexo=$values["sexo"];
			$fechanacimiento=$values["fechanacimiento"];
			$panswiss=$values["panswiss"];
			$nombre=$values["nombre"];
			$apellido=$values["apellido"];
            $profile = getUserProfile($this, $values["id_user_active"]);

            $id_charge_code=$values["id_charge_code"];

			$DOCTORS=$this->createModel(MOD_TELEMEDICINA,"Doctors","Doctors");
            $doctor=$DOCTORS->get(array("page"=>1,"where"=>"username='".$profile["data"][0]["username"]."'"));

            $CHARGES_CODES = $this->createModel(MOD_TELEMEDICINA, "Charges_codes", "Charges_codes");
            $cCode=$CHARGES_CODES->get(array("where"=>"id=".$id_charge_code));
			if ((int)$cCode["totalrecords"]==0) {throw new Exception("No se encontró el beneficiario");}
			$asociado=getUserClubRedondo($this,(int)$cCode["data"][0]["id_club_redondo"]);
            if (!filter_var($asociado["message"]["Email"], FILTER_VALIDATE_EMAIL)) {$asociado["message"]["Email"] = "telemedicina@credipaz.com";}

            $OPERATORS_TASKS = $this->createModel(MOD_TELEMEDICINA, "Operators_tasks", "Operators_tasks");
            $oTask=$OPERATORS_TASKS->get(array("where"=>"id=".$cCode["data"][0]["id_operator_task"]));
			if ((int)$oTask["totalrecords"]==0) {throw new Exception("No se encontró el médico");}

            $USERS = $this->createModel(MOD_BACKEND, "Users", "Users");
            $user=$USERS->get(array("where"=>"id=".$oTask["data"][0]["id_operator"]));
			if ((int)$user["totalrecords"]==0) {throw new Exception("No se encontró el usuario");}
			
            $doctor=$DOCTORS->get(array("page"=>1,"where"=>"username='".$user["data"][0]["username"]."'"));
			if ($doctor["data"][0]["dni"]==""){$doctor["data"][0]["dni"]="12123123";}
			if ($doctor["data"][0]["sex"]==""){$doctor["data"][0]["sex"]="F";}
			if ($doctor["data"][0]["birthday"]==""){$doctor["data"][0]["birthday"]="1980-01-01";}
			$doctor["data"][0]["birthday"]=explode(" ",$doctor["data"][0]["birthday"])[0];
			if (!filter_var($doctor["data"][0]["email"], FILTER_VALIDATE_EMAIL)) {$doctor["data"][0]["email"]="telemedicina@credipaz.com";}

            $fields=array(
				"urlCallback"=>(string)INTRANET."/",
				"direccionConsultorio"=> "",
				"paciente"=>
					array(
						"nombre"=> (string)$nombre,
						"apellido"=> (string)$apellido,
						"tipoDocumento"=> (string)"DNI",
						"nroDocumento"=> (string)$dni,
						"sexo"=> (string)$sexo,
						"email"=> (string)$asociado["message"]["Email"],
						"fechaNacimiento"=> (string)$fechanacimiento,
						"numeroAfiliado"=> (string)$panswiss,
						"numeroFinanciador"=> (string)"205", 
						"plan"=> (string)"Sin Informacion"
					),
				"medico"=>
					array(
						"nombre"=> (string)$doctor["data"][0]["name"],
						"apellido"=> (string)$doctor["data"][0]["surname"],
						"email"=> (string)$doctor["data"][0]["email"],
						"tipoDocumento"=> (string)"DNI",
						"nroDocumento"=> (string)$doctor["data"][0]["dni"],
						"tipoMatricula"=> (string)"MN",
						"numeroMatricula"=> (string)$doctor["data"][0]["mn"],
						"telefono"=> (string)$doctor["data"][0]["phone"],
						"fechaNacimiento"=> (string)$doctor["data"][0]["birthday"],
						"sexo"=> (string)$doctor["data"][0]["sex"],
						"especialidad"=> (string)"GENERALISTA"
					)
				);
			
            $headers = $this->Authenticate();
            $url = ($this->api_server . "/GenerateForm/Generate");
            log_message("error", "RELATED headers " . json_encode($headers, JSON_PRETTY_PRINT));
            log_message("error", "RELATED fields " . json_encode($fields, JSON_PRETTY_PRINT));
            log_message("error", "RELATED url " . json_encode($url, JSON_PRETTY_PRINT));

            $result = $this->callAPI($url,$headers,json_encode($fields));
            log_message("error", "RELATED result " . $result);
            $result = json_decode($result, true);

            if($result["result"]==null){throw new Exception("Se han producido errores: El servicio de Farmalink esta caído - ".$result["errors"][0]." - ".$result["validationErrors"][0]["errorMessage"]);}
            //$this->execAdHoc("EXEC dbCentral.dbo.NS_ServiciosExternos_Update @code='FARMALINK', @estado='ONLINE'");

            return array(
                "code"=>"2000",
                "status"=>strtoupper($result["status"]),
                "url"=>$result["result"]["url"],
                "message"=>$result["successMessage"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "errors"=>$result["errors"],
                "validation"=>$result["validationErrors"],
                "compressed"=>false
            );        
		}
        catch (Exception $e) {
            //$this->execAdHoc("EXEC dbCentral.dbo.NS_ServiciosExternos_Update @code='FARMALINK', @estado='ERROR'");
            return logError($e,__METHOD__ );
        }
    }

	private function Authenticate(){
		$headers = array('Content-Type:application/json');
		$url=($this->api_server."/Aplicacion/login");
		$result = $this->callAPI($url,$headers,json_encode($this->auth_credentials));
		$result = json_decode($result, true);
		return $result["result"]["accessToken"];
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
}



