{* *********************************************************************** 
*  UI类型值: 55
*  说明: 记录共享字段。
*  
*********************************************************************** *}
<input type="hidden" name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" ui = "{$FIELDINFO.UI}" 
	mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}" />
<select id="{$FIELDINFO.name}_share_type" >
	<option value="group" {if $FIELDINFO.value>=1000000}selected="selected"{/if}>工作组</option>
	<option value="user"  {if $FIELDINFO.value<1000000}selected="selected"{/if}>用户</option>
</select >	
<select id="{$FIELDINFO.name}_groups" {if $FIELDINFO.value<1000000}style="display:none;"{/if}>
	{html_options options=$FIELDINFO.groups selected=$FIELDINFO.value}
</select>
<select id="{$FIELDINFO.name}_users" {if $FIELDINFO.value>=1000000}style="display:none;"{/if}>
	{html_options options=$FIELDINFO.users selected=$FIELDINFO.value}
</select>

