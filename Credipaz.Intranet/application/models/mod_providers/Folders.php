<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Folders extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            //$ops=array();
            //$TYPE_CONTROL_POINTS=$this->createModel(MOD_PROVIDERS,"Type_control_points","Type_control_points");
            //$records=$TYPE_CONTROL_POINTS->get(array("where"=>"id!=3","order"=>"id ASC","pagesize"=>-1));
            //foreach($records["data"] as $record){array_push($ops,array("name"=>secureField($record,"description"),"class"=>"btn-folder-change-status","datax"=>"data-module='MOD_PROVIDERS' data-status='".secureField($record,"id")."' data-id=|ID|"));};
            //$ddChangeStatus=getDropdown(array("class"=>"btn-primary btn-title-change-status-|ID|","name"=>"|DESCRIPTION|"),$ops);
			//$values["id_user_active"]=194;

            $this->view="vw_folders";
            if($values["where"]!=""){$values["where"].=" AND ";}
            $values["where"].="id_type_control_point in (1,2,9) AND offline IS null";
            $values["order"]="created DESC";
            $values["records"]=$this->get($values);

            $i=0;
            foreach($values["records"]["data"] as $item) {
                $values["records"]["data"][$i]["audit"]="<button data-id='".$item["id"]."' class='btn btn-sm btn-raised btn-primary btn-folder-audit'><i class='material-icons'>folder_shared</i></button>";
                $i+=1;
            }

            $values["buttons"]=array(
                "new"=>true,
                "edit"=>array(
                    "conditions"=>array(
                           array("field"=>"id_type_control_point","operator"=>"!=","value"=>"9"),
                        )
                    ),
                "delete"=>array(
                    "conditions"=>array(
                           array("field"=>"id_type_control_point","operator"=>"==","value"=>"1"),
                        )
                    ),
                "offline"=>false,
            );

            $values["columns"]=array(
                array("field"=>"id","format"=>"number"),
                array("field"=>"created","format"=>"date"),
                array("field"=>"provider","format"=>"text"),
                //array("field"=>"type_folder","format"=>"type"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"type_control_point","format"=>"type"),
                array("field"=>"reviews","format"=>"status"),
                array("field"=>"aprovals","format"=>"status"),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("id","code","description","keywords","cuit")),
                array("name"=>"browser_id_type_control_point", "operator"=>"=","fields"=>array("id_type_control_point")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Punto de control</span>".comboTypeControlPointsProviders($this,array("where"=>"id IN (1,2,9) ","order"=>"description ASC","pagesize"=>-1)),
            );
            $values["conditionalBackground"]=array(
                array("field"=>"id_type_control_point","value"=>"9","color"=>"red"),
                array("field"=>"id_type_control_point","value"=>"6","color"=>"darkorange"),
                array("field"=>"id_type_control_point","value"=>"2","color"=>"gold"),
                array("field"=>"id_type_control_point","value"=>"3","color"=>"lightgreen"),
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
            $values["godaction"]=(evalPermissions("GODACTION",$profile["data"][0]["groups"]));

            $this->view="vw_folders";
            $values["interface"]=(MOD_PROVIDERS."/folders/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_id_company=array(
                "model"=>(MOD_PROVIDERS."/Companies"),
                "table"=>"companies",
                "name"=>"id_company",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_company"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_pay_condition=array(
                "model"=>(MOD_PROVIDERS."/Type_pay_conditions"),
                "table"=>"Type_pay_conditions",
                "name"=>"id_type_pay_condition",
                "class"=>"form-control dbase id_type_pay_condition validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_pay_condition"),
                "id_field"=>"id",
                "description_field"=>"description"
            );
            $parameters_id_providers=array(
                "model"=>(MOD_PROVIDERS."/Providers"),
                "table"=>"providers",
                "name"=>"id_provider",
                "class"=>"singleselect form-control dbase id_provider validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_provider"),
                "id_field"=>"id",
                "description_field"=>"social_name",
                "get"=>array("fields"=>"id,(social_name + ' ' + cuit) as social_name","where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_sector=array(
                "model"=>(MOD_PROVIDERS."/Type_sectors"),
                "table"=>"type_sectors",
                "name"=>"id_type_sector",
                "class"=>"multiselect dbase validate",
                "actual"=>array("model"=>(MOD_PROVIDERS."/Rel_folders_type_sectors"),"table"=>"rel_folders_type_sectors","id_field"=>"id_folder","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );

			$status_contable=$values["records"]["data"][0]["type_status_contable"];
			if($status_contable==""){$status_contable="SIN ESPECIFICAR";}

            $values["controls"]=array(
                "id_company"=>getCombo($parameters_id_company,$this),
                "id_type_status_contable"=>"<br/><div class='badge badge-info'>".$status_contable."</div>",
                "id_type_pay_condition"=>getCombo($parameters_id_type_pay_condition,$this),
                "id_provider"=>getSingleSelect($parameters_id_providers,$this),
                "id_type_sector"=>getMultiSelect($parameters_id_type_sector,$this),
            );
            $FOLDERS_LOG=$this->createModel(MOD_PROVIDERS,"Folders_log","Folders_log");
            $FOLDERS_LOG->view="vw_folders_log";
            $folders_log=$FOLDERS_LOG->get(array("order"=>"created DESC","where"=>"id_folder=".$values["id"],"pagesize"=>-1));
            $values["folders_log"]=$folders_log["data"];
            $opts=array("module"=>MOD_PROVIDERS,"model"=>"Folder_items","view"=>"vw_folder_items","where"=>"id_folder=".$values["id"],"order"=>"priority ASC");
            $values["attached_files"] = parent::getAttachments($values,$opts);
            $ops=array();
            $TYPE_CONTROL_POINTS=$this->createModel(MOD_PROVIDERS,"Type_control_points","Type_control_points");
            $records=$TYPE_CONTROL_POINTS->get(array("where"=>"id IN (1,2,6,11)","order"=>"code ASC","pagesize"=>-1));
            //$records=$TYPE_CONTROL_POINTS->get(array("order"=>"id ASC","pagesize"=>-1));
            foreach($records["data"] as $record){
                array_push($ops,array("name"=>secureField($record,"description"),"class"=>"btn-folder-change-status","datax"=>"data-module='MOD_PROVIDERS' data-status='".secureField($record,"id")."' data-id=".$values["id"]));
            };
            $values["ddChangeStatus"]=getButtonRibbon(array("class"=>"btn-primary btn-title-change-status-".$values["id"],"name"=>$values["records"]["data"][0]["type_control_point"]),$ops);

            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
			$goForPay=false;
		    $bNew=false;
            $bSave=false;
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            $fields=null;
            if($id==0){
                $bSave=true;
				$bNew=true;
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'created' => $this->now,
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    'id_user'=>$values["id_user_active"],
                    'id_creator'=>$values["id_user_active"],
                    'date_validity' => secureEmptyNull($values,"date_validity"),
                    'date_pay' => secureEmptyNull($values,"date_pay"),
                    'total_amount'=>(float)$values["total_amount"],
                    'keywords' => $values["keywords"],
                    'direct_link'=>null,
                    'freezed'=>null,
                    'json_tags'=>null,
                    'id_type_folder' => 1,//secureEmptyNull($values,"id_type_folder"),
                    'id_type_control_point' => 2, // A revisar!
                    'id_provider' => secureEmptyNull($values,"id_provider"),
                    'id_company' => secureEmptyNull($values,"id_company"),
                    'id_type_pay_condition' => secureEmptyNull($values,"id_type_pay_condition"),
                    'id_type_status_contable' => null,
                    'min_reviews' => 0,
                    'actual_reviews' => 0,
                    'min_aprovals' => 0,
                    'actual_aprovals' => 0,
                    'id_type_limit_aproval'=>0
                );
            } else {
			    $this->view="vw_folders";
                $actual=$this->get(array("id"=>$id));
                $bSave=((int)$actual["data"][0]["id_type_control_point"]==3);
                $bPayed=((int)$actual["data"][0]["allow_pay"]==1);
				$id_type_status_contable_actual=(int)$actual["data"][0]["id_type_status_contable"];
				$id_type_status_contable=secureEmptyNull($values,"id_type_status_contable");
				if (!$bPayed and ($id_type_status_contable_actual!=$id_type_status_contable)){
					$TYPE_STATUS_CONTABLES=$this->createModel(MOD_PROVIDERS,"Type_status_contables","Type_status_contables");
					$type_status_contable=$TYPE_STATUS_CONTABLES->get(array("where"=>"id=".$id_type_status_contable));
					$goForPay=($type_status_contable["data"][0]["allow_pay"]==1);
				}

                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'date_validity' => secureEmptyNull($values,"date_validity"),
                    'date_pay' => secureEmptyNull($values,"date_pay"),
                    'total_amount'=>(float)$values["total_amount"],
                    'keywords' => $values["keywords"],
                    'id_type_folder' => 1,//secureEmptyNull($values,"id_type_folder"),
                    'id_provider' => secureEmptyNull($values,"id_provider"),
                    'id_company' => secureEmptyNull($values,"id_company"),
                    'id_type_pay_condition' => secureEmptyNull($values,"id_type_pay_condition"),
                    'id_type_status_contable' => $id_type_status_contable,
                );
                if (secureEmptyNull($values,"id_type_control_point")==null) {$fields["id_type_control_point"]=1;}
            }
            if($bSave){
               $saved=parent::save($values,$fields);
            } else {
               $saved["status"]="OK";
               $saved["data"]["id"]=$id;
            }
			$id=$saved["data"]["id"];
            if($saved["status"]=="OK"){
               $params_sectors=array(
                    "module"=>MOD_PROVIDERS,
                    "model"=>"Rel_folders_type_sectors",
                    "table"=>"Rel_folders_type_sectors",
                    "key_field"=>"id_folder",
                    "key_value"=>$id,
                    "rel_field"=>"id_type_sector",
                    "rel_values"=>(isset($values["id_type_sector"]) ? $values["id_type_sector"] :array())
               );
               parent::saveRelations($params_sectors);
               $inner=array(
                    "code"=>null,
                    "description"=>null,
                    "created"=>$this->now,
                    "verified"=>$this->now,
                    "fum"=>$this->now,
                    "id_folder"=>$id,
                    "id_user"=>$values["id_user_active"],
                    "keywords"=>"=",
                    "mime"=>null,
                    "data"=>null,
                    "basename"=>null,
                    "id_type_folder_item"=>"=",
                    "priority"=>null,
                );
               $opts=array("module"=>MOD_PROVIDERS,"model"=>"Folder_items","new"=>"new-folder-items","inner"=>$inner);
               parent::saveAttachments($values,$id,$opts);
               $this->saveSectors($fields["id_provider"],$values);
               $this->saveGroups($id,$params_sectors,$values["total_amount"]);
            }
			if ($bNew){
			   $this->notifyEmail($id,2);
			} else {
			   // Si se dan las condiciones de pasaje a estado contable, intentar poner como pagado!
			   if ($goForPay) {$this->changeStatus(array("id"=>$id,"id_type_control_point"=>9,"id_user_active"=>$values["id_user_active"]));}
			}
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function changeStatus($values){
        try {
            //$values["id_user_active"]=46897;
            if (!isset($values["innerAproval"])){$values["innerAproval"]=false;}
			$values["page"]=1;
            $values["fields"]="*";
            $values["where"]=("id=".$values["id"]);
            $this->view="vw_folders";
            $folder=$this->get($values);
            $allow_pay=(int)$folder["data"][0]["allow_pay"];
            $min_reviews=(int)$folder["data"][0]["min_reviews"];
            $actual_reviews=(int)$folder["data"][0]["actual_reviews"];
            $min_aprovals=(int)$folder["data"][0]["min_aprovals"];
            $actual_aprovals=(int)$folder["data"][0]["actual_aprovals"];
            $id_type_control_point_previo=(int)$folder["data"][0]["id_type_control_point"];

            $id_type_control_point=(int)$values["id_type_control_point"];
            $user=getUserProfile($this,$values["id_user_active"]);
            if (!isset($user["data"][0])) {throw new Exception(lang("error_5204"),5204);}

            $bOk=false;
            $fields = array('id_type_control_point' => $id_type_control_point);
            $err_msg=lang("error_5205");
            $err_i=5205;

            $GROUPS=$this->createModel(MOD_BACKEND,"Groups","Groups");
            $FOLDER_ITEMS=$this->createModel(MOD_PROVIDERS,"Folder_items","Folder_items");
            $FOLDERS_LOG=$this->createModel(MOD_PROVIDERS,"Folders_log","Folders_log");
            $FOLDERS_GROUPS=$this->createModel(MOD_PROVIDERS,"Rel_folders_groups","Rel_folders_groups");
            $FOLDERS_GROUPS->view="vw_rel_folders_groups";

            switch($id_type_control_point) {
               case 1:
                  $groups=$GROUPS->get(array("where"=>"(code LIKE '%APROBACIONES%' OR code LIKE 'FULLSYSTEM%')","pagesize"=>-1));
                  $bOk=(evalActionPermissions($groups["data"],$user["data"][0]["groups"]));
                  if ($bOk && $id_type_control_point_previo!=2) { // Debe estar en estado 2 (A revisar!)
                     $bOk=false;
                     $err_msg=lang('error_5210');
                     $err_i=5210;
                  }
                  $fields["actual_reviews"]=0;
                  break;
               case 2: // a revisar
                  $groups=$GROUPS->get(array("where"=>"(code LIKE 'PROV VERIFICA PROVEEDOR%' OR code LIKE '%APROBACIONES%' OR code LIKE 'FULLSYSTEM%')","pagesize"=>-1));
                  $bOk=(evalActionPermissions($groups["data"],$user["data"][0]["groups"]));
                  if ($bOk && $id_type_control_point_previo!=1) { // Debe estar en estado 1 (Borrador!)
                     $bOk=false;
                     $err_msg=lang('error_5210');
                     $err_i=5210;
                  }
                  $fields["actual_reviews"]=0;
                  break;
               case 11:  // A verificar
                  $groups=$GROUPS->get(array("where"=>"(code LIKE '%APROBACIONES%' OR code LIKE 'FULLSYSTEM%')","pagesize"=>-1));
                  $bOk=(evalActionPermissions($groups["data"],$user["data"][0]["groups"]));
                  if ($bOk && $id_type_control_point_previo!=1) { // Debe estar en estado 1 (Borrador!)
                     $bOk=false;
                     $err_msg=lang('error_5210');
                     $err_i=5210;
                  }
                  $fields["actual_reviews"]=0;
                  break;
               case 3: // Aprobado
                  $bOk=(bool)$values["innerAproval"];
                  if (!$bOk){
                     $err_msg=lang('error_5208');
                     $err_i=5208;
                  }
                  break;
               case 7: // Revisado por los gerentes
                  $bOk=true;
                  $folder_log=$FOLDERS_LOG->get(array("where"=>"id_user=".$values["id_user_active"]." AND id_folder=".$values["id"]." AND id_type_control_point=".$id_type_control_point,"pagesize"=>-1));
                  if ((int)$folder_log["totalrecords"]!=0){
                     $bOk=false;
                     $err_msg=lang('error_5209');
                     $err_i=5209;
                  } else {
                     $bOk=true;
                  }
                  if ($bOk) {
                      $folders_groups=$FOLDERS_GROUPS->get(array("where"=>"(code LIKE 'PROV REVISORES%' OR code LIKE 'GERENTE%' OR code LIKE 'FULLSYSTEM%') AND id_folder=".$values["id"],"pagesize"=>-1));
                      $bOk=(evalActionPermissions($folders_groups["data"],$user["data"][0]["groups"]));
                      $fields["actual_reviews"]=($actual_reviews+1);
                      if ((int)$fields["actual_reviews"]>$min_reviews){$fields["actual_reviews"]=$min_reviews;}
                  }
                  break;
               case 8: // Aprobacion de director
                  $bOk=true;
                  $folder_log=$FOLDERS_LOG->get(array("where"=>"id_user=".$values["id_user_active"]." AND id_folder=".$values["id"]." AND id_type_control_point=".$id_type_control_point,"pagesize"=>-1));
                  if ((int)$folder_log["totalrecords"]!=0){
                     $bOk=false;
                     $err_msg=lang('error_5209');
                     $err_i=5209;
                  } else {
                     $bOk=true;
                  }
                  if ($bOk) {
                      $folders_groups=$FOLDERS_GROUPS->get(array("where"=>"(code LIKE 'DIRECTOR%') AND id_folder=".$values["id"],"pagesize"=>-1));
                      $bOk=(evalActionPermissions($folders_groups["data"],$user["data"][0]["groups"]));
                      $fields["actual_reviews"]=$actual_reviews;
                      $fields["actual_aprovals"]=($actual_aprovals+1);
                      if ((int)$fields["actual_aprovals"]>$min_aprovals){$fields["actual_aprovals"]=$min_aprovals;}
                  }
                  break;
               case 9: // Pagado
                  $groups=$GROUPS->get(array("where"=>"(code LIKE '%TESORERIA%' OR code LIKE 'FULLSYSTEM%')","pagesize"=>-1));
                  $bOk=(evalActionPermissions($groups["data"],$user["data"][0]["groups"]));
                  $item=$FOLDER_ITEMS->get(array("where"=>"id_folder=".$values["id"]." AND id_type_folder_item=6","pagesize"=>-1));
                  if ((int)$item["totalrecords"]==0){
                     $bOk=false;
                     $err_msg=lang('error_5213');
                     $err_i=5213;
                  } 
                  if ($bOk && $id_type_control_point_previo!=3) { // Debe estar en estado 3 (Aprobado!)
                     $bOk=false;
                     $err_msg=lang('error_5210');
                     $err_i=5210;
                  }
                  if ($bOk && $allow_pay!=1) { // Debe estar en un estado contable que permita pasar a pagado!
                     $bOk=false;
                     $err_msg=lang('error_5217');
                     $err_i=5217;
                  }
                  break;
               case 5://finalizado
                  $folders_groups=$FOLDERS_GROUPS->get(array("where"=>"(code LIKE '%TESORERIA%' OR code LIKE 'FULLSYSTEM%') AND id_folder=".$values["id"],"pagesize"=>-1));
                  $bOk=(evalActionPermissions($folders_groups["data"],$user["data"][0]["groups"]));
                  break;
               case 6: //rechazado
                  $folders_groups=$FOLDERS_GROUPS->get(array("where"=>"(code LIKE 'GERENTE%' OR code LIKE 'DIRECTOR%' OR code LIKE 'FULLSYSTEM%') AND id_folder=".$values["id"],"pagesize"=>-1));
                  $bOk=(evalActionPermissions($folders_groups["data"],$user["data"][0]["groups"]));
                  break;
            }
            if(!$bOk){throw new Exception($err_msg,$err_i);}

            $ret=$this->updateByWhere($fields,"id='".$values["id"]."'");
            logProviders($this,$values,lang('msg_change_status'));
            
            /*Notify published status to notification list*/
            $this->notifyEmail($values["id"],$id_type_control_point);
            /*Evaluates if Folders is ready for complete Aproval!*/
            $folder=$this->get($values);
            $min_reviews=(int)$folder["data"][0]["min_reviews"];
            $actual_reviews=(int)$folder["data"][0]["actual_reviews"];
            $min_aprovals=(int)$folder["data"][0]["min_aprovals"];
            $actual_aprovals=(int)$folder["data"][0]["actual_aprovals"];
			switch((int)$id_type_control_point) {
			   case 5:
			   case 6:
			   case 9:
			      break;
			   default:
				  $bAprove=false;
				  if ($min_aprovals==0) {
				     $bAprove=($actual_reviews>=$min_reviews);
				  } else {
				     $bAprove=(($actual_reviews>=$min_reviews) and ($actual_aprovals>=$min_aprovals));
				  }
				  if ($bAprove and (bool)$values["innerAproval"]==false) {
					 $values["id_type_control_point"]=3;
					 $values["innerAproval"]=true;
					 $ret=$this->changeStatus($values);
				  }
			}
        }
        catch (Exception $e){
            $ret=logError($e,__METHOD__ );
        }
        return $ret;
    }
    public function folderDetails($values){
        try {
            $FOLDER_ITEMS=$this->createModel(MOD_PROVIDERS,"Folder_items","Folder_items");
            $records=$FOLDER_ITEMS->get(array("order"=>"description ASC","where"=>"id_folder=".$values["id"],"pagesize"=>-1));
            /*Traer todos los usuarios y el estado de access_log
            * para cada archivo del scoope del folder*/
            $i=0;
            foreach ($records["data"] as $item){
                $sql="SELECT u.*, ";
                $sql.="(SELECT max(created) FROM ".MOD_PROVIDERS."_folder_items_log WHERE id_user=u.id AND tag_processed='Visto' AND id_folder_item=".$item["id"].") as viewed ";
                $sql.=" FROM mod_backend_users as u ";
                $sql.=" WHERE u.id in (SELECT id_user FROM ".MOD_BACKEND."_rel_users_groups WHERE id_group IN (SELECT id_group FROM ".MOD_PROVIDERS."_rel_folders_groups WHERE id_folder=".$item["id_folder"]."))";
                $users=$this->dbLayerExecuteWS("records",$sql,"",null);
                $records["data"][$i]["users"]=$users;
                $i+=1;
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$records["data"]
                );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function notifyEmail($id,$id_type_control_point){
        try {
            $data=[];
            /*---------------------------------*/
            /*Retrieve folder's security groups*/
            /*---------------------------------*/
            $values["where"]=("id=".$id);
			$this->view="vw_folders";
            $folder=$this->get($values);
            $actual_reviews=(int)$folder["data"][0]["actual_reviews"];
            $min_reviews=(int)$folder["data"][0]["min_reviews"];
			$provider=$folder["data"][0]["provider"];
			$description=$folder["data"][0]["code_company"];
			$type_folder=$folder["data"][0]["type_folder"];
			$email_provider=$folder["data"][0]["email"];
            $data["id"]=$folder["data"][0]["id"];
            $data["provider"]=$provider;
            $data["description"]=$description;
            $data["type_folder"]=$type_folder;

            $FOLDER_ITEMS=$this->createModel(MOD_PROVIDERS,"Folder_items","Folder_items");
            $folder_items=$FOLDER_ITEMS->get(array("where"=>"id_folder=".$id." AND id_type_folder_item IN (5,6)"));

            $GROUPS=$this->createModel(MOD_BACKEND,"Groups","Groups");
            $groups=$GROUPS->get(array("where"=>"id IN (SELECT id_group FROM ".MOD_PROVIDERS."_rel_folders_groups WHERE id_folder=".$id.")"));
            $email_tesoreria="";
            $email_aprobaciones="";
            $email_revisores="";
            $email_directores="";
            foreach($groups["data"] as $group) {
                if(strpos($group["code"],"TESORERIA")!==false) {$email_tesoreria.=$group["email"].", ";}
                if(strpos($group["code"],"APROBACIONES")!==false) {$email_aprobaciones.=$group["email"].", ";}
                if(strpos($group["code"],"REVISORES")!==false) {$email_revisores.=$group["email"].", ";}
                if(strpos($group["code"],"DIRECTOR")!==false) {$email_directores.=$group["email"].", ";}
            }
            if ($email_tesoreria!=""){$email_tesoreria=substr_replace($email_tesoreria,"",-2);}
            if ($email_aprobaciones!=""){$email_aprobaciones=substr_replace($email_aprobaciones,"",-2);}
            if ($email_revisores!=""){$email_revisores=substr_replace($email_revisores,"",-2);}
            if ($email_directores!=""){$email_directores=substr_replace($email_directores,"",-2);}

            if ($email_tesoreria!=""){
                if($email_aprobaciones!=""){$email_aprobaciones.=", ";}
                $email_aprobaciones.=$email_tesoreria;
            }
            /*---------------------------------*/

            $bSkip=false;
            $params=array("from"=>"intranet@mediya.com.ar","alias_from"=>"","email"=>"","subject"=>"","body"=>"");
            switch((int)$id_type_control_point) {
               case 2: // a revisar
                  $params["alias_from"]=lang('msg_internal_alerts');
                  /*Send to emails related to gruops assigned to folder*/
                  $params["email"]="administracion@credipaz.com,".$email_revisores;
                  $params["subject"]=lang('msg_foraproval_alert');
                  $params["body"]=$this->load->view(MOD_EMAIL.'/templates/forAprovalEmail',$data, true);
                  break;
               case 7: // revisado
                  $params["alias_from"]=lang('msg_internal_alerts');
                  /*Send to emails related to gruops assigned to folder*/
                  $params["email"]=$email_directores;
                  $params["subject"]=lang('msg_forrevised_alert');
                  $params["body"]=$this->load->view(MOD_EMAIL.'/templates/forRevisedEmail',$data, true);
                  break;
               case 3: // aprobado!
                  if ($min_reviews==0 or $actual_reviews>=$min_reviews) {
                      $params["alias_from"]=lang('msg_internal_alerts');
                      /*Send to emails related to gruops assigned to folder*/
                      $params["email"]=$email_aprobaciones;
                      $params["subject"]=lang('msg_foraproved_alert');
                      $params["body"]=$this->load->view(MOD_EMAIL.'/templates/forAprovedEmail',$data, true);
                  }
                  break;
			   case 9: // pagado!
                  $params["alias_from"]=lang('msg_external_alerts');
                  /*Send to provider email*/
                  $params["email"]=$email_provider;//"daniel@gruponeodata.com"
                  $params["subject"]=lang('msg_forpayed_alert');
                  $params["body"]=$this->load->view(MOD_EMAIL.'/templates/forPayedEmail',$data, true);

				  $params["names"]="";
                  $params["attachs"]="";
		          foreach($folder_items["data"] as $item) {
				     if($params["names"]!=""){$params["names"].="[NAME]";}
				     if($params["attachs"]!=""){$params["attachs"].="[FILE]";}
				     $params["names"].=$item["basename"];
                     $stream=getFileBinSSH($item["data"]);
				     $params["attachs"].=base64_encode($stream);
				  }
				  break;
               default:
                  $bSkip=true;
                  break; 
            }
            if (!$bSkip) {
               $EMAIL=$this->createModel(MOD_EMAIL,"Email","Email");
               return $EMAIL->directEmail($params);
            } else {
               return null;
            }
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    private function saveSectors($id_provider,$values){
        /*Propagación a proveedores de sectores relacionados, de acuerdo a la carga, si l proveedor no tiene relaciones previas*/
        $TYPE_SECTORS=$this->createModel(MOD_PROVIDERS,"Type_sectors","Type_sectors");
        $opts=array("where"=>"id IN (SELECT id_type_sector FROM ".MOD_PROVIDERS."_rel_providers_type_sectors WHERE id_provider=".$id_provider.")","pagesize"=>-1);
        $records=$TYPE_SECTORS->get($opts);
        if ((int)$records["totalrecords"]==0){
            $params_sectors=array(
                "module"=>MOD_PROVIDERS,
                "model"=>"Rel_providers_type_sectors",
                "table"=>"Rel_providers_type_sectors",
                "key_field"=>"id_provider",
                "key_value"=>$id_provider,
                "rel_field"=>"id_type_sector",
                "rel_values"=>(isset($values["id_type_sector"]) ? $values["id_type_sector"] :array())
            );
            parent::saveRelations($params_sectors);
        }
    }
    private function saveGroups($id_folder,$params_sectors,$amount){
        $REL_FOLDERS_TYPE_SECTORS=$this->createModel(MOD_PROVIDERS,"Rel_folders_type_sectors","Rel_folders_type_sectors");
        $sectors=$REL_FOLDERS_TYPE_SECTORS->get(array("where"=>"id_folder=".$id_folder));

        /*Grupos de permisos de acceso de acuerdo al los sectores seleccionados para el expediente*/
        $where_type_sectors=implode(",",$params_sectors["rel_values"]);

        $GROUPS=$this->createModel(MOD_BACKEND,"Groups","Groups");
        $where_type_sectors=implode(",",$params_sectors["rel_values"]);
        $opts=array("where"=>"id IN (SELECT id_group FROM ".MOD_PROVIDERS."_rel_type_sectors_groups WHERE id_type_sector IN (".$where_type_sectors."))","pagesize"=>-1);
        $records=$GROUPS->get($opts);
        $rel_values=array();
        foreach ($records["data"] as $item){$rel_values[]=$item["id"];}

        /*Grupos de permisos de acceso de acuerdo al límite de aprobación por importe*/
        $TYPE_LIMITS_APROVALS=$this->createModel(MOD_PROVIDERS,"Type_limits_aprovals","Type_limits_aprovals");
        $GROUPS=$this->createModel(MOD_BACKEND,"Groups","Groups");
        $limit_aproval=$TYPE_LIMITS_APROVALS->get(array("where"=>"amount_from<".(float)$amount." AND amount_to>".(float)$amount));
        if ((int)$limit_aproval["totalrecords"]!=0) {
            $data=array(
                    "id_type_limit_aproval"=>$limit_aproval["data"][0]["id"],
                    "min_reviews"=>(int)$sectors["totalrecords"],
                    "min_aprovals"=>$limit_aproval["data"][0]["min_aprovals"],
                    "fum"=>$this->now,
            );
            if((int)$limit_aproval["data"][0]["min_aprovals"]>1) {$rel_values[]=1020;} //!Force directores Credipaz!
            parent::save(array("id"=>$id_folder),$data);
        }
        $params_sectors=array(
            "module"=>MOD_PROVIDERS,
            "model"=>"Rel_folders_groups",
            "table"=>"Rel_folders_groups",
            "key_field"=>"id_folder",
            "key_value"=>$id_folder,
            "rel_field"=>"id_group",
            "rel_values"=>$rel_values
        );
        parent::saveRelations($params_sectors);
    }
}
