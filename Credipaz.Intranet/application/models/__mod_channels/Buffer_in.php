<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Buffer_in extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $ddAssignTotal="";
            $ddAssign="";
            $profile=getUserProfile($this,$values["id_user_active"]);
            $external=(evalPermissions("EXTERNAL",$profile["data"][0]["groups"]));
            $values["order"]="created DESC, origin ASC";
            //$external=true;
            if ($external) {
               if($values["where"]!=""){$values["where"].=" AND ";}
               $values["where"].="id_type_contact_channel IN (6,5) AND id_operator IS null";
            } else {
               if($values["where"]!=""){$values["where"].=" AND ";}
               $values["where"].="id_operator IS null";
            }
            $values["pagesize"]=10;
            $this->view="vw_buffer_in";
            $values["records"]=$this->get($values);

            //$ops=array();
            $opsTotal=array();
            $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
            $records=$USERS->get(array("where"=>WHERE_USERS_COMERCIAL,"order"=>"id ASC","pagesize"=>-1));
            //array_push($ops,array("name"=>lang('b_no_operator'),"style"=>"color:darkgreen;","class"=>"btn-buffer-assign","datax"=>"data-status='-1' data-id=|ID|"));
            //array_push($ops,array("name"=>lang('b_blacklist'),"style"=>"color:red;","class"=>"btn-buffer-assign","datax"=>"data-status='-2' data-id=|ID|"));
            //array_push($ops,array("name"=>lang('b_nomore'),"style"=>"color:blue;","class"=>"btn-buffer-assign","datax"=>"data-status='-3' data-id=|ID|"));
            array_push($opsTotal,array("name"=>lang('b_no_operator'),"style"=>"color:darkgreen;","class"=>"btn-buffer-assign","datax"=>"data-status='-1' data-id=0"));
            array_push($opsTotal,array("name"=>lang('b_blacklist'),"style"=>"color:red;","class"=>"btn-buffer-assign","datax"=>"data-status='-2' data-id=0"));
            array_push($opsTotal,array("name"=>lang('b_nomore'),"style"=>"color:blue;","class"=>"btn-buffer-assign","datax"=>"data-status='-3' data-id=0"));
           foreach($records["data"] as $record){
                //array_push($ops,array("name"=>secureField($record,"username"),"class"=>"btn-buffer-assign","datax"=>"data-status='".secureField($record,"id")."' data-id=|ID|"));
                array_push($opsTotal,array("name"=>secureField($record,"username"),"class"=>"btn-buffer-assign","datax"=>"data-status='".secureField($record,"id")."' data-id=0"));
            };
            //$ddAssign=getDropdown(array("class"=>"btn-inverse btn-raised","name"=>lang('b_assign')),$ops);
            $ddAssignTotal=getDropdown(array("class"=>"btn-info btn-raised btn-sm","name"=>lang('b_assign_all')),$opsTotal);

            $values["columns"]=array(
                //array("field"=>"id","format"=>"text"),
                array("field"=>"created","forcedlabel"=>"fum","format"=>"datetime","class"=>""),
                //array("field"=>"assign","html"=>$ddAssign,"format"=>"html#block"),
                array("field"=>"title","format"=>"shorten"),
                array("field"=>"response","format"=>"text"),
                //array("field"=>"origin","format"=>"code","class"=>""),
                //array("field"=>"contact_channel","format"=>"status","class"=>""),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("created","origin","title")),
                array("name"=>"browser_id_contact_channel", "operator"=>"=","fields"=>array("id_contact_channel")),
            );
            $get2=array("order"=>"description ASC","pagesize"=>-1);

            if ($external) {
                $ddAssignTotal="<button type='button' class='btn btn-raised btn-record-edit btn-primary btn-sm' data-id='0' data-module='mod_crm' data-model='operators_tasks' data-table='operators_tasks'><i class='material-icons'>note_add</i> Nueva tarea CRM</button> ".$ddAssignTotal;
                $get2=array("where"=>"id_type_contact_channel=6","order"=>"description ASC","pagesize"=>-1);
            }
             $values["controls"]=array(
                $ddAssignTotal,
                "<span class='badge badge-primary'>Canal de contacto</span>".comboContactChannels($this,$get2)
            );
            $values["conditionalBackground"]=array(
                array("field"=>"dirty","operator"=>"=","value"=>"1","color"=>"gold"),
                array("field"=>"dirty","operator"=>"=","value"=>"2","color"=>"darkorange"),
            );

            $values["buttons"]=array(
                "check"=>true,
                "new"=>false,
                "edit"=>false,
                "delete"=>true,
                "offline"=>false,
            );
            
            $i=0;
            foreach($values["records"]["data"] as $record){
                $btn="";
                switch((int)$record["id_type_contact_channel"]) {
                   case 5: //facebook
                   case 6: //messenger
                      $btn="<a data-table='".MOD_CHANNELS."|buffer_in' data-id='".$record["id"]."' data-code='".$record["grouped"]."' data-token='".$record["access_token"]."' class='btn btn-sm btn-primary btn-raised btn-response-messenger'>".lang('b_response')."</a>";
                      break;
                   default:
                      break;
                }
                $values["records"]["data"][$i]["response"]=$btn;
                $i+=1;
            };
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function delete($values){
        try {
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $record=$this->get($values);
            if(isset($record["data"][0]["grouped"])){
                if($record["data"][0]["grouped"]!="") {
                   $this->deleteByWhere("grouped='".$record["data"][0]["grouped"]."' AND id!=".$values["id"]);
                }
            }
            return parent::delete($values);
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    
    public function save($values,$fields=null){
        try {
        //iconv('','UTF-8',$EnkripsiUserPassword); 
            if (!isset($values["id"])){$values["id"]=0;}
            if (!isset($values["code"])){$values["code"]=opensslRandom(16);}
            if (!isset($values["description"])){$values["description"]=("AUTOBUFFERING:".$this->now);}
            if (!isset($values["verified"])){$values["verified"]=$this->now;}
            if (!isset($values["grouped"])){$values["grouped"]=null;}
            if (!isset($values["id_client_credipaz"])){$values["id_client_credipaz"]=null;}
            $id=(int)$values["id"];
            if($id==0){
                if($fields==null){
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $values["verified"],
                        'offline' => null,
                        'fum' => $this->now,
                        "id_contact_channel"=>secureEmptyNull($values,"id_contact_channel"),
                        "id_operator"=>secureEmptyNull($values,"id_operator"),
                        "id_thread"=>secureEmptyNull($values,"id_thread"),
                        "username"=>$values["username"],
                        "subject"=>$values["subject"],
                        "body"=>$values["body"],
                        "from"=>$values["from"],
                        "to"=>$values["to"],
                        "processed"=>null,
                        "tag_processed"=>$values["tag_processed"],
                        "grouped"=>$values["grouped"],
                        "id_client_credipaz"=>$values["id_client_credipaz"],
                        "dirty"=>0
                    );
                }
            } else {
                if($fields==null){
                   $fields = array("body"=>$values["body"]);
                }
                $record=$this->get(array("page"=>1,"where"=>("id=".$id)));
                $fields=array("fum"=>$this->now);
                $this->updateByWhere($fields,"grouped='".$record["data"][0]["grouped"]."'");
            }
            return parent::save($values,$fields);
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
                case -3: // no more gestion in this stage!
                   foreach($ids as $id){
                       $record=$this->get(array("page"=>1,"where"=>("id=".$id)));
                        if ($record["data"][0]["grouped"]!="") {
                            $fields=array("id_operator"=>$id_operator,"fum"=>$this->now,"offline"=>$this->now,"tag_processed"=>"Sin mas gestión en esta etapa");
                            $this->updateByWhere($fields,"grouped='".$record["data"][0]["grouped"]."'");
                        } else {
                            $this->save(array("id"=>$id),array("id_operator"=>$id_operator,"offline"=>$this->now,"tag_processed"=>"Sin mas gestión en esta etapa"));
                        }
                   };
                   break;
                case -2: //
                    $BLACKLIST=$this->createModel(MOD_CHANNELS,"Blacklist","Blacklist");
                    foreach($ids as $id){
                        $record=$this->get(array("page"=>1,"where"=>("id=".$id)));
                        if(isset($record["data"][0]["from"])) {
                            if ($record["data"][0]["grouped"]!="") {
                                $values["where"]=("grouped='".$record["data"][0]["grouped"]."'");
                                $toProcess=$this->get($values);
                                foreach($toProcess["data"] as $item){
                                    $BLACKLIST->save(array("id"=>0,"code"=>$item["from"],"description"=>"Bloquea envíos"));
                                }
                            } else {
                                $BLACKLIST->save(array("id"=>0,"code"=>$record["data"][0]["from"],"description"=>"Bloquea envíos"));
                            }
                        }
                        $this->delete(array("id"=>$id));
                    };
                    break;
                case -1: // generate task without operator assigned
                    // ----------------------------------------------------------------- //
                    // Auto assign manager block                                         //
                    // ----------------------------------------------------------------- //
                    $id_operator=null;
                    $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
                    $autoAssign1=array("lrichieri","sciancia","asuarez","ngrassi"); //info@credipaz.com
                    $autoAssign9=array("aroldan","ngrassi"); //gym@credipaz.com
                    $iter1=0;
                    $iter9=0;
                    for ($i = 0; $i < count($autoAssign1); $i++) {
                        $user=$USERS->get(array("where"=>"username='".$autoAssign1[$i]."@credipaz.com'"));
                        $autoAssign1[$i]=$user["data"][0]["id"];
                    }
                    for ($i = 0; $i < count($autoAssign9); $i++) {
                        $user=$USERS->get(array("where"=>"username='".$autoAssign9[$i]."@credipaz.com'"));
                        $autoAssign9[$i]=$user["data"][0]["id"];
                    }
                    // ----------------------------------------------------------------- //
                default: // generate task 
                    $OPERATORS_TASKS=$this->createModel(MOD_CRM,"Operators_tasks","Operators_tasks");
                    foreach($ids as $id){
                        $item=$this->get(array("page"=>1,"where"=>("id=".$id)));
                        // ------------------------------------------------------------------ //
                        // Switch by contact channel and autoasign if its defined in that way //
                        // ------------------------------------------------------------------ //
                        if ($id_operator==null) {
                            switch ((int)$item["data"][0]["id_contact_channel"]) {
                               case 1:
                                  $id_operator=$autoAssign1[$iter1];
                                  $iter1+=1;
                                  if ($iter1>=count($autoAssign1)) {$iter9=0;} 
                                  break;
                               case 9:
                                  $id_operator=$autoAssign9[$iter9];
                                  $iter9+=1;
                                  if ($iter9>=count($autoAssign9)) {$iter9=0;} 
                                  break;
                               default:
                                  $id_operator=null;
                                  break;
                            }
                        }
                        // ------------------------------------------------------------------ //
                        $this->view="vw_buffer_in";
                        $item2=$this->get(array("page"=>1,"where"=>("id=".$id)));

                        $fields=array("id_operator"=>$id_operator,"processed"=>$this->now,"tag_processed"=>"Generó tarea");
                        $this->updateByWhere($fields,"grouped='".$item["data"][0]["grouped"]."'");

                        if($item["data"][0]["subject"]==""){$item["data"][0]["subject"]=$item["data"][0]["body"];}
                        switch((int)$item["data"][0]["id_type_contact_channel"]){
                            case 3:
                               $body= iconv('unicode','UTF-8',$item2["data"][0]["title"]);
                               break;
                            default:
                               $body=$item2["data"][0]["title"];
                               break;
                        }
                        $params=array(
                            'code' => $item["data"][0]["code"],
                            'description' => $item["data"][0]["description"],
                            'created' => $this->now,
                            'verified' => $this->now,
                            'offline' => null,
                            'fum' => $this->now,
                            'id_contact_channel' => $item["data"][0]["id_contact_channel"],
                            'id_operator' => $id_operator,
                            'id_thread' => $item["data"][0]["id_thread"],
                            'username' => $item["data"][0]["username"],
                            'subject' => $item["data"][0]["subject"],
                            'body' =>$body,
                            'from' => $item["data"][0]["from"],
                            'to' => $item["data"][0]["to"],
                            'id_buffer_in' => $id,
                            'id_type_task_close' => null,
                            'id_client_credipaz' => null,
                            'valorized' => null,
                            'id_club_redondo' => null,
                            'id_credito' => null,
                            'id_mil' => null,
                            'id_myd' => null,
                            'id_otro' => null,
                            'id_tarjeta' => null,
                        );
                        $saved=$OPERATORS_TASKS->save($params,null);
                        if($saved["status"]=="OK"){
                            //evaluate if can be resolved the id_cliente_credipaz, and then update the record in operators tasks!
                            //$saved["data"]["id"]
                        }
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
}

