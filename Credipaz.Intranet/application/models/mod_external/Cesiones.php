<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Cesiones extends MY_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function form($values)
    {
        try {
            $profile=getUserProfile($this,$values["id_user_active"]);
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_" . $values["model"]));
            $data["interno"] = "S";
            $sql = "SELECT * FROM DBCentral.dbo.NS_vw_descuentoCarteraBanco ORDER BY Nombre ASC";
            $data["bancos"] = $this->getRecordsAdHoc($sql);
            $html = $this->load->view("mod_external/cesiones/form", $data, true);
            logGeneral($this, $values, __METHOD__);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => compress($this, $html),
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
                "compressed" => true
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

}
