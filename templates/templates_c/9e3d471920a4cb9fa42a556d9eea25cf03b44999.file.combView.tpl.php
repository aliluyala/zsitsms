<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:07
         compiled from "/var/www/html/zsitsms/templates/templates/PolicyCalculateCom/combView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20207212185abde12b86c681-35990021%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9e3d471920a4cb9fa42a556d9eea25cf03b44999' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/PolicyCalculateCom/combView.tpl',
      1 => 1520839846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20207212185abde12b86c681-35990021',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'VIN_NO' => 0,
    'ACCOUNTID' => 0,
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12b997192_74596786',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12b997192_74596786')) {function content_5abde12b997192_74596786($_smarty_tpl) {?><table border=0 width="100%">
<tr>
<td style="width:130px;vertical-align:top;border:1px solid #79b7e7;text-align:left;">
<div style="font-size:13px;padding-left:10px;height:30px;line-height:30px;background-color:#79b7e7;color:#FFFFFF;font-weight: bold;">
	<span>历史算价</span>
<a id="policy_calculate_create" title="新建..." href="javascript:void(0);" style="border-radius: 3px;margin-left:60px;padding:0px 4px 0px 4px;font-size:20px;background-color:#FFFFFF;font-weight: bold;">
+
</a>
</div>
<div id="policy_calculate_list_box">

</div>

</td>

<td  id="policy_calculate_content"  style="vertical-align:top;border:1px solid #79b7e7;">

</td>
</tr>

</table>

<script>
var policy_vin_no = "<?php echo $_smarty_tpl->tpl_vars['VIN_NO']->value;?>
";
var accountid = "<?php echo $_smarty_tpl->tpl_vars['ACCOUNTID']->value;?>
";
var pc_module = "<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
";



	function loadCalculateList(){
		$.get("index.php?module="+pc_module+"&action=getCalListAjax&vin="+policy_vin_no,function(res){
			
			if(res.type = "success")
			{
				data = res.data;
				$("#policy_calculate_list_box").empty();
				for(idx=0;idx<data.length;idx++)
				{
					var html = '<div recordid = "'+data[idx].ID+'" title="'+data[idx].SUMMARY+'" style="padding:2px;border-bottom:1px solid #EDEDED;">'; 
					html += '<span style="font-size:12px;">'+data[idx].CAL_NO+'</span><br/>';
					html += '<span style="font-size:10px;color:#ACACAC;">'+data[idx].MODIFY_TIME+'</span></div>';
					$("#policy_calculate_list_box").append(html);
				}
				$("#policy_calculate_list_box").children("div")
				.mouseenter(function(){
					$(this).css("background-color","#f5f8f9;");
					$(this).css("cursor","pointer");
				})
				.mouseleave(function(){
					$(this).css("background-color","#FFFFFF");
					$(this).css("cursor","default");
				})
				.data("pc_module",pc_module)
				.click(function(){
					var recordid = $(this).attr("recordid");
					var url = "index.php?module="+$(this).data("pc_module")+"&action=calculateView&recordid=" + recordid;
					$("#policy_calculate_content").load(url);
				});				

			}
		
		},'json');	
	};
		
	$("#policy_calculate_create").data("pc_module",pc_module).click(function(){
		$("#policy_calculate_content").load("index.php?module="+$(this).data("pc_module")+"&action=calculateView&recordid=&vin_no="+policy_vin_no);

	});
	loadCalculateList();	
	$("#policy_calculate_content").load("index.php?module="+pc_module+"&action=calculateView&recordid=&vin_no="+policy_vin_no+"&accountid="+accountid);	

</script><?php }} ?>