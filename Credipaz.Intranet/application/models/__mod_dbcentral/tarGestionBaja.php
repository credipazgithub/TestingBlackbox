<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class tarGestionBaja extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function save($values,$fields=null){
        try {
            $this->table="dbCentral.dbo.tarGestionBaja";
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'nIDSucursal' => 100,
                        'sCodigoTarjeta' => $values["codigo_tarjeta"],
                        'dFecha'=>$this->now,
                        'sResponsable' => " ",
                        'sComentarios' => $values["sComentarios"],
                        'sMotivos' => (int)$values["sMotivos"],
                        'sLKEstado'=>"PEN",
                        'sProcesadoPor'=>null,
                        'sResueltoPor' => null,
                        'sComentariosResolucion' => null,
                        'dFechaResolucion'=>null,
                        'sContacto' => $values["telefono"],
                        'nNivelResponsable' => 2,
                        'slkTipoBaja'=>"CLI",
                        'sCalificacionCliente'=>" ",
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
