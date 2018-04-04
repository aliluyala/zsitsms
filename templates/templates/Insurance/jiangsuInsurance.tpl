<form id="Jiangsu_insurance_form" style="margin-bottom:15px;">
<input type="hidden" name="method" value="{$method}">
{if $isLogin eq 0}
<div id="imageDiv">
验证码：<input type="text" name="image"> <img id="image"  src="{$imageSrc}" /> <input type="button" value="刷新" onclick="account.jiangsu_image_reload()" />
</div>
{/if}
<div style="display:none;">查询条件: <select id="dimensionSelect" name="dimensionSelect" >
					<option value="01">保单号</option>	
					<option value="02" selected>车架号</option>	
					<option value="03">号牌种类+号牌号码</option>	
					<option value="04">证件类型+证件号码</option>	
					<option value="05">事故号+事故日期</option>				
					<option value="06">姓名+身份证后四位</option>	
			   </select> 
			   <span class="spanCon">
					<span>保单号<input type="text" id="policyNo" name="policyNo" title="保单号" value=""></span>
					<span>车架号<input type="text" name="vin" value ="{$accountInfo['vin']}"> </span>
					<span>  
					      号牌种类  <select name="licensetype" id="licensetype" title="号牌种类" value="">
									<option value=""></option>
									<option value="01">大型汽车号牌</option>						
									<option value="02" selected>小型汽车号牌</option>							
									<option value="03">使馆汽车号牌</option>							
									<option value="04">领馆汽车号牌</option>							
									<option value="05">境外汽车号牌</option>							
									<option value="06">外籍汽车号牌</option>						
									<option value="07">两、三轮摩托车号牌</option>						
									<option value="08">轻便摩托车号牌</option>							
									<option value="09">使馆摩托车号牌</option>							
									<option value="10">领馆摩托车号牌</option>							
									<option value="11">境外摩托车号牌</option>							
									<option value="12">外籍摩托车号牌</option>						
									<option value="13">农用运输车号牌</option>						
									<option value="14">拖拉机号牌</option>							
									<option value="15">挂车号牌</option>							
									<option value="16">教练汽车号牌</option>							
									<option value="17">教练摩托车号牌</option>							
									<option value="18">试验汽车号牌</option>							
									<option value="19">试验汽车号牌</option>							
									<option value="20">临时入境汽车号牌</option>							
									<option value="21">临时入境摩托车号牌</option>							
									<option value="22">临时行驶车号牌</option>							
									<option value="23">公安警车号牌</option>							
									<option value="24">公安民用号牌</option>							
									<option value="25">其他号牌</option>						
									<option value="31">武警号牌</option>							
									<option value="32">军队号牌</option>							
									<option value="51">大型新能源汽车</option>							
									<option value="52">小型新能源汽车</option>							
									<option value="99">其他号牌</option>							
			 			</select>
						号牌号码<input type="text" id="carmark" name="carmark" title="车牌号" value="{$accountInfo['plate_no']}">
					</span>
					<span>
					      证件类型<select id="credentialcode" name="credentialcode" title="证件类型" value="">					
								         <option value="01">居民身份证</option>						
		 				             </select>
						  证件号码<input type="text" id="credentialno" name="credentialno" title="证件号码" value="{$accountInfo['id_code']}">
					</span>
					<span>	
					      事故号<input type="text" id="cacciid" name="cacciid" title="事故号" value="">
						  事故日期<input type="text" id="taccidt" name="taccidt" title="事故日期" value="" >
                    </span>
					<span>   
					      姓名<input type="text" id="dname" name="dname" title="姓名" value="{$accountInfo['owner']}">	
                          身份证后四位<input type="text" id="lastNo" name="lastNo" title="身份证后四位" value="{$accountInfo['id_code']}">		
                    </span>							
			  </span>
			   
			   
</div>
{if $isLogin eq 0}
<input type="button" value="查询" id = "searchBtn" onclick="jiangsu_insurance_search()" >
{/if}
<!--
<div>查询信息: {foreach from=$selectInfo key=i item=list}
<input type="checkbox" name="CheckboxGroup1[]" value="{$i}"> {$list}
{/foreach}
</div>
-->
</form>

<style>
		.info-btn { 
			text-decoration:none;
			border:1px solid #ddd;
			padding: 10px;
			border-radius: 5%;
		}
		.info-btn:hover{
			text-decoration:none;
			border-color:#a6c9e2;
		}

		.info-active {
			border:1px solid #999;
			background: #a6c9e2;
		}
		.info-table {
			margin-top:15px;
			display: none;
		}
		.info-table-active{
			display: block;
		}

</style>

<div class="js_info">
</div>
<script>
	if({$isLogin} !=0){

		jiangsu_insurance_search();
	}
	function infoClick(btnThis){
		var index = $(".info-btn").index(btnThis);
		$('.info-btn').attr('class','info-btn');
		$(btnThis).attr('class','info-btn info-active');
		$('.info-table').attr('class','info-table');
		if(index == 0 || index==1){
			$(".info-table:eq("+index+")").attr('class','info-table info-table-active');
			$(".info-table:eq("+(index+2)+")").attr('class','info-table info-table-active');
			if(index == 0){
				$(".info-table:eq("+(index+5)+")").attr('class','info-table info-table-active');
			}
		}else if(index == 2){
			$(".info-table:eq("+(index+2)+")").attr('class','info-table info-table-active');
		}

	}
