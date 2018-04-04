<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:08
         compiled from "/var/www/html/zsitsms/templates/templates/UI/7.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20853839645abde12ca27e44-57056296%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '36409c72714d4810ee095b472984d58e3cad044f' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/7.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20853839645abde12ca27e44-57056296',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12caae157_73490460',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12caae157_73490460')) {function content_5abde12caae157_73490460($_smarty_tpl) {?>
<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
" 
	mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
"  min_len="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['min_len'];?>
"  
	max_len="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['max_len'];?>
"  regexp="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['regexp'];?>
" regtitle = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['regtitle'];?>
"  style="width:<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['width'];?>
"  /><?php }} ?>