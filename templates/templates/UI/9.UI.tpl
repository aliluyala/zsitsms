{* ***********************************************************************
*  UI类型值: 9
*  说明: 文本输入。如果是强制字段,将检查输入值是否为空。
*
*********************************************************************** *}
<textarea name="{$FIELDINFO.name}" rows="{$FIELDINFO.rows}" cols="{$FIELDINFO.cols}" wrap="virtua"
	ui = "{$FIELDINFO.UI}" mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}" class="responsive_width_99" >{$FIELDINFO.value}</textarea>
