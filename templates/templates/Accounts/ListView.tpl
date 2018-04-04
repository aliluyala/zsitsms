{include file="TitleBarA.tpl"}

<div id="{$MODULE}_search_dlg" title="搜索...">
	<div id="search_ui_list" style="display:none">

		<div id="search_ui_select_field">
			<select name = "search_select_field" >
				{foreach $LSITVIEW_SEARCH_UI as $field => $ui}
					<option value="{$ui.name}">{$ui.label}</option>
				{/foreach}
			</select>
		</div>
		<div id="search_ui_condition_num">
			<select name="select_condition">
				<option value="=">等于</option>
				<option value="!=">不等于</option>
				<option value=">">大于</option>
				<option value=">=">大于等于</option>
				<option value="<">小于</option>
				<option value="<=">小于等于</option>
			</select>
		</div>
		<div id="search_ui_condition_str">
			<select name="select_condition">
				<option value="=">等于</option>
				<option value="!=">不等于</option>
				<option value="like_start">开始是</option>
				<option value="like_end">结束是</option>
				<option value="like_contain">包含</option>
				<option value="like_no_contain">不包含</option>
			</select>
		</div>
		<div id="search_ui_link">
			<select name="select_link">
				<option value="and">与</option>
				<option value="or">或</option>
			</select>
		</div>
		<div id="search_ui_delete_but">
			<button class="listview_search_button_one" style="font-size:10px" action="delete_condition">删除</button>
		</div>
	</div>
	<form id="{$MODULE}_search_form">
	<table id="{$MODULE}_search_condition_list" class="client_listview_table small" cellspacing="0" style="text-align:center">
		<tr>
			<th>字段</th><th>条件</th><th>值</th><th>联接</th><th>&nbsp;</th>
		</tr>
	</table>
	</form>
	<button class="listview_search_button" style="font-size:10px" action="add_conditin">增加条件</button >
	<button class="listview_search_button" style="font-size:10px" action="delete_all_condition">删除全部条件</button>


</div>
<script>
	var search_query_where = $.evalJSON(decodeURIComponent("{$LISTVIEW_QUERY_WHERE_JSON}"));
	var search_ui = $.evalJSON(decodeURIComponent("{$LSITVIEW_SEARCH_UI_JSON}"));
	$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
	$("#{$MODULE}_search_dlg").dialog({ldelim}
		autoOpen: false,
		height: 500,
		width: 620,
		modal: true,
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		buttons: {ldelim}
		"确定":function(){ldelim}
			zswitch_create_search_conditin("{$MODULE}");
			$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
			$("#client_listview_table_form").children("[name=record_page]").val("1");
			var url = "index.php?module={$MODULE}&action={$ACTION}";
			zswitch_load_client_view(url,"client_listview_table_form");
			$( this ).dialog("close");
		{rdelim},
		"取消":function(){ldelim}
			$( this ).dialog("close");
		{rdelim}
		{rdelim},
		open:function(){ldelim}
			zswitch_init_search_conditin("{$MODULE}",search_query_where);
		{rdelim}
	{rdelim});
	$(".listview_search_button").button().click(function(){ldelim}
		var  action = $(this).attr("action");
		if(action  == "add_conditin")
		{ldelim}
			zswitch_add_search_conditin("{$MODULE}");
		{rdelim}
		else if(action == "delete_all_condition")
		{ldelim}
			$("#{$MODULE}_search_condition_list").find("tr:gt(0)").remove();
		{rdelim}
		return false;
	{rdelim});
</script>

<form id="client_listview_table_form" onsubmit="return false;" style="margin:0px">
<input name="record_page"  type="hidden" value="{$LISTVIEW_RECORD_PAGE}"/>
<input name="order_by"     type="hidden" value="{$LISTVIEW_ORDER_BY}"/>
<input name="order"        type="hidden" value="{$LISTVIEW_ORDER}"/>
<input name="query_where"  type="hidden" value=""/>
<input name="module"       type="hidden" value="{$MODULE}"/>
<input name="action"       type="hidden" value="{$ACTION}"/>


