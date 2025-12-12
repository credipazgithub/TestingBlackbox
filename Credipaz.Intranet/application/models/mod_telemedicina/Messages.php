<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Messages extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

	public function farmalink($values) {
	   try {
            $i = 0;
	      //log_message("error", "RELATED farmalink ".json_encode($values,JSON_PRETTY_PRINT));
	   }
       catch(Exception $e){
          return logError($e,__METHOD__ );
       }	
	}

    public function save($values,$fields=null){
        try {
            $bOk=false;
            if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
			$id_type_vademecum=secureEmptyNull($values,"id_type_vademecum");
			if ($id_type_vademecum==null or (string)$id_type_vademecum==""){$id_type_vademecum=-1;}

            if($id==0){
                if($fields==null) {
                    $message=$values["message"];
                    $raw_data=$values["raw_data"];
                    $bOk=true;
                    $description="Mensaje telemedicina";
                    switch((int)$values["id_type_item"]){
                       case 1:
                          $description="Imagen: ".date("d/m/Y H:i:s",strtotime($this->now));
                          break;
                       case 2:
                          $description="Receta: ".date("d/m/Y H:i:s",strtotime($this->now));
                          break;
                    }
                    $fields = array(
                        'Code' => opensslRandom(16),
                        'Description' => $description,
                        'created' => $this->now,
                        'verified' => null,
                        'offline' => null,
                        'fum' => $this->now,
                        'Message' => $message,
                        'Raw_data' => $raw_data,
                        'Viewed' => $values["viewed"],
                        'Id_charge_code' => $values["id_charge_code"],
                        'Id_type_item' => $values["id_type_item"],
                        'Id_type_direction' => $values["id_type_direction"],
                        'Id_operator' => secureEmptyNull($values,"id_operator"),
                        'Id_user' => $values["id_user_active"],
                        'Type_media' => $values["type_media"],
                        'Carbon_copy' => $values["carbon_copy"],
                        'Id_type_vademecum' => $id_type_vademecum,
                    );
                }
            } else {
                $bOk=true;
            }
            $NETCORECPFINANCIALS = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $ret = $NETCORECPFINANCIALS->MessageTelemedicina($fields);
            return $ret;

            //$saved=parent::save($values,$fields);
            //return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function directTelemedicina($values){
        try {
            $values["id"]=0;
            $values["viewed"]=0;
            if (!isset($values["obra_social"])){$values["obra_social"]="";}
            if (!isset($values["obra_social_plan"])){$values["obra_social_plan"]="";}
            if (!isset($values["nro_obra_social"])){$values["nro_obra_social"]="";}
            if (!isset($values["id_type_vademecum"])){$values["id_type_vademecum"]=null;}

            switch((int)$values["id_type_direction"]) {
               case 1: // paciente al medico
                  $values["id_user"]=$values["id_user_active"];
                  $values["id_operator"]=null;
                  break;
               case 2: // medico al paciente
                  $values["id_operator"]=$values["id_user_active"];
                  $values["id_user"]=null;
                  $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
                  $charges_code=$CHARGES_CODES->get(array("page"=>1,"where"=>"id=".$values["id_charge_code"]));
                  $id_club_redondo=$charges_code["data"][0]["id_club_redondo"];
                  $club_redondo=getUserClubRedondo($this,$id_club_redondo);
                  if ((int)$charges_code["totalrecords"]!=0){
                      $REL_PERSONA_ADICIONALES=$this->createModel(MOD_TELEMEDICINA,"Rel_persona_adicionales","Rel_persona_adicionales");
                      $rel=$REL_PERSONA_ADICIONALES->get(array("page"=>1,"where"=>"id=".$id_club_redondo));
                      $fields = array(
                          'offline' => null,
                          'fum' => $this->now,
                          'obra_social' => "",
                          'obra_social_plan' => "",
                          'nro_obra_social' => "",
                          'idPersona' => $club_redondo["message"]["id_persona"]
                      );
                      if ((int)$rel["totalrecords"]==0){
                         $fields["code"]="";
                         $fields["description"]="";
                         $fields["created"]=$this->now;
                         $fields["verified"]=$this->now;
                         $id_rel=0;
                      } else {
                         $id_rel=$rel["data"][0]["id"];
                      }
                      $REL_PERSONA_ADICIONALES->save(array("id"=>$id_rel),$fields);
                  } 
                  break;
            }
            return $this->save($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function verifyMessage($values){
        $values["where"]=("id=".$values["id"]);
        $ret=$this->get($values);
        if ((int)$ret["data"][0]["id_type_item"]==1) {
            $OPERATORS_TASKS=$this->createModel(MOD_TELEMEDICINA,"Operators_tasks","Operators_tasks");
            $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
            $charge_code=$CHARGES_CODES->get(array("page"=>1,"where"=>"id=".$ret["data"][0]["id_charge_code"]));
            $fields=array("request_pictures"=>0);
            $OPERATORS_TASKS->updateByWhere($fields,"id='".$charge_code["data"][0]["id_operator_task"]."'");
        }
        return $this->save(array("id"=>$values["id"]),array("verified"=>$this->now,"viewed"=>1));
    }
    public function recetasTelemedicina($values){
        try {
            if (!isset($values["request_types"])){$values["request_types"]="single";}
            if (!isset($values["request_mode"])){$values["request_mode"]="actual";}
            if (!isset($values["id_charge_code"])){$values["id_charge_code"]="0";}
			if ($values["id_charge_code"]==""){$values["id_charge_code"]="0";}
            $where="";
            if ($values["request_types"]=="single") {$where=" AND id_type_direction=2 AND id_type_item=2";}
            switch($values["request_mode"]) {
               case "byuser":
               case "actual":
               case "medic":
                  $CHARGES_CODES=$this->createModel(MOD_TELEMEDICINA,"Charges_codes","Charges_codes");
                  $charge_code=$CHARGES_CODES->get(array("page"=>1,"where"=>"id=".$values["id_charge_code"]));
				  $id_user=0;
				  if ((int)$charge_code["totalrecords"]!=0){
					  $id_user=$charge_code["data"][0]["id_user"];
					  $id_club_redondo=$charge_code["data"][0]["id_club_redondo"];
				  } else {
					  $id_club_redondo=$values["id_club_redondo"];
				  }

                  if ($id_user==""){$id_user=0;}
                  if ($id_club_redondo==""){$id_club_redondo=0;}
				  /*filtradas por 30 dias*/
                  //$data=array("order"=>"created DESC","where"=>"DATEDIFF(day, created, getdate())<30 AND (id_charge_code=".$values["id_charge_code"]." OR id_charge_code IN (SELECT id FROM ".MOD_TELEMEDICINA."_charges_codes WHERE id_club_redondo=".$id_club_redondo.")".$where.")");
                  /*sin filtros*/
				  $top="";
				  $data=array("fields"=>"id,description,created,raw_data,message,id_charge_code,id_type_direction,id_type_item,id_operator,carbon_copy,type_media","order"=>"created DESC","where"=>"(id_charge_code=".$values["id_charge_code"]." OR id_charge_code IN (SELECT id FROM ".MOD_TELEMEDICINA."_charges_codes WHERE id_club_redondo=".$id_club_redondo.")".$where.")");
				  if ($values["request_mode"]=="actual"){$data["pagesize"]=5;}
				  if ($values["request_mode"]=="medic"){$data["fields"]="id,description,created,id_charge_code,id_type_direction,id_type_item,id_operator,carbon_copy,type_media";}
                  break;
               //case "actual":
               //   $data=array("order"=>"created DESC","where"=>"id_charge_code=".$values["id_charge_code"]." AND DATEDIFF(day, created, getdate())<30 ".$where);
               //   break;
               default:
                  $data=array("order"=>"created DESC","where"=>"id_charge_code=".$values["id_charge_code"]." OR id_charge_code IN (SELECT id FROM ".MOD_TELEMEDICINA."_charges_codes WHERE id_club_redondo=".$values["id_club_redondo"]." AND freezed IS NOT NULL) AND DATEDIFF(day, created, getdate())<30".$where);
                  break;
            }
            return $this->get($data);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

	public function clonarReceta($values){
	   try {
		   $receta=$this->get(array("where"=>"id=".$values["id_message"]));
		   $parts=explode("<td>Fecha de emisi",$receta["data"][0]["message"]);
		   $begin=$parts[0];
		   $last=substr($parts[1],50);
		   $message=($begin.'<td>Fecha de emisión</td><td align="right">'.date("d/m/Y H:i:s",strtotime($this->now)).'</td>'.$last);
		   $message=preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $message);
		   $raw_data=preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $receta["data"][0]["raw_data"]);

		   $params=array(
		      'id'=>0,
  			  'id_type_item'=>$receta["data"][0]["id_type_item"],
			  'message'=>utf8_encode($message),
			  'raw_data'=>utf8_encode($raw_data),
			  'viewed' => $receta["data"][0]["viewed"],
			  'id_charge_code' => $values["id_charge_code"],
			  'id_type_item' => $receta["data"][0]["id_type_item"],
			  'id_type_direction' => $receta["data"][0]["id_type_direction"],
			  'id_operator' => $values["id_user_active"],
			  'id_user_active' => $receta["data"][0]["id_user"],
			  'type_media' => $receta["data"][0]["type_media"],
			  'carbon_copy' => $receta["data"][0]["carbon_copy"],
			  'id_type_vademecum' => $receta["data"][0]["id_type_vademecum"],
		   );
		   return $this->save($params,null);
       }
       catch(Exception $e){
          return logError($e,__METHOD__ );
       }
	}
}
