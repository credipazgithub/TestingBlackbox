<?php
//log_message("error", "SQL ".$sql);
/*---------------------------------*/

//HTML COMBOS
function comboTicketsSectores($obj){
    $parameters=array(
        "model"=>(MOD_DBCENTRAL."/wrkRequerimiento"),
        "table"=>"wrkRequerimiento",
        "name"=>"browser_id_sector",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"sSector",
        "description_field"=>"sDescripcion",
        "sql"=>"exec dbcentral.dbo.traersectores",
    );
    return getCombo($parameters,$obj);
}

function comboTicketsSubsistemas($obj){
    $parameters=array(
        "model"=>(MOD_DBCENTRAL."/wrkRequerimiento"),
        "table"=>"wrkRequerimiento",
        "name"=>"browser_id_subsistema",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"nID",
        "description_field"=>"sDescripcion",
        "sql"=>"exec dbcentral.dbo.traersubsistemas",
    );
    return getCombo($parameters,$obj);
}

function comboTicketsTipos($obj){
    $parameters=array(
        "model"=>(MOD_DBCENTRAL."/wrkRequerimiento"),
        "table"=>"wrkRequerimiento",
        "name"=>"browser_id_tipo",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"nID",
        "description_field"=>"sDescripcion",
        "sql"=>"select * from dbcentral.dbo.stdTipoRequerimiento ORDER BY 2 ASC",
    );
    return getCombo($parameters,$obj);
}

function comboEmpresa($obj){
    $parameters=array(
        "model"=>(MOD_DBCENTRAL."/Empresa"),
        "table"=>"Empresa",
        "name"=>"browser_id_empresa",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"Id",
        "description_field"=>"RazonSocial",
        "sql"=>"select * from dbclub.dbo.Empresa WHERE estado='VIG' ORDER BY 3 ASC",
    );
    return getCombo($parameters,$obj);
}

function comboEmpresaComercializadora($obj){
    $parameters=array(
        "model"=>(MOD_DBCENTRAL."/EmpresaComercializadora"),
        "table"=>"EmpresaComercializadora",
        "name"=>"browser_id_empresa",
        "class"=>"form-control",
        "empty"=>true,
        "id_actual"=>"",
        "id_field"=>"Id",
        "description_field"=>"RazonSocial",
        "sql"=>"select * from DBCentral.dbo.EmpresaComercializadora ORDER BY 3 ASC",
    );
    return getCombo($parameters,$obj);
}

function comboTicketsEstados($obj){
    $html="<select data-type='select' id='browser_estado' name='browser_estado' class='browser_estado form-control'>";
    $html.="<option value='' selected>".lang('p_select_combo')."</option>";
    $html.="<option value='ANA'>Análisis</option>";
    $html.="<option value='DES'>Desarrollo</option>";
    $html.="<option value='FIN'>Finalizado</option>";
    $html.="<option value='ING'>Ingresado</option>";
    $html.="<option value='INT'>INT</option>";
    $html.="<option value='UAT'>UAT</option>";
    $html.="</select>";
    $html.="<div class='invalid-feedback invalid-browser_estado d-none'/>";
    return $html;
}

