<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Files_base64 extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function save($values,$fields=null){
        try {
            if (!isset($values["id"])){$values["id"]=0;}
            if (!isset($values["code"])){$values["code"]=opensslRandom(16);}
			$filedata=$values["base64"];
			$filedata_2=$values["base64"];
			$b64=$filedata;
			$b64_2=$filedata_2;
			switch($values["extension"]){
			   case "pdf":
                  $b64=html2pdfBase64($this, base64_decode($values["base64"]));
			      break;
			}
            $id=(int)$values["id"];
            $fields=null;
            if($id==0){
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'created' => $this->now,
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    'base64'=>$b64,
                    'filename'=>$values["filename"],
                    'extension' => $values["extension"],
                    'base64_2'=>$b64_2,
                    'filename_2'=>"raw",
                    'extension_2' => null
                );
            } 
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function fileLoader($values){
        try {
		    $record=$this->get(array("order"=>"created DESC","pagesize"=>"1","where"=>"description='".$values["description"]."' AND code='".$values["code"]."'"));
			if((int)$record["totalrecords"]!=0) {
				$filename=basename($record["data"][0]["filename"]);
				$mime=getMimeType($filename);
				$ret=array();
				$binData=base64_decode($record["data"][0]["base64"]);
				$ret["message"]=$binData;
				$ret["mime"]=$mime;
				$ret["mode"]="view";
				$ret["filename"]=$filename;
				$ret["indisk"]=false;
				$ret["exit"]="download";
				return $ret;
			} else {
			   return null;
			}
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

}
