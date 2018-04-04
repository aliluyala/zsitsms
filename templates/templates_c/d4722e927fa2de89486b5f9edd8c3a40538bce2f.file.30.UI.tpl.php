<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:09
         compiled from "/var/www/html/zsitsms/templates/templates/UI/30.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6180375295abde12d069709-87213060%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd4722e927fa2de89486b5f9edd8c3a40538bce2f' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/30.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6180375295abde12d069709-87213060',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12d0c93d0_42810814',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12d0c93d0_42810814')) {function content_5abde12d0c93d0_42810814($_smarty_tpl) {?>
<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
" 
	mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
"  id="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
"/>


<?php }} ?>