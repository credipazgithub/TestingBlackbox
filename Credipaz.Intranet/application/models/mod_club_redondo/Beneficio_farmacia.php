<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Beneficio_farmacia extends MY_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $sql="SELECT * FROM DBClub.dbo.vw_farmacia ORDER BY Nombre ASC";
            $farmacias["data"]=$this->getRecordsAdHoc($sql);
            $values["records"]=$farmacias;
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>array(
                    "conditions"=>array(
                           array("field"=>"writeable","operator"=>"==","value"=>"1"),
                        )
                    ),
                "delete"=>array(
                    "conditions"=>array(
                           array("field"=>"writeable","operator"=>"==","value"=>"1"),
                        )
                    ),
                "offline"=>false,
            );
            /*fuerza no tener interface con el usuario... solo sis e administra por procso de integracion*/
            $values["buttons"] = array(
                "new" => false,
                "edit" => false,
                "delete" => false,
                "offline" => false
            );
            $values["columns"]=array(
                array("field"=>"Nombre","format"=>"text"),
                array("field"=>"Direccion","format"=>"text"),
                array("field"=>"Localidad","format"=>"text"),
                array("field"=>"Zona","format"=>"text"),
                array("field"=>"Telefonos","format"=>"text"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            //$profile=getUserProfile($this,$values["id_user_active"]);
            $values["interface"]=(MOD_CLUB_REDONDO."/beneficio_farmacia/abm");
            $sql="SELECT * FROM DBClub.dbo.vw_farmacia WHERE id=".$values["id"];
            $farmacias["data"]=$this->getRecordsAdHoc($sql);
            $values["records"]=$farmacias;
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            if (!isset($values["code"])){$values["code"]=null;}
            $id=(int)$values["id"];
            if($id==0){
                if ($fields==null) {
                    $fields = array(
                        'Nombre' => $values["Nombre"],
                        'Direccion' => $values["Direccion"],
                        'Localidad' => $values["Localidad"],
                        'Zona' => $values["Zona"],
                        'Telefonos' => $values["Telefonos"],
                        'Sinopsys' => $values["Sinopsys"],
                        'Lat' => $values["Lat"],
                        'Lng' => $values["Lng"],
                        'Descuento' => $values["Descuento"],
                    );
                }
            } else {
                if ($fields==null) {
                    $fields = array(
                        'Nombre' => $values["Nombre"],
                        'Direccion' => $values["Direccion"],
                        'Localidad' => $values["Localidad"],
                        'Zona' => $values["Zona"],
                        'Telefonos' => $values["Telefonos"],
                        'Sinopsys' => $values["Sinopsys"],
                        'Lat' => $values["Lat"],
                        'Lng' => $values["Lng"],
                        'Descuento' => $values["Descuento"],
                    );
                }
            }
            $BENEFICIO_FARMACIA=$this->createModel(MOD_DBCENTRAL,"Beneficio_Farmacia","DBClub.dbo.Beneficio_Farmacia");
            $BENEFICIO_FARMACIA->save(array("id"=>$id,$fields));
            $data=array("id"=>0);
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data,
                );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function delete($values){
        try {
            $sql="DELETE DBClub.dbo.Beneficio_Farmacia WHERE idPrestador=".$values["id"];
            $this->execAdHoc($sql);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_delete'),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null
                );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
}
