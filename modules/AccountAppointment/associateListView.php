<?php
$operation_allow = true;
$current_page = 1;
if(!empty($_POST['record_page'])) $current_page = $_POST['record_page'];
$operations = Array(
				Array('name'=>'处理','title'=>'处理完成',
				'url'=>'$.get(\'index.php?module=AccountAppointment&action=handled&recordid=\'+$(this).attr(\'recordid\'),function(){zswitch_associate_listview_page_ctrl('.$current_page.',\'associate_listview_table_form\');});'
				),
				Array('name'=>'取消','title'=>'取消预约',
				'url'=>'$.get(\'index.php?module=AccountAppointment&action=cancel&recordid=\'+$(this).attr(\'recordid\'),function(){zswitch_associate_listview_page_ctrl('.$current_page.',\'associate_listview_table_form\');});'
				),
			);
require_once(_ROOT_DIR.'/common/associateListView.php');
?>


