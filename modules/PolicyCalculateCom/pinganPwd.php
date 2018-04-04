<?php

	$encryptPassword = $_POST['encryptPassword'];
	$file = _ROOT_DIR.'/cache/pinganPwd.txt';
	file_put_contents($file,$encryptPassword);


