<?php
require_once './vendor/autoload.php';
MercadoPago\SDK::setAccessToken("TEST-2285802329174953-100113-0f431493a56a83c9b86ce3e1870df22f-239072463");	    

$input=file_get_contents('php://input');

$data=json_decode($input,true);
$parsed_body=$data["formData"];

/*Forzar $1 para el pago*/
//$parsed_body['transaction_amount']=1;

/*
file_put_contents("./0.txt", $input);
file_put_contents("./1.txt", $parsed_body['transaction_amount']);
file_put_contents("./2.txt", $parsed_body['token']);
file_put_contents("./3.txt", $parsed_body['installments']);
file_put_contents("./4.txt", $parsed_body['payment_method_id']);
file_put_contents("./5.txt", $parsed_body['issuer_id']);
file_put_contents("./6.txt", $parsed_body['payer']['email']);
file_put_contents("./7.txt", $parsed_body['payer']['identification']['type']);
file_put_contents("./8.txt", $parsed_body['payer']['identification']['number']);
*/

$payment = new MercadoPago\Payment();
$payment->transaction_amount = (float)$parsed_body['transaction_amount'];
$payment->token = $parsed_body['token'];
$payment->installments = $parsed_body['installments'];
$payment->payment_method_id = $parsed_body['payment_method_id'];
$payment->issuer_id = $parsed_body['issuer_id'];
   
$payer = new MercadoPago\Payer();
$payer->email = $parsed_body['payer']['email'];
$payer->identification = array(
    "type" => $parsed_body['payer']['identification']['type'],
    "number" => $parsed_body['payer']['identification']['number']
);
$payment->payer = $payer;
$payment->save();

/*
file_put_contents("./z1.txt",$payment->status);
file_put_contents("./z2.txt", $payment->status_detail);
file_put_contents("./z3.txt", $payment->id);
*/

$response = array(
	'status' => $payment->status,
	'status_detail' => $payment->status_detail,
	'id' => $payment->id
);

ob_clean();
header_remove(); 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
http_response_code(200);
echo json_encode($response);
exit();
?>