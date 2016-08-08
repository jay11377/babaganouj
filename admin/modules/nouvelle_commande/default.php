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
    	<h3><?php showLang('NEW_ORDER') ?></h3>
    </div>
    <div class="container"><?php
		
		$connector = new DbConnector();
		
		// Check whether a form has been submitted. If so, carry on
		if(isset($_POST['submit']) && $_POST['submit']==getLang('SUBMIT')){
			header( 'Location: moduleinterface.php?module=commander&action=default' ) ;
		}
		
		// If Cancel has been clicked
		else if (isset($_POST['cancel']) && $_POST['cancel']==getLang('CANCEL')){
			header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
		}
		
		// Display the form
        require_once("templates/form.php"); ?>
    </div>
</div>
