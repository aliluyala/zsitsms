<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:25:04
         compiled from "/var/www/html/zsitsms/templates/templates/ErrorMessage1.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3545781635abde650d7e170-89006838%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '01aea38f58525db28a7c57c91ccc1e05360cfcf9' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/ErrorMessage1.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3545781635abde650d7e170-89006838',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ERROR_MESSAGE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde650e4c885_55289897',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde650e4c885_55289897')) {function content_5abde650e4c885_55289897($_smarty_tpl) {?>
<div class="ui-widget" style="height:40px">
	<div class="ui-state-error ui-corner-all" style="height:40px;margin:10px 10px 0px 10px; padding: 0.7em;text-align:left">
		<p><span class="ui-icon ui-icon-info" style="float:left; margin-right:.3em;"></span>
		<?php echo $_smarty_tpl->tpl_vars['ERROR_MESSAGE']->value;?>
</p>
	</div>
</div><?php }} ?>