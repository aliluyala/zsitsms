<form id="batch_delete_dialog_form">
<input type="hidden" id="batch_delete_select_recordid_list" name="select_recordid_list" value="-1"/>
<div style="margin-top:5px;">
<span style="margin-left:10px;line-height:20px;font-weight:bold;">
	<label title="选择记录删除条件">删除条件：</label>
</span>
		  <select id="batch_delete_query_where" name="delete_query_where" >
			<option value="current_select">当前选择</option>
			<option value="current_search">当前搜索</option>
			<option value="all">全部记录</option>
		  </select>
</div>

<div style="margin-top:5px;">
<span style="margin-left:10px;line-height:20px;font-weight:bold;">
	<label title="删除符合上述条件的部分或全部记录">删除范围：</label>
</span>
前<input type="text" name="delete_count" value="0" style="width:50px;"/>条
<span style="width:50px">&nbsp;</span>
<input id="delete_all_record" type="checkbox" name="delete_all_record" value="YES" /><label for="delete_all_record">全部</label>
</div>

</form>
<script type="text/javascript">
	$('#listview_bar_batch_delete_dlg').data("step","1");
	$('input[name=delete_all_record]').click(function(){
		if($(this).prop("checked"))
		{
			$('input[name=delete_count]').prop('disabled',true);
		}
		else
		{
			$('input[name=delete_count]').prop('disabled',false);
		}
	});
	$('input[name=delete_all_record]').click();
</script>