<?php
global $APP_ADODB,$CURRENT_USER_ID;
$event_timeout = 3600;
$valid_time = time() - $event_timeout;
$result = $APP_ADODB->Execute("select * from zswitch_call_event where userid={$CURRENT_USER_ID} and event_time >{$valid_time} limit 1;");
$event = Array('event'=>'none','uuid'=>'','caller'=>'','callee'=>'','queue'=>'','accountdata'=>Array());
if($result && !$result->EOF)
{
	$event['event'] = $result->fields['event_type'];
	$event['uuid'] = $result->fields['uuid'];
	$event['caller'] = $result->fields['callerid'];
	$event['callee'] = $result->fields['calleeid'];
	$event['queue'] = $result->fields['queue'];
	$mnum = '';
	if($event['event'] == 'callin_ringing')
	{
		$mnum = $event['caller'];
	}
	elseif($event['event'] == 'callout_ringing')
	{
		$mnum = $event['callee'];
	}

	$pfx = substr($mnum,0,1);
	$pfx1 = substr($mnum,1,1);
	if($pfx == '0')
	{
		if($pfx1 == '1')
		{
			$mnum = substr($mnum,-11);
		}
		else
		{
			$mnum = substr($mnum,-8);
		}
		
	}
	if($event['event'] == 'callin_ringing' || $event['event'] == 'callout_ringing')
	{
		//echo "select * from cpn_accounts where phone = '{$mnum}';";
		$cpnacc = $APP_ADODB->Execute("select * from cpn_accounts where phone = '{$mnum}';");
		if($cpnacc && !$cpnacc->EOF)
		{
			$event['accountdata']['驻地网客户'] = Array();
			while(!$cpnacc->EOF)
			{			
				$acc = Array('label'=>"{$cpnacc->fields['name']}({$cpnacc->fields['accno']})",'url'=>"javascript:zswitch_load_client_view('index.php?module=CpnAccounts&action=detailView&recordid={$cpnacc->fields['id']}');");
				$event['accountdata']['驻地网客户'][] = $acc;
				$cpnacc->MoveNext();
			}
		}
	
		$cpnacc = $APP_ADODB->Execute("select * from dia_accounts where phone = '{$mnum}';");
		if($cpnacc && !$cpnacc->EOF)
		{
			$event['accountdata']['专线客户'] = Array();
			while(!$cpnacc->EOF)
			{			
				$acc = Array('label'=>"{$cpnacc->fields['name']}({$cpnacc->fields['diano']})",'url'=>"javascript:zswitch_load_client_view('index.php?module=CpnAccounts&action=detailView&recordid={$cpnacc->fields['id']}');");
				$event['accountdata']['专线客户'][] = $acc;
				$cpnacc->MoveNext();
			}
		}
	}
	$APP_ADODB->Execute("delete from zswitch_call_event where id={$result->fields['id']};");
	return_ajax('success',$event);
	die();
}
return_ajax('none',$event);
?>