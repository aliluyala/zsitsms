{* ***********************************************************************
*  UI类型值: 1
*  说明:字符串输入,不验证输入的有效性。如果是强制字段,将检查输入值是否为空
*
*********************************************************************** *}
<input type="text" name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" ui = "{$FIELDINFO.UI}"
	mandatory = "{$FIELDINFO.mandatory}" 	label = "{$FIELDINFO.label}" class="responsive_width_98" />