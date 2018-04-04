<form id ="perssion_setting_share">
<table  width="100%" style="text-align:left;font-size:12px">
	<tr>
		<th width="50%" style="color:#FF8C00;text-align:center;border-bottom:1px solid #79b7e7;">可访问归属哪些“组”的记录</th>
		<th style="color:#FF8C00;text-align:center;border-bottom:1px solid #79b7e7;">可访问归属哪些“用户”的记录</th>
	</tr>
	<tr>
		<td ><span style="margin-left:10px;font-weight:bold;">访问范围：</span>
			<input type="radio" id = "groups_range_allgroup" name="groups_range" value="allgroup" 
				{if $SHARE_GROUPS_RANGE eq "allgroup"} checked="checked" {/if}
				onclick="share_group_range_change('allgroup','{$PMOD}');"/>
			<label for="groups_range_allgroup" title="全部">全部</label> |
			<input type="radio" id = "groups_range_selfgroup" name="groups_range" value="selfgroup" 
				{if $SHARE_GROUPS_RANGE eq "selfgroup"} checked="checked" {/if}
				onclick="share_group_range_change('selfgroup','{$PMOD}');"/> 
			<label for="groups_range_selfgroup" title="本组">本组</label> |
			<input type="radio" id = "groups_range_select" name="groups_range" value="select"
				{if $SHARE_GROUPS_RANGE eq "select"} checked="checked" {/if}
				onclick="share_group_range_change('select','{$PMOD}');"/> 
			<label for="groups_range_select" title="选择">选择</label>

		</td>
		<td><span style="margin-left:10px;font-weight:bold;">访问范围：</span>
			<input type="radio" id = "users_range_alluser" name="users_range" value="alluser"  
				{if $SHARE_USERS_RANGE eq "alluser"} checked="checked" {/if}
				onclick="share_user_range_change('alluser','{$PMOD}');"/>
			<label for="users_range_alluser" title="全部">全部</label> |
			<input type="radio" id = "users_range_selfuser" name="users_range" value="selfuser" 
				{if $SHARE_USERS_RANGE eq "selfuser"} checked="checked" {/if}
				onclick="share_user_range_change('selfuser','{$PMOD}');" /> 
			<label for="users_range_selfuser" title="自已">自已</label> |
			<input type="radio" id = "users_range_select" name="users_range" value="select"  
				{if $SHARE_USERS_RANGE eq "select"} checked="checked" {/if}
				onclick="share_user_range_change('select','{$PMOD}');"/> 
			<label for="users_range_select" title="选择">选择</label>
		</td>
	</tr>	
	<tr>
		<td style ="vertical-align:top">
			<table class="client_listview_table small" cellspacing="0" >
				<tr >
					<th width="30px" style="text-align:center;">
						<input id="all_share_group" type="checkbox" name="all_share_group" title="全选" 
							class="permission_share_group_all" 
							{if $SHARE_GROUPS_RANGE neq "select"} disabled="disabled" {/if} />												
					</th>
					<th style="text-align:center;">组名</th>
				</tr>
				{foreach $SHARE_GROUPS as  $share_groupid => $share_group}
				    <tr>
				    	<td style="text-align:center;">
				    		<input type="checkbox" name="share_group[]" 
							class="permission_share_group_item" value="{$share_groupid}" {if $share_group.shared} checked="checked" {/if}
							{if $SHARE_GROUPS_RANGE neq "select"} disabled="disabled" {/if} />
				    	</td>
				    	<td style="text-align:center;">
							{$share_group.label}
				    	</td>
				    </tr>
				{/foreach}
			</table>
		</td>
		<td style ="vertical-align:top">
			<table class="client_listview_table small" cellspacing="0" >
				<tr>
					<th width="30px" style="text-align:center;">
						<input id="all_share_user" type="checkbox" name="all_share_user" title="全选"
						class="permission_share_user_all"  {if $SHARE_USERS_RANGE neq "select"} disabled="disabled" {/if} />													
					</th >
					<th style="text-align:center;">用户名</th>
				</tr>
				{foreach $SHARE_USERS as  $share_userid => $share_user}	
				    <tr>
				    	<td style="text-align:center;">
				    		<input type="checkbox" name="share_user[]" 
							class="permission_share_user_item" value="{$share_userid}" {if $share_user.shared} checked="checked" {/if}
							{if $SHARE_USERS_RANGE neq "select"} disabled="disabled" {/if}/>
				    	</td>
				    	<td style="text-align:center;">
							{$share_user.label}
				    	</td>
				    </tr>
				{/foreach}
			</table>	
		</td>
	</tr>
</table>
</form>
<script>
 {literal}	
	
	function share_group_range_change(range,pmod)
	{
		if(range != "select")
		{
			
			$("#all_share_group").prop("disabled",true);
			$("input[name^=share_group]").prop("disabled",true);
		}
		else
		{
			$("#all_share_group").prop("disabled",false);
			$("input[name^=share_group]").prop("disabled",false);			
		}		
	}
	
	function share_user_range_change(range,pmod)
	{
		if(range != "select")
		{
			
			$("#all_share_user").prop("disabled",true);
			$("input[name^=share_user]").prop("disabled",true);
		}
		else
		{
			$("#all_share_user").prop("disabled",false);
			$("input[name^=share_user]").prop("disabled",false);
		}		
	}	
	
	function share_group_change()
	{
		$("#all_share_group").prop("checked",true);
		$("input[name^=share_group]").each(function(){
			if(!$(this).is(":checked"))
			{
				$("#all_share_group").prop("checked",false);
				return false;
			}
		});		
	}

	$("input[name^=share_group]").click(function(){
		share_group_change();
	});
	
	function share_user_change()
	{
		$("#all_share_user").prop("checked",true);
		$("input[name^=share_user]").each(function(){
			if(!$(this).is(":checked"))
			{
				$("#all_share_user").prop("checked",false);
				return false;
			}
		});		
	}
	
	$("input[name^=share_user]").click(function(){
		share_user_change();
	});
	
	share_user_change();
	share_group_change();	
	$("#all_share_user").click(function(){
		if($(this).is(":checked"))
		{
			$("input[name^=share_user]").prop("checked",true);
		}
		else
		{
			$("input[name^=share_user]").prop("checked",false);
		}		
		
	});
	$("#all_share_group").click(function(){
		if($(this).is(":checked"))
		{
			$("input[name^=share_group]").prop("checked",true);
		}
		else
		{
			$("input[name^=share_group]").prop("checked",false);
		}				
	});

  {/literal}
</script>							