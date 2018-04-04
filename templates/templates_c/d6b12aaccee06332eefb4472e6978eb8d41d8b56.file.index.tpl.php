<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:03
         compiled from "/var/www/html/zsitsms/templates/templates/Index/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:941012345abde1278ab122-11041752%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd6b12aaccee06332eefb4472e6978eb8d41d8b56' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/Index/index.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '941012345abde1278ab122-11041752',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'IMAGES' => 0,
    'USER' => 0,
    'STYLES' => 0,
    'SCRIPTS' => 0,
    'DEFAULT_JOBVIEW_URL' => 0,
    'HAVE_AGENT' => 0,
    'CHECK_ACTIVITY' => 0,
    'ACTIVITY_CHECK_TIME' => 0,
    'MENUS' => 0,
    'item' => 0,
    'subitem' => 0,
    'VERSION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde127ef4bf7_45736311',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde127ef4bf7_45736311')) {function content_5abde127ef4bf7_45736311($_smarty_tpl) {?><!DOCTYPE html>
<html lang="zh-cn">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link REL="SHORTCUT ICON" HREF="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/qdlogo.ico"/>
		<title><?php echo $_smarty_tpl->tpl_vars['USER']->value;?>
 - 启点客户关系管理系统 </title>
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['STYLES']->value;?>
/jquery-ui/redmond/jquery-ui-1.10.3.custom.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['STYLES']->value;?>
/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['STYLES']->value;?>
/fancytree/ui.fancytree.min.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['STYLES']->value;?>
/zswitch.css" />
    	<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jquery-ui-1.10.3.custom.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/grid.locale-cn.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jquery.jqGrid.min.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jquery.fancytree.min.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jquery.md5.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/globalize.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/cultures/globalize.culture.zh-CN.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jquery-ui.timespinner.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jquery.json-2.4.min.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/date-format.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/zswitch.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/zswitchui.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/ajaxfileupload.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/zswitchui-validity.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/ichart.1.2.1.beta.min.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jqueryui-apc-upload-1.0.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jqueryui-wizardDialog-1.0.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/serverAsynData.js"></script>
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/jquery.base64.js"></script>
		
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/policy_calculate.js"></script>
		
		
			<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['STYLES']->value;?>
/zswitch-softswitch.css" />
			<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/zswitch-softswitch.js"></script>
		
	</head>
	<body >
		<script type="text/javascript" >
		var default_jobview_url = "<?php echo $_smarty_tpl->tpl_vars['DEFAULT_JOBVIEW_URL']->value;?>
";
		<?php if ($_smarty_tpl->tpl_vars['HAVE_AGENT']->value){?>
			var agent_phone_control_dlg = "";
		<?php }?>
		
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
			
		
		
		<?php if ($_smarty_tpl->tpl_vars['CHECK_ACTIVITY']->value){?>
			zswitch_check_user_activity(<?php echo $_smarty_tpl->tpl_vars['ACTIVITY_CHECK_TIME']->value;?>
)
		<?php }?>
		</script>
		<div id="main_view_header">
			欢迎你:<?php echo $_smarty_tpl->tpl_vars['USER']->value;?>
