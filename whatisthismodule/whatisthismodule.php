<?php
/**
* History:
*
* 1.0.0 - First version
* 1.1.0 - display informations of the TPL (file and template)
* 1.1.1 - add "*" for select all address IP 
* 1.2.0 - add CSS and JS for each modules
* 1.2.1 - optimization display
* 1.2.2 - optimization display (icon)
* 1.2.3 - open block H-M by hover or click
*
*  @author    Vincent MASSON <contact@coeos.pro>
*  @copyright Vincent MASSON <www.coeos.pro>
*  @license   http://www.coeos.pro/fr/content/3-conditions-generales-de-ventes
*/

if (!defined('_PS_VERSION_'))
	exit;

class WhatIsThisModule extends Module
{
	protected $error = false;

	public function __construct()
	{
		$this->name = 'whatisthismodule';
		$this->tab = 'administration';
		$this->version = '1.2.3';
		$this->author = 'www.coeos.pro';

		parent::__construct();

		$this->displayName = $this->l('What is this module ?');
		$this->description = $this->l('Allow to display hook name and module name').$this->overridesDisable();
		$this->confirmUninstall = $this->l('Are you sure you want to delete this module ?');
	}
	public function overridesDisable()
	{
		return ((int)Configuration::get('PS_DISABLE_OVERRIDES') == 1 && file_exists(_PS_MODULE_DIR_.$this->name.'/override/')) ? 
				$this->displayError($this->l('The overrides are disabled, you must enable them to use this module')) : '';
	}
	public function install()
	{
		if (!parent::install()
			|| !Configuration::updateValue('witm_config', '')
			|| !Configuration::updateValue('witm_css_js', 1)
			|| !Configuration::updateValue('witm_h_m', 1)
			|| !$this->registerHook('header'))
			return false;
		return true;
	}
	public function uninstall()
	{
		if (!parent::uninstall()
			|| !Configuration::deleteByName('witm_config')
			|| !Configuration::deleteByName('witm_css_js')
			|| !Configuration::deleteByName('witm_h_m')
			)
			return false;
		return true;
	}
	public function hookHeader()
	{
		if (Configuration::get('witm_h_m') == 1)
			$this->context->controller->addCSS($this->_path.'views/css/'.$this->name.'_h_m.css');
		else
			$this->context->controller->addCSS($this->_path.'views/css/'.$this->name.'.css');
		$this->context->controller->addJS($this->_path.'views/js/'.$this->name.'.js');
	}
	private function displayHelp()
	{
		$this->_html .= '
		<p style="float:right"><a href="http://www.coeos.pro/9-modules-prestashop"><img src="'.$this->_path.'/img/coeos_logo.jpg"/></a></p>';
		$this->_html .= '<br /><br />MASSON Vincent<p><a href="http://www.coeos.pro/9-modules-prestashop">www.coeos.pro</a></p>
		<p><a href="mailto:contact@coeos.pro">contact@coeos.pro</a></p>';
	}
	public function getContent()
	{
		$this->_html = '<h1><img src="'.$this->_path.'logo.png"/> '.$this->displayName.'</h1><h3>'.$this->description.'</h3>
		<p>Version 1.0.0 : '.$this->l('First version').'</p>
		<p>Version 1.1.0 : '.$this->l('display informations of the TPL (file and template)').'</p>
		<p>Version 1.1.1 : '.$this->l('add * for select all address IP').'</p>
		<p>Version 1.2.0 : '.$this->l('display CSS and JS for each modules, with link in a new tab').'</p>
		<p>Version 1.2.1 : '.$this->l('optimization display').'</p>
		<p>Version 1.2.2 : '.$this->l('optimization display').'</p>
		<p>Version 1.2.3 : '.$this->l('open block H-M by hover or click').'</p>
		';
		$this->displayHelp();
		if (Tools::getValue('submitConfigWITM'))
		{
			if ($this->saveConfigWITM())
				$this->_html .= $this->displayConfirmation($this->l('The data has been added successfully'));
			else
				$this->_html .= $this->displayError($this->l('An error occured during saving'));
		}
		$this->displayForm();
		return $this->_html;
	}
	private function saveConfigWITM()
	{
		$ip = Tools::getValue('ip');
		$css_js = Tools::getValue('witm_css_js');
		$h_m = Tools::getValue('witm_h_m');
		Configuration::updateValue('witm_config', $ip);
		Configuration::updateValue('witm_css_js', $css_js);
		Configuration::updateValue('witm_h_m', $h_m);
		return true;
	}
	private function displayForm()
	{
		$ip = Configuration::get('witm_config');
		$css_js = Configuration::get('witm_css_js');
		$h_m = Configuration::get('witm_h_m');
		$this->_html .= '<br/>
		<fieldset>
			<legend><img src="'.$this->_path.'logo.png" alt="" title="" /> '.$this->l('Configuration').'</legend>
			<form id="witm_config" method="post" action="" enctype="multipart/form-data" name="monform">
				<label>'.$this->l('Adress IP').'</label>
					<div class="margin-form">
						<input type="text" size="50" name="ip" value="'.$ip.'"/>
							<button type="button" class="btn btn-default" onclick="addRemoteAddr();"><i class="icon-plus"></i> '.$this->l('Add my IP').'</button>	
					</div>
					<div class="margin-form">'.$this->l('use the comma "," to separate multiple IP address').' '.$this->l('and * for select all IP address').'</div>
				<div class="clear"></div>
				
				<label>'.$this->l('open blocks H-M on click').'</label>
					<div class="margin-form">
						<input type="checkbox" name="witm_h_m" value="1" '.(($h_m == 1)? 'checked="checked"': '').'/>'.$this->l('If it is not open to the overview, need to click to open but in theme functions it may be indispensable').'
					</div>
				<div class="clear"></div>		
				
				<label>'.$this->l('CSS and JS').'</label>
					<div class="margin-form">
						<input type="checkbox" name="witm_css_js" value="1" '.(($css_js == 1)? 'checked="checked"': '').'/>'.$this->l('Displays the url of the CSS and JS file for each module').'
					</div>
				<div class="clear"></div>
				
				
				<div class="margin-form">
					<input type="submit" class="button" name="submitConfigWITM" value="'.$this->l('Save').'"/>
				</div>
				<script type="text/javascript">
					function addRemoteAddr()
					{
						var length = $(\'input[name=ip]\').attr(\'value\').length;
						if (length > 0)
							$(\'input[name=ip]\').attr(\'value\',$(\'input[name=ip]\').attr(\'value\') +\','.Tools::getRemoteAddr().'\');
						else
							$(\'input[name=ip]\').attr(\'value\',\''.Tools::getRemoteAddr().'\');
					}
				</script>		
			</form>
		</fieldset>
		<br/>
		<p>'.$this->l('Example').' : </p>
		<img src="'.$this->_path.'/img/avec.jpg"/>';
	}
}