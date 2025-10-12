<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Products extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
	
	public function catalogoMIL($values){
		$values["order"]="description ASC";
		$values["pagesize"]=-1;
		$values["records"]=$this->get($values);
		return parent::get($values);
	}

    public function brow($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
            $values["title"]=lang('m_shopping_items');
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>true,
                "offline"=>true,
            );
            $values["columns"]=array(
                array("field"=>"id","format"=>"number"),
                array("field"=>"description","format"=>"text"),  
				array("field"=>"valorized","format"=>"money"),
                array("field"=>"code","format"=>"code"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
			);

            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description")),
            );
            $this->view="products";
            $values["order"]="description ASC";
            $values["records"]=$this->get($values);
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_DIRECT_SALE."/products/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
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
			    if ($fields==null) {
					$fields = array(
						'code' => $values["code"],
						'description' => $values["description"],
						'created' => $this->now,
						'verified' => $this->now,
						'offline' => null,
						'fum' => $this->now,
						'valorized' => secureEmptyNull($values,"valorized"),
						'image' => $values["image"],
						'details' => $values["details"],
					);
				}
            } else {
			    if ($fields==null) {
					$fields = array(
						'code' => $values["code"],
						'description' => $values["description"],
						'fum' => $this->now,
						'valorized' => secureEmptyNull($values,"valorized"),
						'image' => $values["image"],
						'details' => $values["details"],
					);
				}
            }
			$saved=parent::save($values,$fields);
			$id=$saved["data"]["id"];
			$record=$this->get(array("where"=>"id=".$id));
			$code=$record["data"][0]["code"];
			$image=$record["data"][0]["image"];
			if ($image!=""){
			   $parts=explode(",",$image);
			   $ext=".jpg";
			   if (strpos($parts[0],"image/png;base64")!==false){$ext=".png";}
			   $fullPath=(FILES_TIENDAMIL.$code.$ext);
			   saveBase64ToFile(array("data"=>$image,"path"=>FILES_TIENDAMIL,"fullPath"=>$fullPath));
			   $saved=parent::save(array("id"=>$id),array("filepath"=>$fullPath));
			} else {
			   $saved=parent::save(array("id"=>$id),array("filepath"=>null));
			}
			return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
