<div class="client_titlebar_box">
	<table border="0" cellspacing="0">
		<tr>
		<td style="border-style:solid solid none solid;border-width:2px;border-color:#79b7e7;font-weight:bold;
			padding-left:20px;padding-right:20px;border-top-left-radius:5px;background-color:#f5f8f9;
			border-top-right-radius:5px;-moz-border-top-left-radius:5px;-moz-border-top-right-radius:5px;height:30Px;">
			{if $TITLEBAR_SHOW_MODULE_LABEL}
				<a href="javascript:zswitch_load_client_view('index.php?module={$MODULE}&action=index')">{$MODULE_LABEL}</a> >> 
			{/if}
			{$ACTION_LABEL}
			{* <a href="javascript:zswitch_load_client_view('index.php?module={$MODULE}&action={$ACTION}')">{$ACTION_LABEL}</a> *}
		</td>
		<td style="width:20px;"></td>

		{if $TOOLSBAR.wfrole_sync eq 'yes'}
			<td>
				<a id="wrole_synchronization_button" title="同步工作流角色数据"  href="javascript:void(0);" ><img src="{$IMAGES}/sync.gif"/></a>
			</td>
		{/if}		
		</tr>
	</table>
</div>
<div  id="wrole_synchronization">
   {*
	<div class="ui-widget" style="height:40px">
		<div class="ui-state-error ui-corner-all" style="height:50px;margin:50px 10px 0px 10px; padding: 0.7em;text-align:left">
	*}	
			<p><span class="ui-icon ui-icon-info" style="float:left; margin-right:.3em;"></span>
			“确定”将清除工作流角色数据，并重建。<br/>“取消”忽略本次操作。</p>
	{*		
		</div>
	</div>
	*}
</div>
<script>
{literal}
	$("#wrole_synchronization").dialog({
		title:"同步角色数据",
		autoOpen:false,
		height:170,
		width:300,
		modal:true,		
		dialogClass:"dialog_default_class",
		buttons:{
			'确定':function(){
				$(this).dialog("close");
			},
			'取消':function(){
				$(this).dialog("close");
			}
		}
	});	
	
	$("#wrole_synchronization_button").click(function(){
		console.log('wrole_synchronization');
		$("#wrole_synchronization").dialog('open');
	});
{/literal}		
</script>