{include file="TitleBarA.tpl"}
<form id="policy_calculate_setting_form">
<table border="0" width="100%"  >
<input type="hidden" value="{$DEFAULT_API}" id="DEFAULT_API">
	<tr>
		<td style="vertical-align:top;width:35%;">
		<fieldset style="font-size:12px;border:1px solid #AAAAAA;height:270px;" >
			<legend style="text-align:left;font-weight:bold;">保司接口</legend>
			<div style="border-bottom:1px solid #79b7e7;text-align:left;padding-left:20px;height:25px;line-height:25px;">
				默认接口：
				<select name="default_api" style="font-size:12px;">
					{foreach $APIS.data as $api_name => $api}
						{if $api['default'] eq 1}
						<option value="{$api['code']}" selected="selected">{$api['name']}</option>
						{else}
						<option value="{$api['code']}">{$api['name']}</option>
						{/if}
					{/foreach}
				</select>
			</div>
		</fieldset>
		</td>
		<td style="vertical-align:top;width:35%;">
		<fieldset style="font-size:12px;border:1px solid #AAAAAA;height:270px;" >
			<legend style="text-align:left;font-weight:bold;">保司账号设置</legend>
			<div style="border-bottom:1px solid #79b7e7;text-align:left;padding-left:20px;height:25px;line-height:25px;">
				接口:
				<select id="select_api_setting" style="font-size:12px;">
					{foreach $APIS.data as $api_name => $api}
						<option value="{$api['code']}" id="apicode">{$api['name']}</option>
					{/foreach}
				</select>
			</div>
			<div id="api_setitem_none" style="height:30px;line-height:30px;color:#AAAAAA;font-size:14px;display:none;">
				无设置项。
			</div>

			{foreach $API_SETITEMS as $key =>  $value}
				<div id="api_setitem_{$value['data']['code']}" style="text-align:left;padding-left:20px;margin-top:10px;display:none;">
					<table width="100%">
						{foreach $value['data'] as $k => $v}
						<tr>
							 {if  $k eq 'accounts'}
								{foreach $v as $u => $i}
									{if $i['allot'] eq 1}
									<font style="font-weight:bold;">分配账号：{$i['username']} -- </font>
									{if $i['status'] eq '正常'}
									<font color='green' style="font-weight:bold;">{$i['status']} </font>
									{else}
									<font color='red' style="font-weight:bold;">{$i['status']} </font>
									{/if}
									<p  value = "{$value['data']['code']}"  alt ="{$i['id']}" id="delAllot" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">删除</p><br />
									{/if}
								{/foreach}
								<font style="text-align:left;font-weight:bold;">自有账号：</font>
								<widget  class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"  value="{$value['data']['code']}">详情</widget>
							{/if}
						</tr>
						{/foreach}
					</table>
				</div>
			{/foreach}
			<input type ="hidden" id ="insur">
		</fieldset>
		</td>
		<td style="vertical-align:top;width:35%;">
		    <fieldset style="font-size:12px;border:1px solid #AAAAAA;height:40px;" >
                 <legend style="text-align:left;font-weight:bold;">短链接短信模板</legend>
                前缀：<input type="text" name="short_sms_before" style="width:60%" value="{$SHORT_SMS_BEFORE}">
                后缀：<input type="text" name="short_sms_after"  style="width:20%" value="{$SHORT_SMS_AFTER}">
            </fieldset>
            <fieldset style="font-size:12px;border:1px solid #AAAAAA;height:216px;" >
                <legend style="text-align:left;font-weight:bold;">常规短信模板</legend>
                <textarea name="sms_tpl" style="width:100%;height:90%;resize: none;">{$SMS_TPL}</textarea>
            </fieldset>
		</td>
	</tr>

	<tr>
		<td colspan ="3">
			<fieldset style="font-size:12px;border:1px solid #AAAAAA;height:100px;" >
				<legend style="text-align:left;font-weight:bold;">允许险种</legend>
				<table width="100%">
					<tr>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[TVDI]" name="INSURANCES[]" value="TVDI"/><label for ="INSURANCES[TVDI]">车辆损失险</label></td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[TTBLI]" name="INSURANCES[]" value="TTBLI"/><label for ="INSURANCES[TTBLI]">第三方责任险</label></td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[TWCDMVI]" name="INSURANCES[]" value="TWCDMVI"/><label for ="INSURANCES[TWCDMVI]">全车盗抢险</label></td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[TCPLI_DRIVER]" name="INSURANCES[]" value="TCPLI_DRIVER"/><label for ="INSURANCES[TCPLI_DRIVER]">车上人员责任险(司机)</label></td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[TCPLI_PASSENGER]" name="INSURANCES[]" value="TCPLI_PASSENGER"/><label for ="INSURANCES[TCPLI_PASSENGER]">车上人员责任险(乘客)</label></td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[BSDI]" name="INSURANCES[]" value="BSDI"/><label for ="INSURANCES[BSDI]">车身划痕险</label></td>
					</tr>
					<tr>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[SLOI]" name="INSURANCES[]" value="SLOI"/><label for ="INSURANCES[SLOI]">自燃损失险</label>        </td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[BGAI]" name="INSURANCES[]" value="BGAI"/><label for ="INSURANCES[BGAI]">玻璃单独破碎险</label>    </td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[NIELI]" name="INSURANCES[]" value="NIELI"/><label for ="INSURANCES[NIELI]">新增设备损失险</label> </td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[VWTLI]" name="INSURANCES[]" value="VWTLI"/><label for ="INSURANCES[VWTLI]">发动机涉水损失险</label> </td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[STSFS]" name="INSURANCES[]" value="STSFS"/><label for ="INSURANCES[STSFS]">指定专修厂</label></td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[RDCCI]" name="INSURANCES[]" value="RDCCI"/><label for ="INSURANCES[RDCCI]">修理期间费用补偿险</label></td>

					</tr>
					<tr>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[MVLINFTPSI]" name="INSURANCES[]" value="MVLINFTPSI"/><label for ="INSURANCES[MVLINFTPSI]">第三方特约险</label></td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[TTBLI_DOUBLE]" name="INSURANCES[]" value="TTBLI_DOUBLE"/><label for ="INSURANCES[TTBLI_DOUBLE]">第三方节假日翻倍险</label></td>
					</tr>
					<tr>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[MVTALCI]" name="INSURANCES[]" value="MVTALCI"/><label for ="INSURANCES[MVTALCI]">交强险</label></td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[CUSTOM1]" name="INSURANCES[]" value="CUSTOM1"/><label for ="INSURANCES[CUSTOM1]">自定义险1</label></td>
						<td style="text-align:left;"><input type="checkbox" id="INSURANCES[CUSTOM2]" name="INSURANCES[]" value="CUSTOM2"/><label for ="INSURANCES[CUSTOM2]">自定义险2</label></td>

					</tr>

				</table>
			</fieldset>
		</td>
	</tr>