</script>
<script type="text/javascript">
$('.spanCon').find('span').hide();
$('.spanCon').find('span').eq(1).show();
$('#dimensionSelect').change(function(){
     var val =$(this).val();
    $('#dimensionSelect option').each(function(){
	   
	    if($(this).val() == val){ 
		   i = $(this).index();
		  $('.spanCon').find('span').eq(i).show().siblings('span').hide();
		   
		}
	
	})

});
 function jiangsu_insurance_search(){
    if($("#dimensionSelect").val()==03){
	    if($("#carmark").val().length != 7 ||$("#carmark").val().substring(0,1) !='苏' ){
		   alert('车牌号不对');
		   return;
		}
	}
	 var url = "index.php?module=Insurance&action=JiangsuInsurance&method=search";
     $.post(url,$("#Jiangsu_insurance_form").serialize(),function(msg){
     	var btnTitle = new Array("去年保险信息","历史理赔信息","交通违法信息");
     	var titleInfo = '';
     	for(var u=0;u<btnTitle.length;u++){
     		if(u==0){
     			titleInfo+="<a class='info-btn info-active'  onClick='infoClick(this);'>"+btnTitle[u]+"</a>";
     		}else{
     			titleInfo+="<a class='info-btn'  onClick='infoClick(this);'>"+btnTitle[u]+"</a>";
     		}
     		
     	}


     	var title = new Array("历年交强险承保信息","历年交强险理赔信息","历年商业险承保信息","历年商业险理赔信息","历年交管违法信息");
     	console.log(msg);
		//msg =eval("("+msg+")");
		if(msg.type =='success'){

			var info = '';
			var names = '';
			var info_xz = '';
			for(i=0;i<msg.data.length;i++){
				
				var keyArray = new Array();
				if(i==0 || i==2){
					info+="<div class='info-table info-table-active'>";
				}else{
					info+="<div class='info-table'>";
				}
				
				info+="<div style='font-weight:bold;font-size:18px;'>"+msg.data[i]['title']+"</div>";	
			    info+="<table style='border:1px solid #eee;' >";
				info+="<tr style='font-weight:bold;'>";
				
				for(j=0;j<msg['data'][i]['names'].length;j++){

					switch(i){
						case 0:
							names = new Array("保险公司","保单号","终保日期");
							if(contains(names,msg['data'][i]['names'][j])){
								keyArray.push(contains(msg['data'][i]['names'],msg['data'][i]['names'][j]));
								info+="<td>"+msg['data'][i]['names'][j]+"</td>";
							}
							break;
						case 2:
							names = new Array("保险公司","保单号","终保日期","操作");
							if(contains(names,msg['data'][i]['names'][j])){
								keyArray.push(contains(msg['data'][i]['names'],msg['data'][i]['names'][j]));
								var info_xz_s = '';
								if(msg['data'][i]['names'][j] == "操作"){
									info_xz_s=keyArray[keyArray.length-1];
									info_xz+="<div class='info-table info-table-active'>";	
								    info_xz+="<table style='border:1px solid #eee;' >";
									info_xz+="<tr style='font-weight:bold;'>";
									info_xz+="<td>购买险种</td></tr>";

								}else{
									info+="<td>"+msg['data'][i]['names'][j]+"</td>";
								}
								
							}
							break;

						case 1:
						case 3:
							names = new Array("承保公司","结案时间","号牌号码","出险时间","报案时间","理赔类型");
							if(contains(names,msg['data'][i]['names'][j])){
								keyArray.push(contains(msg['data'][i]['names'],msg['data'][i]['names'][j]));
								info+="<td>"+msg['data'][i]['names'][j]+"</td>";
							}
							break;
						default:
							info+="<td>"+msg['data'][i]['names'][j]+"</td>";
							keyArray.push(contains(msg['data'][i]['names'],msg['data'][i]['names'][j]));
					}
					
				}

				info+="</tr>";
				if(msg['data'][i]['values'] == ''){
					info+="<tr><td colspan='"+msg['data'][i]['names'].length+"'>没有查询到对应信息<td></tr>";
				}else{
					switch(i){
						case 0:
						case 2:
								info+="<tr >";
								for(u=0;u<keyArray.length;u++){
									if(keyArray[u] == info_xz_s){
										info_xz+="<tr >";
										info_xz+="<td>"+msg['data'][i]['values'][0][keyArray[u]]+"&nbsp;&nbsp;</td>";
										info_xz+="</tr></table></div>";
									}else{
										info+="<td>"+msg['data'][i]['values'][0][keyArray[u]]+"&nbsp;&nbsp;</td>";
									}
									
								}
								info+="</tr>";
							
							break;
							
						default:
							
							for(z=0;z<msg['data'][i]['values'].length;z++){
								info+="<tr >";
								for(u=0;u<keyArray.length;u++){
									info+="<td>"+msg['data'][i]['values'][0][keyArray[u]]+"&nbsp;&nbsp;</td>";
								}
								info+="</tr>";
							}
							
							break;
					}	
				}
				info+="</table></div>";
			}
			$('form').css('display','none');
		    $('.js_info').html(titleInfo + info + info_xz);
		}else{
		   alert(msg.data);
		}
            
     },'json');
}

function contains(arr, obj) {
  var i;
  for(i in arr){
    if (arr[i] === obj) {
      return i;
    }
  }
  return false;
}

</script>