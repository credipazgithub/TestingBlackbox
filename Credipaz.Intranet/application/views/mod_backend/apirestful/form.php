<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
?>
<div class="container-full p-4">
    <h2>API Credipaz v1.2</h2>
    <hr />
    <div class="row">
        <div class="col-2 p-2 menuAPI">
           <?php
           $html.="<div id='accordionAPI'>";
           foreach ($menu["data"] as $item) {
               $id=$item["id"];
               $key = ("item" . $id);
               $html.="<div><a class='btn btn-".$item["brief"]." btn-raised btn-md mb-1' data-toggle='collapse' href='#". $key."'>". ucfirst(lang($item["code"]))."</a></div>";

               $html.="  <div id='" . $key . "' class='collapse' data-parent='#accordionAPI'>";
               $html .= '  <ul class="list-group p-0 m-0">';
               foreach ($item["submenu"] as $subitem) {
                   $server = getServer();
                   if (strpos($server, "localhost") === false) {$server = "https://api.credipaz.com";}
                   $html .= '   <li class="list-group-item"><a href="#" class="btn-link" data-link="' . $server . $subitem["data_module"] . '?' . opensslRandom(16) . '" data-endpoint="' . $server . $subitem["data_model"] . '">' . ucfirst(lang($subitem["code"])) . '</a></li>';
               }
               $html .= '  </ul>';
               $html .= "</div>";
           }
           $html .= "</div>";
           echo $html;
           ?>           
        </div>
        <div class="col-10 detailsAPI"></div>
    </div>
    <div class="row">
        <div class="col-2 p-2 menu">
            
        </div>
        <div class="col-10 details"></div>
    </div>
</div>
<script>
    $("body").off("click", ".btn-link").on("click", ".btn-link", function () {
        var _this = $(this);
        $(".detailsAPI").load(_this.attr("data-link"), function () {$("#endpoint").val(_this.attr("data-endpoint"));});
    });
    function docUiExecute(_json) {
        return new Promise(
            function (resolve, reject) {
                var _endpoint = $("#endpoint").val();
                var _call = { type: "POST", dataType: "json", url: _endpoint, data: _json };
                $("#request").html("<pre>" + JSON.stringify(_call, undefined, 2) + "</pre>");
                var ajaxRq = $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: _endpoint,
                    data: _json,
                    error: function (xhr, ajaxOptions, thrownError) { reject(thrownError); },
                    success: function (datajson) { resolve(datajson); }
                });
            });
    }
</script>
