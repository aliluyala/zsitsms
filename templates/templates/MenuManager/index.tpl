{include file="TitleBar.tpl"}
{if $ERROR}
{include file="ErrorMessage.tpl"}
{else}
<form id="menu_seting_form">
	<div id="main_menu_list" style="font-size:12px">
		{foreach $MENUMANAGER_ITEMS as $item}
			<input type="radio" id="{$item.id}" value="{$item.id}" name="menuitem" {if $MENU_CURRENT_ITEM eq $item.id } checked="checked" {/if}/><label for="{$item.id}">{$item.name}</label>
		{/foreach}
	</div>
	<div style="margin:5px 200px 5px 200px;font-size:12px">
	<table class="client_detailview_table" cellspacing="0">	
		<tr>									
			<td class="client_detailview_label_1" >
				<label for="name" title="菜单名">菜单名</label><span style="color:red">*</span>
			</td>
			<td class="client_detailview_value_1">
				<input id="name" name="name" value="{$MENU_NAME}" style="width:300px" />
			</td>
		</tr>
		<tr>
			<td class="client_detailview_label_1" >
				<label for="title" title="提示信息">提示信息</label>
			</td>
			<td class="client_detailview_value_1">
				<input id="title" name="title" value="{$MENU_TITLE}" style="width:300px" />
			</td>						
		</tr>
		<tr>
			<td class="client_detailview_label_1" >
				<label for="seq" title="菜单排列的序号">序号</label><span style="color:red">*</span>
			</td>
			<td class="client_detailview_value_1">
				<input id="seq" name="seq" value="{$MENU_SEQ}" />
			</td>						
		</tr>
		<tr>
			<td class="client_detailview_label_1" >
				<label for="action" title="点击菜单后执行的操作">操作</label><span style="color:red">*</span>
			</td>
			<td class="client_detailview_value_1">
				
				<select id="action" name="action" >
					<options>
						<option value="SUB_MENU"   {if $MENU_ACTION eq "SUB_MENU"} selected="selected" {/if}>打开子菜单</option>
						<option value="OPEN_MODULE" {if $MENU_ACTION eq "OPEN_MODULE"} selected="selected"  {/if}>打开模块</option>
						<option value="OPEN_WINDOWS" {if $MENU_ACTION eq "OPEN_WINDOWS"} selected="selected"  {/if}>打开新窗口</option>
					<options>
				</select>
			</td>						
		</tr>
		<tr id="menu_seting_target">
			<td class="client_detailview_label_1" >
				<label for="target" title=''></label>
			</td>
			<td class="client_detailview_value_1">
				<input id="target" name="target" value="{$MENU_TARGET}" style="width:300px" />
			</td>						
		</tr>					
	</table>
    </div> 	
</form>
<div class="CLIENT_JOBVIEW_BUTTONS">
	<button class="menu_seting_button" operation="save">保存</button> 
	<button class="menu_seting_button" operation="add">增加</button> 
	<button class="menu_seting_button" operation="delete">删除</button>
	<button class="menu_seting_button" operation="cancel">取消</button>
</div>

<div id="menu_dialog_confirm_save" title="确认保存" >
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
  请确认保存对“菜单”项目的修改，<br/>点击“确定”保存，点击“取消”忽略此操作。</p>
</div>

<div id="menu_dialog_confirm_delete" title="确认删除" >
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
  请确认删除“菜单”项目，点击“确定”删除，点击“取消”忽略此操作。</p>
</div>

<script>
  {literal}
    $("#menu_dialog_confirm_save").dialog({
      autoOpen: false,
      height: 200,
      width: 380,
      modal: true,
	  appendTo: "#main_view_client",
	  dialogClass:"dialog_default_class",
      buttons: {
		"确定":function(){
		    zswitch_load_client_view("index.php?module=MenuManager&action=index&operation=save","menu_seting_form");
			$( this ).dialog( "close" );	
		},
		"取消":function(){			
			$( this ).dialog( "close" );        
        }		
	  }	
	});
    $("#menu_dialog_confirm_delete").dialog({
      autoOpen: false,
      height: 200,
      width: 380,
      modal: true,
	  appendTo: "#main_view_client",
	  dialogClass:"dialog_default_class",
      buttons: {
		"确定":function(){
		    zswitch_load_client_view("index.php?module=MenuManager&action=index&operation=delete","menu_seting_form");
			$( this ).dialog( "close" );	
		},
		"取消":function(){
			
			$( this ).dialog( "close" );        
        }		
	  }	
	}); 
  
    $( "#main_menu_list").buttonset();
	$( "#seq" ).spinner();
	$(".menu_seting_button").button().click(function(){
		var url = "index.php?module=MenuManager&action=index&operation=";
		if($(this).attr("operation")=="save")
		{
			url += "save";
			$("#menu_dialog_confirm_save").dialog("open");
			//zswitch_load_client_view(url,"menu_seting_form");
		}
		else if($(this).attr("operation")=="add")
		{
			url += "add";
			zswitch_load_client_view(url,"menu_seting_form");
		}
		else if($(this).attr("operation")=="delete")
		{
			url += "delete";
			$("#menu_dialog_confirm_delete").dialog("open");
			//zswitch_load_client_view(url,"menu_seting_form");
		}
		else if($(this).attr("operation")=="cancel")
		{
			url += "cancel";
			zswitch_load_client_view(url);
		}	
	});
	$("[name=menuitem]").change(function(){		
		zswitch_load_client_view("index.php?module=MenuManager&action=index","menu_seting_form");
	});
	$("[name=action]").change(function(){
		if($(this).val() == "SUB_MENU")
		{
			$("#menu_seting_target").hide();
		}
		else if($(this).val() == "OPEN_MODULE")
		{
			
			$("#menu_seting_target").show();
			$("[for=target]").attr("title","设置打开的模块名");
			$("[for=target]").html("模块名");
		}
		else if($(this).val() == "OPEN_WINDOWS")
		{
			$("#menu_seting_target").show();
			$("[for=target]").attr("title","设置HTTP链接");
			$("[for=target]").html("HTTP链接");			
		}
	});
	$("[name=action]").change();
  {/literal}
</script>
{/if}
