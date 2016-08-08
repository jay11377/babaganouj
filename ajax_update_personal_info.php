<?php
include("includes/top_includes.php");
require_once('admin/includes/Validator.php');
$conn = new DbConnector();

$id_client = (isset($_POST[ 'id_client' ]))?$_POST[ 'id_client' ]: ''; 
$prenom = (isset($_POST[ 'prenom' ]))?$_POST[ 'prenom' ]: '';
$nom = (isset($_POST[ 'nom' ]))?$_POST[ 'nom' ]: '';
$email = (isset($_POST[ 'email' ]))?$_POST[ 'email' ]: '';
$current_password = (isset($_POST[ 'current_password' ]))?$_POST[ 'current_password' ]: '';
$new_password = (isset($_POST[ 'new_password' ]))?$_POST[ 'new_password' ]: '';
$new_password_confirmation = (isset($_POST[ 'new_password_confirmation' ]))?$_POST[ 'new_password_confirmation' ]: '';
$newsletter = (isset($_POST[ 'newsletter' ]))?1:0;

// Get current password
$query = "SELECT password, email FROM clients WHERE id=".$_SESSION['id_client'];
$result = $conn->query($query);
$row = $conn->fetchArray($result);
$client_password = $row['password'];
$client_email = $row['email'];

// Valider les données perso
$validator = new Validator();
$validator->validateGeneral($prenom,getLang('NO_FIRST_NAME_GIVEN'));
$validator->validateGeneral($nom,getLang('NO_LAST_NAME_GIVEN'));
if($email!=$client_email)
	$validator->validateEmailAccount($email,getLang('EMAIL_INCORRECT'));
if($validator->validatePassword($current_password,getLang('NO_CURRENT_PASSWORD_GIVEN')))
	$validator->compare($client_password, setPassword($current_password), getLang('CURRENT_PASSWORD_INCORRECT'));
if($new_password!='')
{
	if($validator->validatePassword($new_password,getLang('NO_NEW_PASSWORD_GIVEN')))
		$validator->compare($new_password,$new_password_confirmation,getLang('PASSWORDS_DONT_MATCH'));
}

if(!$validator->foundErrors()){
		$connector = new DbConnector();
		$query = "UPDATE clients 
					 SET prenom='".isql($prenom)."',
						 nom='".isql($nom)."',
						 email='".isql($email)."',";
		if($new_password!='')
			$query.="password='".setPassword($new_password)."',";
		$query.="newsletter=".$newsletter." 
				 WHERE id =". $id_client;
		
		if ($connector->query($query)){
			$reponse['statut'] = "1";
			$reponse['msg'] = '<p class="bg-success">'.getLang('PERSONAL_INFO_UPDATED').'</p>';
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