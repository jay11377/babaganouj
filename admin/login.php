<?php
// Get the config file
require_once('includes/config.php');
require_once("includes/Sentry.php");

$sentry = new Sentry();

if (isset($_POST['loginsubmit'])){
	$autologin = isset($_POST['autologin']) ? $_POST['autologin'] : '';
    $sentry->checkLogin($_POST['username'],$_POST['password'], false, $autologin,'moduleinterface.php');
}
else{
	include_once('includes/autologin.php');
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php showLang('LOGIN_TITLE') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/jquery.treeTable.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/main.css" media="screen" rel="stylesheet" type="text/css" />
<base href="<?php echo $admindir; ?>" />
</head>
<body>

<div id="login" class="box small">
    <div class="header">
    	<h3><?php showLang('LOGIN_TITLE') ?></h3>
    </div>
    <form class="inset" action="login.php" method="post">
        <?php
			if($sentry->foundErrors()){
				echo'<div class="msg msg-error"><p>'.$sentry->listErrors('<br>').'</p></div>';
			}
			if(isset($_SESSION['pagemsg']))
			{
				echo $_SESSION['pagemsg'];
				unset($_SESSION['pagemsg']);
			}
		?>
        
        <p>
            <label><?php showLang('USER_NAME') ?></label>
            <input type="text" name="username" value="" /> 
        </p>
        
        <p>
            <label><?php showLang('PASSWORD') ?></label>
            <input type="password" name="password" value=""/>
        </p>
        <p>
        	<label><?php showLang('REMEMBER_ME') ?></label>
        	<input type="checkbox" name="autologin" value="1">
        </p>
        <div class="buttons">
            <p>
                <input type="submit" name="loginsubmit" value="<?php showLang('LOGIN') ?>" class="button"/>
                <a class="help" href="pwdreset_default.php"><?php showLang('FORGOT_PASSWORD') ?></a>
            </p>
        </div>
    </form>
</div>

</body>
</html>