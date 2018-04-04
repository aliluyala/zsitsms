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
				{$PRODUCT} 安装 - 设置管理员帐号和密码
			</td>
			<td></td>
		</tr>
		<tr>
			<td style="width:30%"></td>
			<td style="border:1px solid #aaaaaa;height:400px;width:600px;min-width:600px;padding:10px; vertical-align:top;">
				<form id="install_form" action="install.php?step=step4" method="post">
				
				
				<table class="info-table">
					<tr>
						<td class="info-label" >时区:</td>
						<td class="info-content">
							{html_options name='timezone' options=$TIMEZONES selected=$DEFAULT_TIMEZONE}
						</td>
	
					</tr>				

					<tr>
						<td class="info-label" >帐号:</td>
						<td class="info-content">
							<input type="text" name="user" value="admin"/>
						</td>
	
					</tr>
					<tr>
						<td class="info-label" >密码:</td>
						<td class="info-content">
							<input type="text" name="password" value=""/>
						</td>
										
					</tr>
					<tr>
						<td class="info-label" >再次输入密码:</td>
						<td class="info-content">
							<input type="text" name="again_password" value=""/>
						</td>
									
					</tr>
					<tr>
						<td class="info-label" ></td>
						<td class="info-content">
							<div id="error_info_box" style="color:red;">
							</div>
						</td>
									
					</tr>						

				</table>
				</form>
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td class="button_line">&nbsp;

				<button  id="install" >完成</button>		

			</td>
			<td></td>
		</tr>
	</table>		
	<script>
			$("#install").click(function(){
				$("#error_info_box").html("");
				if($("[name='user']").val().length==0)
				{
					$("#error_info_box").html("管理员帐号不能为空。");
				}
				else if($("[name='password']").val().length==0)
				{
					$("#error_info_box").html("密码不能为空。");
				}
				else if($("[name='password']").val()  != $("[name='again_password']").val())
				{
					$("#error_info_box").html("两次输入密码不一致。");
				}
				else
				{
					$("#install_form").submit();
				}
				
			});

			
	</script>
	</body>
</html>	

