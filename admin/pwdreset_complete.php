<?php
// Get the config file
require_once('includes/config.php');
require_once("includes/Sentry.php");
require_once('includes/DbConnector.php');
require_once('includes/Validator.php');




if (isset($_POST['cancel'])){
	header("Location:login.php");
}

$sentry = new Sentry();
$validator = new Validator();
$connector = new DbConnector();
$code = isset($_POST['code'])?trim($_POST['code']):'';
$uid=isset($_POST['uid'])?$_POST['uid']:'';

if(isset($_POST['submit'])){
	$password=cleanValue($_POST['password']);
	$passwordagain=cleanValue($_POST['passwordagain']);
	// Validate the entries
	$validator->validatePassword($password,getLang('NO_PASSWORD_GIVEN'));
	$validator->validatePassword($passwordagain,getLang('NO_PASSWORD_CONFIRMATION_GIVEN'));
	$validator->compare($password,$passwordagain,getLang('NO_PASSWORD_MATCH'));
	if ( !$validator->foundErrors() ){
		// Update
		$query="UPDATE users SET password='".setPassword($password)."' WHERE user_id='".$uid."'";
		if ($result = $connector->query($query)){
			$_SESSION['pagemsg']='<div class="msg msg-ok"><p>'.getLang('PASSWORD_UPDATED').'</p></div>';
			header( 'Location: login.php' ) ;
		}else{
			$validator->addError(getLang('DB_ERROR'));
		}
	}
}

else if (isset($_POST['submitcode'])){
	$connector = new DbConnector();
	$query="SELECT * 
			 FROM users 
			WHERE user_id = ".(int)$uid;
	$getUser = $connector->query($query);
	$row = $connector-> fetchArray($getUser);
	if ($row['code']!=md5($code)){
		$_SESSION['pagemsg']='<div class="msg msg-error"><p>'.getLang('INVALID_CODE').'</p></div>';
		header("Location:pwdreset_confirm.php");	
	}
}
else{
	header("Location:login.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php showLang('RESET_PASSWORD') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/jquery.treeTable.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/main.css" media="screen" rel="stylesheet" type="text/css" />
<base href="<?php echo $admindir; ?>" />
</head>
<body>

<div id="login" class="box small">
    <div class="header">
    	<h3><?php showLang('RESET_PASSWORD') ?></h3>
    </div>
    <form class="inset" action="<?php $_SERVER['REQUEST_URI'] ?>" method="post">
        <input type="hidden" name="uid" value="<?php echo $uid ?>" />
		<?php
			if($validator->foundErrors()){
				echo'<div class="msg msg-error"><p>'.$validator->listErrors('<br>').'</p></div>';
			}
		?> 
        <p>
          <label for="name"><?php showLang('PASSWORD') ?></label>
          <input type="password" name="password" id="password" value="">
        </p>
        <p>
          <label for="name"><?php showLang('CONFIRM_PASSWORD') ?></label>
          <input type="password" name="passwordagain" id="passwordagain" value="">
        </p>
        <div class="buttons">
            <p>
                <input type="submit" name="submit" value="<?php showLang('SUBMIT') ?>" class="button"/>
                <input type="submit" name="cancel" value="<?php showLang('CANCEL') ?>" class="button" />
            </p>
        </div>
    </form>
</div>

</body>
</html>