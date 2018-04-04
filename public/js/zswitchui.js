
function zswitch_ui_form_init(selecter)
{
	var form = $(selecter);
	form.find("[ui][name]").each(function(){
		var ui = $(this).attr("ui");
		if(ui == "7")
		{
			zswitch_ui_init_7($(this));
		}
		else if(ui == "8")
		{
			zswitch_ui_init_8($(this));
		}
		else if(ui == "10")
		{
			zswitch_ui_init_10($(this));
		}
		else if(ui == "23")
		{
			zswitch_ui_init_23($(this));
		}
		else if(ui == "25")
		{
			zswitch_ui_init_25($(this));
		}
		else if(ui == "26")
		{
			zswitch_ui_init_26($(this));
		}
		else if(ui == "27")
		{
			zswitch_ui_init_27($(this));
		}
		else if(ui == "30")
		{
			zswitch_ui_init_30($(this));
		}
		else if(ui == "31" || ui =="35" || ui =="36")
		{
			zswitch_ui_init_31($(this));
		}
		else if(ui == "32")
		{
			zswitch_ui_init_32($(this));
		}
		else if(ui == "34")
		{
			zswitch_ui_init_34($(this));
		}
		else if(ui == "50" || ui == "51" || ui == "52")
		{
			zswitch_ui_init_50($(this));
		}
		else if(ui == "55")
		{
			zswitch_ui_init_55($(this));
		}
		else if(ui == "70")
		{
			//zswitch_ui_init_70($(this));
		}
		else if(ui == "110")
		{
			zswitch_ui_init_110($(this));
		}
	});
	$(".ui-datepicker").css("font-size","12px");
}

function zswitch_ui_init_7(obj)
{
	if(obj.attr("ui_init") == "true") return ;

	obj.blur(function(e){
		var val = $(this).val();
		var max_len = $(this).attr("max_len");
		var min_len = $(this).attr("min_len");
		var len = val.length;

		var err = '';
		if(len==0)
		{
			if($(this).attr("mandatory"))
			{
				err = "输入不能为空.";
			}

		}
		else if(len < min_len)
		{
			err = "长度不能小于："+min_len;
		}
		else if(len > max_len)
		{
			err = "长度不能大于："+max_len;
		}
		else
		{
			var regexp = new RegExp($(this).attr("regexp"));
			if(null == val.match(regexp))
			{
				err = "输入格式不对，"+$(this).attr('regtitle');
			}
		}

		if(err.length>0)
		{
			$(this).css("border",'1px solid red');
			$(this).one("focus",function(e){
				e.currentTarget.style.border = null;
				$("#"+$.md5($(this).attr("name")+"_error_title")).remove();
			});
			var offset = $(this).offset();
			offset.top = offset.top -30;
			var html = '<div style="position:absolute;border:1px solid #79b7e7;background-color:#ECECEC;color:#FF0000;font-size:12px;padding:2px;box-shadow: 5px 5px 5px #888888;">'+err+'</div>';
			var errobj = $(html);
			var dt = new Date();
			var id = $.md5($(this).attr("name")+"_error_title");
			errobj.attr("id",id)
			.insertBefore($(this))
			.offset(offset);
			setTimeout("$('#"+id+"').fadeOut(1000);",5000);
		}

	});
	obj.attr("ui_init","true");
}


