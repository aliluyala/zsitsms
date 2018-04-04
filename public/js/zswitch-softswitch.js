//座席电话控制对角
function agentctrl(cbfun)
{
	this.cbfun = cbfun;
	this.state = "Waiting";
	this.uuid = "";
	this.agent_number = "";
	$("body").data("agent_ctrl_obj",this);
	this.callout = function(number){
		var url = "index.php?module=AgentState&action=callout&number="+number;
		$.get(url);
	};
	this.transfer = function(){
		var url = "index.php?module=AgentState&action=transfer";
	};
	this.hangup = function(){
		var url = "index.php?module=AgentState&action=hangup";
	};
	this.checkCallEvent = function(){
		var d = new Date();
		var url = "webservices/callcenterAgentState.php?agent="+$("body").data("agent_ctrl_obj").agent_number+"&time="+d.getTime();
		//alert(url);
		$.get(url,function(result){
			//alert(result.type+result.data.state);
			if(result.type == 0 )
			{
				if(result.data.status=="Available")
				{
					//$("#agent_phone_panel_agent_status").val("ONLINE");
					$("#main_view_header_agent_status_img_online").show();
					$("#main_view_header_agent_status_img_onoff").hide();
				}
				else
				{
					//$("#agent_phone_panel_agent_status").val("OFFLINE");
					$("#main_view_header_agent_status_img_online").hide();
					$("#main_view_header_agent_status_img_onoff").show();
				}
			}

			if(result.type == 0 && result.data.state != $("body").data("agent_ctrl_obj").state)
			{
				var url = "index.php?module=AgentState&action=checkCallEvent"+"&time="+d.getTime();
				$.get(url,function(result){
					if(result.type == 0)
					{
						$("body").data("agent_ctrl_obj").state = result.data.state;
						$("body").data("agent_ctrl_obj").uuid = result.data.uuid;
						$("body").data("agent_ctrl_obj").cbfun(result.data.state,result.data.agent,result.data.othernumber,result.data.accountdata);
					}
				},"json");
			}

		},"json");
	};
}

