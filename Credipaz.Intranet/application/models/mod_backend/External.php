<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class External extends MY_Model {
    //Desarrollo y testing
    private $cargavirtualTest=array("server"=>"http://10.0.0.80:8088/rtcargavirtual/cargavirtual.asmx?wsdl","username"=>"wscredipaz","password"=>"wscredipaz2019*","idPtoVta"=>"54120");
   
    //Primaria produccion
    private $cargavirtual=array("server"=>"http://172.16.20.1:8080/rtcargavirtual/cargavirtual.asmx?wsdl","username"=>"credipsa","password"=>"credi1806","idPtoVta"=>"96045");
    private $cargavirtual_alt=array("server"=>"http://172.16.30.1:8080/rtcargavirtual/cargavirtual.asmx?wsdl","username"=>"credipsa","password"=>"credi1806","idPtoVta"=>"96045");
    
    public function __construct()
    {
        parent::__construct();
    }

    /*General interface valus for mobile applications */
    public function getIfaceConfiguration($values){
        try {
            switch((int)$values["id_app"]) {
               case 7: // Intranet
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Intranet","Intranet");
                  return $MOBILE->getIfaceConfiguration($values);
               case 2: // Credipaz
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Credipaz","Credipaz");
                  return $MOBILE->getIfaceConfiguration($values);
               case 5: // Club Redondo
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Club_redondo","Club_redondo");
                  return $MOBILE->getIfaceConfiguration($values);
               default:
                  return null;
            }
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    /*First identification with doc number and sex, retrieve names and no more*/
    public function getIdentityInformation($values){
        try {
            switch((int)$values["id_app"]) {
               case 2: // Credipaz
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Credipaz","Credipaz");
                  $r=$MOBILE->firstStepAuth($values);
                  return $r;
               case 5: // Club Redondo
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Club_redondo","Club_redondo");
                  $r=$MOBILE->firstStepAuth($values);
                  return $r;
                default:
                  return null;
            }
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    /*Second identification with additional data, retrieve data structure for interface interaction*/
    public function getUserInformation($values){
        try {
            switch((int)$values["id_app"]) {
               case 2: // Credipaz
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Credipaz","Credipaz");
                  $r=$MOBILE->secondStepAuth($values);
                    return $r;
               case 5: // Club Redondo
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Club_redondo","Club_redondo");
                    $r=$MOBILE->secondStepAuth($values);
                    return $r;
               default:
                  return null;
            }
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    /*Process new user save from mobile applications */
    public function saveNewUser($values){
        try {
            switch((int)$values["id_app"]) {
               case 2: // Credipaz
                  $values["id_type_user"]=80;
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Credipaz","Credipaz");
                  return $MOBILE->saveNewUser($values);
               case 5: // Club Redondo
                  $values["id_type_user"]=82;
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Club_redondo","Club_redondo");
                  return $MOBILE->saveNewUser($values);
               default:
                  return null;
            }
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    /*Process for reset user due to forgot password */
    public function forgotPassword($values) {
        try {
            switch((int)$values["id_app"]) {
               case 2: // Credipaz
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Credipaz","Credipaz");
                  return $MOBILE->forgotPassword($values);
               case 5: // Club Redondo
                  $MOBILE=$this->createModel(MOD_MOBILE_APPS,"Club_redondo","Club_redondo");
                  return $MOBILE->forgotPassword($values);
               default:
                  return null;
            }
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    /*Entry point for most App Mobile calls*/
    public function applicationMobileFunction($values){
        try {
            $id_contact_channel=8;
            $save=true;
            $forcedData=false;
            $mode="MESSAGE";
            $ticket="";
            if (!isset($values["api_key"])){$values["api_key"]="";}
            if (!isset($values["id_credipaz"])){$values["id_credipaz"]=null;}
            if (!isset($values["cuenta"])){$values["cuenta"]=null;}
            $id_credipaz=$values["id_credipaz"];
            if ($id_credipaz==0){$id_credipaz=null;}
            $values["id_credipaz"]=$id_credipaz;
            $id=$values["id_user_active"];
            if($id==""){$id=0;}
            $api_key=$values["api_key"];
            $function=$values["data_function"];
            $subject="";
            $result="¡Nos contactaremos a la brevedad para que puedas";
            $additional=array();
            switch($function) {
                case "arrepentimiento-tarjeta":
                    $save=false;
					//$values["bajaapenom"]
					//$values["bajasexo"]
					//$values["bajadni"]
					//$values["bajaemail"]
					//$values["bajacelular"]
					//$values["bajamotivo"]
                    $TARGESTIONBAJA=$this->createModel(MOD_DBCENTRAL,"tarGestionBaja","tarGestionBaja");
                    $params=array("id"=>"0","codigo_tarjeta"=>$values["codigo_tarjeta"],"telefono"=>$values["bajacelular"],"sMotivos"=>17,"sComentarios"=>"BOTÓN DE ARREPENTIMIENTO");
                    $ret=$TARGESTIONBAJA->save($params);
                    $result="Nos pondremos en contacto con vos a la brevedad.  Tu código de trámite es #".$ret["data"]["id"];
                    $subject="Tu pedido de arrepentimiento ha sido enviado.";
                    break;
                case "baja-tarjeta":
                    $save=false;
                    $TARGESTIONBAJA=$this->createModel(MOD_DBCENTRAL,"tarGestionBaja","tarGestionBaja");
                    $params=array("id"=>"0","codigo_tarjeta"=>$values["codigo_tarjeta"],"telefono"=>$values["bajacelular"],"sMotivos"=>0,"sComentarios"=>"BAJA SOLICITADA POR LA APP DE CREDIPAZ");
                    $ret=$TARGESTIONBAJA->save($params);
                    $result="Nos pondremos en contacto con vos a la brevedad.  Tu código de trámite es #".$ret["data"]["id"];
                    $subject="Tu pedido de baja ha sido enviado";
                    break;
                case "canjear-beneficio":
                    $save=false;
                    $mode="CANJE";
                    $BENEFICIOS=$this->createModel(MOD_CLUB_REDONDO,"Beneficios","Beneficios");
					$ret=$BENEFICIOS->procesarCanje($values);
                    $result=$ret["verification"];
                    $additional=array(
                        "code"=>$ret["code"],
                        "status"=>$ret["status"],
                        "message"=>$ret["message"],
                        "verification"=>$ret["verification"],
                        "qr_code"=>$ret["qr_code"],
                        "brand_image"=>$ret["brand_image"],
                        "status_canje"=>$ret["status_canje"],
                        "message_canje"=>$ret["message_canje"],
                    );
                    break;
                case "reimprimir-canje":
                    $save=false;
                    $mode="CANJE";
                    $BENEFICIOS=$this->createModel(MOD_CLUB_REDONDO,"Beneficios","Beneficios");
                    $BENEFICIOS->reimprimirCanje($values);
                    break;
                case "confirmar-canje":
                    $save=false;
                    $mode="CANJE";
                    $BENEFICIOS=$this->createModel(MOD_CLUB_REDONDO,"Beneficios","Beneficios");
                    $BENEFICIOS->confirmarCanje($values);
                    break;
                case "participar-sorteo":
                    $save=false;
                    $result="Gracias por inscribirte.  ¡Ya estás participando del sorteo!  ¡Recordá que solo es necesario que te inscribas para los sorteos, una sola vez por mes!";
                    $subject="Solicitud de Participar en sorteo";
                    $SORTEOS=$this->createModel(MOD_CLUB_REDONDO,"Sorteos","Sorteos");
                    $SORTEOS->participar($values);
                    break;
                case "participar-sorteo-libre":
                    $save=false;
                    $result="Gracias por inscribirte.  ¡Ya estás participando del sorteo!  ¡Te contactaremos en caso de que ganes alguno de los premios! ";
                    $subject="Solicitud de Participar en sorteo libre";
                    $SORTEOS=$this->createModel(MOD_CLUB_REDONDO,"Sorteos","Sorteos");
                    $SORTEOS->participar($values);
                    break;
                case "recargar-tarjeta":
                    $save=false;
                    if ((int)$id==0){throw new Exception("La operación no ha podido ser procesada. Por favor cierre la sesión actual y vuelva a ingresar poniendo su documento y contraseña.");}
                    $RTCARGAVIRTUAL=$this->createModel(MOD_EXTERNAL,"RtCargaVirtual","RtCargaVirtual");
                    $ret=$RTCARGAVIRTUAL->cargaVirtual($values);
                    if ($ret["status"]=="OK") {
                        $mode=$ret["mode"];
                        $ticket=$ret["ticket"];
                    } else {
                        $result=$ret["result"];
                    }
                    break;
                case "baja-club-redondo":
                    $save=false;
                    $result="¡Se ha procesado el pedido de baja a Mediya!";
			        $EMAIL=$this->createModel(MOD_EMAIL,"Email","Email");
					$params=array("from"=>"intranet@mediya.com.ar","alias_from"=>"","email"=>"","subject"=>"","body"=>"");
					$params["alias_from"]=lang('msg_internal_alerts');
					$params["email"]="retencion@credipaz.com";
					$params["subject"]="Solicitud de baja Mediya";
					$params["body"]=$this->load->view(MOD_EMAIL.'/templates/bajaClubRedondo',$values, true);
					$EMAIL->directEmail($params);
                    break;
                case "arrepentimiento-club-redondo":
                    $save=false;
                    $result="¡Se ha procesado el pedido de arrepentimiento a Mediya!";
			        $EMAIL=$this->createModel(MOD_EMAIL,"Email","Email");
					$params=array("from"=>"intranet@mediya.com.ar","alias_from"=>"","email"=>"","subject"=>"","body"=>"");
					$params["alias_from"]=lang('msg_internal_alerts');
					$params["email"]="retencion@credipaz.com";
					$params["subject"]="Solicitud de arrepentimiento Mediya";
					$params["body"]=$this->load->view(MOD_EMAIL.'/templates/arrepentimientoClubRedondo',$values, true);
					$EMAIL->directEmail($params);
                    break;
                case "presentar-club-redondo":
                    $save=false;
                    $result="¡Se ha procesado la presentación a Mediya!";
                    $subject="Presentar socio a Mediya";
                    $CLUBREDONDOWS=$this->createModel(MOD_EXTERNAL,"ClubRedondoWS","ClubRedondoWS");
                    $CLUBREDONDOWS->referirProspecto($values);
                    break;
                case "landing-club-redondo":
                    $id_contact_channel=11;
                    $result.=" solicitar promotor Mediya!";
                    $subject="Solicitud de Mediya";
                    $forcedData=true;
                    break;
                case "comodin-credito":
                    $result.=" usar tu Comodí­n!";
                    $subject="Solicitud de usar Comodí­n en Crédito";
                    break;
                case "renova-credito":
                    $result.=" renovar tu Crédito!";
                    $subject="Solicitud de renovar Crédito";
                    break;
                case "regulariza-credito":
                    $result.=" regularizar tu Crédito!";
                    $subject="Solicitud de regularizar Crédito";
                    break;
                case "comodin-tarjeta":
                    $result.=" usar tu Comodí­n!";
                    $subject="Solicitud de usar Comodí­n en Tarjeta";
                    break;
                case "aumentar-limite":
                    $result.=" aumentar tu lí­mite de Tarjeta!";
                    $subject="Solicitud de aumentar lí­mite en Tarjeta";
                    break;
                case "pedir-adicional":
                    $result.=" pedir adicional en Tarjeta!";
                    $subject="Solicitud de adicional en Tarjeta";
                    break;
                case "pedir-credito":
                    $result.=" pedir Crédito!";
                    $subject="Solicitud de Crédito";
                    break;
                case "pedir-adelanto-tarjeta":
                    $result.=" pedir adenlanto Tarjeta!";
                    $subject="Solicitud de adelanto Tarjeta";
                    break;
                case "pedir-tarjeta":
                    $result.=" pedir Tarjeta!";
                    $subject="Solicitud de Tarjeta";
                    break;
                case "pedir-clubredondo":
                    $result.=" pedir Mediya!";
                    $subject="Solicitud de Mediya";
                    break;
                case "pedir-mil":
                    $result.=" pedir MIL!";
                    $subject="Solicitud de MIL";
                    break;
                case "pedir-promociones":
                    $result.=" pedir Promociones!";
                    $subject="Solicitud de Promociones";
                    break;
            }
            if ($save) {
                if ($forcedData) {
                    $data["documentNumber"]=$values["dni"];
                    $data["documentSex"]=$values["sexo"];
                    $data["documentArea"]="";
                    $data["documentPhone"]=$values["telefono"];
                    $data["documentName"]=$values["nombre"];
                    $data["email"]=$values["email"];
                    $data["es_cliente"]="NO PUDO SER EVALUADO";
                    $data["VIABLE"]="NO PUDO SER EVALUADO";
                } else {
                    $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
                    $user=$USERS->get(array("page"=>1,"where"=>"token_authentication='".$api_key."'"));
                    $id=$user["data"][0]["id"];
                    $data["documentNumber"]=$user[0]["documentNumber"];
                    $data["documentSex"]=$user[0]["documentSex"];
                    $data["documentArea"]=$user[0]["documentArea"];
                    $data["documentPhone"]=$user[0]["documentPhone"];
                    $data["documentName"]=$user[0]["documentName"];
                    $data["email"]="";
                    if ($id_credipaz==null) {$data["es_cliente"]="NO ES CLIENTE";}else{$data["es_cliente"]="ES CLIENTE #ID ".$id_credipaz;}
                    if ($user[0]["viable"]==1) {$user[0]["viable"]="ES VIABLE";}else{$user[0]["viable"]="NO ES VIABLE";}
                    $data["viable"]=$user[0]["viable"];
                }
                $data["function"]=$function;
                $body=$this->load->view(MOD_BACKEND."/external/mobile_action",$data,true);

                $result="¡Muchas gracias!. Tu solicitud fue exitosa. Nos comunicaremos a la brevedad.";
                $OPERATORS_TASKS=$this->createModel(MOD_CRM,"Operators_tasks","Operators_tasks");
                $exists=$OPERATORS_TASKS->get(array("page"=>1,"where"=>"id_user='".$id."' AND code='".$function."' AND id_type_task_close IS null"));
                if(!isset($exists["data"][0]["id"])) {
                    if ($data["documentName"]==""){
                        if($id_credipaz==0 or $id_credipaz=="") {
                            $data["documentName"]="¡No se pudo obtener nombre con el DNI!";
                        } else {
                            $sql="SELECT sNombre,sSexo,nDoc FROM dbCentral.dbo.wrkClienteTitular WHERE nID=".$id_credipaz;
                            $ret=$this->getRecordsAdHoc($sql);
                            $data["documentName"]=$ret[0]["sNombre"];
                            $data["documentSex"]=$ret[0]["sSexo"];
                            $data["documentNumber"]=$ret[0]["nDoc"];
                            $arrData=array("documentName"=>$data["documentName"],"documentSex"=>$ret[0]["sSexo"],"documentNumber"=>$data["documentNumber"]);
                            $USERS->save(array("id"=>$id,$arrData));
                        }
                    }
                    $fields=array(
                        'code' => $function,
                        'description' => $subject,
                        'created' => $this->now,
                        'verified' => $this->now,
                        'fum' => $this->now,
                        'id_contact_channel' => $id_contact_channel,
                        'username' => $user[0]["documentName"],
                        'subject' => $subject,
                        'body' => $body,
                        'from' => $user[0]["documentName"],
                        'id_client_credipaz' => $id_credipaz,
                        'id_user' => $id,
                    );
                    if ($user[0]["viable"]=="NO ES VIABLE") {
                        $fields["id_type_task_close"]=1;
                        $fields["processed"]=$this->now;
                        $fields["tag_processed"]="Sin gestión por no ser viable";
                    }
                    $OPERATORS_TASKS->save(array("id"=>0),$fields);
                }
            }
            return array('status'=>'OK','mode'=>$mode,'message'=>$result,'ticket'=>$ticket,'additional'=>$additional);
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    /*Additional mobile functions*/
    public function urlExists($values)
    {
        $url=$values["url"];
        if($url == null){
            $ret["connected"]=false;
        } else {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($httpcode>=200 && $httpcode<300){
                $ret["connected"]=true;
            } else {
                $ret["connected"]=false;
            }
        }
        return $ret;
    }
    public function executeExpress($values){
        try {
            $data=null;
            if (!isset($values["express_key"])){$values["express_key"]="0";}
            if (!isset($values["express_code"])){$values["express_code"]="";}
            switch ($values["express_function"]) {
                case "capture_to_server":
                    $targetLocal = (FILES_CAPTURES . "/" . $values["express_key"] . ".webm");
                    $targetRemote = (FILES_TELEMEDICINA . $values["express_key"] . ".webm");
                    $binData = file_get_contents($targetLocal);
                    unlink($targetLocal);
                    setFileBinSSH($targetRemote, $binData);
                    break;
                case "consultadni":
                    $CLUBREDONDOWS = $this->createModel(MOD_EXTERNAL, "ClubRedondoWS", "ClubRedondoWS");
                    $data = $CLUBREDONDOWS->autorizarPrestacion($values);
                    break;
                case "GetCuotasPdf":
                    $NETCORECPFINANCIALS = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
                    $data = $NETCORECPFINANCIALS->GetCuotas($values,"pdf");
                    break;
                case "GetCuotasPlain":
                    $NETCORECPFINANCIALS = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
                    $data = $NETCORECPFINANCIALS->GetCuotas($values,"html");
                    break;
                default:
                    throw new Exception(lang("error_2001"), 2001);
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Operación exitosa",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function fileLoaderSwiss($values){
        try {
			$ret=listFilesSSH(FILES_SWISS_SSH,"pdf",$values["dni"]);
            $file=$ret[0];
            $filename=basename($file);
            $mime=getMimeType($filename);
            $ret=array();
            $ret["message"]=getFileBinSSH($file);
            $ret["mode"]="mime";
            $ret["filename"]=$filename;
            $ret["indisk"]=true;
            $ret["exit"]="download";
            return $ret;
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

    public function fileLoader($values){
        try {
            $file=(FILES_RESUMENES.$values["periodo"]."/".$values["archivo"]."-".$values["periodo"].".pdf");
            if (!existFileSSH($file)){$file=(FILES_RESUMENES."nofile.pdf");}
            $filename=basename($file);
            $mime=getMimeType($filename);
            $ret=array();
            $ret["message"]=getFileBinSSH($file);
            $ret["mime"]=$mime;
            $ret["mode"]="view";
            $ret["filename"]=$filename;
            $ret["indisk"]=true;
            $ret["exit"]="download";
            return $ret;
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function fileLoaderContratos($values){
        try {
            $file=(FILES_CONTRATOS.$values["archivo"]);
            $filename=basename($file);
            $mime=getMimeType($filename);
            $ret=array();
            $ret["message"]=file_get_contents($file);
            $ret["mime"]=$mime;
            $ret["mode"]="view";
            $ret["filename"]=$filename;
            $ret["indisk"]=true;
            $ret["exit"]="download";
            return $ret;
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    
    public function getSucursales($values){
        try {
            if(!isset($values["search"])){$values["search"]="";};
            $search=$values["search"];
            $PLACES=$this->createModel(MOD_PLACES,"Places","Places");
            $where="id_type_place=1";
            if($search!="") {$where="id_type_place=1 AND (description LIKE='%".$search."%' OR address LIKE '%".$search."%')";}
			$PLACES->view="vw_sucursales";
            $offices=$PLACES->get(array("page"=>1,"order"=>"description ASC","where"=>$where));
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "records"=>$offices["data"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function getTransformedImage($values){
        try {
            if(!isset($values["tipo_credencial"])){$values["tipo_credencial"]=0;}
            if(!isset($values["id_club_redondo"])){$values["id_club_redondo"]=0;}
			$values["overtext2"]=iconv("UTF-8", "ISO-8859-1", $values["overtext2"]);
			$values["overtext2"]=ucwords(strtolower($values["overtext2"]));
			$values["overtext3"]="";
			if ((int)$values["id_club_redondo"]!=0) {
				$club_redondo=getUserClubRedondo($this,$values["id_club_redondo"]);
				$values["overtext3"]="DNI ".$club_redondo["message"]["DNI"];
			}
            $file="";
            $size1=0;
            $angle1=0;
            $x1=0;
            $y1=0;
            $size2=0;
            $angle2=0;
            $x2=0;
            $y2=0;
			$r=250;
			$g=250;
			$b=255;
			$fontDefault = realpath("./OCRAEXT.TTF");

			if(!isset($values["overtext4"])){$values["overtext4"]="";}
			if(!isset($values["overtext5"])){$values["overtext5"]="";}
			if(!isset($values["overtext6"])){$values["overtext6"]="";}

            switch($values["file"]) {
                case "credencial-mediya":
					$fontDefault = realpath("./Roboto-Regular.ttf");
				    // Forzando para testing
				    //$values["overtext1"]="16916852";
				    $sql="SELECT * FROM ".MOD_CLUB_REDONDO."_vw_credencialSwiss WHERE NroDocumento='".$values["overtext1"]."'";
                    $ret=$this->getRecordsAdHoc($sql);
				    if (!isset($ret[0])) {
						//$values["overtext1"]="";//"DNI ".$values["overtext1"];
						//$values["overtext2"]="N/A";
						//$values["overtext3"]="N/A";
						//$values["overtext4"]="";//$ret[0]["PLAN"];
						//$values["overtext5"]="N/A";
						//$values["overtext6"]="N/A";
						throw new Exception("Imposible procesar la petición");
					} else {
						$values["overtext1"]="";//"DNI ".$values["overtext1"];
						$values["overtext2"]=strtoupper($ret[0]["NOMBRE"]);
						$values["overtext3"]=$club_redondo["message"]["DNI"];//$ret[0]["NroCredencial"];
						$values["overtext4"]="";//$ret[0]["PLAN"];
						$values["overtext5"]="";//date(FORMAT_DATE_DMY, strtotime($ret[0]["FechaIngreso"]));
						$values["overtext6"]="";//date(FORMAT_DATE_DMY, strtotime($ret[0]["FechaNacimiento"]));
					}

					$r=250;
					$g=250;
					$b=250;

                    $file="credencialMediYa";
                    $size1=56;
                    $angle1=0;
                    $x1=0;
                    $y1=0;
                    
					$size2=55;
                    $angle2=0;
                    $x2=120;
                    $y2=900;

                    $size3=55;
                    $angle3=0;
                    $x3=120;
                    $y3=1000;
					$font3 = realpath("./Roboto-Black.ttf");

                    $size4=40;
                    $angle4=0;
                    $x4=0;
                    $y4=0;

					$size5=40;
                    $angle5=0;
                    $x5=0;
                    $y5=0;

					$size6=40;
                    $angle6=0;
                    $x6=0;
                    $y6=0;
                    
                    break;
                case "credencial-swiss":
					$fontDefault = realpath("./Lato-Black.ttf");
				    // Forzando para testing
				    //$values["overtext1"]="16916852";
				    $sql="SELECT * FROM ".MOD_CLUB_REDONDO."_vw_credencialSwiss WHERE NroDocumento='".$values["overtext1"]."'";
                    $ret=$this->getRecordsAdHoc($sql);
				    if (!isset($ret[0])) {
						//$values["overtext1"]="";//"DNI ".$values["overtext1"];
						//$values["overtext2"]="N/A";
						//$values["overtext3"]="N/A";
						//$values["overtext4"]="";//$ret[0]["PLAN"];
						//$values["overtext5"]="N/A";
						//$values["overtext6"]="N/A";
						throw new Exception("Imposible procesar la petición");
					} else {
						$values["overtext1"]="";//"DNI ".$values["overtext1"];
						$values["overtext2"]=strtoupper($ret[0]["NOMBRE"]);
						$values["overtext3"]=$ret[0]["NroCredencial"];
						$values["overtext4"]="";//$ret[0]["PLAN"];
						$values["overtext5"]=date(FORMAT_DATE_DMY, strtotime($ret[0]["FechaIngreso"]));
						$values["overtext6"]=date(FORMAT_DATE_DMY, strtotime($ret[0]["FechaNacimiento"]));
					}

					$r=76;
					$g=76;
					$b=76;

                    $file="credencialSwiss";
                    $size1=56;
                    $angle1=0;
                    $x1=130;
                    $y1=680;
                    
					$size2=55;
                    $angle2=0;
                    $x2=170;
                    $y2=570;

                    $size3=55;
                    $angle3=0;
                    $x3=170;
                    $y3=460;

                    $size4=40;
                    $angle4=0;
                    $x4=1600;
                    $y4=680;

					$size5=40;
                    $angle5=0;
                    $x5=320;
                    $y5=680;

					$size6=40;
                    $angle6=0;
                    $x6=970;
                    $y6=680;

                    break;
                case "credencial-club-redondo-agnostica":
                    $file="credencialClubRedondoAgnostica";
                    $size1=92;
                    $angle1=0;
                    $x1=180;
                    $y1=920;

                    $size2=56;
                    $angle2=0;
                    $x2=185;
                    $y2=1050;

                    $size3=56;
                    $angle3=0;
                    $x3=185;
                    $y3=1125;

                    $size4=56;
                    $angle4=0;
                    $x4=0;
                    $y4=0;

					$size5=56;
                    $angle5=0;
                    $x5=0;
                    $y5=0;

					$size6=56;
                    $angle6=0;
                    $x6=0;
                    $y6=0;
                    break;
                case "credencial-club-redondo":
                    $file="credencialClubRedondo";
                    $size1=92;
                    $angle1=0;
                    $x1=180;
                    $y1=920;

                    $size2=56;
                    $angle2=0;
                    $x2=185;
                    $y2=1050;

                    $size3=56;
                    $angle3=0;
                    $x3=185;
                    $y3=1125;

                    $size4=56;
                    $angle4=0;
                    $x4=0;
                    $y4=0;

					$size5=56;
                    $angle5=0;
                    $x5=0;
                    $y5=0;

					$size6=56;
                    $angle6=0;
                    $x6=0;
                    $y6=0;
                    break;
                default:
                    throw new Exception("Imposible procesar la petición");
            }

            $im = imagecreatefrompng("./assets/img/".$file."-".$values["tipo_credencial"].".png");
            $color = imagecolorallocate($im, (int)$r, (int)$g, (int)$b);
            if(isset($font1)){$font=$font1;}else{$font=$fontDefault;}
			if ($values["overtext1"]!=""){imagettftext($im, $size1, $angle1, $x1, $y1, $color, $font, $values["overtext1"]);}

            if(isset($font2)){$font=$font2;}else{$font=$fontDefault;}
			if ($values["overtext2"]!=""){imagettftext($im, $size2, $angle2, $x2, $y2, $color, $font, $values["overtext2"]);}

            if(isset($font3)){$font=$font3;}else{$font=$fontDefault;}
			if ($values["overtext3"]!=""){imagettftext($im, $size3, $angle3, $x3, $y3, $color, $font, $values["overtext3"]);}

            if(isset($font4)){$font=$font4;}else{$font=$fontDefault;}
			if ($values["overtext4"]!=""){imagettftext($im, $size4, $angle4, $x4, $y4, $color, $font, $values["overtext4"]);}

            if(isset($font5)){$font=$font5;}else{$font=$fontDefault;}
			if ($values["overtext5"]!=""){imagettftext($im, $size5, $angle5, $x5, $y5, $color, $font, $values["overtext5"]);}

            if(isset($font6)){$font=$font6;}else{$font=$fontDefault;}
			if ($values["overtext6"]!=""){imagettftext($im, $size6, $angle6, $x6, $y6, $color, $font, $values["overtext6"]);}

            $filename=("intercambio/".uniqid().".png");
            imagepng($im,$filename);
            imagedestroy($im);
            $img=file_get_contents($filename); 
            $data=base64_encode($img); 
            unlink($filename);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$data,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "mime"=>"data:image/png;base64",
                "mode"=>"download"
            );

        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
}
