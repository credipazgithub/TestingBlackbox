<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
if (!isset($parameters["records"])) {
    $html=getUnInitialized();
} else {
    $nodata=getNoData();
    $html=" <table  style='width:100%;'>";
    $html.="   <tr>";
    if (!isset($parameters["columns"]) or !is_array($parameters["columns"])){
        $parameters["columns"]=array(
            array("field"=>"code","format"=>"code"),
            array("field"=>"description","format"=>"text"),
         );
    }
    foreach ($parameters["columns"] as $column) {
       $html.="<td><b>".lang("p_".$column["field"])."</b></td>";
    }
    $html.="   </tr>";
    if(is_array($parameters["records"]["data"])) {
        foreach ((array)$parameters["records"]["data"] as $record){
            $nodata="";
            $html.="<tr>";
            foreach ($parameters["columns"] as $column) {$html.="<td>".$record[$column["field"]]."</td>";}
            $html.="</tr>";
        }
    } 
     $html.=" </table>";
    $html.=$nodata;
}
echo $html;
?>
