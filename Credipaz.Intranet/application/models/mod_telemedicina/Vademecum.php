<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Vademecum extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function brow($values){
        try {
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>false,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                array("forcedlabel"=>"","field"=>"description","format"=>"text"),
                array("forcedlabel"=>"","field"=>"stc_mono","format"=>"text"),
                array("forcedlabel"=>"","field"=>"presentation","format"=>"text"),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("description","stc_mono","presentation")),
                array("name"=>"browser_stc_mono", "operator"=>"like","fields"=>array("stc_mono")),
                array("name"=>"browser_presentation", "operator"=>"like","fields"=>array("presentation")),
            );

            $this->view="vw_vademecum";
            $values["order"]="stc_mono ASC, presentation ASC";
            $values["records"]=$this->get($values);
            //$values["custom_class_new"]="btn-check-paycode";
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function importVademecum($values){
        $FILE='d:\Datos\www\vademecum.csv';
        if ($file = fopen($FILE, "r")) {
            while(!feof($file)) {
               $line=fgets($file);
               $idx=explode(',',$line);
               $fields=array(
                    "code"=>$idx[0],
                    "troquel"=>$idx[1],
                    "barcode"=>$idx[2],
                    "description"=>$idx[3],
                    "presentation"=>$idx[4],
                    "id_laboratory"=>$this->evalDetails("Laboratories",$idx[5]),
                    "rpf"=>0,
                    "id_type_monodrug"=>$this->evalDetails("Type_monodrugs",$idx[7]),
                    "id_type_family"=>$this->evalDetails("Type_families",$idx[8]),
                    "id_type_vademecum"=>3,
                    "discount"=>50,
                    "created"=>$this->now,
                    "verified"=>$this->now,
                    "offline"=>null,
                    "fum"=>$this->now,
               );
               $this->save(array("id"=>0),$fields);
            }
            fclose($file);
        }
    }

    public function evalDetails($model,$code){
       $ACTIVE=$this->createModel(MOD_TELEMEDICINA,$model,$model);
       $record=$ACTIVE->get(array("where"=>"code='".$code."'"));
       $id=0;
       if ($record["totalrecords"]==0) {
           $fields = array(
               'code' => $code,
               'description' => $code,
               'created' => $this->now,
               'verified' => $this->now,
               'fum' => $this->now,
               'offline' => null,
            );
          $saved=$ACTIVE->save(array("id"=>0),$fields);
          $id=$saved["data"]["id"];
       } else {
          $id=$record["data"][0]["id"];
       }
       return $id;
    }
}
