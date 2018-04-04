<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:08
         compiled from "/var/www/html/zsitsms/templates/templates/UI/101.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8194870005abde12cbb5090-92460958%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '82f0cd1352392a55f69a6433e9bc8f68968e4f4c' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/101.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8194870005abde12cbb5090-92460958',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12cc0e960_40549512',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12cc0e960_40549512')) {function content_5abde12cc0e960_40549512($_smarty_tpl) {?>
<input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
"
	mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" 	label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
"  /><?php }} ?>