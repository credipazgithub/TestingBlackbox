<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
$id=$parameters["records"]["data"][0]["id"];
$id_type_task_close=$parameters["records"]["data"][0]["id_type_task_close"];
$whatsapp=$parameters["records"]["data"][0]["whatsapp"];
if(!isset($parameters["readonly"])){$parameters["readonly"]="0";}
if($parameters["readonly"]==""){$parameters["readonly"]="0";}

$html=buildHeaderAbmStd($parameters,$parameters["title"]);

$html.="<div class='body-abm'>";
$html.="  <span class='badge badge-info'>".$id."</span>";
$html.="  <table style='width:100%;'>";
$html.="   <tr>";
$html.="      <td class='shadow' style='width:50%;padding:15px;' valign='top'>";
$html.="         <div class='row p-1'><input type='hidden' id='id_ot' name='id_ot' class='id_ot' value='".$id."'/>";
$html.=getInput($parameters,array("col"=>"col-12","name"=>"whatsapp","empty"=>true,"default"=>"+549","type"=>"text","class"=>"form-control text whatsapp dbase validate"));
$html.="         </div>";
$html.="         <div class='row m-1'>";
$html.="		    <div id='meet' style='width:100%;'></div>";
$html.="         </div>";
$html.="         <div class='row p-1'>";
$html.="		    <div class='col-12'><a href='#' class='btn btn-md btn-raised btn-primary btn-block d-none btn-caller' data-auditoria='N' data-target='#meet' data-id-charge-code='0' data-full-name='".$parameters["chat_fullname"]."' data-alias='".$parameters["chat_alias"]."' data-height='".$parameters["chat_height"]."' data-platform-name='".$parameters["chat_platformname"]."' data-room-name='".$parameters["chat_roomname"]."' data-domain='".$parameters["chat_domain"]."'>Iniciar videochat</a></div>";
$html.="         </div>";
$html.="      </td>";
$html.="      <td style='width:50%;' valign='top'>";
$html.="         <div class='row p-2'>";
$html.="		    <div class='col-12'>";
$html.="		       <h4>¿dónde mostrar la cámara del salón de ventas?</h4>";
$html.="		       Misma solapa <input checked type='radio' id='rdOpen' name='rdOpen' value='D'/>";
$html.="		       Nueva solapa <input type='radio' id='rdOpen' name='rdOpen' value='N'/>";
$html.="		    </div>";
$html.=getHtmlResolved($parameters,"controls","id_camera",array("col"=>"col-12"));

$html.="         </div>";
$html.="         <div class='row p-2'>";
$html.="		    <div class='col-12'><a href='#' class='btn btn-md btn-raised btn-info btn-block d-none btnToggleCamera withactivevideo'>Mostrar al cliente</a></div>";
$html.="         </div>";
$html.="         <div class='row p-2'>";
$html.=getInput($parameters,array("col"=>"col-4","name"=>"dni","type"=>"text","class"=>"form-control text dni dbase"));
$html.=getInput($parameters,array("col"=>"col-4","name"=>"name","type"=>"text","class"=>"form-control text name dbase"));
$html.=getInput($parameters,array("col"=>"col-4","name"=>"surname","type"=>"text","class"=>"form-control text surname dbase"));
$html.=getHtmlResolved($parameters,"controls","id_type_status",array("col"=>"col-12"));
$html.=getInput($parameters,array("col"=>"col-12","name"=>"description","type"=>"text","class"=>"form-control text dbase"));
$html.=getTextArea($parameters,array("col"=>"col-12","rows"=>"10","name"=>"sinopsys","class"=>"form-control text dbase"));
$html.="         </div>";
$html.="      </td>";
$html.="   </tr>";
$html.="  </table>";
$html.="</div>";

$html.="<div style='width:100%;'>";
$html.=buildFooterAbmStd($parameters);
$html.="<img src='' class='img-test'/>";

