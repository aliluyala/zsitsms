
//初始化菜单
function zswitch_menus_init()
{
	$('div.main_menu_item').mouseenter(function(){
		var action = $(this).attr("action");
		if(action == "SUB_MENU")
		{
			$(this).removeClass("main_menu_item_noactive");
			$(this).addClass("main_menu_item_active");
		}
		else if(action == "OPEN_MODULE" || action == "OPEN_WINDOWS")
		{
			$(this).css("text-decoration","underline");
			$(this).css("cursor","pointer");
		}
		$(this).find("div.main_view_sub_menu_box").show();
		var offset = $(this).offset();
		offset.left -= 	 5;
		offset.top  += 33;
		$(this).find("div.main_view_sub_menu_box").offset(offset);
	});

	$("div.main_menu_item").mouseleave(function(e){
		var action = $(this).attr("action");
		if(action == "SUB_MENU")
		{
			$(this).removeClass("main_menu_item_active");
			$(this).addClass("main_menu_item_noactive");
		}
		else if(action == "OPEN_MODULE" || action == "OPEN_WINDOWS")
		{
			$(this).css("text-decoration","none");
			$(this).css("cursor","default");
		}

		$(this).find("div.main_view_sub_menu_box").hide();
	});

	$("div.sub_menu_item").mouseenter(function(){
		$(this).addClass("sub_menu_item_active");
	});

	$("div.sub_menu_item").mouseleave(function(e){
		$(this).removeClass("sub_menu_item_active");
	});
	$("div.sub_menu_item").click(function(){

		$(this).removeClass("sub_menu_item_active");
		$(this).parent().hide();
		$(this).parent().parent().removeClass("main_menu_item_active");
		$(this).parent().parent().addClass("main_menu_item_noactive");

	});
}

//打开客户区视图
function zswitch_load_client_view(url,formid)
{
	var d = new Date();
	var ctimes = d.getTime();
	var ptimes = $('body').data('zswitch_load_client_view_prev_time');
	$('body').data('zswitch_load_client_view_prev_time',ctimes);
	if(typeof ptimes === 'number' && isFinite(ptimes))
	{
		var timed = ctimes - ptimes;		
		if(timed < 100) return;
	}
	
	var conbox = arguments[2] ? arguments[2] : 'main_view_client';
	var container =$('#'+conbox);
	container.append('<div id ="main_view_client_progressbar"></div>');
	var progressbar = $("#main_view_client_progressbar");
	progressbar.progressbar({value: false});
	var pos = container.position();
	pos.top += container.height()/2;
	pos.left += container.width()/2 - 150;
	progressbar.css('top',pos.top);
	progressbar.css('left',pos.left);
	$('body').data('current_client_url','#'+url);
	if(typeof(formid) == 'undefined' || formid == null || formid == '')
	{
		$(container).load(url);		
		window.location.hash = '#'+url;		
	}
	else
	{
		$.post(url,$('#'+formid).serialize(),function(data){
			container.html(data);
		});
	}

}

//ajax提交
function zswitch_ajax_request(url,formid,callbackfun)
{
	var datas = '';
	if( formid != '')
	{
		datas = $('#'+formid).serialize();
	}


	if(typeof(callbackfun)!='function')
	{
		$.post(url,datas);
	}
	else
	{
		$.post(url,datas,function(result){
			callbackfun(result.type,result.data);
		},'json');
	}
}

//显示提示框
function zswitch_open_messagebox(id,title,msg,height,width)
{
	if(!$("#main_view_client").find("div").is("#"+id))
	{
		var dlgbox = '<div id="'+id+'"></div>';
		$("#main_view_client").append(dlgbox);
		$("#"+id).dialog({
			autoOpen:false,
			height:height,
			width:width,
			modal:true,
			appendTo: "#main_view_client",
			dialogClass:"dialog_default_class",
			buttons:{
				'确定':function(){
					$(this).dialog("close");
				}
			}
		});
	}
	$("#"+id).dialog("option","title",title);
	var html =  '<p style="font-size:12px;"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
				html = html + msg + "</p>";
	$("#"+id).html(html);
	$("#"+id).dialog("open");
}

//ajax提交
function zswitch_ajax_load_client_view(url,formid)
{
	if(!$("#main_view_client").find("div").is("#ajax_load_client_view_dialog"))
	{
		var dlgbox = '<div id="ajax_load_client_view_dialog"></div>';
		$("#main_view_client").append(dlgbox);
		$("#ajax_load_client_view_dialog").dialog({
			title:"提示信息",
			autoOpen:false,
			height:400,
			width:400,
			modal:true,
			appendTo: "#main_view_client",
			dialogClass:"dialog_default_class",
			beforeClose:function(){
				return $(this).data("handled");
			},
			open:function(){
				$(this).html('<div style="margin-top:150px;text-align:center;">处理中，请稍候.......</div>');
				zswitch_show_progressbar($(this),"ajax_load_progressbar");

			},
		});
	}
	$("#ajax_load_client_view_dialog").dialog( "option", "buttons",[]);
	$("#ajax_load_client_view_dialog").data("handled",false);
	$("#ajax_load_client_view_dialog").dialog("open");
	zswitch_ajax_request(url,formid,function(type,data){
		$("#ajax_load_client_view_dialog").data("handled",true);
		if(type == 'rediect')
		{
			$("#ajax_load_client_view_dialog").dialog("close");
			zswitch_load_client_view(data);
		}
		else if(type == 'error')
		{
			var html =  '<p style="font-size:12px;"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
				html = html + data+"</p>";
			$("#ajax_load_client_view_dialog").html(html);
			$("#ajax_load_client_view_dialog").dialog( "option", "buttons",[{text:"确定",click:function(){$(this).dialog("close");}}]);

		}
		else if(type == 'message')
		{
			$("#ajax_load_client_view_dialog").dialog( "option", "buttons",[{text:"确定",click:function(){$(this).dialog("close");}}]);
			var html =  '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
				html = html + data+"</p>";
			$("#ajax_load_client_view_dialog").html(html);
		}
		else
		{
			$("#ajax_load_client_view_dialog").dialog("close");
			$('#main_view_client').html(data);
		}
	});
}
//初始化列表视图
function zswitch_client_listview_table_init(id)
{
	var datatr = $("#"+id+" tr:gt(0)");
	if(typeof(datatr) == "undefined") return ;
	datatr.mouseenter(function(){
		$(this).addClass("client_listview_table_hightling");
	});
	datatr.mouseleave(function(){
		$(this).removeClass("client_listview_table_hightling");
	});

}

