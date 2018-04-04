<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="{$STYLES}/jquery-ui/redmond/jquery-ui-1.10.3.custom.css" />	
<link rel="stylesheet" type="text/css" href="{$STYLES}/zswitch.css" />			
<script type="text/javascript" src="{$SCRIPTS}/jquery-1.9.1.js"></script> 
<script type="text/javascript" src="{$SCRIPTS}/jquery-ui-1.10.3.custom.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/jquery.md5.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/globalize.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/cultures/globalize.culture.zh-CN.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/jquery-ui.timespinner.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/jquery.json-2.4.min.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/date-format.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/zswitch.js"></script>
<script type="text/javascript" src="{$SCRIPTS}/zswitchui.js"></script>

<script type="text/javascript" src="{$SCRIPTS}/zswitchui-validity.js"></script>




<style type="text/css">
{literal}
body {text-align:center;}
td {height:25px;line-height:25px;}
.formlabel {text-align:right;padding-right:2px;}
.formfieldcontent {text-align:left;padding-left:2px;}
input,select,button {font-size:12px;}

{/literal}
</style>

</head>
<body>
<div id="main_view_client" style="height:0px;min-height:0px;min-width:0px;"></div>
<form id="policy_calculate_form"  style="margin:0px;width:880px;"  onsubmit="return false;">
<input type = "hidden" name="recordid" value="{$recordid}" />
<input type = "hidden" name="operation" value="{$operation}" />

<div class="borderForm" style=" padding-left:0; padding-right:0; ">
<table class="mceItemTable" style="font-size:12px;width: 99%;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr align="left" ><td style="width: 500px; font-weight: bold;border-bottom:1px solid #DCDCDC; ">
	<table class="mceItemTable" style="table-layout:fixed;margin:0px 0px 2px 0px;width: 100%;border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="width: 50px; text-align:right;" > 
				编号：
			</td>
			<td style="width: 150px; " > 
				<span style="text-align:left;" >{$form.NO}</span>
			</td>
			<td style="width: 450px; text-align:right;" > 
				费率表：
			</td>
			<td>
				<span style="text-align:left;" >{$form.PREMIUM_RATE_TABLE}</span>
			</td>
		</tr>
	</tbody>	
	</table>
</td>
</tr>

<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px 0px 2px 0px;width: 100%;border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr >
			<td class="textblue" style="font-weight: bold; width: 80px; border: 1px solid #dcdcdc; " rowspan="2" align="center">投保人</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #ececec; ">名称</td>
			<td class="formfieldcontent" style="width: 419px; border: 1px solid #dcdcdc; " colspan="3">{$form.HOLDER}</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #ececec; ">证件类型</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.HOLDER_IDENTIFY_TYPE}</td>
		</tr>
		<tr>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #ececec; ">地址</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; " colspan="3">{$form.HOLDER_ADDRESS}</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #ececec; " ><span>证件号码</span></td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.HOLDER_IDENTIFY_NO}</td>
		</tr>
	</tbody>	
	</table>
</td></tr>

<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px 0px 2px 0px;width: 100%;border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr >
			<td class="textblue" style="font-weight: bold; width: 80px; border: 1px solid #dcdcdc; " rowspan="2" align="center">被保险人</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #ececec; ">名称</td>
			<td class="formfieldcontent" style="width: 419px; border: 1px solid #dcdcdc; " colspan="3">{$form.INSURANT}</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #ececec; ">证件类型</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.IDENTIFY_TYPE}</td>
		</tr>
		<tr>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #ececec; ">地址</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; " colspan="3">{$form.ADDRESS}</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #ececec; " ><span>证件号码</span></td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.IDENTIFY_NO}</td>
		</tr>
	</tbody>	
	</table>
</td></tr>

<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px 0px 2px 0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>

		<tr>
			<td class="textblue" style="font-weight: bold; width: 80px; border: 1px solid #dcdcdc; " rowspan="5">车辆信息</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #ececec; ">车牌号码</td>
			<td class="formfieldcontent" style="width: 167px; border: 1px solid #dcdcdc; ">{$form.LICENSE_NO}</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #ececec; ">车牌类型</td>
			<td class="formfieldcontent" style="width: 167px; border: 1px solid #dcdcdc; ">{$form.LICENSE_TYPE}</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #ececec; ">拥有人</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.OWNER}</td>
		</tr>
		<tr>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">车辆识别码</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.VIN_NO}</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #edecec; ">发动机号</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; ">{$form.ENGINE_NO}</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #ececec; ">品牌型号</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.MODEL}</td>
		</tr>
		<tr>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #ececec; ">排量</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.ENGINE}</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">核定载客</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.SEATS}</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">整备质量</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.KERB_MASS}</td>
		</tr>
		<tr>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #ececec; ">型号代码</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.MODEL_CODE}</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">新车购置价</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.BUYING_PRICE}</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">注册日期</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.ENROLL_DATE}</td>
		</tr>
		<tr>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">车辆种类</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.VEHICLE_TYPE}</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">使用性质</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.USE_CHARACTER}</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #ececec; ">产地</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.ORIGIN}</td>
		</tr>

	
	</tbody>	
	</table>
