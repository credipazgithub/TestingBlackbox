<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Functions extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    
    public function brow($values){
        try {
            $values["order"]="description ASC";
            $values["records"]=$this->get($values);
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description")),
            );
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>true,
                "offline"=>true,
            );

            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_BACKEND."/functions/abm");
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters=array(
                "model"=>(MOD_BACKEND."/Functions"),
                "table"=>"functions",
                "name"=>"id_parent",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_parent"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id_parent IS null","order"=>"description ASC","pagesize"=>-1),
            );

            $parameters_id_application=array(
                "model"=>(MOD_BACKEND."/Applications"),
                "table"=>"applications",
                "name"=>"id_application",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_BACKEND."/Rel_functions_applications"),"table"=>"rel_functions_applications","id_field"=>"id_function","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "function"=>"get"
            );
            $values["controls"]=array(
                "id_parent"=>getCombo($parameters,$this),
                "id_application"=>getMultiSelect($parameters_id_application,$this)
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
                    'icon' => $values["icon"],
                    'id_parent' => secureEmptyNull($values,"id_parent"),
                    'data_module' => $values["data_module"],
                    'data_model' => $values["data_model"],
                    'data_table' => $values["data_table"],
                    'data_action' => $values["data_action"],
                    'priority' => $values["priority"],
                    'running' => $values["running"],
                    'brief' => $values["brief"],
                    'show_brief' => $values["show_brief"],
                    'alert_build' => $values["alert_build"],
                );
            } else {
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'icon' => $values["icon"],
                    'id_parent' => secureEmptyNull($values,"id_parent"),
                    'data_module' => $values["data_module"],
                    'data_model' => $values["data_model"],
                    'data_table' => $values["data_table"],
                    'data_action' => $values["data_action"],
                    'priority' => $values["priority"],
                    'running' => $values["running"],
                    'brief' => $values["brief"],
                    'show_brief' => $values["show_brief"],
                    'alert_build' => $values["alert_build"],
                );
            }
            $saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){
               $params=array(
                    "module"=>MOD_BACKEND,
                    "model"=>"Rel_functions_applications",
                    "table"=>"Rel_functions_applications",
                    "key_field"=>"id_function",
                    "key_value"=>$saved["data"]["id"],
                    "rel_field"=>"id_application",
                    "rel_values"=>(isset($values["id_application"]) ? $values["id_application"] :array())
               );
               parent::saveRelations($params);
            }
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function menuAPI($values)
    {
        try {
            $values["id_app"] = 11;
            $values["pagesize"] = -1;
            $values["where"] = $this->resolveWhereMenu(null, $values["id_user_active"], $values["id_app"]);
            $values["order"] = "priority ASC";
            $values["pagesize"] = -1;
            $menu = $this->get($values);
            $i = 0;
            foreach ($menu["data"] as $function) {
                $values["where"] = $this->resolveWhereMenu($function["id"], $values["id_user_active"], $values["id_app"]);
                $submenu = $this->get($values);
                $menu["data"][$i]["label"] = lang($function["code"]);
                $y = 0;
                foreach ($submenu["data"] as $subfunction) {
                    $submenu["data"][$y]["label"] = lang($subfunction["code"]);
                    $y += 1;
                }
                $menu["data"][$i]["submenu"] = $submenu["data"];
                $i += 1;
            }
            return $menu;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function menuTree($values){
        try {
            if (!isset($values["id_app"])){$values["id_app"]=$values["id_application"];}
            if (!isset($values["id_app"])){$values["id_app"]=0;}
            if ($values["id_app"]==null or $values["id_app"]==""){$values["id_app"]=0;}
            $values["pagesize"]=-1;
            $values["where"]=$this->resolveWhereMenu(null,$values["id_user_active"],$values["id_app"]);
            $values["order"]="priority ASC";
            $values["pagesize"]=-1;
			$menu=$this->get($values);
            $i=0;
            foreach ($menu["data"] as $function){
                $values["where"]=$this->resolveWhereMenu($function["id"],$values["id_user_active"],$values["id_app"]);
                $submenu=$this->get($values);
                $menu["data"][$i]["label"]=lang($function["code"]);
                $y=0;
                foreach ($submenu["data"] as $subfunction){
                   $submenu["data"][$y]["label"]=lang($subfunction["code"]);
                   $y+=1;
                }
                $menu["data"][$i]["submenu"]=$submenu["data"];
                $i+=1;
            }
            return $menu;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function menuTreeFull(){
        try {
            $values["where"]="id_parent IS null AND offline IS null";
            $values["order"]="priority ASC";
            $values["pagesize"]=-1;
            $menu=$this->get($values);
            $i=0;
            foreach ($menu["data"] as $function){
                $values["where"]="id_parent=".$function["id"]." AND offline IS null";
                $submenu=$this->get($values);
                $menu["data"][$i]["submenu"]=$submenu["data"];
                $i+=1;
            }
            return $menu;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function menuLevelOne($values)
    {
        try {
            $values["where"] = $this->resolveWhereMenu(null, $values["id_user_active"], $values["id_app"]);
            $values["order"] = "description ASC";
            $values["pagesize"] = -1;
            return $this->get($values);
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function form($values){
        try {
            $parameters_functions=array(
                "model"=>(MOD_BACKEND."/Functions"),
                "table"=>"functions",
                "name"=>"id_function",
                "class"=>"form-control cboPivotFunctions",
                "empty"=>true,
                "id_actual"=>-1,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"offline IS null","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_groups=array(
                "model"=>(MOD_BACKEND."/Groups"),
                "table"=>"groups",
                "name"=>"id_group",
                "class"=>"form-control cboPivotGroups",
                "empty"=>true,
                "id_actual"=>-1,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"offline IS null","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_users=array(
                "model"=>(MOD_BACKEND."/Users"),
                "table"=>"user",
                "name"=>"id_user_map",
                "class"=>"form-control cboPivotUsers",
                "empty"=>true,
                "id_actual"=>-1,
                "id_field"=>"id",
                "description_field"=>"username",
                "get"=>array("where"=>"id_type_user IN (77,78) AND offline IS null","order"=>"username ASC","pagesize"=>-1),
            );

            $data["controls"]=array(
                "id_function"=>getCombo($parameters_functions,$this),
                "id_group"=>getCombo($parameters_groups,$this),
                "id_user_map"=>getCombo($parameters_users,$this),
            );

            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_permissions_map"));
            $html=$this->load->view(MOD_BACKEND."/functions/form",$data,true);

            
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

    private function resolveWhereMenu($nodo,$id_user,$id_application){
        if($nodo!=null){$nodo=("=".$nodo);} else{$nodo=" IS null";}
        $where="id_parent ".$nodo." AND offline IS null";
        $where.=" AND id IN ";
        $where.=" (";
        $where.="    SELECT id_function FROM ".MOD_BACKEND."_rel_groups_functions WHERE id_group IN ";
        $where.="       (";
        $where.="          SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$id_user;
        $where.="       )";
        $where.=" )";
        $where.=" AND id IN ";
        $where.=" (";
        $where.="    SELECT id_function FROM ".MOD_BACKEND."_rel_functions_applications WHERE id_application=".$id_application;
        $where.=" )";
        return $where;
    }
}
