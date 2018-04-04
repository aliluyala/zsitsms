<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:04:56
         compiled from "/var/www/html/zsitsms/templates/templates/UI/27.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2811254335abde1983bf1f1-12001867%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '25eb98504074140eeb65aafbf2764e6a5b6c862e' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/27.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2811254335abde1983bf1f1-12001867',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
    'opt' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde198489083_73492952',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde198489083_73492952')) {function content_5abde198489083_73492952($_smarty_tpl) {?>

<select name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
" mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
" 
 module="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['module'];?>
" selected_value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
">
 <?php  $_smarty_tpl->tpl_vars['opt'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['opt']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FIELDINFO']->value['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['opt']->key => $_smarty_tpl->tpl_vars['opt']->value){
$_smarty_tpl->tpl_vars['opt']->_loop = true;
?>
	<option value="<?php echo $_smarty_tpl->tpl_vars['opt']->value['value'];?>
" <?php if ($_smarty_tpl->tpl_vars['opt']->value['value']==$_smarty_tpl->tpl_vars['FIELDINFO']->value['value']){?>  selected="selected" <?php }?> ><?php echo $_smarty_tpl->tpl_vars['opt']->value['show'];?>
</option>
 <?php } ?>
</select>
<?php }} ?>