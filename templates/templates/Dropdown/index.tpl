{include file="TitleBarA.tpl"}
<table border=0 width="100%">
<tr>
<td style="width:130px;vertical-align:top;border:1px solid #79b7e7;text-align:left;">
<div style="font-size:13px;padding-left:10px;height:30px;line-height:30px;background-color:#79b7e7;color:#FFFFFF;font-weight: bold;">
	<span>模块</span>
</div>
<div id="module_list_box" style="height:400px; overflow:auto">
{foreach $MODULE_LIST as $row}
	<div module_name = "{$row.MODULE_NAME}" title="{$row.DESCRIBE}" style="padding:2px;border-bottom:1px solid #EDEDED;"> 
	     <span style="font-size:12px;">{$row.NAME}</span>
	</div>
{/foreach}

</div>

</td>
<td style="vertical-align:top;border:1px solid #79b7e7;font-size:12px;">
	<form id="dropdown_setting_form">
		<div style="margin:5px;">
			<input type="hidden" name="module_name" value=""/>
			<span>模块：<input type="text" id="module_show_name" readonly="readonly"/></span>
			<span>字段：<select name="field_list"></select></span>
		</div>
		
		<div>
			<textarea name="options_text" cols="140" rows="20"></textarea>			
		</div>
	</form>
	    <div>
		   <button id="dropdown_setting_save_btn">保存</button>
		</div>
</td>
</tr>

</table>


<script>

{literal}
		$("#dropdown_setting_form").find("select[name='field_list']").change(function(){			
			var modname = $("#dropdown_setting_form").find("input[name='module_name']").val();
			var field = $(this).val();
			$("#dropdown_setting_form").find("[name='options_text']").val('');
			if(field != null && modname != null )
			{
				var url="index.php?module=Dropdown&action=getOptionsAjax&module_name="+modname+"&field="+field;
				$.getJSON(url,function(data){
					if(data.type=="success")
					{
						$("#dropdown_setting_form").find("[name='options_text']").val(data.data);
					}
					
				});
			}
		}).change();

	    $("#module_list_box div")
		.mouseenter(function(){
			$(this).css("background-color","#f5f8f9;");
			$(this).css("cursor","pointer");
		})
		.mouseleave(function(){
			$(this).css("background-color","#FFFFFF");
			$(this).css("cursor","default");
		})
		.click(function(){
			var mod = $(this).attr("module_name");
			var url = "index.php?module=Dropdown&action=getFieldsAjax&module_name=" + mod;
			$.getJSON(url,function(data){
				if(data.type == 'success')
				{
					var fldlist = $("#dropdown_setting_form").find("select[name='field_list']");
					fldlist.html('');
					$("#dropdown_setting_form").find("input[name='module_name']").val(data.data.module_name);					
					$("#dropdown_setting_form").find("#module_show_name").val(data.data.module_show_name);
					console.log(data.data.fields.length);
					for(idx=0;idx< data.data.fields.length;idx++)
					{
						fldlist.append('<option value="'+data.data.fields[idx][0]+'">'+data.data.fields[idx][1]+'</option>');
					}
					fldlist.change();
				}	
			});
			
		})
		.first().click();

		$("#dropdown_setting_save_btn").button()
		.click(function(){
			var url="index.php?module=Dropdown&action=save";
			var datas = $("#dropdown_setting_form").serialize();
			$.getJSON(url,datas,function(data){
				zswitch_open_messagebox('dropdown_setting_messagebox','下拉框设置',data.data,150,400);
			});
			
		});
		
{/literal}
</script>