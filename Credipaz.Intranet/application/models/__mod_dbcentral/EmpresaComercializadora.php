<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class EmpresaComercializadora extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
	public function lock($values){
			return true;
	}
	public function unlock($values){
			return true;
	}
    public function brow($values){
        try {
            $this->view="DBCentral.dbo.EmpresaComercializadora";
            $values["fields"]="Id as id,Nombre,RazonSocial,CUIT,Telefono,Direccion,Localidad,Estado";
            $values["order"]="RazonSocial ASC";
            $values["title"]=lang('m_Empresa');
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"RazonSocial","format"=>"text"),
                array("field"=>"CUIT","format"=>"number"),
                array("field"=>"Estado","format"=>"status"),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("RazonSocial","CUIT","Telefono","Direccion","Localidad")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $this->view="DBCentral.dbo.EmpresaComercializadora";
            $values["interface"]=(MOD_DBCENTRAL."/EmpresaComercializadora/abm");
            $values["page"]=1;
            $values["fields"]="Id as id,Nombre,RazonSocial,CUIT,Telefono,Direccion,Localidad,Estado";
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            $this->table="DBCentral.dbo.EmpresaComercializadora";
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                $fields = array(
                    'Nombre' => $values["Nombre"],
                    'RazonSocial' => $values["RazonSocial"],
                    'CUIT' => $values["CUIT"],
                    'Telefono' => $values["Telefono"],
                    'Direccion' => $values["Direccion"],
                    'Localidad' => $values["Localidad"],
                    'Estado' => "VIG",
                );
            } else {
                $fields = array(
                    'Nombre' => $values["Nombre"],
                    'RazonSocial' => $values["RazonSocial"],
                    'CUIT' => $values["CUIT"],
                    'Telefono' => $values["Telefono"],
                    'Direccion' => $values["Direccion"],
                    'Localidad' => $values["Localidad"],
                );
            }
			$saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){$t=0;}
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
