<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:08
         compiled from "/var/www/html/zsitsms/templates/templates/UI/17.UI.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13343637525abde12c85b994-07265674%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c56f4c741b6a2c9b7156e769c93e54f0869ee00c' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/UI/17.UI.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13343637525abde12c85b994-07265674',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELDINFO' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12c96bbb5_68577070',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12c96bbb5_68577070')) {function content_5abde12c96bbb5_68577070($_smarty_tpl) {?>
<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['name'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['value'];?>
" ui = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['UI'];?>
"
	mandatory = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['mandatory'];?>
" label = "<?php echo $_smarty_tpl->tpl_vars['FIELDINFO']->value['label'];?>
" readonly="readonly" class="responsive_width_98" /><?php }} ?>