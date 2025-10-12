<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Email extends MY_Model {
    public $time_limit="-1 hours";
    /*Zimbra data for smtp*/
    public $email_accountprefix="bufferout";
    public $email_domainsufix="@credipaz.com";
    public $email_description="Credipaz";
    public $email_server="zimbra.credipaz.com";
    public $email_server_user="zimbra";
    public $email_server_password="ES.calOn33";
    /*3º party API provider*/
    public $api_key="";
    public $api_url="";
    public $api_from="";
    public $api_reply="";
    public $provider="envialosimple";

    public function __construct()
    {
        parent::__construct();
    }
    public function bufferOut($items){
        try {
            $i=0;
            $id_from=0;
            $id_maillist=0;
            $id_campaign=0;
            $html="";
            $plain="";
            $name="";
            $subject="";

            $REL_THREADS_TARGETS_CONTACT_CHANNELS=$this->createModel(MOD_CHANNELS,"Rel_threads_targets_contact_channels","Rel_threads_targets_contact_channels");
            $CONTACT_CHANNELS=$this->createModel(MOD_CHANNELS,"Contact_channels","Contact_channels");
            foreach($items["data"] as $item){
                $html=$item["body"];
                $plain=$item["message"];
                if($plain==""){$plain=html2text($html);}
                $name=$item["subject"];
                $subject=$item["subject"];
                //Initializes sending!
                if ($i==0) {
                    $cc=$CONTACT_CHANNELS->get(array("where"=>"id=".$item["id_contact_channel"]));
                    if($cc["totalrecords"]!=0) {
                        $this->api_reply=$cc["data"][0]["username"];
                        $this->api_key=$cc["data"][0]["api_key"];
                        $this->api_url=$cc["data"][0]["send_endpoint"];
                        $ret=$this->createAdminEmails($this->email_description,$this->api_reply);
                        if($ret["status"]=="ERROR") {throw new Exception("From: ".$ret["message"]);}
                        $id_from=$ret["id"];
                    }
                    //Creates the emails account in zimbra for send via EnvialSimple!
                    $pwd = bin2hex(openssl_random_pseudo_bytes(4));
                    $email_from=($this->email_accountprefix.".".$item["id_thread"].$this->email_domainsufix);
                    $command='zmprov ca '.$email_from.' '.$pwd.' displayName "'.$this->email_description.'"';
                    $params[]=$this->email_server;
                    $this->load->library('V_ssh2',$params,'V_ssh2');
                    $this->V_ssh2->Execute($this->email_server_user, $this->email_server_password, $command);
                    $ret=$this->createMailList($name,$item["id_thread"]);
                    if($ret["status"]=="ERROR") {throw new Exception($ret["message"]." ".$ret["url"]);}
                    $id_maillist=$ret["id"];
                    /*Add CONTACTS to MAILLIST*/
                    if (filter_var($item["email"], FILTER_VALIDATE_EMAIL)) {
                        $ret=$this->createMailListItem($id_maillist,$item);
                        if($ret["status"]=="ERROR") {throw new Exception($ret["message"]." id_maillist: ".$id_maillist." ".$ret["url"]);}
                    }
                }
                $REL_THREADS_TARGETS_CONTACT_CHANNELS->save(array("id"=>$item["id_rel"]),array('processed' => $this->now));
                $i+=1;
            }
            if($id_maillist!=0) {
               $ret=$this->createCampaign($id_maillist,$name,$subject,$id_from);
               if($ret["status"]=="ERROR") {throw new Exception($ret["message"]);}
               $id_campaign=$ret["id"];
               $ret=$this->contentCampaign($id_campaign, $html, $plain);
               if($ret["status"]=="ERROR") {throw new Exception($ret["message"]." ".$ret["url"]);}
               $ret=$this->sendCampaign($id_campaign);
               if($ret["status"]=="ERROR") {throw new Exception($ret["message"]." ".$ret["url"]);}
               
               /*Update remaining emails in third party API provider*/
               $ret=$this->statusEmails();
               if($ret["status"]=="ERROR") {throw new Exception($ret["message"]);}
               $available=array("available"=>((int)$ret["message"]-$i));
               $ccupd=$CONTACT_CHANNELS->get(array("where"=>"imap_status='ALL'"));
               foreach($ccupd["data"] as $row){
                  $CONTACT_CHANNELS->save(array("id"=>$row["id"]),array("structure"=>json_encode($available)));
               }
            } 
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
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
            $i=0;
            $BUFFER_IN=$this->createModel(MOD_CHANNELS,"Buffer_in","Buffer_in");
            $CONTACT_CHANNELS=$this->createModel(MOD_CHANNELS,"contact_channels","contact_channels");
            $contact_channels=$CONTACT_CHANNELS->get(array("where"=>"id_type_contact_channel=3"));
            foreach($contact_channels["data"] as $channel) {
                $connection = imap_open(($channel["imap_address"].$channel["imap_inbox"]),$channel["username"],$channel["password"]);
                $date = date("d M Y", strToTime($this->time_limit));
                //$messagestatus = $channel["imap_status"];
                $messagestatus = "SINCE \"$date\"";
                $emails=imap_search($connection,$messagestatus,SE_UID);
                if($emails) {
                    rsort($emails);
                    foreach($emails as $email_number) {
                        $header=imap_fetch_overview($connection,$email_number,FT_UID);
                        $code=imapUtf8Fix($header[0]->uid).imapUtf8Fix($header[0]->msgno);
                        $verified=date('Y-m-d H:i:s',strtotime(imapUtf8Fix($header[0]->date)));
                        $message=imap_fetchbody($connection,$email_number,1.1,FT_UID);
                        if ($message == "") {$message = imap_fetchbody($connection, $email_number, 1,FT_UID);}
                        if ($message == "") {$message = imap_fetchbody($connection, $email_number, 0,FT_UID);}
                        if (isBase64Encoded($message)===true) {$message=base64_decode($message);}
                        if ($message!="") {
                            $record=$BUFFER_IN->get(array("where"=>("code='".$code."'")));
                            $id=0;
                            if($record["totalrecords"]==0) {
                                $fields=array(
                                    "code"=>$code,
                                    "description"=>("AUTOBUFFERING:".$this->now),
                                    "created" => $this->now,
                                    "offline" => null,
                                    "fum" => $this->now,
                                    "verified"=>$verified,
                                    "username"=>$channel["username"],
                                    "subject"=>imapUtf8Fix($header[0]->subject),
                                    "body"=> quoted_printable_decode($message),
                                    "from"=>imapUtf8Fix($header[0]->from),
                                    "to"=>imapUtf8Fix($header[0]->to),
                                    "id_contact_channel"=>$channel["id"],
                                    "tag_processed"=>$message,
                                    "grouped"=>null,
                                    "id_client_credipaz"=>null,
                                    "dirty"=>0
                                );
                                $fields["subject"] = mb_convert_encoding($fields["subject"], 'UTF-8', 'UTF-8');
                                $fields["body"] = mb_convert_encoding($fields["body"], 'UTF-8', 'UTF-8');

                            } else {
                                $id=$record["data"][0]["id"];
                                $fields=array("body"=>mb_convert_encoding(quoted_printable_decode($message), 'UTF-8', 'UTF-8'));
                            }
                            $BUFFER_IN->save(array("id"=>$id),$fields);
                            $i+=1;
                        }
                    }
                }
                imap_expunge($connection);
                imap_close($connection);
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
    public function send($values){
        try {
		    if (!isset($values["priority"])){$values["priority"]=0;}
            return $this->emLayerExecuteWS("send","send","",$values["subject"],$values["body"],$values["from"],$values["alias_from"],$values["replyTo"],$values["to"],$values["cc"],$values["bcc"],$values["attachments"],$values["names"],$values["priority"]);
        } catch(Exception $e) {
            return array("status"=>"ERROR","message"=>$e->getMessage());
        }
    }
    public function directEmail($values){
       try {
            if(!isset($values["priority"])){$values["priority"]=0;}
            if(!isset($values["names"])){$values["names"]=null;}
            if(!isset($values["attachs"])){$values["attachs"]=null;}

            //$values["from"]="lansweeper@credipaz.com";

            if (isset($values["id_operator_task"])) {
                $REL_THREADS_TARGETS_CONTACT_CHANNELS=$this->createModel(MOD_CHANNELS,"Rel_threads_targets_contact_channels","Rel_threads_targets_contact_channels");
                $OPERATORS_TASKS=$this->createModel(MOD_CRM,"Operators_tasks","Operators_tasks");
                $record=$OPERATORS_TASKS->get(array("where"=>("id=".$values["id_operator_task"])));
                $values["from"]=$record["data"][0]["username"];
                $values["from"]=str_replace('bufferin.','',$values["from"]);
                $arrData=array(
                    "id_thread_target"=>null,
                    "id_thread"=>2,
                    "id_contact_channel"=>$record["data"][0]["id_contact_channel"],
                    "processed"=>$this->now,
                    "username"=>$values["email"],
                    "subject"=>$values["subject"],
                    "body"=>$values["body"],
                    "from"=>$values["from"],
                    "to"=>$values["email"],
					"priority"=>$values["priority"]
                );
                $REL_THREADS_TARGETS_CONTACT_CHANNELS->save(array("id"=>0),$arrData);
            }
            if ($values["email"]!="") {
                $params=array(
                    "from"=>$values["from"],
                    "alias_from"=>$values["from"],
                    "to"=>$values["email"],
                    "cc"=>"",
                    "bcc"=>"",
                    "subject"=>$values["subject"],
                    "body"=>$values["body"],
                    "names"=>$values["names"],
					"priority"=>$values["priority"],
                    "attachments"=>$values["attachs"]
                );
            $ret=$this->send($params);
            }
            return array("status"=>"OK","message"=>serialize($ret)." ¡La respuesta ha sido enviada!");
        } catch(Exception $e) {
            return array("status"=>"ERROR","message"=>$e->getMessage());
        }
    }
	public function alertaDinamica($page){
	   $targets="";
	   $view="";
	   $response=null;
        $data = null;
	   $md5=md5(date(FORMAT_DATE_TOKENJWT));
	   $message="No se ha enviado el correo con la alerta";
       //$script=explode("-",$page["md5"]);
	   //switch($script[0]) {
	   //   case md5("CheckIntranet"):
		     $targets="afleischer@credipaz.com,daniel@gruponeodata.com,";
			 $view="alertCheckIntranet";
	   //    break;
	   //}
	   //if ($md5==$script[1] and $targets!=""){
            $params=array(
				"from"=>"intranet@mediya.com.ar",
				"alias_from"=>lang('msg_internal_alerts'),
				"to"=>$targets,
                "cc"=>"",
                "bcc"=>"",
				"subject"=>lang('msg_'.$view),
				"body"=>$this->load->view((MOD_EMAIL.'/templates/'.$view),$data, true)
			);
			$response=$this->send($params);
    	    $message="Correo con alerta enviado";
	   //}
	   return array("status"=>"OK","message"=>$message,"response"=>$response);
	}
    private function statusEmails(){
        $params = array();
        $params['APIKey']=$this->api_key;
        $url=($this->api_url.'/administrator/status?'.http_build_query($params));
        $response=cUrl($url);
        $ok = isset($response['root']['ajaxResponse']['success']);
        $available=$response['root']['ajaxResponse']['credits']['availableCredits'];
        if($ok) {
           return array("status"=>"OK","message"=>$available,"response"=>$response);
        } else {
           return array("status"=>"ERROR","message"=>"No se pudo acceder al status de la cuenta");
        }
    }
    private function createAdminEmails($name, $emailAddress){
        try {
            $id=0;
            $ret=$this->listAdminEmails();
            if($ret["status"]=="ERROR") {throw new Exception($ret["message"]);}
            $emailsAdmin=$ret["message"];
            foreach($emailsAdmin as $item) {
               if ($item["email"]==$emailAddress) {
                  $id=$item["id"];
                  return array("status"=>"OK","message"=>$name,"id"=>$id);
               }
            }
            $params = array();
            $params['APIKey']=$this->api_key;
            $params['Name']=$name;
            $params['EmailAddress']=$emailAddress;
            $url=($this->api_url.'/administratoremail/edit?'.http_build_query($params));
            $response = cUrl($url);
            $id = $response['root']['ajaxResponse']['email']['EmailID'];
            $ok = isset($response['root']['ajaxResponse']['success']);
            if($ok) {
               return array("status"=>"OK","message"=>$name,"id"=>$id,"response"=>$response);
            } else {
               return array("status"=>"ERROR","message"=>"No se pudo crear el email URL: ".$url);
            }
        } catch(Exception $e){
           return array("status"=>"ERROR","message"=>$e->getMessage());
        }
    }
    private function listAdminEmails(){
        $params = array();
        $params['APIKey']=$this->api_key;
        $url=($this->api_url.'/administratoremail/list?'.http_build_query($params));
        $response = cUrl($url);
        $ok = isset($response['root']['ajaxResponse']['success']);
        $emailsAdmin=[];
        foreach($response['root']['ajaxResponse']['list']['item'] as $item){
           $emailsAdmin[]=array("email"=>$item["EmailAddress"],"id"=>$item["EmailID"]);
        }
        if($ok) {
           return array("status"=>"OK","message"=>$emailsAdmin,"response"=>$response);
        } else {
           return array("status"=>"ERROR","message"=>"No se pudo acceder la lista de emails de administración URL: ".$url);
        }
    }
    private function createMailList($name,$id_thread){
        $params = array();
        $params['APIKey']=$this->api_key;
        $params['MailListName']=$name;
        $url=($this->api_url.'/maillist/edit?'.http_build_query($params));
        $response = cUrl($url);
        $id = $response['root']['ajaxResponse']['maillist']['MailListID'];
        $name = $response['root']['ajaxResponse']['maillist']['MailListName'];
        $ok = isset($response['root']['ajaxResponse']['success']);
        if($ok) {
           return array("status"=>"OK","message"=>$name,"id"=>$id,"response"=>$response);
        } else {
           return array("status"=>"ERROR","url"=>$url,"message"=>"No se pudo crear la mail list (1)");
        }
    }
    private function createMailListItem($id_maillist,$row){
        $params = array();
        $params['APIKey']=$this->api_key;
        $params['MailListID']=$id_maillist;
        $params['Email']=$row["email"];
        $params['CustomField1']=$row["email"];
        $params['CustomField2']=$row["email"];
        $url=($this->api_url.'/member/edit?'.http_build_query($params));
        $response=cUrl($url);
        $id=$response['root']['ajaxResponse']['member']['MemberID'];
        $email=$response['root']['ajaxResponse']['member']['Email'];
        $ok=isset($response['root']['ajaxResponse']['success']);
        if($ok) {
           return array("status"=>"OK","message"=>$email,"id"=>$id,"response"=>$response);
        } else {
           return array("status"=>"ERROR","url"=>$url,"message"=>"No se pudo crear el mail list item (0)","response"=>$response);
        }
    }
    private function createCampaign($id_maillist,$name,$subject,$id_from){
        $params = array();
        $params['APIKey']=(string)$this->api_key;
        $params['CampaignName']=(string)$name;
        $params['CampaignSubject']=(string)$subject;
        $params['MailListsIds']=array((string)$id_maillist);
        $params['FromID']=(string)$id_from;
        $params['ReplyToID']=(string)$this->api_reply;
        $params['TrackLinkClicks']='1';
        $params['TrackReads']='1';
        $params['TrackAnalitics']='1';
        $params['SendStateReport']='';
        $params['AddToPublicArchive']='1';
        $params['ScheduleCampaign']='1';
        $params['SendNow']='1';
        $params['SendDate']="2040-01-01 00:00:00";
        $url=($this->api_url.'/campaign/save?'.http_build_query($params));
        $response = cUrl($url);
        $ok = isset($response['root']['ajaxResponse']['success']);
        $err="Error no determinado";
        if($ok){
           $id = $response['root']['ajaxResponse']['campaign']['CampaignID'];
           $name = $response['root']['ajaxResponse']['campaign']['Name'];
           return array("status"=>"OK","id"=>$id,"name"=>$name);
        } else {
           switch (-1) {
              case $response['root']['ajaxResponse']['campaign']['integrity']['subject']:
                 $err="Debes asignar un Asunto";
                 break;
              case $response['root']['ajaxResponse']['campaign']['integrity']['schedule']:
                 $err="Debes determinar una configuracion de envio";
                 break;
              case $response['root']['ajaxResponse']['campaign']['integrity']['content']:
                 $err="Debes determinar un contenido de la campaña";
                 break;
              case $response['root']['ajaxResponse']['campaign']['integrity']['replyTo']:
                 $err="Debes determinar un remitente 'reply to'";
                 break;
              case $response['root']['ajaxResponse']['campaign']['integrity']['fromTo']:
                 $err="Debes determinar un origen 'from'";
                 break;
              case $response['root']['ajaxResponse']['campaign']['integrity']['maillist']:
                 $err="Debes determinar una o varias listas de contactos";
                 break;
           }
           return array("status"=>"ERROR","message"=>$err);
        }
    }
    private function contentCampaign($id, $html, $plain){
        try {
            $params = array();
            $params['APIKey']=$this->api_key;
            $params['CampaignID']=$id;
            $unsuscribeLink="<br/><br/>";
            $unsuscribeLink.="Haga click <a href='%UnSubscribe%'>aquí­</a> para desuscribirse de esta lista de correo.";
            $html=str_replace("\"./attached/threads/","\"".$this->content_server,$html);
            $html=str_replace("https://wproxy.credipaz.com:50555/attached/threads/","\"".$this->content_server,$html);
            $params['HTML']=($html.$unsuscribeLink);
            $params['PlainText']=($plain." | ".$unsuscribeLink);
            $url=($this->api_url.'/content/edit?'.http_build_query($params));
            $response=cUrl($url);
            $ok=isset($response['root']['ajaxResponse']['success']);
            if($ok) {
               return array("status"=>"OK","message"=>"Cuerpo de la campaña creado","id"=>$id,"response"=>$response);
            } else {
               return array("status"=>"ERROR","url"=>$url,"message"=>"No se pudo asignar el contenido del email a la campaña");
            }
        } catch(Exception $e) {
           return array("status"=>"ERROR","message"=>$e->getMessage());
        }
    }
    private function sendCampaign($id){
        $params = array();
        $params['APIKey']=$this->api_key;
        $params['CampaignID']=$id;
        $url=($this->api_url.'/campaign/resume?'.http_build_query($params));
        $response=cUrl($url);
        $ok=isset($response['root']['ajaxResponse']['success']);
        if($ok) {
           return array("status"=>"OK","message"=>"Campaña enviada. Para ver el avance y el resultado de la campaña ver el modulo de 'reportes'","id"=>$id,"response"=>$response);
        } else {
           return array("status"=>"ERROR","url"=>$url,"message"=>"Error al enviar la campaña");
        }
    }
}
