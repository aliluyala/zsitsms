<div id="import_dialog_title">
	<ul>
		<li>导出设置</li>
	</ul>
</div>
<div id="import_dialog_info">
	<ul>
		<li>此导出功能可以导出EXECL文档或文本文件。</li>
		<li>文本文件的编码格式是“UTF-8”，你可以使用“Notepad++”，“记事本”等软件进行编码转换。</li>
		<li>你可以选择只导出部分字段，并可选择只导出符合条件的记录。</li>
	</ul>
</div>
<form id="export_dialog_form">
<div style="margin-left:10px;line-height:20px;font-weight:bold;">选择要导出的字段：</div>
<div id="export_dialog_data_brower" style="width:570px;height:75px;border-style:solid;border-width:1px;border-color:#79b7e7;overflow:auto;text-align:center;">
	<table  class="client_listview_table small" cellspacing="0" >
		<tr>
			{foreach $FIELD_LIST as $field=>$field_name}
				<td><input type="checkbox" name="export_fields[]" value="{$field}" checked="checked"/></td>
			{/foreach}
		</tr>
		<tr>
			{foreach $FIELD_LIST as $field=>$field_name}
				<td nowrap="nowrap" style="padding-left:5px;padding-right:5px;">{$field_name}</td>
			{/foreach}
		</tr>
	</table>
</div>
<div style="margin-top:5px;">
<span style="margin-left:10px;line-height:20px;font-weight:bold;">
	查询条件：
</span>
		  <select name="export_query_where" >
			<option value="current_search">当前搜索</option>
			<option value="all">全部记录</option>
		  </select>
</div>
<div style="margin-top:5px;">
<label>
<span style="margin-left:10px;line-height:20px;font-weight:bold;">
	定制表头：
</span>
	<input type="checkbox" name="is_distribution" value="YES" />
</label>
</div>
<div style="margin-top:5px;">
<span style="margin-left:10px;line-height:20px;font-weight:bold;">
<label title="如果以Excel工作薄导出，一次导出行数限制为65536。">导出范围：</label>
</span>
从<input type="text" name="export_start_count" style="width:50px;"/>到<input type="text" name="export_end_count" style="width:50px;"/>
<span style="width:50px">&nbsp;</span>
<input id="export_all_record" type="checkbox" name="export_all_record" value="YES" /><label for="export_all_record">全部</label>
</div>

<div style="margin-top:5px;">
<span style="margin-left:10px;line-height:20px;font-weight:bold;">
导出格式：
</span>
<select name="export_file_format">
	<option value="csv">文本文件(.csv)</option>
	<option value="xlsx">Excel 工作薄(.xlsx)</option>
	<option value="xls">Excel 97-2003工作薄(.xls)</option>
</select>

</form>
<script type="text/javascript">
	var pre_distribution = "{$PRE_DISTRIBUTION}".split(",");
	{literal}
		$('#titlebar_export_data_dlg').data("step","1");
		$('input[name=export_all_record]').click(function(){
			if($(this).prop("checked"))
			{
				$('input[name=export_start_count]').prop('disabled',true);
				$('input[name=export_end_count]').prop('disabled',true);
			}
			else
			{
				$('input[name=export_start_count]').prop('disabled',false);
				$('input[name=export_end_count]').prop('disabled',false);
			}
		});
		$('input[name=export_all_record]').click();
		$("input[name=is_distribution]").click(function(){
			if($(this).prop("checked"))
			{
				$("#export_dialog_data_brower").find("input[type=checkbox]").each(function(index, data){
					if($.inArray(data.value, pre_distribution) != -1){
						$(data).prop("checked", true);
					}else{
						$(data).prop("checked", false);
					}
				});
			}
			else
			{
				$("#export_dialog_data_brower").find("input[type=checkbox]").prop("checked", true);
			}
		});
	{/literal}
</script>
