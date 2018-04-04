<?php
$module = _MODULE;
$detailview_buttons = createDetailviewButtons(Array('edit','next'));
$tpl_file = "{$module}/DetailView.tpl";
if(!isset($recordid)) $recordid = -1;
//保证编辑后客户id不会归0
if(isset($_GET['recordid']) && !empty($_GET['recordid'])) $recordid = $_GET['recordid'];
$orderby = 'preset_time';
$order = 'DESC';
$custom_buttons = Array(
						Array('label'=>'跟踪记录','title'=>'添加跟踪记录',
							'command'=>"zswitch_add_track_popup_dlg({$recordid},'AccountTrackSHWY')"
						),
                        Array('label'=>'去年保险记录','title'=>'去年保险记录','command'=>"account.set(recordid)"),
					);
require_once(_ROOT_DIR.'/common/detailView.php');
?>