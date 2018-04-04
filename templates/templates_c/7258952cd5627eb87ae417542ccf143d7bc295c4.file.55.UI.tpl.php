<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:04:56
         compiled from "/var/www/html/zsitsms/templates/templates/UI/55.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4610748175abde198779ad9-62269631%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7258952cd5627eb87ae417542ccf143d7bc295c4' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/55.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4610748175abde198779ad9-62269631',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde1988c35c9_00983677',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde1988c35c9_00983677')) {function content_5abde1988c35c9_00983677($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/zsitsms/include/Smarty/plugins/function.html_options.php';
?>
<input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
" 
	mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
" />
<select id="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
_share_type" >
	<option value="group" <?php if ($_smarty_tpl->tpl_vars['FIELDINFO']->value['value']>=1000000){?>selected="selected"<?php }?>>工作组</option>
	<option value="user"  <?php if ($_smarty_tpl->tpl_vars['FIELDINFO']->value['value']<1000000){?>selected="selected"<?php }?>>用户</option>
</select >	
<select id="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
_groups" <?php if ($_smarty_tpl->tpl_vars['FIELDINFO']->value['value']<1000000){?>style="display:none;"<?php }?>>
	<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['FIELDINFO']->value['groups'],'selected'=>$_smarty_tpl->tpl_vars['FIELDINFO']->value['value']),$_smarty_tpl);?>

</select>
<select id="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
_users" <?php if ($_smarty_tpl->tpl_vars['FIELDINFO']->value['value']>=1000000){?>style="display:none;"<?php }?>>
	<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['FIELDINFO']->value['users'],'selected'=>$_smarty_tpl->tpl_vars['FIELDINFO']->value['value']),$_smarty_tpl);?>

</select>

<?php }} ?>