{* *********************************************************************** 
*  UI类型值: 25
*  说明: 下拉单选,选择项分组并根据关联字段动态变化。如果是强制字段,将检查输入值是否为空。
*  
*********************************************************************** *}

<select name="{$FIELDINFO.name}" ui = "{$FIELDINFO.UI}" mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}" 
	picklist_group_field = "{$FIELDINFO.picklist_group_field}"    id = "{$FIELDINFO.name}" >
	
</select>
<div id="picklist_groups_box" style="display:none">
{foreach $FIELDINFO.options as $group_name => $group}
	<div id="{$group_name}">
		{html_options options=$group selected=$FIELDINFO.value}
	</div>	
{/foreach}
</div>