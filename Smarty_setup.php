<?php
global $APP_CONFIG;
require(_ROOT_DIR.'/include/Smarty/Smarty.class.php');
class ZS_Smarty extends Smarty
{
	function __construct() 
	{		
		parent::__construct();
        $this->setTemplateDir(_ROOT_DIR.'/templates/templates/');
        $this->setCompileDir(_ROOT_DIR.'/templates/templates_c/');
        $this->setConfigDir(_ROOT_DIR.'/templates/configs/');
        $this->setCacheDir(_ROOT_DIR.'/templates/cache/');	
		$this->assign('IMAGES',_ROOT_URL.'public/images');
		$this->assign('STYLES',_ROOT_URL.'public/css');
		$this->assign('SCRIPTS',_ROOT_URL.'public/js');
		$this->assign('PUBLIC',_ROOT_URL.'public');

	}
	
}
?>