<div style="height:25px;">
文件名：{if $HAVE_FILE}
			{$FILE_NAME}
			<a href="{$DOWNLOAD_FILE}" style="color:#1E90FF;" title="点击下载">下载</a>
		{else}
			{$FILE_NAME}
		{/if}
		
</div>
<div>
	<table style="width:100%">


	</table>
</div>
{if $BROWER_IS_MSIE}
	<object id="wmplayer" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" height="65px" width="100%">
		<param name="autoStart" value="true">
		{if $HAVE_FILE}
			<param name="url" value="{$DOWNLOAD_FILE}">
		{/if}
	</object>
{else}
	<audio controls="controls" autoplay="autoplay" style="width:100%"
		{if $HAVE_FILE}
			src="{$DOWNLOAD_FILE}"
		{/if}	
	>
		你的浏览器不支持在线播放。
	</audio>
	
{/if}

