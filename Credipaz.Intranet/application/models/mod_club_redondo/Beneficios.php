<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Beneficios extends MY_Model {
    public $headersAPIGet = null;
    public $headersAPIPost = null;
    public $takeAPI = 3000;
    public $forceExternal=false;

    public function __construct()
    {
        $this->headersAPIGet = array('Content-Type:application/json','Content-Length: 0','Authorization: Basic '. base64_encode("credipaz:credipaz1"));
        $this->headersAPIPost = array('Accept:application/json','Authorization: Basic '. base64_encode("credipaz:credipaz1"));
        parent::__construct();
    }

    public function brow($values){
        try {
            /*Seteos por tipo de acceso externo!*/
            $profile=getUserProfile($this,$values["id_user_active"]);
            $new=true;
            $offline=true;
            if ($values["where"]!=""){$values["where"].=" AND ";}
            $values["where"].=" id_type_beneficio IN (1,4) AND id_type_category!=-125"; // filtro por tipo externo
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("description","code","sinopsys")),
                array("name"=>"browser_id_type_category", "operator"=>"=","fields"=>array("id_type_category")),
                array("name"=>"browser_id_type_beneficio", "operator"=>"=","fields"=>array("id_type_beneficio")),
                array("name"=>"browser_id_type_execution", "operator"=>"=","fields"=>array("id_type_execution")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Tipo</span>".comboTypeBeneficios($this,array("where"=>"id IN (1,4)","order"=>"description ASC","pagesize"=>-1)),
                "<span class='badge badge-primary'>Categoría</span>".comboTypeCategories($this,array("where"=>"id!=-125 AND id_type_beneficio IN (1,4)","order"=>"description ASC","pagesize"=>-1)),
                "<span class='badge badge-primary'>Ejecución</span>".comboTypeExecutions($this,array("where"=>"id IN (6)","order"=>"description ASC","pagesize"=>-1)),
            );

            $this->view="vw_beneficios_brow";
            $values["order"]="description ASC";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>$new,
               "edit"=>array(
                    "conditions"=>array(
                           array("field"=>"id_type_beneficio","operator"=>"==","value"=>"1"),
                        )
                    ),                "delete"=>false,
                "offline"=>$offline,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"description","format"=>"text"),
                array("field"=>"type_category","format"=>"danger"),
                array("field"=>"date_from","format"=>"date"),
                array("field"=>"date_to","format"=>"date"),
                array("field"=>"type_beneficio","format"=>"type"),
                array("field"=>"","format"=>null),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $empty=true;
            /*Seteos por tipo de acceso externo!*/
            $profile=getUserProfile($this,$values["id_user_active"]);
            $values["accesoExterno"]=false;
            $getId_type_beneficio=array("where"=>"id in (1)","order"=>"description ASC","pagesize"=>-1);
            $getId_type_category=array("where"=>"id!=-125 AND id_type_beneficio in (1)","order"=>"description ASC","pagesize"=>-1);
            $getId_type_product=array("order"=>"description ASC","pagesize"=>-1);
            $getId_type_execution=array("where"=>"id in (6)","order"=>"description ASC","pagesize"=>-1);

            $values["interface"]=(MOD_CLUB_REDONDO."/beneficios/abm");
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);

            $parameters_id_type_beneficio=array(
                "model"=>(MOD_CLUB_REDONDO."/Type_beneficios"),
                "table"=>"type_beneficios",
                "name"=>"id_type_beneficio",
                "class"=>"form-control dbase validate",
                "empty"=>$empty,
                "id_actual"=>secureComboPosition($values["records"],"id_type_beneficio"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>$getId_type_beneficio,
            );
            $parameters_id_type_category=array(
                "model"=>(MOD_CLUB_REDONDO."/Type_categories"),
                "table"=>"type_categories",
                "name"=>"id_type_category",
                "class"=>"form-control dbase validate",
                "empty"=>$empty,
                "id_actual"=>secureComboPosition($values["records"],"id_type_category"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>$getId_type_category,
            );
            $parameters_id_type_product=array(
                "model"=>(MOD_CLUB_REDONDO."/Type_products"),
                "table"=>"type_products",
                "name"=>"id_type_product",
                "class"=>"form-control dbase",
                "empty"=>$empty,
                "id_actual"=>secureComboPosition($values["records"],"id_type_product"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>$getId_type_product,
            );
            $parameters_id_type_execution=array(
                "model"=>(MOD_CLUB_REDONDO."/Type_executions"),
                "table"=>"type_executions",
                "name"=>"id_type_execution",
                "class"=>"form-control dbase validate",
                "empty"=>$empty,
                "id_actual"=>secureComboPosition($values["records"],"id_type_execution"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>$getId_type_execution,
            );
            $values["controls"]=array(
                "id_type_beneficio"=>getCombo($parameters_id_type_beneficio,$this),
                "id_type_category"=>getCombo($parameters_id_type_category,$this),
                "id_type_product"=>getCombo($parameters_id_type_product,$this),
                "id_type_execution"=>getCombo($parameters_id_type_execution,$this),
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
			$id_type_category=(int)secureEmptyNull($values,"id_type_category");
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
                        'id_type_beneficio'=>secureEmptyNull($values,"id_type_beneficio"),
                        'id_type_category'=>$id_type_category,
                        'id_type_product'=>secureEmptyNull($values,"id_type_product"),
                        'id_type_execution'=>secureEmptyNull($values,"id_type_execution"),
                        'date_from' => secureEmptyNull($values,"date_from"),
                        'date_to' => secureEmptyNull($values,"date_to"),
                        'limit_user_canje'=>$values["limit_user_canje"],
                        'code_qr'=>$values["code_qr"],
                        'lat'=>secureFloatNull($values,"lat"),
                        'lng'=>secureFloatNull($values,"lng"),
                        'address'=>$values["address"],
                        'location'=>$values["location"],
                        'city'=>$values["city"],
                        'province'=>$values["province"],
                        'image'=>$values["image"],
                        'url_image'=>$values["url_image"],
                        'image_apaisada'=>$values["image_apaisada"],
                        'url_image_apaisada'=>$values["url_image_apaisada"],
                        'amount'=>$values["amount"],
                        'id_type_vademecum' => secureEmptyNull($values,"id_type_vademecum"),
                        'des_legales'=>$values["des_legales"],
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
                        'id_type_beneficio'=>secureEmptyNull($values,"id_type_beneficio"),
                        'id_type_category'=>$id_type_category,
                        'id_type_product'=>secureEmptyNull($values,"id_type_product"),
                        'id_type_execution'=>secureEmptyNull($values,"id_type_execution"),
                        'date_from' => secureEmptyNull($values,"date_from"),
                        'date_to' => secureEmptyNull($values,"date_to"),
                        'limit_user_canje'=>$values["limit_user_canje"],
                        'code_qr'=>$values["code_qr"],
                        'lat'=>secureFloatNull($values,"lat"),
                        'lng'=>secureFloatNull($values,"lng"),
                        'address'=>$values["address"],
                        'location'=>$values["location"],
                        'city'=>$values["city"],
                        'province'=>$values["province"],
                        'image'=>$values["image"],
                        'url_image'=>$values["url_image"],
                        'image_apaisada'=>$values["image_apaisada"],
                        'url_image_apaisada'=>$values["url_image_apaisada"],
                        'amount'=>$values["amount"],
                        'phone'=>$values["phone"],
                        'cellphone'=>$values["cellphone"],
                        'email'=>$values["email"],
                        'id_type_vademecum' => secureEmptyNull($values,"id_type_vademecum"),
                        'des_legales'=>$values["des_legales"],
                    );
                }
            }
			$saved=parent::save($values,$fields);

			/*funcionalidad atada a tipo categoria MIL*/
			if ($id_type_category==259) {
			    $id_beneficio=$saved["data"]["id"];
				$EXTERNAL=$this->createModel(MOD_BACKEND,"external","external");
				$sucursales=$EXTERNAL->getSucursales(null);

				$BENEFICIOS_LOCATIONS=$this->createModel(MOD_CLUB_REDONDO,"Beneficios_locations","Beneficios_locations");
				$this->execAdHoc("DELETE ".MOD_CLUB_REDONDO."_beneficios_locations WHERE id_beneficio=".$id_beneficio);

				foreach ($sucursales["records"] as $location){
					if (strpos($location["lat"], '.') === false) {$location["lat"]=substr($location["lat"],0,3).".".substr($location["lat"],3);}
					if (strpos($location["lng"], '.') === false) {$location["lng"]=substr($location["lng"],0,3).".".substr($location["lng"],3);}
					$location["description"]=str_replace("'","´",$location["description"]);
					$location["address"]=str_replace("'","´",$location["address"]);
					$location["neighborhood"]=str_replace("'","´",$location["neighborhood"]);
					$location["place"]=str_replace("'","´",$location["place"]);
					$location["state"]=str_replace("'","´",$location["state"]);
					$fields = array(
						'code' => $location["id"],
						'description' => $location["description"],
						'created' => $this->now,
						'verified' => $this->now,
						'offline' => null,
						'fum' => $this->now,
						'productId' => $id_beneficio,
						'address' => $location["address"],
						'neighborhood' => "",
						'place' => "",
						'state' => "",
						'lat' => $location["lat"],
						'lng' => $location["lng"],
						'id_beneficio' => $id_beneficio,
					);
					$BENEFICIOS_LOCATIONS->save(array("id"=>0),$fields);
				}
			}
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }

    public function getCuponsRefactored($values){
		if ((int)$values["type_categoria"]==-99){$values["type_categoria"]="254,268";}
		$TYPE_CATEGORIES=$this->createModel(MOD_CLUB_REDONDO,"Type_categories","Type_categories");
        $type_category=[];
        if(!isset($values["id_type_vademecum"])){$values["id_type_vademecum"]=-1;}
        if($values["id_type_vademecum"]==0){$values["id_type_vademecum"]=-1;}
		$values["id_type_vademecum"]=(int)$values["id_type_vademecum"];
        if(!isset($values["radio"])){$values["radio"]=5;}
        if(!isset($values["lat"])){$values["lat"]=0;}
        if(!isset($values["lng"])){$values["lng"]=0;}
		if(!isset($values["dni"])){$values["dni"]=-1;}
		if($values["dni"]==""){$values["dni"]=-1;}
		if(!isset($values["id_club_redondo"])){
			$sql="SELECT * FROM DBClub.dbo.Persona WHERE Nrodocumento='".$values["dni"]."'";
			$persona=$this->getRecordsAdHoc($sql);
	        if (isset($persona[0])) { 
				$sql="SELECT * FROM DBClub.dbo.Socio WHERE IdPersona=".$persona[0]["IdPersona"]." ORDER BY IdSocio DESC";
				$socio=$this->getRecordsAdHoc($sql);
				$values["id_club_redondo"]=$socio[0]["IdSocio"];
			}
		}
        switch($values["mode_categoria"]){
            case "miscupones":
               $this->view="vw_canjes";
               $values["where"].="id_club_redondo=".$values["id_club_redondo"]." AND id_type_status_canje IN (1,2)";
               $values["order"]="date_canje DESC";
               break;
            default:
               $this->view="vw_beneficios_mobile";
               //$this->view="beneficios_resueltos";
				$near=(string)$values["near"];
				$coords=(string)$values["coords"];
				if ($coords!=""){$values["search"]=$coords;}
                $kmW = "DEGREES(ACOS(COS(RADIANS(".$values["lat"].")) * COS(RADIANS(lat)) * COS(RADIANS(".$values["lng"]." - lng)) + SIN(RADIANS(".$values["lat"].")) * SIN(RADIANS(lat)))) * 111.045";
				$values["fields"]="id, domicilio, localidad, ciudad, provincia, description,phone,cellphone,email,sinopsys,legales,lat,lng,image,image_apaisada,amount,address,neighborhood,".$kmW." as kms";
				$whereGeo="offline IS null AND (DEGREES(ACOS(COS(RADIANS(".$values["lat"].")) * COS(RADIANS(lat)) * COS(RADIANS(".$values["lng"]." - lng)) + SIN(RADIANS(".$values["lat"].")) * SIN(RADIANS(lat)))) * 111.045 < ".$values["radio"].")";
				$whereSearch="offline IS null AND (neighborhood LIKE '%".$values["search"]."%' OR address LIKE '%".$values["search"]."%' OR description LIKE '%".$values["search"]."%')";

				switch($values["mode_categoria"]){
					case "reimprimir":
						$values["where"]="id=".$values["id_beneficio"]." AND ". $whereGeo;
						break;
					case "cercamio":
						if ($values["lat"]!="" and $values["lng"]!="") {$values["where"]=$whereGeo;}
						break;
					case "farmacias":
				        $whereSearch="offline IS null AND (provincia LIKE '%".$values["search"]."%' OR ciudad LIKE '%".$values["search"]."%' OR localidad LIKE '%".$values["search"]."%')";
					case "categoria":
						if ($values["type_categoria"]=="0") {
							$type_category=$TYPE_CATEGORIES->get(array("pagesize"=>-1,"order"=>"priority ASC, description ASC","where"=>" id not in (252) AND id_type_beneficio=4 "));
							$x=0;
							foreach ($type_category["data"] as $record){
   							   $item=$TYPE_CATEGORIES->get(array("pagesize"=>-1,"where"=>"code='".$record["code"]."' AND id_type_beneficio=1"));
							   if ((int)$item["totalrecords"]!=0) {$type_category["data"][$x]["id"].=(",".$item["data"][0]["id"]);}
							   $x+=1;
							}
						} else {
							if ($values["where"]!=""){$values["where"].=" AND ";}
                            $values["where"] .= "offline IS null AND (id_type_category IN (" . $values["type_categoria"] . ") OR id IN (SELECT id_beneficio FROM " . MOD_CLUB_REDONDO . "_Rel_beneficios_type_categories WHERE id_type_category IN (" . $values["type_categoria"] . "))) ";
                            if ($near=="1") {
								if ($values["lat"]!="" and $values["lng"]!="") {$values["where"].=" AND (".$whereGeo.")";}
							}
							if($coords!=""){$values["where"].=" AND (".$whereSearch.")";}
						}
						break;
					case "buscar":
						if ($values["type_categoria"]!="0") {$whereSearch.=" AND (id_type_category IN (".$values["type_categoria"]."))";}
						$values["where"]=$whereSearch;
						if ($near=="1") {if ($values["lat"]!="" and $values["lng"]!="") {$values["where"].=" AND (".$whereGeo.")";}}
				        if (is_numeric($values["search"])) {$values["where"]="offline IS null AND id=".$values["search"];}
						break;
				}
				//$values["where"].=" AND id_type_beneficio IN (1,4,5) ";//"AND getdate()>=date_from AND getdate()<=date_to";// SOLO LOS TIPOS ACTIVOS ACTUALMENTE!
				if ($values["id_type_vademecum"]!=-1) {$values["where"].=(" AND id_type_vademecum=".$values["id_type_vademecum"]);}
				$values["order"]=$kmW." ASC, domicilio ASC,description ASC";
			break;
        }
        if(!isset($values["page"])){$values["page"]=1;}
        $values["onlytotals"]=true;
		/*Search total recs from sql string for calculate a dynamic pagesize in response*/
        //log_message("error", "FULL RELATED " . json_encode($values, JSON_PRETTY_PRINT));

        $records=$this->get($values);
		//$trec=(int)$records["totalrecords"];
		//if ($trec>1000 and $trec<10000) {$values["pagesize"]=(int)($trec/20);}
		//if ($trec>10000 and $trec<50000) {$values["pagesize"]=(int)($trec/50);}
		//if ($trec>50000) {$values["pagesize"]=1500;}
        $values["pagesize"]=5;
		/*Full sql string call for retrieve results*/
		$values["onlytotals"]=false;
        $records=$this->get($values);
		$final=$records;
		/*
		$final=array("data"=>array());
		$last_id=0;
		$last_description="";
		foreach ($records["data"] as $record){
		    if (($last_id!=(int)$record["id"]) and ($last_description!=$record["code"])) {
			   $last_id=(int)$record["id"];
			   $last_description=$record["code"];
		       array_push($final["data"], $record);
			}
		}
		*/

        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>$final,
            "child_categories"=>$type_category,
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>null,
            "compressed"=>false
        );
    }
    public function getImage($values){ 
        $this->view="vw_beneficios_mobile_images";
        $return = $this->get(array("where"=>("id=".$values["id"]),"fields"=>"isnull(sinopsys,'') as sinopsys,id,".$values["type"]));
		$return["data"][0]["sinopsys"]=str_replace("<img ","<img style='width:100%;' ",$return["data"][0]["sinopsys"]);
        return $return;
    }
    public function procesarCanje($values){
        try {
			if (!isset($values["dni"])){$values["dni"]=-1;}
			if ($values["dni"]==""){$values["dni"]=-1;}
			if (!isset($values["id_club_redondo"])){
				$sql="SELECT * FROM DBClub.dbo.Persona WHERE Nrodocumento='".$values["dni"]."'";
				$persona=$this->getRecordsAdHoc($sql);
	            if (!isset($persona[0])) { throw new Exception("El documento provisto pareciera no ser el de un socio de Mediya",1110); }
				$sql="SELECT * FROM DBClub.dbo.Socio WHERE IdPersona=".$persona[0]["IdPersona"]." ORDER BY IdSocio DESC";
			    $socio=$this->getRecordsAdHoc($sql);
	            if (!isset($socio[0])) { throw new Exception("El documento provisto pareciera no ser el de un socio de Mediya",1110); }
				$values["id_club_redondo"]=$socio[0]["IdSocio"];
			}
            $this->view="vw_beneficios";
            $beneficio=$this->get(array("page"=>1,"where"=>"id=".$values["id_beneficio"]));
			$id_type_execution=(int)$beneficio["data"][0]["id_type_execution"];

            $qr_code="";
			$brand_image="";
            $status_canje="";
            $message_canje="";
            $CANJES=$this->createModel(MOD_CLUB_REDONDO,"Canjes","Canjes");
            $sql="SELECT * FROM DBClub.dbo.Socio WHERE IdSocio=".$values["id_club_redondo"]." ORDER BY IdSocio DESC";
            $socio=$this->getRecordsAdHoc($sql);
            if (!isset($socio[0])) { throw new Exception("El documento provisto pareciera no ser el de un socio de Mediya",1110); }
            $sql="SELECT * FROM DBClub.dbo.Persona WHERE IdPersona=".$socio[0]["IdPersona"];
            $persona=$this->getRecordsAdHoc($sql);
            if (!isset($persona[0])) { throw new Exception("El documento provisto pareciera no ser el de un socio de Mediya",1110); }
            switch((int)$id_type_execution) {
               case 6: // API local - Credipaz
                  $status_canje="OK";
                  $message_canje="¡Ya tenés listo tu cupón!";
                  $qr_code=cUrlImageBase64(INTRANET."/assets/logos/app-clubredondo.png");
                  $verification=opensslRandom(8);
                  break;
               case 7: 
                  break;
            }
            $values["id"]=0;
            $values["code"]=$beneficio["data"][0]["code"];
            $values["description"]=$beneficio["data"][0]["description"];
            $values["verification"]=$verification;
            $values["qr_code"]=$qr_code;
            $values["status"]=$status_canje;
            $values["status_canje"]=$status_canje;
            $values["message_canje"]=$message_canje;
            $CANJES->save($values);
            $end=array(
                "code"=>"2000",
                "status"=>$values["status"],
                "verification"=>$values["verification"],
                "qr_code"=>$values["qr_code"],
				"brand_image"=>$brand_image,
                "status_canje"=>$values["status"],
                "message_canje"=>$values["message_canje"],
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
			return $end;
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function confirmarCanje($values){
        try {
            $CANJES=$this->createModel(MOD_CLUB_REDONDO,"Canjes","Canjes");
            $CANJES->save($values);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    public function reimprimirCanje($values){
        try {
            $CANJES=$this->createModel(MOD_CLUB_REDONDO,"Canjes","Canjes");
            $CANJES->save($values);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>false
            );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }
    
    /*Sincronización cupones externos en modelo propietario*/
    public function syncRemoteData($values){
        /*-------------------------------------------------*/    
        set_time_limit(0);
		switch($values["branch"]) {
		   case "GERDANNA":/*CREDIPAZ GERDANNA*/
				try {
					$this->syncBeneficiosGerdanna($values);
				} catch(Exception $e){
					logError($e,__METHOD__ );
				}
				break;
		   case "CR":/*CREDIPAZ -125 FARMACIAS PROPIAS*/
				try {
	                $this->execAdHoc("DELETE ".MOD_CLUB_REDONDO."_rel_beneficios_type_categories WHERE id_beneficio IN (SELECT id FROM ".MOD_CLUB_REDONDO."_beneficios WHERE id_type_category=-125)");
					$this->syncBeneficiosCR($values);
				} catch(Exception $e){
					logError($e,__METHOD__ );
				}
				break;
		}
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"Se han procesado todos los cupones externos.",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>null,
        );
    }
    
    

    /*Helper functions for sync*/
    public function syncBeneficiosGerdanna(){
        /*Sincro especialidades medicas*/
        $view="DBClub.dbo.vw_Gerdanna_rel_prestadores_medicos_especialidades";
        $prestadores=$this->getRecordsAdHoc("SELECT * FROM ".$view." WHERE calle!='' ORDER BY IdExterno ASC");
        $this->buildBeneficiosGerdanna($prestadores,$view);

        /*Sincro especialidades odontologicas*/
        $view="DBClub.dbo.vw_Gerdanna_rel_prestadores_odontologicos_especialidades";
        $prestadores=$this->getRecordsAdHoc("SELECT * FROM ".$view." WHERE calle!='' ORDER BY IdExterno ASC");
        $this->buildBeneficiosGerdanna($prestadores,$view);
    }
    public function buildBeneficiosGerdanna($prestadores,$sqlAddView){
        $last_id=0;
        $servicios="";
        $record=null;
        foreach ($prestadores as $item){
            try {
                if ($last_id!=(int)$item["IdExterno"]){
                    $last_id=(int)$item["IdExterno"];
                    $sql="SELECT especialidad, id_type_category FROM ".$sqlAddView." WHERE idExterno=".$item["IdExterno"]." ORDER BY especialidad ASC";
                    $especialidades=$this->getRecordsAdHoc($sql);
                    $servicios="";
                    foreach ($especialidades as $especialidad){
                       if($servicios!=""){$servicios.=", ";}
                       $servicios.=$especialidad["especialidad"];
                    }
                    $record=$this->get(array("where"=>"code='".$item["IdExterno"]."' AND id_type_beneficio=5"));
                    $direccion=($item["Calle"]." ".$item["Puerta"]);
                    $synopsis="";
                    if($servicios!="") {$synopsis=($servicios."<br/>");}
                    if($item["Telefonos"]!=""){$synopsis.="<br/><h5>Teléfonos: ".$item["Telefonos"]."</h5>";}
                    if($item["Horaridh"]!=""){$synopsis.="<h5>Horarios: ".$item["Horaridh"]."</h5>";}
                    //$synopsis.="<p><table><tr><td>Guardia: ".$item["Guardia"]."</td><td>Copago: ".$item["Copago"]."</td></tr></table></p>";
                    $searchAddress=($direccion." ".$item["Ciudad"]." ".$item["Provincia"].", Argentina");
                    $lat=$record["data"][0]["lat"];
                    $lng=$record["data"][0]["lng"];
                    if ($lat == "") {$lat = 0;}
                    if ($lng == "") {$lng = 0;}
                    if ((int)$lat == 0 || (int)$lng == 0) {
                        $PLACES = $this->createModel(MOD_PLACES, "Places", "Places");
                        $reverse = $PLACES->getReverse(array("address" => $searchAddress));
                        $lat = $reverse["lat"];
                        $lng = $reverse["lng"];
                    }
                    $fields = array(
                        'code' =>$item["IdExterno"],
                        'description' => $item["prestador"],
                        'fum'=>$this->now,
                        'offline' => null,
                        'sinopsys' => $synopsis,
                        'id_type_beneficio'=>5, // Gerdanna
                        'id_type_category'=>$item["id_type_category"],
                        'id_type_execution'=>null, // no ejecuta canje
                        'date_from' => $this->now,
                        'date_to' => $this->now,
                        'limit_user_canje'=>0,
                        'code_qr'=>"",
                        'phone'=>$item["Telefonos"],
                        'lat'=>$lat,
                        'lng'=>$lng,
                        'address'=>$direccion,
                        'location'=>$item["Barrio"],
                        'city'=>$item["Ciudad"],
                        'province'=>$item["Provincia"],
                        'image'=>'',
                        'cuponId'=>$item["id"],
                        'amount'=>0,
					    'id_type_vademecum'=>null,
                    );
                    if ($record["totalrecords"]==0) {
                        $values=array("id"=>0);
                        $fields["created"]=$this->now;
                        $fields["verified"]=$this->now;
                        $fields["priority"]=0;
                    } else {
                        $values=array("id"=>$record["data"][0]["id"]);
                    }
                    $saved=$this->save($values,$fields);
                    $id_beneficio=(int)$saved["data"]["id"];
			        $this->execAdHoc("DELETE ".MOD_CLUB_REDONDO."_rel_beneficios_type_categories WHERE id_beneficio=".$id_beneficio);

                    foreach ($especialidades as $especialidad){
				        $this->execAdHoc("INSERT ".MOD_CLUB_REDONDO."_rel_beneficios_type_categories (id_beneficio,id_type_category) values (".$id_beneficio.",".$especialidad["id_type_category"].")");
                    }
                }
                $this->execAdHoc("UPDATE mod_club_redondo_beneficios SET date_to=date_to+3600 WHERE id_type_beneficio=5");
                $this->execAdHoc("DELETE mod_club_redondo_beneficios WHERE id_type_beneficio=5 AND datediff(day,fum,getdate())>2");
            } catch(Exception $e){

            }
        }
        return true;
    }

    public function syncBeneficiosCR($values){
        //$this->execAdHoc("UPDATE ".MOD_CLUB_REDONDO."_beneficios SET offline=getdate() WHERE id_type_beneficio=1 AND id_type_category NOT IN (259) ");
        $PLACES = $this->createModel(MOD_PLACES, "Places", "Places");
        $sql="SELECT * FROM DBClub.dbo.vw_farmacia";
        $farmacias=$this->getRecordsAdHoc($sql);
        foreach ($farmacias as $item){
            try {
                $lat=$item["Lat"];
                $lng = $item["Lng"];
                if ($lat == "") {$lat = 0;}
                if ($lng == "") {$lng = 0;}
                $searchAddress=($item["Direccion"]." ".$item["Localidad"]);
                if ((int) $lat == 0 || (int) $lng == 0) {
                    $reverse = $PLACES->getReverse(array("address" => $searchAddress));
                    $lat = $reverse["lat"];
                    $lng = $reverse["lng"];
                }
                $record=$this->get(array("where"=>"code='".$item["IdPrestador"]."' AND id_type_beneficio=1"));
                $fields = array(
                    'code' =>$item["IdPrestador"],
                    'description' => $item["Nombre"],
                    'fum'=>$this->now,
                    'offline' => null,
                    'sinopsys' => $item["Sinopsys"],
                    'id_type_beneficio'=>1, // Propio
                    'id_type_category'=>-125,
                    'id_type_execution'=>6, // API Credipaz
                    'date_from' => $this->now,
                    'date_to' => $this->now,
                    'limit_user_canje'=>0,
                    'code_qr'=>"",
                    'lat'=>$lat,
                    'lng'=>$lng,
                    'address'=>$item["Direccion"],
                    'location'=>$item["Localidad"],
                    'city'=>$item["Zona"],
                    'province'=>$item["Zona"],
                    'image'=>'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFFmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDI0LTA0LTI5VDE4OjAwOjM2LTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyNC0wNS0wNFQxMTo0NToyOS0wMzowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyNC0wNS0wNFQxMTo0NToyOS0wMzowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpjYTEwZmJkZS03MWQzLWM1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',
                    'cuponId'=>$item["IdPrestador"],
                    'amount'=>$item["Descuento"],
					'id_type_vademecum'=>$item["IdVademecum"],
                );
                if ($record["totalrecords"]==0) {
                    $values=array("id"=>0);
                    $fields["created"]=$this->now;
                    $fields["verified"]=$this->now;
                    $fields["priority"]=0;
                } else {
                    $values=array("id"=>$record["data"][0]["id"]);
                }
                $saved=$this->save($values,$fields);
				$id_beneficio=(int)$saved["data"]["id"];
				//$this->Sync_log(array("id"=>0,"code"=>$saved["data"]["id"],"description"=>"Beneficios:syncBeneficiosCredipaz"));
            } catch(Exception $e){

            }
        }
    }

/*cURL functions*/  
    private function cUrlRestful($url,$headers,$post, $fields, $timeout){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);

        curl_setopt($ch, CURLOPT_POST, $post);
        if ($headers!=null){curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);}
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $jsonResponse = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        $response = $jsonResponse;
        return $response;
    }
    private function cUrlRestfulGet($url,$headers){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 0);
        $jsonResponse = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        $response = $jsonResponse;
        return $response;
    }
	private function Sync_log($values){
        $SYNC_LOG=$this->createModel(MOD_CLUB_REDONDO,"Sync_log","Sync_log");
		$SYNC_LOG->save($values);
	}
}