//增加搜索条件
function zswitch_add_search_conditin(mod)
{
	var search_uis = $("#"+mod+"_search_dlg #search_ui_list");
	var tab = $("#"+mod+"_search_condition_list");
	tab.append("<tr><td>&nbsp;</td><td>&nbsp;</td><td style='width:40%'>&nbsp;</td><td style='width:10%'>&nbsp;</td><td style='width:10%'></td></tr>");
	tab.find("tr:last").children("td:eq(0)").html(search_uis.children("#search_ui_select_field").html());
	var selField = tab.find("tr:last").children("td:eq(0)").children("[name=search_select_field]");
	selField.change(function(){
		var fieldName = $(this).val();
		var thistr = $(this).parent().parent();
		if(search_ui[fieldName]["dataType"] == "S")
		{
			thistr.children("td:eq(1)").html(search_uis.children("#search_ui_condition_str").html());
		}
		else
		{
			thistr.children("td:eq(1)").html(search_uis.children("#search_ui_condition_num").html());
		}

		thistr.children("td:eq(2)").html(search_ui[fieldName]['html']);
		thistr.children("td:eq(2)").children("[name="+fieldName+"]").attr("readonly",false);
		var tridx = thistr.index();
		if(tridx>1)
		{
			thistr.parent().children("tr:eq("+(tridx-1)+")").children("td:eq(3)").html(search_uis.children("#search_ui_link").html());
		}
		thistr.children("td:eq(4)").html(search_uis.children("#search_ui_delete_but").html());
		thistr.children("td:eq(4)").children(".listview_search_button_one").button().click(function(){
			var tridx = $(this).parent().parent().index();
			var lastidx = $(this).parent().parent().parent().children("tr:last").index();
			if(tridx == lastidx)
			{
				$(this).parent().parent().parent().children("tr:eq("+(tridx-1)+")").children("td:eq(3)").empty();
			}
			$(this).parent().parent().remove();
		});
		zswitch_ui_form_init("#"+mod+"_search_form");
	});
	selField.change();
}

//初始化搜索条件
function zswitch_init_search_conditin(mod,search_query_where)
{

	$("#"+mod+"_search_condition_list").find("tr:gt(0)").remove();
	for(var i=0;i< search_query_where.length;i++)
	{
		zswitch_add_search_conditin(mod);
	}
	var idx = 1;
	for( x in search_query_where )
	{
		var fieldname = search_query_where[x][0];
		var thistr = $("#"+mod+"_search_condition_list").find("tr:eq("+idx+")");
		var fieldSelect = thistr.children("td:eq(0)").children("[name=search_select_field]");
		fieldSelect.val(fieldname);
		fieldSelect.change();
		var conditionSelect = thistr.children("td:eq(1)").children("[name=select_condition]");
		conditionSelect.val(search_query_where[x][1]);
		var valueinput = thistr.children("td:eq(2)").children("[name="+fieldname+"]:first");
		if(valueinput.attr("ui") == "21")
		{
			thistr.children("td:eq(2)").children("input[value="+search_query_where[x][2]+"]").prop("checked",true);

		}
		else if(valueinput.attr("ui") == "50")
		{
			valueinput.val(search_query_where[x][2]);
			thistr.children("td:eq(2)").children("input:eq(1)").val(search_query_where[x][4]);
		}
        else if(valueinput.attr("ui") == "55")
        {
            if(search_query_where[x][2] >= 1000000){
                thistr.children("td:eq(2)").children("select:eq(0)").find("option[value=group]").prop("selected",true);
                thistr.children("td:eq(2)").children("select:eq(0)").change();
                thistr.children("td:eq(2)").children("select:eq(1)").find("option[value="+search_query_where[x][2]+"]").prop("selected",true);
            }else{
                thistr.children("td:eq(2)").children("select:eq(0)").find("option[value=user]").prop("selected",true);
                thistr.children("td:eq(2)").children("select:eq(0)").change();
                thistr.children("td:eq(2)").children("select:eq(2)").find("option[value="+search_query_where[x][2]+"]").prop("selected",true);
            }
        }
		else
		{
			valueinput.val(search_query_where[x][2]);
		}

		idx++;
	}
	var idx = 1;
	for( x in search_query_where)
	{
		var thistr = $("#"+mod+"_search_condition_list").find("tr:eq("+idx+")");
		if(thistr.children("td:eq(3)").children().is("[name=select_link]"))
		{
			var linkSelect = thistr.children("td:eq(3)").children("[name=select_link]");
			linkSelect.val(search_query_where[x][3]);
		}
		idx++;
	}
}

