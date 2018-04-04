<form id="form_entry_view">
<table class="client_detailview_table" cellspacing="0">	
	{foreach $EDITVIEW_DATAS.datas as $row}		
			{foreach $row as $field}
				
				{if $field.UI == 101}
					{include file="UI/{$field.UI}.UI.tpl" FIELDINFO=$field}
				{else}
				<tr>
					<td class="client_detailview_label_1" >
						<label for="{$field.name}" title="{$field.title}">{$field.label}</label>
						{if $field.mandatory}<span style="color:red">*</span>{/if}
					</td>
					<td class="client_detailview_value_1">
						{if $field.edit}
							{include file="UI/{$field.UI}.UI.tpl" FIELDINFO=$field}
						{else}
							{$field.value}
						{/if}
						
					</td>
				</tr>	
				{/if}
			{/foreach}
		
	{/foreach}
</table>					
</form>
<script type="text/javascript">
	zswitch_ui_form_init("#form_entry_view");
	{literal}
 
	{/literal}
</script>