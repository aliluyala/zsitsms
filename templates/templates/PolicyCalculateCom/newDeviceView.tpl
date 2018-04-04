<form  id="new_device_list_form">
<table id="new_device_list_table" class="mceItemTable" style="margin:0px 0px 2px 0px;width: 100%;border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">
			设备名称
	    </td>
		<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">
			新购价格(元)
		</td>
		<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">
			数量
		</td>
		<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">
			购买日期
		</td>
		<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">
			折旧价(元)
		</td>
		<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">
			操作
		</td>
	</tr>
</table>
</form>
<div id="calculate_waiting_box" style="position: absolute;z-index:99999;left:260px;top:100px;display:none;"  ><img src="{$IMAGES}/loading_a.gif" width="40px;" height="40px;"/></div>
<button id="add_new_device">增加</button><button id="calculate_device_depreciation">计算</button>
<script>
var pc_module = "{$MODULE}";
{literal}
	$("#add_new_device").click(function(){
		var device = $(this).data("device");
		if(device == null)
		{
			device = {NAME:"",BUYING_PRICE:"0.00",COUNT:"1",DEPRECIATION:"0.00",BUYING_DATE:""};
		}
		$(this).data("device",null);
		var html  = '<tr><td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">';
			html += '<input type="text" name="NAME" value="'+device.NAME+'" ui = "7" mandatory = "true" label = "设备名称"  min_len="1" max_len="50"  regexp="" regtitle = ""  style="width:100px;" />';
			html += '</td>';
			html += '<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">';
			html += '<input type="text" name="BUYING_PRICE" value="'+device.BUYING_PRICE+'" ui = "8" mandatory = "true" label = "新购价格"  min="1"	max="999999999"  decimal="2"  style="width:70px;" />';
			html += '</td>';
			html += '<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">';
			html += '<input type="text" name="COUNT" value="'+device.COUNT+'" ui = "8" mandatory = "true" label = "数量"  min="1"	max="30"  decimal="0"  style="width:100px;" />';
			html += '</td>';
			html += '<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">';
			html += '<input type="text" name="BUYING_DATE" value="'+device.BUYING_DATE+'" ui = "30" mandatory = "true" label = "购买日期"  style="width:70px;" />';
			html += '</td>';
			html += '<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; ">';
			html += '<input type="text" name="DEPRECIATION" value="'+device.DEPRECIATION+'" ui = "7" mandatory = "false" label = "折旧价"  min="1"	max="999999999"  decimal="2"  style="width:70px;" />';
			html += '</td>';
			html += '<td style="font-weight: bold; border: 1px solid #dcdcdc;text-align:center; "><button onclick="$(this).parent().parent().remove();" >删除</button></td></tr>';
		$("#new_device_list_table").append(html);
		zswitch_ui_form_init("#new_device_list_form");
	});
	var jsonstr = $("[name='form[POLICY][NIELI_DEVICE_LIST]']").val();

	if(jsonstr.length == 0)
	{
		jsonstr = "[]";
	}
	var deviceList = $.secureEvalJSON(decodeURIComponent(jsonstr));
	for(idx =0 ;idx<deviceList.length;idx++)
	{
		$("#add_new_device").data("device",deviceList[idx]).click();
	}

	$("#calculate_device_depreciation").data("module",pc_module).click(function(){
		var info = zswitchui_validity_check("#new_device_list_form",true);
		if(info.length>0) return;

		var devicelist = [];
		$("#new_device_list_table").find("tr:gt(0)").each(function(){
			var device = {NAME:"",BUYING_PRICE:"",COUNT:"",DEPRECIATION:"",BUYING_DATE:""};
			device.NAME = $(this).find("[name='NAME']").val();
			device.BUYING_PRICE = $(this).find("[name='BUYING_PRICE']").val();
			device.COUNT = $(this).find("[name='COUNT']").val();
			device.DEPRECIATION = $(this).find("[name='DEPRECIATION']").val();
			device.BUYING_DATE = $(this).find("[name='BUYING_DATE']").val();
			devicelist.push(device);
		});
		if(devicelist.length == 0) return;
		var devicejson = encodeURIComponent($.toJSON(devicelist));
		var url = "index.php?module="+$(this).data("module")+"&action=deviceDepreciation&deviceList="+devicejson;
		$("#calculate_waiting_box").show();
		zswitch_ajax_request(url,"policy_calculate_form",function(type,data){
			$("#calculate_waiting_box").hide();
			if(type == 'error')
			{
				alert(data);
				return;
			}
			if(type == "success")
			{
				var total = 0;
				for(idx=0;idx<data.length;idx++)
				{
					var no = idx+1;
					var depr = parseFloat(data[idx].DEPRECIATION);
					total = total + depr;
					$("#new_device_list_table").find("tr:eq("+no+")").find("[name='DEPRECIATION']").val(depr.toFixed(2));
					devicelist[idx].DEPRECIATION = depr.toFixed(2);
				}
				$("[name='form[POLICY][NIELI_INSURANCE_AMOUNT]']").val(total.toFixed(0)).change();
				var devicejson = encodeURIComponent($.toJSON(devicelist));
				$("[name='form[POLICY][NIELI_DEVICE_LIST]']").val(devicejson);
			}
		});

	});

	zswitch_ui_form_init("#new_device_list_form");
{/literal}
</script>

