<div id="import_dialog_title">
	<ul>
		<li>第一步 选择要导入的数据文件</li>
	</ul>
</div>
<div id="import_dialog_info" >
	<ul>
		<li>导入数据前请按规范整理导入数据文件,具体要求请参考帮助文档,或联系技术支持。</li>
		<li>支持EXECL文档和<span style="color:red;">文本格式(推荐)</span>的数据文件导入,文件扩展名:xls,xlsx,csv,txt等。</li>
		<li>建议文本文件的编码格式是“UTF-8 无BOM”,你可以使用“Notepad++”,“记事本”等软件进行编码转换。</li>
	</ul>
</div>
<form>
<div style="margin-top:5px;font-weight:bold;">
	数据文件：<br/>
</div>
<div>
	<input type="file" name="import_upload_file" id="import_upload_file" accept=".txt,.csv,.xls,.xlsx" style="width:100%"/>
</div>
</form>
<script type="text/javascript">
	$('#titlebar_import_data_dlg').data("step","1");
</script>
