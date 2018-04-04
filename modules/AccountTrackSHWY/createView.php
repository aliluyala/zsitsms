<?php
$operation = 'create';
if(!empty($_GET['accountid'])) $_POST['accountid'] = $_GET['accountid'];
require_once(_ROOT_DIR."/common/editView.php");

?>