<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class SMS extends MY_Model {

    public function __construct()
    {
        parent::__construct();
    }
	public function send($fields){
        try {
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

	private function authenticate(){
        try {
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

	private function callAPI($url, $headers, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		//curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        if (is_array($headers)) {curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);}
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        return $response;
	}
}

