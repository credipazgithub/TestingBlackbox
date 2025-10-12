<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Discharges extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
			    if ($fields==null){ 
					$fields = array(
						'code' => $values["code"],
						'description' => $values["description"],
						'created' => $this->now,
						'verified' => $this->now,
						'offline' => null,
						'fum' => $this->now,
						'id_sinister'=> $values["id_sinister"],
						'is_medical_discharge'=> secureEmptyNull($values,"is_medical_discharge"),
						'is_treatment_end'=> secureEmptyNull($values,"is_treatment_end"),
						'more_treatment'=> secureEmptyNull($values,"more_treatment"),
						'odontology'=> secureEmptyNull($values,"odontology"),
						'dermatology'=> secureEmptyNull($values,"dermatology"),
						'psicoterapy'=> secureEmptyNull($values,"psicoterapy"),
						'other'=> $values["other"],
						'next_revision_date_discharge'=> str_replace("T", " ",$values["next_revision_date_discharge"]),
						'requalification'=> secureEmptyNull($values,"requalification"),
						'back_work'=> str_replace("T", " ",$values["back_work"]),
						'treatment_end_date'=> str_replace("T", " ",$values["treatment_end_date"]),
						'medical_discharge'=> secureEmptyNull($values,"medical_discharge"),
						'reject'=> secureEmptyNull($values,"reject"),
						'death'=> secureEmptyNull($values,"death"),
						'treatment_end'=> secureEmptyNull($values,"treatment_end"),
						'referral'=> secureEmptyNull($values,"referral"),
						'type_referral'=> $values["type_referral"],
						'inculpable_disease'=> secureEmptyNull($values,"inculpable_disease"),
						'inculpable_disease_detail'=> $values["inculpable_disease_detail"],
						'sequels'=> secureEmptyNull($values,"sequels"),
						'maintenance_services'=> secureEmptyNull($values,"maintenance_services"),
						'treatment_end_date2'=> str_replace("T", " ",$values["treatment_end_date2"]),
						'sequels2'=> secureEmptyNull($values,"sequels2"),
						'requalification2'=> secureEmptyNull($values,"requalification2"),
						'maintenance_services2'=> secureEmptyNull($values,"maintenance_services2"),
						'id_user'=>$values["id_user_active"],
						'sinopsys_discharge'=>$values["sinopsys_discharge"],
						'prognosys_discharge'=>$values["prognosys_discharge"],
						'indications_discharge'=>$values["indications_discharge"],
					);
				}
            }
			$SINISTERS=$this->createModel(MOD_FOLLOW,"sinisters","sinisters");
			$SINISTERS->process(array("id"=>$values["id_sinister"],"id_type_status"=>"4"));
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
