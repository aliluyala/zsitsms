

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">短链接短信</a></li>
    <li><a href="#tabs-0">常规短信</a></li>

  </ul>
  <div id="tabs-1">
       <form id="tools_send_sms_dlg_form_1">
		{* <input type="hidden" name = "woid" value="{$SMS_ID}"/> *}
        <input type="hidden" name = "caculate_data" value=""/> 
		<table class="client_detailview_table" cellspacing="0">	
	
		<tr>
			<td class="client_detailview_label_1">
				<label for="{$SMS_CALLEEID_FIELD.name}" title="{$SMS_CALLEEID_FIELD.title}">接收号码:</label>
			</td>
			<td class="client_detailview_value_1">{include file="UI/5.UI.tpl" FIELDINFO=$SMS_CALLEEID_FIELD}</td>
		</tr>
		<tr>
			<td class="client_detailview_label_1">
				<label for="{$SELF_DEFINE_CONTENT.name}" title="{$SELF_DEFINE_CONTENT.title}">自定义内容:</label>
			</td>
			<td class="client_detailview_value_1">{include file="UI/9.UI.tpl" FIELDINFO=$SELF_DEFINE_CONTENT}</td>
		</tr>

		<tr>
			<td class="client_detailview_label_1">
				<label>短短信内容:</label>
			</td>
			<td class="client_detailview_value_1">
				<input type = "text" name="content" id="short_sms_before" style="width:99%" value="">
			    <input type="hidden" name="short_sms_after" value="">
			</td>
		</tr>
		
		</table>
		</form> 
  </div>
  <div id="tabs-0">
      <form id="tools_send_sms_dlg_form_0">
		{* <input type="hidden" name = "woid" value="{$SMS_ID}"/> *}
		<table class="client_detailview_table" cellspacing="0">	
	
		<tr>
			<td class="client_detailview_label_1">
				<label for="{$SMS_CALLEEID_FIELD.name}" title="{$SMS_CALLEEID_FIELD.title}">接收号码:</label>
			</td>
			<td class="client_detailview_value_1">{include file="UI/5.UI.tpl" FIELDINFO=$SMS_CALLEEID_FIELD}</td>
		</tr>
		<tr>
			<td class="client_detailview_label_1">
				<label for="{$SMS_CONTENT_FIELD.name}" title="{$SMS_CONTENT_FIELD.title}">短信内容:</label>
			</td>
			<td class="client_detailview_value_1">{include file="UI/9.UI.tpl" FIELDINFO=$SMS_CONTENT_FIELD}</td>
		</tr>

		</table>
		</form>
  </div>
</div>

<script>
{literal}
	zswitch_ui_form_init("#tools_send_sms_dlg_form");
{/literal}

  $(function() {
    $( "#tabs" ).tabs();
  });
</script>
