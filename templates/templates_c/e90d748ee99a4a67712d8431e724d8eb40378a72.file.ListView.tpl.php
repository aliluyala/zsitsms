<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:04:57
         compiled from "/var/www/html/zsitsms/templates/templates/Accounts/ListView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19877484185abde19904da17-02529688%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e90d748ee99a4a67712d8431e724d8eb40378a72' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/Accounts/ListView.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19877484185abde19904da17-02529688',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'LSITVIEW_SEARCH_UI' => 0,
    'ui' => 0,
    'LISTVIEW_QUERY_WHERE_JSON' => 0,
    'LSITVIEW_SEARCH_UI_JSON' => 0,
    'ACTION' => 0,
    'LISTVIEW_RECORD_PAGE' => 0,
    'LISTVIEW_ORDER_BY' => 0,
    'LISTVIEW_ORDER' => 0,
    'LISTVIEW_BAR_ALLOW' => 0,
    'LISTVIEW_BUTTONS' => 0,
    'LISTVIEW_RECORD_START' => 0,
    'LISTVIEW_RECORD_END' => 0,
    'LISTVIEW_RECORD_TOTAL' => 0,
    'LISTVIEW_RECORD_PAGECOUNT' => 0,
    'LISTVIEW_FILTER_LIST' => 0,
    'LISTVIEW_SELECTED_FILTER' => 0,
    'LISTVIEW_SELECTER_ALLOW' => 0,
    'LISTVIEW_HEADERS' => 0,
    'col' => 0,
    'name' => 0,
    'IMAGES' => 0,
    'LISTVIEW_OPERATION_ALLOW' => 0,
    'LISTVIEW_DATA' => 0,
    'id' => 0,
    'row' => 0,
    'field' => 0,
    'LISTVIEW_OPERATIONS' => 0,
    'oper' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde199452e28_68265931',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde199452e28_68265931')) {function content_5abde199452e28_68265931($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/zsitsms/include/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("TitleBarA.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_search_dlg" title="搜索...">
	<div id="search_ui_list" style="display:none">

		<div id="search_ui_select_field">
			<select name = "search_select_field" >
				<?php  $_smarty_tpl->tpl_vars['ui'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ui']->_loop = false;
 $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LSITVIEW_SEARCH_UI']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ui']->key => $_smarty_tpl->tpl_vars['ui']->value){
$_smarty_tpl->tpl_vars['ui']->_loop = true;
 $_smarty_tpl->tpl_vars['field']->value = $_smarty_tpl->tpl_vars['ui']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['ui']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['ui']->value['label'];?>
</option>
				<?php } ?>
			</select>
		</div>
		<div id="search_ui_condition_num">
			<select name="select_condition">
				<option value="=">等于</option>
				<option value="!=">不等于</option>
				<option value=">">大于</option>
				<option value=">=">大于等于</option>
				<option value="<">小于</option>
				<option value="<=">小于等于</option>
			</select>
		</div>
		<div id="search_ui_condition_str">
			<select name="select_condition">
				<option value="=">等于</option>
				<option value="!=">不等于</option>
				<option value="like_start">开始是</option>
				<option value="like_end">结束是</option>
				<option value="like_contain">包含</option>
				<option value="like_no_contain">不包含</option>
			</select>
		</div>
		<div id="search_ui_link">
			<select name="select_link">
				<option value="and">与</option>
				<option value="or">或</option>
			</select>
		</div>
		<div id="search_ui_delete_but">
			<button class="listview_search_button_one" style="font-size:10px" action="delete_condition">删除</button>
		</div>
	</div>
	<form id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_search_form">
	<table id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_search_condition_list" class="client_listview_table small" cellspacing="0" style="text-align:center">
		<tr>
			<th>字段</th><th>条件</th><th>值</th><th>联接</th><th>&nbsp;</th>
		</tr>
	</table>
	</form>
	<button class="listview_search_button" style="font-size:10px" action="add_conditin">增加条件</button >
	<button class="listview_search_button" style="font-size:10px" action="delete_all_condition">删除全部条件</button>


</div>
<script>
	var search_query_where = $.evalJSON(decodeURIComponent("<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_QUERY_WHERE_JSON']->value;?>
"));
	var search_ui = $.evalJSON(decodeURIComponent("<?php echo $_smarty_tpl->tpl_vars['LSITVIEW_SEARCH_UI_JSON']->value;?>
"));
	$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
	$("#<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_search_dlg").dialog({
		autoOpen: false,
		height: 500,
		width: 620,
		modal: true,
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		buttons: {
		"确定":function(){
			zswitch_create_search_conditin("<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
");
			$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
			$("#client_listview_table_form").children("[name=record_page]").val("1");
			var url = "index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&action=<?php echo $_smarty_tpl->tpl_vars['ACTION']->value;?>
";
			zswitch_load_client_view(url,"client_listview_table_form");
			$( this ).dialog("close");
		},
		"取消":function(){
			$( this ).dialog("close");
		}
		},
		open:function(){
			zswitch_init_search_conditin("<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
",search_query_where);
		}
	});
	$(".listview_search_button").button().click(function(){
		var  action = $(this).attr("action");
		if(action  == "add_conditin")
		{
			zswitch_add_search_conditin("<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
");
		}
		else if(action == "delete_all_condition")
		{
			$("#<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_search_condition_list").find("tr:gt(0)").remove();
		}
		return false;
	});
</script>

<form id="client_listview_table_form" onsubmit="return false;" style="margin:0px">
<input name="record_page"  type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_RECORD_PAGE']->value;?>
"/>
<input name="order_by"     type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ORDER_BY']->value;?>
"/>
<input name="order"        type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ORDER']->value;?>
"/>
<input name="query_where"  type="hidden" value=""/>
<input name="module"       type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
"/>
<input name="action"       type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['ACTION']->value;?>
"/>


<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_BAR_ALLOW']->value){?>
<div class="client_listview_bar small">
	<table border="0" cellspacing="0"  width="100%">
		<tr>
			<!-- 批量操作按钮-->
			<td style="text-align:left;">
			<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_BUTTONS']->value['delete']){?>
				<button class="client_listview_button small" title="批量删除" action="batch_delete">批量删除</button>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_BUTTONS']->value['modify']){?>
				<button class="client_listview_button small" title="批量修改" action="batch_modify">批量修改</button>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_BUTTONS']->value['today_appointment']){?>
				<button class="client_listview_button small" title="今日预约" action="today_appointment">今日预约</button>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_BUTTONS']->value['tomorrow_appointment']){?>
				<button class="client_listview_button small" title="明日预约" action="tomorrow_appointment">明日预约</button>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_BUTTONS']->value['after_tomorrow_appointment']){?>
				<button class="client_listview_button small" title="后日预约" action="after_tomorrow_appointment">后日预约</button>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_BUTTONS']->value['total_show']){?>
				<button class="client_listview_button small" title="全部" action="total_show">全部</button>
			<?php }?>
			</td>
			<td style="text-align:right;">
				显示:<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_RECORD_START']->value;?>
-<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_RECORD_END']->value;?>
 共计:<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_RECORD_TOTAL']->value;?>
条 |
				<a href="javascript:zswitch_client_listview_page_ctrl(1,'client_listview_table_form');" title="跳转到第一页">首页</a> |
				<a href="javascript:zswitch_client_listview_page_ctrl(<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_RECORD_PAGE']->value;?>
-1,'client_listview_table_form');" title="跳转到上一页">上页</a> |
				<input name="current_page" style="width:30px;font-size:10px" value="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_RECORD_PAGE']->value;?>
"
					onchange="zswitch_client_listview_page_ctrl(this.value,'client_listview_table_form');"	/> /<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_RECORD_PAGECOUNT']->value;?>
 |
				<a href="javascript:zswitch_client_listview_page_ctrl(<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_RECORD_PAGE']->value;?>
+1,'client_listview_table_form');" title="跳转到下一页">下页<a/> |
				<a href="javascript:zswitch_client_listview_page_ctrl(99999999999,'client_listview_table_form');" title="跳转到最后一页">尾页</a> |
				过滤:
				<select name ="filterid" style="font-size:10px;"
					onchange="zswitch_load_client_view('index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&action=<?php echo $_smarty_tpl->tpl_vars['ACTION']->value;?>
','client_listview_table_form')">
					<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['LISTVIEW_FILTER_LIST']->value,'selected'=>$_smarty_tpl->tpl_vars['LISTVIEW_SELECTED_FILTER']->value),$_smarty_tpl);?>

				</select> <a href="javascript:zswitch_listview_filter_modify_dlg('<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
');" title="编辑、修改过滤条件">编辑</a>
			</td>
		</tr>
	</table>
</div>
<?php }?>
<div style="margin-top:3px;">

<table id="client_listview_table" class="client_listview_table small" cellspacing="0" >
	<tr>
		<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_SELECTER_ALLOW']->value){?>
			<th width="30px"><input id="select_record_all" type="checkbox" title="全选"/></th>
		<?php }?>
		<?php  $_smarty_tpl->tpl_vars['col'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['col']->_loop = false;
 $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['col']->key => $_smarty_tpl->tpl_vars['col']->value){
$_smarty_tpl->tpl_vars['col']->_loop = true;
 $_smarty_tpl->tpl_vars['name']->value = $_smarty_tpl->tpl_vars['col']->key;
?>
			<th>
				<?php if ($_smarty_tpl->tpl_vars['col']->value['allow_order']){?>
					
					<a href="javascript:zswitch_client_listview_order_ctrl('<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
','<?php echo $_smarty_tpl->tpl_vars['col']->value['order'];?>
','client_listview_table_form')"
						title="点击重新排序"><?php echo $_smarty_tpl->tpl_vars['col']->value['label'];?>

					<?php if ($_smarty_tpl->tpl_vars['col']->value['order']=="ASC"){?>
						<img style="border-style: none;" src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/arrow_up_red.gif" />
					<?php }elseif($_smarty_tpl->tpl_vars['col']->value['order']=="DESC"){?>
						<img style="border-style: none;" src="<?php echo $_smarty_tpl->tpl_vars['IMAGES']->value;?>
/arrow_down_red.gif" />
					<?php }?>
					</a>
				<?php }else{ ?>
					<?php echo $_smarty_tpl->tpl_vars['col']->value['label'];?>

				<?php }?>
			</th>
		<?php } ?>
		<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_OPERATION_ALLOW']->value){?>
			<th>操作</th>
		<?php }?>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_DATA']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
		<tr>
			<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_SELECTER_ALLOW']->value){?>
				<td><input type="checkbox" name="selected_records[]" value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"/></td>
			<?php }?>
			<?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_smarty_tpl->tpl_vars['fieldname'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['row']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
 $_smarty_tpl->tpl_vars['fieldname']->value = $_smarty_tpl->tpl_vars['field']->key;
?>
				<td>
				<?php if ($_smarty_tpl->tpl_vars['field']->value['have_associate']){?>
					<a href="<?php echo $_smarty_tpl->tpl_vars['field']->value['associate_to'];?>
" recordid="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>
</a>
				<?php }else{ ?>
					<?php echo $_smarty_tpl->tpl_vars['field']->value['value'];?>

				<?php }?>
				&nbsp;
				</td>
			<?php } ?>
			<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_OPERATION_ALLOW']->value){?>
				<td>
				<?php  $_smarty_tpl->tpl_vars['oper'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['oper']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_OPERATIONS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['oper']->key => $_smarty_tpl->tpl_vars['oper']->value){
$_smarty_tpl->tpl_vars['oper']->_loop = true;
?>
					<a href="javascript:void(0);" recordid="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" onclick="<?php echo $_smarty_tpl->tpl_vars['oper']->value['url'];?>
" ><?php echo $_smarty_tpl->tpl_vars['oper']->value['name'];?>
</a>	|
				<?php } ?>
				</td>
			<?php }?>
		</tr>
	<?php } ?>
