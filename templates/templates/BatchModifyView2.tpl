<div id="import_dialog_info" style="margin-top:10px;font-size:12p;">
<div class="ui-widget">
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		<strong>警告:</strong>你的操作将影响“{$MODULE_LABEL}”共计{$MODIFY_COUNT}条记录！记录被修改后将不能恢复。是否修改？</p>
	</div>
</div>
</div>
<script type="text/javascript">
	{literal}
		$('#listview_bar_batch_modify_dlg').data("step","2");
	{/literal}	
</script>