</td></tr>


<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px 0px 2px 0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>

		<tr>
			<td class="textblue" style="font-weight: bold; width: 80px; border: 1px solid #dcdcdc; background-color:#f5f8f9;"  >费用总览</td>
			<td  style="border: 1px solid #dcdcdc; text-align:left; background-color:#f5f8f9; ">
				<span style="padding-left:5px;" >
					折后费用合计：{$form.TOTAL_PREMIUM}
				</span>
				<span style="padding-left:18px;" >
					标准保费合计：{$form.TOTAL_STANDARD_PREMIUM}
				</span>
				
				<span style="padding-left:18px;" >
					商业险：{$form.TOTAL_BUSINESS_PREMIUM}
				</span>
				<span style="padding-left:18px;">
					交强险：{$form.TOTAL_MVTALCI_PREMIUM}
				</span>
				<span style="padding-left:18px;">
					车船税：{$form.TOTAL_TRAVEL_TAX_PREMIUM}
				</span>
			</td>
		</tr>
			
	</tbody>	
	</table>
</td></tr>		

<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px 0px 2px 0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>

		<tr>
			<td class="textblue" style="font-weight: bold; width: 80px; border: 1px solid #dcdcdc; " rowspan="2" >交强险<input id="form[MVTALCI_SELECT]" type="checkbox" name="form[MVTALCI_SELECT][]" value="YES" /></td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; width: 80px; background-color: #ececec; ">保费</td>
			<td class="formfieldcontent" style="width: 167px;border: 1px solid #dcdcdc; " >{$form.MVTALCI_PREMIUM}</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; width: 80px; background-color: #ececec; ">浮动标准</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.FLOATING_RATE}</td>
		</tr>
		<tr>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #ececec; " >车船税</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " align="left" >{$form.TRAVEL_TAX_PREMIUM}</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #ececec; " >交强险期限</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " > 从 {$form.MVTALCI_START_TIME} 至 {$form.MVTALCI_END_TIME} </td>
		</tr>
			
	</tbody>	
	</table>
</td></tr>	
	

<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px ;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>

		<tr>
			<td class="textblue" style="font-weight: bold; width: 80px; border: 1px solid #dcdcdc;background-color:#f5f8f9; "  >商业险摘要</td>
			<td  style="border: 1px solid #dcdcdc; text-align:left; background-color:#f5f8f9; ">
				<span style="padding-left:20px;" >
					折后商业合计：{$form.BUSINESS_PREMIUM}
				</span>
				<span style="padding-left:50px;" >
					四叶草折扣：{$form.BUSINESS_DISCOUNT}
				</span>
				<span style="padding-left:50px;" >
					标准保费：{$form.BUSINESS_STANDARD_PREMIUM}
				</span>
			</td>
		</tr>
			
	</tbody>	
	</table>
</td></tr>	


<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr style="" data-mce-style="">
			<td class="textblue" style="width: 80px; border: 1px solid #dcdcdc; " align="center">商业险期限</td>			
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " > 从 {$form.BUSINESS_START_TIME} 至 {$form.BUSINESS_END_TIME}</td>
		</tr>	
	</tbody>	
	</table>
</td></tr>	

<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
		<td class="textblue" style=" width: 80px; border: 1px solid #dcdcdc; " rowspan="4" >指定驾驶人</td>
		<td style="font-weight: bold; width: 30px; border: 1px solid #dcdcdc; background-color: #ececec; " align="center">#</td>
		<td style="font-weight: bold; width: 180px; border: 1px solid #dcdcdc; background-color: #ececec; " >姓名</td>
		<td style="font-weight: bold; width: 180px; border: 1px solid #dcdcdc; background-color: #ececec; " >驾驶证号</td>
		<td style="font-weight: bold; width: 60px; border: 1px solid #dcdcdc; background-color: #ececec; " >准驾</td>
		<td style="font-weight: bold; width: 60px; border: 1px solid #dcdcdc; background-color: #ececec; " >性别</td>
		<td style="font-weight: bold; border: 1px solid #dcdcdc; background-color: #ececec; " >年龄</td>
		<td style="font-weight: bold; border: 1px solid #dcdcdc; background-color: #ececec; " >驾龄</td>
		</tr>
		<tr>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; "  align="center"><input id="form[DESIGNATED_DRIVER1]" type="checkbox" name="form[DESIGNATED_DRIVER1][]" value="YES" /></td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_NAME1}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVING_LICENCE_NO1}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_ALLOW_DRIVE1}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_SEX1}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_AGE1}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVING_YEARS1}</td>
		</tr>
		<tr>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; "  align="center" ><input id="form[DESIGNATED_DRIVER2]" type="checkbox" name="form[DESIGNATED_DRIVER2][]" value="YES" /></td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_NAME2}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVING_LICENCE_NO2}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_ALLOW_DRIVE2}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_SEX2}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_AGE2}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVING_YEARS2}</td>
		</tr>
		<tr>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; "  align="center" ><input id="form[DESIGNATED_DRIVER3]" type="checkbox" name="form[DESIGNATED_DRIVER3][]" value="YES" /></td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_NAME3}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVING_LICENCE_NO3}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_ALLOW_DRIVE3}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_SEX3}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVER_AGE3}</td>
		<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.DRIVING_YEARS3}</td>
		</tr>
		
	</tbody>	
	</table>
