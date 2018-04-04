{* ***********************************************************************
*  UI类型值: 6
*  说明: 数值输入,验证输入值范围 。如果是强制字段,将检查输入值是否为空。
*
*********************************************************************** *}
<input type="text" name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" ui = "{$FIELDINFO.UI}"
	mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}"  min="{$FIELDINFO.min}"  max="{$FIELDINFO.max}" class="responsive_width_98" />