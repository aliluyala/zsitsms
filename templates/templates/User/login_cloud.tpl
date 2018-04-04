<!DOCTYPE html>
<html lang="zh-cn">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link REL="SHORTCUT ICON" HREF="{$IMAGES}/qdlogo.ico"/>
		<title>登录 - 启点云服务</title>
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
	<div id="login_view_logo_title_saas">
		<h1 class="logo_saas">启点云</h1><h2 class="logo_title_saas" >登录</h2>
		<ul class="header_nav">
			<li class="nav_first">
			<a href="http://www.cdipcc.com" target="_blank" >关于我们</a>
			</li>
			<li>
			<a href="http://www.cdipcc.com" target="_blank" >帮助</a>
			</li>
		<ul>
	</div>	

	<div id="login_view_main_box_saas">
		<table style="width:100%;height:100%;">
		<tr>
		<td style="width:55%;text-align:right;padding-right:5%;">
		<div id="login_view_proudct_info_saas">
			<img src="{$IMAGES}/login_info_background.jpg"  />
		</div>
		</td><td style="text-align:left;padding-left:5%;">	
		<div id="login_view_info_saas" >
			<div class="title">登录启点云服务</div>
			<form action="index.php?module=User&action=login" method="post">
			<div class="label">云ID：</div>
			<div><input type="text" id="cloudid" name="cloudid" value="{$CLOUDID}"/></div>
			<div class="label">用户名：</div>
			<div><input type="text" id="user_name" name="user_name" value="{$USER_NAME}"/></div>
			<div class="label">密码：</div>
			<div><input type="password" id="password" name="password"/></div>
			{if $HAVE_AGENT}
			<div class="label">座席号码：</div>
			<div><input type="text" id="agent_number" name="agent_number" value="{$AGENT_NUMBER}"/></div>
			{/if}
			<div class="label">验证码：</div>
			<div ><input type="text" id="verify_code" name="verify_code"/> 
				<img id="verify_code_img" src="index.php?module=User&action=verifyCode" style="vertical-align:middle;"/>
				<a class="small"  href="javascript:ref_verify_code()">看不清,换一张</a> </div>
			{if $LOGIN_ERROR neq ''}
						<div class="errorMessage">
							{$LOGIN_ERROR}
						</div>
			{/if}
			<div ><input type="submit" value="登录" id="submit" onclick="$('#password').val($.md5($('#password').val()));return true"/></div>
			<div style="height:40px;line-height:40px;" >
				<input type="checkbox" checked="checked" />我已阅读<a href="#">《启点云服务协议》</a>
			</div>

			</form>
			
		</div>
		</td></tr></table>
		
	</div>	
	
	<div id="login_flooter" class="login_flooter_saas">
			© 2012-2017 
			<a href="http://www.cdipcc.com" target="_blank">cdipcc.com</a> 版权所有 		
	</div>	
	</body>
</html>	