function zswitch_ui_init_8(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	obj.keypress(function(e){
		var oldVal = $(this).val();
		var CaretPos = 0;
		var decimal = $(this).attr("decimal");
		if (document.selection)
		{
			// IE Support
			e.currentTarget.focus ();
			var Sel = document.selection.createRange();
			Sel.moveStart ('character', -e.currentTarget.value.length);
			CaretPos = Sel.text.length;
		}
		else if (e.currentTarget.selectionStart || e.currentTarget.selectionStart == '0')
		{
			// Firefox support
			CaretPos = e.currentTarget.selectionStart;
		}

		if(e.charCode>47 && e.charCode<59 )
		{

			var decpost = oldVal.search(/\./);
			if(decimal > 0 && decpost>=0)
			{
				if( CaretPos > decpost && (oldVal.length-decpost) > decimal )
				{
					e.preventDefault();
				}
			}
		}
		else if(e.charCode == 46)
		{
			if(-1<oldVal.search(/\./) ||  decimal == 0)
			{
				e.preventDefault();
			}
		}
		else if(e.charCode ==  45 || e.charCode ==  43)
		{
			if((CaretPos != 0) || (-1<oldVal.search(/[\+\-]/))  )
			{
				e.preventDefault();
			}
		}
		else
		{
			e.preventDefault();
		}
	});
	obj.blur(function(e){
		var val = $(this).val();
		var max = $(this).attr("max");
		var min = $(this).attr("min");

		var err = '';
		if(val.length==0)
		{
			if($(this).attr("mandatory"))
			{
				err = "输入不能为空.";
			}

		}
		else if(parseFloat(val) < min)
		{
			err = "输入不能小于："+min;
		}
		else if(parseFloat(val) > max)
		{
			err = "输入不能大于："+max;
		}
		else
		{
			var decimal = $(this).attr("decimal");
			$(this).val(parseFloat(val).toFixed(decimal));
		}

		if(err.length>0)
		{
			$(this).css("border",'1px solid red');
			$(this).one("focus",function(e){
				e.currentTarget.style.border = null;
				$("#"+$.md5($(this).attr("name")+"_error_title")).remove();
			});
			var offset = $(this).offset();
			offset.top = offset.top -30;
			var html = '<div style="position:absolute;border:1px solid #79b7e7;background-color:#ECECEC;color:#FF0000;font-size:12px;padding:2px;box-shadow: 5px 5px 5px #888888;">'+err+'</div>';
			var errobj = $(html);
			var dt = new Date();
			var id = $.md5($(this).attr("name")+"_error_title");
			errobj.attr("id",id)
			.insertBefore($(this))
			.offset(offset);
			setTimeout("$('#"+id+"').fadeOut(1000);",5000);
		}

	});
	obj.attr("ui_init","true");
}

function zswitch_ui_init_10(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	var dt = new Date();
	var id = $.md5(obj.attr("name")+dt.getTime()+ obj.parent().parent().index());
	obj.attr("id",id);

	var parent = obj.parent();
	obj.val(obj.next().html());
	parent.data("inputid",id);
	parent.prev().hide();
	var width = parent.width()-20;

	if(width<850) width = 850;

	var ueditor = UE.getEditor(obj.next().attr("id"),{initialFrameWidth:width,initialFrameHeight:500});
	parent.data("ueditor",ueditor);

	parent.mouseleave(function(){
		var inputid = $(this).data("inputid");
		var ueditor = $(this).data("ueditor");
		if(ueditor)
		{
			$("#"+inputid).val(ueditor.getContent());
		}
	});
	obj.attr("ui_init","true");
}

function zswitch_ui_init_23(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	obj.buttonset()
	   .click(function(event){
		if(event.target.type == "checkbox")
		{
			var val = $(this).prev().val();
			val = val.replace(event.target.value+",","");
			val = val.replace(","+event.target.value,"");
			val = val.replace(event.target.value,"");
			if(event.target.checked)
			{
				if(val.length == 0) val = event.target.value;
				else val += "," + event.target.value;
				$("[for='"+event.target.id+"']").find(":first").removeClass("ui-icon-closethick").addClass("ui-icon-check");
			}
			else
			{
				$("[for='"+event.target.id+"']").find(":first").addClass("ui-icon-closethick").removeClass("ui-icon-check");
			}
			$(this).prev().val(val);
		}

	});
	obj.find(":checkbox").each(function(){
		if($(this).prop("checked"))
		{
			$("label[for='"+$(this).attr("id")+"']").prepend('<span class="ui-button-icon-primary ui-icon ui-icon-check"></span>');
		}
		else
		{
			$("label[for='"+$(this).attr("id")+"']").prepend('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span>');
		}
		$("label[for='"+$(this).attr("id")+"']").removeClass("ui-button-text-only").addClass("ui-button-text-icon-primary");
	});

	obj.attr("ui_init","true");
}

function zswitch_ui_init_25(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	var dt = new Date();
	var id = $.md5(obj.attr("name")+dt.getTime()+ obj.parent().parent().index());
	obj.attr("id",id);

	var form = obj.parentsUntil("form");
	var filter_field = obj.attr("picklist_group_field");
	var selects = form.find("select");
	var this_idx = selects.index(obj);

	var filter_select = null;
	if(form.find("select:lt("+this_idx+")").is("[name="+filter_field+"]"))
	{
		filter_select = form.find("select:lt("+this_idx+")").filter("[name="+filter_field+"]").first();
	}
	else if(form.find("select:gt("+this_idx+")").is("[name="+filter_field+"]"))
	{
		filter_select = form.find("select:lt("+this_idx+")").filter("[name="+filter_field+"]").first();
	}
	var script = "filter_select.change(function(){";
		script += "var id = '"+id+"';";
		script += "var obj = $('#'+id);\
		var group_name = $(this).val();\
		if(obj.next().children().is('#'+group_name)) \
		{\
			obj.html(obj.next().children('#'+group_name).html());\
		}\
		else\
		{\
			obj.html('<option value=\"\"></option>');\
			}\
		});"
	eval(script.toString());
	filter_select.change();
	obj.attr("ui_init","true");
}

