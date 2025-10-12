<?php 
   $language_file=str_replace("-","_",$language);
?>
<head>
	<meta charset="utf-8">
	<title><?php echo $title_page;?></title>
    <meta http-equiv="Content-Language" content="<?php echo $language;?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta http-equiv="Content-Security-Policy" content="
          default-src * https://* whatsapp://* gap://* tel://* mail://* 'self' 'unsafe-inline' 'unsafe-eval';
          worker-src * blob: 'self' 'unsafe-inline' 'unsafe-eval';
          script-src * data: content: blob: 'self' 'unsafe-inline' 'unsafe-eval';
          connect-src * 'self' 'unsafe-inline' 'unsafe-eval';
          media-src * data: content: blob: 'self' 'unsafe-inline' 'unsafe-eval';
          img-src * data: content: blob: 'self' 'unsafe-inline' 'unsafe-eval';
          style-src * 'self' 'unsafe-inline' 'unsafe-eval';
		  frame-src * 'self' 'unsafe-inline' 'unsafe-eval' blob: data:;"
		  />
	<meta property='og:type' content='website' />
	<meta property='og:title' content='Acceso directo' />
	<meta property='og:description' content='Acceso a funcionalidad específica por parte de terceros' />
	<meta property='og:image' content='/assets/img/accept.gif'/>

    <link rel="icon" type="image/png" href="favicon.ico"/>
    <link rel="stylesheet" href="./assets/css/neotransac.css" />
    <link rel="stylesheet" href="./assets/css/croppie.css" />
    <link rel="stylesheet" href="./assets/trumbo/ui/trumbowyg.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
	<link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap-theme.min.css" />
	<link rel="stylesheet" href="./assets/bootstrap/css/bootstrap-select.css" />
	<link rel="stylesheet" href="./assets/bootstrap-material-design/css/bootstrap-material-design.css" />
    <link rel="stylesheet" href="./assets/css/website.css" />
    <link rel="stylesheet" href="./assets/css/material.css" />

    <script src="./assets/js/_third/jquery.min.js"></script>
    <script src="./assets/js/_third/popper.min.js"></script>
    <script src="./assets/js/_third/jszip.min.js"></script>
    <script src="./assets/js/_third/moment.min.js"></script>
    <script src="./assets/js/_third/moment-timezone.min.js"></script>
    <script src="./assets/js/_third/shorten.js"></script>
    <script src="./assets/js/_third/barcode.js"></script>
    <script src="./assets/js/_third/blockui.js"></script>
    <script src="./assets/js/_third/exif.js"></script>
    <script src="./assets/js/_third/croppie.min.js"></script>
    <script src="./assets/js/_third/html2canvas.js"></script>
    <script src="./assets/js/_third/html2pdf.js"></script>
    <script src="./assets/js/_third/screenfull.js"></script>
    <script src="./assets/js/_third/jquery.classyqr.min.js"></script>
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/bootstrap/js/bootstrap-select.min.js"></script>
	<script src="./assets/bootstrap-material-design/js/bootstrap-material-design.min.js"></script>
    <script src="./assets/js/trumbo/trumbowyg.js"></script>
    <script src="./assets/js/trumbo/langs/es_ar.min.js"></script>
</head>
