{include file="TitleBar.tpl"}
{if $ERROR}
	{include file="ErrorMessage.tpl"}
{else}
<form id="permission_edit_form">
<input type="hidden" name="id" value="{$PERMISSION_ID}"/>
<input type="hidden" name="operation" value="{$OPERATION}"/>
{if $OPERATION eq 'copy'}
	<input type="hidden" name="new_id" value="{$NEW_ID}"/>
{/if}
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
						<input type="text" id="name" name="name" value="{$PERMISSION_NAME}" style="width:98%"/>
					</td>		
					<td class="client_detailview_label_2" >
						<label for="description" title="对权限的详细描述">描述</label>
					</td>
					<td class="client_detailview_value_2">							
						<input type="text" id="description" name="description" value="{$PERMISSION_DESCRIPTION}" style="width:98%"/>
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
							<input type="checkbox" name="module_access[]" {if $info.access} 
							 checked="checked" {/if} value="{$module_name}" onclick="javascript:module_access_change('{$module_name}',this.checked)"/>
						</td>            			
						<td style="text-align:center;">{$info.label}</td>
						<td>
							{foreach $info.actions as $aciton_name => $action}
								<input type="checkbox" name="{$module_name}_actions[]" {if $action.access} checked="checked" {/if} value="{$aciton_name}"
								{if !$info.access}  disabled="disabled" {/if}
								/>{$action.label} | 
							{/foreach}
							&nbsp;
						</td>
						<td style="text-align:center;font-size:12px">

							<a id="{$module_name}_recordset_but" class="permission_module_setting_button"  action = "share"  module="{$module_name}" permissionid="{$PERMISSION_ID}"  								
								href="javascript:permission_setting_dlg('{$module_name}','share','{$PERMISSION_ID}');"
								{if !$info.access}  style="display:none;" {/if}
							>修改</a>
						</td>
						<td style="text-align:center;font-size:12px">
							<a  id="{$module_name}_field_but" class="permission_module_setting_button" action = "fields"  module="{$module_name}" permissionid="{$PERMISSION_ID}"  
								href="javascript:permission_setting_dlg('{$module_name}','fields','{$PERMISSION_ID}');"
								{if !$info.access}  style="display:none;"  {/if}
							>修改</button>

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
<div id="dialog_confirm_save" title="确认保存" >
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
  请确认保存权限设置，<br/>点击“确定”保存，点击“取消”忽略此操作。</p>
</div>	

<div class="CLIENT_JOBVIEW_BUTTONS">
	<button class="menu_seting_button" operation="save">保存</button> 
	<button class="menu_seting_button" operation="cancel">取消</button>
</div>
<div id="permission_module_setting_dlg"></div>
<script>
  {literal}
    $("#dialog_confirm_save").dialog({
     autoOpen: false,
     height: 200,
     width: 380,
     modal: true,
	 appendTo: "#main_view_client",
     dialogClass:"dialog_default_class",
     buttons: {
    "确定":function(){
		zswitch_ajax_load_client_view("index.php?module=PermissionManager&action=save","permission_edit_form");
		//zswitch_load_client_view("index.php?module=PermissionManager&action=save","permission_edit_form");
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
		if(oper == "save")
		{
			var name = $("#permission_edit_form").find("[name=name]").val();
			if(name.length<1)
			{
				alert('“权限名”不能为空！');
			}
			else
			{
				$("#dialog_confirm_save").dialog("open");	
			}	
		}
		else if(oper == "cancel")
		{
			zswitch_load_client_view(url+"index");
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
			var pid = $(this).data("permissionid");
			var new_pid = $("#permission_module_setting_dlg").data("new_pid");
			var url = "";
			zswitch_show_progressbar($(this),"permission_module_setting_dlg_progress");
			if(type == "share")
			{
				$(this).dialog( "option", "title","记录权限");
				url = "index.php?module=PermissionManager&action=editRecordsetView";
				url += "&pmod="+mod+"&pid="+pid+"&new_pid="+new_pid;
			}
			else if(type == "fields")
			{
				$(this).dialog( "option", "title","字段权限");
				url = "index.php?module=PermissionManager&action=editFieldView";
				url += "&pmod="+mod+"&pid="+pid+"&new_pid="+new_pid;
			}			
			$(this).load(url);			
		},
		close:function(){
			$(this).html("");
		},
		buttons:{
			'保存':function(){
				var mod = $(this).data('module');
				var type = $(this).data('type');
				var pid = $(this).data("permissionid");
				var new_pid = $("#permission_module_setting_dlg").data("new_pid");
				var url = "";	
				zswitch_show_progressbar($(this),"permission_module_setting_dlg_progress");	
				if(type == "share")
				{
					url = "index.php?module=PermissionManager&action=saveRecordset";
					url += "&pmod="+mod+"&pid="+pid+"&new_pid="+new_pid;
				}
				else if(type == "fields")
				{
					url = "index.php?module=PermissionManager&action=saveField";
					url += "&pmod="+mod+"&pid="+pid+"&new_pid="+new_pid;
				}	
				//zswitch_load_client_view(url,"perssion_setting_share");	
				$.post(url,$("#perssion_setting_share").serialize(),function(rep){
						//alert(rep);
					},'text');				
				$(this).dialog("close");
			},
			'取消':function(){$(this).dialog("close");}
		}
	});
	

	function permission_setting_dlg(module,type,permissionid)
	{
		$("#permission_module_setting_dlg").data("module",module);
		$("#permission_module_setting_dlg").data("type",type);
		$("#permission_module_setting_dlg").data("permissionid",permissionid);
		var new_pid = "";
		if($("#permission_edit_form").children().is("input[name=new_id]"))
		{
			new_pid = $("#permission_edit_form").children("input[name=new_id]").val();
		}
		
		$("#permission_module_setting_dlg").data("new_pid",new_pid);
		$("#permission_module_setting_dlg").dialog("open");									
	}	
	
	function module_access_change(mod,state)
	{
		if(state)
		{
			$("input:checkbox[name*="+mod+"_actions]").prop("disabled",false);
			$("#"+mod+"_recordset_but").show();
			$("#"+mod+"_field_but").show();
		}
		else
		{
			$("input:checkbox[name*="+mod+"_actions]").prop("disabled",true);
			$("#"+mod+"_recordset_but").hide();
			$("#"+mod+"_field_but").hide();
		}	
	}

  {/literal}
</script>


{/if}
