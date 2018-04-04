{include file="TitleBar.tpl"}
{if $ERROR}
	{include file="ErrorMessage.tpl"}
{else}

	<table style="width:100%; font-size:12px;">
	  <tr>
		<td style="width:50%">
		  <div style="margin:5px 0px 3px 0px;font-weight:bold;font-size:14px;color:#FF8C00;">模块列表</div>
	  	  <div style="height:400px;border-style:solid;border-width:1px;border-color:#79b7e7;text-align:left;font-size:10px;overflow:scroll">
			<ul id="sortable">
				{foreach $MODULES_INFO as $name => $module}
					<li class="ui-state-default">
						<table style="width:100%; font-size:12px;">
						<tr>
						<td style="width:30px;">
						<input type="hidden" name="module_list_id" value="{$module.id}"/>
						<input type="hidden" name="module_list_name" value="{$name}"/>
						<input type="checkbox" onclick="activeModule(this.checked,$(this))" 
							title="激活模块" name="module_list_actived" {if $module.actived}checked="checked"{/if}/>						
						</td>
						<td  style="width:200px">
						{$name}
						</td>
						<td style="">
						{$module.describe}
						</td>
						</tr>	
						</table>	
					</li>
				{/foreach}
			</ul>
		  </div>
		</td>
		<td>
		  <div style="margin:5px 0px 3px 0px;font-weight:bold;font-size:14px;color:#FF8C00;">模块设置</div>
		  <div id="module_info_edit_box" style="height:400px;border-style:solid;border-width:1px;border-color:#79b7e7;">
							
		  </div>

		</td>
	  </tr>	
	</table>
	<script>
	  {literal}
		
		function loadModuleInfoEditView(id)
		{
			$("#module_info_edit_box").load("index.php?module=ModuleManager&action=editModule&recordid="+id);
		}
		$("#sortable" ).sortable({
			placeholder: "ui-state-highlight",
			stop: function( event, ui ) {
				var idxarr = "";
				$("#sortable" ).children("li").each(function(index,em){
					id = $(this).find("[name=module_list_id]").val();
					if(id!="-1")
					{
						idxarr += id+":"+index+",";
					}								
				});
				$.post("index.php?module=ModuleManager&action=sortable",{"sort":idxarr},function(data){
					zswitch_load_client_view("index.php?module=ModuleManager&action=index");
				});
				
			}
		});
		$("#sortable li" ).click(function(){			
			loadModuleInfoEditView($(this).find("[name=module_list_id]").val())
		});
		function activeModule(actived,obj)
		{
			var id = obj.siblings('[name=module_list_id]').val();
			var name = obj.siblings('[name=module_list_name]').val();
			if(actived)
			{
				
				$.get("index.php?module=ModuleManager&action=active&name="+name,function(data){				
					obj.siblings("[name=module_list_id]").val(data);
					loadModuleInfoEditView(data);
				});
			}
			else
			{
				$.get("index.php?module=ModuleManager&action=remove&recordid="+id,function(data){
					
					if(data == "-1")
					{
						obj.siblings('[name=module_list_id]').val("-1");
						loadModuleInfoEditView("-1");						
					}
					else
					{
						obj.attr("checked",true);
					}					
				});				
			}
		}
      {/literal}
	  loadModuleInfoEditView({$MODULE_CURRENT_ITEM_ID});
	</script>  
{/if}