<?php
session_start();

if(isset($_POST['deliveryCity']))
	$_SESSION['deliveryCity'] = $reponse['deliveryCity'] = $_POST['deliveryCity'];
if(isset($_POST['orderMin']))
	$_SESSION['orderMin'] = $reponse['orderMin'] = $_POST['orderMin'];
if(isset($_POST['orderTime']))
	$_SESSION['orderTime'] = $reponse['orderTime'] = $_POST['orderTime'];

header('Content-Type: application/json');
echo json_encode($reponse);
?>