//座席电话面板对象
function agentPanel(container)
{
	//var dt = new Date();
	var id = "main_agent_penel_dialog_box";
	var html = '<div id="'+id+'"></div>';
	$(container).append(html);
	this.dlgobj = $("#"+id);
	$('body').data('agent_panel_dlgobj',this);
	this.dlgobj.dialog({
		autoOpen:false,
		show: {
			effect: "blind",
			duration: 300
		},
        hide: {
			effect: "blind",
			duration: 300
		},
		close:function(){
				$('body').data('agent_panel_dlgobj').isshow = false;
			},
		height:330,
		width:250,
		modal:false,
		title:"座席电话面板",
		appendTo: "body",
		dialogClass:"dialog_default_class",
		create:function(){
			zswitch_show_progressbar($(this),"main_view_header_agent_panel_progress");
			var url ="index.php?module=AgentState&action=loadAgentCtrlPanel";
			$(this).load(url);
		}
	});

	this.isshow = false;
	this.show = function(){
			if(!this.isshow)
			{
				this.dlgobj.dialog("open");
				this.isshow = true;
			}
		};
	this.hide = function(){
			if(this.isshow)
			{
				this.dlgobj.dialog("close");
				this.isshow = false;
			}
		};
	this.moveToObj = function(selecter){
			this.dlgobj.dialog("option", "position", { my: "right top", at: "right bottom", of: selecter } );
		};
	this.callout = function(number){
			this.agentctrlobj.callout(number);
		};
	this.callback = function(state,agent,othernumber,accountdata){
			var dlg = $('body').data('agent_panel_dlgobj');
			if($("body").data("agent_panel_agent_popup") == "YES")
			{
				dlg.show();
			}
			var numinput = dlg.dlgobj.find("#agent_panel_number_input");
			var callinfo = dlg.dlgobj.find("#agent_panel_call_info");
			var phonebut = dlg.dlgobj.find("#agent_phone_button_panel");
			var accinfo = dlg.dlgobj.find("#agent_phone_panel_account_info");
			if(state == "callin_ringing" || state == "callout_ringing")
			{
				numinput.hide();
				callinfo.show();
				if(state == "callin_ringing")
				{
					callinfo.html(othernumber+" 来电等待接听");
				}
				else
				{
					callinfo.html(othernumber+" 去电等待接听");
				}
				phonebut.hide();
				accinfo.show();
				$("#agent_phone_panel_button_callout").hide();
				$("#agent_phone_panel_button_hangup").show();
				$("#agent_phone_panel_button_transfer").hide();
				$("#agent_phone_panel_button_clear").hide();
				$("#agent_phone_panel_button_hidepanel").hide();
				$("#agent_phone_panel_button_showpanel").hide();
				var achtml = "";
				for(x in accountdata)
				{

					achtml += x + ":</br>"
					for(i in accountdata[x])
					{
						achtml += " <a href=\""+accountdata[x][i]["url"]+"\" style='color:#1E90FF;'>"+accountdata[x][i]["label"]+"</a></br>";
					}
				}
				accinfo.html(achtml);
			}
			else if(state == "callin_talking"  || state == "callout_talking" )
			{
				numinput.hide();
				callinfo.show();
				callinfo.html(othernumber+" 通话中");
				phonebut.hide();
				accinfo.show();
				$("#agent_phone_panel_button_callout").hide();
				$("#agent_phone_panel_button_hangup").show();
				$("#agent_phone_panel_button_transfer").show();
				$("#agent_phone_panel_button_clear").hide();
				$("#agent_phone_panel_button_hidepanel").hide();
				$("#agent_phone_panel_button_showpanel").show();
			}
			else if(state == "Waiting")
			{
				numinput.show();
				numinput.val("");
				callinfo.hide();
				callinfo.html("");
				phonebut.show();
				accinfo.hide();
				accinfo.html("");
				$("#agent_phone_panel_button_callout").show();
				$("#agent_phone_panel_button_hangup").hide();
				$("#agent_phone_panel_button_transfer").hide();
				$("#agent_phone_panel_button_clear").show();
				$("#agent_phone_panel_button_hidepanel").hide();
				$("#agent_phone_panel_button_showpanel").hide();
			}

		};
	this.agentctrlobj = new agentctrl(this.callback);
	self.setInterval("$('body').data('agent_panel_dlgobj').agentctrlobj.checkCallEvent();",3000);
}
//座席详单录音播放
function zswitch_callcenter_agent_recordfile(recordid)
{
	window.open("index.php?module=AgentCDR&action=playRecordView&recordid="+recordid,"zswitchcrm_play_record","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=600, height=150");
}
/*function zswitch_callcenter_agent_recordfile(recordid)
{

	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"></div>';
	$("#main_view_client").append(html);
	dlgobj = $("#"+id);
	dlgobj.data("recordid",recordid);
	dlgobj.dialog({
		autoOpen:true,
		close:function(){
			var wmplayerem =  document.getElementById("wmplayer");
			if(wmplayerem && wmplayerem != "undefined")
			{
				wmplayerem.controls.stop();
			}
			var audioem = document.getElementById("agentcdr_listview_audio");
			if(audioem && audioem != "undefined")
			{
				audioem.src="";
				audioem.pause();
				audioem.currentTime = 0;
			}
			$(this).dialog("destroy");
			$(this).remove();
		},

		height:150,
		width:450,
		modal:true,
		title:"通话录音",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			var audioem = document.getElementById("agentcdr_listview_audio");

			zswitch_show_progressbar($(this),"main_view_header_agent_panel_progress");
			var url ="index.php?module=AgentCDR&action=playRecordView&recordid="+$(this).data("recordid");
			$(this).load(url);
		}
	});
}
*/

