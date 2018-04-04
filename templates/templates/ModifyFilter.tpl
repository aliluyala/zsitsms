<div id="import_dialog_info" style="font-size:12px;">
	<span style="margin-left:10px;line-height:20px;font-weight:bold;">
		<label title="选择要辑编的过滤视图">过滤视图：</label>
	</span>
	<select id="listview_filter_where" name="filter_where" module="{$MODULE}">
		{foreach $FILTER_WHERE_LIST as $id => $filter}
			<option value="{$id}" {if $CURRENT_FILTER_ID eq $id}selected="selected"{/if}>{$filter}</option>
		{/foreach}
	</select>	
	<span style="width:50px">&nbsp;</span>	
	<button title="删除过滤视图" class="listview_filter_modify_button" action="delete" module="{$MODULE}">删除</button>
	<span style="width:50px">&nbsp;</span>
	<button title="把当前搜索条件增加为过滤视图" class="listview_filter_modify_button" action="add" module="{$MODULE}">增加</button>	
</div>
<div id="import_dialog_info" style="font-size:12px;">
	<span style="margin-left:10px;line-height:20px;font-weight:bold;">
		<label title="过滤视图显示的名称" for="listview_modify_filter_name">过滤名称：</label>
	</span>
	<input type="text" id="listview_modify_filter_name" name="filter_name" value="{$FILTER_NAME}"/>
	<span style="width:50px">&nbsp;</span>
	<button title="保存修改" class="listview_filter_modify_button" action="save" module="{$MODULE}">保存</button>	
</div>	

<table id="LoginLog_search_condition_list" class="client_listview_table small" cellspacing="0" style="text-align:center">
	<tr>
		<th>字段</th><th>条件</th><th>值</th><th>联接</th>
		{foreach $FILTER_WHERE as $where}
			<tr>
				<td>{$where[0]}</td>
				<td>{$where[1]}</td>
				<td>{$where[2]}</td>
				<td>{$where[3]}</td>
			</tr>
		{/foreach}
	</tr>
</table>
<script type="text/javascript">
{literal}
	$(".listview_filter_modify_button").button().click(function(){
		var module = $(this).attr("module");
		var oper = $(this).attr("action");
		var url = "index.php?module="+module+"&action=modifyFilter&oper="+oper;
		if(oper == "delete")
		{
			url += "&filterid="+$("#listview_filter_where").val();
		}
		else if(oper == "save")
		{
			url += "&filterid="+$("#listview_filter_where").val();
			url += "&name="+$("#listview_modify_filter_name").val();
		}
		zswitch_show_progressbar($('#listview_filter_modify_dlg'),"modify_filter_progress");
		$('#listview_filter_modify_dlg').load(encodeURI(url));
	});
	$("#listview_filter_where").change(function(){
		var module = $(this).attr("module");
		var url = "index.php?module="+module+"&action=modifyFilter&filterid="+$(this).val();
		zswitch_show_progressbar($('#listview_filter_modify_dlg'),"modify_filter_progress");
		$('#listview_filter_modify_dlg').load(url);
	});
	
{/literal}
</script>
