<?php
$occurence=ucfirst($module);
if(isset($_GET['id'])){
	$id=$_GET['id'];
	$connector = new DbConnector();
	$query="DELETE FROM $module WHERE id=".(int)$id;
	if ($result = $connector->query($query)){
		$_SESSION['pagemsg']='<div class="msg msg-info"><p>'.getLang('VOUCHER_DELETED').'</p></div>';
		header( 'Location: moduleinterface.php?module='.$module.'&action=default' ) ;
	}else{	
		echo '<div class="msgbox">';
		echo '<div class="msg msg-error"><p>'.getLang('DB_ERROR_2').'</p></div>';
		echo '</div>';
	}
}
?>
