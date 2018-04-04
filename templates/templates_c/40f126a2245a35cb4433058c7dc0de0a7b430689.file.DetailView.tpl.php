<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:05
         compiled from "/var/www/html/zsitsms/templates/templates/Accounts/DetailView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5829794295abde1296c7c45-36732428%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '40f126a2245a35cb4433058c7dc0de0a7b430689' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/Accounts/DetailView.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5829794295abde1296c7c45-36732428',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'DETAILVIEW_DATAS' => 0,
    'MODULE_DETAILVIEW_LABEL' => 0,
    'MODULE_DETAILVIEW_ASSOCIATEBY' => 0,
    'tab' => 0,
    'name' => 0,
    'block' => 0,
    'row' => 0,
    'field' => 0,
    'MODULE' => 0,
    'DETAILVIEW_RECORDID' => 0,
    'DETAILVIEW_BUTTONS' => 0,
    'DETAILVIEW_CUSTOM_BUTTONS' => 0,
    'custom_button' => 0,
    'SCRIPTS' => 0,
    'RETURN_MODULE' => 0,
    'RETURN_ACTION' => 0,
    'RETURN_RECORDID' => 0,
    'DETAILVIEW_AUTO_EXECUTE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12a04c0d0_03380274',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12a04c0d0_03380274')) {function content_5abde12a04c0d0_03380274($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("TitleBar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_DATAS']->value){?>
<table border="0px" style="width:100%;font-size:12px;vertical-align:top;">
	<tr>
		<td style="vertical-align: top ;">
			<div id="detailview_info_tabs" style="min-height:450px;">
				<ul>
					
					<li><a href="#tabs-base"><?php echo $_smarty_tpl->tpl_vars['MODULE_DETAILVIEW_LABEL']->value;?>
</a></li>
					<?php  $_smarty_tpl->tpl_vars['tab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tab']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['MODULE_DETAILVIEW_ASSOCIATEBY']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tab']->key => $_smarty_tpl->tpl_vars['tab']->value){
$_smarty_tpl->tpl_vars['tab']->_loop = true;
?>
						<li><a href="<?php echo $_smarty_tpl->tpl_vars['tab']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['tab']->value['label'];?>
</a></li>
					<?php } ?>
 				</ul>

				<div id="tabs-base" style="padding:5px 0px 0px 0px;">
					<?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_DATAS']->value['have_block']){?>
						


						<?php  $_smarty_tpl->tpl_vars['block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['block']->_loop = false;
 $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['DETAILVIEW_DATAS']->value['blocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['block']->key => $_smarty_tpl->tpl_vars['block']->value){
$_smarty_tpl->tpl_vars['block']->_loop = true;
 $_smarty_tpl->tpl_vars['name']->value = $_smarty_tpl->tpl_vars['block']->key;
?>
						<div id="detailview_blocks_accordion_<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
"  style="text-align:left;">
							<h3 ><?php echo $_smarty_tpl->tpl_vars['block']->value['label'];?>
</h3>
							<div style="padding:1px;">
								<table class="client_detailview_table" cellspacing="0">
									<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['block']->value['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
										<tr>
											<?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['row']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
												<?php if ($_smarty_tpl->tpl_vars['field']->value['UI']==101){?>
												<input type="hidden" id="form[<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['field']->value['inter_value'];?>
" />
												<?php continue 1?>
												<?php }?>
												<td class="client_detailview_label_<?php echo $_smarty_tpl->tpl_vars['block']->value['cols'];?>
" >
													<label for="<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['field']->value['title'];?>
"><?php echo $_smarty_tpl->tpl_vars['field']->value['label'];?>
</label><?php if ($_smarty_tpl->tpl_vars['field']->value['mandatory']){?><span style="color:red">*</span><?php }?>
												</td>
												<td class="client_detailview_value_<?php echo $_smarty_tpl->tpl_vars['block']->value['cols'];?>
">

													<?php if ($_smarty_tpl->tpl_vars['field']->value['UI']==70){?>
														<?php echo $_smarty_tpl->getSubTemplate ("UI/70.UI.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('FIELDINFO'=>$_smarty_tpl->tpl_vars['field']->value), 0);?>

													<?php }else{ ?>

													    <div id="<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
" class="detailview_value_contenter">
														<input type="hidden" id="form[<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['field']->value['inter_value'];?>
" />
														<?php if ($_smarty_tpl->tpl_vars['field']->value['UI']==50||$_smarty_tpl->tpl_vars['field']->value['UI']==51||$_smarty_tpl->tpl_vars['field']->value['UI']==52||$_smarty_tpl->tpl_vars['field']->value['UI']==55){?>
															<a href="javascript:zswitch_load_client_view('index.php?module=<?php echo $_smarty_tpl->tpl_vars['field']->value['associate_module'];?>
&action=<?php echo $_smarty_tpl->tpl_vars['field']->value['associate_action'];?>
&recordid=<?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
&return_module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&return_action=detailView&return_recordid=<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_RECORDID']->value;?>
')" style="color:#1E90FF"><?php echo $_smarty_tpl->tpl_vars['field']->value['show_value'];?>
</a>
														<?php }elseif($_smarty_tpl->tpl_vars['field']->value['UI']==61){?>
															<a href="javascript:zswitch_callcenter_click_call_a('<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
',<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_RECORDID']->value;?>
,'<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
','<?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
');" title="点击呼叫:<?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
" style="color:#1E90FF"><?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
</a>
														<?php }else{ ?>
															<?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
&nbsp;
														<?php }?>
														<?php if ($_smarty_tpl->tpl_vars['field']->value['edit']){?>
															<a class="detailview_miss_edit" href="javascript:zswitch_open_missedit_dlg('<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
','<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
',<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_RECORDID']->value;?>
);" style="color:#1E90FF;display:none">编辑</a>
													    <?php }?>
														</div>

													<?php }?>
												</td>
											<?php } ?>
										</tr>
									<?php } ?>
								</table>
							</div>
						</div>
						<script type="text/javascript">
							$("#detailview_blocks_accordion_<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
").accordion({
								active:<?php if ($_smarty_tpl->tpl_vars['block']->value['active']){?>0 <?php }else{ ?> false <?php }?>,
								collapsible: true,
								heightStyle:"content" } );
						</script>
						<?php } ?>


					<?php }else{ ?>
						<table class="client_detailview_table" cellspacing="0">
							<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['DETAILVIEW_DATAS']->value['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
								<tr>
									<?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['row']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
										<?php if ($_smarty_tpl->tpl_vars['field']->value['UI']==101){?>  <?php continue 1?> <?php }?>
										<td class="client_detailview_label_<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_DATAS']->value['cols'];?>
" >
											<label for="<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['field']->value['title'];?>
"><?php echo $_smarty_tpl->tpl_vars['field']->value['label'];?>
</label>
											<?php if ($_smarty_tpl->tpl_vars['field']->value['mandatory']){?><span style="color:red">*</span><?php }?>
										</td>
										<td class="client_detailview_value_<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_DATAS']->value['cols'];?>
">
											<?php if ($_smarty_tpl->tpl_vars['field']->value['UI']==70){?>
												<?php echo $_smarty_tpl->getSubTemplate ("UI/70.UI.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('FIELDINFO'=>$_smarty_tpl->tpl_vars['field']->value), 0);?>

											<?php }else{ ?>
											    <div id="<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
" class="detailview_value_contenter">
													<?php if ($_smarty_tpl->tpl_vars['field']->value['UI']==50||$_smarty_tpl->tpl_vars['field']->value['UI']==51||$_smarty_tpl->tpl_vars['field']->value['UI']==52||$_smarty_tpl->tpl_vars['field']->value['UI']==55){?>
														<a href="javascript:zswitch_load_client_view('index.php?module=<?php echo $_smarty_tpl->tpl_vars['field']->value['associate_module'];?>
&action=<?php echo $_smarty_tpl->tpl_vars['field']->value['associate_action'];?>
&recordid=<?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
&return_module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&return_action=detailView&return_recordid=<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_RECORDID']->value;?>
')" style="color:#1E90FF"><?php echo $_smarty_tpl->tpl_vars['field']->value['show_value'];?>
</a>
													<?php }elseif($_smarty_tpl->tpl_vars['field']->value['UI']==61){?>
														<a href="javascript:zswitch_callcenter_click_call_a('<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
',<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_RECORDID']->value;?>
,'<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
','<?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
');" title="点击呼叫:<?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
" style="color:#1E90FF"><?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
</a>

													<?php }else{ ?>
														<?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
&nbsp;
													<?php }?>
													<?php if ($_smarty_tpl->tpl_vars['field']->value['edit']){?>
											    	<a class="detailview_miss_edit" href="javascript:zswitch_open_missedit_dlg('<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
','<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
',<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_RECORDID']->value;?>
);" style="color:#1E90FF;display:none">编辑</a>
													<?php }?>
											    </div>
											<?php }?>
										</td>
									<?php } ?>
								</tr>
							<?php } ?>
						</table>
					<?php }?>
				</div>

			</div>
		</td>
		<td style="vertical-align: top ;width:120px;">
		<?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_BUTTONS']->value['return']){?>
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="返回"   action="return" >返回</button><br/>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_BUTTONS']->value['back']){?>
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="上一条" action="back">上一条</button><br/>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_BUTTONS']->value['next']){?>
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="下一条" action="next">下一条</button><br/>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_BUTTONS']->value['edit']){?>
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="编辑"   action="edit">编辑</button><br/>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_BUTTONS']->value['delete']){?>
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="删除"   action="delete">删除</button><br/>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['DETAILVIEW_BUTTONS']->value['copy']){?>
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="复制"   action="copy">复制</button><br/>
		<?php }?>
		<br/>
		<?php  $_smarty_tpl->tpl_vars['custom_button'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['custom_button']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['DETAILVIEW_CUSTOM_BUTTONS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['custom_button']->key => $_smarty_tpl->tpl_vars['custom_button']->value){
$_smarty_tpl->tpl_vars['custom_button']->_loop = true;
?>
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small"
				title="<?php echo $_smarty_tpl->tpl_vars['custom_button']->value['title'];?>
"  onclick="<?php echo $_smarty_tpl->tpl_vars['custom_button']->value['command'];?>
"><?php echo $_smarty_tpl->tpl_vars['custom_button']->value['label'];?>
</button><br/>
		<?php } ?>
		</td>
	</tr>
</table>
<?php }else{ ?>
	<div >
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="height:80px;line-height:80px;margin-top: 20px; padding: 0 .7em;text-align:left">
				<table><tr>
				<td>
				<span class="ui-icon ui-icon-info" ></span></td><td>
				<strong>对不起！</strong> 没有满足条件的记录可显示。
				</td></tr></table>
			</div>
		</div>
	</div>
<?php }?>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['SCRIPTS']->value;?>
/zswitch-accounts.js"></script>
<script type="text/javascript">
	var module = "<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
