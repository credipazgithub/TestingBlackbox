<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
    function API_EsFuncional($id){
        $fields=array("Id"=>$id);
	    $headers = array('Content-Type:application/json','Authorization: Bearer ');
	    $func = API_callAPIGet("/Asesores/EsFuncional?_Id=".$id,$headers,json_encode($fields));
	    $func = json_decode($func, true);
        $idEmpresario="";
        if (isset($func["records"][0])) {$idEmpresario=(string)$func["records"][0]["idEmpresario"];}
        return API_EvaluarHabilitado($idEmpresario);
    }
    function API_EvaluarHabilitado($eval){
        $ret=array(
            "habilitado"=>false,
            "detalle"=>"El usuario autenticado no puede realizar acciones de lecto escritura que requieran acciones de 'Empresario'.  Debe aguardar su habilitación para operar en forma completa."
        );
        if ($eval!="") {
            $ret=array(
                "habilitado"=>true,
                "detalle"=>"Usuario habilitado para realizar operaciones de lecto escritura que requieran relaciones de 'Empresario'."
            );
        }
        return $ret;
    }
    function API_callAPI($url, $headers, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, (CPFINANCIALS.$url));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        if (is_array($headers)) {curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);}
        $response = curl_exec($ch);
	    $response=trim($response, "\xEF\xBB\xBF");
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        return $response;
    }
    function API_callAPIGet($url,$headers){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, (CPFINANCIALS.$url));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_POST, 0);
        if (is_array($headers)) {curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);}
        $jsonResponse = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        $response = $jsonResponse;
        return $response;
    }
