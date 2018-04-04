{include file="TitleBar.tpl"}
{if $DETAILVIEW_DATAS}
<table border="0px" style="width:100%;font-size:12px;vertical-align:top;">
	<tr>
		<td style="vertical-align: top ;">
			<div id="detailview_info_tabs" style="min-height:450px;">
				<ul>
					{* 页头  *}
					<li><a href="#tabs-base">{$MODULE_DETAILVIEW_LABEL}</a></li>
					{foreach $MODULE_DETAILVIEW_ASSOCIATEBY as $tab}
						<li><a href="{$tab.url}">{$tab.label}</a></li>
					{/foreach}
 				</ul>

				<div id="tabs-base" style="padding:5px 0px 0px 0px;">
					{if $DETAILVIEW_DATAS.have_block }
						{* 分栏显示  *}


						{foreach $DETAILVIEW_DATAS.blocks as $name=>$block}
						<div id="detailview_blocks_accordion_{$name}"  style="text-align:left;">
							<h3 >{$block.label}</h3>
							<div style="padding:1px;">
								<table class="client_detailview_table" cellspacing="0">
									{foreach $block.datas as $row}
										<tr>
											{foreach $row as $field}
												{if $field.UI == 101}
												<input type="hidden" id="form[{$field.name}]" value="{$field.inter_value}" />
												{continue}
												{/if}
												<td class="client_detailview_label_{$block.cols}" >
													<label for="{$field.name}" title="{$field.title}">{$field.label}</label>{if $field.mandatory}<span style="color:red">*</span>{/if}
												</td>
												<td class="client_detailview_value_{$block.cols}">

													{if $field.UI == 70}
														{include file="UI/70.UI.tpl" FIELDINFO=$field}
													{else}

													    <div id="{$field.name}" class="detailview_value_contenter">
														<input type="hidden" id="form[{$field.name}]" value="{$field.inter_value}" />
														{if $field.UI == 50 OR $field.UI == 51 OR $field.UI == 52 OR  $field.UI == 55 }
															<a href="javascript:zswitch_load_client_view('index.php?module={$field.associate_module}&action={$field.associate_action}&recordid={$field.value}&return_module={$MODULE}&return_action=detailView&return_recordid={$DETAILVIEW_RECORDID}')" style="color:#1E90FF">{$field.show_value}</a>
														{elseif $field.UI == 61}
															<a href="javascript:zswitch_callcenter_click_call_a('{$MODULE}',{$DETAILVIEW_RECORDID},'{$field.name}','{$field.value}');" title="点击呼叫:{$field.value}" style="color:#1E90FF">{$field.value}</a>
														{else}
															{$field.value}&nbsp;
														{/if}
														{if $field.edit}
															<a class="detailview_miss_edit" href="javascript:zswitch_open_missedit_dlg('{$MODULE}','{$field.name}',{$DETAILVIEW_RECORDID});" style="color:#1E90FF;display:none">编辑</a>
													    {/if}
														</div>

													{/if}
												</td>
											{/foreach}
										</tr>
									{/foreach}
								</table>
							</div>
						</div>
						<script type="text/javascript">
							$("#detailview_blocks_accordion_{$name}").accordion({ldelim}
								active:{if  $block.active}0 {else} false {/if},
								collapsible: true,
								heightStyle:"content" {rdelim} );
						</script>
						{/foreach}


					{else}
						<table class="client_detailview_table" cellspacing="0">
							{foreach $DETAILVIEW_DATAS.datas as $row}
								<tr>
									{foreach $row as $field}
										{if $field.UI == 101}  {continue} {/if}
										<td class="client_detailview_label_{$DETAILVIEW_DATAS.cols}" >
											<label for="{$field.name}" title="{$field.title}">{$field.label}</label>
											{if $field.mandatory}<span style="color:red">*</span>{/if}
										</td>
										<td class="client_detailview_value_{$DETAILVIEW_DATAS.cols}">
											{if $field.UI == 70}
												{include file="UI/70.UI.tpl" FIELDINFO=$field}
											{else}
											    <div id="{$field.name}" class="detailview_value_contenter">
													{if $field.UI == 50 OR $field.UI == 51 OR $field.UI == 52 OR  $field.UI == 55 }
														<a href="javascript:zswitch_load_client_view('index.php?module={$field.associate_module}&action={$field.associate_action}&recordid={$field.value}&return_module={$MODULE}&return_action=detailView&return_recordid={$DETAILVIEW_RECORDID}')" style="color:#1E90FF">{$field.show_value}</a>
													{elseif $field.UI == 61}
														<a href="javascript:zswitch_callcenter_click_call_a('{$MODULE}',{$DETAILVIEW_RECORDID},'{$field.name}','{$field.value}');" title="点击呼叫:{$field.value}" style="color:#1E90FF">{$field.value}</a>

													{else}
														{$field.value}&nbsp;
													{/if}
													{if $field.edit}
											    	<a class="detailview_miss_edit" href="javascript:zswitch_open_missedit_dlg('{$MODULE}','{$field.name}',{$DETAILVIEW_RECORDID});" style="color:#1E90FF;display:none">编辑</a>
													{/if}
											    </div>
											{/if}
										</td>
									{/foreach}
								</tr>
							{/foreach}
						</table>
					{/if}
				</div>

			</div>
		</td>
		<td style="vertical-align: top ;width:120px;">
		{if $DETAILVIEW_BUTTONS.return}
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="返回"   action="return" >返回</button><br/>
		{/if}
		{if $DETAILVIEW_BUTTONS.back}
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="上一条" action="back">上一条</button><br/>
		{/if}
		{if $DETAILVIEW_BUTTONS.next}
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="下一条" action="next">下一条</button><br/>
		{/if}
		{if $DETAILVIEW_BUTTONS.edit}
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="编辑"   action="edit">编辑</button><br/>
		{/if}
		{if $DETAILVIEW_BUTTONS.delete}
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="删除"   action="delete">删除</button><br/>
		{/if}
		{if $DETAILVIEW_BUTTONS.copy}
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small" title="复制"   action="copy">复制</button><br/>
		{/if}
		<br/>
		{foreach $DETAILVIEW_CUSTOM_BUTTONS as $custom_button}
			<button  style="width:100px;padding-bottom:2px;" class="client_detailview_operation_button small"
				title="{$custom_button.title}"  onclick="{$custom_button.command}">{$custom_button.label}</button><br/>
		{/foreach}
		</td>
	</tr>
