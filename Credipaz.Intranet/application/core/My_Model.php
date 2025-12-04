<?php
defined('BASEPATH') or exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/

class My_Model extends CI_Model
{
    public $REFERRER_NEOCORE = "";
    public $TOKEN_NEOCORE = "";
    public $ready = false;
    public $status = null;
    public $language = DEFAULT_LANGUAGE;
    public $module = "";
    public $model = "";
    public $table = "";
    public $view = "";
    public $now = null;
    public $psession = null;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set(DEFAULT_TIMEZONE);
        $this->now = date(FORMAT_DATE);
    }
    /*Initialize*/
    public function init($model, $table, $lang = null)
    {
        try {
            //$this->execAdHoc("EXEC dbCentral.dbo.NS_ServiciosExternos_Update @code='INTRANET', @estado='ONLINE'");

            $this->REFERRER_NEOCORE = "BLACKBOX";
            $this->TOKEN_NEOCORE = bin2hex(getEncryptionKey());
            if ($lang != null) {
                $this->language = $lang;
            }
            $this->module = explode("/", $model)[0];
            $this->model = $model;
            $this->table = $table;
            $this->view = $table;
            $this->ready = true;
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function createModel($module, $model, $table)
    {
        try {
            $this->load->model($module . "/" . $model, $model, false);
            $this->{$model}->status = $this->{$model}->init($module . "/" . $model, $table, $this->language);
            if ($this->{$model}->status["status"] != "OK") {
                throw new Exception($this->{$model}->status["message"], (int) $this->{$model}->status["code"]);
            }
            return $this->{$model};
        } catch (Exception $e) {
            return null;
        }
    }
    public function prepareModule()
    {
        /*Módulos sin prefijo*/
        switch (strtoupper($this->module)) {
            case strtoupper(MOD_DBCENTRAL):
            case strtoupper(MOD_DBCLUB):
                $this->module = "";
                break;
            default:
                if (substr($this->module, -1) == "_") {
                    $this->module = rtrim($this->module, "_");
                }
                if ($this->module != "") {
                    $this->module = ($this->module . "_");
                }
                break;
        }
    }
    public function getServer()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'];
        return ($protocol . $host . ":" . $port);
    }

    /*Public I/O methods*/
    public function get($values)
    {
        try {
            if (isBase64Encoded($values["where"])) {
                $values["where"] = base64_decode($values["where"]);
            }
            if (isset($values["view"])) {
                $this->view = $values["view"];
            }
            $data = $this->getRecords($values);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "Records",
                "table" => $this->table,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $data["records"],
                "totalrecords" => $data["totalrecords"],
                "totalpages" => $data["totalpages"],
                "page" => $data["page"]
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function getByWhere($params)
    {
        try {
            $ACTIVE = $this->createModel($params["module"], $params["model"], $params["model"]);
            $record = $ACTIVE->get(array("fields" => $params["field"], "where" => $params["where"]));
            $data = null;
            if (isset($record["data"][0][$params["field"]])) {
                $data = $record["data"][0][$params["field"]];
            }
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "Record retrieved",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $data,
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function save($values, $fields = null)
    {
        try {
            if (!isset($values["id"]) or $values["id"] == "") {$values["id"] = 0;}
            $id = (int)$values["id"];
            $message = "";
            if ($id == 0) {
                if ($fields == null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'created' => $this->now,
                        'verified' => $this->now,
                        'offline' => null,
                        'fum' => $this->now,
                    );
                }
            } else {
                if ($fields == null) {
                    $fields = array(
                        'code' => $values["code"],
                        'description' => $values["description"],
                        'fum' => $this->now,
                    );
                }
            }
            $id = $this->setRecord($fields, $id);
            if ((int) $id == 0) {
                //logGeneral($this, $values, __METHOD__, "ERROR ZERO ID");
                throw new Exception(lang("error_5004"), 5004);
            }

            $this->saveAttachments($values, $id, null);
            $this->saveMessages($values, $id, null);

            $data = array("id" => $id);
            //logGeneral($this, $values, __METHOD__);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => $message,
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $data,
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function insertBySelect($values)
    {
        try {
            $this->prepareModule();
            $sql = "INSERT INTO " . ($this->module . $this->table) . " (" . $values["fieldList"] . ") (" . $values["selectToInsert"] . ")";
            $this->dbLayerExecuteWS("nothing", $sql, "");
            //logGeneral($this, $values, __METHOD__);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    public function offline($values)
    {
        try {
            $data = array("id" => $this->setRecord(array('offline' => $this->now, 'fum' => $this->now), $values["id"]));
            //logGeneral($this, $values, __METHOD__);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => lang('msg_offline'),
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $data
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function online($values)
    {
        try {
            $data = array("id" => $this->setRecord(array('offline' => null, 'fum' => $this->now), $values["id"]));
            //logGeneral($this, $values, __METHOD__);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => lang('msg_online'),
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => $data
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function delete($values)
    {
        try {
            $del = $this->delRecord(("id=" . $values["id"]));
            if ($del !== true) {
                throw $del;
            }
            ;
            try {
                $FILES_ATTACHED = $this->createModel(MOD_BACKEND, "Files_attached", "Files_attached");
                $file = $FILES_ATTACHED->get(array("where" => "id_rel=" . $values["id"] . " AND table_rel='" . $this->table . "'"));
                foreach ($file["data"] as $item) {
                    unlink($item["src"]);
                    $FILES_ATTACHED->delete(array("id" => $item["id"]));
                }
            } catch (Exception $e) {
            }

            //logGeneral($this, $values, __METHOD__);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => lang('msg_delete'),
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function deleteByWhere($where)
    {
        try {
            //log_message("error", "WHERE ".json_encode($where,JSON_PRETTY_PRINT));
            $filter = "";
            if (is_array($where)) {
                foreach ($where as $key => $value) {
                    if ($filter != "") {
                        $filter .= " AND ";
                    }
                    $filter = ($key . "='" . $where[$key] . "'");
                }
            } else {
                $filter = $where;
            }
            $del = $this->delRecord($filter);
            if ($del !== true) {
                throw $del;
            }
            ;
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function updateByWhere($fields, $where)
    {
        try {
            $this->prepareModule();
            $resolvedTableView = ($this->module . $this->table);
            $fieldPart = "";
            foreach (array_keys($fields) as $key) {
                if ($fieldPart != "") {
                    $fieldPart .= ",";
                }
                $fieldPart .= $key . "='" . $fields[$key] . "'";
            }
            $sql = ("UPDATE " . $resolvedTableView . " SET " . $fieldPart . " WHERE " . $where);
            $this->dbLayerExecuteWS("nothing", $sql, "");
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function info($values)
    {
        try {
            $values["fields"] = "max(id) as max_id, count(*) as total";
            $info = $this->get($values);
            if ($info["status"] != "OK") {
                throw new Exception($info["message"], (int) $info["code"]);
            }
            ;
            if (isset($info["data"][0])) {
                $data = array(
                    "total" => (int) $info["data"][0]["total"],
                    "max_id" => (int) $info["data"][0]["max_id"],
                );
                return array(
                    "code" => "2000",
                    "status" => "OK",
                    "message" => lang('msg_info'),
                    "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                    "data" => $data,
                );
            } else {
                throw new Exception(lang("error_5001"), 5001);
            }
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function process($values)
    {
        try {
            //logGeneral($this, $values, __METHOD__);
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => lang('msg_process'),
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function getRecordsAdHoc($sql)
    {
        try {
            $records = $this->dbLayerExecuteWS("records", $sql, "");
            if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
                $records = toUtf8($records);
            }
            return $records;
        } catch (Exception $e) {
            return null;
        }
    }
    public function execAdHoc($sql)
    {
        try {
            return $this->dbLayerExecuteWS("nothing", $sql, "");
        } catch (Exception $e) {
            return null;
        }
    }

    /*Public GPI methods*/
    public function form($values)
    {
        try {
            if (!isset($values["interface"])) {
                $values["interface"] = ("form");
            }
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_" . strtolower($values["model"])));
            $html = $this->load->view($values["interface"], $data, true);
            
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
    public function brow($values)
    {
        try {
            $values["mobile"] = false;
            if (!isset($values["buttons"])) {
                $values["buttons"] = array(
                    "new" => true,
                    "edit" => true,
                    "delete" => true,
                    "offline" => false,
                );
            }
            if (!isset($values["interface"])) {
                $values["interface"] = ("brow");
            }
            $data["parameters"] = $values;
            if (!isset($values["title"])) {
                $data["title"] = ucfirst(lang("m_" . strtolower($values["model"])));
            } else {
                $data["title"] = ucfirst($values["title"]);
            }
            $html = $this->load->view($values["interface"], $data, true);
            
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
    public function excel($values)
    {
        try {
            if (!isset($values["delimiter"])) {
                $values["delimiter"] = (",");
            }
            if (!isset($values["interface"])) {
                $values["interface"] = ("excel");
            }
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_" . strtolower($values["model"])));
            $html = $this->load->view($values["interface"], $data, true);
            
            $ret = array("message" => $html, "mime" => "text/csv", "mode" => $values["mode"], "indisk" => false);
            return $ret;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function pdf($values)
    {
        try {
            if (!isset($values["interface"])) {
                $values["interface"] = ("pdf");
            }
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_" . strtolower($values["model"])));
            $html = $this->load->view($values["interface"], $data, true);
            $this->load->library("m_pdf");
            $this->m_pdf->pdf->WriteHTML($html, 2);
            ob_end_clean();
            $html = $this->m_pdf->pdf->Output("legalizacion.pdf", "S");
            
            $ret = array("message" => $html, "mime" => "application/pdf", "mode" => $values["mode"], "indisk" => false);
            return $ret;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function edit($values)
    {
        try {
            $values["mobile"] = false;
            if (!isset($values["interface"])) {
                $values["interface"] = "abm";
            }
            if (!isset($values["attached_files"])) {
                $data["attached_files"] = $this->getAttachments($values, null);
            } else {
                $data["attached_files"] = $values["attached_files"];
            }
            if (!isset($values["attached_messages"])) {
                $data["attached_messages"] = $this->getMessages($values, null);
            } else {
                $data["attached_messages"] = $values["attached_messages"];
            }
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_" . strtolower($values["model"])));
            $html = $this->load->view($values["interface"], $data, true);
            
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

    /*Public 1 a N relation methods*/
    public function saveRelations($values)
    {
        try {
            $ACTIVE = $this->createModel($values["module"], $values["model"], $values["model"]);
            $ACTIVE->deleteByWhere(array($values["key_field"] => $values["key_value"]));
            foreach ($values["rel_values"] as $item) {
                if ($item != null and $item != "") {
                    $ACTIVE->save(array("id" => 0), array($values["key_field"] => $values["key_value"], $values["rel_field"] => $item));
                }
            }
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function deleteRelations($values)
    {
        try {
            $ACTIVE = $this->createModel($values["module"], $values["model"], $values["model"]);
            $ACTIVE->deleteByWhere(array($values["key_field"] => $values["key_value"]));
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    /*Public FILE attachment methods*/
    public function saveAttachments($values, $id, $opts = null)
    {
        try {
            if (!isset($opts["module"])) {
                $opts["module"] = MOD_BACKEND;
            }
            if (!isset($opts["model"])) {
                $opts["model"] = "Files_attached";
            }
            if (!isset($opts["new"])) {
                $opts["new"] = "new-files";
            }
            if (!isset($opts["del"])) {
                $opts["del"] = "del-files";
            }
            if (!isset($opts["newLinks"])) {
                $opts["newLinks"] = "new-links";
            }
            if (!isset($opts["delLinks"])) {
                $opts["delLinks"] = "del-links";
            }
            if (!isset($opts["id"])) {
                $opts["id"] = "id";
            }
            if (!isset($opts["source"])) {
                $opts["source"] = "src";
            }
            if (!isset($opts["filename"])) {
                $opts["filename"] = "filename";
            }
            if (!isset($opts["priority"])) {
                $opts["priority"] = "priority";
            }
            $this->prepareModule();
            $resolvedTableView = ($this->module . $this->table);

            $ACTIVE = $this->createModel($opts["module"], $opts["model"], $opts["model"]);
            //Process new attached files
            $path = (FILES_ATTACHED . $this->table);
            //if($opts["module"]==MOD_WEB_POSTS){$path=(FILES_WEB_POSTS.$this->table);}
            if ($opts["module"] == MOD_FOLDERS) {
                $path = (FILES_CRM . $this->table);
            }
            if ($opts["module"] == MOD_PROVIDERS) {
                $path = (FILES_CRM . $this->table);
            }
            if (isset($values[$opts["new"]]) and is_array($values[$opts["new"]])) {
                foreach ($values[$opts["new"]] as $item) {
                    if ($item[$opts["source"]] != null and $item[$opts["source"]] != "") {
                        $code = opensslRandom(16);
                        $opts["filename"] = removeAccents($opts["filename"]);
                        $fullpath = ($path . "/" . $code . "_" . $item[$opts["filename"]]);
                        $base64 = $item[$opts["source"]];
                        //saveBase64ToFile(array("data"=>$base64,"path"=>$path,"fullPath"=>$fullpath));

                        /*SSH Put!*/
                        $mime = explode(',', $base64);
                        $encoded = $mime[1];
                        $encoded = str_replace(' ', '+', $encoded);
                        $binData = base64_decode($encoded);

                        setFileBinSSH($fullpath, $binData);

                        if (!isset($opts["inner"])) {
                            $fields = array(
                                "code" => $code,
                                "description" => $item[$opts["filename"]],
                                "created" => $this->now,
                                "verified" => $this->now,
                                "fum" => $this->now,
                                "src" => $fullpath,
                                "filename" => basename($fullpath),
                                "id_rel" => $id,
                                "table_rel" => $resolvedTableView
                            );
                        } else {
                            $fields = $opts["inner"];
                            foreach (array_keys($fields) as $key) {
                                switch ($key) {
                                    case "code":
                                        $fields[$key] = $code;
                                        break;
                                    case "description":
                                        $fields[$key] = $item[$opts["filename"]];
                                        break;
                                    case "data":
                                        $fields[$key] = $fullpath;
                                        break;
                                    case "mime":
                                        $fields[$key] = getMimeType($fullpath);
                                        break;
                                    case "basename":
                                        $fields[$key] = basename($fullpath);
                                        break;
                                    case "priority":
                                        $fields[$key] = $item[$opts["priority"]];
                                        break;
                                    default:
                                        if ($fields[$key] == "=") {
                                            $fields[$key] = $item[$key];
                                        }
                                        break;
                                }
                            }
                        }
                        $ACTIVE->save(array("id" => 0), $fields);
                    }
                }
            }
            if (isset($values[$opts["newLinks"]]) and is_array($values[$opts["newLinks"]])) {
                foreach ($values[$opts["newLinks"]] as $item) {
                    if ($item[$opts["source"]] != null and $item[$opts["source"]] != "") {
                        $code = opensslRandom(16);
                        $fullpath = $item["link"];

                        if (!isset($opts["inner"])) {
                            $fields = array(
                                "code" => $code,
                                "description" => $item["src"],
                                "created" => $this->now,
                                "verified" => $this->now,
                                "fum" => $this->now,
                                "src" => $fullpath,
                                "filename" => $fullpath,
                                "id_rel" => $id,
                                "table_rel" => $resolvedTableView
                            );
                        } else {
                            $fields = $opts["inner"];
                            foreach (array_keys($fields) as $key) {
                                switch ($key) {
                                    case "code":
                                        $fields[$key] = $code;
                                        break;
                                    case "description":
                                        $fields[$key] = $item["src"];
                                        break;
                                    case "data":
                                        $fields[$key] = $fullpath;
                                        break;
                                    case "mime":
                                        $fields[$key] = getMimeType($fullpath);
                                        break;
                                    case "basename":
                                        $fields[$key] = basename($fullpath);
                                        break;
                                    case "priority":
                                        $fields[$key] = $item[$opts["priority"]];
                                        break;
                                    default:
                                        if ($fields[$key] == "=") {
                                            $fields[$key] = $item[$key];
                                        }
                                        break;
                                }
                            }
                        }
                        $ACTIVE->save(array("id" => 0), $fields);
                    }
                }
            }
            //Process del attached files
            if (isset($values[$opts["del"]]) and is_array($values[$opts["del"]])) {
                foreach ($values[$opts["del"]] as $item) {
                    $file = $ACTIVE->get(array("where" => "id=" . $item[$opts["id"]]));
                    foreach ($file["data"] as $item) {
                        unlink($item[$opts["source"]]);
                        $ACTIVE->delete(array("id" => $item[$opts["id"]]));
                    }
                }
            }
            if (isset($values[$opts["delLinks"]]) and is_array($values[$opts["delLinks"]])) {
                foreach ($values[$opts["delLinks"]] as $item) {
                    $ACTIVE->delete(array("id" => $item[$opts["id"]]));
                }
            }
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function getAttachments($values, $opts = null)
    {
        try {
            if (!isset($opts["module"])) {
                $opts["module"] = MOD_BACKEND;
            }
            if (!isset($opts["model"])) {
                $opts["model"] = "Files_attached";
            }
            if (!isset($opts["where"])) {
                $resolvedTableView = ($values["module"] . "_" . $values["table"]);
                $opts["where"] = ("table_rel='" . $resolvedTableView . "' AND id_rel=" . $values["id"]);
            }
            if (!isset($opts["view"])) {
                $opts["view"] = $opts["model"];
            }
            if (!isset($opts["order"])) {
                $opts["order"] = "description ASC";
            }
            $ACTIVE = $this->createModel($opts["module"], $opts["model"], $opts["model"]);
            $ACTIVE->view = $opts["view"];
            return $ACTIVE->get(array("fields" => $opts["fields"], "where" => $opts["where"], "order" => $opts["order"]));
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    /*Public MESSAGES attachment methods*/
    public function saveMessages($values, $id, $opts = null)
    {
        try {
            if (!isset($opts["module"])) {
                $opts["module"] = MOD_BACKEND;
            }
            if (!isset($opts["model"])) {
                $opts["model"] = "Messages_attached";
            }
            if (!isset($opts["new"])) {
                $opts["new"] = "new-messages";
            }
            if (!isset($opts["del"])) {
                $opts["del"] = "del-messages";
            }
            if (!isset($opts["id"])) {
                $opts["id"] = "id";
            }
            if (!isset($opts["source"])) {
                $opts["source"] = "message";
            }
            $this->prepareModule();
            $resolvedTableView = ($this->module . $this->table);
            $ACTIVE = $this->createModel($opts["module"], $opts["model"], $opts["model"]);
            //Process new attached messages
            if (isset($values[$opts["new"]]) and is_array($values[$opts["new"]])) {
                foreach ($values[$opts["new"]] as $item) {
                    if ($item[$opts["source"]] != null and $item[$opts["source"]] != "") {
                        $code = opensslRandom(16);
                        $fields = array(
                            "code" => $code,
                            "description" => $code,
                            "created" => $this->now,
                            "verified" => $this->now,
                            "fum" => $this->now,
                            "message" => $item[$opts["source"]],
                            "id_user" => $values["id_user_active"],
                            "id_rel" => $id,
                            "table_rel" => $resolvedTableView
                        );
                        $ret = $ACTIVE->save(array("id" => 0), $fields);
                        if ($ret["status"] != "OK") {
                            throw new Exception($ret["message"], (int) $ret["code"]);
                        }
                        if (isset($ret["data"]["id"])) {
                            $values["id"] = $ret["data"]["id"];
                            logMessagesAttached($this, $values, lang('msg_message_viewed'));
                        }
                    }
                }
            }
            //Process del attached messages
            if (isset($values[$opts["del"]]) and is_array($values[$opts["del"]])) {
                foreach ($values[$opts["del"]] as $item) {
                    $file = $ACTIVE->get(array("where" => "id=" . $item[$opts["id"]]));
                    foreach ($file["data"] as $item) {
                        $ACTIVE->delete(array("id" => $item[$opts["id"]]));
                    }
                }
            }
            return array(
                "code" => "2000",
                "status" => "OK",
                "message" => "",
                "function" => ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ : ENVIRONMENT),
                "data" => null,
            );
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function getMessages($values, $opts = null)
    {
        try {
            if (!isset($opts["module"])) {
                $opts["module"] = MOD_BACKEND;
            }
            if (!isset($opts["model"])) {
                $opts["model"] = "Messages_attached";
            }
            if (!isset($opts["where"])) {
                $this->prepareModule();
                $resolvedTableView = ($this->module . $this->table);
                $opts["where"] = ("table_rel='" . $resolvedTableView . "' AND id_rel=" . $values["id"]);
            }
            if (!isset($opts["view"])) {
                $opts["view"] = $opts["model"];
            }
            $ACTIVE = $this->createModel($opts["module"], $opts["model"], $opts["model"]);
            $ACTIVE->view = $opts["view"];
            return $ACTIVE->get(array("where" => $opts["where"], "ORDER BY created DESC"));
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }

    /*Private I/O methods*/
    public function dbLayerExecuteWS($expected, $command, $token, $params = null)
    {
        try {
            $NETCORECPFINANCIALS = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $ret = $NETCORECPFINANCIALS->BridgeDirectCommand("dbIntranet", $command, $expected);
            if ($ret["status"] == "OK") {
                $ret = json_decode($ret["message"], true);
                $ret = $ret["records"];
            } else {
                throw new Exception(lang('error_100') . ": " . $ret["message"], 100);
            }
            return $ret;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    public function emLayerExecuteWS($expected, $command, $token, $subject, $body, $from, $alias_from, $replyTo, $to, $cc, $bcc, $attachments, $names, $priority)
    {
        try {
            $NETCORECPFINANCIALS = $this->createModel(MOD_EXTERNAL, "NetCoreCPFinancial", "NetCoreCPFinancial");
            $ret = $NETCORECPFINANCIALS->BridgeDirectEmail($to, $from, $body, $subject);
            return $ret;
        } catch (Exception $e) {
            return logError($e, __METHOD__);
        }
    }
    
    public function getRecords($values)
    {
        try {
            $this->prepareModule();
            $resolvedTableView = ($this->module . $this->view);
            if (!isset($values["onlytotals"])) {
                $values["onlytotals"] = false;
            }
            if (!isset($values["page"])) {
                $values["page"] = 1;
            }
            if (!isset($values["pagesize"])) {
                $values["pagesize"] = 25;
                $preferences = getPreference($this, $values, 1);
                if ($preferences != null) {
                    if ((int) $preferences["totalrecords"] != 0) {
                        $values["pagesize"] = (int) $preferences["data"][0]["value"];
                    }
                }
            }
            if (!isset($values["fields"])) {
                $values["fields"] = "*";
            }
            //Total records
            $sql = ("SELECT count(*) as total FROM " . $resolvedTableView);
            if (isset($values["where"]) and $values["where"] != "") {$sql .= " WHERE " . $values["where"];}
            $records = $this->dbLayerExecuteWS("records", $sql, "");
            $totalrecords = $records[0]["total"];
            $totalpages = ceil($totalrecords / $values["pagesize"]);
            if ($values["onlytotals"]) {
                $return = array(
                    "records" => null,
                    "totalrecords" => $totalrecords,
                    "totalpages" => $totalpages,
                    "page" => $values["page"]
                );
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    return $return;
                } else {
                    return toUtf8($return);
                }
            }
            //Filtered get
            $top = "";
            if ((int) $values["pagesize"] > 0 and (int) $values["page"] == 1) {
                $top = ("TOP " . $values["pagesize"]);
            }
            $sql = ("SELECT " . $top . " " . $values["fields"] . " FROM " . $resolvedTableView);
            if (isset($values["where"]) and $values["where"] != "") {
                $sql .= " WHERE " . $values["where"];
            }
            if (isset($values["order"]) and $values["order"] != "") {
                $sql .= " ORDER BY " . $values["order"];
            }

            if ((int) $values["pagesize"] > 0 and (int) $values["page"] > 1) {
                $from = (int) ((($values["page"] - 1) * $values["pagesize"]));
                $size = (int) $values["pagesize"];
                $to = ($from + $size);
                if (isset($values["where"]) and $values["where"] != "") {
                    $resolvedTableView .= " WHERE " . $values["where"];
                }
                $innerOrder = "ORDER BY id DESC";
                if (isset($values["order"]) and $values["order"] != "") {
                    $innerOrder = " ORDER BY " . $values["order"];
                }
                $sql = "SELECT * FROM (SELECT ROW_NUMBER() OVER(" . $innerOrder . ") AS CI_rownum, " . $values["fields"] . " FROM " . $resolvedTableView;
                $sql .= ") as CI_subquery WHERE CI_rownum BETWEEN " . ($from + 1) . " AND " . $to;
                if (isset($values["order"]) and $values["order"] != "") {
                    $sql .= " ORDER BY " . $values["order"];
                }
                $sql = str_replace(", kms ASC", "", $sql);
            }
            $records = $this->dbLayerExecuteWS("records", "SET language 'Español';".$sql, "");
            $return = array(
                "records" => $records,
                "totalrecords" => $totalrecords,
                "totalpages" => $totalpages,
                "page" => $values["page"]
            );
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                return $return;
            } else {
                return toUtf8($return);
            }
        } catch (Exception $e) {
            return $e;
        }
    }
    public function setRecord($fields, $id)
    {
        try {
            $this->prepareModule();
            $resolvedTableView = ($this->module . $this->table);
            if ($id == 0) {
                $fieldPart = "(";
                $valuesPart = "(";
                foreach (array_keys($fields) as $key) {
                    if ($fieldPart != "(") {
                        $fieldPart .= ",";
                    }
                    if ($valuesPart != "(") {
                        $valuesPart .= ",";
                    }
                    $fieldPart .= "[" . $key . "]";
                    if ($fields[$key] != "" or is_numeric($fields[$key])) {
                        $valuesPart .= ("'" . $fields[$key] . "'");
                    } else {
                        $valuesPart .= "null";
                    }
                }
                if ($fieldPart != "(") {
                    $fieldPart .= ")";
                }
                if ($valuesPart != "(") {
                    $valuesPart .= ")";
                }
                $sql = ("INSERT INTO " . $resolvedTableView . " " . $fieldPart . " VALUES " . $valuesPart);
                $this->dbLayerExecuteWS("nothing", $sql, "");
                
                $sql = ("SELECT IDENT_CURRENT('". $resolvedTableView."') as id");
                $records = $this->dbLayerExecuteWS("records", $sql, "");
                $id = $records[0]["id"];
            } else {
                $fieldPart = "";
                foreach (array_keys($fields) as $key) {
                    if ($fieldPart != "") {
                        $fieldPart .= ",";
                    }
                    $valuesPart = "";
                    if ($fields[$key] != "" or is_numeric($fields[$key])) {
                        $valuesPart = ("'" . $fields[$key] . "'");
                    } else {
                        $valuesPart = "null";
                    }
                    $fieldPart .= ("[" . $key . "]" . "=" . $valuesPart);
                }
                $sql = ("UPDATE " . $resolvedTableView . " SET " . $fieldPart . " WHERE id='" . $id . "'");
                $this->dbLayerExecuteWS("nothing", $sql, "");
            }
            if ($this->table != "sinisters" and $id == 0) {
                $id = -1;
            }
            return $id;
        } catch (Exception $e) {
            return $e;
        }
    }
    public function delRecord($where)
    {
        try {
            $this->prepareModule();
            $resolvedTableView = ($this->module . $this->table);
            $sql = ("DELETE " . $resolvedTableView . " WHERE " . $where);
            $this->dbLayerExecuteWS("nothing", $sql, "");
            return true;
        } catch (Exception $e) {
            return $e;
        }
    }

    private function cUrlRestful($url, $headers, $post, $fields = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, $post);
        if (is_array($headers)) {curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);}
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $jsonResponse = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        $response = $jsonResponse;
        return $response;
    }
    private function buildEmailEntry($data, $sep)
    {
        if (is_array($data)) {
            return $data;
        }
        $arr = array();
        $v1 = explode($sep, $data);
        foreach ($v1 as $v) {
            if ($v != "") {
                array_push($arr, array("email" => trim($v), "showName" => trim($v)));
            }
        }
        return $arr;
    }
}
