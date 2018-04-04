<?php /* Smarty version Smarty-3.1.12, created on 2018-03-30 15:03:09
         compiled from "/var/www/html/zsitsms/templates/templates/PolicyCalculateCom/Calculate.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19961810155abde12d656a05-03798893%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a146a386fdc8078385ac5c16241685a660b48795' => 
    array (
      0 => '/var/www/html/zsitsms/templates/templates/PolicyCalculateCom/Calculate.tpl',
      1 => 1520844696,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19961810155abde12d656a05-03798893',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'recordid' => 0,
    'accountid' => 0,
    'operation' => 0,
    'form' => 0,
    'MODEL' => 0,
    'ALLOW_INSURANCES' => 0,
    'SELECTED_INSURANCES' => 0,
    'DESIGNATED_DRIVER' => 0,
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_5abde12da8e579_86520117',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5abde12da8e579_86520117')) {function content_5abde12da8e579_86520117($_smarty_tpl) {?><div id="main_view_client" style="height:0px;min-height:0px;min-width:0px;"></div>
<form id="policy_calculate_form"  style="margin:0px;width:880px;"  onsubmit="return false;">
<input type = "hidden" name="recordid" value="<?php echo $_smarty_tpl->tpl_vars['recordid']->value;?>
" />
<input type = "hidden" name="accountid" value="<?php echo $_smarty_tpl->tpl_vars['accountid']->value;?>
" />
<input type = "hidden" name="operation" value="<?php echo $_smarty_tpl->tpl_vars['operation']->value;?>
" />





<!-- <?php echo $_smarty_tpl->tpl_vars['form']->value['MOBILE'];?>
 -->
<!-- <?php echo $_smarty_tpl->tpl_vars['form']->value['HOLDER'];?>
 -->
<?php echo $_smarty_tpl->tpl_vars['form']->value['HOLDER_IDENTIFY_TYPE'];?>

<?php echo $_smarty_tpl->tpl_vars['form']->value['HOLDER_ADDRESS'];?>

<!-- <?php echo $_smarty_tpl->tpl_vars['form']->value['HOLDER_IDENTIFY_NO'];?>
 -->
<?php echo $_smarty_tpl->tpl_vars['form']->value['INSURANT'];?>

<?php echo $_smarty_tpl->tpl_vars['form']->value['INSURANT_IDENTIFY_TYPE'];?>

<?php echo $_smarty_tpl->tpl_vars['form']->value['INSURANT_ADDRESS'];?>

<?php echo $_smarty_tpl->tpl_vars['form']->value['INSURANT_IDENTIFY_NO'];?>

<?php echo $_smarty_tpl->tpl_vars['form']->value['INSURANCE_COMPANY'];?>



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
				<span style="text-align:left;" ><?php echo $_smarty_tpl->tpl_vars['form']->value['NO'];?>
</span>
			</td>
			<td style="padding-left:10px;">
				<span id = 'refresh'><img  src="/zsitsms/public/images/sync.gif" width="20px" height="20px"/></span>
			</td>
			<td style="width: 320px; text-align:right;" >
				费率表：
			</td>
			<td style="width: 230px; text-align:right;">
				<span style="text-align:left;" ><?php echo $_smarty_tpl->tpl_vars['form']->value['PREMIUM_RATE_TABLE'];?>
</span>
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
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['HOLDER_IDENTIFY_NO'];?>
</td>
			<td class="formlabel" style="width: 74px; border: 1px solid #dcdcdc; background-color: #ececec; ">被保险人姓名</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['HOLDER'];?>
</td>
			<td class="formlabel" style="width: 50px; border: 1px solid #dcdcdc; background-color: #ececec; ">手机号码</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['MOBILE'];?>
</td>
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
			<td class="formfieldcontent" style="width: 167px; border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['LICENSE_NO'];?>

				<button style="width: 62px;font-size: 12px;padding:  5px;" class="client_detailview_operation_button small ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" title="快速报价"  id ="quickinsure" role="button" aria-disabled="false">
					<span>快速报价</span>
				</button>
			</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #ececec; ">车牌类型</td>
			<td class="formfieldcontent" style="width: 167px; border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['LICENSE_TYPE'];?>
</td>
			<td class="formlabel" style="width: 80px; border: 1px solid #dcdcdc; background-color: #ececec; ">拥有人</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['OWNER'];?>
</td>
		</tr>
		<tr>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">车辆识别码</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['VIN_NO'];?>
</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; background-color: #edecec; ">发动机号</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['ENGINE_NO'];?>
</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #ececec; ">品牌型号</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['MODEL'];?>
</td>
		</tr>
		<tr>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #ececec; ">排量</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['ENGINE'];?>
</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">核定载客</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['SEATS'];?>
</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">整备质量</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['KERB_MASS'];?>
</td>
		</tr>
		<tr>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #ececec; ">型号代码</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['MODEL_CODE'];?>
</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">新车购置价</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['BUYING_PRICE'];?>

				<button style="width: 65px;margin-left: 2px;font-size: 12px;padding: 5px;" id="policy_calculate_queryBuyingPrice_btn" class="client_detailview_operation_button small ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" title="查询车型" onclick="" role="button" aria-disabled="false">
					<span>查询车型</span>
				</button>
			</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">注册日期</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['ENROLL_DATE'];?>
</td>
		</tr>
		<tr>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">车辆种类</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['VEHICLE_TYPE'];?>
</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #edecec; ">使用性质</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['USE_CHARACTER'];?>
</td>
			<td class="formlabel" style=" border: 1px solid #dcdcdc; background-color: #ececec; ">产地</td>
			<td class="formfieldcontent" style=" border: 1px solid #dcdcdc; "><?php echo $_smarty_tpl->tpl_vars['form']->value['ORIGIN'];?>
</td>
			<td class="formlabel" style="display:none;">核定载质量</td>
			<td class="formfieldcontent" style="display:none;"><?php echo $_smarty_tpl->tpl_vars['form']->value['TONNAGE'];?>
</td>
			<td class="formlabel" style="display:none;">模型代码</td>
			<td class="formfieldcontent" style="display:none;"><?php echo $_smarty_tpl->tpl_vars['form']->value['INDUSTY_MODEL_CODE'];?>
</td>
			<td class="formlabel" style="display:none;">车辆折扣价</td>
			<td class="formfieldcontent" style="display:none;"><?php echo $_smarty_tpl->tpl_vars['form']->value['DISCOUNT_PRICE'];?>
</td>


			<td class="formlabel" style="display:none;"></td>
			<td class="formfieldcontent" style="display:none;"><?php echo $_smarty_tpl->tpl_vars['form']->value['DZA_DEMANDNOS'];?>
</td>

			<td class="formlabel" style="display:none;"></td>
			<td class="formfieldcontent" style="display:none;"><?php echo $_smarty_tpl->tpl_vars['form']->value['DZA_CHECKCODES'];?>
</td>

			<td class="formlabel" style="display:none;"></td>
			<td class="formfieldcontent" style="display:none;"><?php echo $_smarty_tpl->tpl_vars['form']->value['DAA_DEMANDNOS'];?>
</td>

			<td class="formlabel" style="display:none;"></td>
			<td class="formfieldcontent" style="display:none;"><?php echo $_smarty_tpl->tpl_vars['form']->value['DAA_CHECKCODES'];?>
</td>

			<td class="formlabel" style="display:none;"></td>
			<td class="formfieldcontent" style="display:none;"><?php echo $_smarty_tpl->tpl_vars['form']->value['MODEL_ALIAS'];?>
</td>

		</tr>
	</tbody>
	</table>
</td></tr>

<tr><td>
	<table class="mceItemTable" style="table-layout:fixed;margin:0px;width: 100%; border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0">
	<tbody>

		<tr style="" data-mce-style="">
			<td class="textblue" style="width: 80px; border: 1px solid #dcdcdc; " align="center">交强险期限</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " > 从 <?php echo $_smarty_tpl->tpl_vars['form']->value['MVTALCI_START_TIME'];?>
 至 <?php echo $_smarty_tpl->tpl_vars['form']->value['MVTALCI_END_TIME'];?>
</td>
			<td class="textblue" style="width: 80px; border: 1px solid #dcdcdc; " align="center">商业险期限</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " > 从 <?php echo $_smarty_tpl->tpl_vars['form']->value['BUSINESS_START_TIME'];?>
 至 <?php echo $_smarty_tpl->tpl_vars['form']->value['BUSINESS_END_TIME'];?>
</td>
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
			<td class="formfieldcontent" style="width: 167px;border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['MVTALCI_PREMIUM'];?>
</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; width: 80px; background-color: #ececec; ">保司折扣</td>
			<td class="formfieldcontent" style="width: 167px;border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['MVTALCI_DISCOUNT'];?>
</td>
			<td class="formlabel" style="border: 1px solid #dcdcdc; width: 80px; background-color: #ececec; ">车船税</td>
			<td class="formfieldcontent" style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TRAVEL_TAX_PREMIUM'];?>
</td>
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
					商业险合计：<?php echo $_smarty_tpl->tpl_vars['form']->value['BUSINESS_PREMIUM'];?>

				</span>
				<span style="padding-left:20px;" >
					保司折扣：<?php echo $_smarty_tpl->tpl_vars['form']->value['BUSINESS_DISCOUNT'];?>

				</span>
				<span style="padding-left:20px;" >
					自定折扣：<?php echo $_smarty_tpl->tpl_vars['form']->value['BUSINESS_CUSTOM_DISCOUNT'];?>

				</span>
				<span style="padding-left:20px;" >
					折后合计：<?php echo $_smarty_tpl->tpl_vars['form']->value['BUSINESS_DISCOUNT_PREMIUM'];?>

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
					保费合计：<?php echo $_smarty_tpl->tpl_vars['form']->value['TOTAL_PREMIUM'];?>

				</span>

				<span style="padding-left:20px;" >
					折后商业险：<?php echo $_smarty_tpl->tpl_vars['form']->value['TOTAL_BUSINESS_PREMIUM'];?>

				</span>
				<span style="padding-left:20px;">
					交强险：<?php echo $_smarty_tpl->tpl_vars['form']->value['TOTAL_MVTALCI_PREMIUM'];?>

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
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;<?php echo $_smarty_tpl->tpl_vars['form']->value['TVDI_INSURANCE_AMOUNT'];?>
 </td>
			<td style="border: 1px solid #dcdcdc; " align="left" >
			&nbsp;<!--可选免赔额：<?php echo $_smarty_tpl->tpl_vars['form']->value['DOC_AMOUNT'];?>
 元-->
			</td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TVDI_PREMIUM'];?>
</td>
		<!--	<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TVDI_DISCOUNT_PREMIUM'];?>
</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TVDI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TVDI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TVDI_NDSI_PREMIUM'];?>
</td>

		</tr>
		<tr>

			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TTBLI]">第三方责任险</label></span><input id="form[BUSINESS_ITEMS][TTBLI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TTBLI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;<?php echo $_smarty_tpl->tpl_vars['form']->value['TTBLI_INSURANCE_AMOUNT'];?>
 万<!--<?php echo $_smarty_tpl->tpl_vars['form']->value['TTBLI_INSURANCE_AMOUNT_EXT'];?>
--></td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TTBLI_PREMIUM'];?>
</td>
		<!--	<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TTBLI_DISCOUNT_PREMIUM'];?>
</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TTBLI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TTBLI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TTBLI_NDSI_PREMIUM'];?>
</td>

		</tr>
		<tr>

			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TWCDMVI]">盗抢险</label></span><input id="form[BUSINESS_ITEMS][TWCDMVI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TWCDMVI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;<?php echo $_smarty_tpl->tpl_vars['form']->value['TWCDMVI_INSURANCE_AMOUNT'];?>
</td>
			<td style="border: 1px solid #dcdcdc; " align="left" >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TWCDMVI_PREMIUM'];?>
</td>
		<!--	<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TWCDMVI_DISCOUNT_PREMIUM'];?>
</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TWCDMVI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TWCDMVI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TWCDMVI_NDSI_PREMIUM'];?>
</td>

		</tr>
		<tr>

			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TCPLI_DRIVER]">车上人员责任险(司机)</label></span><input id="form[BUSINESS_ITEMS][TCPLI_DRIVER]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TCPLI_DRIVER" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<?php echo $_smarty_tpl->tpl_vars['form']->value['TCPLI_INSURANCE_DRIVER_AMOUNT'];?>
</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TCPLI_DRIVER_PREMIUM'];?>
</td>
		<!--	<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TCPLI_DRIVER_DISCOUNT_PREMIUM'];?>
</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TCPLI_DRIVER_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TCPLI_DRIVER_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TCPLI_DRIVER_NDSI_PREMIUM'];?>
</td>

		</tr>
		<tr>

			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TCPLI_PASSENGER]">车上人员责任险(乘客)</label></span><input id="form[BUSINESS_ITEMS][TCPLI_PASSENGER]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TCPLI_PASSENGER" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<?php echo $_smarty_tpl->tpl_vars['form']->value['TCPLI_INSURANCE_PASSENGER_AMOUNT'];?>
&nbsp;X&nbsp;<?php echo $_smarty_tpl->tpl_vars['form']->value['TCPLI_PASSENGER_COUNT'];?>
人</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TCPLI_PASSENGER_PREMIUM'];?>
</td>
		<!--	<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TCPLI_PASSENGER_DISCOUNT_PREMIUM'];?>
</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][TCPLI_PASSENGER_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TCPLI_PASSENGER_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TCPLI_PASSENGER_NDSI_PREMIUM'];?>
</td>

		</tr>
		<tr>
			<td class="textblue" style="padding-right: 5px; border: 1px solid #dcdcdc; " rowspan="9" align="center" >
			<p>附</p>
			<p>加</p>
			<p>险</p>
			</td>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][BSDI]">车身划痕险</label></span><input id="form[BUSINESS_ITEMS][BSDI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BSDI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span><?php echo $_smarty_tpl->tpl_vars['form']->value['BSDI_INSURANCE_AMOUNT'];?>
</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['BSDI_PREMIUM'];?>
</td>
			<!-- <td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['BSDI_DISCOUNT_PREMIUM'];?>
</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][BSDI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BSDI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['BSDI_NDSI_PREMIUM'];?>
</td>
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][SLOI]">自燃损失险</label></span><input id="form[BUSINESS_ITEMS][SLOI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="SLOI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span><?php echo $_smarty_tpl->tpl_vars['form']->value['SLOI_INSURANCE_AMOUNT'];?>
</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['SLOI_PREMIUM'];?>
</td>
		   <!-- <td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['SLOI_DISCOUNT_PREMIUM'];?>
</td> -->
			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][SLOI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="SLOI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['SLOI_NDSI_PREMIUM'];?>
</td>

		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][BGAI]">玻璃单独破碎险</label></span><input id="form[BUSINESS_ITEMS][BGAI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BGAI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ><span>&nbsp;玻璃产地：<?php echo $_smarty_tpl->tpl_vars['form']->value['GLASS_ORIGIN'];?>
</span></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['BGAI_PREMIUM'];?>
</td>
		<!--	<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['BGAI_DISCOUNT_PREMIUM'];?>
</td> -->
			<td style="border: 1px solid #dcdcdc; " > <!--<input id="form[BUSINESS_ITEMS][BGAI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="BGAI_NDSI" />--> </td>
			<td style="border: 1px solid #dcdcdc; " > <!--<?php echo $_smarty_tpl->tpl_vars['form']->value['BGAI_NDSI_PREMIUM'];?>
 --> </td>

		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][NIELI]">新增设备损失险</label></span><input id="form[BUSINESS_ITEMS][NIELI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="NIELI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span><?php echo $_smarty_tpl->tpl_vars['form']->value['NIELI_INSURANCE_AMOUNT'];?>
</span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<button id="policy_calculate_nieli_device_btn">设备信息</button><?php echo $_smarty_tpl->tpl_vars['form']->value['NIELI_DEVICE_LIST'];?>
</td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['NIELI_PREMIUM'];?>
</td>

			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][NIELI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="NIELI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['NIELI_NDSI_PREMIUM'];?>
</td>


		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][VWTLI]">发动机涉水损失险</label></span><input id="form[BUSINESS_ITEMS][VWTLI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="VWTLI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span></span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['VWTLI_PREMIUM'];?>
</td>

			<td style="border: 1px solid #dcdcdc; " ><input id="form[BUSINESS_ITEMS][VWTLI_NDSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="VWTLI_NDSI" /></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['VWTLI_NDSI_PREMIUM'];?>
</td>
		</tr>
		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][STSFS]">指定专修厂</label></span><input id="form[BUSINESS_ITEMS][STSFS]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="STSFS" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ><span>&nbsp;上浮比例：<?php echo $_smarty_tpl->tpl_vars['form']->value['STSFS_RATE'];?>
</span></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['STSFS_PREMIUM'];?>
</td>

			<td style="border: 1px solid #dcdcdc; " ></td>
			<td style="border: 1px solid #dcdcdc; " ></td>
		</tr>

		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][RDCCI]">修理期间费用补偿险</label></span><input id="form[BUSINESS_ITEMS][RDCCI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="RDCCI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<?php echo $_smarty_tpl->tpl_vars['form']->value['RDCCI_INSURANCE_UNIT'];?>
 X <?php echo $_smarty_tpl->tpl_vars['form']->value['RDCCI_INSURANCE_QUANTITY'];?>
天 <?php echo $_smarty_tpl->tpl_vars['form']->value['RDCCI_INSURANCE_AMOUNT'];?>
</td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['RDCCI_PREMIUM'];?>
</td>

			<td style="border: 1px solid #dcdcdc; " ></td>
			<td style="border: 1px solid #dcdcdc; " ></td>
		</tr>

		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][MVLINFTPSI]">第三方特约险</label></span><input id="form[BUSINESS_ITEMS][MVLINFTPSI]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="MVLINFTPSI" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span></span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['MVLINFTPSI_PREMIUM'];?>
</td>
			<td style="border: 1px solid #dcdcdc; " ></td>
			<td style="border: 1px solid #dcdcdc; " ></td>
		</tr>

		<tr>
			<td style="padding-right: 5px; border: 1px solid #dcdcdc; " align="right" ><span class="FormCheck"><label for="form[BUSINESS_ITEMS][TTBLI_DOUBLE]">第三方节假日翻倍险</label></span><input id="form[BUSINESS_ITEMS][TTBLI_DOUBLE]" type="checkbox" name="form[BUSINESS_ITEMS][]" value="TTBLI_DOUBLE" /></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  >&nbsp;<span></span></td>
			<td style="border: 1px solid #dcdcdc; " align="left"  ></td>
			<td style="border: 1px solid #dcdcdc; " ><?php echo $_smarty_tpl->tpl_vars['form']->value['TTBLI_DOUBLE_PREMIUM'];?>
</td>
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
	pc.init("#policy_calculate_form","<?php echo $_smarty_tpl->tpl_vars['MODEL']->value;?>
",<?php echo $_smarty_tpl->tpl_vars['ALLOW_INSURANCES']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['SELECTED_INSURANCES']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['DESIGNATED_DRIVER']->value;?>
,"<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
");
	/***********算价日期联动************/
	
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

    
</script>


<?php }} ?>