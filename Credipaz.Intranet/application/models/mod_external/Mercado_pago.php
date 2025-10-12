<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Mercado_pago extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

	public function pagosMercadoPago(){
	    $this->load->library("MercadoPago");
	    $this->MercadoPago->payment->transaction_amount = (float)$_POST['transactionAmount'];
	    $this->MercadoPago->payment->token = $_POST['token'];
		$this->MercadoPago->payment->description = $_POST['description'];
		$this->MercadoPago->payment->installments = (int)$_POST['installments'];
		$this->MercadoPago->payment->payment_method_id = $_POST['paymentMethodId'];
		$this->MercadoPago->payment->issuer_id = (int)$_POST['issuer'];
		$this->MercadoPago->payer = new MercadoPago\Payer();
		$this->MercadoPago->payer->email = $_POST['cardholderEmail'];
		$this->MercadoPago->payer->identification = array(
			"type" => $_POST['identificationType'],
			"number" => $_POST['identificationNumber']
		);
		$this->MercadoPago->payer->first_name = $_POST['cardholderName'];
		$this->MercadoPago->payment->payer = $this->MercadoPago->payer;
		$this->MercadoPago->payment->save();

		$response = array(
			'status' => $this->MercadoPago->payment->status,
			'status_detail' => $this->MercadoPago->payment->status_detail,
			'id' => $this->MercadoPago->payment->id
		);
		return $response;
	}
}

