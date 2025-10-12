<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Rel_sinisters_questions extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function save($values,$fields=null){
        try {
		    $id_sinister=0;
		    $id_user=$values["id_user_active"];
		    //key_<id_sinister>_<id_question>_<revision>_<codecontrol>
			$i=0;
			foreach($values as $key=>$value)
			{
			    $item=$values[$key];
				$item=trim($item);
				if ($item!="") {
					$parts=explode("_",$key);
					if ($parts[0]=="key") {
						switch($parts[4]){
						   case "DATETIME":
						      if ($item!=""){$item=str_replace("T", " ",$item);}else{$item=null;}
							  break;
						}
						$id_sinister=$parts[1];
						$fields = array(	
							'id_sinister'=>$id_sinister,
							'id_question'=>$parts[2],
							'revision' => $parts[3],
							'created' => $this->now,
							'value' => $item,
							'id_user'=>$id_user,
						);
						$saved=parent::save($values,$fields);
						/*Update sinister head with this specific value*/
						if((int)$parts[2]==23){$this->execAdHoc("UPDATE ".MOD_FOLLOW."_sinisters SET fum='".$this->now."', next_revision_date_desnorm='".$item."' WHERE id=".$id_sinister);}
					}
				}
			}
			$SINISTERS=$this->createModel(MOD_FOLLOW,"sinisters","sinisters");
			$this->execAdHoc("UPDATE ".MOD_FOLLOW."_sinisters SET fum='".$this->now."', actual_review=actual_review+1 WHERE id=".$id_sinister);
			
			/*Check if that was the last revision!!!!*/
			$SINISTERS->view="vw_sinisters";
			$ret=$SINISTERS->get(array("where"=>"id=".$id_sinister));
			if ((int)$ret["data"][0]["actual_review"]>=(int)$ret["data"][0]["occurs"]){
   			   $this->execAdHoc("UPDATE ".MOD_FOLLOW."_sinisters SET fum='".$this->now."', actual_review=".$ret["data"][0]["occurs"].",id_type_status=2 WHERE id=".$id_sinister);
			}
			return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
