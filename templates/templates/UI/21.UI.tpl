{* *********************************************************************** 
*  UI类型值: 21
*  说明: radio单选。
*  
*********************************************************************** *}
{foreach $FIELDINFO.options as $val=>$label}
	<input id="ui_21_{$FIELDINFO.name}_{$val}" type="radio" name="{$FIELDINFO.name}" value="{$val}"  ui = "{$FIELDINFO.UI}" mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}" 
	{if $val == $FIELDINFO.value}checked="checked"{/if}
	/><label for="ui_21_{$FIELDINFO.name}_{$val}">{$label}</label> | 
{/foreach}
