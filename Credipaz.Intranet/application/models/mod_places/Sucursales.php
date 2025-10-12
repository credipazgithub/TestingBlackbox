<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Sucursales extends MY_Model {
    public $GOOGLE_PLACES_KEY="AIzaSyAm2l3M0cVh_FZ-fa7R5K81iirb2lWZne4";
    public $GOOGLE_DIRECTIONS_KEY ="AIzaSyAm2l3M0cVh_FZ-fa7R5K81iirb2lWZne4";

    public function __construct()
    {
        parent::__construct();
    }
}
