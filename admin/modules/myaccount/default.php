<?php
// Get all the ionfo regarding the user
if(isset($_POST['id'])){
	$id=$_POST['id'];
}
else{
	$id=$_SESSION['user_id'];
}
// Create an instance of DbConnector
$connector = new DbConnector();

// Check whether a form has been submitted. If so, carry on
if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
	$username=cleanValue($_POST['username']);
	$password=cleanValue($_POST['password']);
	$passwordagain=cleanValue($_POST['passwordagain']);
	$firstname=cleanValue($_POST['firstname']);
	$lastname=cleanValue($_POST['lastname']);
	$email=cleanValue($_POST['email']);
	
	// Validate the entries
	$validator = new Validator();
	$validator->validateUserName($username,getLang('NO_USER_NAME_GIVEN'));
	if($password!='' or $passwordagain!=''){
		$validator->validatePassword($password,getLang('NO_PASSWORD_GIVEN'));
		$validator->validatePassword($passwordagain,getLang('NO_PASSWORD_CONFIRMATION_GIVEN'));
		$validator->compare($password,$passwordagain,getLang('NO_PASSWORD_MATCH'));
	}
	if($email!="")
		$validator->validateEmail($email,getLang('EMAIL_INCORRECT'));
	// Check whether the validator found any problems
	if ( $validator->foundErrors() ){
		 // Show the errors, with a line between each
		 echo '<div class="msgbox">
    				<div class="msg msg-error"><p>'.$validator->listErrors('<br>').'</p></div>
    		   </div>';
	}else{
		// Create an SQL query (MySQL version)
		$query = "UPDATE users 
					 SET username='".$username."',";
		 if($password!='')
			{	
			    $query.= "password='".setPassword($password)."',";
			}
			   $query.= "first_name='".$firstname."',
						 last_name='".$lastname."',
						 email='".$email."'
				   WHERE user_id=".$id;
		
		// Display the confirmation message
		if ($result = $connector->query($query)){
			 echo '<div class="msgbox">
				   		<div class="msg msg-ok"><p>'.getLang('PROFILE_UPDATED').'</p></div>
				   </div>';
		}else{	
			// It hasn't worked so stop. Better error handling code would be good here!
			 echo '<div class="msgbox">
    				<div class="msg msg-error"><p>'.getLang('DB_ERROR_2').'</p></div>
    		   </div>';
	
		}
	}
}

// If Cancel has been clicked
else if (isset($_POST['cancel']) && $_POST['cancel']==getLang('CANCEL')){
	header( 'Location: moduleinterface.php');
}

// Form with the user
else{
	$query = "SELECT * FROM users WHERE user_id=".$id;
	$result = $connector->query($query);
	$row = $connector->fetchArray($result);
	$username = $row['username'];
	$password = $row['password'];
	$firstname = $row['first_name'];
	$lastname = $row['last_name'];
	$email = $row['email'];
}

// Display the form
require_once("templates/formuser.php");
?>
