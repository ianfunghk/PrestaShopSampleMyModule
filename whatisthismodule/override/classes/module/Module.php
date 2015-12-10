<?php
class Module extends ModuleCore
{
    public function display($file, $template, $cache_id = null, $compile_id = null)
    {
        $result = parent::display($file, $template, $cache_id, $compile_id);
		$ip = Configuration::get('witm_config');
		$ip_array = explode(',', $ip);
		if (!in_array(Tools::getRemoteAddr(), $ip_array) && !in_array('*', $ip_array))
			return $result;
		return '<div class="div_infos_tpl"><span class="infos_tpl">TPL<span class="file_template">FILE : '.$file.'<br/>TEMPLATE : '.$template.'</span></span>'.$result.'</div>';
    }
}