<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Charges_codes extends MY_Model {
    private $imgAFIP = "data:image/png;base64,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";

    public function __construct()
    {
        parent::__construct();
    }

    public function checkPaycode($values){
        try {
            //$profile=getUserProfile($this,$values["id_user_active"]);
            $id_ot=0;
            switch((int)$values["code"]) {
               case -1:  //First free waiting in queue
                  //Retrieve first free charges code!
                  $sql="SELECT min(id) as id FROM ".MOD_LEGAL."_charges_codes WHERE isnull(id_operator_task,0)=0 AND verified IS null";
                  $charge_code=$this->getRecordsAdHoc($sql);
                  $values["code"]=$charge_code[0]["id"];
                  if ($values["code"]==""){throw new exception(lang("error_7000"),7000);}
                  break;
               case -999: //Atención espontánea
                  if ($values["id_club_redondo"]==""){
                       $club_redondo=getIdUserClubRedondo($this,$values["dni"]);
					   if($club_redondo["message"]==null){throw new exception("El DNI ingresado no pertenece a un cliente Mediya");}
                       $values["id_club_redondo"]=$club_redondo["message"]["ClubRedondo"];
				  }
                  $club_redondo=getUserClubRedondo($this,$values["id_club_redondo"]);
                  $values["id_payment"]=null;
                  $values["code_payment"]="ATENCION_ESPONTANEA";
                  $values["importe_total"]=0;
                  $values["id_type_request"]=6; //Otro
                  $values["name_club_redondo"]=$club_redondo["message"]["ApellidoNombre"];
                  $values["telefono_contacto"]=$club_redondo["message"]["Telefono"];
                  $values["motivo_consulta"]="";
                  $new=$this->generatePaycode($values);
                  $values["code"]=$new["data"]["id"];
               default:
                  $sql="SELECT min(id) as id FROM ".MOD_LEGAL."_charges_codes WHERE id=".$values["code"]." AND isnull(id_operator_task,0)=0 AND verified IS null ";
                  //$sql.=" AND especialidad IN (SELECT code FROM ".MOD_BACKEND. "_groups WHERE id IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"]."))";
                  $charge_code=$this->getRecordsAdHoc($sql);
                  $values["code"]=$charge_code[0]["id"];
                  if ($values["code"]==""){throw new exception(lang("error_7001"),7001);}
                  break;
            }

            $OPERATORS_TASKS=$this->createModel(MOD_LEGAL,"Operators_tasks","Operators_tasks");
            $paycode=$this->get(array("where"=>"id=".$values["code"]." AND freezed IS null"));
            if ((int)$paycode["totalrecords"]!=0){
               if ($paycode["data"][0]["id_operator_task"]==null) {
                  /*Correct status for generate OpTask!*/
                  $refiereArr=json_decode($paycode["data"][0]["serialized"],true);
                  $refiere="";
                  if (is_array($refiereArr)){
                     $refiere="El cliente indica que consulta por: ";
                  }
                  $fields=array(
                     'code' => $values["code"],
                     'description' => "Atención por canal de Orientación jurídica",
                     'id_operator' => null,
                     'refiere' => $refiere,
                     'motivo' => $paycode["data"][0]["motivo_consulta"],
                     'id_type_request' => secureEmptyNull($paycode["data"][0],"id_type_request"),
                     'id_type_task_close' => null,
                     'id_client_credipaz' => secureEmptyNull($paycode["data"][0],"id_credipaz"),
                     'created' => $this->now,
                     'verified' => null,
                     'offline' => null,
                     'fum' => $this->now,
                  );
                  $saved=$OPERATORS_TASKS->save(array("id"=>0),$fields);
                  $id_ot=$saved["data"]["id"];

                  $CHARGES_CODES_ACCESS=$this->createModel(MOD_LEGAL,"Charges_codes_access","Charges_codes_access");
                  $fields=array(
                     "code"=>opensslRandom(16),
                     "description"=>"",
                     "created"=>$this->now,
                     "verified"=>$this->now,
                     "fum"=>$this->now,
                     "id_charge_code"=>$paycode["data"][0]["id"],
                     "id_operator"=>$values["id_operator"],
                  );
                  $CHARGES_CODES_ACCESS->save(array('id'=>0),$fields);
               } else {
                  $id_ot=$paycode["data"][0]["id_operator_task"];
                  $operator_task=$OPERATORS_TASKS->get(array("where"=>"id=".$id_ot));
                  if ($operator_task["data"][0]["id_type_task_close"]!="") {
                     throw new exception("El código ".$values["code"]." fue utilizado el ".date(FORMAT_DATE_DMYHMS, strtotime($paycode["data"][0]["verified"])).".  Imposible procesar la solicitud.");
                  }
               }
               $fields=array("id_operator_task"=>$id_ot,"verified"=>$this->now,"fum"=>$this->now,"accessed"=>((int)$paycode["data"][0]["accessed"]+1));
               $this->updateByWhere($fields,"id=".$paycode["data"][0]["id"]);
            } else {
               throw new exception("No existe el código ".$values["code"].".  Imposible procesar la solicitud.");
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "id_ot"=>$id_ot,
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function generatePaycode($values){
        try {
		    if (isset($values["DNI"])) {
			   $club_redondo=getIdUserClubRedondo($this,$values["DNI"]);
               $values["id_club_redondo"]=$club_redondo["message"]["ClubRedondo"];
			}
		    if(!isset($values["amount"])){$values["amount"]=0;}
            if(!isset($values["id_credipaz"])){$values["id_credipaz"]=0;}
            if($values["id_credipaz"]=="0"){$values["id_credipaz"]=0;}
			$id_user=secureEmptyNull($values,"id_user_active");
			if($id_user==null){$id_user=0;}
			$id_club_redondo=secureEmptyNull($values,"id_club_redondo");
			if($id_club_redondo==null){$id_club_redondo=0;}
            $bSkipPush=((int)$values["code"]==-999);
			if ($id_user==0 or $id_club_redondo==0){throw new exception(lang("error_5118"),5118);}
			$club_redondo=getUserClubRedondo($this,(int)$id_club_redondo);

            $data=array(
                'code' => $values["code"],
                'description' => "Atención por canal de Orientación jurídica",
                'id_operator' => null,
                'refiere' => $refiere,
                'motivo' =>  $values["motivo_consulta"],
                'id_type_request' => secureEmptyNull($values,"id_type_request"),
                'id_type_task_close' => null,
                'id_client_credipaz' => null,//secureEmptyNull($values,"id_credipaz"),
                'created' => $this->now,
                'verified' => $this->now,
                'offline' => null,
                'fum' => $this->now,
            );
            $OPERATORS_TASKS=$this->createModel(MOD_LEGAL,"Operators_tasks","Operators_tasks");
            $ret=$OPERATORS_TASKS->save(array("id"=>0),$data);
            $fields = array(
                'code' => opensslRandom(16),
                'description' => 'Código de pago',
                'created' => $this->now,
                'verified' => null,
                'offline' => null,
                'fum' => $this->now,
                'id_user' => $id_user,
                'id_payment' => secureEmptyNull($values,"id_payment"),
                'code_payment' => $values["code_payment"],
                'importe_total' => $values["importe_total"],
                'id_operator_task' => $ret["data"]["id"],
                'accessed' => 0,
                'id_credipaz' => null,//secureEmptyNull($values,"id_credipaz"),
                'id_club_redondo' => $id_club_redondo,
                'serialized' => json_encode($values),
                'videoLawyerStatus' => 0,
                'videoClientStatus' => 0,
                'id_type_request' => secureEmptyNull($values,"id_type_request"),
                'name_club_redondo' => $club_redondo["message"]["ApellidoNombre"],
                'telefono' => $values["telefono_contacto"],
                'motivo_consulta' => $values["motivo_consulta"],
            );
            $save=$this->save(array("id"=>0),$fields);
		    $OPERATORS_TASKS->notifyEmail($ret["data"]["id"],"legalRequestNew");
			return $save;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function statusLegales($values){
        try {
            $OPERATORS_TASKS=$this->createModel(MOD_LEGAL,"Operators_tasks","Operators_tasks");
		    if(!isset($values["id_club_redondo"])){$values["id_club_redondo"]=0;}
		    if($values["id_club_redondo"]==""){$values["id_club_redondo"]=0;}
			$message="";
            $paycode=0;
            $token_meet="";
            $videoLawyerStatus=0;
            $videoClientStatus=0;
            $tiempo_espera_estimado=5;
            $video_sala_espera="8dLVQbad5yo";
            $eval=$this->get(array("where"=>"id_club_redondo=".$values["id_club_redondo"]." AND freezed IS null"));
            if ((int)$eval["totalrecords"]!=0){ 
                $paycode=$eval["data"][0]["id"];
                if ($eval["data"][0]["id_operator_task"]!="") {
                   $operator_task=$OPERATORS_TASKS->get(array("where"=>"id=".$eval["data"][0]["id_operator_task"]));
                }
                $token_meet=$eval["data"][0]["code"];
                $videoLawyerStatus=$eval["data"][0]["videoLawyerStatus"];
                $videoClientStatus=$eval["data"][0]["videoClientStatus"];
                $record=$this->get(array("fields"=>"datediff(second,fum,getdate()) as elapsed","where"=>"id=".$paycode));
                if ((int)$record["totalrecords"]!=0){
                    $elapsed=$record["data"][0]["elapsed"];
                    if ((int)$elapsed>30) {
                        //$this->videoDoctorStatus(array("push"=>"no","token_meet"=>$token_meet,"id_charge_code"=>$paycode,"videoStatus"=>0));
                        $videoLawyerStatus=0;
                        $videoClientStatus=0;
                    }
                }
            }
            $eval=$this->get(array("pagesize"=>"1","fields"=>"*","where"=>"id_club_redondo=".$values["id_club_redondo"],"order"=>"1 desc"));
			$ot=$OPERATORS_TASKS->get(array("where"=>"code='".$eval["data"][0]["id"]."'"));
			$id_ot=0;
            if ((int)$ot["totalrecords"]!=0){$id_ot=$ot["data"][0]["id"];}
			if($paycode!=0){$message="Ya tenés consultas legales activas.  ¡Podés hacer otra consulta, pero asegurate de que sea por un motivo diferente!";}
			$ret=array(
                "code"=>"2000",
                "status"=>"OK",
                "paycode"=>$paycode,
                "token_meet"=>$token_meet,
                "videoLawyerStatus"=>$videoLawyerStatus,
                "videoClientStatus"=>$videoClientStatus,
                "tee"=>$tiempo_espera_estimado,
                "adLink"=>$video_sala_espera,
                "message"=>$message,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

}
