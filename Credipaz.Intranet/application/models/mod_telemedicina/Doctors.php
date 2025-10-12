<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Doctors extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $values["order"]="surname ASC, name ASC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>true,
            );
            $values["columns"]=array(
                array("field"=>"surname","format"=>"text"),
                array("field"=>"name","format"=>"text"),
                array("field"=>"license","format"=>"text"),
                array("field"=>"username","format"=>"status"),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("name","surname","license","username")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_TELEMEDICINA."/doctors/abm");
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
            $fields=null;

            if($id==0){
                $fields = array(
                    'code' => '',
                    'description' => '',
                    'created' => $this->now,
                    'verified' => $this->now,
                    'offline' => null,
                    'fum' => $this->now,
                    'name' => $values["name"],
                    'surname' => $values["surname"],
                    'license' => $values["license"],
                    'image' => $values["image"],
                    'username' => $values["username"],
                    'test' => $values["test"],
                    'email' => $values["email"],
                    'dni' => $values["dni"],
                    'sex' => $values["sex"],
                    'phone' => $values["phone"],
                    'birthday' => $values["birthday"],
                    'mn' => $values["mn"],
                    'mp' => $values["mp"],
                );
            } else {
                $fields = array(
                    'fum' => $this->now,
                    'name' => $values["name"],
                    'surname' => $values["surname"],
                    'license' => $values["license"],
                    'image' => $values["image"],
                    'username' => $values["username"],
                    'test' => $values["test"],
                    'email' => $values["email"],
                    'dni' => $values["dni"],
                    'sex' => $values["sex"],
                    'phone' => $values["phone"],
                    'birthday' => $values["birthday"],
                    'mn' => $values["mn"],
                    'mp' => $values["mp"],
                );
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
