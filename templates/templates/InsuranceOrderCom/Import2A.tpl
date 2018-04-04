<div id="import_dialog_title">
	<ul>
	<li>导入设置</li>
	</ul>
</div>
<form id="import_dialog_form_id_step2">
<input name = "dataFile" type="hidden" value="{$DATA_FILE}"/>
<div style="margin-top:5px;">
 <fieldset style="font-size:12px;border:1px solid #AAAAAA;height:100px;" >
 <legend style="text-align:left;font-weight:bold;">导入参数</legend>
  <table border="0" width="100%"  >
	<tr>
	<td width="50%" style="vertical-align:text-top;" >

	<div style="padding-left:20px;height:25px;line-height:25px;">
		<label for="firstline">第一行是标题：</label>
		<input type="checkbox" id="firstline" name="firstline" value="YES" checked="checked"/>
	</div>
	{if $FILE_TYPE_IS_EXECL}
		<div style="padding-left:20px;height:25px;line-height:25px;">
			导入“工作表”：
			<select id="worksheet" name="worksheet">
				{foreach $EXECL_SHEETS as $sheetname}
					<option value = "{$sheetname}">{$sheetname}</option>
				{/foreach}
			</select>
		</div>
	{/if}
	{if $FILE_TYPE_IS_TEXT}
	<div style="padding-left:20px;height:25px;line-height:25px;">
		选择列分隔符：<select id ="col_separator" name="col_separator">
			<option value = "comma"  {if $PRE_SEPARATOR eq "comma"}   selected="selected" {/if} >逗号</option>
			<option value = "tab"    {if $PRE_SEPARATOR eq "tab"}   selected="selected" {/if} >制表符</option>
			<option value = "space"  {if $PRE_SEPARATOR eq "space"}   selected="selected" {/if}  >空格</option>
			<option value = "semicolon" {if $PRE_SEPARATOR eq "semicolon"}   selected="selected" {/if} >分号</option>
			<option value = "vertical" {if $PRE_SEPARATOR eq "vertical"}   selected="selected" {/if} >竖线</option>
		</select>
	</div>
	{/if}
	</td>
	<td style="vertical-align:text-top;">
		<div style="padding-left:20px;height:25px;line-height:25px;">
			<label for="title_is_filed">标题匹配字段：</label>
			<input type="checkbox" id="title_is_filed" name="title_is_filed" value="YES" checked="checked"/>
		</div>
	</td>
	</tr>
  </table>
   </fieldset>
</div>

{if $FILE_TYPE_IS_EXECL}
<div id="import_dialog_data_brower" style="width:550px;height:170px;border-style:solid;border-width:1px;border-color:#79b7e7;margin-top:5px;overflow:auto;text-align:center;">
	{foreach $EXECL_DATAS as $shname=>$sheet}
		<table id="{$shname}" class="client_listview_table small" cellspacing="0" >
			<tr>
				<th>&nbsp;</th>
				{foreach $sheet.headers as $column}
					<th nowrap="nowrap" style="padding-left:5px;padding-right:5px; min-width:40px;">{$column}</th>
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
		$('#import_dialog_form_id_step2').find('#worksheet').change(function(){
			$('#import_dialog_data_brower').find('table').hide();
			$('#import_dialog_data_brower').find("[id='"+$(this).val()+"']").show();
		});
		$('#import_dialog_form_id_step2').find('#worksheet').change();
	</script>
</div>
{/if}
{if $FILE_TYPE_IS_TEXT}
	<textarea style="width:560px;height:170px;margin-top:5px" wrap="off">{$FILE_TEXT_CONTENT}</textarea>
{/if}

</form>
<script type="text/javascript">

	$('#import_dialog_form_id_step2').find('input[name=firstline]').click(function(){
		if($(this).is(":checked"))
		{
			$('#import_dialog_form_id_step2').find('input[name=title_is_filed]').prop("disabled",false);
		}
		else
		{
			$('#import_dialog_form_id_step2').find('input[name=title_is_filed]').prop("disabled",true);
		}

	});

	$('#import_dialog_form_id_step2').find('#check_repeat_record').click(function(){
		if($(this).is(":checked"))
		{
			$('#import_dialog_form_id_step2').find('#check_repeat_record_field').prop("disabled",false);
		}
		else
		{
			$('#import_dialog_form_id_step2').find('#check_repeat_record_field').prop("disabled",true);
		}

	});

</script>
