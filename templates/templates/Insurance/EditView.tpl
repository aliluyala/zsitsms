<style type="text/css">
	td.client_detailview_value_4{
		padding-top: 0px;
		padding-bottom: 0px;
	}
	/*input[name="tcpli_insurance_passenger_amount"] {
		float: left;
	}
	label[for="passengers"] {
		border-style: none solid none solid;
		border-width: 1px;
		border-color: #DCDCDC;
		height: 25px;
		line-height: 25px;
		background-color: #EDEDED;
		text-align: center;
		padding-right: 5px;
		width: 40px;
		color: #5f5f5f;
		display:block;
		float:left;
		margin-right:5px;
		margin-left:5px;
	}
	input[name="passengers"] {
		clear: both;
	}*/
</style>
<form id="form_edit_view">
	<input type="hidden" id="editview_module_name" value="{$MODULE}"/>
	<input type="hidden" name="recordid" value="{$RECORDID}"/>
	<input type="hidden" name="new_recordid" value="{$NEW_RECORDID}"/>
	<input type="hidden" name="operation" value="{$OPERATION}"/>
	<input type="hidden" name="autoid" value="{$AUTOID.value}"/>
	<div class="insurance_container" style="font-size:12px">
		<table class="client_detailview_table" cellspacing="0">
		    <tr>
		        <td class="client_detailview_label_1">
		            <label for="{$CALCULATE_NO.name}" title="{$CALCULATE_NO.title}">{$CALCULATE_NO.label}</label>
		        </td>
		        <td class="client_detailview_value_1">{include file="UI/{$CALCULATE_NO.UI}.UI.tpl" FIELDINFO=$CALCULATE_NO}</td>
		    </tr>
		</table>
		<table class="client_detailview_table" cellspacing="0">
			<tr>
		        <td class="client_detailview_label_1" rowspan=6>
		        </td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="{$LAST_POLICY.name}" title="{$LAST_POLICY.title}">{$LAST_POLICY.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$LAST_POLICY.UI}.UI.tpl" FIELDINFO=$LAST_POLICY}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$PURCHASE_PRICE.name}" title="{$PURCHASE_PRICE.title}">{$PURCHASE_PRICE.label}</label>
		        </td>
		        <td class="client_detailview_value_4">
		        	{include file="UI/{$PURCHASE_PRICE.UI}.UI.tpl" FIELDINFO=$PURCHASE_PRICE}
					<button id="queryPurchasePrice" onclick="return false;">查询</button>
		        </td>
				<!-- <input type="text" name="{$PURCHASE_PRICE.name}" value="{$PURCHASE_PRICE.value}" ui = "{$PURCHASE_PRICE.UI}"
					mandatory = "{$PURCHASE_PRICE.mandatory}" label = "{$PURCHASE_PRICE.label}"  min_len="{$PURCHASE_PRICE.min_len}"  max_len="{$PURCHASE_PRICE.max_len}" style="width:50%" /> -->
		        <td class="client_detailview_label_4">
		            <label for="{$PLATE_NO.name}" title="{$PLATE_NO.title}">{$PLATE_NO.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$PLATE_NO.UI}.UI.tpl" FIELDINFO=$PLATE_NO}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$VEHICLE_TYPE.name}" title="{$VEHICLE_TYPE.title}">{$VEHICLE_TYPE.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$VEHICLE_TYPE.UI}.UI.tpl" FIELDINFO=$VEHICLE_TYPE}</td>
		    </tr>

		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="{$USE_CHARACTER.name}" title="{$USE_CHARACTER.title}">{$USE_CHARACTER.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$USE_CHARACTER.UI}.UI.tpl" FIELDINFO=$USE_CHARACTER}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$MODEL.name}" title="{$MODEL.title}">{$MODEL.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$MODEL.UI}.UI.tpl" FIELDINFO=$MODEL}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$VIN.name}" title="{$VIN.title}">{$VIN.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$VIN.UI}.UI.tpl" FIELDINFO=$VIN}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$ENGINE_NO.name}" title="{$ENGINE_NO.title}">{$ENGINE_NO.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$ENGINE_NO.UI}.UI.tpl" FIELDINFO=$ENGINE_NO}</td>
		    </tr>

		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="{$REGISTER_DATE.name}" title="{$REGISTER_DATE.title}">{$REGISTER_DATE.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$REGISTER_DATE.UI}.UI.tpl" FIELDINFO=$REGISTER_DATE}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$REGISTER_ADDRESS.name}" title="{$REGISTER_ADDRESS.title}">{$REGISTER_ADDRESS.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$REGISTER_ADDRESS.UI}.UI.tpl" FIELDINFO=$REGISTER_ADDRESS}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$SEATS.name}" title="{$SEATS.title}">{$SEATS.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$SEATS.UI}.UI.tpl" FIELDINFO=$SEATS}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$KERB_MASS.name}" title="{$KERB_MASS.title}">{$KERB_MASS.label}</label>
		        </td>
		        <td class="client_detailview_value_4 input-append">
		        	{include file="UI/{$KERB_MASS.UI}.UI.tpl" FIELDINFO=$KERB_MASS}
		        	<span class="unit">KG</span>
		        </td>
		    </tr>

		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="{$TOTAL_MASS.name}" title="{$TOTAL_MASS.title}">{$TOTAL_MASS.label}</label>
		        </td>
		        <td class="client_detailview_value_4 input-append">
		        	{include file="UI/{$TOTAL_MASS.UI}.UI.tpl" FIELDINFO=$TOTAL_MASS}
		        	<span class="unit">KG</span>
		        </td>
		        <td class="client_detailview_label_4">
		            <label for="{$RATIFY_LOAD.name}" title="{$RATIFY_LOAD.title}">{$RATIFY_LOAD.label}</label>
		        </td>
		        <td class="client_detailview_value_4 input-append">
		        	{include file="UI/{$RATIFY_LOAD.UI}.UI.tpl" FIELDINFO=$RATIFY_LOAD}
		        	<span class="unit">KG</span>
		        </td>
		        <td class="client_detailview_label_4">
		            <label for="{$TOW_MASS.name}" title="{$TOW_MASS.title}">{$TOW_MASS.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$TOW_MASS.UI}.UI.tpl" FIELDINFO=$TOW_MASS}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$ENGINE.name}" title="{$ENGINE.title}">{$ENGINE.label}</label>
		        </td>
		        <td class="client_detailview_value_4 input-append">
		        	{include file="UI/{$ENGINE.UI}.UI.tpl" FIELDINFO=$ENGINE}
		        	<span class="unit">ML</span>
		        </td>
		    </tr>

		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="{$POWER.name}" title="{$POWER.title}">{$POWER.label}</label>
		        </td>
		        <td class="client_detailview_value_4 input-append">
		        	{include file="UI/{$POWER.UI}.UI.tpl" FIELDINFO=$POWER}
		        	<span class="unit">KW</span>
		        </td>
		        <td class="client_detailview_label_4">
		            <label for="{$BODY_SIZE.name}" title="{$BODY_SIZE.title}">{$BODY_SIZE.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$BODY_SIZE.UI}.UI.tpl" FIELDINFO=$BODY_SIZE}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$BODY_COLOR.name}" title="{$BODY_COLOR.title}">{$BODY_COLOR.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$BODY_COLOR.UI}.UI.tpl" FIELDINFO=$BODY_COLOR}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$ORIGIN.name}" title="{$ORIGIN.title}">{$ORIGIN.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$ORIGIN.UI}.UI.tpl" FIELDINFO=$ORIGIN}</td>
		    </tr>
		</table>
		<table class="client_detailview_table" cellspacing="0">
			<tr>
		        <td class="client_detailview_label_1" rowspan=4>
		            <label for="" title="">系数因子</label>
		        </td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="{$DESIGNATED_DRIVER.name}" title="{$DESIGNATED_DRIVER.title}">{$DESIGNATED_DRIVER.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$DESIGNATED_DRIVER.UI}.UI.tpl" FIELDINFO=$DESIGNATED_DRIVER}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$DRIVING_YEARS.name}" title="{$DRIVING_YEARS.title}">{$DRIVING_YEARS.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$DRIVING_YEARS.UI}.UI.tpl" FIELDINFO=$DRIVING_YEARS}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$DRIVER_AGE.name}" title="{$DRIVER_AGE.title}">{$DRIVER_AGE.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$DRIVER_AGE.UI}.UI.tpl" FIELDINFO=$DRIVER_AGE}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$DRIVER_SEX.name}" title="{$DRIVER_SEX.title}">{$DRIVER_SEX.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$DRIVER_SEX.UI}.UI.tpl" FIELDINFO=$DRIVER_SEX}</td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="{$DRIVING_AREA.name}" title="{$DRIVING_AREA.title}">{$DRIVING_AREA.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$DRIVING_AREA.UI}.UI.tpl" FIELDINFO=$DRIVING_AREA}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$AVERAGE_ANNUAL_MILEAGE.name}" title="{$AVERAGE_ANNUAL_MILEAGE.title}">{$AVERAGE_ANNUAL_MILEAGE.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$AVERAGE_ANNUAL_MILEAGE.UI}.UI.tpl" FIELDINFO=$AVERAGE_ANNUAL_MILEAGE}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$CLAIM_RECORDS.name}" title="{$CLAIM_RECORDS.title}">{$CLAIM_RECORDS.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$CLAIM_RECORDS.UI}.UI.tpl" FIELDINFO=$CLAIM_RECORDS}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$YEARS_OF_INSURANCE.name}" title="{$YEARS_OF_INSURANCE.title}">{$YEARS_OF_INSURANCE.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$YEARS_OF_INSURANCE.UI}.UI.tpl" FIELDINFO=$YEARS_OF_INSURANCE}</td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="{$MULTIPLE_INSURANCE.name}" title="{$MULTIPLE_INSURANCE.title}">{$MULTIPLE_INSURANCE.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$MULTIPLE_INSURANCE.UI}.UI.tpl" FIELDINFO=$MULTIPLE_INSURANCE}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$DISCOUNT.name}" title="{$DISCOUNT.title}">{$DISCOUNT.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$DISCOUNT.UI}.UI.tpl" FIELDINFO=$DISCOUNT}</td>
		        <td class="client_detailview_label_4"></td>
		        <td class="client_detailview_value_4"></td>
		        <td class="client_detailview_label_4"></td>
		        <td class="client_detailview_value_4"></td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_1" rowspan=2>
		            <label for="" title="">保险信息</label>
		        </td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="{$COMPANY.name}" title="{$COMPANY.title}">{$COMPANY.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$COMPANY.UI}.UI.tpl" FIELDINFO=$COMPANY}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$MVTALCI_START_TIME.name}" title="{$MVTALCI_START_TIME.title}">{$MVTALCI_START_TIME.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$MVTALCI_START_TIME.UI}.UI.tpl" FIELDINFO=$MVTALCI_START_TIME}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$OTHER_START_TIME.name}" title="{$OTHER_START_TIME.title}">{$OTHER_START_TIME.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$OTHER_START_TIME.UI}.UI.tpl" FIELDINFO=$OTHER_START_TIME}</td>
		        <td class="client_detailview_label_4"></td>
		        <td class="client_detailview_value_4"></td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_1" rowspan=2>
		            <label for="" title="">保费总览</label>
		        </td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="{$MVTALCI_SUM.name}" title="{$MVTALCI_SUM.title}">{$MVTALCI_SUM.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$MVTALCI_SUM.UI}.UI.tpl" FIELDINFO=$MVTALCI_SUM}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$TRAVEL_TAX_SUM.name}" title="{$TRAVEL_TAX_SUM.title}">{$TRAVEL_TAX_SUM.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$TRAVEL_TAX_SUM.UI}.UI.tpl" FIELDINFO=$TRAVEL_TAX_SUM}</td>
		        <td class="client_detailview_label_4">
		            <label for="{$COMMERCIAL_SUM.name}" title="{$COMMERCIAL_SUM.title}">{$COMMERCIAL_SUM.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$COMMERCIAL_SUM.UI}.UI.tpl" FIELDINFO=$COMMERCIAL_SUM}</td>
		        <td class="client_detailview_label_4">
		           <label for="{$TOTAL_SUM.name}" title="{$TOTAL_SUM.title}">{$TOTAL_SUM.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$TOTAL_SUM.UI}.UI.tpl" FIELDINFO=$TOTAL_SUM}</td>
		    </tr>
		</table>
		<table class="client_detailview_table" cellspacing="0">
			<tr>
		        <td class="client_detailview_label_1" rowspan=3>
		            <label for="" title=""></label>
		        </td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="" title="">交强险</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="MVTALCI"></td>

				<td class="client_detailview_label_4">
		            <label for="{$FLOATING_RATE.name}" title="{$FLOATING_RATE.title}">{$FLOATING_RATE.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$FLOATING_RATE.UI}.UI.tpl" FIELDINFO=$FLOATING_RATE}</td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4">
		        	<label class="after-discount" for="" title=""></label>
		        </td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="" title="">车船税</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="TRAVEL_TAX"></td>
		        <td class="client_detailview_label_4"></td>
		        <td class="client_detailview_value_4"></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_1" rowspan=6>
		            <label for="" title="">基本险</label>
		        </td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="" title="">车辆损失险</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="TVDI"></td>
		        <td class="client_detailview_label_4">
		            <label for="{$TVDI_INSURANCE_AMOUNT.name}" title="{$TVDI_INSURANCE_AMOUNT.title}">{$TVDI_INSURANCE_AMOUNT.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$TVDI_INSURANCE_AMOUNT.UI}.UI.tpl" FIELDINFO=$TVDI_INSURANCE_AMOUNT}</td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="" title="">第三方责任险</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="TTBLI"></td>
		        <td class="client_detailview_label_4">
		            <label for="{$TTBLI_INSURANCE_AMOUNT.name}" title="{$TTBLI_INSURANCE_AMOUNT.title}">{$TTBLI_INSURANCE_AMOUNT.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$TTBLI_INSURANCE_AMOUNT.UI}.UI.tpl" FIELDINFO=$TTBLI_INSURANCE_AMOUNT}</td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="" title="">全车盗抢险</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="TWCDMVI"></td>
		        <td class="client_detailview_label_4">
		            <label for="{$TWCDMVI_INSURANCE_AMOUNT.name}" title="{$TWCDMVI_INSURANCE_AMOUNT.title}">{$TWCDMVI_INSURANCE_AMOUNT.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$TWCDMVI_INSURANCE_AMOUNT.UI}.UI.tpl" FIELDINFO=$TWCDMVI_INSURANCE_AMOUNT}</td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_4" rowspan=2>
		            <label for="" title="">车上人员责任险</label>
		        </td>
		        <td class="client_detailview_value_4" rowspan=2><input type="checkbox" name="buy_types[]" value="TCPLI"></td>
		        <td class="client_detailview_label_4">
		            <label for="{$TCPLI_INSURANCE_DRIVER_AMOUNT.name}" title="{$TCPLI_INSURANCE_DRIVER_AMOUNT.title}">{$TCPLI_INSURANCE_DRIVER_AMOUNT.label}</label>
		        </td>
		        <td class="client_detailview_value_4 input-append">
		        	{include file="UI/{$TCPLI_INSURANCE_DRIVER_AMOUNT.UI}.UI.tpl" FIELDINFO=$TCPLI_INSURANCE_DRIVER_AMOUNT}
		        	<span class="unit">万</span>
		        </td>
		        <td class="client_detailview_label_4" rowspan=2>
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4" rowspan=2><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4" rowspan=2>
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4" rowspan=2><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		        <td class="client_detailview_label_4">
		            <label for="{$TCPLI_INSURANCE_PASSENGER_AMOUNT.name}" title="{$TCPLI_INSURANCE_PASSENGER_AMOUNT.title}">{$TCPLI_INSURANCE_PASSENGER_AMOUNT.label}</label>
		        </td>
		        <td class="client_detailview_value_4 input-append">
		        	{include file="UI/3.UI.tpl" FIELDINFO=$TCPLI_INSURANCE_PASSENGER_AMOUNT}
		        	<!-- <input type="text" name="{$TCPLI_INSURANCE_PASSENGER_AMOUNT.name}" value="{$TCPLI_INSURANCE_PASSENGER_AMOUNT.value}" ui = "{$TCPLI_INSURANCE_PASSENGER_AMOUNT.UI}"
			mandatory = "{$TCPLI_INSURANCE_PASSENGER_AMOUNT.mandatory}" label = "{$TCPLI_INSURANCE_PASSENGER_AMOUNT.label}"  min_len="{$TCPLI_INSURANCE_PASSENGER_AMOUNT.min_len}"  max_len="{$TCPLI_INSURANCE_PASSENGER_AMOUNT.max_len}" style="width:28%" /> -->
			<span class="unit">万</span>
			<label class="add-on" for="{$PASSENGERS.name}" title="{$PASSENGERS.title}">{$PASSENGERS.label}</label>
			{include file="UI/3.UI.tpl" FIELDINFO=$PASSENGERS}
			<span class="unit">位</span>
		         <!-- <input type="text" name="{$PASSENGERS.name}" value="{$PASSENGERS.value}" ui = "{$PASSENGERS.UI}"
			mandatory = "{$PASSENGERS.mandatory}" label = "{$PASSENGERS.label}"  min_len="{$PASSENGERS.min_len}"  max_len="{$PASSENGERS.max_len}" style="width:28%" /> -->
		        </td>
		        <!-- <td class="client_detailview_label_4">
		            <label for="{$PASSENGERS.name}" title="{$PASSENGERS.title}">{$PASSENGERS.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/3.UI.tpl" FIELDINFO=$PASSENGERS}</td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label for="" title=""></label></td> -->
		    </tr>
			<tr>
		        <td class="client_detailview_label_1" rowspan=6>
		            <label for="" title="">不计免赔</label>
		        </td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">车损险<!-- 不计免赔 --></label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="TVDI_NDSI"></td>
		        <td class="client_detailview_label_4"></td>
		        <td class="client_detailview_value_4"></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">第三方责任险<!-- 不计免赔 --></label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="TTBLI_NDSI"></td>
		        <td class="client_detailview_label_4"></td>
		        <td class="client_detailview_value_4"></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">全车盗抢险<!-- 不计免赔 --></label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="TWCDMVI_NDSI"></td>
		        <td class="client_detailview_label_4"></td>
		        <td class="client_detailview_value_4"></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">车上人员责任险<!-- 不计免赔 --></label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="TCPLI_NDSI"></td>
		        <td class="client_detailview_label_4"></td>
		        <td class="client_detailview_value_4"></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">车身划痕险<!-- 不计免赔 --></label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="BSDI_NDSI"></td>
		        <td class="client_detailview_label_4"></td>
		        <td class="client_detailview_value_4"></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
			<tr>
		        <td class="client_detailview_label_1" rowspan=7>
		            <label for="" title="">附加险</label>
		        </td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">车身划痕险</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="BSDI"></td>
		        <td class="client_detailview_label_4">
		            <label for="{$BSDI_INSURANCE_AMOUNT.name}" title="{$BSDI_INSURANCE_AMOUNT.title}">{$BSDI_INSURANCE_AMOUNT.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$BSDI_INSURANCE_AMOUNT.UI}.UI.tpl" FIELDINFO=$BSDI_INSURANCE_AMOUNT}</td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">玻璃单独破碎险</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="BGAI"></td>
		        <td class="client_detailview_label_4">
		            <label for="{$GLASS_ORIGIN.name}" title="{$GLASS_ORIGIN.title}">{$GLASS_ORIGIN.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$GLASS_ORIGIN.UI}.UI.tpl" FIELDINFO=$GLASS_ORIGIN}</td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">车辆自燃损失险</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="SLOI"></td>
		        <td class="client_detailview_label_4">
		           <!--  <label for="{$TVILI_AMOUNT.name}" title="{$TVILI_AMOUNT.title}">{$TVILI_AMOUNT.label}</label> -->
		        </td>
		        <td class="client_detailview_value_4"><!-- {include file="UI/3.UI.tpl" FIELDINFO=$TVILI_AMOUNT} --></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">新增设备损失险</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="NIELI"></td>
		        <td class="client_detailview_label_4">
		            <label for="{$NIELI_INSURANCE_AMOUNT.name}" title="{$NIELI_INSURANCE_AMOUNT.title}">{$NIELI_INSURANCE_AMOUNT.label}</label>
		        </td>
		        <td class="client_detailview_value_4">{include file="UI/{$NIELI_INSURANCE_AMOUNT.UI}.UI.tpl" FIELDINFO=$NIELI_INSURANCE_AMOUNT}</td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">车辆涉水损失险</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="VWTLI"></td>
		        <td class="client_detailview_label_4"></td>
		        <td class="client_detailview_value_4"></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		    <tr>
		    	<td class="client_detailview_label_4">
		            <label for="" title="">指定专修厂</label>
		        </td>
		        <td class="client_detailview_value_4"><input type="checkbox" name="buy_types[]" value="STSFS"></td>
		        <td class="client_detailview_label_4">
		            <label for="{$STSFS_RATE.name}" title="{$STSFS_RATE.title}">{$STSFS_RATE.label}</label>
		        </td>
		        <td class="client_detailview_value_4 input-append">
		        	{include file="UI/{$STSFS_RATE.UI}.UI.tpl" FIELDINFO=$STSFS_RATE}
		        	<span class="unit">%</span>
		        </td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">小计</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="subtotal" for="" title=""></label></td>
		        <td class="client_detailview_label_4">
		            <label for="" title="">折后</label>
		        </td>
		        <td class="client_detailview_value_4"><label class="after-discount" for="" title=""></label></td>
		    </tr>
		</table>
	</div>
</form>
<div id="dialog_confirm_save" title="确认保存" >
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
  请确认保存记录，<br/>点击“确定”保存，点击“取消”忽略此操作。</p>
</div>
{if $AUTOID neq 0}
	<div class="CLIENT_JOBVIEW_BUTTONS">
		<button class="module_seting_button" operation="save">保存</button>
		<button class="module_seting_button" operation="cancel">取消</button>
		<button class="module_seting_button" operation="sendSms">发送短信</button>
	</div>
{/if}
<script type="text/javascript">
	zswitch_ui_form_init("#form_edit_view");
	var return_module = "{$RETURN_MODULE}";
	var return_action = "{$RETURN_ACTION}";
	var return_recordid = "{$RETURN_RECORDID}";
	var buy_types = "{$BUY_TYPES}";
	var accountid = "{$AUTOID.value}";

	{literal}
    $("#dialog_confirm_save").dialog({
     autoOpen: false,
     height: 200,
     width: 380,
     modal: true,
	 appendTo: "#main_view_client",
     dialogClass:"dialog_default_class",
     buttons: {
    "确定":function(){
    	var mod = $("#editview_module_name").val();
    	zswitch_show_progressbar($(this),"import_progress");
		var url = "index.php?module="+mod+"&action=save";
		$.post(url,$("#form_edit_view").serialize(),function(){
				$("#dialog_confirm_save").dialog( "close" );
		});
		/*var mod = $("#editview_module_name").val();
		//zswitch_load_client_view("index.php?module="+mod+"&action=save","form_edit_view");
		zswitch_ajax_load_client_view("index.php?module="+mod+"&action=save&return_module="+return_module+"&return_action="+return_action+"&return_recordid="+return_recordid,"form_edit_view");*/
    },
    "取消":function(){
    	$( this ).dialog( "close" );
       }
     }
    });

    Insurance.disabledSave(accountid);
	$(".module_seting_button").button().click(function(){
		var oper = $(this).attr("operation");
		var mod = $("#editview_module_name").val();
		if(oper == 'save')
		{
			var info = zswitchui_validity_check("#form_edit_view");
			if(info.length<=0)
			{
				$("#dialog_confirm_save").dialog("open");
			}
			else
			{
				info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
				zswitch_open_messagebox("editview_input_errorr","输入错误",info,400,400);
			}
		}
		else if(oper == 'cancel')
		{
			zswitch_load_client_view("index.php?module="+return_module+"&action="+return_action+"&recordid="+return_recordid);
			/*if($("#form_edit_view input[name=operation]").val()=="create")
			{
				zswitch_load_client_view("index.php?module="+return_module+"&action=index");
			}
			else
			{
				zswitch_load_client_view("index.php?module="+return_module+"&action="+return_action+"&recordid="+return_recordid);
			}*/
		}
		else if(oper == 'sendSms')
		{
			var mobile = $("div#detailview_blocks_accordion_LBL_ACCOUNT_BASE").find("div#mobile a:first").text();
            var plate_num = $("input[name='plate_no']").val();
			var content = "XXX先生/女士您好！我是人保金牛车险续保部XXX,今年给你爱车（"+ plate_num +"）保险：";
			var value = $("input[name='mvtalci_sum']").val();
			if(String(value).length>0)
			{
				content += "交强险"+value+"元;";
			}
			value = $("input[name='travel_tax_sum']").val();
			if(String(value).length>0)
			{
				content += "车船税"+value+"元;";
			}
			value = $("input[name='commercial_sum']").val();
			if(String(value).length>0)
			{
				content += "商业险合计"+Math.round(value)+"元;";
			}
			content += "其中:";
			//console.log($("input[name='buy_types[]'][value='TVDI']").val());
			if($("input[name='buy_types[]'][value='TVDI']").is(":checked"))
			{
				content += "车损" + $("input[name='tvdi_insurance_amount']").val() + "元/";
				content += Math.round($("input[name='tvdi_insurance_amount']").parent().next().next().next().next().find("label").text())+"元;";
			}
			if($("input[name='buy_types[]'][value='TTBLI']").is(":checked"))
			{
                //content += "三者：" + $("select[name='ttbli_insurance_amount']").val() + "元/";
				content += "三者" + $("select[name='ttbli_insurance_amount'] option:selected").text() + "/";
				content += Math.round($("select[name='ttbli_insurance_amount']").parent().next().next().next().next().find("label").text())+"元;";
			}
			if($("input[name='buy_types[]'][value='TWCDMVI']").is(":checked"))
			{
				content += "盗抢" + $("input[name='twcdmvi_insurance_amount']").val() + "元/";
				content += Math.round($("input[name='twcdmvi_insurance_amount']").parent().next().next().next().next().find("label").text())+"元;";
			}
			if($("input[name='buy_types[]'][value='TCPLI']").is(":checked"))
			{
				content += "座位:司机" + $("input[name='tcpli_insurance_driver_amount']").val() + "万+乘客";
				content += $("input[name='passengers']").val() + "*";
				content += $("input[name='tcpli_insurance_passenger_amount']").val() + "万/";
				content += Math.round($("input[name='tcpli_insurance_driver_amount']").parent().next().next().next().next().find("label").text())+"元;";
			}
			if($("input[name='buy_types[]'][value='BSDI']").is(":checked"))
			{
				content += "划痕" + $("select[name='bsdi_insurance_amount'] option:selected").text() + "/";
				content += Math.round($("select[name='bsdi_insurance_amount']").parent().next().next().next().next().find("label").text())+"元;";
			}
			if($("input[name='buy_types[]'][value='BGAI']").is(":checked"))
			{
				content += "玻璃";
				content += Math.round($("select[name='glass_origin']").parent().next().next().next().next().find("label").text())+"元;";
			}
			if($("input[name='buy_types[]'][value='SLOI']").is(":checked"))
			{
				content += "自燃";
				content += Math.round($("input[name='buy_types[]'][value='SLOI']").parent().next().next().next().next().next().next().find("label").text())+"元;";
			}
			if($("input[name='buy_types[]'][value='NIELI']").is(":checked"))
			{
				content += "新增设备险" + $("input[name='nieli_insurance_amount']").val() + "元/";
				content += Math.round($("input[name='nieli_insurance_amount']").parent().next().next().next().next().find("label").text())+"元;";
			}
			if($("input[name='buy_types[]'][value='VWTLI']").is(":checked"))
			{
				content += "涉水险";
				content += Math.round($("input[name='buy_types[]'][value='VWTLI']").parent().next().next().next().next().next().next().find("label").text())+"元;";
			}
			if($("input[name='buy_types[]'][value='STSFS']").is(":checked"))
			{
				content += "指定专修厂";
				content += Math.round($("input[name='buy_types[]'][value='STSFS']").parent().next().next().next().next().next().next().find("label").text())+"元;";
			}

			var ndsi = 0;
			var nval = 0;
			if($("input[name='buy_types[]'][value='TVDI_NDSI']").is(":checked"))
			{
				nval = $("input[name='buy_types[]'][value='TVDI_NDSI']").parent().next().next().next().next().next().next().find("label").text();
				ndsi += Number(nval);
			}
			if($("input[name='buy_types[]'][value='TTBLI_NDSI']").is(":checked"))
			{
				nval = $("input[name='buy_types[]'][value='TTBLI_NDSI']").parent().next().next().next().next().next().next().find("label").text();
				ndsi += Number(nval);
			}
			if($("input[name='buy_types[]'][value='TWCDMVI_NDSI']").is(":checked"))
			{
				nval = $("input[name='buy_types[]'][value='TWCDMVI_NDSI']").parent().next().next().next().next().next().next().find("label").text();
				ndsi += Number(nval);
			}
			if($("input[name='buy_types[]'][value='TCPLI_NDSI']").is(":checked"))
			{
				nval = $("input[name='buy_types[]'][value='TCPLI_NDSI']").parent().next().next().next().next().next().next().find("label").text();
				ndsi += Number(nval);
			}
			if($("input[name='buy_types[]'][value='BSDI_NDSI']").is(":checked"))
			{
				nval = $("input[name='buy_types[]'][value='BSDI_NDSI']").parent().next().next().next().next().next().next().find("label").text();
				ndsi += Number(nval);
			}
			//content += "不计免赔:" + ndsi.toFixed(3) + "元。";
			content += "不计免赔" + ndsi.toFixed(0) + "元。";
			content += "地址:成都市金牛区一环路北四段168号. 请以出单为准，详询请致电：xxxxxxxxxxx，车险售后热线:189808756234";
			zswitch_open_sendsms_dlg(mobile,content);


		}
	});


	$("input[name='purchase_price']").keyup(function(){
		$("input[name='tvdi_insurance_amount']").val($(this).val());
	});
	$("input[name='tvdi_insurance_amount']").keyup(function(){
		$("input[name='purchase_price']").val($(this).val());
	});
	$("input[name='seats']").keyup(function(){
		$("input[name='passengers']").val(Number($(this).val()) - 1);
	});

	$("input[name='buy_types[]']").each(function(){
		$(this).parent().nextAll().children("input,select").attr("disabled","disabled");
		 if($(this).parent().attr("rowspan") == 2)
	            $(this).parent().parent().next().children().children("input,select").attr("disabled","disabled");
	});
	if(buy_types)
		initCalc(buy_types);
	function initCalc(buy_types){
		var types = new Array();
		types = buy_types.split(",");
		$.each(types,function(index,val){
			$("input[value='"+val+"']").attr("checked",true);
			allowEdit("input[value='"+val+"']");
		});
		run();
	}
	//险种选择
    $("input[name='buy_types[]']").change(function(){
        if($(this).is(":checked")){
            allowEdit(this);
            run();
        }else{
            run();
            denyEdit(this);
        }
    });
	$("form#form_edit_view input[type!='checkbox'],form#form_edit_view select").change(function(){
		run();
	});
    //编辑:允许
	function allowEdit(obj){
	    $(obj).parent().nextAll().children("input,select").removeAttr("disabled");
	        if($(obj).parent().attr("rowspan") == 2)
	            $(obj).parent().parent().next().children().children("input,select").removeAttr("disabled");
	}
	//编辑:拒绝
	function denyEdit(obj){
		clearData(obj);
	    $(obj).parent().nextAll().children("input,select").attr("disabled","disabled");
	        if($(obj).parent().attr("rowspan") == 2)
	            $(obj).parent().parent().next().children().children("input,select").attr("disabled","disabled");
	}
	//清除算价内容
	function clearData(obj){
		$(obj).parent().next().next().next().next().children("label").text("");
		$(obj).parent().next().next().next().next().next().next().children("label").text("");
	}
	function run(){
		var mod = $("#editview_module_name").val();
		var url = "index.php?module="+mod+"&action=calc&recordid="+return_recordid;
        //zswitch_load_client_view(url,"form_edit_view");
        zswitch_ajax_request(url,"form_edit_view",function(type,data){
        	$("input[name='twcdmvi_insurance_amount']").val(data.DEPRECIATION_PRICE);
        	$("input[name='discount']").val(data.DISCOUNT);

        	$("input[name='commercial_sum']").val(data.COMMERCIAL_SUM);
        	$("input[name='total_sum']").val(data.TOTAL_SUM);
        	//$("input[name='net_sales']").val(data.NET_SALES);
        	$("input[name='mvtalci_sum']").val(data.MVTALCI_SUM ? Number(data.MVTALCI_SUM) : 0);
        	$("input[name='travel_tax_sum']").val(data.TRAVEL_TAX_SUM);
        	for(var key in data.BEFORE){
        		data.BEFORE[key] != false ?
        			$("input[value='" + key + "']").parent().next().next().next().next().children("label").text(data.BEFORE[key])
        		:
        			$("input[value='" + key + "']").parent().next().next().next().next().children("label").text(0);
        	}
        	for(var key in data.AFTER){
        		data.AFTER[key] != false ?
        			$("input[value='" + key + "']").parent().next().next().next().next().next().next().children("label").text(data.AFTER[key])
        		:
        			$("input[value='" + key + "']").parent().next().next().next().next().next().next().children("label").text(0);
        	}
        });
	}
	$("#queryPurchasePrice").button().click(function(){queryPurchasePrice();});
	function queryPurchasePrice(){
        if(!$('#main_view_client').children().is('#query_purchaseprice_dlg'))
	    {
	        var html = '<div id="query_purchaseprice_dlg"></div>';
	        $('#main_view_client').append(html);
	    }

	    $('#query_purchaseprice_dlg').data('model',$("input[name='model']").val());
	    $('#query_purchaseprice_dlg').dialog({
	        autoOpen:true,
	        height:500,
	        width:800,
	        modal:true,
	        title:"新车购置价查询",
	        appendTo: "#main_view_client",
	        dialogClass:"dialog_default_class",
	        open:function(){
	            zswitch_show_progressbar($(this),"import_progress");
	            var url = encodeURI("index.php?module=Insurance&action=queryPurchasePrice&model=" + $('#query_purchaseprice_dlg').data('model'));
	            $(this).load(url);
	        },
	        beforeClose:function(){
	            $(this).dialog("destroy");
	            $(this).remove();
	        },
	        buttons: {
	            "确定":function(){
	            	if($("input[name='buyprice']:checked").val()){
	            		var vehicleinfo = $("input[name='buyprice']:checked").val().split(",");
	            		$("input[name='purchase_price']").val(vehicleinfo[0]);
	            		$("input[name='tvdi_insurance_amount']").val(vehicleinfo[0]);
	            		$("input[name='engine']").val(Number(vehicleinfo[1]) * 1000);
	            		$("input[name='seats']").val(Number(vehicleinfo[2]));
	            		$("input[name='passengers']").val(Number(vehicleinfo[2]) - 1);
	            		run();
	            	}
	                $( this ).dialog( "close" );
	            },
	            "取消":function(){
	                $( this ).dialog( "close" );
	            }
	        }
	    });
    }
	{/literal}
</script>