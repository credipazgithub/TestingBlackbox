<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Payments_fiserv extends MY_Model {

    public function __construct()
    {
        parent::__construct();
    }

	public function buildView($data){
		$data["mode"]="";
		$data["dni"]="";
        $html=$this->load->view(MOD_PAYMENTS."/payments/".$data["interface"],$data,true);
        
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>compress($this,$html),
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>null,
            "compressed"=>true
        );
	}
    public function form_full($values){
        try {
            $data["title"]="Pagos Credipaz / Mediya";
            $data["platform"]="intranet-pagos";
            $data["interface"]="full";
            $data["gateway"]="FSRV";
            $data["form"]="TAR,CRE,CICR,CRDO,SAM,MOR";
            $data["link"]="";
            $data["parameters"]=$values;
            //$data["id_type_item"]=comboTypeItems($this);
			return $this->buildView($data);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function form($values){
        try {
            $data["title"]="Datos del pago a procesar";
            $data["platform"]="intranet-pagos";
            $data["interface"]="full";
            $data["gateway"]="FSRV";
            $data["form"]="TAR,CRE,SAM,CRDO,MOR";
            $data["link"]="";
            $data["parameters"]=$values;
			return $this->buildView($data);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function form_cicr($values){
        try {
            $data["title"]="Pagos Mediya - Cuota inicial";
            $data["platform"]="link-cuotainicial-clubredondo";
            $data["interface"]="full";
            $data["gateway"]="FSRV";
            $data["form"]="CICR";
            $data["link"]="pagos-cicr";
            $data["parameters"]=$values;
			return $this->buildView($data);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function form_crdo($values){
        try {
            $data["title"]="Pagos Mediya - Cuota";
            $data["platform"]="link-cuota-clubredondo";
            $data["interface"]="full";
            $data["gateway"]="FSRV";
            $data["form"]="CRDO";
            $data["link"]="pagos-crdo";
            $data["parameters"]=$values;
			return $this->buildView($data);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function form_sam($values)
    {
        try {
            $data["title"] = "Pagos SAM";
            $data["platform"] = "link-sam";
            $data["interface"] = "full";
            $data["gateway"] = "FSRV";
            $data["form"] = "SAM";
            $data["link"] = "pagos-sam";
            $data["parameters"] = $values;
            return $this->buildView($data);
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    public function buildFormFiserv($values){
        try {
		    if(!isset($values["parentUri"])){$values["parentUri"]="";}
		    if(!isset($values["pagoTarjeta"])){$values["pagoTarjeta"]=0;}
		    if(!isset($values["paymentMethod"])){$values["paymentMethod"]="";}
		    if(!isset($values["styleButton"])){$values["styleButton"]="";}
			if($values["styleButton"]==""){$values["styleButton"]="border-radius:5px;padding:3px;color:black;border:solid 1px white !important;background-color:WHITE !important;";}
		    if(!isset($values["currency"])){$values["currency"]="032";}
		    if(!isset($values["dni"])){$values["dni"]="";}
		    if(!isset($values["installments"])){$values["installments"]=0;}
			if((int)$values["installments"]==0){$values["installments"]=1;}
		    if(!isset($values["responseFailURL"])){$values["responseFailURL"]="";}
		    if(!isset($values["responseSuccessURL"])){$values["responseSuccessURL"]="";}
		    if(!isset($values["targetFrame"])){$values["targetFrame"]="iframe_fiserv";}
		    if($values["responseFailURL"]==""){$values["responseFailURL"]="https://intranet.credipaz.com/linkDirect/fiserv-error";}
		    if($values["responseSuccessURL"]==""){$values["responseSuccessURL"]="https://intranet.credipaz.com/linkDirect/fiserv-ok";}

			$checkoutoption="classic";
			$hostURI="https://intranet.credipaz.com/linkDirect/fiserv-notify";
			$txntype="sale";
			$timezone="America/Buenos_Aires";
			$txndatetime=date("Y:m:d-H:i:s");
		    $currency=$values["currency"];
			$chargetotal=$values["total"];
		    if(!isset($values["itemsPagos"])){
			   $values["itemsPagos"]=array(
					array(
						"Tipo"=>"TAR",
						"Identificacion"=>($values["dni"]." Pago tarjeta"),
						"Importe"=>$chargetotal,
						"idTransfer"=>0
					)
				);
				$values["itemsPagos"]=json_encode($values["itemsPagos"]);
			}
			$identificacion="";
			$values["itemsPagos"]=json_decode($values["itemsPagos"], true);
			$identificacion=$values["itemsPagos"][0]["Identificacion"];
			$responseFailURL=$values["responseFailURL"];
			$responseSuccessURL=$values["responseSuccessURL"];
			$transactionNotificationURL="https://intranet.credipaz.com/linkDirect/fiserv-notify";
			$authenticateTransaction="false";
			$mode="payonly";
			$cardFunction="";
			$dynamicMerchantName="";
			$invoicenumber="";
			$item1="";
			$language="es_ES";
			$merchantTransactionId="";
			$mobileMode="true";
			$numberOfInstallments=$values["installments"];
			$oid="";
			//Si viene vacío muestra todas las tarjetas posibles, sino hay que pasarle los valores desde la implementación!
			$paymentMethod=$values["paymentMethod"]; 
			$refer="";
			$referencedMerchantTransactionID="";
			$referencedSchemeTransactionId="";
			$shipping="";
			$trxOrigin="ECI";
			$vattax="";
			$action="";
			$storeID="";
			$sharedSecret="";
			$hash_algorithm="SHA256";
			$parentUri=$values["parentUri"];
   			$action=FISERV_URL;
			$storeID=FISERV_STOREID;
			$sharedSecret=FISERV_SHAREDSECRET;
            $stringToHash = ($storeID.$txndatetime.$chargetotal.$currency.$sharedSecret);  
            $extendedHash = hash("sha256", bin2hex($stringToHash));
			$action.=("?".opensslRandom(10));

			$html="  <form id='checkoutform' method='post' action='".$action."' target='".$values["targetFrame"]."'>";
			$html.="   <table style='width:100%;' class='tbl-fiserv d-none'>";
			/*Mandatory*/
			$html.="    <tr><td style='font-weight:bold;'>parentUri</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='parentUri' name='parentUri' value='".$parentUri."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>checkoutoption</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='checkoutoption' name='checkoutoption' value='".$checkoutoption."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>hostURI</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='hostURI' name='hostURI' value='".$hostURI."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>txntype</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='txntype' name='txntype' value='".$txntype."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>timezone</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='timezone' name='timezone' value='".$timezone."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>txndatetime</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='txndatetime' name='txndatetime' value='".$txndatetime."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>hash_algorithm</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='hash_algorithm' name='hash_algorithm' value='".$hash_algorithm."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>hash</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='hash' name='hash' value='".$extendedHash."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>storename</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='storename' name='storename' value='".$storeID."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>currency</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='currency' name='currency' value='".$currency."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>chargetotal</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='chargetotal' name='chargetotal' value='".$chargetotal."' /></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>responseFailURL</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='responseFailURL' name='responseFailURL' value='".$responseFailURL."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>responseSuccessURL</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='responseSuccessURL' name='responseSuccessURL' value='".$responseSuccessURL."'/></td></tr>";
			//$html.="    <tr><td style='font-weight:bold;'>transactionNotificationURL</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='transactionNotificationURL' name='transactionNotificationURL' value='".$transactionNotificationURL."'/></td></tr>";
			$html.="    <tr><td style='font-weight:bold;'>authenticateTransaction</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='authenticateTransaction' name='authenticateTransaction' value='".$authenticateTransaction."'/></td></tr>";
			/*Optionals*/
			$html.="    <tr><td style='font-weight:bold;'>mode</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='mode' name='mode' value='".$mode."'/></td></tr>";
			if($cardFunction!=""){$html.="<tr><td style='font-weight:bold;'>cardFunction</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='cardFunction' name='cardFunction' value='".$cardFunction."'/></td></tr>";}
			$html.="<tr><td style='font-weight:bold;'>comments</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='comments' name='comments' value=''/></td></tr>";
			if($identificacion!=""){$html.="<tr><td style='font-weight:bold;'>customerid</td><td style='width:100%;'><input sclass='dataPost' tyle='width:100%;' type='text' id='customerid' name='customerid' value='".$identificacion."'/></td></tr>";}
			if($dynamicMerchantName!=""){$html.="<tr><td style='font-weight:bold;'>dynamicMerchantName</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='dynamicMerchantName' name='dynamicMerchantName' value='".$dynamicMerchantName."'/></td></tr>";}
			if($invoicenumber!=""){$html.="<tr><td style='font-weight:bold;'>invoicenumber</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='invoicenumber' name='invoicenumber' value='".$invoicenumber."'/></td></tr>";}
			if($item1!=""){$html.="<tr><td style='font-weight:bold;'>item1</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='item1' name='item1' value='".$item1."'/></td></tr>";}
			if($language!=""){$html.="<tr><td style='font-weight:bold;'>language</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='language' name='language' value='".$language."'/></td></tr>";}
			if($merchantTransactionId!=""){$html.="<tr><td style='font-weight:bold;'>merchantTransactionId</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='merchantTransactionId' name='merchantTransactionId' value='".$merchantTransactionId."'/></td></tr>";}
			if($mobileMode!=""){$html.="<tr><td style='font-weight:bold;'>mobileMode</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='mobileMode' name='mobileMode' value='".$mobileMode."'/></td></tr>";}
			if($numberOfInstallments!=""){$html.="<tr><td style='font-weight:bold;'>numberOfInstallments</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='numberOfInstallments' name='numberOfInstallments' value='".$numberOfInstallments."'/></td></tr>";}
			if($oid!=""){$html.="<tr><td style='font-weight:bold;'>oid</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='oid' name='oid' value='".$oid."'/></td></tr>";}
			$html.="<tr><td style='font-weight:bold;'>paymentMethod</td><td style='width:100%;'><input class='dataPost paymentMethod' style='width:100%;' type='text' id='paymentMethod' name='paymentMethod' value='".$paymentMethod."'/></td></tr>";
			if($refer!=""){$html.="<tr><td style='font-weight:bold;'>refer</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='refer' name='refer' value='".$refer."'/></td></tr>";}
			$html.="<tr><td style='font-weight:bold;'>referencedMerchantTransactionID</td><td class='dataPost' style='width:100%;'><input style='width:100%;' type='text' id='referencedMerchantTransactionID' name='referencedMerchantTransactionID' value='".$referencedMerchantTransactionID."'/></td></tr>";
			if($referencedSchemeTransactionId!=""){$html.="<tr><td style='font-weight:bold;'>referencedSchemeTransactionId</td><td class='dataPost' style='width:100%;'><input style='width:100%;' type='text' id='referencedSchemeTransactionId' name='referencedSchemeTransactionId' value='".$referencedSchemeTransactionId."'/></td></tr>";}
			if($shipping!=""){$html.="<tr><td style='font-weight:bold;'>shipping</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='shipping' name='shipping' value='".$shipping."'/></td></tr>";}
			if($trxOrigin!=""){$html.="<tr><td style='font-weight:bold;'>trxOrigin</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='trxOrigin' name='trxOrigin' value='".$trxOrigin."'/></td></tr>";}
			if($vattax!=""){$html.="<tr><td style='font-weight:bold;'>vattax</td><td style='width:100%;'><input class='dataPost' style='width:100%;' type='text' id='vattax' name='vattax' value='".$vattax."'/></td></tr>";}
			$html.="   </table>";
			$html.="   <br/>";
			$html.="   <h3>Seleccione tarjeta y complete sus datos de pago</h3>";
			$html.="   <a href='#' data-tc='' class='btn btn-raised btn-info btn-pagar-fiserv btn-all'>Elegir tarjeta para el pago</a>";
			$html.="  </form>";
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "data"=>$html,
                "message"=>"",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT)
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function initTransactionFiserv($values){
        try {
            $TRANSACTIONS=$this->createModel(MOD_PAYMENTS,"Transactions","Transactions");
            
			$values["id"]=0;
            return $TRANSACTIONS->save($values,null);

            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>compress($this,$html),
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                "data"=>null,
                "compressed"=>true
            );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
}
