<div id="main_view_client" style="height:0px;min-height:0px;min-width:0px;"></div>
<form id="policy_calculate_form"  style="margin:0px;width:880px;"  onsubmit="return false;">
<input type = "hidden" name="recordid" value="{$recordid}" />
<input type = "hidden" name="accountid" value="{$accountid}" />
<input type = "hidden" name="operation" value="{$operation}" />





<!-- {$form.MOBILE} -->
<!-- {$form.HOLDER} -->
{$form.HOLDER_IDENTIFY_TYPE}
{$form.HOLDER_ADDRESS}
<!-- {$form.HOLDER_IDENTIFY_NO} -->
{$form.INSURANT}
{$form.INSURANT_IDENTIFY_TYPE}
{$form.INSURANT_ADDRESS}
{$form.INSURANT_IDENTIFY_NO}
{$form.INSURANCE_COMPANY}


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
			<td style="padding-left:10px;">
				<span id = 'refresh'><img  src="/zsitsms/public/images/sync.gif" width="20px" height="20px"/></span>
			</td>
			<td style="width: 320px; text-align:right;" >
				费率表：
			</td>
			<td style="width: 230px; text-align:right;">
				<span style="text-align:left;" >{$form.PREMIUM_RATE_TABLE}</span>
			</td>

			<td style="padding-left: 8px;height: 25px;line-height: 25px;">

				<button id="policy_calculate_start_btn" style="width: 53px;font-size: 12px;" class="client_detailview_operation_button small ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" title="算价" onclick="" role="button" aria-disabled="false">
					<span class="ui-button-text" style="position: relative;margin-left: 3px;">算价</span>
					<img src="/zsitsms/public/images/loading_a.gif" width="20px" height="20px" style="display:none">
				</button>
			</td>
		</tr>
	</tbody>
	</table>
</td>
</tr>




<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px 0px 2px 0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>

		<tr>
			<td class="textblue" style="font-weight: bold; width: 80px; border: 1px solid #dcdcdc; " rowspan="5">被保险人信息</td>
			<td class="formlabel" style="width: 95px; border: 1px solid #dcdcdc; background-color: #ececec; ">身份证/机构代码</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.HOLDER_IDENTIFY_NO}</td>
			<td class="formlabel" style="width: 74px; border: 1px solid #dcdcdc; background-color: #ececec; ">被保险人姓名</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.HOLDER}</td>
			<td class="formlabel" style="width: 50px; border: 1px solid #dcdcdc; background-color: #ececec; ">手机号码</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.MOBILE}</td>
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
			<td class="formfieldcontent" style="width: 167px; border: 1px solid #dcdcdc; ">{$form.LICENSE_NO}
				<button style="width: 62px;font-size: 12px;padding:  5px;" class="client_detailview_operation_button small ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" title="快速报价"  id ="quickinsure" role="button" aria-disabled="false">
					<span>快速报价</span>
				</button>
			</td>
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
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; ">{$form.BUYING_PRICE}
				<button style="width: 65px;margin-left: 2px;font-size: 12px;padding: 5px;" id="policy_calculate_queryBuyingPrice_btn" class="client_detailview_operation_button small ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" title="查询车型" onclick="" role="button" aria-disabled="false">
					<span>查询车型</span>
				</button>
			</td>
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
			<td class="formlabel" style="display:none;">核定载质量</td>
			<td class="formfieldcontent" style="display:none;">{$form.TONNAGE}</td>
			<td class="formlabel" style="display:none;">模型代码</td>
			<td class="formfieldcontent" style="display:none;">{$form.INDUSTY_MODEL_CODE}</td>
			<td class="formlabel" style="display:none;">车辆折扣价</td>
			<td class="formfieldcontent" style="display:none;">{$form.DISCOUNT_PRICE}</td>


			<td class="formlabel" style="display:none;"></td>
			<td class="formfieldcontent" style="display:none;">{$form.DZA_DEMANDNOS}</td>

			<td class="formlabel" style="display:none;"></td>
			<td class="formfieldcontent" style="display:none;">{$form.DZA_CHECKCODES}</td>

			<td class="formlabel" style="display:none;"></td>
			<td class="formfieldcontent" style="display:none;">{$form.DAA_DEMANDNOS}</td>

			<td class="formlabel" style="display:none;"></td>
			<td class="formfieldcontent" style="display:none;">{$form.DAA_CHECKCODES}</td>

			<td class="formlabel" style="display:none;"></td>
			<td class="formfieldcontent" style="display:none;">{$form.MODEL_ALIAS}</td>

		</tr>
	</tbody>
	</table>
</td></tr>

