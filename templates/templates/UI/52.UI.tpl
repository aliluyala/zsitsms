{* ***********************************************************************
*  UI类型值: 52
*  说明: 记录修改人。
*
*********************************************************************** *}
<input type="hidden" name="{$FIELDINFO.name}" value="{$FIELDINFO.value}" ui = "{$FIELDINFO.UI}"
	mandatory = "{$FIELDINFO.mandatory}" label = "{$FIELDINFO.label}" />
<input type="text" id="{$FIELDINFO.name}" value="{$FIELDINFO.show_value}" readonly="readonly" class="responsive_width_70" />
<a href="javascript:void(0)"  title="选择">
	<img src="{$IMAGES}/plus_2.png" style="width:16px; height:16px;border:none"/>
</a>
<a href="javascript:void(0)" title="擦除内容">
	<img src="{$IMAGES}/eraser.png" style="width:16px; height:16px;border:none"/>
</a>

<div  title="选择关联的记录" module="{$FIELDINFO.associate_module}" show_field="{$FIELDINFO.show_field}"
	list_filter_field="{$FIELDINFO.list_filter_field}" list_filter_value="{$FIELDINFO.list_filter_value}" list_fields="{$FIELDINFO.list_fields}">
</div>

