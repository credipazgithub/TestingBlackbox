<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//log_message("error", "RELATED ".json_encode($data,JSON_PRETTY_PRINT));
/*---------------------------------*/
?>
	<div class='body-abm d-flex border border-light m-0 px-2 py-1' style='display:none;'>
		<h2 class="p-0 m-0">¡Buenos días <?php echo $parameters["profile"]["data"][0]["username"];?>!</h2>
    </div>
	<div class='body-abm d-flex border border-light m-0 px-2 py-1' style='display:none;'>
    <div class="row p-0 m-0">
		<?php 
		$recData="data-id='0' data-module='mod_folders' data-model='folder_items' data-table='folder_items' data-action='brow' data-page='1' data-filters='[{&quot;name&quot;:&quot;browser_search&quot;,&quot;operator&quot;:&quot;like&quot;,&quot;fields&quot;:[&quot;description&quot;,&quot;keywords&quot;,&quot;folder_keywords&quot;]},{&quot;name&quot;:&quot;browser_id_type_folder&quot;,&quot;operator&quot;:&quot;=&quot;,&quot;fields&quot;:[&quot;id_type_folder&quot;]},{&quot;name&quot;:&quot;browser_id_type_folder_item&quot;,&quot;operator&quot;:&quot;=&quot;,&quot;fields&quot;:[&quot;id_type_folder_item&quot;]}]'";
		$html="<div class='col-8 mt-1'>";
		$html.="	<input id='browser_search' name='browser_search' type='text' class='form-control browser_search' placeholder='".lang('p_search')."' aria-label='".lang('p_search')."' />";
		$html.="</div>";
		$html.="<div class='col-1 mt-1'>";
		$html.="	<button class='btn btn-secondary btn-sm btn-browser-search' ".$recData." type='button'><i class='material-icons' style='font-size:22px;vertical-align:middle;'>search</i>".lang('p_search')."</button>";
		$html.="</div>";
		echo $html;

		$params["title"]="Comunicaciones";
		$params["icon"]="chat_bubble_outline";
		$params["button"]="m_folder_items";
		$params["module"]="mod_folders";
		$params["model"]="folders_userview";
		$params["table"]="folders";
		$params["action"]="brow";
		$params["page"]="1";
		$params["field"]="code_type_folder";
		$params["value"]="COMM";//2
		$params["ver-title"]="Ver por carpeta";
		$params["icon2"]="read_more";
		echo homePanel($params);

		$params["title"]="Normativa";
		$params["icon"]="bookmark_border";
		$params["button"]="m_folder_items";
		$params["module"]="mod_folders";
		$params["model"]="folder_items";
		$params["table"]="folder_items";
		$params["action"]="brow";
		$params["page"]="1";
		$params["field"]="code_type_folder";
		$params["value"]="MNYP";//1
		$params["ver-title"]="Ver por archivo";
		$params["icon2"]="read_more";
		echo homePanel($params);

		$params["title"]="Manual del usuario";
		$params["icon"]="menu_book";
		$params["button"]="m_folder_items";
		$params["module"]="mod_folders";
		$params["model"]="folder_items";
		$params["table"]="folder_items";
		$params["action"]="brow";
		$params["page"]="1";
		$params["field"]="code_type_folder";
		$params["value"]="POL";//3
		$params["ver-title"]="Ver por archivo";
		$params["icon2"]="read_more";
		echo homePanel($params);

		$params["title"]="Recursos humanos";
		$params["icon"]="people_outline";
		$params["button"]="m_folder_items";
		$params["module"]="mod_folders";
		$params["model"]="folders_userview";
		$params["table"]="folders";
		$params["action"]="brow";
		$params["page"]="1";
		$params["field"]="code_type_folder";
		$params["value"]="RRHH";//4
		$params["ver-title"]="Ver por carpeta";
		$params["icon2"]="read_more";
		echo homePanel($params);

		$params["title"]="Seguridad y protección de la información";
		$params["icon"]="lock_open";
		$params["button"]="m_folder_items";
		$params["module"]="mod_folders";
		$params["model"]="folders_userview";
		$params["table"]="folder_items";
		$params["action"]="brow";
		$params["page"]="1";
		$params["field"]="code_type_folder";
		$params["value"]="SYPI";//5
		$params["ver-title"]="Ver por carpeta";
		$params["icon2"]="read_more";
		echo homePanel($params);

		$params["title"]="Documentación interna";
		$params["icon"]="published_with_changes";
		$params["button"]="m_folder_items";
		$params["module"]="mod_folders";
		$params["model"]="folders_userview";
		$params["table"]="folders";
		$params["action"]="brow";
		$params["page"]="1";
		$params["field"]="code_type_folder";
		$params["value"]="DI";//6
		$params["ver-title"]="Ver por carpeta";
		$params["icon2"]="read_more";
		echo homePanel($params);

		$params["title"]="Comité de Seguridad ";
		$params["icon"]="lock_open";
		$params["button"]="m_folder_items";
		$params["module"]="mod_folders";
		$params["model"]="folders_userview";
		$params["table"]="folder_items";
		$params["action"]="brow";
		$params["page"]="1";
		$params["field"]="code_type_folder";
		$params["value"]="COMSEG";//8
		$params["ver-title"]="Ver por carpeta";
		$params["icon2"]="read_more";
		echo homePanel($params);

		?>
   </div>
</div>
<script>
    $(".body-abm").fadeIn("slow");
    clearInterval(_FUNCTIONS._TIMER_INTRANET);
	_FUNCTIONS.onFoldersNotViewedNotification(null);
	_FUNCTIONS.onMessagesNotification(null);
</script>
