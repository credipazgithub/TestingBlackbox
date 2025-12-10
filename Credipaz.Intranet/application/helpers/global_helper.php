<?php
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
require APPPATH.'/libraries/CreatorJwt.php';
//CRYPTO
function sign($raw){
    try {
		if(!file_exists(DIR_PRIVATE_KEY) and !file_exists(DIR_PUBLIC_KEY) ){
            $config = array(
                "digest_alg" => "sha512",
                "private_key_bits" => 4096,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
                "config"=>DIR_CONFIG_OPENSSL);
            $res = openssl_pkey_new($config);
            openssl_pkey_export($res, $private_key_pem,null,$config);
            $public_key_pem = openssl_pkey_get_details($res);
            $public_key_pem = $public_key_pem["key"];
            file_put_contents(DIR_PRIVATE_KEY, $private_key_pem);
            file_put_contents(DIR_PUBLIC_KEY, $public_key_pem);
        } else {
            $private_key_pem = file_get_contents(DIR_PRIVATE_KEY);
        }
        openssl_sign($raw, $sign, $private_key_pem, OPENSSL_ALGO_SHA256);
        $data=array("sign"=>base64_encode($sign),"certificate"=>base64_encode(json_encode($x509)));
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"Signed",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>$data,
            );
        }
        catch(Exception $e) {
            return logError($e,__METHOD__ );
        }
}
function verify($raw,$sign){
    try {
        if ($sign!="") {
            $info=sign($raw);
            if($info["status"]!="OK"){throw new Exception($info["message"],(int)$info["code"]);}
            if ($info["data"]["sign"]!=$sign){throw new Exception(lang('msg_bad_sign'));}
        }
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"Signed",
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
            "data"=>null,
            );
    }
    catch(Exception $e) {
        return logError($e,__METHOD__ );
    }
}
//ERROR BEHAVIOUR
function getHeader($id){
    $headers = getallheaders();
    foreach ($headers as $key => $value)
    {
        if ($id==$key) {return $value;}
    }
    return "";
}
function getServer(){
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host=$_SERVER['SERVER_NAME'];
    $port=$_SERVER['SERVER_PORT'];
    return ($protocol.$host.":".$port);
}
function logError($e,$function){
    return array(
        "code"=>$e->getCode(),
        "status"=>"ERROR",
        "message"=>$e->getMessage(),
        "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? $function :ENVIRONMENT),
        );
}
function logGeneralCustom($obj,$values,$method,$custom_trace){
    try {
        if(!isset($values["id"])){$values["id"]=null;}
        if(!isset($values["type_rel"])){$values["type_rel"]=null;}
        if(!isset($values["identify_rel"])){$values["identify_rel"]=null;}
        if(!isset($values["amount_rel"])){$values["amount_rel"]=null;}
        $LOG_GENERAL=$obj->createModel(MOD_BACKEND,"Log_general","Log_general");
        $obj->prepareModule();
        $resolvedTableView=($obj->module.$obj->table);
        $fields = array(
            'code' => opensslRandom(16),
            'description' => lang('msg_log_general'),
            'created' => $obj->now,
            'verified' => $obj->now,
            'offline' => null,
            'fum' => $obj->now,
            'id_user' => $values["id_user_active"],
            'action' => $method,
            'trace' => $custom_trace,
            'id_rel' => $values["id"],
            'table_rel' => $resolvedTableView,
            'type_rel' => $values["type_rel"],
            'identify_rel' => $values["identify_rel"],
            'amount_rel' => $values["amount_rel"],
        );
        return $LOG_GENERAL->save(array("id"=>0),$fields);
    } catch(Exception $e) {
        if ($e->getCode()!==9999){return logError($e,__METHOD__ );} else {return null;}
    }
}
function logGeneral($obj,$values,$method,$custom_trace=null){
    try {
        if(!isset($values["id_user_active"]) or $obj->table=="log_general"){throw new Exception(lang('error_9999'),9999);}
        if(!isset($values["id"])){$values["id"]=null;}
        if(!isset($values["username"])){$values["username"]=null;}
        if(!isset($values["type_rel"])){$values["type_rel"]=null;}
        if(!isset($values["identify_rel"])){$values["identify_rel"]=null;}
        if(!isset($values["amount_rel"])){$values["amount_rel"]=null;}
        $LOG_GENERAL=$obj->createModel(MOD_BACKEND,"Log_general","Log_general");
        $obj->prepareModule();
        $resolvedTableView=($obj->module.$obj->table);
        $trace=array(
                "line"=>__LINE__,
                "file"=>__FILE__,
                "dir"=>__DIR__,
                "function"=>__FUNCTION__,
                "class"=>__CLASS__,
                "trait"=>__TRAIT__,
                "method"=>__METHOD__,
                "namespace"=>__NAMESPACE__
        );
        if($values["username"]!=null) {$trace["username"]=$values["username"];}
        if($custom_trace!=null) {$trace["custom"]=json_encode($custom_trace);}
        $fields = array(
            'code' => opensslRandom(16),
            'description' => lang('msg_log_general'),
            'created' => $obj->now,
            'verified' => $obj->now,
            'offline' => null,
            'fum' => $obj->now,
            'id_user' => $values["id_user_active"],
            'action' => $method,
            'trace' => json_encode($trace),
            'id_rel' => $values["id"],
            'table_rel' => $resolvedTableView,
            'type_rel' => $values["type_rel"],
            'identify_rel' => $values["identify_rel"],
            'amount_rel' => $values["amount_rel"],
        );
        return $LOG_GENERAL->save(array("id"=>0),$fields);
    } catch(Exception $e) {
        if ($e->getCode()!==9999){return logError($e,__METHOD__ );} else {return null;}
    }
}
function logMessagesAttached($obj,$values,$method){
    try {
        if(!isset($values["id_user_active"])){throw new Exception(lang('error_9999'),9999);}
        if(!isset($values["id"])){$values["id"]=null;}
        $MESSAGES_ATTACHED_LOG=$obj->createModel(MOD_BACKEND,"Messages_attached_log","Messages_attached_log");
        $fields = array(
            'code' => opensslRandom(16),
            'description' => lang('msg_log_folder_items'),
            'created' => $obj->now,
            'verified' => $obj->now,
            'offline' => null,
            'fum' => $obj->now,
            'id_user' => $values["id_user_active"],
            'id_message_attached' => $values["id"],
            'processed' => $obj->now,
            'tag_processed' => $method,
        );
        return $MESSAGES_ATTACHED_LOG->save(array("id"=>0),$fields);
    } catch(Exception $e) {
        if ($e->getCode()!==9999){return logError($e,__METHOD__ );} else {return null;}
    }
}
//CRIPTOGRAPHY
function opensslRandom($len){
    $bytes = openssl_random_pseudo_bytes($len);
    return bin2hex($bytes);
}
function getEncryptionKey(){
    $myconfig=&get_config();
    return $myconfig['encryption_key'];
}
function getSecureRandomize($min, $max)
{
    $ver = (float)phpversion();
    if ($ver >= 7.0) {
        $ret=random_int($min,$max);
    } elseif ($ver >= 5.6) {
        $ret=((unpack("N", openssl_random_pseudo_bytes(4)) % ($max - $min)) + $min);
    } else {
        $ret=rand($min,$max);
    }
    return $ret;
}
function decodeTokenJWT($values) {
    try {
        $objOfJwt = new CreatorJwt();
        $jwtData = $objOfJwt->DecodeToken($values['token'],SERVER_KEY_JWT);
        return array(
            "code"=>"2000",
            "status"=>"OK",
            "message"=>"",
            "token"=>$jwtData,
            "function"=> ((ENVIRONMENT === 'development' or ENVIRONMENT === 'testing') ? __METHOD__ :ENVIRONMENT),
        );
    }
    catch (Exception $e){
        return logError($e,__METHOD__ );
    }
}
function encodeTokenJWT($tokenData){
    try {
        $objOfJwt=new CreatorJwt();
        return $objOfJwt->GenerateToken($tokenData,SERVER_KEY_JWT);
    }
    catch (Exception $e){
        return logError($e,__METHOD__ );
    }
}
function encodeTokenJWTSSH($tokenData){
    try {
        $objOfJwt=new CreatorJwt();
        return $objOfJwt->GenerateToken($tokenData,SERVER_KEY_JWT_SSH);
    }
    catch (Exception $e){
        return logError($e,__METHOD__ );
    }
}
//FORMATING
function TransformHtmlToPdf($obj,$html)
{
    $obj->load->library("m_pdf");
    $obj->m_pdf->pdf->useSubstitutions = false;
    $obj->m_pdf->pdf->simpleTables = true;
    $obj->m_pdf->pdf->WriteHTML($html, 2);
    return $obj->m_pdf->pdf->Output("form.pdf", 'S');
}

