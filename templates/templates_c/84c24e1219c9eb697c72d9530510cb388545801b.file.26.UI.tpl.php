<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:04:56
         compiled from "/var/www/html/zsitsms/templates/templates/UI/26.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4870808705abde1985ec141-11521906%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '84c24e1219c9eb697c72d9530510cb388545801b' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/26.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4870808705abde1985ec141-11521906',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde198673571_23498190',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde198673571_23498190')) {function content_5abde198673571_23498190($_smarty_tpl) {?>

<select name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
" mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
" 
	picklist_group_field = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['picklist_group_field'];?>
" picklist_table_name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['picklist_table_name'];?>
"
	picklist_items_field = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['picklist_items_field'];?>
" 	picklist_filter_field = <?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['picklist_filter_field'];?>

	id="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" picklist_value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
">
</select>
<?php }} ?>