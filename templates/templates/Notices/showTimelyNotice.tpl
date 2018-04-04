
	
	{foreach $LIST_DATA as $id => $row}
		<table id="client_listview_table" class="client_listview_table small" cellspacing="0" style="margin-bottom:15px;">
		<tr><td style="background:#F5FFFA;width:80px;"><span style="font-weight:bold;">来自:</span></td><td>{$row['user_create']['value']} </td></tr>
		<tr><td style="background:#F5FFFA;width:80px;"><span style="font-weight:bold;">标题:</span></td><td>{$row['title']['value']} </td></tr>
		<tr><td style="background:#F5FFFA;width:80px;"><span style="font-weight:bold;">内容:</span></td><td>{$row['contant']['value']}</td></tr>
		</table>
	{/foreach}	

