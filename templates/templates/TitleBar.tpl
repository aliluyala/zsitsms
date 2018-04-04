<div class="client_titlebar_box">
	<table border="0" cellspacing="0">
		<tr>
		<td style="border-style:solid solid none solid;border-width:2px;border-color:#79b7e7;font-weight:bold;
			padding-left:20px;padding-right:20px;border-top-left-radius:5px;background-color:#f5f8f9;
			border-top-right-radius:5px;-moz-border-top-left-radius:5px;-moz-border-top-right-radius:5px;">
			{if $TITLEBAR_SHOW_MODULE_LABEL}
				<a href="javascript:zswitch_load_client_view('index.php?module={$MODULE}&action=index')">{$MODULE_LABEL}</a> >> 
			{/if}
			{$ACTION_LABEL}
			{* <a href="javascript:zswitch_load_client_view('index.php?module={$MODULE}&action={$ACTION}')">{$ACTION_LABEL}</a> *}
		</td>
		<td style="width:20px;"></td>
		{if $TOOLSBAR.create eq 'yes'}
			<td>
				<a title="新建..." href="javascript:zswitch_load_client_view('index.php?module={$MODULE}&action=createView&return_module={$MODULE}&return_action=detailView')">
					<img src="{$IMAGES}/plus_2.png"/>
				</a>
			</td>
		{/if}
		{if $TOOLSBAR.search eq 'yes'}
			<td>
				<a title="搜索..." href="javascript:void(0);" onclick="$('#{$MODULE}_search_dlg').dialog('open');">
					{if $HAVE_QUERY_WHERE}
						<img src="{$IMAGES}/search_lense_cond1.png"/>
					{else}
						<img src="{$IMAGES}/search_lense.png"/>
					{/if}
				</a>
			</td>
		{/if}
		<td style="width:20px;"></td>
		{if $TOOLSBAR.calendar eq 'yes'}
			<td>
				<a title="日历" href="javascript:void(0);" onclick="zswitch_open_calendar_dlg();"><img src="{$IMAGES}/calendar.png"/></a>
			</td>
		{/if}	
		{if $TOOLSBAR.calculator eq 'yes'}
			<td>
				<a title="计算器"><img src="{$IMAGES}/calculator.png"/></a>
			</td>
		{/if}	
		<td style="width:20px;"></td>
		{if $TOOLSBAR.email eq 'yes'}
			<td>
				<a title="发送电子邮件"><img src="{$IMAGES}/email.png"/></a>
			</td>
		{/if}
		{if $TOOLSBAR.sms eq 'yes'}
			<td>
				<a title="发送手机短信" href="javascript:void(0);" onclick="zswitch_open_sendsms_dlg();"><img src="{$IMAGES}/mobile_sms.png"/></a>
			</td>	
		{/if}
		{*
		{if $TOOLSBAR.phone eq 'yes'}
			<td>
				<a title="打开软电话"><img src="{$IMAGES}/phone.png"/></a>
			</td>
		{/if}
		*}
		<td style="width:20px;"></td>
		{if $TOOLSBAR.import eq 'yes'}
			<td>
				<a title="导入"  href="javascript:void(0);" onclick="zswitch_open_import_dlg('{$MODULE}');"><img src="{$IMAGES}/import.png"/></a>
			</td>
		{/if}
		{if $TOOLSBAR.export eq 'yes'}
			<td>
				<a title="导出"  href="javascript:void(0);" onclick="zswitch_open_export_dlg('{$MODULE}');"><img src="{$IMAGES}/export.png"/></a>
			</td>
		{/if}
		</tr>
	</table>
</div>
