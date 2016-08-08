<?php
session_start();
/*
if(isset($_POST['deliveryCity']))
	$_SESSION['deliveryCity'] = $reponse['deliveryCity'] = $_POST['deliveryCity'];
if(isset($_POST['orderMin']))
	$_SESSION['orderMin'] = $reponse['orderMin'] = $_POST['orderMin'];
if(isset($_POST['orderTime']))
	$_SESSION['orderTime'] = $reponse['orderTime'] = $_POST['orderTime'];
*/
if(isset($_POST['date']))
	$_SESSION['deliveryDate'] = $_POST['date'];
if(isset($_POST['heure']))
	$_SESSION['deliveryTime'] = $_POST['heure'];

header('Content-Type: application/json');
echo json_encode($reponse);
?>