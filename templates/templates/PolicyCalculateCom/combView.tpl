<table border=0 width="100%">
<tr>
<td style="width:130px;vertical-align:top;border:1px solid #79b7e7;text-align:left;">
<div style="font-size:13px;padding-left:10px;height:30px;line-height:30px;background-color:#79b7e7;color:#FFFFFF;font-weight: bold;">
	<span>历史算价</span>
<a id="policy_calculate_create" title="新建..." href="javascript:void(0);" style="border-radius: 3px;margin-left:60px;padding:0px 4px 0px 4px;font-size:20px;background-color:#FFFFFF;font-weight: bold;">
+
</a>
</div>
<div id="policy_calculate_list_box">
{*
{foreach $POLICY_CALCULATE_LIST as $row}
	<div recordid = "{$row.ID}" title="{$row.SUMMARY}" style="padding:2px;border-bottom:1px solid #EDEDED;"> 
		<span style="font-size:12px;">{$row.CAL_NO}</span><br/>	 
		<span style="font-size:10px;color:#ACACAC;">{$row.MODIFY_TIME}</span>
	</div>
{/foreach}
*}
</div>

</td>

<td  id="policy_calculate_content"  style="vertical-align:top;border:1px solid #79b7e7;">

</td>
</tr>

</table>

<script>
var policy_vin_no = "{$VIN_NO}";
var accountid = "{$ACCOUNTID}";
var pc_module = "{$MODULE}";

{literal}

	function loadCalculateList(){
		$.get("index.php?module="+pc_module+"&action=getCalListAjax&vin="+policy_vin_no,function(res){
			
			if(res.type = "success")
			{
				data = res.data;
				$("#policy_calculate_list_box").empty();
				for(idx=0;idx<data.length;idx++)
				{
					var html = '<div recordid = "'+data[idx].ID+'" title="'+data[idx].SUMMARY+'" style="padding:2px;border-bottom:1px solid #EDEDED;">'; 
					html += '<span style="font-size:12px;">'+data[idx].CAL_NO+'</span><br/>';
					html += '<span style="font-size:10px;color:#ACACAC;">'+data[idx].MODIFY_TIME+'</span></div>';
					$("#policy_calculate_list_box").append(html);
				}
				$("#policy_calculate_list_box").children("div")
				.mouseenter(function(){
					$(this).css("background-color","#f5f8f9;");
					$(this).css("cursor","pointer");
				})
				.mouseleave(function(){
					$(this).css("background-color","#FFFFFF");
					$(this).css("cursor","default");
				})
				.data("pc_module",pc_module)
				.click(function(){
					var recordid = $(this).attr("recordid");
					var url = "index.php?module="+$(this).data("pc_module")+"&action=calculateView&recordid=" + recordid;
					$("#policy_calculate_content").load(url);
				});				

			}
		
		},'json');	
	};
		
	$("#policy_calculate_create").data("pc_module",pc_module).click(function(){
		$("#policy_calculate_content").load("index.php?module="+$(this).data("pc_module")+"&action=calculateView&recordid=&vin_no="+policy_vin_no);

	});
	loadCalculateList();	
	$("#policy_calculate_content").load("index.php?module="+pc_module+"&action=calculateView&recordid=&vin_no="+policy_vin_no+"&accountid="+accountid);	
{/literal}
</script>