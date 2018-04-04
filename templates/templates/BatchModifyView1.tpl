<form id="batch_modify_dialog_form">
<input type="hidden" id="batch_modify_select_recordid_list" name="select_recordid_list" value="-1"/>
<div style="margin:5px 0px 5px 0px;border-bottom:1px solid #1E90FF;">
<span style="margin-left:10px;line-height:20px;font-weight:bold;">
	<label title="选择记录修改条件">修改条件：</label>
</span>
		  <select id="batch_modify_query_where" name="modify_query_where" >
			<option value="current_select">当前选择</option>
			<option value="current_search">当前搜索</option>
			<option value="all">全部记录</option>
		  </select>		
<span style="margin-left:10px;line-height:20px;font-weight:bold;">

</span>
前<input type="text" name="modify_count" value="0" style="width:50px;"/>条
<span style="width:50px">&nbsp;</span>	
<input id="modify_all_record" type="checkbox" name="modify_all_record" value="YES" /><label for="modify_all_record">全部</label> 
</div>

<table class="client_detailview_table" cellspacing="0">	
	{foreach $EDITVIEW_DATAS.datas as $row}
		<tr>
			{foreach $row as $field}
				<td style="border-bottom:1px solid #DCDCDC;width:20px;">
					<input type="checkbox" name="modify_fileds[]" value="{$field.name}" checked="checked"/>
				</td>
				<td class="client_detailview_label_{$EDITVIEW_DATAS.cols}" >
					<label for="{$field.name}" title="{$field.title}">{$field.label}</label>
					{if $field.mandatory}<span style="color:red">*</span>{/if}
				</td>
				<td class="client_detailview_value_{$EDITVIEW_DATAS.cols}">
					{if $field.edit}
						{include file="UI/{$field.UI}.UI.tpl" FIELDINFO=$field}
					{else}
						{$field.value}
					{/if}						
				</td>
			{/foreach}
		</tr>
	{/foreach}
</table>					



</form>
<script type="text/javascript">
	zswitch_ui_form_init("#batch_modify_dialog_form");
	$('#listview_bar_batch_modify_dlg').data("step","1");
	$('input[name=modify_all_record]').click(function(){
		if($(this).prop("checked"))
		{
			$('input[name=modify_count]').prop('disabled',true);			
		}
		else
		{
			$('input[name=modify_count]').prop('disabled',false);			
		}
	});
	$('input[name=modify_all_record]').click();
	$('#batch_modify_dialog_form').find('[name^=modify_fileds]').click(function(){
		if($(this).prop("checked"))
		{
			$(this).parent().next().next().find("input,select,a,button").prop('disabled',false);
		}
		else
		{
			$(this).parent().next().next().find("input,select,a,button").prop('disabled',true);
		}
	});
	$('#batch_modify_dialog_form').find('[name^=modify_fileds]').click();
</script>