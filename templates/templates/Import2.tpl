<div id="import_dialog_title">
	<ul>
	<li>第二步 设置导入参数</li>
	</ul>
</div>
<form id="import_dialog_form">

<div style="margin-top:5px;font-weight:bold;">
	<div><label for="firstline">第一行是标题</label><input type="checkbox" id="firstline" name="firstline" value="YES" checked="checked"/><span></span>
	     <label for="title_is_filed">标题匹配字段</label><input type="checkbox" id="title_is_filed" name="title_is_filed" value="YES" checked="checked"/></div>
	{if $FILE_TYPE_IS_EXECL}
		<div>
			请选择从哪个“工作表”导入：
			<select id="worksheet" name="worksheet">
				{foreach $EXECL_SHEETS as $sheetname}
					<option value = "{$sheetname}">{$sheetname}</option>
				{/foreach}
			</select>	
		</div>
	{/if}
	{if $FILE_TYPE_IS_TEXT}
	<div>
		列分隔符：<label for="separator_space">空格</label><input type="radio" id="separator_space" name="separator" value=" "/> | 
		<label for="separator_comma">逗号</label><input type="radio" id="separator_comma" name="separator" value="," checked="checked"/> | 
		<label for="separator_ semicolon">分号</label><input type="radio" id="separator_semicolon" name="separator" value=","/> | 
		<label for="separator_tab">制表符</label><input type="radio" id="separator_tab" name="separator" value="	"/> | 
		<label for="separator_vertical">竖线</label><input type="radio" id="separator_vertical" name="separator" value="|"/> 	 	
	</div>
	{/if}
</div>

{if $FILE_TYPE_IS_EXECL}
<div id="import_dialog_data_brower" style="width:550px;height:240px;border-style:solid;border-width:1px;border-color:#79b7e7;margin-top:5px;overflow:auto;text-align:center;">	
	{foreach $EXECL_DATAS as $shname=>$sheet}
		<table id="{$shname}" class="client_listview_table small" cellspacing="0" >
			<tr>
				<th>&nbsp;</th>
				{foreach $sheet.headers as $column}
					<th nowrap="nowrap" style="padding-left:5px;padding-right:5px;">{$column}</th>
				{/foreach}				
			</tr>
			{foreach $sheet.datas as $row_num => $data_row}
				<tr>
					<td>{$row_num}</td>
					{foreach $data_row as $value}
						<td>{$value}&nbsp;</td>
					{/foreach}
				</tr>
			{/foreach}
		</table>
	{/foreach}
	<script type="text/javascript">
		$('#import_dialog_form').find('#worksheet').change(function(){
			$('#import_dialog_data_brower').find('table').hide();
			$('#import_dialog_data_brower').find('#'+$(this).val()).show();
		});
		$('#import_dialog_form').find('#worksheet').change();
	</script>
</div>
{/if}
{if $FILE_TYPE_IS_TEXT}
	<textarea style="width:560px;height:250px" wrap="off">{$FILE_TEXT_CONTENT}</textarea>
{/if}

</form>
<script type="text/javascript">
	$('#titlebar_import_data_dlg').data("step","2");
	$('#import_dialog_form').find('input[name=firstline]').click(function(){
		if($(this).is(":checked"))
		{
			$('#import_dialog_form').find('input[name=title_is_filed]').prop("disabled",false);
		}
		else
		{
			$('#import_dialog_form').find('input[name=title_is_filed]').prop("disabled",true);
		}
	
	});
	
</script>