//搜索条件生成
function zswitch_create_search_conditin(mod)
{
	search_query_where = new Array();
	$("#"+mod+"_search_condition_list").find("tr:gt(0)").each(function(){
		var idx = $(this).index()-1;
		var fieldname = $(this).children("td:eq(0)").children("[name=search_select_field]").val();
		var conditin = $(this).children("td:eq(1)").children("[name=select_condition]").val();
		var valueinput = $(this).children("td:eq(2)").children("[name="+fieldname+"]").val();
		var link = '';
		if($(this).children("td:eq(3)").children().is("[name=select_link]"))
		{
			link = $(this).children("td:eq(3)").children("[name=select_link]").val();
		}
		var showvalue = '';
		var ui = $(this).children("td:eq(2)").children("[name="+fieldname+"]:first").attr("ui") ;
		if(ui == "50")
		{
			showvalue = $(this).children("td:eq(2)").children("input:eq(1)").val();
		}
		else if(ui == "21")
		{
			valueinput = $(this).children("td:eq(2)").children("input:checked").val();

		}
		search_query_where[idx] = new Array();
		search_query_where[idx][0]=fieldname;
		search_query_where[idx][1]=conditin;
		search_query_where[idx][2]=valueinput;
		search_query_where[idx][3]=link;
		search_query_where[idx][4]=showvalue;
	});
}


//列表视图翻页控制
function zswitch_client_listview_page_ctrl(page,formid)
{
	$("#"+formid).children("[name=record_page]:input").val(page);
	var module = $("#"+formid).children("[name=module]:input").val();
	var action = $("#"+formid).children("[name=action]:input").val();
	var url = "index.php?module="+module+"&action="+action;
	zswitch_load_client_view(url,formid);
}



//列表视图排序控制
function zswitch_client_listview_order_ctrl(field,order,formid)
{

	$("#"+formid).children("[name=order_by]:input").val(field);
	if(order == "ASC")
	{
		$("#"+formid).children("[name=order]:input").val("DESC");
	}
	else if(order == "DESC")
	{
		$("#"+formid).children("[name=order]:input").val("NONE");
		$("#"+formid).children("[name=order_by]:input").val("");
	}
	else
	{
		$("#"+formid).children("[name=order]:input").val("ASC");
	}
	var url = "index.php?module="+$("#"+formid).children("[name=module]:input").val()+"&action="+$("#"+formid).children("[name=action]:input").val();
	zswitch_load_client_view(url,formid);
}

//列表视图记录编辑
function zswitch_listview_operation_edit(mod,id)
{
	var url = "index.php?module="+mod+"&action=editView&recordid="+id;
		url += "&return_module="+mod+"&return_action=index";
	zswitch_load_client_view(url);
}



//列表视图记录删除
function zswitch_listview_operation_delete(mod,id)
{
	var container =$('#main_view_client');
	container.find("#dialog_confirm_delete_record").remove();

	var html = '<div id="dialog_confirm_delete_record" title="确认删除" >';
	    html += '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
	    html += '你确定要删除记录吗？<br/>点击“确定”删除，点击“取消”忽略。</p></div>';

	container.append(html);
	var dlg = container.find("#dialog_confirm_delete_record");
	dlg.data('recordid',id);
	dlg.data('module',mod);
	dlg.dialog({
     autoOpen: true,
     height: 200,
     width: 380,
     modal: true,
	 appendTo: "#main_view_client",
     dialogClass:"dialog_default_class",
     buttons: {
    "确定":function(){
		var url = "index.php?module="+dlg.data('module')+"&action=delete&recordid="+dlg.data('recordid');
		zswitch_ajax_load_client_view(url);
		//zswitch_load_client_view(url);
    	$( this ).dialog( "close" );
    },
    "取消":function(){
    	$( this ).dialog( "close" );
       }
     }
    });

}

//

//加载关联选择
function load_associate_selecter(module,showField,ctrl,searchValue,start,searchdlgid,list_filter_field,list_filter_value,list_fields)
{
	var container =   $("#"+searchdlgid);

	zswitch_show_progressbar(container,"associate_selecter_progressbar");
	var url = "index.php?module="+module+"&action=associateSelecter&showField="+showField+"&pageCtrl="+ctrl;
	url +="&searchValue="+searchValue+"&start="+start+"&searchdlgid="+searchdlgid;
	url +="&list_filter_field="+list_filter_field+"&list_filter_value="+list_filter_value+"&list_fields="+list_fields;
	container.load(url);
}

//关联列表视图翻页控制
function zswitch_associate_listview_page_ctrl(page,formid)
{
	var id = $("#"+formid).parent().attr("id");
	$("#"+formid).children("[name=record_page]:input").val(page);
	var module = $("#"+formid).children("[name=module]:input").val();
	var action = $("#"+formid).children("[name=action]:input").val();
	var associate_field =  $("#"+formid).children("[name=associate_field]:input").val();
	var associate_value =  $("#"+formid).children("[name=associate_value]:input").val();
	var list_fields =  $("#"+formid).children("[name=list_fields]:input").val();
	var url = "index.php?module="+module+"&action=associateListView&associateField="+associate_field+"&fieldvalue="+associate_value+"&listfields="+list_fields;

	zswitch_load_client_view(url,formid,id);
}



//关联列表视图排序控制
function zswitch_associate_listview_order_ctrl(field,order,formid)
{
	var id = $("#"+formid).parent().attr("id");
	var module = $("#"+formid).children("[name=module]:input").val();
	var action = $("#"+formid).children("[name=action]:input").val();
	var associate_field =  $("#"+formid).children("[name=associate_field]:input").val();
	var associate_value =  $("#"+formid).children("[name=associate_value]:input").val();
	var list_fields =  $("#"+formid).children("[name=list_fields]:input").val();

	$("#"+formid).children("[name=order_by]:input").val(field);
	if(order == "ASC")
	{
		$("#"+formid).children("[name=order]:input").val("DESC");
	}
	else if(order == "DESC")
	{
		$("#"+formid).children("[name=order]:input").val("NONE");
		$("#"+formid).children("[name=order_by]:input").val("");
	}
	else
	{
		$("#"+formid).children("[name=order]:input").val("ASC");
	}

	var associate_field =  $("#"+formid).children("[name=associate_field]:input").val();
	var associate_value =  $("#"+formid).children("[name=associate_value]:input").val();
	var list_fields =  $("#"+formid).children("[name=list_fields]:input").val();
	var url = "index.php?module="+module+"&action=associateListView&associateField="+associate_field+"&fieldvalue="+associate_value+"&listfields="+list_fields;
	zswitch_load_client_view(url,formid,id);
}

