<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Consents extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function save($values,$fields=null){
        try {
			$params=array(
			    "code"=>"Consent:telemedicina",
			    "description"=>"Consentimiento del usuario ". $values["type_class"],
				"mime_type"=>"text/plain",
				"raw_data"=>$values["raw_data"],
				"externalid"=>null
			);
			$NEOTRANSACTIONS=$this->createModel(MOD_EXTERNAL,"NeoTransactions","NeoTransactions");
            $values["id"]=0;
            $fields = array(
                'code' => opensslRandom(16),
                'description' => $params["description"],
                'created' => $this->now,
                'verified' => $this->now,
                'offline' => null,
                'fum' => $this->now,
                'id_user' => secureEmptyNull($values,"id_user_active"),
                'id_external' => $neotransactions["data"]["records"][0]["id"],
                'type_class' => $values["type_class"],
                'raw_data' => $values["raw_data"],
				'id_charge_code' => secureEmptyNull($values,"id_charge_code")
            );
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }}
