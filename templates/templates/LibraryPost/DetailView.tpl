{include file="TitleBar.tpl"}
<link rel="stylesheet" type="text/css" href="{$STYLES}/genericons/genericons.css" />
<table class="library_posts" border="0px">
	<tr>
		<td style="vertical-align: top ;">
			<div id="detailview_info_tabs" style="min-height:450px;">

				{foreach $DETAILVIEW_DATAS.blocks as $name=>$block}
					{foreach $block.datas as $row}
						{foreach $row as $field}
							{if $field.name eq 'title'}
								{assign var=title value=$field.value}
								{assign var=title_name value=$field.name}
								{assign var=title_edit value=$field.edit}
							{/if}
							{if $field.name eq 'categoryid'}
								{assign var=category value=$field.show_value}
								{assign var=category_name value=$field.name}
								{assign var=category_edit value=$field.edit}
							{/if}
							{if $field.name eq 'status'}
								{assign var=status value=$field.value}
								{assign var=status_name value=$field.name}
								{assign var=status_edit value=$field.edit}
							{/if}
							{if $field.name eq 'date_create'}
								{assign var=date_create value=$field.value}
								{assign var=date_create_name value=$field.name}
								{assign var=date_create_edit value=$field.edit}
							{/if}
							{if $field.name eq 'user_create'}
								{assign var=user_create value=$field.show_value}
								{assign var=user_create_name value=$field.name}
								{assign var=user_create_edit value=$field.edit}
							{/if}
							{if $field.name eq 'content'}
								{assign var=content value=$field.value}
								{assign var=content_name value=$field.name}
								{assign var=content_edit value=$field.edit}
							{/if}
						{/foreach}
					{/foreach}
				{/foreach}
				<div id="tabs-base" style="padding:5px 0px 0px 0px;">
					<div id="detailview_blocks_accordion_{$name}"  style="text-align:left;">
						<div id="entry-post">
							<header class="entry-header">
								<h1 class="entry-title">
									{if $title_edit}
										<a class="entry-edit detailview_value_contenter" id="{$title_name}" href="javascript:zswitch_open_missedit_dlg('{$MODULE}','{$title_name}',{$DETAILVIEW_RECORDID});">{$title}</a>
									{else}
										{$title}
									{/if}
								</h1>
							</header>
							<div class="entry-meta">
								<span class="entry-category">
									{if $category_edit}
										<a class="entry-edit detailview_value_contenter" id="{$category_name}" href="javascript:zswitch_open_missedit_dlg('{$MODULE}','{$category_name}',{$DETAILVIEW_RECORDID});">{$category}</a>
									{else}
										{$category}
									{/if}
								</span>
								<span class="entry-author">
									{if $user_create_edit}
										<a class="entry-edit detailview_value_contenter" id="{$user_create_name}" href="javascript:zswitch_open_missedit_dlg('{$MODULE}','{$user_create_name}',{$DETAILVIEW_RECORDID});">{$user_create}</a>
									{else}
										{$user_create}
									{/if}
								</span>
								<span class="entry-date">
									{if $date_create_edit}
										<a class="entry-edit detailview_value_contenter" id="{$date_create_name}" href="javascript:zswitch_open_missedit_dlg('{$MODULE}','{$date_create_name}',{$DETAILVIEW_RECORDID});">{$date_create}</a>
									{else}
										{$date_create}
									{/if}
								</span>
								<span class="entry-post">
									{if $status_edit}
										<a class="entry-edit detailview_value_contenter" id="{$status_name}" href="javascript:zswitch_open_missedit_dlg('{$MODULE}','{$status_name}',{$DETAILVIEW_RECORDID});">{$status}</a>
									{else}
										{$status}
									{/if}
								</span>
							</div>
							<div class="entry-content">
								<p>{$content}</p>
							</div>
						</div>
					</div>
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

	//$("#detailview_info_tabs" ).tabs();
	/*$(".detailview_value_contenter").mouseenter(function(){
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
	$(".detailview_miss_edit").hide();*/
	{/literal}
	{$DETAILVIEW_AUTO_EXECUTE}
</script>

