<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Type_categories extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getMobile($values){
        $values["order"]="priority ASC,description ASC";
        $values["where"]=("id not in (200,125,126,127,128,129,130,131) AND code NOT IN ('FARMACIA','CARTILLA','ATENCION','ASISTENCIA','SEGURO') AND isnull(parentId,0)=0"); //Evita las categorias que no se deben mostrar en los links directos, como por ejemplo FARMACIA
        $values["records"]=$this->get($values);
        $i=0;
        foreach ($values["records"]["data"] as $record){
           switch((int)$record["id"]){
              case 118://
                 $values["records"]["data"][$i]["code"]="SALUD";
                 break;
              case 120://
                 $values["records"]["data"][$i]["code"]="GASTRONOMIA";
                 break;
              case 122://
                 $values["records"]["data"][$i]["code"]="SUPERMERCADOS";
                 break;
              case 119://
                 $values["records"]["data"][$i]["code"]="HOGAR";
                 break;
           }
           $i+=1;
        }
        return $values["records"];
    }

    public function brow($values){
        try {
            $values["order"]="description ASC";
            $values["pagesize"]=-1;
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>false,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"code","format"=>"code"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"priority","format"=>"number"),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["interface"]=(MOD_CLUB_REDONDO."/type_categories/abm");
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
                        'sinopsys' => $values["sinopsys"],
                        'priority' => $values["priority"],
                        'external_id'=>$values["external_id"],
                        'url'=>$values["url"],
                        'items'=>$values["items"],
                        'id_parent'=>secureEmptyNull($values,"id_parent"),
                        'parentId'=>secureEmptyNull($values,"parentId"),
                    );
                }
            } else {
                if ($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'fum' => $this->now,
                        'sinopsys' => $values["sinopsys"],
                        'priority' => $values["priority"],
                        'external_id'=>$values["external_id"],
                        'url'=>$values["url"],
                        'items'=>$values["items"],
                        'id_parent'=>secureEmptyNull($values,"id_parent"),
                        'parentId'=>secureEmptyNull($values,"parentId"),
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
