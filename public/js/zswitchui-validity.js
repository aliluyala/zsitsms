//字段输入有效性验证
function zswitchui_validity_check(selecter,taglabel)
{
	var zswitchui_validity_check_info = "";
	var form = $(selecter);
		form.find("[ui][name]").each(function(){
		if($(this).prop("disabled")) return;
		var ui = $(this).attr("ui");
		
		if(taglabel == null)
		{
			var label = $(this).parent().prev().children("label");
		}
		
		var info = "";
		if(ui == "1")
		{
			info = ui_validity_check_1($(this));
		}
		else if(ui == "2")
		{
			info = ui_validity_check_2($(this));
		}
		else if(ui == "3")
		{
			info = ui_validity_check_3($(this));
		}
		else if(ui == "4")
		{
			info = ui_validity_check_4($(this));
		}
		else if(ui == "5" || ui =="9" || ui == "50")
		{
			info = ui_validity_check_5($(this));
		}
		else if(ui == "6")
		{
			info = ui_validity_check_6($(this));
		}
		else if(ui == "7")
		{
			info = ui_validity_check_7($(this));
		}
		else if(ui == "8")
		{
			info = ui_validity_check_8($(this));
		}			
		else if(ui == "30")
		{
			info = ui_validity_check_30($(this));
		}
		else if(ui == "31")
		{
			info = ui_validity_check_31($(this));
		}
		else if(ui == "32")
		{
			info = ui_validity_check_32($(this));
		}
		else if(ui == "60")
		{
			info = ui_validity_check_60($(this));
		}
		else if(ui == "110")
		{
			info = ui_validity_check_110($(this));
		}
		
		if(info.length>0)
		{
			if(taglabel == null)	
			{				
				label.css("font-style","italic");
				label.css("color","#FF0000");				
			}
			
			zswitchui_validity_check_info += "<span style='line-height:18px;'>"+info + "</span><br/>";
		}
		else
		{
			if(taglabel == null)
			{
				label.css("font-style","normal");
				label.css("color","#5f5f5f");
			}

		}	
	});
	return zswitchui_validity_check_info;
}

function ui_validity_check_1(obj)
{
	if(obj.attr("mandatory"))
	{
		if(obj.val().length<=0)
		{
			
			return obj.attr("label")+"：不能为空。";
		}
	}
	return "";
}

function ui_validity_check_2(obj)
{
	var len = obj.val().length;
	var min_len = obj.attr("min_len");
	var max_len = obj.attr("max_len");
	if(obj.attr("mandatory"))
	{
		if(len<min_len || len>max_len)
		{
			
			return obj.attr("label")+"：不能为空,且长度最小"+min_len+"，最长"+max_len+"。";
		}
	}
	else if(len>0)
	{
		if(len<min_len || len>max_len)
		{
		
			return obj.attr("label")+"：输入长度必须最小"+min_len+"，最长"+max_len+"。";
		}		
	}
	return "";
}

function ui_validity_check_3(obj)
{
	var len = obj.val().length;
	var min_len = obj.attr("min_len");
	var max_len = obj.attr("max_len");
	if(len>0 && obj.val().match(/^\d+$/g) == null)
	{
		return obj.attr("label")+"：输入只能是数字。";		
	}
	if(obj.attr("mandatory"))
	{
		if(len<min_len || len>max_len)
		{
			return obj.attr("label")+"：不能为空,且长度最小"+min_len+"，最长"+max_len+"。";
		}
	}
	else if(len>0)
	{
		if(len<min_len || len>max_len)
		{
			return obj.attr("label")+"：输入长度必须最小"+min_len+"，最长"+max_len+"。";
		}		
	}
	return "";	
}


function ui_validity_check_4(obj)
{
	var len = obj.val().length;
	var min_len = obj.attr("min_len");
	var max_len = obj.attr("max_len");
	if(obj.val().match(/^[\w\W\s]+$/g) == null)
	{
		obj.css("border-color","#ff0000");
		return obj.attr("label")+"：输入只能是英文字符.";		
	}
	if(obj.attr("mandatory"))
	{
		if(len<min_len || len>max_len)
		{
			obj.css("border-color","#ff0000");
			return obj.attr("label")+"：不能为空,且长度最小"+min_len+",最长"+max_len+".";
		}
	}
	else if(len>0)
	{
		if(len<min_len || len>max_len)
		{
			obj.css("border-color","#ff0000");
			return obj.attr("label")+"：输入长度必须最小"+min_len+",最长"+max_len+".";
		}		
	}
	obj.css("border-color","#000000");
	return "";	
}

