<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Folder_items extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

	private function replaceType($where,$id_type_folder,$inList){
	    $ret=array("dirty"=>false,"where"=>$where,"inList"=>$inList);
	    $findme1=("((id_type_folder = '".$id_type_folder."'))");
	    $findme2=("id_type_folder='".$id_type_folder."'");
		$replace=("([id_type_folder] = '".$id_type_folder."' OR id_type_folder_item IN ".$inList.")");
		$pos=strpos($ret["where"], $findme1);
		if ($pos !== false) {
		    $ret["dirty"]=true;
			$ret["where"]=str_replace($findme1, $replace, $ret["where"]);
			return $ret;
		}
		$pos=strpos($ret["where"], $findme2);
		if ($pos !== false) {
		    $ret["dirty"]=true;
			$ret["where"]=str_replace($findme2, $replace, $ret["where"]);
			return $ret;
		}
		return $ret;
	}

    public function brow($values){
        try {
            $this->view="vw_folder_items";
            if(isset($values["forced_field"]) and isset($values["forced_value"])) {$values["where"]=($values["forced_field"]."='".$values["forced_value"]."'");}
            if($values["where"]!=""){$values["where"].=" AND ";}
            $values["where"].=("id_type_control_point=4 AND id_folder IN (SELECT id_folder FROM ".MOD_FOLDERS."_rel_folders_groups WHERE id_group IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"]."))");

			$ret=$this->replaceType($values["where"],"1","(2,3,4,5,6,7,8,10,11)");
			if (!$ret["dirty"]){$ret=$this->replaceType($values["where"],"3","(6,7,10)");}
			
			/*Evaluar si no hubo reemplazos en ningun nivel*/
			if (!$ret["dirty"]){$ret["inList"]="";}

			$values["where"]=$ret["where"];

            $values["fields"]="*,(SELECT count(id) FROM ".MOD_FOLDERS."_folder_items_log as l WHERE l.id_folder_item=".MOD_FOLDERS."_vw_folder_items.id AND l.id_user=".$values["id_user_active"].") as viewed";
            $values["order"]="created DESC";
            $values["records"]=$this->get($values);
            $i=0;
            foreach($values["records"]["data"] as $item) {
                $color="black";
                $status="notready";
				if ($item["viewed"] == 0) {
                    $color="magenta";
                    $status="ready";
			        $menu="<div class='btn-group p-0 m-0 btn-menu-".$item["id"]."'>";
			        $menu.=" <button type='button' class='btn btn-sm btn-dark' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='material-icons'>more_vert</i></button>";
			        $menu.="   <div class='dropdown-menu'>";
			        $menu.="      <button data-id='".$item["id"]."' data-status='".$status."' class='".$color." ready-".$item["id"]." btn-status-folder-item dropdown-item' type='button'>".lang("p_".$status)."</button>";
			        $menu.="   </div>";
			        $menu.="</div>";
				} else {
                    $menu="";
                }
                $link="<a target='_blank' href='".$values["baseserver"]."folderDirectLink/".base64_encode($item["data"])."' style='width:40px;' class='btn btn-sm btn-view-file btn-dark img-".$item["id"]."'><i class='material-icons'>attach_file</i></a>";
                $html="<table style='border:solid 0px transparent;'>";
                $html.=" <tr>";
                $html.="   <td style='border:solid 0px transparent;'>".$link."</td>";
                $html.="   <td style='border:solid 0px transparent;width:10%;'><span class='badge badge-light p-2'>".$item["type_folder"]."</span></td>";
                $html.="   <td style='border:solid 0px transparent;width:100%;' align='left'><div class='p-1 ".$color." status-".$item["id"]."'><span class='badge badge-primary'>".$item["datetime"]."</span> ".$item["description"]."</div></td>";
                $html.="   <td style='border:solid 0px transparent;'>".$menu."</td>";
                $html.=" </tr>";
                $html.="</table>";
                $values["records"]["data"][$i]["resolved"]="<button type='button' class='btn btn-raised btn-record-edit btn-info btn-sm' data-id='".$item["id_folder"]."' data-module='mod_folders' data-model='folders_userview' data-table='folders'><i class='material-icons'>edit</i> Carpeta</button>";
                $values["records"]["data"][$i]["editfolder"]=$html;
                $i+=1;
            }

            $values["buttons"]=array(
                "new"=>false,
                "edit"=>false,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("field"=>"resolved","format"=>""),
                array("field"=>"editfolder","format"=>""),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("description","keywords","folder_keywords")),
                array("name"=>"browser_id_type_folder", "operator"=>"=","fields"=>array("id_type_folder")),
                array("name"=>"browser_id_type_folder_item", "operator"=>"=","fields"=>array("id_type_folder_item")),
            );

			$getFolderItems=null;
			if ($ret["inList"]!=""){$getFolderItems=array("where"=>"id IN ".$ret["inList"],"order"=>"description ASC","pagesize"=>-1);}
            $values["controls"]=array(
                "<span class='badge badge-primary'>Tipo de carpeta</span>".comboTypeFolders($this),
                "<span class='badge badge-primary'>Tipo de archivo</span>".comboTypeFolderItems($this,$getFolderItems),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function notifications($values){
        $i=0;
        $records=array();
        $values["where"]="id_type_control_point=4 AND id_folder IN (SELECT id_folder FROM ".MOD_FOLDERS."_rel_folders_groups WHERE id_group IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"]."))";
        $values["fields"]="*,(SELECT count(id) FROM ".MOD_FOLDERS."_folder_items_log as l WHERE l.id_folder_item=".MOD_FOLDERS."_vw_folder_items.id AND l.id_user=".$values["id_user_active"].") as viewed";
        $values["order"]="created DESC";
        $this->view="vw_folder_items";
        $ret=$this->get($values);
        foreach($ret["data"] as $record){
            if($record["viewed"]==0){
                array_push($records,$record);
            } else {
                if($i<10){array_push($records,$record);}
                $i+=1;   
            }
        };
        $ret["data"]=$records;
        if($ret["status"]=="OK"){$ret["message"]="";}
        return $ret;
    }
    public function notViewedNotifications($values){
	    //$values["id_user_active"]=46803;->Execute
	    $user=getUserProfile($this,$values["id_user_active"]);
        $data=null;
        $data_revisar=null;
        $data_publicar=null;
        $sql="SELECT count(fi.id) as total, fi.code_type_folder as id_type_folder FROM ".MOD_FOLDERS."_vw_folder_items as fi WHERE fi.id_type_folder!=1 AND ";
        $sql.=" fi.folder_offline IS null AND fi.id_type_control_point=4 AND fi.id_type_folder IS NOT null AND fi.id NOT IN (SELECT l.id_folder_item FROM ".MOD_FOLDERS."_folder_items_log as l WHERE l.id_folder_item=fi.id AND l.id_user=".$values["id_user_active"].") AND ";
        $sql.=" fi.id_folder IN (SELECT rfg.id_folder FROM ".MOD_FOLDERS."_rel_folders_groups as rfg WHERE rfg.id_group IN (SELECT rug.id_group FROM ".MOD_BACKEND."_rel_users_groups as rug WHERE rug.id_user=".$values["id_user_active"]."))";
        $sql.=" GROUP BY fi.code_type_folder";
        $sql.=" UNION ";
        $sql.="SELECT count(fi.id) as total, 'MNYP' as id_type_folder FROM ".MOD_FOLDERS."_vw_folder_items as fi WHERE fi.id_type_folder_item=3 AND ";
        $sql.=" fi.folder_offline IS null AND fi.id_type_control_point=4 AND fi.id_type_folder IS NOT null AND fi.id NOT IN (SELECT l.id_folder_item FROM ".MOD_FOLDERS."_folder_items_log as l WHERE l.id_folder_item=fi.id AND l.id_user=".$values["id_user_active"].") AND ";
        $sql.=" fi.id_folder IN (SELECT rfg.id_folder FROM ".MOD_FOLDERS."_rel_folders_groups as rfg WHERE rfg.id_group IN (SELECT rug.id_group FROM ".MOD_BACKEND."_rel_users_groups as rug WHERE rug.id_user=".$values["id_user_active"]."))";
        $data=$this->getRecordsAdHoc($sql);
        

        $sql="SELECT count(f.id) as total,f.code_type_folder FROM ".MOD_FOLDERS."_vw_folders as f WHERE f.offline IS null AND f.id_type_control_point=4 AND f.id IN (";
		$sql.="SELECT fg.id_folder FROM ".MOD_FOLDERS."_rel_folders_groups as fg WHERE fg.id_group IN (";
		$sql.="SELECT ug.id_group FROM ".MOD_BACKEND."_rel_users_groups as ug WHERE ug.id_user=".$values["id_user_active"].")) GROUP BY code_type_folder";
        $totals=$this->getRecordsAdHoc($sql);

		if (evalActionPermissions("REVISORES",$user["data"][0]["groups"])){
            $sql="SELECT count(fi.id) as total, fi.code_type_folder FROM ".MOD_FOLDERS."_vw_folder_items as fi WHERE ";
            $sql.=" fi.folder_offline IS null AND fi.id_type_control_point=3 AND fi.id_type_folder IS NOT null AND fi.id NOT IN (SELECT l.id_folder_item FROM ".MOD_FOLDERS."_folder_items_log as l WHERE l.id_folder_item=fi.id AND l.id_user=".$values["id_user_active"].") AND ";
            $sql.=" fi.id_folder IN (SELECT rfg.id_folder FROM ".MOD_FOLDERS."_rel_folders_groups as rfg WHERE rfg.id_group IN (SELECT rug.id_group FROM ".MOD_BACKEND."_rel_users_groups as rug WHERE rug.id_user=".$values["id_user_active"]."))";
            $sql.=" GROUP BY fi.code_type_folder";
            $data_revisar=$this->getRecordsAdHoc($sql);
        }

        if (evalActionPermissions("PUBLICADORES",$user["data"][0]["groups"])){
            $sql="SELECT count(fi.id) as total, fi.code_type_folder FROM ".MOD_FOLDERS."_vw_folder_items as fi WHERE ";
            $sql.=" fi.folder_offline IS null AND fi.id_type_control_point=2 AND fi.id_type_folder IS NOT null AND fi.id NOT IN (SELECT l.id_folder_item FROM ".MOD_FOLDERS."_folder_items_log as l WHERE l.id_folder_item=fi.id AND l.id_user=".$values["id_user_active"].") AND ";
            $sql.=" fi.id_folder IN (SELECT rfg.id_folder FROM ".MOD_FOLDERS."_rel_folders_groups as rfg WHERE rfg.id_group IN (SELECT rug.id_group FROM ".MOD_BACKEND."_rel_users_groups as rug WHERE rug.id_user=".$values["id_user_active"]."))";
            $sql.=" GROUP BY fi.code_type_folder";
            $data_publicar=$this->getRecordsAdHoc($sql);
        }

        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"Records",
            "table"=>$this->table,
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>$data,
            "data_publicar"=>$data_publicar,
            "data_revisar"=>$data_revisar,
			"totals"=>$totals,
        );
    }
    public function priority($values){
       return $this->save(array("id"=>$values["id"]),array("priority"=>$values["priority"]));
    }
    public function fileLoader($values){
        try {
            $file=base64_decode($values["data"]);
            $values["where"]=("data='".$file."'");
            $ret=$this->get($values);
            if ($ret["status"]!="OK"){throw new Exception($ret["message"],(int)$ret["code"]);}
            if (isset($ret["data"][0])) {
               $filename=basename($ret["data"][0]["data"]);
               $ret["message"]=getFileBinSSH($ret["data"][0]["data"]);
               $ret["mime"]=$ret["data"][0]["mime"];
               $ret["mode"]=$values["mode"];
               $ret["filename"]=$filename;
               $ret["indisk"]=true;
            }
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function fileExternal($values){
        try {
            $file=base64_decode($values["data"]);
            $values["where"]=("data='".$file."'");
            $ret=$this->get($values);
            if ($ret["status"]!="OK"){throw new Exception($ret["message"],(int)$ret["code"]);}
            logGeneral($this,$values,__METHOD__);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "title"=>$ret["data"][0]["data"],
                "body"=>$ret["data"][0]["description"],
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function statusFolderItem($values){
       switch($values["status"]){
          case "ready":
             return logFolderItems($this,$values,lang('msg_viewed'));
          default:
             return unLogFolderItems($this,$values);
       }
    }
}
