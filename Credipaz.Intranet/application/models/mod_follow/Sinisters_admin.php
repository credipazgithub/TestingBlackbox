<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Sinisters_admin extends MY_Model {
	public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
			$is_doctor=($profile["data"][0]["id_doctor"]!="" and $profile["data"][0]["offline_doctor"]=="" );
            $this->view="vw_sinisters";
            $values["order"]="created DESC";
            $values["records"]=$this->get($values);
			$values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
            );

            $values["columns"]=array(
                array("field"=>"type_priority","format"=>"type"),
                array("field"=>"days_accident","format"=>"number"),
                array("field"=>"created","forcedlabel"=>"input","format"=>"date"),
                array("field"=>"full_sinister","format"=>"code"),
                array("field"=>"limit","format"=>"code"),
                array("forcedlabel"=>"patient","field"=>"incident","format"=>"text"),
                array("field"=>"type_status","format"=>"type"),
                array("forcedlabel"=>"reviews","field"=>"reviewed_status","format"=>"reviewed"),
            );

            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("art","module_sinister","incident","document")),
                array("name"=>"browser_id_type_priority", "operator"=>"=","fields"=>array("id_type_priority")),
                array("name"=>"browser_id_type_status", "operator"=>"=","fields"=>array("id_type_status")),
                array("name"=>"browser_id_type_protocol", "operator"=>"=","fields"=>array("id_type_protocol")),
                array("name"=>"browser_id_type_contingency", "operator"=>"=","fields"=>array("id_type_contingency")),
                array("name"=>"browser_id_type_art", "operator"=>"=","fields"=>array("id_type_art")),
            );

            $values["controls"]=array(
                "<span class='badge badge-primary'>Prioridad</span>".comboTypePriorities($this),
                "<span class='badge badge-primary'>Estado</span>".comboTypeStatusFollow($this),
                "<span class='badge badge-primary'>Protocolo</span>".comboTypeProtocols($this),
                "<span class='badge badge-primary'>Contingencia</span>".comboTypeContingency($this),
                "<span class='badge badge-primary'>ART</span>".comboTypeArts($this),

            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
			$is_doctor=($profile["data"][0]["id_doctor"]!="" and $profile["data"][0]["offline_doctor"]=="" );
			$values["profile"]=$profile;
            $this->view="vw_sinisters";
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $values["interface"]=(MOD_FOLLOW."/sinisters/abm");

            $parameters_id_type_priority=array(
                "model"=>(MOD_FOLLOW."/Type_priorities"),
                "table"=>"type_priorities",
                "name"=>"id_type_priority",
                "class"=>"form-control dbase",
                "empty"=>false,
                "id_actual"=>secureComboPosition($values["records"],"id_type_priority"),
                "id_field"=>"id",
                "description_field"=>"description",
            );
            $parameters_id_type_protocol=array(
                "model"=>(MOD_FOLLOW."/Type_protocols"),
                "table"=>"type_protocols",
                "name"=>"id_type_protocol",
                "class"=>"form-control dbase validate",
                "empty"=>false,
                "id_actual"=>secureComboPosition($values["records"],"id_type_protocol"),
                "id_field"=>"id",
                "description_field"=>"description",
            );
            $parameters_id_type_contingency=array(
                "model"=>(MOD_FOLLOW."/Type_contingency"),
                "table"=>"type_contingency",
                "name"=>"id_type_contingency",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_contingency"),
                "id_field"=>"id",
                "description_field"=>"description",
            );
            $parameters_id_type_art=array(
                "model"=>(MOD_FOLLOW."/Type_arts"),
                "table"=>"type_arts",
                "name"=>"id_type_art",
                "class"=>"form-control dbase validate",
                "empty"=>false,
                "id_actual"=>1,//secureComboPosition($values["records"],"id_type_art"),
                "id_field"=>"id",
                "description_field"=>"description",
            );
            $values["controls"]=array(
                "id_type_priority"=>getCombo($parameters_id_type_priority,$this),
                "id_type_protocol"=>getCombo($parameters_id_type_protocol,$this),
                "id_type_contingency"=>getCombo($parameters_id_type_contingency,$this),
                "id_type_contingency_request"=>getCombo($parameters_id_type_contingency,$this),
                "id_type_art"=>getCombo($parameters_id_type_art,$this),
            );
			$values["is_doctor"]=false;
			$values["is_admin"]=true;
			$values["canceled"]=(secureComboPosition($values["records"],"id_type_status")=="6");
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
			if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
			$this->view="vw_sinisters";
			$sinister=$this->get(array("where"=>"id=".$id));
			if ((int)$sinister["totalrecords"]!=0){
			    if ($id!=(int)$sinister["data"][0]["id"]){throw new Exception(lang("error_2002"),2002);}
			}
			//$values["module_sinister"]=$sinister["data"][0]["module_sinister"];
			//$values["version"]=$sinister["data"][0]["version"];
			$values["id_type_status"]=$sinister["data"][0]["id_type_status"];
			$values["id_type_priority"]=$sinister["data"][0]["id_type_priority"];
			if($values["version"]==0){$values["version"]=0;}
			if($fields==null){
				$fields = array(	
					'fum' => $this->now,
					'module_sinister' => $values["module_sinister"],
					'name' => $values["name"],
					'surname' => $values["surname"],
					'document' => $values["document"],
					'test_confirm' => $values["test_confirm"],
					'test_type' => $values["test_type"],
					'test_date' => $values["test_date"],
					'personal_email' => $values["personal_email"],
					'personal_phone' => $values["personal_phone"],
					'id_type_contingency'=>$values["id_type_contingency"],
					'art'=>$values["art"],
					'sex'=>$values["sex"],
					'birthdate'=>$values["birthdate"],
					'personal_address_street'=>$values["personal_address_street"],
					'personal_address_number'=>$values["personal_address_number"],
					'personal_address_floor'=>$values["personal_address_floor"],	
					'personal_address_apto'=>$values["personal_address_apto"],
					'personal_address_location'=>$values["personal_address_location"],
					'personal_address_province'=>$values["personal_address_province"],
					'personal_address_postal_code'=>$values["personal_address_postal_code"],
					'personal_prefix_phone'=>$values["personal_prefix_phone"],
					'personal_phone'=>$values["personal_phone"],
					'personal_prefix_cel'=>$values["personal_prefix_cel"],
					'personal_cel'=>$values["personal_cel"],
					'personal_prefix_alt_phone'=>$values["personal_prefix_alt_phone"],
					'personal_alt_phone'=>$values["personal_alt_phone"],
					'personal_email'=>$values["personal_email"],
					'company_name'=>$values["company_name"],
					'company_cuit'=>$values["company_cuit"],
					'company_address'=>$values["company_address"],
					'company_prefix_phone'=>$values["company_prefix_phone"],
					'company_phone'=>$values["company_phone"],
					'company_contact'=>$values["company_contact"],
					'company_contact_prefix_cel'=>$values["company_contact_prefix_cel"],
					'company_contact_cel'=>$values["company_contact_cel"],
					'company_email'=>$values["company_email"],
					'accident_place'=>$values["accident_place"],
					'accident_address'=>$values["accident_address"],
					'accident_prefix_phone'=>$values["accident_prefix_phone"],
					'accident_phone'=>$values["accident_phone"],
					'accident_contact'=>$values["accident_contact_cel"],
					'accident_contact_prefix_cel'=>$values[""],
					'accident_contact_cel'=>$values[""],
					'accident_contact_email'=>$values["accident_contact_email"],
					'sanity_name'=>$values["sanity_name"],
					'sanity_cuit'=>$values["sanity_cuit"],
					'sanity_address_street'=>$values["sanity_address_street"],
					'sanity_address_number'=>$values["sanity_address_number"],
					'sanity_address_floor'=>$values["sanity_address_floor"],
					'sanity_address_apto'=>$values["sanity_address_apto"],
					'sanity_address_location'=>$values["sanity_address_location"],
					'sanity_address_province'=>$values["sanity_address_province"],
					'sanity_address_postal_code'=>$values["sanity_address_postal_code"],
					'sanity_prefix_phone'=>$values["sanity_prefix_phone"],
					'sanity_phone'=>$values["sanity_phone"],
					'sanity_fax'=>$values["sanity_fax"],
					'sanity_email'=>$values["sanity_email"],
					'id_type_contingency_request'=>$values["id_type_contingency_request"],
					'accident_date'=>$values["accident_date"],
					'fault_date'=>$values["fault_date"],
					'aid_date'=>$values["aid_date"],
					'sinopsys'=>$values["sinopsys"],
					'sinopsys1'=>$values["sinopsys1"],
					'sinopsys2'=>$values["sinopsys2"],
					'prognosys'=>$values["prognosys"],
					'prognosys1'=>$values["prognosys1"],
					'prognosys2'=>$values["prognosys2"],
					'indications'=>$values["indications"],
					'indications1'=>$values["indications1"],
					'indications2'=>$values["indications2"],
					'stop_work'=>$values["stop_work"],
					'free_date'=>$values["free_date"],
					'next_revision_date'=>$values["next_revision_date"],
					'next_revision_date_desnorm'=>$values["next_revision_date"],
					'back_work'=>$values["back_work"],
					'footer_place'=>$values["footer_place"],
					'id_type_protocol'=>secureEmptyNull($values,"id_type_protocol"),
					'id_type_art'=>secureEmptyNull($values,"id_type_art"),
					'medical_notes'=>$values["medical_notes"],
					'version'=>$values["version"],
					'full_vacuna'=>$values["full_vacuna"],
				);
			}
			$SINISTERS=$this->createModel(MOD_FOLLOW,"Sinisters","Sinisters");
			return $SINISTERS->save(array("id"=>$id,"s_admin"=>"S"),$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
