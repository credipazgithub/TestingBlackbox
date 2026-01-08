<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Mstfraude extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
	public function lock($values){
			return true;
	}
	public function unlock($values){
			return true;
	}
	public function offline($values){
	    $sql="UPDATE DBCentral.dbo.mstfraude SET lVigente=null WHERE nId=".$values["id"];
        $this->execAdHoc($sql);
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
        );
	}
	public function online($values){
	    $sql="UPDATE DBCentral.dbo.mstfraude SET lVigente=1 WHERE nId=".$values["id"];
        $this->execAdHoc($sql);
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
        );
	}
    public function brow($values){
        try {
            $this->view="DBCentral.dbo.mstfraude";
            $values["fields"]="*, nId as id, CASE WHEN lVigente IS null THEN getdate() ELSE null END as offline";
            $values["order"]="nDoc ASC";
            $values["title"]=lang('m_fraudulento');
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>true,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"nDoc","format"=>"text"),
                array("field"=>"sSexo","format"=>"text"),
                array("field"=>"CUIL","format"=>"text"),
                array("field"=>"sMotivo","format"=>"text"),
                array("field"=>"","format"=>""),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("nDoc","sMotivo")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_DBCENTRAL."/Mstfraude/abm");
            $sql="SELECT nId as id, nDoc,sSexo,lVigente, sMotivo,CUIL FROM DBCentral.dbo.mstfraude WHERE nId=".$values["id"];
            $values["records"]["data"]=$this->getRecordsAdHoc($sql);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);

            $this->table="DBCentral.dbo.mstfraude";
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
            if($id==0){
                $fields = array(
                    'nDoc' => $values["nDoc"],
                    'sSexo' => $values["sSexo"],
                    'lVigente' => 1,
                    'sMotivo' => $values["sMotivo"],
                    'sAudAltaUsuario' => $profile["data"][0]["username"],
                    'dAudAltaFecha' => $this->now,
	                "CUIL"=>$values["CUIL"]
                );
    			$saved=parent::save($values,$fields);
            } else {
                $fields = array(
                    'sMotivo' => $values["sMotivo"],
                    'sAudModiUsuario' => $profile["data"][0]["username"],
                    'dAudModiFecha' => $this->now,
	                "CUIL"=>$values["CUIL"]
                );
                $saved=$this->updateByWhere($fields,"nId='".$id."'");
            }
            if($saved["status"]=="OK"){$t=0;}
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