//打开一个日历对话框
function zswitch_open_calendar_dlg()
{
	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"><div id="calendar"></div></div>';
	$('#main_view_client').append(html);
	$("#"+id).dialog({
		autoOpen: true,
		height: 320,
		width: 280,
		modal: false,
		title:"日历",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			$(this).children("#calendar").datepicker({changeMonth: true,changeYear: true});
		},
		beforeClose:function(){
			$(this).children("#calendar").datepicker("destroy");
			$(this).dialog("destroy");
			$(this).remove();
		},
		buttons: {
		"关闭":function(){
			$( this ).dialog( "close" );
		}
		}
    });

}

//打开短信发送对话框
function zswitch_open_sendsms_dlg(calleeid,content,caculate_data,short_sms_before,short_sms_after)
{
	if(!$('#main_view_client').children().is('#titlebar_sms_notify_dlg'))
	{
		var html = '<div id="titlebar_sms_notify_dlg"></div>';
		$('#main_view_client').append(html);
	}
	if(typeof(calleeid) != 'undefined' && typeof(content) != 'undefined')
	{
		$("#titlebar_sms_notify_dlg").data("calleeid",calleeid);
		$("#titlebar_sms_notify_dlg").data("content",content);
	}
	if(typeof(caculate_data) != 'undefined'){
		$("#titlebar_sms_notify_dlg").data("caculate_data",caculate_data);
	}
	if(typeof(short_sms_before) != 'undefined'){
		$("#titlebar_sms_notify_dlg").data("short_sms_before",short_sms_before);
	}
	if(typeof(short_sms_after) != 'undefined'){
		$("#titlebar_sms_notify_dlg").data("short_sms_after",short_sms_after);
	}
	$("#titlebar_sms_notify_dlg").dialog({
		autoOpen:true,
		height:500,
		width:600,
		modal:true,
		title:"发送短信",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"dialog_progress_bar");
			var url ="index.php?module=SMSNotify&action=sendSMSView";
			$(this).load(url,function(){
				var calleeid = $("#titlebar_sms_notify_dlg").data("calleeid");
				var content = $("#titlebar_sms_notify_dlg").data("content");
				var caculate_data = $("#titlebar_sms_notify_dlg").data("caculate_data");
				if(typeof(calleeid) != 'undefined' && typeof(content) != 'undefined')
				{
					//console.log(calleeid);
					//console.log(content);
					$("#titlebar_sms_notify_dlg").find("input[name='calleeid']").val(calleeid);
					$("#titlebar_sms_notify_dlg").find("textarea[name='content']").val(content);

				}
				if(typeof(caculate_data) != 'undefined'){ 
					$("#titlebar_sms_notify_dlg").find("input[name='caculate_data']").val(caculate_data);
				}
                if(typeof(short_sms_before) != 'undefined'){
					$("#titlebar_sms_notify_dlg").find("#short_sms_before").val(short_sms_before);
				}
				if(typeof(short_sms_after) != 'undefined'){
					$("#titlebar_sms_notify_dlg").find("input[name='short_sms_after']").val(short_sms_after);
				}

			});
		},
		beforeClose:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},
		buttons: {
		"确定":function(){
			$(this).parent().find('button:contains("确定")').attr("disabled","disabled");
            if($('#tabs-0').css('display') =='block'){
			   var formid = 'tools_send_sms_dlg_form_0';
		    }else{
			   var formid = 'tools_send_sms_dlg_form_1';
		    }

			var url = "index.php?module=SMSNotify&action=sendSMS";
			//zswitch_load_client_view(url,"tools_send_sms_dlg_form");
			zswitch_show_progressbar($(this),"dialog_progress_bar");
			zswitch_ajax_request(url,formid,function(type,data){
				zswitch_hide_progressbar($("#titlebar_sms_notify_dlg"),"dialog_progress_bar");
				//alert(data);
				if(type == "success")
				{
					$("#titlebar_sms_notify_dlg").dialog( "close" );
				}
				zswitch_open_messagebox("zswitch_sms_operation_messagebox","发送短信",data,200,350);
              
			});
             $(this).parent().find('button:contains("确定")').removeAttr("disabled");
		},
		"取消":function(){
			$( this ).dialog( "close" );
		}
		}
	});
}

function zswitch_show_progressbar(obj,id)
{
	var html = '<div id="'+id+'" style="position: absolute;"></div>';

	obj.prepend(html);
	var progressbar = obj.find("#"+id);
	progressbar.progressbar({value: false});
	var pos = obj.position();
	progressbar.width(obj.width()/2);
	progressbar.height("20");
	pos.top += obj.height()/2.3;
	pos.left += obj.width()/4;
	progressbar.css('top',pos.top);
	progressbar.css('left',pos.left);
}

function zswitch_hide_progressbar(obj,id)
{
	if(obj.children().is("#"+id))
	{
		var pobj = obj.find("#"+id);
		pobj.progressbar( "destroy" );
		pobj.remove();
	}
}

