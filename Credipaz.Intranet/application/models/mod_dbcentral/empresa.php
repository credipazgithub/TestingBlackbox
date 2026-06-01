<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Empresa extends MY_Model {
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
	public function StopGo($values){
	    $result=null;
		$sql=("EXEC DBClub.dbo.NS_StopGo_Empresa ".trim($values["id"]));
		$result=$this->getRecordsAdHoc($sql);
		return $result;
	}
    public function brow($values){
        try {
            $this->view="dbclub.dbo.Empresa";
            $values["fields"]="Id as id,*";
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
                array(
                    "field"=>"modo","forcedlabel"=>"",
                    "html"=>"<a href='#' class='btn btn-sm btn-raised btn-dark btn-stop-go' data-id='|ID|'>Stop / Go</a>",
                    "format"=>"html#record"),
                array("field"=>"Stop","format"=>"yesno"),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("RazonSocial","CUIT","Telefono","Direccion","Localidad")),
                array("name"=>"browser_Estado", "operator"=>"=","fields"=>array("Estado")),
                array("name"=>"browser_Stop", "operator"=>"=","fields"=>array("Stop")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Estado</span><select id='browser_Estado' name='browser_Estado' class='form-control browser_Estado'><option value=''>[Todos]</option><option value='VIG'>Vigentes</option><option value='ANU'>Anuladas</option><option value='INH'>Inhabilitadas</option></select>",
                "<span class='badge badge-primary'>¿STOP?</span><select id='browser_Stop' name='browser_Stop' class='form-control browser_Stop'><option value=''>[Todas]</option><option value='S'>Solo con Stop</option><option value='N'>Solo sin Stop</option></select>",
            );
            $values["conditionalBackground"]=array(
                array("field"=>"Estado","value"=>"VIG","color"=>"lightgreen"),
                array("field"=>"Estado","value"=>"ANU","color"=>"pink"),
                array("field"=>"Estado","value"=>"INH","color"=>"cyan"),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $this->view="dbclub.dbo.Empresa";
            $values["interface"]=(MOD_DBCENTRAL."/Empresa/abm");
            $values["fields"]="Id as id, *";
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters_plan=array(
                "model"=>(MOD_DBCENTRAL."/Planes"),
                "table"=>"Planes",
                "name"=>"idPlan",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>$values["records"]["data"][0]["idPlan"],
                "id_field"=>"id",
                "description_field"=>"nombre",
                "sql"=>"SELECT * FROM dbclub.dbo.Planes ORDER BY nombre ASC",
            );

            $parameters_empresa=array(
                "model"=>(MOD_DBCENTRAL."/EmpresaFactura"),
                "table"=>"EmpresaFactura",
                "name"=>"idEmpFactura",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>$values["records"]["data"][0]["idEmpFactura"],
                "id_field"=>"id",
                "description_field"=>"Descripcion",
                "sql"=>"SELECT * FROM DBClub.cob.EmpresaFactura ORDER BY descripcion ASC",
            );

            $values["controls"]=array(
                "IdPlanes"=>getCombo($parameters_plan,$this),
                "IdEmpresas"=>getCombo($parameters_empresa,$this),
            );

            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            $this->table="dbclub.dbo.Empresa";
            if (!isset($values["Id"])){$values["Id"]=0;}
            $id=(int)$values["Id"];
            $fields = array(
                'Nombre' => $values["Nombre"],
                'RazonSocial' => $values["RazonSocial"],
                'CUIT' => $values["CUIT"],
                'Telefono' => $values["Telefono"],
                'Direccion' => $values["Direccion"],
                'Localidad' => $values["Localidad"],
                'Estado' => $values["Estado"],
                'ImporteCuota' => $values["ImporteCuota"],
                'ImporteCuotaLista2' => $values["ImporteCuotaLista2"],
                'AdicMayor1' => $values["AdicMayor1"],
                'AdicMayor2' => $values["AdicMayor2"],
                'AdicMenor1' => $values["AdicMenor1"],
                'AdicMenor2' => $values["AdicMenor2"],
                'Liquidacion' => $values["Liquidacion"],
                'idPlan' => $values["idPlan"],
                'idEmpFactura' => $values["idEmpFactura"],
                'EstadoInicial' => $values["EstadoInicial"],
                'DiasVtoFactura' => $values["DiasVtoFactura"],
            );

            	log_message("error", "RELATED ".json_encode($fields,JSON_PRETTY_PRINT));


            if($id!=0){$fields["Id"] = $id;}
			$saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){$t=0;}
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