function ui_validity_check_5(obj)
{
	if(obj.attr("mandatory"))
	{
		if(obj.val().length<=0)
		{
			return obj.attr("label")+"：不能为空。";
		}
	}
	return "";	
}

function ui_validity_check_6(obj)
{
	var len = obj.val().length;
	if(len<=0 && obj.attr("mandatory"))
	{
		return obj.attr("label")+"：不能为空。";
	}
	else if(obj.val().match(/^(\d+|\d+\.\d+|\.\d+)$/g) == null)
	{
		return obj.attr("label")+"：输入只能是数值(如：123.123)。";
	}
	else
	{
		var min = obj.attr('min');
		var max = obj.attr('max');
		var val = obj.val();
		if(val<min || val>max)
		{
			return obj.attr("label")+"：输入数值超出范围,最小"+min+"最大"+max;
		}
	}
	return "";
}

function ui_validity_check_7(obj)
{
	var val = obj.val();
	var len = val.length;
	var min_len = obj.attr("min_len");
	var max_len = obj.attr("max_len");
	var regexp  = obj.attr("regexp");

	if(!obj.attr("mandatory") && len <=0)
	{
		return "";
	}
	else if(obj.attr("mandatory") && len <=0)
	{
		return obj.attr("label")+"：不能为空。";		
	}
	else if(len<min_len || len>max_len)
	{
		return obj.attr("label")+"：长度超出范围，最短："+min_len+",最长："+max_len;
	}
	else
	{
		var regexp = new RegExp(obj.attr("regexp"));
		if(null == val.match(regexp))
		{
			return obj.attr("label")+"：输入不正确，"+obj.attr('regtitle');
		}			
	}
	return "";	
}

function ui_validity_check_8(obj)
{
	var len = obj.val().length;
	if(len<=0 && !obj.attr("mandatory"))
	{
		return "";
	}
	else if(len<=0 && obj.attr("mandatory"))
	{
		return obj.attr("label")+"：不能为空。";
	}
	else
	{
		var min = obj.attr('min');
		var max = obj.attr('max');
		var val = parseFloat(obj.val());
		if(val<min || val>max)
		{
			return obj.attr("label")+"：输入数值超出范围,最小:"+min+",最大:"+max;
		}		
	}
	return "";
}

function ui_validity_check_30(obj)
{
	var len = obj.val().length;
	if(len<=0)
	{
		if(obj.attr("mandatory"))
		{
			return obj.attr("label")+"：不能为空。";
		}
	}
	else if(obj.val().match(/^\d+\-\d+\-\d+$/g) == null)
	{
		return obj.attr("label")+"：格式不正确，日期格式为:YYYY-mm-dd。";
	}
	return "";
}

function ui_validity_check_31(obj)
{
	var len = obj.val().length;
	if(len<=0)
	{
		if(obj.attr("mandatory"))
		{
			return obj.attr("label")+"：不能为空。";
		}
	}
	else if(obj.val().match(/^\d+\-\d+\-\d+\s+\d+\:\d+\:\d+$/g) == null)
	{
		return obj.attr("label")+"：格式不正确，日期时间格式为:YYYY-MM-dd HH:mm:ss。";
	}
	return "";
}

function ui_validity_check_32(obj)
{
	var len = obj.val().length;
	if(len<=0)
	{
		if(obj.attr("mandatory"))
		{
			return obj.attr("label")+"：不能为空。";
		}
	}
	else if(obj.val().match(/^\d+\:\d+\:\d+$/g) == null)
	{
		return obj.attr("label")+"：格式不正确，时间格式为:HH-mm-ss。";
	}
	return "";
}

function ui_validity_check_60(obj)
{	
	var len = obj.val().length;
	if(len<=0)
	{
		if(obj.attr("mandatory"))
		{
			return obj.attr("label")+"：不能为空。";
		}
	}
	else if(obj.val().match(/^(1[3458]\d{9})|(0\d{10,20})|([2-8]\d{6,7})$/g) == null)
	{
		return obj.attr("label")+"：号码格式不正确。";
	}
	return "";
}

function ui_validity_check_110(obj)
{
	if(obj.attr("mode")=="view") return "";
	if(obj.attr("mandatory"))
	{
		if(obj.val().length<=0)
		{
			
			return obj.attr("label")+"：不能为空。";
		}
	}
	var validinfo = obj.attr('invalid_info');
	if(validinfo && validinfo.length>0)
	{
		return obj.attr("label")+"："+validinfo;
	}
	return "";
}