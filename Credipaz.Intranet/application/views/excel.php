<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
hacer log_message de $data, permite ver TODOS los valores que recibe la view, por si se quieren armar encabezados, titulos o funcionalida especifica
*/
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$csv=fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
fputs($csv,$bom=(chr(0xEF).chr(0xBB).chr(0xBF)));
if (!isset($parameters["columns"]) or !is_array($parameters["columns"])){
    $parameters["columns"]=array(
        array("field"=>"code","format"=>"code"),
        array("field"=>"description","format"=>"text"),
    );
}
$headers=array();
foreach ($parameters["columns"] as $column) {
    $headers[]=lang("p_".$column["field"]);
}
fputcsv($csv, $haders,$parameters["delimiter"]);

foreach ((array)$parameters["records"]["data"] as $record){
    $line=array();
    foreach ($parameters["columns"] as $column) {
        $line[]=$record[$column["field"]];
    }
    fputcsv($csv,$line,,$parameters["delimiter"]);
}
rewind($csv);
echo stream_get_contents($csv);
?>