function keySecureString($array, $key)
{
    if (!isset($array[$key])) {$array[$key] = "";}
    return $array[$key];
}
function keySecureSexo($array, $key)
{
    if (!isset($array[$key])) {$array[$key] = "";}
    $array[$key] = strtoupper($array[$key]);
    switch ($array[$key]) {
        case "M":
        case "F":
            break;
        default:
            return "";
    }
    return $array[$key];
}
function keySecureProducto($array, $key)
{
    if (!isset($array[$key])) {$array[$key] = "";}
    $array[$key] = strtoupper($array[$key]);
    switch ($array[$key]) {
        case "":
        case "tarjeta":
        case "credito":
        case "mediya":
        case "moratemprana":
        case "moratardia":
            break;
        default:
            return "";
    }
    return $array[$key];
}
function keySecureZero($array,$key){
    if(!isset($array[$key])){$array[$key]=0;}
    if(!is_numeric($array[$key])){$array[$key]=0;}
    return (float)$array[$key];
}
function keySecureNumbers($array, $key)
{
    if (!isset($array[$key])) {$array[$key] = 0;}
    if (!is_numeric($array[$key])) {$array[$key] = 0;
    }
    $str = preg_replace('/[^0-9.]+/', '', $array[$key]);
    return $str;
}

function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ){
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    $interval = date_diff($datetime1, $datetime2);
    return $interval->format($differenceFormat);
}
function objectToArrayRecusive($object,$assoc=1,$empty=''){
    $out_arr = array();
    $assoc = (!empty($assoc)) ? TRUE : FALSE;
    if (!empty($object)) {
        $arrObj = is_object($object) ? get_object_vars($object) : $object;
        $i=0;
        foreach ($arrObj as $key => $val) {
            $akey = ($assoc !== FALSE) ? $key : $i;
            if (is_array($val) || is_object($val)) {
                $out_arr[$key] = (empty($val)) ? $empty : objectToArrayRecusive($val);
            }
            else {
                $out_arr[$key] = (empty($val)) ? $empty : (string)$val;
            }
            $i++;
        }
    }
    return $out_arr;
}
function avoidNull($data){
   if ($data==null){return "";}else{return $data;};
}
function toUtf8($d){
    if (is_array($d))
        foreach ($d as $k => $v) $d[$k] = toUtf8($v);
    else if(is_object($d))
        foreach ($d as $k => $v) $d->$k = toUtf8($v);
    else
        return utf8_encode($d);
    return $d;
}
function getMimeType($filename){
    $idx = explode( '.', $filename );
    $count_explode = count($idx);
    $idx = strtolower($idx[$count_explode-1]);
	$mimet = array(
		'3gp'     => 'video/3gpp',
		'ai'      => 'application/postscript',
		'aif'     => 'audio/x-aiff',
		'aifc'    => 'audio/x-aiff',
		'aiff'    => 'audio/x-aiff',
		'amr'     => 'audio/amr',
		'asc'     => 'text/plain',
		'atom'    => 'application/atom+xml',
		'au'      => 'audio/basic',
		'avi'     => 'video/x-msvideo',
		'bcpio'   => 'application/x-bcpio',
		'bin'     => 'application/octet-stream',
		'bmp'     => 'image/bmp',
		'cdf'     => 'application/x-netcdf',
		'cgm'     => 'image/cgm',
		'class'   => 'application/octet-stream',
		'cpio'    => 'application/x-cpio',
		'cpt'     => 'application/mac-compactpro',
		'csh'     => 'application/x-csh',
		'css'     => 'text/css',
		'csv'     => 'text/csv',
		'dcr'     => 'application/x-director',
		'dir'     => 'application/x-director',
		'djv'     => 'image/vnd.djvu',
		'djvu'    => 'image/vnd.djvu',
		'dll'     => 'application/octet-stream',
		'dmg'     => 'application/octet-stream',
		'dms'     => 'application/octet-stream',
		'doc'     => 'application/msword',
		'dtd'     => 'application/xml-dtd',
		'dvi'     => 'application/x-dvi',
		'dxr'     => 'application/x-director',
		'eps'     => 'application/postscript',
		'etx'     => 'text/x-setext',
		'exe'     => 'application/octet-stream',
		'ez'      => 'application/andrew-inset',
		'flv'     => 'video/x-flv',
		'gif'     => 'image/gif',
		'gram'    => 'application/srgs',
		'grxml'   => 'application/srgs+xml',
		'gtar'    => 'application/x-gtar',
		'hdf'     => 'application/x-hdf',
		'hqx'     => 'application/mac-binhex40',
		'htm'     => 'text/html',
		'html'    => 'text/html',
		'ice'     => 'x-conference/x-cooltalk',
		'ico'     => 'image/x-icon',
		'ics'     => 'text/calendar',
		'ief'     => 'image/ief',
		'ifb'     => 'text/calendar',
		'iges'    => 'model/iges',
		'igs'     => 'model/iges',
		'jpe'     => 'image/jpeg',
		'jpeg'    => 'image/jpeg',
		'jpg'     => 'image/jpeg',
		'js'      => 'application/x-javascript',
		'json'    => 'application/json',
		'kar'     => 'audio/midi',
		'latex'   => 'application/x-latex',
		'lha'     => 'application/octet-stream',
		'lzh'     => 'application/octet-stream',
		'm3u'     => 'audio/x-mpegurl',
		'man'     => 'application/x-troff-man',
		'mathml'  => 'application/mathml+xml',
		'me'      => 'application/x-troff-me',
		'mesh'    => 'model/mesh',
		'mid'     => 'audio/midi',
		'midi'    => 'audio/midi',
		'mif'     => 'application/vnd.mif',
		'mov'     => 'video/quicktime',
		'movie'   => 'video/x-sgi-movie',
		'm3u8'    => 'application/x-mpegURL',
		'm4a'     => 'audio/mp4',
		'mp2'     => 'audio/mpeg',
		'mp3'     => 'audio/mpeg',
		'mp4'     => 'video/mp4',
		'mpe'     => 'video/mpeg',
		'mpeg'    => 'video/mpeg',
		'mpg'     => 'video/mpeg',
		'mpga'    => 'audio/mpeg',
		'ms'      => 'application/x-troff-ms',
		'msh'     => 'model/mesh',
		'mxu'     => 'video/vnd.mpegurl',
		'nc'      => 'application/x-netcdf',
		'oda'     => 'application/oda',
		'ogg'     => 'application/ogg',
		'pbm'     => 'image/x-portable-bitmap',
		'pdb'     => 'chemical/x-pdb',
		'pdf'     => 'application/pdf',
		'pgm'     => 'image/x-portable-graymap',
		'pgn'     => 'application/x-chess-pgn',
		'png'     => 'image/png',
		'pnm'     => 'image/x-portable-anymap',
		'ppm'     => 'image/x-portable-pixmap',
		'ppt'     => 'application/vnd.ms-powerpoint',
		'pptx'    => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'ps'      => 'application/postscript',
		'qt'      => 'video/quicktime',
		'ra'      => 'audio/x-pn-realaudio',
		'ram'     => 'audio/x-pn-realaudio',
		'ras'     => 'image/x-cmu-raster',
		'rdf'     => 'application/rdf+xml',
		'rgb'     => 'image/x-rgb',
		'rm'      => 'application/vnd.rn-realmedia',
		'rmi'     => 'audio/mid',
		'roff'    => 'application/x-troff',
		'rss'     => 'application/rss+xml',
		'rtf'     => 'text/rtf',
		'rtx'     => 'text/richtext',
		'sgm'     => 'text/sgml',
		'sgml'    => 'text/sgml',
		'sh'      => 'application/x-sh',
		'shar'    => 'application/x-shar',
		'silo'    => 'model/mesh',
		'sit'     => 'application/x-stuffit',
		'skd'     => 'application/x-koan',
		'skm'     => 'application/x-koan',
		'skp'     => 'application/x-koan',
		'skt'     => 'application/x-koan',
		'smi'     => 'application/smil',
		'smil'    => 'application/smil',
		'snd'     => 'audio/basic',
		'so'      => 'application/octet-stream',
		'spl'     => 'application/x-futuresplash',
		'src'     => 'application/x-wais-source',
		'sv4cpio' => 'application/x-sv4cpio',
		'sv4crc'  => 'application/x-sv4crc',
		'svg'     => 'image/svg+xml',
		'svgz'    => 'image/svg+xml',
		'swf'     => 'application/x-shockwave-flash',
		't'       => 'application/x-troff',
		'tar'     => 'application/x-tar',
		'tcl'     => 'application/x-tcl',
		'tex'     => 'application/x-tex',
		'texi'    => 'application/x-texinfo',
		'texinfo' => 'application/x-texinfo',
		'tif'     => 'image/tiff',
		'tiff'    => 'image/tiff',
		'tr'      => 'application/x-troff',
		'tsv'     => 'text/tab-separated-values',
		'txt'     => 'text/plain',
		'ustar'   => 'application/x-ustar',
		'vcd'     => 'application/x-cdlink',
		'vrml'    => 'model/vrml',
		'vxml'    => 'application/voicexml+xml',
		'wav'     => 'audio/x-wav',
		'wbmp'    => 'image/vnd.wap.wbmp',
		'wbxml'   => 'application/vnd.wap.wbxml',
		'wml'     => 'text/vnd.wap.wml',
		'wmlc'    => 'application/vnd.wap.wmlc',
		'wmls'    => 'text/vnd.wap.wmlscript',
		'wmlsc'   => 'application/vnd.wap.wmlscriptc',
		'wmv'     => 'video/x-ms-wmv',
		'wrl'     => 'model/vrml',
		'xbm'     => 'image/x-xbitmap',
		'xht'     => 'application/xhtml+xml',
		'xhtml'   => 'application/xhtml+xml',
		'xls'     => 'application/vnd.ms-excel',
		'xml'     => 'application/xml',
		'xpm'     => 'image/x-xpixmap',
		'xsl'     => 'application/xml',
		'xlsx'    => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xslt'    => 'application/xslt+xml',
		'xul'     => 'application/vnd.mozilla.xul+xml',
		'xwd'     => 'image/x-xwindowdump',
		'xyz'     => 'chemical/x-xyz',
		'zip'     => 'application/zip'
	);    
    if (isset( $mimet[$idx] )) {
        return $mimet[$idx];
    } else {
        return 'no/mime';
    }
}
function imapUtf8Fix($string){
    $string=iconv_mime_decode($string,2,"UTF-8");
    return $string;
}
function isBase64Encoded($str){
    try
    {
        $str=str_replace(' ','+',$str);
        return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $str);
    }
    catch(Exception $e)
    {
        return false;
    }
}
function html2text($Document){
    $Rules = array ('@<script[^>]*?>.*?</script>@si',
                    '@<[\/\!]*?[^<>]*?>@si',
                    '@([\r\n])[\s]+@',
                    '@&(quot|#34);@i',
                    '@&(amp|#38);@i',
                    '@&(lt|#60);@i',
                    '@&(gt|#62);@i',
                    '@&(nbsp|#160);@i',
                    '@&(iexcl|#161);@i',
                    '@&(cent|#162);@i',
                    '@&(pound|#163);@i',
                    '@&(copy|#169);@i',
                    '@&(reg|#174);@i',
                    '@&#(d+);@e'
             );
    $Replace = array ('',
                      '',
                      '',
                      '',
                      '&',
                      '<',
                      '>',
                      ' ',
                      chr(161),
                      chr(162),
                      chr(163),
                      chr(169),
                      chr(174),
                      'chr()'
                );
    //return preg_replace($Rules, $Replace, $Document);
    return strip_tags($Document);
}
function IsInArray($records,$key,$value){
    foreach($records as $item) {if ((string)$item[$key]==(string)$value) {return true;}}
    return false;
}
function getEmailArrayFromString($sString = ''){
    $sPattern = '/[\._\p{L}\p{M}\p{N}-]+@[\._\p{L}\p{M}\p{N}-]+/u';
    preg_match_all($sPattern, $sString, $aMatch);
    $aMatch = array_keys(array_flip(current($aMatch)));
    return $aMatch;
}
function validateDateTime($format){
    return function($dateStr) use ($format) {
        $date = DateTime::createFromFormat($format, $dateStr);
        return $date && $date->format($format) === $dateStr;
    };
}
function removeAccents($str){
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
  return str_replace($a, $b, $str);
}
function mime2ext($mime){
    $mime_map = [
        'video/3gpp2'                                                               => '3g2',
        'video/3gp'                                                                 => '3gp',
        'video/3gpp'                                                                => '3gp',
        'application/x-compressed'                                                  => '7zip',
        'audio/x-acc'                                                               => 'aac',
        'audio/ac3'                                                                 => 'ac3',
        'audio/amr'                                                                 => 'amr',
        'application/postscript'                                                    => 'ai',
        'audio/x-aiff'                                                              => 'aif',
        'audio/aiff'                                                                => 'aif',
        'audio/x-au'                                                                => 'au',
        'video/x-msvideo'                                                           => 'avi',
        'video/msvideo'                                                             => 'avi',
        'video/avi'                                                                 => 'avi',
        'application/x-troff-msvideo'                                               => 'avi',
        'application/macbinary'                                                     => 'bin',
        'application/mac-binary'                                                    => 'bin',
        'application/x-binary'                                                      => 'bin',
        'application/x-macbinary'                                                   => 'bin',
        'image/bmp'                                                                 => 'bmp',
        'image/x-bmp'                                                               => 'bmp',
        'image/x-bitmap'                                                            => 'bmp',
        'image/x-xbitmap'                                                           => 'bmp',
        'image/x-win-bitmap'                                                        => 'bmp',
        'image/x-windows-bmp'                                                       => 'bmp',
        'image/ms-bmp'                                                              => 'bmp',
        'image/x-ms-bmp'                                                            => 'bmp',
        'application/bmp'                                                           => 'bmp',
        'application/x-bmp'                                                         => 'bmp',
        'application/x-win-bitmap'                                                  => 'bmp',
        'application/cdr'                                                           => 'cdr',
        'application/coreldraw'                                                     => 'cdr',
        'application/x-cdr'                                                         => 'cdr',
        'application/x-coreldraw'                                                   => 'cdr',
        'image/cdr'                                                                 => 'cdr',
        'image/x-cdr'                                                               => 'cdr',
        'zz-application/zz-winassoc-cdr'                                            => 'cdr',
        'application/mac-compactpro'                                                => 'cpt',
        'application/pkix-crl'                                                      => 'crl',
        'application/pkcs-crl'                                                      => 'crl',
        'application/x-x509-ca-cert'                                                => 'crt',
        'application/pkix-cert'                                                     => 'crt',
        'text/css'                                                                  => 'css',
        'text/x-comma-separated-values'                                             => 'csv',
        'text/comma-separated-values'                                               => 'csv',
        'application/vnd.msexcel'                                                   => 'csv',
        'application/x-director'                                                    => 'dcr',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
        'application/x-dvi'                                                         => 'dvi',
        'message/rfc822'                                                            => 'eml',
        'application/x-msdownload'                                                  => 'exe',
        'video/x-f4v'                                                               => 'f4v',
        'audio/x-flac'                                                              => 'flac',
        'video/x-flv'                                                               => 'flv',
        'image/gif'                                                                 => 'gif',
        'application/gpg-keys'                                                      => 'gpg',
        'application/x-gtar'                                                        => 'gtar',
        'application/x-gzip'                                                        => 'gzip',
        'application/mac-binhex40'                                                  => 'hqx',
        'application/mac-binhex'                                                    => 'hqx',
        'application/x-binhex40'                                                    => 'hqx',
        'application/x-mac-binhex40'                                                => 'hqx',
        'text/html'                                                                 => 'html',
        'image/x-icon'                                                              => 'ico',
        'image/x-ico'                                                               => 'ico',
        'image/vnd.microsoft.icon'                                                  => 'ico',
        'text/calendar'                                                             => 'ics',
        'application/java-archive'                                                  => 'jar',
        'application/x-java-application'                                            => 'jar',
        'application/x-jar'                                                         => 'jar',
        'image/jp2'                                                                 => 'jp2',
        'video/mj2'                                                                 => 'jp2',
        'image/jpx'                                                                 => 'jp2',
        'image/jpm'                                                                 => 'jp2',
        'image/jpeg'                                                                => 'jpeg',
        'image/pjpeg'                                                               => 'jpeg',
        'application/x-javascript'                                                  => 'js',
        'application/json'                                                          => 'json',
        'text/json'                                                                 => 'json',
        'application/vnd.google-earth.kml+xml'                                      => 'kml',
        'application/vnd.google-earth.kmz'                                          => 'kmz',
        'text/x-log'                                                                => 'log',
        'audio/x-m4a'                                                               => 'm4a',
        'audio/mp4'                                                                 => 'm4a',
        'application/vnd.mpegurl'                                                   => 'm4u',
        'audio/midi'                                                                => 'mid',
        'application/vnd.mif'                                                       => 'mif',
        'video/quicktime'                                                           => 'mov',
        'video/x-sgi-movie'                                                         => 'movie',
        'audio/mpeg'                                                                => 'mp3',
        'audio/mpg'                                                                 => 'mp3',
        'audio/mpeg3'                                                               => 'mp3',
        'audio/mp3'                                                                 => 'mp3',
        'video/mp4'                                                                 => 'mp4',
        'video/mpeg'                                                                => 'mpeg',
        'application/oda'                                                           => 'oda',
        'audio/ogg'                                                                 => 'ogg',
        'video/ogg'                                                                 => 'ogg',
        'application/ogg'                                                           => 'ogg',
        'font/otf'                                                                  => 'otf',
        'application/x-pkcs10'                                                      => 'p10',
        'application/pkcs10'                                                        => 'p10',
        'application/x-pkcs12'                                                      => 'p12',
        'application/x-pkcs7-signature'                                             => 'p7a',
        'application/pkcs7-mime'                                                    => 'p7c',
        'application/x-pkcs7-mime'                                                  => 'p7c',
        'application/x-pkcs7-certreqresp'                                           => 'p7r',
        'application/pkcs7-signature'                                               => 'p7s',
        'application/pdf'                                                           => 'pdf',
        'application/octet-stream'                                                  => 'pdf',
        'application/x-x509-user-cert'                                              => 'pem',
        'application/x-pem-file'                                                    => 'pem',
        'application/pgp'                                                           => 'pgp',
        'application/x-httpd-php'                                                   => 'php',
        'application/php'                                                           => 'php',
        'application/x-php'                                                         => 'php',
        'text/php'                                                                  => 'php',
        'text/x-php'                                                                => 'php',
        'application/x-httpd-php-source'                                            => 'php',
        'image/png'                                                                 => 'png',
        'image/x-png'                                                               => 'png',
        'application/powerpoint'                                                    => 'ppt',
        'application/vnd.ms-powerpoint'                                             => 'ppt',
        'application/vnd.ms-office'                                                 => 'ppt',
        'application/msword'                                                        => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'application/x-photoshop'                                                   => 'psd',
        'image/vnd.adobe.photoshop'                                                 => 'psd',
        'audio/x-realaudio'                                                         => 'ra',
        'audio/x-pn-realaudio'                                                      => 'ram',
        'application/x-rar'                                                         => 'rar',
        'application/rar'                                                           => 'rar',
        'application/x-rar-compressed'                                              => 'rar',
        'audio/x-pn-realaudio-plugin'                                               => 'rpm',
        'application/x-pkcs7'                                                       => 'rsa',
        'text/rtf'                                                                  => 'rtf',
        'text/richtext'                                                             => 'rtx',
        'video/vnd.rn-realvideo'                                                    => 'rv',
        'application/x-stuffit'                                                     => 'sit',
        'application/smil'                                                          => 'smil',
        'text/srt'                                                                  => 'srt',
        'image/svg+xml'                                                             => 'svg',
        'application/x-shockwave-flash'                                             => 'swf',
        'application/x-tar'                                                         => 'tar',
        'application/x-gzip-compressed'                                             => 'tgz',
        'image/tiff'                                                                => 'tiff',
        'font/ttf'                                                                  => 'ttf',
        'text/plain'                                                                => 'txt',
        'text/x-vcard'                                                              => 'vcf',
        'application/videolan'                                                      => 'vlc',
        'text/vtt'                                                                  => 'vtt',
        'audio/x-wav'                                                               => 'wav',
        'audio/wave'                                                                => 'wav',
        'audio/wav'                                                                 => 'wav',
        'application/wbxml'                                                         => 'wbxml',
        'video/webm'                                                                => 'webm',
        'image/webp'                                                                => 'webp',
        'audio/x-ms-wma'                                                            => 'wma',
        'application/wmlc'                                                          => 'wmlc',
        'video/x-ms-wmv'                                                            => 'wmv',
        'video/x-ms-asf'                                                            => 'wmv',
        'font/woff'                                                                 => 'woff',
        'font/woff2'                                                                => 'woff2',
        'application/xhtml+xml'                                                     => 'xhtml',
        'application/excel'                                                         => 'xl',
        'application/msexcel'                                                       => 'xls',
        'application/x-msexcel'                                                     => 'xls',
        'application/x-ms-excel'                                                    => 'xls',
        'application/x-excel'                                                       => 'xls',
        'application/x-dos_ms_excel'                                                => 'xls',
        'application/xls'                                                           => 'xls',
        'application/x-xls'                                                         => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
        'application/vnd.ms-excel'                                                  => 'xlsx',
        'application/xml'                                                           => 'xml',
        'text/xml'                                                                  => 'xml',
        'text/xsl'                                                                  => 'xsl',
        'application/xspf+xml'                                                      => 'xspf',
        'application/x-compress'                                                    => 'z',
        'application/x-zip'                                                         => 'zip',
        'application/zip'                                                           => 'zip',
        'application/x-zip-compressed'                                              => 'zip',
        'application/s-compressed'                                                  => 'zip',
        'multipart/x-zip'                                                           => 'zip',
        'text/x-scriptzsh'                                                          => 'zsh',
    ];
    return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
}
function deleteOldFiles($path,$days){
    $expire = strtotime('-'.$days.' DAYS');
    $files = glob($path . '/*');
    foreach ($files as $file) {
        if (!is_file($file)) {continue;}
        if (filemtime($file) > $expire) {continue;}
        unlink($file);
    }
}
function getAge($fechanacimiento){
  list($ano,$mes,$dia) = explode("-",$fechanacimiento);
  $ano_diferencia  = date("Y") - $ano;
  $mes_diferencia = date("m") - $mes;
  $dia_diferencia   = date("d") - $dia;
  if ($dia_diferencia < 0 || $mes_diferencia < 0){$ano_diferencia--;}
  if ($dia==null or $dia=""){$ano_diferencia="--";}
  return $ano_diferencia;
}
//FILES I/O and COMPRESSION
function connectSFTP(){
    //date_default_timezone_set(DEFAULT_TIMEZONE);
    //$tokenData=array("date"=>date(FORMAT_DATE_TOKENJWT));
    //$jwtToken=encodeTokenJWTSSH($tokenData);
    $session=ssh2_connect(SSH2_FILESERVER, (int)SSH2_FILESERVER_PORT);
    ssh2_auth_password($session, SSH2_FILESERVER_USERNAME, SSH2_FILESERVER_PASSWORD);
    $sftp=ssh2_sftp($session);
    return $sftp;
}
function getFileBinSSH($filePath){
    $sftp=connectSFTP();
    $file="ssh2.sftp://".intval($sftp).$filePath;
    //if(!file_exists($file)){return null;}
    $stream=file_get_contents($file);
    return $stream;
}
function setFileBinSSH($filePath,$binData){
    $sftp=connectSFTP();
    return file_put_contents("ssh2.sftp://".intval($sftp).$filePath,$binData);
}
function listFilesSSH($filePath,$type,$partialname=null){
    $sftp=connectSFTP();
    $arr_files=array();
    $handle = opendir("ssh2.sftp://".intval($sftp).$filePath);
    while (false !== ($fileName = readdir($handle))){
        $part=explode('.', $fileName);
        $ext=strtoupper(end($part));
        if ($fileName!="." and $fileName!=".." and $ext!="THUMBS") {
            if ($type=="" or $type==null or $type=="*.*" or $type=="*") {
               $type="";
               $ext="";
            }
            if (strtoupper($type)==$ext){
                if ($partialname==null or strpos($fileName, $partialname)!==false){array_push($arr_files,($filePath.$fileName));}
            }
        }
    }
    closedir($handle);
    return $arr_files;
}
function existFileSSH($filePath){
    $sftp=connectSFTP();
    $stream=file_exists("ssh2.sftp://".intval($sftp).$filePath);
    return $stream;
}
function saveBase64ToFile($values){
    if (!file_exists($values["path"])) {mkdir($values["path"], 0777, true);}
    $mime=explode(',',$values["data"]);
    $encoded=$mime[1];
    $encoded=str_replace(' ','+',$encoded);
    $data=base64_decode($encoded);
	$values["fullPath"]=removeAccents($values["fullPath"]);
    return file_put_contents($values["fullPath"],$data,FILE_USE_INCLUDE_PATH);
}
function saveBinToFile($values){
    if (!file_exists($values["path"])) {mkdir($values["path"], 0777, true);}
    return file_put_contents($values["fullPath"],$values["data"], FILE_USE_INCLUDE_PATH);
}
function compress($obj,$data) {
    $obj->load->library('zip');
    $obj->zip->compression_level = 9;
    $obj->zip->add_data("compressed.tmp", $data);
    return base64_encode($obj->zip->get_zip());
}
function fileToBase64($filename,$addMime){
   $bin = file_get_contents($filename);
   $base64=base64_encode($bin);
   if ($addMime) {
      $a=getMimeType($filename);
      $base64=('data:'.$a.';base64,'.$base64);
   }
   return $base64;
}
function saveFileInCarpetaDigital($obj,$dni,$segmento,$level,$filename,$rawData)
{
    $dir = CARPETA_DIGITAL;
    $rootpath = ($dir . $dni);
    if ($segmento != null and $segmento != "") {$fullpath = ($rootpath . "/" . $segmento);}
    $sftp = connectSFTP();
    $fileExists = is_dir(('ssh2.sftp://' . $sftp . $rootpath));
    if (!$fileExists) {ssh2_sftp_mkdir($sftp, $rootpath);}
    $fileExists = is_dir(('ssh2.sftp://' . $sftp . $fullpath));
    if (!$fileExists) {ssh2_sftp_mkdir($sftp, $fullpath);}
    $filenameToSave = "";
    switch ($level) {
        case 0 :
            $filenameToSave = ($rootpath ."/". $filename);
            break;
        case 1:
            $filenameToSave = ($fullpath ."/". $filename);
            break;
    }
    if ($rawData != "" and isBase64Encoded($rawData) and $filenameToSave!="") {setFileBinSSH($filenameToSave, base64_decode($rawData));}
}

