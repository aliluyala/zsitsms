<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:08
         compiled from "/var/www/html/zsitsms/templates/templates/UI/8.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19145975245abde12ce4d619-86612328%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '16ff8235f760eea371c8b3e6b10de62f3238e64c' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/8.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19145975245abde12ce4d619-86612328',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12ceca1c3_04066602',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12ceca1c3_04066602')) {function content_5abde12ceca1c3_04066602($_smarty_tpl) {?>
<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
" 
	mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
"  min="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['min'];?>
"  
	decimal="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['decimal'];?>
" max="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['max'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['width'];?>
" /> <span><?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['unit'];?>
</span>
<?php }} ?>