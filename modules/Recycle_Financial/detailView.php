<?php
if(!isset($module)) $module = _MODULE;
$tpl_file="{$module}/DetailView.tpl";
$detailview_buttons = createDetailviewButtons(Array('return','back','next','delete','recycle'));
require_once(_ROOT_DIR.'/common/detailView.php');
?>