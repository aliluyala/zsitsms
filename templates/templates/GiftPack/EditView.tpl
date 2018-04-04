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
	<div  style="font-size:12px" >
			<div id="editview_blocks_accordion_base"  style="text-align:left;">
				<h3 >礼包设置</h3>
				<div style="padding:1px;">
					<table class="client_detailview_table" cellspacing="0" >
						{foreach $EDITVIEW_DATAS.datas as $row}
							<tr>
								
								{foreach $row as $field}
									
									{if $field.UI == 101}
										{include file="UI/{$field.UI}.UI.tpl" FIELDINFO=$field}
									{else}
										<td class="client_detailview_label_1" >
											<label for="{$field.name}" title="{$field.title}">{$field.label}</label>
											{if $field.mandatory}<span style="color:red">*</span>{/if}
										</td>
										<td class="client_detailview_value_1">
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
				$("#editview_blocks_accordion_base").accordion({
					active:0, 
					collapsible: true,
					heightStyle:"content"  });
			</script>		
			<div id="editview_blocks_accordion_content"  style="text-align:left;">
				<h3 >礼包内容</h3>
				<div style="padding:1px;">
				<table id="gift_list"></table>
				<!-- <div id="gift_list_pager"></div> -->
				<div style="font-size:10px;padding:5px;">
					<button id="add_product" >增加</button>					
				<!--	<button id="modify_product" >修改</button> -->
					<button id="delete_product" >删除</button>
				</div>
				<div id="add_product_dlg"></div>
				<script type="text/javascript">	
					//var giftContentUrl = "index.php?module=GiftPack&action=giftContent&oper=getList&recordid="+$("input[name='recordid']").val();
				{literal}
					$("#gift_list").jqGrid(
					{
					//url : giftContentUrl,		
					height:"100",
					width:$(window).width()<988?938:$(window).width()-50,
					datatype : "local",
					hidegrid : false,
					colNames : [ '编码', '名称','规格' ,'数量'],
					colModel : [									
								{name : 'code',index : 'code',align:'center',editable : true}, 
								{name : 'name',index : 'name',align:'center',editable : false},  
								{name : 'standard',index : 'standard',align:'center',editable : false},
								{name : 'count',index : 'count',align:'center',editable : true}
							   ],
					rowNum : 20,
					rowList : [ 20 ],
					//pager : '#gift_list_pager',
					sortname : 'code',
					mtype : "post",
					viewrecords : true,
					emptyrecords : '礼品包为空.',
					sortorder : "desc",
					//caption : "",
					onSelectRow:function(rowid,status){
						
					}
					
					});
					
					//jQuery("#gift_list").jqGrid('inlineNav', "#gift_list_pager");
					
					$(window).resize(function(event){		
						var newWidth = $(window).width();
						var gridWidth = newWidth-50;
						if(gridWidth<938) gridWidth = 938;
						$("#gift_list").setGridWidth(gridWidth,true);						
					});	
					
					$("#add_product_dlg").dialog({
						autoOpen: false,
						height: 200,
						width: 380,
						modal: false,
						appendTo: "#main_view_client",
						dialogClass:"dialog_default_class",
						open:function(){
							var url = "index.php?module=GiftPack&action=addProductView";
							$(this).load(url);						
						},
						buttons: {
						"确定":function(){
							var productinfo = {};
							$("#add_product_view_form input[name]").each(function(){
								productinfo[$(this).attr("name")] = $(this).val();
							});
							var url = "index.php?module=GiftPack&action=queryProductAjax&productid="+productinfo.product_id;
							$.get(url,function(data){
								if(data.type=="success")
								{								
									var rowdata = data.data;	
									rowdata['count'] = productinfo.product_count;
									var id = $("#gift_list").jqGrid('getGridParam','records');									
									$("#gift_list").jqGrid('addRowData', id, rowdata);									
								}
							},'json');
							$( this ).dialog( "close" );
						},
						"取消":function(){
							$( this ).dialog( "close" );
						}
						}
						})			
					
					
					$("#add_product").button().click(function(e){
						$("#add_product_dlg").dialog("open");						
						e.preventDefault();
					});
					
					$("#delete_product").button().click(function(e){
						var selrow = $("#gift_list").jqGrid('getGridParam','selrow');					
						$("#gift_list").jqGrid('delRowData', selrow);
						e.preventDefault();					
					});
					$("#modify_product").button().click(function(e){
						e.preventDefault();					
					});	
					var cont = 	$("#form_edit_view").find("[name='content']").val();					
					if(cont)
					{
						var griddata = $.evalJSON(decodeURIComponent($.base64.atob(cont)));
						for ( var i = 0; i <= griddata.length; i++)
						{
							$("#gift_list").jqGrid('addRowData', i, griddata[i]);
						}
					}	
					
					
				{/literal}
				</script>
				

				
				</div>
			</div>
			<script type="text/javascript">
				$("#editview_blocks_accordion_content").accordion({
					active:0, 
					collapsible: true,
					heightStyle:"content"  });
			</script>		
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
<script type="text/javascript">
	$.base64.utf8encode = true;
	zswitch_ui_form_init("#form_edit_view");	
	var return_module = "{$RETURN_MODULE}";
	var return_action = "{$RETURN_ACTION}";
	var return_recordid = "{$RETURN_RECORDID}";

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
		var content=$("#gift_list").jqGrid("getRowData");
		$("#form_edit_view").find("[name='content']").val($.base64.btoa(encodeURIComponent($.toJSON(content))));
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
	{/literal}
</script>