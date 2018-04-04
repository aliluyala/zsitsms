<form id="detailview_account_appointment_add_dlg_form">
<input type="hidden" name="recordid" value="{$RECORDID}"/>
<input type="hidden" name = "accountid" value="{$ACCOUNTID}"/>
<input type="hidden" name = "state" value="Waiting"/>
<input type="hidden" name="operation" value="create"/>
<table class="client_detailview_table" cellspacing="0">	
<tr>
	<td class="client_detailview_label_1">
		<label for="{$APPOINTMENT_TIME_FIELD.name}" title="{$APPOINTMENT_TIME_FIELD.title}">预约时间</label>
	</td>
	<td class="client_detailview_value_1">{include file="UI/31.UI.tpl" FIELDINFO=$APPOINTMENT_TIME_FIELD}</td>
</tr>
<tr>
	<td class="client_detailview_label_1">
		<label for="{$REMARK_FIELD.name}" title="{$REMARK_FIELD.title}">备注</label>
	</td>
	<td class="client_detailview_value_1">{include file="UI/5.UI.tpl" FIELDINFO=$REMARK_FIELD}</td>
</tr>

</table>
</form>

<script>
{literal}
	zswitch_ui_form_init("#detailview_account_appointment_add_dlg_form");
{/literal}	
</script>