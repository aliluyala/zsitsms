<?php
$detailview_buttons = createDetailviewButtons(Array('edit','next'));
$tpl_file = "Telemarketing_Financial/DetailView.tpl";
if(!isset($recordid)) $recordid = -1;
//保证编辑后客户id不会归0
if(isset($_GET['recordid']) && !empty($_GET['recordid'])) $recordid = $_GET['recordid'];
$orderby = 'preset_time';
$order = 'DESC';
$custom_buttons = Array(
						Array('label'=>'跟踪记录','title'=>'添加跟踪记录',
                            //'command'=>"account.track_popup_run(module,recordid,false)"
							'command'=>"account.add_track_popup(recordid,'AccountTrack',module)"
						),
					);
require_once(_ROOT_DIR.'/common/detailView.php');
?>