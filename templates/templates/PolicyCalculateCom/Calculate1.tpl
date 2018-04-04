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
			<td style="width: 350px; text-align:right;" > 
				费率表：
			</td>
			<td style="width: 230px; text-align:right;">
				<span style="text-align:left;" >{$form.PREMIUM_RATE_TABLE}</span>
			</td>
			<td style="padding-left:20px;">
				<button id="policy_calculate_start_btn" style="width:40px;height:26px;"><span>计算</span><img  src="{$IMAGES}/loading_a.gif" width="20px" height="20px" style="display:none"/></button>
			</td>
		</tr>
	</tbody>	
	</table>
</td>
</tr>
<!--
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
-->
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
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.BUYING_PRICE} <button id="policy_calculate_queryBuyingPrice_btn">查询</button></td>
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
			<td  style="border: 1px solid #dcdcdc; text-align:left; background-color:#E6E6FA; ">
				<span style="padding-left:20px;" >
					保费合计：{$form.TOTAL_PREMIUM}
				</span>
				
				<span style="padding-left:20px;" >
					商业险：{$form.TOTAL_BUSINESS_PREMIUM}
				</span>
				<span style="padding-left:20px;">
					交强险：{$form.TOTAL_MVTALCI_PREMIUM}
				</span>
				<span style="padding-left:20px;">
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
			<td class="formlabel" style="border: 1px solid #dcdcdc; width: 80px; background-color: #ececec; ">折扣</td>
			<td class="formfieldcontent" style="width: 167px;border: 1px solid #dcdcdc; " >{$form.MVTALCI_DISCOUNT}</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; width: 80px; background-color: #ececec; ">车船税</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.TRAVEL_TAX_PREMIUM}</td>
		</tr>
		<tr>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #ececec; " >交强险期限</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " colspan="5"> 从 {$form.MVTALCI_START_TIME} 至 {$form.MVTALCI_END_TIME} </td>
		
		</tr>
			
	</tbody>	
	</table>
</td></tr>	
	

<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px ;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>

		<tr>
			<td class="textblue" style="font-weight: bold; width: 80px; border: 1px solid #dcdcdc;background-color:#f5f8f9; "  >商业险摘要</td>
			<td  style="border: 1px solid #dcdcdc; text-align:left; background-color:#E6E6FA; ">
				<span style="padding-left:20px;" >
					商业合计：{$form.BUSINESS_PREMIUM}
				</span>
				<span style="padding-left:20px;" >
					折扣：{$form.BUSINESS_DISCOUNT}
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


