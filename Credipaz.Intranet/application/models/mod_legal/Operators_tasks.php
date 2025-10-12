<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Operators_tasks extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
			$is_lawyer=($profile["data"][0]["id_lawyer"]!="");
			$is_lawyer_admin=0;
			$edit=array(
                    "conditions"=>array(
                           array("field"=>"edit","operator"=>"==","value"=>"1"),
                        ));
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>$edit,
                "delete"=>false,
                "offline"=>false,
            );
			$url=(getServer()."/resumenLegal/|ID|");
			$PDFResumen="<button type='button' class='btn btn-sm btn-success btn-raised' style='color:white;'><a href='".$url."' target='_blank' style='color:white;'>Resumen</a></button>";

            $values["columns"]=array(
                array("field"=>"id","format"=>"number"),
                array("field"=>"created","format"=>"datetime"),
                array("field"=>"type_status","forcedlabel"=>"","html"=>$PDFResumen,"format"=>"html#block"),
                array("field"=>"scheduled_date","format"=>"code"),
                array("field"=>"scheduled_time","format"=>"code"),
                //array("field"=>"type_status","format"=>"primary"),
                array("field"=>"lawyer","format"=>"type"),
                array("field"=>"name_club_redondo","format"=>"warning"),
                //array("forcedlabel"=>"refiere_legal","field"=>"refiere","format"=>"text"),
                //array("field"=>"nocontact","format"=>"danger"),
            );

            $values["title"]=lang('m_legal_requests');
            if (!$is_lawyer){
                $values["where"]=("1=2");
                $values["alert_message"]="<div class='alert alert-danger fade show' role='alert'>".lang('msg_is_not_lawyer')."</div>";
            } else {
				$LAWYERS=$this->createModel(MOD_LEGAL,"Lawyers","Lawyers");
				$lawyer=$LAWYERS->get(array("page"=>1,"where"=>"username='".$profile["data"][0]["username"]."'"));
				$is_lawyer_admin=(int)$lawyer["data"][0]["admin"];
				if ($is_lawyer_admin==0) {
					if($values["where"]!="") {$values["where"].=" AND ";}
					$values["where"].=(" (id_operator=".$values["id_user_active"]);
				}
			}
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("code","description")),
                array("type"=>"date","name"=>"browser_date_from", "operator"=>">=","fields"=>array("scheduled")),
                array("type"=>"date","name"=>"browser_date_to", "operator"=>"<=","fields"=>array("scheduled")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>".lang('p_date_from')."</span> <input id='browser_date_from' name='browser_date_from' type='date' class='form-control'/>",
                "<span class='badge badge-primary'>".lang('p_date_to')."</span> <input id='browser_date_to' name='browser_date_to' type='date' class='form-control'/>",
            );
            
            $values["conditionalBackground"]=array(
                array("field"=>"modo","operator"=>"=","value"=>"0","color"=>"lightgreen"),
                array("field"=>"modo","operator"=>"=","value"=>"1","color"=>"white"),
            );

            $this->view="vw_operators_tasks";
            $values["order"]="created DESC";
            $values["records"]=$this->get($values);
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
		    $id=$values["id"];
            if(!isset($values["forced"])){$values["forced"]="";}
            $values["readonly"]=false;
            $values["title"]=lang('m_legal_requests');
            $profile=getUserProfile($this,$values["id_user_active"]);
            //$external=(evalPermissions("EXTERNAL",$profile["data"][0]["groups"]));
            $this->view="vw_operators_tasks";
            $values["interface"]=(MOD_LEGAL."/operators_tasks/abm");
            $values["page"]=1;
            $values["where"]=("id=".$id);
            $ot["records"]=$this->get($values);
            if ($ot["records"]["refiere"]==""){$values["records"]["refiere"]=lang("msg_empty");}

            $OPERATORS_TASKS_ITEMS=$this->createModel(MOD_LEGAL,"Operators_tasks_items","Operators_tasks_items");
			$OPERATORS_TASKS_ITEMS->view="vw_operators_tasks_items";

            $values["where"]=("id!=".$id." AND id_club_redondo=".$ot["records"]["data"][0]["id_club_redondo"]);
            $values["order"]="created DESC";
            $previous=$this->get($values);
			$i=0;
			foreach ($previous["data"] as $item){
   			   $oi=$OPERATORS_TASKS_ITEMS->get(array("pagesize"=>-1,"where"=>"id_operator_task=".$item["id"],"order"=>"created DESC"));
			   $previous["data"][$i]["items"]=$oi["data"];
			   $i+=1;
			}

			$operators_tasks_items=$OPERATORS_TASKS_ITEMS->get(array("pagesize"=>-1,"where"=>"id_operator_task=".$id,"order"=>"created DESC"));
            $LAWYERS=$this->createModel(MOD_LEGAL,"Lawyers","Lawyers");
            $lawyer=$LAWYERS->get(array("page"=>1,"where"=>"username='".$profile["data"][0]["username"]."'"));
			$is_lawyer_admin=(int)$lawyer["data"][0]["admin"];
			
			//$is_lawyer_admin=1;
			$whereLawyers="id_lawyer is not null";
			$emptyLawyers=true;
			if ($is_lawyer_admin==0) {$whereLawyers=" AND username=''".$values["username_active"]."'";$emptyLawyers=false;}

            $parameters_id_lawyer=array(
                "model"=>(MOD_BACKEND."/Users"),
                "table"=>"users",
                "view"=>"vw_users",
                "name"=>"id_operator",
                "class"=>"form-control dbase",
                "empty"=>$emptyLawyers,
                "id_actual"=>secureComboPosition($ot["records"],"id_operator"),
                "id_field"=>"id",
                "description_field"=>"lawyer",
                "get"=>array("order"=>"lawyer ASC","pagesize"=>-1, "where"=>$whereLawyers),
            );
            $parameters_id_type_task_close=array(
                "model"=>(MOD_LEGAL."/Type_tasks_close"),
                "table"=>"type_tasks_close",
                "name"=>"id_type_task_close",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>secureComboPosition($ot["records"],"id_type_task_close"),
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("order"=>"description ASC","pagesize"=>-1,"where"=>"id NOT IN (5)"),
            );
            $values["controls"]=array(
                "id_operator"=>getCombo($parameters_id_lawyer,$this),
                "id_type_task_close"=>getCombo($parameters_id_type_task_close,$this),
                "refiere"=>("<p>".$ot["records"]["refiere"]."</p>"),
                "motivo"=>("<p>".$ot["records"]["data"][0]["motivo"]."</p>"),
            );

            $club_redondo=getUserClubRedondo($this,(int)$ot["records"]["data"][0]["id_club_redondo"]);
			//$club_redondo=getIdUserClubRedondo($this,(int)$ot["records"]["data"][0]["documentNumber"]);
            $values["club_redondo"]=$club_redondo["message"];
            $values["lawyer"]=$lawyer["data"][0];
			$values["operators_tasks_items"]=$operators_tasks_items["data"];
			$values["previous"]=$previous["data"];
			$values["where"]=("id=".$id);

            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function save($values,$fields=null){
        try {
			$bNew=false;
			if (!isset($values["id"])){$values["id"]=0;}
            $id=(int)$values["id"];
			$values["scheduled"]= str_replace("T", " ", $values["scheduled"]);
            if($id==0){
			    $bNew=true;
                if($fields==null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => null,
                        'offline' => null,
                        'fum' => $this->now,
                        'id_operator' => secureEmptyNull($values,"id_operator"),
                        'id_type_task_close' => secureEmptyNull($values,"id_type_task_close"),
                        'id_client_credipaz' => secureEmptyNull($values,"id_client_credipaz"),
                        'refiere' => $values["refiere"],
                        'motivo' => $values["motivo"],
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'fum' => $this->now,
                        'id_operator' => secureEmptyNull($values,"id_operator"),
                        'id_type_task_close' => secureEmptyNull($values,"id_type_task_close"),
                        'id_client_credipaz' => secureEmptyNull($values,"id_client_credipaz"),
                        'encomienda_profesional' => $values["encomienda_profesional"],
                        'monto_reclamo' => $values["monto_reclamo"],
						'scheduled'=>$values["scheduled"],
                    );
                }
            }
            $saved=parent::save($values,$fields);
			return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
	public function buildResumenLegal($id_operator_task){
        try {
		    $html="";
			$this->view="vw_operators_tasks";
			$operators_tasks=$this->get(array("where"=>"id=".$id_operator_task));
			$OPERATORS_TASKS_ITEMS=$this->createModel(MOD_LEGAL,"operators_tasks_items","operators_tasks_items");
			$OPERATORS_TASKS_ITEMS->view="vw_operators_tasks_items";
			$items=$OPERATORS_TASKS_ITEMS->get(array("order"=>"created DESC","where"=>"offline IS null AND id_operator_task=".$id_operator_task));
			if ((int)$operators_tasks["totalrecords"]!=0){
				$html.="<h3>Datos de la solicitud</h3>";
				$html.="<ul>";
				$html.="<li>ID: ".$operators_tasks["data"][0]["id"]."</li>";
				$html.="<li>Nombre: ".$operators_tasks["data"][0]["name_club_redondo"]."</li>";
				$html.="<li>CR: ".$operators_tasks["data"][0]["id_club_redondo"]."</li>";
				$html.="<li>Teléfono: ".$operators_tasks["data"][0]["telefono"]."</li>";
				$html.="<li>Asignado a: ".$operators_tasks["data"][0]["lawyer"]."</li>";
				$html.="<li>Fecha agenda contacto: ".$operators_tasks["data"][0]["scheduled_date"]." a las ".$operators_tasks["data"][0]["scheduled_time"]."</li>";
				$html.="</ul>";

				$html.="<h3>Detalles de contactos</h3>";
				foreach ($items["data"] as $item) {
					$html.=date(FORMAT_DATE_DMYHMS, strtotime($item["created"]))." <u>".$item["lawyer"]."</u><br/><b>".$item["description"]."</b><br/><div style='border:solid 1px silver;padding:3px;width:100%;'>".$item["data"]."</div><br/>";
				}
 			} else {
				$html="<div style='font-family:arial;width:100%;'>";
				$html.="   <h1>Requerimiento de asesoría legal inexistente</h1>";
				$html.="</div>";
			}
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
		return $html;	
	}
    public function notifyEmail($id,$notify){
        try {
            $data=[];
            $this->view="vw_operators_tasks";
			$ot=$this->get(array("where"=>"id=".$id));
			$data["operators_tasks"]=$ot["data"][0];
            $params=array(
				"from"=>"intranet@mediya.com.ar",
				"alias_from"=>"",
				"email"=>NOTIFY_LEGAL_LIST,
				"subject"=>lang('msg_'.$notify),
				"body"=>$this->load->view(MOD_EMAIL.'/templates/'.$notify, $data, true)
			);
            $EMAIL=$this->createModel(MOD_EMAIL,"Email","Email");
            return $EMAIL->directEmail($params);
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
