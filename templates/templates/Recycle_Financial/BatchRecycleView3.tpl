<div id="import_dialog_info">
	<ul>
		<li>共恢复“{$MODULE_LABEL}”的{$RECYCLE_COUNT}条记录。</li>
	</ul>
</div>
<script type="text/javascript">
	{literal}
	$('#listview_bar_batch_recycle_dlg').dialog("option","buttons",[{text:"完成",click:function(){
			var url = "index.php?module="+$(this).data("module")+"&action=index";
			$(this).dialog("close");
			zswitch_load_client_view(url,"client_listview_table_form");
		}}]);
	{/literal}

</script>