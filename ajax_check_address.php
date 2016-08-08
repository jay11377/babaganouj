<?php
include("includes/top_includes.php");
$conn = new DbConnector();

$adresse_livraison = (isset($_POST[ 'adresse_livraison' ]))?$_POST[ 'adresse_livraison' ]: ''; 
$adresse_facturation = (isset($_POST[ 'adresse_facturation' ]))?$_POST[ 'adresse_facturation' ]: ''; 

$cp_array = array();
$query ="SELECT postcode FROM villes";
$result = $conn->query($query);
while($row = $conn->fetchArray($result))
{
	$array = explode(",", $row['postcode']); 
	foreach($array as $cp)
	{
		$cp_array[] = trim($cp);
	}
}

$statut = 1;
$msg = "";

// Vérifier que la ville est livrable
$query ="SELECT cp FROM adresses WHERE id=".(int)$adresse_livraison;
$result = $conn->query($query);
$row = $conn->fetchArray($result);

if(!(in_array($row['cp'], $cp_array))){
	$statut = 0;
	$msg=getLang('SHIPPING_ADDRESS_INCORRECT');
}

// Si la ville est livrable, vérifier que la ville de livraison correspond à la ville sélectionnée au départ
/*
if($statut==1)
{
	if(getCpFromCity($_SESSION['deliveryCity'])!=getCp((int)$adresse_livraison))
	{
		$statut = 0;
		$msg=getLang('DELIVERY_ADDRESS_DIFFERENT').getCityFromAddress((int)$adresse_livraison).getLang('DELIVERY_ADDRESS_DIFFERENT_2').$_SESSION['deliveryCity'];
	}
}
*/
// Si la ville est livrable, vérifier que le minimum de commande est atteint
if($statut==1)
{
	if($_SESSION['total_ttc']<getOrderMin(getCp((int)$adresse_livraison)))
	{
		$statut = 0;
		$msg=getLang('MIN_ERROR');
	}
}

$reponse['statut'] = $statut;
$reponse['msg'] = $msg;


header('Content-Type: application/json');
echo json_encode($reponse);
?>