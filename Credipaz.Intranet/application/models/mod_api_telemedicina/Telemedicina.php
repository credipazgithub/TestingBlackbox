<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Telemedicina extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function monitoreo($values){
        try {
            $values["idUser"] = keySecureNumbers($values, "idUser");
            if ($values["idUser"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
            $values["Id"] = keySecureZero($values, "Id");
            if ($values["Id"] == 0) {$values["Id"]=null;}
            $values["iModo"] = keySecureValInArray($values, "iModo",['1','2','3','4']);
            if ($values["iModo"] == "") {throw new Exception(lang("api_error_1066"), 1066);}

            $fields = ["Id_type" => $values["iModo"],"Id"=>$values["Id"],"Id_user"=>$values["idUser"]];
            $ret = API_callAPIfields("/Mediya/GrillaMonitoreoTelemedicina/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function supervision($values){
        try {
            $ret = API_callAPI("/Mediya/GrillaChargesCodesTelemedicina/", null);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function consultas($values){
        try {
            $values["idUser"] = keySecureNumbers($values, "idUser");
            if ($values["idUser"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
            $fields = ["idUser" => $values["idUser"]];
            $ret = API_callAPIfields("/Mediya/GrillaChargesCodesTelemedicina/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function cancelar($values){
        try {
            $values["Id"] = keySecureNumbers($values, "Id");
            if ($values["Id"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
            $fields = ["Id" => $values["Id"]];
            $ret = API_callAPIfields("/Mediya/CancelTelemedicina/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function postcierre($values){
        try {
            $values["Id"] = keySecureNumbers($values, "Id");
            if ($values["Id"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
            $values["Nota"]=keySecureString($values,"Nota");
            if ($values["Nota"] == "") {throw new Exception(lang("api_error_1068"), 1068);}
            $fields = ["Id" => $values["Id"], "Descripcion" => $values["Nota"]];
            $ret = API_callAPIfields("/Mediya/SavePostCierre/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function solicitarambulancia($values){
        try {
            $values["Id"] = keySecureNumbers($values, "Id");
            if ($values["Id"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
            $values["Tipo"] = keySecureNumbers($values, "Tipo");
            if ($values["Tipo"] == "") {throw new Exception(lang("api_error_1069"), 1069);}

            $fields = ["Id_type" => $values["Tipo"],"Id"=>$values["Id"], "Descripcion"=>$values["Nota"]];
            $ret = API_callAPIfields("/Mediya/SolicitarAmbulancia/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function atencionesanteriores($values){
        try {
            $values["idSocio"] = keySecureNumbers($values, "idSocio");
            if ($values["idSocio"] == "0") {throw new Exception(lang("api_error_1070"), 1070);}
            $fields = ["Id" => $values["idSocio"]];
            $ret = API_callAPIfields("/Mediya/AtencionesAnteriores/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function mensajes($values){
        try {
            $values["idSocio"] = keySecureZero($values, "idSocio");
            if ($values["idSocio"] == 0) {$values["idSocio"]=null;}
            $values["idChargeCode"] = keySecureZero($values, "idChargeCode");
            if ($values["idChargeCode"] == 0) {$values["idChargeCode"]=null;}
            $values["idTypeDirection"] = keySecureValInArray($values, "idTypeDirection",['1','2']);
            if ($values["idTypeDirection"] == "") {throw new Exception(lang("api_error_1071"), 1071);}
            $values["idTypeItem"] = keySecureValInArray($values, "idTypeItem",['1','2']);
            if ($values["idTypeItem"] == "") {throw new Exception(lang("api_error_1072"), 1072);}
            $fields = ["Id_socio" => $values["idSocio"],"Id" => $values["idChargeCode"],"Id_clasificacion" => $values["idTypeDirection"],"Id_type" => $values["idTypeItem"]];
            $ret = API_callAPIfields("/Mediya/Mensajes/", $fields);
            $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function farmalinkreceta($values){
        try {
            $values["fechaNacimiento"]=keySecureString($values,"fechaNacimiento");
            if ($values["fechaNacimiento"] == "") {throw new Exception(lang("api_error_1006"), 1006);}
             $values["panswiss"]=keySecureString($values,"panswiss");
            if ($values["panswiss"] == "") {throw new Exception(lang("api_error_1074"), 1074);}
            $values["dni"]=keySecureZero($values,"dni");
            if ($values["dni"] == "0") {throw new Exception(lang("api_error_1001"), 1001);}
             $values["sexo"]=keySecureString($values,"sexo");
            if ($values["sexo"] == "") {throw new Exception(lang("api_error_1002"), 1002);}
            $values["nombre"]=keySecureString($values,"nombre");
            if ($values["nombre"] == "") {throw new Exception(lang("api_error_1007"), 1007);}
            $values["apellido"]=keySecureString($values,"apellido");
            if ($values["apellido"] == "") {throw new Exception(lang("api_error_1008"), 1008);}
            $values["idChargeCode"] = keySecureZero($values, "idChargeCode");
            if ($values["idChargeCode"] == 0) {throw new Exception(lang("api_error_1073"), 1073);}
            $values["idUser"] = keySecureNumbers($values, "idUser");
            if ($values["idUser"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}

			$fechanacimiento=$values["fechaNacimiento"];
			$panswiss=$values["panswiss"];
			$dni=$values["dni"];
			$sexo=$values["sexo"];
			$nombre=$values["nombre"];
			$apellido=$values["apellido"];
            $id_charge_code=$values["idChargeCode"];
            $idUser=$values["idUser"];

            $profile = getUserProfile($this, $idUser);

			$DOCTORS=$this->createModel(MOD_TELEMEDICINA,"Doctors","Doctors");
            $doctor=$DOCTORS->get(array("page"=>1,"where"=>"username='".$profile["data"][0]["username"]."'"));

            $CHARGES_CODES = $this->createModel(MOD_TELEMEDICINA, "Charges_codes", "Charges_codes");
            $cCode=$CHARGES_CODES->get(array("where"=>"id=".$id_charge_code));
			if ((int)$cCode["totalrecords"]==0) {throw new Exception("No se encontró el beneficiario");}
			$asociado=getUserMediya($this,(int)$cCode["data"][0]["id_club_redondo"]);
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
    public function atencionespontanea($values){
        try {
            $values["idUser"] = keySecureNumbers($values, "idUser");
            if ($values["idUser"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
            $values["dni"]=keySecureZero($values,"dni");
            $values["idSocio"]=keySecureZero($values,"idSocio");
            $fields = ["idSocio" => $values["idSocio"],"dni" => $values["dni"],"id_user" => $values["idUser"]];
            $ret = API_callAPIfields("/Mediya/AtencionEspontanea/", $fields);
            $ret = json_decode($ret, true);

            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function grabarordenmedica($values){
        $values["idUser"] = keySecureNumbers($values, "idUser");
        if ($values["idUser"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
        $values["idChargeCode"] = keySecureNumbers($values, "idChargeCode");
        if ($values["idChargeCode"] == "0") {throw new Exception(lang("api_error_1073"), 1073);}
        $values["carbonCopy"] = keySecureValInArray($values, "carbonCopy",["0","1"]);
        if ($values["carbonCopy"] == "") {throw new Exception(lang("api_error_1076"), 1076);}
        $values["Message"]=keySecureString($values,"Message");
        if ($values["Message"] == "") {throw new Exception(lang("api_error_1060"), 1060);}
        $values["Raw_data"]=keySecureString($values,"Raw_data");
        if ($values["Raw_data"] == "") {throw new Exception(lang("api_error_1075"), 1075);}
        try {
            $params=[
                "description"=>"Orden emitida el: ".date(FORMAT_DATE_DMYHMS, strtotime($this->now)),
                "message"=>$values["Message"],
                "raw_data"=>$values["Raw_data"],
                "viewed"=>0,
                "id_charge_code"=>$values["idChargeCode"],
                "id_type_item"=>2,
                "id_type_direction"=>2,
                "id_operator"=>$values["idUser"],
                "id_user"=>$values["idUser"],
                "carbon_copy"=>$values["carbonCopy"],
                "id_type_vademecum"=>-1
            ];
            $result = API_callAPIfields("/Mediya/Message", $params);
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function grabarreceta($values){
        $values["idUser"] = keySecureNumbers($values, "idUser");
        if ($values["idUser"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
        $values["idChargeCode"] = keySecureNumbers($values, "idChargeCode");
        if ($values["idChargeCode"] == "0") {throw new Exception(lang("api_error_1073"), 1073);}
        $values["Message"]=keySecureString($values,"Message");
        if ($values["Message"] == "") {throw new Exception(lang("api_error_1060"), 1060);}
        try {
            $params=[
                "description"=>"Receta emitida el: ".date(FORMAT_DATE_DMYHMS, strtotime($this->now)),
                "message"=>$values["Message"],
                "raw_data"=>"",
                "viewed"=>0,
                "id_charge_code"=>$values["idChargeCode"],
                "id_type_item"=>2,
                "id_type_direction"=>2,
                "id_operator"=>$values["idUser"],
                "id_user"=>$values["idUser"],
                "carbon_copy"=>0,
                "type_media"=>"pdf",
                "id_type_vademecum"=>-1
            ];
            $result = API_callAPIfields("/Mediya/Message", $params);
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function grabaratencion($values){
        $values["idUser"] = keySecureNumbers($values, "idUser");
        if ($values["idUser"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
        $values["idOperatorTask"] = keySecureNumbers($values, "idOperatorTask");
        if ($values["idOperatorTask"] == "0") {throw new Exception(lang("api_error_1077"), 1077);}

        try {
            $result = API_callAPIfields("/Mediya/GrabarAtencion", $values);
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function cambiarestadodoctor($values){
        $values["idUser"] = keySecureNumbers($values, "idUser");
        if ($values["idUser"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
        $values["Estado"] = keySecureValInArray($values, "Estado",['ATENDER','DESCANSAR']);
        if ($values["Estado"] == "") {throw new Exception(lang("api_error_1078"), 1078);}
        $params=["Id_user"=>$values["idUser"],"Estado"=>$values["Estado"]];
        try {
            $result = API_callAPIfields("/Mediya/CambiarEstadoDoctor", $params);
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function estadocolaatencion($values){
        $values["idUser"] = keySecureNumbers($values, "idUser");
        if ($values["idUser"] == "0") {throw new Exception(lang("api_error_1067"), 1067);}
        $params=["Id_user"=>$values["idUser"]];
        try {
            $result = API_callAPIfields("/Mediya/EstadoColaAtencion", $params);
            $result = json_decode($result, true);
            return $result;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
        /*



        $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
        $profile=getUserProfile($this,$values["id_user_active"]);
        $inGroup="";
        foreach($profile["data"][0]["groups"] as $group){
            if ($inGroup!=""){$inGroup.=",";}
            $inGroup.=("'".$group["code"]."'");
        };
        $charge_code=$CHARGES_CODES->get(
            array(
                "fields"=>"count(id) as total,datediff(second,min(created),getdate()) as seconds, dbo.fc_formatSeconds(datediff(second,min(created),getdate()),'s') as elapsed",
                "where"=>"offline IS null AND id_operator_task IS null"
            )
        );
        $seconds=(int)$charge_code["data"][0]["seconds"];
        $html="<span class='badge badge-primary m-0'>Nadie espera</span>";
        $pacientes=0;
        if ($seconds!=0) {
            $pacientes=$charge_code["data"][0]["total"];
            $class="badge badge-info";
            $moreInfo="Normal - menos de 15 minutos";
            if ($seconds>(60*15)){$class="badge badge-warning m-0 blink_me";$moreInfo="ALERTA - más de 15 minutos";}
            $html = "<span class='" . $class . "' style='font-size:12px;'>" . $pacientes . " pacientes en espera, desde hace " . $charge_code["data"][0]["elapsed"] . "</span>";
            $html .= "<span class='badge badge-primary m-0' style='font-size:12px;'>".$moreInfo."</span>";
        }
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"",
            "table"=>$this->table,
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>$html,
            "pacientes"=>$pacientes,
            "active"=>$active
        );
        */



    }    
}
