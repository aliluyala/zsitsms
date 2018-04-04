<!DOCTYPE html>
<html lang="zh-cn">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link REL="SHORTCUT ICON" HREF="{$IMAGES}/qdlogo.ico"/>
		<title>启点保险电销管理系统-安装</title>
		<link rel="stylesheet" type="text/css" href="{$STYLES}/zswitch.css" />
		<script type="text/javascript" src="{$SCRIPTS}/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/jquery.md5.js"></script>
		<style type="text/css">
{literal}		
			.info-content {height:30px;width:200px;text-align:left;padding-left:5px;font-size:16px;}
			.info-label  {height:30px;width:100px;text-align:right;padding-right:5px;font-size:16px;}
			.info-label img {width:16px;height:16px;}
			.info-content img {width:16px;height:16px;}
			.button_line {text-align:right;border:1px solid #aaaaaa;padding:4px;background-color:#EEEEEE;}
{/literal}
		</style>
		
		
	</head>	
	<body>

	<table style="width:100%;min-width:600px;margin-top:10px;">
		<tr >
			<td></td>
			<td style="height:40px;background-color:#888888;color:#FFFFFF;font-size:18px;font-weight:bold;text-align:left;padding-left:20px;">
				{$PRODUCT} 安装 - 环境检测
			</td>
			<td></td>
		</tr>
		<tr>
			<td style="width:30%"></td>
			<td style="border:1px solid #aaaaaa;height:400px;width:600px;min-width:600px;padding:10px; vertical-align:top;">
				<table class="info-table">
					{foreach $EVN_INFOS as $row}
						<tr>
							{foreach $row as $cell}
							<td class="info-label" >{$cell.label}</td>
							<td class="info-content">
								{if $cell.pass}
									<img  src="{$IMAGES}/check-64.png"/>
								{else}
									<img  src="{$IMAGES}/delete_2.png"/>
								{/if}
							</td>
							{/foreach}									
						</tr>					
					{/foreach}
					<tr>
						<td class="info-label" >WEB服务器</td>
						<td class="info-content">{$WEB_SERVER}<td/>
						<td class="info-label" ></td>
						<td class="info-content"></td>
					</tr>
					<tr>
						<td class="info-label" ></td>
						<td class="info-content"><button onclick="location.href='install.php?step=step1';" >重新检测</button><td/>
						<td class="info-label" ></td>
						<td class="info-content"></td>
					</tr>					
				</table>
			
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td class="button_line">
				{if $CHECK_PASS}
				<button onclick="location.href='install.php?step=step2';" >下一步</button>		
				{/if}	
			</td>
			<td></td>
		</tr>
	</table>
		

	</body>
</html>	

