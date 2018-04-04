<div id="import_dialog_content_box" >
<div id="import_dialog_title">
	<ul>
	<li>数据导入中......</li>
	</ul>
</div>

<div id="import_dialog_info" style="font-size:12px">
	<ul>
		<li>正在导入数据，请不要关闭浏览器，也不要退出些页面。</li>
	</ul>
</div>

<div style="padding-top:40px;padding-left:251px;">
<img src="{$IMAGES}/loading_a.gif" width="64px" height="64px" />
</div>
<div>
	<table id="import_conter_box" border="0" width="100%">
		<tr><td style="width:50%;text-align:right;height:20px;text-size:14px;font-weight:bold;">已处理行数：  </td><td id="rows"     style="text-align:left;text-size:14px;font-weight:bold;">0</td></tr>
		<tr><td style="width:50%;text-align:right;height:20px;text-size:14px;font-weight:bold;">无效数据：    </td><td id="invalids" style="text-align:left;text-size:14px;font-weight:bold;">0</td></tr>		
		<tr><td style="width:50%;text-align:right;height:20px;text-size:14px;font-weight:bold;">新增条数：    </td><td id="inserts"  style="text-align:left;text-size:14px;font-weight:bold;">0</td></tr>
		<tr><td style="width:50%;text-align:right;height:20px;text-size:14px;font-weight:bold;">重复条数：    </td><td id="repeats"  style="text-align:left;text-size:14px;font-weight:bold;">0</td></tr>
		<tr><td style="width:50%;text-align:right;height:20px;text-size:14px;font-weight:bold;">重复更新条数：</td><td id="updates"  style="text-align:left;text-size:14px;font-weight:bold;">0</td></tr>
		<tr><td style="width:50%;text-align:right;height:20px;text-size:14px;font-weight:bold;">重复丢弃条数：</td><td id="discards" style="text-align:left;text-size:14px;font-weight:bold;">0</td></tr>
		
	</table>

</div>

</form>

<script type="text/javascript">
	var importDataUrl = "{$IMPORT_DATA_URL}";
	var importProgressUrl = "{$IMPORT_PROGRESS_URL}";
	var importruning = true;
	var import_rows     =0;
	var import_invalids =0;
	var import_inserts  =0;
	var import_repeats  =0;
	var import_updates  =0;
	var import_discards =0;
	
	var import_rows_diff     =0;
	var import_invalids_diff =0;
	var import_inserts_diff  =0;
	var import_repeats_diff  =0;
	var import_updates_diff  =0;
	var import_discards_diff =0;
	
	var import_rows_timed     =500;	
	var import_invalids_timed =500;	
	var import_inserts_timed  =500;	
	var import_repeats_timed  =500;	
	var import_updates_timed  =500;	
	var import_discards_timed =500;
	
	{literal}
	function updateImportRowsCounter()
	{
		if(import_rows_diff <= 0) return ;
		import_rows++;
		import_rows_diff--;
		$("#import_conter_box").find("#rows").html(import_rows);
		setTimeout("updateImportRowsCounter();",import_rows_timed);
		
	}
	function updateImportInvalidsCounter()
	{
		if(import_invalids_diff <= 0) return ;
		import_invalids++;
		import_invalids_diff--;
		$("#import_conter_box").find("#invalids").html(import_invalids);
		setTimeout("updateImportInvalidsCounter();",import_invalids_timed);
		
	}	
	function updateImportInsertsCounter()
	{
		if(import_inserts_diff <= 0) return ;
		import_inserts++;
		import_inserts_diff--;
		$("#import_conter_box").find("#inserts").html(import_inserts);
		setTimeout("updateImportInsertsCounter();",import_inserts_timed);
		
	}	
	function updateImportRepeatsCounter()
	{
		if(import_repeats_diff <= 0) return ;
		import_repeats++;
		import_repeats_diff--;
		$("#import_conter_box").find("#repeats").html(import_repeats);
		setTimeout("updateImportRepeatsCounter();",import_repeats_timed);	
		
	}
	function updateImportUpdatesCounter()
	{
		if(import_updates_diff <= 0) return ;
		import_updates++;
		import_updates_diff--;
		$("#import_conter_box").find("#updates").html(import_updates);
		setTimeout("updateImportUpdatesCounter();",import_updates_timed);	
		
	}		
	function updateImportDiscardsCounter()
	{
		if(import_discards_diff <= 0) return ;
		import_discards++;
		import_discards_diff--;
		$("#import_conter_box").find("#discards").html(import_discards);	
		setTimeout("updateImportDiscardsCounter();",import_discards_timed);	
	}		
	
	function getImportProcessData()
	{
		$.get(importProgressUrl,function(data){			
			import_rows_diff     = data.data.rows     - import_rows    ;
			import_invalids_diff = data.data.invalids - import_invalids;
			import_inserts_diff  = data.data.inserts  - import_inserts ;
			import_repeats_diff  = data.data.repeats  - import_repeats ;
			import_updates_diff  = data.data.updates  - import_updates ;
			import_discards_diff = data.data.discards - import_discards;

			import_rows_timed     = import_rows_diff    ==0?500:2000/import_rows_diff    ;
			import_invalids_timed = import_invalids_diff==0?500:2000/import_invalids_diff;
			import_inserts_timed  = import_inserts_diff ==0?500:2000/import_inserts_diff ;
			import_repeats_timed  = import_repeats_diff ==0?500:2000/import_repeats_diff ;
			import_updates_timed  = import_updates_diff ==0?500:2000/import_updates_diff ;
			import_discards_timed = import_discards_diff==0?500:2000/import_discards_diff;
			
			updateImportRowsCounter();
			updateImportInvalidsCounter();
			updateImportInsertsCounter();
			updateImportRepeatsCounter();
			updateImportUpdatesCounter();
			updateImportDiscardsCounter();
		
		},'json');
		if(!importruning) return ;
		
		setTimeout("getImportProcessData();",2000);		
	}
		
	$('#import_dialog_content_box').load(importDataUrl,function(){		
		importruning = false;
	});	
	getImportProcessData();
	{/literal}
</script>

</div>