，离开请点右侧“注销”退出系统！
			<span style="width:20px;margin-left:20px;"> </span>
			<?php if ($_smarty_tpl->tpl_vars['HAVE_AGENT']->value){?>
				<a id= "main_view_header_agent_phone_button" class="middle" title="座席电话面板" href="javascript:void(0);"

					onclick="agent_phone_control_dlg.show();" style="margin-right:0px;">
					<img id="main_view_header_agent_status_img_online" style="height:20px;width:26px;text-align:left;margin-right:0px;border-style:solid none solid solid;border-width:1px;border-color:#aaaaaa;border-radius:3px 0px 0px 3px;-moz-border-radius:3px 0px 0px 3px;" src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/agent.png"/>
					<img id="main_view_header_agent_status_img_onoff" style="height:20px;width:26px;text-align:left;margin-right:0px;border-style:solid none solid solid;border-width:1px;border-color:#aaaaaa;border-radius:3px 0px 0px 3px;-moz-border-radius:3px 0px 0px 3px;display:none;" src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/agent_off.png"/>
				</a>
				<a  id= "main_view_header_agent_phone_status"  class="middle" title="设置座席工作状态" style="margin-left:-7px"  href="javascript:void(0);">
					<img style="margin-right:0px;border-style:solid solid solid solid;border-width:1px;border-color:#aaaaaa #aaaaaa #aaaaaa #eeeeee;border-radius:0px 3px 3px 0px;-moz-border-radius:0px 3px 3px 0px;" src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/opened1.png"/>
				</a>


				<ul id="main_view_header_agent_phone_status_menu" style="width:60px; position: absolute;font-size:12px;text-align:left;">
					<li><a href="javascript:void(0);" onclick="var d=new Date();$.get('index.php?module=AgentState&action=loginAgent&time='+d.getTime());$('#main_view_header_agent_status_img_online').show();$('#main_view_header_agent_status_img_onoff').hide();">在线</a></li>
					<li><a href="javascript:void(0);" onclick="var d=new Date();$.get('index.php?module=AgentState&action=logoutAgent&time='+d.getTime());$('#main_view_header_agent_status_img_online').hide();$('#main_view_header_agent_status_img_onoff').show();">离线</a></li>
				</ul>

				<span style="width:20px;margin-left:20px;"> </span>

			<?php }?>
			<a  class="middle" title="个人设置" href="javascript:void(0);" onclick="zswitch_load_client_view('index.php?module=User&action=selfSetting')">
				<img  src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/options_2.png"/>
			</a>
			<span style="width:20px;margin-left:20px;"> </span>
			<a id="main_view_header_logout_button" class="middle" href="javascript:void(0);" onclick="main_header_logout_button();" title="注销">
				<img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/exit.png"/></a>
			<span style="width:20px;margin-left:20px;"> </span>
		</div>


		<div id="main_view_menu_box">
			<!-- 主菜单条 -->
			<div class="main_menu_item main_menu_item_noactive" action="NONE"></div>
			<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['MENUS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
				<div class="main_menu_item main_menu_item_noactive"
				     action="<?php echo $_smarty_tpl->tpl_vars['item']->value['action'];?>
"
					 title="<?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
"
					 <?php if ($_smarty_tpl->tpl_vars['item']->value['action']!='SUB_MENU'){?>
						onclick="<?php echo $_smarty_tpl->tpl_vars['item']->value['target'];?>
"
					 <?php }?>
					 >
					 <?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>

					<?php if ($_smarty_tpl->tpl_vars['item']->value['action']=='SUB_MENU'){?>
						<!-- 如果有子菜单 生成弹出菜单 -->
						<img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/menuDnArrow.gif"/>
						<div class="main_view_sub_menu_box">
							<?php  $_smarty_tpl->tpl_vars['subitem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['subitem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['item']->value['submenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['subitem']->key => $_smarty_tpl->tpl_vars['subitem']->value){
$_smarty_tpl->tpl_vars['subitem']->_loop = true;
?>
								<div class="sub_menu_item sub_menu_item_noactive"
									title="<?php echo $_smarty_tpl->tpl_vars['subitem']->value['title'];?>
"
									onclick="<?php echo $_smarty_tpl->tpl_vars['subitem']->value['target'];?>
">
									<?php echo $_smarty_tpl->tpl_vars['subitem']->value['name'];?>

								</div>
							<?php } ?>
						</div>
					<?php }?>
				</div>
			<?php } ?>

		</div>
		<div id="main_view_client">

		</div>
		<div id="main_view_flooter" class="small">
			ZSwitch CRM <?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
 |
			© 2012-2016 |
			<a href="http://www.cdipcc.com" target="_blank">www.cdipcc.com</a>
			| <a href="http://www.cdipcc.com" target="_blank">成都启点科技有限公司</a>
		</div>
	</body>
</html><?php }} ?>