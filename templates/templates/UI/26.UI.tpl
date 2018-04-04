{* *********************************************************************** 
*  UI类型值: 26
*  说明: 下拉单选,选项根据关联字段值动态变化,选项值通过数据库设置,可动态设置。如果是强制字段,将检查输入值是否为空。
*  
*********************************************************************** *}

<select name="{$FIELDINFO.name}" ui = "{$FIELDINFO.UI}" mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}" 
	picklist_group_field = "{$FIELDINFO.picklist_group_field}" picklist_table_name="{$FIELDINFO.picklist_table_name}"
	picklist_items_field = "{$FIELDINFO.picklist_items_field}" 	picklist_filter_field = {$FIELDINFO.picklist_filter_field}
	id="{$FIELDINFO.name}" picklist_value="{$FIELDINFO.value}">
</select>
