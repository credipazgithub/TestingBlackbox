<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Farmalink extends MY_Model {
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

            $b64= base64_encode(json_encode($fields));
            $NETCORECPFINANCIAL = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $params=array("base64"=>$b64);
            $result = $NETCORECPFINANCIAL->GenerarLinkFarmalink($params);
            $result = json_decode($result["data"], true);
            return array(
                "code"=>"2000",
                "status"=>strtoupper($result["status"]),
                "url"=>$result["url"],
                "message"=>$result["successMessage"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "errors"=>$result["errors"],
                "validation"=>$result["validationErrors"],
                "compressed"=>false
            );        
		}
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
}



