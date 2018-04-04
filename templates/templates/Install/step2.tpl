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
				{$PRODUCT} 安装 - 数据库设置
			</td>
			<td></td>
		</tr>
		<tr>
			<td style="width:30%"></td>
			<td style="border:1px solid #aaaaaa;height:400px;width:600px;min-width:600px;padding:10px; vertical-align:top;">
				<div id="processbar" style="position: absolute;"><img width="60px" height="60px" src = "{$IMAGES}/loading_a.gif"/></div>
				<form id="set_db_form" action="install.php?step=step2" method="post">
				<table class="info-table">
					<tr>
						<td class="info-label" >数据库类型:</td>
						<td class="info-content">
							{$DB_SERVER_INFO}
						</td>

					</tr>
					<tr>
						<td class="info-label" >主机:</td>
						<td class="info-content">
							<input type="text" name="host" value="{$DB_HOST}"/>
						</td>
	
					</tr>
					<tr>
						<td class="info-label" >端口:</td>
						<td class="info-content">
							<input type="text" name="port" value="{$DB_PORT}"/>
						</td>
										
					</tr>
					<tr>
						<td class="info-label" >用户名:</td>
						<td class="info-content">
							<input type="text" name="user" value="{$DB_USER}"/>
						</td>
									
					</tr>	
					<tr>
						<td class="info-label" >密码: </td>
						<td class="info-content">
							<input type="password" name="password" value="{$DB_PASSWORD}"/>
						</td>									
					</tr>
					<tr>
						<td class="info-label" >数据库名: </td>
						<td class="info-content">
							<input type="database" name="database" value="{$DB_DATABASE}"/>
							{if $DATABASE_EXIST}
								<input type="checkbox" name="delete_database" id="delete_database" value="YES"/>
								<label for="delete_database">删除数据库</label>
							{/if}
						</td>
										
					</tr>

					<tr>
						<td></td><td style="color:red">{$ERROR_INFO}</td>
					</tr>
					<tr>
						<td class="info-label" ></td>
						<td class="info-content">
							<button id="again_check">检测</button>
							{if $CHECK_PASS}
								<img  src="{$IMAGES}/check-64.png"/>
							{else}
								<img  src="{$IMAGES}/delete_2.png"/>
							{/if}	
							<td/>
						<td class="info-label" ></td>
						<td class="info-content"></td>
					</tr>					
				</table>
				</form>
				
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td class="button_line">&nbsp;
				{if $CHECK_PASS}
				<button  id="skip_next_step" >安装数据库</button>		
				{/if}	
			</td>
			<td></td>
		</tr>
	</table>		
	<script>
			$("#again_check").click(function(){
				$("#set_db_form").submit();
			});
			$("#skip_next_step").click(function(){
				$("#set_db_form").attr("action","install.php?step=step3");
				$("#processbar").show();
				var offset = $("#processbar").offset();
				offset.left += 250;
				offset.top += 170;
				$("#processbar").offset(offset);
					
				$("#set_db_form").submit();
			});
			$("#processbar").hide();
	</script>
	</body>
</html>	

