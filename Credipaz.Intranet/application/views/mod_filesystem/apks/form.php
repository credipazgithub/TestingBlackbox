<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container marco">
    <img src="https://intranet.credipaz.com/assets/credipaz/img/small.png" style="width:200px;padding-top:20px;"/>
    <h2><?php echo $title;?></h2>
    <?php 
        $html="";
        $i=0;
        foreach($files as $file){
            $base=basename($file);
            $size=number_format(filesize($file) / 1048576, 2);
            $date=date("d/m/Y H:i:s", filemtime($file));
            $html.="<div class='card' style='font-size:18px;padding:15px;margin-bottom:20px;font-size:22px;'>";
            $html.="<a href='.".$file."'>";
            $html.="<span class='badge badge-light'>".$date."</span>";
            $html.="<span class='badge badge-dark'>".$base."</span>";
            $html.="<span class='badge badge-light'>".$size."MB</span>";
            $html.="<span class='badge badge-danger'> (Versión para distribución)</span>";
            $html.="</a>";
            $html.="</div>";
            $i+=1;
        }
        echo $html;
    ?>
</div>
