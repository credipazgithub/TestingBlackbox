<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Audit extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $values["forced"]="audit";
            $OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
            return $OPERATORS_TASKS->brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["forced"]="audit";
            $OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
            return $OPERATORS_TASKS->edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function form($values){
        try {
            $data["parameters"] = $values;
            $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
            $OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
            $OPERATORS_TASKS->view="vw_operators_tasks";
            $profile=getUserProfile($this,$values["id_user_active"]);
            $auditor=(evalPermissions("X_AUDITORIA_MEDICOS",$profile["data"][0]["groups"]));
            $auditor=(evalPermissions("FULLSYSTEM",$profile["data"][0]["groups"]));
            $data["auditor"] = $auditor;
            $data["title"] = ucfirst(lang("m_cancelaciones"));
            if (!$auditor) {$data["title"] = ucfirst(lang("m_medical_monitoring"));}
            $data["espera"] = $CHARGES_CODES->get(array("pagesize"=>"-1","fields"=>"*,datediff(second,created,getdate()) as seconds,dbo.fc_formatSeconds(datediff(second,created,getdate()),'s') as elapsed","where"=>"id_operator_task IS null","order"=>"id DESC"));
            $data["encurso"] = $OPERATORS_TASKS->get(array("pagesize"=>"-1","fields"=>"*,datediff(second,verified,getdate()) as seconds,dbo.fc_formatSeconds(datediff(second,verified,getdate()),'s') as elapsed","where"=>"id_type_task_close IS null","order"=>"id DESC"));
            $data["finalizadas"] = $OPERATORS_TASKS->get(array("pagesize"=>"20","fields"=>"*,datediff(second,verified,fum) as seconds,dbo.fc_formatSeconds(datediff(second,verified,fum),'s') as elapsed","where"=>"id_type_task_close IS NOT null","order"=>"id DESC"));
            $html=$this->load->view(MOD_TELEMEDICINA."/audit/form",$data,true);
            logGeneral($this,$values,__METHOD__);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

}
