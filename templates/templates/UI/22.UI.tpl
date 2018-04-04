{* *********************************************************************** 
*  UI类型值: 22
*  说明: 下拉单选。在列表,详细视图中显法图标。如果是强制字段,将检查输入值是否为空。
*  
*********************************************************************** *}

<select name="{$FIELDINFO.name}" ui = "{$FIELDINFO.UI}" mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}"  id="{$FIELDINFO.name}" >
{html_options options=$FIELDINFO.options selected=$FIELDINFO.value}
</select>
