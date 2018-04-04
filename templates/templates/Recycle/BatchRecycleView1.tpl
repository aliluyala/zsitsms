<form id="batch_recycle_dialog_form">
<input type="hidden" id="batch_recycle_select_recordid_list" name="select_recordid_list" value="-1"/>
<div style="margin-top:5px;">
<span style="margin-left:10px;line-height:20px;font-weight:bold;">
	<label title="选择记录删除条件">恢复条件：</label>
</span>
		  <select id="batch_recycle_query_where" name="recycle_query_where" >
			<option value="current_select">当前选择</option>
			<option value="current_search">当前搜索</option>
			<option value="all">全部记录</option>
		  </select>
</div>

<div style="margin-top:5px;">
<span style="margin-left:10px;line-height:20px;font-weight:bold;">
	<label title="删除符合上述条件的部分或全部记录">恢复范围：</label>
</span>
前<input type="text" name="recycle_count" value="0" style="width:50px;"/>条
<span style="width:50px">&nbsp;</span>
<input id="recycle_all_record" type="checkbox" name="recycle_all_record" value="YES" /><label for="recycle_all_record">全部</label>
</div>

</form>
<script type="text/javascript">
	$('#listview_bar_batch_recycle_dlg').data("step","1");
	$('input[name=recycle_all_record]').click(function(){
		if($(this).prop("checked"))
		{
			$('input[name=recycle_count]').prop('disabled',true);
		}
		else
		{
			$('input[name=recycle_count]').prop('disabled',false);
		}
	});
	$('input[name=recycle_all_record]').click();
</script>