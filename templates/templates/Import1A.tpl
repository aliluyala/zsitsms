<div id="import_dialog_title">
	<ul>
		<li>上传数据文件</li>
	</ul>
</div>
<div id="import_dialog_info" style="font-size:12px;">
	<ul>
		<li style="margin-top:5px">导入数据前请按规范整理导入数据文件，具体要求请参考帮助文档，或联系技术支持。</li>
		<li style="margin-top:5px">数据文件可以是EXECL文档或<span style="color:red;">文本文件（推荐）</span>，扩展名:xls,xlsx,csv,txt。</li>
		<li style="margin-top:5px">建议将文本文件保存为“UTF-8 无BOM”格式。</li>
	</ul>
</div>
<div style="height:40px;line-height:40px;border-style:none none solid none;border-width:1px;border-color:#79b7e7;font-weight:bold;">
<label for="select_uploaded_file_checkbox" >导入已上传文件</label><input id="select_uploaded_file_checkbox" type="checkbox"/>
<button id="clear_upload_files"  style="height:24px;">清理上传文件</button>
<div id="clear_upload_files_confirm_dialog">

</div>
</div>
<div id="import_select_uploaded_file_ui" style="margin-top:5px;height:25px;line-height:25px;display:none;">
	<select  id="uploaded_file_list_select">
		<option value="">请选择文件</option>
		{html_options options=$FILE_LIST }
	</select>
</div>
<div id="import_uploadfile_ui" style="margin-top:5px;">
</div>

<form id="import_dialog_form_id_step1">
<input type="hidden" name="dataFile"  />
</form>
<script>
var importUploadUrl = "index.php?module={$MODULE}&action=import&step=uploadFile";
var importUploadProgressUrl = "index.php?module={$MODULE}&action=import&step=uploadFileProgress";
var importClearUploadFileUrl = "index.php?module={$MODULE}&action=import&step=clearFile";
{literal}

	var uploadFileObj = $("#import_uploadfile_ui").APCUpload({
										  appendTo: "#main_view_client",
			                              dialogClass:"dialog_default_class",
										  type: "single",
										  uploadUrl: importUploadUrl,
									      progressUrl: importUploadProgressUrl,
		                                  filter: [
		                                  			//This will almost always work(.FILETYPE)
		                                  			".txt",".csv",".xls",".xlsx"		                                  		 
												  ]

									     })
										 .bind("apcuploadcomplete",{formid:"import_dialog_form_id_step1"},function(event,ui){

											  if(ui.uploadStatus.length>0 && ui.uploadStatus[0].error==0)
											  {
												  $("#"+event.data.formid).find("input[name='dataFile']").val(ui.uploadStatus[0].file);
											  }

										  });
	$("#select_uploaded_file_checkbox").button().bind("click",{uploadFileObj:uploadFileObj},function(event){
		if($(this).prop("checked"))
		{
			$("#import_select_uploaded_file_ui").show();
			event.data.uploadFileObj.hide();
		}
		else
		{
			$("#import_select_uploaded_file_ui").hide();
			event.data.uploadFileObj.show();
		}
	});

	$("#uploaded_file_list_select").change(function(){
		$("#import_dialog_form_id_step1").find("input[name='dataFile']").val($(this).val());
	});

	$("#clear_upload_files_confirm_dialog").dialog({
		 appendTo: "#main_view_client",
		 dialogClass: "dialog_default_class",
		 modal:true,
		 title: "清理确认",
		 autoOpen: false,
		 width: 400,
		 buttons:{
			'确定':function(){
				$("#uploaded_file_list_select").html('<option value="">请选择文件</option>');
				$("#import_dialog_form_id_step1").find("input[name='dataFile']").val("");
				$.get(importClearUploadFileUrl);
				$(this).dialog("close");
			},
			'取消':function(){
				$(this).dialog("close");
			}
		 },
		 open:function(event,ui){
			var html = '<p style="font-size:12px;"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
			html += "“清理上传文件”将删除你已经上传到服务器上的所有文件。<br/>";
			html += "确认要清理请点“确定”键，不清理请点“取消”键。</p>";
			$(this).html(html);
		 }
	});


	$("#clear_upload_files").button().click(function(){
		$("#clear_upload_files_confirm_dialog").dialog("open");

	});






{/literal}
</script>