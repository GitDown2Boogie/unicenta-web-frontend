<?php
/* Main page handler for remove-from-basket script */

//Switch to load correct page
switch(getModuleAction())
{
	default:
	$page = 'home';
	break;
}
//Include the requested page
require_once($page . '.incl.php');
