<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
class Telegram extends My_Model {
    function __construct()
    {
        parent::__construct();
    }

    public function AlertTelegramTiendaMil($values)
    {
        try {
            $sql="SELECT count(*) as total FROM ".MOD_BACKEND."_alert_control WHERE id_rel=". $values["id"]." AND table_rel='tienda_mil'";
            $alertas=$this->getRecordsAdHoc($sql);
            $i = (int) $alertas[0]["total"];
            if ($i != 0) { return false; }
            $sql="INSERT INTO ".MOD_BACKEND."_alert_control (code,[description],created,verified,offline,fum,id_rel,table_rel) VALUES ('".opensslRandom(16)."','Alerta nueva atención Tienda MIL',getdate(),getdate(),getdate(),getdate(),". $values["id"].",'tienda_mil')";
            $this->execAdHoc($sql);
            return $this->send("8269889132:AAEXa8MUgjvkjpyvVJ8t3XTSetIx-Hhe0Uk", "-4891344472","¡Hay un cliente esperando atención!");
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function send($token,$chat,$mensaje){
        try {
            $url = ("https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chat . "&text=" . urlencode($mensaje));
            $this->cUrlRestfulGet($url, "");
            return true;
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    private function cUrlRestfulGet($url, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 0);
        $jsonResponse = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        $response = $jsonResponse;
        return $response;
    }
}
