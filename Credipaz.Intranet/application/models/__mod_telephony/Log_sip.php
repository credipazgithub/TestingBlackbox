<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class Log_sip extends My_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function brow($values){
        try {
            if($values["where"]!=""){$values["where"].=" AND ";}
            $values["where"].=" action='Audit' ";
            $values["order"]="created DESC";
            $this->view="vw_log_sip";
            $values["records"]=$this->get($values);
            $values["buttons"]=array(
                "new"=>false,
                "edit"=>false,
                "delete"=>false,
                "offline"=>false,
            );
            $values["columns"]=array(
                //array("field"=>"id","format"=>"number"),
                array("forcedlabel"=>"","field"=>"created","format"=>"datetime"),
                array("forcedlabel"=>"","field"=>"id_llamada","format"=>"status"),
                array("forcedlabel"=>"","field"=>"direccion","format"=>"auditoria_io"),
                array("forcedlabel"=>"","field"=>"username_resolver","format"=>"status"),
                array("forcedlabel"=>"","field"=>"sip_username","format"=>"status"),
                array("forcedlabel"=>"","field"=>"telefono","format"=>"code"),
                array("forcedlabel"=>"","field"=>"duration","format"=>"number"),
                array("forcedlabel"=>"","field"=>"grabacion","format"=>"auditoria_telefonica"),
            );
            $values["filters"]=array(
                array("name"=>"browser_search", "operator"=>"like","fields"=>array("telefono","sip_username","id_llamada")),
                array("type"=>"date","name"=>"browser_date_from", "operator"=>">=","fields"=>array("created")),
                array("type"=>"date","name"=>"browser_date_to", "operator"=>"<=","fields"=>array("created")),
                array("name"=>"browser_direction", "operator"=>"=","fields"=>array("direccion")),
                array("name"=>"browser_id_user", "operator"=>"=","fields"=>array("id_user")),
            );
            $values["controls"]=array(
				"<span class='badge badge-primary'>".lang('p_username')."</span>".comboUsers($this,array("where"=>WHERE_USERS_COMERCIAL,"order"=>"username ASC","pagesize"=>-1)),
				"<span class='badge badge-primary'>".lang('p_direccion')."</span>".comboDireccion(array("name"=>"browser_direction","class"=>"form-control","empty"=>1)),
				"<span class='badge badge-primary'>".lang('p_date_from')."</span> <input id='browser_date_from' name='browser_date_from' type='date' class='form-control'/>",
				"<span class='badge badge-primary'>".lang('p_date_to')."</span> <input id='browser_date_to' name='browser_date_to' type='date' class='form-control'/>",
            );
            return parent::brow($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function edit($values){
        try {
            $values["where"]=("id=".$values["id"]);
            $values["records"]=$this->get($values);
            return parent::edit($values);
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function import($values){
        try {
	    ini_set('max_execution_time', 0);
            $zip = new ZipArchive;
            $USERS_SIP=$this->createModel(MOD_TELEPHONY,"Users_sip","Users_sip");
            $IMPORTED_LOGS=$this->createModel(MOD_TELEPHONY,"Imported_logs","Imported_logs");
            $files=listFilesSSH(FILES_TELEPHONY_TICKETS,"zip");
            $i=0;
            foreach($files as $fileSSH) {
                $log=$IMPORTED_LOGS->get(array("where"=>"description='".$fileSSH."'"));
                if ($log["totalrecords"]==0) {
                    $fileLocalZip=str_replace(FILES_TELEPHONY_TICKETS,FILES_INTERCAMBIO,$fileSSH);
                    $fileLocalTxt=str_replace('.zip','.txt',$fileLocalZip);
                    $stream=getFileBinSSH($fileSSH);
                    file_put_contents($fileLocalZip,$stream);
                    $zip->open($fileLocalZip);
                    $zip->extractTo(FILES_INTERCAMBIO);
                    $zip->close();
                    $fields = array(
                        'code' => opensslRandom(16),
                        'description' => $fileSSH,
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                    );
                    $IMPORTED_LOGS->save(array("id"=>0),$fields);
                    $lines=file($fileLocalTxt);
                    foreach($lines as $line) {
                        $row=explode("|",$line);
                        $rec=$this->get(array("where"=>"id_llamada='".$row[2]."'"));
                        if ($rec["totalrecords"]==0) {
                            if($row[11]=="ANSWER" and trim((string)$row[22])!="") {
                                $user=$USERS_SIP->get(array("where"=>"sip_username='".$row[9]."'"));
                                $username=null;
                                if(isset($user["data"][0]["username"])){$username=$user["data"][0]["username"];}
                                $params=array(
                                    "code"=>null,
                                    "description"=>null,
                                    "created"=>$row[14],
                                    "verified"=>$this->now,
                                    "fum"=>$this->now,
                                    "status_raw"=>$line,
                                    "id_llamada"=>$row[2],
                                    "grabacion"=>$row[22],
                                    "direccion"=>$row[20],
                                    "telefono"=>$row[18],
                                    "username"=>$username,
                                    "sip_device"=>$row[3],
                                    "sip_username"=>$row[9],
                                    "action"=>"Audit",
                                    "action_tag"=>"",
                                    "action_detail"=>"teléfono/off system",
                                    "duration"=>$row[13],
                                    "rearranged"=>0
                                );
                                $this->save(array("id"=>0),$params);
                            }
                        }
                    }
					unlink($fileLocalZip);
					unlink($fileLocalTxt);
                }
                $i+=1;
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"",
                "files"=>$files,
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }
    public function importPost($values){
        try {
	    ini_set('max_execution_time', 0);
	    ini_set('memory_limit', '1024M');
            $this->view="log_sip";
            $files=$this->get(array("pagesize"=>1000,"page"=>1,"where"=>" rearranged=0 AND isnull(grabacion,'')!=''"));
            $sftp=connectSFTP();
            foreach($files["data"] as $file){
                $segments=explode("-",$file["grabacion"]);
                $strDate=explode(".",$segments[2]);
                $data=explode("-",(string)date("Y-m-d", strtotime($strDate[0])));
				clearstatcache();
                $dir=('ssh2.sftp://'.$sftp.FILES_TELEPHONY_MP3.$data[0]);
                $fileExists=is_dir($dir);
                if (!$fileExists) {ssh2_sftp_mkdir($sftp,FILES_TELEPHONY_MP3.$data[0]);}
                $dir=('ssh2.sftp://'.$sftp.FILES_TELEPHONY_MP3.$data[0].'/'.$data[1]);
                $fileExists=file_exists($dir);
                if (!$fileExists) {ssh2_sftp_mkdir($sftp,FILES_TELEPHONY_MP3.$data[0].'/'.$data[1]);}
                $dir=('ssh2.sftp://'.$sftp.FILES_TELEPHONY_MP3.$data[0].'/'.$data[1].'/'.$data[2]);
                $fileExists=file_exists($dir);
                if (!$fileExists) {ssh2_sftp_mkdir($sftp,FILES_TELEPHONY_MP3.$data[0].'/'.$data[1].'/'.$data[2]);}

                $path_from=(FILES_TELEPHONY_MP3.$file["grabacion"]);
                $path_to=(FILES_TELEPHONY_MP3.$data[0].'/'.$data[1].'/'.$data[2].'/'.$file["grabacion"]);

                ssh2_sftp_rename($sftp, $path_from, $path_to);
                $params=array("fum"=>$this->now,"rearranged"=>"1");
                $this->save(array("id"=>$file["id"]),$params);
            }
            return array(
                "code"=>"2000",
                "status"=>"OK",
                "message"=>"Se ha ejecutado el proceso satisfactoriamente",
                "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
                );
        }
        catch(Exception $e){
            return logError($e,__METHOD__ );
        }
    }

}
