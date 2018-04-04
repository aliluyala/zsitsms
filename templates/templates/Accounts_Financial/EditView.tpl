{include file="TitleBar.tpl"}
<div class="CLIENT_JOBVIEW_BUTTONS_TOP ">
	<button class="module_seting_button" operation="save">保存</button>
	<button class="module_seting_button" operation="cancel">取消</button>
</div>
<form id="form_edit_view">
	<input type="hidden" id="editview_module_name" value="{$MODULE}"/>
	<input type="hidden" name="recordid" value="{$RECORDID}"/>
	<input type="hidden" name="new_recordid" value="{$NEW_RECORDID}"/>
	<input type="hidden" name="operation" value="{$OPERATION}"/>
	<div  style="font-size:12px">
		{if $EDITVIEW_DATAS.have_block }
			{* 分栏显示  *}
			{foreach $EDITVIEW_DATAS.blocks as $name=>$block}
			<div id="editview_blocks_accordion_{$name}"  style="text-align:left;">
				<h3 >{$block.label}</h3>
				<div style="padding:1px;">
					<table class="client_detailview_table" cellspacing="0">
						{foreach $block.datas as $row}
							<tr>
								{foreach $row as $field}
									{if $field.UI == 101}
										{include file="UI/{$field.UI}.UI.tpl" FIELDINFO=$field}
									{else}
										<td class="client_detailview_label_{$block.cols}" >
											<label for="{$field.name}" title="{$field.title}">{$field.label}</label>
											{if $field.mandatory}<span style="color:red">*</span>{/if}
										</td>
										<td class="client_detailview_value_{$block.cols}">
											{if $field.edit}
												{include file="UI/{$field.UI}.UI.tpl" FIELDINFO=$field}
											{else}
												{$field.value}
											{/if}

										</td>
									{/if}
								{/foreach}
							</tr>
						{/foreach}
					</table>
				</div>
			</div>
			<script type="text/javascript">
				$("#editview_blocks_accordion_{$name}").accordion({ldelim}
					active:{if  $block.active}0 {else} false {/if},
					collapsible: true,
					heightStyle:"content" {rdelim} );
			</script>
			{/foreach}


		{else}
			<table class="client_detailview_table" cellspacing="0">
				{foreach $EDITVIEW_DATAS.datas as $row}
					<tr>
						{foreach $row as $field}
							{if $field.UI == 101}
								{include file="UI/{$field.UI}.UI.tpl" FIELDINFO=$field}
							{else}
								<td class="client_detailview_label_{$EDITVIEW_DATAS.cols}" >
									<label for="{$field.name}" title="{$field.title}">{$field.label}</label>
									{if $field.mandatory}<span style="color:red">*</span>{/if}
								</td>
								<td class="client_detailview_value_{$EDITVIEW_DATAS.cols}">
									{if $field.edit}
										{include file="UI/{$field.UI}.UI.tpl" FIELDINFO=$field}
									{else}
										{$field.value}
									{/if}

								</td>
							{/if}
						{/foreach}
					</tr>
				{/foreach}
			</table>
		{/if}
	</div>
</form>
<div id="dialog_confirm_save" title="确认保存" >
  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
  请确认保存记录，<br/>点击“确定”保存，点击“取消”忽略此操作。</p>
</div>

<div class="CLIENT_JOBVIEW_BUTTONS">
	<button class="module_seting_button" operation="save">保存</button>
	<button class="module_seting_button" operation="cancel">取消</button>
</div>
<script type="text/javascript" src="{$SCRIPTS}/zswitch-accounts.js"></script>
<script type="text/javascript">
	zswitch_ui_form_init("#form_edit_view");
	var return_module = "{$RETURN_MODULE}";
	var return_action = "{$RETURN_ACTION}";
	var return_recordid = "{$RETURN_RECORDID}";
	var recordid = "{$RECORDID}";

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
		var mod = $("#editview_module_name").val();
		//zswitch_load_client_view("index.php?module="+mod+"&action=save","form_edit_view");
		zswitch_ajax_load_client_view("index.php?module="+mod+"&action=save&return_module="+return_module+"&return_action="+return_action+"&return_recordid="+return_recordid,"form_edit_view");
 		$( this ).dialog( "close" );
    },
    "取消":function(){
    	$( this ).dialog( "close" );
       }
     }
    });


	$(".module_seting_button").button().click(function(){
		var oper = $(this).attr("operation");
		var mod = $("#editview_module_name").val();
		if(oper == 'save')
		{
			var info = zswitchui_validity_check("#form_edit_view");
			if(info.length<=0)
			{
				$("#dialog_confirm_save").dialog("open");
			}
			else
			{
				info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
				zswitch_open_messagebox("editview_input_errorr","输入错误",info,400,400);
			}
		}
		else if(oper == 'cancel')
		{
			if($("#form_edit_view input[name=operation]").val()=="create")
			{
				zswitch_load_client_view("index.php?module="+return_module+"&action=index");
			}
			else
			{
				zswitch_load_client_view("index.php?module="+return_module+"&action="+return_action+"&recordid="+return_recordid);
			}
		}
	});
	if($("input[name='operation']").val() != "edit"){
		$("input[name=mobile]").blur(function(){
			account.isRepeat($(this).val(),recordid);
		});
	}
	{/literal}
</script>