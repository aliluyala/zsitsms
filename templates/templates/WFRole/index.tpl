{include file="WFRole/TitleBar.tpl"}
<table id ="process_role_box" cellpadding="0" cellspacing="0" style="border:1px solid #ECECEC;width:100%;height:500px;font-size:13px;">
	<tr>
		<td style="border:1px solid #ECECEC;width:300px;vertical-align:top;text-align:left;" rowspan="2">
			<div style="background-color:#DCDCDC;height:30px;line-height:30px;font-weight:bold;text-align:center;">流程任务</div>
			<div id="process_role_tree" style="height:480px;">
				<ul>
				{foreach $PROCESSES_ROLE as $role}	
					<li id="PRO_{$role.guid}" >{$role.name}
						<ul>
						{foreach $role.tasks as $task}
							<li id="TAS_{$task.guid}" >{$task.name}
						{/foreach}								
						</ul>
				{/foreach}			
				</ul>
			
			</div>	
		</td>
		<td id="process_role_assign_title" style="border:1px solid #ECECEC;height:30px;background-color:#DCDCDC;font-weight:bold;text-align:center;" colspan="3"></td>
	</tr>

	<tr>
		<td style="border:1px solid #ECECEC;vertical-align:top;">
			<div style="height:25px;line-height:25px;background-color:#ECECEC;font-weight:bold;" >可选用户</div>
			<table id="validated_user_list"></table>
			<div id="validated_user_list_pager"></div>
		</td>
		<td style="border:1px solid #ECECEC;width:40px;">
			<button id="process_role_assign" title="增加" >>></button><br/><br/>
			<button id="process_role_remove" title="删除" ><<</button>
		</td>
		<td style="border:1px solid #ECECEC;vertical-align:top;">
			<div style="height:25px;line-height:25px;background-color:#ECECEC;font-weight:bold;" >已选用户</div>
			<table id="selected_user_list"></table>
			<div id="selected_user_list_pager"></div>
		
		</td>
	</tr>
	

</table>




<script>
{literal}
	$(window).resize(function(event){		
		var newWidth = $(window).width();
		var gridWidth = (newWidth-340)/2-7;
		$("#validated_user_list").setGridWidth(gridWidth,true);
		$("#selected_user_list").setGridWidth(gridWidth,true);
	});
	
	$("#process_role_tree").fancytree({		
		activate: function(event, data){
			var node = data.node;
			var info = node.key.split("_");
			if(info[0] == "PRO")
			{				
				$("#process_role_assign_title").html(node.title+"：【流程管理员】");				
			}
			else
			{
				$("#process_role_assign_title").html(node.parent.title+"：《"+node.title+"》");				
			}	
			$("#validated_user_list").setGridParam({postData:{role_type:info[0],guid:info[1]}});
			$("#validated_user_list").trigger("reloadGrid");
			$("#selected_user_list").setGridParam({postData:{role_type:info[0],guid:info[1]}});
			$("#selected_user_list").trigger("reloadGrid");
			
			$("#process_role_assign").data("role_guid",info[1]);
			$("#process_role_assign").data("role_type",info[0]);
			$("#process_role_remove").data("role_guid",info[1]);
			$("#process_role_remove").data("role_type",info[0]);
			
			$("#process_role_remove").removeData("user_id");
			$("#process_role_remove").removeData("user_type");
			$("#process_role_assign").removeData("user_id");
			$("#process_role_assign").removeData("user_type");
			
		}	
	});
	
	
	$("#validated_user_list").jqGrid(
      {
        url : "index.php?module=WFRole&action=getUserListAjax&data_type=validated",		
		height:380,
        datatype : "json",
		hidegrid: false,
        colNames : [ '名称', '类别','状态' ],
        colModel : [ 
					 	
                     {name : 'name',index : 'name',align:'center'}, 
					 {name : 'type',index : 'type',sortable:false,align:'center'},  
                     {name : 'status',index : 'status',sortable:false,align:'center'}
 
                   ],
        rowNum : 200,
        rowList : [ 200 ],
        pager : '#validated_user_list_pager',
        sortname : 'name',
        mtype : "post",
        viewrecords : true,
        sortorder : "desc",
       // caption : "可选用户",
		onSelectRow:function(rowid,status){
			$("#process_role_assign").data("user_id",rowid);
			$("#process_role_assign").data("user_type",$(this).getCell(rowid,"type"));
		}
		
      });
  
	$("#selected_user_list").jqGrid(
      {
        url : "index.php?module=WFRole&action=getUserListAjax&data_type=selected",		
		height:380,
        datatype : "json",
		hidegrid: false,
        colNames : [ '名称','类别', '状态' ],
        colModel : [ 
 					 	
                     {name : 'name',index : 'name',align:'center'}, 
					 {name : 'type',index : 'type',sortable:false,align:'center'},  
                     {name : 'status',index : 'status',sortable:false,align:'center'}					
                   ],
        rowNum : 200,
        rowList : [ 200 ],
        pager : '#selected_user_list_pager',
        sortname : 'id',
        mtype : "post",
        viewrecords : true,
        sortorder : "desc",
       // caption : "已选用户",
		onSelectRow:function(rowid,status){
			$("#process_role_remove").data("user_id",rowid);
			$("#process_role_remove").data("user_type",$(this).getCell(rowid,"type"));
		}		
      }); 
	  

	$("#process_role_assign").button().click(function(){
		var role_guid = $(this).data("role_guid");
		var role_type = $(this).data("role_type");
		var user_id = $(this).data("user_id");
		var user_type = $(this).data("user_type");		
		if(typeof(user_id) == "undefined") 
		{			
			zswitch_open_messagebox("role_no_select_dlg","流程角色","请选择要要添加的用户或用户组。",150,350);
		}
		else
		{
			$.getJSON("index.php?module=WFRole&action=assignUserAjax",
			          {
					     role_guid:role_guid,
					     role_type:role_type,
					     user_id:user_id,
					     user_type:user_type					   
					  },
					  function(data){
						if(data.type != "success")
						{
							zswitch_open_messagebox("role_no_select_dlg","流程角色","增加失败！",150,350);
						}
						else
						{
							$("#validated_user_list").trigger("reloadGrid");
							$("#selected_user_list").trigger("reloadGrid");
						}
			          });		
		}

		
	});	
	
	$("#process_role_remove").button().click(function(){
		var role_guid = $(this).data("role_guid");
		var role_type = $(this).data("role_type");
		var user_id = $(this).data("user_id");
		var user_type = $(this).data("user_type");		
		if(typeof(user_id) == "undefined") 
		{			
			zswitch_open_messagebox("role_no_select_dlg","流程角色","请选择要要删除的用户或用户组。",150,350);
		}
		else
		{
			$.getJSON("index.php?module=WFRole&action=unassignUserAjax",
			          {
					     role_guid:role_guid,
					     role_type:role_type,
					     user_id:user_id,
					     user_type:user_type					   
					  },
					  function(data){
						if(data.type != "success")
						{
							zswitch_open_messagebox("role_no_select_dlg","流程角色","删除失败！",150,350);
						}
						else
						{
							$("#validated_user_list").trigger("reloadGrid");
							$("#selected_user_list").trigger("reloadGrid");
						}
			          });		
		}		
	});	

   
	$(window).resize();
{/literal}
</script>