$html.="</div>";
echo $html;
?>
<script>
    var _chatroom='<?php echo $parameters["chat_roomname"];?>';
	var winCamera = null;
	var _sharing = false;
	let gdmStream;

	async function shareScreen(){
		if (!_sharing) {
			try {
				gdmStream = await navigator.mediaDevices.getDisplayMedia({ video: { frameRate: 1, width: 320, height: 200, displaySurface: "browser" }, audio: false });
				gdmStream.getVideoTracks()[0].onended = function () {
					_sharing = false;
				    setStatusShare(_sharing);
				};
				_sharing = true;
			    setStatusShare(_sharing);
			} catch (e) {
				_sharing = false;
			    setStatusShare(_sharing);
			}
		} else {
			gdmStream.getTracks().forEach(function(track){
			    track.stop();
				_sharing = false;
			    setStatusShare(_sharing);
			});
		}
	}
	function setStatusShare(_status){
	   if (_status) {
			$(".btnToggleCamera").html("Dejar de compartir").removeClass("btn-info").addClass("btn-warning");
	   } else {
			$(".btnToggleCamera").html("Mostrar al cliente").removeClass("btn-warning").addClass("btn-info");
	   }
	}

	$("body").off("click", ".btnToggleCamera").on("click", ".btnToggleCamera", function () {
	    shareScreen();
	});
	$("body").off("change", ".id_camera").on("change", ".id_camera", function () {
		if ($(this).val()!="") {
			var _param = { "module": "mod_direct_sale", "table": "cameras", "model": "cameras", "order": "description ASC", "page": -1, "pagesize": -1, "where":"id="+$(this).val()};
			_AJAX.UiGet(_param).then(function (_datajson) {
			    var _target="_blank";
			    switch($("#rdOpen:checked").val()) {
				   case "D":
				      _target="_camera";
				      break;
				}
				winCamera = window.open(_datajson["data"][0]["url"], _target);
				if (!winCamera) {
					alert('¡Por favor, no cancele los popups para este sitio!');
				} else {
				   winCamera.blur();
				   setTimeout(function(){window.focus();},500);
				}   
			});
		} 
	});
	$("body").off("keyup", ".whatsapp").on("keyup", ".whatsapp", function () {
	     $(".btn-caller").addClass("d-none");
	     if ($(this).val().length>=10){$(".btn-caller").removeClass("d-none");}
	});
	$("body").off("click", ".btn-caller").on("click", ".btn-caller", function () {
	 	/*NeoVideo implementation! */
	    var today = new Date();
		$.blockUI({ message: '<img src="https://intranet.credipaz.com/assets/img/wait.gif" />', css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });

	    //var _close_url = (_NEOVIDEO._SERVER + "assets/html/thanks.html?" + today.toDateString());
	    var _close_url = ("https://intranet.credipaz.com/webrtc/thanks.html?" + today.toDateString());

		_NEOVIDEO.onDisconnect= function(){
			if ($(".id_type_status").val() == "") { $(".id_type_status").val(3); }
			$(".btn-abm-accept").click();
		};

		_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.height = "600px";
		_NEOVIDEO._CONFIG_INIT_VIDEO_DEFAULTS.width = "100%";
	    _FUNCTIONS.onCreateVideoHost("tienda-en-vivo", _close_url).then(function (_params) {
			$.unblockUI();
			$(".withactivevideo").removeClass("d-none");
			$(".id_camera").val("").change();
			//var _text = (_NEOVIDEO._SERVER + "assets/html/neomobile.html?data=" + _TOOLS.utf8_to_b64(JSON.stringify(_params)));
			var _text = ("https://intranet.credipaz.com/webrtc/neomobile.html?data=" + _TOOLS.utf8_to_b64(JSON.stringify(_params)));
			var _link = ("https://wa.me/" + $(".whatsapp").val() + "?text=" + encodeURIComponent(_text));
			$(".last_link").val(_link);
			$(".btn-last_link").removeClass("d-none");
			window.open(_link, '_blank');
		}).catch(function(err){
			$.unblockUI();
			_FUNCTIONS.onShowAlert(err.message, "Alerta");
		});
	});
	$(".whatsapp").keyup();
</script>
