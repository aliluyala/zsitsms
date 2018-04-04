<?php
/**
 * 项目：          保险数据管理平台
 * 文件名:         IDQProxyPICC.php
 * 版权所有：      2015 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *
 * 人保承保系统查询代理
 *
 * 错误代码
 * 0 ：成功
 * 1 ：参数错误
 * 2 ：没查到数据
 * 10 ：保险帐号无效
 *
 **/
  // date_default_timezone_set('Asia/Chongqing');
 // $post_json = '{
	// "mbid": "92189",
	// "logid": "162641",
	// "license_no": "渝AFT755",
	// "model": "奥迪FV7201TFCVTG",
	// "model_code": "",
	// "owner": "向伟",
	// "identify_no": "51232419670904135X",
	// "vin_no": "LFV3A24F5A3026476",
	// "engine_no": "BPJ189582"
// }';
// $_POST = json_decode($post_json,true);
set_time_limit(300);
error_reporting(0);
ini_set( 'display_errors', 'Off' );
function return_ajax($code,$describe,$data=null)
{
	$res = array();
	$res['code'] = $code;
	$res['describe'] = $describe;
	$res['data'] = $data;
	return json_encode($res);	
}
//if($_SERVER['REMOTE_ADDR'] != '10.9.0.1') die('Access forbidden!');
$finfo = pathinfo(__FILE__);
//定义目录常量
define('_ROOT_DIR',$finfo['dirname'].'/..');
require(_ROOT_DIR.'/include/InsDataExtraction/extraction.php');
require(_ROOT_DIR.'/include/InsDataExtraction/InsDataExtractionCQPicc.class.php');
if(!array_key_exists('method',$_GET))
{
	die(return_ajax(1,'参数错误:没有参数[method]。'));
}
$method = $_GET['method'];
if(!array_key_exists('ins_uid',$_GET)||!array_key_exists('ins_pwd',$_GET))
{
	die(return_ajax(1,'参数错误:没有用户、密码。'));
}	   
$ins_uid = $_GET['ins_uid'];
$ins_pwd = $_GET['ins_pwd']; 
$conf = array('uid'=>$ins_uid ,'pwd'=>$ins_pwd);
 
$cookiePath = _ROOT_DIR.'/cache';
if(!is_dir($cookiePath)) mkdir($cookiePath,true); 
$ide = new InsDataExtractionCQPicc($conf,$cookiePath) ;

if($method != 'queryAll' && empty($_POST['policy_no']))
{
//	die(return_ajax(1,'参数错误:至少需要保单号。'));
}
	
$data = null;


if($method == 'queryAll')
{
	$data = array('policy_list'=>array(),
	              'auto_info'=>array(),
                  'cinsured_info' => array(),
                  'claims_info' => array(),
				  'price' => array(),	
				  );
		 
	$pl = $ide->queryPolicyList($_POST);

	if(!is_array($pl)) 
	{
		$data = $pl;
	}	
	else
	{
		$lastPolicyNo = '';
		$lastTime = 0;
		$firstQL = true;
		foreach($pl as $po)
		{
			if($po['insurance_company'] == 'PICC')
			{
				$po1 = $ide->getPolicyInfo($po['policy_no'],null);
				if(!is_array($po1) && $firstQL)
				{
					$po1 = $ide->queryLastYearBI($po['license_no']);
					$firstQL = false;
					
				}
				
				if(is_array($po1))
				{
					if($po['end_date_timestamp']>$lastTime)
					{
						$lastPolicyNo = $po['policy_no'];
						$lastTime = $po['end_date_timestamp'];
					}
					$data['policy_list'][] = $po1;	
				}
				else
				{										
					$data['policy_list'][] = $po;					
				}
				
				$claims = $ide->getClaimsInfo($po['policy_no'],null);
				if(is_array($claims))
				{
					$data['claims_info'][$po['policy_no']] = $claims;
				}
								
			}
			else
			{
				$data['policy_list'][] = $po; 
			}		
		}
		
		if(!empty($lastPolicyNo))
		{
			$auto = $ide->getAutoInfo($lastPolicyNo,null);
			if(is_array($auto))
			{
				$data['auto_info'] = $auto;
			}
			$cinsured = $ide->getCinsuredinfo($lastPolicyNo,null);	
			if(is_array($cinsured))
			{
				$data['cinsured_info'] = $cinsured;
			}			
		}
		
	}		
	
}
elseif($method == 'queryPolicy')
{
	$data = $ide->getPolicyInfo($_POST['policy_no'],$_POST);
}
elseif($method == 'queryClaims')
{
	$data = $ide->getClaimsInfo($_POST['idno'],$_POST);		
}
elseif($method == 'queryCustom')
{
	$data = $ide->queryCustom($_POST['idno'],$_POST);		
}
elseif($method == 'premium')
{
	$data = $ide->queryCustom($_POST);
}

if($data === -1)
{
	die(return_ajax(10,"{$_SERVER['HTTP_HOST']}:保险帐号无效<{$ins_uid}>"));
}
elseif($data === -2)
{
	die(return_ajax(12,"{$_SERVER['HTTP_HOST']}:访问人保承保平台失败!"));
}
elseif($data === -3)
{
	die(return_ajax(13,"{$_SERVER['HTTP_HOST']}:访问人保承保平台URL错误,<{$method}>!"));
}
elseif(!$data )
{
	$err = "{$_SERVER['HTTP_HOST']}:没有查到数据";
	if(!empty($_POST['policy_no']))
	{
		$err .= '<保单号:'.$_POST['policy_no'].'>';
	}
	if(!empty($_POST['vin_no']))
	{
		
		$err .= '<车辆识别码:'.$_POST['vin_no'].'>';
	}		
	
	die(return_ajax(2,$err));
}
die(return_ajax(0,'成功!',$data));	
 
 ?>