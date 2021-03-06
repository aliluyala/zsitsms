{if $NO_AGENT}
	<div>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;你没有设置你的座席号码，座席电话面板不可用！</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;
		你可以在“个人设置”->"座席设置"->"座席号码"指定默认座席号码。或者在登录时指定座席号码。</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;请正确设置后重新登录！</p>
	</div>
	<script>
		$("#main_agent_penel_dialog_box").dialog("option","title","座席电话面板(不可用)");
		$("#main_view_header_agent_phone_button").attr("title","座席电话面板(不可用)");
	</script>
{else}
<table style="width:100%;height:100%">
	<tr style="height:40px">
		<td>
			<div id="agent_panel_call_info" ></div>
			<input id="agent_panel_number_input" type="text" />
		</td>
	</tr>
	<tr style="height:160px;"><td>
		<div id="agent_phone_panel_account_info" >
		
		</div>
		<table id="agent_phone_button_panel" class="phone_button_panel">
			<tr><td>1</td><td>2</td><td>3</td></tr>
			<tr><td>4</td><td>5</td><td>6</td></tr>
			<tr><td>7</td><td>8</td><td>9</td></tr>
			<tr><td>*</td><td>0</td><td>#</td></tr>
		</table></td>	
	</tr>
	<tr >
		<td>
			<button  id="agent_phone_panel_button_callout" class="agent_phone_panel_buttons" action="callout">呼叫</button>
			<button  id="agent_phone_panel_button_hangup" class="agent_phone_panel_buttons" action="hangup" style="display:none">挂断</button>
			<button  id="agent_phone_panel_button_transfer" class="agent_phone_panel_buttons" action="transfer" style="display:none">转接</button>
			<button  id="agent_phone_panel_button_showpanel" class="agent_phone_panel_buttons" action="showpanel" style="display:none">键盘</button>
			<button  id="agent_phone_panel_button_hidepanel" class="agent_phone_panel_buttons" action="hidepanel" style="display:none">客户信息</button>
			<button  id="agent_phone_panel_button_clear" class="agent_phone_panel_buttons" action="clear">清除</button>
			<div style="line-height:30px">

			</div>
		</td>
	</tr>
</table>
<script>
	var agent_panel_popup = "{$AGENT_POPUP}";
	var agent_panel_agnet_number = "{$AGENT_NUMBER}";
{literal}
	$("body").data("agent_panel_agent_popup",agent_panel_popup);
	$("#main_agent_penel_dialog_box").dialog("option","title","座席电话面板("+agent_panel_agnet_number+")");
	$("#main_view_header_agent_phone_button").attr("title","座席电话面板("+agent_panel_agnet_number+")");
	$("body").data("agent_ctrl_obj").agent_number = agent_panel_agnet_number;
	$("#agent_phone_button_panel").find("td").hover(function(){
			$(this).css("cursor","pointer");	
		},
		function(){	
			$(this).css("cursor","default");
			$(this).css("background-color","#FFFFFF");		
		}
	).mousedown(function(){
		$(this).css("background-color","#E0FFFF");	
	}).mouseup(function(){
		$(this).css("background-color","#FFFFFF");	
	}).click(function(){
		if($("body").data("agent_ctrl_obj").agent_status == "talk")
		{
			var url = "index.php?module=AgentState&action=sendDTMF&char="+$(this).text();			
			$.get(url);
		}
		else
		{
			var oldtxt = $("#agent_panel_number_input").val();
			$("#agent_panel_number_input").val(oldtxt+$(this).text());
		}	
	});	
	$(".agent_phone_panel_buttons").button().click(function(){
		var action = $(this).attr("action");
		var d = new Date();
		if(action == "callout")
		{
			var number = $("#agent_panel_number_input").val();			
			var url = "index.php?module=AgentState&action=callout&number="+$("#agent_panel_number_input").val()+"&time="+d.getTime();
			$.get(url,function(result){
				if(result.type != 0)
				{
					$('body').data('agent_panel_dlgobj').callback("Waiting","","",null);
				}				
			}
			,'json'
			);
			$('body').data('agent_panel_dlgobj').callback("callout_ringing","",$("#agent_panel_number_input").val(),null);
		}
		else if(action == "clear")
		{
			var num = $("#agent_panel_number_input").val();
			$("#agent_panel_number_input").val(num.substr(0,num.length-1));
		}
		else if(action == "hangup")
		{
			var url = "index.php?module=AgentState&action=hangup"+"&time="+d.getTime();

			$.get(url,function(result){
							
			},'json');

		}
		else if(action == "transfer")
		{
			zswitch_callcenter_agent_transfer_dlg();	
		}
		else if(action == "showpanel")
		{
			$("#agent_phone_panel_account_info").hide();
			$("#agent_phone_button_panel").show();
			$(this).hide();
			$("#agent_phone_panel_button_hidepanel").show();		
		}
		else if(action == "hidepanel")
		{
			$("#agent_phone_panel_account_info").show();
			$("#agent_phone_button_panel").hide();
			$(this).hide();
			$("#agent_phone_panel_button_showpanel").show();		
		}		
	});
	/*
	$("#agent_phone_panel_agent_status").change(function(){
		var status = $(this).val();
		if(status == "ONLINE")
		{
			$.get("index.php?module=AgentState&action=loginAgent");
		}
		else
		{
			$.get("index.php?module=AgentState&action=logoutAgent");
		}
	
	});
	*/
	window.onbeforeunload   =   function(event)
	{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}	
			xmlhttp.open("GET","index.php?module=AgentState&action=logoutAgent",false);
			xmlhttp.send();
				
 	}


	
{/literal}
</script>
{/if}