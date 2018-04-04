<form id ="add_product_view_form">
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
</form>						
<script>
	zswitch_ui_form_init("#add_product_view_form");
	
	

</script>