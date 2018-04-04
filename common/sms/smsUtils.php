<?php
//$MAS_CONFIG = require(_ROOT_DIR.'/config/cmmas.conf.php');
function submitSMS($calleeid,$content)
{
	if(empty($calleeid)) return array('result'=>false,'message'=>'电话号不能为空');
	global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$MAS_CONFIG,$CURRENT_USER_NAME;
	//$cont = "{$content}[{$MAS_CONFIG['sign']}]";
    $charlen = mb_strlen($content,"UTF8") + 6;//短信签名固定为6个字符
    $sms_num =  $charlen > 65 ? ceil($charlen/62) : 1;//65个字符以内为一条短信，超过65个字符为62个字符一条短信，不区分中英文 // //
	$recordid = getNewModuleSeq('sms_notify');
	$sql = "insert into sms_notify(id,userid,groupid,callerid,calleeid,dir,send_time,content,state,sms_num) ";
	$sql .= "values({$recordid},{$CURRENT_USER_ID},{$CURRENT_USER_GROUPID},'{$CURRENT_USER_NAME}','{$calleeid}','send',now(),'{$content}','wait',{$sms_num});";
	$APP_ADODB->Execute($sql);
	return array('result'=>true,'message'=>'发送成功');
}


//发送短链接短信
function shortSubmitSMS($calleeid,$_POST){
    if(empty($calleeid)) return array('result'=>false,'message'=>'电话号不能为空');
	global $APP_ADODB,$CURRENT_USER_ID,$CURRENT_USER_GROUPID,$MAS_CONFIG,$CURRENT_USER_NAME;
	
    if(is_file(_ROOT_DIR."/webservices/qiDianapi.class.php"))
	{
		require_once(_ROOT_DIR."/webservices/qiDianapi.class.php");
		$qidianmod= "qiDianapi";
		$qidianSdk = new $qidianmod();
	}else{
		return array('result'=>false,'message'=>'系统发送错误');
	}
	
	if(is_file(_ROOT_DIR."/config/isapi.conf.php"))
	{
		$apiArray = require_once(_ROOT_DIR."/config/isapi.conf.php");
		
	}else{
		return array('result'=>false,'message'=>'系统发送错误');
	}
	$caculate_data = json_decode($_POST['caculate_data'],true);
	$data['owner'] = $caculate_data['owner'];
	$data['license_no'] = $caculate_data['license_no'];
	$data['mobile'] = $calleeid;
	$data['user_line'] = $CURRENT_USER_NAME;//坐席号
	$data['custom_name'] = $apiArray['user'];
	$data['custom_content'] = $_POST['self_define_content'];
	$data['premium_result'] = $_POST['caculate_data'];
	$smsResult = $qidianSdk->getCliectSdk('sms.link',$data,'POST');
     if(!is_array($smsResult)){ 
    	return array('result'=>false,'message'=>'接口发生错误');
    }
    if($smsResult['code'] !=0){
    	return array('result'=>false,'message'=>$smsResult['describe']);
    }

	$content = $_POST['content'] . $smsResult['data']['url'].$_POST['short_sms_after'];
    $charlen = mb_strlen($content,"UTF8") + 6;//短信签名固定为6个字符
    $sms_num =  $charlen > 65 ? ceil($charlen/62) : 1;//65个字符以内为一条短信，超过65个字符为62个字符一条短信，不区分中英文 // //
	$self_define_content = $_POST['self_define_content'];
   
	$recordid = getNewModuleSeq('sms_notify');
	$sql = "insert into sms_notify(id,userid,groupid,callerid,calleeid,dir,send_time,content,state,sms_num,self_define_content) ";
	$sql .= "values({$recordid},{$CURRENT_USER_ID},{$CURRENT_USER_GROUPID},'{$CURRENT_USER_NAME}','{$calleeid}','send',now(),'{$content}','wait',{$sms_num},'{$self_define_content}');";
	$APP_ADODB->Execute($sql); 
	return array('result'=>true,'message'=> '报价信息链接：<a target="blank" href="'.$smsResult['data']['url'].'"> '.$smsResult['data']['url'].'</a>');
}



?>