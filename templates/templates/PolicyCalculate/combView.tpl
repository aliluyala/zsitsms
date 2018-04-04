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
{foreach $POLICY_CALCULATE_LIST as $row}
	<div recordid = "{$row.ID}" title="{$row.SUMMARY}" style="padding:2px;border-bottom:1px solid #EDEDED;"> 
	 <span style="font-size:12px;">{$row.CAL_NO}</span><br/>	 
	 <span style="font-size:10px;color:#ACACAC;">{$row.MODIFY_TIME}</span>
	</div>
{/foreach}

</div>

</td>
<td style="vertical-align:top;border:1px solid #79b7e7;">
<iframe id="policy_calculate_content"  src="index.php?module=PolicyCalculate&action=calculateView&recordid=&vin_no={$VIN_NO}" style="border-style: none;width:100%;height:1240px;" >
</iframe>
</td>
</tr>

</table>

<script>
var policy_vin_no = "{$VIN_NO}";
{literal}
	$("#policy_calculate_list_box").children("div")
		.mouseenter(function(){
			$(this).css("background-color","#f5f8f9;");
			$(this).css("cursor","pointer");
		})
		.mouseleave(function(){
			$(this).css("background-color","#FFFFFF");
			$(this).css("cursor","default");
		})
		.click(function(){
			var recordid = $(this).attr("recordid");
			var url = "index.php?module=PolicyCalculate&action=calculateView&recordid=" + recordid;
			$("#policy_calculate_content").attr("src",url);
		});
		
	$("#policy_calculate_create").click(function(){
		$("#policy_calculate_content").attr("src","index.php?module=PolicyCalculate&action=calculateView&recordid=&vin_no="+policy_vin_no);
	});	
		
{/literal}
</script>