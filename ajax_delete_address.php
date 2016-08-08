<?php
include("includes/top_includes.php");
$id_adresse = (isset($_POST[ 'id_adresse' ]))?$_POST[ 'id_adresse' ]: ''; 
$connector = new DbConnector();
$query = "UPDATE adresses SET active=0 WHERE id=".$id_adresse;
if ($connector->query($query)){
	$reponse['statut'] = "1";
	$reponse['msg'] = '<p class="bg-success">'.getLang('ADDRESS_DELETED').'</p>';
}
else{
	$reponse['statut'] = "0";
	$reponse['msg'] = '<p class="bg-danger">'.getLang('DB_ERROR').'</p>';
}

header('Content-Type: application/json');
echo json_encode($reponse);
?>