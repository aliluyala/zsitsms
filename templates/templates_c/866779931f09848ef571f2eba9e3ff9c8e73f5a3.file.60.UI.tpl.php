<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:04:56
         compiled from "/var/www/html/zsitsms/templates/templates/UI/60.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11164859265abde198198220-28803953%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '866779931f09848ef571f2eba9e3ff9c8e73f5a3' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/60.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11164859265abde198198220-28803953',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde1982d42b4_94668052',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde1982d42b4_94668052')) {function content_5abde1982d42b4_94668052($_smarty_tpl) {?>
<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
"
	mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
" class="responsive_width_98" /><?php }} ?>