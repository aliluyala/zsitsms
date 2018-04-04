
<form id="form_missedit_view">
	<input type="hidden" name="recordid" value="{$RECORDID}"/>
	<input type="hidden" name="operation" value="edit"/>
	<div  style="font-size:12px">
		<table class="client_detailview_table" cellspacing="0">	
			<tr>
				<td class="client_detailview_label_1">
					<label for="{$FIELD.name}" title="{$FIELD.title}">{$FIELD.label}</label>
					{if $FIELD.mandatory}<span style="color:red">*</span>{/if}
				</td>
				<td class="client_detailview_value_1">
					{if $FIELD.edit}
						{include file="UI/{$FIELD.UI}.UI.tpl" FIELDINFO=$FIELD}
					{else}
						{$FIELD.value}
					{/if}					
				</td>
			</tr>
		</table>
	</div>	
</form>

<script type="text/javascript">
	zswitch_ui_form_init("#form_missedit_view");
	var ch = $("#form_missedit_view").height();
	var dh = $("#detailview_missedit_dlg").dialog( "option", "height" );
	if(ch+100>dh) dh = ch+100;
	if(dh>500) dh = 500;
	$("#detailview_missedit_dlg").dialog( "option", "height" ,dh);
</script>