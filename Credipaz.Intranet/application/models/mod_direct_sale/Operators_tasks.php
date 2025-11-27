<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Operators_tasks extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
            $values["title"]=lang('m_mil');
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>false,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("forcedlabel"=>"","field"=>"id","format"=>"number"),
                array("forcedlabel"=>"","field"=>"created","format"=>"datetime"),
                array("forcedlabel"=>"","field"=>"description","format"=>"text"),
            );

            $values["controls"]=array(
			   "<span class='badge badge-primary'>Estado</span>".comboTypeStatusSales($this),
			   "<button title='Nuevo registro' class='btn btn-sm btn-raised btn-success btn-record-edit ml-2' data-id='0' data-module='mod_direct_sale' data-model='operators_tasks' data-table='operators_tasks'><i class='material-icons'>add_circle_outline</i> Nueva venta</button>"
			);

            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description")),
                array("name"=>"browser_id_type_status", "operator"=>"=","fields"=>array("id_type_status")),
            );
            $this->view="vw_operators_tasks";
            $values["order"]="created DESC";
            $values["records"]=$this->get($values);
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
		    if((int)$values["id"]==0){
			   $saved=$this->save(array("id"=>0,"code"=>"NEW","description"=>"Nueva venta","id_type_task_close"=>null,"id_type_status"=>null,"sinopsys"=>""),null);
			   $values["id"]=$saved["data"]["id"];
			}
		    $chat_roomname=opensslRandom(8);
            $values["title"]=lang('m_mil');
            $profile=getUserProfile($this,$values["id_user_active"]);
            $values["chat_fullname"]=$profile["data"][0]["username"];
            $values["chat_alias"]=$profile["data"][0]["username"];
            $values["chat_height"]="450";
            $values["chat_platformname"]="Videoconsulta";
            $values["chat_roomname"]=("CHARGECODEID".$values["id"]);
            $values["chat_domain"]=SERVER_SUB;

            $this->view="vw_operators_tasks";
            $values["interface"]=(MOD_DIRECT_SALE."/operators_tasks/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_id_type_status=array(
                "model"=>(MOD_DIRECT_SALE."/Type_status"),
                "table"=>"type_status",
                "name"=>"id_type_status",
                "class"=>"form-control dbase id_type_status validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_status"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_camera=array(
                "model"=>(MOD_DIRECT_SALE."/Cameras"),
                "table"=>"cameras",
                "name"=>"id_camera",
                "class"=>"form-control",
                "empty"=>true,
                "id_actual"=>-1,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1),
            );
            $values["controls"]=array(
                "id_type_status"=>getCombo($parameters_id_type_status,$this),
                "id_camera"=>getCombo($parameters_id_camera,$this),
            );
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
                        'verified' => null,
                        'offline' => null,
                        'fum' => $this->now,
                        'id_operator' => $values["id_user_active"],
                        'id_type_status' => secureEmptyNull($values,"id_type_status"),
                        'sinopsys' => $values["sinopsys"],
                        'whatsapp' => $values["whatsapp"],
                        'dni' => $values["dni"],
                        'name' => $values["name"],
                        'surname' => $values["surname"],
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'fum' => $this->now,
                        'description' => $values["description"],
                        'id_operator' => secureEmptyNull($values,"id_operator"),
                        'id_type_status' => secureEmptyNull($values,"id_type_status"),
                        'sinopsys' => $values["sinopsys"],
                        'whatsapp' => $values["whatsapp"],
                        'dni' => $values["dni"],
                        'name' => $values["name"],
                        'surname' => $values["surname"],
                    );
                }
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function form($values){
        try {
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".$values["model"]));
            $CAMERAS = $this->createModel(MOD_DIRECT_SALE, "Cameras", "Cameras");
            $cams=$CAMERAS->get(array("order"=>"description ASC"));
            $data["cams"] = $cams["data"];
            $html=$this->load->view(MOD_DIRECT_SALE."/operators_tasks/form",$data,true);
           
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

    public function videoVendorStatus($values){
  	   if (!isset($values["token_meet"])){$values["token_meet"]="TOKEN ERROR";}
       if (!isset($values["id_operator_task"])){$values["id_operator_task"]=0;}
       if (!isset($values["videoStatus"])){$values["videoStatus"]=0;}
       if ($values["videoStatus"]==""){$values["videoStatus"]=0;}
       if((int)$values["videoStatus"]!=0){
          $return = $this->save(array("id"=>$values["id_operator_task"]),array("code"=>$values["token_meet"],"videoVendorStatus"=>$values["videoStatus"],"date_last_status_connected"=>$this->now,"id_vendor_connected"=>$values["id_user_active"]));
       } else {
          $return = $this->save(array("id"=>$values["id_operator_task"]),array("code"=>"","videoVendorStatus"=>$values["videoStatus"],"date_last_status_connected"=>null,"id_vendor_connected"=>null));
       }
       $ot=$this->get(array("where"=>"id=".$values["id_operator_task"]));
       if((int)$values["videoStatus"]==0) {$return=$this->videoBuyerStatus($values);}
	   return $return;
    }
    public function videoBuyerStatus($values){
       if (!isset($values["id_operator_task"])){$values["id_operator_task"]=0;}
       if (!isset($values["videoStatus"])){$values["videoStatus"]=0;}
       if ($values["videoStatus"]==""){$values["videoStatus"]=0;}
       if((int)$values["videoStatus"]!=0){
          $return = $this->save(array("id"=>$values["id_operator_task"]),array("id_user_in_use"=>$values["id_user_active"],"videoBuyerStatus"=>$values["videoStatus"],"date_last_buyer_connected"=>$this->now));
       } else {
          $return = $this->save(array("id"=>$values["id_operator_task"]),array("id_user_in_use"=>null,"videoBuyerStatus"=>$values["videoStatus"],"date_last_buyer_connected"=>null));
       }
 	   $ot=$this->get(array("fields"=>"*,datediff(second,date_last_status_connected,getdate()) as seconds","where"=>"id=".$values["id_operator_task"]));
       return $ot;
    }
    public function statusVideoResponse($values){
       try {
           $record=$this->get(array("where"=>"id=".$values["id_charge_code"]));
           if ((int)$record["totalrecords"]==0){
               throw new exception("Imposible procesar el código de venta provisto");
           }
           $ret=$this->statusMIL($values);
           return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function statusMIL($values){
        try {
            $id=0;
            $token_meet="";
            $videoVendorStatus=0;
            $videoBuyerStatus=0;
            $eval=$this->get(array("where"=>"id=".$values["id_charge_code"]));
            if ((int)$eval["totalrecords"]!=0){ 
                $id=$eval["data"][0]["id"];
                $token_meet=$eval["data"][0]["code"];
                $videoVendorStatus=$eval["data"][0]["videoVendorStatus"];
                $videoBuyerStatus=$eval["data"][0]["videoBuyerStatus"];
                $record=$this->get(array("fields"=>"datediff(second,date_last_status_connected,getdate()) as elapsed","where"=>"id=".$id));
                if ((int)$record["totalrecords"]!=0){
                    $elapsed=$record["data"][0]["elapsed"];
                    if ((int)$elapsed>30) {
                        $this->videoVendorStatus(array("push"=>"no","token_meet"=>$token_meet,"id_operator_task"=>$id,"videoStatus"=>0));
                        $videoVendorStatus=0;
                        $videoBuyerStatus=0;
                    }
                }
            }

			$ret=array(
                "code"=>"2000",
                "status"=>"OK",
                "paycode"=>$id,
                "token_meet"=>$token_meet,
                "videoVendorStatus"=>$videoVendorStatus,
                "videoBuyerStatus"=>$videoBuyerStatus,
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            );
			return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
