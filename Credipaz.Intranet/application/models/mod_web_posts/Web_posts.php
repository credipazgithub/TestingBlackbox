<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Web_posts extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function getByCode($values){
        try {
            $FILES_ATTACHED=$this->createModel(MOD_BACKEND,"Files_attached","Files_attached");
		    if(!isset($values["code"])){throw new Exception(lang("error_5116"),5116);}
			$values["where"]=("code='".$values["code"]."'");
			$ret=$this->get($values);
			$x=0;
			foreach($ret["data"] as $record){
				$param=array("where"=>("id_rel=".$record["id"]." AND table_rel='".MOD_WEB_POSTS."_web_posts'"));
				$files=$FILES_ATTACHED->get($param);
				$i=0;
				foreach($files["data"] as $file){
					$files["data"][$i]["src"]="https://intranet.credipaz.com".PREFIX_FILEGET.$file["src"];
					$i+=1;
				};
				$ret["data"][$x]["files"]=$files["data"];
				$x+=1;
			};
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function getByCodeByType($values){
        try {
            $FILES_ATTACHED=$this->createModel(MOD_BACKEND,"Files_attached","Files_attached");
		    if(!isset($values["id_type_post"])){throw new Exception(lang("error_5119"),5119);}
			$values["where"]=("id_type_post='".$values["id_type_post"]."'");
			$ret=$this->get($values);
			$x=0;
			foreach($ret["data"] as $record){
				$param=array("where"=>("id_rel=".$record["id"]." AND table_rel='".MOD_WEB_POSTS."_web_posts'"));
				$files=$FILES_ATTACHED->get($param);
				$i=0;
				foreach($files["data"] as $file){
					$files["data"][$i]["src"]="https://intranet.credipaz.com".PREFIX_FILEGET.$file["src"];
					$i+=1;
				};
				$ret["data"][$x]["files"]=$files["data"];
				$x+=1;
			};
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function get($values){
        $ret=parent::get($values);

        //log_message("error", "RELATED VALUES ".json_encode($values,JSON_PRETTY_PRINT));
        //log_message("error", "RELATED GET ".json_encode($ret,JSON_PRETTY_PRINT));

        return $ret;
    }
    public function brow($values){
        try {
            $values["order"]="fum DESC";
			$this->view="vw_web_posts";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>true,
                "offline"=>true,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"fum","format"=>"date"),
                array("field"=>"created","format"=>"date"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"code","format"=>"code"),
                array("field"=>"rewrite","format"=>"text"),
                array("field"=>"type_post","format"=>"type"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description","rewrite")),
                array("name"=>"browser_id_type_post", "operator"=>"=","fields"=>array("id_type_post")),
            );

            $values["controls"]=array(
                "<span class='badge badge-primary'>Tipo</span>".comboTypePosts($this),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_WEB_POSTS."/web_posts/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_id_type_post=array(
                "model"=>(MOD_WEB_POSTS."/type_posts"),
                "table"=>"type_posts",
                "name"=>"id_type_post",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_post"),
                "id_field"=>"id",
                "description_field"=>"description"
            );
            $parameters_id_parent=array(
                "model"=>(MOD_WEB_POSTS."/Web_posts"),
                "table"=>"web_posts",
                "name"=>"id_parent",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_parent"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id_parent IS null","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_section=array(
                "model"=>(MOD_WEB_POSTS."/Sections"),
                "table"=>"sections",
                "name"=>"id_section",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_WEB_POSTS."/Rel_web_posts_sections"),"table"=>"rel_web_posts_sections","id_field"=>"id_web_post","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );
            $parameters_id_group=array(
                "model"=>(MOD_BACKEND."/Groups"),
                "table"=>"groups",
                "name"=>"id_group",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_WEB_POSTS."/Rel_web_posts_groups"),"table"=>"rel_web_posts_groups","id_field"=>"id_web_post","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );
            $values["controls"]=array(
                "id_type_post"=>getCombo($parameters_id_type_post,$this),
                "id_parent"=>getCombo($parameters_id_parent,$this),
                "id_section"=>getMultiSelect($parameters_id_section,$this),
                "id_group"=>getMultiSelect($parameters_id_group,$this),
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
            $fields=null;
            if($id==0){
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'created' => $this->now,
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    'rewrite' => $values["rewrite"],
                    'tags' => $values["tags"],
                    'id_creator' => secureEmptyNull($values,"id_user_active"),
                    'id_parent' => secureEmptyNull($values,"id_parent"),
                    'date_from' => secureEmptyNull($values,"date_from"),
                    'date_to' => secureEmptyNull($values,"date_to"),
                    'body_post' => $values["body_post"],
                    'brief_post' => $values["brief_post"],
                    'allow_comments' => $values["allow_comments"],
                    'priority' => $values["priority"],
                    'hide_title' => $values["hide_title"],
                    'is_menu' => $values["is_menu"],
                    'is_fullscreen' => $values["is_fullscreen"],
                    'id_type_post' => secureEmptyNull($values,"id_type_post"),
                );
            } else {
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'rewrite' => $values["rewrite"],
                    'tags' => $values["tags"],
                    'id_parent' => secureEmptyNull($values,"id_parent"),
                    'date_from' => secureEmptyNull($values,"date_from"),
                    'date_to' => secureEmptyNull($values,"date_to"),
                    'body_post' => $values["body_post"],
                    'brief_post' => $values["brief_post"],
                    'allow_comments' => $values["allow_comments"],
                    'priority' => $values["priority"],
                    'hide_title' => $values["hide_title"],
                    'is_menu' => $values["is_menu"],
                    'is_fullscreen' => $values["is_fullscreen"],
                    'id_type_post' => secureEmptyNull($values,"id_type_post"),
                );
            }
            $saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){
               $params_sections=array(
                    "module"=>MOD_WEB_POSTS,
                    "model"=>"Rel_web_posts_sections",
                    "table"=>"rel_web_posts_sections",
                    "key_field"=>"id_web_post",
                    "key_value"=>$saved["data"]["id"],
                    "rel_field"=>"id_section",
                    "rel_values"=>(isset($values["id_section"]) ? $values["id_section"] :array())
               );
               $params_groups=array(
                    "module"=>MOD_WEB_POSTS,
                    "model"=>"Rel_web_posts_groups",
                    "table"=>"rel_web_posts_groups",
                    "key_field"=>"id_web_post",
                    "key_value"=>$saved["data"]["id"],
                    "rel_field"=>"id_group",
                    "rel_values"=>(isset($values["id_group"]) ? $values["id_group"] :array())
               );
               parent::saveRelations($params_sections);
               parent::saveRelations($params_groups);
               $arr=explode("|",$values["tags"]);
               if ($arr[0]=="push") {
                   $params=array(
                     'id_type_subscription'=>$arr[1],
                     'id_type_command'=>$arr[2],
                     'id_type_target'=>$arr[3],
                     'subject'=>$arr[4],
                     'body'=>$arr[5],
                     'image_url'=>$arr[6],
                     'to_one'=>$arr[7],
                  );
                  //$PUSH_OUT=$this->createModel(MOD_PUSH,"Push_out","Push_out");
                  //$PUSH_OUT->save($params);
               }
            }
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function delete($values){
        try {
            $deleted=parent::delete($values);
            if($deleted["status"]=="OK"){
               $params_sections=array(
                    "module"=>MOD_WEB_POSTS,
                    "model"=>"Rel_web_posts_sections",
                    "table"=>"rel_web_posts_sections",
                    "key_field"=>"id_web_post",
                    "key_value"=>$values["id"],
               );
               $params_groups=array(
                    "module"=>MOD_WEB_POSTS,
                    "model"=>"Rel_web_posts_groups",
                    "table"=>"rel_web_posts_groups",
                    "key_field"=>"id_web_post",
                    "key_value"=>$values["id"],
               );
               parent::deleteRelations($params_sections);
               parent::deleteRelations($params_groups);
            }
            return $deleted;
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function fileLoader($values){
        try {
            $FILES_ATTACHED=$this->createModel(MOD_BACKEND,"Files_attached","Files_attached");
            $file=base64_decode($values["data"]);
            $values["where"]=("src='".$file."' AND table_rel='".MOD_WEB_POSTS."_web_posts'");
            $ret=$FILES_ATTACHED->get($values);
            if ($ret["status"]!="OK"){throw new Exception($ret["message"],(int)$ret["code"]);}
            if (isset($ret["data"][0])) {
               $ret["message"]=getFileBinSSH($file);
               $ret["mime"]=getMimeType($file);
               $ret["mode"]=$values["mode"];
               $ret["filename"]=basename($file);
               $ret["indisk"]=true;
            }
            return $ret;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
