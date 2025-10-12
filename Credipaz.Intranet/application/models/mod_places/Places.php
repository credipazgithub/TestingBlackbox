<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Places extends MY_Model {
    public $GOOGLE_PLACES_KEY="AIzaSyAm2l3M0cVh_FZ-fa7R5K81iirb2lWZne4";
    public $GOOGLE_DIRECTIONS_KEY ="AIzaSyAm2l3M0cVh_FZ-fa7R5K81iirb2lWZne4";

    public function __construct()
    {
        parent::__construct();
    }

	public function get($values){
	   $values["view"]="vw_sucursales";
	   return parent::get($values);
	}

    public function brow($values){
        try {
            $this->view="vw_places";
            $values["order"]="description ASC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>true,
                "edit"=>true,
                "delete"=>true,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"created","format"=>"date"),
                array("field"=>"code","format"=>"code"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"type_place","format"=>"type"),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description")),
                array("name"=>"browser_id_type_place", "operator"=>"=","fields"=>array("id_type_place")),
            );
            $values["controls"]=array(
			   "<span class='badge badge-primary'>Tipo</span>".comboTypePlaces($this)
			);

            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $this->view="vw_places";
            $values["interface"]=(MOD_PLACES."/places/abm");
            $values["page"]=1;
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            $parameters_id_type_place=array(
                "model"=>(MOD_PLACES."/Type_places"),
                "table"=>"type_places",
                "name"=>"id_type_place",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"id_type_place"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"","order"=>"description ASC","pagesize"=>-1),
            );
            $values["controls"]=array(
                "id_type_place"=>getCombo($parameters_id_type_place,$this),
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
                    'lat' => $values["lat"],
                    'lng' => $values["lng"],
                    'icon' => $values["icon"],
                    'id_type_place' => secureEmptyNull($values,"id_type_place"),
                    'address' => $values["address"],
                    'open' => $values["open"],
                    'transport' => $values["transport"],
                );
            } else {
                $fields = array(
                    'code' => $values["code"],
                    'description' => $values["description"],
                    'fum' => $this->now,
                    'lat' => $values["lat"],
                    'lng' => $values["lng"],
                    'icon' => $values["icon"],
                    'id_type_place' => secureEmptyNull($values,"id_type_place"),
                    'address' => $values["address"],
                    'open' => $values["open"],
                    'transport' => $values["transport"],
                );
            }
            return parent::save($values,$fields);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function directions($values){
       try {
            if (!isset($values["language"]) or $values["language"]==""){$values["language"]="es";}
            if (!isset($values["mode"]) or $values["mode"]==""){$values["mode"]="driving";}
            if (!isset($values["units"]) or $values["units"]==""){$values["units"]="metric";}
            $mode=$values["mode"];
            $origin=$values["origin"];
            $destination=$values["destination"];
            $sql="DELETE ".MOD_BACKEND."_directions_cache WHERE dead_cache<getdate();";
            $this->dbLayerExecuteWS("nothing",$sql,"",null);

            $sql = "SELECT * FROM mod_backend_directions_cache WHERE origin='".$origin."' AND destination='".$destination."' AND mode='".$mode."' AND dead_cache>getdate();";
            $prevCheck = $this->dbLayerExecuteWS("records",$sql,"",null);
            if($prevCheck["totalrecords"]>0){
                $ret=$this->getRecordsAdHoc($sql);
                $directions=unserialize($ret[0]["data_cache"]);
            } else {        
                $url="https://maps.googleapis.com/maps/api/directions/json?origin=".$origin."&destination=".$destination."&mode=".$mode."&language=".$values["language"]."&units=".$values["units"]."&key=".$this->GOOGLE_DIRECTIONS_KEY;
                $directions=$this->getUrlContent($url);
                $date = strtotime("+30 day");
                $data_cache=serialize($directions);
                $cache = array(
                    'created'=>$this->now,
                    'verified'=>$this->now,
                    'fum'=>$this->now,
                    'origin' => $origin,
                    'destination' => $destination,
                    'mode' => $mode,
                    'dead_cache'=>date("Y-m-d H:i:s",$date),
                    'data_cache'=>$data_cache,
                );
                $DIRECTIONS_CACHE=$this->createModel(MOD_BACKEND,"directions_cache","directions_cache");
                $DIRECTIONS_CACHE->save(array("id"=>0,$cache));
            }            
            $directions=json_decode($directions, true);
            return $directions;
       } catch(Exception $e){
            return null;
       }
    }
    private function getUrlContent($url) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $data = curl_exec($ch);
	    curl_close($ch);
        return $data;
    }

    function getReverse($values){
        try {
		    if (!isset($values["address"])){$values["address"]="";}
		    if ($values["address"]==""){throw new Exception("No se puede geolocalizar una dirección no provista");}
			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($values["address"])."&key=".$this->GOOGLE_PLACES_KEY;
			$places = $this->getUrlContent($url);
			$places = json_decode($places, true);
            $return=array(
                "status"=>"OK",
                "origin"=>"V1 model",
                "source"=>"getReverse",
                "code"=> "200",
                "lat"=>$places["results"][0]["geometry"]["location"]["lat"],
                "lng"=>$places["results"][0]["geometry"]["location"]["lng"]
            );
        }
        catch(Exception $e) {
            $return=array(
                "status"=>"ERROR",
                "origin"=>"V1 model",
                "source"=>"getReverse",
                "code"=> $e->getCode(),
                "message"=>$e->getMessage(),
                "lat"=>0,
                "lng"=>0
            );
        }
        return $return;
    }

}
