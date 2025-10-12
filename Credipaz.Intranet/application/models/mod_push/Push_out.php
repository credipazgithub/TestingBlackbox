<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
class Push_out extends My_Model {
    function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $this->view="vw_push_out";
            $values["order"]="id DESC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>true,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"created","format"=>"date"),
                array("field"=>"subject","format"=>"text"),
                array("field"=>"type_target","format"=>"type"),
                array("field"=>"type_command","format"=>"type"),
                array("field"=>"type_subscription","format"=>"type"),
                array("field"=>"group","format"=>"type"),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description","subject","body")),
                array("name"=>"browser_id_type_command", "operator"=>"=","fields"=>array("id_type_command")),
                array("name"=>"browser_id_type_target", "operator"=>"=","fields"=>array("id_type_target")),
                array("name"=>"browser_id_type_subscription", "operator"=>"=","fields"=>array("id_type_subscription")),
                array("name"=>"browser_id_group", "operator"=>"=","fields"=>array("id_group")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Comando</span>".comboTypeCommands($this),
                "<span class='badge badge-primary'>Blanco de acción</span>".comboTypeTargets($this),
                "<span class='badge badge-primary'>Suscripción</span>".comboTypeSubscriptions($this),
                "<span class='badge badge-primary'>Grupo</span>".comboGroups($this),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $this->view="vw_push_out";
            $values["interface"]=(MOD_PUSH."/push_out/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_id_group=array(
                "model"=>(MOD_BACKEND."/Groups"),
                "table"=>"groups",
                "name"=>"id_group",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_group"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_target=array(
                "model"=>(MOD_PUSH."/Type_targets"),
                "table"=>"type_targets",
                "name"=>"id_type_target",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_target"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_command=array(
                "model"=>(MOD_PUSH."/Type_commands"),
                "table"=>"type_commands",
                "name"=>"id_type_command",
                "class"=>"form-control dbase id_type_command",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_command"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_subscription=array(
                "model"=>(MOD_PUSH."/Type_subscriptions"),
                "table"=>"type_subscriptions",
                "name"=>"id_type_subscription",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_subscription"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
			$test="<div class='input-group mb-3'>";
			$test.="  <input id='dni' name='dni' type='text' class='form-control dni dbase' placeholder='DNI para prueba' aria-label='DNI para prueba' aria-describedby='basic-addon2'>";
			$test.="  <div class='input-group-append px-2'>";
			$test.="     <button class='btn btn-xs btn-raised btn-primary btn-test-push d-none' type='button'>Test!</button>";
			$test.="  </div>";
			$test.="</div>";

            $values["controls"]=array(
                "id_type_target"=>getCombo($parameters_id_type_target,$this),
                "id_type_command"=>getCombo($parameters_id_type_command,$this),
                "id_type_subscription"=>getCombo($parameters_id_type_subscription,$this),
                "group"=>getCombo($parameters_id_group,$this),
				"test_push"=>$test
            );
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
			$USERS=$this->createModel(MOD_BACKEND,"Users","Users");
            if (!isset($values["id"])){$values["id"]=0;}
            if (!isset($values["to_one"])){$values["to_one"]="";}
            if (!isset($values["to_many"])){$values["to_many"]="";}
            if (!isset($values["send_message"])){$values["send_message"]=1;}
			$send_message=(int)$values["send_message"];
			$testing=((int)$values["testing"]!=0);
            //Fuerza notificacones a grupos LDAP por medio del canal APP notifier            
            if (secureEmptyNull($values,"id_group")!=null){$values["id_type_subscription"]="5";} 
            $id=(int)$values["id"];
			$delete=($id==0);
			if ($testing) {
			    $id=0;
			    $id_type_user=0;
			    switch((int)$values["id_type_subscription"]){
				   case 2: //APP Credipaz
					  $id_type_user=80;
				      break;
				   case 3:// APP Club Redondo
				   case 4:// APP Club Redondo Telemedicina
					  $id_type_user=82;
				      break;
				   case 5:// APP Notifier
					  $id_type_user=84;
				      break;
				}
				$users=$USERS->get(array("where"=>"id_type_user=".$id_type_user." AND username LIKE '%".$values["dni"]."%'"));
	            if ((int)$users["totalrecords"]!=0){
				   $values["to_one"]=$users["data"][0]["token_push"];
				   $values["to_many"]="";
				} else {
				   throw new Exception(lang('error_8001'),8001);
				} 
			}

            if($id==0){
               if ($fields==null) {
                   $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                        'subject' => $values["subject"],
                        'body' => $values["body"],
                        'id_type_subscription' => secureEmptyNull($values,"id_type_subscription"),
                        'id_type_target' => secureEmptyNull($values,"id_type_target"),
                        'id_type_command' => secureEmptyNull($values,"id_type_command"),
                        'image_url'=>$values["image_url"],
                        'to_one' =>$values["to_one"],
                        'scheduled'=>null,
                        'automatic'=>null,
                        'id_group' => secureEmptyNull($values,"id_group"),
                        'to_many' =>$values["to_many"],
                        'send_message' =>$send_message,
                        'id_beneficio' => secureEmptyNull($values,"id_beneficio"),
                   );
               }
            } else {
               if ($fields==null) {
                   $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'offline' => null,
                        'fum' => $this->now,
                        'subject' => $values["subject"],
                        'body' => $values["body"],
                        'id_type_subscription' => secureEmptyNull($values,"id_type_subscription"),
                        'id_type_target' => secureEmptyNull($values,"id_type_target"),
                        'id_type_command' => secureEmptyNull($values,"id_type_command"),
                        'image_url'=>$values["image_url"],
                        'to_one' =>$values["to_one"],
                        'scheduled'=>null,
                        'automatic'=>null,
                        'id_group' => secureEmptyNull($values,"id_group"),
                        'to_many' =>$values["to_many"],
                        'send_message' =>$send_message,
                        'id_beneficio' => secureEmptyNull($values,"id_beneficio"),
                   );
               }
			}
			$saved=parent::save($values,$fields);
			if ($send_message==1 or $testing) {
				$id=$saved["data"]["id"];
				if($fields["to_many"]!="") {
			  		$users=$USERS->get(array("where"=>"username IN (".$fields["to_many"].")"));
					$this->send($id,$users);
				} else {
					if ($fields["id_group"]!=null) {
						$USERS=$this->createModel(MOD_BACKEND,"Users","Users");
						$users=$USERS->get(array("where"=>"id IN (SELECT id_user FROM ".MOD_BACKEND."_rel_users_groups WHERE id_group=".$fields["id_group"].")"));
						$this->send($id,$users);
					} else {
						if ($fields["id_type_subscription"]!=null) {$this->send($id,null);}
					}
					if ($testing) {
						if ($delete) {$this->delete(array("id"=>$id));}
						throw new Exception(lang('error_8002'),8002);
					}
				}
			}
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function send($id,$users=null){
        try {
            $this->view="vw_push_out";
            $push=$this->get(array("where"=>"id=".$id));
            $id_beneficio=$push["data"][0]["id_beneficio"];
			if($id_beneficio==""){$id_beneficio=0;}
            $to_one=$push["data"][0]["to_one"];
            $image=$push["data"][0]["image_url"];
            $config=(DIR_CONFIG.'/'.$push["data"][0]["code_subscription"]);
            $scoope="https://www.googleapis.com/auth/firebase.messaging";
            $url=("https://fcm.googleapis.com/v1/projects/".$push["data"][0]["fcm_server_key"]."/messages:send");

            $this->load->library('google');
            $client=$this->google;
            $client->setAuthConfig($config);
            $client->addScope($scoope);
            $client->fetchAccessTokenWithAssertion();
            $auth=$client->getAccessToken();
            $data=array(
                "date"=>$this->now,
                "target"=>$push["data"][0]["type_target"],
                "command"=>$push["data"][0]["type_command"],
                "dtitle"=>$push["data"][0]["subject"],
                "dbody"=>$push["data"][0]["body"],
				"id_beneficio"=>(string)$id_beneficio,
            );

            if ($image!=""){$data["dimage"]=$image;}
            $Androidnotification=array(
                        "notification"=>array(
                            "body"=>$push["data"][0]["body"],
                            "title"=>$push["data"][0]["subject"],
                            "notification_priority"=>5,
                            "visibility"=>2,
                            "default_sound"=>true,
                            "default_vibrate_timings"=>true,
                            "default_light_settings"=>true,
                        )
            );
            $message=array("message"=>array("android"=>$Androidnotification));

            if ($image!=""){$message["message"]["android"]["notification"]["image"]=$image;}
            $message["message"]["android"]["data"]=$data;
            $message["message"]["android"]["priority"]="high";
            $message["message"]["android"]["direct_boot_ok"]=true;

            if ($users==null)
            {
                if ($to_one!=""){$message["message"]["token"]=$to_one;}else{$message["message"]["topic"]="all";}
                $ret=$this->hardSend($url,$auth,$message);
            } else {
                foreach($users["data"] as $item){
                   if (strlen($item["token_push"])>15) {
                       $message["message"]["token"]=$item["token_push"];
                       $ret=$this->hardSend($url,$auth,$message);
                   }
                }
            }
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    private function hardSend($url,$auth,$message){
        $message=json_encode($message);
        $headers=array('Content-Type:application/json','Content-Length: '.strlen($message),'Authorization: Bearer '.$auth["access_token"]);
        $result=$this->cUrlRestful($url,$headers,1,$message);
        return array("status"=>"OK","message"=>$result);
    }

    public function sendToOne($values){
        try {
            $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
            $usr=$USERS->get(array("where"=>"id=".$values["id_user"]));
            if ((int)$usr["totalrecords"]!=0){
                $values["to_one"]=$usr["data"][0]["token_push"];
                if ($values["to_one"]=="" or $values["to_one"]==null){throw new Exception(lang('error_8000'),8000);}
                $TYPE_SUBSCRIPTIONS=$this->createModel(MOD_PUSH,"Type_subscriptions","Type_subscriptions");
                $ts=$TYPE_SUBSCRIPTIONS->get(array("pagesize"=>"-1","where"=>"id=4"));
                $values["id_type_subscription"]=$ts["data"][0]["id"];
                $return=$this->save($values);
            }
            return $return;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function sendToGroup($values){
        try {
            $params = array(
                'id' => 0,
                'code' => "",
                'description' => "",
                'created' => $this->now,
                'verified' => $this->now,
                'offline' => null,
                'fum' => $this->now,
                'subject'=>$values["subject"],
                'body'=>$values["body"],
                'id_type_subscription' => null,
                'id_type_target' => null,
                'id_type_command' => null,
                'image_url'=>null,
                'to_one' =>null,
                'scheduled'=>null,
                'automatic'=>null,
                'to_many'=>null,
                'id_group'=> $values["id_group"]
            );
            $this->save($params,null);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function bufferOut($items){
        try {
            $i=0;
            $REL_THREADS_TARGETS_CONTACT_CHANNELS=$this->createModel(MOD_CHANNELS,"Rel_threads_targets_contact_channels","Rel_threads_targets_contact_channels");
            foreach($items["data"] as $item){
                //Here logic for effective send through channel!
                $REL_THREADS_TARGETS_CONTACT_CHANNELS->save(array("id"=>$item["id_rel"]),array('processed' => $this->now));
                $i+=1;
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$this->table,
                "processed"=>$i,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function bufferIn($channel){
        try {
            /*No get external data by this way!*/
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$this->table,
                "processed"=>0,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    private function cUrlRestful($url,$headers,$post,$fields=null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, $post);
        if ($headers!=null){curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);}
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $jsonResponse = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        $response = $jsonResponse;
        return $response;
    }
}