//呼叫详单录音播放
function zswitch_listview_zswitch_recordfile(recordid)
{
	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"></div>';
	$("#main_view_client").append(html);
	dlgobj = $("#"+id);
	dlgobj.data("recordid",recordid);
	dlgobj.dialog({
		autoOpen:true,
		close:function(){
			if(typeof(wmplayer) != "undefined")
			{
				wmplayer.controls.stop();
			}
			$(this).dialog("destroy");
			$(this).remove();
		},

		height:150,
		width:450,
		modal:true,
		title:"通话录音",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			zswitch_show_progressbar($(this),"main_view_header_agent_panel_progress");
			var url ="index.php?module=ZSwitchManager&action=playRecordView&recordid="+$(this).data("recordid");
			$(this).load(url);
		}
	});
}


function zswitch_callcenter_agent_spy(recordid)
{
	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"></div>';
	$("#main_view_client").append(html);
	dlgobj = $("#"+id);
	dlgobj.data("recordid",recordid);
	dlgobj.dialog({
		autoOpen:true,
		close:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},

		height:200,
		width:250,
		modal:true,
		title:"监听座席",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			var html = '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><span id="agent_spy_dialog_info">';
			html += '请在听到你的座席电话振铃后，摘机开始监听！<br/>点击“关闭”停止监听</span></p></div>';
			$(this).html(html);
			var url ="index.php?module=AgentState&action=spy&recordid="+$(this).data("recordid");
			$.get(url,function(result){

				if(result.type != 0)
				{
					$("#agent_spy_dialog_info").html("监听起动失败！");
				}
			},"json");
			//$(this).load(url);
		},
		buttons: {
			"关闭":function(){
					var url ="index.php?module=AgentState&action=hangup";
					$.get(url);
					$(this).dialog( "close" );
				}
		}
	});
}



function zswitch_callcenter_agent_hangup(recordid)
{
	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"></div>';
	$("#main_view_client").append(html);
	dlgobj = $("#"+id);
	dlgobj.data("recordid",recordid);
	dlgobj.dialog({
		autoOpen:true,
		close:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},

		height:200,
		width:250,
		modal:true,
		title:"挂断座席",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			var html = '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
			html += '你确定要挂断此座席的通话吗？<br/>点击“确定”挂断，点击“取消”忽略。</p></div>';
			$(this).html(html);
			//$(this).load(url);
		},
		buttons: {
			"确定":function(){
				var url ="index.php?module=AgentState&action=hangup&recordid="+$(this).data("recordid");
				$.get(url,function(result){
					if(result.type==0)
					{
						setTimeout("zswitch_load_client_view('index.php?module=AgentState&action=index');",500);
					}
				},"json");
				$(this).dialog( "close" );
			},
			"取消":function(){$(this).dialog( "close" );}
		}
	});
}

//队列成员挂机
function zswitch_callcenter_member_hangup(recordid)
{
	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"></div>';
	$("#main_view_client").append(html);
	dlgobj = $("#"+id);
	dlgobj.data("recordid",recordid);
	dlgobj.dialog({
		autoOpen:true,
		close:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},

		height:200,
		width:250,
		modal:true,
		title:"挂断成员",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			var html = '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
			html += '你确定要挂断此成员的通话吗？<br/>点击“确定”挂断，点击“取消”忽略。</p></div>';
			$(this).html(html);
			//$(this).load(url);
		},
		buttons: {
			"确定":function(){
				var url ="index.php?module=MemberState&action=hangup&recordid="+$(this).data("recordid");
				$.get(url,function(result){
					if(result.type==0)
					{
						setTimeout("zswitch_load_client_view('index.php?module=MemberState&action=index');",500);
					}
				},"json");
				$(this).dialog( "close" );
			},
			"取消":function(){$(this).dialog( "close" );}
		}
	});
}
//队列成员监听
function zswitch_callcenter_member_spy(recordid)
{
	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"></div>';
	$("#main_view_client").append(html);
	dlgobj = $("#"+id);
	dlgobj.data("recordid",recordid);
	dlgobj.dialog({
		autoOpen:true,
		close:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},

		height:200,
		width:250,
		modal:true,
		title:"监听队列成员",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			var html = '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><span id="agent_spy_dialog_info">';
			html += '请在听到你的座席电话振铃后，摘机开始监听！<br/>点击“关闭”停止监听</span></p></div>';
			$(this).html(html);
			var url ="index.php?module=MemberState&action=spy&recordid="+$(this).data("recordid");
			$.get(url,function(result){
				if(result.type != 0)
				{
					$("#agent_spy_dialog_info").html("监听起动失败！");
				}

			},"json");
			//$(this).load(url);
		},
		buttons: {
			"关闭":function(){
					var url ="index.php?module=AgentState&action=hangup";
					$.get(url);
					$(this).dialog( "close" );
				}
		}
	});
}

