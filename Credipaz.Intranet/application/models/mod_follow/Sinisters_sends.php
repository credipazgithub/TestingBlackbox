<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Sinisters_sends extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
	public function registerSends($values){
		$pData=array("id"=>0,"code"=>"PMI_paciente","description"=>"PMI Paciente","id_sinister"=>$values["id"],"id_user"=>$values["id_user_active"]);
	    if ((int)$values["PMI_paciente"]==1) { $this->save($pData,null);}
		
		$pData=array("id"=>0,"code"=>"FI_paciente","description"=>"FI Paciente","id_sinister"=>$values["id"],"id_user"=>$values["id_user_active"]);
		if ((int)$values["FI_paciente"]==1) { $this->save($pData,null); }
		
		$pData=array("id"=>0,"code"=>"PME_paciente","description"=>"PME Paciente","id_sinister"=>$values["id"],"id_user"=>$values["id_user_active"]);
		if ((int)$values["PME_paciente"]==1) { $this->save($pData,null); }
		
		$pData=array("id"=>0,"code"=>"FA_paciente","description"=>"FA Paciente","id_sinister"=>$values["id"],"id_user"=>$values["id_user_active"]);
		if ((int)$values["FA_paciente"]==1) { $this->save($pData,null); }
		
		$pData=array("id"=>0,"code"=>"PMI_ART","description"=>"PMI ART","id_sinister"=>$values["id"],"id_user"=>$values["id_user_active"]);
		if ((int)$values["PMI_ART"]==1) { $this->save($pData,null); }
		
		$pData=array("id"=>0,"code"=>"FI_ART","description"=>"FI ART","id_sinister"=>$values["id"],"id_user"=>$values["id_user_active"]);
		if ((int)$values["FI_ART"]==1) { $this->save($pData,null); }
		
		$pData=array("id"=>0,"code"=>"PME_ART","description"=>"PME ART","id_sinister"=>$values["id"],"id_user"=>$values["id_user_active"]);
		if ((int)$values["PME_ART"]==1) { $this->save($pData,null); }
		
		$pData=array("id"=>0,"code"=>"FA_ART","description"=>"FA ART","id_sinister"=>$values["id"],"id_user"=>$values["id_user_active"]);
		if ((int)$values["FA_ART"]==1) { $this->save($pData,null); }

		return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
	}

	public function statusSends($values){
        $SINISTERS=$this->createModel(MOD_FOLLOW,"Sinisters","Sinisters");
		$sinisters=$SINISTERS->get(array("where"=>"id=".$values["id"]));
		$data=$this->get(array("where"=>"id_sinister=".$values["id"]));
        $return=array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"Records",
            "table"=>$this->table,
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>$data["data"],
			"id_type_status"=>$sinisters["data"][0]["id_type_status"],
            "totalrecords"=>$data["totalrecords"],
            "totalpages"=>$data["totalpages"],
            "page"=>$data["page"]
        );
	    
		return $return;
	}

    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            
            if($id==0){
			    if ($fields==null) {
					$fields = array(
						'code' => $values["code"],
						'description' => $values["description"],
						'created' => $this->now,
						'verified' => $this->now,
						'offline' => null,
						'fum' => $this->now,
						'id_sinister' => $values["id_sinister"],
						'id_user' => $values["id_user"],
					);
				}
            } else {
			    if ($fields==null) {
					$fields = array(
						'code' => $values["code"],
						'description' => $values["description"],
						'fum' => $this->now,
						'id_sinister' => $values["id_sinister"],
						'id_user' => $values["id_user"],
					);
				}
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
