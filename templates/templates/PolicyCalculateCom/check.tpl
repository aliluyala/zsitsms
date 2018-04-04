{if !$check}
<input type="hidden" id="vin_no" value="{$DATA.VIN_NO}">
<input type="hidden" id="license_no" value="{$DATA.LICENSE_NO}">
<input type="hidden" id="rate_table" value="{$DATA.rate_table}">
<input type="hidden" id="checknum" value="{$CHECK.checkno}">
<div style="margin:0px 0px 3px 20px;" id="check_tpl"><span><img src="data:image/jpg;base64,{$CHECK.checkcode}" id="img_check">&nbsp;<button id="vehicle_check">刷新</button>&nbsp;验证码：<input id="check_id" type="text"/></span>&nbsp;<span><button id="policy_calculate_submit_code_btn">查询</button></span></div>
<script>

$("#policy_calculate_submit_code_btn").click(function(){
var check_id=$("#check_id").val();
if(check_id=="")
{
alert('验证码不能为空');
return false;
}

var VIN_NO = $("#vin_no").val();
var LICENSE_NO =  $("#license_no").val();
var CHECKNUM =  $("#checknum").val();
var rate_table =  $("#rate_table").val();
var MODEL = $("#policy_calculate_form").find("[name='form[AUTO][MODEL]']").val();
var ENROLL_DATE = $("#policy_calculate_form").find("[name='form[AUTO][ENROLL_DATE]']").val();
var url = "index.php?module=PolicyCalculateCom&action=checkcode&checkcode="+check_id+"&checkno="+CHECKNUM+"&rate_table="+rate_table+"&vin_no="+VIN_NO+"&MODEL="+MODEL+"&ENROLL_DATE="+ENROLL_DATE;

$.get(url,function(responseText){
	if(responseText.type=="error")
		{	
			alert(responseText.data);
			return false;
		}	
  $("#policy_calculate_check_dlg").dialog("close"); 
 var carloteququality= responseText.data[0].carloteququality;//整备质量
 var color = responseText.data[0].color;//颜色
 var engineno= responseText.data[0].engineno;//发动机号
 var enrolldate = responseText.data[0].enrolldate;//注册时间
 var exhaustcapacity =  responseText.data[0].exhaustcapacity;//排量
 var ineffectualdate = responseText.data[0].ineffectualdate;//商业险起保日期
 var licenseno = responseText.data[0].licenseno;//车牌号
 var licensetype = responseText.data[0].licensetype;//车牌类型
 var madefactory= responseText.data[0].madefactory;//车辆公司
 var owner= responseText.data[0].owner;//拥有人
 var pmfueltype = responseText.data[0].pmfueltype;
 var producertype = responseText.data[0].producertype;
 var vin= responseText.data[0].rackno;//车架号
 var rejectdate= responseText.data[0].rejectdate;
 var seatcount= responseText.data[0].seatcount;
 var status=responseText.data[0].status;
 var vehiclebarand1= responseText.data[0].vehiclebarand1;//车辆品牌
 var vehiclebarand2 = responseText.data[0].vehiclebarand2;//车辆品牌logo
 var vehiclecategory = responseText.data[0].vehiclecategory;
 var vehiclemodel = responseText.data[0].vehiclemodel;//车辆品牌型号
 var vehiclestyle  = responseText.data[0].vehiclestyle;//车辆交管车辆类型

 $("input[name='form[AUTO][LICENSE_NO]']").val(licenseno);
 $("input[name='form[AUTO][OWNER]']").val(owner);
 $("input[name='form[AUTO][VIN_NO]']").val(vin);
 $("input[name='form[AUTO][ENGINE_NO]']").val(engineno);
 $("input[name='form[AUTO][MODEL]']").val(vehiclemodel);
 $("input[name='form[AUTO][ENGINE] ']").val(exhaustcapacity);
 //$("input[name='form[POLICY][MVTALCI_START_TIME]']").val(ineffectualdate+" 00:00:00");
 $("input[name='form[AUTO][KERB_MASS]']").val(carloteququality);
 $("input[name='form[AUTO][ENROLL_DATE]']").val(enrolldate);
 $("input[name='form[AUTO][CONTROL_TYPE]']").val(vehiclestyle);


},"json");

		
})


