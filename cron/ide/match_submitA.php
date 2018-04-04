<?php
/**
 * 项目：          保险电销系统
 * 文件名:         match_submitA.php
 * 版权所有：      2015 Tang DaYong.
 * 作者：          Tang DaYong  
 * 版本：          1.0.1
 *
 * 提交数据匹配请求(CRON)
 **/
define('MATCH_SUBMITA_PHP',1);
set_time_limit(0);
error_reporting(E_ALL);
ini_set( 'display_errors', 'On' );

//if($_SERVER['REMOTE_ADDR'] != '127.0.0.1') die('Access forbidden!');
//最小提交时间间隔ms
$min_request_timed = 5;
//每分钟最大提交数
$max_rate = 600;
//最大失败次数
$max_failures = 10;
//失败等待时间(秒)
$failure_wait_timed = 3600;

$finfo = pathinfo(__FILE__);
//定义目录常量
//define('_ROOT_DIR',$finfo['dirname'].'/../..');
//系统配置
$APP_CONFIG = require(_ROOT_DIR.'/config/config.php');
$dbs = $APP_CONFIG['DBServers']['master']['DBHost'];
$user = $APP_CONFIG['DBServers']['master']['DBUserName'];
$pw = $APP_CONFIG['DBServers']['master']['DBPassword'];
$db = $APP_CONFIG['DBServers']['master']['Database'];
//IDE配置
$IDE_CONFIG = require(_ROOT_DIR.'/config/IDEService.conf.php');


$con = mysql_connect($dbs,$user,$pw);
if(!$con) die('mysql server connect error!');
if(!mysql_select_db($db,$con)) die('select database error!');
mysql_query('set names utf8;');
//检查是否重复执行
$result = mysql_query("select * from match_cron_state where cron_name='match_client'  limit 1;",$con);
if(!$result) die(__LINE__.':database error!');

$row = mysql_fetch_assoc($result);
if(!$row)  die(__LINE__.':database error!');

if($row['state'] > 0 && (time() - $row['last_time']) < 60)
{
	die('cron  runing!');
}	
if($row['state'] == -1)
{
	die('cron deny run!');
}

$cstamp = time();
$sql = "update match_cron_state  set state=1,last_time={$cstamp} where cron_name='match_client';";
mysql_query($sql,$con);
//进入工作循环
$tasks = array();
$prevCheckCronTime = time() - 10 ;
$prevCheckTaskTime = time() - 10 ;
$Logined = false;


