<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Log_general extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $this->view="vw_log_general";
            $values["order"]="created DESC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"created","format"=>"date"),
                array("field"=>"id_rel","format"=>"code"),
                array("field"=>"table_rel","format"=>"code"),
                array("field"=>"action","format"=>"code"),
                array("field"=>"username","format"=>"email"),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("created","username","table_rel","action")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $this->view="vw_log_general";
            $values["interface"]=(MOD_BACKEND."/log_general/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $values["readonly"]=true;
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
            if($id==0){
                $bSave=true;
                if($fields["action"]==null){$fields["action"]="";};
                switch($fields["action"]){
                   case "":
                   case "Sinisters::changeAudit":
                   case "Sinisters::changeFullVacuna":
                   case "Sinisters::changeMedicalNotes":
                   case "Sinisters::changePriority":
                   case "Sinisters::form":
                   case "Folders::offline":
                   case "My_Model::offline":
                   case "My_Model::online":
                   case "Operators_tasks::form":
                   case "Transactions::form":
                   case "Requests::form":
                   case "Adherir::form":
                   case "Audit::form":
                   case "Functions::form":
                      $bSave=false; // Deshabilita el registro del log para el evento
                      break;
                   case "Charges_codes::alertDelayTelemedicina":
                   case "Charges_codes::generatePaycode":
                   case "ClubRedondo::referirProspecto":
                   case "ClubRedondoWS::autorizarPrestacion":
                   case "controlDuplicidad::generatePaycode":
                   case "Devices::form_device":
                   case "Devices::form_vendor":
                   case "External_forms::add_creditcard":
                   case "Ingresos::consulta":
                   case "Landings::efectivo":
                   case "Landings::tarjeta":
                   case "Mediya::subdiario":
                   case "My_Model::delete":
                   case "My_Model::save":
                   case "Onboarding::Save":
                   case "Payments::diferenciaMonto":
                   case "Payments::FacturarMediya":
                   case "Payments::getPaymentsByType":
                   case "Payments::initExternalTransaction":
                   case "Payments::prePayment":
                   case "Payments::setItemPago":
                   case "Payments::setItemPagoResponse":
                   case "Payments_fiserv::buildFormFiserv":
                   case "Payments_fiserv::form":
                   case "Payments_fiserv::form_cr":
                   case "Payments_fiserv::form_full":
                   case "RtCargaVirtual::anularCargaVirtual":
                   case "RtCargaVirtual::cargaVirtual":
                   case "Users::authenticate":
                   case "Users::logout":
                   case "Users::TryLogin":
                      $bSave=true;
                      break;
                }

                if(!$bSave) {return false;}

                $saved=parent::save($values,$fields);
		   
			    $trace=json_decode($fields["trace"], true);
			    if(json_last_error()!=JSON_ERROR_NONE){$trace=$fields["trace"];}

			    $externalData=array(
			        "trace"=>$trace,
			        "id_user"=>$fields["id_user"],
			        "id_rel"=>$fields["id_rel"],
			        "table_rel"=>$fields["table_rel"],
			        "type_rel"=>$fields["type_rel"],
			        "identify_rel"=>$fields["identify_rel"],
			        "amount_rel"=>$fields["amount_rel"]
			    );
			    $description=array(
			        "entity"=>"log_general",
			        "id"=>$saved["data"]["id"],
				    "description"=>"Log general"
			    );
			    $params=array(
			        "code"=>$fields["action"],
			        "description"=>json_encode($description),
				    "mime_type"=>"text/plain",
				    "raw_data"=>json_encode($externalData),
				    "externalid"=>$saved["data"]["id"]
			    );
			    //$NEOTRANSACTIONS=$this->createModel(MOD_EXTERNAL,"NeoTransactions","NeoTransactions");
			    //$neotransactions=$NEOTRANSACTIONS->setTransaction($params);
			    return $saved;
            } else {
                throw new Exception(lang('error_9999'),9999);
            }
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

	public function forceLogGeneral($values){
	   try {
			if (is_array($values["trace"])){$values["trace"]=json_encode($values["trace"]);}
			return logGeneralCustom($this,$values,$values["action"],$values["trace"]);
		}
        catch (Exception $e){
			return array(
				"code"=>$e->getCode(),
				"status"=>"ERROR",
				"message"=>$e->getMessage(),
				"function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? $function :ENVIRONMENT),
				);
        }
	}
}
