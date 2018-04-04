<div id="import_dialog_info" style="margin-top:10px;font-size:12p;">
<div class="ui-widget">
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		<strong>警告:</strong>你将删除“{$MODULE_LABEL}”共计{$DELETE_COUNT}条记录！记录删除后将不能恢复。是否删除？</p>
	</div>
</div>
</div>
<script type="text/javascript">
	{literal}
		$('#listview_bar_batch_delete_dlg').data("step","2");
	{/literal}
	
</script>