<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>

		<tr style="" data-mce-style="">
			<td class="textblue" style="width: 80px; border: 1px solid #dcdcdc; " align="center">交强险期限</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " > 从 {$form.MVTALCI_START_TIME} 至 {$form.MVTALCI_END_TIME}</td>
			<td class="textblue" style="width: 80px; border: 1px solid #dcdcdc; " align="center">商业险期限</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " > 从 {$form.BUSINESS_START_TIME} 至 {$form.BUSINESS_END_TIME}</td>
		</tr>
	</tbody>
	</table>
</td></tr>

<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px 0px 2px 0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>

		<tr>
			<td class="textblue" style="font-weight: bold; width: 80px; border: 1px solid #dcdcdc; " rowspan="2" >交强险摘要<input id="form[MVTALCI_SELECT]" type="checkbox" name="form[MVTALCI_SELECT][]" value="YES" /></td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; width: 80px; background-color: #ececec; ">保费</td>
			<td class="formfieldcontent" style="width: 167px;border: 1px solid #dcdcdc; " >{$form.MVTALCI_PREMIUM}</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; width: 80px; background-color: #ececec; ">保司折扣</td>
			<td class="formfieldcontent" style="width: 167px;border: 1px solid #dcdcdc; " >{$form.MVTALCI_DISCOUNT}</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; width: 80px; background-color: #ececec; ">车船税</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " >{$form.TRAVEL_TAX_PREMIUM}</td>
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
					商业险合计：{$form.BUSINESS_PREMIUM}
				</span>
				<span style="padding-left:20px;" >
					保司折扣：{$form.BUSINESS_DISCOUNT}
				</span>
				<span style="padding-left:20px;" >
					自定折扣：{$form.BUSINESS_CUSTOM_DISCOUNT}
				</span>
				<span style="padding-left:20px;" >
					折后合计：{$form.BUSINESS_DISCOUNT_PREMIUM}
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
			<td class="textblue" style="font-weight: bold; width: 80px; border: 1px solid #dcdcdc; background-color:#f5f8f9;"  >费用总览</td>
			<td  style="border: 1px solid #dcdcdc; text-align:left; background-color:#E6E6FA; ">
				<span style="padding-left:20px;" >
					保费合计：{$form.TOTAL_PREMIUM}
				</span>

				<span style="padding-left:20px;" >
					折后商业险：{$form.TOTAL_BUSINESS_PREMIUM}
				</span>
				<span style="padding-left:20px;">
					交强险：{$form.TOTAL_MVTALCI_PREMIUM}
				</span>
			</td>
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
			<td class="textblue" style="padding-right: 5px; border: 1px solid #dcdcdc; " rowspan="9" align="center" >
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

			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][NIELI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="NIELI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.NIELI_NDSI_PREMIUM}</td>


		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][VWTLI]">发动机涉水损失险</label></span><input id="form[BUSINESS_ITEMS][VWTLI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="VWTLI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span></span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " >{$form.VWTLI_PREMIUM}</td>

			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][VWTLI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="VWTLI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.VWTLI_NDSI_PREMIUM}</td>
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][STSFS]">指定专修厂</label></span><input id="form[BUSINESS_ITEMS][STSFS]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="STSFS" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ><span>&nbsp;上浮比例：{$form.STSFS_RATE}</span></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.STSFS_PREMIUM}</td>

			<td style="border: 1px solid #dcdcdc; " ></td>
			<td style="border: 1px solid #dcdcdc; " ></td>
		</tr>

		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][RDCCI]">修理期间费用补偿险</label></span><input id="form[BUSINESS_ITEMS][RDCCI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="RDCCI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;{$form.RDCCI_INSURANCE_UNIT} X {$form.RDCCI_INSURANCE_QUANTITY}天 {$form.RDCCI_INSURANCE_AMOUNT}</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.RDCCI_PREMIUM}</td>

			<td style="border: 1px solid #dcdcdc; " ></td>
			<td style="border: 1px solid #dcdcdc; " ></td>
		</tr>

		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][MVLINFTPSI]">第三方特约险</label></span><input id="form[BUSINESS_ITEMS][MVLINFTPSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="MVLINFTPSI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span></span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.MVLINFTPSI_PREMIUM}</td>
			<td style="border: 1px solid #dcdcdc; " ></td>
			<td style="border: 1px solid #dcdcdc; " ></td>
		</tr>

		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TTBLI_DOUBLE]">第三方节假日翻倍险</label></span><input id="form[BUSINESS_ITEMS][TTBLI_DOUBLE]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TTBLI_DOUBLE" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span></span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ></td>
			<td style="border: 1px solid #dcdcdc; " >{$form.TTBLI_DOUBLE_PREMIUM}</td>
			<td style="border: 1px solid #dcdcdc; " ></td>
			<td style="border: 1px solid #dcdcdc; " ></td>
		</tr>

	</tbody>
	</table>
</td></tr>

