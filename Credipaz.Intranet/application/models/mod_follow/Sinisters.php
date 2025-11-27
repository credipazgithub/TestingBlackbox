<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Sinisters extends MY_Model {
	public function __construct()
    {
        parent::__construct();
    }
    public function form($values){
        try {
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_statics"));
			$data["cboDoctors"] = comboDoctorsUsername($this,array("where"=>"offline is null and test!=1","order"=>"username ASC","pagesize"=>-1));
            $html=$this->load->view(MOD_FOLLOW."/sinisters/form",$data,true);
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
    public function brow($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
			$is_doctor=($profile["data"][0]["id_doctor"]!="" and $profile["data"][0]["offline_doctor"]=="" );
            $this->view="vw_sinisters";
            if ($values["where"]!=""){$values["where"].=" AND ";}
			$pos=strpos($values["where"], "((actives = '2'))");
	        if($pos===true or $pos===0){
			   $values["where"]=str_replace("((actives = '2'))", "1=1", $values["where"]);
			   $values["where"].=" convert(varchar,created,103) = convert(varchar,getdate(),103)";
			} else {
  			   $pos=strpos($values["where"], "((id_type_status = '4'))");
			   $pos=strpos($values["where"], "((actives = '1'))");
			   if($pos===false){$values["where"].="id_type_status NOT IN (4,6)";}else{$values["where"].="1=1";}
			}
            $values["order"]="code_type_priority ASC, created DESC";
            $values["records"]=$this->get($values);
			$values["buttons"]=array(
                "new"=>true,
                "edit"=>array(
                    "conditions"=>array(
                           array("field"=>"show_edit","operator"=>"==","value"=>1),
                        )
                    ),
                "delete"=>false,
            );

			$i=0;
            foreach($values["records"]["data"] as $item) {
			    $sends_info="";
			    $seconds_locked=$item["seconds_locked"];
			    if($seconds_locked==""){$seconds_locked=999999;}
                if($item["id_user_lock"]==$values["id_user_active"] or $item["id_user_lock"]=="" or (int)$seconds_locked>$this->lock_limit) {
				   $values["records"]["data"][$i]["show_edit"]=1;
                   $values["records"]["data"][$i]["lock_status"]="<i class='material-icons' style='color:green;'>lock_open</i>";
                } else {
				   $values["records"]["data"][$i]["show_edit"]=0;
                   $values["records"]["data"][$i]["lock_status"]="<i class='material-icons' style='color:red;'>lock</i>";
                }
				$SINISTERS_SENDS=$this->createModel(MOD_FOLLOW,"Sinisters_sends","Sinisters_sends");
		        $sSends=$SINISTERS_SENDS->get(array("where"=>"id_sinister=".$item["id"],"order"=>"description ASC"));
				$x=0;
	            foreach($sSends["data"] as $rec) {
				   if ($x==0){$sends_info="<table style=\"font-size:11px;\">";}
				   $sends_info.="<tr><td align=\"left\">".$rec["description"]."</td><td> Enviado:</td><td>".date(FORMAT_DATE_DMY,strtotime($rec["created"]))."</td>";
				   $x+=1;
				}
				if($sends_info!=""){$sends_info.="</table>";}
				$values["records"]["data"][$i]["envios_details"]=$sends_info;
                $i+=1;
            }

			//------------------------------
			//Impresión de PMI!
			//------------------------------
			$url=(getServer()."/siniestroArt/|ID|/2/".$values["id_user_active"]);
			$urlFI=(getServer()."/siniestrocasoleveArt/|ID|/2/".$values["id_user_active"]);
			//Button!
			$PDFIngreso="  <button type='button' class='btn btn-sm btn-success btn-raised' style='color:white;'><a href='".$url."' target='_blank' style='color:white;'>PMI</a></button>";
			$PDFIngreso.="  <button type='button' class='btn btn-sm btn-success btn-raised' style='color:white;'><a href='".$urlFI."' target='_blank' style='color:white;'>FI</a></button>";
			//------------------------------

			//------------------------------
			//Impresión de PME!
			//------------------------------
			$url=(getServer()."/altamedicaArt/|ID|/2/".$values["id_user_active"]);
			$urlFA=(getServer()."/altamedicalaboralArt/|ID|/2/".$values["id_user_active"]);
			//Button!
			$PDFAlta="  <button type='button' class='btn btn-sm btn-success btn-raised' style='color:white;'><a href='".$url."' target='_blank' style='color:white;'>PME</a></button>";
			$PDFAlta.="  <button type='button' class='btn btn-sm btn-success btn-raised' style='color:white;'><a href='".$urlFA."' target='_blank' style='color:white;'>FA</a></button>";
			//------------------------------
			$ddMedNote=("<a href='#' data-record='|RECORD|' data-toggle='tooltip' data-placement='left' data-html='true' title='|DESCRIPTION|' data-id='|ID|' class='btn btn-sm btn-info btn-raised btn-medical-notes'>Notas</a>");
			$ddEnviosDetails=("<a href='#' data-record='|RECORD|' data-toggle='tooltip' data-placement='left' data-html='true' title='|DESCRIPTION|' data-id='|ID|' class='btn btn-sm btn-info btn-raised btn-envios-details'>Envíos</a>");
			
            $ops=array();
            $TYPE_PRIORITIES=$this->createModel(MOD_FOLLOW,"Type_priorities","Type_priorities");
            $records=$TYPE_PRIORITIES->get(array("order"=>"description ASC","pagesize"=>-1));
            foreach($records["data"] as $record){
               array_push($ops,array("style"=>"padding:0px, margin:0px;","name"=>secureField($record,"description"),"class"=>"btn-follow-change-priority","datax"=>"data-module='mod_follow' data-status='".secureField($record,"id")."' data-id=|ID|"));
            };
            $ddChangePriority=getDropdown(array("class"=>"btn-dark btn-title-change-priority-|ID|","name"=>"|DESCRIPTION|"),$ops);

            $opsU=array();
            $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
			$USERS->view="vw_users";
            $records=$USERS->get(array("where"=>"id_doctor is not null and offline_doctor is null and test!=1","order"=>"username ASC","pagesize"=>-1));
            foreach($records["data"] as $record){
               array_push($opsU,array("style"=>"padding:0px, margin:0px;","name"=>secureField($record,"description"),"class"=>"btn-follow-assign-doctor","datax"=>"data-module='mod_follow' data-id-user='".secureField($record,"id")."' data-id=|ID|"));
            };
            array_push($opsU,array("style"=>"padding:0px, margin:0px;","name"=>"[Quitar asignación]","class"=>"btn-follow-assign-doctor","datax"=>"data-module='mod_follow' data-id-user='-1' data-id=|ID|"));
            $ddAssignDoctor=getDropdown(array("direction"=>"dropleft","class"=>"btn-dark btn-title-assign-doctor-|ID|","name"=>"|DESCRIPTION|"),$opsU);

            $values["columns"]=array(
                array("field"=>"type_priority","html"=>$ddChangePriority,"format"=>"html#block"),
                //array("field"=>"lock_status","format"=>"text"),
                array("field"=>"days_accident","format"=>"number"),
                array("field"=>"modo","forcedlabel"=>"forms","html"=>$PDFIngreso,"operator"=>"!=","conditional_field"=>"id_type_status","conditional_value"=>"1","format"=>"html#block"),
                array("field"=>"modo","forcedlabel"=>"adds","html"=>$PDFAlta,"operator"=>"=","conditional_field"=>"id_type_status","conditional_value"=>"4","format"=>"html#block"),
                array("forcedlabel"=>"envios_details","field"=>"envios_details","html"=>$ddEnviosDetails,"format"=>"html#record"),
                array("field"=>"created","forcedlabel"=>"input","format"=>"date"),
                array("field"=>"full_sinister","forcedlabel"=>"sinis","format"=>"code"),
                array("field"=>"limit","format"=>"text"),
                array("forcedlabel"=>"patient","field"=>"incident","format"=>"text"),
	            array("forcedlabel"=>"","field"=>"vacuna","format"=>"icongreen"),
                array("forcedlabel"=>"notes","field"=>"medical_notes","html"=>$ddMedNote,"format"=>"html#record"),
                array("field"=>"type_status","format"=>"type"),
                array("forcedlabel"=>"reviews","forcedlabel"=>"revs","field"=>"reviewed_status","format"=>"reviewed"),
            );
			if ($is_doctor) {
               array_push( $values["columns"],array("field"=>"doctor","format"=>"text"));
			} else {
               array_push( $values["columns"],array("field"=>"doctor","html"=>$ddAssignDoctor,"format"=>"html#block"));
			}

            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("art","module_sinister","incident","document")),
                array("name"=>"browser_id_type_cancelation", "operator"=>"=","fields"=>array("id_type_cancelation")),
                array("name"=>"browser_id_type_priority", "operator"=>"=","fields"=>array("id_type_priority")),
                array("name"=>"browser_id_type_status", "operator"=>"=","fields"=>array("id_type_status")),
                array("name"=>"browser_id_type_protocol", "operator"=>"=","fields"=>array("id_type_protocol")),
                array("name"=>"browser_id_type_contingency", "operator"=>"=","fields"=>array("id_type_contingency")),
                array("name"=>"browser_id_type_art", "operator"=>"=","fields"=>array("id_type_art")),
                array("name"=>"browser_reviewed_today", "operator"=>"=","fields"=>array("reviewed_today")),
                array("name"=>"browser_actives", "operator"=>"=","fields"=>array("actives")),
                array("name"=>"browser_id_user_asigned", "operator"=>"=","fields"=>array("id_user_asigned")),
                array("name"=>"browser_next_filter", "operator"=>"=","fields"=>array("next_filter")),
            );

			$cboReviewed="<select data-type='select' id='browser_reviewed_today' name='browser_reviewed_today' class='form-control'>";
			$cboReviewed.="<option value='' selected>".lang('p_select_all')."</option>";
			$cboReviewed.="<option value='0'>No revisadas</option>";
			$cboReviewed.="<option value='1'>Revisadas</option>";
			$cboReviewed.="</select>";
			$cboActives="<select data-type='select' id='browser_actives' name='browser_actives' class='form-control'>";
			$cboActives.="<option value='' selected>Solo activos</option>";
			$cboActives.="<option value='2'>Ingresados hoy</option>";
			$cboActives.="<option value='1'>".lang('p_select_all')."</option>";
			$cboActives.="</select>";
			$cboMyCases="<select data-type='select' id='browser_id_user_asigned' name='browser_id_user_asigned' class='form-control'>";
			$cboMyCases.="<option value='' selected>".lang('p_select_all')."</option>";
			$cboMyCases.="<option value='".$values["id_user_active"]."'>Solo míos</option>";
			$cboMyCases.="</select>";

            $values["controls"]=array(
                "<span class='badge badge-primary'>Mis casos</span>".$cboMyCases,
                "<span class='badge badge-primary'>Casos</span>".$cboActives,
                "<span class='badge badge-primary'>Revisiones</span>".$cboReviewed,
                "<span class='badge badge-primary'>Prioridad</span>".comboTypePriorities($this),
                "<span class='badge badge-primary'>Estado</span>".comboTypeStatusFollow($this),
                "<span class='badge badge-danger'>Cancelado</span>".comboTypeCancelations($this),
                "<span class='badge badge-primary'>Protocolo</span>".comboTypeProtocols($this),
                "<span class='badge badge-primary'>Contingencia</span>".comboTypeContingency($this),
                "<span class='badge badge-primary'>ART</span>".comboTypeArts($this),
                "<span class='badge badge-primary'>Próx.revisión</span> <input id='browser_next_filter' name='browser_next_filter' type='date' class='form-control'/>",

            );

            $TYPE_PRIORITIES=$this->createModel(MOD_FOLLOW,"type_priorities","type_priorities");
			$priorities=$TYPE_PRIORITIES->get(array("page"=>-1,"pagesize"=>-1,"order"=>"description ASC"));
			$conditional=array();
            foreach($priorities["data"] as $item) {
			   array_push($conditional,array("field"=>"id_type_priority","value"=>$item["id"],"color"=>$item["color"]));
			}
			$values["conditionalBackground"]=$conditional;

			$sql="SELECT count(*) as total,id_type_priority,type_priority FROM ".MOD_FOLLOW."_vw_sinisters where id_type_status!=4 GROUP BY id_type_priority,type_priority";
			$byPriority=$this->getRecordsAdHoc($sql);
			$sql="SELECT count(*) as total,reviewed_status FROM ".MOD_FOLLOW."_vw_sinisters where id_type_status!=4 GROUP BY reviewed_status";
			$byReviewed=$this->getRecordsAdHoc($sql);
			$sql="SELECT count(*) as total FROM ".MOD_FOLLOW."_vw_sinisters where id_type_status!=4";
			$byTomorrow=$this->getRecordsAdHoc($sql);
			$sql="SELECT * FROM ".MOD_FOLLOW."_sinisters_history ORDER BY total_date DESC";
			$byTomorrowYesterday=$this->getRecordsAdHoc($sql);

			$html="<table style='font-size:11px;width:100%;padding:0px;'>";
			$iTotal=0;
            foreach($byReviewed as $item) {
			    $status="Revisado";
				if($item["reviewed_status"]==""){$status="No revisado";}
				$html.="<tr>";
				$html.="   <td style='padding:0px;'>".$status."</td>";
				$html.="   <td align='right' style='padding:0px;'>".$item["total"]."</td>";
				$html.="</tr>";
				$iTotal+=(int)$item["total"];
			}
			$html.="<tr>";
			$html.="   <td colspan='2' style='border-top:solid 1px black;padding:0px;'></td>";
			$html.="</tr>";
			$html.="</table>";

			$html.="<table style='font-size:11px;width:100%;padding:0px;'>";
            foreach($byPriority as $item) {
				$html.="<tr>";
				$html.="   <td style='padding:0px;'>".$item["type_priority"]."</td>";
				$html.="   <td align='right' style='padding:0px;'>".$item["total"]."</td>";
				$html.="</tr>";
			}
			$html.="<tr style='font-weight:bold;font-size:12px;color:rgb(235, 0, 139);'>";
			$html.="   <td style='border-top:solid 1px black;padding:0px;'>Totales</td>";
			$html.="   <td align='right' style='border-top:solid 1px black;padding:0px;'>".$iTotal."</td>";
			$html.="</tr>";
			$html.="</table>";

			$html.="<table style='font-size:11px;width:100%;padding:0px;'>";
			$html.=" <tr>";
			$html.="   <td style='padding:0px;'>De ayer</td>";
			$html.="   <td style='padding:0px;' align='right'>".$byTomorrowYesterday[0]["module_sinisters_count"]."</td>";
			$html.=" </tr>";
			$html.=" <tr>";
			$html.="   <td style='padding:0px;'>Para mañana</td>";
			$html.="   <td style='padding:0px;' align='right'>".(int)$byTomorrow[0]["total"]."</td>";
			$html.=" </tr>";
			$html.="</table>";

			$values["subtitle"]=$html;
			$values["show_totals"]=true;
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
			if((int)$values["records"]["totalrecords"]!=0){
			   if ($is_doctor) {
				   $id_user_lock=$values["records"]["data"][0]["id_user_lock"];
				   $seconds_locked=$values["records"]["data"][0]["seconds_locked"];
				   if($seconds_locked==""){$seconds_locked=999999;}
				   if ((int)$id_user_lock==(int)$values["id_user_active"]){
					  $this->lock($values);
				   } else {
					   if((int)$seconds_locked>$this->lock_limit){
						  $this->lock($values);
					   } else { 
						   // this condition is when locked and live lock for other user!
						   //reject edition by the user active!!!!
						  throw new Exception(lang("error_5300"),5300);
					   }
				   }
 			       if($values["records"]["data"][0]["id_user_asigned"]!="" and (int)$values["records"]["data"][0]["id_user_asigned"]!=(int)$values["id_user_active"]) {
				      throw new Exception(lang("error_5302"),5302);
				   }
			   }
			}
			if((int)$values["records"]["totalrecords"]==0 or (int)$values["records"]["data"][0]["id_type_status"]==1) {
               $values["interface"]=(MOD_FOLLOW."/sinisters/abm");
			} else {
			   $id_type_protocol=$values["records"]["data"][0]["id_type_protocol"];
			   $actual_review=$values["records"]["data"][0]["actual_review"];
               $values["interface"]=(MOD_FOLLOW."/sinisters/follow");
               $QUESTIONS=$this->createModel(MOD_FOLLOW,"questions","questions");
			   $QUESTIONS->view="vw_questions";
			   $questions=$QUESTIONS->get(array("page"=>-1,"pagesize"=>-1,"where"=>("id_type_protocol=".$id_type_protocol),"order"=>"type_segment_priority ASC, [priority] ASC"));
			   $values["questions"]=$questions["data"];

               $REL_SINISTERS_QUESTIONS=$this->createModel(MOD_FOLLOW,"rel_sinisters_questions","rel_sinisters_questions");
			   $REL_SINISTERS_QUESTIONS->view="vw_rel_sinisters_questions";
			   $rel_sinisters_questions=array();
			   for($i=0;$i<$actual_review;$i++){
			      $ret=$REL_SINISTERS_QUESTIONS->get(array("page"=>-1,"pagesize"=>-1,"where"=>("id_sinister=".$values["id"]." AND revision=".($i+1)),"order"=>"type_segment_priority ASC, [priority] ASC"));
				  $rel_sinisters_questions[]=$ret["data"];
			   }
			   $values["rel_sinisters_questions"]=$rel_sinisters_questions;

               $DISCHARGES=$this->createModel(MOD_FOLLOW,"discharges","discharges");
			   $discharge=$DISCHARGES->get(array("page"=>-1,"pagesize"=>-1,"where"=>"offline IS null AND id_sinister=".$values["id"]));
			   $values["discharge"]=$discharge["data"];
			}
			$whereProtocol="";
			$actualProtocol=secureComboPosition($values["records"],"id_type_protocol");
			if ((int)$values["id"]==0){
				$whereProtocol="id=1";
				$actualProtocol=1;
			}

			$wherePriority="";
			//if((int)$values["id"]==0){$wherePriority="id=6";}

            $parameters_id_type_cancelation=array(
                "model"=>(MOD_FOLLOW."/Type_cancelations"),
                "table"=>"type_cancelations",
                "name"=>"id_type_cancelation",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_cancelation"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_priority=array(
                "model"=>(MOD_FOLLOW."/Type_priorities"),
                "table"=>"type_priorities",
                "name"=>"id_type_priority",
                "class"=>"form-control dbase",
                "empty"=>false,
                "id_actual"=>secureComboPosition($values["records"],"id_type_priority"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>$wherePriority,"order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_protocol=array(
                "model"=>(MOD_FOLLOW."/Type_protocols"),
                "table"=>"type_protocols",
                "name"=>"id_type_protocol",
                "class"=>"form-control dbase validate",
                "empty"=>false,
                "id_actual"=>$actualProtocol,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>$whereProtocol,"order"=>"description ASC","pagesize"=>-1),
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
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
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
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
            $values["controls"]=array(
                "id_type_cancelation"=>getCombo($parameters_id_type_cancelation,$this),
                "id_type_priority"=>getCombo($parameters_id_type_priority,$this),
                "id_type_protocol"=>getCombo($parameters_id_type_protocol,$this),
                "id_type_contingency"=>getCombo($parameters_id_type_contingency,$this),
                "id_type_contingency_request"=>getCombo($parameters_id_type_contingency,$this),
                "id_type_art"=>getCombo($parameters_id_type_art,$this),
            );
			$values["is_doctor"]=$is_doctor;
			$values["canceled"]=(secureComboPosition($values["records"],"id_type_status")=="6");
			$values["is_admin"]=false;
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
			if (!isset($values["s_admin"])){$values["s_admin"]="";}
			if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
			if ($values["s_admin"]=="S") {
			   if ($fields!=null){
				   $id_type_status=(int)$fields["id_type_status"];
				   $fields["accident_date"]= str_replace("T", " ", $fields["accident_date"]);
				   $fields["fault_date"]= str_replace("T", " ", $fields["fault_date"]);
				   $fields["aid_date"]= str_replace("T", " ", $fields["aid_date"]);
				   $fields["next_revision_date"]= str_replace("T", " ", $fields["next_revision_date"]);
				   $values=$fields;
				   $values["id"]=$id;
			   } else {
		   		   $id_type_status=0;
			   }
			} else {
				$id_type_status=1;
			}
			$values["accident_date"]= str_replace("T", " ", $values["accident_date"]);
			$values["fault_date"]= str_replace("T", " ", $values["fault_date"]);
			$values["aid_date"]= str_replace("T", " ", $values["aid_date"]);
			$values["next_revision_date"]= str_replace("T", " ", $values["next_revision_date"]);
			$id_type_protocol=secureEmptyNull($values,"id_type_protocol");
			$id_type_priority=secureEmptyNull($values,"id_type_priority");

			$sinister=$this->get(array("where"=>"module_sinister='".$values["module_sinister"]."' AND version=".$values["version"]));
			if ((int)$sinister["totalrecords"]!=0){
			    if ($id==0){throw new Exception(lang("error_2002"),2002);}
			}
			if($fields==null){
			    /*Valores forzados!*/
				$values["test_date"] = $values["accident_date"]; // Forzado!
				$values["test_type"] = "PCR"; // Forzado!
				$values["test_confirm"] = 1; // Forzado!

				$fields = array(	
					'code' => $values["code"],
					'description' => $values["description"],
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
					'id_user'=>$values["id_user_active"],
					'id_type_cancelation'=>secureEmptyNull($values,"id_type_cancelation"),
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
					'id_type_protocol'=>$id_type_protocol,
					'id_type_art'=>secureEmptyNull($values,"id_type_art"),
					'id_type_priority'=>$id_type_priority,
					'medical_notes'=>$values["medical_notes"],
					'version'=>$values["version"],
					'full_vacuna'=>$values["full_vacuna"],
				);
			}
			if ($values["s_admin"]=="") {
				if($id==0){
					$TYPE_PROTOCOLS=$this->createModel(MOD_FOLLOW,"type_protocols","type_protocols");
					$type_protocol=$TYPE_PROTOCOLS->get(array("where"=>"id=".$id_type_protocol));
					$max_reviews=$type_protocol["data"][0]["occurs"];
					if((int)$values["pass_to_review"]!=0){$id_type_status=2;}
					if($fields==null){$fields=array();}
					$fields["created"]=$this->now;
					$fields["verified"]=$this->now;
					$fields["offline"]=null;
					$fields["actual_review"]=0;
					$fields["max_reviews"]=$max_reviews;
				} else {
					//Si el status es 1 y se chequea pasar a atencion medica! Solamente ahi
					if((int)$values["pass_to_review"]!=0 and secureEmptyNull($values,"id_type_status")==1){$id_type_status=2;}
				}
				if (secureEmptyNull($values,"id_type_cancelation")!=null){
				   $values["cancel_by_patient"]=1;
				   $values["pass_to_review"]=0;
				   $id_type_status=6;
				}
				//if((int)$values["pass_to_review"]==0){$id_type_priority=6;}
				//$fields["id_type_priority"]=$id_type_priority;
				$fields['id_user']=$values["id_user_active"];
			} else{
			   unset($fields["id_type_priority"]);
			   unset($values["id_type_priority"]);
			}
			if ($id_type_status!=0) {$fields["id_type_status"]=$id_type_status;}
			$saved=parent::save($values,$fields);
			$this->unlock(array("id"=>$saved["data"]["id"]));
			return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
	public function process($values){
		$this->execAdHoc("UPDATE ".MOD_FOLLOW."_sinisters SET fum='".$this->now."', id_type_status=".$values["id_type_status"]." WHERE id=".$values["id"]);
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
        );
	}
	public function buildAltaMedica($id_sinister,$mode,$id_user=null){
        try {
			$preferences=getPreference($this,array("id_user_active"=>$id_user),2);
		    $html="";
			$this->view="vw_sinisters";
			$sinister=$this->get(array("where"=>"id=".$id_sinister));
			$DISCHARGES=$this->createModel(MOD_FOLLOW,"discharges","discharges");
			$DISCHARGES->view="vw_discharges";
			$discharge=$DISCHARGES->get(array("where"=>"offline IS null AND id_sinister=".$id_sinister));
			if ((int)$sinister["totalrecords"]!=0){
				$html="<div style='position:absolute;left:0px;top:0px;font-size:11px;font-family:console;width:210mm;height:297mm;'>";
				if((int)$mode>=1){$html.="<img src='./assets/img/form0772.jpg' style='width:100%;'/>";}
				$html.="</div>";
				$fontFamily="console";
				$rec=$sinister["data"][0];
				$recDis=$discharge["data"][0];
				$DOCTORS=$this->createModel(MOD_TELEMEDICINA,"Doctors","Doctors");
				$doctor=$DOCTORS->get(array("where"=>"username='".$recDis["doctor"]."'"));

				$art=$rec["type_art"];
				if($art==""){$art=$rec["art"];}
				$param[]=array("text"=>$art,"left"=>"35mm","top"=>"20mm","fontsize"=>"20px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["module_sinister"],"left"=>"37mm","top"=>"29mm","fontsize"=>"18px","fontfamily"=>$fontFamily);
				/*Datos del trabajador*/
				$param[]=array("text"=>$rec["surname"].", ".$rec["name"],"left"=>"40mm","top"=>"43mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["document"],"left"=>"137mm","top"=>"43mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$date=$rec["birthdate"];
				if($date!=""){
					$param[]=array("text"=>date("d", strtotime($date)),"left"=>"43mm","top"=>"47mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", strtotime($date)),"left"=>"52mm","top"=>"47mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", strtotime($date)),"left"=>"60mm","top"=>"47mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				switch($rec["sex"]){
					case "M":
					   $left="84mm";
					   break;
					case "F":
					   $left="91mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"45mm","fontsize"=>"30px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_street"],"left"=>"20mm","top"=>"52mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_number"],"left"=>"91mm","top"=>"52mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_floor"],"left"=>"112mm","top"=>"52mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_apto"],"left"=>"134mm","top"=>"52mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_location"],"left"=>"159mm","top"=>"52mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_province"],"left"=>"25mm","top"=>"56mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_postal_code"],"left"=>"65mm","top"=>"56mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_prefix_phone"],"left"=>"103mm","top"=>"56mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_phone"],"left"=>"114mm","top"=>"56mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_prefix_cel"],"left"=>"157mm","top"=>"56mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_cel"],"left"=>"170mm","top"=>"56mm","fontsize"=>"12px","fontfamily"=>$fontFamily);

				/*Datos del empleador*/
				$param[]=array("text"=>$rec["company_name"],"left"=>"44mm","top"=>"69mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["company_cuit"],"left"=>"141mm","top"=>"69mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				/*Datos del prestador*/
				$param[]=array("text"=>$rec["sanity_name"],"left"=>"69mm","top"=>"80mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_cuit"],"left"=>"161mm","top"=>"80mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_street"],"left"=>"20mm","top"=>"85mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_number"],"left"=>"92mm","top"=>"85mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_floor"],"left"=>"112mm","top"=>"85mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_apto"],"left"=>"134mm","top"=>"85mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_location"],"left"=>"159mm","top"=>"85mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_province"],"left"=>"26mm","top"=>"89mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_postal_code"],"left"=>"65mm","top"=>"89mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_prefix_phone"],"left"=>"97mm","top"=>"89mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_phone"],"left"=>"114mm","top"=>"89mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_fax"],"left"=>"155mm","top"=>"89mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_email"],"left"=>"19mm","top"=>"93mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				/*Descripción del motivo del la consulta*/
				switch((int)$rec["id_type_contingency"]){
					case 1: //Accidente de trabajo
					   $left="40mm";
					   break;
					case 2: //Accidente in itere
					   $left="89mm";
					   break;
					case 3: //Enfermedad profesional
					   $left="141mm";
					   break;
					case 4: //Intercurrencia
					   $left="186mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"101mm","fontsize"=>"30px","fontfamily"=>$fontFamily);
				$date=$rec["accident_date"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"108mm","top"=>"108mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"116mm","top"=>"108mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"124mm","top"=>"108mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"141mm","top"=>"108mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"146mm","top"=>"108mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$date=$rec["fault_date"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"108mm","top"=>"112mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"116mm","top"=>"112mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"124mm","top"=>"112mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"141mm","top"=>"112mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"146mm","top"=>"112mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$date=$rec["aid_date"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"108mm","top"=>"116mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"116mm","top"=>"116mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"124mm","top"=>"116mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"141mm","top"=>"116mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"146mm","top"=>"116mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$ret=$this->getCalcLine(($recDis["sinopsys_discharge"]),0,100);
				$param[]=array("text"=>$ret["value"],"left"=>"62mm","top"=>"121mm","fontsize"=>"9px","fontfamily"=>"courier");
				$ret=$this->getCalcLine($ret["rest"],0,120);
				$param[]=array("text"=>$ret["value"],"left"=>"10mm","top"=>"125mm","fontsize"=>"9px","fontfamily"=>"courier");
				$ret=$this->getCalcLine($ret["rest"],0,120);
				$param[]=array("text"=>$ret["value"],"left"=>"10mm","top"=>"129mm","fontsize"=>"9px","fontfamily"=>"courier");

				$ret=$this->getCalcLine($recDis["prognosys_discharge"],0,110);
				$param[]=array("text"=>$ret["value"],"left"=>"29mm","top"=>"134mm","fontsize"=>"9px","fontfamily"=>"courier");
				$ret=$this->getCalcLine($ret["rest"],0,120);
				$param[]=array("text"=>$ret["value"],"left"=>"10mm","top"=>"138mm","fontsize"=>"9px","fontfamily"=>"courier");
				$ret=$this->getCalcLine($ret["rest"],0,120);
				$param[]=array("text"=>$ret["value"],"left"=>"10mm","top"=>"142mm","fontsize"=>"9px","fontfamily"=>"courier");

				$ret=$this->getCalcLine($recDis["indications_discharge"],0,100);
				$param[]=array("text"=>$ret["value"],"left"=>"51mm","top"=>"149mm","fontsize"=>"9px","fontfamily"=>"courier");
				$ret=$this->getCalcLine($ret["rest"],0,120);
				$param[]=array("text"=>$ret["value"],"left"=>"10mm","top"=>"153mm","fontsize"=>"9px","fontfamily"=>"courier");
				$ret=$this->getCalcLine($ret["rest"],0,120);
				$param[]=array("text"=>$ret["value"],"left"=>"10mm","top"=>"157mm","fontsize"=>"9px","fontfamily"=>"courier");
				$ret=$this->getCalcLine($ret["rest"],0,120);
				$param[]=array("text"=>$ret["value"],"left"=>"10mm","top"=>"161mm","fontsize"=>"9px","fontfamily"=>"courier");

				/*constancia de alta medica*/
				if((int)$recDis["is_medical_discharge"]==1){$param[]=array("text"=>"X","left"=>"76mm","top"=>"163mm","fontsize"=>"30px","fontfamily"=>$fontFamily);}
				$left="-100mm";
				switch((string)$recDis["more_treatment"]){
					case "1": 
					   $left="62mm";
					   break;
					case "0": 
					   $left="76mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"170mm","fontsize"=>"20px","fontfamily"=>$fontFamily);
				if((int)$recDis["odontology"]==1){$param[]=array("text"=>"X","left"=>"25mm","top"=>"175mm","fontsize"=>"20px","fontfamily"=>$fontFamily);}
				if((int)$recDis["dermatology"]==1){$param[]=array("text"=>"X","left"=>"44mm","top"=>"175mm","fontsize"=>"20px","fontfamily"=>$fontFamily);}
				if((int)$recDis["psicoterapy"]==1){$param[]=array("text"=>"X","left"=>"63mm","top"=>"175mm","fontsize"=>"20px","fontfamily"=>$fontFamily);}

				$date=$recDis["next_revision_date"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"56mm","top"=>"179mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"63mm","top"=>"179mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"68mm","top"=>"179mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"82mm","top"=>"179mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"87mm","top"=>"179mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}

				$left="-100mm";
				switch((string)$recDis["requalification"]){
					case "1": 
					   $left="62mm";
					   break;
					case "0": 
					   $left="76mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"182mm","fontsize"=>"20px","fontfamily"=>$fontFamily);

				$date=$recDis["back_work"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"56mm","top"=>"187mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"63mm","top"=>"187mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"68mm","top"=>"187mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"82mm","top"=>"187mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"87mm","top"=>"187mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$date=$recDis["treatment_end_date"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"56mm","top"=>"191mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"63mm","top"=>"191mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"68mm","top"=>"191mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"82mm","top"=>"191mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"87mm","top"=>"191mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}

				if((int)$recDis["medical_discharge"]==1){$param[]=array("text"=>"X","left"=>"43mm","top"=>"198mm","fontsize"=>"20px","fontfamily"=>$fontFamily);}
				if((int)$recDis["reject"]==1){$param[]=array("text"=>"X","left"=>"43mm","top"=>"202mm","fontsize"=>"20px","fontfamily"=>$fontFamily);}
				if((int)$recDis["death"]==1){$param[]=array("text"=>"X","left"=>"43mm","top"=>"206mm","fontsize"=>"20px","fontfamily"=>$fontFamily);}
				if((int)$recDis["treatment_end"]==1){$param[]=array("text"=>"X","left"=>"43mm","top"=>"210mm","fontsize"=>"20px","fontfamily"=>$fontFamily);}
				if((int)$recDis["referral"]==1){$param[]=array("text"=>"X","left"=>"43mm","top"=>"214mm","fontsize"=>"20px","fontfamily"=>$fontFamily);}
				$param[]=array("text"=>$recDis["type_referral"],"left"=>"72mm","top"=>"214mm","fontsize"=>"12px","fontfamily"=>$fontFamily);

				$left="-100mm";
				switch((string)$recDis["inculpable_disease"]){
					case "1": 
					   $left="61mm";
					   break;
					case "0": 
					   $left="68mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"217mm","fontsize"=>"20px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$recDis["inculpable_disease_detail"],"left"=>"72mm","top"=>"218mm","fontsize"=>"12px","fontfamily"=>$fontFamily);

				$left="-100mm";
				switch((string)$recDis["sequels"]){
					case "1": 
					   $left="63mm";
					   break;
					case "0": 
					   $left="89mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"221mm","fontsize"=>"20px","fontfamily"=>$fontFamily);
				$left="-100mm";
				switch((string)$recDis["maintenance_services"]){
					case "1": 
					   $left="63mm";
					   break;
					case "0": 
					   $left="89mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"225mm","fontsize"=>"20px","fontfamily"=>$fontFamily);

				/*Constancia de fin de tratamiento*/
				if((int)$recDis["is_treatment_end"]==1){$param[]=array("text"=>"X","left"=>"189mm","top"=>"163mm","fontsize"=>"30px","fontfamily"=>$fontFamily);}
				$date=$recDis["treatment_end_date2"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"155mm","top"=>"175mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"162mm","top"=>"175mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"167mm","top"=>"175mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"182mm","top"=>"175mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"187mm","top"=>"175mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$left="-100mm";
				switch((string)$recDis["sequels2"]){
					case "1": 
					   $left="162mm";
					   break;
					case "0": 
					   $left="187mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"181mm","fontsize"=>"20px","fontfamily"=>$fontFamily);
				$left="-100mm";
				switch((string)$recDis["requalification2"]){
					case "1": 
					   $left="162mm";
					   break;
					case "0": 
					   $left="187mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"189mm","fontsize"=>"20px","fontfamily"=>$fontFamily);
				$left="-100mm";
				switch((string)$recDis["maintenance_services2"]){
					case "1": 
					   $left="162mm";
					   break;
					case "0": 
					   $left="187mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"197mm","fontsize"=>"20px","fontfamily"=>$fontFamily);

				$date=$recDis["created"];
				if($date!=""){
				    $date=strtotime($date);
					$footer_place=("Buenos Aires, ".date("d", $date)."/".date("m", $date)."/".date("Y", $date));
					$param[]=array("text"=>$footer_place,"left"=>"20mm","top"=>"276mm","fontsize"=>"12px","fontfamily"=>"courier");
				}

				$signed=true;
				if($preferences!=null) {
					if((int)$preferences["totalrecords"]!=0){
					   if ((int)$preferences["data"][0]["value"]==1){$signed=false;}
				    }
				}

				if($signed){
					$doctor_name=$doctor["data"][0]["name"]." ".$doctor["data"][0]["surname"];
					$doctor_license=$doctor["data"][0]["license"];
					$doctor_sign="<img src='".$doctor["data"][0]["image"]."' style='width:175px;'/>";
					$param[]=array("text"=>$doctor_sign,"left"=>"145mm","top"=>"250mm","fontsize"=>"12px","fontfamily"=>"courier");
					$param[]=array("text"=>$doctor_name." / ".$doctor_license,"left"=>"145mm","top"=>"276.75mm","fontsize"=>"10px","fontfamily"=>"courier","color"=>"#404040");
				}

				foreach ($param as $item) {$html.=$this->drawField($item);}
 			} else {
				$html="<div style='font-family:arial;width:100%;'>";
				$html.="   <h1>Siniestro inexistente</h1>";
				$html.="</div>";
			}
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
		return $html;	
	}
	public function buildAltaMedicaLaboral($id_sinister,$mode,$id_user=null){
        try {
		    $preferences=getPreference($this,array("id_user_active"=>$id_user),2);
		    $html="";
			$this->view="vw_sinisters";
			$sinister=$this->get(array("where"=>"id=".$id_sinister));
			$DISCHARGES=$this->createModel(MOD_FOLLOW,"discharges","discharges");
			$DISCHARGES->view="vw_discharges";
			$discharge=$DISCHARGES->get(array("where"=>"offline IS null AND id_sinister=".$id_sinister));

			if ((int)$sinister["totalrecords"]!=0){
				$recDis=$discharge["data"][0];
				$fontFamily="console";
				$rec=$sinister["data"][0];
				$art=$rec["type_art"];
				if($art==""){$art=$rec["art"];}

				$date=$recDis["created"];
				$place="Buenos Aires, ".date(FORMAT_DATE_DMY,strtotime($date));
				$param[]=array("text"=>$place,"left"=>"120mm","top"=>"15mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>"Siniestro: ".$rec["module_sinister"],"left"=>"15mm","top"=>"25mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$DOCTORS=$this->createModel(MOD_TELEMEDICINA,"Doctors","Doctors");
				$doctor=$DOCTORS->get(array("where"=>"username='".$recDis["doctor"]."'"));

				/*Datos del trabajador*/
				$param[]=array("text"=>"Trabajador: ".$rec["surname"].", ".$rec["name"],"left"=>"15mm","top"=>"35mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>"DNI: ".$rec["document"],"left"=>"15mm","top"=>"45mm","fontsize"=>"12px","fontfamily"=>$fontFamily);

				$line1="Dadas las condiciones clínicas del paciente, habiendo sido un caso de Enfermedad leve por Covid-19 y";
				$line2="habiendo cumplido los días de aislamiento epidemiológico recomendados por el Ministerio de Salud de la Nación.";
				$line3="Se procede a otorgar el Alta Epidemiológica por Teleasistencia en el día de la fecha. Se adjunta formulario de";
				$line4="ALTA MEDICA de ART. El cual debe presentar a su Empleador, al presentarse a trabajar en el día de mañana.";

				$param[]=array("text"=>$line1,"left"=>"25mm","top"=>"55mm","fontsize"=>"10.5px","fontfamily"=>"courier");
				$param[]=array("text"=>$line2,"left"=>"15mm","top"=>"60mm","fontsize"=>"10.5px","fontfamily"=>"courier");
				$param[]=array("text"=>$line3,"left"=>"15mm","top"=>"65mm","fontsize"=>"10.5px","fontfamily"=>"courier");
				$param[]=array("text"=>$line4,"left"=>"15mm","top"=>"70mm","fontsize"=>"10.5px","fontfamily"=>"courier");

				$signed=true;
				if($preferences!=null) {
					if((int)$preferences["totalrecords"]!=0){
					   if ((int)$preferences["data"][0]["value"]==1){$signed=false;}
				    }
				}
				if($signed){
					$doctor_name=$doctor["data"][0]["name"]." ".$doctor["data"][0]["surname"];
					$doctor_license=$doctor["data"][0]["license"];
					$doctor_sign="<img src='".$doctor["data"][0]["image"]."' style='width:175px;'/>";
					$param[]=array("text"=>$doctor_sign,"left"=>"120mm","top"=>"80mm","fontsize"=>"12px","fontfamily"=>"courier");
					$param[]=array("text"=>$doctor_name." / ".$doctor_license,"left"=>"120mm","top"=>"120mm","fontsize"=>"10px","fontfamily"=>"courier","color"=>"#404040");
				}
				foreach ($param as $item) {$html.=$this->drawField($item);}
 			} else {
				$html="<div style='font-family:arial;width:100%;'>";
				$html.="   <h1>Siniestro inexistente</h1>";
				$html.="</div>";
			}
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
		return $html;	
	}
	public function buildRevision($id_sinister,$nRevision,$mode,$id_user=null){
        try {
		    $preferences=getPreference($this,array("id_user_active"=>$id_user),2);
			$html="";
			$SINISTERS=$this->createModel(MOD_FOLLOW,"sinisters","sinisters");
			$SINISTERS->view="vw_sinisters";
			$sinister=$SINISTERS->get(array("where"=>"id=".$id_sinister));

			$REL_SINISTERS_QUESTIONS=$this->createModel(MOD_FOLLOW,"rel_sinisters_questions","rel_sinisters_questions");
			$REL_SINISTERS_QUESTIONS->view="vw_rel_sinisters_questions";
			$REL_SINISTERS_QUESTIONS->get(array("id_sinister"=>$id_sinister));
			$revision=$REL_SINISTERS_QUESTIONS->get(array("page"=>-1,"pagesize"=>-1,"where"=>("id_sinister=".$id_sinister." AND revision=".$nRevision." AND id_type_segment IN (6,7)"),"order"=>"type_segment_priority ASC, [priority] ASC"));

			if ((int)$sinister["totalrecords"]!=0){
				$html="<div style='position:absolute;left:0px;top:0px;font-size:11px;font-family:console;width:210mm;height:297mm;'>";
				if((int)$mode>=1){$html.="<img src='./assets/img/form0080.jpg' style='width:100%;'/>";}
				$html.="</div>";

				$fontFamily="console";
				$rec=$sinister["data"][0];
				$art=$rec["type_art"];
				if($art==""){$art=$rec["art"];}
				$param[]=array("text"=>$art,"left"=>"38mm","top"=>"20mm","fontsize"=>"20px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["module_sinister"],"left"=>"38mm","top"=>"37mm","fontsize"=>"18px","fontfamily"=>$fontFamily);
				switch((int)$rec["id_type_contingency"]){
					case 1: //Accidente de trabajo
					   $left="74mm";
					   break;
					case 2: //Accidente in itere
					   $left="115mm";
					   break;
					case 3: //Enfermedad profesional
					   $left="161mm";
					   break;
					case 4: //Intercurrencia
					   $left="193mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"46mm","fontsize"=>"30px","fontfamily"=>$fontFamily);
				/*Datos del trabajador*/
				$param[]=array("text"=>$rec["surname"].", ".$rec["name"],"left"=>"41mm","top"=>"68mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["document"],"left"=>"138mm","top"=>"68mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				/*Datos del empleador*/
				$param[]=array("text"=>$rec["company_name"],"left"=>"47mm","top"=>"83mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["company_cuit"],"left"=>"143mm","top"=>"83mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				/*Datos del prestador*/
				$param[]=array("text"=>$rec["sanity_name"],"left"=>"70mm","top"=>"99mm","fontsize"=>"12px","fontfamily"=>$fontFamily);

				$DOCTORS=$this->createModel(MOD_TELEMEDICINA,"Doctors","Doctors");
				$doctor=null;
			    foreach ($revision["data"] as $record){
					if ($doctor==null){$doctor=$DOCTORS->get(array("where"=>"username='".$record["username"]."'"));}
				    switch((int)$record["id_question"]){
					   case 23://Próxima revisión
						  $date=$record["value"];
						  if($date!=""){
							 $date=strtotime($date);
							 $param[]=array("text"=>date("d", $date),"left"=>"73mm","top"=>"216mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
							 $param[]=array("text"=>date("m", $date),"left"=>"81mm","top"=>"216mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
							 $param[]=array("text"=>date("Y", $date),"left"=>"89mm","top"=>"216mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
							 $param[]=array("text"=>date("H", $date).":","left"=>"106mm","top"=>"216mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
							 $param[]=array("text"=>date("i", $date),"left"=>"111mm","top"=>"216mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
						  }
					      break;
					   case 24:
						  $param[]=array("text"=>$record["value"],"left"=>"22mm","top"=>"269mm","fontsize"=>"12px","fontfamily"=>"courier");
					      break;
					   case 26://Descripción del motivo de consulta
						  $ret=$this->getCalcLine($record["value"],0,100);
						  $param[]=array("text"=>$ret["value"],"left"=>"64mm","top"=>"116mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"123mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"130mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"136mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"142mm","fontsize"=>"9px","fontfamily"=>"courier");
					      break;
					   case 27://Diagnóstico
						  $value=$record["value"];
						  $ret=$this->getCalcLine($record["value"],0,110);
						  $param[]=array("text"=>$ret["value"],"left"=>"31mm","top"=>"148mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"154mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"161mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"167mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"173mm","fontsize"=>"9px","fontfamily"=>"courier");
					      break;
					   case 28://Indicaciones / Tratamiento
						  $value=$record["value"];
						  $ret=$this->getCalcLine($record["value"],0,100);
						  $param[]=array("text"=>$ret["value"],"left"=>"52mm","top"=>"179mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"185mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"192mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"198mm","fontsize"=>"9px","fontfamily"=>"courier");
						  $ret=$this->getCalcLine($ret["rest"],0,120);
						  $param[]=array("text"=>$ret["value"],"left"=>"13mm","top"=>"203mm","fontsize"=>"9px","fontfamily"=>"courier");
					      break;
					}

					$value=$record["value"];
					$code=$record["code_type_control"];
					$key="";
					$class="class='form-control";
					$custom="";
					$possible_values="";
					$params=array("value"=>$value,"code"=>$code,"key"=>$key,"class"=>$class,"custom"=>$custom,"possible_values"=>$possible_values);
					$html.="<tr><td width='25%'><b>".$record["description"]."</b></td><td>".getFromControls($params,true)."</td></tr>";
				}

				$signed=true;
				if($preferences!=null) {
					if((int)$preferences["totalrecords"]!=0){
					   if ((int)$preferences["data"][0]["value"]==1){$signed=false;}
				    }
				}
				if($signed){
					$doctor_name=$doctor["data"][0]["name"]." ".$doctor["data"][0]["surname"];
					$doctor_license=$doctor["data"][0]["license"];
					$doctor_sign="<img src='".$doctor["data"][0]["image"]."' style='width:175px;'/>";
					$param[]=array("text"=>$doctor_sign,"left"=>"145mm","top"=>"230mm","fontsize"=>"12px","fontfamily"=>"courier");
					$param[]=array("text"=>$doctor_name." / ".$doctor_license,"left"=>"143.20mm","top"=>"269.75mm","fontsize"=>"10px","fontfamily"=>"courier","color"=>"#404040");
				}

				foreach ($param as $item) {$html.=$this->drawField($item);}

				$html.="    </table>";
				$html.="</div>";
 			} else {
				$html="<div style='font-family:arial;width:100%;'>";
				$html.="   <h1>Siniestro inexistente</h1>";
				$html.="</div>";
			}
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
		return $html;	
	}
	public function buildSiniestro($id_sinister,$mode,$id_user=null){
        try {
		    $preferences=getPreference($this,array("id_user_active"=>$id_user),2);
		    $html="";
			$this->view="vw_sinisters";
			$sinister=$this->get(array("where"=>"id=".$id_sinister));
			if ((int)$sinister["totalrecords"]!=0){
				$html="<div style='position:absolute;left:0px;top:0px;font-size:11px;font-family:console;width:210mm;height:297mm;'>";
				if((int)$mode>=1){$html.="<img src='./assets/img/form0069.jpg' style='width:100%;'/>";}
				$html.="</div>";
				$fontFamily="console";
				$rec=$sinister["data"][0];
				$art=$rec["type_art"];
				if($art==""){$art=$rec["art"];}
				$param[]=array("text"=>$art,"left"=>"35mm","top"=>"12mm","fontsize"=>"20px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["module_sinister"],"left"=>"37mm","top"=>"23mm","fontsize"=>"18px","fontfamily"=>$fontFamily);
				$DOCTORS=$this->createModel(MOD_TELEMEDICINA,"Doctors","Doctors");
				$doctor=$DOCTORS->get(array("where"=>"username='".$rec["doctor_control"]."'"));
				switch((int)$rec["id_type_contingency"]){
					case 1: //Accidente de trabajo
					   $left="73mm";
					   break;
					case 2: //Accidente in itere
					   $left="114mm";
					   break;
					case 3: //Enfermedad profesional
					   $left="160mm";
					   break;
					case 4: //Intercurrencia
					   $left="192mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"30mm","fontsize"=>"30px","fontfamily"=>$fontFamily);
				/*Datos del trabajador*/
				$param[]=array("text"=>$rec["surname"].", ".$rec["name"],"left"=>"38mm","top"=>"47mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["document"],"left"=>"137mm","top"=>"47mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				switch($rec["sex"]){
					case "M":
					   $left="185mm";
					   break;
					case "F":
					   $left="193mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"45mm","fontsize"=>"30px","fontfamily"=>$fontFamily);
				$date=$rec["birthdate"];
				if ($date!="") {$param[]=array("text"=>date(FORMAT_DATE_DMY, strtotime($date)),"left"=>"41mm","top"=>"54mm","fontsize"=>"12px","fontfamily"=>$fontFamily);}
				$param[]=array("text"=>$rec["personal_address_street"],"left"=>"72mm","top"=>"54mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_number"],"left"=>"16mm","top"=>"60mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_floor"],"left"=>"39mm","top"=>"60mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_apto"],"left"=>"57mm","top"=>"60mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_location"],"left"=>"79mm","top"=>"60mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_province"],"left"=>"139mm","top"=>"60mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_address_postal_code"],"left"=>"180mm","top"=>"60mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_prefix_phone"],"left"=>"33mm","top"=>"67mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_phone"],"left"=>"46mm","top"=>"67mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal__prefix_cel"],"left"=>"88mm","top"=>"67mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_cel"],"left"=>"103mm","top"=>"67mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_prefix_alt_phone"],"left"=>"151mm","top"=>"67mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_alt_phone"],"left"=>"163mm","top"=>"67mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["personal_email"],"left"=>"21mm","top"=>"74mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				/*Datos del empleador*/
				$param[]=array("text"=>$rec["company_name"],"left"=>"43mm","top"=>"89mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["company_cuit"],"left"=>"146mm","top"=>"89mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["company_address"],"left"=>"38mm","top"=>"95mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["company_prefix_phone"],"left"=>"147mm","top"=>"95mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["company_phone"],"left"=>"159mm","top"=>"95mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["company_contact"],"left"=>"41mm","top"=>"102mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["company_contact_prefix_cel"],"left"=>"99mm","top"=>"102mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["company_contact_cel"],"left"=>"109mm","top"=>"102mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["company_email"],"left"=>"137mm","top"=>"102mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["accident_place"],"left"=>"87mm","top"=>"108mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["accident_address"],"left"=>"38mm","top"=>"115mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["accident_prefix_phone"],"left"=>"148mm","top"=>"115mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["accident_phone"],"left"=>"161mm","top"=>"115mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["accident_contact"],"left"=>"41mm","top"=>"121mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["accident_contact_prefix_cel"],"left"=>"99mm","top"=>"121mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["accident_contact_cel"],"left"=>"109mm","top"=>"121mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["accident_contact_email"],"left"=>"137mm","top"=>"121mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				/*Datos del prestador*/
				$param[]=array("text"=>$rec["sanity_name"],"left"=>"65mm","top"=>"136mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_cuit"],"left"=>"163mm","top"=>"136mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_street"],"left"=>"19mm","top"=>"143mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_number"],"left"=>"91mm","top"=>"143mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_floor"],"left"=>"113mm","top"=>"143mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_apto"],"left"=>"135mm","top"=>"143mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_location"],"left"=>"160mm","top"=>"143mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_province"],"left"=>"24mm","top"=>"150mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_address_postal_code"],"left"=>"67mm","top"=>"150mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_prefix_phone"],"left"=>"99mm","top"=>"150mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_phone"],"left"=>"113mm","top"=>"150mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_fax"],"left"=>"155mm","top"=>"150mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>$rec["sanity_email"],"left"=>"21mm","top"=>"156mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				/*Descripción del motivo del la consulta*/
				switch((int)$rec["id_type_contingency"]){
					case 1: //Accidente de trabajo
					   $left="41mm";
					   break;
					case 2: //Accidente in itere
					   $left="90mm";
					   break;
					case 3: //Enfermedad profesional
					   $left="148mm";
					   break;
					case 4: //Intercurrencia
					   $left="193mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"169mm","fontsize"=>"30px","fontfamily"=>$fontFamily);
				$date=$rec["accident_date"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"105mm","top"=>"178mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"113mm","top"=>"178mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"122mm","top"=>"178mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"138mm","top"=>"178mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"143mm","top"=>"178mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$date=$rec["fault_date"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"105mm","top"=>"184mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"113mm","top"=>"184mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"122mm","top"=>"184mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"138mm","top"=>"184mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"143mm","top"=>"184mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$date=$rec["aid_date"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"105mm","top"=>"190mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"113mm","top"=>"190mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"122mm","top"=>"190mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"138mm","top"=>"190mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"143mm","top"=>"190mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$param[]=array("text"=>$rec["sinopsys"],"left"=>"57mm","top"=>"198mm","fontsize"=>"9px","fontfamily"=>"courier");
				$param[]=array("text"=>$rec["sinopsys1"],"left"=>"11mm","top"=>"204mm","fontsize"=>"9px","fontfamily"=>"courier");
				$param[]=array("text"=>$rec["sinopsys2"],"left"=>"11mm","top"=>"209mm","fontsize"=>"9px","fontfamily"=>"courier");
				$param[]=array("text"=>$rec["prognosys"],"left"=>"28mm","top"=>"215mm","fontsize"=>"9px","fontfamily"=>"courier");
				$param[]=array("text"=>$rec["prognosys1"],"left"=>"11mm","top"=>"221mm","fontsize"=>"9px","fontfamily"=>"courier");
				$param[]=array("text"=>$rec["prognosys2"],"left"=>"11mm","top"=>"226mm","fontsize"=>"9px","fontfamily"=>"courier");
				$param[]=array("text"=>$rec["indications"],"left"=>"47mm","top"=>"232mm","fontsize"=>"9px","fontfamily"=>"courier");
				$param[]=array("text"=>$rec["indications1"],"left"=>"11mm","top"=>"238mm","fontsize"=>"9px","fontfamily"=>"courier");
				$param[]=array("text"=>$rec["indications2"],"left"=>"11mm","top"=>"244mm","fontsize"=>"9px","fontfamily"=>"courier");
				$left="-100mm";
				switch((string)$rec["stop_work"]){
					case "1":
					   $left="53mm";
					   break;
					case "0":
					   $left="67mm";
					   break;
				}
			    $param[]=array("text"=>"X","left"=>$left,"top"=>"246mm","fontsize"=>"30px","fontfamily"=>$fontFamily);
				$date=$rec["free_date"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"173mm","top"=>"249mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"182mm","top"=>"249mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"189mm","top"=>"249mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$date=$rec["next_revision_date"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"49mm","top"=>"256mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"57mm","top"=>"256mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"64mm","top"=>"256mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("H", $date).":","left"=>"90mm","top"=>"256mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("i", $date),"left"=>"95mm","top"=>"256mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$date=$rec["back_work"];
				if($date!=""){
				    $date=strtotime($date);
					$param[]=array("text"=>date("d", $date),"left"=>"173mm","top"=>"256mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("m", $date),"left"=>"182mm","top"=>"256mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
					$param[]=array("text"=>date("Y", $date),"left"=>"189mm","top"=>"256mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				}
				$param[]=array("text"=>$rec["footer_place"],"left"=>"12mm","top"=>"277mm","fontsize"=>"12px","fontfamily"=>"courier");

				$signed=true;
				if($preferences!=null) {
					if((int)$preferences["totalrecords"]!=0){
					   if ((int)$preferences["data"][0]["value"]==1){$signed=false;}
				    }
				}

				if($signed){
					$doctor_name=$doctor["data"][0]["name"]." ".$doctor["data"][0]["surname"];
					$doctor_license=$doctor["data"][0]["license"];
					$doctor_sign="<img src='".$doctor["data"][0]["image"]."' style='width:175px;'/>";
					$param[]=array("text"=>$doctor_sign,"left"=>"145mm","top"=>"250mm","fontsize"=>"12px","fontfamily"=>"courier");
					$param[]=array("text"=>$doctor_name." / ".$doctor_license,"left"=>"145mm","top"=>"277mm","fontsize"=>"10px","fontfamily"=>"courier","color"=>"#404040");
				}
				foreach ($param as $item) {$html.=$this->drawField($item);}
 			} else {
				$html="<div style='font-family:arial;width:100%;'>";
				$html.="   <h1>Siniestro inexistente</h1>";
				$html.="</div>";
			}
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
		return $html;	
	}
	public function buildSiniestroCasoLeve($id_sinister,$mode,$id_user=null){
        try {
		    $preferences=getPreference($this,array("id_user_active"=>$id_user),2);
		    $html="";
			$this->view="vw_sinisters";
			$sinister=$this->get(array("where"=>"id=".$id_sinister));
			if ((int)$sinister["totalrecords"]!=0){
				$fontFamily="console";
				$rec=$sinister["data"][0];
				$art=$rec["type_art"];
				if($art==""){$art=$rec["art"];}
				$place="Buenos Aires, ".date(FORMAT_DATE_DMY,strtotime($rec["created"]));
				$param[]=array("text"=>$place,"left"=>"120mm","top"=>"15mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				
				$param[]=array("text"=>"Siniestro: ".$rec["module_sinister"],"left"=>"15mm","top"=>"25mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$DOCTORS=$this->createModel(MOD_TELEMEDICINA,"Doctors","Doctors");
				$doctor=$DOCTORS->get(array("where"=>"username='".$rec["doctor_control"]."'"));
				/*Datos del trabajador*/
				$param[]=array("text"=>"Trabajador: ".$rec["surname"].", ".$rec["name"],"left"=>"15mm","top"=>"35mm","fontsize"=>"12px","fontfamily"=>$fontFamily);
				$param[]=array("text"=>"DNI: ".$rec["document"],"left"=>"15mm","top"=>"45mm","fontsize"=>"12px","fontfamily"=>$fontFamily);

				$line1="Al analizar el caso, se constata caso leve en aislamiento domiciliario, cumpliendo el protocolo vigente";
				$line2="estipulado por el Ministerio de Salud, considerando que al no mediar complicaciones no requiere de mayor ";
				$line3="complejidad para su seguimiento. Y asimismo, en vistas de que el sistema de salud se encuentra atravesando"; 
				$line4="una situación de emergencia sanitaria, es que se procede a efectuar la consulta de manera virtual.";

				$param[]=array("text"=>$line1,"left"=>"25mm","top"=>"55mm","fontsize"=>"10.5px","fontfamily"=>"courier");
				$param[]=array("text"=>$line2,"left"=>"15mm","top"=>"60mm","fontsize"=>"10.5px","fontfamily"=>"courier");
				$param[]=array("text"=>$line3,"left"=>"15mm","top"=>"65mm","fontsize"=>"10.5px","fontfamily"=>"courier");
				$param[]=array("text"=>$line4,"left"=>"15mm","top"=>"70mm","fontsize"=>"10.5px","fontfamily"=>"courier");

				$signed=true;
				if($preferences!=null) {
					if((int)$preferences["totalrecords"]!=0){
					   if ((int)$preferences["data"][0]["value"]==1){$signed=false;}
				    }
				}
				if($signed){
					$doctor_name=$doctor["data"][0]["name"]." ".$doctor["data"][0]["surname"];
					$doctor_license=$doctor["data"][0]["license"];
					$doctor_sign="<img src='".$doctor["data"][0]["image"]."' style='width:175px;'/>";
					$param[]=array("text"=>$doctor_sign,"left"=>"120mm","top"=>"80mm","fontsize"=>"12px","fontfamily"=>"courier");
					$param[]=array("text"=>$doctor_name." / ".$doctor_license,"left"=>"120mm","top"=>"120mm","fontsize"=>"10px","fontfamily"=>"courier","color"=>"#404040");
				}
				foreach ($param as $item) {$html.=$this->drawField($item);}
 			} else {
				$html="<div style='font-family:arial;width:100%;'>";
				$html.="   <h1>Siniestro inexistente</h1>";
				$html.="</div>";
			}
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
		return $html;	
	}
	public function assignDoctor($values){
        try {
		    if ((int)$values["id_user_asigned"]==-1) {
               $fields = array('id_user_asigned' => null);
			} else {
               $fields = array('id_user_asigned' => $values["id_user_asigned"]);
			}
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            $ret=logError($e,__METHOD__ );
        }
        return $ret;
	}
	public function addOccurs($values) {
		$this->execAdHoc("UPDATE ".MOD_FOLLOW."_sinisters SET max_reviews=max_reviews+1, id_type_status=2 WHERE id=".$values["id"]);
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
        );
	}
    public function changePriority($values){
        try {
            $fields = array('id_type_priority' => $values["id_type_priority"]);
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            $ret=logError($e,__METHOD__ );
        }
        return $ret;
    }
    public function changeFullVacuna($values){
        try {
            $fields = array('full_vacuna' => $values["full_vacuna"]);
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            $ret=logError($e,__METHOD__ );
        }
        return $ret;
    }
    public function changeAudit($values){
        try {
            $fields = array('audit_control' => $values["audit_control"]);
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            $ret=logError($e,__METHOD__ );
        }
        return $ret;
    }
    public function changeMedicalNotes($values){
        try {
            $fields = array('medical_notes' => $values["medical_notes"]);
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            $ret=logError($e,__METHOD__ );
        }
        return $ret;
    }
    public function lock($values){
        try {
            $fields = array('id_user_lock' => $values["id_user_active"],'lock_date'=>$this->now);
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            $ret=logError($e,__METHOD__ );
        }
        return $ret;
    }
    public function unlock($values){
        try {
            $fields = array('id_user_lock' => null,'lock_date'=>null);
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            $ret=logError($e,__METHOD__ );
        }
        return $ret;
    }

	private function getCalcLine($value,$from,$chars){
	    $original=$value;
	    $value=substr($value,$from,$chars);
		$to=strripos($value," ");
		$value=substr($value,0,$to);
		$rest=substr($original,$to);
		if ($to===false or $to===0 or strlen($original)<$chars){
			$value=$original;
			$rest="";
		}
		return array("value"=>$value,"len"=>strlen($value),"rest"=>$rest);
	}
	private function drawField($param){
	    if(!isset($param["color"])){$param["color"]="black";}
	    $line="<div style='position:absolute;width:100%;color:".$param["color"].";left:".$param["left"].";top:".$param["top"].";font-size:".$param["fontsize"].";font-family:".$param["fontfamily"].";'>".$param["text"]."</div>";
		return $line;
	}
}