</table>
	<?php if ($_smarty_tpl->tpl_vars['LISTVIEW_RECORD_TOTAL']->value==0){?>
	<div >
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="height:80px;line-height:80px;margin-top: 20px; padding: 0 .7em;text-align:left">
				<table><tr>
				<td>
				<span class="ui-icon ui-icon-info" ></span></td><td>
				<strong>对不起！</strong> 没有满足条件的记录可显示，请重新确认你的查询条件。
				</td></tr></table>
			</div>
		</div>
	</div>
	<?php }?>
</div>
</form>
<script type="text/javascript" >
	var listModuleName = "<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
";
	
		zswitch_client_listview_table_init("client_listview_table");
		$("#select_record_all").click(function(){
			if($(this).is(":checked"))
			{
				$("[name^=selected_records]").prop("checked",true);
			}
			else
			{
				$("[name^=selected_records]").prop("checked",false);
			}
		});
		$("[name^=selected_records]").click(function(){
			if(!$(this).is(":checked"))
			{
				$("#select_record_all").prop("checked",false);
			}
			else
			{
				$("#select_record_all").prop("checked",true);
				$("[name^=selected_records]").each(function(){
					if(!$(this).is(":checked"))
					{
						$("#select_record_all").prop("checked",false);
					}
				});
			}
		});
		$(".client_listview_button").button().click(function(){
			var oper = $(this).attr("action");
			//alert(oper);
			if(oper == 'batch_delete')
			{
				zswitch_listview_batch_delete_dlg(listModuleName);
			}
			else if(oper == 'batch_modify')
			{
				zswitch_listview_batch_modify_dlg(listModuleName);
			}
			else if(oper == 'today_appointment')
			{
				var today=new Date();
				var st = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' 00:00:00';
				var et = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' 23:59:59';
				search_query_where = new Array();
				search_query_where[0] = new Array();
				search_query_where[0][0]='preset_time';
				search_query_where[0][1]='>=';
				search_query_where[0][2]=st;
				search_query_where[0][3]='and';
				search_query_where[0][4]='';
				search_query_where[1] = new Array();
				search_query_where[1][0]='preset_time';
				search_query_where[1][1]='<=';
				search_query_where[1][2]=et;
				search_query_where[1][3]='and';
				search_query_where[1][4]='';
				search_query_where[2] = new Array();
				search_query_where[2][0]='status';
				search_query_where[2][1]='!=';
				search_query_where[2][2]='FIRST_DIAL';
				search_query_where[2][3]='and';
				search_query_where[2][4]='';
				search_query_where[3] = new Array();
				search_query_where[3][0]='status';
				search_query_where[3][1]='!=';
				search_query_where[3][2]='FAILED';
				search_query_where[3][3]='and';
				search_query_where[3][4]='';
				search_query_where[4] = new Array();
				search_query_where[4][0]='status';
				search_query_where[4][1]='!=';
				search_query_where[4][2]='INVALID';
				search_query_where[4][3]='and';
				search_query_where[4][4]='';
				search_query_where[5] = new Array();
				search_query_where[5][0]='status';
				search_query_where[5][1]='!=';
				search_query_where[5][2]='SUCCESS';
				search_query_where[5][3]='';
				search_query_where[5][4]='';
				$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
				$("#client_listview_table_form").children("[name=record_page]").val("1");
				var url = "index.php?module=Accounts&action=index";
				zswitch_load_client_view(url,"client_listview_table_form");
			}
			else if(oper == 'tomorrow_appointment')
			{
				var today=new Date();
				var stamp = today.getTime() + 24*3600*1000;
				var tomorrow = new Date();
				tomorrow.setTime(stamp);
				var st = tomorrow.getFullYear()+'-'+(tomorrow.getMonth()+1)+'-'+tomorrow.getDate()+' 00:00:00';
				var et = tomorrow.getFullYear()+'-'+(tomorrow.getMonth()+1)+'-'+tomorrow.getDate()+' 23:59:59';
				search_query_where = new Array();
				search_query_where[0] = new Array();
				search_query_where[0][0]='preset_time';
				search_query_where[0][1]='>=';
				search_query_where[0][2]=st;
				search_query_where[0][3]='and';
				search_query_where[0][4]='';
				search_query_where[1] = new Array();
				search_query_where[1][0]='preset_time';
				search_query_where[1][1]='<=';
				search_query_where[1][2]=et;
				search_query_where[1][3]='and';
				search_query_where[1][4]='';
				search_query_where[2] = new Array();
				search_query_where[2][0]='status';
				search_query_where[2][1]='!=';
				search_query_where[2][2]='FIRST_DIAL';
				search_query_where[2][3]='and';
				search_query_where[2][4]='';
				search_query_where[3] = new Array();
				search_query_where[3][0]='status';
				search_query_where[3][1]='!=';
				search_query_where[3][2]='FAILED';
				search_query_where[3][3]='and';
				search_query_where[3][4]='';
				search_query_where[4] = new Array();
				search_query_where[4][0]='status';
				search_query_where[4][1]='!=';
				search_query_where[4][2]='INVALID';
				search_query_where[4][3]='and';
				search_query_where[4][4]='';
				search_query_where[5] = new Array();
				search_query_where[5][0]='status';
				search_query_where[5][1]='!=';
				search_query_where[5][2]='SUCCESS';
				search_query_where[5][3]='';
				search_query_where[5][4]='';

				$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
				$("#client_listview_table_form").children("[name=record_page]").val("1");
				var url = "index.php?module=Accounts&action=index";
				zswitch_load_client_view(url,"client_listview_table_form");
			}
			else if(oper == 'after_tomorrow_appointment')
			{
				var today=new Date();
				var stamp = today.getTime() + 48*3600*1000;
				var after_tomorrow = new Date();
				after_tomorrow.setTime(stamp);
				var st = after_tomorrow.getFullYear() + '-' + (after_tomorrow.getMonth() + 1) + '-' + after_tomorrow.getDate() + ' 00:00:00';
				var et = after_tomorrow.getFullYear() + '-' + (after_tomorrow.getMonth() + 1) + '-' + after_tomorrow.getDate() + ' 23:59:59';
				search_query_where = new Array();
				search_query_where[0] = new Array();
				search_query_where[0][0]='preset_time';
				search_query_where[0][1]='>=';
				search_query_where[0][2]=st;
				search_query_where[0][3]='and';
				search_query_where[0][4]='';
				search_query_where[1] = new Array();
				search_query_where[1][0]='preset_time';
				search_query_where[1][1]='<=';
				search_query_where[1][2]=et;
				search_query_where[1][3]='and';
				search_query_where[1][4]='';
				search_query_where[2] = new Array();
				search_query_where[2][0]='status';
				search_query_where[2][1]='!=';
				search_query_where[2][2]='FIRST_DIAL';
				search_query_where[2][3]='and';
				search_query_where[2][4]='';
				search_query_where[3] = new Array();
				search_query_where[3][0]='status';
				search_query_where[3][1]='!=';
				search_query_where[3][2]='FAILED';
				search_query_where[3][3]='and';
				search_query_where[3][4]='';
				search_query_where[4] = new Array();
				search_query_where[4][0]='status';
				search_query_where[4][1]='!=';
				search_query_where[4][2]='INVALID';
				search_query_where[4][3]='and';
				search_query_where[4][4]='';
				search_query_where[5] = new Array();
				search_query_where[5][0]='status';
				search_query_where[5][1]='!=';
				search_query_where[5][2]='SUCCESS';
				search_query_where[5][3]='';
				search_query_where[5][4]='';
				$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
				$("#client_listview_table_form").children("[name=record_page]").val("1");
				var url = "index.php?module=Accounts&action=index";
				zswitch_load_client_view(url,"client_listview_table_form");
			}
			else if(oper == 'total_show')
			{
				search_query_where = new Array();
				$("#client_listview_table_form").children("[name=query_where]").val($.toJSON(search_query_where));
				$("#client_listview_table_form").children("[name=record_page]").val("1");
				var url = "index.php?module=Accounts&action=index";
				zswitch_load_client_view(url,"client_listview_table_form");
			}
		});
	
</script>

<?php }} ?>