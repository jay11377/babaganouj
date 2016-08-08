<?php
if(isset($_GET['id'])){
	$id=$_GET['id'];
	$id_client=$_GET['id_client'];
	$connector = new DbConnector();
	$query = "UPDATE adresses SET defaut=0 WHERE id_client=".$id_client. " AND id!=".$id;
	if ($connector->query($query)){
			$query = "UPDATE adresses SET defaut=1 WHERE id=".$id;
			if ($connector->query($query)){
				$_SESSION['pagemsg']='<div class="msg msg-info"><p>'.getLang('ADDRESS_UPDATED').'</p></div>';
				header( 'Location: moduleinterface.php?module=clients&action=edit&id='.$id_client ) ;
			}
			else{
				echo '<div class="msgbox">';
				echo '<div class="msg msg-error"><p>'.getLang('DB_ERROR_2').'</p></div>';
				echo '</div>';
			}
	}
	else{
		echo '<div class="msgbox">';
		echo '<div class="msg msg-error"><p>'.getLang('DB_ERROR_2').'</p></div>';
		echo '</div>';
	}
}
?>
