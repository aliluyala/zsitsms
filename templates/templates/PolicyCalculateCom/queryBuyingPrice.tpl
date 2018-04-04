<div id = "abc">
<div style="margin:0px 0px 3px 20px;"><span><input id="vehicle_model" value="{$VEHICLE_MODEL}"/></span><span><button id="vehicle_model_search">搜索</button></span></div>
<table id="buying_price_list"></table>
<div id="buying_price_list_pager"></div>
<script>
var pc_module = "{$MODULE}";
var vehicle_model  = "{$VEHICLE_MODEL}";
var rate_table = "{$RATE_TABLE}";
var vin_no = "{$VIN_NO}";
var mydata = {$DATAS};
{literal}
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
{/literal}
</script>
</div>