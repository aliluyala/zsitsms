<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:14:51
         compiled from "/var/www/html/zsitsms/templates/templates/PolicyCalculateCom/queryBuyingPrice.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17209975735abde1331aec85-75083829%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dd8fac506b6fd66273a054f2bc7f9b2cccc5af1c' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/PolicyCalculateCom/queryBuyingPrice.tpl',
      1 => 1522394074,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17209975735abde1331aec85-75083829',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde133359545_06543762',
  'variables' => 
  array (
    'VEHICLE_MODEL' => 0,
    'MODULE' => 0,
    'RATE_TABLE' => 0,
    'VIN_NO' => 0,
    'DATAS' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde133359545_06543762')) {function content_5abde133359545_06543762($_smarty_tpl) {?><div id = "abc">
<div style="margin:0px 0px 3px 20px;"><span><input id="vehicle_model" value="<?php echo $_smarty_tpl->tpl_vars['VEHICLE_MODEL']->value;?>
"/></span><span><button id="vehicle_model_search">搜索</button></span></div>
<table id="buying_price_list"></table>
<div id="buying_price_list_pager"></div>
<script>
var pc_module = "<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
";
var vehicle_model  = "<?php echo $_smarty_tpl->tpl_vars['VEHICLE_MODEL']->value;?>
";
var rate_table = "<?php echo $_smarty_tpl->tpl_vars['RATE_TABLE']->value;?>
";
var vin_no = "<?php echo $_smarty_tpl->tpl_vars['VIN_NO']->value;?>
";
var mydata = <?php echo $_smarty_tpl->tpl_vars['DATAS']->value;?>
;

$(function(){
  pageInit();
});

function pageInit(){

	var grid = $("#buying_price_list").data("module",pc_module).jqGrid({
	  	datatype: 'local',
		colNames : [ '车型代码','车型名称','车型别名','新车购置价','排气量(L)','核定载客','整备质量(KG)','上市年份','核定载质量','行业车型代码','车辆模型代码'],
		colModel : [
		             {name : 'vehicleId'             ,index : 'vehicleId',sortable:false,align:'center',width:400},
					 {name : 'vehicleName'           ,index : 'vehicleName',sortable:false,align:'center',width:400},
		             {name : 'vehicleAlias'          ,index : 'vehicleAlias',sortable:false,align:'center',width:400},
		             {name : 'vehiclePrice'          ,index : 'vehiclePrice',sortable:false,align:'center',width:400},
					 {name : 'vehicleDisplacement'   ,index : 'vehicleDisplacement',sortable:false,align:'center'},
					 {name : 'vehicleSeat'           ,index : 'vehicleSeat',sortable:false,align:'center'},
		             {name : 'vehicleWeight'         ,index : 'vehicleWeight',sortable:false,align:'center'},
		             {name : 'vehicleYear'           ,index : 'vehicleYear',sortable:false,align:'center'},
					 {name : 'vehicleTonnage'        ,index : 'vehicleTonnage',sortable:false,align:'center'},
					 {name : 'industryModelCode'     ,index : 'industryModelCode',sortable:false,align:'center'},
					 {name : 'vehicleModelcode'     ,index : 'vehicleModelcode',sortable:false,align:'center'},

		           ],
		pager: "#buying_price_list_pager",
		sortname: '0',
		viewrecords: true,
		gridview: true,
	    height:280,
		width:700,
		multiselect : false,
	    onSelectRow:function(rowid,status)
	    {
			var industryModelCode= $(this).getCell(rowid,'industryModelCode');
			var price = $(this).getCell(rowid,'vehiclePrice');
			var vehicleid = $(this).getCell(rowid,'vehicleId');
			var engine = $(this).getCell(rowid,'vehicleDisplacement');
			var seats = parseInt($(this).getCell(rowid,'vehicleSeat'));
	        var curb  = $(this).getCell(rowid,'vehicleWeight');
	        var curb  = curb.replace(".","");//去掉小数点
			var model = $(this).getCell(rowid,'vehicleName');
			var vehicleTonnage  = $(this).getCell(rowid,'vehicleTonnage');
			var vehicle_modelcode=$(this).getCell(rowid,'vehicle_modelcode');//增加模型代码
			var vehicleAlias = $(this).getCell(rowid,'vehicleAlias');


			if(vehicle_modelcode!="")
			{
				$("#policy_calculate_form").find("[name='form[AUTO][INDUSTY_MODEL_CODE]']").val(vehicle_modelcode);
			}
			$("#policy_calculate_queryBuyingPrice_dlg").dialog("close");

			$("#policy_calculate_form").find("[name='form[AUTO][MODEL_ALIAS]']").val(vehicleAlias);//新增车型别名
			$("#policy_calculate_form").find("[name='form[AUTO][BUYING_PRICE]']").val(parseFloat(price).toFixed(2));
			$("#policy_calculate_form").find("[name='form[AUTO][MODEL_CODE]']").val(vehicleid);
			$("#policy_calculate_form").find("[name='form[AUTO][MODEL]']").val(model);
			$("#policy_calculate_form").find("[name='form[AUTO][TONNAGE]']").val(vehicleTonnage*1000);
			$("#policy_calculate_form").find("[name='form[AUTO][INDUSTY_CODE]']").val(industryModelCode);
	        if(engine==0 && $("#policy_calculate_form").find("[name='form[AUTO][ENGINE]']").val()==0)
	        {
	            $("#policy_calculate_form").find("[name='form[AUTO][ENGINE]']").val(engine+".0000");
	        }
	        else if(engine!=0 && $("#policy_calculate_form").find("[name='form[AUTO][ENGINE]']").val()==0)
	        {
	            $("#policy_calculate_form").find("[name='form[AUTO][ENGINE]']").val(engine);
	        }
	        $("#policy_calculate_form").find("[name='form[AUTO][KERB_MASS]']").val(curb);
			$("#policy_calculate_form").find("[name='form[AUTO][SEATS]']").val(seats);
			if(seats>0)
			{
				$("#policy_calculate_form").find("[name='form[POLICY][TCPLI_PASSENGER_COUNT]']").val(seats-1);
			}
			var url = "index.php?module="+pc_module+"&action=depreciation";
			zswitch_ajax_request(url,"policy_calculate_form",function(type,data){
				if(type == "success")
				{
					var depre = parseFloat(data).toFixed(2);
					$("#policy_calculate_form").find("[name='form[AUTO][DISCOUNT_PRICE]']").val(depre);
					$("#policy_calculate_form").find("[name='form[POLICY][TVDI_INSURANCE_AMOUNT]']").val(depre);
					$("#policy_calculate_form").find("[name='form[POLICY][TWCDMVI_INSURANCE_AMOUNT]']").val(depre);
					$("#policy_calculate_form").find("[name='form[POLICY][SLOI_INSURANCE_AMOUNT]']").val(depre).change();
				}
				else
				{
					$("#policy_calculate_save_title").data("MsgContent",data).dialog("open");
				}
			});
		}
	});
	var reader = {
	  root: function(obj) { return mydata.rows; },
	  page: function(obj) { return mydata.page; },
	  total: function(obj) { return mydata.totalPage; },
	  records: function(obj) { return mydata.totalRows; },
	}
	var url = "index.php?module="+$(this).data("module")+"&action=queryBuyingPrice&model="+$("#vehicle_model").val();
    url += "&rate_table=" + $(this).data("rate_table")+"&vin_no="+vin_no+"&modelcode="+$("#policy_calculate_form").find("[name='form[AUTO][MODEL_CODE]']").val();
	grid.setGridParam({data: mydata.rows, rowNum: 10}).trigger("reloadGrid");
}

$("#vehicle_model_search").data("module",pc_module).data("rate_table",rate_table).click(function(){
	var url = "index.php?module="+$(this).data("module")+"&action=queryBuyingPrice&model="+$("#vehicle_model").val();
	    url += "&rate_table=" + $(this).data("rate_table")+"&vin_no="+vin_no+"&modelcode="+$("#policy_calculate_form").find("[name='form[AUTO][MODEL_CODE]']").val();
	$.get(url,function(msg){
		$("#abc").html(msg);
	})
});

</script>
</div><?php }} ?>