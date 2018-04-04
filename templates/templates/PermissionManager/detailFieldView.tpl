
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
				{if $field_info.show}
					<img src="{$IMAGES}/check-64.png" width="12" height="12"/> 
				{else}
					<img src="{$IMAGES}/delete_2.png" width="12" height="12"/> 	
				{/if}
			</td>
			<td style="text-align:center;">	
				{if $field_info.hidden}
					<img src="{$IMAGES}/check-64.png" width="12" height="12"/> 
				{else}
					<img src="{$IMAGES}/delete_2.png" width="12" height="12"/> 	
				{/if}
			</td>
			<td style="text-align:center;">													
				{if  $field_info.hidden}{$field_info.hidden_start}{/if}	&nbsp; 		
			</td>
			<td style="text-align:center;">	
				{if $field_info.hidden}{$field_info.hidden_end}{/if}&nbsp; 
			</td>
			<td style="text-align:center;">	
				{if $field_info.show}
					{if $field_info.modify}
						<img src="{$IMAGES}/check-64.png" width="12" height="12"/> 
					{else}
						<img src="{$IMAGES}/delete_2.png" width="12" height="12"/> 	
					{/if}
				{/if}										
			</td>
		</tr>
	{/foreach}
</table>

