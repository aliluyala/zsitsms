<table  width="100%" style="text-align:left;font-size:12px;">
	<tr style="height:25px;">
		<th width="50%" style="color:#FF8C00;text-align:center; border-bottom:1px solid #79b7e7;">可访问归属哪些“组”的记录</th>
		<th style="color:#FF8C00;text-align:center;border-bottom:1px solid #79b7e7;">可访问归属哪些“用户”的记录</th>
	</tr>
	<tr>
		<td ><span style="margin-left:10px;font-weight:bold;">访问范围：</span>
			{if $SHARE_GROUPS_RANGE eq "allgroup"}
				全部
			{elseif  $SHARE_GROUPS_RANGE eq "selfgroup" }
				本组
			{else}
				选择
			{/if}	
		</td>
		<td><span style="margin-left:10px;font-weight:bold;">访问范围：</span>
			{if $SHARE_USERS_RANGE eq "alluser"}
				全部
			{elseif $SHARE_USERS_RANGE eq "selfuser"}
				自已
			{else}
				选择
			{/if}	
		</td>
	</tr>
	<tr>
		<td style ="vertical-align:top">
			<table class="client_listview_table small" cellspacing="0" >
				<tr >
					<th width="30px" style="text-align:center;">
																
					</th>
					<th style="text-align:center;">组名</th>
				</tr>
				{foreach $SHARE_GROUPS as  $share_groupid => $share_group}
				    <tr>
				    	<td style="text-align:center;">												    		
							{if $share_group.shared}
								<img src="{$IMAGES}/check-64.png" width="12" height="12"/> 
							{else}
								<img src="{$IMAGES}/delete_2.png" width="12" height="12"/> 	
							{/if}
				    	</td>
				    	<td style="text-align:center;">
							{$share_group.label}&nbsp; 
				    	</td>
				    </tr>
				{/foreach} 
			</table>
		</td>
		<td style ="vertical-align:top">
			<table class="client_listview_table small" cellspacing="0" >
				<tr>
					<th width="30px" style="text-align:center;">
					
					</th >
					<th style="text-align:center;">用户名</th>
				</tr>
				{foreach $SHARE_USERS as  $share_userid => $share_user}	
				    <tr>
				    	<td style="text-align:center;">												    		
							{if $share_user.shared} 
								<img src="{$IMAGES}/check-64.png" width="12" height="12"/> 
							{else}
								<img src="{$IMAGES}/delete_2.png" width="12" height="12"/> 	
							{/if}
				    	</td>
				    	<td style="text-align:center;">
							{$share_user.label}&nbsp; 
				    	</td>
				    </tr>
				{/foreach}
			</table>	
		</td>
	</tr>
</table>
