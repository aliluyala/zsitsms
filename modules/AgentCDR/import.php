<?php
require_once(_ROOT_DIR."/modules/AgentCDR/AgentCDRModule.class.php");
$mod = new AgentCDRModule();	
$mod->editFields = Array(
					'userid'           ,
					'queue'            ,
	                'agent_name'       ,
	                'dir'              ,
	                'other_number'     ,
	                'uuid'             ,
	                'source'           ,
	                'context'          ,
	                'channel_name'     ,
	                'created_datetime' ,
	                'answered_datetime',
	                'hangup_datetime'  ,
	                'bleg_uuid'        ,
	                'hangup_cause'     ,
					'total_timed'	   ,
					'talk_timed'	   ,					
					);
require_once(_ROOT_DIR."/common/importA.php");

?>