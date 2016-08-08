<?php
////////////////////////////////////////////////////////////////////////////////////////
// Class: User
// Purpose: Control access to Orders
///////////////////////////////////////////////////////////////////////////////////////
class User {
	var $loggedin = false;	//	Boolean to store whether the user is logged in
	var $userdata;			//  Array to contain user's data
	var $errors; 			// A variable to store a list of error messages 
	
	function User(){
		session_start();
		header("Cache-control: private"); 
	}
	
	function logout(){
		include("includes/functions.php");
		unset($this->userdata);
		//deconnecter();
		session_destroy();
		return true;
	}

	function checkLogin($email = '', $password = '', $hash=false, $goodRedirect = ''){
		global $langvars;
		require_once('admin/includes/DbConnector.php');
		require_once('admin/includes/Validator.php');
		include_once("admin/includes/functions.php");
		$validate = new Validator();
		$loginConnector = new DbConnector();
		// If user is already logged in then check credentials
		if (isset($_SESSION['email']) && isset($_SESSION['password'])){
			if (!$validate->validateEmail($_SESSION['username']))
			{
				$this->errors[] = $langvars['EMAIL_INCORRECT'];
				return false;
			}
			if (!$validate->validatePassword($_SESSION['password']))
			{
				$this->errors[] = $langvars['PASSWORD_INCORRECT'];
				return false;
			}
			
			if($hash==false)
				$password=setPassword($password);
			$query="SELECT * 
					 FROM clients 
					WHERE email = '".$_SESSION['email']."' 
					  AND password = '".$_SESSION['password']."'";
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
			if (!$validate->validateEmail($email))
			{
				$this->errors[] = $langvars['EMAIL_INCORRECT'];
				return false;
			}
			if (!$validate->validatePassword($password))
			{
				$this->errors[] = $langvars['PASSWORD_INCORRECT'];
				return false;
			}
												
			// Look up user in DB
			if($hash==false)
				$password=setPassword($password);
			$query="SELECT * 
					 FROM clients 
					WHERE email = '".$email."' 
					  AND password = '".$password."'";
			$getUser = $loginConnector->query($query);
			$this->userdata = $loginConnector->fetchArray($getUser);
			
			if ($loginConnector->getNumRows($getUser) > 0){
				// Login OK, store session details
				$_SESSION['id_client'] = $this->userdata['id'];
				$_SESSION['email'] = $this->userdata['email'];
				$_SESSION['nom'] = $this->userdata['prenom'].' '.$this->userdata['nom']; // get the first name or the username if the first name is not defined
				$_SESSION['password'] = $this->userdata['password'];
				
				$result_address = $loginConnector->query("SELECT ville, cp FROM adresses WHERE id_client=".$this->userdata['id']." AND active=1 AND defaut=1");
				$row_address = $loginConnector->fetchArray($result_address);
				if(isset($row_address['ville']))
				{
					if(!isset($_SESSION['deliveryCity'])){
						$_SESSION['deliveryCity'] = getCityName($row_address['cp']);
						$_SESSION['orderTime']= getOrderTime($row_address['cp']);
						$_SESSION['orderMin']= getOrderMin($row_address['cp']);
					}
				}
				
				if (isset($goodRedirect) && $goodRedirect!='') { 
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
	
	function checkLoginMobile($email = '', $password = '', $hash=false, $goodRedirect = ''){
		global $langvars;
		require_once('../admin/includes/DbConnector.php');
		require_once('../admin/includes/Validator.php');
		include_once("../admin/includes/functions.php");
		$validate = new Validator();
		$loginConnector = new DbConnector();
		// If user is already logged in then check credentials
		if (isset($_SESSION['email']) && isset($_SESSION['password'])){
			if (!$validate->validateEmail($_SESSION['username']))
			{
				$this->errors[] = $langvars['EMAIL_INCORRECT'];
				return false;
			}
			if (!$validate->validatePassword($_SESSION['password']))
			{
				$this->errors[] = $langvars['PASSWORD_INCORRECT'];
				return false;
			}
			
			if($hash==false)
				$password=setPassword($password);
			$query="SELECT * 
					 FROM clients 
					WHERE email = '".$_SESSION['email']."' 
					  AND password = '".$_SESSION['password']."'";
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
			if (!$validate->validateEmail($email))
			{
				$this->errors[] = $langvars['EMAIL_INCORRECT'];
				return false;
			}
			if (!$validate->validatePassword($password))
			{
				$this->errors[] = $langvars['PASSWORD_INCORRECT'];
				return false;
			}
												
			// Look up user in DB
			if($hash==false)
				$password=setPassword($password);
			$query="SELECT * 
					 FROM clients 
					WHERE email = '".$email."' 
					  AND password = '".$password."'";
			$getUser = $loginConnector->query($query);
			$this->userdata = $loginConnector->fetchArray($getUser);
			
			if ($loginConnector->getNumRows($getUser) > 0){
				// Login OK, store session details
				$_SESSION['id_client'] = $this->userdata['id'];
				$_SESSION['email'] = $this->userdata['email'];
				$_SESSION['nom'] = $this->userdata['prenom'].' '.$this->userdata['nom']; // get the first name or the username if the first name is not defined
				$_SESSION['password'] = $this->userdata['password'];
				
				$result_address = $loginConnector->query("SELECT ville, cp FROM adresses WHERE id_client=".$this->userdata['id']." AND active=1 AND defaut=1");
				$row_address = $loginConnector->fetchArray($result_address);
				if(isset($row_address['ville']))
				{
					if(!isset($_SESSION['deliveryCity'])){
						$_SESSION['deliveryCity'] = getCityName($row_address['cp']);
						$_SESSION['orderTime']= getOrderTime($row_address['cp']);
						$_SESSION['orderMin']= getOrderMin($row_address['cp']);
					}
				}
				
				if (isset($goodRedirect) && $goodRedirect!='') { 
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
}	
?>