<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>

<?php 
   $language_file=str_replace("-","_",$language);
?>
  <head>
	<meta charset="utf-8">
	<title><?php echo $title_page;?></title>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Language" content="<?php echo $language;?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <link rel="icon" type="image/png" href="favicon.ico"/>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/website.css" />
    <script type="text/javascript" src="/assets/js/_third/jszip.min.js"></script>
    <script type="text/javascript" src="/assets/js/_third/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/_third/popper.min.js"></script>
    <script type="text/javascript" src="/assets/js/_third/blockui.js"></script>
    <script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/js/_third/moment.min.js"></script>
    <script type="text/javascript" src="/assets/js/_third/moment-timezone.min.js"></script>
  </head>
