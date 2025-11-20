<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Operators_tasks extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
			$is_doctor=($profile["data"][0]["id_doctor"]!="");
            $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>array(
                    "conditions"=>array(
                           array("field"=>"edit","operator"=>"=","value"=>"1","alternate"=>""),
                        )),
                "delete"=>false,
                "offline"=>false,
            );
            $values["controls"]=array();
            $values["columns"]=array(
                array("field"=>"","format"=>null),
                array(
                    "field"=>"modo","forcedlabel"=>"",
                    "html"=>"<a href='#' class='btn btn-sm btn-raised btn-secondary btn-next-attention btn-next-attention-|ID|' data-id='|ID|' data-item='|RECORD|' data-module='mod_telemedicina' data-model='operators_tasks' data-table='operators_tasks'>Forzar atención</a>",
                    "operator"=>"=","conditional_field"=>"modo","conditional_value"=>"0",
                    "format"=>"html#record"),

                array(
                    "field"=>"type_task_close","forcedlabel"=>"",
                    "html"=>"<a href='#' class='previous-telemedicina btn btn-sm btn-raised btn-primary btn-postclose btn-postclose-|ID|' data-id='|ID|' data-item='|RECORD|'>Notas post cierre</a>",
                    "operator"=>"!=","conditional_field"=>"type_task_close","conditional_value"=>"",
                    "format"=>"html#record"),

                array("field"=>"created","format"=>"datetime"),
                array("field"=>"name_club_redondo","format"=>"warning"),
                array("field"=>"code","format"=>"code"),
                array("field"=>"doctor","format"=>"type"),
                array(
                    "field"=>"type_task_close","forcedlabel"=>"",
                    "html"=>"<span class='badge badge-secondary'>Atención en curso</span>",
                    "operator"=>"=","conditional_field"=>"type_task_close","conditional_value"=>"",
                    "format"=>"html#block"),
                array("field"=>"refiere","format"=>"text"),
                array(
                    "field"=>"request_pictures","forcedlabel"=>"",
                    "html"=>"<span class='badge badge-info'>Esperando imágenes...</span>",
                    "operator"=>"!=","conditional_field"=>"request_pictures","conditional_value"=>"0",
                    "format"=>"html#block"),
                array(
                    "field"=>"pictures_ready","forcedlabel"=>"",
                    "html"=>"<span class='badge badge-success'>Imágenes recibidas</span>",
                    "operator"=>"!=","conditional_field"=>"pictures_ready","conditional_value"=>"0",
                    "format"=>"html#block"),

                array("field"=>"type_task_close","format"=>"status"),
                array(
                    "field"=>"id","forcedlabel"=>"",
                    "html"=>"<button data-id='|ID|' class='btn btn-sm btn-raised btn-warning btn-free-telemedicina'>Liberar atención</button>",
                    "operator"=>"=","conditional_field"=>"id_type_task_close","conditional_value"=>"0",
                    "format"=>"html#block"),
            );

            if(!isset($values["forced"])){$values["forced"]="";}
            $audit=($values["forced"]=="audit");
            if ($audit) {
                $values["title"]=lang('m_doctor_audit');
                $btnNext="";
                $values["filters"]=array(
                    array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description")),
                    array("name"=>"browser_id_doctor", "operator"=>"=","fields"=>array("id_doctor")),
                    array("name"=>"browser_id_type_task_close", "operator"=>"=","fields"=>array("id_type_task_close")),
                );
                $values["controls"]=array(
                    "<span class='badge badge-primary'>Doctor/a</span>".comboDoctorsUsername($this,array("where"=>"offline is null and test!=1","order"=>"username ASC","pagesize"=>-1)),
                    "<span class='badge badge-primary'>Tipo de cierre</span>".comboTypeTaskClose($this,array("order"=>"description ASC","pagesize"=>-1,"where"=>"id NOT IN (5)")),
                );
                $values["buttons"]["edit"]=true;
            } else {
                $values["title"]=lang('m_operators_tasks_telemedicina');
                $data=array("videoDoctorStatus"=>0,"videoPatientStatus"=>0);
                $where="id_operator_task IN (SELECT id FROM ".MOD_TELEMEDICINA."_operators_tasks WHERE id_operator=".$values["id_user_active"].")";
                $CHARGES_CODES->updateByWhere($data,$where);
                if($values["where"]!="") {$values["where"].=" AND ";}
                $values["where"].=(" (id_operator=".$values["id_user_active"]." OR id_operator IS null) ");
                $values["where"].=" AND (especialidad IN (SELECT code FROM ".MOD_BACKEND. "_groups WHERE id IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"].")) OR especialidad IS null)";

                if (!$is_doctor){
                    //$values["where"]=("1=2");
                    $values["alert_message"]="<div class='alert alert-danger fade show' role='alert'>".lang('msg_is_not_doctor')."</div>";
                }
                $btnAdds="<table>";
                $btnAdds.=" <tr>";
                $btnAdds.="   <td valign='bottom'>";
                $btnAdds.="      <span class='badge badge-primary'>DNI</span> <input id='dniAdd' name='dniAdd' type='text' class='form-control dniAdd' value='' placeholder='DNI'/>";
                $btnAdds.="   </td>";
                $btnAdds.="   <td valign='bottom'>";
                $btnAdds.="      <span class='badge badge-primary'>Nº socio/a</span><input id='socioAdd' name='socioAdd' type='text' class='form-control socioAdd' value='' placeholder='Nº socio CR'/>";
                $btnAdds.="   </td>";
				$btnAdds.="   <td valign='bottom'>";
                $btnAdds.="      <button type='button' class='btn btn-md btn-raised btn-primary btn-next-attention' data-id='-999' data-module='mod_telemedicina' data-model='operators_tasks' data-table='operators_tasks'>Demanda espontánea</button>";
                $btnAdds.="   </td>";
				$btnAdds.="   <td valign='bottom'>";
                $btnAdds.="      <button type='button' class='btn btn-md btn-raised btn-warning btn-next-attention' data-id='-1' data-module='mod_telemedicina' data-model='operators_tasks' data-table='operators_tasks'>Atender primero pendiente</button>";
                $btnAdds.="   </td>";
                $btnAdds.=" </tr>";
                $btnAdds.="</table>";
                $values["filters"]=array(
                    array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description")),
                );
            }
            array_push($values["controls"],$btnAdds);
            $values["conditionalBackground"]=array(
                array("field"=>"modo","operator"=>"=","value"=>"0","color"=>"lightgreen"),
                array("field"=>"modo","operator"=>"=","value"=>"1","color"=>"white"),
            );

            $this->view="vw_operators_taks_and_charges_codes";

            $values["order"]="modo ASC, id DESC";
            $values["records"]=$this->get($values);
            //$values["custom_class_new"]="btn-check-paycode";
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            if(!isset($values["forced"])){$values["forced"]="";}
            $audit=($values["forced"]=="audit");
            $values["readonly"]=$audit;
            $values["title"]=lang('m_operators_tasks_telemedicina');
            $profile=getUserProfile($this,$values["id_user_active"]);
            //$external=(evalPermissions("EXTERNAL",$profile["data"][0]["groups"]));
            $this->view="vw_operators_tasks";
            $values["interface"]=(MOD_TELEMEDICINA."/operators_tasks/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            if ($values["records"]["data"][0]["refiere"]==""){$values["records"]["data"][0]["refiere"]=lang("msg_empty");}

            $sql = "UPDATE " . MOD_TELEMEDICINA . "_charges_codes SET id_operator_task=".$values["id"]." WHERE id=" . $values["records"]["data"][0]["code"];
            $this->execAdHoc($sql);

            $sql="SELECT DISTINCT stc_mono_presentation,id_type_vademecum,vademecum FROM ".MOD_TELEMEDICINA."_vw_vademecum WHERE isnull(stc_mono_presentation,'') !='' ORDER BY stc_mono_presentation ASC";
            $values["vademecum"]=$this->getRecordsAdHoc($sql);

            $DOCTORS=$this->createModel(MOD_TELEMEDICINA,"Doctors","Doctors");
            $doctor=$DOCTORS->get(array("page"=>1,"where"=>"username='".$profile["data"][0]["username"]."'"));

            $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
            $values["charges_codes"]=$CHARGES_CODES->get(array("page"=>1,"where"=>"id=".$values["records"]["data"][0]["code"]));

            $GROUPS=$this->createModel(MOD_BACKEND,"Groups","Groups");
            $values["especialidad"]=$GROUPS->get(array("page"=>1,"where"=>"code='". $values["charges_codes"]["data"][0]["especialidad"]."'"));

            $parameters_id_type_task_close=array(
                "model"=>(MOD_TELEMEDICINA."/Type_tasks_close"),
                "table"=>"type_tasks_close",
                "name"=>"id_type_task_close",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_task_close"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1,"where"=>"id NOT IN (5)"),
            );
            $values["controls"]=array(
                "id_type_task_close"=>getCombo($parameters_id_type_task_close,$this),
                "refiere"=>("<p>".$values["records"]["data"][0]["refiere"]."</p>"),
                "motivo"=>("<p>".$values["records"]["data"][0]["motivo"]."</p>"),
            );
            $values["chat_fullname"]=$profile["data"][0]["username"];
            $values["chat_alias"]=$profile["data"][0]["username"];
            $values["chat_height"]="450";
            $values["chat_platformname"]="Videoconsulta";
            $values["chat_roomname"]=("CHARGECODEID".$values["charges_codes"]["data"][0]["id"]);
            $values["chat_domain"]=SERVER_SUB;
            $club_redondo=getUserClubRedondo($this,(int)$values["records"]["data"][0]["id_club_redondo"]);
            $values["club_redondo"]=$club_redondo["message"];
            $CREDENTIALS=$this->createModel(MOD_EXTERNAL,"NetCoreCPFinancial","NetCoreCPFinancial");
            $nDoc=$club_redondo["message"]["DNI"];
            $Sexo=$club_redondo["message"]["Sexo"];
            $values["swiss"]=$CREDENTIALS->GetCredenciales(array("Tipo"=>"SWISS","NroDocumento"=>$nDoc,"Sexo"=>$Sexo));
            $values["gerdanna"]=$CREDENTIALS->GetCredenciales(array("Tipo"=>"GERDANNA","NroDocumento"=>$nDoc,"Sexo"=>$Sexo));
            $values["matricula"]=$doctor["data"][0]["license"];
            $values["medico"]=$doctor["data"][0]["name"]." ".$doctor["data"][0]["surname"];
            $values["firma"]=$doctor["data"][0]["image"];
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
		    $bSavePoll=false;
            $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
			    $bSavePoll=true;
                if($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => null,
                        'offline' => null,
                        'fum' => $this->now,
                        'id_operator' => $values["id_operator"],
                        'id_type_task_close' => secureEmptyNull($values,"id_type_task_close"),
                        'id_client_credipaz' => secureEmptyNull($values,"id_client_credipaz"),
                        'request_pictures' => 0,
                        'refiere' => $values["refiere"],
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'fum' => $this->now,
                        'id_type_task_close' => secureEmptyNull($values,"id_type_task_close"),
                        'id_client_credipaz' => secureEmptyNull($values,"id_client_credipaz"),
                        'motivo' => $values["motivo"],
                        'evolucion' => $values["evolucion"],
                        'diagnostico' => $values["diagnostico"],
                        'indicaciones' => $values["indicaciones"],
                        'derivado_consulta' => $values["derivado_consulta"],
                        'derivado_especialista' => $values["derivado_especialista"],
                        'temperatura' => $values["temperatura"],
                        'tos' => $values["tos"],
                        'expectoracion' => $values["expectoracion"],
                        'odinofagia' => $values["odinofagia"],
                        'disfagia' => $values["disfagia"],
                        'disnea' => $values["disnea"],
                        'nauseas' => $values["nauseas"],
                        'vomitos' => $values["vomitos"],
                        'dolor_abdominal' => $values["dolor_abdominal"],
                        'diarrea' => $values["diarrea"],
                        'proctorragia' => $values["proctorragia"],
                        'disuria' => $values["disuria"],
                        'polaquiuria' => $values["polaquiuria"],
                        'edemas' => $values["edemas"],
                        'parestesias' => $values["parestesias"],
                        'calambres' => $values["calambres"],
                        'insensibilidad_miembro' => $values["insensibilidad_miembro"],
                        'cefaleas' => $values["cefaleas"],
                        'migrana_antecedente' => $values["migrana_antecedente"],
                        'migrana_medicada' => $values["migrana_medicada"],
                        'ta_constatada' => $values["ta_constatada"],
                        'request_pictures' => $values["request_pictures"],
                        'id_type_emergency' => secureEmptyNull($values,"id_type_emergency"),
                        'derivado_emergencia' => secureEmptyNull($values,"derivado_emergencia"),
                        'emergency_request' => secureEmptyNull($values,"emergency_request"),
                        'emergency_processed' => secureEmptyNull($values,"emergency_processed"),
                        'emergency_details' => $values["emergency_details"],
                        'otras_evaluaciones' => $values["otras_evaluaciones"],
                        'note_close' => $values["note_close"],
                    );
                }
            }
            $saved=parent::save($values,$fields);
			$id_operator_task=$saved["data"]["id"];

            if ($fields["id_type_task_close"]!=null) {
               $this->cUrlLocalReload(array("id"=>$id_operator_task));
               $saved=parent::save($values,array("request_pictures"=>0));
               $data=array("freezed"=>$this->now,"videoDoctorStatus"=>0,"videoPatientStatus"=>0);
               $CHARGES_CODES->updateByWhere($data,"id_operator_task=".$saved["data"]["id"]);

               $this->view="vw_operators_tasks";
               $charge_code=$this->get(
                   array(
                       "fields"=>"*,datediff(second,created,getdate()) as seconds, dbo.fc_formatSeconds(datediff(second,created,getdate()),'s') as elapsed, dbo.fc_formatSeconds(datediff(second,cc_created,created),'s') as demora,getdate() as now ",
                       "where"=>"id=".$saved["data"]["id"]
                   )
               );
               $notifyTo=DELAY_TELEMEDICINA_LIST;

               /*Add to notify list, all doctors actives or inactive from less than 60 minutes*/
               $sql="SELECT email FROM ".MOD_TELEMEDICINA."_doctors WHERE datediff(minute,inactive_from,getdate())<60 OR active_from IS NOT NULL";
               $doctors=$this->getRecordsAdHoc($sql);
               foreach ($doctors as $record){
                   if ($record["email"]!=""){
                       if ($notifyTo!=""){$notifyTo.=",";}
                       $notifyTo.=$record["email"];
                   }
               }
               $ALERT_CONTROL=$this->createModel(MOD_BACKEND,"Alert_control","Alert_control");
               $alert=$ALERT_CONTROL->get(array("where"=>"table_rel='charges_codes' AND id_rel=".$charge_code["data"][0]["id_charges_codes"]));
               if ((int)$alert["totalrecords"]!=0){
                   $EMAIL=$this->createModel(MOD_EMAIL,"Email","Email");
                   $data["patient"]=$charge_code["data"][0];
                   $params=array("from"=>"intranet@mediya.com.ar","alias_from"=>"","email"=>"","subject"=>"","body"=>"");
                   $params["alias_from"]=lang('msg_internal_alerts');
                   $params["email"]=$notifyTo;
                   $params["subject"]=lang('msg_telemedicina_delay_alert_resolved')." ".$data["patient"]["name_club_redondo"]." - ".date(FORMAT_DATE_DMYHMS, strtotime($data["patient"]["created"]));

                   $params["body"]=$this->load->view(MOD_EMAIL.'/templates/finishDelayTelemedicina',$data, true);
                   $ALERT_CONTROL->deleteByWhere("id_rel=".$charge_code["data"][0]["id_charges_codes"]." AND table_rel='charges_codes'");
                   $EMAIL->directEmail($params);
               }
            }
			/*Creación de la encuesta de satisfacción*/
			if ($bSavePoll){
				$cc=$CHARGES_CODES->get(array("fields"=>"*","where"=>"id_operator_task=".$id_operator_task));
				$POLLS=$this->createModel(MOD_BACKEND,"Polls","Polls");
				$params=array(
				   "code"=>opensslRandom(8),
				   "description"=>"Encuesta de satisfacción - Telemedicina",
				   "id_type_poll"=>1, //Satisfaccion TELEMEDICNA
				   "id_club_redondo"=>$cc["data"][0]["id_club_redondo"],
				   "id_rel"=>$id_operator_task,
				   "table_rel"=>(MOD_TELEMEDICINA."_operators_tasks")
				);
				$POLLS->save($params,null);
			}
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function postClose($values){
       try {
          $post_close="[Fecha: ".date(FORMAT_DATE_DMYHMS, strtotime($this->now))."] ".$values["post_close"]."<br/>";
          $sql="UPDATE ".MOD_TELEMEDICINA."_operators_tasks SET post_close=isnull(post_close,'')+'".$post_close."' WHERE id=".$values["id"];
          return $this->execAdHoc($sql);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function assign($values){
        try {
            if(!isset($values["ids"])){$values["ids"]=null;}
            $ids=$values["ids"];
            $id_operator=(int)$values["id_operator"];
            switch($id_operator) {
                case -1: // without operator assigned
                    $id_operator=null;
                default: // assign operator
                    foreach($ids as $id){
                        $this->save(array("id"=>$id),array("id_operator"=>$id_operator,"processed"=>$this->now,"tag_processed"=>"Operador asignado"));
                    };
                    break;
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function previousTelemedicina($values){
        try {
            if (!isset($values["request_mode"])){$values["request_mode"]="byuser";}
            switch($values["request_mode"]) {
               case "byuser":
                  $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
                  $charge_code=$CHARGES_CODES->get(array("page"=>1,"where"=>"id=".$values["id_charge_code"]));
				  /* Busca por el id user */
                  //$data=array("order"=>"created DESC","where"=>"id_type_task_close IS NOT null AND id IN (SELECT id_operator_task FROM ".MOD_TELEMEDICINA."_charges_codes WHERE id_user=".$charge_code["data"][0]["id_user"].")");
				  /* Busca por el id_club redondo */
                  $data=array("order"=>"created DESC","where"=>"id_type_task_close IS NOT null AND id IN (SELECT id_operator_task FROM ".MOD_TELEMEDICINA."_charges_codes WHERE id_club_redondo=".$charge_code["data"][0]["id_club_redondo"].")");
                  break;
            }
            return $this->get($data);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function freeTelemedicina($values){
       $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
       $data=array("verified"=>null,"id_operator_task"=>null,"videoDoctorStatus"=>0,"videoPatientStatus"=>0);
       $CHARGES_CODES->updateByWhere($data,"id_operator_task=".$values["id"]);
       return parent::delete($values);
    }
    public function emergency($values){
       try {
          $values["derivado_emergencia"]=1;
          $values["emergency_request"]=$this->now;
          $values["emergency_processed"]=$this->now;
          $saved=$this->save($values);
          if ($saved["status"]!="OK"){throw new Exception($saved["message"],(int)$saved["code"]);}
          $TYPE_EMERGENCY=$this->createModel(MOD_TELEMEDICINA,"Type_emergency","Type_emergency");
          $te=$TYPE_EMERGENCY->get(array("where"=>"id=".$values["id_type_emergency"]));
  		  $html= "<div style='padding:5px;background-color:".$te["data"][0]["color"]."'>";
		  $html.= "<b>Ambulancia solicitada</b> ".date(FORMAT_DATE_DMYHMS, strtotime($values["emergency_request"]));
		  $html.= "<br/>";
		  $html.= "<b>Clasificación TRIAGE</b> ".$te["data"][0]["description"];
		  $html.= "</div>";
          $saved["data"]=$html;
          return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function evalTelemedicinaQueue($values){
        $active=0;
        $id_doctor=null;
        $LOG_ACTIONS=$this->createModel(MOD_TELEMEDICINA,"Log_actions","Log_actions");
        $DOCTORS=$this->createModel(MOD_TELEMEDICINA,"Doctors","Doctors");
        if (isset($values["toggle"])){
           switch($values["toggle"]) {
              case "pause":
                 $sql="UPDATE ".MOD_TELEMEDICINA."_doctors SET active_from=null, inactive_from=getdate() WHERE username='".$values["username_active"]."'";
                 break;
              case "active":
                 $sql="UPDATE ".MOD_TELEMEDICINA."_doctors SET active_from=getdate(), inactive_from=null WHERE username='".$values["username_active"]."'";
                 break;
           }
           $this->execAdHoc($sql);
           $doctor=$DOCTORS->get(array("fields"=>"id,active_from,inactive_from","where"=>"username='".$values["username_active"]."'"));
           if ((int)$doctor["totalrecords"]!=0) {$id_doctor=$doctor["data"][0]["id"];} 
           $params=array(
               "code"=>opensslRandom(8),
               "description"=>"Cambio de estado de atención",
               "created"=>$this->now,
               "verified"=>$this->now,
               "fum"=>$this->now,
               "id_doctor"=>$id_doctor,
               "id_user"=>$values["id_user_active"],
               "action"=>$values["toggle"],
               "action_tag"=>"",
               "action_details"=>"",

           );
           $LOG_ACTIONS->save(array("id"=>0),$params);
        } else {
           $doctor=$DOCTORS->get(array("fields"=>"id,active_from,inactive_from","where"=>"username='".$values["username_active"]."'"));
        }
        if ((int)$doctor["totalrecords"]!=0) {if ($doctor["data"][0]["active_from"]!=""){$active=1;}} 
        $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
        $profile=getUserProfile($this,$values["id_user_active"]);
        $inGroup="";
        foreach($profile["data"][0]["groups"] as $group){
            if ($inGroup!=""){$inGroup.=",";}
            $inGroup.=("'".$group["code"]."'");
        };
        $charge_code=$CHARGES_CODES->get(
            array(
                "fields"=>"count(id) as total,datediff(second,min(created),getdate()) as seconds, dbo.fc_formatSeconds(datediff(second,min(created),getdate()),'s') as elapsed",
                "where"=>"offline IS null AND id_operator_task IS null AND especialidad IN (".$inGroup.")"
            )
        );
        $seconds=(int)$charge_code["data"][0]["seconds"];
        $html="<span class='badge badge-primary m-0'>Nadie espera</span>";
        $pacientes=0;
        if ($seconds!=0) {
            $pacientes=$charge_code["data"][0]["total"];
            $class="badge badge-info";
            $moreInfo="Normal - menos de 15 minutos";
            if ($seconds>(60*15)){$class="badge badge-warning m-0 blink_me";$moreInfo="ALERTA - más de 15 minutos";}
            $html = "<span class='" . $class . "' style='font-size:12px;'>" . $pacientes . " pacientes en espera, desde hace " . $charge_code["data"][0]["elapsed"] . "</span>";
            $html .= "<span class='badge badge-primary m-0' style='font-size:12px;'>".$moreInfo."</span>";
        }
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"",
            "table"=>$this->table,
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>$html,
            "pacientes"=>$pacientes,
            "active"=>$active
        );
    }

    private function cUrlLocalReload($values){
        try {
            $url=$this->getServer()."/api.v1.express/capture_to_server/".$values["id"];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            $data = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e){}
    }
}
