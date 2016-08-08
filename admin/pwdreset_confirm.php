<?php
// Get the config file
require_once('includes/config.php');
require_once("includes/Sentry.php");

$sentry = new Sentry();
$uid=$_GET['uid'];

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
    <form class="inset" action="pwdreset_complete.php" method="post">
        <input type="hidden" name="uid" value="<?php echo $uid ?>" />
         <?php
			if(isset($_SESSION['pagemsg']))
			{
				echo $_SESSION['pagemsg'];
				unset($_SESSION['pagemsg']);
			}
		?>
        <p><?php showLang('VERIFICATION_CODE_SENT') ?></p>
        <p>
            <label><?php showLang('CODE') ?></label>
            <input type="text" name="code" value="" /> 
        </p>
        <div class="buttons">
            <p>
                <input type="submit" name="submitcode" value="<?php showLang('SUBMIT') ?>" class="button"/>
                <input type="submit" name="cancel" value="<?php showLang('CANCEL') ?>" class="button" />
            </p>
        </div>
    </form>
</div>

</body>
</html>