{if $LISTVIEW_BAR_ALLOW}
<div class="client_listview_bar small">
	<table border="0" cellspacing="0"  width="100%">
		<tr>
			<!-- 批量操作按钮-->
			<td style="text-align:left;">
			{if $LISTVIEW_BUTTONS.delete }
				<button class="client_listview_button small" title="批量删除" action="batch_delete">批量删除</button>
			{/if}
			{if $LISTVIEW_BUTTONS.modify }
				<button class="client_listview_button small" title="批量修改" action="batch_modify">批量修改</button>
			{/if}
			{if $LISTVIEW_BUTTONS.today_appointment }
				<button class="client_listview_button small" title="今日预约" action="today_appointment">今日预约</button>
			{/if}
			{if $LISTVIEW_BUTTONS.tomorrow_appointment}
				<button class="client_listview_button small" title="明日预约" action="tomorrow_appointment">明日预约</button>
			{/if}
			{if $LISTVIEW_BUTTONS.after_tomorrow_appointment }
				<button class="client_listview_button small" title="后日预约" action="after_tomorrow_appointment">后日预约</button>
			{/if}
			{if $LISTVIEW_BUTTONS.total_show }
				<button class="client_listview_button small" title="全部" action="total_show">全部</button>
			{/if}
			</td>
			<td style="text-align:right;">
				显示:{$LISTVIEW_RECORD_START}-{$LISTVIEW_RECORD_END} 共计:{$LISTVIEW_RECORD_TOTAL}条 |
				<a href="javascript:zswitch_client_listview_page_ctrl(1,'client_listview_table_form');" title="跳转到第一页">首页</a> |
				<a href="javascript:zswitch_client_listview_page_ctrl({$LISTVIEW_RECORD_PAGE}-1,'client_listview_table_form');" title="跳转到上一页">上页</a> |
				<input name="current_page" style="width:30px;font-size:10px" value="{$LISTVIEW_RECORD_PAGE}"
					onchange="zswitch_client_listview_page_ctrl(this.value,'client_listview_table_form');"	/> /{$LISTVIEW_RECORD_PAGECOUNT} |
				<a href="javascript:zswitch_client_listview_page_ctrl({$LISTVIEW_RECORD_PAGE}+1,'client_listview_table_form');" title="跳转到下一页">下页<a/> |
				<a href="javascript:zswitch_client_listview_page_ctrl(99999999999,'client_listview_table_form');" title="跳转到最后一页">尾页</a> |
				过滤:
				<select name ="filterid" style="font-size:10px;"
					onchange="zswitch_load_client_view('index.php?module={$MODULE}&action={$ACTION}','client_listview_table_form')">
					{html_options options=$LISTVIEW_FILTER_LIST selected=$LISTVIEW_SELECTED_FILTER}
				</select> <a href="javascript:zswitch_listview_filter_modify_dlg('{$MODULE}');" title="编辑、修改过滤条件">编辑</a>
			</td>
		</tr>
	</table>
</div>
{/if}
<div style="margin-top:3px;">

