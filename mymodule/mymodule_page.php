<?php
global $smarty;
 
include('../../config/config.inc.php');
include('../../header.php');
 
$mymodule = new MyModule();
$message = $mymodule->l('Welcome to my shop!');
$smarty->assign('messageSmarty', $message); // creation of our variable
$smarty->display(dirname(__FILE__).'/mymodule_page.tpl');
 
include( '../../footer.php' );
