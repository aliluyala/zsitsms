<!DOCTYPE html>
<html lang="zh-cn">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link REL="SHORTCUT ICON" HREF="{$IMAGES}/qdlogo.ico"/>
		<title>{$USER} - 启点云服务 </title>
		<link rel="stylesheet" type="text/css" href="{$STYLES}/jquery-ui/redmond/jquery-ui-1.10.3.custom.css" />
		<link rel="stylesheet" type="text/css" href="{$STYLES}/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" href="{$STYLES}/fancytree/ui.fancytree.min.css" />
		<link rel="stylesheet" type="text/css" href="{$STYLES}/zswitch.css" />		
		<script type="text/javascript" src="{$SCRIPTS}/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/jquery-ui-1.10.3.custom.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/grid.locale-cn.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/jquery.jqGrid.min.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/jquery.fancytree.min.js"></script>		
		<script type="text/javascript" src="{$SCRIPTS}/jquery.md5.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/globalize.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/cultures/globalize.culture.zh-CN.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/jquery-ui.timespinner.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/jquery.json-2.4.min.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/date-format.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/zswitch.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/zswitchui.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/ajaxfileupload.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/zswitchui-validity.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/ichart.1.2.1.beta.min.js"></script>	
		<script type="text/javascript" src="{$SCRIPTS}/jqueryui-apc-upload-1.0.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/jqueryui-wizardDialog-1.0.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/serverAsynData.js"></script>
		<script type="text/javascript" src="{$SCRIPTS}/jquery.base64.js"></script>		
		{* 在此加入自定义脚本库 *}
		<script type="text/javascript" src="{$SCRIPTS}/policy_calculate.js"></script>
		
		{* {if $HAVE_AGENT} *}
			<link rel="stylesheet" type="text/css" href="{$STYLES}/zswitch-softswitch.css" />			
			<script type="text/javascript" src="{$SCRIPTS}/zswitch-softswitch.js"></script>
		{* {/if} *}
	</head>	
	<body >
		<script type="text/javascript" >
		var default_jobview_url = "{$DEFAULT_JOBVIEW_URL}";
		{if $HAVE_AGENT}
			var agent_phone_control_dlg = "";
		{/if}
		{literal}
			$(document).ready(function(){
				zswitch_menus_init();				
				//zswitch_load_client_view(default_jobview_url);
				if(typeof(agent_phone_control_dlg) != "undefined")
				{
					agent_phone_control_dlg = new agentPanel("body");
					agent_phone_control_dlg.moveToObj("#main_view_header_agent_phone_button");
					$("#main_view_header_agent_phone_status").click(function(){
						var menu = $("#main_view_header_agent_phone_status_menu").show().position({
							my: "left top",
							at: "left bottom",
							of: this
						});
						$( document ).one( "click", function() {
							$("#main_view_header_agent_phone_status_menu").hide();
						});
						return false;
					});
					$("#main_view_header_agent_phone_status_menu").hide().menu();				
				}
				window.onhashchange = function(){				
					var hash = window.location.hash;
					
					var current = $('body').data('current_client_url');		
					if(hash == null || hash == "" || hash== "#undefined")
					{
						zswitch_load_client_view(default_jobview_url);
					}
					else 
					{						
						
						//if(typeof(current) == "undefined" || current == null)
						//{
						//	alert("      通过浏览器‘刷新’按键或‘F5’刷新页面将会中断座席通话，你要安全刷新页面请点击页面右侧‘刷新’链接！");
						//}
						zswitch_load_client_view(hash.replace(/#/,''));
					}
				};
				window.onhashchange(); 
			});
			
			
			function main_header_logout_button(){
				if(typeof(agent_phone_control_dlg) != "undefined")
				{
					$.get("index.php?module=AgentState&action=logoutAgent",function(){
						window.location.replace("index.php?module=User&action=logout");
					});
					
				}
				else
				{
					window.location.replace("index.php?module=User&action=logout");
				}	
			};
		{/literal}
			
		</script>
		<div id="main_view_header_sass">
			<h1 class="logo_saas"> {$CLOUD_USER_NAME} </h1>
			<ul class="header_nav">
			<li class="nav_first" >
			欢迎你:{$USER}，离开请点右侧“注销”退出系统！
			</li>
			<li >
			<a  title="个人设置" href="javascript:void(0);" onclick="zswitch_load_client_view('index.php?module=User&action=selfSetting')" >个人设置</a>
			</li>
			<li>
			<a href="javascript:void(0);" onclick="main_header_logout_button();" title="注销" >注销</a>
			</li>
			<ul>
		</div>

		
		<div id="main_view_menu_box"> 
			<!-- 主菜单条 -->
			<div class="main_menu_item main_menu_item_noactive" action="NONE"></div>
			{foreach $MENUS as $item}
				<div class="main_menu_item main_menu_item_noactive" 
				     action="{$item.action}" 
					 title="{$item.title}"
					 {if $item.action neq 'SUB_MENU'}
						onclick="{$item.target}"
					 {/if}
					 >
					 {$item.name}
					{if $item.action eq 'SUB_MENU'}
						<!-- 如果有子菜单 生成弹出菜单 -->
						<img src="{$IMAGES}/menuDnArrow.gif"/>						
						<div class="main_view_sub_menu_box">
							{foreach $item.submenu as $subitem}
								<div class="sub_menu_item sub_menu_item_noactive" 
									title="{$subitem.title}"
									onclick="{$subitem.target}">
									{$subitem.name}
								</div>
							{/foreach}
						</div>
					{/if}
				</div>				
			{/foreach}

		</div>
		<div id="main_view_client">
			
		</div>
		<div id="main_view_flooter" class="small">
			© 2012-2017 
			<a href="http://www.cdipcc.com" target="_blank">cdipcc.com</a> 版权所有 	
		</div>
	</body>
</html>	