<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Reorganizer extends MY_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function reorganize(){
        try {
		    $turnos=$this->processTurnos();
		    $swiss=$this->processSwiss();

			$ret=array(
			   "Turnos"=>$turnos,
			   "Swiss"=>$swiss
			);
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>$ret,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                );
        }
        catch (Exception $e) {
            return logError($e,__METHOD__ );
        }
    }

	private function processTurnos(){
		$files = glob(FILES_TURNOS_LOCAL."*");
		$i=0;
		$sftp=connectSFTP();
		foreach ($files as $file) {
			if (!is_file($file)) {continue;}
			$targetRemote=(FILES_TURNOS_SSH.basename($file));
			$binData=file_get_contents($file);
			unlink($file);
			file_put_contents("ssh2.sftp://".intval($sftp).$targetRemote,$binData);
			$i+=1;
		}
		return ($i." archivos reorganizados de ".FILES_TURNOS_LOCAL." a ".FILES_TURNOS_SSH);
	}
	private function processSwiss(){
		$files = glob(FILES_SWISS_LOCAL."*");
		$i=0;
		$sftp=connectSFTP();
		foreach ($files as $file) {
			if (!is_file($file)) {continue;}
			$targetRemote=(FILES_SWISS_SSH.basename($file));
			$binData=file_get_contents($file);
			unlink($file);
			file_put_contents("ssh2.sftp://".intval($sftp).$targetRemote,$binData);
			$i+=1;
		}
		return ($i." archivos reorganizados de ".FILES_SIWSS_LOCAL." a ".FILES_SWISS_SSH);
	}
}
