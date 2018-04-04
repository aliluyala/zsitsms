{* *********************************************************************** 
*  UI类型值: 7 
*  说明: 如果是强制字段,将检查输入值是否为空。
*  
*********************************************************************** *}
<input type="text" name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" ui = "{$FIELDINFO.UI}" 
	mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}"  min_len="{$FIELDINFO.min_len}"  
	max_len="{$FIELDINFO.max_len}"  regexp="{$FIELDINFO.regexp}" regtitle = "{$FIELDINFO.regtitle}"  style="width:{$FIELDINFO.width}"  />