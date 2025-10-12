<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Reports_crm extends My_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function form($values){
        try {
            $parameters_id_type_task_close=array(
                "model"=>(MOD_CRM."/Type_tasks_close"),
                "table"=>"Type_tasks_close",
                "name"=>"id_type_task_close",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>null,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_type_contact_channel=array(
                "model"=>(MOD_CHANNELS."/Type_contact_channels"),
                "table"=>"Type_contact_channels",
                "name"=>"id_type_contact_channel",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>null,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_tarjeta=array(
                "model"=>(MOD_CRM."/Tarjeta"),
                "table"=>"tarjeta",
                "name"=>"id_tarjeta",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>null,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_otro=array(
                "model"=>(MOD_CRM."/Otros"),
                "table"=>"otros",
                "name"=>"id_otro",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>null,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_myd=array(
                "model"=>(MOD_CRM."/Myd"),
                "table"=>"myd",
                "name"=>"id_myd",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>null,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_mil=array(
                "model"=>(MOD_CRM."/Mil"),
                "table"=>"mil",
                "name"=>"id_mil",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>null,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_credito=array(
                "model"=>(MOD_CRM."/Credito"),
                "table"=>"credito",
                "name"=>"id_credito",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>null,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $parameters_id_club_redondo=array(
                "model"=>(MOD_CRM."/Club_redondo"),
                "table"=>"club_redondo",
                "name"=>"id_club_redondo",
                "class"=>"form-control dbase",
                "empty"=>true,
                "id_actual"=>null,
                "id_field"=>"id",
                "description_field"=>"description",
                "get"=>array("where"=>"id!=0","order"=>"description ASC","pagesize"=>-1),
            );
            $values["interface"]=(MOD_CRM."/reports_crm/form");
            $parameters=array(
                "mode"=>"MULTISELECT",
                "model"=>(MOD_BACKEND."/users"),
                "table"=>"users",
                "name"=>"username",
                "class"=>"multiselect form-control dbase username",
                "empty"=>false,
                "id_actual"=>null,
                "id_field"=>"username",
                "description_field"=>"username",
                "get"=>array("where"=>WHERE_USERS_COMERCIAL,"order"=>"username ASC","pagesize"=>-1),
            );
            $values["controls"]=array(
                "id_type_task_close"=>getCombo($parameters_id_type_task_close,$this),
                "id_type_contact_channel"=>getCombo($parameters_id_type_contact_channel,$this),
                "id_tarjeta"=>getCombo($parameters_id_tarjeta,$this),
                "id_otro"=>getCombo($parameters_id_otro,$this),
                "id_myd"=>getCombo($parameters_id_myd,$this),
                "id_mil"=>getCombo($parameters_id_mil,$this),
                "id_credito"=>getCombo($parameters_id_credito,$this),
                "id_club_redondo"=>getCombo($parameters_id_club_redondo,$this),
                "username"=>getCombo($parameters,$this)
            );
            return parent::form($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

}
