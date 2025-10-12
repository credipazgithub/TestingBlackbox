<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Telephony extends MY_Model {
    private $url_api="http://172.16.41.210/neoapi/webservice.asmx?wsdl"; //CLOUD 
    private $id_contact_channel=10;

    public function __construct()
    {
        parent::__construct();
    }

    function Login($values){
       $return=array();
       try {
            if(!isset($values["telephony_device"])){throw new Exception(lang('error_5701'),5701);}
            if(!isset($values["telephony_username"])){throw new Exception(lang('error_5702'),5702);}
            if(!isset($values["telephony_password"])){throw new Exception(lang('error_5703'),5703);}
            if(!isset($values["telephony_campaign"])){throw new Exception(lang('error_5704'),5704);}
            $USERS_SIP=$this->createModel(MOD_TELEPHONY,"Users_sip","Users_sip");
            $USERS_SIP->register($values);
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->url_api,$options);
            $soapClient->__setLocation($this->url_api);
            $soapParams=array("DEVICE" => $values["telephony_device"],"USUARIO" => $values["telephony_username"],"CLAVE" => $values["telephony_password"]);
            $response=$soapClient->__soapCall("Login", array($soapParams));
            $return=array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_telephony_open'),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$response,
                "rawfiltered"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "raw"=>$soapClient->__getLastResponse(),
                "rHeaders"=>$soapClient->__getLastResponseHeaders()
            );
           $this->saveLog($values,"Login","","login",0);
       } catch(Exception $e){
            if(strpos($e->getMessage(),"not free")) {
                $return=array("code"=>"2000",
                    "status"=>"OK",
                    "message"=>lang('msg_telephony_still'),
                    "data"=>"",
                    "rawfiltered"=>"",
                    "raw"=>"",
                    "rHeaders"=>"");
            } else {
               $return=logError($e,__METHOD__ );
            }
       }
       if ($return["status"]=="OK"){
           $values["campaign_mode"]="login";
           $return=$this->Login_Campaign($values);
       }
       return $return;
    }
    function Login_Campaign($values){
       try {
            if(!isset($values["telephony_username"])){throw new Exception(lang('error_5702'),5702);}
            if(!isset($values["telephony_campaign"])){throw new Exception(lang('error_5704'),5704);}
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->url_api,$options);
            $soapClient->__setLocation($this->url_api);
            $soapParams=array("USUARIO" => $values["telephony_username"],"CAMPAÑA" => $values["telephony_campaign"]);
            $response = $soapClient->__soapCall("Login_Campaign", array($soapParams));
            $this->saveLog($values,"Campaign login",$values["telephony_campaign"],"campaña",0);
            //$soapParams=array("USUARIO" => $values["telephony_username"]);
            //$response = $soapClient->__soapCall("Logout_Campaign", array($soapParams));
            //$this->saveLog($values,"Campaign logout",$values["telephony_campaign"],"campaña",0);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_telephony_open'),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$response,
                "rawfiltered"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "raw"=>$soapClient->__getLastResponse(),
                "rHeaders"=>$soapClient->__getLastResponseHeaders()
            );
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }
    function Pause($values){
       try {
            if(!isset($values["telephony_username"])){throw new Exception(lang('error_5702'),5702);}
            if(!isset($values["telephony_type_pause"])){throw new Exception(lang('error_5705'),5705);}
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->url_api,$options);
            $soapClient->__setLocation($this->url_api);
            $soapParams=array("USUARIO" => $values["telephony_username"],"SUBTIPO_DESCANSO" => $values["telephony_type_pause"]);
            $response = $soapClient->__soapCall("Pause", array($soapParams));
            $TYPE_PAUSE=$this->createModel(MOD_TELEPHONY,"Type_pause","Type_pause");
            $pause=$TYPE_PAUSE->get(array("where"=>"id='".$values["telephony_type_pause"]."'"));
            $this->saveLog($values,"Pause",$pause["data"][0]["id"],$pause["data"][0]["description"],0);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_telephony_pause').".  ".$pause["data"][0]["description"]."'.",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$response,
                "rawfiltered"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "raw"=>$soapClient->__getLastResponse(),
                "rHeaders"=>$soapClient->__getLastResponseHeaders(),
            );
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }
    function Unpause($values){
       try {
            if(!isset($values["telephony_username"])){throw new Exception(lang('error_5702'),5702);}
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->url_api,$options);
            $soapClient->__setLocation($this->url_api);
            $soapParams=array("USUARIO" => $values["telephony_username"]);
            $response = $soapClient->__soapCall("Unpause", array($soapParams));
            $LOG_SIP=$this->createModel(MOD_TELEPHONY,"Log_sip","Log_sip");
            $log=$LOG_SIP->get(array("where"=>"username='".$values["username_active"]."' AND action='Pause'","order"=>"created DESC"));
            $this->saveLog($values,"Unpause",$log["data"][0]["action_tag"],$log["data"][0]["action_detail"],$log["data"][0]["duration"]);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_telephony_unpause'),
                "data"=>$response,
                "rawfiltered"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "raw"=>$soapClient->__getLastResponse(),
                "rHeaders"=>$soapClient->__getLastResponseHeaders());
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }
    function Dial($values){
       try {
            if(!isset($values["telephony_username"])){throw new Exception(lang('error_5702'),5702);}
            if(!isset($values["telephony_telephone"])){throw new Exception(lang('error_5706'),5706);}
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->url_api,$options);
            $soapClient->__setLocation($this->url_api);
            $soapParams=array("USUARIO" => $values["telephony_username"],"TELEFONO" => $values["telephony_telephone"]);
            $response = $soapClient->__soapCall("Dial", array($soapParams));
            $this->saveLog($values,"Dial",$values["telephony_telephone"],"teléfono",0);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_telephony_call_init'),
                "compressed"=>false,
                "data"=>$response,
                "rawfiltered"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "raw"=>$soapClient->__getLastResponse(),
                "rHeaders"=>$soapClient->__getLastResponseHeaders());
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }
    function BlindTransfer($values){
       try {
            if(!isset($values["telephony_username"])){throw new Exception(lang('error_5702'),5702);}
            if(!isset($values["telephony_extension"])){throw new Exception(lang('error_5707'),5707);}
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->url_api,$options);
            $soapClient->__setLocation($this->url_api);
            $soapParams=array("USUARIO" => $values["telephony_username"],"EXTENSION" => $values["telephony_extension"]);
            $response = $soapClient->__soapCall("BlindTransfer", array($soapParams));
            $this->saveLog($values,"BlindTransfer",$values["telephony_extension"],"transferencia",0);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_telephony_transfer')." '".$values["telephony_extension"]."'.",
                "data"=>$response,
                "rawfiltered"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "raw"=>$soapClient->__getLastResponse(),
                "rHeaders"=>$soapClient->__getLastResponseHeaders());
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }
    function SendDTMF($values){
       try {
            if(!isset($values["telephony_username"])){throw new Exception(lang('error_5702'),5702);}
            if(!isset($values["telephony_character"])){throw new Exception(lang('error_5708'),5708);}
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->url_api,$options);
            $soapClient->__setLocation($this->url_api);
            $soapParams=array("USUARIO" => $values["telephony_username"],"DIGITOS" => $values["telephony_character"]);
            $response = $soapClient->__soapCall("SendDTMF", array($soapParams));
            $this->saveLog($values,"SendDTMF",$values["telephony_character"],"caracteres",0);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "data"=>$response,
                "rawfiltered"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "raw"=>$soapClient->__getLastResponse(),
                "rHeaders"=>$soapClient->__getLastResponseHeaders());
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }
    function Hangup($values){
       try {
            if(!isset($values["telephony_username"])){throw new Exception(lang('error_5702'),5702);}
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->url_api,$options);
            $soapClient->__setLocation($this->url_api);
            $soapParams=array("USUARIO" => $values["telephony_username"]);
            $response = $soapClient->__soapCall("Hangup", array($soapParams));
            $LOG_SIP=$this->createModel(MOD_TELEPHONY,"Log_sip","Log_sip");
            $log=$LOG_SIP->get(array("where"=>"username='".$values["username_active"]."' AND action='Dial'","order"=>"created DESC"));
            $this->saveLog($values,"Hangup",$log["data"][0]["action_tag"],$log["data"][0]["action_detail"],$log["data"][0]["duration"]);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_telephony_call_end'),
                "data"=>$response,
                "rawfiltered"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "raw"=>$soapClient->__getLastResponse(),
                "rHeaders"=>$soapClient->__getLastResponseHeaders());
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }
    function Logout($values){
       try {
            if(!isset($values["telephony_username"])){throw new Exception(lang('error_5702'),5702);}
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->url_api,$options);
            $soapClient->__setLocation($this->url_api);
            $soapParams=array("USUARIO" => $values["telephony_username"]);
            $response = $soapClient->__soapCall("Logout", array($soapParams));
            $LOG_SIP=$this->createModel(MOD_TELEPHONY,"Log_sip","Log_sip");
            $log=$LOG_SIP->get(array("where"=>"username='".$values["username_active"]."' AND action='Login'","order"=>"created DESC"));
            $this->saveLog($values,"Logout",$log["data"][0]["action_tag"],$log["data"][0]["action_detail"],$log["data"][0]["duration"]);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>lang('msg_telephony_close'),
                "data"=>$response,
                "rawfiltered"=>htmlspecialchars($soapClient->__getLastResponse(), ENT_QUOTES),
                "raw"=>$soapClient->__getLastResponse(),
                "rHeaders"=>$soapClient->__getLastResponseHeaders());
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }

    function PositionGeneral($values){
        try {
            $ret=array();
            $USERS_SIP=$this->createModel(MOD_TELEPHONY,"users_sip","users_sip");
            $users=$USERS_SIP->get(array("order"=>"username ASC"));
            foreach($users["data"] as $row) {
                $item=$this->Position(array("request_mode"=>"ALL","telephony_username"=>$row["sip_username"]));
                if ($item["status"]=="OK"){array_push($ret,$row["sip_username"]." [".$row["username"]."] ".$item["position"]);}
            }
            return $ret;
        } catch(Exception$e){
            return logError($e,__METHOD__ );
        }
    }
    function Position($values){
       try {
            $id_ot=0;
            $action="";
            $TYPE_PAUSE=$this->createModel(MOD_TELEPHONY,"Type_pause","Type_pause");
            $LOG_SIP=$this->createModel(MOD_TELEPHONY,"Log_sip","Log_sip");
            $log=$LOG_SIP->get(array("where"=>"sip_username='".$values["telephony_username"]."'","order"=>"created DESC"));
            
            $data=array();
            if(!isset($values["request_mode"])){$values["request_mode"]="SINGLE";}
            if(!isset($values["telephony_username"])){throw new Exception(lang('error_5700'),5700);}
            $options=array('trace'=>true,'exceptions'=>true,'cache_wsdl' => WSDL_CACHE_NONE);
            $soapClient = new SoapClient($this->url_api,$options);
            $soapClient->__setLocation($this->url_api);
            $soapParams=array("USUARIO" => $values["telephony_username"]);
            $response = $soapClient->__soapCall("Position", array($soapParams));
            $status_raw=$response->PositionResult;
            $rows=explode("|",$status_raw);
            foreach($rows as $row){
                $row=explode("=",$row);
                if(isset($row[1])) {
                    if(strpos($row[1]," ")) {
                        $dt=explode(" ",$row[1]);
                        $date=explode("/",$dt[0]);
                    }
                }
                switch($row[0]){
                    case "SUBTIPO_DESCANSO":
                        $pause=$TYPE_PAUSE->get(array("where"=>"id='".$row[1]."'"));
                        $data["PAUSED"]=$row[1];
                        $row[1]="";
                        if(isset($pause["data"][0]["description"])) {$row[1]=$pause["data"][0]["description"];}
                        break;
                    case "INICIO_LOGIN":
                        $limit=($date[2]."-".$date[0]."-".$date[1]." ".$dt[1]);
                        $diff=dateDifference($limit,date("Y-m-d h:i:s"),'%Hh:%im:%ss');
                        $data["ELAPSED_LOGIN"]=$diff;
                        break;
                    case "TIEMPO_LLAMADA":
                        $limit=($date[2]."-".$date[0]."-".$date[1]." ".$dt[1]);
                        $diff=dateDifference($limit,date("Y-m-d h:i:s"),'%Hh:%im:%ss');
                        $data["ELAPSED_LLAMADA"]=$diff;
                        break;
                    case "INICIO_DESCANSO":
                        $limit=($date[2]."-".$date[0]."-".$date[1]." ".$dt[1]);
                        $diff=dateDifference($limit,date("Y-m-d h:i:s"),'%Hh:%im:%ss');
                        $data["ELAPSED_DESCANSO"]=$diff;
                        break;
                    case "SUB_ESTADO":
                        if ($row[1]=="AGENT") {$data["CALLING"]=1;}else{$data["CALLING"]=0;}
                        break;
                    case "GRABANDO":
                        if ($row[1]=="NO") {$row[1]=0;}else{$row[1]=1;}
                        break;
                }
                if(!isset($row[1])) {$row[1]="";}
                $data[$row[0]]=$row[1];
            }

            /*Auto sincro with status position*/
            /*Para controlar si cortan por fuera de la interface*/
            /*Si no hay llamada activa, pero el log tiene Dial como última entrada*/
            if ($data["CALLING"]==0 and $log["data"][0]["action"]=="Dial"){$this->saveLog($values,"Hangup",$data["TELEFONO"],"teléfono/off system",$log["data"][0]["duration"]);}
            /*Para controlar si llaman por fuera de la interface*/
            /*Si hay llamada activa, pero el log NO tiene Dial como última entrada*/
            if ($data["CALLING"]==1 and $log["data"][0]["action"]!="Dial"){
               switch($log["data"][0]["action"]) {
                  case "Login": // ingresar Dial directamente
                  case "Unpause": // ingresar Dial directamente
                     break;
                  case "Hangup": // ingresar Dial directamente
                     break;
                  case "Logout": // ingresar Login y luego Dial
                     $this->saveLog($values,"Login",$data["SAL_CAMPAÑA_DEFAULT"],"login/off system",0);
                     $this->saveLog($values,"Campaign",$data["SAL_CAMPAÑA_DEFAULT"],"campaña/off system",0);
                     break;
                  case "Pause": // ingresar Unpause y luego Dial
                     $this->saveLog($values,"Unpause",$log["data"][0]["action_tag"],($log["data"][0]["action_detail"]."/off system"),$log["data"][0]["duration"]);
                     break;
               }
               $this->saveLog($values,"Dial",$data["TELEFONO"],"teléfono/off system","");

               if ($values["request_mode"]=="SINGLE"){
                  /*
                     Identificar por el troncal, linea, o informacion del IVR, cual es el canald e entrada de la llamada, para generar la tarea en el modulo correcto! 
                  */
                  $module="mod_crm";
                  /*
                     Try to resolve calling user, for save id_client_credipaz!
                  */
                  $id_client_credipaz=null;
                  switch($module) {
                     case "mod_crm":
                        $OPERATORS_TASKS=$this->createModel(MOD_CRM,"Operators_tasks","Operators_tasks");
                        $params=array(
                           "code"=>"llamada-telefonica",
                           "description"=>lang('msg_telephony_call')." ".$data["DIRECCION"],
                           "subject"=>lang('msg_telephony_call')." ".$data["DIRECCION"],
                           "body"=>lang('msg_telephony_body'),
                           "id_contact_channel"=>$this->id_contact_channel, // Telefonia CC
                           "id_operator"=>$values["id_user_active"],
                           "id_client_credipaz"=>$id_client_credipaz,
                        );
                        break;
                     case "mod_telemedicina":
                        $action="check-paycode";
                        ///*Try to resolve PAY CODE with the IVR!*/
                        //$code_payment="";
                        //$OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
                        //$params=array(
                        //   "code"=>$code_payment, // CODIGO DE PAGO! DEBERIA VENIR EN EL ivr!
                        //   "description"=>lang('msg_telephony_call')." ".$data["DIRECCION"],
                        //   "subject"=>lang('msg_telephony_call')." ".$data["DIRECCION"],
                        //   "body"=>lang('msg_telephony_body'),
                        //   "id_operator"=>$values["id_user_active"],
                        //   "id_client_credipaz"=>$id_client_credipaz,
                        //);
                        break;
                  }
                  $saved=$OPERATORS_TASKS->save(array("id"=>0),$params);
                  $id_ot=$saved["data"]["id"];
               }
               /*raw STATUS*/
               //DEVICE=SIP/Credipaz0032|IP=172.16.17.54|PORT=48470|STATUS=OK|SERVER=NEO1|USUARIO=1002|INICIO_LOGIN=28/05/2019 02:30:34 p.m.|SAL_CAMPAÑA_DEFAULT=3|INICIO_LOGIN_CAMPAÑA=28/05/2019 02:30:39 p.m.|CHANNEL=SIP/Credipaz0032-00002666|SUB_ESTADO=AGENT|SUB_ESTADO_ULT=AGENT|RINGING=01/01/0001 12:00:00 a.m.|DIALING=01/01/0001 12:00:00 a.m.|AGENT=01/01/0001 12:00:00 a.m.|TIEMPO_LLAMADA=28/05/2019 02:57:43 p.m.|SUBTIPO_DESCANSO=0|INICIO_DESCANSO=01/01/2000 12:00:00 a.m.|TIEMPO_DESCANSO=01/01/2000 12:00:00 a.m.|ESTADO_CRM=Available|INICIO_CRM=01/01/2000 12:00:00 a.m.|CAMPAÑA=3|CAMPAÑA_ULT=3|COLA=0|COLA_ULT=0|DNIS=|ANI=|TELEFONO=0348715366797|ANI_TELEFONO_ULT=0348715366797|TIPO_LLAMADA=Manual|TIPO_LLAMADA_ULT=Manual|ORIGEN_LLAMADA=|ORIGEN_LLAMADA_ULT=|DIRECCION=SALIENTE|DIRECCION_ULT=SALIENTE|CRM=0|BASE=0|IDCONTACTO=0|DATA=|CLAVE=|CAMPO_BUSQUEDA=|IDAGENDA=|IDLLAMADA=1159449|IDLLAMADA_ULT=1159449|CONFERENCIA=|GRABANDO=SI|GRABACION=0348715366797-1159449-20190528145718.mp3|TELEFONO_DESVIO=|SAL_TIPO_DISCADOR=Manual|SAL_CRM=0|SAL_BASE=0|CANALES_ASOCIADOS=¦¦SIP/TASA-00002667|
            }
            $log=$LOG_SIP->get(array("where"=>"sip_username='".$values["telephony_username"]."'","order"=>"created DESC"));
            if ($data["CALLING"]==1 and $log["data"][0]["action"]=="Dial" and $values["request_mode"]=="SINGLE" and $log["data"][0]["status_raw"]==""){
                $params=array(
                   "status_raw"=>$status_raw,
                   "id_llamada"=>$data["IDLLAMADA"],
                   "grabacion"=>$data["GRABACION"],
                   "telefono"=>$data["TELEFONO"],
                   "direccion"=>$data["DIRECCION"],
                );
                $LOG_SIP->save(array("id"=>$log["data"][0]["id"]),$params);
            }
            if ($data["CALLING"]==1){
                $message=lang('msg_telephony_active_call')." ".$data["TELEFONO"];
            } else {
                if ((int)$data["PAUSED"]==0){
                   $message=lang('msg_telephony_not_call');
                } else {
                   $message=lang('msg_telephony_pause').": ".$data["SUBTIPO_DESCANSO"].". ".$data["ELAPSED_DESCANSO"];
                }
            }
            $data["LOGGED"]=1;
            //if ($data["DIRECCION"]=="ENTRANTE"){log_message("error", "TELEPHONY LOG ENTRADA: ".json_encode($data,JSON_PRETTY_PRINT));}

            $data["CUSTOM_DATA"]["Estado"]="";
            $data["CUSTOM_DATA"]["nID"]="";
            $data["CUSTOM_DATA"]["sNombre"]="";
            $data["CUSTOM_DATA"]["sLKNacionalidad"]="";
            $data["CUSTOM_DATA"]["nDoc"]="";
            $data["CUSTOM_DATA"]["sLKEstadoCivil"]="";
            $data["CUSTOM_DATA"]["address"]="";
            if ($data["CLAVE"]!="") {
                $sql="SELECT 'Cliente' as Estado,nID,sNombre,sLKNacionalidad,sSexo,nDoc,sLKEstadoCivil,sDomiCalle+' '+sDomiNro+' '+sDomiPisoDpto+' '+sDomiEntre+' '+sDomiBarrio+' '+sLKDomiLocalidad as [address] FROM dbCentral.dbo.wrkClienteTitular WHERE nDoc='".$data["CLAVE"]."'";
                $records=$this->getRecordsAdHoc($sql);
                if(!isset($records["data"][0])){
                   $sql="SELECT 'Prospecto' as Estado,nID,sNombre,sLKNacionalidad,nDoc,sLKEstadoCivil,sDomiCalle+' '+sDomiNro+' '+sDomiPisoDpto+' '+sDomiEntre+' '+sDomiBarrio+' '+sLKDomiLocalidad as [address] FROM dbCentral.dbo.promClienteTitular WHERE nDoc='".$data["CLAVE"]."'";
                   $records=$this->getRecordsAdHoc($sql);
                }
                $data["CUSTOM_DATA"]=$records[0];
            }

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "data"=>$data,
                "position"=>$message,
                "message"=>"",
                "id_ot"=>$id_ot,
                "action"=>$action);
       } catch(Exception $e){
            if(strpos($e->getMessage(),"not logged")) {
               $data["LOGGED"]=0;
               $data["GRABANDO"]=0;
               $data["USUARIO"]=$values["telephony_username"];
               $data["STATUS"]="N/A";
               return array(
                    "code"=>"2000",
                    "status"=>"OK",
                    "data"=>$data,
                    "position"=>"",
                    "message"=>"",
                    "id_ot"=>0,
                    "action"=>$action);
            } else {
               return logError($e,__METHOD__ );
            }
       }
    }
    function LogSip($values){
       try {
            $LOG_SIP=$this->createModel(MOD_TELEPHONY,"Log_sip","Log_sip");
            if(!isset($values["datauser"])){$values["datauser"]="";}
            if(!isset($values["hours"])){$values["hours"]=1;}
            $values["datauser"]=explode(" ",$values["datauser"])[0];
            return $LOG_SIP->get(array("where"=>"sip_username='".$values["datauser"]."' AND created >= DATEADD(HOUR, -".$values["hours"].", GETDATE())","order"=>"created DESC"));
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }

    function Reports($values){
       try {
            $username=implode("','",$values["username"]);
            $title=$values["title"];
            $report=$values["report"];
            $from=$values["from"];
            $to=$values["to"];
            $template="common";
            switch($report){
               case "Logout":
               case "Hangup":
               case "Unpause":
                   $sql="SELECT count(id) as total,sum(duration) as duration,username,sip_username,action FROM ".MOD_TELEPHONY."_log_sip";
                   $sql.=" WHERE created >= '".$from."' AND created <='".$to."' AND action='".$report."' AND username IN ('".$username."')";
                   $sql.=" GROUP BY username,sip_username,action";
                   break;
               case "Entrantes":
                   $template="inout";
                   $sql="SELECT ";
                   $sql.=" (SELECT count(id) as total FROM ".MOD_TELEPHONY."_log_sip as ll WHERE ll.id_llamada=vl.id_llamada) as total,direccion,telefono,username,created";
                   $sql.=" FROM ".MOD_TELEPHONY."_vw_log_sip as vl WHERE action in ('Dial') AND direccion='ENTRANTE' AND status_raw is not null ORDER BY 2,1";
                   break;
               case "Salientes":
                   $template="inout";
                   $sql="SELECT ";
                   $sql.=" (SELECT count(id) as total FROM ".MOD_TELEPHONY."_log_sip as ll WHERE ll.id_llamada=vl.id_llamada) as total,direccion,telefono,username,created";
                   $sql.=" FROM ".MOD_TELEPHONY."_vw_log_sip as vl WHERE action in ('Dial') AND direccion='SALIENTE' AND status_raw is not null ORDER BY 2,1";
                   break;
            }
            $ret=$this->getRecordsAdHoc($sql);
            $data["records"]=$ret;
            $data["title"]=$title;
            $html=$this->load->view(MOD_TELEPHONY."/reports_sip/".$template,$data,true);
            return array("code"=>"2000","status"=>"OK","message"=>compress($this,$html),"compressed"=>true);
       } catch(Exception $e){
            return logError($e,__METHOD__ );
       }
    }

    private function saveLog($values,$action,$action_tag,$action_detail,$duration){
        $LOG_SIP=$this->createModel(MOD_TELEPHONY,"Log_sip","Log_sip");
        if(!isset($values["username_active"])){$values["username_active"]="";}
        if(!isset($values["telephony_device"])){$values["telephony_device"]="";}
        if(!isset($values["telephony_username"])){$values["telephony_username"]="";}
        $fields=array(
            "code"=>null,
            "description"=>null,
            "created"=>$this->now,
            "verified"=>$this->now,
            "fum"=>$this->now,
            "username"=>$values["username_active"],
            "sip_device"=>$values["telephony_device"],
            "sip_username"=>$values["telephony_username"],
            "action"=>$action,
            "action_tag"=>$action_tag,
            "action_detail"=>$action_detail,
            "duration"=>$duration
        );
        return $LOG_SIP->save(array("id"=>0),$fields);
    }
}
