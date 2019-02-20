<?php
require(APP_PATH.'/vendors/smarty/' . 'Smarty.class.php');
class PlotViewTemplate extends Smarty{
	function __construct() {
      parent::__construct();
      $this->setTemplateDir(EXPAND_DIR . '/template');
      $this->setCompileDir(EXPAND_DIR . '/cache');
      $this->setConfigDir(EXPAND_DIR . '/config');
      $this->setCacheDir(EXPAND_DIR . '/cache');
    }
    
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
    	$this->assign('contents', $this->fetch($template));
    	parent::display(APP_PATH.'/template/app_master.tpl');
    }
}