</table>
{else}
	<div >
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all" style="height:80px;line-height:80px;margin-top: 20px; padding: 0 .7em;text-align:left">
				<table><tr>
				<td>
				<span class="ui-icon ui-icon-info" ></span></td><td>
				<strong>对不起！</strong> 没有满足条件的记录可显示。
				</td></tr></table>
			</div>
		</div>
	</div>
{/if}
<script type="text/javascript" src="{$SCRIPTS}/zswitch-accounts.js"></script>
<script type="text/javascript">
	var module = "{$MODULE}";
	var recordid = "{$DETAILVIEW_RECORDID}";
	var return_module = "{$RETURN_MODULE}";
	var return_action = "{$RETURN_ACTION}";
	var return_recordid = "{$RETURN_RECORDID}";
	{literal}
	$(".client_detailview_operation_button").button().click(function(){
		var action = $(this).attr("action");
		if(action == "return")
		{
			zswitch_load_client_view("index.php?module="+return_module+"&action="+return_action+"&recordid="+return_recordid);
		}
		else if(action == "edit")
		{
			zswitch_load_client_view("index.php?module="+module+"&action=editView&recordid="+recordid+"&return_module="+module+"&return_action=detailView&return_recordid="+recordid);
		}
		else if(action == "copy")
		{
			zswitch_load_client_view("index.php?module="+module+"&action=copyView&recordid="+recordid);
		}
		else if(action == "delete")
		{
			zswitch_listview_operation_delete(module,recordid);
		}
		else if(action == "back")
		{
			zswitch_load_client_view("index.php?module="+module+"&action=detailView&recordid="+recordid+"&operation=prev");
		}
		else if(action == "next")
		{
			zswitch_load_client_view("index.php?module="+module+"&action=detailView&recordid="+recordid+"&operation=next");
		}
	});

	$("#detailview_info_tabs" ).tabs({
		//1.9之后cache被移除,手动实现cache功能
		beforeLoad: function( event, ui ) {
            if ( ui.tab.data( "loaded" ) ) {
				event.preventDefault();
				return;
            }

            ui.jqXHR.success(function() {
				ui.tab.data( "loaded", true );
            });
        }
	});
	$(".detailview_value_contenter").mouseenter(function(){
		modifyobj = $(this).find(".detailview_miss_edit");
		modifyobj.show();
		modifyobj.css("position","absolute");
		offset = $(this).position();
		modifyobj.css("top",offset.top);
		modifyobj.css("left",offset.left+$(this).width()-modifyobj.width());

	});
	$(".detailview_value_contenter").mouseleave(function(){
		$(this).find(".detailview_miss_edit").hide();
	});
	$(".detailview_miss_edit").hide();
	{/literal}
	{$DETAILVIEW_AUTO_EXECUTE}
</script>

