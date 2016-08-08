<?php
$occurence=ucfirst($module);
if(isset($_GET['id'])){
	$id=$_GET['id'];
	$id_option=$_GET['id_option'];
	$connector = new DbConnector();
	$query="DELETE FROM options_items WHERE id=".$id;
	if ($result = $connector->query($query)){
		$_SESSION['pagemsg']='<div class="msg msg-info"><p>'.getLang('OPTION_DISH_DELETED').'</p></div>';
		header( 'Location: moduleinterface.php?module='.$module.'&action=default_option_items&id_option='.$id_option.'' ) ;
	}else{	
		echo '<div class="msgbox">';
		echo '<div class="msg msg-error"><p>'.getLang('DB_ERROR_2').'</p></div>';
		echo '</div>';
	}
}
?>
