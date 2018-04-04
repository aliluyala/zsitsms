	<table border="0" cellspacing="0"  style="text-align:center">
	<tr><td style="font-weight:bold">搜索:</td>
		<td><input id="serach_input" type="text" value="{$SEARCH_VALUE}"/>

		</td>
		<td><a href="javascript:void(0)" 
				onclick="load_associate_selecter('{$MODULE}','{$SHOW_FIELD}','search',encodeURI($(this).parents('table').first().find('#serach_input').val()),0,'{$SEARCHDLGID}','{$LIST_FILTER_FIELD}','{$LIST_FILTER_VALUE}','{$LIST_FIELDS}');" title="搜索">
				<img src="{$IMAGES}/search_lense.png" style="width:16px; height:16px;border:none"/>
			</a>
		</td>
	</tr>
	</table>
	<table  cellspacing="0"  style="width:100%;text-align:right;border-style:solid none none none;	border-width:1px;border-color:#ACACAC;">
	<tr>
		<td >
			<a href="javascript:void(0)" style="color:#1E90FF;font-size:12px" title="向前翻一页" 
			onclick="load_associate_selecter('{$MODULE}','{$SHOW_FIELD}','back',encodeURI($(this).parents('table').first().prev().find('#serach_input').val()),{$RECORD_START},'{$SEARCHDLGID}','{$LIST_FILTER_FIELD}','{$LIST_FILTER_VALUE}','{$LIST_FIELDS}');" 
			>前一页</a>  | 
			<a href="javascript:void(0)" style="color:#1E90FF;font-size:12px" title="向后翻一页"
			onclick="load_associate_selecter('{$MODULE}','{$SHOW_FIELD}','next',encodeURI($(this).parents('table').first().prev().find('#serach_input').val()),{$RECORD_START},'{$SEARCHDLGID}','{$LIST_FILTER_FIELD}','{$LIST_FILTER_VALUE}','{$LIST_FIELDS}');"
			>后一页</a> 
		</td>
	</tr>
	</table>
	<table id="select_list" class="client_listview_table small" cellspacing="0" style="text-align:center">
		<tr><th style="width:30px">&nbsp;</th>
			{foreach $LIST_HEADERS as $field => $field_label}
				<th >{$field_label}</th>
			{/foreach}
		</tr>
		
		{foreach $ASSOCIATE_LIST as $id=>$row}
			<tr>
				<td>					
					<input type="radio" name="associate_select_radio" value="{$id}" show_value="{$row[$SHOW_FIELD]['value']}"/>
				</td>
				{foreach $row as $field => $field_label}
					<td>
						{$row[$field]['value']}&nbsp;
					</td>
				{/foreach}
			</tr>
		{/foreach}
	</table>