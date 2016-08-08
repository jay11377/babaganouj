<?php
include("includes/top_includes.php");

$id_adresse = (isset($_POST[ 'id_adresse' ]))?$_POST[ 'id_adresse' ]: '';
$id_client = (isset($_POST[ 'id_client' ]))?$_POST[ 'id_client' ]: ''; 

$connector = new DbConnector();
$query = "UPDATE adresses SET defaut=0 WHERE id_client=".$id_client. " AND id!=".$id_adresse;
if ($connector->query($query)){
		$query = "UPDATE adresses SET defaut=1 WHERE id=".$id_adresse;
		if ($connector->query($query)){
			$reponse['statut'] = "1";
			$reponse['msg'] = '<p class="bg-success">'.getLang('ADDRESS_UPDATED').'</p>';
		}
		else{
			$reponse['statut'] = "0";
			$reponse['msg'] = '<p class="bg-danger">'.getLang('DB_ERROR').'</p>';
		}
}
else{
	$reponse['msg'] = '<p class="bg-danger">'.getLang('DB_ERROR').'</p>';
}


header('Content-Type: application/json');
echo json_encode($reponse);

?>