$("#vehicle_check").click(function(){
var VIN_NO = $("#policy_calculate_form").find("[name='form[AUTO][VIN_NO]']").val();
var LICENSE_NO =  $("#policy_calculate_form").find("[name='form[AUTO][LICENSE_NO]']").val();
var rate_table = $("#policy_calculate_form").find("[name='form[OTHER][PREMIUM_RATE_TABLE]']").val();
var url = "index.php?module=PolicyCalculateCom&action=check_refresh&vin="+VIN_NO+"&license_no="+LICENSE_NO+"&rate_table="+rate_table+"&vin_no="+VIN_NO;
		$.get(url,function(responseText){
		var data;
		eval("data="+responseText); 
		var checkcode= "data:image/jpg;base64,"+data.data.checkcode;
		$("#img_check").attr("src",checkcode);
		$("#checknum").val(data.data.checkno);
		})
})

</script>
{else}

<div id="check_tpl">
验证码：<img src='{$check}'>
<a id='check_s' onclick="check_fun();">看不清楚?点击刷新验证码</a>
<input type="text" name="check" id="check">
<input type="button" id="check_button" value="登录">
</div>

<script>
function check_fun()
{
	var VIN_NO = $("input[name='form[AUTO][VIN_NO]']").val();
	var LICENSE_NO =  $("input[name='form[AUTO][LICENSE_NO]']").val();
	var rate_table = $("#policy_calculate_form").find("[name='form[OTHER][PREMIUM_RATE_TABLE]']").val();
	var url="index.php?module=PolicyCalculateCom&action=check&vin="+VIN_NO+"&license_no="+LICENSE_NO+"&rate_table="+rate_table+"";
	$("#policy_calculate_check_dlg").load(url);


}

	$("#check_button").click(function()
	{
		$check= $("#check").val();
			if($check=="")
			{
				alert('请输入登录验证码');
			}
			else
			{
						var rate_table = $("#policy_calculate_form").find("[name='form[OTHER][PREMIUM_RATE_TABLE]']").val();
						var url="index.php?module=PolicyCalculateCom&action=logincheck&checkname="+$check+"&rate_table="+rate_table;
						$.get(url,function(responseText){
							var text = eval("data="+responseText); 
							
							if(text.type=="error")
							{	
								alert(text.data);
								return false;
							}	

							var pars= JSON.parse(responseText);
							$("#check_tpl").empty();
						
							$html='<input type="hidden" id="vin_no" value=""><input type="hidden" id="license_no" value=""><input type="hidden" id="rate_table" value=""><input type="hidden" id="checknum" value="'+pars.data.checkno+'"><div style="margin:0px 0px 3px 20px;" id="check_tpl"><span><img src="data:image/jpg;base64,'+pars.data.checkcode+'" id="img_check">&nbsp;<button id="vehicle_check">刷新</button>&nbsp;验证码：<input id="check_id" type="text"/></span>&nbsp;<span><button id="policy_calculate_submit_code_btn">查询</button></span></div>';
							$("#check_tpl").html($html);
							$("#policy_calculate_submit_code_btn").click(function(){
							var check_id=$("#check_id").val();
							if(check_id=="")
							{
							alert('验证码不能为空');
							return false;
							}

							var VIN_NO = $("input[name='form[AUTO][VIN_NO]']").val();
							var LICENSE_NO =  $("input[name='form[AUTO][LICENSE_NO]']").val();
							var CHECKNUM =  $("#checknum").val();
							var MODEL = $("#policy_calculate_form").find("[name='form[AUTO][MODEL]']").val();
							var ENROLL_DATE = $("#policy_calculate_form").find("[name='form[AUTO][ENROLL_DATE]']").val();

							var url = "index.php?module=PolicyCalculateCom&action=checkcode&checkcode="+check_id+"&checkno="+CHECKNUM+"&rate_table="+rate_table+"&vin_no="+VIN_NO+"&MODEL="+MODEL+"&ENROLL_DATE="+ENROLL_DATE;
							
						$.get(url,function(responseText){
								if(responseText.type=="error")
									{	
										alert(responseText.data);
										return false;
									}	
							 $("#policy_calculate_check_dlg").dialog("close"); 
							 var carloteququality= responseText.data[0].carloteququality;//整备质量
							 var color = responseText.data[0].color;//颜色
							 var engineno= responseText.data[0].engineno;//发动机号
							 var enrolldate = responseText.data[0].enrolldate;//注册时间
							 var exhaustcapacity =  responseText.data[0].exhaustcapacity;//排量
							 var ineffectualdate = responseText.data[0].ineffectualdate;//商业险起保日期
							 var licenseno = responseText.data[0].licenseno;//车牌号
							 var licensetype = responseText.data[0].licensetype;//车牌类型
							 var madefactory= responseText.data[0].madefactory;//车辆公司
							 var owner= responseText.data[0].owner;//拥有人
							 var pmfueltype = responseText.data[0].pmfueltype;
							 var producertype = responseText.data[0].producertype;
							 var vin= responseText.data[0].rackno;//车架号
							 var rejectdate= responseText.data[0].rejectdate;
							 var seatcount= responseText.data[0].seatcount;
							 var status=responseText.data[0].status;
							 var vehiclebarand1= responseText.data[0].vehiclebarand1;//车辆品牌
							 var vehiclebarand2 = responseText.data[0].vehiclebarand2;//车辆品牌logo
							 var vehiclecategory = responseText.data[0].vehiclecategory;
							 var vehiclemodel = responseText.data[0].vehiclemodel;//车辆品牌型号
							 var vehiclestyle  = responseText.data[0].vehiclestyle;//车辆交管车辆类型

							 $("input[name='form[AUTO][LICENSE_NO]']").val(licenseno);
							 $("input[name='form[AUTO][OWNER]']").val(owner);
							 $("input[name='form[AUTO][VIN_NO]']").val(vin);
							 $("input[name='form[AUTO][ENGINE_NO]']").val(engineno);
							 $("input[name='form[AUTO][MODEL]']").val(vehiclemodel);
							 $("input[name='form[AUTO][ENGINE] ']").val(exhaustcapacity);
							 //$("input[name='form[POLICY][MVTALCI_START_TIME]']").val(ineffectualdate+" 00:00:00");
							 $("input[name='form[AUTO][KERB_MASS]']").val(carloteququality);
							 $("input[name='form[AUTO][ENROLL_DATE]']").val(enrolldate);
							 $("input[name='form[AUTO][CONTROL_TYPE]']").val(vehiclestyle);


							},"json");

									
							})
							

							$("#vehicle_check").click(function(){
							var VIN_NO = $("#policy_calculate_form").find("[name='form[AUTO][VIN_NO]']").val();
							var LICENSE_NO =  $("#policy_calculate_form").find("[name='form[AUTO][LICENSE_NO]']").val();
							var rate_table = $("#policy_calculate_form").find("[name='form[OTHER][PREMIUM_RATE_TABLE]']").val();
							var url = "index.php?module=PolicyCalculateCom&action=check_refresh&vin="+VIN_NO+"&license_no="+LICENSE_NO+"&rate_table="+rate_table;
									$.get(url,function(responseText){
									var data;
									eval("data="+responseText); 
									var checkcode= "data:image/jpg;base64,"+data.data.checkcode;
									$("#img_check").attr("src",checkcode);
									$("#checknum").val(data.data.checkno);
									})
							})
							



						})


			}	

	})







</script>


{/if}



