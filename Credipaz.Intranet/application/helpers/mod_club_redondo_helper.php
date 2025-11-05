<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboTypeBeneficios($obj,$get=array("order"=>"description ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_CLUB_REDONDO."/Type_beneficios"),
        "table"=>"type_beneficios",
        "name"=>"browser_id_type_beneficio",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
function comboTypeCategories($obj,$get=array("order"=>"description ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_CLUB_REDONDO."/Type_categories"),
        "table"=>"type_categories",
        "name"=>"browser_id_type_category",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
function comboTypeProducts($obj,$get=array("order"=>"description ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_CLUB_REDONDO."/Type_products"),
        "table"=>"type_products",
        "name"=>"browser_id_type_product",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}
function comboTypeExecutions($obj,$get=array("order"=>"description ASC","pagesize"=>-1)){
    $parameters=array(
        "model"=>(MOD_CLUB_REDONDO."/Type_executions"),
        "table"=>"type_executions",
        "name"=>"browser_id_type_execution",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"id",
        "description_field"=>"description",
        "get"=>$get,
    );
    return getCombo($parameters,$obj);
}

function getUserClubRedondo($obj,$id){
    $result=[];
    $sql="SELECT * FROM DBClub.dbo.Socio WHERE IdSocio=".$id;
    $socio=$obj->getRecordsAdHoc($sql);
    if(isset($socio[0]["IdSocio"])){
        $sql="SELECT * FROM DBClub.dbo.Persona WHERE IdPersona=".$socio[0]["IdPersona"];
        $persona=$obj->getRecordsAdHoc($sql);
        if(isset($persona[0]["IdPersona"])){
            //$REL_PERSONA_ADICIONALES=$obj->createModel(MOD_TELEMEDICINA,"Rel_persona_adicionales","Rel_persona_adicionales");
            //$rel=$REL_PERSONA_ADICIONALES->get(array("page"=>1,"where"=>"idPersona=".$persona[0]["IdPersona"]));
            /**
             * Resolver que telefono se muestra!
             */
            $sql = "SELECT TOP 1 * FROM DBClub.dbo.SocioTelefono WHERE Red='TLM' AND IdSocio=" . $socio[0]["IdSocio"] . " ORDER BY 1 ASC";
            $telefonos = $obj->getRecordsAdHoc($sql);
            foreach ($telefonos as $record) {
                $retTel = ($record["CodigoArea"]. $record["Numero"]);
            }
            if ($retTel == "") {
                $sql = "SELECT TOP 1 * FROM DBClub.dbo.SocioTelefono WHERE IdSocio=" . $socio[0]["IdSocio"] . " ORDER BY 1 ASC";
                $telefonos = $obj->getRecordsAdHoc($sql);
                foreach ($telefonos as $record) {
                    $retTel = ($record["CodigoArea"] . $record["Numero"]);
                }
            }
            if ($retTel == "") {$retTel = $persona[0]["Telefono"];}
            $result["obra_social"]="";
            $result["obra_social_plan"]="";
            $result["nro_obra_social"]="";

            $result["id_persona"]=$persona[0]["IdPersona"];
            $result["Apellido"]=$persona[0]["Apellido"];
            $result["Nombre"]=$persona[0]["Nombre"];

            $result["ApellidoNombre"]=$persona[0]["Apellido"];
            if ($result["ApellidoNombre"]!=""){$result["ApellidoNombre"].=", ";}
            $result["ApellidoNombre"].=$persona[0]["Nombre"];
            $result["PANClub"]=$socio[0]["PANsocio"];
			$result["PANSwiss"]="";
            $result["Estado"]=$socio[0]["Estado"];
            $result["TipoSocio"]=$socio[0]["TipoSocio"];
            $result["ClubRedondo"]=$socio[0]["IdSocio"];
            $result["DNI"]=$persona[0]["NroDocumento"];
            $result["CUIL"]=$persona[0]["CUIL"];
            $result["Sexo"]=$persona[0]["Sexo"];
            $result["Email"]=$persona[0]["Email"];
            $result["Telefono"]= $retTel;
            $result["FechaAlta"]=explode('T', $socio[0]["FechaAlta"])[0];
            $result["FechaNacimiento"]=explode('T', $persona[0]["FechaNacimiento"])[0];
            $result["Empresa"]=(int)$socio[0]["Empresa"];

			$sql="SELECT * FROM ".MOD_CLUB_REDONDO."_vw_credencialSwiss WHERE NroDocumento='".$result["DNI"]."'";
            $ret=$obj->getRecordsAdHoc($sql);
			if (isset($ret[0])) {$result["PANSwiss"]=$ret[0]["NroCredencial"];}
			/*Si Empresa=999, es empleado de Credipaz*/

            if (strlen($result["FechaAlta"])!=10){$result["FechaAlta"]=null;}
            if (strlen($result["FechaNacimiento"])!=10){$result["FechaNacimiento"]=null;}
        } else {
            $result=null;
        }
    } else {
       $result=null;
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
function getIdUserClubRedondo($obj,$doc){
    $result=[];
    if($doc==""){$doc=-999999;}
    $sql="SELECT * FROM DBClub.dbo.Persona WHERE nrodocumento=".$doc;
    $persona=$obj->getRecordsAdHoc($sql);
    if(isset($persona[0]["IdPersona"])){
        $sql="SELECT * FROM DBClub.dbo.Socio WHERE IdPersona=".$persona[0]["IdPersona"]." AND Estado='VIG'";
        $socio=$obj->getRecordsAdHoc($sql);
        if(isset($socio[0]["IdSocio"])){
            //$REL_PERSONA_ADICIONALES=$obj->createModel(MOD_TELEMEDICINA,"Rel_persona_adicionales","Rel_persona_adicionales");
            //$rel=$REL_PERSONA_ADICIONALES->get(array("page"=>1,"where"=>"idPersona=".$persona[0]["IdPersona"]));
            
            $result["obra_social"]="";
            $result["obra_social_plan"]="";
            $result["nro_obra_social"]="";

            $result["id_persona"]=$persona[0]["IdPersona"];
            $result["ApellidoNombre"]=$persona[0]["Apellido"];
            if ($result["ApellidoNombre"]!=""){$result["ApellidoNombre"].=", ";}
            $result["ApellidoNombre"].=$persona[0]["Nombre"];
            $result["PANClub"]=$socio[0]["PANsocio"];
            $result["Estado"]=$socio[0]["Estado"];
            $result["TipoSocio"]=$socio[0]["TipoSocio"];
            $result["ClubRedondo"]=$socio[0]["IdSocio"];
            $result["DNI"]=$persona[0]["NroDocumento"];
            $result["CUIL"]=$persona[0]["CUIL"];
            $result["Sexo"]=$persona[0]["Sexo"];
            $result["Email"]=$persona[0]["Email"];
            $result["Telefono"]=$persona[0]["Telefono"];
            $result["FechaAlta"]=explode('T', $socio[0]["FechaAlta"])[0];
            $result["FechaNacimiento"]=explode('T', $persona[0]["FechaNacimiento"])[0];
            if (strlen($result["FechaAlta"])!=10){$result["FechaAlta"]=null;}
            if (strlen($result["FechaNacimiento"])!=10){$result["FechaNacimiento"]=null;}
        } else {
            $result=null;
        }
    } else {
       $result=null;
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