//USER VERIFICATION
function getUserProfile($obj,$id){
    try {
        $USERS=$obj->createModel(MOD_BACKEND,"Users","Users");
		$USERS->view="vw_users";
        $user=$USERS->get(array("where"=>"id='".$id."'"));
        $GROUPS=$obj->createModel(MOD_BACKEND,"Groups","Groups");
        $group=$GROUPS->get(array("where"=>"id IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$id.")","page"=>1,"pagesize"=>-1));
        if($group["totalrecords"]==0){
            $group=$GROUPS->get(array("where"=>"id IN (SELECT id_group FROM ".MOD_BACKEND."_rel_users_groups WHERE id_user=".$id.")","page"=>1,"pagesize"=>-1));
        }
        $user["data"][0]["groups"]=$group["data"];
        return $user;
    } catch(Exception $e) {
        if ($e->getCode()!==9999){return logError($e,__METHOD__ );} else {return null;}
    }
}
function getPreference($obj,$values,$id_preference) {
    $preferences=null;
	$REL_USERS_PREFERENCES=$obj->createModel(MOD_BACKEND,"Rel_users_preferences","Rel_users_preferences");
	if(!isset($values["id_user_active"])){$values["id_user_active"]="";}
	if($values["id_user_active"]!="" and (int)$values["id_user_active"]!=0){
		$preferences=$REL_USERS_PREFERENCES->get(array("page"=>1,"pagesize"=>1,"where"=>"id_preference=".$id_preference." AND id_user=".$values["id_user_active"]));
	}
	return $preferences;
}
function evalActionPermissions($eval,$groups){
    if (!is_array($eval)) {
       foreach($groups as $group) {if(strpos($group["code"],$eval)===0) {return true;}}
    } else {
       foreach($eval as $item) {
           foreach($groups as $group) {
              if($item["code"]==$group["code"]) {return true;}
           }
       }
    }
    return false;
}
function evalPermissions($eval,$groups){
    foreach($groups as $group) {if(strpos($group["code"],$eval)!==false) {return true;}}
    return false;
}
/*CURL*/
function cUrlGet($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    $jsonResponse = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err=curl_error($ch);
    curl_close($ch);
    $response = json_decode($jsonResponse, true);
    return $response;
}
function cUrl($url,$fields=null){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    if ($fields!=null) {curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);}
    $jsonResponse = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err=curl_error($ch);
    curl_close($ch);
    $response = json_decode($jsonResponse, true);
    return $response;
}
function cUrlImageBase64($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $image=curl_exec($ch);
    $imageData=base64_encode($image);
    $mime_types = array(
        'gif' => 'image/gif',
        'jpg' => 'image/jpg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'bmp' => 'image/bmp'
    );
    $ext = pathinfo($url, PATHINFO_EXTENSION);
    if (array_key_exists($ext, $mime_types)) {
       $a=$mime_types[$ext];
    } else {
       $a=$mime_types["jpg"];
    }
    return 'data:'.$a.';base64,'.$imageData;
}
function getSslPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function cUrlStatusCode($url) {
    $ch = curl_init($url);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: 0'));  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT,10);
    curl_setopt($ch, CURLOPT_POST, 0);
    $output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpcode;
}
function URL_exists($url){
   $headers=get_headers($url);
   return stripos($headers[0],"200 OK")?true:false;
}
function countDecimalPlacesUsingStrrpos($stringValue){
    $locale_info = localeconv();
    $pos = strrpos($stringValue, $locale_info['decimal_point']);
    if ($pos !== false) {
        return strlen($stringValue) - ($pos + 1);
    }
    return 0;
}