//打开数据导入对话框
function zswitch_open_import_dlg(module)
{
	if(!$('#main_view_client').children().is('#titlebar_import_data_dlg'))
	{
		var html = '<div id="titlebar_import_data_dlg"></div>';
		$('#main_view_client').append(html);
	}

	$('#titlebar_import_data_dlg').data('module',module);
	$('#titlebar_import_data_dlg').dialog({
		autoOpen:true,
		height:500,
		width:600,
		modal:true,
		title:"数据导入向导",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"dialog_progress_bar");
			var url ="index.php?module="+$(this).data("module")+"&action=import";
			$(this).load(url);
		},
		beforeClose:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},
		buttons: {
		"下一步":function(){
			var step = $(this).data("step") ;
			if(step == "1")
			{
				zswitch_show_progressbar($(this),"dialog_progress_bar");
				$.ajaxFileUpload({
					url:"index.php?module="+$(this).data("module")+"&action=import&step=uploadfile",
					secureuri:false,
					fileElementId:'import_upload_file',
					dataType: 'json',
					success: function (data, status){
						zswitch_hide_progressbar($("#titlebar_import_data_dlg"),"dialog_progress_bar");
						if(typeof(data.error) != 'undefined')
						{
							if(data.error != '')
							{
								zswitch_open_messagebox("zswitch_import_messagebox","数据导入",data.error,200,300);
								//alert(data.error);
							}
							else
							{
								zswitch_show_progressbar($("#titlebar_import_data_dlg"),"dialog_progress_bar");
								var url = "index.php?module="+$('#titlebar_import_data_dlg').data("module")+"&action=import&step=2&datafile="+data.msg;
								$("#titlebar_import_data_dlg").load(url);
								$("#titlebar_import_data_dlg").data('datafile',data.msg);
							}
						}
					},
					error: function (data, status, e){
						zswitch_hide_progressbar($("#titlebar_import_data_dlg"),"dialog_progress_bar");
						//alert(e);
						zswitch_open_messagebox("zswitch_import_messagebox","数据导入",e,200,300);
					}
				});
			}
			else if(step == "2")
			{
				zswitch_show_progressbar($(this),"dialog_progress_bar");
				var url = "index.php?module="+$(this).data("module")+"&action=import&step=3&datafile="+$("#titlebar_import_data_dlg").data('datafile');
				$.post(url,$('#import_dialog_form').serialize(),function(result){
					$('#titlebar_import_data_dlg').html(result);
				});
			}
			else if(step == "3")
			{
				zswitch_show_progressbar($(this),"dialog_progress_bar");
				var url = "index.php?module="+$(this).data("module")+"&action=import&step=4&datafile="+$("#titlebar_import_data_dlg").data('datafile');
				$.post(url,$('#import_dialog_form').serialize(),function(result){
					$('#titlebar_import_data_dlg').html(result);
				});
			}

		},
		"取消":function(){
			$( this ).dialog( "close" );
		}
		}
	});

}