</table>
</form>
<div id ="policy_calculate_setting_dlg">
</div>
<div style="text-align:center;margin-top:20px;font-size:12px;">
	<button id="policy_calculate_setting_save">保存</button>
</div>

<script>
	var allow_apis = {$ALLOW_APIS};
	var allow_insurances = {$ALLOW_INSURANCES};
{literal}
	var formobj = $("#policy_calculate_setting_form");
	formobj.find("[name='allow_apis[]']").click(function(){
		var api = $(this).val();
		var id = $(this).attr("id");
		var label = $("label[for='"+id+"']").html();
		var DEFAULT_API= $("#DEFAULT_API").val();
		if($(this).prop("checked"))
		{
			if(api == DEFAULT_API)
			{
				var html = '<option value="'+api+'" selected="selected">'+label+'</option>';
			}
			else
			{
				var html = '<option value="'+api+'">'+label+'</option>';
			}

			formobj.find("select[name='default_api']").append(html);
		}
		else
		{
			formobj.find("select[name='default_api'] option[value='"+api+"']").remove();
		}
	});
	for(idx in allow_apis)
	{
		formobj.find("[name='allow_apis[]'][value='"+allow_apis[idx]+"']").prop("checked",false).click();
	}

	for(idx in allow_insurances)
	{
		formobj.find("[name='INSURANCES[]'][value='"+allow_insurances[idx]+"']").prop("checked",false).click();
	}

	$("#groupid").change(function()
	{
		var groupid = $(this).val();
		var url = "index.php?module=PCSetting&action=index&groupid="+groupid;
		$("#main_view_client").load(url);
	});

	$("#select_api_setting").change(function(){
		var api = $(this).val();

		$("[id^='api_setitem_']").hide();
		var setbox = $("#api_setitem_"+api);
		if(setbox ==  null)
		{
			$("#api_setitem_none");
		}
		else
		{
			setbox.show();
		}
	}).change();

	$("widget").click(function(){
		var insurance = $(this).attr("value");
		$("#insur").val(insurance);
		$("#policy_calculate_setting_dlg").dialog("open");

	})
	$("p").click(function()
	{
		if(window.confirm('你确定要取消删除吗？'))
		{
			var insurance = $(this).attr("value");
			var id = $(this).attr("alt");
			var url = "index.php?module=PCSetting&action=insuranceDetail&insurance="+insurance;
			$.post(url,{'oper':'del','delid':id},function(msg)
			{
				var respen = JSON.parse(msg);
                  if(respen.type == 'error')
                  {
                    alert(respen.data);
                    return false;
                  }
                  else
                  {
                    alert('删除成功!');
                    var indexUrl = "index.php?module=PCSetting&action=index";
					$("#main_view_client").load(indexUrl);
                    return false;
                  }
			});
		}
		else
		{
			return false;
		}
	})

	$("#policy_calculate_setting_dlg").data("module",this.module).dialog({
		title:"算价器账号",
		autoOpen:false,
		height:280,
		width:810,
		position: { my: "center top", at: "center top", of: window },
		modal:false,
		appendTo: "body",
		dialogClass:"dialog_default_class",
		open:function(){
				jq_loading();
				var insurance = $("#insur").val();
				var url = "index.php?module=PCSetting&action=insuranceDetail&insurance="+insurance;
				$(this).load(url);

		},
		close:function(){
			jq_clearloading();
    	},
	});

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
			   'z-index': 99 //保证这个悬浮层位于其它内容之上
			});
	}

	function jq_clearloading()
	{
		$("#overlay").remove();
	}



	$("#policy_calculate_setting_save").button().click(function(){
		var url = "index.php?module=PCSetting&action=save";
		zswitch_ajax_request(url,"policy_calculate_setting_form",function(type,data){
			zswitch_open_messagebox('policy_calculate_setting_msgbox','算价器设置',data,150,400);
		});

	});

	$("input[type='password']").focus(function(){
	   $(this).val('');
	   $(this).attr('type','text');
	});
	$("input[type='password']").blur(function(){
	   if($(this).val() == ''){
	       $(this).attr('type','password');
	       $(this).val($(this).attr('value'));
	   }
	});

	$("input[type='password']").focus(function(){
	   $(this).val('');
	   $(this).attr('type','text');
	});
	$("input[type='password']").blur(function(){
	   if($(this).val() == ''){
	       $(this).attr('type','password');
	       $(this).val($(this).attr('value'));
	   }
	});




{/literal}
</script>