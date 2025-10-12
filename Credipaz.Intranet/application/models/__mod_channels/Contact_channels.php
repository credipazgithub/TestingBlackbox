<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Contact_channels extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $this->view="vw_contact_channels";
            $values["order"]="description ASC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>false,
                "offline"=>true,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"created","format"=>"date"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"type_contact_channel","format"=>"type"),
                array("field"=>"","format"=>null),
            );

            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description")),
                array("name"=>"browser_id_type_folder", "operator"=>"=","fields"=>array("id_type_contact_channel")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Tipo</span>".comboTypeContactChannels($this),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $this->view="vw_contact_channels";
            $values["interface"]=(MOD_CHANNELS."/contact_channels/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_id_type_contact_channel=array(
                "model"=>(MOD_CHANNELS."/Type_contact_channels"),
                "table"=>"type_contact_channels",
                "name"=>"id_type_contact_channel",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_contact_channel"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
            $values["controls"]=array(
                "id_type_contact_channel"=>getCombo($parameters_id_type_contact_channel,$this),
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
            if (!isset($values["code"])){$values["code"]=null;}
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
                        'id_type_contact_channel' => secureEmptyNull($values,"id_type_contact_channel"),
                        'username' => $values["username"],
                        'alias' => $values["alias"],
                        'password' => $values["password"],
                        'structure' => $values["structure"],
                        'server_key' => $values["server_key"],
                        'send_endpoint' => $values["send_endpoint"],
                        'id_owner' => $values["id_owner"],
                        'api_key' => $values["api_key"],
                        'api_secret' => $values["api_secret"],
                        'access_token' => $values["access_token"],
                        'access_token_secret' => $values["access_token_secret"],
                        'imap_inbox' => $values["imap_inbox"],
                        'imap_status' => $values["imap_status"],
                        'imap_address' => $values["imap_address"],
                        'shared_channel' => $values["shared_channel"],
                        'active_channel' => $values["active_channel"],
                        'out_ready' => $values["out_ready"],
                        'in_ready' => $values["in_ready"],
                        'allow_external' => $values["allow_external"],
                        'allow_manual' => $values["allow_manual"],
                    );
                }
            } else {
                if ($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'fum' => $this->now,
                        'id_type_contact_channel' => secureEmptyNull($values,"id_type_contact_channel"),
                        'username' => $values["username"],
                        'alias' => $values["alias"],
                        'password' => $values["password"],
                        'structure' => $values["structure"],
                        'server_key' => $values["server_key"],
                        'send_endpoint' => $values["send_endpoint"],
                        'id_owner' => $values["id_owner"],
                        'api_key' => $values["api_key"],
                        'api_secret' => $values["api_secret"],
                        'access_token' => $values["access_token"],
                        'access_token_secret' => $values["access_token_secret"],
                        'imap_inbox' => $values["imap_inbox"],
                        'imap_status' => $values["imap_status"],
                        'imap_address' => $values["imap_address"],
                        'shared_channel' => $values["shared_channel"],
                        'active_channel' => $values["active_channel"],
                        'out_ready' => $values["out_ready"],
                        'in_ready' => $values["in_ready"],
                        'allow_external' => $values["allow_external"],
                        'allow_manual' => $values["allow_manual"],
                    );
                }
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
