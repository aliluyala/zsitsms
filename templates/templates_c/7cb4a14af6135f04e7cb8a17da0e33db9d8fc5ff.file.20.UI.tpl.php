<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:08
         compiled from "/var/www/html/zsitsms/templates/templates/UI/20.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11526842095abde12ccb9687-85866343%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7cb4a14af6135f04e7cb8a17da0e33db9d8fc5ff' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/20.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11526842095abde12ccb9687-85866343',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12cd7ac00_32105976',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12cd7ac00_32105976')) {function content_5abde12cd7ac00_32105976($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/zsitsms/include/Smarty/plugins/function.html_options.php';
?>

<select name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
" mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
"  id="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" >
<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['FIELDINFO']->value['options'],'selected'=>$_smarty_tpl->tpl_vars['FIELDINFO']->value['value']),$_smarty_tpl);?>

</select>
<?php }} ?>