<table id="client_listview_table" class="client_listview_table small" cellspacing="0" >
	<tr>
		{if $LISTVIEW_SELECTER_ALLOW}
			<th width="30px"><input id="select_record_all" type="checkbox" title="全选"/></th>
		{/if}
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
			{if $LISTVIEW_SELECTER_ALLOW}
				<td><input type="checkbox" name="selected_records[]" value="{$id}"/></td>
			{/if}
			{foreach $row as $fieldname => $field}
				<td>
				{if $field.have_associate}
					<a href="{$field.associate_to}" recordid="{$id}" >{$field.value}</a>
				{else}
					{$field.value}
				{/if}
				&nbsp;
				</td>
			{/foreach}
			{if $LISTVIEW_OPERATION_ALLOW}
				<td>
				{foreach $LISTVIEW_OPERATIONS as $oper}
					<a href="javascript:void(0);" recordid="{$id}" onclick="{$oper.url}" >{$oper.name}</a>	|
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
	var listModuleName = "{$MODULE}";
	{literal}
		zswitch_client_listview_table_init("client_listview_table");
		$("#select_record_all").click(function(){
			if($(this).is(":checked"))
			{
				$("[name^=selected_records]").prop("checked",true);
			}
			else
			{
				$("[name^=selected_records]").prop("checked",false);
			}
		});
		$("[name^=selected_records]").click(function(){
			if(!$(this).is(":checked"))
			{
				$("#select_record_all").prop("checked",false);
			}
			else
			{
				$("#select_record_all").prop("checked",true);
				$("[name^=selected_records]").each(function(){
					if(!$(this).is(":checked"))
					{
						$("#select_record_all").prop("checked",false);
					}
				});
			}
		});
		$(".client_listview_button").button().click(function(){
			var oper = $(this).attr("action");
			//alert(oper);
			if(oper == 'batch_delete')
			{
				zswitch_listview_batch_delete_dlg(listModuleName);
			}
			else if(oper == 'batch_modify')
			{
				zswitch_listview_batch_modify_dlg(listModuleName);
			}
			else if(oper == 'today_appointment')
			{
				var today=new Date();
				var st = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' 00:00:00';
				var et = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' 23:59:59';
				search_query_where = new Array();
				search_query_where[0] = new Array();
				search_query_where[0][0]='preset_time';
				search_query_where[0][1]='>=';
				search_query_where[0][2]=st;
				search_query_where[0][3]='and';
				search_query_where[0][4]='';
				search_query_where[1] = new Array();
				search_query_where[1][0]='preset_time';
				search_query_where[1][1]='<=';
				search_query_where[1][2]=et;
				search_query_where[1][3]='and';
				search_query_where[1][4]='';
				search_query_where[2] = new Array();
				search_query_where[2][0]='status';
				search_query_where[2][1]='!=';
				search_query_where[2][2]='FIRST_DIAL';
				search_query_where[2][3]='and';
				search_query_where[2][4]='';
				search_query_where[3] = new Array();
				search_query_where[3][0]='status';
				search_query_where[3][1]='!=';
				search_query_where[3][2]='FAILED';
				search_query_where[3][3]='and';
				search_query_where[3][4]='';
				search_query_where[4] = new Array();
				search_query_where[4][0]='status';
				search_query_where[4][1]='!=';
				search_query_where[4][2]='INVALID';
				search_query_where[4][3]='and';
				search_query_where[4][4]='';
				search_query_where[5] = new Array();
				search_query_where[5][0]='status';
				search_query_where[5][1]='!=';
				search_query_where[5][2]='SUCCESS';
				search_query_where[5][3]='';
				search_query_where[5][4]='';
				$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
				$("#client_listview_table_form").children("[name=record_page]").val("1");
				var url = "index.php?module=Accounts&action=index";
				zswitch_load_client_view(url,"client_listview_table_form");
			}
			else if(oper == 'tomorrow_appointment')
			{
				var today=new Date();
				var stamp = today.getTime() + 24*3600*1000;
				var tomorrow = new Date();
				tomorrow.setTime(stamp);
				var st = tomorrow.getFullYear()+'-'+(tomorrow.getMonth()+1)+'-'+tomorrow.getDate()+' 00:00:00';
				var et = tomorrow.getFullYear()+'-'+(tomorrow.getMonth()+1)+'-'+tomorrow.getDate()+' 23:59:59';
				search_query_where = new Array();
				search_query_where[0] = new Array();
				search_query_where[0][0]='preset_time';
				search_query_where[0][1]='>=';
				search_query_where[0][2]=st;
				search_query_where[0][3]='and';
				search_query_where[0][4]='';
				search_query_where[1] = new Array();
				search_query_where[1][0]='preset_time';
				search_query_where[1][1]='<=';
				search_query_where[1][2]=et;
				search_query_where[1][3]='and';
				search_query_where[1][4]='';
				search_query_where[2] = new Array();
				search_query_where[2][0]='status';
				search_query_where[2][1]='!=';
				search_query_where[2][2]='FIRST_DIAL';
				search_query_where[2][3]='and';
				search_query_where[2][4]='';
				search_query_where[3] = new Array();
				search_query_where[3][0]='status';
				search_query_where[3][1]='!=';
				search_query_where[3][2]='FAILED';
				search_query_where[3][3]='and';
				search_query_where[3][4]='';
				search_query_where[4] = new Array();
				search_query_where[4][0]='status';
				search_query_where[4][1]='!=';
				search_query_where[4][2]='INVALID';
				search_query_where[4][3]='and';
				search_query_where[4][4]='';
				search_query_where[5] = new Array();
				search_query_where[5][0]='status';
				search_query_where[5][1]='!=';
				search_query_where[5][2]='SUCCESS';
				search_query_where[5][3]='';
				search_query_where[5][4]='';

				$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
				$("#client_listview_table_form").children("[name=record_page]").val("1");
				var url = "index.php?module=Accounts&action=index";
				zswitch_load_client_view(url,"client_listview_table_form");
			}
			else if(oper == 'after_tomorrow_appointment')
			{
				var today=new Date();
				var stamp = today.getTime() + 48*3600*1000;
				var after_tomorrow = new Date();
				after_tomorrow.setTime(stamp);
				var st = after_tomorrow.getFullYear() + '-' + (after_tomorrow.getMonth() + 1) + '-' + after_tomorrow.getDate() + ' 00:00:00';
				var et = after_tomorrow.getFullYear() + '-' + (after_tomorrow.getMonth() + 1) + '-' + after_tomorrow.getDate() + ' 23:59:59';
				search_query_where = new Array();
				search_query_where[0] = new Array();
				search_query_where[0][0]='preset_time';
				search_query_where[0][1]='>=';
				search_query_where[0][2]=st;
				search_query_where[0][3]='and';
				search_query_where[0][4]='';
				search_query_where[1] = new Array();
				search_query_where[1][0]='preset_time';
				search_query_where[1][1]='<=';
				search_query_where[1][2]=et;
				search_query_where[1][3]='and';
				search_query_where[1][4]='';
				search_query_where[2] = new Array();
				search_query_where[2][0]='status';
				search_query_where[2][1]='!=';
				search_query_where[2][2]='FIRST_DIAL';
				search_query_where[2][3]='and';
				search_query_where[2][4]='';
				search_query_where[3] = new Array();
				search_query_where[3][0]='status';
				search_query_where[3][1]='!=';
				search_query_where[3][2]='FAILED';
				search_query_where[3][3]='and';
				search_query_where[3][4]='';
				search_query_where[4] = new Array();
				search_query_where[4][0]='status';
				search_query_where[4][1]='!=';
				search_query_where[4][2]='INVALID';
				search_query_where[4][3]='and';
				search_query_where[4][4]='';
				search_query_where[5] = new Array();
				search_query_where[5][0]='status';
				search_query_where[5][1]='!=';
				search_query_where[5][2]='SUCCESS';
				search_query_where[5][3]='';
				search_query_where[5][4]='';
				$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
				$("#client_listview_table_form").children("[name=record_page]").val("1");
				var url = "index.php?module=Accounts&action=index";
				zswitch_load_client_view(url,"client_listview_table_form");
			}
			else if(oper == 'total_show')
			{
				search_query_where = new Array();
				$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
				$("#client_listview_table_form").children("[name=record_page]").val("1");
				var url = "index.php?module=Accounts&action=index";
				zswitch_load_client_view(url,"client_listview_table_form");
			}
		});
	{/literal}
</script>

