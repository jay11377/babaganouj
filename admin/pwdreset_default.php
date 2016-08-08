<?php
// Get the config file
require_once('includes/config.php');
require_once("includes/Sentry.php");

$sentry = new Sentry();
$email=isset($_POST['email'])?$_POST['email']:'';

if (isset($_POST['cancel'])){
	header("Location:login.php");
}
if (isset($_POST['submit'])){
	require_once('includes/DbConnector.php');
	$connector = new DbConnector();	
	$query="SELECT * 
			 FROM users 
			WHERE email = '".$email."'";
	$getUser = $connector->query($query);
	if ($connector->getNumRows($getUser) > 0){
		$code = genRandomString();
		$body = getLang('VERIFICATION_CODE').$code;
		if( sendMail( siteName, siteEmail, $email, getRawLang('RESET_PASSWORD') , $body, cHighPriority, cHtmlFormat) ) {
			$row = $connector->fetchArray($getUser);
			$user_id = $row['user_id'];
			$query="UPDATE users SET code='".md5($code)."' WHERE user_id='".(int)$user_id."'";
			$connector->query($query);
			header("Location:pwdreset_confirm.php?uid=".$user_id);	
		}
		else {
			$sentry->addError(getLang('ERROR_SENDING_EMAIL'));
		}
	}
	else{
		$sentry->addError(getLang('INVALID_EMAIL'));
	}
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
        <?php
			if($sentry->foundErrors()){
				echo'<div class="msg msg-error"><p>'.$sentry->listErrors('<br>').'</p></div>';
			}
		?>
        <p>
            <label><?php showLang('EMAIL_ADDRESS') ?></label>
            <input type="text" name="email" value="<?php echo $email ?>" /> 
        </p>
        <div class="buttons">
            <p>
                <input type="submit" name="submit" value="<?php showLang('SUBMIT') ?>" class="button" />
                <input type="submit" name="cancel" value="<?php showLang('CANCEL') ?>" class="button" />
            </p>
        </div>
    </form>
</div>

</body>
</html>