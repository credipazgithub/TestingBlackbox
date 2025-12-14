<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class prospecto extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function save($values,$fields=null){
        try {
            $this->table="dbCentral.dbo.prospecto";
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'Origen' => $values["Origen"],
                        'Tipo' => $values["Tipo"],
                        'Fecha'=>$this->now,
                        'Nombre' => $values["Nombre"],
                        'Documento' => $values["Documento"],
                        'Sexo' => $values["Sexo"],
                        'Email' => $values["Email"],
                        'Telefono' => $values["Telefono"],
                        'Resultado' => $values["Resultado"],
                        'Identificacion' => 0,
                        'Observaciones' => $values["Observaciones"],
                        'IDVendedor' => $values["IDVendedor"],
                        'IdSucursal' => $values["IdSucursal"],
                        'Usuario' => $values["Usuario"],
                        'Contacto' => "",
                        'FechaContacto' => null,
                    );
                }
            }
            $saved=parent::save($values,$fields);
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

}
