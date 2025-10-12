<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Polls extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
			if($values["code"]==""){$values["code"]=opensslRandom(8);}
            if($id==0){
			    if($fields==null){
					$fields = array(
						'code' => $values["code"],
						'description' => $values["description"],
						'created' => $this->now,
						'verified' => $this->now,
						'offline' => null,
						'fum' => $this->now,
						'id_type_poll' => secureEmptyNull($values,"id_type_poll"),
						'id_response' => secureEmptyNull($values,"id_response"),
						'id_rel' => $values["id_rel"],
						'table_rel' => $values["table_rel"],
					);
				}
            } else {
			    if ($fields==null){
					$fields = array(
						'fum' => $this->now,
					);
				}
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
	public function pollResponse($values){
	    return $this->save(array("id"=>$values["id"]),array("id_response"=>$values["id_response"],"fum"=>$this->now));
	}
}
