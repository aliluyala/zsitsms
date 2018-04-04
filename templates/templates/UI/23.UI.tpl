{* *********************************************************************** 
*  UI类型值: 23
*  说明: 多选。如果是强制字段,将检查输入值是否为空。
*  
*********************************************************************** *}
<input type="hidden"  name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" />
<div id="{$FIELDINFO.name}" ui="{$FIELDINFO.UI}" name="{$FIELDINFO.name}" mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}">	
	{foreach  $FIELDINFO.options as $key => $option}
		<input type="checkbox" id="{$FIELDINFO.name}_{$key}"  value="{$key}"  {if $option.checked} checked="checked" {/if} >
		<label for="{$FIELDINFO.name}_{$key}">{$option.label}</label>
	{/foreach}
</div>

