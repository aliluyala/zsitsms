{* ***********************************************************************
*  UI类型值: 5
*  说明: 字符串输入,输入框宽度自动调节。如果是强制字段,将检查输入值是否为空。
*
*********************************************************************** *}
<input type="text" name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" ui = "{$FIELDINFO.UI}"
	mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}" class="responsive_width_98" />