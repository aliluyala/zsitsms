{* ***********************************************************************
*  UI类型值: 60
*  说明: 电话号输入,只接受数字 。如果是强制字段,将检查输入值是否为空。
*        在列表、详情视图将点击拨号
*********************************************************************** *}
<input type="text" name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" ui = "{$FIELDINFO.UI}"
	mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}" class="responsive_width_98" />