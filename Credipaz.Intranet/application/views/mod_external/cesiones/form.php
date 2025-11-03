<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$hide = "d-flex";
$show = "";
if ($interno == "S") {
    $hide = "d-none";
    $show = "d-flex";
}
?>
<input type="hidden" id="interno" name="interno" class="interno" value="<?php echo $interno; ?>"/>
<div class="container-full" id="wrapper">
    <div id="page-content-wrapper">
        <div class="<?php echo $hide;?>" style="height:50px;border-bottom:2px solid black;">
            <div class="col-12 ml-auto m-auto p-auto" style="max-height:47px;">
                <div class="float-right status-ajax-calls d-none p-0 m-0">
                    <img class="rounded-circle shadow img-user" src="https://intranet.credipaz.com/assets/img/user.jpg" style="height:38px;"/>
                    <span class="text-break font-weight-lighter badge badge-primary mx-0 px-2 raw-username_active d-none d-sm-inline"></span>
                    <a href="./cesiones" class="btn btn-raised btn-sm btn-logout"><i class='material-icons'>logout</i></a>
                </div>
            </div>
        </div>

        <div class="container-full m-0 p-1">
            <h2 style="text-align:left;margin:0px;padding:0px;font-size:22px;font-weight:bold;color:#dd127b;font-family: 'Poppins-Bold' !important;">Consulta de créditos</h2>        
            <div class="row mt-2">
                <div class="col-4 pt-2 d-none back">
                    <button type="button" class="btn-raised btn btn-md btn-danger btn-informes" data-dni=""><i class="material-icons">west</i>Volver</button>
                </div>
                <?php if ($interno == "S") {
                    echo "<div class='col-2 pt-2 search'>";
                    echo "   <select id='cboBanco' name='cboBanco' class='from-control cboBanco'>";
                    foreach ($bancos as $record) {
                        echo "<option selected value='" . $record["id_user_active"] . "'>" . $record["Nombre"] . "</option>";
                    }
                    echo "   </select>";
                    echo "</div>";
                } else {
                    echo "<input type='hidden' id='cboBanco' name='cboBanco' class='cboBanco' value='" . $id_user_active . "'/>";
                };?>

                <div class="col-2 pt-2 search">
                    <select id="cboCesion" name="cboCesion" class="from-control cboCesion"></select>
                </div>
                <div class="col-2 pt-2 search">
                    <input type="number" class="number form-control dni" id="dni" name="dni" placeholder="Buscar DNI" />
                </div>
                <div class="col-2 pt-2 search">
                    <button type="button" class="btn-raised btn btn-sm btn-warning btn-downloadZip" data-dni=""><i class="material-icons">download</i>Descargar ZIP</button>
                </div>
            </div>
        </div>
        <div class="container-full m-0 p-1 listado"></div>
    </div>
</div>
<script>
    $.getScript('./application/views/mod_external/cesiones/form.js', function () {
        _AJAX.docUiExecute(_AJAX.server + "credipaz/cesiones", { "id_user_active": $(".cboBanco").val() }).then(function (data) {
            $.each(data.data, function (i, item) {
                $(".cboCesion").append('<option selected value="' + item.FechaCesion+ '">' + item.Descripcion + '</option>');
            })
            $(".cboCesion").val("ALL");
            FillGrid("", false);
        });
	});
</script>
