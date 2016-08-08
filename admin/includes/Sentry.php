<?php
////////////////////////////////////////////////////////////////////////////////////////
// Class: sentry
// Purpose: Control access to pages
///////////////////////////////////////////////////////////////////////////////////////
class Sentry {
	
	var $loggedin = false;	//	Boolean to store whether the user is logged in
	var $userdata;			//  Array to contain user's data
	var $errors; 			// A variable to store a list of error messages
	
	function Sentry(){
		session_start();
		//header("Cache-control: private"); 
	}
	
	//======================================================================================
	// Log out, destroy session
	function logout(){
		//global $cookie_name;
		//global $domain;
		setcookie ($cookie_name, '', time() - 3600,'/',$domain);
		unset($this->userdata);
		session_destroy();
		
		return true;
	}

	//======================================================================================
	// Log in, and either redirect to goodRedirect or badRedirect depending on success
	function checkLogin($username = '',$password = '', $hash=false, $autologin = '', $goodRedirect = ''){

		global $langvars;
		// Include database and validation classes, and create objects
		require_once('DbConnector.php');
		require_once('Validator.php');
		$validate = new Validator();
		$loginConnector = new DbConnector();
		
		// If user is already logged in then check credentials
		if (isset($_SESSION['username']) && isset($_SESSION['password'])){
			// Validate session data
			if (!$validate->validateTextOnly($_SESSION['username']))
			{
				$this->errors[] = $langvars['USER_PASSWORD_INCORRECT'];
				return false;
			}
			if (!$validate->validateTextOnly($_SESSION['password']))
			{
				$this->errors[] = $langvars['USER_PASSWORD_INCORRECT'];
				return false;
			}
			
			$query="SELECT * 
					 FROM users U,groups G 
					WHERE U.group_id = G.group_id
					  AND username = '".$_SESSION['username']."' 
					  AND password = '".$_SESSION['password']."' 
					  AND U.active = 1
					  AND G.active = 1";
			$getUser = $loginConnector->query($query);

			if ($loginConnector->getNumRows($getUser) > 0){
				// Existing user ok, continue
				if ($goodRedirect != '') { 
					header("Location: ".$goodRedirect."?".strip_tags(session_id())) ;
				}			
				return true;
			}else{
				// Existing user not ok, logout
				$this->logout();
				return false;
			}
			
		// User isn't logged in, check credentials
		}else{
			// Validate input
			if (!$validate->validateTextOnly($username))
			{
				$this->errors[] = $langvars['USER_PASSWORD_INCORRECT'];
				return false;
			}
			if (!$validate->validateTextOnly($password))
			{
				$this->errors[] = $langvars['USER_PASSWORD_INCORRECT'];
				return false;
			}
												
			// Look up user in DB
			if($hash==false)
				$password=setPassword($password);
			$query="SELECT * 
					 FROM users U,groups G 
					WHERE U.group_id=G.group_id
					  AND username = '".$username."' 
					  AND password = '".$password."' 
					  AND U.active = 1
					  AND G.active = 1";
			$getUser = $loginConnector->query($query);
			$this->userdata = $loginConnector->fetchArray($getUser);
			
			if ($loginConnector->getNumRows($getUser) > 0){
				// Login OK, store session details
				// Log in
				$_SESSION['user_id'] = $this->userdata['user_id'];
				$_SESSION['username'] = $username;
				$_SESSION['name'] = ($this->userdata['first_name']!='')?$this->userdata['first_name'].' '.$this->userdata['last_name']:$username; // get the first name or the username if the first name is not defined
				$_SESSION['password'] = $this->userdata['password'];
				$_SESSION['group_id'] = $this->userdata['group_id'];
				
				// Remember me
				if($autologin == 1){
					global $cookie_name;
					global $cookie_time;
					global $domain;
					setcookie ($cookie_name, 'usr='.$username.'&hash='.$password, time() + $cookie_time,'/',$domain);
				}
				
				if (isset($goodRedirect)) { 
					header("Location: ".$goodRedirect."?".strip_tags(session_id())) ;
				}
				return true;

			}else{
				// Login BAD
				$this->errors[] = $langvars['USER_PASSWORD_INCORRECT'];
				unset($this->userdata);
				return false;
			}
		}			
	}
	
	// Check rights for the file we're trying to access to and show error msg
	function checkRights($permission){
		global $langvars;
		require_once('DbConnector.php');
		$rightsConnector = new DbConnector();
		$query = "SELECT * 
				   FROM permissions P, group_perms GP 
				  WHERE group_id=".$_SESSION["group_id"]."
					AND P.permission_id=GP.permission_id
					AND permission_name='".$permission."'";
		$getRights = $rightsConnector->query($query);
		if ($rightsConnector->getNumRows($getRights)==0){
			$this->showErrorAndDie($langvars['NO_ACCESS_TO'].' '.$permission);
		}
	}
	
	// Check right and return true or false
	function checkRights2($permission){
		require_once('DbConnector.php');
		$rightsConnector = new DbConnector();
		$query = "SELECT * 
				   FROM permissions P, group_perms GP 
				  WHERE group_id=".$_SESSION["group_id"]."
					AND P.permission_id=GP.permission_id
					AND permission_name='".$permission."'";
		$getRights = $rightsConnector->query($query);
		if ($rightsConnector->getNumRows($getRights)==0){
			return false;
		}
		else
			return true;
	}
	
	// Check that the user is an admin and display an error if not
	function checkAdmin($permission){
		global $langvars;
		if (!(isset($_SESSION['group_id']) and $_SESSION['group_id']==1)){
			$this->showErrorAndDie($langvars['NO_ACCESS_TO'].' '.$permission);
		}	
	}
	
	function showErrorAndDie($msg){
		echo '<div class="pageerrorcontainer"><div class="pageoverflow"><ul class="pageerror"><li>'.$msg.'.</li></ul></div></div>';
		die();
	}	
	
	
	
	// Check whether any errors have been found (i.e. validation has returned false)
	// since the object was created
	function foundErrors() {
		if (count($this->errors) > 0){
			return true;
		}else{
			return false;
		}
	}
	// Return a string containing a list of errors found,
	// Seperated by a given deliminator
	function listErrors($delim = ' '){
		return implode($delim,$this->errors);
	}
	
	// Manually add something to the list of errors
	function addError($description){
		$this->errors[] = $description;
	}	
}	
?>