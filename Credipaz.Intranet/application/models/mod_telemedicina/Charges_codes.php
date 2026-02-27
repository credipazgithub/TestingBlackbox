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

    public function getIdVideoFromChargeCode($values){
        try {
            $sql="SELECT code FROM ".MOD_TELEMEDICINA."_charges_codes WHERE id=".$values["id"];
            $charge_code=$this->getRecordsAdHoc($sql);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "id_video"=>$charge_code[0]["code"]
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function checkPaycode($values){
        try {
            //$profile=getUserProfile($this,$values["id_user_active"]);
            $id_ot=0;
            $sql="UPDATE ".MOD_TELEMEDICINA."_charges_codes SET especialidad='ESPECIALIDAD_CLINICA_MEDICA' WHERE especialidad IS null";
            $this->execAdHoc($sql);

            switch((int)$values["code"]) {
               case -1:  //First free waiting in queue
                  //Retrieve first free charges code!
                  $sql="SELECT min(id) as id FROM ".MOD_TELEMEDICINA."_charges_codes WHERE isnull(id_operator_task,0)=0 AND verified IS null AND ";
                  $sql.=" especialidad IN (SELECT code FROM ".MOD_BACKEND. "_groups WHERE id IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"]."))";
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
                  $values["cboEspecialidad"]="ESPECIALIDAD_CLINICA_MEDICA";
                  $values["name_club_redondo"]=$club_redondo["message"]["ApellidoNombre"];
                  $values["telefono_contacto"]=$club_redondo["message"]["Telefono"];
                  $values["motivo_consulta"]="";
                  $new=$this->generatePaycode($values);
                  $values["code"]=$new["data"]["id"];
               default:
                  $sql="SELECT min(id) as id FROM ".MOD_TELEMEDICINA."_charges_codes WHERE id=".$values["code"]." AND isnull(id_operator_task,0)=0 AND verified IS null ";
                  $sql.=" AND (especialidad IN (SELECT code FROM ".MOD_BACKEND. "_groups WHERE id IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"].") OR especialidad IS null))";
                  $charge_code=$this->getRecordsAdHoc($sql);
                  $values["code"]=$charge_code[0]["id"];
                  if ($values["code"]==""){throw new exception(lang("error_7001"),7001);}
                  break;
            }

            $OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
            $paycode=$this->get(array("where"=>"id=".$values["code"]." AND freezed IS null"));
            if ((int)$paycode["totalrecords"]!=0){
               if ($paycode["data"][0]["id_operator_task"]==null) {
                  /*Correct status for generate OpTask!*/
                  $refiereArr=json_decode($paycode["data"][0]["serialized"],true);
                  $refiere="";
                  if (is_array($refiereArr)){
                     //$refiere="estar en el domicilio <b>".$refiereArr["domicilio_contacto"]."</b>";
                     //if($refiere!=""){$refiere.=", ";};$refiere.="cuyo teléfono es <b>".$refiereArr["telefono_contacto"]."</b>";
                     $refiere="El paciente indica que consulta por: ";
					 if($refiereArr["fumador"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="ser fumador";}
                     if($refiereArr["alergias"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="tener alergias";}
                     //if($refiereArr["presionalta"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="ser hipertenso";}
                     if($refiereArr["presionbaja"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="ser hipotenso";}
                     if($refiereArr["colesterol"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="tener colesterol elevado";}
                     //if($refiereArr["fiebre"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="hipertermia > 38º";}
                     if($refiereArr["secreciones"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="sufrir secreciones";}
                     if($refiereArr["hemorragias"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="cursar hemorragias o pérdidas de sangre";}
                     //if($refiereArr["dolores"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="tener dolores corporales";}


					 if($refiereArr["fiebre"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="fiebre";}
                     if($refiereArr["cefalea"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="cefalea";}
                     if($refiereArr["mareos"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="mareos";}
                     if($refiereArr["dolores"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="dolor";}
                     if($refiereArr["tos"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="tos";}
                     if($refiereArr["ahogos"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="ahogos";}
                     if($refiereArr["nauseas"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="nauseas";}
                     if($refiereArr["diarrea"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="diarrea";}
                     if($refiereArr["palpitaciones"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="palpitaciones";}
                     if($refiereArr["presionalta"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="presión";}
                     if($refiereArr["urticarias"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="urticarias";}
                     if($refiereArr["otros"]=="on"){if($refiere!=""){$refiere.=", ";};$refiere.="otros";}

                  }
                  $fields=array(
                     'code' => $values["code"],
                     'description' => "Atención por canal de Telemedicina",
                     'id_operator' => $values["id_user_active"],
                     'refiere' => $refiere,
                     'motivo' => $paycode["data"][0]["motivo_consulta"],
                     'id_type_task_close' => null,
                     'id_client_credipaz' => secureEmptyNull($paycode["data"][0],"id_credipaz"),
                     'request_pictures' => 0,
                     'created' => $this->now,
                     'verified' => null,
                     'offline' => null,
                     'fum' => $this->now,
                  );
                  $saved=$OPERATORS_TASKS->save(array("id"=>0),$fields);
                  $id_ot=$saved["data"]["id"];

                  $CHARGES_CODES_ACCESS=$this->createModel(MOD_TELEMEDICINA,"Charges_codes_access","Charges_codes_access");
                  $fields=array(
                     "code"=>opensslRandom(8),
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
            $id = 0;
            if(!isset($values["amount"])){$values["amount"]=0;}
            if(!isset($values["id_credipaz"])){$values["id_credipaz"]=0;}
            if($values["id_credipaz"]=="0"){$values["id_credipaz"]=0;}

            $bSkipPush=((int)$values["code"]==-999);
            $club_redondo=getUserClubRedondo($this,(int)$values["id_club_redondo"]);
            $id_club_redondo=secureEmptyNull($values,"id_club_redondo");

            $sql="SELECT * FROM ".MOD_TELEMEDICINA."_charges_codes WHERE id_club_redondo=".$id_club_redondo." AND datediff(minute,created,getdate())<5";
            $eval=$this->getRecordsAdHoc($sql);
            foreach ($eval as $record){$id=(int)$record["id"];}

            if ($id==0) {
                if (!isset($values["code"]) || $values["code"]=="" || $values["code"]==null){$values["code"] = opensslRandom(8);}
                $fields = array(
                    'code' => $values["code"],
                    'description' => 'Código de pago',
                    'created' => $this->now,
                    'verified' => null,
                    'offline' => null,
                    'fum' => $this->now,
                    'id_user' => secureEmptyNull($values,"id_user_active"),
                    'id_payment' => secureEmptyNull($values,"id_payment"),
                    'code_payment' => $values["code_payment"],
                    'importe_total' => $values["importe_total"],
                    'id_operator_task' => null,
                    'accessed' => 0,
                    'id_credipaz' => 0,//secureEmptyNull($values,"id_credipaz"),
                    'id_club_redondo' => $id_club_redondo,
                    'serialized' => json_encode($values),
                    'videoDoctorStatus' => 0,
                    'videoPatientStatus' => 0,
                    'especialidad' => $values["cboEspecialidad"],
                    'name_club_redondo' => $club_redondo["message"]["ApellidoNombre"],
                    'telefono' => $values["telefono_contacto"],
                    'motivo_consulta' => $values["motivo_consulta"],
                );
                $saved=parent::save(array("id"=>0),$fields);
    		    logGeneralCustom($this,array("id_rel"=>$saved["data"]["id"]),"Charges_codes::generatePaycode","");

			    /* Update id_charge code in table Consents */
			    /*id charge code to newest one*/
			    $sql=("UPDATE ".MOD_BACKEND."_consents SET id_charge_code=".$saved["data"]["id"]." WHERE id_user=".$fields["id_user"]." AND id_charge_code IS null AND id IN (SELECT max(id) FROM ".MOD_BACKEND."_consents WHERE id_user=".$fields["id_user"]." AND id_charge_code IS null)");
			    $this->execAdHoc($sql);
			    /*mark with -1 all other constents with no charge code*/
			    $sql=("UPDATE ".MOD_BACKEND."_consents SET id_charge_code=-1 WHERE id_user=".$fields["id_user"]." AND id_charge_code IS null");
			    $this->execAdHoc($sql);
                $this->alertNewPatient();
                if ($values["telefono"] == null) {$values["telefono"] = "";}
                if ($values["telefono"] != "") {
                    $sql = ("EXEC dbclub.dbo.NS_SocioTelefono_Verificar @idSocio=" . $id_club_redondo . ", @area='" . $values["area"] . "', @telefono='" . $values["telefono"] . "'");
                    $this->execAdHoc($sql);
                }
            } else {
                $saved=$this->save(array("id"=>$id),array("fum"=>$this->now));
		        $params=array("id_rel"=>$id);
    		    logGeneralCustom($this,$params,"controlDuplicidad::generatePaycode","");
            }
            return $saved;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
	public function comprobantesTelemedicina($values){
        try {
		    if(!isset($values["id_club_redondo"])){$values["id_club_redondo"]=0;}
		    if($values["id_club_redondo"]==""){$values["id_club_redondo"]=0;}
            //$recibos=$this->get(
			//	array(
			//		"fields"=>"id,created,code_payment,id_club_redondo,especialidad,name_club_redondo,importe_total",
			//		"where"=>"isnumeric(code_payment)=1 AND id_club_redondo=".$values["id_club_redondo"],
			//		"order"=>"created DESC",
			//		"pagesize"=>-1,
			//		"page"=>-1)
			//);
			//$values["NroDocumento"]=32319266;
            $FILES_BASE64=$this->createModel(MOD_BACKEND,"Files_base64","Files_base64");
			$recibos=$FILES_BASE64->get(array("fields"=>"created,base64_2","where"=>"code='".$values["NroDocumento"]."'","order"=>"created DESC"));

            $CLUB_REDONDO=$this->createModel(MOD_EXTERNAL,"ClubRedondoWS","ClubRedondoWS");
			$values["origen"]="MEDIYA";
			//$values["codigo"]=85992;
			//$values["codigo"]=66800;
			$values["codigo"]=$values["id_club_redondo"];
			$facturas=$CLUB_REDONDO->FacturasPorPersona($values);
			$facturas["data"]=json_decode($facturas["message"],true);
			$facturas["message"]["logoAFIP"]=$this->imgAFIP;
			$ret=array(
                "code"=>"2000",
                "status"=>"OK",
                "recibos"=>$recibos,
                "facturas"=>$facturas,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
	}
    public function statusTelemedicina($values){
        try {
            if (!isset($values["id_transaction"])) {$values["id_transaction"] = 0;}
            if (!isset($values["id_club_redondo"])) {$values["id_club_redondo"] = 0;}
            if($values["id_club_redondo"]==""){$values["id_club_redondo"]=0;}
			$activePoll=null;
            $paycode=0;
            $token_meet="";
            $videoDoctorStatus=0;
            $videoPatientStatus=0;
            $request_pictures=0;
            $tiempo_espera_estimado=5;
            $id_transaccion = (int) $values["id_transaction"];
            if ($id_transaccion != 0) {
                $sql = "UPDATE " . MOD_TELEMEDICINA . "_charges_codes SET code='" . $id_transaccion . "' WHERE id_club_redondo=" . $values["id_club_redondo"] . " AND freezed IS null";
                $this->execAdHoc($sql);
            }
            /*Devolver video almacenado en web post*/
            $video_sala_espera="";
            $WEB_POSTS=$this->createModel(MOD_WEB_POSTS,"web_posts","web_posts");
            $vpost=$WEB_POSTS->get(array("where"=>"id=106"));
            if((int)$vpost["totalrecords"]!=0){
               $video_sala_espera=$vpost["data"][0]["body_post"];
               $video_sala_espera= str_replace('<p>','',$video_sala_espera);
               $video_sala_espera= str_replace('</p>','',$video_sala_espera);
            }

            $eval=$this->get(array("where"=>"id_club_redondo=".$values["id_club_redondo"]." AND freezed IS null"));
            if ((int)$eval["totalrecords"]!=0){ 
                $paycode=$eval["data"][0]["id"];
                $OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
                if ($eval["data"][0]["id_operator_task"]!="") {
                   $operator_task=$OPERATORS_TASKS->get(array("where"=>"id=".$eval["data"][0]["id_operator_task"]));
                   $request_pictures=$operator_task["data"][0]["request_pictures"];
                }
                $token_meet=$eval["data"][0]["code"];
                $videoDoctorStatus=$eval["data"][0]["videoDoctorStatus"];
                $videoPatientStatus=$eval["data"][0]["videoPatientStatus"];
                $record=$this->get(array("fields"=>"datediff(second,fum,getdate()) as elapsed","where"=>"id=".$paycode));
                if ((int)$record["totalrecords"]!=0){
                    $elapsed=$record["data"][0]["elapsed"];
                    if ((int)$elapsed>30) {
                        $this->videoDoctorStatus(array("push"=>"no","token_meet"=>$token_meet,"id_charge_code"=>$paycode,"videoStatus"=>0));
                        $videoDoctorStatus=0;
                        $videoPatientStatus=0;
                    }
                }
            }
			$POLLS=$this->createModel(MOD_BACKEND,"Polls","Polls");
			$OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");

            $eval=$this->get(array("pagesize"=>"1","fields"=>"*","where"=>"id_club_redondo=".$values["id_club_redondo"],"order"=>"1 desc"));
			$ot=$OPERATORS_TASKS->get(array("where"=>"code='".$eval["data"][0]["id"]."'"));
			$id_ot=0;
            if ((int)$ot["totalrecords"]!=0){$id_ot=$ot["data"][0]["id"];}
			$poll=$POLLS->get(array("where"=>"id_rel=".$id_ot." AND table_rel='".MOD_TELEMEDICINA."_operators_tasks' AND id_response IS null"));
			if ((int)$poll["totalrecords"]!=0){$activePoll=$poll["data"][0];}
			$ret=array(
                "code"=>"2000",
                "status"=>"OK",
                "paycode"=>$paycode,
                "token_meet"=>$token_meet,
                "videoDoctorStatus"=>$videoDoctorStatus,
                "videoPatientStatus"=>$videoPatientStatus,
                "request_pictures"=>$request_pictures,
                "tee"=>$tiempo_espera_estimado,
                "adLink"=>$video_sala_espera,
				"activePoll"=>$activePoll,
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function statusVideoResponse($values){
       try {
           $record=$this->get(array("where"=>"id=".$values["id_charge_code"]));
           if ((int)$record["totalrecords"]!=0){
               $values["id_club_redondo"]=$record["data"][0]["id_club_redondo"];
           } else {
               throw new exception("Imposible procesar el código de pago provisto");
           }
           $ret=$this->statusTelemedicina($values);
           return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function videoDoctorStatus($values){
       if (!isset($values["auditoria"])){$values["auditoria"]="N";}
       if (!isset($values["push"])){$values["push"]="";}
       if (!isset($values["token_meet"])){$values["token_meet"]="TOKEN ERROR";}
       if (!isset($values["id_charge_code"])){$values["id_charge_code"]=0;}
       if (!isset($values["videoStatus"])){$values["videoStatus"]=0;}
       if ($values["videoStatus"]==""){$values["videoStatus"]=0;}
	   if ((int)$values["id_charge_code"]==0){return false;}
       if ($values["auditoria"]=="S"){
          $values["push"]="no";
          $return=array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
           );
       } else {
           if((int)$values["videoStatus"]!=0){
              $return = $this->save(array("id"=>$values["id_charge_code"]),array("code"=>$values["token_meet"],"videoDoctorStatus"=>$values["videoStatus"],"fum"=>$this->now));
           } else {
              $return = $this->save(array("id"=>$values["id_charge_code"]),array("code"=>$values["token_meet"],"videoDoctorStatus"=>$values["videoStatus"]));
           }
           $charge_code=$this->get(array("where"=>"id=".$values["id_charge_code"]));
           $OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
		   if ((string)$charge_code["data"][0]["id_operator_task"]==""){$charge_code["data"][0]["id_operator_task"]=0;}
           $OPERATORS_TASKS->updateByWhere(array("verified"=>$this->now),"id=".$charge_code["data"][0]["id_operator_task"]." AND verified IS null");
           $operator_task=$OPERATORS_TASKS->get(array("fields"=>"*,datediff(second,verified,getdate()) as seconds,dbo.fc_formatSeconds(datediff(second,verified,getdate()),'s') as elapsed","where"=>"id=".$charge_code["data"][0]["id_operator_task"]));

           if((int)$values["videoStatus"]==0) {
                $return=$this->videoPatienStatus($values);
           }else{
                if ($values["push"]!="no") {
                    $params=array(
                        "id_user"=>$charge_code["data"][0]["id_user"],
                        "id_type_command"=>null,
                        "id_type_target"=>null,
                        "subject"=>"¡Su médico lo espera!",
                        "body"=>"Por favor, ingrese a la aplicación y consulta a tu médico",
                        "image_url"=>INTRANET."/assets/uploads/push/alerta_videoconsulta.jpg"
                    );
                    $PUSH_OUT=$this->createModel(MOD_PUSH,"Push_out","Push_out");
                    //$PUSH_OUT->sendToOne($params);
                }
           }
           $return["elapsed"]=$operator_task["data"][0]["elapsed"];
           $return["seconds"]=$operator_task["data"][0]["seconds"];
        }
        return $return;
    }
    public function videoPatienStatus($values){
       if (!isset($values["id_charge_code"])){$values["id_charge_code"]=0;}
       if (!isset($values["videoStatus"])){$values["videoStatus"]=0;}
       if ($values["videoStatus"]==""){$values["videoStatus"]=0;}
       return $this->save(array("id"=>$values["id_charge_code"]),array("videoPatientStatus"=>$values["videoStatus"]));
    }
    public function cancelTelemedicina($values){
       try {
            $id=$values["id"];
            $data_table=$values["data-table"];
            switch($data_table){
               case "charges_codes":
                  $fields=array("id_operator_task"=>"-1","canceled"=>$this->now,"id_user_cancel"=>$values["id_user_active"],"fum"=>$this->now,"freezed"=>$this->now);
                  $this->save(array("id"=>$id),$fields);
                  break;
               case "operators_tasks":
                  $OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
                  $fields=array("fum"=>$this->now,"id_type_task_close"=>"5");
                  $OPERATORS_TASKS->save(array("id"=>$id),$fields);
                  $fields=array("canceled"=>$this->now,"id_user_cancel"=>$values["id_user_active"]);
                  $this->updateByWhere($fields,"id_operator_task=".$id);
                  break;
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function sendAuditAV($values){
       $targetLocal=(FILES_CAPTURES.$values["filename"]);
       $whandle = fopen($targetLocal,'a');
       stream_filter_append($whandle, 'convert.base64-decode',STREAM_FILTER_WRITE);
       $base64=explode("base64,",$values["base64"])[1];
       fwrite($whandle,$base64);
       fclose($whandle);       
       $charge_code=$this->get(array("where"=>"id=".$values["id"]));
       $id_operator_task=$charge_code["data"][0]["id_operator_task"];
       $OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
       $fields=array("last_date_audit"=>$this->now);
       $OPERATORS_TASKS->save(array("id"=>$id_operator_task),$fields);
       return array(
           "code"=>"2000",
           "status"=>"OK",
           "message"=>"",
           "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
       );    
    }
    public function alertDelayTelemedicina($values){
        $notifyTo=DELAY_TELEMEDICINA_LIST;
        /*Set inactive all doctors with +12 hs of activity */
        $this->execAdHoc("UPDATE ".MOD_TELEMEDICINA."_doctors SET active_from=null, inactive_from=getdate() WHERE datediff(minute,active_from,getdate())>750");
        /*Add to notify list, all doctors actives or inactive from less than 60 minutes*/
        $sql="SELECT email FROM ".MOD_TELEMEDICINA."_doctors WHERE datediff(minute,inactive_from,getdate())<60 OR active_from IS NOT NULL";
        $doctors=$this->getRecordsAdHoc($sql);
        foreach ($doctors as $record){
           if ($record["email"]!=""){
              if ($notifyTo!=""){$notifyTo.=",";}
              $notifyTo.=$record["email"];
           }
        }

        $values["id_user_active"]="2";
        /*TESTING*/
        //$notifyTo = "daniel@gruponeodata.com,afleischer@credipaz.com";

        $sql="SELECT *,ISNULL(datediff(second,created,getdate()),0) as seconds, dbo.fc_formatSeconds((datediff(second,created,getdate())+".DELAY_TELEMEDICINA_INTERVAL."),'s') as elapsed from ".MOD_TELEMEDICINA."_charges_codes ";
        $sql.=" WHERE ";
        //$sql.=" id=615 AND ";
        $sql.=" offline IS null AND ";
        $sql.=" id_operator_task IS null AND ";
        $sql.=" ISNULL(datediff(second,created,getdate()),0) >=".(int)DELAY_TELEMEDICINA_INTERVAL." AND ";
        //$sql.=" ISNULL(datediff(second,created,getdate()),0) >=10 AND ";
        $sql.=" id NOT IN (SELECT id_rel FROM ".MOD_BACKEND."_alert_control WHERE table_rel='charges_codes')";
        $patients=$this->getRecordsAdHoc($sql);

        $EMAIL=$this->createModel(MOD_EMAIL,"Email","Email");

        foreach ($patients as $record){
            $data["patient"]=$record;
            $params=array("from"=>"intranet@mediya.com.ar","alias_from"=>lang('msg_internal_alerts'),"email"=>$notifyTo,"subject"=>"","body"=>"");
		    if(isset($record["created"]) and date(FORMAT_DATE_DMYHMS, strtotime($record["created"]))!="") {
				$params["subject"]=lang('msg_telemedicina_delay_alert')." ".$record["name_club_redondo"]." - ".date(FORMAT_DATE_DMYHMS, strtotime($record["created"]));
				$params["body"]=$this->load->view(MOD_EMAIL.'/templates/alertDelayTelemedicina',$data, true);
                $ret=$EMAIL->directEmail($params);
                //logGeneral($this, $values, __METHOD__);
                $sql="INSERT INTO ".MOD_BACKEND."_alert_control (code,[description],created,verified,offline,fum,id_rel,table_rel) VALUES ('".opensslRandom(8)."','Alerta delay Telemedicina',getdate(),getdate(),null,getdate(),".$record["id"].",'charges_codes')";
                $this->execAdHoc($sql);
			}
        }
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
        ); 
    }
    public function alertNoAuditing($values){
        $notifyTo=DELAY_TELEMEDICINA_LIST;
        $sql="SELECT email FROM ".MOD_TELEMEDICINA."_doctors WHERE datediff(minute,inactive_from,getdate())<60 OR active_from IS NOT NULL";
        $doctors=$this->getRecordsAdHoc($sql);
        foreach ($doctors as $record){
           if ($record["email"]!=""){
              if ($notifyTo!=""){$notifyTo.=",";}
              $notifyTo.=$record["email"];
           }
        }
        $values["id_user_active"]="2";
        $sql="SELECT *,ISNULL(datediff(second,created,getdate()),0) as seconds, dbo.fc_formatSeconds(datediff(second,created,getdate()),'s') as elapsed from ".MOD_TELEMEDICINA."_charges_codes ";
        $sql.=" WHERE id=".$values["id"];

        /*TESTING*/
        //$notifyTo="daniel@gruponeodata.com,czuniga@credipaz.com";
        $patients=$this->getRecordsAdHoc($sql);

        $EMAIL=$this->createModel(MOD_EMAIL,"Email","Email");
        foreach ($patients as $record){
			$data["patient"]=$record;
			$params=array("from"=>"intranet@mediya.com.ar","alias_from"=>"","email"=>"","subject"=>"","body"=>"");
			$params["alias_from"]=lang('msg_internal_alerts');
			$params["email"]=$notifyTo;
			$params["subject"]=lang('msg_telemedicina_noauditing_alert')." ".$record["name_club_redondo"]." - ".date(FORMAT_DATE_DMYHMS, strtotime($record["created"]));
			$params["body"]=$this->load->view(MOD_EMAIL.'/templates/alertNoAuditingTelemedicina',$data, true);
			
			$EMAIL->directEmail($params);
        }
        $charge_code=$this->get(array("where"=>"id=".$values["id"]));
        $id_operator_task=$charge_code["data"][0]["id_operator_task"];
        $OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
        $fields=array("last_date_audit_fail"=>$this->now);
        $OPERATORS_TASKS->save(array("id"=>$id_operator_task),$fields);
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
        ); 
    }
    public function alertNewPatient()
    {
        /*Alert via Telegram*/
        $mensaje = "===>".date(FORMAT_DATE_DMYHMS)."<===\n";
        $mensaje .= "¡Llamado de paciente entrante!";
        $TELEGRAM = $this->createModel(MOD_PUSH, "Telegram", "Telegram");
        $docs = $this->getRecordsAdHoc("EXEC dbIntranet.dbo.NS_Telemedicina_DocEnSala");
        $bD = 0;
        $mensaje .= "\n\nDoctores atendiendo:\n";
        foreach ($docs as $record) {
            $mensaje .= $record["Nombre"] . " " . $record["Apellido"] . " - " . $record["hora"] . "\n";
            $bD = 1;
        }
        if ($bD == 0) {$mensaje .= "No hay doctores atendiento\n";}

        //$pacs = $this->getRecordsAdHoc("EXEC dbIntranet.dbo.NS_Telemedicina_VerCola");
        //$bP = 0;
        //$mensaje .= "\nPacientes esperando:\n";
        //foreach ($pacs as $record) {
        //    $mensaje .= $record["Numero"] . " " . $record["Paciente"] . " " . $record["FechaLlamado"] . "\n";
        //    $bP = 1;
        //}
        //if ($bP == 0) {$mensaje .= "No hay pacientes en espera\n";}
        $TELEGRAM->send("NUEVOPACIENTE", $mensaje);

        /* PUSH a lista de alerta y medicos en atención */
        /*
        $server = getServer();
        if (strpos($server, "localhost") !== false) {$bSkipPush = true;}
        if (!$bSkipPush) {
            try {
                $PUSH_OUT = $this->createModel(MOD_PUSH, "Push_out", "Push_out");
                $params = array(
                    "id_group" => 1047, // TELEMEDICINA USUARIOS
                    "subject" => lang('msg_telemedicina_push_alert'),
                    "body" => ("El paciente " . $club_redondo["message"]["ApellidoNombre"] . " aguarda ser atendido.  Su consulta se relaciona con: " . $values["motivo_consulta"] . ", para " . $values["cboEspecialidad"] . ".")
                );
                $PUSH_OUT->sendToGroup($params);
            } catch (Exception $ex) {
            }
        }
        */

    }
}
