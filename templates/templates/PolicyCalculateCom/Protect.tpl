{if !empty($dza_demandNo)}
	交强险转保码:<input type="hidden" id="dza_demandNo" value="{$dza_demandNo}">{$dza_demandNo}<br />
	验证码:<input type="text" name="dza_check" id="dza_checkcode">
	<img src='data:image/png;base64,{$dza_checkCode}'><br />
{/if}

{if !empty($daa_demandNo)}
	商业险转保码:<input type="hidden" id="daa_demandNo" value="{$daa_demandNo}">{$daa_demandNo}<br />
	验证码:<input type="text" name="daa_check" id="daa_checkcode">
	<img src='data:image/png;base64,{$daa_checkCode}'><br />
{/if}


