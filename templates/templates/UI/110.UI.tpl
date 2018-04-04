{* *********************************************************************** 
*  UI类型值: 110 
*  说明: ajax有效性检查。
*  
*********************************************************************** *}
<input type="text" name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" ui = "{$FIELDINFO.UI}" 
	mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}"  recordid="{$FIELDINFO.recordid}" source_module="{$FIELDINFO.source_module}"
	validity_url="{$FIELDINFO.validity_url}"  style="width:{$FIELDINFO.width}" mode="{$FIELDINFO.mode}" />
	<span>
		<img id="loading" src="{$IMAGES}/loading_g.gif"  style="width:16px;height:16px;display:none;" />
		<img id="invalid" src="{$IMAGES}/delete_2.png"  style="width:16px;height:16px;display:none;"/>
		<img id="valid" src="{$IMAGES}/check-64.png"  style="width:16px;height:16px;display:none;"/>
	</span>