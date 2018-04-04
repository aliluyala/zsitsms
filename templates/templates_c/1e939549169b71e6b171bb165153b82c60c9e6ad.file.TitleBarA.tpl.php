<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:04:57
         compiled from "/var/www/html/zsitsms/templates/templates/TitleBarA.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6863730625abde1997a89a4-34465487%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e939549169b71e6b171bb165153b82c60c9e6ad' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/TitleBarA.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6863730625abde1997a89a4-34465487',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'TITLEBAR_SHOW_MODULE_LABEL' => 0,
    'MODULE' => 0,
    'MODULE_LABEL' => 0,
    'ACTION_LABEL' => 0,
    'TOOLSBAR' => 0,
    'IMAGES' => 0,
    'HAVE_QUERY_WHERE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde1998f2946_27341207',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde1998f2946_27341207')) {function content_5abde1998f2946_27341207($_smarty_tpl) {?><div class="client_titlebar_box">
	<table border="0" cellspacing="0">
		<tr>
		<td style="border-style:solid solid none solid;border-width:2px;border-color:#79b7e7;font-weight:bold;
			padding-left:20px;padding-right:20px;border-top-left-radius:5px;background-color:#f5f8f9;
			border-top-right-radius:5px;-moz-border-top-left-radius:5px;-moz-border-top-right-radius:5px;">
			<?php if ($_smarty_tpl->tpl_vars['TITLEBAR_SHOW_MODULE_LABEL']->value){?>
				<a href="javascript:zswitch_load_client_view('index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&action=index')"><?php echo $_smarty_tpl->tpl_vars['MODULE_LABEL']->value;?>
</a> >> 
			<?php }?>
			<?php echo $_smarty_tpl->tpl_vars['ACTION_LABEL']->value;?>

			
		</td>
		<td style="width:20px;"></td>
		<?php if ($_smarty_tpl->tpl_vars['TOOLSBAR']->value['create']=='yes'){?>
			<td>
				<a title="新建..." href="javascript:zswitch_load_client_view('index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&action=createView&return_module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&return_action=detailView')">
					<img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/plus_2.png"/>
				</a>
			</td>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['TOOLSBAR']->value['search']=='yes'){?>
			<td>
				<a title="搜索..." href="javascript:void(0);" onclick="$('#<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_search_dlg').dialog('open');">
					<?php if ($_smarty_tpl->tpl_vars['HAVE_QUERY_WHERE']->value){?>
						<img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/search_lense_cond1.png"/>
					<?php }else{ ?>
						<img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/search_lense.png"/>
					<?php }?>
				</a>
			</td>
		<?php }?>
		<td style="width:20px;"></td>
		<?php if ($_smarty_tpl->tpl_vars['TOOLSBAR']->value['calendar']=='yes'){?>
			<td>
				<a title="日历" href="javascript:void(0);" onclick="zswitch_open_calendar_dlg();"><img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/calendar.png"/></a>
			</td>
		<?php }?>	
		<?php if ($_smarty_tpl->tpl_vars['TOOLSBAR']->value['calculator']=='yes'){?>
			<td>
				<a title="计算器"><img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/calculator.png"/></a>
			</td>
		<?php }?>	
		<td style="width:20px;"></td>
		<?php if ($_smarty_tpl->tpl_vars['TOOLSBAR']->value['email']=='yes'){?>
			<td>
				<a title="发送电子邮件"><img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/email.png"/></a>
			</td>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['TOOLSBAR']->value['sms']=='yes'){?>
			<td>
				<a title="发送手机短信" href="javascript:void(0);" onclick="zswitch_open_sendsms_dlg();"><img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/mobile_sms.png"/></a>
			</td>	
		<?php }?>
		
		<td style="width:20px;"></td>
		<?php if ($_smarty_tpl->tpl_vars['TOOLSBAR']->value['import']=='yes'){?>
			<td>
				<a title="导入"  href="javascript:void(0);" onclick="zswitch_open_import_dlg_A('<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
');"><img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/import.png"/></a>
			</td>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['TOOLSBAR']->value['export']=='yes'){?>
			<td>
				<a title="导出"  href="javascript:void(0);" onclick="zswitch_open_export_dlg('<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
');"><img src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/export.png"/></a>
			</td>
		<?php }?>
		</tr>
	</table>
</div>
<?php }} ?>