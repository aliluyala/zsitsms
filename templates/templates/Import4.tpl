<div id="import_dialog_title">
	<ul>
	<li>数据导入完成</li>
	</ul>
</div>

<div id="import_dialog_info">
	<ul>
		<li>共计导入数据 {$DATA_LINES} 行。</li>
	</ul>
</div>

<script type="text/javascript">
	$("#titlebar_import_data_dlg").data("module","{$MODULE}");
	{literal}
	$("#titlebar_import_data_dlg").dialog("option","buttons",[{
			text:"确定",
			click:function() { 
				var url = "index.php?module="+$("#titlebar_import_data_dlg").data("module")+"&action=index";
				$( this ).dialog( "close" ); 
				zswitch_load_client_view(url);	
			} 
		}]
	);	

	{/literal}
</script>
