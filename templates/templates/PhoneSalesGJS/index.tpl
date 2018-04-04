{include file="TitleBar.tpl"}
<table border="0px" style="width:100%;font-size:12px;vertical-align:top;">
	<tr>
		<td style="vertical-align:top;">
			<div id="" style="min-height:350px;">
				<div style="margin-top:150px;">
					<a id="phone_sales_start_button"  href="javascript:void(0);" title="点击开始">
						<img src="{$IMAGES}/start_arrow.png" width="80px" height="80px"
							style="border:1px solid #79b7e7;border-radius:5px;-moz-border-radius:5px;"/>
					</a>
					<a id="phone_sales_proccess" href="javascript:void(0);" title="点击停止" style="display:none">
						<img src="{$IMAGES}/progress1.gif" width="80px" height="80px"
							style="border:1px solid #79b7e7;border-radius:5px;-moz-border-radius:5px;"/>
					</a>		
				</div>
				<div id="phone_sales_title_info" style="margin:5px 0px 20px 0px;font-size:20px;color:#000080;font-weight:bold;">点击开始</div>
				
				<div id="phone_sales_title_help" style="margin:5px 0px 20px 0px;font-size:14px;color:#555555;">
					点击开始自动搜索客户，并将有效的客户电话转接到你的座席。<br/>请保持座席电话空闭！</div>	
			</div>
			
		</td>

	</tr>
</table>
<script type="text/javascript">
	var module = "{$MODULE}";
	{literal}
		function phoneSalesGetNumberStats()
		{
			var url = "index.php?module=PhoneSalesGJS&action=getNumber&operation=status";
			$.get(url,function(result){
				if(result.type ==0)
				{	
					$("#phone_sales_start_button").hide();
					$("#phone_sales_proccess").show();				
					if(result.data.state == "answered")
					{
						var url = "index.php?module=PhoneSalesGJS&action=index&action=index&operation=popup&numberid="+result.data.numberid;
						zswitch_load_client_view(url)
					}
					else
					{
						$("#phone_sales_title_info").html("正在呼叫-"+result.data.number);	
						setTimeout("phoneSalesGetNumberStats()",1000);						
					}					
				}
				else
				{
					$("#phone_sales_title_info").html("点击开始");
					$("#phone_sales_proccess").hide();	
					$("#phone_sales_start_button").show();	
				}
			},"json")
		}
		$("#phone_sales_start_button").click(function(){
			var url="index.php?module=PhoneSalesGJS&action=getNumber&operation=start";
			$.get(url,function(result){
				if(result.type == 0)
				{
					$("#phone_sales_start_button").hide();
					$("#phone_sales_proccess").show();
					$("#phone_sales_title_info").html("系统处理中");	
					setTimeout("phoneSalesGetNumberStats();",3000);
				}	
				else
				{
					zswitch_open_messagebox("phone_sales_GJS_title_box","取号失败","取号失败!可能是已经没有号码，或系统问题！",200,500);					
				}	
			
			},"json");

		});
		$("#phone_sales_proccess").click(function(){
			$("#phone_sales_proccess").hide();
			$("#phone_sales_start_button").show();
			var url="index.php?module=PhoneSalesGJS&action=getNumber&operation=stop";
			$.get(url);
		});	
		setTimeout("phoneSalesGetNumberStats()",1000);	
	{/literal}
	
</script>

