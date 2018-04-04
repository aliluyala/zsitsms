<!DOCTYPE html>
<html lang="zh-cn">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link REL="SHORTCUT ICON" HREF="{$IMAGES}/qdlogo.ico"/>
		<title>登录 - 启点保险电销管理系统</title>
		<link rel="stylesheet" type="text/css" href="{$STYLES}/zswitch.css" />
		<script type="text/javascript" src="{$SCRIPTS}/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/jquery.md5.js"></script>
	</head>	
	<body>
	<script type="text/javascript">
		function ref_verify_code()
		{
			$("#verify_code_img").attr("src","index.php?module=User&action=verifyCode&"+Math.random());			
		}
	</script>	
	<div id="login_view_logo_title"></div>	

	<div id="login_view_main_box">
		<div id="login_view_proudct_info">
	
		</div>	
		<div id="login_view_info" >
			<div class="title">登录系统</div>
			<form action="index.php?module=User&action=login" method="post">
			<div class="label">用户名</div>
			<div><input type="text" id="user_name" name="user_name" value="{$USER_NAME}"/></div>
			<div class="label">密码</div>
			<div><input type="password" id="password" name="password"/></div>
			{if $HAVE_AGENT}
			<div class="label">座席号码</div>
			<div><input type="text" id="agent_number" name="agent_number" value="{$AGENT_NUMBER}"/></div>
			{/if}
			<div class="label">验证码</div>
			<div ><input type="text" id="verify_code" name="verify_code"/> 
				<img id="verify_code_img" src="index.php?module=User&action=verifyCode" style="vertical-align:middle;"/>
				<a class="small"  href="javascript:ref_verify_code()">看不清,换一张</a> </div>
			{if $LOGIN_ERROR neq ''}
						<div class="errorMessage">
							{$LOGIN_ERROR}
						</div>
			{/if}
			<div ><input type="submit" value="登录" id="submit" onclick="$('#password').val($.md5($('#password').val()));return true"/></div>

			</form>
			
		</div>
		<div class="clear"></div>
	</div>	
	
	<div id="login_flooter" class="login_flooter small">
			ZSitsms {$VERSION} | 
			© 2012-2016 |
			<a href="http://www.cdipcc.com" target="_blank">www.cdipcc.com</a> 
			| <a href="http://www.cdipcc.com" target="_blank">成都启点科技有限公司</a></div>
	</div>	
	</body>
</html>	