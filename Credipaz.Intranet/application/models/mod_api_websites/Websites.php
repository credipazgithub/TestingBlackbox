<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Websites extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function landing($values){
        try {
            $values["idCanalRegistro"] = keySecureZero($values, "idCanalRegistro");
            if ($values["idCanalRegistro"] == 0) {throw new Exception(lang("api_error_1022"), 1022);}
            $idCanalRegistro = (int) $values["idCanalRegistro"];

            /*
            $values["area"] = keySecureZero($values, "area");
            if ($values["area"] == 0) {throw new Exception(lang("api_error_1031"), 1031);}
            if (strlen($values["area"]) > 4) {throw new Exception(lang("api_error_1031"), 1031);}
            $values["telefono"] = keySecureZero($values, "telefono");
            if ($values["telefono"] == 0) {throw new Exception(lang("api_error_1032"), 1032);}
            if (strlen($values["telefono"]>8)){throw new Exception(lang("api_error_1032"), 1032);}
            */

            $fields=array(
                "Circuito" => 1, // 1 circuito largo / valor fijo
                "Producto" =>$idCanalRegistro,
                "Origen" => 4,
                "Nombre" => (string) $values["nombre"],
                "Apellido" => (string) $values["apellido"],
                "Tipodocumento" => "DNI",
                "Documento" => (string) $values["dni"],
                "Sexo" => (string) $values["sexo"],
                "Email" => (string) $values["email"],
                "Prefijo" => (string) $values["area"],
                "Telefono" => (string) $values["telefono"],
                "Usuario" => "Landing",
                "IdTransaccion" => 0
            );
            if (isset($values["idSucursal"])) {
                $values["idSucursal"] = keySecureZero($values, "idSucursal");
                if ($values["idSucursal"] == 0) {$values["idSucursal"] = 100;}
            }
            $fields["IDSucursal"] = (int)$values["idSucursal"];

            /*En caso de no venir con sucursal, siempre redefinir los envios a sucursal como canal digital*/
            if ((int) $fields["IDSucursal"]==100) {
                switch ($idCanalRegistro) {
                    case 551:
                        $fields["Producto"] = 551; 
                        break;
                    case 552:
                        $fields["Producto"] = 552; 
                        break;
                    case 14:
                        $fields["Producto"] = 14; //forzardo por cambio de Alfredo 10/3/2025
                        break;
                    case 141:
                        $fields["Producto"] = 241;
                        break;
                    case 15:
                    case 151:
                        $fields["Producto"] = 251;
                        break;
                    case 16:
                    case 161:
                        $fields["Producto"] = 261;
                        break;
                    case 18:
                    case 181:
                        $fields["Producto"] = 281;
                        break;
                }
            }
            $headers = array('Content-Type:application/json','Authorization: Bearer ');
	        $ret = API_callAPI("/Landing/Insertar/",$headers,json_encode($fields));
	        $ret = json_decode($ret, true);
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
