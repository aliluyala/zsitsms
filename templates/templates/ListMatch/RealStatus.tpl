{include file="TitleBar.tpl"}


<div class="client_listview_bar small">
	<table border="0" cellspacing="0"  width="100%">
		<tr>
			
			<td style="text-align:left;">			
				<button id="list_match_list_view_button" class="client_listview_button small" title="列表显示" action="list_view">列表显示</button>
			</td>
			<td style="text-align:right;padding-right:40px;">
				任务数量：<span id="list_match_task_count"></span> | 停止：<span id="list_match_task_stop_count"></span> | 
				等待中：<span id="list_match_task_wait_count"></span> | 执行中：<span id="list_match_task_execute_count"></span> | 完成：<span id="list_match_task_complete_count"></span>  
			</td>	
		</tr>
	</table>
</div>


<div id= "list_match_show_box" style="margin:3px 5px 3px 5px; border-top:2px solid #79b7e7; ">
	<div  style="border:2px solid #79b7e7;padding:5px;margin-top:3px;border-radius:15px;font-size:12px;color:#ffffff;font-weight:bold;background-color:#778899;">
		<div style="border-bottom:1px solid #79b7e7;">
			<table style="width:100%;">
				<tr style="height:25px;">
					<td style="width:80px;text-align:right;">任务名称：</td><td id="match_task_name"  style="width:300px;text-align:left;text-decoration: underline;"></td>
					<td  style="width:80px;text-align:right;">名单批次：</td><td id="match_task_batch" style="width:100px;text-align:left;text-decoration: underline;"></td>
					<td style="width:50px;text-align:right;">状态：</td><td id="match_task_state" style="width:50px;text-align:left;text-decoration: underline;"></td>
					<td style="text-align:left;">
					   <a id="match_task_stop_button" href="javascript:void(0);"  title="停止"><img src="{$IMAGES}/stop_2.png" style="width:24px;height:24px;"/></a>
					   <a id="match_task_start_button" href="javascript:void(0);" title="启动" style="display:none;"><img src="{$IMAGES}/play.png" style="width:24px;height:24px;"/></a>
					</td>
				</tr>		
			</table>
		</div>
		<div style="border-bottom:1px solid #79b7e7;">
			<table style="width:100%">
				<tr style="height:25px;">
					<td style="width:80px;text-align:right;">运行标志：</td><td id="match_task_tag" style="width:80px;text-align:left;text-decoration: underline;"></td>
					<td style="width:80px;text-align:right;">最近执行：</td><td id="match_task_last_time" style="width:150px;text-align:left;text-decoration: underline;"></td>
					<td style="width:80px;text-align:right;">提交完成：</td><td id="match_task_request_complete" style="width:150px;text-align:left;text-decoration: underline;"></td>
				</tr>
				<tr style="height:25px;">	
					<td style="width:80px;text-align:right;">提交数量：</td><td id="match_task_request_count"  style="width:150px;text-align:left;text-decoration: underline;"></td>
					<td style="width:80px;text-align:right;">完成数量：</td><td id="match_task_complete_count" style="width:150px;text-align:left;text-decoration: underline;"></td>
					<td style="width:80px;text-align:right;">成功数量：</td><td id="match_task_success_count"  style="width:150px;text-align:left;text-decoration: underline;"></td>
					<td style="width:80px;text-align:right;">失败数量：</td><td id="match_task_failure_count"   style="width:150px;text-align:left;text-decoration: underline;"></td>
					<td ></td>
				</tr>
	
			</table>
		</div>
		<table style="width:100%">
			<tr style="height:25px;">	
				<td style="width:80px;text-align:right;">错误信息：</td><td id="match_task_error_info"   style="text-align:left;text-decoration: underline;"></td>
			</tr>		
		</table>
	<div>
</div>

<script>
$("#list_match_list_view_button").button().click(function(){
	zswitch_load_client_view("index.php?module=ListMatch&action=index");
});


function list_match_real_status_refresh()
{
	var url = "index.php?module=ListMatch&action=realStatusData&oper=query";
	var boxobj = $("#list_match_show_box");
	var task_count = 0;
	var stop_count = 0;
	var wait_count = 0;
	var execute_count = 0;
	var complete_count = 0;
	
	if(!boxobj.is("div")) return ;
	$.getJSON(url,function(result){
		for(idx=0;idx<result.data.length;idx++)
		{
			task_count++;
			var recordid = result.data[idx].id;
			
			var rowobj = null;

			if(boxobj.children().is("[recordid='"+recordid+"']"))
			{
				rowobj = boxobj.children("[recordid='"+recordid+"']");
			}
			else
			{
				if(boxobj.children(":first").is("[recordid]"))
				{
					rowobj = boxobj.children(":first").clone();
					rowobj.appendTo(boxobj);					
				}
				else
				{
					rowobj = boxobj.children(":first");
					
				}
				rowobj.attr("recordid",recordid);
				rowobj.find("#match_task_stop_button").attr("recordid",recordid)
				                                      .click(function(){
					var recordid = $(this).attr("recordid");
					var url = "index.php?module=ListMatch&action=realStatusData&oper=stop&recordid="+recordid;
					$.get(url);														
				});
				rowobj.find("#match_task_start_button").attr("recordid",recordid)
												       .click(function(){
					var recordid = $(this).attr("recordid");
					var url = "index.php?module=ListMatch&action=realStatusData&oper=run&recordid="+recordid;
					$.get(url);	
				});
			}			
			rowobj.find("#match_task_name").html(result.data[idx].name);
			rowobj.find("#match_task_batch").html(result.data[idx].batch);
			if(result.data[idx].state=="RUNING")
			{
				rowobj.find("#match_task_state").html("启动");
				rowobj.find("#match_task_stop_button").show();
				rowobj.find("#match_task_start_button").hide();
			}
			else
			{
				stop_count++;
				rowobj.find("#match_task_state").html("停止");
				rowobj.find("#match_task_stop_button").hide();
				rowobj.find("#match_task_start_button").show();
			}
			if(result.data[idx].tag == "EXECUTING")
			{
				execute_count++;
				rowobj.find("#match_task_tag").html("执行中");
			}
			else if(result.data[idx].tag == "WAITING")
			{
				wait_count++;
				rowobj.find("#match_task_tag").html("等待中");
			}
			else if(result.data[idx].tag == "COMPLETE")
			{
				complete_count++;
				rowobj.find("#match_task_tag").html("完成");
			} 
			rowobj.find("#match_task_last_time").html(result.data[idx].last_time);
			rowobj.find("#match_task_request_count" ).html(result.data[idx].request_count );
			rowobj.find("#match_task_complete_count").html(result.data[idx].complete_count);
			rowobj.find("#match_task_success_count" ).html(result.data[idx].success_count );
			rowobj.find("#match_task_failure_count"  ).html(result.data[idx].failure_count  );
			if(result.data[idx].request_complete == "YES")
			{
				rowobj.find("#match_task_request_complete"  ).html("是");
			}	
			else
			{
				rowobj.find("#match_task_request_complete"  ).html("否");
			}
			rowobj.find("#match_task_error_info"  ).html(result.data[idx].error_info  );
		}
		$("#list_match_task_count").html(task_count);
		$("#list_match_task_stop_count").html(stop_count);
		$("#list_match_task_wait_count").html(wait_count);
		$("#list_match_task_execute_count").html(execute_count);
		$("#list_match_task_complete_count").html(complete_count);
	});
	
	
	setTimeout('list_match_real_status_refresh()',1000);
}


list_match_real_status_refresh();

</script>