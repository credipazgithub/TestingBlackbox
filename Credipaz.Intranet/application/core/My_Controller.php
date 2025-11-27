<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class MY_Controller extends CI_Controller {
    public $legacy = false;

    public $ready = false;
    public $status = null;
    public $language = DEFAULT_LANGUAGE;
    public $parameters = array();
    public $psession=null;
    public $profile=null;

    public function __construct() {
        set_time_limit(0);
        parent::__construct();
    }

    public function init($lang=null){
        try {
            if ($lang!=null){$this->language=$lang;}
            $this->ready = true;

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                );
        }
        catch(Exception $e) {
            $this->ready = false;
            return logError($e,__METHOD__ );
        }
    }
    public function createModel($module,$model,$table) {
        try {
            $this->load->model($module."/".$model,$model, (DB_ACCESS_MODE=="local"));
            $this->{$model}->status=$this->{$model}->init($module."/".$model,$table,$this->language);
            if ($this->{$model}->status["status"]!="OK"){throw new Exception($this->{$model}->status["message"],(int)$this->{$model}->status["code"]);}
            return $this->{$model};
        }
        catch(Exception $e) {
            return null;
        }
    }
        
    public function neocommand($forced=false){
        try {
            if ($this->legacy) {throw new Exception(lang("error_legacy"),-1);}
            if(!$forced or $_POST["function"]=="registrarSocioNT"){
                $raw=$this->rawInput();
                if ($raw!=null)  {throw new Exception($raw);}
                $this->status=$this->init();
            }
            if (!isset($_POST["force"])) {$_POST["force"]="";}

            if (!isset($_POST["function"])) {throw new Exception(lang("error_5100"),5100);}
            if (!isset($_POST["module"])) {throw new Exception(lang("error_5101"),5101);}
            if (!isset($_POST["table"])) {throw new Exception(lang("error_5102"),5102);}
            if (!isset($_POST["model"])) {throw new Exception(lang("error_5103"),5103);}
            if (!isset($_POST["exit"])) {$_POST["exit"]="output";}
            $baseserver="http://";
            if ((int)$_SERVER["SERVER_PORT_SECURE"]!=0){$baseserver="https://";}
            $baseserver.=$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/";
            $_POST["baseserver"]=$baseserver;
            $_POST["check_token"]=true;
            switch($_POST["model"]){
                case "charges_codes": //Exception model NOT uses authentication
                case "messages": //Exception model NOT uses authentication
                case "folder_items": //Exception model NOT uses authentication
                case "web_posts": //Exception model NOT uses authentication
                case "external": //Exception model NOT uses authentication
                case "files_base64": //Exception model NOT uses authentication
                case "filesystem": //Exception model NOT uses authentication
                   $_POST["check_token"]=false;
                   break;
                default:
                   if (isset($_POST["mode"])) {
                       if ($_POST["mode"]==bin2hex(getEncryptionKey())) {$_POST["check_token"]=false;}
                   }
                   break;
            }
            if ($_POST["force"]=="force") {$_POST["check_token"]=false;}
            /*-----------------------------------
             | Break point for $_POST manipulation
             -----------------------------------*/
			$this->parameters=$_POST;
            /*-----------------------------------*/
            if ($_POST["check_token"]) {
                if(!isset($_POST["id_user_active"])){$_POST["id_user_active"]=0;}
                if(is_NaN($_POST["id_user_active"])){$_POST["id_user_active"]=0;}
                $id_user_active=(int)$_POST["id_user_active"];
                if($id_user_active==0){throw new Exception(lang("error_5107"),5107);}
                if (!isset($_POST["token_authentication"]) or $_POST["token_authentication"]=="") {throw new Exception(lang("error_5109"),5109);}
                $USERS=$this->createModel(MOD_BACKEND,"Users","Users");
                $verify=$USERS->verifyTokenAuthentication($this->parameters);
                if($verify["status"]!="OK"){throw new Exception($verify["message"],(int)$verify["code"]);}
            }
            $return=$this->InnerResolver();
            switch ($_POST["express_function"]) {
                case "GetCuotasPlain":
                    $this->outputRaw($return, "text/html");
                    break;
                case "GetCuotasPdf":
                    $this->outputRaw($return,"application/pdf");
                    break;
                default:
                    $this->{$_POST["exit"]}($return);
                    break;
            }
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function neocommandTransparent($forced=false){
        $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
        $this->neocommand($forced);
    }

    public function rawInput(){
        try {
            $str=trim(file_get_contents("php://input"));
            parse_str($str, $parsed);
            $str=json_encode($parsed);
            if (!$_POST) {$_POST = json_decode($str,true);}
            return null;
        }
        catch(Exception $e) {
            return $e;
        }
    }
    public function InnerResolver(){
        try {
            $ACTIVE=$this->createModel($this->parameters["module"],$this->parameters["model"],$this->parameters["model"]);
            return $ACTIVE->{$this->parameters["function"]}($this->parameters);
        }
        catch(Exception $e){
            return logError($e,__METHOD__." model: ".$this->parameters["model"]);
        }
    }
    public function output($return){
        $this->output
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Content-Security-Policy: frame-ancestors '.FRAME_ANCESTORS,true)
            ->set_header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method')
            ->set_header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS')
            ->set_header('Referrer-Policy: no-referrer')
            ->set_content_type('application/json','utf-8')
            ->set_output(json_encode($return));
    }
    public function download($return){
        if(!isset($return["indisk"])){$return["indisk"]=true;}
        $this->load->helper('download');
        $this->load->helper('file');
        if($return["indisk"]){
           $return["mime"]=getMimeType($return["filename"]);
        } else {
           if(!isset($return["filename"])) {$return["filename"]=uniqid('cem_',true).".".explode("/",$return["mime"])[1];}
        }
        switch($return["mode"]) {
           case "mime":
			  $this->output
                  ->set_status_header(200)
                  ->set_header('Content-Transfer-Encoding: binary;Accept-Ranges: bytes;Content-Disposition: inline; filename="'.$return["filename"].'"')
                  ->set_content_type($return["mime"])
                  ->set_output($return["message"])
                  ->_display();
                  break;
           case "html":
              $this->output
                  ->set_status_header(200)
                  ->set_content_type('text/html','utf-8')
                  ->set_output($return["message"])
                  ->_display();
                  break;
           case "view":
              $this->output
                  ->set_status_header(200)
                  ->set_header('Content-Disposition: attachment; filename="'.$return["filename"].'"')
                  ->set_content_type($return["mime"])
                  ->set_output($return["message"])
                  ->_display();
                  break;
           case "download":
              force_download($return["filename"], $return["message"]);
              break;
        }
    }
    public function outputRaw($return,$mime)
    {
        $this->output
            ->set_status_header(200)
            ->set_content_type($mime, 'utf-8')
            ->set_output($return["data"])->_display();
    }
}
