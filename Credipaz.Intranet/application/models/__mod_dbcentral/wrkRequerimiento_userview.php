<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class wrkRequerimiento_userview extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $this->view="dbcentral.dbo.wrkRequerimiento";
            $profile=getUserProfile($this,$values["id_user_active"]);
            if ($values["where"]!=""){$values["where"].=" AND ";}
            $values["where"].="sUsuarioSolicitante='".$profile["data"][0]["username"]."'";
            $values["fields"]="*, nID as id";
            $values["order"]="dFechaIngreso DESC";
            $values["title"]=lang('m_wrkRequerimiento_userview');
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"dFechaIngreso","format"=>"datetime"),
                array("field"=>"sLKSector","format"=>"status"),
                array("field"=>"sLKEstado","format"=>"warning"),
                array("field"=>"sAsunto","format"=>"text"),
                array("field"=>"sHorasEstimadas","format"=>"number"),
                array("field"=>"sPrioridad","format"=>"danger"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("sAsunto","sDescripcion")),
                array("name"=>"browser_estado", "operator"=>"=","fields"=>array("sLKEstado")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Tipo</span>".comboTicketsEstados($this),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        $values["noedit"]=true;
        $WRKREQUERIMIENTO=$this->createModel(MOD_DBCENTRAL,"wrkRequerimiento","wrkRequerimiento");
        return $WRKREQUERIMIENTO->edit($values);
    }
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'nIDSucursal' => 0,
                        'sLKSector' => $values["sLKSector"],
                        'nIDSubsistema' => $values["nIDSubsistema"],
                        'nIDEspecFuncional' => 0,
                        'sUsuarioSolicitante' => $values["username_active"],
                        'sUsuarioAprobo' => null,
                        'nIDTipo'=>secureEmptyNull($values,"nIDTipo"),
                        'sDescripcion'=>$values["sDescripcion"],
                        'sFuncionalidad' => null,
                        'sObservaciones' => null,
                        'sAsunto' => $values["sAsunto"],
                        'sLKEstado' => "ING",
                        'dFechaModificacion'=>null,
                        'dFechaCreacion'=>$this->now,
                        'dFechaIngreso'=>$this->now,
                        'dFechaDefinicion' => null,
                        'dFechaInicio' => null,
                        'dFechaCierre' => null,
                        'dFechaEF' => null,
                        'ISuspendido' => null,
                        'sHorasEstimadas' => "-",
                        'sPrioridad' => "M"
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
