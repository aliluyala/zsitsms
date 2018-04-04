{* *********************************************************************** 
*  UI类型值: 8 
*  说明: 如果是强制字段,将检查输入值是否为空。
*  
*********************************************************************** *}
<input type="text" name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" ui = "{$FIELDINFO.UI}" 
	mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}"  min="{$FIELDINFO.min}"  
	decimal="{$FIELDINFO.decimal}" max="{$FIELDINFO.max}" style="width:{$FIELDINFO.width}" /> <span>{$FIELDINFO.unit}</span>
