<?php

global $APP_ADODB,$CURRENT_USER_ID;
$result = $APP_ADODB->Execute("select * from zswitch_cc_agent_state where userid={$CURRENT_USER_ID}  limit 1;");
$event = Array('state'=>'none','uuid'=>'','agent'=>'','othernumber'=>'','queue'=>'','accountdata'=>Array());
if($result && !$result->EOF)
{
	$zsconf = require(_ROOT_DIR.'/config/zswitch.conf.php');
	$popupconf = null;
	if($zsconf && array_key_exists('popup',$zsconf))
	{
		$popupconf = $zsconf['popup'];
	}

	$event['state'] = $result->fields['state'];
	$event['uuid'] = $result->fields['uuid'];
	$event['agent'] = $result->fields['name'];
	$event['othernumber'] = $result->fields['other_number'];
	$event['queue'] = $result->fields['queue'];
	
	if(!empty($popupconf) &&  array_key_exists('module',$popupconf) && array_key_exists('show_field',$popupconf) &&
	   array_key_exists('info_action',$popupconf) && array_key_exists('new_action',$popupconf) &&
	   array_key_exists('fields',$popupconf) && array_key_exists('save_field',$popupconf))
	{
		
		if($event['state'] == 'callin_ringing' || $event['state'] == 'callout_ringing')
		{
			
			$where = array();
			$idx = 0;
			foreach($popupconf['fields'] as $fld)
			{
				$w = array($fld,'=',$event['othernumber'],'');
				$where[] = $w;
				if($idx > 0)
				{
					$where[$idx-1][3] = 'or';
				}
				$idx ++ ;
			}
			$modclass = $popupconf['module'].'Module';
			if(!class_exists($modclass))
			{
				@include(_ROOT_DIR.'/modules/'.$popupconf['module'].'/'.$modclass.'.class.php');
			}

			if(class_exists($modclass))
			{
				$infomod = new $modclass();
				
				$reset = $infomod-> getListQueryRecord($where,array(),null,null,null,null,0,10);
				if(!empty($reset))
				{
					$event['accountdata']['客户'] = Array();
					foreach($reset as $row)
					{
						$acc = Array('label'=>"{$row[$popupconf['show_field']]}",'url'=>"javascript:zswitch_load_client_view('index.php?module={$popupconf['module']}&action={$popupconf['info_action']}&recordid={$row['id']}');");
						$event['accountdata']['客户'][] = $acc;						
					}
				}
			}				
			$acc = Array('label'=>"新建客户",'url'=>"javascript:zswitch_load_client_view('index.php?module={$popupconf['module']}&action={$popupconf['new_action']}&{$popupconf['save_field']}={$event['othernumber']}');");
			$event['accountdata']['客户'][] = $acc;
		}
		
	}
	return_ajax(0,$event);
	die();
	
}
return_ajax(1,$event);
?>