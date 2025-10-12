<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class CR_Vendedor extends MY_Model {
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
            $this->view="dbclub.dbo.CR_Vendedor";
            $values["fields"]="Id as id,Nombre,NroDocumento,Password,Estado,Email,Admin,IdEmpresa";
            $values["order"]="Nombre ASC";
            $values["title"]=lang('m_CR_Vendedor');
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"Nombre","format"=>"text"),
                array("field"=>"NroDocumento","format"=>"number"),
                array("field"=>"Estado","format"=>"status"),
                array("field"=>"Email","format"=>"email"),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("Nombre","NroDocumento","Email")),
                array("name"=>"browser_id_empresa", "operator"=>"=","fields"=>array("IdEmpresa")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Empresa</span>".comboEmpresa($this),
            );

            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $this->view="dbclub.dbo.CR_Vendedor";
            $values["interface"]=(MOD_DBCENTRAL."/CR_Vendedor/abm");
            $values["page"]=1;
            $values["fields"]="Id as id,Nombre,NroDocumento,Password,Estado,Email,Admin,IdEmpresa";
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters_empresa=array(
                "model"=>(MOD_DBCENTRAL."/Empresa"),
                "table"=>"Empresa",
                "name"=>"IdEmpresa",
                "class"=>"form-control validate dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"IdEmpresa"),
                "id_field"=>"Id",
                "description_field"=>"RazonSocial",
                "sql"=>"select * from dbclub.dbo.Empresa WHERE estado='VIG' ORDER BY 3 ASC",
            );
            $values["controls"]=array(
                "IdEmpresa"=>getCombo($parameters_empresa,$this),
            );
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            $this->table="dbclub.dbo.CR_Vendedor";
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                $fields = array(
                    'Nombre' => $values["Nombre"],
                    'NroDocumento' => $values["NroDocumento"],
                    'Password' => $values["Password"],
                    'Estado' => "VIG",
                    'Email' => $values["Email"],
                    'Admin' => 0,
	                "IdEmpresa"=>secureEmptyNull($values,"IdEmpresa"),
                );
            } else {
                $fields = array(
                    'Nombre' => $values["Nombre"],
                    'NroDocumento' => $values["NroDocumento"],
                    'Password' => $values["Password"],
                    'Email' => $values["Email"],
	                "IdEmpresa"=>secureEmptyNull($values,"IdEmpresa"),
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
