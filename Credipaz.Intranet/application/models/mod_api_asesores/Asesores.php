<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Asesores extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function listar($values){
        try {
 	        $fields=array("Id"=>(int)$values["id_user_active"]);
	        $headers = array('Content-Type:application/json','Authorization: Bearer ');
	        $ret = API_callAPI("/Asesores/GetRows/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
            $ret["code"]=$ret["codigo"];
            $ret["error"]=$ret["error"];
            $ret["status"]=$ret["estado"];
            $ret["message"]=$ret["mensaje"];
            $ret["function"]=$ret["function"];
            $ret["timestamp"]=date(FORMAT_DATE);

            /*controla acciones de habilitación del usuario en cuanto a empresario*/
            $eval=API_EvaluarHabilitado((string)$ret["records"][0]["idEmpresario"]);
            $ret["data"]["habilitado"]=$eval["habilitado"];
            $ret["data"]["detalle"]=$eval["detalle"];

            $ret["data"]["idAsesor"]=$ret["records"][0]["id"];
            $ret["data"]["username"]=$ret["records"][0]["username"];
            $ret["data"]["nombre"]=$ret["records"][0]["Nombre"];
            $ret["data"]["apellido"]=$ret["records"][0]["Apellido"];
            $ret["data"]["dni"]=$ret["records"][0]["documentNumber"];
            $ret["data"]["sexo"]=$ret["records"][0]["documentSex"];
            $ret["data"]["area"]=$ret["records"][0]["documentArea"];
            $ret["data"]["telefono"]=$ret["records"][0]["phone"];
            $ret["data"]["email"]=$ret["records"][0]["email"];

            unset($ret["id"]);
            unset($ret["codigo"]);
            unset($ret["error"]);
            unset($ret["estado"]);
            unset($ret["mensaje"]);
            unset($ret["logica"]);
            unset($ret["trace"]);
            unset($ret["funcion"]);
            unset($ret["records"]);

            if ($eval["habilitado"]) {
	            $grafico = API_callAPI("/Asesores/GetGrafico/",$headers,json_encode($fields));
	            $grafico = json_decode($grafico, true);
                $ret["grafico"]=$grafico["records"];

                $totales = API_callAPI("/Asesores/GetTotales/",$headers,json_encode($fields));
	            $totales = json_decode($totales, true);
                $ret["totales"]=$totales["records"];

                $nivel = API_callAPI("/Asesores/GetNivel/",$headers,json_encode($fields));
	            $nivel = json_decode($nivel, true);
                $ret["nivel"]=$nivel["records"];
            }
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function altaModificar($values,$fields=null){
        try {
            if (!isset($values["idAsesor"])) {throw new Exception(lang("api_error_1005"), 1005);}

            $values["dni"]=keySecureZero($values,"dni");
            if ($values["dni"]==0){throw new Exception(lang("api_error_1001"),1001);}

            if(!isset($values["sexo"])){$values["sexo"]="";}
            $values["sexo"]=strtoupper($values["sexo"]);
            switch($values["sexo"]){
               case "M":
               case "F":
                  break;
               default:
                  throw new Exception(lang("api_error_1002"),1002);
            }

            $values["area"]=keySecureZero($values,"area");
            if ($values["area"]==0){throw new Exception(lang("api_error_1003"),1003);}

            $values["telefono"]=keySecureZero($values,"telefono");
            if ($values["telefono"]==0){throw new Exception(lang("api_error_1004"),1004);}

            if(!isset($values["nombre"])){$values["nombre"]="";}
            if ($values["nombre"]==""){throw new Exception(lang("api_error_1007"),1007);}

            if(!isset($values["apellido"])){$values["apellido"]="";}
            if ($values["apellido"]==""){throw new Exception(lang("api_error_1008"),1008);}

            if(!isset($values["email"])){$values["email"]="";}
            if ($values["email"]==""){throw new Exception(lang("api_error_1009"),1009);}

 	        $fields=array(
                 "Id"=>(int)$values["idAsesor"],
                 "Id_type_user"=>87,
                 "Id_application"=>(int)$values["id_app"],
                 "Username"=>(string)$values["username"],
                 "Password"=>md5((string)$values["password"]),
                 "documentType"=>"DNI",
                 "documentNumber"=>(int)$values["dni"],
                 "documentSex"=>(string)$values["sexo"],
                 "documentArea"=>(int)$values["area"],
                 "documentPhone"=>(int)$values["telefono"],
                 "documentName"=>(string)$values["nombre"],
                 "documentSurname"=>(string)$values["apellido"],
                 "documentEmail"=>(string)$values["email"]
             );
	        $headers = array('Content-Type:application/json','Authorization: Bearer ');
	        $ret = API_callAPI("/Asesores/GuardarABM/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
            
            $ret["data"]["idAsesor"]=$ret["records"][0]["id"];
            $ret["data"]["created"]=$ret["records"][0]["created"];
            $ret["data"]["fum"]=$ret["records"][0]["fum"];
            $ret["data"]["username"]=$ret["records"][0]["username"];
            $ret["data"]["nombre"]=$ret["records"][0]["documentName"];
            $ret["data"]["dni"]=$ret["records"][0]["documentNumber"];
            $ret["data"]["sexo"]=$ret["records"][0]["documentSex"];
            $ret["data"]["area"]=$ret["records"][0]["documentArea"];
            $ret["data"]["telefono"]=$ret["records"][0]["phone"];
            $ret["data"]["email"]=$ret["records"][0]["email_vendedor"];

            unset($ret["id"]);
            unset($ret["logica"]);
            unset($ret["trace"]);
            unset($ret["funcion"]);
            unset($ret["records"]);

            $ret["timestamp"]=date(FORMAT_DATE);
            return $ret;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function cambiarEstado($values){
        try {
            $values["idAsesor"]=keySecureZero($values,"idAsesor");
            if ($values["idAsesor"]==0){throw new Exception(lang("api_error_1005"),1005);}
 	        $fields=array("Id"=>(int)$values["idAsesor"],"Id_type_user"=>87);
	        $headers = array('Content-Type:application/json','Authorization: Bearer ');
	        $ret = API_callAPI("/Asesores/".$values["endpoint"]."/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);

            $ret["code"]=$ret["codigo"];
            $ret["error"]=$ret["error"];
            $ret["status"]=$ret["estado"];
            $ret["message"]=$ret["mensaje"];
            $ret["function"]=$ret["funcion"];

            $ret["data"]["idAsesor"]=$ret["id"];

            unset($ret["id"]);
            unset($ret["logica"]);
            unset($ret["codigo"]);
            unset($ret["estado"]);
            unset($ret["mensaje"]);
            unset($ret["trace"]);
            unset($ret["funcion"]);
            unset($ret["records"]);

            $ret["timestamp"]=date(FORMAT_DATE);
            return $ret;
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
}
