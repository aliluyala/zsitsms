{include file="TitleBar.tpl"}
<div  id="policy_calculate_content"  style="vertical-align:top;border:1px solid #79b7e7;"></div>

<script>
	$("#policy_calculate_content").load("index.php?module={$MODULE}&action=calculateView&recordid={$RECORDID}");	
</script>