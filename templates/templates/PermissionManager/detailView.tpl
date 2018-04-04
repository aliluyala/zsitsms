{include file="TitleBar.tpl"}
{if $ERROR}
	{include file="ErrorMessage.tpl"}
{else}
<form id="permission_edit_form">
<input type="hidden" name="id" value="{$PERMISSION_ID}"/>
<input type="hidden" name="operation" value="{$OPERATION}"/>
<div style="font-size:12px">
	<div id="editview_blocks_accordion_base"  style="text-align:left;">
		<h3 >基本信息</h3>
		<div style="padding:1px;">
			<table class="client_detailview_table" cellspacing="0">
				<tr>					
					<td class="client_detailview_label_2" >
						<label for="name" title="权限的名称">权限名</label><span style="color:red">*</span>
					</td>
					<td class="client_detailview_value_2">							
						{$PERMISSION_NAME}&nbsp; 
					</td>		
					<td class="client_detailview_label_2" >
						<label for="description" title="对权限的详细描述">描述</label>
					</td>
					<td class="client_detailview_value_2">							
						{$PERMISSION_DESCRIPTION}&nbsp; 
					</td>						
				</tr>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		$("#editview_blocks_accordion_base").accordion({ldelim} 
			active:0, 
			collapsible: true,
			heightStyle:"content" {rdelim} );
	</script>	
	<div id="editview_blocks_accordion_module"  style="text-align:left;">
		<h3 >权限设置</h3>
		<div style="padding:1px;">
			
            <table  class="client_listview_table small" cellspacing="0" >
            	<tr>            		
            		<th width="30px" style="text-align:center;">
						&nbsp;
					</th> 
            		<th style="text-align:center;">模块</th>
					<th style="text-align:center;">方法</th>
					<th style="text-align:center;">记录权限</th>
					<th style="text-align:center;">字段权限</th>					 
            	</tr>
				{foreach $PERMISSION_INFO as $module_name => $info}
					<tr>            			
            			<td style="text-align:center;">
							{if $info.access} 
								<img src="{$IMAGES}/check-64.png" width="12" height="12"/> 
							{else}
								<img src="{$IMAGES}/delete_2.png" width="12" height="12"/> 	
							{/if} 
						</td>            			
						<td style="text-align:center;">{$info.label}</td>
						<td>
							{foreach $info.actions as $aciton_name => $action}
								 {if $action.access} 
									<img src="{$IMAGES}/check-64.png" width="12" height="12"/> 
								 {else}
									<img src="{$IMAGES}/delete_2.png" width="12" height="12"/> 									
								 {/if} {$action.label} | 
							{/foreach}
							&nbsp;
						</td>
						<td style="text-align:center;font-size:12px">	
							{if $info.access}  
								<a id="{$module_name}_modify_share" class="permission_module_setting_button" 
									module="{$module_name}" action="share" permissionid="{$PERMISSION_ID}" 
									href="javascript:permission_setting_dlg('{$module_name}','share','{$PERMISSION_ID}');"
								>查看</a>
							{/if}	
							
						</td>
						<td style="text-align:center;font-size:12px">
							{if $info.access}  
								<a id="{$module_name}_modify_fields" class="permission_module_setting_button" 
									module="{$module_name}" action="fields" permissionid="{$PERMISSION_ID}" 
									href="javascript:permission_setting_dlg('{$module_name}','fields','{$PERMISSION_ID}');"
								>查看</a>					
							{/if}									
						</td>	
            		</tr>
				{/foreach}
            </table>
		</div>
	</div>
	<script type="text/javascript">
		$("#editview_blocks_accordion_module").accordion({ldelim} 
			active:0, 
			collapsible: true,
			heightStyle:"content" {rdelim} );
	</script>		
	
</div>
</form>
<div id="dialog_confirm_delete" title="确认删除" >
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
  你确定要删除该权限吗？<br/>点击“确定”删除，点击“取消”忽略。</p>
</div>

<div class="CLIENT_JOBVIEW_BUTTONS">
	<button class="menu_seting_button" operation="edit">编辑</button> 
	<button class="menu_seting_button" operation="delete">删除</button>
	<button class="menu_seting_button" operation="copy">复制</button>
</div>
<div id="permission_module_setting_dlg">
</div>
<script>
  {literal}
    $("#dialog_confirm_delete").dialog({
     autoOpen: false,
     height: 200,
     width: 380,
     modal: true,
	 appendTo: "#main_view_client",
     dialogClass:"dialog_default_class",
     buttons: {
    "确定":function(){
		var url = "index.php?module=PermissionManager&action=delete&recordid=";
		var id = $("#permission_edit_form").find("[name=id]:input").val();
		zswitch_ajax_load_client_view(url+id);
    	$( this ).dialog( "close" );				
    },
    "取消":function(){			
    	$( this ).dialog( "close" );        
       }		
     }	
    });  
	$(".menu_seting_button").button().click(function(){
		var oper= $(this).attr("operation") ;
		var url = "index.php?module=PermissionManager&action=";
		var id = $("#permission_edit_form").find("[name=id]:input").val();
		if(oper == "edit")
		{			
			zswitch_load_client_view(url+"editView&recordid="+id);
		}
		else if(oper == "delete")
		{
			$("#dialog_confirm_delete").dialog("open");
		}
		else if(oper == "copy")
		{		
			zswitch_load_client_view(url+"copyView&recordid="+id);
		}

	
	});
	$("#permission_module_setting_dlg").dialog({
		autoOpen:false,
		height:500,
		width:700,
		modal:true,
		appendTo: "#main_view_client",
		dialogClass:"dialog_default_class",
		open:function(){
			var mod = $(this).data('module');
			var type = $(this).data('type');
			var pid = $("#permission_module_setting_dlg").data("permissionid");
			var url = "";
			zswitch_show_progressbar($(this),"permission_module_setting_dlg_progress");
			if(type == "share")
			{
				$(this).dialog( "option", "title","记录权限");
				url = "index.php?module=PermissionManager&action=detailRecordsetView";
			}
			else if(type == "fields")
			{
				$(this).dialog( "option", "title","字段权限");
				url = "index.php?module=PermissionManager&action=detailFieldView";
			}
			url += "&pmod="+mod+"&pid="+pid;
			$(this).html("");
			$(this).load(url);
			
		}	
	});
	function permission_setting_dlg(module,type,permissionid)
	{
		$("#permission_module_setting_dlg").data("module",module);
		$("#permission_module_setting_dlg").data("type",type);
		$("#permission_module_setting_dlg").data("permissionid",permissionid);
		$("#permission_module_setting_dlg").dialog("open");									
	}	
			
  {/literal}
</script>


{/if}
