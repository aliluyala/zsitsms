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
			状态：
			<select id="agent_phone_panel_agent_status">
				<option value="OFFLINE" {if $AGENT_STATUS eq 'OFFLINE'}selected="selected"{/if}>离线</option>
				<option value="ONLINE" {if $AGENT_STATUS eq 'ONLINE'}selected="selected"{/if}>在线</option>
			</select>
			</div>
		</td>
	</tr>
</table>
<script>
	var agent_panel_popup = "{$AGENT_POPUP}";
{literal}
	$("body").data("agent_panel_agent_popup",agent_panel_popup);
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
			var url = "index.php?module=ZSwitchManager&action=sendDTMF&char="+$(this).text();
			url += "&uuid=" + $("body").data("agent_ctrl_obj").uuid;
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
		if(action == "callout")
		{
			var url = "index.php?module=ZSwitchManager&action=callout&number="+$("#agent_panel_number_input").val();
			$.get(url);
			$('body').data('agent_panel_dlgobj').callback("callout_ringing","",$("#agent_panel_number_input").val(),null);
		}
		else if(action == "clear")
		{
			var num = $("#agent_panel_number_input").val();
			$("#agent_panel_number_input").val(num.substr(0,num.length-1));
		}
		else if(action == "hangup")
		{
			var url = "index.php?module=ZSwitchManager&action=hangup";
			url += "&uuid=" + $("body").data("agent_ctrl_obj").uuid;			
			$('body').data('agent_panel_dlgobj').callback("hangup","","",null);
		}
		else if(action == "transfer")
		{
						
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
	
	$("#agent_phone_panel_agent_status").change(function(){
		var url = "index.php?module=ZSwitchManager&action=changeStatus&status="+$(this).val();
		$.get(url);
	});
	
{/literal}
</script>