<tr style="display:none;"><td>
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
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #edecec; "> </td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; ">  </td>
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
		    <td style="font-weight: bold; border: 1px solid #dcdcdc; background-color: #ececec; " align="center" >项目保费(元)</td>
		<!--    <td style="font-weight: bold; border: 1px solid #dcdcdc; background-color: #ececec; " align="center" >折后保费(元)</td> -->
			<td style="font-weight: bold; border-width: 1px 0px 1px 1px ;border-style: solid none solid solid ; border-color: #dcdcdc; background-color: #ececec; background-color: #ececec; width:20px; " align="center" ></td>			
		    <td style="font-weight: bold; border-width: 1px 1px 1px 0px ;border-style: solid none solid solid ; border-color: #dcdcdc; background-color: #ececec; background-color: #ececec; " align="left" >不计免赔保费(元)</td>
			
		</tr>

		<tr>
			<td id="policy_calculate_base_insurance_box" class="textblue" style="padding-right: 5px; border: 1px solid #dcdcdc;  " rowspan="5" align="center">
			<p>基</p>
			<p>本</p>
			<p>险</p>
			</td>
		
			<td style="padding-right: 5px; border: 1px solid #dcdcdc;  width: 160px;" align="right"><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TVDI]">车辆损失险</label></span><input id="form[BUSINESS_ITEMS][TVDI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TVDI"  /></td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;{$form.TVDI_INSURANCE_AMOUNT} </td>
			<td style="border: 1px solid #dcdcdc; " align="left" >
			&nbsp;<!--可选免赔额：{$form.DOC_AMOUNT} 元-->
			</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TVDI_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.TVDI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TVDI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TVDI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TVDI_NDSI_PREMIUM}</td>

		</tr>		
		<tr>

			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TTBLI]">第三方责任险</label></span><input id="form[BUSINESS_ITEMS][TTBLI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TTBLI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;{$form.TTBLI_INSURANCE_AMOUNT} 万<!--{$form.TTBLI_INSURANCE_AMOUNT_EXT}--></td>
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
			<td class="textblue" style="padding-right: 5px; border: 1px solid #dcdcdc; " rowspan="6" align="center" >
			<p>附</p>
			<p>加</p>
			<p>险</p>
			</td>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][BSDI]">车身划痕险</label></span><input id="form[BUSINESS_ITEMS][BSDI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BSDI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span>{$form.BSDI_INSURANCE_AMOUNT}</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.BSDI_PREMIUM}</td>
			<!-- <td style="border: 1px solid #dcdcdc; " >{$form.BSDI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][BSDI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BSDI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.BSDI_NDSI_PREMIUM}</td>
					
		</tr>
		
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][SLOI]">自燃损失险</label></span><input id="form[BUSINESS_ITEMS][SLOI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="SLOI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span>{$form.SLOI_INSURANCE_AMOUNT}</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.SLOI_PREMIUM}</td>
		   <!-- <td style="border: 1px solid #dcdcdc; " >{$form.SLOI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][SLOI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="SLOI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.SLOI_NDSI_PREMIUM}</td>
					
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][BGAI]">玻璃单独破碎险</label></span><input id="form[BUSINESS_ITEMS][BGAI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BGAI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ><span>&nbsp;玻璃产地：{$form.GLASS_ORIGIN}</span></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.BGAI_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.BGAI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " > <!--<input id="form[BUSINESS_ITEMS][BGAI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BGAI_NDSI" />--> </td>
			<td style="border: 1px solid #dcdcdc; " > <!--{$form.BGAI_NDSI_PREMIUM} --> </td>
					
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][NIELI]">新增设备损失险</label></span><input id="form[BUSINESS_ITEMS][NIELI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="NIELI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span>{$form.NIELI_INSURANCE_AMOUNT}</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<button id="policy_calculate_nieli_device_btn">设备信息</button>{$form.NIELI_DEVICE_LIST}</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.NIELI_PREMIUM}</td>
		<!--	<td style="border: 1px solid #dcdcdc; " >{$form.NIELI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][NIELI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="NIELI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.NIELI_NDSI_PREMIUM}</td>
	
				
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][VWTLI]">发动机涉水损失险</label></span><input id="form[BUSINESS_ITEMS][VWTLI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="VWTLI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span><!--{$form.VWTLI_INSURANCE_AMOUNT}--></span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.VWTLI_PREMIUM}</td>
	<!--	<td style="border: 1px solid #dcdcdc; " >{$form.VWTLI_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][VWTLI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="VWTLI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.VWTLI_NDSI_PREMIUM}</td>					
		</tr>			
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][STSFS]">指定专修厂</label></span><input id="form[BUSINESS_ITEMS][STSFS]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="STSFS" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ><span>&nbsp;上浮比例：{$form.STSFS_RATE}</span></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.STSFS_PREMIUM}</td>
	<!--	<td style="border: 1px solid #dcdcdc; " >{$form.CUSTOM1_DISCOUNT_PREMIUM}</td> -->
			<td style="border: 1px solid #dcdcdc; " > <!--<input id="form[BUSINESS_ITEMS][STSFS_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="STSFS_NDSI" /> --> </td>
			<td style="border: 1px solid #dcdcdc; " > <!--{$form.STSFS_NDSI_PREMIUM}--> </td>					
		</tr>			
{*	
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
*}	
	
		
	</tbody>	
	</table>
</td></tr>	

<!--

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
-->
<tr><td>
<div class="CLIENT_JOBVIEW_BUTTONS">
	<button class="module_seting_button" operation="save">保存</button> 
	<button class="module_seting_button" operation="sendsms">发送短信</button>
	<!--<button class="module_seting_button" operation="submitAuditing">提交核保</button>-->
</div>
</td></tr>
</tbody>	
</table>
</div>
</form>
	
<div id = "policy_calculate_save_title"></div>
<div id = "policy_calculate_save_confirm"></div>
<div id = "policy_calculate_submitAuditing_confirm"></div>
<div id = "policy_calculate_queryBuyingPrice_dlg"></div>
<div id = "policy_calculate_nieli_device_dlg"></div> 

<script type="text/javascript">
	var pc = new policy_calculate();
	$("#policy_calculate_form").find("td").css("height","25px").css("line-height","25px");
	$("#policy_calculate_form").find("input,select,button").css("font-size","12px");
	pc.init("#policy_calculate_form","{$MODEL}",{$ALLOW_INSURANCES},{$SELECTED_INSURANCES},{$DESIGNATED_DRIVER},"{$MODULE}");
</script>	


