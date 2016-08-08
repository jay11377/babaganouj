<?php
include("includes/top_includes.php");

$id_commande = $_POST['id_commande'];

$conn = new DbConnector();
$query = "SELECT * FROM ligne_commande WHERE id_commande=".$id_commande." AND remise=0 ORDER BY id";
$result = $conn->query($query);
unset($_SESSION['cart']);
while($row = $conn->fetchArray($result))
{
	$id_produit = $row['id_plat'];
	$quantite = $row['quantite'];
	$_SESSION['cart'][$id_produit]=$quantite;
	
	if(getCategoryId($id_produit)==1){ // categorie Mezze
		$arrayOptions = explode("<br />", $row['options']);
		$arrayOptions = array_slice($arrayOptions, 0, -1);
		$index = getMezzeIndex($id_produit, $arrayOptions);
		$_SESSION['cart']['options'][$id_produit][$index]['quantite']=$quantite;
		foreach($arrayOptions as $key=>$value) {
			$_SESSION['cart']['options'][$id_produit][$index][] = $value;
		}
	}
}
// echo "carte.php?id=".getDefaultCategoryId();
echo "commander.php";
?>
