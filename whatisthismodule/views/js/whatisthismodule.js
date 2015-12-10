function display_module(hook)
{
	if (document.getElementById("d_m_" + hook).style.display == "block")
		document.getElementById("d_m_" + hook).style.display = "none";
	else
		document.getElementById("d_m_" + hook).style.display = "block";
}

function display_infos_hook(hook)
{
	if (document.getElementById("hook_module_" + hook).style.display == "block")
		document.getElementById("hook_module_" + hook).style.display = "none";
	else
		document.getElementById("hook_module_" + hook).style.display = "block";
}