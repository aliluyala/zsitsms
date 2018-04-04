<form id ="perssion_setting_share">
<table  width="100%" class="client_listview_table small" cellspacing="0">
	<tr>
		<th style="text-align:center;">字段名</th>
		<th style="text-align:center;">查看</th>
		<th style="text-align:center;">隐藏</th>
		<th style="text-align:center;">隐藏开始</th>
		<th style="text-align:center;">隐藏长度</th>
		<th style="text-align:center;">修改</th>
	</tr>
	{foreach  $SHARE_FIELDS as $field_name => $field_info }
		<tr>
			<td style="text-align:center;">
				{$field_info.label}
			</td>
			<td style="text-align:center;">
				<input type="checkbox" name="field_show_{$field_name}"
					{if $field_info.show}checked="checked"{/if} value="YES"/>
			</td>
			<td style="text-align:center;">	
				<input type="checkbox" name="field_hidden_{$field_name}"	
					{if $field_info.hidden}checked="checked"{/if}  {if not $field_info.show}disabled="disabled"{/if}  value="YES"/>
			</td>
			<td style="text-align:center;">	
				<input type="text" name="field_hidden_start_{$field_name}"	
							{if not $field_info.hidden}disabled="disabled"{/if}															
							value="{$field_info.hidden_start}" style="width:30px"/>
			</td>
			<td style="text-align:center;">	
				<input type="text" name="field_hidden_end_{$field_name}" 
							{if not $field_info.hidden}disabled="disabled"{/if}
							value="{$field_info.hidden_end}" style="width:30px"/>
			</td>
			<td style="text-align:center;">	
				<input type="checkbox" name="field_modify_{$field_name}"
					{if (not $field_info.show) or $field_info.hidden }disabled="disabled"{/if}
					{if $field_info.modify}checked="checked"{/if} value="YES"/>											
			</td>
		</tr>
	{/foreach}
</table>
</form>
<script>

	$("#perssion_setting_share").find("input[name^=field_show_]").click(function(){
		var field_name = $(this).attr("name").substr(11);

		if($(this).is(":checked"))
		{
			$(this).parent().parent().find("[name=field_hidden_"+field_name+"]").prop("disabled",false);
			if($(this).parent().parent().find("[name=field_hidden_"+field_name+"]").is(":checked"))
			{
				$(this).parent().parent().find("[name=field_hidden_end_"+field_name+"]").prop("disabled",false);
				$(this).parent().parent().find("[name=field_hidden_start_"+field_name+"]").prop("disabled",false);	
				$(this).parent().parent().find("[name=field_modify_"+field_name+"]").prop("disabled",true);	
			}
			else
			{
				$(this).parent().parent().find("[name=field_hidden_end_"+field_name+"]").prop("disabled",true);
				$(this).parent().parent().find("[name=field_hidden_start_"+field_name+"]").prop("disabled",true);	
				$(this).parent().parent().find("[name=field_modify_"+field_name+"]").prop("disabled",false);
			}	
		}
		else
		{
			$(this).parent().parent().find("[name=field_hidden_"+field_name+"]").prop("disabled",true);
			$(this).parent().parent().find("[name=field_hidden_end_"+field_name+"]").prop("disabled",true);
			$(this).parent().parent().find("[name=field_hidden_start_"+field_name+"]").prop("disabled",true);					
			$(this).parent().parent().find("[name=field_modify_"+field_name+"]").prop("disabled",true);			
		}
	});

	$("#perssion_setting_share").find("input:checkbox[name^=field_hidden_]").click(function(){
		var field_name = $(this).attr("name").substr(13);
		if($(this).is(":checked"))
		{
			$(this).parent().parent().find("[name=field_hidden_end_"+field_name+"]").prop("disabled",false);
			$(this).parent().parent().find("[name=field_hidden_start_"+field_name+"]").prop("disabled",false);
			$(this).parent().parent().find("[name=field_modify_"+field_name+"]").prop("disabled",true);				
		}
		else
		{
			$(this).parent().parent().find("[name=field_hidden_end_"+field_name+"]").prop("disabled",true);
			$(this).parent().parent().find("[name=field_hidden_start_"+field_name+"]").prop("disabled",true);
			$(this).parent().parent().find("[name=field_modify_"+field_name+"]").prop("disabled",false);							
		}
	});
</script>