";
	var recordid = "<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_RECORDID']->value;?>
";
	var return_module = "<?php echo $_smarty_tpl->tpl_vars['RETURN_MODULE']->value;?>
";
	var return_action = "<?php echo $_smarty_tpl->tpl_vars['RETURN_ACTION']->value;?>
";
	var return_recordid = "<?php echo $_smarty_tpl->tpl_vars['RETURN_RECORDID']->value;?>
";
	
	$(".client_detailview_operation_button").button().click(function(){
		var action = $(this).attr("action");
		if(action == "return")
		{
			zswitch_load_client_view("index.php?module="+return_module+"&action="+return_action+"&recordid="+return_recordid);
		}
		else if(action == "edit")
		{
			zswitch_load_client_view("index.php?module="+module+"&action=editView&recordid="+recordid+"&return_module="+module+"&return_action=detailView&return_recordid="+recordid);
		}
		else if(action == "copy")
		{
			zswitch_load_client_view("index.php?module="+module+"&action=copyView&recordid="+recordid);
		}
		else if(action == "delete")
		{
			zswitch_listview_operation_delete(module,recordid);
		}
		else if(action == "back")
		{
			zswitch_load_client_view("index.php?module="+module+"&action=detailView&recordid="+recordid+"&operation=prev");
		}
		else if(action == "next")
		{
			zswitch_load_client_view("index.php?module="+module+"&action=detailView&recordid="+recordid+"&operation=next");
		}
	});

	$("#detailview_info_tabs" ).tabs({
		//1.9之后cache被移除,手动实现cache功能
		beforeLoad: function( event, ui ) {
            if ( ui.tab.data( "loaded" ) ) {
				event.preventDefault();
				return;
            }

            ui.jqXHR.success(function() {
				ui.tab.data( "loaded", true );
            });
        }
	});
	$(".detailview_value_contenter").mouseenter(function(){
		modifyobj = $(this).find(".detailview_miss_edit");
		modifyobj.show();
		modifyobj.css("position","absolute");
		offset = $(this).position();
		modifyobj.css("top",offset.top);
		modifyobj.css("left",offset.left+$(this).width()-modifyobj.width());

	});
	$(".detailview_value_contenter").mouseleave(function(){
		$(this).find(".detailview_miss_edit").hide();
	});
	$(".detailview_miss_edit").hide();
	
	<?php echo $_smarty_tpl->tpl_vars['DETAILVIEW_AUTO_EXECUTE']->value;?>

</script>

<?php }} ?>