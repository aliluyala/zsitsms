<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:04:56
         compiled from "/var/www/html/zsitsms/templates/templates/UI/9.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5122947665abde198a057c2-68208875%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bf977ccdcb043d19f4799f3a2e28378b855dd83b' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/9.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5122947665abde198a057c2-68208875',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde198a74756_75039485',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde198a74756_75039485')) {function content_5abde198a74756_75039485($_smarty_tpl) {?>
<textarea name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" rows="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['rows'];?>
" cols="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['cols'];?>
" wrap="virtua"
	ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
" mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
" class="responsive_width_99" ><?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
</textarea>
<?php }} ?>