//座席激活
function zswitch_callcenter_agent_active(recordid)
{
	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"></div>';
	$("#main_view_client").append(html);
	dlgobj = $("#"+id);
	dlgobj.data("recordid",recordid);
	dlgobj.dialog({
		autoOpen:true,
		close:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},

		height:200,
		width:250,
		modal:true,
		title:"激活座席",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			var html = '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
			html += '你确定要激活此座席吗？<br/>点击“确定”激活，点击“取消”忽略。</p></div>';
			$(this).html(html);
			//$(this).load(url);
		},
		buttons: {
			"确定":function(){
				var url ="index.php?module=AgentState&action=activeAgent&recordid="+$(this).data("recordid");
				$.get(url,function(result){
					if(result.type==0)
					{
						setTimeout("zswitch_load_client_view('index.php?module=AgentState&action=index');",500);
					}
				},"json");
				$(this).dialog( "close" );
			},
			"取消":function(){$(this).dialog( "close" );}
		}
	});
}


//座席阻塞
function zswitch_callcenter_agent_break(recordid)
{
	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"></div>';
	$("#main_view_client").append(html);
	dlgobj = $("#"+id);
	dlgobj.data("recordid",recordid);
	dlgobj.dialog({
		autoOpen:true,
		close:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},

		height:200,
		width:250,
		modal:true,
		title:"阻塞座席",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			var html = '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
			html += '你确定要阻塞此座席吗？<br/>点击“确定”激活，点击“取消”忽略。</p></div>';
			$(this).html(html);
			//$(this).load(url);
		},
		buttons: {
			"确定":function(){
				var url ="index.php?module=AgentState&action=breakAgent&recordid="+$(this).data("recordid");
				$.get(url,function(result){
					if(result.type==0)
					{
						setTimeout("zswitch_load_client_view('index.php?module=AgentState&action=index');",500);
					}
				},"json");
				$(this).dialog( "close" );
			},
			"取消":function(){$(this).dialog( "close" );}
		}
	});
}

//座席踢出
function zswitch_callcenter_agent_kill(recordid)
{
	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"></div>';
	$("#main_view_client").append(html);
	dlgobj = $("#"+id);
	dlgobj.data("recordid",recordid);
	dlgobj.dialog({
		autoOpen:true,
		close:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},
		height:200,
		width:250,
		modal:true,
		title:"踢出座席",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			var html = '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
			html += '你确定要踢出此座席吗？<br/>点击“确定”踢出，点击“取消”忽略。</p></div>';
			$(this).html(html);
			//$(this).load(url);
		},
		buttons: {
			"确定":function(){
				var url ="index.php?module=AgentState&action=killAgent&recordid="+$(this).data("recordid");
				$.get(url,function(result){
					if(result.type==0)
					{
						setTimeout("zswitch_load_client_view('index.php?module=AgentState&action=index');",500);
					}
				},"json");
				$(this).dialog( "close" );
			},
			"取消":function(){$(this).dialog( "close" );}
		}
	});
}

