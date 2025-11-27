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
            $this->offlineDocuments();
            $this->view="vw_folders";

            if($values["where"]!=""){$values["where"].=" AND ";}
            $values["where"].=("(([private]=0) OR ([private]=1 AND id IN (SELECT id_folder FROM ".MOD_FOLDERS."_rel_folders_groups WHERE id_group IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"]."))))");
            $values["order"]="fum DESC";
            $values["records"]=$this->get($values);
            $ops=array();
            $TYPE_CONTROL_POINTS=$this->createModel(MOD_FOLDERS,"Type_control_points","Type_control_points");
            $records=$TYPE_CONTROL_POINTS->get(array("order"=>"id ASC","pagesize"=>-1));
            foreach($records["data"] as $record){
               array_push($ops,array("style"=>"padding:0px, margin:0px;","name"=>secureField($record,"description"),"class"=>"btn-folder-change-status","datax"=>"data-module='mod_folders' data-status='".secureField($record,"id")."' data-id=|ID|"));
            };
            $ddChangeStatus=getDropdown(array("class"=>"btn-primary btn-title-change-status-|ID|","name"=>"|DESCRIPTION|"),$ops);
            $i=0;
            foreach($values["records"]["data"] as $item) {
                if($item["offline"]=="") {
                   $values["records"]["data"][$i]["audit"]="<button title='Auditoría' data-id='".$item["id"]."' class='p-0 pl-1 btn btn-sm btn-primary btn-folder-audit'><i class='material-icons' style='color:grey;'>folder_open</i></button>";
                } else {
                   $values["records"]["data"][$i]["audit"]="<span class='badge badge-danger'>".$values["records"]["data"][$i]["type_control_point"]."</span>";
                }
                $i+=1;
            }
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>array(
                    "conditions"=>array(
                           array("field"=>"id_user","operator"=>"==","value"=>$values["id_user_active"]),
                        )
                    ),
                "offline"=>true,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"fum","format"=>"date"),
                array("field"=>"type_folder","format"=>"text"),
                array("field"=>"description","format"=>"shorten"),
                array("field"=>"type_control_point","html"=>$ddChangeStatus,"format"=>"html#block"),
                array("field"=>"reviews","format"=>"text"),
                //array("field"=>"groups","format"=>"private"),
                array("field"=>"audit","forcedlabel"=>"","format"=>""),
                array("field"=>"priority","forcedlabel"=>"","true"=>"<span class='material-icons' style='color:red;'>bolt</span>","false"=>"","format"=>"conditional#bool"),
                //array("field"=>"files","format"=>"number"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description","keywords")),
                array("name"=>"browser_id_type_control_point", "operator"=>"=","fields"=>array("id_type_control_point")),
                array("name"=>"browser_code_type_folder", "operator"=>"=","fields"=>array("code_type_folder")),
            );

            $values["controls"]=array(
                "<span class='badge badge-primary'>Tipo</span>".comboTypeFoldersCode($this,array("where"=>"id!=9","order"=>"description ASC","pagesize"=>-1)),
                "<span class='badge badge-primary'>Punto de control</span>".comboTypeControlPoints($this),
            );

            $values["conditionalBackground"]=array(
                array("field"=>"offline","operator"=>"!=","value"=>"","color"=>"rgb(241,134,91)"),
                array("field"=>"id_type_control_point","value"=>"2","color"=>"rgb(138,180,193)"),
                array("field"=>"id_type_control_point","value"=>"3","color"=>"rgb(176,201,65)"),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $id=$values["id"];
            $profile=getUserProfile($this,$values["id_user_active"]);
            $values["godaction"]=(evalPermissions("GODACTION",$profile["data"][0]["groups"]));
            $this->view="vw_folders";
            $values["interface"]=(MOD_FOLDERS."/folders/abm");
            $values["page"]=1;
            $values["where"]=("id=".$id);
            $registro=$this->get($values);
            $values["records"]=$registro;
            $parameters_id_type_folder=array(
                "model"=>(MOD_FOLDERS."/Type_folders"),
                "table"=>"type_folders",
                "name"=>"id_type_folder",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_folder"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_group=array(
                "model"=>(MOD_BACKEND."/Groups"),
                "table"=>"groups",
                "name"=>"id_group",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_FOLDERS."/Rel_folders_groups"),"table"=>"rel_folders_groups","id_field"=>"id_folder","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );
            $values["controls"]=array(
                "id_type_folder"=>getCombo($parameters_id_type_folder,$this),
                "id_group"=>getMultiSelect($parameters_id_group,$this),
            );

            $FOLDERS_LOG=$this->createModel(MOD_FOLDERS,"Folders_log","Folders_log");
            $FOLDERS_LOG->view="vw_folders_log";
            $folders_log=$FOLDERS_LOG->get(array("order"=>"created DESC","where"=>"id_type_control_point IN (3,4) AND id_folder=".$values["id"],"pagesize"=>-1));
            $values["folders_log"]=$folders_log["data"];
            $opts=array("module"=>MOD_FOLDERS,"model"=>"Folder_items","view"=>"vw_folder_items","where"=>"id_folder=".$values["id"],"order"=>"priority ASC");
            $values["attached_files"] = parent::getAttachments($values,$opts);
            $opts=array("module"=>MOD_BACKEND,"model"=>"Messages_attached","view"=>"vw_messages_attached","where"=>"table_rel='".MOD_FOLDERS."_folders' AND id_rel=".$values["id"]);
            $values["attached_messages"] = parent::getMessages($values,$opts);
            $values["estadoActual"]="";
            if ((int)$values["id"]!=0) {
                $FOLDERS_GROUPS=$this->createModel(MOD_FOLDERS,"Rel_folders_groups","Rel_folders_groups");
                $FOLDERS_GROUPS->view="vw_rel_folders_groups";
                switch((int)$registro["data"][0]["id_type_control_point"]){
                   case 2://a revisar
                      $values["estadoActual"]="Debe ser revisado por alguno de estos usuarios: ";
                      $values["folders_groups"]=$FOLDERS_GROUPS->get(array("where"=>"(code LIKE 'REVISORES%') AND id_folder=".$values["id"],"pagesize"=>-1));
                       $sql="SELECT u.username FROM mod_backend_users as u WHERE u.id NOT IN (SELECT l.id_user FROM mod_folders_vw_folders_log as l WHERE id_folder=" . $id . " AND id_type_control_point=3) AND u.id IN (";
                       $sql.="SELECT r.id_user FROM mod_backend_rel_users_groups as r WHERE r.id_group IN (";
                       $sql.=" SELECT fg.id_group FROM mod_folders_rel_folders_groups as fg WHERE fg.id_folder=".$id." AND fg.id_group IN (";
                       $sql.="  SELECT g.id FROM dbIntranet.DBO.mod_backend_groups AS g where code like 'REVISOR%')))";
                       $values["users_auth"] = $this->getRecordsAdHoc($sql);
                        break;
                   //case 3: //para publicar 
                   //   $values["estadoActual"]="Debe ser publicado por: ";
                   //   $values["folders_groups"]=$FOLDERS_GROUPS->get(array("where"=>"(code LIKE 'PUBLICADORES%') AND id_folder=".$values["id"],"pagesize"=>-1));
                   //   break;
                }
            }
            
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
			if(!isset($values["priority"])){$values["priority"]=0;}
            $id=(int)$values["id"];
            if ($values["min_reviews"]==""){$values["min_reviews"]=0;};
            $min_reviews=(int)$values["min_reviews"];
            $type_folder=(int)secureEmptyNull($values,"id_type_folder");
               switch($type_folder) {
                  case 9:
                      if ($min_reviews<2){$min_reviews="2";}
                      break;
               }
            $fields=null;
            if($id==0){
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
                    'keywords' => $values["keywords"],
                    'min_reviews' => $min_reviews,
                    'actual_reviews' => 0,
                    'direct_link'=>null,
                    'freezed'=>null,
                    'json_tags'=>null,
                    'id_type_folder' => $type_folder,
                    'id_type_control_point' => 1,
                    'priority' => $values["priority"],
                );
            } else {
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'date_validity' => secureEmptyNull($values,"date_validity"),
                    'keywords' => $values["keywords"],
                    'min_reviews' => $min_reviews,
                    'id_type_folder' => $type_folder,
                    'priority' => $values["priority"],
                );
                if (secureEmptyNull($values,"id_type_control_point")==null) {$fields["id_type_control_point"]=1;}
            }
            $saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){
               $id=$saved["data"]["id"];
               $params_groups=array(
                    "module"=>MOD_FOLDERS,
                    "model"=>"Rel_folders_groups",
                    "table"=>"rel_folders_groups",
                    "key_field"=>"id_folder",
                    "key_value"=>$id,
                    "rel_field"=>"id_group",
                    "rel_values"=>(isset($values["id_group"]) ? $values["id_group"] :array())
               );
               parent::saveRelations($params_groups);
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
               $opts=array("module"=>MOD_FOLDERS,"model"=>"Folder_items","new"=>"new-folder-items","inner"=>$inner);
               parent::saveAttachments($values,$saved["data"]["id"],$opts);
               //Post procesamiento de grabaciones adicionales de grupos de seguridad a cada carpeta
               switch($type_folder) {
                  case 9:
				      $this->execAdHoc("DELETE ".MOD_FOLDERS."_Rel_folders_groups WHERE id_folder=".$id." AND id_group IN (1011,1012)");
				      $this->execAdHoc("INSERT INTO ".MOD_FOLDERS."_Rel_folders_groups (id_folder,id_group) VALUES (".$id.",1011)");
				      $this->execAdHoc("INSERT INTO ".MOD_FOLDERS."_Rel_folders_groups (id_folder,id_group) VALUES (".$id.",1012)");
                      break;
               }
            }
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function offline($values){
        try {
            $data=array("id"=>$this->setRecord(array('offline' => $this->now),$values["id"]));
            
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_offline'),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data
                );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function changeStatus($values){
        try {
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $folder=$this->get($values);
            if ($folder["data"][0]["offline"]!=""){throw new Exception(lang("error_5214"),5214);}
            $actual_reviews=(int)$folder["data"][0]["actual_reviews"];
            $min_reviews=(int)$folder["data"][0]["min_reviews"];

            $id_type_control_point=(int)$values["id_type_control_point"];
            $user=getUserProfile($this,$values["id_user_active"]);
            if (!isset($user["data"][0])) {throw new Exception(lang("error_5204"),5204);}

            $bOk=false;
            $fields = array('id_type_control_point' => $values["id_type_control_point"]);
            $err_msg=lang("error_5205");
            $err_i=5205;

            $FOLDERS_GROUPS=$this->createModel(MOD_FOLDERS,"Rel_folders_groups","Rel_folders_groups");
            $FOLDERS_GROUPS->view="vw_rel_folders_groups";
            switch($id_type_control_point) {
               case 1:
                  $folders_groups=$FOLDERS_GROUPS->get(array("where"=>"(code LIKE 'EDITORES%') AND id_folder=".$values["id"],"pagesize"=>-1));
				  $folders_groups["data"][]=array("code"=>"FULLSYSTEM");
                  $bOk=(evalActionPermissions($folders_groups["data"],$user["data"][0]["groups"]));
                  $fields["actual_reviews"]=0;
		          $this->execAdHoc("DELETE ".MOD_FOLDERS."_folders_log WHERE id_folder=".$id);
                  break;
               case 2:
                  $folders_groups=$FOLDERS_GROUPS->get(array("where"=>"(code LIKE 'EDITORES%') AND id_folder=".$values["id"],"pagesize"=>-1));
				  $folders_groups["data"][]=array("code"=>"FULLSYSTEM");
                  $bOk=(evalActionPermissions($folders_groups["data"],$user["data"][0]["groups"]));
                  $fields["actual_reviews"]=0;
                  break;
               case 3:
                  $folders_groups=$FOLDERS_GROUPS->get(array("where"=>"(code LIKE 'REVISORES%') AND id_folder=".$values["id"],"pagesize"=>-1));
				  $folders_groups["data"][]=array("code"=>"FULLSYSTEM");
                  $bOk=(evalActionPermissions($folders_groups["data"],$user["data"][0]["groups"]));
                  $fields["actual_reviews"]=($actual_reviews+1);
                  if ($fields["actual_reviews"]>$min_reviews){$fields["actual_reviews"]=$min_reviews;}
                  break;
               case 4:
                  $folders_groups=$FOLDERS_GROUPS->get(array("where"=>"(code LIKE '%FULLSYS%' or code LIKE 'PUBLICADORES%') AND id_folder=".$values["id"],"pagesize"=>-1));
				  $folders_groups["data"][]=array("code"=>"FULLSYSTEM");
                  $bOk=($actual_reviews>=$min_reviews);
				  if ($bOk){$bOk=(evalActionPermissions($folders_groups["data"],$user["data"][0]["groups"]));}
				  $err_msg=lang("error_5206");
                  $err_i=5206;
                  break;
            }
            if(!$bOk){throw new Exception($err_msg,$err_i);}
            $ret=parent::save($values,$fields);
            if($ret["status"]!="OK"){throw new Exception($ret["message"],(int)$ret["code"]);}
            logFolders($this,$values,lang('msg_change_status'));
            
            /*Notify published status to notification list*/
            $this->notifyEmail($values["id"],$id_type_control_point);
        }
        catch (Exception $e){
            $ret=logError($e,__METHOD__ );
        }
        return $ret;
    }
    public function folderDetails($values){
        try {
            $FOLDER_ITEMS=$this->createModel(MOD_FOLDERS,"Folder_items","Folder_items");
            $records=$FOLDER_ITEMS->get(array("order"=>"description ASC","where"=>"id_folder=".$values["id"],"pagesize"=>-1));
            /*Traer todos los usuarios y el estado de access_log
            * para cada archivo del scoope del folder*/
            $i=0;
            foreach ($records["data"] as $item){
                $sql="SELECT u.*, ";
                $sql.="(SELECT max(created) FROM ".MOD_FOLDERS."_folder_items_log WHERE id_user=u.id AND tag_processed='Visto' AND id_folder_item=".$item["id"].") as viewed ";
                $sql.=" FROM mod_backend_users as u ";
                $sql.=" WHERE u.id in (SELECT id_user FROM ".MOD_BACKEND."_rel_users_groups WHERE id_group IN (SELECT id_group FROM ".MOD_FOLDERS."_rel_folders_groups WHERE id_folder=".$item["id_folder"]."))";
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
            /*---------------------------------*/
            /*Retrieve folder's security groups*/
            /*---------------------------------*/
            $values["where"]=("id=".$id);
			$this->view="vw_folders";
            $folder=$this->get($values);
			$private=(int)$folder["data"][0]["private"];
			$id_type_folder=(int)$folder["data"][0]["id_type_folder"];
			$priority=(int)$folder["data"][0]["priority"];

            $GROUPS=$this->createModel(MOD_BACKEND,"Groups","Groups");
            $groups=$GROUPS->get(array("where"=>"id IN (SELECT id_group FROM ".MOD_FOLDERS."_rel_folders_groups WHERE id_folder=".$id.")"));
            $email_revisores="";
            $email_publicadores="";
			/*Notify automated list based on AD groups!*/
            $email_publicado="todos@credipaz.com";//NOTIFY_PUBLISHED_FOLDER;
			switch($id_type_folder) {
			   case 6: //type_folder Documentación interna
	              $email_publicado="";//NOTIFY_PUBLISHED_FOLDER;
			      break; 
			   case 8: //type_folder Comite de seguridad
	              $email_publicado="comitedeseguridad@credipaz.com";//NOTIFY_PUBLISHED_FOLDER;
			      break; 
			}
            foreach($groups["data"] as $group) {
                if(strpos($group["code"],"REVISORES")!==false) {$email_revisores.=$group["email"].", ";}
                if(strpos($group["code"],"PUBLICADORES")!==false) {$email_publicadores.=$group["email"].", ";}
            }
            if ($email_revisores!=""){$email_revisores=substr_replace($email_revisores,"",-2);}
            if ($email_publicadores!=""){$email_publicadores=substr_replace($email_publicadores,"",-2);}
            /*---------------------------------*/

            $bSkip=false;
            $data=[];
            $params=array("from"=>"intranet@mediya.com.ar","alias_from"=>"","email"=>"","subject"=>"","body"=>"");

            switch((int)$id_type_control_point) {
               case 2: // a revisar
                  $params["alias_from"]=lang('msg_internal_alerts');
                  /*Send to emails related to gruops assigned to folder*/
                  $params["email"]=$email_revisores;
                  $params["subject"]=lang('msg_forrevision_alert');
                  $params["body"]=$this->load->view(MOD_EMAIL.'/templates/forRevisionEmail',$data, true);
                  break;
               case 3: // a publicar
                  $params["alias_from"]=lang('msg_internal_alerts');
                  /*Send to emails related to gruops assigned to folder*/
                  $params["email"]=$email_publicadores;
                  $params["subject"]=lang('msg_forpublish_alert');
                  $params["body"]=$this->load->view(MOD_EMAIL.'/templates/forPublishEmail',$data, true);
                  break;
               case 4: // publicado
                  $params["alias_from"]=lang('msg_internal_alerts');
				  /*Original*/
	              //$params["subject"]=lang('msg_publish_alert');
				  //$params["email"]=$email_publicado;
				  //$params["body"]=$this->load->view(MOD_EMAIL.'/templates/notifyEmail',$data, true);
				  /*Nuevo a testear*/
				  switch($priority){
				     case 1:
						$icons="🧧";
	                    $params["subject"]=($icons." ".lang('msg_publish_alert_priority'));
						$params["subject"]=("=?UTF-8?B?".base64_encode($params["subject"])."?=");
	                    //$params["email"]="daniel@gruponeodata.com, nyoan@credipaz.com, czuniga@credipaz.com, jmedina@credipaz.com";
	                    $params["email"]=$email_publicado;
	                    $params["body"]=$this->load->view(MOD_EMAIL.'/templates/notifyEmailPriority',$data, true);
						$params["priority"]=2;
					    break;
					 default:
	                    $params["subject"]=lang('msg_publish_alert');
					    $params["email"]=$email_publicado;
						$params["body"]=$this->load->view(MOD_EMAIL.'/templates/notifyEmail',$data, true);
					    break;
				  }
                  break;
               default:
                  $bSkip=true;
                  break; 
            }
			if($private==1){$skip=true;}
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

    public function offlineDocuments(){
       try {
            $FOLDER_ITEMS=$this->createModel(MOD_FOLDERS,"Folder_items","Folder_items");
            $values["where"]="id_type_control_point IN (2,3) AND fum<DATEADD(day,-30,getdate())";
            $values["pagesize"]=-1;
            $folders=$this->get($values);
            foreach($folders["data"] as $item) {$this->offline(array("id"=>$item["id"]));}

            $values["where"]="id_type_control_point IN (1) AND fum<DATEADD(day,-30,getdate())";
            $values["pagesize"]=-1;
            $folders=$this->get($values);
            foreach($folders["data"] as $item) {
                $FOLDER_ITEMS->deleteByWhere(array("id_folder"=>$item["id"]));
                $this->delete(array("id"=>$item["id"]));
            }
            return true;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
