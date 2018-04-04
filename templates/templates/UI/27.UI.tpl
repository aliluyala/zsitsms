{* *********************************************************************** 
*  UI类型值: 27
*  说明: 下拉选择,选项可设置。
*  
*********************************************************************** *}

<select name="{$FIELDINFO.name}" ui = "{$FIELDINFO.UI}" mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}" 
 module="{$FIELDINFO.module}" selected_value="{$FIELDINFO.value}">
 {foreach $FIELDINFO.options as $opt}
	<option value="{$opt.value}" {if $opt.value == $FIELDINFO.value}  selected="selected" {/if} >{$opt.show}</option>
 {/foreach}
</select>
