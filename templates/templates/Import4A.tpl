<div id="import_dialog_title">
	<ul>
	<li>设置字段默认值</li>
	</ul>
</div>

<div id="import_dialog_info" style="font-size:12px;">
	<ul>
		{if $HAVE_DEFAULT_FIELD}
			<li>以下字段的值不能为空，请为它们设置默认值。</li>
		{else}
			<li>没有字段需要设置默认值。</li>
		{/if}
	</ul>
</div>



<form id="import_dialog_form_id_step4">
<input type="hidden" name="import_option_dataFile" value="{$DATA_FILE}"/>
<input type="hidden" name="import_option_firstline" value="{$FIRSTLINE}"/>
<input type="hidden" name="import_option_worksheet" value="{$WORKSHEET}"/>
<input type="hidden" name="import_option_col_separator" value="{$SEPARATOR}"/>
<input type="hidden" name="import_option_colToField" value="{$COL_TO_FIELD}"/>
<input type="hidden" name="import_option_check_repeat_field" value="{$CHECK_REPEAT_FIELD}"/>
<input type="hidden" name="repeat_record_handle" value="{$REPEAT_RECORD_HANDLE}"/>

{if $HAVE_DEFAULT_FIELD}
<table class="client_detailview_table" cellspacing="0">	
	{foreach $EDITVIEW_DATAS.datas as $row}
		<tr>
			{foreach $row as $field}
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
{/if}
</form>
<script type="text/javascript">
	zswitch_ui_form_init("#import_dialog_form_id_step4");
</script>	