</td></tr>	


<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td class="textblue" style="width: 80px; border: 1px solid #dcdcdc; " rowspan="2" align="center">折扣系数</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #edecec; ">投保年度</td>
			<td class="formfieldcontent" style="width: 167px; border: 1px solid #dcdcdc; ">{$form.YEARS_OF_INSURANCE}</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #edecec; ">赔款记录</td>
			<td class="formfieldcontent" style="width: 167px; border: 1px solid #dcdcdc; ">{$form.CLAIM_RECORDS}</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #ececec; ">约定行驶区域</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.DRIVING_AREA}</td>
		</tr>
		<tr>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #edecec; ">平均行驶里程</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; ">{$form.AVERAGE_ANNUAL_MILEAGE}</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #ececec; ">多险种优惠</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; ">{$form.MULTIPLE_INSURANCE}</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #edecec; ">商业险折扣率</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; ">{$form.BUSINESS_DISCOUNT_SHWY}</td>
		</tr>
	</tbody>	
	</table>
</td></tr>	


<tr><td>
	<table id="business_items_table" class="mceItemTable" style="table-layout:fixed;margin:1px 0px 0px 0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
		    <td style="font-weight: bold; border-width: 1px 0px 1px 1px ;border-style: solid none solid solid ; border-color: #dcdcdc; background-color: #ececec; width: 40px; "  align="center"> </td>
		    <td style="font-weight: bold; border-width: 1px 1px 1px 0px ;border-style: solid none solid solid ; border-color: #dcdcdc; background-color: #ececec; width: 160px; "  align="center">投保项目&nbsp;&nbsp;&nbsp;</td>
		    <td style="font-weight: bold; border: 1px solid #dcdcdc; background-color: #ececec; width:200px;" align="center" >保额(元)</td>
		    <td style="font-weight: bold; border: 1px solid #dcdcdc; background-color: #ececec; width:186px;" align="center" >附加条件</td>
		    <td style="font-weight: bold; border: 1px solid #dcdcdc; background-color: #ececec; " align="center" >标准保费(元)</td>
		<!--    <td style="font-weight: bold; border: 1px solid #dcdcdc; background-color: #ececec; " align="center" >折后保费(元)</td> -->
			<td style="font-weight: bold; border-width: 1px 0px 1px 1px ;border-style: solid none solid solid ; border-color: #dcdcdc; background-color: #ececec; background-color: #ececec; width:20px; " align="center" ></td>			
		    <td style="font-weight: bold; border-width: 1px 1px 1px 0px ;border-style: solid none solid solid ; border-color: #dcdcdc; background-color: #ececec; background-color: #ececec; " align="left" >不计免赔(元)</td>
			
		</tr>

		<tr>
			<td class="textblue" style="padding-right: 5px; border: 1px solid #dcdcdc;  " rowspan="5" align="center">
			<p>基</p>
			<p>本</p>
			<p>险</p>
			</td>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc;  width: 160px;" align="right"><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TVDI]">车辆损失险</label></span><input id="form[BUSINESS_ITEMS][TVDI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TVDI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;{$form.TVDI_INSURANCE_AMOUNT} </td>
			<td style="border: 1px solid #dcdcdc; " align="left" >
			&nbsp;可选免赔额：{$form.DOC_AMOUNT} 元
			</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TVDI_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.TVDI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TVDI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TVDI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TVDI_NDSI_PREMIUM}</td>
			
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TTBLI]">第三方责任险</label></span><input id="form[BUSINESS_ITEMS][TTBLI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TTBLI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;{$form.TTBLI_INSURANCE_AMOUNT} {$form.TTBLI_INSURANCE_AMOUNT_EXT}</td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TTBLI_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.TTBLI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TTBLI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TTBLI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TTBLI_NDSI_PREMIUM}</td>
			
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TWCDMVI]">盗抢险</label></span><input id="form[BUSINESS_ITEMS][TWCDMVI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TWCDMVI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;{$form.TWCDMVI_INSURANCE_AMOUNT}</td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TWCDMVI_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.TWCDMVI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TWCDMVI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TWCDMVI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TWCDMVI_NDSI_PREMIUM}</td>
					
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TCPLI_DRIVER]">车上人员责任险(司机)</label></span><input id="form[BUSINESS_ITEMS][TCPLI_DRIVER]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TCPLI_DRIVER" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;{$form.TCPLI_INSURANCE_DRIVER_AMOUNT}</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TCPLI_DRIVER_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.TCPLI_DRIVER_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TCPLI_DRIVER_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TCPLI_DRIVER_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TCPLI_DRIVER_NDSI_PREMIUM}</td>
					
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TCPLI_PASSENGER]">车上人员责任险(乘客)</label></span><input id="form[BUSINESS_ITEMS][TCPLI_PASSENGER]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TCPLI_PASSENGER" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;{$form.TCPLI_INSURANCE_PASSENGER_AMOUNT}&nbsp;X&nbsp;{$form.TCPLI_PASSENGER_COUNT}人</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TCPLI_PASSENGER_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.TCPLI_PASSENGER_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TCPLI_PASSENGER_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TCPLI_PASSENGER_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TCPLI_PASSENGER_NDSI_PREMIUM}</td>
					
		</tr>

	
		<tr>
			<td class="textblue" style="padding-right: 5px; border: 1px solid #dcdcdc; " rowspan="8" align="center" >
			<p>附</p>
			<p>加</p>
			<p>险</p>
			</td>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][BSDI]">车身划痕险</label></span><input id="form[BUSINESS_ITEMS][BSDI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BSDI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span>{$form.BSDI_INSURANCE_AMOUNT}</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.BSDI_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.BSDI_DISCOUNT_PREMIUM}</td>-->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][BSDI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BSDI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.BSDI_NDSI_PREMIUM}</td>
					
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][SLOI]">自燃损失险</label></span><input id="form[BUSINESS_ITEMS][SLOI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="SLOI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span>{$form.SLOI_INSURANCE_AMOUNT}</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.SLOI_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.SLOI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][SLOI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="SLOI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.SLOI_NDSI_PREMIUM}</td>
					
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][BGAI]">玻璃单独破碎险</label></span><input id="form[BUSINESS_ITEMS][BGAI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BGAI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ><span>&nbsp;玻璃产地：{$form.GLASS_ORIGIN}</span></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.BGAI_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.BGAI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][BGAI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BGAI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.BGAI_NDSI_PREMIUM}</td>
					
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][NIELI]">新增设备损失险</label></span><input id="form[BUSINESS_ITEMS][NIELI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="NIELI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span>{$form.NIELI_INSURANCE_AMOUNT}</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.NIELI_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.NIELI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][NIELI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="NIELI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.NIELI_NDSI_PREMIUM}</td>
	
				
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][WADING]">涉水险</label></span><input id="form[BUSINESS_ITEMS][WADING]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="WADING" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span>{$form.WADING_INSURANCE_AMOUNT}</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.WADING_PREMIUM}</td>
	<!--	<td style="border: 1px solid #dcdcdc; " >{$form.WADING_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][WADING_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="WADING_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.WADING_NDSI_PREMIUM}</td>					
		</tr>			
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][STSFS]">指定专修厂</label></span><input id="form[BUSINESS_ITEMS][STSFS]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="STSFS" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ><span>&nbsp;上浮比例：{$form.STSFS_RATE}</span></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.STSFS_PREMIUM}</td>
	<!--	<td style="border: 1px solid #dcdcdc; " >{$form.CUSTOM1_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][STSFS_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="STSFS_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.STSFS_NDSI_PREMIUM}</td>					
		</tr>			
		
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][CUSTOM1]">自定义险别1</label></span><input id="form[BUSINESS_ITEMS][CUSTOM1]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="CUSTOM1" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span>{$form.CUSTOM1_INSURANCE_AMOUNT}</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >{$form.CUSTOM1_INSURANCE_NAME}</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.CUSTOM1_PREMIUM}</td>
	<!--	<td style="border: 1px solid #dcdcdc; " >{$form.CUSTOM1_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][CUSTOM1_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="CUSTOM1_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.CUSTOM1_NDSI_PREMIUM}</td>					
		</tr>

		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][CUSTOM2]">自定义险别2</label></span><input id="form[BUSINESS_ITEMS][CUSTOM2]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="CUSTOM2" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span>{$form.CUSTOM2_INSURANCE_AMOUNT}</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >{$form.CUSTOM2_INSURANCE_NAME}</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.CUSTOM2_PREMIUM}</td>
	<!--	<td style="border: 1px solid #dcdcdc; " >{$form.CUSTOM1_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][CUSTOM2_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="CUSTOM2_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.CUSTOM2_NDSI_PREMIUM}</td>					
		</tr>	
	
	
		
	</tbody>	
	</table>
</td></tr>	


<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:2px 0px 0px 0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td class="textblue" style="width: 80px; border: 1px solid #dcdcdc; "  align="center">承保公司</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; ">{$form.INSURANCE_COMPANY}</td>
		</tr>
	</tbody>	
	</table>
</td></tr>	


<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:2px 0px 0px 0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td class="textblue" style="width: 80px; border: 1px solid #dcdcdc; " rowspan="3" align="center" >其它</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #edecec; width:80px;" >四叶草产品</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.SHIYEICAO_PRODUCT}</td>
		</tr>
		<tr>			
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #edecec; width:80px;" >礼品</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.GIFT}</td>
		</tr>
		<tr>			
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #edecec; width:80px;" >备注</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.REMARKS}</td>
		</tr>		
	</tbody>	
	</table>
</td></tr>	
<tr><td>
<div class="CLIENT_JOBVIEW_BUTTONS">
	<button class="module_seting_button" operation="save">保存</button> 
	<button class="module_seting_button" operation="sendsms">发送短信</button>
	<button class="module_seting_button" operation="submitAuditing">生成订单</button>
</div>
</td></tr>
</tbody>	
</table>
</div>
</form>
	
<div id ="policy_calculate_save_title"></div>
<div id ="policy_calculate_save_confirm"></div>
<div id ="policy_calculate_submitAuditing_confirm"></div>

<script type="text/javascript">
	var business_items = {$BUSINESS_ITEMS};
	var designated_driver1 = '{$DESIGNATED_DRIVER1}';
	var designated_driver2 = '{$DESIGNATED_DRIVER2}';
	var designated_driver3 = '{$DESIGNATED_DRIVER3}';
	var mvtalci_select     = '{$MVTALCI_SELECT}';
	
	zswitch_ui_form_init("#policy_calculate_form");	
	
{literal}
	$(":checkbox").each(function(){
		var id = $(this).attr("id");
		if(id == "form[MVTALCI_SELECT]")
		{			
			if(mvtalci_select == 'NO') $(this).prop("checked",true);
			$(this).click(function(e){				
				if($(this).prop("checked"))
				{
					$(this).parent().parent().parent().find(":text").each(function(){
						$(this).prop('disabled',false);					
					});
				}
				else
				{
					$(this).parent().parent().parent().find(":text").each(function(){
						$(this).prop('disabled',true);					
					});
					
				}
				
			});	
			$(this).click();	
		}
		else if(id == "form[DESIGNATED_DRIVER1]" || id == "form[DESIGNATED_DRIVER2]" || id == "form[DESIGNATED_DRIVER3]")
		{
			if(id == "form[DESIGNATED_DRIVER1]" && designated_driver1 == 'NO' )
			{
				$(this).prop("checked",true);
			}
			if(id == "form[DESIGNATED_DRIVER2]" && designated_driver2 == 'NO' )
			{
				$(this).prop("checked",true);
			}
			if(id == "form[DESIGNATED_DRIVER3]" && designated_driver3 == 'NO' )
			{
				$(this).prop("checked",true);
			}
			
			$(this).click(function(e){
				
				if($(this).prop("checked"))
				{					
					$(this).parent().parent().find(":text,select").each(function(){
						$(this).prop('disabled',false);					
					});
				}
				else
				{
					$(this).parent().parent().find(":text,select").each(function(){
						$(this).prop('disabled',true);					
					});
					
				}				
			});	
            $(this).click();			
		}
		else if(id == "form[BUSINESS_ITEMS][TVDI]" || id == "form[BUSINESS_ITEMS][TTBLI]" ||
                id == "form[BUSINESS_ITEMS][SLOI]" || id == "form[BUSINESS_ITEMS][TWCDMVI]" ||
				id == "form[BUSINESS_ITEMS][TCPLI_PASSENGER]" || id == "form[BUSINESS_ITEMS][BSDI]" ||
				id == "form[BUSINESS_ITEMS][BGAI]" || id == "form[BUSINESS_ITEMS][NIELI]" ||
				id == "form[BUSINESS_ITEMS][STSFS]" || id == "form[BUSINESS_ITEMS][TCPLI_DRIVER]" ||
				id == "form[BUSINESS_ITEMS][WADING]" || id == "form[BUSINESS_ITEMS][CUSTOM1]" ||
				id == "form[BUSINESS_ITEMS][CUSTOM2]" )
		{
			var is_select = false;
			for(x in business_items)
			{
				if("form[BUSINESS_ITEMS]["+business_items[x]+"]" == id )
				{
					is_select = true;
					break;
				}
			}
		
			if(!is_select)
			{
				$(this).prop("checked",true);
			}
			
			$(this).click(function(e){
				
				if($(this).prop("checked"))
				{					
					$(this).parent().parent().find(":text,select,:checkbox").each(function(){
						var id = $(this).attr("id");
						
						if(!id || id.search(/BUSINESS_ITEMS/) == -1)
						{
							var name = $(this).attr("name");
							if(name.search(/NDSI_PREMIUM/)>-1)
							{
								
								if($(this).parent().prev().children("input:first").prop("checked"))
								{
									$(this).prop('disabled',false);
								}
							}
							else
							{
								$(this).prop('disabled',false);
							}
							
						}						
						if(id && id.search(/NDSI/) >0 && $(this).attr("type") == 'checkbox' )
						{							
							$(this).prop('disabled',false);
							if(null == $(this).attr('is_click'))
							{
								$(this).attr('is_click','true');
							}
							else
							{
								$(this).prop('checked',true);
							}
						}					
						
					});
				}
				else
				{
					$(this).parent().parent().find(":text,select,:checkbox").each(function(){
						var id = $(this).attr("id");
						if(!id || id.search(/BUSINESS_ITEMS/) == -1)
						{
							
							$(this).prop('disabled',true);	
						}
						
						if(id && id.search(/NDSI/) >0 && $(this).attr("type") == 'checkbox' )
						{
							$(this).prop('disabled',true);
							if(null == $(this).attr('is_click'))
							{
								$(this).attr('is_click','true');
							}
							else
							{
								$(this).prop('checked',false);
							}							
						}
										
					});
					
				}
				
			});			
			 $(this).click();
		}
		else if(id && id.search(/NDSI/) >0 && $(this).attr("type") == 'checkbox')
		{
			var is_select = false;
			for(x in business_items)
			{
				if("form[BUSINESS_ITEMS]["+business_items[x]+"]" == id )
				{
					is_select = true;
					break;
				}
			}
			
			if(!$(this).prop("disabled"))
			{
				if(!is_select )
				{
					$(this).prop("checked",true);
				}
			}	

		
			$(this).click(function(e){
				if($(this).prop("checked"))
				{
					$(this).parent().next().find("input").prop('disabled',false);	
				}
				else
				{
					$(this).parent().next().find("input").prop('disabled',true);
				}		
			
			});
			
			if(!$(this).prop("disabled"))
			{
				$(this).click();
			}
			
		}
		 
	});

	
	$("input,select").change(function(e){		
		var id = $(this).attr("id");
		var name = $(this).attr("name");
		if(id == "form[POLICY][TTBLI_INSURANCE_AMOUNT]")
		{
			if($(this).val() == "100+")
			{
				$(this).next().show();
			}
			else
			{
				$(this).next().hide();
			}
		}		
		
		if(name.search(/BUSINESS_ITEMS|PREMIUM|FLOATING_RATE|BUSINESS_DISCOUNT|MVTALCI_SELECT/)>-1 )
		{
			var sum = 0;
			var tmp = 0;
			
			$("#business_items_table").find("input[name$='PREMIUM]']").each(function(){
				var name = $(this).attr("name");
				if(name.search(/DISCOUNT/)==-1 && !$(this).prop("disabled"))
				{
					tmp = parseFloat($(this).val());
					if(!isNaN(tmp)) sum += tmp;
				}
				
			});
			var bus = sum;			
			$("[name='form[POLICY][BUSINESS_STANDARD_PREMIUM]']").val(bus.toFixed(2));			
			var dis = parseFloat($("[name='form[POLICY][BUSINESS_DISCOUNT]']").val());	
			var busdis = dis*bus;
			$("[name='form[POLICY][BUSINESS_PREMIUM]']").val(busdis.toFixed(2));			
			$("[name='form[POLICY][TOTAL_BUSINESS_PREMIUM]']").val(busdis.toFixed(2));
			
			var ci = 0;
			var tax = 0;
			if(!$("[name='form[POLICY][MVTALCI_PREMIUM]']").prop("disabled"))
			{
				ci =  parseFloat($("[name='form[POLICY][MVTALCI_PREMIUM]']").val());
				if(isNaN(ci)) ci =0;
			}
			if(!$("[name='form[POLICY][TRAVEL_TAX_PREMIUM]']").prop("disabled"))
			{
				tax = parseFloat($("[name='form[POLICY][TRAVEL_TAX_PREMIUM]']").val());	
				if(isNaN(tax)) tax = 0;
			}
			
			var total = busdis + ci +tax;
			var total_std = bus + ci +tax;
			
			$("[name='form[POLICY][TOTAL_PREMIUM]']").val(total.toFixed(2));
			$("[name='form[POLICY][TOTAL_STANDARD_PREMIUM]']").val(total_std.toFixed(2));
			$("[name='form[POLICY][TOTAL_MVTALCI_PREMIUM]']").val(ci.toFixed(2));
			$("[name='form[POLICY][TOTAL_TRAVEL_TAX_PREMIUM]']").val(tax.toFixed(2));
			
		}		
	});
	
	$("input,select").change();
	
	$("[name^='form[POLICY][TOTAL']").prop("readonly",true);
	
	$("[name='form[POLICY][MVTALCI_START_TIME]']").change(function(){		
		var starttimestr = $(this).val();
		if(starttimestr.length>0)
		{
			var starttime = new Date();
			starttime.setTime(Date.parse(starttimestr));						
			var endtimestr = $("[name='form[POLICY][MVTALCI_END_TIME]']").val();
			if(endtimestr.length == 0)
			{				
				var endtime = new Date();
				endtime.setTime(starttime.getTime()-1000);
				endtime.setFullYear(starttime.getFullYear()+1);				
				$("[name='form[POLICY][MVTALCI_END_TIME]']").val(endtime.Format("yyyy-MM-dd hh:mm:ss"));
			}
			else
			{
				var endtime = new Date();
				endtime.setTime(Date.parse(endtimestr));
				if(starttime.getTime()>endtime.getTime())
				{
					starttime.setTime(endtime.getTime()-86399000);
					$(this).val(starttime.Format("yyyy-MM-dd hh:mm:ss"));					
				}
			}
			
		}	
	}).change();
	
	$("[name='form[POLICY][MVTALCI_END_TIME]']").change(function(){
		var endtimestr = $(this).val();
		var starttimestr = $("[name='form[POLICY][MVTALCI_START_TIME]']").val();
		if(endtimestr.length>0 && starttimestr.length>0)
		{
			var endtime = new Date();
			var starttime = new Date();
			endtime.setTime(Date.parse(endtimestr));
			starttime.setTime(Date.parse(starttimestr));
			if(endtime.getTime()<starttime.getTime())
			{
				endtime.setTime(starttime.getTime()+86399000);
				$(this).val(endtime.Format("yyyy-MM-dd hh:mm:ss"));
			}			
		}	
	});
	

	$("[name='form[POLICY][BUSINESS_START_TIME]']").change(function(){
		var starttimestr = $(this).val();
		if(starttimestr.length>0)
		{
			var starttime = new Date();
			starttime.setTime(Date.parse(starttimestr));						
			var endtimestr = $("[name='form[POLICY][BUSINESS_END_TIME]']").val();
			if(endtimestr.length == 0)
			{				
				var endtime = new Date();
				endtime.setTime(starttime.getTime()-1000);
				endtime.setFullYear(starttime.getFullYear()+1);				
				$("[name='form[POLICY][BUSINESS_END_TIME]']").val(endtime.Format("yyyy-MM-dd hh:mm:ss"));
			}
			else
			{
				var endtime = new Date();
				endtime.setTime(Date.parse(endtimestr));
				if(starttime.getTime()>endtime.getTime())
				{
					starttime.setTime(endtime.getTime()-86399000);
					$(this).val(starttime.Format("yyyy-MM-dd hh:mm:ss"));					
				}
			}
			
		}	
	}).change();
	
	$("[name='form[POLICY][BUSINESS_END_TIME]']").change(function(){
		var endtimestr = $(this).val();
		var starttimestr = $("[name='form[POLICY][BUSINESS_START_TIME]']").val();
		if(endtimestr.length>0 && starttimestr.length>0)
		{
			var endtime = new Date();
			var starttime = new Date();
			endtime.setTime(Date.parse(endtimestr));
			starttime.setTime(Date.parse(starttimestr));
			if(endtime.getTime()<starttime.getTime())
			{
				endtime.setTime(starttime.getTime()+86399000);
				$(this).val(endtime.Format("yyyy-MM-dd hh:mm:ss"));
			}			
		}	
	});

	
	$(".module_seting_button").button().click(function(){
		var oper = $(this).attr("operation");
		if(oper == 'save')
		{
			var info = zswitchui_validity_check("#policy_calculate_form",true);
			
			if(info.length == 0 )
			{
				if(!$("[name='form[POLICY][TVDI_INSURANCE_AMOUNT]']").prop("disabled"))
				{
					var tvdi_amount = parseFloat($("[name='form[POLICY][TVDI_INSURANCE_AMOUNT]']").val());
					var buying_price = parseFloat($("[name='form[AUTO][BUYING_PRICE]']").val());
					if(tvdi_amount > buying_price)
					{
						$("#policy_calculate_save_title").data('MsgContent','车损险保额必须小于等于新车购置价！')
						.dialog( "option", "height", 150 )	
						.dialog( "option", "position", { my: "center bottom", at: "center top", of: $(this) } )		
						.dialog('open');
						return;
					}
				}
				
				var warninfo = '';
				
				if($("[name='form[POLICY][BUSINESS_START_TIME]']").val() != $("[name='form[POLICY][MVTALCI_START_TIME]']").val() )
				{
					warninfo += '商业险和交强险保险期限不一致。<br/>';
				}
				
				if($("[name='form[AUTO][VIN_NO]']").val().length<17)
				{
					warninfo += '车辆识别码不足17位。<br/>';
				}
				
				if(warninfo.length>0)
				{
					$("#policy_calculate_save_confirm").data('MsgContent',warninfo+'<br/>是否继续保存！')
					.dialog( "option", "height", 220 )	
					.dialog( "option", "position", { my: "center bottom", at: "center top", of: $(this) } )
					.dialog('open');
				}
				else
				{
					$("#policy_calculate_save_confirm").data('MsgContent','请确认要保存算价记录！')
					.dialog( "option", "position", { my: "center bottom", at: "center top", of: $(this) } )
					.dialog('open');
				}
			}
			else
			{
				info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
				$("#policy_calculate_save_title").dialog( "option", "height", 400 )				
				.data('MsgContent',info)
				.dialog( "option", "position", { my: "center bottom", at: "center top", of: $(this) } )
				.dialog('open');			
			}
			

		}
		else if(oper == 'sendsms')
		{
			var url = "index.php?module=PolicyCalculate&action=sendPolicySMS";
			zswitch_ajax_request(url,"policy_calculate_form",function(type,data){	
				
				if(type == "success")
				{	
					zswitch_open_sendsms_dlg(data.callee,decodeURIComponent(data.content));
				}
				else
				{
					$("#policy_calculate_save_title").data('MsgContent',decodeURI(data))
					.dialog( "option", "height", 150 )					
					.dialog('open');
				}
			});			
		}
		else if(oper == 'submitAuditing')
		{
			$("#policy_calculate_submitAuditing_confirm")
			.dialog( "option", "position", { my: "center bottom", at: "center top", of: $(this) } )
			.dialog("open");		
		}
		
	});	
	
	$("#policy_calculate_save_title").dialog({
			title:'保单算价',
			autoOpen:false,
			height:150,
			width:500,
			modal:true,
			appendTo: "body",
			dialogClass:"dialog_default_class",
			buttons:{
				'确定':function(){
					var recordid = 	$(this).data("recordid");					
					if(recordid != null)
					{
						//location.href="index.php?module=PolicyCalculate&action=calculateView&recordid="+$(this).data("recordid");
					}					
					$(this).dialog("close");
				}
			},
			open:function(){
				var html =  '<p style="font-size:12px;"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
				html = html + $(this).data('MsgContent') + "</p>";
				$(this).html(html);			
			}
		});		
	
	$("#policy_calculate_save_confirm").dialog({
			title:'保单算价',
			autoOpen:false,
			height:150,
			width:500,
			modal:true,
			appendTo: "body",
			dialogClass:"dialog_default_class",
			buttons:{
				'确定':function(){							
					$(this).dialog("close");
					var url = "index.php?module=PolicyCalculate&action=save";
					zswitch_ajax_request(url,"policy_calculate_form",function(type,data){		
						if(type == "success")
						{				
							$("#policy_calculate_save_title").data('MsgContent','算价记录保存成功！')
							.dialog( "option", "height", 150 )
							.data("recordid",data)
							.dialog('open');
							$("[name='recordid']").val(data);
							
						}
						else
						{
							$("#policy_calculate_save_title").dialog( "option", "height", 150 )
							.data('MsgContent','算价记录保存失败！')					
							.dialog('open');
						}
					});					
					
				},
				'取消':function(){
					$(this).dialog("close");
				}				
			},
			open:function(){
				var html =  '<p style="font-size:12px;"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
				html = html + $(this).data('MsgContent') + "</p>";
				$(this).html(html);			
			}
		});		

	$("#policy_calculate_submitAuditing_confirm").dialog({
			title:'保单算价',
			autoOpen:false,
			height:180,
			width:500,
			modal:true,
			appendTo: "body",
			dialogClass:"dialog_default_class",			
			buttons:{
				'确定':function(){							
					$(this).dialog("close");
					var url = "index.php?module=PolicyCalculate&action=submitAuditing";
					zswitch_ajax_request(url,"policy_calculate_form",function(type,data){		
						if(type == "success")
						{				
							$("#policy_calculate_save_title").data('MsgContent',data)
							.dialog( "option", "height", 150 )
							.data("recordid",data)
							.dialog('open');
						}
						else
						{
							$("#policy_calculate_save_title").dialog( "option", "height", 150 )
							.data('MsgContent',data)					
							.dialog('open');
						}
					});					
					
				},
				'取消':function(){
					$(this).dialog("close");
				}				
			},
			open:function(){
				var html =  '<p style="font-size:12px;"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
				html = html + "生成订单前请首先保存算价记录！<br/>你确认将此保单提交订单流程吗？ “确定”将提交，“取消”忽略。" + "</p>";
				$(this).html(html);			
			}
		});			
	
{/literal}


</script>	


</body>
</html>
