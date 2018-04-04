<div id="import_dialog_title">
	<ul>
	<li>第三步 选择导入字段</li>
	</ul>
</div>

<div id="import_dialog_info">
	<ul>
		<li>请在下表中确定要导入的数据列，并选择数据列对应的字段名。</li>
	</ul>
</div>

<form id="import_dialog_form">
<input type="hidden" name="firstline" value="{$FIRSTLINE}"/>
<input type="hidden" name="worksheet" value="{$WORKSHEET}"/>
<input type="hidden" name="separator" value="{$SEPARATOR}"/>
<div id="import_dialog_data_brower" style="width:550px;height:240px;border-style:solid;border-width:1px;border-color:#79b7e7;margin-top:5px;overflow:auto;text-align:center;">	
	<table  class="client_listview_table small" cellspacing="0" >
		<tr>
			{for $col = 0 to $DATA_COLS-1}
				<th><input type="checkbox" name="import_cols[]" value="{$col}"
					{if $COL_INFOS[$col].field} checked="checked" {/if}
				/></th>	
			{/for}	
		</tr>
		<tr>
			{for $col = 0 to $DATA_COLS-1}
				<th>
					<select name = "col_field_name_{$col}"  
						{if not $COL_INFOS[$col].field} disabled="disabled" {/if}
					>
						{html_options options=$FIELD_LIST selected=$COL_INFOS[$col].field}
					</select>
				</th>
			{/for}		
		</tr>
		{foreach $DATAS as $row}
			<tr>
				{foreach $row as $cell}
					<td>{$cell}&nbsp;</td>
				{/foreach}
			</tr>
		{/foreach}
	</table>

</div>

</form>
<script type="text/javascript">
	$("#titlebar_import_data_dlg").data("step","3");	
	$("#import_dialog_data_brower").find(":checkbox[name*=import_cols]").change(function(){
		if($(this).is(":checked"))
		{
			$("select[name=col_field_name_"+$(this).val()+"]").prop("disabled",false);
		}
		else
		{
			$("select[name=col_field_name_"+$(this).val()+"]").prop("disabled",true);
		}
	});
</script>
