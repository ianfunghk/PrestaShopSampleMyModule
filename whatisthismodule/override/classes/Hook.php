<?php
class Hook extends HookCore
{
	public static function exec($hook_name, $hook_args = array(), $id_module = null, $array_return = false, $check_exceptions = true,
                                $use_push = false, $id_shop = null)
    {
		$output = parent::exec($hook_name, $hook_args, $id_module, $array_return, $check_exceptions, $use_push, $id_shop);
		$live_edit = (Tools::isSubmit('live_edit'))? true : false;
        if (!$module_list = Hook::getHookModuleExecList($hook_name)) {
            return '';
        }
		$ip = Configuration::get('witm_config');
		$ip_array = explode(',', $ip);

		$before_output = '';
		$after_output = '';

		if(in_array(Tools::getRemoteAddr(), $ip_array) || in_array('*', $ip_array))
		{
				if (Configuration::get('witm_h_m') == 1)
					$before_output .= '
					<div class="div_infos_hook">
					<span class="infos_hook"><span onclick="display_infos_hook(\''.$hook_name.'\')"><i class="icon-expand-alt icon-large"></i> H-M</span>
						<span class="hook_module" id="hook_module_'.$hook_name.'"><br/>
					HOOK: '.$hook_name.'<div class="see_modules"><span onclick="display_module(\''.$hook_name.'\')"><i class="icon-expand-alt icon-large"></i> Modules</span><span id="d_m_'.$hook_name.'" class="display_modules">';
				else
					$before_output .= '
					<div class="div_infos_hook">
					<span class="infos_hook">H-M
						<span class="hook_module"><br/>
					HOOK: '.$hook_name.'<div class="see_modules"><span onclick="display_module(\''.$hook_name.'\')"><i class="icon-expand-alt icon-large"></i> Modules</span><span id="d_m_'.$hook_name.'" class="display_modules">';
							
			$css_js = (int)Configuration::get('witm_css_js');

			$img = '<img src="'.Context::getContext()->shop->physical_uri.'/modules/whatisthismodule/img/open_new_tab.png"/>';
			foreach($module_list as $module)
			{
				$css = '';
				$js = '';	
				if ($css_js == 1)
				{
					foreach (Context::getContext()->controller->css_files as $key => $value)
						if (strstr($key, '/'.$module['module'].'/'))
							$css .= '<br/> <a class="file_css" href="'.$key.'" target="_blank">CSS : '.$key.' '.$img.'</a>';	

					foreach (Context::getContext()->controller->js_files as $key => $value)
						if (strstr($value, '/'.$module['module'].'/'))
							$js .= '<br/> <a class="file_js" href="'.$value.'" target="_blank">JS : '.$value.' '.$img.'</a>';						
				}
				$before_output .= '<br/>- '.$module['module'].(($css != '' && $js != '')? ' : ' : '').$css.$js;
			}
			$before_output .= '</span></div></span></span>';
			$after_output .= '</div>';
		}
        if ($array_return) {
            return $output;
        } else {
            return ($live_edit ? '<script type="text/javascript">hooks_list.push(\''.$hook_name.'\');</script>
				<div id="'.$hook_name.'" class="dndHook" style="min-height:50px">' : '').$before_output.$output.$after_output.($live_edit ? '</div>' : '');
        }
    }
}