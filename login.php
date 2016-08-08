<?php
require_once('includes/User.php');
require_once('includes/fr.php');
$theUser = new User();

$email = $_POST['email'];
$password = $_POST['password'];


if ($theUser->checkLogin($email, $password))
{ 
	$reponse['email'] = getLang('EMAIL');
	$reponse['statut'] = "1";
	$reponse['msg'] = getLang('WELCOME')." ".$_SESSION['nom'].'<div id="account_links_logged"><a href="mon_compte.php">'.getLang('MY_ORDERS_AND_MY_ACCOUNT').'</a><br /><a href="" id="deconnexion">'.getLang('LOG_OUT').'</a></div>';
	$reponse['deliveryCity'] = $_SESSION['deliveryCity'];
	$reponse['orderTime'] = $_SESSION['orderTime'];
	$reponse['orderMin'] = $_SESSION['orderMin'];
}
else
{ 
	$reponse['statut'] = "0"; 
	$reponse['msg'] = $theUser->errors[0];
}

header('Content-Type: application/json');
echo json_encode($reponse);
?>