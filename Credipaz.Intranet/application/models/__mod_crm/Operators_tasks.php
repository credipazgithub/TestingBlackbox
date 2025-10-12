<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Operators_tasks extends MY_Model {
    public $id_grupo_fullsystem = "1";
    public $id_grupo_admin = "1021";

    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            //Evaluate if user logged is in group allowed for assign operator!
            $profile=getUserProfile($this,$values["id_user_active"]);
            $admin=IsInArray($profile["data"][0]["groups"],"id",$this->id_grupo_admin);
            if (!$admin){$admin=IsInArray($profile["data"][0]["groups"],"id",$this->id_grupo_fullsystem);}
            $external=(evalPermissions("EXTERNAL",$profile["data"][0]["groups"]));

            $external=IsInArray($profile["data"][0]["groups"],"id",$this->id_grupo_admin) or IsInArray($profile["data"][0]["groups"],"id",$this->id_grupo_fullsystem);
            $ddAssignTotal="";
            $seeTitle="";
            $editConditions=true;
            if($admin) {
                $ops=array();
                $opsTotal=array();
                $OPERATORS=$this->createModel(MOD_BACKEND,"Users","Users");
                $records=$OPERATORS->get(array("where"=>WHERE_USERS_COMERCIAL,"order"=>"username ASC","pagesize"=>-1));
                array_push($ops,array("name"=>lang('b_no_operator'),"style"=>"color:darkgreen;","class"=>"btn-crm-assign","datax"=>"data-status='-1' data-id=|ID|"));
                array_push($opsTotal,array("name"=>lang('b_no_operator'),"style"=>"color:darkgreen;","class"=>"btn-crm-assign","datax"=>"data-status='-1' data-id=0"));
                foreach($records["data"] as $record){
                    array_push($ops,array("name"=>secureField($record,"username"),"class"=>"btn-crm-assign","datax"=>"data-status='".secureField($record,"id")."' data-id=|ID|"));
                    array_push($opsTotal,array("name"=>secureField($record,"username"),"class"=>"btn-crm-assign","datax"=>"data-status='".secureField($record,"id")."' data-id=0"));
                };
                $ddAssignTotal=getDropdown(array("class"=>"btn-info btn-raised btn-sm","name"=>lang('b_assign_operator_all')),$opsTotal);
                $seeTitle=array("field"=>"operator","format"=>"type");
                $values["controls"]=array(
                    $ddAssignTotal,
                    "<span class='badge badge-primary'>Usuario</span>".comboUsers($this,array("where"=>WHERE_USERS_COMERCIAL,"order"=>"username ASC","pagesize"=>-1)),
                    "<span class='badge badge-primary'>Tipo de contacto</span>".comboTypeContactChannels($this,array("where"=>"id IN (3,4,5,6,8,9)")),
                    "<span class='badge badge-primary'>Tipo de cierre</span>".comboTypeTasksClose($this)
                );
            } else {
                if($values["where"]!="") {$values["where"].=" AND ";}
                $values["where"].=(" id_operator=".$values["id_user_active"]. " AND id_type_task_close IS null");
                $seeTitle=array("field"=>"subject","format"=>"text");
                $editConditions=array(
                    "conditions"=>array(
                           array("field"=>"id_type_task_close","operator"=>"=","value"=>"","alternate"=>""),
                        )
                     );
            }
            $values["columns"]=array(
                array("field"=>"elapsed","format"=>"code"),
                array("field"=>"subject","format"=>"shorten"),
                $seeTitle,
                array("field"=>"type_contact_channel","format"=>"status"),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("username","operator","subject")),
                array("name"=>"browser_id_user", "operator"=>"=","fields"=>array("id_operator")),
                array("name"=>"browser_id_type_contact_channel", "operator"=>"=","fields"=>array("id_type_contact_channel")),
                array("name"=>"browser_id_type_task_close", "operator"=>"=","fields"=>array("id_type_task_close")),
            );

            $this->view="vw_operators_tasks";
            $values["buttons"]=array(
                "check"=>($admin or $external),
                "new"=>true,
                "edit"=>$editConditions,
                "delete"=>array(
                    "conditions"=>array(
                           array("field"=>"id_operator","operator"=>"=","value"=>"","alternate"=>""),
                           array("field"=>"processed","operator"=>"==","value"=>""),
                        )
                    ),
                "offline"=>false,
            );
            if($values["where"]!=""){$values["where"].=" AND ";}
            $values["where"].=" id_type_task_close IS null";
            $values["order"]="id_operator ASC, elapsed_minutes DESC";
            $values["records"]=$this->get($values);
            $values["conditionalBackground"]=array(
                array("field"=>"dirty","operator"=>"=","value"=>"1","color"=>"gold"),
                array("field"=>"dirty","operator"=>"=","value"=>"2","color"=>"darkorange"),
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
            $external=(evalPermissions("EXTERNAL",$profile["data"][0]["groups"]));
            $this->view="vw_operators_tasks";
            $values["interface"]=(MOD_CRM."/operators_tasks/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $values["grouped"]="";
            $values["access_token"]="";
            $values["obj_target"]="";
            if($values["records"]["data"][0]["id_buffer_in"]!=""){
                $BUFFER_IN=$this->createModel(MOD_CHANNELS,"buffer_in","buffer_in");
                $BUFFER_IN->view="vw_buffer_in_minimal";
                $buffer_in=$BUFFER_IN->get(array("fields"=>"grouped","where"=>"id=".$values["records"]["data"][0]["id_buffer_in"]));
                $values["grouped"]=$buffer_in["data"][0]["grouped"];
                $buffer_in=$BUFFER_IN->get(array("fields"=>"grouped,access_token,username","where"=>"grouped='".$values["grouped"]."' AND username NOT LIKE 'Credipaz%'"));
                $values["access_token"]=$buffer_in["data"][0]["access_token"];
                $values["obj_target"]=$buffer_in["data"][0]["username"];
            }
            $id_client_credipaz=0;
            if(isset($values["records"]["data"][0]["id_client_credipaz"])){$id_client_credipaz=$values["records"]["data"][0]["id_client_credipaz"];} 
            if($id_client_credipaz==null or $id_client_credipaz==""){$id_client_credipaz=0;}
            $parameters_id_operator=array(
                "model"=>(MOD_BACKEND."/Users"),
                "table"=>"users",
                "name"=>"id_operator",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_operator"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>WHERE_USERS_COMERCIAL,"order"=>"description ASC","pagesize"=>-1),
            );
            $get=array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1);
            if($external){$get=array("where"=>"id_type_contact_channel=6","order"=>"description ASC","pagesize"=>-1);}
            $parameters_id_contact_channel=array(
                "model"=>(MOD_CHANNELS."/Contact_channels"),
                "table"=>"Contact_channels",
                "name"=>"id_contact_channel",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_contact_channel"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>$get,
            );
            $parameters_id_tarjeta=array(
                "model"=>(MOD_CRM."/Tarjeta"),
                "table"=>"tarjeta",
                "name"=>"id_tarjeta",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_tarjeta"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_otro=array(
                "model"=>(MOD_CRM."/Otros"),
                "table"=>"otros",
                "name"=>"id_otro",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_otro"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $validate="";
            if($values["records"]["data"][0]["id_type_contact_channel"]==8){$validate="validate";} 
            $parameters_id_myd=array(
                "model"=>(MOD_CRM."/Myd"),
                "table"=>"myd",
                "name"=>"id_myd",
                "class"=>"form-control dbase ".$validate,
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_myd"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_mil=array(
                "model"=>(MOD_CRM."/Mil"),
                "table"=>"mil",
                "name"=>"id_mil",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_mil"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_credito=array(
                "model"=>(MOD_CRM."/Credito"),
                "table"=>"credito",
                "name"=>"id_credito",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_credito"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_club_redondo=array(
                "model"=>(MOD_CRM."/Club_redondo"),
                "table"=>"club_redondo",
                "name"=>"id_club_redondo",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_club_redondo"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $values["controls"]=array(
                "id_tarjeta"=>getCombo($parameters_id_tarjeta,$this),
                "id_otro"=>getCombo($parameters_id_otro,$this),
                "id_myd"=>getCombo($parameters_id_myd,$this),
                "id_mil"=>getCombo($parameters_id_mil,$this),
                "id_credito"=>getCombo($parameters_id_credito,$this),
                "id_club_redondo"=>getCombo($parameters_id_club_redondo,$this),
            );
            if ($values["id"]!=0){
                $values["controls"]["id_contact_channel"]=formatHtmlValue($values["records"]["data"][0]["contact_channel"],"code")."<input id='id_contact_channel' name='id_contact_channel' type='hidden' class='dbase' value='".$values["records"]["data"][0]["id_contact_channel"]."'/>";
                $values["controls"]["id_operator"]=formatHtmlValue($values["records"]["data"][0]["operator"],"code")."<input id='id_operator' name='id_operator' type='hidden' class='dbase' value='".$values["records"]["data"][0]["id_operator"]."'/>";
                $values["controls"]["created"]=formatHtmlValue($values["records"]["data"][0]["created"],"datetime");
                $values["controls"]["from"]=formatHtmlValue($values["records"]["data"][0]["from"],"email-action");
                $values["controls"]["subject"]=("<h3>".$values["records"]["data"][0]["subject"]."</h3>");
                $values["controls"]["body"]=("<div>".$values["records"]["data"][0]["body"]."</div>");
                if(!$external){$values["controls"]["statusBar"]=$this->buildActionBar($values["records"]["data"][0]["id_client_credipaz"],$values);}
            } else{
                $values["controls"]["id_contact_channel"]=getCombo($parameters_id_contact_channel,$this);
                $values["controls"]["id_operator"]=getCombo($parameters_id_operator,$this);
                $values["controls"]["created"]=formatHtmlValue($this->now,"datetime");
                $values["controls"]["from"]=formatHtmlValue(lang('msg_manual'),"code");;
                $values["controls"]["subject"]="";
                $values["controls"]["body"]=getTextArea($values,array("col"=>"col-md-12","nolabel"=>true,"name"=>"body","class"=>"form-control text dbase trumbo"));
                if(!$external){$values["controls"]["statusBar"]=$this->buildActionBar($id_client_credipaz,$values);}
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
            $id=(int)$values["id"];
            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                        'id_contact_channel' => secureEmptyNull($values,"id_contact_channel"),
                        'id_operator' => secureEmptyNull($values,"id_operator"),
                        'id_thread' => secureEmptyNull($values,"id_thread"),
                        'username' => $values["username"],
                        'subject' => $values["subject"],
                        'body' => $values["body"],
                        'from' => $values["from"],
                        'to' => $values["to"],
                        'id_buffer_in' => secureEmptyNull($values,"id_buffer_in"),
                        'id_type_task_close' => secureEmptyNull($values,"id_type_task_close"),
                        'id_client_credipaz' => secureEmptyNull($values,"id_client_credipaz"),
                        'valorized' => $values["valorized"],
                        'id_club_redondo' => secureEmptyNull($values,"id_club_redondo"),
                        'id_credito' => secureEmptyNull($values,"id_credito"),
                        'id_mil' => secureEmptyNull($values,"id_mil"),
                        'id_myd' => secureEmptyNull($values,"id_myd"),
                        'id_otro' => secureEmptyNull($values,"id_otro"),
                        'id_tarjeta' => secureEmptyNull($values,"id_tarjeta"),
                        'id_user' => secureEmptyNull($values,"id_user"),
                        'dirty'=>0
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'fum' => $this->now,
                        'id_operator' => secureEmptyNull($values,"id_operator"),
                        'id_contact_channel' => secureEmptyNull($values,"id_contact_channel"),
                        'id_type_task_close' => secureEmptyNull($values,"id_type_task_close"),
                        'id_client_credipaz' => secureEmptyNull($values,"id_cliente_credipaz"),
                        'valorized' => $values["valorized"],
                        'id_club_redondo' => secureEmptyNull($values,"id_club_redondo"),
                        'id_credito' => secureEmptyNull($values,"id_credito"),
                        'id_mil' => secureEmptyNull($values,"id_mil"),
                        'id_myd' => secureEmptyNull($values,"id_myd"),
                        'id_otro' => secureEmptyNull($values,"id_otro"),
                        'id_tarjeta' => secureEmptyNull($values,"id_tarjeta"),
                        'tag_processed' => $values["tag_processed"],
                        'id_user' => secureEmptyNull($values,"id_user"),
                    );
                    if ($fields["id_type_task_close"]!=null){$fields["dirty"]=0;}
                }
            }
            $saved=parent::save($values,$fields);
            if($values["re_scheduled"]!="") {
                $fieldList="[code],[description],[created],[verified],[fum],[id_contact_channel],[id_operator],[id_thread],[username],[subject],[body],[from],[to],[id_parent],[id_client_credipaz],[id_club_redondo],[id_credito],[id_mil],[id_myd],[id_otro],[id_tarjeta]";
                $selectToInsert="SELECT [code],'REAGENDADO: ".$this->now."',getdate(),getdate(),getdate(),[id_contact_channel],[id_operator],[id_thread],[username],'REAGENDADO: ".$this->now."',[body],[from],[to],[id_parent],[id_client_credipaz],[id_club_redondo],[id_credito],[id_mil],[id_myd],[id_otro],[id_tarjeta] FROM ".MOD_CRM."_operators_tasks WHERE id=".$values["id"];
                $params=array("fieldList"=>$fieldList,"selectToInsert"=>$selectToInsert);
                $this->insertBySelect($params);
            }
            return $saved;
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

    function Reports($values){
       try {
            $username=implode("','",$values["username"]);
            $from=$values["from"];
            $to=$values["to"];
            $id_type_task_close=$values["id_type_task_close"];
            $id_type_contact_channel=$values["id_type_contact_channel"];
            $id_tarjeta=$values["id_tarjeta"];
            $id_otro=$values["id_otro"];
            $id_myd=$values["id_myd"];
            $id_mil=$values["id_mil"];
            $id_credito=$values["id_credito"];
            $id_club_redondo=$values["id_club_redondo"];

            $sql="SELECT * FROM ".MOD_CRM."_vw_operators_tasks WHERE created >= '".$from."' AND created <='".$to."'";
            if ($username!="") {$sql.="' AND operator IN ('".$username."')";}
            if ($id_type_task_close!="") {$sql.="' AND id_type_task_close=".$id_type_task_close;}
            if ($id_type_contact_channel!="") {$sql.="' AND id_type_contact_channel=".$id_type_contact_channel;}
            if ($id_tarjeta!="") {$sql.="' AND id_tarjeta=".$id_tarjeta;}
            if ($id_otro!="") {$sql.="' AND id_otro=".$id_otro;}
            if ($id_myd!="") {$sql.="' AND id_myd=".$id_myd;}
            if ($id_mil!="") {$sql.="' AND id_mil=".$id_mil;}
            if ($id_credito!="") {$sql.="' AND id_credito=".$id_credito;}
            if ($id_club_redondo!="") {$sql.="' AND id_club_redondo=".$id_club_redondo;}
            $sql.=" ORDER BY operator ASC, created DESC";

            $ret=$this->getRecordsAdHoc($sql);
            return array("code">="2000","status"=>"OK","message"=>"","data"=>$ret);
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }
    
    private function buildActionBar($id,$values){
        $ops=array();
        $FUNCTIONS=$this->createModel(MOD_BACKEND,"Functions","Functions");
        $reports=$FUNCTIONS->get(array("where"=>"data_model!='veraz_experto' AND data_module='mod_reports_cpcentral' AND id IN (SELECT id_function FROM ".MOD_BACKEND."_rel_groups_functions WHERE id_group IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"]."))","order"=>"description ASC","pagesize"=>-1));
        foreach($reports["data"] as $record){
            $class="btn-menu-click btn-".secureField($record,"code");
            $datax="data-module='".secureField($record,"data_module")."' data-forced='.div-reports' data-model='".secureField($record,"data_model")."' data-table='".secureField($record,"data_table")."' data-action='".secureField($record,"data_action")."'";
            array_push($ops,array("name"=>lang(secureField($record,"code")),"class"=>$class,"datax"=>$datax));
        };
        $ddAssignReports=getDropdown(array("class"=>"btn-warning btn-raised","name"=>lang('b_reports_cp_central')),$ops);
        $html="<nav class='navbar navbar-light bg-primary'>";
        $html.="<ul class='nav nav-tabs'>";
        $html.="    <li class='nav-item'>".$ddAssignReports."</li>";
        $html.="    <li class='nav-item'><a class='nav-link' data-toggle='tab' href='#resolver' style='color:white;'>".lang('b_resolver')."</a></li>";
        if ($id==0){$html.="<li class='nav-item'><span class='badge badge-warning blink_me'>".lang('msg_notclient')."</span></li>";}
        $html.="    <li class='nav-item'><a class='nav-link' data-toggle='tab' href='#available' style='color:white;'>".lang('b_available')."</a></li>";
        $html.="    <li class='nav-item'><a class='nav-link' data-toggle='tab' href='#communications' style='color:white;'>".lang('b_communications')."</a></li>";
        $html.="    <li class='nav-item'><a class='nav-link' data-toggle='tab' href='#offices' style='color:white;'>".lang('b_offices')."</a></li>";
        $html.="    <li class='nav-item'><a class='nav-link btn-taskCLose' data-toggle='tab' href='#taskclose' style='font-weight:bold;color:red;'>".lang('b_close_task')."</a></li>";
        $html.="</ul>";
        $html.="</nav>";

        $html.="<div class='div-tabs tab-content'>";
        $html.="    <div id='resolver' class='hideable tab-pane fade'>".$this->buildTableClient($values)."</div>";
        $html.="    <div id='available' class='hideable tab-pane fade'>".$this->buildTableAvailable($values)."</div>";
        $html.="    <div id='communications' class='hideable tab-pane fade'>".$this->buildTableCommunications($values)."</div>";
        $html.="    <div id='offices' class='hideable tab-pane fade'>".$this->buildTablePlaces()."</div>";
        $html.="    <div id='taskclose' class='hideable tab-pane fade'>".$this->buildTableCloseTask($values)."</div>";
        $html.="</div>";
        $html.="<div class='div-reports card'></div>";
        return $html;
    }
    private function buildTableClient($values){
        try {
            $records=null;
            if(isset($values["records"]["data"][0]["id_client_credipaz"])) {
                $id=$values["records"]["data"][0]["id_client_credipaz"];
                if ($id==""){$id=0;}
                $sql="SELECT 'Cliente' as Estado,nID,sNombre,nID as id_client_credipaz,nIDSucursal,dFechaNac,sLKNacionalidad,sSexo,sLKDoctipo,nDoc,sLKEstadoCivil,sDomiCalle+' '+sDomiNro+' '+sDomiPisoDpto+' '+sDomiEntre+' '+sDomiBarrio+' '+sLKDomiLocalidad as [address],sDomiTETelediscado+sDomiTE as phone,nCUIT,nCUIL,sLKTipoVivienda,sLKCalificacion,sEmail,sCBU FROM dbCentral.dbo.wrkClienteTitular WHERE nID=".$id;
                if ((int)$id==0){
                    $id_user=$values["records"]["data"][0]["id_user"];
                    $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
                    $user=$USERS->get(array("where"=>"id=".$id_user));
                    if(isset($user["data"][0]["documentNumber"])){
                       if($user["data"][0]["documentNumber"]==""){$user["data"][0]["documentNumber"]=0;}
                       if ((int)$user["data"][0]["documentNumber"]==0){throw new Exception("",0);}
                       $sql="SELECT 'Prospecto' as Estado,nID,sNombre,null as id_client_credipaz,nIDSucursal,dFechaNac,sLKNacionalidad,sLKSexo,sLKDoctipo,nDoc,sLKEstadoCivil,sDomiCalle+' '+sDomiNro+' '+sDomiPisoDpto+' '+sDomiEntre+' '+sDomiBarrio+' '+sLKDomiLocalidad as [address],sDomiTETelediscado+sDomiTE as phone,null as nCUIT,sCUIL as nCUIT,null as sLKTipoVivienda,null as sLKCalificacion,null as sEmail,null as sCBU FROM dbCentral.dbo.promClienteTitular WHERE nDoc='".$user["data"][0]["documentNumber"]."'";
                    }
                }
                $records=$this->getRecordsAdHoc($sql);
            } else {
                if(!isset($values["nDoc"])){$values["nDoc"]=0;}
                if ((int)$values["nDoc"]==0){throw new Exception("",0);}
                $sql="SELECT 'Cliente' as Estado,nID,sNombre,nID as id_client_credipaz,nIDSucursal,dFechaNac,sLKNacionalidad,sSexo,sLKDoctipo,nDoc,sLKEstadoCivil,sDomiCalle+' '+sDomiNro+' '+sDomiPisoDpto+' '+sDomiEntre+' '+sDomiBarrio+' '+sLKDomiLocalidad as [address],sDomiTETelediscado+sDomiTE as phone,nCUIT,nCUIL,sLKTipoVivienda,sLKCalificacion,sEmail,sCBU FROM dbCentral.dbo.wrkClienteTitular WHERE nDoc='".$values["nDoc"]."'";
                $records=$this->getRecordsAdHoc($sql);
                if(!isset($records["data"][0])){
                   $sql="SELECT 'Prospecto' as Estado,nID,sNombre,null as id_client_credipaz,nIDSucursal,dFechaNac,sLKNacionalidad,sLKSexo,sLKDoctipo,nDoc,sLKEstadoCivil,sDomiCalle+' '+sDomiNro+' '+sDomiPisoDpto+' '+sDomiEntre+' '+sDomiBarrio+' '+sLKDomiLocalidad as [address],sDomiTETelediscado+sDomiTE as phone,null as nCUIT,sCUIL as nCUIL,null as sLKTipoVivienda,null as sLKCalificacion,null as sEmail,null as sCBU FROM dbCentral.dbo.promClienteTitular WHERE nDoc='".$values["nDoc"]."'";
                   $records=$this->getRecordsAdHoc($sql);
                }
            }
            if (!isset($records[0])) {throw new Exception("",0);}
            $html="<table class='table table-condensed table-hover'>";
            $html.="   <tbody>";
            $condition=lang('p_not_client');
            if ($records[0]["id_client_credipaz"]!=""){$condition=lang('p_client')." ".$records[0]["id_client_credipaz"];}
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_condition')."</td><td>".$condition."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_qualification')."</td><td>".$records[0]["sLKCalificacion"]."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_name')."</td><td>".$records[0]["sNombre"]."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_phone')."</td><td>".$records[0]["phone"]."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_email')."</td><td>".formatHtmlValue($records[0]["sEmail"],"email-action")."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_address')."</td><td>".$records[0]["address"]."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_number_doc')."</td><td>".$records[0]["sLKDoctipo"]." ".$records[0]["nDoc"]."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_nationality')."</td><td>".$records[0]["sLKNacionalidad"]."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_sex')."</td><td>".$records[0]["sSexo"]."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_civil_status')."</td><td>".$records[0]["sLKEstadoCivil"]."</td></tr>";
            $html.="      <tr class='warning'><td style='font-weight:bold;'>".lang('p_birthday')."</td><td>".formatHtmlValue($records[0]["dFechaNac"],"date")."</td></tr>";
            $html.="      <tr class='warning'><td style='font-weight:bold;'>".lang('p_type_building')."</td><td>".$records[0]["sLKTipoVivienda"]."</td></tr>";
            $html.="      <tr class='warning'><td style='font-weight:bold;'>CUIT</td><td>".$records[0]["nCUIT"]."</td></tr>";
            $html.="      <tr class='warning'><td style='font-weight:bold;'>CUIL</td><td>".$records[0]["nCUIL"]."</td></tr>";
            $html.="      <tr class='warning'><td style='font-weight:bold;'>CBU</td><td>".$records[0]["sCBU"]."</td></tr>";
            $html.="   </tbody>";
            $html.="</table>";
        } catch(Exception $e){
            $html=getNoData();
        }
        return $html;
    }
    private function buildTableAvailable($values){
        try {
            $id=$values["records"]["data"][0]["id_client_credipaz"];
            if ($id==""){$id=0;}
            if ((int)$id==0){throw new Exception("",0);}
            $sql="SELECT sCodigo as cuenta FROM dbCentral.dbo.tarTarjeta WHERE nIDCliente=".$id;
            $cuenta=$this->getRecordsAdHoc($sql);
            if (!isset($cuenta[0]["cuenta"])){throw new Exception("",0);}
            $sql="EXEC dbCentral.dbo.tar_Saldo_Disponible '".$cuenta[0]["cuenta"]."', 'T', 1";
            $records=$this->getRecordsAdHoc($sql);
            if (!isset($records[0]["PuedeOperar"])){throw new Exception("",0);}
            $habilitado="<span class='label label-danger'>No habilitado</span>";
            if ($records[0]["PuedeOperar"]==1){$habilitado="<span class='label label-success'>Habilitado</span>";}
            $redondo="<span class='label label-danger'>No es socio</span>";
            if ($records[0]["Socioredondo"]==1){$redondo="<span class='label label-success'>Es socio</span>";}
            $html="<table class='table table-condensed table-hover'>";
            $html.="   <tbody>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_account')."</td><td>".$records[0]['Cuenta']."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_state')."</td><td>".$habilitado."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_club_redondo')."</td><td>".$redondo."</td></tr>";
            $html.="      <tr class='active'><td style='font-weight:bold;'>".lang('p_day_points')."</td><td align='right'>".$records[0]['PuntosDia']."</td></tr>";
            $html.="      <tr class='info'><td style='font-weight:bold;'>".lang('p_available_balance')."</td><td align='right'>$".$records[0]['SaldoDisponible']."</td></tr>";
            $html.="      <tr class='info'><td style='font-weight:bold;'>".lang('p_closed_balance')."</td><td align='right'>$".$records[0]['SaldoCierre']."</td></tr>";
            $html.="      <tr class='info'><td style='font-weight:bold;'>".lang('p_close_deb')."</td><td align='right'>$".$records[0]['DeudaCierre']."</td></tr>";
            $html.="      <tr class='info'><td style='font-weight:bold;'>".lang('p_min_payment')."</td><td align='right'>$".$records[0]['PagoMinimo']."</td></tr>";
            $html.="      <tr class='info'><td style='font-weight:bold;'>".lang('p_credit_limit')."</td><td align='right'>$".$records[0]['LimiteCredito']."</td></tr>";
            $html.="      <tr class='info'><td style='font-weight:bold;'>".lang('p_future_assets')."</td><td>".$records[0]['CuotasFuturas']."</td></tr>";
            $html.="      <tr class='info'><td style='font-weight:bold;'>".lang('p_day_patrim')."</td><td>".$records[0]['PatrimDia']."</td></tr>";
            $html.="      <tr class='warning'><td style='font-weight:bold;'>".lang('p_activation')."</td><td>".formatHtmlValue($records[0]["FechaActivacion"],"date")."</td></tr>";
            $html.="      <tr class='warning'><td style='font-weight:bold;'>".lang('p_process')."</td><td>".formatHtmlValue($records[0]["FechaProceso"],"date")."</td></tr>";
            $html.="   </tbody>";
            $html.="</table>";
        } catch(Exception $e){
            $html=getNoData();
        }
        return $html;
    }
    private function buildTableCommunications($values){
        try {
            $id=$values["records"]["data"][0]["id_client_credipaz"];
            $id_user=$values["records"]["data"][0]["id_user"];
            if ($id==""){$id=0;}
            if ($id_user==""){$id_user=0;}
            $operations=$this->get(array("page"=>1,"order"=>"created DESC","where"=>("id_client_credipaz=".$id." OR id_user=".$id_user)));
            if (!isset($operations["data"][0]["created"])){throw new Exception("",0);}
            $html="<table class='table table-condensed table-hover'>";
            foreach($operations["data"] as $operation){
                $html.="<tr>";
                $html.="   <td>".formatHtmlValue($operation["created"],"datetime")."</span></td>";
                $html.="   <td><div>".$operation["body"]."</div></td>";
                $html.="</tr>";
            }
            $html.="</table>";
        } catch(Exception $e){
            $html=getNoData();
        }
        return $html;
    }
    private function buildTablePlaces(){
        $nodata=getNoData();
        $PLACES=$this->createModel(MOD_PLACES,"Places","Places");
        $offices=$PLACES->get(array("page"=>1,"order"=>"description ASC","where"=>("id_type_place=1")));
        $html="<table class='table table-condensed table-hover'>";
        foreach($offices["data"] as $office){
            $nodata="";
            $html.="<tr>";
            $html.="   <td>".$office["description"]."</td>";
            $html.="   <td>".$office["address"]."</td>";
            $html.="   <td>".$office["open"]."</td>";
            $html.="</tr>";
        }
        $html.="</table>";
        $html.=$nodata;
        return $html;
    }
    private function buildTableCloseTask($values){
        $id_type_task_close=$values["records"]["data"][0]["id_type_task_close"];
        if($id_type_task_close==""){$id_type_task_close=-1;}
        $html="";
        $html.="<div class='form-row card'>";
        $TYPE_TASKS_CLOSE=$this->createModel(MOD_CRM,"Type_tasks_close","Type_tasks_close");
        $type_tasks_close=$TYPE_TASKS_CLOSE->get(array("order"=>"description ASC"));
        $html.="<div class='col-md-12'>";
        $html.="<select id='id_type_task_close' name='id_type_task_close' class='dbase form-control id_type_task_close' style='color:black;'>";
        try {
            $html.="<option value='-1'>".lang('p_select_combo')."</option>";
            foreach($type_tasks_close["data"] as $row){
                $selected="";
                $id=$row["id"];
                if($id==$id_type_task_close){$selected="selected";}
                $html.="<option data-is_cash='".$row["is_cash"]."' data-is_rescheduled='".$row["is_rescheduled"]."' ".$selected." value='".$id."'>";
                $html.=$row["description"];
                $html.="</option>";
            };
        } catch(Exception $e){}
        $html.="</select>";
        $html.="</div>";
        $html.="<div class='col-md-12 is_cash d-none'>";
        $html.=getInput($values,array("col"=>"col-md-2","name"=>"valorized","type"=>"number","class"=>"form-control number dbase valorized"));
        $html.="</div>";
        $html.="<div class='col-md-12 is_rescheduled d-none'>";
        $html.=getInput($values,array("col"=>"col-md-2","name"=>"re_scheduled","type"=>"date","class"=>"form-control date dbase re_scheduled"));
        $html.="</div>";
        $html.=getTextArea($values,array("col"=>"col-md-12","name"=>"tag_processed","class"=>"form-control text dbase"));
        $html.="</div>";
        return $html;
    }
}
