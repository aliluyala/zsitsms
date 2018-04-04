<input type="hidden" id="vin_no" value="{$vin_no}">
<input type="hidden" id="model" value="{$model}">
<input type="hidden" id="page" value="{$page}">
<input type="hidden" id="action" value="{$action}">
<input type="hidden" id="rate_table" value="{$rate_table}">
<div style="margin:0px 0px 3px 20px;" id="check_tpl"><span><img src="{$verifyCode}" id="img_check">&nbsp;<button id="verify_refresh">刷新</button>&nbsp;验证码：<input id="check_id" type="text"/></span>&nbsp;<span><button id="policy_calculate_submit_code_btn">查询</button></span></div>
<script>

$("#policy_calculate_submit_code_btn").click(function(){
    var check_id=$("#check_id").val();
    if(check_id=="")
    {
        alert('验证码不能为空');
        return false;
    }

    var url = "index.php?module=PolicyCalculateCom&action={$action}&model={$model}&rate_table={$rate_table}&vin_no={$vin_no}&checkcode="+check_id;

    $.get(url,function(responseText){
        if(responseText.type=="error"){
                alert(responseText.data);
                return false;
        }
        $("#policy_calculate_queryBuyingPrice_dlg").html(responseText);
    });
})

$("#verify_refresh").click(function () {
    var url = "index.php?module=PolicyCalculateCom&action={$action}&model={$model}&rate_table={$rate_table}&vin_no={$vin_no}&verify_refresh=1";
    $.get(url, function (responseText) {
        if(responseText.type=="error"){
            alert(responseText);
            return false;
        }
        $("#policy_calculate_queryBuyingPrice_dlg").html(responseText);
    });
});

</script>


