<table cellspacing="0" style="width:100%;height:100%;">
{for $row=1 to $HOME.rows}
	<tr>
		{for $col = 1 to $HOME.cols }
			<td style="width:{$HOME.width}%;">
				<div class="home_view_cell_box" style="height:{$HOME.height}px;">
					<div class="home_view_cell_title" >{$HOME.cells[$row][$col].title}</div>
					<div class="home_view_cell_content" url="{$HOME.cells[$row][$col].url}" style="height:{$HOME.height-20}px;overflow:auto;"></div>
				</div>
			</td>
		{/for}
	</tr>
{/for}	
</table>

<script type="text/javascript">
$(".home_view_cell_content").each(function(){
	var url = $(this).attr("url");
	zswitch_show_progressbar($(this),"home_cell_load_progressbar");
	if(url != "") $(this).load(url);
	else $(this).html("url不存在！");
});




</script>