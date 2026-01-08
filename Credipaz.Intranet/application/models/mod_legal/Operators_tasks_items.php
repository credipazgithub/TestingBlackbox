<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Operators_tasks_items extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            if (!isset($values["auto"])){$values["auto"]="";}
            $id=(int)$values["id"];
			$id_operator_task=$values["id_operator_task"];
			$item=$this->get(array("where"=>"id_operator_task=".$id_operator_task));
			$bFirst=((int)$item["totalrecords"]==0);
            $fields=null;
            if($id==0){
                $fields = array(
                    'code' => opensslRandom(8),
                    'description' => $values["description"],
                    'created' => $this->now,
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    'id_operator_task' => $id_operator_task,
                    'id_type_direction' => 1,
                    'id_type_item' => 1,
                    'mime' => "text/html",
                    'id_user' => $values["id_user_active"],
                    'data' => $values["data"],
                );
            } else {
                $fields = array(
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'mime' => $values["mime"],
                    'id_user' => $values["id_user"],
                    'data' => $values["data"],
                );
            }
            $saved=parent::save($values,$fields);
			switch ($values["auto"]){
			   case "nocontact":
				  $this->execAdHoc("UPDATE ".MOD_LEGAL."_operators_tasks SET nocontact=nocontact+1 WHERE id=".$id_operator_task);
				  $ot=$OPERATORS_TASKS->get(array("where"=>"id=".$id_operator_task));
				  if ((int)$ot["data"][0]["nocontact"]>=3) {
				     $this->execAdHoc("UPDATE ".MOD_LEGAL."_operators_tasks SET id_type_status=3 WHERE id=".$id_operator_task);
					 $OPERATORS_TASKS->notifyEmail($id_operator_task,"legalRequestNoContact");
				  }
			      break;
			   default:
				  $this->execAdHoc("UPDATE ".MOD_LEGAL."_operators_tasks SET nocontact=0 WHERE id=".$id_operator_task);
				  if($bFirst){$OPERATORS_TASKS->notifyEmail($id_operator_task,"legalRequestFirst");}
			      break;
			}
			return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
