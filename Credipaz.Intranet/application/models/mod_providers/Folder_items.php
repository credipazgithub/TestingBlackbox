<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Folder_items extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $this->view="vw_folder_items";
            if(isset($values["forced_field"]) and isset($values["forced_value"])) {$values["where"]=($values["forced_field"]."='".$values["forced_value"]."'");}
            if($values["where"]!=""){$values["where"].=" AND ";}
            $values["where"].=("id_type_control_point=4 AND id_folder IN (SELECT id_folder FROM ".MOD_PROVIDERS."_rel_folders_groups WHERE id_group IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"]."))");
            $values["fields"]="*,(SELECT count(id) FROM ".MOD_PROVIDERS."_folder_items_log as l WHERE l.id_folder_item=".MOD_PROVIDERS."_vw_folder_items.id AND l.id_user=".$values["id_user_active"].") as viewed";
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
                $html="<table class='p-0 m-0'>";
                $html.=" <tr>";
                $html.="   <td>".$link."</td>";
                $html.="   <td style='width:10%;'><span class='badge badge-light p-2'>".$item["type_folder"]."</span></td>";
                $html.="   <td style='width:100%;'><div class='p-1 ".$color." status-".$item["id"]."'><span class='badge badge-primary'>".$item["datetime"]."</span> ".$item["description"]."</div></td>";
                $html.="   <td>".$menu."</td>";
                $html.=" </tr>";
                $html.="</table>";
                $values["records"]["data"][$i]["resolved"]=$html;
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
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("description","keywords","folder_keywords")),
                array("name"=>"browser_id_type_folder", "operator"=>"=","fields"=>array("id_type_folder")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Tipo</span>".comboTypeFoldersProviders($this),
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
        $values["where"]="id_type_control_point=4 AND id_folder IN (SELECT id_folder FROM ".MOD_PROVIDERS."_rel_folders_groups WHERE id_group IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$values["id_user_active"]."))";
        $values["fields"]="*,(SELECT count(id) FROM ".MOD_PROVIDERS."_folder_items_log as l WHERE l.id_folder_item=".MOD_PROVIDERS."_vw_folder_items.id AND l.id_user=".$values["id_user_active"].") as viewed";
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
        $user=getUserProfile($this,$values["id_user_active"]);
        $data=null;
        $data_revisar=null;
        $data_publicar=null;
        $sql="SELECT count(fi.id) as total, fi.id_type_folder FROM ".MOD_PROVIDERS."_vw_folder_items as fi WHERE ";
        $sql.=" fi.id_type_control_point=4 AND fi.id_type_folder IS NOT null AND fi.id NOT IN (SELECT l.id_folder_item FROM ".MOD_PROVIDERS."_folder_items_log as l WHERE l.id_folder_item=fi.id AND l.id_user=".$values["id_user_active"].") AND ";
        $sql.=" fi.id_folder IN (SELECT rfg.id_folder FROM ".MOD_PROVIDERS."_rel_folders_groups as rfg WHERE rfg.id_group IN (SELECT rug.id_group FROM ".MOD_BACKEND."_rel_users_groups as rug WHERE rug.id_user=".$values["id_user_active"]."))";
        $sql.=" GROUP BY fi.id_type_folder";
        $data=$this->getRecordsAdHoc($sql);

        if (evalActionPermissions("REVISORES",$user["data"][0]["groups"])){
            $sql="SELECT count(fi.id) as total, fi.id_type_folder FROM ".MOD_PROVIDERS."_vw_folder_items as fi WHERE ";
            $sql.=" fi.id_type_control_point=3 AND fi.id_type_folder IS NOT null AND fi.id NOT IN (SELECT l.id_folder_item FROM ".MOD_PROVIDERS."_folder_items_log as l WHERE l.id_folder_item=fi.id AND l.id_user=".$values["id_user_active"].") AND ";
            $sql.=" fi.id_folder IN (SELECT rfg.id_folder FROM ".MOD_PROVIDERS."_rel_folders_groups as rfg WHERE rfg.id_group IN (SELECT rug.id_group FROM ".MOD_BACKEND."_rel_users_groups as rug WHERE rug.id_user=".$values["id_user_active"]."))";
            $sql.=" GROUP BY fi.id_type_folder";
            $data_revisar=$this->getRecordsAdHoc($sql);
        }

        if (evalActionPermissions("PUBLICADORES",$user["data"][0]["groups"])){
            $sql="SELECT count(fi.id) as total, fi.id_type_folder FROM ".MOD_PROVIDERS."_vw_folder_items as fi WHERE ";
            $sql.=" fi.id_type_control_point=2 AND fi.id_type_folder IS NOT null AND fi.id NOT IN (SELECT l.id_folder_item FROM ".MOD_PROVIDERS."_folder_items_log as l WHERE l.id_folder_item=fi.id AND l.id_user=".$values["id_user_active"].") AND ";
            $sql.=" fi.id_folder IN (SELECT rfg.id_folder FROM ".MOD_PROVIDERS."_rel_folders_groups as rfg WHERE rfg.id_group IN (SELECT rug.id_group FROM ".MOD_BACKEND."_rel_users_groups as rug WHERE rug.id_user=".$values["id_user_active"]."))";
            $sql.=" GROUP BY fi.id_type_folder";
            $data_publicar=$this->getRecordsAdHoc($sql);
        }
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"Records",
            "table"=>$this->table,
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>$data,
            "data_publicar"=>$data_revisar,
            "data_revisar"=>$data_publicar,
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
