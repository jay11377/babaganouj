<?php
include("includes/top_includes.php");
require_once('admin/includes/Validator.php');


$societe = (isset($_POST[ 'societe' ]))?$_POST[ 'societe' ]: '';
$prenom_adresse = (isset($_POST[ 'prenom_adresse' ]))?$_POST[ 'prenom_adresse' ]: '';
$nom_adresse = (isset($_POST[ 'nom_adresse' ]))?$_POST[ 'nom_adresse' ]: '';
$adresse1 = (isset($_POST[ 'adresse1' ]))?$_POST[ 'adresse1' ]: '';
$adresse2 = (isset($_POST[ 'adresse2' ]))?$_POST[ 'adresse2' ]: '';
$cp = (isset($_POST[ 'cp' ]))?$_POST[ 'cp' ]: '';
$ville = (isset($_POST[ 'ville' ]))?$_POST[ 'ville' ]: '';
$telephone = (isset($_POST[ 'telephone' ]))?$_POST[ 'telephone' ]: '';
$titre_adresse = (isset($_POST[ 'titre_adresse' ]))?$_POST[ 'titre_adresse' ]: getLang('ADDRESS_TITLE_DEFAULT');
$code_entree = (isset($_POST[ 'code_entree' ]))?$_POST[ 'code_entree' ]: '';
$interphone = (isset($_POST[ 'interphone' ]))?$_POST[ 'interphone' ]: '';
$service = (isset($_POST[ 'service' ]))?$_POST[ 'service' ]: '';
$escalier = (isset($_POST[ 'escalier' ]))?$_POST[ 'escalier' ]: '';
$etage = (isset($_POST[ 'etage' ]))?$_POST[ 'etage' ]: '';
$numero_appartement = (isset($_POST[ 'numero_appartement' ]))?$_POST[ 'numero_appartement' ]: '';
$remarque = (isset($_POST[ 'remarque' ]))?$_POST[ 'remarque' ]: '';


// Valider l'adresse
$validator = new Validator();
$validator->validateGeneral($prenom_adresse,getLang('NO_FIRST_NAME_ADDRESS_GIVEN'));
$validator->validateGeneral($nom_adresse,getLang('NO_LAST_NAME_ADDRESS_GIVEN'));
$validator->validateGeneral($adresse1,getLang('NO_ADDRESS_GIVEN'));
$validator->validateMultiplePostCode($cp,getLang('POSTCODE_LENGTH'));
$validator->validateGeneral($ville,getLang('NO_CITY_GIVEN'));
$validator->validateGeneral($telephone,getLang('NO_TELEPHONE_GIVEN'));
$validator->validateGeneral($titre_adresse,getLang('NO_ADDRESS_TITLE_GIVEN'));

if(!$validator->foundErrors()){
	$connector = new DbConnector();
	$query = "INSERT INTO adresses (id_client, societe, prenom, nom, adresse1, adresse2, cp, ville, telephone, code_entree, interphone, service, escalier, etage, numero_appartement, remarque, titre_adresse, active, defaut) VALUES (".
	$_SESSION['id_client'].", ".
	"'".isql($societe)."', ".
	"'".isql($prenom_adresse)."', ".
	"'".isql($nom_adresse)."', ".
	"'".isql($adresse1)."', ".
	"'".isql($adresse2)."', ".
	"'".isql($cp)."', ".
	"'".isql(getCloserCityName($ville))."', ".
	"'".isql($telephone)."', ".
	"'".isql($code_entree)."', ".
	"'".isql($interphone)."', ".
	"'".isql($service)."', ".
	"'".isql($escalier)."', ".
	"'".isql($etage)."', ".
	"'".isql($numero_appartement)."', ".
	"'".isql($remarque)."', ".
	"'".isql($titre_adresse)."', ".
	"1, ".
	"0".
	")";
	
	if ($connector->query($query)){
		$reponse['statut'] = "1";
		$reponse['msg'] = '<p class="bg-success">'.getLang('ADDRESS_ADDED').'</p>';
	}
	else{
		$reponse['statut'] = "0";
		$reponse['msg'] = '<p class="bg-danger">'.getLang('DB_ERROR').'</p>';
	}
}
else
{
	$reponse['statut'] = "0";
	$reponse['msg'] = '<p class="bg-danger">'.$validator->listErrors('<br>').'</p>';
}

header('Content-Type: application/json');
echo json_encode($reponse);

?>