$test = 0;
while($test<10)
{
	//$test++;

	if((time() - $prevCheckCronTime) >= 5)
	{
		$result = mysql_query("select * from match_cron_state where cron_name='match_client'  limit 1;",$con);
		$row = mysql_fetch_assoc($result);
		if($row['state'] == -1) break;
		$cstamp = time();
		$sql = "update match_cron_state  set last_time={$cstamp} where cron_name='match_client';";
		mysql_query($sql,$con);	
		$prevCheckCronTime = time();	
	}
	
	if(!$Logined)
	{
		//获取保险公司帐号
		$sql = 'select * from insurance_account_cli ;';
		$result = mysql_query($sql,$con);
		if(!$result && mysql_num_rows($result)<=0)
		{
			//echo 'The insurance company account was not found!<br/>';
			break;
		}
		
		$accs = array();
		while($row = mysql_fetch_assoc($result))
		{
			$acc = array();
			$acc['uid'] = $row['uid'];
			$acc['pwd'] = $row['pwd'];
			$acc['insurance_company'] = $row['insurance_company'];
			$accs[] = $acc;
		}
		
		$url = $IDE_CONFIG['url'].'?module=IDEServiceA&user='.$IDE_CONFIG['user'].'&password='.$IDE_CONFIG['password'].'&method=';
		$data = 'insurance_account='.urlencode(json_encode($accs));
		
		//登录
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url.'login');  
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; Trident/7.0; rv:11.0) like Gecko'); 
		curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept-Language: zh-CN','Accept: text/html, application/xhtml+xml, */*'));
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
		curl_setopt($curl, CURLOPT_POST, 1); 
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
		curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 	    
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		$curlstr  = curl_exec($curl);		
		
		if(!$curlstr)
		{
			$Logined = false;
						
		}
		$curlres  = json_decode($curlstr);
		if(!$curlres )
		{
			$Logined = false;
		}
		elseif( $curlres->code == 0)
		{
			$Logined = true;
		}
		else
		{
			$Logined = false;
		}	
	}
	
	if($Logined)
	{
		if((time() - $prevCheckTaskTime) >= 5)
		{
			//更新任务装态
			$currentDate = date('Y-m-d H:i:s');
			$currentTime = date('H:i:s');
			$sql = "update list_match set tag = 'WAITING'  where tag!= 'COMPLETE' and ( state = 'STOP' or start_time>'{$currentDate}' or (  ";
			$sql .= "(timed1_start > '{$currentTime}' or timed1_end < '{$currentTime}') and  (timed2_start > '{$currentTime}' or timed2_end < '{$currentTime}') ";
			$sql .= " and (timed3_start > '{$currentTime}' or timed3_end < '{$currentTime}') and   (timed4_start > '{$currentTime}' or timed4_end < '{$currentTime}') ) );";
			mysql_query($sql,$con);
			
			
			$sql = "select id, batch from list_match  where tag!= 'COMPLETE';";
			$taskreset = mysql_query($sql,$con);
			while($taskreset && $row = mysql_fetch_assoc($taskreset))
			{
				$sql = "select 1 from list_data where   batch='{$row['batch']}' and (state = 'WAIT_RESPONE' or state= 'WAITING' ) limit 1;";
				$haveList = mysql_query($sql,$con);
				if(mysql_num_rows($haveList) <= 0)
				{
					$sql = "update list_match set tag = 'COMPLETE' where id={$row['id']} ;";
					mysql_query($sql,$con);
				}		
			}	
			
			$sql = "select id,ins_end_st,ins_end_end,batch from list_match where state = 'RUNING' and tag!= 'COMPLETE' and start_time <= '{$currentDate}' and ";
			$sql .= "((timed1_start <= '{$currentTime}' and timed1_end > '{$currentTime}') or (timed2_start <= '{$currentTime}' and timed2_end > '{$currentTime}') ";
			$sql .= "or (timed3_start <= '{$currentTime}' and timed3_end > '{$currentTime}') or (timed4_start <= '{$currentTime}' and timed4_end > '{$currentTime}'));";
			$taskreset = mysql_query($sql,$con);
			$tasks = array();	
			if(mysql_num_rows($taskreset) > 0)
			{
				while($row = mysql_fetch_assoc($taskreset))
				{
					$tasks[] = $row;
					$sql = "update list_match set tag = 'EXECUTING' ,last_time = now() where id={$row['id']};";
					mysql_query($sql,$con);
				}				
			}
			$prevCheckTaskTime = time();	
		}
		
		if(!empty($tasks))
		{
			
			foreach($tasks as $task)
			{
				//提交
				curl_setopt($curl, CURLOPT_URL, $url.'match');
				$sql = 'select id,license_no,license_type,owner,identify_type,identify_no,telphone,mobile,address,model,model_code,';
				$sql .= 'vin_no,engine_no,enroll_date,seats,kerb_mass,total_mass,load_mass,tow_mass,engine,power,origin,run_area  ';
				$sql .= "from list_data  force index(batch_state)  where batch='{$task['batch']}' and  state='WAITING'  limit 1 ; ";
				$result = mysql_query($sql,$con);
				if(mysql_num_rows($result)==0)
				{
					$sql = "update list_match set request_complete = 'YES' where id={$task['id']};";
					mysql_query($sql,$con);	
				}
				else
				{
					$reqTime = date('Y-m-d H:i:s');
					$mdata = mysql_fetch_assoc($result);
					$mdata['match_start_date'] = $task['ins_end_st'];
					$mdata['match_end_date'] = $task['ins_end_end'];
					curl_setopt($curl, CURLOPT_POSTFIELDS, $mdata);
					$curlstr  = curl_exec($curl);
					$curlres  = json_decode($curlstr);		
					if($curlres  && property_exists($curlres,'data'))	
					{
						$tasksql = "update list_match set request_count=request_count+1 ,last_time = now() ";
						$sql = "update list_data set ";
						$po = $curlres->data;
						if($curlres->code == 0 || $curlres->code == 8)
						{						
							$sql .= "policy_no='{$po->policy_no}',insurance_company='{$po->insurance_company}',end_date='{$po->end_date}',prev_claims={$po->prev_claims}";
							if(preg_match('/^\s*[\x{4e00}-\x{9fa5}][a-zA-Z][a-zA-Z0-9]{5}\s*$/u',$po->license_no))
							{
								$sql .= ",license_no='{$po->license_no}'";	
							}
							$sql .= ",start_date='{$po->start_date}' , current_claims={$po->current_claims} ";
							$sql .= ",discount={$po->discount},premium={$po->premium} ";	
						}
						if($curlres->code == 0)                                                                        
						{	                                                                                           
							$tasksql .= ',complete_count=complete_count+1,success_count=success_count+1 ';
							$sql .= ",state ='SUCCESS' ,respone_time=now() , request_time='{$reqTime}' ";
						}
						elseif($curlres->code == 8)
						{
							$tasksql .= ',complete_count=complete_count+1,part_count=part_count+1 ';
							$sql .= ",state ='MATCH_PART' ,respone_time=now() , request_time='{$reqTime}' ";
						}
						elseif($curlres->code == 7)
						{
							$sql .= "state ='WAIT_RESPONE' , logid={$po} , request_time='{$reqTime}' ";
						}
						elseif($curlres->code == 9)
						{
							$tasksql .= ",complete_count=complete_count+1,failure_count=failure_count+1 , error_info='{$curlres->describe}' ";
							$sql .= "state ='FAILURE' ,respone_time=now() , request_time='{$reqTime}' ";							
						}
						else
						{
							$tasksql .= ", error_info='{$curlres->describe}' ";
							$Logined = false;
							break;
						}
						
						if($curlres->code == 0 && property_exists($po,'items') && !empty($po->items))
						{
							$poitems = $po->items;
							
							if(property_exists($poitems,'TVDI'))
							{
								$sql .= ",tvdi=".$poitems->TVDI;
							}
							if(property_exists($poitems,'TWCDMVI'))
							{
								$sql .= ",twcdmvi=".$poitems->TWCDMVI;
							}						
							if(property_exists($poitems,'TTBLI'))
							{
								$sql .= ",ttbli=".$poitems->TTBLI;
							}					          
							if(property_exists($poitems,'TCPLI_D'))
							{
								$sql .= ",tcpli_d=".$poitems->TCPLI_D;
							}	
							if(property_exists($poitems,'TCPLI_P'))
							{
								$sql .= ",tcpli_p=".$poitems->TCPLI_P;
							}	
							if(property_exists($poitems,'SLOI'))
							{
								$sql .= ",sloi=".$poitems->SLOI;
							}	
							if(property_exists($poitems,'BSDI'))
							{
								$sql .= ",bsdi=".$poitems->BSDI;
							}							
							if(property_exists($poitems,'BGAI'))
							{
								$sql .= ",bgai=".$poitems->BGAI;
							}
							if(property_exists($poitems,'NIELI'))
							{
								$sql .= ",nieli=".$poitems->NIELI;
							}						
							if(property_exists($poitems,'VWTLI'))
							{
								$sql .= ",vwtli=".$poitems->VWTLI;
							}					          
							if(property_exists($poitems,'STSFS'))
							{
								$sql .= ",stsfs=".$poitems->STSFS;
							}	
							if(property_exists($poitems,'TVDI_NDSI'))
							{
								$sql .= ",tvdi_ndsi=".$poitems->TVDI_NDSI;
							}	
							if(property_exists($poitems,'TTBLI_NDSI'))
							{
								$sql .= ",ttbli_ndsi=".$poitems->TTBLI_NDSI;
							}	
							if(property_exists($poitems,'TWCDMVI_NDSI'))
							{
								$sql .= ",twcdmvi_ndsi=".$poitems->TWCDMVI_NDSI;
							}							
							if(property_exists($poitems,'TCPLI_D_NDSI'))
							{
								$sql .= ",tcpli_d_ndsi=".$poitems->TCPLI_D_NDSI;
							}	
							if(property_exists($poitems,'TCPLI_P_NDSI'))
							{
								$sql .= ",tcpli_p_ndsi=".$poitems->TCPLI_P_NDSI;
							}	
							if(property_exists($poitems,'BSDI_NDSI'))
							{
								$sql .= ",bsdi_ndsi=".$poitems->BSDI_NDSI;
							}	
							if(property_exists($poitems,'OTHER_INS'))
							{
								$sql .= ",other_ins=".$poitems->OTHER_INS;
							}				
						}
						$sql .= " where id={$mdata['id']};";
						mysql_query($sql,$con);
						$tasksql .= " where id={$task['id']};";						
						mysql_query($tasksql,$con);
					}					
				}
				
				//查询完成
				curl_setopt($curl, CURLOPT_URL, $url. 'queryFinish' );
				$curlstr  = curl_exec($curl);
				
				$curlres  = json_decode($curlstr,true);
				if($curlstr  && !empty($curlres['data']))
				{
					$logids = $curlres['data'];
					foreach($logids as $logid)
					{
						//查询延迟结果
						curl_setopt($curl, CURLOPT_URL, $url. 'queryMatch' );
						$sql = 'select id,license_no,vin_no, logid ';
						$sql .= "from list_data   where logid={$logid}  limit 1 ; ";
						$result = mysql_query($sql,$con);
						if(mysql_num_rows($result)>0)
						{
							$mdata = mysql_fetch_assoc($result);
							$mdata['match_start_date'] = $task['ins_end_st'];
							$mdata['match_end_date'] = $task['ins_end_end'];
							curl_setopt($curl, CURLOPT_POSTFIELDS, $mdata);
							$curlstr  = curl_exec($curl);
							$curlres  = json_decode($curlstr);		
							if($curlres  && property_exists($curlres,'data'))	
							{
								$tasksql = "update list_match set last_time = now() ";
								$sql = "update list_data set ";
								$po = $curlres->data;	
							
								if($curlres->code == 0 || $curlres->code == 8)
								{						
									$sql .= "policy_no='{$po->policy_no}',insurance_company='{$po->insurance_company}',end_date='{$po->end_date}',prev_claims={$po->prev_claims}";
									
									if(preg_match('/^[\x{4e00}-\x{9fa5}][a-zA-Z][a-zA-Z0-9]{5}$/u',$po->license_no))
									{
										$sql .= ",license_no='{$po->license_no}'";	
									}
									$sql .= ",start_date='{$po->start_date}' , current_claims={$po->current_claims} ";
									$sql .= ",discount={$po->discount},premium={$po->premium} ";	
								}
								
								if($curlres->code == 0)                                                                        
								{	                                                                                           
									$tasksql .= ',complete_count=complete_count+1,success_count=success_count+1 ';
									$sql .= ",state ='SUCCESS' ,respone_time=now()  ";
								}
								elseif($curlres->code == 8)
								{
									$tasksql .= ',complete_count=complete_count+1,part_count=part_count+1 ';
									$sql .= ",state ='MATCH_PART' ,respone_time=now()  ";
								}
								elseif($curlres->code == 9)
								{
									$tasksql .= ",complete_count=complete_count+1,failure_count=failure_count+1 , error_info='{$curlres->describe}' ";
									$sql .= "state ='FAILURE' ,respone_time=now()  ";							
								}
								
								elseif($curlres->code != 7)
								{
									$tasksql .= ",error_info='{$curlres->describe}' ";
									$Logined = false;
									break;	
								}
								
								if($curlres->code == 0 && property_exists($po,'items') && !empty($po->items))
								{
									$poitems = $po->items;
									
									if(property_exists($poitems,'TVDI'))
									{
										$sql .= ",tvdi=".$poitems->TVDI;
									}
									if(property_exists($poitems,'TWCDMVI'))
									{
										$sql .= ",twcdmvi=".$poitems->TWCDMVI;
									}						
									if(property_exists($poitems,'TTBLI'))
									{
										$sql .= ",ttbli=".$poitems->TTBLI;
									}					          
									if(property_exists($poitems,'TCPLI_D'))
									{
										$sql .= ",tcpli_d=".$poitems->TCPLI_D;
									}	
									if(property_exists($poitems,'TCPLI_P'))
									{
										$sql .= ",tcpli_p=".$poitems->TCPLI_P;
									}	
									if(property_exists($poitems,'SLOI'))
									{
										$sql .= ",sloi=".$poitems->SLOI;
									}	
									if(property_exists($poitems,'BSDI'))
									{
										$sql .= ",bsdi=".$poitems->BSDI;
									}							
									if(property_exists($poitems,'BGAI'))
									{
										$sql .= ",bgai=".$poitems->BGAI;
									}
									if(property_exists($poitems,'NIELI'))
									{
										$sql .= ",nieli=".$poitems->NIELI;
									}						
									if(property_exists($poitems,'VWTLI'))
									{
										$sql .= ",vwtli=".$poitems->VWTLI;
									}					          
									if(property_exists($poitems,'STSFS'))
									{
										$sql .= ",stsfs=".$poitems->STSFS;
									}	
									if(property_exists($poitems,'TVDI_NDSI'))
									{
										$sql .= ",tvdi_ndsi=".$poitems->TVDI_NDSI;
									}	
									if(property_exists($poitems,'TTBLI_NDSI'))
									{
										$sql .= ",ttbli_ndsi=".$poitems->TTBLI_NDSI;
									}	
									if(property_exists($poitems,'TWCDMVI_NDSI'))
									{
										$sql .= ",twcdmvi_ndsi=".$poitems->TWCDMVI_NDSI;
									}							
									if(property_exists($poitems,'TCPLI_D_NDSI'))
									{
										$sql .= ",tcpli_d_ndsi=".$poitems->TCPLI_D_NDSI;
									}	
									if(property_exists($poitems,'TCPLI_P_NDSI'))
									{
										$sql .= ",tcpli_p_ndsi=".$poitems->TCPLI_P_NDSI;
									}	
									if(property_exists($poitems,'BSDI_NDSI'))
									{
										$sql .= ",bsdi_ndsi=".$poitems->BSDI_NDSI;
									}	
									if(property_exists($poitems,'OTHER_INS'))
									{
										$sql .= ",other_ins=".$poitems->OTHER_INS;
									}				
								}
								$sql .= " where id={$mdata['id']};";
								
								mysql_query($sql,$con);
								$tasksql .= " where id={$task['id']};";
								
								mysql_query($tasksql,$con);
							}						
		
						}
						else
						{
							//过期日志
							$mdata['logid'] = $logid;
							curl_setopt($curl, CURLOPT_POSTFIELDS, $mdata);
							curl_exec($curl);
						}		
					}
					
				}
				if(!$Logined) break; 
				usleep($min_request_timed*1000);
			}			
			
		}
		else
		{
			sleep(1);
		}
		
	}
	else
	{
		sleep(1);
	}
	
}
$sql = "update list_match set tag = 'WAITING'  where tag!= 'COMPLETE';";
mysql_query($sql);
$sql = "update match_cron_state  set state=0 where cron_name='match_client';";
mysql_query($sql);



?>