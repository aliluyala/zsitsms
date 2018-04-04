Array.prototype.in_array = function(search) {
    for(i=0; i<this.length; i++) {
        if(this[i]==search) {
            return true;
        }
    }
    return false;
}

function jq_loading()
{
		var docHeight = $(document).height(); //获取窗口高度
		$('body').append('<div id="overlay"></div>');
		$('#overlay').height(docHeight).css({
		   'opacity': .10, //透明度
		   'position': 'absolute',
		   'top': 0,
		   'left': 0,
		   'background-color': '#000000',
		   'width': '100%',
		   'z-index': 5000 //保证这个悬浮层位于其它内容之上
		});
}

function jq_clearloading()
{
	$("#overlay").remove();
}

function policy_calculate(){

	this.form = null;
	this.model = null;
	this.params = [
		'form[OTHER][PREMIUM_RATE_TABLE]',
		'form[AUTO][ENGINE]',
		'form[AUTO][SEATS]',
		'form[AUTO][KERB_MASS]',
		'form[AUTO][BUYING_PRICE]',
		'form[AUTO][VEHICLE_TYPE]',
		'form[AUTO][USE_CHARACTER]',
		'form[POLICY][FLOATING_RATE]',
		'form[POLICY][MVTALCI_START_TIME]',
		'form[POLICY][MVTALCI_END_TIME]',
		'form[POLICY][BUSINESS_START_TIME]',
		'form[POLICY][BUSINESS_END_TIME]',
		'form[POLICY][DRIVER_SEX1]',
		'form[POLICY][DRIVER_AGE1]',
		'form[POLICY][DRIVING_YEARS1]',
		'form[POLICY][DRIVER_SEX2]',
		'form[POLICY][DRIVER_AGE2]',
		'form[POLICY][DRIVING_YEARS2]',
		'form[POLICY][DRIVER_SEX3]',
		'form[POLICY][DRIVER_AGE3]',
		'form[POLICY][DRIVING_YEARS3]',
		'form[POLICY][YEARS_OF_INSURANCE]',
		'form[POLICY][CLAIM_RECORDS]',
		'form[POLICY][DRIVING_AREA]',
		'form[POLICY][AVERAGE_ANNUAL_MILEAGE]',
		'form[POLICY][MULTIPLE_INSURANCE]',
		'form[POLICY][TVDI_INSURANCE_AMOUNT]',
		'form[POLICY][DOC_AMOUNT]',
		'form[POLICY][TTBLI_INSURANCE_AMOUNT]',
		'form[POLICY][TWCDMVI_INSURANCE_AMOUNT]',
		'form[POLICY][TCPLI_INSURANCE_DRIVER_AMOUNT]',
		'form[POLICY][TCPLI_INSURANCE_PASSENGER_AMOUNT]',
		'form[POLICY][TCPLI_PASSENGER_COUNT]',
		'form[POLICY][BSDI_INSURANCE_AMOUNT]',
		'form[POLICY][SLOI_INSURANCE_AMOUNT]',
		'form[POLICY][GLASS_ORIGIN]',
		'form[POLICY][NIELI_INSURANCE_AMOUNT]',
		'form[POLICY][STSFS_RATE]',
		'form[POLICY][VWTLI_INSURANCE_AMOUNT]',
		'form[POLICY][CUSTOM1_INSURANCE_AMOUNT]',
		'form[POLICY][CUSTOM2_INSURANCE_AMOUNT]',
		'form[POLICY][RDCCI_INSURANCE_AMOUNT]'
	];

	$("#policy_calculate_checkcode_dlg").dialog({
		title:"转保查询",
		autoOpen:false,
		height:410,
		width:570,
		position: { my: "center top", at: "center top", of: window },
		modal:true,
		appendTo: "body",
		dialogClass:"dialog_default_class",
		buttons:{
			'确定':function(){

				if($("#dza_demandNo").val() != "")
				{
					$("[name='form[OTHER][DZA_DEMANDNOS]'").val($("#dza_demandNo").val());
				}
				if($("#dza_checkcode").val() != "")
				{
					$("[name='form[OTHER][DZA_CHECKCODES]'").val($("#dza_checkcode").val());
				}
				if($("#daa_demandNo").val() != "")
				{
					$("[name='form[OTHER][DAA_DEMANDNOS]'").val($("#daa_demandNo").val());
				}
				if($("#daa_checkcode").val() != "")
				{
					$("[name='form[OTHER][DAA_CHECKCODES]'").val($("#daa_checkcode").val());
				}
				premium_calculate('PolicyCalculateCom',event.timeStamp);
				$("#policy_calculate_checkcode_dlg").dialog("close");
				$("[name='form[OTHER][DZA_DEMANDNOS]'").val("");
				$("[name='form[OTHER][DZA_CHECKCODES]'").val("");
				$("[name='form[OTHER][DAA_DEMANDNOS]'").val("");
				$("[name='form[OTHER][DAA_CHECKCODES]'").val("");
			}
		},
		open:function(){

			var checkcode = $("#policy_calculate_checkcode_dlg").data("CheckCode");
			var url = "index.php?module=PolicyCalculateCom&action=protectCheckcode";
			$(this).load(url,{'checkcode':checkcode,'status':1});
		}
	});

	$("#quickinsure").click(function(){

			var vin_no = $("#policy_calculate_form").find("[name='form[AUTO][VIN_NO]']").val();
			var license_no = $("#policy_calculate_form").find("[name='form[AUTO][LICENSE_NO]']").val();
			var url = "index.php?module=" + pc_module + "&action=quickinsure";
			jq_loading();
			zswitch_ajax_request(url,"policy_calculate_form",function(type,data){
				jq_clearloading();
				if(type == 'error')
				{
					$("#policy_calculate_save_title")
					.data("MsgContent",data).dialog("open")
					.dialog( "option", "position", { my: "center top", at: "center top", of: $(this) } );
				}
				else if(type == 'protectCheckcode')
				{
					$("#policy_calculate_checkcode_dlg").data("CheckCode",data).dialog("open");
				}
				else if(type == 'success')
				{

					for(idx in data.MVTALCI)
					{
						$("[name='form[POLICY]["+idx+"]'").val(data.MVTALCI[idx]);
					}
					for(idx in data.BUSINESS)
					{
						if(idx == 'BUSINESS_ITEMS')
						{
							for(iname in data.BUSINESS.BUSINESS_ITEMS)
							{
								for(pname in data.BUSINESS.BUSINESS_ITEMS[iname])
								{
									$("[name='form[POLICY]["+iname+"_"+pname+"]'").val(data.BUSINESS.BUSINESS_ITEMS[iname][pname]);
								}

							}
						}
						else if(idx == 'INSURANCE_COMPANY')
						{
							$("[name='form[OTHER]["+idx+"]'").val(data.BUSINESS[idx]);
						}
						else
						{
							$("[name='form[POLICY]["+idx+"]'").val(data.BUSINESS[idx]);
						}

					}
					$("[name='form[POLICY][TOTAL_MVTALCI_PREMIUM]']").val($("[name='form[POLICY][MVTALCI_PREMIUM]']").val());
					$("[name='form[POLICY][TOTAL_TRAVEL_TAX_PREMIUM]']").val($("[name='form[POLICY][TRAVEL_TAX_PREMIUM]']").val());

					var cdisc = parseFloat($("[name='form[POLICY][BUSINESS_CUSTOM_DISCOUNT]']").val());
					if(isNaN(cdisc))
					{
						$("[name='form[POLICY][BUSINESS_CUSTOM_DISCOUNT]']").val('1.0000');
						cdisc = 1;
					}


					var total = parseFloat($("[name='form[POLICY][MVTALCI_PREMIUM]']").val());
					total += parseFloat($("[name='form[POLICY][TRAVEL_TAX_PREMIUM]']").val());
					var discprem = cdisc*parseFloat($("[name='form[POLICY][BUSINESS_PREMIUM]']").val());
					$("[name='form[POLICY][BUSINESS_DISCOUNT_PREMIUM]']").val(discprem.toFixed(2));
					$("[name='form[POLICY][TOTAL_BUSINESS_PREMIUM]']").val(discprem.toFixed(2));
					total += discprem;
					$("[name='form[POLICY][TOTAL_STANDARD_PREMIUM]']").val(total.toFixed(2));
					$("[name='form[POLICY][TOTAL_PREMIUM]']").val(total.toFixed(2));

					if(data.MESSAGE && data.MESSAGE.length>0 )
					{
						$("#policy_calculate_save_title")
						.data("MsgContent",data.MESSAGE).dialog("open")
						.dialog( "option", "position", { my: "center top", at: "center top", of: $(this) } );
					}

				}

			});
	});




	function premium_calculate(module,timeStamp){

		if(timeStamp - this.lastTimeStamp > 100 && !$("#policy_calculate_start_btn").data("calculating"))
		{
			$("#policy_calculate_start_btn").find("span").hide();
			$("#policy_calculate_start_btn").data("calculating",true).find("img").show();
			jq_loading();
			var url = "index.php?module="+module+"&action=calculate";
			zswitch_ajax_request(url,"policy_calculate_form",function(type,data){
				jq_clearloading();
				if(type == 'protectCheckcode')
				{
					$("#policy_calculate_checkcode_dlg").data("CheckCode",data).dialog("open");

				}
				if(type == 'error')
				{
					$("#policy_calculate_save_title")
					.data("MsgContent",data).dialog("open")
					.dialog( "option", "position", { my: "center top", at: "center top", of: $(this) } );
				}
				else if(type == 'success')
				{

					for(idx in data.MVTALCI)
					{
						$("[name='form[POLICY]["+idx+"]'").val(data.MVTALCI[idx]);
					}
					for(idx in data.BUSINESS)
					{
						if(idx == 'BUSINESS_ITEMS')
						{
							for(iname in data.BUSINESS.BUSINESS_ITEMS)
							{
								for(pname in data.BUSINESS.BUSINESS_ITEMS[iname])
								{
									$("[name='form[POLICY]["+iname+"_"+pname+"]'").val(data.BUSINESS.BUSINESS_ITEMS[iname][pname]);
								}

							}
						}
						else if(idx == 'INSURANCE_COMPANY')
						{
							$("[name='form[OTHER]["+idx+"]'").val(data.BUSINESS[idx]);
						}
						else
						{
							$("[name='form[POLICY]["+idx+"]'").val(data.BUSINESS[idx]);
						}

					}
					$("[name='form[POLICY][TOTAL_MVTALCI_PREMIUM]']").val($("[name='form[POLICY][MVTALCI_PREMIUM]']").val());
					$("[name='form[POLICY][TOTAL_TRAVEL_TAX_PREMIUM]']").val($("[name='form[POLICY][TRAVEL_TAX_PREMIUM]']").val());

					var cdisc = parseFloat($("[name='form[POLICY][BUSINESS_CUSTOM_DISCOUNT]']").val());
					if(isNaN(cdisc))
					{
						$("[name='form[POLICY][BUSINESS_CUSTOM_DISCOUNT]']").val('1.0000');
						cdisc = 1;
					}


					var total = parseFloat($("[name='form[POLICY][MVTALCI_PREMIUM]']").val());
					total += parseFloat($("[name='form[POLICY][TRAVEL_TAX_PREMIUM]']").val());
					var discprem = cdisc*parseFloat($("[name='form[POLICY][BUSINESS_PREMIUM]']").val());
					$("[name='form[POLICY][BUSINESS_DISCOUNT_PREMIUM]']").val(discprem.toFixed(2));
					$("[name='form[POLICY][TOTAL_BUSINESS_PREMIUM]']").val(discprem.toFixed(2));
					total += discprem;
					$("[name='form[POLICY][TOTAL_STANDARD_PREMIUM]']").val(total.toFixed(2));
					$("[name='form[POLICY][TOTAL_PREMIUM]']").val(total.toFixed(2));

					if(data.MESSAGE && data.MESSAGE.length>0 )
					{
						$("#policy_calculate_save_title")
						.data("MsgContent",data.MESSAGE).dialog("open")
						.dialog( "option", "position", { my: "center top", at: "center top", of: $(this) } );
					}

				}
				$("#policy_calculate_start_btn").data("calculating",false).find("img").hide();

				$("#policy_calculate_start_btn").find("span").show();

			});

		}
		this.lastTimeStamp = timeStamp;
	}

	function clearResult()
	{

		if($("#policy_calculate_form").data("is_opened") == true)
		{
			$("[name$='_PREMIUM]']").val("0.00");
			$("[name$='MVTALCI_DISCOUNT]'],[name$='BUSINESS_DISCOUNT]']").val('1.0000');
		}

	}

	this.init = function(form_selecter,model,allow_insurances,selected_insurances,designated_driver,module){
		this.form = $(form_selecter);
		this.model = model;
		this.module = module;

		if(allow_insurances.in_array("MVTALCI")){
			this.form.find("[id='form[MVTALCI_SELECT]']").click(function(){
				$("[name='form[POLICY][MVTALCI_START_TIME]']").prop('disabled',!$(this).prop("checked"));
				$("[name='form[POLICY][MVTALCI_END_TIME]']").prop('disabled',!$(this).prop("checked"));
				$(this).parent().parent().parent().find("select,input").not("[id='form[MVTALCI_SELECT]']").prop('disabled',!$(this).prop("checked"));
			}).prop("checked",!selected_insurances.in_array("MVTALCI")).click();

		}else{
			this.form.find("[id='form[MVTALCI_SELECT]']").parent().parent().parent().css("color","#AAAAAA")
			.find("input,select").prop("disabled",true);
		}

		this.form.find("[id^='form[BUSINESS_ITEMS][']").not("[id$='_NDSI]']").each(function(){
			var val = $(this).val();
			if(allow_insurances.in_array(val)){
				$(this).click(function(){

					$(this).parent().nextAll().find("input,select,button").prop("disabled",!$(this).prop("checked"));
					$("[id='form[BUSINESS_ITEMS]["+$(this).val()+"_NDSI]']").prop("checked",$(this).prop("checked"));

				});
				$("[id='form[BUSINESS_ITEMS]["+val+"_NDSI]']").click(function(){
					$(this).parent().next().find("input,select").prop("disabled",!$(this).prop("checked"));
				});
				if(selected_insurances.in_array(val)){
					$(this).prop("checked",false).click();
					$("[id='form[BUSINESS_ITEMS]["+val+"_NDSI]']").prop("checked",!selected_insurances.in_array(val+"_NDSI")).click();
				}else{
					$(this).prop("checked",true).click();
				}

			}else{
				$(this).parent().css("color","#AAAAAA").find("input,select").prop("disabled",true);
				$(this).parent().nextAll().css("color","#AAAAAA").find("input,select").prop("disabled",true);
			}
		});

		this.form.find("[id^='form[DESIGNATED_DRIVER']").click(function(){
			$(this).parent().nextAll().find("input,select").prop("disabled",!$(this).prop("checked"));
		}).each(function(){
			var id = $(this).attr("id") ;
			var m = id.match(/(\d)/);
			$(this).prop("checked",!designated_driver.in_array(m[1])).click();
		});

		if(this.model == "manual"){
			this.form.find("[name^='form[POLICY][TOTAL_'],[name='form[POLICY][BUSINESS_PREMIUM]'],[name='form[POLICY][BUSINESS_STANDARD_PREMIUM]']").prop('readonly',true);


		}else{
			this.form.find("[name$='_PREMIUM]'],[name$='_DISCOUNT]']").prop('readonly',true);
			this.form.find("[name$='BUSINESS_CUSTOM_DISCOUNT]']").prop('readonly',false).change(function(){
					var cdisc = parseFloat($("[name='form[POLICY][BUSINESS_CUSTOM_DISCOUNT]']").val());
					if(isNaN(cdisc))
					{
						$("[name='form[POLICY][BUSINESS_CUSTOM_DISCOUNT]']").val('1.0000');
						cdisc = 1;
					}


					var total = parseFloat($("[name='form[POLICY][MVTALCI_PREMIUM]']").val());
					total += parseFloat($("[name='form[POLICY][TRAVEL_TAX_PREMIUM]']").val());
					var discprem = cdisc*parseFloat($("[name='form[POLICY][BUSINESS_PREMIUM]']").val());
					$("[name='form[POLICY][BUSINESS_DISCOUNT_PREMIUM]']").val(discprem.toFixed(2));
					$("[name='form[POLICY][TOTAL_BUSINESS_PREMIUM]']").val(discprem.toFixed(2));
					total += discprem;
					$("[name='form[POLICY][TOTAL_STANDARD_PREMIUM]']").val(total.toFixed(2));
					$("[name='form[POLICY][TOTAL_PREMIUM]']").val(total.toFixed(2));

			});
			this.form.find(":checkbox").data("module",this.module).click(function(event){
				//premium_calculate($(this).data("module"),event.timeStamp);
				clearResult();
			});

			$("[name='form[AUTO][MODEL]'],[name='form[AUTO][ENROLL_DATE]']").change(function(){
				$("[name='form[AUTO][BUYING_PRICE]']").val(0.00);
				clearResult();
			});

			$("[name='form[POLICY][RDCCI_INSURANCE_AMOUNT]']").prop('readonly',true);
			$("[name='form[POLICY][RDCCI_INSURANCE_UNIT]'],[name='form[POLICY][RDCCI_INSURANCE_QUANTITY]']").change(function(){
				var unit = parseInt($("[name='form[POLICY][RDCCI_INSURANCE_UNIT]']").val());
				var quant = parseInt($("[name='form[POLICY][RDCCI_INSURANCE_QUANTITY]']").val());
				var amount = unit*quant;

				if(isNaN(amount)) amount = 0;
				$("[name='form[POLICY][RDCCI_INSURANCE_AMOUNT]']").val(amount).change();

			});

			for(idx in this.params){
				$("[name='"+this.params[idx]+"']").data("module",this.module).change(function(event){
					//premium_calculate($(this).data("module"),event.timeStamp);
					clearResult();
				});
			}
		}
		zswitch_ui_form_init(form_selecter);




		$("#policy_calculate_queryBuyingPrice_dlg").data("module",this.module).dialog({
			title:"车型查询",
			autoOpen:false,
			height:410,
			width:570,
			position: { my: "center top", at: "center top", of: window },
			modal:true,
			appendTo: "body",
			dialogClass:"dialog_default_class",
			open:function(){
				var model = $("#policy_calculate_form").find("[name='form[AUTO][MODEL]']").val();
				var vin = $("#policy_calculate_form").find("[name='form[AUTO][VIN_NO]']").val();
				var rate_table = $("#policy_calculate_form").find("[name='form[OTHER][PREMIUM_RATE_TABLE]']").val();
				var license_no = $("#policy_calculate_form").find("[name='form[AUTO][LICENSE_NO]']").val();
				var engine_no = $("#policy_calculate_form").find("[name='form[AUTO][ENGINE_NO]']").val();
				var enroll_date = $("#policy_calculate_form").find("[name='form[AUTO][ENROLL_DATE]']").val();
				var modelcode = $("#policy_calculate_form").find("[name='form[AUTO][MODEL_CODE]']").val();
				var url  = "index.php?module="+$("#policy_calculate_queryBuyingPrice_dlg").data("module");
				    url += "&action=queryBuyingPrice&model="+encodeURIComponent(model)+"&rate_table="+rate_table;
				    url += "&modelcode="+modelcode;
					url += "&vin_no="+vin;
					url += "&license_no="+license_no;
					url += "&engine_no="+engine_no;
					url += "&enroll_date="+enroll_date;
					$(this).load(url);
			},
		});

		$("#policy_calculate_queryBuyingPrice_btn").click(function(){

			var vintype = $("#policy_calculate_form").find("[name='vintype']").val();
			var rate_table = $("#policy_calculate_form").find("[name='form[OTHER][PREMIUM_RATE_TABLE]']").val();
			var model = $("#policy_calculate_form").find("[name='form[AUTO][MODEL]']").val();
			var vin = $("#policy_calculate_form").find("[name='form[AUTO][VIN_NO]']").val();
			if(vintype == 0)
			{
				var url  = "index.php?module="+$("#policy_calculate_queryBuyingPrice_dlg").data("module");
				    url += "&action=vehicleInfoFetch&model="+encodeURIComponent(model)+"&rate_table="+rate_table;
					url += "&vin_no="+vin;
					url += "&vintype="+vintype;
					$.get(url,function(data){

						var data = $.parseJSON(data);
						if(data.type == "success" && data.data != null)
						{
							$("#policy_calculate_form").find("[name='form[AUTO][BUYING_PRICE]']").val(parseFloat(data.data.buying_price).toFixed(2));
							$("#policy_calculate_form").find("[name='form[AUTO][MODEL_CODE]']").val(data.data.model_code);
							$("#policy_calculate_form").find("[name='form[AUTO][MODEL]']").val(data.data.model);
				            $("#policy_calculate_form").find("[name='form[AUTO][ENGINE]']").val(data.data.engine);
				            $("#policy_calculate_form").find("[name='form[AUTO][KERB_MASS]']").val(data.data.kerb_mass);
							$("#policy_calculate_form").find("[name='form[AUTO][SEATS]']").val(data.data.seats);
							$("#policy_calculate_form").find("[name='form[HOLDER][HOLDER_IDENTIFY_NO]']").val(data.data.identify_no);
							$("#policy_calculate_form").find("[name='form[AUTO][OWNER]']").val(data.data.owner);
							$("#policy_calculate_form").find("[name='form[AUTO][MOBILE]']").val(data.data.mobile);
							$("#policy_calculate_form").find("[name='form[AUTO][LICENSE_NO]']").val(data.data.license_no);
							$("#policy_calculate_form").find("[name='form[AUTO][VIN_NO]']").val(data.data.vin_no);
							$("#policy_calculate_form").find("[name='form[AUTO][ENGINE_NO]']").val(data.data.engine_no);
							$("#policy_calculate_form").find("[name='form[AUTO][ENROLL_DATE]']").val(data.data.enroll_date);
							$("#policy_calculate_form").find("[name='vintype']").val(1);
							var url = "index.php?module="+$("#policy_calculate_queryBuyingPrice_dlg").data("module")+"&action=depreciation";
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
							return;
						}
						$("#policy_calculate_form").find("[name='vintype']").val(1);
						$("#policy_calculate_queryBuyingPrice_dlg").dialog("open");
					});
					return;
			}
			$("#policy_calculate_queryBuyingPrice_dlg").dialog("open");
		});


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


	$(".module_seting_button").button().data("module",this.module).click(function(){
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
			var url = "index.php?module="+$(this).data("module")+"&action=sendPolicySMS";
			zswitch_ajax_request(url,"policy_calculate_form",function(type,data){

				if(type == "success")
				{
					zswitch_open_sendsms_dlg(data.callee,decodeURIComponent(data.content),data.caculate_data,data.short_sms_before,data.short_sms_after);
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
	        $("button[operation='submitAuditing']").attr('disabled', 'disabled');
	        $("button[operation='submitAuditing']").addClass('ui-state-disabled');
			$("#policy_calculate_submitAuditing_confirm")
			.dialog( "option", "position", { my: "center bottom", at: "center top", of: $(this) } )
			.dialog("open");
		}
		else if(oper == 'saveas')
		{
			var oper = $("#policy_calculate_form").find("[name='operation']").val();
			if(oper == 'edit')
			{
				var url = "index.php?module="+$(this).data("module")+"&action=saveAs";
				$.get(url,function(data){
					if(data.type == 'success')
					{
						$("#policy_calculate_form").find("[name='operation']").val('create');
						$("#policy_calculate_form").find("[name='form[NO]']").val(data.data);
						$("button[operation='save']").click();
					}
				},'json');
			}

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
			//position: { my: "center top", at: "center top", of: window },
			buttons:{
				'确定':function(){
					$(this).dialog("close");
				}
			},
			open:function(){
				var html =  '<p style="font-size:12px;"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
				html = html + $(this).data('MsgContent') + "</p>";
				$(this).html(html);
			}
		});

	$("#policy_calculate_save_confirm").data("module",this.module).dialog({
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
					var url = "index.php?module="+$("#policy_calculate_save_confirm").data("module")+"&action=save";
					zswitch_ajax_request(url,"policy_calculate_form",function(type,data){
						if(type == "success")
						{
							$("#policy_calculate_save_title").data('MsgContent','算价记录保存成功！')
							.dialog( "option", "height", 150 )
							.data("recordid",data)
							.dialog('open');
							$("[name='operation']").val('edit');
							$("[name='recordid']").val(data);
							if(typeof(loadCalculateList)=='function')
							{
								loadCalculateList();
							}
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

		$("#policy_calculate_submitAuditing_confirm").data("module",this.module).dialog({
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
					var url = "index.php?module="+$("#policy_calculate_submitAuditing_confirm").data("module")+"&action=submitAuditing";
					zswitch_ajax_request(url,"policy_calculate_form",function(type,data){
				        $("button[operation='submitAuditing']").removeAttr('disabled');
				        $("button[operation='submitAuditing']").removeClass('ui-state-disabled');
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
			        $("button[operation='submitAuditing']").removeAttr('disabled');
			        $("button[operation='submitAuditing']").removeClass('ui-state-disabled');
					$(this).dialog("close");
				}
			},
			open:function(){
				var url = "index.php?module="+$("#policy_calculate_save_confirm").data("module")+"&action=save";
				zswitch_ajax_request(url,"policy_calculate_form",function(type,data){
					if(type == "success")
					{
						$("[name='operation']").val('edit');
						$("[name='recordid']").val(data);
						if(typeof(loadCalculateList)=='function')
						{
							loadCalculateList();
						}
						var html =  '<p style="font-size:12px;"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
						html = html + "你确认将此保单提交审核流程。“确定”将提交，“取消”忽略。" + "</p>";
						$("#policy_calculate_submitAuditing_confirm").html(html);
					}
					else
					{
						var html =  '<p style="font-size:12px;"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
						html = html + "算价记录保存失败，提交核保失败。" + "</p>";
						$("#policy_calculate_submitAuditing_confirm").html(html);
					}
				});




			}
	});

	$("#policy_calculate_start_btn").data("module",this.module).click(function(event){
			var info = zswitchui_validity_check("#policy_calculate_form",true);

			if(info.length>0)
			{
				$("#policy_calculate_save_title").data("MsgContent",info).dialog("open")
				.dialog( "option", "position", { my: "center top", at: "center top", of: $(this) } );
			}
			else
			{
				premium_calculate($(this).data("module"),event.timeStamp);
			}

	});

	$("#policy_calculate_nieli_device_dlg").data("module",this.module).dialog({
			title:'设备详情',
			autoOpen:false,
			height:300,
			width:550,
			modal:true,
			appendTo: "body",
			dialogClass:"dialog_default_class",
			buttons:{
				'确定':function(){
					$(this).dialog("close");

				}
			},
			open:function(){
				var url = "index.php?module="+$(this).data("module")+"&action=newDeviceView";
				$(this).load(url);
			}


	});

	$("#policy_calculate_nieli_device_btn").click(function(){
		$("#policy_calculate_nieli_device_dlg").dialog( "option", "position", { my: "center bottom", at: "center top", of: $(this) } )
		.dialog("open");
	});

	$("#policy_calculate_form").data("is_opened",true);

	};


	$("#policy_calculate_check_dlg").data("module",this.module).dialog({
		title:"交管车辆信息查询",
		autoOpen:false,
		height:110,
		width:300,
		position: { my: "center top", at: "center top", of: window },
		modal:true,
		appendTo: "body",
		dialogClass:"dialog_default_class",
		open:function(){
			 var VIN_NO = $("#policy_calculate_form").find("[name='form[AUTO][VIN_NO]']").val();
			 var LICENSE_NO =  $("#policy_calculate_form").find("[name='form[AUTO][LICENSE_NO]']").val();
			 var rate_table = $("#policy_calculate_form").find("[name='form[OTHER][PREMIUM_RATE_TABLE]']").val();
			 var url = "index.php?module=PolicyCalculateCom&action=check&vin="+VIN_NO+"&license_no="+LICENSE_NO+"&rate_table="+rate_table;
			 $(this).load(url);

		},
	});


	$("#policy_calculate_check_btn").click(function(){
		$("#policy_calculate_check_dlg").dialog("open");

	});


	$("[name='form[OTHER][PREMIUM_RATE_TABLE]']").change(function(){
		var rate_table = $(this).val();
		var vin_no = $("[name='form[AUTO][VIN_NO]']").val();
		var accountid = $("[name='accountid']").val();
		$("#policy_calculate_content").load("index.php?module=PolicyCalculateCom&action=calculateView&recordid=&vin_no="+vin_no+"&rate_table="+rate_table+"&accountid="+accountid);

	});


};