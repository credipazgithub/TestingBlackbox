<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class NeoSignature extends MY_Model {
    private $url_neoauthentication="https://api.gruponeodata.com/neoauthentication.v1/";
    private $url_neosignature="https://api.gruponeodata.com/neosignature.v1/";
    //private $url_neosignature="https://localhost:44315/neosignature.v1/";

	private $id_application=5;

    public function __construct()
    {
        parent::__construct();
    }
	public function setTransfer($fields){
        try {
		    $auth=$this->authenticate();
			if ($auth["data"]["status"]=="ERROR"){throw new Exception(lang("error_10001"),10001);}
			$id_user=$auth["data"]["records"][0]["id_user"];
			$fields["id_user"]=$id_user;
			$fields["id_application"]=$this->id_application;
			$fields["token"]=$auth["data"]["tokenSingleUse"];
			$url=($this->url_neosignature."Create/");
			$result = $this->callAPI($url,$fields);
			$result = json_decode($result, true);
            sleep(1);
            $id=$result["records"][0]["id"];
            $result["link_certificate"] = "";
            $result["link_extract"] = "";
            if ($id != "" and $id != "0" and $id!=0) {
                $result["link_certificate"] = $this->url_neosignature . "Certificate?id_application=" . $this->id_application . "&id_user=" . $id_user . "&id=" . $id . "&mode=0&token=" . $auth["data"]["tokenEthernal"];
                $result["link_extract"] = $this->url_neosignature . "RawData?id_application=" . $this->id_application . "&id_user=" . $id_user . "&id=" . $id . "&mode=0&token=" . $auth["data"]["tokenEthernal"];
            }
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
	public function getLink($link){
	   return $this->cUrlRestfulGet($link);
	}
	private function authenticate(){
        try {
			$fields=array("username"=>"credipaz","password"=>"08.!Rcp#@80","id"=>$this->id_application);
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
	private function cUrlRestfulGet($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        $response = curl_exec($ch);
		$response=trim($response, "\xEF\xBB\xBF");
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err=curl_error($ch);
        curl_close($ch);
        return $response;
    }
}

