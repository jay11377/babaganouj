<?php
require_once('includes/Sentry.php');
$theSentry = new Sentry();
if (!$theSentry->checkLogin() )
{ 
	header("Location: login.php"); die(); 
}
else
{
	header("Location: moduleinterface.php"); die(); 
}
?>