<tr><td>
<div class="CLIENT_JOBVIEW_BUTTONS">
	<button class="module_seting_button" operation="save">保存</button>
	<button class="module_seting_button" operation="saveas">另存为</button>
	<button class="module_seting_button" operation="sendsms">发送短信</button>
	<button class="module_seting_button" operation="submitAuditing">提交核保</button>
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
<div id = "policy_calculate_check_dlg"></div>
<div id = "policy_calculate_nieli_device_dlg"></div>
<div id = "policy_calculate_checkcode_dlg"></div>

<script type="text/javascript">
	var pc = new policy_calculate();
	$("#policy_calculate_form").find("td").css("height","25px").css("line-height","25px");
	$("#policy_calculate_form").find("input,select,button").css("font-size","12px");
	pc.init("#policy_calculate_form","{$MODEL}",{$ALLOW_INSURANCES},{$SELECTED_INSURANCES},{$DESIGNATED_DRIVER},"{$MODULE}");
	/***********算价日期联动************/
	{literal}
		$("input[name='form[POLICY][MVTALCI_START_TIME]'],input[name='form[POLICY][MVTALCI_END_TIME]'],input[name='form[POLICY][BUSINESS_START_TIME]'],input[name='form[POLICY][BUSINESS_END_TIME]']").unbind("change");
		$("input[name='form[POLICY][MVTALCI_START_TIME]'],input[name='form[POLICY][MVTALCI_END_TIME]'],input[name='form[POLICY][BUSINESS_START_TIME]'],input[name='form[POLICY][BUSINESS_END_TIME]']").datetimepicker("option", "timeFormat", "hh:mm:ss");//set default time

		$("input[name='form[POLICY][MVTALCI_START_TIME]'],input[name='form[POLICY][MVTALCI_END_TIME]'],input[name='form[POLICY][BUSINESS_START_TIME]'],input[name='form[POLICY][BUSINESS_END_TIME]']").datetimepicker("option", {
	        onClose: function(dateText, inst){
	        	var params = {name:$(this).attr("name").replace("END_TIME", "START_TIME"), month:-12, day:1, suffix:" 00:00:00"};
	        	if($(this).attr("name").indexOf("START_TIME") > 0){
					params.name   = $(this).attr("name").replace("START_TIME", "END_TIME");
					params.month  = 12;
					params.day    = -1;
					params.suffix = " 23:59:59"
	        	}
	        	var select_time = new Date($(this).val().replace(/-/g, "/"));//replace "-" to "/" for safari
	        	var result_time = new Date(new Date(select_time.setMonth(select_time.getMonth() + params.month)).setDate(select_time.getDate() + params.day)).Format("yyyy-MM-dd") + params.suffix;//Y-1 D+1
	        	$("input[name='" + params.name + "']").val(result_time);

	        	if($(this).attr("name") == "form[POLICY][BUSINESS_START_TIME]" || $(this).attr("name") == "form[POLICY][BUSINESS_END_TIME]")
	        	{
		        	if($("input[name='form[AUTO][BUYING_PRICE]']").val() !='' && $("#buying_price_list").data("module") != null){
					    var url = "index.php?module=" + $("#buying_price_list").data("module") + "&action=depreciation";
						zswitch_ajax_request(url, "policy_calculate_form", function(type,data){
							if(type == "success")
							{
								var depre = parseFloat(data).toFixed(2);
								$("#policy_calculate_form").find("[name='form[POLICY][TVDI_INSURANCE_AMOUNT]']").val(depre);
								$("#policy_calculate_form").find("[name='form[POLICY][TWCDMVI_INSURANCE_AMOUNT]']").val(depre);
								$("#policy_calculate_form").find("[name='form[POLICY][SLOI_INSURANCE_AMOUNT]']").val(depre).change();
								$("#policy_calculate_form").find("[name='form[AUTO][DISCOUT_PRICE]']").val(depre).change();
							}
							else
							{
								$("#policy_calculate_save_title").data("MsgContent",data).dialog("open");
							}
						});
					}
				}
	        }
	    });


		$("#refresh").click(function(){
			var msg = '刷新会删除缓存费率表，是否愿意？';
			if(confirm(msg) == true)
			{
				var vin_no = $("#policy_calculate_form").find("[name='form[AUTO][VIN_NO]']").val();
				var accountid = $("input:hidden[name='accountid']").val();
				var url = "index.php?module=" + pc_module + "&action=refreshPremium";
				$.get(url,function(result){
					var obj = eval('(' + result + ')');
					if(obj.type == 'success')
					{
						$("#policy_calculate_content").load("index.php?module=PolicyCalculateCom&action=calculateView&recordid=&vin_no="+vin_no+"&accountid="+accountid+"");
					}
				   	else
				   	{
				   		alert(obj.data);
				   	}
				});
			}
		})

    {/literal}
</script>