function zswitch_ui_init_26(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	var dt = new Date();
	var id = $.md5(obj.attr("name")+dt.getTime()+ obj.parent().parent().index());
	obj.attr("id",id);
	var form = obj.parentsUntil("form");
	var filter_field = obj.attr("picklist_group_field");
	var selects = form.find("select");
	var this_idx = selects.index(obj);

	var filter_select = null;
	if(form.find("select:lt("+this_idx+")").is("[name="+filter_field+"]"))
	{
		filter_select = form.find("select:lt("+this_idx+")").filter("[name="+filter_field+"]").first();
	}
	else if(form.find("select:gt("+this_idx+")").is("[name="+filter_field+"]"))
	{
		filter_select = form.find("select:lt("+this_idx+")").filter("[name="+filter_field+"]").first();
	}


	var script = "filter_select.change(function(){";
		script += "var table = '" + obj.attr("picklist_table_name") + "';";
		script += "var items_field = '" + obj.attr("picklist_items_field") + "';";
		script += "var filter_field = '" + obj.attr("picklist_filter_field") + "';";
		script += "var value = '" + obj.attr("picklist_value") + "';";
		script += "var group_name = $(this).val();";
		script += "var url = 'index.php?module=Picklists&action=getPicklist&table_name='+table;	";
		script += "url += '&value_field=' + items_field + '&filter_field=' + filter_field + '&filter_value=' + $(this).val();";
		script += "$.get(url,function(data){";
		script += "var ops = '<option value=\"\"> </option>';";
		script += "for(x=0;x<data.data.length;x++){";
		script += "if(value == data.data[x]) ops += '<option value=\"'+data.data[x]+'\" selected=\"selected\">' +   data.data[x] + '</option>';else{";
		script += "ops += '<option value=\"'+data.data[x]+'\">' +	data.data[x] + '</option>';}}";
		script += "var id = '"+id+"';";
		script += "var obj = $('#'+id);";
		script += "obj.html(ops);},'json');});";
	eval(script.toString());
	filter_select.change();
	obj.attr("ui_init","true");
}

function zswitch_ui_init_27(obj)
{
	if(obj.attr("ui_init") == "true") return ;

	obj.attr("ui_init","true");
}

function zswitch_ui_init_30(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	var dt = new Date();
	var id = $.md5(obj.attr("name")+dt.getTime()+ obj.parent().parent().index());
	obj.attr("id",id);
	obj.datepicker({
		
		dateFormat: "yy-mm-dd",
		changeMonth: true,
        changeYear: true
		});
	obj.mouseup(function(){
		$("#ui-datepicker-div").css("zIndex",9999);
	});
	obj.attr("ui_init","true");
}

function zswitch_ui_init_31(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	var dt = new Date();
	var id = $.md5(obj.attr("name")+dt.getTime()+ obj.parent().parent().index());
	obj.attr("id",id);
	obj.datetimepicker({
		timeFormat: "hh:mm:ss",
		dateFormat: "yy-mm-dd",
		changeMonth: true,
        changeYear: true
		});
	obj.attr("ui_init","true");
}



function zswitch_ui_init_32(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	var dt = obj.val();
	obj.val(dt.replace(/^\d+-\d+-\d+\s+/g,""));
	obj.timespinner();
	obj.attr("ui_init","true");
}

function zswitch_ui_init_34(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	obj.spinner();
	obj.attr("ui_init","true");
}


