{* ***********************************************************************
*  UI类型值: 70
*  说明: 密码设置，用于关联输入。
*
*********************************************************************** *}
<button id="{$FIELDINFO.name}_but" >重置密码</button>
<div id="{$FIELDINFO.name}_dlg" title="重置密码">
	<form id="{$FIELDINFO.name}_form">
	输入新密码:<br/>
	<input type="hidden" name="recordid" value="{$DETAILVIEW_RECORDID}"/>
	<input type="hidden" name="{$FIELDINFO.name}" />
	<input id="{$FIELDINFO.name}_new_one" type="password" class="respinsive_width_95" /><br/><br/>
	再次输入密码:<br/>
	<input id="{$FIELDINFO.name}_new_two" type="password" class="respinsive_width_95" />
	</form>
</div>

<script >
	$("#{$FIELDINFO.name}_but").button().click(function() {
		$("#{$FIELDINFO.name}_dlg").dialog("open");
		return false;
	} );


    $(function() {
	 $("#{$FIELDINFO.name}_dlg").data('max_len',{$FIELDINFO.max_len}).data('min_len',{$FIELDINFO.min_len}).dialog(  {
     autoOpen: false,
     height: 250,
     width: 200,
     modal: true,
	 appendTo: "#main_view_client",
     dialogClass:"dialog_default_class",
     buttons:  {
    "确定":function()  {
		var url = "index.php?module={$MODULE}&action=setPassword";
		var p1 = $("#{$FIELDINFO.name}_new_one").val();
		var p2 = $("#{$FIELDINFO.name}_new_two").val();
		
		var min_len = $(this).data('min_len');
		var max_len = $(this).data('max_len');
		
		if(p1.length<min_len || p1.length>max_len)
		{
			alert("密码最短"+min_len+"位，最长"+max_len+"位。");
		}
		else if(p1 != p2)
		{
			alert("两次输入密码不一致,请重新输入。");
		}
		else
		{
			$("[name={$FIELDINFO.name}]:input").val($.md5(p1));
			zswitch_ajax_load_client_view(url,"{$FIELDINFO.name}_form");
			$( this ).dialog( "close" );
		}
    },
    "取消":function(){
    	$( this ).dialog( "close" );
       }
     }

	 } );


  } );

</script>