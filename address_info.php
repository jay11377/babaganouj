<?php
include("includes/top_includes.php");
$id_address = $_POST['id_address'];

if(isset($_POST['setTime']) && $_POST['setTime']==1)
{
	session_start();
	$_SESSION['deliveryCity'] = getCityFromAddress($id_address);
	$_SESSION['orderTime'] = getOrderTime(getCp($id_address));
	/*
	if(isset($_POST['deliveryCity']))
		$_SESSION['deliveryCity'] = $reponse['deliveryCity'] = $_POST['deliveryCity'];
	if(isset($_POST['orderMin']))
		$_SESSION['orderMin'] = $reponse['orderMin'] = $_POST['orderMin'];
	if(isset($_POST['orderTime']))
		$_SESSION['orderTime'] = $reponse['orderTime'] = $_POST['orderTime'];
	if(isset($_POST['date']))
		$_SESSION['deliveryDate'] = $reponse['deliveryDate'] = $_POST['date'];
	if(isset($_POST['heure']))
		$_SESSION['deliveryTime'] = $reponse['deliveryTime'] = $_POST['heure'];
	*/
}

$query = "SELECT * FROM adresses WHERE id=".$id_address. " AND active=1 ORDER BY titre_adresse";
$result = $connector->query($query);
$row = $connector->fetchArray($result);
if($row['societe']!='')
	echo osql($row['societe'])."<br />";
echo osql($row['prenom'])." ".osql($row['nom'])."<br />";
echo osql($row['adresse1'])."<br />";
if($row['adresse2']!='')
	echo osql($row['adresse2'])."<br />";
echo $row['cp']." ".osql($row['ville'])."<br />";
echo $row['telephone'];
?>
<br />----------------------------<br /><?php
if($row['code_entree']!='')
	echo getLang('ENTRY_CODE').' : '.osql($row['code_entree'])."<br />";
if($row['interphone']!='')
	echo getLang('INTERCOM').' : '.osql($row['interphone'])."<br />";
if($row['service']!='')
	echo getLang('SERVICE').' : '.osql($row['service'])."<br />";
if($row['escalier']!='')
	echo getLang('STAIRCASE').' : '.osql($row['escalier'])."<br />";
if($row['etage']!='')
	echo getLang('FLOOR').' : '.osql($row['etage'])."<br />";
if($row['numero_appartement']!='')
	echo getLang('APARTMENT_NUMBER').' : '.osql($row['numero_appartement'])."<br />";
?>