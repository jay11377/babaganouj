<?php
// header('Content-Type: text/html;charset=utf-8');
require_once('admin/includes/config.php');
require_once('admin/includes/DbConnector.php');
include("includes/functions.php");
require_once('includes/User.php');
require_once('includes/fr.php');
$connector = new DbConnector();
$theUser = new User();
?>