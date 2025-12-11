<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
defined('BASEPATH') OR exit('No direct script access allowed');
/*---------------------------------*/

class Backend extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }
    public function index()	{
        $this->status=$this->init();
        $data["title"] = TITLE_GENERAL;
        $data["title_page"] = TITLE_PAGE;
        $data["status"] = $this->status;
        $data["mode_login"] = "backend";
        $data["language"] = $this->language;
        $data["header"] = $this->load->view('common/_header',$data, true);
        $data["footer"] = $this->load->view('common/_footer',$data, true);
        try {
            if (!$this->ready){throw new Exception(lang("error_5002"),5002);}
            $this->load->view('login',$data);
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
	}
    public function sendPushToGroup(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'sendToGroup';
            $_POST['module'] = MOD_PUSH;
            $_POST['model'] = 'Push_out';
            $_POST['table'] = 'Push_out';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function ObtenerDeuda(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            //$_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'GetDeudaAPagar';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'NetCoreCPFinancial';
            $_POST['table'] = 'NetCoreCPFinancial';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function MenuLevelOne()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {
                throw new Exception($raw);
            }
            $this->status = $this->init();
            //$_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'menuLevelOne';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'functions';
            $_POST['table'] = 'functions';
            $this->neocommand(true);
        } catch (Exception $e) {
            $this->output(logError($e, __METHOD__));
        }
    }
    public function EsCliente(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            //$_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'EvaluarDocumento';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'ClubRedondoWS';
            $_POST['table'] = 'ClubRedondoWS';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function checkStatusPayment(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'get';
            $_POST['module'] = MOD_PAYMENTS;
            $_POST['model'] = 'Transactions';
            $_POST['table'] = 'Transactions';
            $_POST['page'] = 1;
            $_POST['pagesize'] = 1;
            $_POST['order'] = "description ASC";
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function transactionPayment(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); //Avoid authentication check
            $_POST['function'] = 'save';
            $_POST['module'] = MOD_PAYMENTS;
            $_POST['model'] = 'Transactions';
            $_POST['table'] = 'Transactions';
            $this->neocommandTransparent(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function autorizarComercios(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'autorizarComercios';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'WfcGestion';
            $_POST['table'] = 'WfcGestion';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function autorizarEfectivo(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'autorizarEfectivo';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'WfcGestion';
            $_POST['table'] = 'WfcGestion';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function autorizarTarjeta(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'autorizarTarjeta';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'WfcGestion';
            $_POST['table'] = 'WfcGestion';
	        $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function linkDirect($page, $additional=null){
        try {
            $sec=getHeader("Sec-Fetch-Site");
            $data["callback"]="";
            $data["referente"]="";
            $data["track"]="";
            $data["IdEmpresa"]="";
            if(isset($_GET["return"])){$data["callback"]=$_GET["return"];}
            if(isset($_GET["referente"])){$data["referente"]=$_GET["referente"];}
            if(isset($_GET["track"])){$data["track"]=$_GET["track"];}
            if(isset($_GET["IdEmpresa"])){$data["IdEmpresa"]=$_GET["IdEmpresa"];}
            $data["UsuarioAlta"]="WEB";
            $data["prefijo"]=".";
            $data["pre"]="";

            switch ($page) {
               case "tarjeta-admin":
               case "tarjeta-admin-auth":
                   $data["title"]="Tarjeta de crédito";
                   $data["auth"]=($page=="tarjeta-admin-auth");
                   if($additional==null){
                       $additional=0;
                   } else {
                       $data["pre"]="../";
                   }
                   //$data["id"]=$additional;
                   $data["additional"]=$additional;
                   $this->load->view("common/_external_header",$data);
                   $this->load->view(MOD_EXTERNAL."/external_forms/tarjeta",$data);
                   $this->load->view("common/_external_footer",$data);
                   break;

                case "mediya-adherir":
                case "mediya-adherir-auth":
                    $data["pUser"] = $_GET["u"];
                    $data["pSucursal"] = $_GET["s"];
                    $data["title"] = "Adhesión Mediya";
                    $data["auth"] = ($page == "mediya-adherir-auth");
                    if ($additional == null) {
                        $additional = 0;
                    } else {
                        $data["pre"] = "../";
                    }
                    //$data["id"]=$additional;
                    $data["additional"] = $additional;
                    $this->load->view("common/_external_header", $data);
                    $this->load->view(MOD_EXTERNAL . "/external_forms/mediya", $data);
                    $this->load->view("common/_external_footer", $data);
                    break;

                case "pagos-mora":
                case "pagos-mediya":
                case "pagos-tarjeta":
                case "pagos-credito":
                case "pagos-fiserv":
                case "pagos-fiserv-cicr":
                case "pagos-fiserv-crdo":
                case "pagos-fiserv-pwacp":
                case "pagos-fiserv-pwacr":
                case "pagos-fiserv-mcp":
                case "pagos-fiserv-mcr":
                   $data["mode"]="WEB";
                   $data["dni"]="";
                   $data["title"]="";
                   $data["platform"]="";
                   $data["interface"]="full";
                   $data["gateway"]="FSRV";
                   $data["form"]="";
                   $data["link"]="";
                   $data["pre"]="../";
                   switch($page){
                        case "pagos-mora":
                            $data["title"] = "Pagos Credipaz";
                            $data["platform"] = "link-pagos";
                            $data["form"] = "MOR";
                            break;
                        case "pagos-tarjeta":
                            $data["title"] = "Pagos Credipaz";
                            $data["platform"] = "link-pagos";
                            $data["form"] = "TAR";
                            break;
                        case "pagos-credito":
                            $data["title"] = "Pagos Credipaz";
                            $data["platform"] = "link-pagos";
                            $data["form"] = "CRE";
                            break;
                        case "pagos-fiserv":
                          $data["title"]="Pagos Credipaz / Mediya";
                          $data["platform"]="link-pagos";
                          $data["form"]="TAR,CRE,CICR,CRDO,SAM,MOR";
                          break;
                        case "pagos-sam":
                            $data["title"] = "Pagos SAM";
                            $data["platform"] = "link-sam";
                            $data["form"] = "SAM";
                            $data["link"] = "pagos-sam";
                            break;
                        case "pagos-fiserv-cicr":
                          $data["title"]="Pagos Mediya - Cuota inicial";
                          $data["platform"]="link-cuotainicial-clubredondo";
                          $data["form"]="CICR";
                          $data["link"]="pagos-cicr";
                          break;
                        case "pagos-mediya":
                        case "pagos-fiserv-crdo":
                            $data["title"]="Pagos Mediya - Cuota";
                          $data["platform"]="link-cuota-clubredondo";
                          $data["form"]="CRDO";
                          break;
                       case "pagos-fiserv-pwacp":
                          $data["title"]="Pagos Credipaz";
                          $data["platform"]="pwa-credipaz";
                          $data["form"]="TAR,CRE,SAM,CRDO,MOR";
                          break;
                       case "pagos-fiserv-pwacr":
                          $data["title"]="Pagos Mediya";
                          $data["platform"]="pwa-clubredondo";
                          $data["form"]="TAR,CRE,SAM,CRDO,MOR";
                          break;
                       case "pagos-fiserv-mcp":
                          $data["title"]="Pagos Credipaz";
                          $data["platform"]="movil-credipaz";
                          $data["form"]="TAR,CRE,SAM,CRDO,MOR";
                          break;
                       case "pagos-fiserv-mcr":
                          $data["title"]="Pagos Mediya";
                          $data["platform"]="movil-clubredondo";
                          $data["form"]="TAR,CRE,SAM,CRDO,MOR";
                          break;
                   }
			       $this->pagosOutput($data,$additional);
				   break;

               case "fiserv-ok-test":
                   $_POST["comments"]='[{"Tipo":"TAR","Identificacion":"0114141089","Importe":"188030.00","idTransfer":3},{"Tipo":"CRE","Identificacion":1528230,"Importe":"58120.00"}]';
                   $_POST["approval_code"]="Y:192178:4625678746:PPXX:1921784351";
                   $_POST["status"]="APROBADO";
                   $_POST["currency"]="032";
                   $_POST["chargetotal"]="1,00";
                   $_POST["ccbrand"]="VISA";
                   $_POST["bname"]="juan gomez";
                   $_POST["cardnumber"]="(VISA) ... 0005";
               case "fiserv-ok":
               case "fiserv-error":
                   $data["get"]=$_GET;
                   $data["post"]=$_POST;
        	       $NETCORECPFINANCIAL=$this->createModel(MOD_EXTERNAL,"NetCoreCPFinancial","NetCoreCPFinancial");
            	   $CLUBREDONDOWS=$this->createModel(MOD_EXTERNAL,"ClubRedondoWS","ClubRedondoWS");
				   $TRANSACTIONS=$this->createModel(MOD_PAYMENTS,"Transactions","Transactions");
				   $comments=json_decode($_POST["comments"], true);
				   $id=$comments[0]["idTransfer"];

				   $record=$TRANSACTIONS->get(array("where"=>"id=".$id));
				   $dni_request=$record[0]["dni_request"];
                   status=$record[0]["status"];
                   if ($status=="INICIADO") {
                       $registro_externo=explode(":",$_POST["approval_code"])[1];
                       $params=array(
			               'id'=>$id,
                           'status' => $_POST["status"],
                           'currency_response' => $_POST["currency"],
                           'dni_response' => "",
                           'amount_response' => str_replace(',','.',$_POST["chargetotal"]),
                           'card_response' => $_POST["ccbrand"],
                           'card_name' => $_POST["bname"],
                           'partial_card_number' => $_POST["cardnumber"],
                           'message' => $_POST["approval_code"],
                           'raw_response' => serialize($_POST),
                           'registro_externo' => $registro_externo,
			           );
                       $saved=$NETCORECPFINANCIAL->PagosTerminarTransaccion($params);
				       logGeneral($this,$_POST,__METHOD__);

                       if(($page=="fiserv-ok" or $page=="fiserv-ok-test" ) and $id!=null) {
				           if ($_POST["status"]=="APROBADO") {
					           $params2=array(
                                  "id"=>$id,
						          "servicioPago"=>"FSRV",
						          "NroDocumento"=>$dni_request,
						          "TipoUsuario"=>"CP",
						          "itemsPagos"=>$comments,
						          "origen"=>7, //Web intranet - Btn de pago implementado!
						          "MedioPago"=>$_POST["ccbrand"],
						          "Resultado"=>$_POST["status"],
						          "Transaccion"=>$registro_externo,
						          "Respuesta"=>serialize($_POST),
						          "posProceso"=>"pagosonline",
                                  "Registro_externo"=>(string)$registro_externo
					           );
					           $CLUBREDONDOWS->registrarCobranza($params2);
				           }
                       }
                   }
                   $this->load->view("common/_external_header",$data);
				   $data["post"]=$_POST;
				   $data["get"]=$_GET;
                   $this->load->view(MOD_PAYMENTS."/payments/".$page,$data);
                   $this->load->view("common/_external_footer",$data);
				   break;

			   case "resetPassword":
                   $data["title"]="Blanqueo de contraseña";
                   $data["additional"]=$additional;
		           $data["pre"]="../";
                   $this->load->view("common/_external_header",$data);
                   $this->load->view(MOD_BACKEND."/users/reset",$data);
                   $this->load->view("common/_external_footer",$data);
                   break;
               case "credipaz-dev-support":
                   $data["title"]="Soporte";
                   $this->load->view("common/_external_header",$data);
                   $this->load->view(MOD_WEB_POSTS."/web_posts/credipaz-dev-support",$data);
                   $this->load->view("common/_external_footer",$data);
                   break;
               case "credipaz-privacy-policy":
                   $data["title"]="Política de privacidad";
                   $data["body_post"]="";
                   $MOD_WEB_POSTS=$this->createModel(MOD_WEB_POSTS,"Web_posts","Web_posts");
                   $record=$MOD_WEB_POSTS->get(array("page"=>1,"where"=>("id=3")));
                   if ($record["status"]=="OK"){$data["body_post"]=$record["data"][0]["body_post"];}
                   $this->load->view("common/_external_header",$data);
                   $this->load->view(MOD_WEB_POSTS."/web_posts/credipaz-privacy-policy",$data);
                   $this->load->view("common/_external_footer",$data);
                   break;
               case "upload":
                   $data["title"]="Upload";
                   if(isset($data["track"])){$data["track"]=base64_decode($data["track"]);}
                   if(isset($data["referente"])){$data["referente"]=base64_decode($data["referente"]);}
                   $this->load->view("common/_external_header",$data);
                   $this->load->view(MOD_BACKEND."/upload/form",$data);
                   $this->load->view("common/_external_footer",$data);
                   break;
               default:
                   $data["title"]="Error 404";
                   $data["heading"]=lang('error_404');
                   $data["message"]=$page;
                   $this->load->view("errors/html/error_404",$data);
                   break;
            }
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
	}
    public function DirectLink($link){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = "view";
            $_POST['exit'] = "download";
            $_POST['data'] = $link;
            $_POST['function'] = 'fileLoader';
            $_POST['module'] = MOD_DBCENTRAL;
            $_POST['model'] = 'filesystem';
            $_POST['table'] = 'filesystem';
            $this->neocommand(false);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function ExternalLink($link){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = "view";
            $_POST['exit'] = "download";
            $_POST['data'] = $link;
            $_POST['function'] = 'fileLoader';
            $_POST['module'] = MOD_DBCENTRAL;
            $_POST['model'] = 'filesystem';
            $_POST['table'] = 'filesystem';
            $this->neocommand(false);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
	public function webpostDirectLink($link) {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = "view";
            $_POST['exit'] = "output";
            $_POST['data'] = $link;
            $_POST['function'] = 'fileLoader';
            $_POST['module'] = MOD_WEB_POSTS;
            $_POST['model'] = 'web_posts';
            $_POST['table'] = 'web_posts';
            $this->neocommandTransparent(false);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function folderDirectLink($link) {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = "view";
            $_POST['exit'] = "download";
            $_POST['data'] = $link;
            $_POST['function'] = 'fileLoader';
            $_POST['module'] = MOD_FOLDERS;
            $_POST['model'] = 'folder_items';
            $_POST['table'] = 'folder_items';
            $this->neocommand(false);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function folderExternalLink($link) {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['data'] = $link;
            $_POST['module'] = MOD_FOLDERS;
            $_POST['model'] = 'folder_items';
            $_POST['table'] = 'folder_items';
            $ACTIVE=$this->createModel(MOD_FOLDERS,"Folder_items","Folder_items");
            $ret=$ACTIVE->fileExternal($_POST);
            $this->load->view(MOD_FOLDERS.'/folder_items/form',$ret);
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
    }
    public function providersDirectLink($link) {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = "view";
            $_POST['exit'] = "download";
            $_POST['data'] = $link;
            $_POST['function'] = 'fileLoader';
            $_POST['module'] = MOD_PROVIDERS;
            $_POST['model'] = 'folder_items';
            $_POST['table'] = 'folder_items';
            $this->neocommand(false);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function providersExternalLink($link) {
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['data'] = $link;
            $_POST['module'] = MOD_PROVIDERS;
            $_POST['model'] = 'folder_items';
            $_POST['table'] = 'folder_items';
            $ACTIVE=$this->createModel(MOD_PROVIDERS,"Folder_items","Folder_items");
            $ret=$ACTIVE->fileExternal($_POST);
            $this->load->view(MOD_PROVIDERS.'/folder_items/form',$ret);
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
    }
    public function mediya(){
        $this->status=$this->init();
        $data["title"] = "<img src='./assets/img/mediya.png' style='height:150px;'/><span style='vertical-align:middle;'></span>";
        $data["title_page"] = TITLE_PAGE;
        $data["status"] = $this->status;
        $data["mode_login"] = "mediya";
        $data["language"] = $this->language;
        $data["header"] = $this->load->view('common/_header',$data, true);
        $data["footer"] = $this->load->view('common/_footer',$data, true);
        try {
            if (!$this->ready){throw new Exception(lang("error_5002"),5002);}
            $this->load->view('login',$data);
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
	}
    public function integracion(){
        $this->status=$this->init();
        $data["title"] = "<img src='./assets/img/integracion.png' style='height:150px;'/><span style='vertical-align:middle;'></span>";
        $data["title_page"] = TITLE_PAGE;
        $data["status"] = $this->status;
        $data["mode_login"] = "integracion";
        $data["language"] = $this->language;
        $data["header"] = $this->load->view('common/_header',$data, true);
        $data["footer"] = $this->load->view('common/_footer',$data, true);
        try {
            if (!$this->ready){throw new Exception(lang("error_5002"),5002);}
            $this->load->view('login',$data);
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
	}
    public function tiendamil(){
        $this->status=$this->init();
        $data["title"] = "<img src='./assets/img/tiendamil.png' style='height:150px;'/><span style='vertical-align:middle;'></span>";
        $data["title_page"] = TITLE_PAGE;
        $data["status"] = $this->status;
        $data["mode_login"] = "tiendamil";
        $data["language"] = $this->language;
        $data["header"] = $this->load->view('common/_header',$data, true);
        $data["footer"] = $this->load->view('common/_footer',$data, true);
        try {
            if (!$this->ready){throw new Exception(lang("error_5002"),5002);}
            $this->load->view('login',$data);
        }
        catch (Exception $e){
            $data["code"]=$e->getCode();
            $data["message"]=$e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error',$data);
        }
	}
    public function cesiones()
    {
        $this->status = $this->init();
        $data["title"] = "<img src='./assets/img/logo.png' style='height:150px;'/><span style='vertical-align:middle;'></span>";
        $data["title_page"] = TITLE_PAGE;
        $data["status"] = $this->status;
        $data["mode_login"] = "cesiones";
        $data["language"] = $this->language;
        $data["header"] = $this->load->view('common/_header', $data, true);
        $data["footer"] = $this->load->view('common/_footer', $data, true);
        try {
            if (!$this->ready) {
                throw new Exception(lang("error_5002"), 5002);
            }
            $this->load->view('login', $data);
        } catch (Exception $e) {
            $data["code"] = $e->getCode();
            $data["message"] = $e->getMessage();
            $data["title"] = $e->getCode();
            $this->load->view('common/_error', $data);
        }
    }
    public function catalogoMIL(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'catalogoMIL';
            $_POST['module'] = MOD_DIRECT_SALE;
            $_POST['model'] = 'products';
            $_POST['table'] = 'products';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function express($function,$key,$code=null){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'executeExpress';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
            $_POST['express_function'] = $function;
            $_POST['express_key'] = $key;
            if ($code!=null){$_POST['express_code'] = $code;}
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function expressFile(){
        $mime=getMimeType($_GET['file']);
        $bin=getFileBinSSH($_GET['file']);
        $this->output
            ->set_status_header(200)
            ->set_content_type($mime)
            ->set_output($bin)
            ->_display();
    }
    public function informUserArea(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'informUserArea';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'Users';
            $_POST['table'] = 'Users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function Uif(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey());
            $_POST['function'] = 'offlineDocuments';
            $_POST['module'] = MOD_FOLDERS;
            $_POST['model'] = 'folders';
            $_POST['table'] = 'folders';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function UiAlertDelayTelemedicina(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey());
            $_POST['function'] = 'alertDelayTelemedicina';
            $_POST['module'] = MOD_TELEMEDICINA;
            $_POST['model'] = 'charges_codes';
            $_POST['table'] = 'charges_codes';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function UiSyncBeneficiosGerdanna(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'syncRemoteData';
            $_POST['module'] = MOD_CLUB_REDONDO;
            $_POST['model'] = 'beneficios';
            $_POST['table'] = 'beneficios';
            $_POST['branch'] = 'GERDANNA';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function UiSyncBeneficiosCR(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'syncRemoteData';
            $_POST['module'] = MOD_CLUB_REDONDO;
            $_POST['model'] = 'beneficios';
            $_POST['table'] = 'beneficios';
            $_POST['branch'] = 'CR';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function UiProcessExternalChunk(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['module'] = MOD_CLUB_REDONDO;
            $_POST['model'] = 'beneficios';
            $_POST['table'] = 'beneficios';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function UiDropCanjes(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'dropCanjes';
            $_POST['module'] = MOD_CLUB_REDONDO;
            $_POST['model'] = 'canjes';
            $_POST['table'] = 'canjes';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function logGeneral(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'log_general';
            $_POST['table'] = 'log_general';
            $_POST['function'] = 'forceLogGeneral';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function authenticate(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            if (!isset($_POST["username"])) {throw new Exception(lang("error_5104"),5104);}
            if (!isset($_POST["password"])) {throw new Exception(lang("error_5105"),5105);}
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'authenticate';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function getSucursales(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'getSucursales';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function sendThreads(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'send';
            $_POST['module'] = MOD_CHANNELS;
            $_POST['model'] = 'threads';
            $_POST['table'] = 'threads';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function retrieveChannels(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'retrieve';
            $_POST['module'] = MOD_CHANNELS;
            $_POST['model'] = 'threads';
            $_POST['table'] = 'threads';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function importVademecum(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'importVademecum';
            $_POST['module'] = MOD_TELEMEDICINA;
            $_POST['model'] = 'vademecum';
            $_POST['table'] = 'vademecum';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
	public function downloadSwiss($dni){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = "mime";
            $_POST['exit'] = "download";
            $_POST['function'] = 'fileLoaderSwiss';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
            $_POST['dni'] = $dni;
            $this->neocommand(false);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    public function downloadResumen($periodo,$archivo){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $archivo=str_replace('.pdf','',$archivo);
            $_POST['mode'] = "view";
            $_POST['exit'] = "download";
            $_POST['function'] = 'fileLoader';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
            $_POST['periodo'] = $periodo;
            $_POST['archivo'] = $archivo;
            $this->neocommand(false);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function downloadBase64File($code,$description){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = "view";
            $_POST['exit'] = "download";
            $_POST['function'] = 'fileLoader';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'files_base64';
            $_POST['table'] = 'files_base64';
            $_POST['code'] = $code;
            $_POST['description'] = $description;
            $this->neocommand(false);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function downloadContratoTarjeta($archivo){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = "view";
            $_POST['exit'] = "download";
            $_POST['function'] = 'fileLoaderContratos';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'external';
            $_POST['table'] = 'external';
            $_POST['archivo'] = $archivo;
            $this->neocommand(false);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function logged(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $FUNCTIONS=$this->createModel(MOD_BACKEND,"Functions","Functions");
			$preferences=getPreference($this,$_POST,1);

            $menu=$FUNCTIONS->menuTree($_POST);
            $data["title"] = TITLE_GENERAL;
            $data["language"] = $this->language;
            $data["menu"] = $menu["data"];
            $html=$this->load->view("logged",$data,true);
            $return=array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
            $this->output($return);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function loggedIntegracion(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $FUNCTIONS=$this->createModel(MOD_BACKEND,"Functions","Functions");
			$preferences=getPreference($this,$_POST,1);

            $menu=$FUNCTIONS->menuTree($_POST);
            $data["title"] = TITLE_GENERAL;
            $data["language"] = $this->language;
            $data["menu"] = $menu["data"];
            $html=$this->load->view("loggedIntegracion",$data,true);
            $return=array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
            $this->output($return);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function loggedCesiones()
    {
        try {
            $raw = $this->rawInput();
            if ($raw != null) {throw new Exception($raw);}
            $data["title"] = TITLE_GENERAL;
            $data["language"] = $this->language;
            $data["interno"] = "N";
            $data["id_user_active"] = $_POST["id_user_active"];
            $html = $this->load->view("mod_external/cesiones/form", $data, true);
            $return = array(
                "code" => "2000",
                "status" => "OK",
                "message" => compress($this, $html),
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => true
            );
            $this->output($return);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $data
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function loggedTiendaMil(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $FUNCTIONS=$this->createModel(MOD_BACKEND,"Functions","Functions");
			$preferences=getPreference($this,$_POST,1);

            $menu=$FUNCTIONS->menuTree($_POST);
            $data["title"] = TITLE_GENERAL;
            $data["language"] = $this->language;
            $data["menu"] = $menu["data"];
            $html=$this->load->view("loggedTiendaMil",$data,true);
            $return=array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
            $this->output($return);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
	public function loggedMediYa(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $FUNCTIONS=$this->createModel(MOD_BACKEND,"Functions","Functions");
			$preferences=getPreference($this,$_POST,1);

            $menu=$FUNCTIONS->menuTree($_POST);
            $data["title"] = TITLE_GENERAL;
            $data["language"] = $this->language;
            $data["menu"] = $menu["data"];
            $html=$this->load->view("loggedMediYa",$data,true);
            $return=array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
            $this->output($return);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>$data
            );
        }
        catch (Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function logout(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'logout';
            $_POST['module'] = MOD_BACKEND;
            $_POST['model'] = 'users';
            $_POST['table'] = 'users';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function reOrganizeFiles(){
        try {
			$raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'reorganize';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'reorganizer';
            $_POST['table'] = 'reorganizer';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
    public function UiAFIPalameEsta(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'AFIPalameEsta';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'ClubRedondoWS';
            $_POST['table'] = 'ClubRedondoWS';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
    }
	public function eventoActual(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'EventoActual';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'ClubRedondoWS';
            $_POST['table'] = 'ClubRedondoWS';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
	public function checkInvitado(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'CheckInvitado';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'ClubRedondoWS';
            $_POST['table'] = 'ClubRedondoWS';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
	public function registrarIngreso(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'RegistrarIngreso';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'ClubRedondoWS';
            $_POST['table'] = 'ClubRedondoWS';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
	public function responseTransactionAsync(){
        try {
            $raw=$this->rawInput();
            if ($raw!=null)  {throw new Exception($raw);}
            $this->status=$this->init();
            $_POST['mode'] = bin2hex(getEncryptionKey()); /*Avoid authentication check*/
            $_POST['function'] = 'responseTransactionAsync';
            $_POST['module'] = MOD_EXTERNAL;
            $_POST['model'] = 'ClubRedondoWS';
            $_POST['table'] = 'ClubRedondoWS';
            $this->neocommand(true);
        }
        catch (Exception $e){
            $this->output(logError($e,__METHOD__ ));
        }
	}
    private function pagosOutput($data, $additional)
    {
        if (!isset($data["interface"])) {$data["interface"] = "";}
        if ($additional != null) {
            $add = base64_decode($additional);
            $add = explode("|", $add);
            $data["dni"] = $add[0];
            $data["importe"] = $add[1];
        }
        $html = $this->load->view("common/_external_header", $data, true);
        $html .= $this->load->view(MOD_PAYMENTS . "/payments/" . $data["interface"], $data, true);
        $html .= $this->load->view("common/_external_footer", $data, true);
        $this->output
            ->set_header('HTTP/1.0 200 OK')
            ->set_header('HTTP/1.1 200 OK')
            ->set_header('Access-Control-Allow-Origin: *', true)
            ->set_header('Access-Control-Allow-Headers: *', true)
            ->set_header('Access-Control-Allow-Methods: *', true)
            ->set_header('Content-Security-Policy: frame-ancestors ' . FRAME_ANCESTORS, true)
            ->set_content_type('text/html', 'utf-8')
            ->set_output($html);
    }
    private function modulosOutput($data, $additional)
    {
        if (!isset($data["interface"])) {
            $data["interface"] = "form";
        }
        if ($additional != null) {
            $data["id_socio"] = base64_decode($additional);
        }
        $html = $this->load->view("common/_external_header", $data, true);
        $html .= $this->load->view(MOD_CLUB_REDONDO . "/rel_modulos/" . $data["interface"], $data, true);
        $html .= $this->load->view("common/_external_footer", $data, true);
        $this->output
            ->set_header('HTTP/1.0 200 OK')
            ->set_header('HTTP/1.1 200 OK')
            ->set_header('Access-Control-Allow-Origin: *', true)
            ->set_header('Access-Control-Allow-Headers: *', true)
            ->set_header('Access-Control-Allow-Methods: *', true)
            ->set_header('Content-Security-Policy: frame-ancestors ' . FRAME_ANCESTORS, true)
            ->set_content_type('text/html', 'utf-8')
            ->set_output($html);
    }
}


