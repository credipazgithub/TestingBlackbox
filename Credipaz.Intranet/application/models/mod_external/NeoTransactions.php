<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class NeoTransactions extends MY_Model {
    private $url_neoauthentication="https://api.gruponeodata.com/neoauthentication.v1/";
    private $url_neotransactions="https://api.gruponeodata.com/neotransactions.v1/";

    public function __construct()
    {
        parent::__construct();
    }
	public function setTransaction($fields){
        try {
		    $auth=$this->authenticate();
			if ($auth["data"]["status"]=="ERROR"){throw new Exception(lang("error_10001"),10001);}
			$fields["id_user"]=$auth["data"]["records"][0]["id_user"];
			$fields["id_application"]=1;
			$fields["token"]=$auth["data"]["tokenSingleUse"];
			$url=($this->url_neotransactions."Create/");
			$result = $this->callAPI($url,$fields);
			$result = json_decode($result, true);
			return array(
				"code"=>"2000",
				"status"=>"OK",
				"message"=>"",
				"function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
				"data"=>$result,
				"compressed"=>false
			);
		}
		catch (Exception $e) {
			return logError($e,__METHOD__ );
		}
	}

	private function authenticate(){
        try {
			$fields=array("username"=>"credipaz","password"=>"08.!Rcp#@80","id"=>1);
			$url=($this->url_neoauthentication."Authenticate/");
			$result = $this->callAPI($url,$fields);
			$result = json_decode($result, true);
			return array(
				"code"=>"2000",
				"status"=>"OK",
				"message"=>"",
				"function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
				"data"=>$result,
				"compressed"=>false
			);
		}
		catch (Exception $e) {
			return logError($e,__METHOD__ );
		}
	}

	private function callAPI($url, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//curl_setopt($ch, CURLOPT_HEADER, true);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $response = curl_exec($ch);
		$response=trim($response, "\xEF\xBB\xBF");
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        return $response;
	}
}

