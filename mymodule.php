<?php
if (!defined('_PS_VERSION_') )
	exit;

class MyModule extends Module 
{
	public function __construct()
	{
		$this->name = 'mymodule';
		$this->tab = 'Test';
		$this->version = 1.0;
		$this->author = 'Ian Fung';
		
		/**
		* The need_instance flag indicates whether to load the module's class when
		* displaying the "Modules" page in the back-office. If set at 0, the module
		* will not be loaded, and therefore will spend less resources to generate the
		* page module. If your module needs to display a warning message in the
		* "Modules" page, then you must set this attribute to 1.
		**/
		$this->need_instance = 0; 
		
		parent::__construct();
		
		$this->displayName = $this->l('My module');
		$this->description = $this->l( 'Description of my module.');
		
	}
	
	public function install()
	{
		if (parent::install() == false OR !$this->registerHook('leftColumn')) {
			return false;
		} else {
			return true;
		}
	}

	public function hookLeftColumn($params)
	{
		global $smarty;
		return $this->display( __FILE__, 'mymodule.tpl');
	}
	
	public function hookRightColumn($params)
	{
		return $this->hookLeftColumn($params);
	}
	public function uninstall()
	{
		if (!parent::uninstall())
		{
			Db::getInstance()->Execute( 'DELETE FROM `' . _DB_PREFIX_ . 'mymodule`');
			parent::uninstall();
		}
	}	
}