{* ***********************************************************************
*  UI类型值: 4
*  说明: 字符串输入,只接受英文字符,验证输入的长度(最小,最大) 。如果是强制字段,将检查输入值是否为空。
*
*********************************************************************** *}
<input type="text" name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" ui = "{$FIELDINFO.UI}"
	mandatory = "{$FIELDINFO.mandatory}" label = "$FIELDINFO.label"  min_len="$FIELDINFO.min_val"  max_len="$FIELDINFO.max_len" class="responsive_width_98" />