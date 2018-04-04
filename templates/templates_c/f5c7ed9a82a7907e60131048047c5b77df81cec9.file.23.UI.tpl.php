<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:09
         compiled from "/var/www/html/zsitsms/templates/templates/UI/23.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18219813005abde12d1a1fb6-76818177%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f5c7ed9a82a7907e60131048047c5b77df81cec9' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/23.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18219813005abde12d1a1fb6-76818177',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
    'key' => 0,
    'option' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12d290a82_85660386',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12d290a82_85660386')) {function content_5abde12d290a82_85660386($_smarty_tpl) {?>
<input type="hidden"  name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
" />
<div id="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" ui="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
">	
	<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['FIELDINFO']->value['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value){
$_smarty_tpl->tpl_vars['option']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['option']->key;
?>
		<input type="checkbox" id="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"  value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"  <?php if ($_smarty_tpl->tpl_vars['option']->value['checked']){?> checked="checked" <?php }?> >
		<label for="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['option']->value['label'];?>
</label>
	<?php } ?>
</div>

<?php }} ?>