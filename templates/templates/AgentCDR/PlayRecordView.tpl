<!DOCTYPE html>
<html lang="zh-cn">
<head>
<title>播放录音</title>
</head>
<body>
<div style="height:25px;font-size:14px;">
文件名：{if $HAVE_FILE}
			{$FILE_NAME}
			<a href="{$DOWNLOAD_FILE_URL}" style="color:#1E90FF;" title="点击下载" >下载</a>
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
		<param name="autoStart" value="true"/>
		{if $HAVE_FILE}
			<param name="url" value="{$DOWNLOAD_FILE_URL}"/>
		{/if}
	</object>
{else}
	<audio id="agentcdr_listview_audio" controls="controls" autoplay="autoplay" style="width:100%"
		{if $HAVE_FILE}
			<source src="{$DOWNLOAD_FILE_URL}" type="audio/wav">			
		{/if}	
	>
		你的浏览器不支持在线播放。
	</audio>
	
{/if}
</body>
</html>