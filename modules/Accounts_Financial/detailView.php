<?php
$custom_buttons = Array(
						Array('label'=>'跟踪记录','title'=>'添加跟踪记录',
							'command'=>"account.add_track_popup(recordid,'AccountTrack_Financial','Accounts_Financial')"
						),
					);
$tpl_file="Accounts_Financial/DetailView.tpl";
require_once(_ROOT_DIR.'/common/detailView.php');
?>