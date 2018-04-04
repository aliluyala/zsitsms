{include file="TitleBar.tpl"}

<form id="client_listview_table_form" onsubmit="return false;" style="margin:0px">

<input name="record_page"  type="hidden" value="{$LISTVIEW_RECORD_PAGE}"/>
<input name="order_by"     type="hidden" value="{$LISTVIEW_ORDER_BY}"/>
<input name="order"        type="hidden" value="{$LISTVIEW_ORDER}"/>
<input name="query_where"  type="hidden" value="{$LISTVIEW_QUERY_WHERE}"/>
<input name="module"       type="hidden" value="{$MODULE}"/>
<input name="action"       type="hidden" value="{$ACTION}"/>


{if $LISTVIEW_BAR_ALLOW}
<div class="client_listview_bar small">
	<table border="0" cellspacing="0"  width="100%">
		<tr>
			{* 批量操作按钮 *}
			<td style="text-align:left;">
			{if $LISTVIEW_BUTTONS.rebuild }
				<button class="client_listview_button small" title="在个修改权限设置和用户权限后，需要重建权限控制文件，权限才能生效。此操作，重建全部权限控制文件。" action="rebuild">重建权限</button>
			{/if}	
			{if $LISTVIEW_BUTTONS.refurbish }
				<button class="client_listview_button small" title="修改模块代码后，或修改模块设置后，需刷新权限数据，以保证权限控制生效。" action="refurbish">刷新权限</button>
			{/if}	
			</td>
			<td style="text-align:right;">
				显示:{$LISTVIEW_RECORD_START}-{$LISTVIEW_RECORD_END} 共计:{$LISTVIEW_RECORD_TOTAL}条 |
				<a href="javascript:zswitch_client_listview_page_ctrl(1,'client_listview_table_form');" title="跳转到第一页">首页</a> |
				<a href="javascript:zswitch_client_listview_page_ctrl({$LISTVIEW_RECORD_PAGE}-1,'client_listview_table_form');" title="跳转到上一页">上页</a> |
				<input name="current_page" style="width:30px;font-size:10px" value="{$LISTVIEW_RECORD_PAGECOUNT}" 
					onchange="zswitch_client_listview_page_ctrl(this.value,'client_listview_table_form');"	/> /{$LISTVIEW_RECORD_PAGECOUNT} | 
				<a href="javascript:zswitch_client_listview_page_ctrl({$LISTVIEW_RECORD_PAGE}+1,'client_listview_table_form');" title="跳转到下一页">下页<a/> | 
				<a href="javascript:zswitch_client_listview_page_ctrl(99999999999,'client_listview_table_form');" title="跳转到最后一页">尾页</a> | 
			<!--	过滤:
				<select name ="filterid" style="font-size:10px;" 
					onchange="zswitch_load_client_view('index.php?module={$MODULE}&action={$ACTION}','client_listview_table_form')">
					{html_options options=$LISTVIEW_FILTER_LIST selected=$LISTVIEW_SELECTED_FILTER}
				</select> <a href="" title="编辑、修改过滤条件">编辑</a> -->
			</td>	
		</tr>
	</table>
</div>
{/if}
<div style="margin-top:3px;">

<table id="client_listview_table" class="client_listview_table small" cellspacing="0" >
	<tr>
		
		{foreach $LISTVIEW_HEADERS as $name => $col }
			<th>
				{if $col.allow_order }
					{* 列排序 *}
					<a href="javascript:zswitch_client_listview_order_ctrl('{$name}','{$col.order}','client_listview_table_form')" 
						title="点击重新排序">{$col.label} 
					{if $col.order eq "ASC"}
						<img style="border-style: none;" src="{$IMAGES}/arrow_up_red.gif" />
					{elseif $col.order eq "DESC"}
						<img style="border-style: none;" src="{$IMAGES}/arrow_down_red.gif" />
					{/if}	
					</a>
				{else}
					{$col.label} 
				{/if}	
			</th>
		{/foreach}
		{if $LISTVIEW_OPERATION_ALLOW}
			<th>操作</th>	
		{/if}
	</tr>
	{foreach $LISTVIEW_DATA as $id => $row}
		<tr>
			
			{foreach $row as $fieldname => $field}
				<td>
				{if $field.have_associate}
					<a href="{$field.associate_to}" recordid="{$id}">{$field.value}</a>
				{else}
					{$field.value}
				{/if}
				&nbsp; 
				</td>
			{/foreach}
			{if $LISTVIEW_OPERATION_ALLOW}
				<td>
				{foreach $LISTVIEW_OPERATIONS as $oper}					
					<a href="javascript:void(0);" onclick="{$oper.url}" recordid="{$id}">{$oper.name}</a>	|				
				{/foreach}
				</td>	
			{/if}			
		</tr>
	{/foreach}
</table>
	{if $LISTVIEW_RECORD_TOTAL eq 0}
	<div >
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="height:80px;line-height:80px;margin-top: 20px; padding: 0 .7em;text-align:left">
				<table><tr>
				<td>
				<span class="ui-icon ui-icon-info" ></span></td><td>
				<strong>对不起！</strong> 没有满足条件的记录可显示，请重新确认你的查询条件。
				</td></tr></table>
			</div>
		</div>
	</div>
	{/if}
</div>
</form>

<div id="permission_operation_dlg"></div>


<script type="text/javascript" >
	{literal}
		zswitch_client_listview_table_init("client_listview_table");
		$(".client_listview_button").button().click(function(){
			var oper = $(this).attr("action");
			$("#permission_operation_dlg").html("");
			$("#permission_operation_dlg").dialog("option","buttons",[]);
			if(oper == "rebuild")
			{
				$("#permission_operation_dlg").dialog("option","title","重建权限控制文件");
				$("#permission_operation_dlg").dialog("open");
				zswitch_ajax_request("index.php?module=PermissionManager&action=rebuild",'',function(type,data){
					$("#permission_operation_dlg").html(data);
					$("#permission_operation_dlg").dialog("option","buttons",[{text:"确定",click:function(){$(this).dialog( "close" ); }}]);	
				});
				//zswitch_load_client_view("index.php?module=PermissionManager&action=rebuild");
			}
			else if(oper == "refurbish")
			{
				$("#permission_operation_dlg").dialog("option","title","刷新权限数据");
				$("#permission_operation_dlg").dialog("open");
				zswitch_ajax_request("index.php?module=PermissionManager&action=refurbish",'',function(type,data){
					$("#permission_operation_dlg").html(data);
					$("#permission_operation_dlg").dialog("option","buttons",[{text:"确定",click:function(){$(this).dialog( "close" ); }}]);							
				});
				//zswitch_load_client_view("index.php?module=PermissionManager&action=refurbish");
			}
		
		});
		$("#permission_operation_dlg").dialog({
			autoOpen: false,
			height: 150,
			width: 300,
			modal: true,
			appendTo: "#main_view_client",
			dialogClass:"dialog_default_class",
			beforeClose:function(){
				//return false;
			},
			open:function(){
				zswitch_show_progressbar($(this),"permission_operation_progress_bar");
			}
		});
	{/literal}	
</script>

