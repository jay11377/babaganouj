<?php
// Display a message
if(isset($_SESSION['pagemsg']))
{
	echo '<div class="msgbox">';
	echo $_SESSION['pagemsg'];
	echo '</div>';
	unset($_SESSION['pagemsg']);
}
?>
<div class="msgbox" id="msgbox" style="display:none"></div>
<div class="box">
	<div class="header">
    	<h3><?php showLang('EDIT_CUSTOMER') ?></h3>
    </div>
    <div class="container"><?php
		if(isset($_GET['id'])){
			$id=$_GET['id'];
		}
		else if(isset($_POST['id'])){
			$id=$_POST['id'];
		}
		
		$connector = new DbConnector();
		$query="SELECT * FROM $module WHERE id=".$id;
		$result=$connector->query($query);
		$row=$connector->fetchArray($result);
		
		$prenom = (isset($_POST[ 'prenom' ]))?$_POST[ 'prenom' ]: $row['prenom'];
		$nom = (isset($_POST[ 'nom' ]))?$_POST[ 'nom' ]: $row['nom'];
		$email = '';
		$password = '';
		
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			// Validate the entries
			$validator = new Validator();
			$validator->validateGeneral($prenom,getLang('NO_FIRST_NAME_GIVEN'));
			$validator->validateGeneral($nom,getLang('NO_LAST_NAME_GIVEN'));
			/*
			$validator->validateEmailAccount($email,getLang('EMAIL_INCORRECT'));
			if($validator->validatePassword($password,getLang('NO_PASSWORD_GIVEN')));
				$validator->compare($password,$password_confirmation,getLang('PASSWORDS_DONT_MATCH'));
			*/
			

			// Check whether the validator found any problems
			if ( $validator->foundErrors() ){
				 // Show the errors, with a line between each ?>
                 <script>
					 $('#msgbox').html('<div class="msg msg-error"><p><?php echo $validator->listErrors('<br>'); ?></p></div>');
					 $('#msgbox').show();
                 </script><?php
			}else{
				// Update
				$query = "UPDATE $module 
						     SET nom='".isql($nom)."',
							 prenom ='".isql($prenom)."'";
				$query .= "WHERE id =".intval($id);
				
				if ($result = $connector->query($query)){
					$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('CLIENT_UPDATED').'</p></div>';
					header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
				}else{ ?>
					<script>
                         $('#msgbox').html('<div class="msg msg-error"><p><?php showLang('DB_ERROR')?></p></div>');
                         $('#msgbox').show();
                    </script><?php
				}
			}
		}
		
		// If Cancel has been clicked
		else if (isset($_POST['cancel']) && $_POST['cancel']==getLang('CANCEL')){
			header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
		}
		
		// Display the form
        require_once("templates/form_edit.php"); ?>
    </div>
</div>