function zswitch_ui_init_50(obj)
{
	if(obj.attr("ui_init") == "true") return ;

	var showinput = obj.next();
	var selectbut = showinput.next();
	var deletebut = selectbut.next();
	var selectdlg = deletebut.next();
	var dt = new Date();
	var dlgid = $.md5(obj.attr("name")+dt.getTime()+ obj.parent().parent().index());

	selectdlg.attr("id",dlgid);
	obj.attr("ui_selecter_id",dlgid+"_value");
	showinput.attr("ui_selecter_id",dlgid+"_show_value");
	selectbut.attr("ui_selecter_id",dlgid);
	$("#"+dlgid).dialog({
		  autoOpen: false,
          height: 450,
          width: 700,
          modal: true,
          appendTo: "#main_view_client",
          dialogClass:"dialog_default_class",
  	      buttons:{
  	            '确定':function(){
						if($(this).find("[name=associate_select_radio]").is(":checked"))
						{
							var sel = $(this).find("[name=associate_select_radio]:checked");
							$("[ui_selecter_id="+$(this).attr("id")+"_value]").val(sel.val());
							$("[ui_selecter_id="+$(this).attr("id")+"_show_value]").val(sel.attr("show_value"));
						}
						$(this).dialog("close");
  	                 },
	            '取消':function(){
						$(this).dialog("close");
	                 }

	            },

		   open: function( event, ui ) {
				load_associate_selecter($(this).attr("module"),$(this).attr("show_field"),"","",0,$(this).attr("id"),$(this).attr("list_filter_field"),$(this).attr("list_filter_value"),$(this).attr("list_fields"));
		   }

	});

	deletebut.click(function(){
		$(this).prev().prev().val("");
		$(this).prev().prev().prev().val("");
	});
	selectbut.click(function(){
		var dlg = $("#"+$(this).attr("ui_selecter_id"));
		dlg.dialog("open");
	});
	obj.attr("ui_init","true");
}

function zswitch_ui_init_55(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	var typeselect = obj.next();
	var groupselect = typeselect.next();
	var userselect = groupselect.next();
	//var fieldname = obj.attr("name");

	typeselect.change(function(){
		var type = $(this).val();
		if(type == "group")
		{
			$(this).next().show();
			$(this).next().next().hide();
		}
		else if(type == "user")
		{
			$(this).next().hide();
			$(this).next().next().show();
		}
	});
	groupselect.change(function(){
		$(this).prev().prev().val($(this).val());
	});
	userselect.change(function(){
		$(this).prev().prev().prev().val($(this).val());
	});
	obj.attr("ui_init","true");
}

function zswitch_ui_init_110(obj)
{
	if(obj.attr("ui_init") == "true") return ;
	if(obj.attr("mode")=="view")
	{
		obj.prop("readonly","readonly");
		obj.attr("ui_init","true");
		return ;
	}
	var dt = new Date();
	var id = $.md5(obj.attr("name")+dt.getTime()+ obj.parent().parent().index());
	obj.attr("id",id);

	obj.blur(function(e){
		var val = $(this).val();
		var len = val.length;

		var err = '';
		if(len==0)
		{
			if($(this).attr("mandatory"))
			{
				err = "输入不能为空.";
			}
		}
		else
		{
			var url = $(this).attr("validity_url");
			url += "&source_module="+$(this).attr("source_module");
			url += "&source_field="+$(this).attr("name");
			url += "&recordid="+$(this).attr("recordid");
			url += "&value="+val;
			$(this).next().find("#loading").show();
			var id = $(this).attr("id");
			var script = "$.get(url,function(data){ ";
				script += "var inputobj = $('#"+id+"');";
				script += "if(data.type=='success')";
				script += "{ inputobj.next().children().hide(); inputobj.next().find('#valid').show(); inputobj.attr('invalid_info','');}";
				script += "else {  inputobj.next().children().hide();  inputobj.next().find('#invalid').show(); inputobj.attr('invalid_info',data.data);} ";
				script += "inputobj.one('focus',function(){ $(this).next().children().hide(); });";

				script += "},'json');";

			eval(script.toString());
		}

		if(err.length>0)
		{
			$(this).css("border",'1px solid red');
			$(this).one("focus",function(e){
				e.currentTarget.style.border = null;
				$("#"+$.md5($(this).attr("name")+"_error_title")).remove();
			});
			var offset = $(this).offset();
			offset.top = offset.top -30;
			var html = '<div style="position:absolute;border:1px solid #79b7e7;background-color:#ECECEC;color:#FF0000;font-size:12px;padding:2px;box-shadow: 5px 5px 5px #888888;">'+err+'</div>';
			var errobj = $(html);
			var dt = new Date();
			var id = $.md5($(this).attr("name")+"_error_title");
			errobj.attr("id",id)
			.insertBefore($(this))
			.offset(offset);
			setTimeout("$('#"+id+"').fadeOut(1000);",5000);
		}

	});
	obj.attr("ui_init","true");
}