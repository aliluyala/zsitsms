<?php
$custom_buttons = Array(
						Array('label'=>'跟踪记录','title'=>'添加跟踪记录',
							'command'=>"zswitch_add_track_popup_dlg({$_GET['recordid']})"
						),
						Array('label'=>'添加预约','title'=>'添加预约记录',
							'command'=>"zswitch_add_appointment_popup_dlg({$_GET['recordid']});"
						),						
					);
require_once(_ROOT_DIR.'/common/detailView.php');
?>