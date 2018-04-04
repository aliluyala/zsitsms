{include file="TitleBar.tpl"}
<iframe id="workflow_view_container"  
     src="http://{$PM_HOST}/sys{$PM_WORKSPACE}/{$PM_LANG}/{$PM_SKIN}/cases/main_init?sid={$PM_SESSIONID}" 
	 width="100%" height="500px;" frameborder="0" style="margin-top:2px;">
</iframe>
<script>

{literal}

	$(window).resize(function(){
		$("#workflow_view_container").height($(window).height()-160);	
	});
	$(window).resize();
{/literal}
</script>