<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class wrkRequerimiento extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            $this->view="dbcentral.dbo.wrkRequerimiento";
            $values["fields"]="*, nID as id";
            $values["order"]="dFechaIngreso DESC";
            $values["title"]=lang('m_wrkRequerimiento');
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>true,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("field"=>"dFechaIngreso","format"=>"datetime"),
                array("field"=>"sLKSector","format"=>"status"),
                array("field"=>"sLKEstado","format"=>"warning"),
                array("field"=>"sAsunto","format"=>"text"),
                array("field"=>"sHorasEstimadas","format"=>"number"),
                array("field"=>"sPrioridad","format"=>"danger"),
                array("field"=>"","format"=>null),
                array("field"=>"","format"=>null),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("sAsunto","sDescripcion")),
                array("name"=>"browser_id_sector", "operator"=>"=","fields"=>array("sLKSector")),
                array("name"=>"browser_id_subsistema", "operator"=>"=","fields"=>array("nIDSubsistema")),
                array("name"=>"browser_estado", "operator"=>"=","fields"=>array("sLKEstado")),
            );
            $values["controls"]=array(
                "<span class='badge badge-primary'>Sector</span>".comboTicketsSectores($this),
                "<span class='badge badge-primary'>Sistema</span>".comboTicketsSubsistemas($this),
                "<span class='badge badge-primary'>Estado</span>".comboTicketsEstados($this),
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            if ($values["username_active"]=="neodata"){$values["username_active"]="czuniga";}
            $this->view="dbcentral.dbo.wrkRequerimiento";
            $values["fields"]="*, nID as id";
            $values["interface"]=(MOD_DBCENTRAL."/wrkRequerimiento/abm");
            $values["page"]=1;
            $values["where"]=("nID=".$values["id"]);
            $values["records"]=$this->get($values);

            if (((int)$values["records"]["totalrecords"]!=0)) {
                if(!isset($values["noedit"])){$values["noedit"]=false;}
                if($values["noedit"]){$values["readonly"]=true;}
                // List related files
                $attachments["records"]=[];
                $path=(FILES_TICKETS_SERVICIO.$values["records"]["data"][0]["nID"]."/");
                $files=listFilesSSH($path,"");
                $i=0;
                foreach($files as $file){
                    $base=basename($file);
                    //$date=date("d/m/Y H:i:s", filemtime($file));
                    $date="";
                    array_push($attachments["records"],array("id"=>$i,"code"=>$base,"description"=>$file,"data"=>$file,"src"=>$file,"filename"=>$base,"created"=>$date,"verified"=>$date,"fum"=>$date,"id_rel"=>$i,"table_rel"=>"filesystem"));
                    $i+=1;
                }
                $values["attached_files"] = $attachments["records"];
            }

            $STDUSUARIO=$this->createModel(MOD_DBCENTRAL,"stdUsuario","stdUsuario");
            $STDUSUARIO->view="dbcentral.dbo.stdUsuario";
            $stdUser=$STDUSUARIO->get(array("fields"=>"sDepartamento","where"=>"sUsuario='".$values["username_active"]."'"));
            $values["sDepartamento"]="N/A";
            if ((int)$stdUser["totalrecords"]!=0) {$values["sLKSector"]=$stdUser["data"][0]["sDepartamento"];}
            $parameters_subsistemas=array(
                "model"=>(MOD_DBCENTRAL."/wrkRequerimiento"),
                "table"=>"wrkRequerimiento",
                "name"=>"nIDSubsistema",
                "class"=>"form-control",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"nIDSubsistema"),
                "id_field"=>"nID",
                "description_field"=>"sDescripcion",
                "sql"=>"exec dbcentral.dbo.traersubsistemas",
            );
            $parameters_tipos=array(
                "model"=>(MOD_DBCENTRAL."/wrkRequerimiento"),
                "table"=>"Users",
                "name"=>"nIDTipo",
                "class"=>"form-control",
                "empty"=>true,
                "id_actual"=>secureComboPosition($values["records"],"nIDTipo"),
                "id_field"=>"nID",
                "description_field"=>"sDescripcion",
                "sql"=>"select * from dbcentral.dbo.stdTipoRequerimiento ORDER BY 2 ASC",
            );
            $sel=secureComboPosition($values["records"],"sLKEstado");
            $cboEstado="<select data-type='select' id='sLKEstado' name='sLKEstado' class='sLKEstado form-control'>";
            $cboEstado.="<option value='' selected>".lang('p_select_combo')."</option>";
            $selected="";
            if ($sel=="ANA"){$selected="selected";}else{$selected="";}
            $cboEstado.="<option ".$selected." value='ANA'>Análisis</option>";
            if ($sel=="DES"){$selected="selected";}else{$selected="";}
            $cboEstado.="<option ".$selected." value='DES'>Desarrollo</option>";
            if ($sel=="FIN"){$selected="selected";}else{$selected="";}
            $cboEstado.="<option ".$selected." value='FIN'>Finalizado</option>";
            if ($sel=="ING"){$selected="selected";}else{$selected="";}
            $cboEstado.="<option ".$selected." value='ING'>Ingresado</option>";
            if ($sel=="INT"){$selected="selected";}else{$selected="";}
            $cboEstado.="<option ".$selected." value='INT'>INT</option>";
            if ($sel=="UAT"){$selected="selected";}else{$selected="";}
            $cboEstado.="<option ".$selected." value='UAT'>UAT</option>";
            $cboEstado.="</select>";
            $cboEstado.="<div class='invalid-feedback invalid-sLKEstado d-none'/>";

            $sel=secureComboPosition($values["records"],"sPrioridad");
            $cboPrioridad="<select data-type='select' id='sPrioridad' name='sPrioridad' class='sPrioridad form-control'>";
            $cboPrioridad.="<option value='' selected>".lang('p_select_combo')."</option>";
            $selected="";
            if ($sel=="B"){$selected="selected";}else{$selected="";}
            $cboPrioridad.="<option ".$selected." value='B'>Baja</option>";
            if ($sel=="M"){$selected="selected";}else{$selected="";}
            $cboPrioridad.="<option ".$selected." value='M'>Media</option>";
            if ($sel=="A"){$selected="selected";}else{$selected="";}
            $cboPrioridad.="<option ".$selected." value='A'>Alta</option>";
            $cboPrioridad.="</select>";
            $cboPrioridad.="<div class='invalid-feedback invalid-sPrioridad d-none'/>";

            $values["controls"]=array(
                "nIDSubsistema"=>getCombo($parameters_subsistemas,$this),
                "nIDTipo"=>getCombo($parameters_tipos,$this),
                "sLKEstado"=>$cboEstado,
                "sPrioridad"=>$cboPrioridad,
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
            if($id==0){
                if($fields==null) {
                    $fields = array(
                        'nIDSucursal' => 0,
                        'sLKSector' => $values["sLKSector"],
                        'nIDSubsistema'=>secureEmptyNull($values,"nIDSubsistema"),
                        'nIDEspecFuncional' => 0,
                        'sUsuarioSolicitante' => $values["username_active"],
                        'sUsuarioAprobo' => null,
                        'nIDTipo'=>secureEmptyNull($values,"nIDTipo"),
                        'sDescripcion'=>$values["sDescripcion"],
                        'sFuncionalidad' => null,
                        'sObservaciones' => null,
                        'sAsunto' => $values["sAsunto"],
                        'sLKEstado' => "ING",
                        'dFechaModificacion'=>$this->now,
                        'dFechaCreacion'=>$this->now,
                        'dFechaIngreso'=>$this->now,
                        'dFechaDefinicion' => null,
                        'dFechaInicio' => $this->now,
                        'ISuspendido' => 0,
                        'sHorasEstimadas' => "-",
                        'sPrioridad' => "M"
                    );
                }
            } else {
                if($fields==null) {
                    $fields = array(
                        'nIDSubsistema'=>secureEmptyNull($values,"nIDSubsistema"),
                        'nIDTipo'=>secureEmptyNull($values,"nIDTipo"),
                        'sDescripcion'=>$values["sDescripcion"],
                        'sAsunto' => $values["sAsunto"],
                        'sLKEstado' => $values["sLKEstado"],
                        'dFechaModificacion'=>$this->now,
                        'dFechaDefinicion' => $values["dFechaDefinicion"],
                        'dFechaEF' => $values["dFechaEF"],
                        'ISuspendido' => $values["ISuspendido"],
                        'sHorasEstimadas' => $values["sHorasEstimadas"],
                        'sPrioridad' => $values["sPrioridad"]
                    );
                    if ($values["sLKEstado"]=="FIN"){$fields["dFechaCierre"]=$this->now;}

                }
            }
            $saved=parent::save($values,$fields);
            if($saved["status"]=="OK"){
               $t=0;
            }
            return $saved;
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
