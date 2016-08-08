<?php
if(isset($_GET['id'])){
	$id=$_GET['id'];
	$id_client=$_GET['id_client'];
	$connector = new DbConnector();
	//$query="DELETE FROM $module WHERE id=".$id;
	$query = "UPDATE adresses SET active=0 WHERE id=".(int)$id;
	if ($result = $connector->query($query)){
		$_SESSION['pagemsg']='<div class="msg msg-info"><p>'.getLang('ADDRESS_DELETED').'</p></div>';
		header( 'Location: moduleinterface.php?module=clients&action=edit&id='.$id_client ) ;
	}else{	
		echo '<div class="msgbox">';
		echo '<div class="msg msg-error"><p>'.getLang('DB_ERROR_2').'</p></div>';
		echo '</div>';
	}
}
?>
