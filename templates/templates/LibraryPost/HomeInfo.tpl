<table id="client_listview_table" class="client_listview_table small" cellspacing="0" >
	{foreach $HEADERS as $name => $col }
		<th>
			{$col.label}			
		</th>
	{/foreach}
	
	{foreach $LIST_DATA as $id => $row}
		<tr>
			{foreach $row as $fieldname => $field}
				<td>
				{if $field.have_associate}
					<a href="{$field.associate_to}" recordid="{$id}">{$field.value}</a>
				{else}
					{$field.value}
				{/if}
				&nbsp; 
				</td>
			{/foreach}
		</tr>
	{/foreach}	
</table>