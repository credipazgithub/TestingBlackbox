<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Threads extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $this->view="vw_threads";
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>array(
                    "alternate"=>"",
                    "conditions"=>array(
                           array("field"=>"hidden","operator"=>"!=","value"=>"1"),
                           array("field"=>"processed","operator"=>"==","value"=>""),
                        )
                    ),
                "delete"=>array(
                    "conditions"=>array(
                           array("field"=>"hidden","operator"=>"!=","value"=>"1","alternate"=>""),
                           array("field"=>"processed","operator"=>"==","value"=>""),
                        )
                    ),
                "offline"=>false,
            );
            $values["order"]="description ASC";
            $values["records"]=$this->get($values);
             
            $ddProcess="<button type='button' class='btn btn-raised btn-record-process btn-success btn-sm' ".buildDataSegment($values)." data-action=''><i class='material-icons'>play_arrow</i> ".lang('b_process')."</button>";
            $ddReady="<span class='badge badge-success'><i class='material-icons'>done_all</i></span>";
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"scheduled","format"=>"date"),
                array("field"=>"validto","format"=>"date"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"processed","format"=>"datetime"),
                array("field"=>"hide_processed","forcedlabel"=>"","html"=>$ddProcess,"format"=>"conditional#block","whenready"=>$ddReady),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description","subject")),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_CHANNELS."/threads/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_id_thread_condition=array(
                "model"=>(MOD_CHANNELS."/Threads_conditions"),
                "table"=>"threads_conditions",
                "name"=>"id_thread_condition",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_thread_condition"),
                "id_field"=>"IdCampania",
                "description_field"=>"description",
                "get"=>array("view"=>"vw_threads_conditions","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_thread=array(
                "model"=>(MOD_CHANNELS."/Type_threads"),
                "table"=>"type_threads",
                "name"=>"id_type_thread",
                "class"=>"form-control dbase validate",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_thread"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"hidden!=1","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_contact_channel=array(
                "model"=>(MOD_CHANNELS."/Contact_channels"),
                "table"=>"contact_channels",
                "name"=>"id_contact_channel",
                "class"=>"multiselect dbase",
                "actual"=>array("model"=>(MOD_CHANNELS."/Rel_threads_contact_channels"),"table"=>"rel_threads_contact_channels","id_field"=>"id_thread","id_value"=>$values["id"]),
                "id_field"=>"id",
                "description_field"=>"description",
                "options"=>array("order"=>"description ASC","pagesize"=>-1),
                "function"=>"get",
            );
            $values["controls"]=array(
                "id_thread_condition"=>getCombo($parameters_id_thread_condition,$this),
                "id_type_thread"=>getCombo($parameters_id_type_thread,$this),
                "id_contact_channel"=>getMultiSelect($parameters_id_contact_channel,$this),
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
                    'id_user' => secureEmptyNull($values,"id_user_active"),
                    'subject' => $values["description"],
                    'body' => $values["body"],
                    'message' => $values["message"],
                    'short_message' => $values["short_message"],
                    'id_type_thread' => secureEmptyNull($values,"id_type_thread"),
                    'id_thread_condition' => secureEmptyNull($values,"id_thread_condition"),
                    'keywords_positive' => $values["keywords_positive"],
                    'keywords_negative' => $values["keywords_negative"],
                    'scheduled' => secureEmptyNull($values,"scheduled"),
                    'validto' => secureEmptyNull($values,"validto"),
                );
            } else {
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'subject' => $values["description"],
                    'body' => $values["body"],
                    'message' => $values["message"],
                    'short_message' => $values["short_message"],
                    'id_type_thread' => secureEmptyNull($values,"id_type_thread"),
                    'id_thread_condition' => secureEmptyNull($values,"id_thread_condition"),
                    'keywords_positive' => $values["keywords_positive"],
                    'keywords_negative' => $values["keywords_negative"],
                    'scheduled' => secureEmptyNull($values,"scheduled"),
                    'validto' => secureEmptyNull($values,"validto"),
                );
            }
            $saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){
               $params_channels=array(
                    "module"=>MOD_CHANNELS,
                    "model"=>"Rel_threads_contact_channels",
                    "table"=>"Rel_threads_contact_channels",
                    "key_field"=>"id_thread",
                    "key_value"=>$saved["data"]["id"],
                    "rel_field"=>"id_contact_channel",
                    "rel_values"=>(isset($values["id_contact_channel"]) ? $values["id_contact_channel"] :array())
               );
               parent::saveRelations($params_channels);
           }
           return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    /*FIRST -> Here the buil messages queue is build!*/
    public function process($values){
        try {
            //Retrieve actual processing Thread
            $this->view="vw_threads";
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $thread=$this->get($values);
            if ($thread["data"][0]["processed"]!=""){throw new Exception(lang('error_3000'),3000);}

            //Mark as processed at beginning!
            $fields = array('processed' => $this->now);
            $ret=parent::save($values,$fields);

            //Delete all previous targets for this Thread
            $THREADS_TARGETS=$this->createModel(MOD_CHANNELS,"Threads_targets","Threads_targets");
            $THREADS_TARGETS->deleteByWhere("id_thread=".$values["id"]);

            //Create targets list for this Thread
            $fieldList="code,[description],created,verified,offline,fum,id_thread,IdCampania,IdCliente,sCodigoArea,sNumero,eMail,Sexo,Nombre,DocTipo,DocNro,processed";
            $selectToInsert="SELECT code,[description],created,verified,offline,fum,".$values["id"].",IdCampania,IdCliente,sCodigoArea,sNumero,eMail,Sexo,Nombre,DocTipo,DocNro,null FROM ";
            $selectToInsert.=MOD_CHANNELS."_vw_threads_universe WHERE IdCampania=".$thread["data"][0]["id_thread_condition"];
            if($thread["data"][0]["skip_blacklist"]!=1) {$selectToInsert.=" AND IdCliente NOT IN (SELECT IdCliente FROM ".MOD_CHANNELS."_blacklist)";}
            $params=array("fieldList"=>$fieldList,"selectToInsert"=>$selectToInsert);
            $THREADS_TARGETS->insertBySelect($params);

            //Send via each related contact_channel
            $CONTACT_CHANNELS=$this->createModel(MOD_CHANNELS,"Contact_channels","Contact_channels");
            $CONTACT_CHANNELS->view="vw_contact_channels";
            $contact_channel=$CONTACT_CHANNELS->get(array("where"=>"offline IS null AND id IN (SELECT id_contact_channel FROM mod_channels_rel_threads_contact_channels WHERE id_thread=".$values["id"].")"));
            
            //Delete all previous relations for this Thread
            $REL_THREADS_TARGETS_CONTACT_CHANNELS=$this->createModel(MOD_CHANNELS,"Rel_threads_targets_contact_channels","Rel_threads_targets_contact_channels");
            $REL_THREADS_TARGETS_CONTACT_CHANNELS->deleteByWhere("id_thread=".$values["id"]);

            //Create relations for automatizated sending!
            $fieldList="id_thread_target,id_contact_channel,id_thread,processed";
            foreach($contact_channel["data"] as $channel){
                $selectToInsert="SELECT id,".$channel["id"].",".$values["id"].",null FROM ".MOD_CHANNELS."_threads_targets WHERE id_thread=".$values["id"];
                $params=array("fieldList"=>$fieldList,"selectToInsert"=>$selectToInsert);
                $REL_THREADS_TARGETS_CONTACT_CHANNELS->insertBySelect($params);
            }
            $fields=array('frozen' => $this->now);
            $ret=parent::save($values,$fields);
            if($ret["status"]!="OK"){throw new Exception($ret["message"],(int)$ret["code"]);}
            return $ret;
        }
        catch (Exception $e){
            $ret=logError($e,__METHOD__ );
        }
        return $ret;
    }
    
    /*SECOND -> Here really the messages go away!*/
    public function send($values){
       try {
            $processed=array();
            $REL_THREADS_TARGETS_CONTACT_CHANNELS=$this->createModel(MOD_CHANNELS,"Rel_threads_targets_contact_channels","Rel_threads_targets_contact_channels");
            $CONTACT_CHANNELS=$this->createModel(MOD_CHANNELS,"Contact_channels","Contact_channels");
            $CONTACT_CHANNELS->view="vw_contact_channels";
            $contact_channel=$CONTACT_CHANNELS->get(array("order"=>"description"));
            foreach($contact_channel["data"] as $channel){
                if($channel["data_model"]!="") {
                    $ACTIVE=$this->createModel($channel["data_module"],$channel["data_model"],$channel["data_model"]);
                    $REL_THREADS_TARGETS_CONTACT_CHANNELS->view="vw_rel_threads_targets_contact_channels";
                    $items=$REL_THREADS_TARGETS_CONTACT_CHANNELS->get(array("where"=>"id=".$channel["id"]." AND processed IS null AND scheduled<=GETDATE()","order"=>"data_module,data_model"));

                    /*For test pourpose!*/
                    $items=$REL_THREADS_TARGETS_CONTACT_CHANNELS->get(array("where"=>"id_type_contact_channel=3 AND id=".$channel["id"]." AND processed IS null AND scheduled<=GETDATE()","order"=>"data_module,data_model"));

                    if(isset($items["data"][0])) {
                        $response=$ACTIVE->bufferOut($items);
                        array_push($processed,array("model"=>$channel["data_model"],"innerResponse"=>$response));
                    }
                }
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "data"=>$processed
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    /*OFF QUEUE -> Here the channels read for input occurs!*/
    public function retrieve($values){
       try {
            $processed=array();
            $CONTACT_CHANNELS=$this->createModel(MOD_CHANNELS,"Contact_channels","Contact_channels");
            $CONTACT_CHANNELS->view="vw_contact_channels";
            $contact_channel=$CONTACT_CHANNELS->get(array("where"=>"in_ready=1","order"=>"description"));
            /*for test purpouse!*/
            //$contact_channel=$CONTACT_CHANNELS->get(array("where"=>"in_ready=1 and id_type_contact_channel=3","order"=>"description"));

            foreach($contact_channel["data"] as $channel){
                if($channel["data_model"]!="") {
                    $ACTIVE=$this->createModel($channel["data_module"],$channel["data_model"],$channel["data_model"]);
                    $response=$ACTIVE->bufferIn($channel);
                    array_push($processed,array("model"=>$channel["data_model"],"innerResponse"=>$response));
                }
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "data"=>$processed
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
