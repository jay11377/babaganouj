<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Impression de commande</title>
</head>

<?php
require_once('includes/fr.php');
require_once('includes/config.php');
require_once('includes/Sentry.php');
require_once('includes/DbConnector.php');


function str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);
    if($pos === false)
    {
        return $subject;
    }
    else
    {
        return substr_replace($subject, $replace, $pos, strlen($search));
    }
}


if(isset($_GET['id'])){
	$id=$_GET['id'];
}
else if(isset($_POST['id'])){
	$id=$_POST['id'];
}

$connector = new DbConnector();

$query = "SELECT C.id AS id_commande, C.id_moyen_paiement, C.id_client, C.date, C.total_ht, C.total_ttc, C.couverts, C.message, C.date_livraison, C.creneau_livraison, C.heure_livraison, CL.email, S.id as statut_id, S.statut, O.name as moyen_paiement, A.*
			FROM commandes C
	   LEFT JOIN clients CL ON C.id_client=CL.id
	   LEFT JOIN statut_commande S ON C.id_statut=S.id
	   LEFT JOIN order_methods O ON C.id_moyen_paiement=O.id
	   LEFT JOIN adresses A ON C.adresse_livraison=A.id
		   WHERE C.id=".$id;
$result = $connector->query($query);
$row=$connector->fetchArray($result);
$result_statut = $connector->query("SELECT * FROM statut_commande ORDER BY id");
?>

<body>
<pre>
_______________________________________________________________________________
Informations sur la commande :
  
  Date et heure de la commande  : <?php echo dateLongPrintENtoFR($row['date']) ?>  
  N° de la commande             : <?php echo $row['id_commande']; ?> 
  Client                        : <?php echo (alreadyClient($row['id_client'])) ? "déjà client" : "nouveau client" ?> 
  Date de livraison             : <?php echo dateENtoFR($row['date_livraison']) ?> 
  Livraison souhaitée           : <?php echo osql($row['creneau_livraison']) ?> 
  Heure de livraison réelle     : <?php echo osql($row['heure_livraison']) ?> 
  Règlement                     : <?php echo osql(str_replace('_', ' ', $row['moyen_paiement'])) ?> 
_______________________________________________________________________________
Adresse de livraison :

  Tél         : <?php echo osql($row['telephone']) ?> 
  Nom         : <?php echo osql($row['nom']) ?>   
  Prénom      : <?php echo osql($row['prenom']) ?> 
  Adresse     : <?php echo osql($row['adresse1']) ?> 
  		<?php if ($row['adresse2'] && $row['adresse2']!='') echo osql($row['adresse2']) ?> 
  Code postal : <?php echo osql($row['cp']) ?>			Ville       : <?php echo osql($row['ville']) ?> 
  Escalier    : <?php echo osql($row['escalier']) ?> 
  Code entrée : <?php echo osql($row['code_entree']) ?>			Etage       : <?php echo osql($row['etage']) ?> 
  No sonnette : <?php echo osql($row['interphone']) ?>			No appart.  : <?php echo osql($row['numero_appartement']) ?>  
  Nom société : <?php echo osql($row['societe']) ?>			Nom service : <?php echo osql($row['service']) ?>                       
  Complément  : 
  <?php echo wordwrap(osql($row['remarque']), 60, "\n") ?> 
_______________________________________________________________________________
Remarques :
<?php echo wordwrap(osql($row['message']), 60, "\n"); ?> 
_______________________________________________________________________________


Plats commandés :

<?php
$query = "SELECT LC.*, P.name, P.thumbnail1 
			FROM ligne_commande LC
	   LEFT JOIN plats P ON (LC.id_plat=P.id)
		   WHERE id_commande=".$id."
		     AND remise=0 
		ORDER BY id";
$result_details = $connector->query($query);
$nb_space_nom = 3;
$nb_space_prix = 45;
echo "Qté";
for($i=$nb_space_nom; $i>0; $i--)
    echo " ";
echo "Nom du plat";
for($i=$nb_space_prix; $i>0; $i--)
    echo " ";
echo "prix (EUROS)";
echo "\n";

while($row_details = $connector->fetchArray($result_details))
{
	echo $row_details['quantite'];
	$size = strlen($row_details['quantite']);
	for($i=$nb_space_nom + 3; $i>$size; $i--)
		echo " ";
	echo $row_details['name'];
	$size = strlen(utf8_decode($row_details['name']));
	for($i=$nb_space_prix + 11; $i>$size; $i--){
		echo " ";
	}
	echo showPriceCurrency($row_details['total_ttc']);
	echo "\n";
	if($row_details['options']!=''){
		$space_string="";
		for($i=$nb_space_nom + 3; $i>0; $i--)
			$space_string .= " ";
		echo $space_string.str_replace("<br />", "\n".$space_string, str_lreplace("<br />", "", $row_details['options']));
		echo "\n";
	}
}
// Remises
$query_vouchers = "SELECT * FROM ligne_commande LC WHERE id_commande=".$id." AND remise=1";
$result_vouchers = $connector->query($query_vouchers);
while($row_voucher = $connector->fetchArray($result_vouchers))
{
	echo $row_voucher['quantite'];
	$size = strlen($row_voucher['quantite']);
	for($i=$nb_space_nom + 3; $i>$size; $i--)
		echo " ";
	echo $row_voucher['description_remise'];
	$size = strlen(utf8_decode($row_voucher['description_remise']));
	for($i=$nb_space_prix + 11; $i>$size; $i--){
		echo " ";
	}
	echo "-".showPriceCurrency($row_voucher['total_ttc']);
	echo "\n";
} 
?> 
  Couverts et serviettes pour <?php echo $row['couverts']; ?> 

<?php
for($i=$nb_space_nom + $nb_space_prix - 3; $i>0; $i--)
	echo " ";
?>
Total à payer :  <?php echo showPriceCurrency($row['total_ttc']) ?> 

<?php
if($row['id_moyen_paiement']==1 || $row['id_moyen_paiement']==2){
	echo "ATTENTION, cette commande est déjà réglée";
	echo "\n\n";
}
?>
<?php
for($i=$nb_space_nom + $nb_space_prix - 7; $i>0; $i--)
	echo " ";
?>
Total à percevoir :  <?php 
if($row['id_moyen_paiement']==1 || $row['id_moyen_paiement']==2)
	echo showPriceCurrency(0);
else
	echo showPriceCurrency($row['total_ttc']);
?>
</pre>
</body>
</html>
