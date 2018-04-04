<?php
global $recordid;
if(empty($recordid) && !empty($_GET['recordid'])) $recordid = $_GET['recordid'];
$detailview_buttons = createDetailviewButtons(Array());
$custom_buttons = Array(
						Array('label'=>'完成','title'=>'完成',
							  'command'=>'zswitch_load_client_view(\'index.php?module=PhoneSalesGJS&action=index\');'
						),
						Array('label'=>'跟踪记录','title'=>'添加跟踪记录',
							  'command'=>"zswitch_add_track_popup_dlg({$recordid})"
						),
						Array('label'=>'添加预约','title'=>'添加预约记录',
							  'command'=>"zswitch_add_appointment_popup_dlg({$recordid});"
						),						
					);
require_once(_ROOT_DIR.'/common/detailView.php');
?>