//打开数据导入对话框A
function zswitch_open_import_dlg_A(module)
{
	if(!$('#main_view_client').children().is('#titlebar_import_data_dlg_A'))
	{
		var html = '<div id="titlebar_import_data_dlg_A"></div>';
		$('#main_view_client').append(html);
	}

	//$('#titlebar_import_data_dlg_A').data('module',module);
	$('#titlebar_import_data_dlg_A').wizardDialog({
		autoOpen:true,
		height:500,
		width:600,
		modal:true,
		//title:"数据导入向导",
		appendTo: "#main_view_client",
		dialogClass: "dialog_default_class",
		loadIcon: "public/images/loading_c.gif",
		module:module,
		close:function(){
			$(this).wizardDialog("destroy");
			$(this).remove();
		},
		steps:[
				{title:"数据导入-上传数据文件",url:"index.php?module="+module+"&action=import&step=1",formSelector:"#import_dialog_form_id_step1"},
				{title:"数据导入-导入设置",url:"index.php?module="+module+"&action=import&step=2",formSelector:"#import_dialog_form_id_step2"},
				{title:"数据导入-字段设置",url:"index.php?module="+module+"&action=import&step=3",formSelector:"#import_dialog_form_id_step3"},
				{title:"数据导入-设置字段默认值",url:"index.php?module="+module+"&action=import&step=4",formSelector:"#import_dialog_form_id_step4"},
				{title:"数据导入-完成",url:"index.php?module="+module+"&action=import&step=5",formSelector:"#import_dialog_form_id_step5"}
	    ],
		stepchangebefore:function(event,ui){
			if(ui.oper == "next" && ui.currentStep == 1)
			{
				if(ui.formData[0].name == "dataFile" && ui.formData[0].value.length>0)
				{
					$(this).wizardDialog("allowChange",true);
				}
				else
				{
					zswitch_open_messagebox("import_error_messagebox","操作错误","你还没有上传数据文件！",150,300);
					$(this).wizardDialog("allowChange",false);
				}
			}
			else if(ui.oper == "next" && ui.currentStep == 3)
			{
				var selCols = 0;
				var colarr = [];
				//查检是否指定的导入列
				for(idx in ui.formData )
				{
					if(ui.formData[idx].name == "import_cols[]" )
					{
						selCols++;
						colarr[selCols] = ui.formData[idx].value;
					}
				}
				if(selCols <=0)
				{
					zswitch_open_messagebox("import_error_messagebox","操作错误","需要至少指定一列数据导入！",150,300);
					$(this).wizardDialog("allowChange",false);
				}
				else
				{
					$(this).wizardDialog("allowChange",true);
				}
			}
			else if(ui.oper == "prev" && ui.currentStep == 3)
			{
				$(this).wizardDialog("allowChange",true);
			}
			else if(ui.oper == "next" && ui.currentStep == 4)
			{

				var info = zswitchui_validity_check(ui.formSelector);

				if(info.length<=0)
				{
					$(this).wizardDialog("allowChange",true);
				}
				else
				{
					info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
					zswitch_open_messagebox("import_editview_input_errorr","输入错误",info,400,400);
					$(this).wizardDialog("allowChange",false);
				}

			}
			else if(ui.oper == "prev" && ui.currentStep == 4)
			{
				$(this).wizardDialog("allowChange",true);
			}
			else if(ui.oper == "prev" && ui.currentStep == 5)
			{
				$(this).wizardDialog("allowChange",false);
			}
			else if(ui.oper == "complete")
			{
				if(typeof(import_dialog_complete_tag) != 'undefined' && import_dialog_complete_tag)
				{
					$(this).wizardDialog("close");
				}
				else
				{
					zswitch_open_messagebox("import_editview_input_errorr","操作错误","数据导入未完成，不能退出！",180,400);
					$(this).wizardDialog("allowChange",false);
				}
			}
		},
		stepchangeafter:function(event,ui){

		},
		contentload:function(event,ui){

			var formobj = $(ui.formSelector);
			if(ui.currentStep == 2 && ui.oldStep > ui.currentStep)
			{
				var dataFile = "";
				var col_separator = "";
				var firstline = false;
				var title_is_filed = false;
				var worksheet = "";
				var crr_field = "";
				for(index in ui.formData)
				{
					if(ui.formData[index].name == "dataFile") dataFile = ui.formData[index].value;
					else if(ui.formData[index].name == "col_separator") col_separator = ui.formData[index].value;
					else if(ui.formData[index].name == "firstline") firstline = true;
					else if(ui.formData[index].name == "title_is_filed") title_is_filed = true;
					else if(ui.formData[index].name == "worksheet") worksheet = ui.formData[index].value;
					else if(ui.formData[index].name == "check_repeat_record_field") crr_field = ui.formData[index].value;

				}
				formobj.find("[name='dataFile']").val(dataFile);
				formobj.find("[name='col_separator']").val(col_separator);
				formobj.find("[name='firstline']").prop("checked",firstline);
				formobj.find("[name='title_is_filed']").prop("checked",title_is_filed);
				if(!firstline)
				{
					formobj.find("[name='title_is_filed']").prop("disabled",true);
				}
				formobj.find("[name='worksheet']").val(worksheet);
				if(crr_field == "")
				{
					formobj.find("#check_repeat_record").prop("checked",false);
					formobj.find("#check_repeat_record_field").prop("disabled",true);
				}
				else
				{
					formobj.find("#check_repeat_record").prop("checked",true);
					formobj.find("#check_repeat_record_field").prop("disabled",false);
					formobj.find("#check_repeat_record_field").val(crr_field);
				}
			}
			if(ui.currentStep == 3 && ui.oldStep > ui.currentStep)
			{
				var cols = [];
				for(index in ui.formData)
				{
					if(ui.formData[index].name.search(/^import_cols/)!=-1)
					{
						var col = ui.formData[index].value;
						formobj.find("[name^='import_cols'][value='"+col+"']").prop("checked",true);
						formobj.find("[name='col_field_name_"+col+"']").prop("disabled",false);
					}
					else
					{
						formobj.find("[name='"+ui.formData[index].name+"']").val(ui.formData[index].value);
					}

				}
			}
		},
		beforeClose:function(){
			if(typeof(import_dialog_complete_tag) != 'undefined' && import_dialog_complete_tag)
			{
				//如果在导入完成后关闭对话框，则刷新页面。
				import_dialog_complete_tag = null;
				var url = "index.php?module="+$(this).wizardDialog('option','module')+"&action=index";
				setTimeout("zswitch_load_client_view('"+url+"');",100);
			}
		}

	});

}


//打开数据导出对话框
function zswitch_open_export_dlg(module)
{
	if(!$('#main_view_client').children().is('#titlebar_export_data_dlg'))
	{
		var html = '<div id="titlebar_export_data_dlg"></div>';
		$('#main_view_client').append(html);
	}

	$('#titlebar_export_data_dlg').data('module',module);
	$('#titlebar_export_data_dlg').dialog({
		autoOpen:true,
		height:500,
		width:600,
		modal:true,
		title:"数据导出向导",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"import_progress");
			var url ="index.php?module="+$(this).data("module")+"&action=export";
			$(this).load(url);
		},
		beforeClose:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},
		buttons: {
		"确定":function(){
			var step = $('#titlebar_export_data_dlg').data("step");
			if(step == "1")
			{
				zswitch_show_progressbar($(this),"dialog_progress_bar");
				var url = "index.php?module="+$(this).data("module")+"&action=export&step=2";
				$.post(url,$('#export_dialog_form').serialize(),function(result){
					$('#titlebar_export_data_dlg').html(result);
				});
			}
		},
		"取消":function(){
			$( this ).dialog( "close" );
		}
		}
	});
}

//批量删除对话框
function zswitch_listview_batch_delete_dlg(module)
{
	if(!$('#main_view_client').children().is('#listview_bar_batch_delete_dlg'))
	{
		var html = '<div id="listview_bar_batch_delete_dlg"></div>';
		$('#main_view_client').append(html);
	}

	$('#listview_bar_batch_delete_dlg').data('module',module);
	$('#listview_bar_batch_delete_dlg').dialog({
		autoOpen:true,
		height:200,
		width:500,
		modal:true,
		title:"批量删除",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"import_progress");
			var url ="index.php?module="+$(this).data("module")+"&action=batchDelete";
			$(this).load(url);
		},
		beforeClose:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},
		buttons: {
		"确定":function(){
			var step = $('#listview_bar_batch_delete_dlg').data("step");
			if(step == "1")
			{
				zswitch_show_progressbar($(this),"dialog_progress_bar");
				if($("#batch_delete_query_where").val() == "current_select")
				{
					$("#client_listview_table_form").find("[name^=selected_records]:checked").each(function(){
						if($("#batch_delete_select_recordid_list").val() == "")
						{
							$("#batch_delete_select_recordid_list").val($(this).val());
						}
						else
						{
							$("#batch_delete_select_recordid_list").val($("#batch_delete_select_recordid_list").val()+","+$(this).val());
						}
					});
				}
				var url = "index.php?module="+$(this).data("module")+"&action=batchDelete&step=2";

				$.post(url,$('#batch_delete_dialog_form').serialize(),function(result){
					$('#listview_bar_batch_delete_dlg').html(result);
				});
			}
			else if(step == "2")
			{
				var url = "index.php?module="+$(this).data("module")+"&action=batchDelete&step=3";

				$.post(url,function(result){
					$('#listview_bar_batch_delete_dlg').html(result);
				});
			}
		},
		"取消":function(){
			$( this ).dialog( "close" );
		}
		}
	});
}

