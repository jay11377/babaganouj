<?php

require_once("includes/Sentry.php");
$sentry = new Sentry();
if ($sentry->logout()){
	header( 'Location: login.php') ;	
}
?>