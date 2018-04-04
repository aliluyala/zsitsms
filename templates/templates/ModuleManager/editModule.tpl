{if $ERROR}
	{include file="ErrorMessage1.tpl"}
{else}
<form id ="module_info_edit">
 
 <table class="client_detailview_table" cellspacing="0">	
   <tr>									
     <td class="client_detailview_label_1" >
	   <label for="module_name" title="模块内部名字">模块名</label>
     </td>
     <td class="client_detailview_value_1">
	   {$MODULE_NAME}
     </td>
   </tr>
   <tr>									
     <td class="client_detailview_label_1" >
	   <label for="module_describe" title="对模块功能的描述">说明</label>
     </td>
     <td class="client_detailview_value_1">
	   <input id="module_describe" name="module_describe" value="{$MODULE_DESCRIBE}" style="width:300px" />
     </td>
   </tr>
   <tr>									
     <td class="client_detailview_label_1" >
	   <label for="module_menu" title="选择在哪项主菜单下调用">菜单</label>
     </td>
     <td class="client_detailview_value_1">		
	   <select  id="module_menu" name="module_menu">
		{html_options options=$MENU_LISTS selected=$MENU_ITEM_SELECTED}
	   </select >
     </td>
   </tr>	
      <tr>									
     <td class="client_detailview_label_1" >
	   <label for="module_action" title="调用模块时的默认入口方法">默认方法</label>
     </td>
     <td class="client_detailview_value_1">
	   <input id="module_action" name="module_action" value="{$MODULE_ACTION}" style="width:300px" />
     </td>
   </tr>			  		  
 </table>	
</form> 
  <div id="module_dialog_confirm_save" title="确认保存" >
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
  请确认保存对模块信息的修改，<br/>点击“确定”保存，点击“取消”忽略此操作。</p>
  </div>	
  <div class="CLIENT_JOBVIEW_BUTTONS">
	<button class="module_seting_button" operation="save">保存</button> 
  </div>	
  <script>
	var moduleid = {$MODULE_ID};
	{literal}
       $("#module_dialog_confirm_save").dialog({
         autoOpen: false,
         height: 200,
         width: 380,
         modal: true,
		 appendTo: "#main_view_client",
         dialogClass:"dialog_default_class",
         buttons: {
        "确定":function(){
            $.post("index.php?module=ModuleManager&action=save&recordid="+moduleid,$("#module_info_edit").serialize(),function(data){
				zswitch_load_client_view("index.php?module=ModuleManager&action=index&recordid="+moduleid);					
			});
        	$( this ).dialog( "close" );
					
        },
        "取消":function(){			
        	$( this ).dialog( "close" );        
           }		
         }	
        });
        $(".module_seting_button").button().click(function(){$("#module_dialog_confirm_save").dialog("open")});
	{/literal}
  </script>  
{/if} 