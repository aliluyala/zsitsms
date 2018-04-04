{* *********************************************************************** 
*  UI类型值: 10
*  说明: ueditor文本编辑器。如果是强制字段,将检查输入值是否为空。
*  
*********************************************************************** *}

<script type="text/javascript" charset="utf-8">
	window.UEDITOR_HOME_URL = "{$SCRIPTS}/../../pkgs/ueditor/";
</script>		
<script type="text/javascript" src="{$SCRIPTS}/../../pkgs/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/../../pkgs/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/../../pkgs/ueditor/lang/zh-cn/zh-cn.js"></script>

<input type="hidden" name="{$FIELDINFO.name}" ui = "{$FIELDINFO.UI}" mandatory = "{$FIELDINFO.mandatory}" />	
<script id="ueditor_container_{$FIELDINFO.name}"  type="text/plain">
    {$FIELDINFO.value}
</script>