//miss字段编辑对话框
function zswitch_open_missedit_dlg(module,field,recordid)
{
	if(!$('#main_view_client').children().is('#detailview_missedit_dlg'))
	{
		var html = '<div id="detailview_missedit_dlg"></div>';
		$('#main_view_client').append(html);
	}
	var vdiv = $(".detailview_value_contenter[id="+field+"]").parent();
	var ldiv = vdiv.prev();
	cw = vdiv.width()+ldiv.width();
	ch = vdiv.height();
	var dlgw = cw+50;
	var dlgh = ch+110;
	if(dlgh>500) dlgh=500;
	if(dlgw>900) dlgw=900;
	$('#detailview_missedit_dlg').data('module',module);
	$('#detailview_missedit_dlg').data('recordid',recordid);
	$('#detailview_missedit_dlg').data('field',field);
	$('#detailview_missedit_dlg').dialog({
		autoOpen:true,
		height:dlgh,
		width:dlgw,
		modal:true,
		title:"字段修改",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"dialog_progress_bar");
			var url ="index.php?module="+$(this).data("module")+"&action=missEdit&field="+$(this).data("field")+"&recordid="+$(this).data('recordid');
			$(this).load(url);
		},
		beforeClose:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},
		buttons: {
		"确定":function(){
			var url = "index.php?module="+$(this).data("module")+"&action=save";
			var info = zswitchui_validity_check("#form_missedit_view");
			if(info.length<=0)
			{
				zswitch_show_progressbar($(this),"dialog_progress_bar");
				$.post(url,$('#form_missedit_view').serialize(),function(result){
					$('#detailview_missedit_dlg').dialog("close");
					if(result.type=="rediect")
					{
						zswitch_load_client_view(result.data);
					}
				},'json');
			}
			else
			{
				info = "<span style='font-weight:bold;line-height:20px;'>输入字段值无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
				zswitch_open_messagebox("batch_modify_dlg_input_errorr","输入错误",info,400,400);
			}
		},
		"取消":function(){
			$( this ).dialog( "close" );
		}
		}
	});

}




//列表视图过滤编辑
function zswitch_listview_filter_modify_dlg(module)
{
	if(!$('#main_view_client').children().is('#listview_filter_modify_dlg'))
	{
		var html = '<div id="listview_filter_modify_dlg"></div>';
		$('#main_view_client').append(html);
	}
	$('#listview_filter_modify_dlg').data('module',module);
	$('#listview_filter_modify_dlg').dialog({
		autoOpen:true,
		height:500,
		width:600,
		modal:true,
		title:"列表视图过滤编辑",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"import_progress");
			var url ="index.php?module="+$(this).data("module")+"&action=modifyFilter";
			$(this).load(url);
		},
		beforeClose:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},
		buttons: {
		"关闭":function(){
			var url ="index.php?module="+$(this).data("module")+"&action=index";
			$( this ).dialog( "close" );
			zswitch_load_client_view(url,"client_listview_table_form");
		}
		}
	});
}

//批量修改对话框
function zswitch_listview_batch_modify_dlg(module)
{
	if(!$('#main_view_client').children().is('#listview_bar_batch_modify_dlg'))
	{
		var html = '<div id="listview_bar_batch_modify_dlg"></div>';
		$('#main_view_client').append(html);
	}

	$('#listview_bar_batch_modify_dlg').data('module',module);
	$('#listview_bar_batch_modify_dlg').dialog({
		autoOpen:true,
		height:500,
		width:600,
		modal:true,
		title:"批量修改",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"import_progress");
			var url ="index.php?module="+$(this).data("module")+"&action=batchModify";
			$(this).load(url);
		},
		beforeClose:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},
		buttons: {
		"确定":function(){
			var step = $('#listview_bar_batch_modify_dlg').data("step");
			if(step == "1")
			{

				if($("#batch_modify_query_where").val() == "current_select")
				{
					$("#client_listview_table_form").find("[name^=selected_records]:checked").each(function(){
						if($("#batch_modify_select_recordid_list").val() == "")
						{
							$("#batch_modify_select_recordid_list").val($(this).val());
						}
						else
						{
							$("#batch_modify_select_recordid_list").val($("#batch_modify_select_recordid_list").val()+","+$(this).val());
						}
					});
				}
				var url = "index.php?module="+$(this).data("module")+"&action=batchModify&step=2";
				var info = zswitchui_validity_check("#batch_modify_dialog_form");
				if(info.length<=0)
				{
					zswitch_show_progressbar($(this),"dialog_progress_bar");
					$.post(url,$('#batch_modify_dialog_form').serialize(),function(result){
						$('#listview_bar_batch_modify_dlg').html(result);
					});
				}
				else
				{
					info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
					zswitch_open_messagebox("batch_modify_dlg_input_errorr","输入错误",info,400,400);
				}

			}
			else if(step == "2")
			{
				var url = "index.php?module="+$(this).data("module")+"&action=batchModify&step=3";

				$.post(url,function(result){
					$('#listview_bar_batch_modify_dlg').html(result);
				});
			}


		},
		"取消":function(){
			$( this ).dialog( "close" );
		}
		}
	});
}


