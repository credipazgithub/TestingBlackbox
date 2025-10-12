<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Requests extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
	public function brow($values){
        try {
            $this->view="vw_requests";
			if ($values["where"]!=""){$values["where"].=" AND ";}
			$values["where"].=" id_type_status=2";
            $values["order"]="created DESC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"created","format"=>"datetime"),
                array("field"=>"type_request","format"=>"type"),
                array("field"=>"ApeNom","format"=>"text"),
                array("field"=>"Documento","format"=>"code"),
                array("field"=>"type_status","format"=>"type"),
                array("field"=>"controlPoint","format"=>"warning"),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("controlPoint","ApeNom","documento")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
	        $COMMONS=$this->createModel(MOD_EXTERNAL,"Commons","Commons");
            $profile=getUserProfile($this,$values["id_user_active"]);
            $values["godaction"]=(evalPermissions("GODACTION",$profile["data"][0]["groups"]));
            $this->view="vw_requests_fullrecord";
            $values["interface"]=(MOD_ONBOARDING."/requests/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters_id_type_status=array(
                "model"=>(MOD_ONBOARDING."/Type_status"),
                "table"=>"type_status",
                "name"=>"id_type_status",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_status"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id IN (2,3)","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_Nacionalidad=array(
                "model"=>(MOD_EXTERNAL."/Commons"),
                "name"=>"Nacionalidad",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"Nacionalidad"),
                "id_field"=>"codigo",
                "description_field"=>"descripcion",
				"records"=>$COMMONS->lookup(array("tableType"=>"Nacionalidad"))
            );
            $parameters_EstadoCivil=array(
                "model"=>(MOD_EXTERNAL."/Commons"),
                "name"=>"EstadoCivil",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"EstadoCivil"),
                "id_field"=>"codigo",
                "description_field"=>"descripcion",
				"records"=>$COMMONS->lookup(array("tableType"=>"EstadoCivil"))
            );
            $parameters_Vivienda=array(
                "model"=>(MOD_EXTERNAL."/Commons"),
                "name"=>"Vivienda",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"Vivienda"),
                "id_field"=>"codigo",
                "description_field"=>"descripcion",
				"records"=>$COMMONS->lookup(array("tableType"=>"TipoVivienda"))
            );
            $parameters_Ocupacion=array(
                "model"=>(MOD_EXTERNAL."/Commons"),
                "name"=>"Ocupacion",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"Ocupacion"),
                "id_field"=>"codigo",
                "description_field"=>"descripcion",
				"records"=>$COMMONS->lookup(array("tableType"=>"Ocupacion"))
            );
            $parameters_iva=array(
                "model"=>(MOD_EXTERNAL."/Commons"),
                "name"=>"iva",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"iva"),
                "id_field"=>"codigo",
                "description_field"=>"descripcion",
				"records"=>$COMMONS->lookup(array("tableType"=>"CondIVA"))
            );
            $parameters_Rubro=array(
                "model"=>(MOD_EXTERNAL."/Commons"),
                "name"=>"Rubro",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"Rubro"),
                "id_field"=>"codigo",
                "description_field"=>"descripcion",
				"records"=>$COMMONS->lookup(array("tableType"=>"RubroLaboral"))
            );
            $parameters_Provincia=array(
                "model"=>(MOD_EXTERNAL."/Commons"),
                "name"=>"Provincia",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"Provincia"),
                "id_field"=>"codigo",
                "description_field"=>"descripcion",
				"records"=>$COMMONS->lookup(array("tableType"=>"Provincia"))
            );

            $values["controls"]=array(
                "id_type_status"=>getCombo($parameters_id_type_status,$this),
				"Nacionalidad"=>getCombo($parameters_Nacionalidad,$this),
				"EstadoCivil"=>getCombo($parameters_EstadoCivil,$this),
				"Provincia"=>getCombo($parameters_Provincia,$this),
				"Vivienda"=>getCombo($parameters_Vivienda,$this),
				"iva"=>getCombo($parameters_iva,$this),
				"Ocupacion"=>getCombo($parameters_Ocupacion,$this),
				"Rubro"=>getCombo($parameters_Rubro,$this),
				"ProvinciaEmpresa"=>getCombo($parameters_Provincia,$this),
            );
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
