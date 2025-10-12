<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Devices extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function form_vendor($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
			$data["profile"]=$profile["data"][0];
            $data["parameters"] = $values;
			$data["devices"]=$this->get(array("page"=>1,"pagesize"=>-1));
			$parameters=array(
				"model"=>(MOD_ONBOARDING."/Requests_core"),
				"table"=>"Requests_core",
				"name"=>"browser_id_request",
				"class"=>"form-control browser_id_request",
				"empty"=>true,
				"id_actual"=>"",
				"id_field"=>"id",
				"description_field"=>"selector",
				"view"=>"vw_requests",
				"get"=>array("where"=>"id_type_request=1 AND offline IS null AND id_type_status=2","order"=>"selector ASC","pagesize"=>-1)
			);
			$data["browser_id_request"]=getCombo($parameters,$this);
            $html=$this->load->view(MOD_DIRECT_SALE."/devices/form_vendor",$data,true);

            logGeneral($this,$values,__METHOD__);
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
    public function form_device($values){
        try {
            $data["parameters"] = $values;
			$data["devices"]=$this->get(array("page"=>1,"pagesize"=>-1));
			$data["files"]=glob(FILES_VIDEOS."*");
			$data["id_user_active"]=$values["id_user_active"];
            $html=$this->load->view(MOD_DIRECT_SALE."/devices/form_device",$data,true);
            logGeneral($this,$values,__METHOD__);
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
    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
			    if ($fields==null) {
					$fields = array(
						'description' => '',
						'created' => $this->now,
						'verified' => $this->now,
						'offline' => null,
						'fum' => $this->now,
						'id_type_device' => secureEmptyNull($values,"id_type_device"),
						'date_in_use' => $values["date_in_use"],
						'id_user_in_use' => secureEmptyNull($values,"id_user_in_use"),
						'date_connected' => $values["date_connected"],
						'id_vendor_connected' => secureEmptyNull($values,"id_vendor_connected"),
						'date_last_status_in_use' => $values["date_last_status_in_use"],
						'date_last_status_connected' => $values["date_last_status_connected"],
						'requested_attention'=>0,
					);
				}
            } else {
			    if ($fields==null) {
					$fields = array(
						'fum' => $this->now,
						'id_type_device' => secureEmptyNull($values,"id_type_device"),
						'date_in_use' => $values["date_in_use"],
						'id_user_in_use' => secureEmptyNull($values,"id_user_in_use"),
						'date_connected' => $values["date_connected"],
						'id_vendor_connected' => secureEmptyNull($values,"id_vendor_connected"),
						'date_last_status_in_use' => $values["date_last_status_in_use"],
						'date_last_status_connected' => $values["date_last_status_connected"],
					);
				}
            }

            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function videoVendorStatus($values){
	   try {
		   if (!isset($values["id_device"])){$values["id_device"]=0;}
		   if ((int)$values["id_device"]==0) {throw new Exception(lang("error_5005"),5005);}
		   if (!isset($values["token_meet"])){$values["token_meet"]="TOKEN ERROR";}
		   if (!isset($values["videoStatus"])){$values["videoStatus"]=0;}
		   if ($values["videoStatus"]==""){$values["videoStatus"]=0;}
		   if((int)$values["videoStatus"]!=0){
			  $return = $this->save(array("id"=>$values["id_device"]),array("code"=>$values["token_meet"],"videoVendorStatus"=>$values["videoStatus"],"date_last_status_connected"=>$this->now,"id_vendor_connected"=>$values["id_user_active"]));
		   } else {
			  $return = $this->save(array("id"=>$values["id_device"]),array("code"=>"","videoVendorStatus"=>$values["videoStatus"],"date_last_status_connected"=>null,"id_vendor_connected"=>null));
		   }
		   $device=$this->get(array("where"=>"id=".$values["id_device"]));
		   if((int)$values["videoStatus"]==0) {$return=$this->videoBuyerStatus($values);}
		   return $return;
	   } catch(Exception $e) {
            return logError($e,__METHOD__ );
	   }
    }
    public function videoBuyerStatus($values){
	   try {
		   if (!isset($values["id_device"])){$values["id_device"]=0;}
		   if ((int)$values["id_device"]==0) {throw new Exception(lang("error_5005"),5005);}
		   if (!isset($values["videoStatus"])){$values["videoStatus"]=0;}
		   if ($values["videoStatus"]==""){$values["videoStatus"]=0;}
		   if((int)$values["videoStatus"]!=0){
			  $return = $this->save(array("id"=>$values["id_device"]),array("id_user_in_use"=>$values["id_user_active"],"videoBuyerStatus"=>$values["videoStatus"],"date_last_buyer_connected"=>$this->now));
		   } else {
			  $return = $this->save(array("id"=>$values["id_device"]),array("id_user_in_use"=>null,"videoBuyerStatus"=>$values["videoStatus"],"date_last_buyer_connected"=>null));
		   }
 		   $devices=$this->get(array("fields"=>"*,datediff(second,date_last_status_connected,getdate()) as seconds","where"=>"id=".$values["id_device"]));
		   return $devices;
	   } catch(Exception $e) {
            return logError($e,__METHOD__ );
	   }
    }

	public function deActivateDevice($values){
	   $this->execAdHoc("UPDATE ".MOD_DIRECT_SALE."_devices SET id_user_in_use=null, date_connected=null,id_vendor_connected=null,date_last_status_in_use=null,date_last_status_connected=null WHERE id=".$values["id"]);
 	   $devices=$this->get(array("where"=>"id=".$values["id"],"page"=>1,"pagesize"=>-1));
	   return $devices;
	}
	public function activateDevice($values){
	   $this->execAdHoc("UPDATE ".MOD_DIRECT_SALE."_devices SET id_user_in_use=null, date_connected=null,id_vendor_connected=null,date_last_status_in_use=null,date_last_status_connected=null WHERE id_user_in_use IS NOT null AND datediff(second,date_last_status_in_use,getdate()) > 30");
 	   $devices=$this->get(array("where"=>"(id_user_in_use IS null OR id_user_in_use=".$values["id_user_active"].") AND id=".$values["id"],"page"=>1,"pagesize"=>-1));
	   //$values["videoStatus"]=1;
	   $params=array("videoStatus"=>1,"id_device"=>$values["id"],"id_user_active"=>$values["id_user_active"]);
	   $this->videoBuyerStatus($params);
	   return $devices;
	}
    public function sendDeviceCapture($values){
	   try {
		   if (!isset($values["id"])){$values["id"]=0;}
		   if ((int)$values["id"]==0) {throw new Exception(lang("error_5005"),5005);}
		   $fields=array(
			  "date_in_use"=>$this->now,
			  "date_last_status_in_use"=>$this->now,
			  "id_user_in_use"=>$values["id_user_active"],
		   );
		   $this->save(array("id"=>$values["id"]),$fields);
		   $fullPath=(FILES_ATTACHED_LOCAL."device-".$values["id"].".png");
		   saveBase64ToFile(array("data"=>$values["base64"],"path"=>FILES_ATTACHED_LOCAL,"fullPath"=>$fullPath));
		   return array(
			   "code"=>"2000",
			   "status"=>"OK",
			   "message"=>"",
			   "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
		   );    
	   } catch(Exception $e) {
            return logError($e,__METHOD__ );
	   }
    }
    public function stopDeviceCapture($values){
	   try {
		   if (!isset($values["id"])){$values["id"]=0;}
		   if ((int)$values["id"]==0) {throw new Exception(lang("error_5005"),5005);}
		   $fields=array(
			  "date_in_use"=>null,
			  "date_last_status_in_use"=>null,
			  "id_user_in_use"=>null,
		   );
		   $this->save(array("id"=>$values["id"]),$fields);
		   return array(
			   "code"=>"2000",
			   "status"=>"OK",
			   "message"=>"",
			   "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
		   );    
	   } catch(Exception $e) {
            return logError($e,__METHOD__ );
	   }
    }
    public function checkDevices($values){
	   $devices=$this->get(array("fields"=>"*,datediff(second,date_last_status_in_use,getdate()) as seconds,datediff(second,isnull(date_last_status_connected,getdate()-10),getdate()) as connected","page"=>1,"pagesize"=>-1));
	   if ((int)$devices["data"][0]["seconds"]>10){
	      $this->stopDeviceCapture(array("id"=>$devices["data"][0]["id"]));
	      $devices=$this->get(array("fields"=>"*,datediff(second,date_last_status_in_use,getdate()) as seconds,datediff(second,isnull(date_last_status_connected,getdate()-10),getdate()) as connected","page"=>1,"pagesize"=>-1));
   	      //$devices=$this->get(array("page"=>1,"pagesize"=>-1));
	   }
       return $devices;    
    }
    public function requestAttention($values){
	   try {
		   if (!isset($values["id"])){$values["id"]=0;}
		   if ((int)$values["id"]==0) {throw new Exception(lang("error_5005"),5005);}
			$upd=null;
			$id=$values["id"];
			$code=$values["code"];
			if((int)$values["requested_attention"]==1){$upd=$this->now;}
			$return = $this->save(array("id"=>$id),array("code"=>$code,"date_last_requested_attention"=>$upd,"requested_attention"=>$values["requested_attention"]));
			$devices=$this->get(array("where"=>"id=".$id,"page"=>1,"pagesize"=>-1));
			try {
				if ((int)$values["requested_attention"]==1) {
					$PUSH_OUT=$this->createModel(MOD_PUSH,"Push_out","Push_out");
					$params=array(
						"id_group"=>1088, // TIENDA_MIL
						"subject"=>lang('msg_direct_sale_push_alert'),
						"body"=>("Se ha recibido una solicitud de atención personalizada desde ".$devices["data"][0]["description"])
					);
					$PUSH_OUT->sendToGroup($params);
				}
			} catch(Exception $ex){}

		   return $return;
	   } catch(Exception $e) {
            return logError($e,__METHOD__ );
	   }
    }
}
