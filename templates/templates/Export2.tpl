<div id="import_dialog_title">
	<ul>
		<li>导出完成</li>
	</ul>	
</div>
<div id="import_dialog_info">
	<ul>
		<li>记录共{$RECORD_COUNT}条，本次导出{$EXPORT_COUNT}条。</li>
		<li>导出文件名：<a id="export_file_download_link" href="{$DOWNLOAD_URL}" style="color:#1E90FF">{$FILE_NAME}</a></li>
		<li>如果导出文件没有自动下载，你可以点击上面文件名直接下载。</li>
	</ul>
</div>
<iframe src="{$DOWNLOAD_URL}" width="0" height="0"></iframe>
<script type="text/javascript">
	{literal}
	$('#titlebar_export_data_dlg').dialog("option","buttons",[{text:"完成",click:function(){$(this).dialog("close")}}]);
	{/literal}
</script>
	