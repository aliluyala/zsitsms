<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:09
         compiled from "/var/www/html/zsitsms/templates/templates/UI/31.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18091566535abde12d3a9436-95608841%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '674f386470c3739b87debb230f2c1d7e23b58eca' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/31.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18091566535abde12d3a9436-95608841',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12d408e29_48456805',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12d408e29_48456805')) {function content_5abde12d408e29_48456805($_smarty_tpl) {?>

<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
" readonly = "readonly"
	mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
"  id="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" style="width:140px;"/>


<?php }} ?>