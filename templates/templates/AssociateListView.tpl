<form id="associate_listview_table_form" onsubmit="return false;" style="margin:0px">
<input name="record_page"  type="hidden" value="{$LISTVIEW_RECORD_PAGE}"/>
<input name="order_by"     type="hidden" value="{$LISTVIEW_ORDER_BY}"/>
<input name="order"        type="hidden" value="{$LISTVIEW_ORDER}"/>
<input name="module"       type="hidden" value="{$MODULE}"/>
<input name="action"       type="hidden" value="{$ACTION}"/>
<input name="associate_field" type="hidden" value ="{$ASSOCIATE_FIELD}"/>
<input name="associate_value" type="hidden" value = "{$ASSOCIATE_VALUE}"/>
<input name="list_fields" type="hidden" value ="{$LIST_FIELDS}"/>


<div class="client_listview_bar small">
	<table border="0" cellspacing="0"  width="100%">
		<tr>
			<td style="text-align:right;">
				显示:{$LISTVIEW_RECORD_START}-{$LISTVIEW_RECORD_END} 共计:{$LISTVIEW_RECORD_TOTAL}条 |
				<a href="javascript:zswitch_associate_listview_page_ctrl(1,'associate_listview_table_form');" title="跳转到第一页">首页</a> |
				<a href="javascript:zswitch_associate_listview_page_ctrl({$LISTVIEW_RECORD_PAGE}-1,'associate_listview_table_form');" title="跳转到上一页">上页</a> |
				<input name="current_page" style="width:30px;font-size:10px" value="{$LISTVIEW_RECORD_PAGE}" 
					onchange="zswitch_associate_listview_page_ctrl(this.value,'associate_listview_table_form');"	/> /{$LISTVIEW_RECORD_PAGECOUNT} | 
				<a href="javascript:zswitch_associate_listview_page_ctrl({$LISTVIEW_RECORD_PAGE}+1,'associate_listview_table_form');" title="跳转到下一页">下页<a/> | 
				<a href="javascript:zswitch_associate_listview_page_ctrl(99999999999,'associate_listview_table_form');" title="跳转到最后一页">尾页</a> | 

			</td>	
		</tr>
	</table>
</div>


<div style="margin-top:3px;">

<table id="client_listview_table" class="client_listview_table small" cellspacing="0"  style="margin:0px 0px 0px 0px;">
	<tr>

		{foreach $LISTVIEW_HEADERS as $name => $col }
			<th>
				{if $col.allow_order }
					{* 列排序 *}
					<a href="javascript:zswitch_associate_listview_order_ctrl('{$name}','{$col.order}','associate_listview_table_form')" 
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
		{if $ASSOCIATE_LISTVIEW_OPERATION_ALLOW}
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
			{if $ASSOCIATE_LISTVIEW_OPERATION_ALLOW}
				<td>
				{foreach $ASSOCIATE_LISTVIEW_OPERATIONS as $oper}					
					<a href="javascript:void(0);" recordid="{$id}" onclick="{$oper.url}" title="{$oper.title}">{$oper.name}</a>	|				
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
<script type="text/javascript" >
	{literal}
		zswitch_client_listview_table_init("client_listview_table");
		$("#select_record_all").click(function(){
			if($(this).is(":checked"))
			{
				$("[name^=selected_records]").prop("checked",true);
			}
		});
		$("[name^=selected_records]").click(function(){
			if(!$(this).is(":checked"))
			{
				$("#select_record_all").prop("checked",false);
			}	
		});
		$(".client_listview_button").button().click(function(){
			var oper = $(this).attr("action");
			alert(oper);
		});
	{/literal}	
</script>