//增加预约对话框
function zswitch_add_appointment_popup_dlg(accid,module)
{
	if(!$('#main_view_client').children().is('#detail_add_appointment_popup_dlg'))
	{
		var html = '<div id="detail_add_appointment_popup_dlg"></div>';
		$('#main_view_client').append(html);
	}
	if(typeof(module)=="undefined") module = "AccountAppointment";
	$('#detail_add_appointment_popup_dlg').data('module',module);
	$('#detail_add_appointment_popup_dlg').data('accountid',accid);
	$('#detail_add_appointment_popup_dlg').dialog({
		autoOpen:true,
		height:170,
		width:500,
		modal:true,
		title:"添加预约记录",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"import_progress");
			var url ="index.php?module="+$('#detail_add_appointment_popup_dlg').data('module')+"&action=addAppointmentPopup&accountid="+$('#detail_add_appointment_popup_dlg').data('accountid');
			$(this).load(url);
		},
		beforeClose:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},
		buttons: {
		"确定":function(){
			var info = zswitchui_validity_check("#detailview_account_appointment_add_dlg_form");
			if(info.length<=0)
			{
				zswitch_show_progressbar($(this),"import_progress");
				var url = "index.php?module="+$('#detail_add_appointment_popup_dlg').data('module')+"&action=save";
				$.post(url,$("#detailview_account_appointment_add_dlg_form").serialize(),function(){
						$('#detail_add_appointment_popup_dlg').dialog("close");
				});
			}
			else
			{
				info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
				zswitch_open_messagebox("editview_input_errorr","输入错误",info,200,400);
			}

		},
		"取消":function(){
			$( this ).dialog( "close" );
		}
		}
	});
}



//增加跟踪记录对话框
function zswitch_add_track_popup_dlg(accid,module,accmodule)
{
	if(!$('#main_view_client').children().is('#detail_add_track_popup_dlg'))
	{
		var html = '<div id="detail_add_track_popup_dlg"></div>';
		$('#main_view_client').append(html);
	}
	if(typeof(module)=="undefined") module = "AccountTrack";
	$('#detail_add_track_popup_dlg').data('accountid',accid);
	$('#detail_add_track_popup_dlg').data('module',module);
	$('#detail_add_track_popup_dlg').data('accmodule',accmodule);

	$('#detail_add_track_popup_dlg').dialog({
		autoOpen:true,
		height:300,
		width:500,
		modal:true,
		title:"添加跟踪记录",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"import_progress");
			var url ="index.php?module="+$('#detail_add_track_popup_dlg').data('module')+"&action=addTrackPopup&accountid="+$('#detail_add_track_popup_dlg').data('accountid');
			$(this).load(url);
		},
		beforeClose:function(){
			var recordid = $('#detail_add_track_popup_dlg').data('accountid');
			var accmodule   = $('#detail_add_track_popup_dlg').data('accmodule');
			$(this).dialog("destroy");
			$(this).remove();
			zswitch_load_client_view("index.php?module="+accmodule+"&action=detailView&recordid="+recordid+"&return_module="+accmodule+"&return_action=index");
		},
		buttons: {
		"确定":function(){
			var info = zswitchui_validity_check("#detailview_account_track_add_dlg_form");
			if(info.length<=0)
			{
				zswitch_show_progressbar($(this),"import_progress");
				var url = "index.php?module="+$('#detail_add_track_popup_dlg').data('module')+"&action=save";
				$.post(url,$("#detailview_account_track_add_dlg_form").serialize(),function(){
						$('#detail_add_track_popup_dlg').dialog("close");
				});
			}
			else
			{
				info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
				zswitch_open_messagebox("editview_input_errorr","输入错误",info,200,400);
			}

		},
		"取消":function(){
			$( this ).dialog( "close" );
		}
		}
	});
}

//消息接收弹出框
function zswitch_recieve_message_dlg(ids){
	if(!$('#main_view_client').children().is('#recieve_message_dlg'))
	{
		var html = '<div id="recieve_message_dlg"></div>';
		$('#main_view_client').append(html);
	}
	//$('#recieve_message_dlg').data('accountid',accid);
	$('#recieve_message_dlg').dialog({
		autoOpen:true,
		height:270,
		width:500,
		modal:true,
		title:"通知消息",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"import_progress");
			var url ="index.php?module=Notices&action=showTimelyNotice&ids="+ids;
			$(this).load(url);
		},
		beforeClose:function(){
			$(this).dialog("destroy");
			$(this).remove();
		}
	});
}
//获取消息
function zswitch_get_message(){
	$.ajax({
	  method: "GET",
	  url: "index.php?module=Notices&action=getTimelyNotice",
	  dataType:"json",
	  success:function(msg){
		 if(msg['type']=='success'){
			 zswitch_recieve_message_dlg(msg['data']);
		 }
	  }
	});
}
//及时消息定时器
window.setInterval("zswitch_get_message()",10000);

//检查用户活动
function zswitch_check_user_activity(timeout){
	$.get("index.php?module=User&action=checkActivity",
	function(respone){
		if((respone.type!="success") || (respone.type=="success" && respone.data.state=="extrusion")){
			msg = "你的账号于"+respone.data.activity_time
			msg += "在"+respone.data.client_address+"登录,你将被强制退出.\r\n"
			msg += "如有疑问,请联系管理员."
			alert(msg)
			window.location.replace("index.php?module=User&action=logout");
		}
		else{
			window.setTimeout("zswitch_check_user_activity("+timeout+")",timeout*1000)
		}
		
	},"json"
	
	)
	
}