//座席转接
function zswitch_callcenter_agent_transfer_dlg()
{
	var dt = new Date();
	var id = $.md5("a"+dt.getTime());
	var html = '<div id="'+id+'"></div>';
	$("#main_view_client").append(html);
	dlgobj = $("#"+id);

	dlgobj.dialog({
		autoOpen:true,
		close:function(){
			$(this).dialog("destroy");
			$(this).remove();
		},
		height:200,
		width:250,
		modal:true,
		title:"座转接接",
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			var html = '<div style="height:30px;line-height:30px;">';
			html += '<label for="transferNumber">号码：</label><input id="transferNumber" type="text" value="" style="width:160px;"/>';
			html += '<br/>点击“确定”转接，点击“取消”忽略。</div>';
			$(this).html(html);
			//$(this).load(url);
		},
		buttons: {
			"确定":function(){
				var url ="index.php?module=AgentState&action=transfer&number="+$(this).find("#transferNumber").val();
				$.get(url);
				$(this).dialog( "close" );
			},
			"取消":function(){$(this).dialog( "close" );}
		}
	});
}

function zswitch_callcenter_click_call_b(number)
{
	window.location.href = "sip:" + number;
}
//点击呼叫
function zswitch_callcenter_click_call(number)
{
	//window.location.href = "sip:" + number;
	var d = new Date();
	var url = "index.php?module=AgentState&action=callout&number="+number+"&time="+d.getTime();
	$.get(url,function(result){
		if(result.type != 0)
		{
			var dt = new Date();
			var id = $.md5("a"+dt.getTime());
			var html = '<div id="'+id+'"></div>';
			$("#main_view_client").append(html);
			dlgobj = $("#"+id);
			dlgobj.dialog({
				autoOpen:true,
				close:function(){
					$(this).dialog("destroy");
					$(this).remove();
				},
				height:160,
				width:350,
				modal:true,
				title:"呼叫失败",
				appendTo: "#main_view_client",
				dialogClass:"dialog_default_class",
				open:function(){
					var html = '<div class="ui-widget" style="font-size:12px">'
					html += '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;text-align:left">';
					html += '<p><span class="ui-icon ui-icon-info" style="float:left; margin-right:.3em;"></span>';
					html += '呼叫失败！请确认座席空闭、号码正确后重试。</p></div></div>';
					$(this).html(html);
				},
				buttons: {
					"确定":function(){$(this).dialog( "close" );}
				}
			});
		}
	}
	,'json'
	);
}

//点击呼叫,支持号码隐藏
function zswitch_callcenter_click_call_a(module,recordid,field,hideNumber)
{
	var d = new Date();
	var url = "index.php?module=AgentState&action=clickCall&cmodule="+module+"&crecordid="+recordid+"&cfield="+field+"&chideNumber="+hideNumber+"&time="+d.getTime();
	$.get(url,function(result){
		if(result.type != 0)
		{
			var dt = new Date();
			var id = $.md5("a"+dt.getTime());
			var html = '<div id="'+id+'"></div>';
			$("#main_view_client").append(html);
			dlgobj = $("#"+id);
			dlgobj.dialog({
				autoOpen:true,
				close:function(){
					$(this).dialog("destroy");
					$(this).remove();
				},
				height:160,
				width:350,
				modal:true,
				title:"呼叫失败",
				appendTo: "#main_view_client",
				dialogClass:"dialog_default_class",
				open:function(){
					var html = '<div class="ui-widget" style="font-size:12px">'
					html += '<div class="ui-state-error ui-corner-all" style="padding: 0.7em;text-align:left">';
					html += '<p><span class="ui-icon ui-icon-info" style="float:left; margin-right:.3em;"></span>';
					html += '呼叫失败！请确认座席空闭、号码正确后重试。</p></div></div>';
					$(this).html(html);
				},
				buttons: {
					"确定":function(){$(this).dialog( "close" );}
				}
			});
		}
	}
	,'json'
	);
}



