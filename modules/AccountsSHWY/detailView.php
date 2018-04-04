<?php
$recordid = $_GET['recordid'];
$custom_buttons = Array(
						Array('label'=>'跟踪记录','title'=>'添加跟踪记录',
							'command'=>"zswitch_add_track_popup_dlg({$recordid},'AccountTrackSHWY','AccountsSHWY')"
						),
					);
require_once(_ROOT_DIR.'/common/detailView.php');
?>